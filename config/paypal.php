<?php
return [
    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'secret' => env('PAYPAL_SECRET', ''),
    "createUrl" => "https://api.sandbox.paypal.com/v1/payments/payment",
    "tokenUrl" => 'https://api.sandbox.paypal.com/v1/oauth2/token',
    'settings' => array(
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR',
    ),
];
