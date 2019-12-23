<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 10:57
 */

namespace app\modules\api\models;

use app\models\Invoice;
use yii\data\Pagination;

class InvoiceForm extends ApiModel
{
    public $store_id;
    public $user_id;

    public $page;
    public $limit;
    public $status;
    public $corporate_name;
    public $total_sum;
    public $email;
    public $number;
    public $order_no;
    public $project_name;
    public $project_coding;
    public $type;
    public $fpDm;
    public $fpHm;
    public $addtime;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['store_id', 'user_id', 'corporate_name', 'total_sum','email','number','order_no','project_name','project_coding'], 'required'],
            // [['store_id', 'user_id', 'addtime','status','type'], 'integer'],
            // [['corporate_name', 'total_sum','email','number','order_no','project_name','project_coding','fpDm','fpHm'], 'string'],
        ];
    }
    
    public function detail()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $list = UserCard::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->user_card_id,
            'is_delete' => 0,
        ]);

        return [
            'code'=>0,
            'msg'=> '',
            'data'=> [
                'list'=>$list,
            ]
        ];
    }
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $fromLocation = explode(',', $this->locations);
        $userId = $this->user_id;
        $tableName = Order::tableName();

        $results = Order::findBySql("select count(cabinet_id) as num, cabinet_id, id from {$tableName} where user_id = {$userId} and is_delete = 0 GROUP BY `cabinet_id` order by num desc limit 3 ")->asArray()->all();
        if (count($results) <1){
            return [
                'code'=>0,
                'msg'=> '',
                'data'=> [
                    'list'=>[],
                ]
            ];
        }
        $details = [];
        foreach ($results as $k => $result){
            $cabinet_id = $result['cabinet_id'];
            $cabinet = Cabinet::findOne(['id' => $result['cabinet_id']]);
            $details[$k]['id'] = $cabinet->id;
            $details[$k]['cabinet_id'] = $cabinet->cabinet_id;
            $details[$k]['cabinet_type'] = $cabinet->cabinet_type;
            $details[$k]['province'] = $cabinet->province;
            $details[$k]['city'] = $cabinet->city;
            $details[$k]['address'] = $cabinet->address;
            //$latitude = $cabinet->
            $details[$k]['location'] = [
                $cabinet->longitude,
                $cabinet->latitude
            ];
            $details[$k]['distance'] = $this->get_distance($fromLocation, $details[$k]['location'], false);

        }
        return [
            'code'=>0,
            'msg'=> '',
            'data'=> [
                'list'=>$details,
            ]
        ];

    }

    public function getList(){
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $fromLocation = explode(',', $this->locations);
        $userId = $this->user_id;
        $list = Cabinet::find()->select('id,cabinet_id,cabinet_type,province,city,address,longitude,latitude')->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ])->orderBy('addtime DESC')->asArray()->all();
        foreach ($list as $i => $item) {
            $list[$i]['cabinetList'] = $item['cabinet_id'].$item['cabinet_type'].$item['province'] . $item['city'].$item['address'];
        }
        $details = [];
        foreach ($list as $k => $result){
            $cabinet_id = $result['cabinet_id'];
            $details[$k]['id'] = $result['id'];
            $details[$k]['cabinet_id'] = $result['cabinet_id'];
            $details[$k]['cabinet_type'] = $result['cabinet_type'];
            $details[$k]['province'] = $result['province'];
            $details[$k]['city'] = $result['city'];
            $details[$k]['address'] = $result['address'];
            //$latitude = $cabinet->
            $details[$k]['location'] = [
                $result['longitude'],
                $result['latitude']
            ];
            $details[$k]['distance'] = $this->get_distance($fromLocation, $details[$k]['location'], false);

        }
        return [
            'code'=>0,
            'msg'=> '',
            'data'=> [
                'list'=>$details,
            ]
        ];
    }

    public function sign($sign_array, $appScret){
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
        return md5($sign.$appScret);
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

    public function getLocation($machineId){
        $appId='19103111555648';
        $appScret='105ef16489204001b046ab742b4acb7c';
        $loginUrl="http://open.iwuyi.net/api/authorization/login";
        $array=array(
            'appId'=>$appId,
            'appScret'=>$appScret,
        );
        $result=$this->getCurl($loginUrl,json_encode($array));
        $result_array=json_decode($result,true);
        $authorizToken='';
        if($result_array['code']==0){
            $authorizToken=$result_array['data']['authorizToken'];
        }
        if(isset($authorizToken)){
            $locationUrl="http://open.iwuyi.net/api/machine/location";
            $location_result=array();
            //foreach ($list as $key => $val) {
                $timestamp=time();
                $sign_array=array(
                    'machineId'=>$machineId,
                    'timestamp'=>$timestamp,
                );
                $sign=$this->sign($sign_array,$appScret);
                $location_array=array(
                    'machineId'=>$machineId,
                    'sign'=>$sign,
                    'timestamp'=>$timestamp,
                );
                $location_result=json_decode($this->getCurl($locationUrl,json_encode($location_array),$authorizToken),true);

            //}
            return $location_result;
        }

    }

    public function getNearby($localcation){
        $list = Cabinet::find()->select('id,cabinet_id,cabinet_type,province,city,address')->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
        ])->orderBy('addtime DESC')->asArray()->all();
        //dia
    }

    /**
     * 根据起点坐标和终点坐标测距离
     * @param  [array]   $from  [起点坐标(经纬度),例如:array(118.012951,36.810024)]
     * @param  [array]   $to    [终点坐标(经纬度)]
     * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
     * @param  [int]     $decimal   精度 保留小数位数
     * @return [string]  距离数值
     */
    public function get_distance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}
