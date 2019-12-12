<?php


namespace app\modules\api\models\cabinet;


class CabinetPlatForm
{
    const APP_ID = '19103111555648';
    const APP_SCRET = '105ef16489204001b046ab742b4acb7c';
    const URI = 'http://open.iwuyi.net';
    private $mch_id;
    public function __construct($mchId)
    {
        $this->mch_id = $mchId;
    }

    private static $url = [
        'login' => self::URI.'/api/authorization/login',
        'queryEmptyCell' => self::URI.'/api/express/queryEmptyCell',
        'createOrder' => self::URI.'/api/express/createOrder',
        'cancelOrder' => self::URI.'/api/express/cancelOrder',
        'queryOrderDetail' => self::URI.'/api/express/queryOrderDetail',
        'list' => self::URI.'/api/machine/machineList',
        'orderDetail' => self::URI.'/api/express/queryOrderDetail',
        'location' => self::URI.'/api/machine/location',

    ];

    public function login(){
        $result=$this->getCurl(self::$url['login'],json_encode(['appId' => self::APP_ID, 'appScret' => self::APP_SCRET]));
        $result_array=json_decode($result,true);
        $authorizToken='';
        if($result_array['code']==0){
            $authorizToken=$result_array['data']['authorizToken'];
        }
        return $authorizToken;

    }

    public function sign($data){
        $timestamp=time();
        $sign_array=$data;
        $data['timestamp'] = $timestamp;
        $sign_str='';
        ksort($sign_array);
        foreach ($sign_array as $key => $val) {
            if(!isset($sign_array[$key])){
                unset($sign_array[$key]);
            }
            $sign_str.=$key."=".$val."&";
        }
        if(isset($sign_str)){
            $sign=substr($sign_str,0,strlen($sign_str)-1);
        }
        return md5($sign.self::APP_SCRET);
    }

    /**
     * @param $coolType
     * @param null $timestamp
     * @param null $sign
     * @return mixed
     * @desc 查询空闲格子数量
     */
    public function queryEmptyCell( $coolType, $timestamp = null, $sign = null){
        $url = self::$url['queryEmptyCell'];
        $data = [];
        $data['machineId'] = $this->mch_id;
        $data['coolType'] = $coolType;
        if (empty($timestamp)){
            $timestamp = time();
        }
        $data['sign'] = $this->sign($data);
        $data['timestamp'] = $timestamp;
        //var_dump($data, $this->login());die;

        $result = $this->call($url, json_encode($data), $this->login());
        return $result;
    }

    /**
     * @param $orderNo
     * @param $delivers
     * @return mixed
     * @desc 创建订单
     */
    public function createOrder($orderNo, $delivers, $total){
        $url = self::$url['createOrder'];
        $data = [];
        $data['machineId'] = $this->mch_id;
        $data['orderNo'] = $orderNo;
        $data['delivers'] = $delivers;
        $data['total'] = $total;
        $result = $this->call($url, json_encode($data), $this->login());
        return $result;

    }

    public function detail($orderNo){
        $url = self::$url['orderDetail'];
        $data = [];
        $data['orderNo'] = $orderNo;
        $data['sign'] = $this->sign($data);
        $result = $this->call($url, json_encode($data), $this->login());
        return $result;
    }

    /**
     * @param $orderNo
     * @return mixed
     * @desc 取消订单
     */
    public function cancelOrder($orderNo){
        $url = self::$url['cancelOrder'];
        $data = [];
        $data['orderNo'] = $orderNo;
        $data['sign'] = $this->sign($data);
        $result = $this->call($url, json_encode($data), $this->login());
        return $result;

    }

    public function getList(){
        $url = self::$url['list'];
        $result = $this->call($url,'', $this->login());
        return $result;
    }

    /**
     * @param $machineId
     * @return mixed
     * @desc 获取机器定位
     */
    public function getLocation(){
        $url = self::$url['location'];
        $data = [];
        $data['machineId'] = $this->mch_id;
        if (empty($timestamp)){
            $timestamp = time();
        }
        $data['timestamp'] = $timestamp;
        $data['sign'] = $this->sign($data);
        //var_dump($data, $this->login());die;

        $result = $this->call($url, json_encode($data), $this->login());
        return $result;
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
}