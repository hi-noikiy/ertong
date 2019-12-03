<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/7
 * Time: 12:59
 */

namespace app\modules\mch\models;

use app\models\Cabinet;
use Hejiang\Event\EventArgument;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
class CabinetForm extends MchModel
{
    public $cabinet;
    public $id;
    public $store_id;
    public $cabinet_id;
    public $cabinet_type;
    public $wherehouse_id;
    public $province;
    public $city;
    public $address;
    public $addtime;
    public $is_delete;
    public $keyword;
    public $latitude;
    public $longitude;
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['store_id', 'cabinet_id', 'cabinet_type', 'wherehouse_id', 'province', 'city', 'address'], 'required'],
            [['store_id', 'cabinet_type', 'wherehouse_id', 'id'], 'integer'],
            [['cabinet_id', 'province', 'city', 'address'], 'string'],
            
        ];
    }

    public function attributeLabels()
    {
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'cabinet_id' => '自提柜ID',
            'cabinet_type' => '自提柜类型',
            'wherehouse_id' => '柜子所属仓库ID',
            'province' => '省',
            'city' => '市',
            'latitude' => '纬度',
            'longitude' => '经度',
            'address' => '详细地址',
        ];
    }

    /**
     * 编辑
     * @return array
     */
    public function save()
    {
       if ($this->validate()) {
            $preg = "/^\d{1,16}$/";
            if (!$this->cabinet_id || $this->cabinet_id=='') {
                return [
                    'code' => 1,
                    'msg' => '请填写柜子ID',
                ];
            }

            if (!preg_match($preg,$this->cabinet_id)) {
                return [
                    'code' => 1,
                    'msg' => '请正确填写柜子ID',
                ];
            }
            if (!$this->cabinet_type || $this->cabinet_type==0) {
                return [
                    'code' => 1,
                    'msg' => '请选择类型',
                ];
            }

            if (!$this->wherehouse_id || $this->wherehouse_id==0) {
                return [
                    'code' => 1,
                    'msg' => '请选择仓库分类',
                ];
            }
            if (!$this->province || $this->province=='' || $this->province=='请选择') {
                return [
                    'code' => 1,
                    'msg' => '请选择投放的省份',
                ];
            }
            if (!$this->city || $this->city=='' || $this->city=='请选择') {
                return [
                    'code' => 1,
                    'msg' => '请选择投放的市/区',
                ];
            }
            if (!$this->address || $this->address=='') {
                return [
                    'code' => 1,
                    'msg' => '请填写详细地址',
                ];
            }
            $cabinet = $this->cabinet;
            
            if (!$this->id || $this->id=='') {
                $result=Cabinet::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'cabinet_id'=>$this->cabinet_id])->one();
                if($result){
                    return [
                        'code' => 1,
                        'msg' => '柜子ID已存在',
                    ];
                }
            }
            $latitude=0;
            $longitude=0;
            //添加柜子经纬度  开始
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
                $machineId=$this->cabinet_id;
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
                if(isset($location_result)){
                    if($location_result['code']==0){
                        $latitude=$location_result['data']['latitude'];
                        $longitude=$location_result['data']['longitude'];
                    }
                }
                
            }
            //添加柜子经纬度  结束
            $cabinet->store_id = $this->store_id;
            $cabinet->cabinet_id = $this->cabinet_id;//自提柜ID
            $cabinet->cabinet_type = $this->cabinet_type;//自提柜类型
            $cabinet->wherehouse_id = $this->wherehouse_id;//柜子所属仓库ID
            $cabinet->province = $this->province;//省
            $cabinet->city = $this->city;//市
            $cabinet->address = $this->address;//详细地址
            $cabinet->latitude = $latitude;
            $cabinet->longitude = $longitude;
            $cabinet->addtime = time();
            if ($cabinet->save()) {
                    return [
                        'code' => 0,
                        'msg' => '保存成功',
                    ];
            } else {
                return $this->getErrorResponse($cabinet);
            }
        } else {
            return $this->errorResponse;
        } 
    }
    /**
     * 查找
     * @return array
     */
    public function GetList()
    {
        $type=array('1'=>'常温柜','2'=>'冷藏柜','3'=>'冷冻柜');
        $keyword = $this->keyword;
        $cabinet_type = $this->cabinet_type;
        $query = Cabinet::find()->where(['store_id' => $this->store->id, 'is_delete' => 0]);

        if ($cabinet_type != null) {
            $query->andWhere('cabinet_type=:cabinet_type', [':cabinet_type' => $cabinet_type]);
        }
        
        if (trim($keyword)) {
            $query->andWhere(['LIKE', 'cabinet_id', $keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'route' => \Yii::$app->requestedRoute]);
        $list = $query->orderBy('addtime DESC')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        $goodsList = ArrayHelper::toArray($list);
        foreach ($goodsList as $key => $val) {
            $goodsList[$key]['cabinet_type']=$type[$val['cabinet_type']];
            $goodsList[$key]['put_in_time']=date('Y-m-d H:i:s',$val['addtime']);
        }
        return [
            'list' => $list,
            'goodsList' => $goodsList,
            'pagination' => $pagination
        ];
    }
    /**
     * 删除
     * @return array
     */
    public function delete()
    {
        
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
