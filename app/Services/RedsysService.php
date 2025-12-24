<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
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

        $this->merchantUrl = $configs['redsys_url'];
        $this->merchantCode = $configs['redsys_merchant_code'];
        $this->terminal = $configs['redsys_terminal'];
        $this->key = $configs['redsys_key'];
    }

    public function getPaymentParameters($amount, $orderId, $user)
    {
        // Amount in cents
        $amountCents = intval(round($amount * 100));

        // Frontend URL for callbacks
        $frontendUrl = config('services.frontend.url');
        $callbackUrl = route('api.payment.notify');

        // Redsys Redirect parameters are Case-Sensitive and use specific CamelCase
        $parameters = [
            'Ds_Merchant_Amount' => strval($amountCents),
            'Ds_Merchant_Order' => strval($orderId),
            'Ds_Merchant_MerchantCode' => strval($this->merchantCode),
            'Ds_Merchant_Currency' => strval($this->currency),
            'Ds_Merchant_TransactionType' => strval($this->transactionType),
            'Ds_Merchant_Terminal' => strval($this->terminal),
            'Ds_Merchant_MerchantURL' => strval($callbackUrl),
            'Ds_Merchant_UrlOK' => strval("{$frontendUrl}/payment/result?status=ok"),
            'Ds_Merchant_UrlKO' => strval("{$frontendUrl}/payment/result?status=ko"),
            'Ds_Merchant_ProductDescription' => 'Carrega de saldo: ' . $user->email,
            'Ds_Merchant_Titular' => substr($user->name . ' ' . $user->last_name, 0, 60),
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

        // 2. Encrypt OrderId with TripleDES (DES-EDE3-CBC)
        // Redsys implementation requires padding to multiple of 8 if it's not (12 is not).
        // Standard practice is padding with null bytes to 16 bytes for 12 chars order.
        $l = ceil(strlen($orderId) / 8) * 8;
        $orderIdPadded = $orderId . str_repeat("\0", $l - strlen($orderId));

        $iv = "\0\0\0\0\0\0\0\0";
        $key3des = openssl_encrypt($orderIdPadded, 'des-ede3-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);

        // 3. HMAC SHA256 of params with the derived key
        $res = hash_hmac('sha256', $params, $key3des, true);

        return base64_encode($res);
    }

    public function checkSignature($params, $signatureRecieved)
    {
        $decodedParams = $this->decodeParams($params);
        $orderId = $decodedParams['Ds_Order'] ?? $decodedParams['Ds_Merchant_Order'] ?? null;

        if (!$orderId) {
            Log::warning('Redsys checkSignature: OrderId not found in params');
            return false;
        }

        $calcSignature = $this->generateSignature($params, $orderId);

        // Standard comparison
        if ($calcSignature === $signatureRecieved) return true;

        // URL-safe comparison
        $signatureRecievedNormal = str_replace(['-', '_'], ['+', '/'], $signatureRecieved);
        if ($calcSignature === $signatureRecievedNormal) return true;

        $calcSignatureUrlSafe = str_replace(['+', '/'], ['-', '_'], $calcSignature);
        if ($calcSignatureUrlSafe === $signatureRecieved) return true;

        Log::error("Redsys Signature Mismatch: Recieved: {$signatureRecieved}, Calculated: {$calcSignature}");
        return false;
    }

    public function decodeParams($params)
    {
        $normalized = str_replace(['-', '_'], ['+', '/'], $params);
        $decoded = base64_decode($normalized);
        return json_decode($decoded, true);
    }
}
