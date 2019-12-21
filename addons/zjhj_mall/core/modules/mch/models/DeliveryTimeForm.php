<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 15:51
 */

namespace app\modules\mch\models;

use app\models\DeliveryTime;
use yii\data\Pagination;

/**
 * @property \app\models\DeliveryTime $delivery;
 */
class DeliveryTimeForm extends MchModel
{
    // public $delivery;

    public $store_id;
    public $start_time;
    public $end_time;
    public $start_d;
    public $end_d;
    public $service_shang;
    public $service_xia;
    public $is_delete;
    public $service_time;
    public $model;
    
    
    public function save()
    {

        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $attributes = $this->model;

        $start_time=$attributes['start_time'];
        $end_time=$attributes['end_time'];
        $start_d=$attributes['start_d'];
        $end_d=$attributes['end_d'];
        $service_shang=$attributes['service_shang'];
        $service_xia=$attributes['service_xia'];
        $list=array();
        $key=0;
        for ($i=$start_d; $i <= $end_d; $i++) { 
            $list[$key]=array(
                    'day'=>$i,
                    'start_time'=>$service_shang,
                    'end_time'=>$service_xia,
                );
            $key++;
        }
        $service_time[0]['list']=$list;
        if($attributes['delivery_id']!=''){
            $DeliveryTime=DeliveryTime::findOne(['id'=>$attributes['delivery_id']]);
            $DeliveryTime->store_id=$this->store_id;
            $DeliveryTime->start_time=$start_time;
            $DeliveryTime->end_time=$end_time;
            $DeliveryTime->service_time=json_encode($service_time);
            if($DeliveryTime->save()){
                return [
                    'code'=>0,
                    'msg'=>'成功'
                ];
            }else{
                return [
                    'code'=>1,
                    'msg'=>'修改失败'
                ];
            }
        }
        $DeliveryTime=new DeliveryTime();
        $DeliveryTime->store_id=$this->store_id;
        $DeliveryTime->start_time=$start_time;
        $DeliveryTime->end_time=$end_time;
        $DeliveryTime->service_time=json_encode($service_time);
        $DeliveryTime->is_delete=1;
        if($DeliveryTime->save()){
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        }else{
            return [
                'code'=>1,
                'msg'=>'添加失败'
            ];
        }
        
    }
}
