<?php
namespace App\Classes;

class Paypal
{
    public function create($request)
    {
        $url = config("paypal.createUrl");
        $requestBody = [
            "intent" => "sale",
            "redirect_urls" => [
                "return_url" => isset($request->returnUrl) ? $request->returnUrl : "",
                "cancel_url" => isset($request->cancel_url) ? $request->cancel_url : "",
            ],
            "payer" => [
                "payment_method" => "paypal",
            ],
            "transactions" => [[
                "amount" => [
                    "total" => $request->amount,
                    "currency" => isset($request->currency) ? $request->currency : "AUD",
                ],
            ]]];
        $TOKEN = self::getToken();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer " . $TOKEN));

        $curl_response = curl_exec($ch);
        // Check the return value of curl_exec(), too
        if ($curl_response === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }
        return json_decode($curl_response);

    }

    public function getToken()
    {

        $username = config("paypal.client_id");
        $password = config("paypal.PAYPAL_SECRET");

        $request_body = ['grant_type' => 'client_credentials'];

        $data_string = json_encode($request_body);

        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', "Authorization: Basic " . base64_encode($username . ":" . $password)));

        $curl_response = curl_exec($ch);

        // Check the return value of curl_exec(), too
        if ($curl_response === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $responseBody = json_decode($curl_response);
        return $responseBody->access_token;
    }
}
