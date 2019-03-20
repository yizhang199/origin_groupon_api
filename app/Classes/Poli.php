<?php
namespace App\Classes;

class Poli
{

    public function create($request)
    {

        $url = config("poli.createUrl");
        $reference = config("poli.reference");
        $auth = base64_encode(config("poli.auth"));
        $homepage = config("app.homepage");
        $payment_returnUrl = config("app.returnUrl");

        $requestBody = [
            "Amount" => $request->total,
            "CurrencyCode" => isset($request->currency) ? $request->currency : "AUD",
            "MerchantReference" => $reference,
            "MerchantHomepageURL" => $homepage,
            "SuccessURL" => "$payment_returnUrl/poli",
            "FailureURL" => $homepage,
            "CancellationURL" => config('app.paymentCancelUrl'),
            "NotificationURL" => config('app.paymentNotifycationUrl'),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Basic " . $auth));

        $curl_response = curl_exec($ch);
// Check the return value of curl_exec(), too
        if ($curl_response === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }
        return json_decode($curl_response);
    }
}
