<?php

return [
    // 'simulator' or 'ccavenue'. Phase 4 defaults to simulator; Phase 6 swaps
    // once the owner provisions CCAvenue test credentials.
    'provider' => env('PAYMENT_PROVIDER', 'simulator'),

    'simulator_secret' => env('PAYMENT_SIMULATOR_SECRET', 'dev-sim-secret-rotate-me'),

    'ccavenue' => [
        'merchant_id' => env('CCAVENUE_MERCHANT_ID', ''),
        'access_code' => env('CCAVENUE_ACCESS_CODE', ''),
        'working_key' => env('CCAVENUE_WORKING_KEY', ''),
        'action_url'  => env('CCAVENUE_ACTION_URL', 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction'),
    ],

    // Shipping is a flat default until the owner confirms their rule.
    'shipping_paise_flat' => 0,
];
