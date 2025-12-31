<?php

namespace App\Http\Controllers;

use App\Jobs\PaymentEndActions;
use App\Models\Configuration;
use App\Models\Transaction;
use App\Services\RedsysService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        private readonly RedsysService $redsysService,
        private readonly StripeService $stripeService
    ) {}

    /* -----------------------------------------------------------------
     |  PUBLIC ENDPOINTS
     |------------------------------------------------------------------*/

    public function initiate(Request $request)
    {
        $this->validateInitiateRequest($request);

        $user   = $request->user();
        $amount = (float) $request->amount;
        $orderId = $this->generateOrderId();

        $transaction = $this->createTransaction($user->id, $amount, $orderId);

        $provider = Configuration::where('key', 'payment_provider')->value('value') ?? 'redsys';

        if ($provider === 'stripe') {
            try {
                $url = $this->stripeService->createCheckoutSession($user, $amount, $orderId);
                return response()->json([
                    'action' => 'redirect',
                    'url' => $url,
                ]);
            } catch (\Exception $e) {
                Log::error("Stripe Error: " . $e->getMessage());
                return response()->json(['error' => 'Payment initiation failed'], 500);
            }
        }

        // Redsys Default
        return response()->json(
            array_merge(
                ['action' => 'form_submit'],
                $this->redsysService->getPaymentParameters($amount, $orderId, $user)
            )
        );
    }

    public function notifyStripe(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $result = $this->stripeService->handleWebhook($payload, $sig_header);

        if (!$result) {
            return response()->json(['status' => 'ignored'], 400);
        }

        $orderId = $result['order_id'];
        $transaction = $this->findTransaction($orderId);

        if (!$transaction) {
            Log::error("Transaction not found for Stripe order: {$orderId}");
            return response()->json(['status' => 'not_found'], 404);
        }

        if ($result['status'] === 'completed') {
            $this->completeTransaction($transaction, ['stripe_intent' => $result['payment_intent']], 0); // 0 for success
        }

        return response()->json(['status' => 'success']);
    }

    public function notify(Request $request)
    {
        Log::info('Redsys Notification Received', $request->all());

        [$params, $signature] = $this->getRedsysNotificationParams($request);

        if (!$this->isValidSignature($params, $signature)) {
            return $this->koResponse('Invalid signature');
        }

        $decoded = $this->redsysService->decodeParams($params);

        $orderId       = $this->extractOrderId($decoded);
        $responseCode  = $this->extractResponseCode($decoded);

        if (!$orderId) {
            return $this->koResponse('OrderId not found', 400, $decoded);
        }

        $transaction = $this->findTransaction($orderId);

        if (!$transaction) {
            return $this->koResponse("Transaction not found for order {$orderId}", 404);
        }

        $this->processTransactionResult(
            $transaction,
            $decoded,
            $responseCode
        );

        return response()->json(['status' => 'ok']);
    }

    /* -----------------------------------------------------------------
     |  INITIATE HELPERS
     |------------------------------------------------------------------*/

    private function validateInitiateRequest(Request $request): void
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:150',
        ]);
    }

    private function generateOrderId(): string
    {
        // Max 12 chars alphanumeric (Redsys compatible)
        // Ensure Stripe also likes it (it does)
        return substr(time() . Str::upper(Str::random(4)), 0, 12);
    }

    private function createTransaction(int $userId, float $amount, string $orderId): Transaction
    {
        return Transaction::create([
            'user_id'     => $userId,
            'amount'      => $amount,
            'type'        => Transaction::TYPE_TOPUP,
            'description' => 'Recàrrega de saldo',
            'status'      => 'pending',
            'order_id'    => $orderId,
        ]);
    }

    /* -----------------------------------------------------------------
     |  NOTIFY HELPERS
     |------------------------------------------------------------------*/

    private function getRedsysNotificationParams(Request $request): array
    {
        $params    = $request->input('Ds_MerchantParameters');
        $signature = $request->input('Ds_Signature');

        if (!$params || !$signature) {
            Log::error('Redsys Notification: Missing parameters');
            abort(400, 'Missing Redsys parameters');
        }

        return [$params, $signature];
    }

    private function isValidSignature(string $params, string $signature): bool
    {
        if (!$this->redsysService->checkSignature($params, $signature)) {
            Log::error('Redsys Notification: Invalid signature');
            return false;
        }

        return true;
    }

    private function extractOrderId(array $decoded): ?string
    {
        return $decoded['Ds_Order']
            ?? $decoded['Ds_Merchant_Order']
            ?? null;
    }

    private function extractResponseCode(array $decoded): int
    {
        return isset($decoded['Ds_Response'])
            ? (int) $decoded['Ds_Response']
            : -1;
    }

    private function findTransaction(string $orderId): ?Transaction
    {
        return Transaction::where('order_id', $orderId)->first();
    }

    private function processTransactionResult(
        Transaction $transaction,
        array $decoded,
        int $responseCode
    ): void {
        Log::info("Redsys processed", [
            'order'    => $transaction->order_id,
            'response' => $responseCode
        ]);

        if ($this->isAuthorized($responseCode)) {
            $this->completeTransaction($transaction, $decoded, $responseCode);
        } else {
            $this->failTransaction($transaction, $decoded, $responseCode);
        }
    }

    private function isAuthorized(int $responseCode): bool
    {
        // 0000–0099 = OK
        return $responseCode >= 0 && $responseCode <= 99;
    }

    private function completeTransaction(
        Transaction $transaction,
        array $extraData,
        int $responseCode
    ): void {
        if ($transaction->status === 'completed') {
            return;
        }

        $transaction->update([
            'status'             => 'completed',
            'response_code'      => $responseCode,
            'authorization_code' => $extraData['Ds_AuthorisationCode'] ?? $extraData['stripe_intent'] ?? null,
        ]);

        $this->increaseUserBalance($transaction);

        PaymentEndActions::dispatch(
            $transaction->user,
            $transaction->amount
        );
    }

    private function failTransaction(
        Transaction $transaction,
        array $decoded,
        int $responseCode
    ): void {
        $transaction->update([
            'status'             => 'failed',
            'response_code'      => $responseCode,
            'authorization_code' => $decoded['Ds_AuthorisationCode'] ?? null,
        ]);
    }

    private function increaseUserBalance(Transaction $transaction): void
    {
        $user = $transaction->user;
        $user->balance += $transaction->amount;
        $user->save();
    }

    /* -----------------------------------------------------------------
     |  RESPONSES
     |------------------------------------------------------------------*/

    private function koResponse(
        string $message,
        int $status = 400,
        array $context = []
    ) {
        Log::error("Redsys Notification: {$message}", $context);

        return response()->json(['status' => 'ko'], $status);
    }
}
