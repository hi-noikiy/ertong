<?php


namespace app\models;


use app\utils\CurlHelper;
use phpDocumentor\Reflection\Types\Self_;

class ErpPlatform
{
    const USER_NAME = 'ERP01';
    const PASSWORD = 'ERP01';

    private $userName;
    private $password;

    private static $url = [
        'insert' => 'u8cloud/api/so/saleorder/insert',
        'query' => 'u8cloud/api/so/saleorder/query',
        'unapprove' => 'u8cloud/api/so/saleorder/unapprove',

    ];
    public function __construct($userName, $password)
    {
        $this->userName = Self::USER_NAME;
        $this->password = md5(self::PASSWORD);
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