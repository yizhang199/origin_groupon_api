<?php
namespace App\Classes;

class Redpayments
{
    private $key = "";
    private $version = "";

    public function __construct()
    {
        $this->key = config("redpayments.key");
        $this->version = config("redpayments.version");
    }

    public function create($request)
    {
        // read input
        $requestBody = [
            "amount" => $request->amount,
            "channel" => $request->channel,
            "currency" => $request->currency,
            "item" => $request->item,
            "mchNo" => $request->mchNo,
            "mchOrderNo" => $request->mchOrderNo,
            "notifyUrl" => $request->notifyUrl,
            "params" => $request->params,
            "payWay" => $request->payWay,
            "quantity" => $request->quantity,
            "returnUrl" => $request->returnUrl,
            "storeNo" => $request->storeNo,
            "timestamp" => $request->timestamp,
            "version" => $this->version,
        ];
        // add sign
        $requestBody["sign"] = self::getSign($requestBody);

        // call web api
        $data_string = json_encode($requestBody);

        $url = config("redpayments.createUrl");

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

        $curl_response = curl_exec($curl);

// make reposnse object
        $responseBody = json_decode($curl_response);
// return reponse
        return $responseBody;
    }

    public function getSign($requestBody)
    {
        $str = '';
        ksort($params);
        foreach ($params as $k => $v) {
            $str .= "$k=$v&";
        }
        $str .= "key=$this->key";
        return md5($str);
    }

}
