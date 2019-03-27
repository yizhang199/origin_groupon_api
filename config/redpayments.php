<?php
return [
    'key' => env('REDPAYMENTS_KEY', ''),
    "version" => env("REDPAYMENTS_VERSION", ""),
    "createUrl" => env("RED_PAYMENTS_CREATE_URL", "https://service.redpayments.com.au/pay/gateway/create-order"),
    "queryUrl" => env("RED_PAYMENTS_QUERY_URL"),
    "test_createUrl" => "https://dev-service.redpayments.com.au/pay/gateway/create-order",

    "mchNo" => env("RED_PAYMENTS_mchNo", "77902"),
    "storeNo" => env("RED_PAYMENTS_storeNo", "77911"),
    "payWay" => env("RED_PAYMENTS_payWay", "BUYER_SCAN_TRX_QRCODE"),
    "params" => env("RED_PAYMENTS_params", '{"buyerId":285502587945850268}'),
    "items" => env("RED_PAYMENTS_items", "Food"),
];
