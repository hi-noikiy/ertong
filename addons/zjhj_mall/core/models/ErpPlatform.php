<?php


namespace app\models;


use app\utils\CurlHelper;
use phpDocumentor\Reflection\Types\Self_;

class ErpPlatform
{
    const USER_NAME = 'ERP01';
    const PASSWORD = 'ERP01';
    const SYSTEM = '0001';
    const URL = 'http://etkjyxgs.yyu8c.com:81';

    private $userName;
    private $password;
    private $system;

    private static $url = [
        'insert' => self::URL.'/u8cloud/api/so/saleorder/insert',
        'query' => self::URL.'/u8cloud/api/so/saleorder/query',
        'unapprove' => self::URL.'/u8cloud/api/so/saleorder/unapprove',
        'approve' => self::URL.'/u8cloud/api/so/saleorder/approve',

    ];
    public function __construct()
    {
        $this->userName = Self::USER_NAME;
        $this->password = md5(self::PASSWORD);
        $this->system  = Self::SYSTEM;
    }

    public function getCurl($url, $jsonStr, $authorizToken=null){
        if(isset($authorizToken)){
            $httpHeader=array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
                'system:'.$this->system,
                'usercode:'.$this->userName,
                'password:'.$this->password
            );
        }else{
            $httpHeader=array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
                'system:'.$this->system,
                'usercode:'.$this->userName,
                'password:'.$this->password
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

    /**
     * @param $orderNo
     * @return mixed
     * @desc 创建销售单
     */

    public function insertOrder($order){

        $url = self::$url['insert'];
        $result = $this->call($url, $order);
        return $result;

    }

    /**
     * @param $orderNo
     * @return mixed
     * @desc 销售订单取消审批
     */

    public function unapprove($order){

        $url = self::$url['unapprove'];
        $result = $this->call($url, $order);
        return $result;

    }

    /**
     * @param $orderNo
     * @return mixed
     * @desc 销售订单审批
     */

    public function approve($order){

        $url = self::$url['approve'];
        $result = $this->call($url, $order);
        return $result;

    }

}