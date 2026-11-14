<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // SMS -- selects which provider sends mobile OTPs. 'log' writes the SMS
    // to storage/logs/laravel.log so you can read it without spending credits
    // during dev. Set to 'fast2sms' for live sends.
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'log'),
    ],

    // Fast2SMS transactional SMS. Sign up at https://www.fast2sms.com,
    // fund the wallet, then copy the API key below.
    //
    // Routes:
    //   q    -> Quick Transactional (no DLT needed, pricier, ok for OTP)
    //   dlt  -> DLT-registered sender; set DLT message_id + sender_id
    'fast2sms' => [
        'api_key'    => env('FAST2SMS_API_KEY'),
        'route'      => env('FAST2SMS_ROUTE', 'q'),
        'sender_id'  => env('FAST2SMS_SENDER_ID'),
        'message_id' => env('FAST2SMS_MESSAGE_ID'),
    ],

    // Google reCAPTCHA -- optional bot shield on the public review form.
    // Leaving these blank keeps the form protected by the built-in
    // honeypot + math challenge only. To enable:
    //   1. go to https://www.google.com/recaptcha/admin/create
    //   2. pick "reCAPTCHA v2" + "I'm not a robot" checkbox
    //      (or "reCAPTCHA v3" if you prefer invisible scoring)
    //   3. add your domain(s): localhost, your.domain, etc.
    //   4. copy the Site key + Secret key into .env
    //   5. php artisan config:clear
    'recaptcha' => [
        'site_key'   => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'version'    => env('RECAPTCHA_VERSION', 'v2'),       // v2 | v3
        'min_score'  => (float) env('RECAPTCHA_MIN_SCORE', 0.5), // v3 only
    ],

];
