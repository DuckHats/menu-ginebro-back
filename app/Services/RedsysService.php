<?php

namespace App\Services;

use App\Models\Configuration;

class RedsysService
{
    private $merchantUrl;
    private $merchantCode;
    private $terminal;
    private $key;
    private $transactionType = '0'; // Autorización
    private $currency = '978'; // EUR

    public function __construct()
    {
        $configs = Configuration::whereIn('key', [
            'redsys_url',
            'redsys_merchant_code',
            'redsys_terminal',
            'redsys_key'
        ])->pluck('value', 'key');

        $this->merchantUrl = $configs['redsys_url'] ?? config('services.redsys.url');
        $this->merchantCode = $configs['redsys_merchant_code'] ?? config('services.redsys.merchant_code');
        $this->terminal = $configs['redsys_terminal'] ?? config('services.redsys.terminal');
        $this->key = $configs['redsys_key'] ?? config('services.redsys.key');
    }

    public function getPaymentParameters($amount, $orderId, $user)
    {
        // Amount in cents
        $amountCents = intval(round($amount * 100));

        // Frontend URL for callbacks
        $frontendUrl = config('services.frontend.url');
        $callbackUrl = route('api.payment.notify'); // Using route() helper requires Named Route

        $parameters = [
            'DS_MERCHANT_AMOUNT' => $amountCents,
            'DS_MERCHANT_ORDER' => strval($orderId),
            'DS_MERCHANT_MERCHANTCODE' => $this->merchantCode,
            'DS_MERCHANT_CURRENCY' => $this->currency,
            'DS_MERCHANT_TRANSACTIONTYPE' => $this->transactionType,
            'DS_MERCHANT_TERMINAL' => $this->terminal,
            'DS_MERCHANT_MERCHANTURL' => $callbackUrl,
            'DS_MERCHANT_URLOK' => "{$frontendUrl}/payment/result?status=ok",
            'DS_MERCHANT_URLKO' => "{$frontendUrl}/payment/result?status=ko",
            'DS_MERCHANT_PRODUCTDESCRIPTION' => 'Carrega de saldo: ' . $user->email,
            'DS_MERCHANT_TITULAR' => substr($user->name . ' ' . $user->last_name, 0, 60),
        ];

        $paramsBase64 = base64_encode(json_encode($parameters));
        $signature = $this->generateSignature($paramsBase64, $orderId);

        return [
            'url' => $this->merchantUrl,
            'version' => 'HMAC_SHA256_V1',
            'params' => $paramsBase64,
            'signature' => $signature,
        ];
    }

    private function generateSignature($params, $orderId)
    {
        // 1. Decode Key (Base64)
        $key = base64_decode($this->key);

        // 2. Encrypt OrderId with 3DES
        // Pad orderId with null bytes to 8 multiple length if needed? Redsys usually expects strict length or padding.
        // For standard 3DES via OpenSSL:
        $orderId = $orderId;

        // Redsys specific 3DES implementation via OpenSSL
        // IV is usually zero bytes
        $iv = implode(array_map("chr", array(0, 0, 0, 0, 0, 0, 0, 0)));

        $key3des = openssl_encrypt($orderId, 'DES-EDE3-CBC', $key, OPENSSL_RAW_DATA, $iv);

        // 3. HMAC SHA256 of params with the new 3DES key
        return base64_encode(hash_hmac('sha256', $params, $key3des, true));
    }

    public function checkSignature($params, $signatureRecieved)
    {
        // Decode params
        $decodedParams = json_decode(base64_decode($params), true);
        $orderId = $decodedParams['DS_ORDER'] ?? $decodedParams['DS_MERCHANT_ORDER'] ?? null;

        if (!$orderId) {
            return false;
        }

        $calcSignature = $this->generateSignature($params, $orderId);

        // Use a safe comparison
        return str_replace(['+', '/'], ['-', '_'], $calcSignature) === str_replace(['+', '/'], ['-', '_'], $signatureRecieved) || $calcSignature === $signatureRecieved;
    }

    public function decodeParams($params)
    {
        return json_decode(base64_decode($params), true);
    }
}
