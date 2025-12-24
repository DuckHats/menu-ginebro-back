<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Services\RedsysService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private $redsysService;

    public function __construct(RedsysService $redsysService)
    {
        $this->redsysService = $redsysService;
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = $request->user();
        $amount = $request->amount;

        // Create unique order ID (numeric, max 12 chars usually for Redsys, but letters allowed in some versions. 
        // Redsys expects DS_MERCHANT_ORDER to be max 12 chars alphanumeric.
        // We'll use a timestamp + random component, ensuring it is 12 chars.
        // Or simple sequential/random if easier. Let's try 12 chars alphanumeric.
        $orderId = substr(time() . Str::upper(Str::random(4)), 0, 12);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'status' => 'pending',
            'order_id' => $orderId,
        ]);

        $paymentParams = $this->redsysService->getPaymentParameters($amount, $orderId, $user);

        return response()->json($paymentParams);
    }

    public function notify(Request $request)
    {
        // Redsys sends parameters in Ds_MerchantParameters (base64) and Ds_Signature
        $params = $request->input('Ds_MerchantParameters');
        $signature = $request->input('Ds_Signature');

        if (!$params || !$signature) {
            Log::error('Redsys Notification: Missing parameters');
            return response()->json(['status' => 'ko'], 400);
        }

        if (!$this->redsysService->checkSignature($params, $signature)) {
            Log::error('Redsys Notification: Invalid signature');
            return response()->json(['status' => 'ko'], 400);
        }

        $decoded = $this->redsysService->decodeParams($params);
        $orderId = $decoded['Ds_Order'];
        $responseCode = intval($decoded['Ds_Response']);

        Log::info("Redsys Notification for Order: {$orderId}, Response: {$responseCode}");

        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::error("Redsys Notification: Transaction not found for order {$orderId}");
            return response()->json(['status' => 'ko'], 404);
        }

        // 0000 to 0099 are authenticated authorizations (OK)
        // 0900 is authorized refund (OK for refund, but here we only do payment) - treat carefully if supporting refunds
        // We focus on payment: 0000-0099
        $isAuthorized = $responseCode >= 0 && $responseCode <= 99;

        if ($isAuthorized) {
            if ($transaction->status !== 'completed') {
                $transaction->update([
                    'status' => 'completed',
                    'response_code' => $responseCode,
                    'authorization_code' => $decoded['Ds_AuthorisationCode'] ?? null,
                ]);

                // Update User Balance
                $user = $transaction->user;
                $user->balance += $transaction->amount;
                $user->save();
            }
        } else {
            $transaction->update([
                'status' => 'failed',
                'response_code' => $responseCode,
                'authorization_code' => $decoded['Ds_AuthorisationCode'] ?? null,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
