<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 9:50
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\ActionLog;
use app\models\Model;
use app\models\Official;
use app\modules\api\models\OfficialForm;
use app\modules\api\behaviors\LoginBehavior;
use app\models\Cabinet;
class OfficialController extends Controller
{
    function __construct(){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods:POST,GET");
        header("Access-Control-Allow-Headers:x-requested-with,content-type");
        header("Content-type:text/json;charset=utf-8");
    }
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }
    //新闻动态
    public function actionOfficialList()
    {
        $result=Official::find()->where(['is_show'=>1,'is_delete'=>0])->orderBy('official_sort ASC,addtime DESC')->asArray()->all();
        if(empty($result)){
            return new BaseApiResponse((object)[
                'code' => 1,
                'msg' => '未找到新闻数据',
                
            ]);
        }else{
            foreach ($result as $key => $val) {
                $result[$key]['add_time']=date('Y-m-d H:i:s',$val['addtime']);
            }
            return new BaseApiResponse([
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $result,
                ],               
            ]);
        }
        
    }
    //单个新闻动态
    public function actionOfficialDetail()
    {
        $attributes=\Yii::$app->request->post();
        $id=isset($attributes['id'])?$attributes['id']:'';
        if($id===''){
            return new BaseApiResponse((object)[
                'code' => 2,
                'msg' => '请求与参数异常',
                
            ]);
        }
        $result=Official::find()->where(['is_show'=>1,'is_delete'=>0,'id'=>$id])->asArray()->one();
        if(empty($result)){
            return new BaseApiResponse((object)[
                'code' => 1,
                'msg' => '未找到新闻数据',
                
            ]);
        }else{
            $result['add_time']=date('Y-m-d H:i:s',$result['addtime']);
            return new BaseApiResponse([
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $result,
                ],               
            ]);
        }
        
    }
    //柜子坐标
    public function actionCabinetList()
    {
        
        
        $attributes=\Yii::$app->request->post();
        $province=isset($attributes['province'])?$attributes['province']:'';
        $city=isset($attributes['city'])?$attributes['city']:'';
        $address=isset($attributes['address'])?$attributes['address']:'';
        
        $query=Cabinet::find()->where([
            'province'=>$province,
                'city'=>$city,
            ]);
        if($address!==''){
            $query->andWhere(['LIKE', 'address', $address]);
        }
        $list=$query->asArray()->all();
        
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
            foreach ($list as $key => $val) {
                $machineId=$val['cabinet_id'];
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
                $location_result[]=json_decode($this->getCurl($locationUrl,json_encode($location_array),$authorizToken),true);

            }
            if(!empty($location_result)){
                foreach ($location_result as $key => $val) {
                    if($val['code']==0){
                        $cabinet_list[]=$val['data'];
                    }else{
                        unset($location_result[$key]);
                    }
                }
                if(!empty($cabinet_list)){
                    return new BaseApiResponse([
                        'code' => 0,
                        'msg' => 'success',
                        'data' => [
                            'list' => $cabinet_list,
                        ],               
                    ]);
                }else{
                    return new BaseApiResponse((object)[
                        'code' => 1,
                        'msg' => '当前地区没有柜子',
                        
                    ]);
                }
            }else{
                return new BaseApiResponse((object)[
                    'code' => 1,
                    'msg' => '当前地区没有柜子',
                    
                ]);
            }
            
        }

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
}
