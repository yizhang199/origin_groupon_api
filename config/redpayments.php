<?php
return [
    'key' => env('REDPAYMENTS_KEY', ''),
    "version" => env("REDPAYMENTS_VERSION", ""),
    "createUrl" => "https://service.redpayments.com.au/pay/gateway/create-order",
    "test_createUrl" => "https://dev-service.redpayments.com.au/pay/gateway/create-order",

];
