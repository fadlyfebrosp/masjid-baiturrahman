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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'collectapi' => [
        'key' => env('COLLECTAPI_KEY'),
    ],

    'tinymce' => [
        'api_key' => env('TINYMCE_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration (AMAN)
    |--------------------------------------------------------------------------
    | Semua kunci hanya diambil dari .env, tidak boleh diubah dari UI.
    | Tidak menyimpan server key / client key di database atau file lain.
    |
    */

    'midtrans' => [
        'mode' => env('MIDTRANS_MODE', 'sandbox'),

        'sandbox' => [
            'client'   => env('MIDTRANS_SANDBOX_CLIENT'),
            'server'   => env('MIDTRANS_SANDBOX_SERVER'),
            'merchant' => env('MIDTRANS_SANDBOX_MERCHANT'),
        ],

        'production' => [
            'client'   => env('MIDTRANS_PRODUCTION_CLIENT'),
            'server'   => env('MIDTRANS_PRODUCTION_SERVER'),
            'merchant' => env('MIDTRANS_PRODUCTION_MERCHANT'),
        ],
    ],

];
