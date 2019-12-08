<?php


namespace app\models;


use app\utils\CurlHelper;

class ErpPlatform
{
    private $userName;
    private $password;
    public function __construct($userName, $password)
    {
        $this->userName = $userName;
        $this->password = $password;
    }

    public function getCurl($url, $jsonStr, $authorizToken=null){
        if(isset($authorizToken)){
            $httpHeader=array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
                'authorizToken:'.$authorizToken,
            );
        }else{
            $httpHeader=array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        $response = curl_exec($ch);
        //$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }

    public function call($url, $jsonStr, $authorizToken=null){
        $result = $this->getCurl($url, $jsonStr, $authorizToken);
        $resultArr = json_decode($result, true);

        return $resultArr;
    }

    public function orderInsert($data){

    }

}