<?php

namespace App\Services;

use App\Models\Configuration;
use Illuminate\Support\Facades\Log;

class RedsysService
{
    /* -----------------------------------------------------------------
     |  REDSYS CONSTANTS
     |------------------------------------------------------------------*/

    private const TRANSACTION_TYPE_AUTH = '0';
    private const CURRENCY_EUR = '978';
    private const SIGNATURE_VERSION = 'HMAC_SHA256_V1';

    /* -----------------------------------------------------------------
     |  CONFIGURATION
     |------------------------------------------------------------------*/

    private string $merchantUrl;
    private string $merchantCode;
    private string $terminal;
    private string $key;

    public function __construct()
    {
        $this->loadConfiguration();
    }

    /* -----------------------------------------------------------------
     |  PUBLIC API
     |------------------------------------------------------------------*/

    public function getPaymentParameters(float $amount, string $orderId, $user): array
    {
        $parameters = $this->buildMerchantParameters($amount, $orderId, $user);
        $paramsBase64 = $this->encodeParameters($parameters);

        return [
            'url'       => $this->merchantUrl,
            'version'   => self::SIGNATURE_VERSION,
            'params'    => $paramsBase64,
            'signature' => $this->generateSignature($paramsBase64, $orderId),
        ];
    }

    public function checkSignature(string $params, string $receivedSignature): bool
    {
        $decodedParams = $this->decodeParams($params);
        $orderId = $this->extractOrderId($decodedParams);

        if (!$orderId) {
            Log::warning('Redsys checkSignature: OrderId not found');
            return false;
        }

        return $this->signaturesMatch(
            $this->generateSignature($params, $orderId),
            $receivedSignature
        );
    }

    public function decodeParams(string $params): array
    {
        return json_decode(
            base64_decode($this->normalizeBase64($params)),
            true
        );
    }

    /* -----------------------------------------------------------------
     |  CONFIG LOADING
     |------------------------------------------------------------------*/

    private function loadConfiguration(): void
    {
        $configs = Configuration::whereIn('key', [
            'redsys_url',
            'redsys_merchant_code',
            'redsys_terminal',
            'redsys_key',
        ])->pluck('value', 'key');

        $this->merchantUrl  = $configs['redsys_url'];
        $this->merchantCode = $configs['redsys_merchant_code'];
        $this->terminal     = $configs['redsys_terminal'];
        $this->key          = $configs['redsys_key'];
    }

    /* -----------------------------------------------------------------
     |  PARAMETER BUILDING
     |------------------------------------------------------------------*/

    private function buildMerchantParameters(float $amount, string $orderId, $user): array
    {
        return [
            'Ds_Merchant_Amount'             => $this->amountToCents($amount),
            'Ds_Merchant_Order'              => $orderId,
            'Ds_Merchant_MerchantCode'       => $this->merchantCode,
            'Ds_Merchant_Currency'           => self::CURRENCY_EUR,
            'Ds_Merchant_TransactionType'    => self::TRANSACTION_TYPE_AUTH,
            'Ds_Merchant_Terminal'           => $this->terminal,
            'Ds_Merchant_MerchantURL'        => route('api.payment.notify'),
            'Ds_Merchant_UrlOK'              => $this->frontendResultUrl('ok'),
            'Ds_Merchant_UrlKO'              => $this->frontendResultUrl('ko'),
            'Ds_Merchant_ProductDescription' => "Carrega de saldo: {$user->email}",
            'Ds_Merchant_Titular'            => $this->buildTitularName($user),
        ];
    }

    private function frontendResultUrl(string $status): string
    {
        return config('services.frontend.url') . "/payment/result?status={$status}";
    }

    private function buildTitularName($user): string
    {
        return substr(
            trim("{$user->name} {$user->last_name}"),
            0,
            60
        );
    }

    private function amountToCents(float $amount): string
    {
        return (string) intval(round($amount * 100));
    }

    /* -----------------------------------------------------------------
     |  ENCODING & SIGNATURE
     |------------------------------------------------------------------*/

    private function encodeParameters(array $parameters): string
    {
        return base64_encode(json_encode($parameters));
    }

    private function generateSignature(string $paramsBase64, string $orderId): string
    {
        $derivedKey = $this->deriveKeyFromOrder($orderId);

        return base64_encode(
            hash_hmac('sha256', $paramsBase64, $derivedKey, true)
        );
    }

    private function deriveKeyFromOrder(string $orderId): string
    {
        $decodedKey = base64_decode($this->key);

        $paddedOrder = $this->padOrderId($orderId);

        return openssl_encrypt(
            $paddedOrder,
            'des-ede3-cbc',
            $decodedKey,
            OPENSSL_RAW_DATA | OPENSSL_NO_PADDING,
            $this->initializationVector()
        );
    }

    private function padOrderId(string $orderId): string
    {
        $blockSize = 8;
        $length = ceil(strlen($orderId) / $blockSize) * $blockSize;

        return $orderId . str_repeat("\0", $length - strlen($orderId));
    }

    private function initializationVector(): string
    {
        return str_repeat("\0", 8);
    }

    /* -----------------------------------------------------------------
     |  SIGNATURE COMPARISON
     |------------------------------------------------------------------*/

    private function signaturesMatch(string $calculated, string $received): bool
    {
        if ($calculated === $received) {
            return true;
        }

        $receivedNormalized = $this->normalizeBase64($received);
        if ($calculated === $receivedNormalized) {
            return true;
        }

        $calculatedUrlSafe = $this->urlSafeBase64($calculated);
        if ($calculatedUrlSafe === $received) {
            return true;
        }

        Log::error('Redsys Signature Mismatch', [
            'received'    => $received,
            'calculated'  => $calculated,
        ]);

        return false;
    }

    private function normalizeBase64(string $value): string
    {
        return str_replace(['-', '_'], ['+', '/'], $value);
    }

    private function urlSafeBase64(string $value): string
    {
        return str_replace(['+', '/'], ['-', '_'], $value);
    }

    /* -----------------------------------------------------------------
     |  HELPERS
     |------------------------------------------------------------------*/

    private function extractOrderId(array $decodedParams): ?string
    {
        return $decodedParams['Ds_Order']
            ?? $decodedParams['Ds_Merchant_Order']
            ?? null;
    }
}
