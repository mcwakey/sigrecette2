<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Mobile Payment Provider
    |--------------------------------------------------------------------------
    */
    'default_provider' => env('MOBILE_PAYMENT_DEFAULT_PROVIDER', 'qosic'),

    /*
    |--------------------------------------------------------------------------
    | Transaction Expiry (minutes)
    |--------------------------------------------------------------------------
    */
    'transaction_expiry_minutes' => env('MOBILE_PAYMENT_EXPIRY_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Verification Settings
    |--------------------------------------------------------------------------
    */
    'verification' => [
        'max_attempts' => 5,
        'backoff_seconds' => [10, 30, 60, 120, 300],
    ],

    /*
    |--------------------------------------------------------------------------
    | Provider Configurations
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'qosic' => [
            'base_url' => env('QOSIC_BASE_URL', ''),
            'username' => env('QOSIC_USERNAME', ''),
            'password' => env('QOSIC_PASSWORD', ''),
            'merchant_id' => env('QOSIC_MERCHANT_ID', ''),
            'timeout' => env('QOSIC_TIMEOUT', 30),
        ],

        'fedapay' => [
            'base_url' => env('FEDAPAY_BASE_URL', 'https://sandbox-api.fedapay.com'),
            'secret_key' => env('FEDAPAY_SECRET_KEY', ''),
            'public_key' => env('FEDAPAY_PUBLIC_KEY', ''),
            'timeout' => env('FEDAPAY_TIMEOUT', 30),
        ],

        'paygate' => [
            'base_url' => env('PAYGATE_BASE_URL', ''),
            'api_key' => env('PAYGATE_API_KEY', ''),
            'secret' => env('PAYGATE_SECRET', ''),
            'timeout' => env('PAYGATE_TIMEOUT', 30),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    */
    'sms' => [
        'enabled' => env('FEATURE_SMS_NOTIFICATIONS', false),
        'provider' => env('SMS_PROVIDER', ''),
        'api_key' => env('SMS_API_KEY', ''),
        'sender_id' => env('SMS_SENDER_ID', 'SIGRECETTE'),
    ],
];
