<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'frontend' => [
        'url' => env('FRONTEND_URL', 'http://localhost:4200'),
    ],

    'redsys' => [
        'url' => env('REDSYS_URL', 'https://sis-t.redsys.es:25443/sis/realizarPago'),
        'merchant_code' => env('REDSYS_MERCHANT_CODE', '999008881'),
        'terminal' => env('REDSYS_TERMINAL', '1'),
        'key' => env('REDSYS_KEY', 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'),
    ],
];
