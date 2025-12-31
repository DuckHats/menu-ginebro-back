<?php

namespace App\Services;

use App\Models\Configuration;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    private string $key;
    private string $secret;
    private string $webhookSecret;

    public function __construct()
    {
        $this->loadConfiguration();
        Stripe::setApiKey($this->secret);
    }

    public function createCheckoutSession($user, float $amount, string $orderId): string
    {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Recàrrega de saldo',
                        'description' => "Order ID: {$orderId}",
                    ],
                    'unit_amount' => $this->amountToCents($amount),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->frontendResultUrl('ok'),
            'cancel_url' => $this->frontendResultUrl('ko'),
            'client_reference_id' => $orderId,
            'customer_email' => $user->email,
            'metadata' => [
                'order_id' => $orderId,
                'user_id' => $user->id,
            ],
        ]);

        return $session->url;
    }

    public function handleWebhook(string $payload, string $sigHeader): ?array
    {
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $this->webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook Error: Invalid payload');
            return null;
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe Webhook Error: Invalid signature');
            return null;
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            return [
                'order_id' => $session->client_reference_id,
                'amount' => $session->amount_total / 100, // Convert back to main unit
                'status' => 'completed',
                'payment_intent' => $session->payment_intent,
            ];
        }

        return null;
    }

    private function loadConfiguration(): void
    {
        $configs = Configuration::whereIn('key', [
            'stripe_key',
            'stripe_secret',
            'stripe_webhook_secret',
        ])->pluck('value', 'key');

        $this->key = $configs['stripe_key'] ?? '';
        $this->secret = $configs['stripe_secret'] ?? '';
        $this->webhookSecret = $configs['stripe_webhook_secret'] ?? '';
    }

    private function amountToCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function frontendResultUrl(string $status): string
    {
        return config('services.frontend.url') . "/payment/result?status={$status}";
    }
}
