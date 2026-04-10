<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Mobile Payment Provider
    |--------------------------------------------------------------------------
    */
    'default_provider' => env('MOBILE_PAYMENT_DEFAULT_PROVIDER', 'paygate'),

    /*
    |--------------------------------------------------------------------------
    | Transaction Expiry (minutes)
    |--------------------------------------------------------------------------
    */
    'transaction_expiry_minutes' => env('MOBILE_PAYMENT_EXPIRY_MINUTES', 1),

    /*
    |--------------------------------------------------------------------------
    | Verification Settings
    |--------------------------------------------------------------------------
    */
    'verification' => [
        'max_attempts' => 3,
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
        'default_provider' => env('SMS_DEFAULT_PROVIDER', 'smsvas'),
        'sender_id' => env('SMS_SENDER_ID', 'SIGRECETTE'),

        'providers' => [
            'smsvas' => [
                'base_url' => env('SMSVAS_BASE_URL', 'https://smsvas.fr'),
                'token' => env('SMSVAS_TOKEN', ''),
                'from' => env('SMSVAS_FROM', 'SIGRECETTE'),
                'timeout' => env('SMSVAS_TIMEOUT', 15),
            ],

            'bestcom' => [
                'base_url' => env('BESTCOM_BASE_URL', 'https://bestcom.tg'),
                'api_key' => env('BESTCOM_API_KEY', ''),
                'api_secret' => env('BESTCOM_API_SECRET', ''),
                'titre' => env('BESTCOM_TITRE', 'SIGRECETTE'),
                'timeout' => env('BESTCOM_TIMEOUT', 15),
            ],

            'nalo' => [
                'base_url' => env('NALO_BASE_URL', 'https://sms.nalosolutions.com'),
                'api_key' => env('NALO_API_KEY', ''),
                'api_secret' => env('NALO_API_SECRET', ''),
                'sender_id' => env('NALO_SENDER_ID', 'SIGRECETTE'),
                'type' => env('NALO_SMS_TYPE', 0),
                'timeout' => env('NALO_TIMEOUT', 15),
            ],
        ],
    ],
];
