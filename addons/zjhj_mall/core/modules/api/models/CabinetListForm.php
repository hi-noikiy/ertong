<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 10:57
 */

namespace app\modules\api\models;

use app\models\Cabinet;
use app\models\UserCard;
use yii\data\Pagination;

class CabinetListForm extends ApiModel
{
    public $store_id;
    public $user_id;

    public $page;
    public $limit;
    public $status;
    public $user_card_id;

    public function rules()
    {
        return [
//            [['user_card_id'], 'integer'],
//            [['page'],'default','value'=>1],
//            [['limit'],'default','value'=>10],
//            [['status'],'default','value'=>1]
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
        $userId = $this->user_id;
        $tableName = Cabinet::tableName();

        $results = Cabinet::findBySql("select count(cabinet_id) as num, cabinet_id from {$tableName} where user_id = {$userId} and is_delete = 0 GROUP BY `cabinet_id` order by num desc limit 3 ");
        $details = [];
        foreach ($results as $k => $result){
            $cabinet_id = $result['cabinet_id'];
            $cabinet = Cabinet::findOne(['id' => $cabinet_id]);
            $details[$k]['id'] = $cabinet_id;
            $details[$k]['cabinet_id'] = $cabinet->cabinet_id;
            $details[$k]['cabinet_type'] = $cabinet->cabinet_type;
            $details[$k]['province'] = $cabinet->province;
            $details[$k]['city'] = $cabinet->city;
            $details[$k]['address'] = $cabinet->address;
            $details[$k]['location'] = $this->getLocation($cabinet->cabinet_id);

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
}
