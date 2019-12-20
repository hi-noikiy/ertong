<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 10:56
 */

namespace app\modules\mch\controllers;

use app\models\DeliveryTime;
use app\modules\mch\models\DeliveryTimeForm;
use Hejiang\Event\EventArgument;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
/**
 * Class CabinetController
 * @package app\modules\mch\controllers
 * 送达时间
 */
class DeliveryTimeController extends Controller
{

    /**
     * 自提柜管理
     * @return string
     */
    public function actionList()
    {
        
        $list=DeliveryTime::find()->where(['is_delete'=>1, 'store_id' => $this->store->id])->asArray()->all();
        
        foreach ($list as $key => $val) {
            $list[$key]['service_time']=json_decode($val['service_time'],true);
            $list[$key]['start_time'] = substr($val['start_time'], 0,5);
            $list[$key]['end_time'] = substr($val['end_time'], 0,5);
            $date_time=json_decode($val['service_time'],true);
            foreach ($date_time[0]['list'] as $k => $val) {
                $month=date("m",strtotime("+".$val['day']." day"));
                $day=date("d",strtotime("+".$val['day']." day"));
                $date_time[0]['list'][$k]['next_day']=$month."月".$day."日";
            }
            $list[$key]['service_time_app']=$date_time[0];

        }
        return $this->render('list', [
            'list' => $list,
        ]);
    }

    /**
     * 自提柜修改
     * @param int $id
     * @return string
     */
    public function actionDeliveryTimeEdit($id=0)
    {

        

        
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post();
            if($model['id']==''){
                $delivery_time = new DeliveryTimeForm();
                print_r($model);die;
                $delivery_time->model = $model;
                $delivery_time->store_id = $this->store->id;
                return $delivery_time->save();
            }
            
        }else{
            $delivery_time = DeliveryTime::findOne(['id' => $id, 'store_id' => $this->store->id]);
            $list = ArrayHelper::toArray($delivery_time);
            $start_h=substr($list['start_time'],0,2);
            $start_i=substr($list['start_time'],3,2);
            $end_h=substr($list['end_time'],0,2);
            $end_i=substr($list['end_time'],3,2);
            $date_time=json_decode($list['service_time'],true);
            $service_time=$date_time[0];
            
            $count=count($service_time['list']);
            
            $start_d=$service_time['list'][0]['day'];
            
            $end_d=$service_time['list'][$count-1]['day'];
            
            $service_shang=$service_time['list'][0]['start_time'];

            $service_xia=$service_time['list'][0]['end_time'];

            $start_h_arr=array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
            $start_i_arr=array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58","59");
            $start_d_arr=array("1"=>"后1天","2"=>"后2天","3"=>"后3天","4"=>"后4天","5"=>"后5天");
            $end_d_arr=array("2"=>"后2天","3"=>"后3天","4"=>"后4天","5"=>"后5天","6"=>"后6天");
            $service_shang_arr=array("0:00","0:30","1:00","1:30","2:00","2:30","3:00","3:30","4:00","4:30","5:00","5:30","6:00","6:30","7:00","7:30","8:00","8:30","9:00","9:30","10:00","10:30","11:00","11:30");
            $service_xia_arr=array("12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30");
            
            return $this->render('delivery-time-edit', [
                'id' => $list['id'],
                'start_h' => $start_h,
                'start_i' => $start_i,
                'end_h' => $end_h,
                'end_i' => $end_i,
                'start_d' => $start_d,
                'end_d' => $end_d,
                'service_shang' => $service_shang,
                'service_xia' => $service_xia,
                'start_h_arr' => $start_h_arr,
                'start_i_arr' => $start_i_arr,
                'start_d_arr' => $start_d_arr,
                'end_d_arr' => $end_d_arr,
                'service_shang_arr' => $service_shang_arr,
                'service_xia_arr' => $service_xia_arr,
            ]);

        }
        
        
    }

    /**
     * 删除（逻辑）
     * @param int $id
     */
    public function actionDeliveryTimeDel($id = 0)
    {
        $cabinet = DeliveryTime::findOne(['id' => $id, 'store_id' => $this->store->id,'is_delete'=>1]);
        if (!$cabinet) {
            return [
                'code' => 1,
                'msg' => '送达时间已删除',
            ];
        }
        $cabinet->is_delete = 0;
        if ($cabinet->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($cabinet->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

}