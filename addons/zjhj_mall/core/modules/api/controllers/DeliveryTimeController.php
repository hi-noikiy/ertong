<?php


namespace app\modules\api\controllers;


use app\hejiang\BaseApiResponse;
use app\models\DeliveryTime;
use app\modules\api\behaviors\LoginBehavior;

class DeliveryTimeController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }
    public function actionList(){
        $time=date("H:i:s",time());

        $list = DeliveryTime::find()->where(['<=', 'start_time', $time])->andWhere(['>=', 'end_time', $time])->asArray()->all();
        
        foreach ($list as $key => $item) {
            $list[$key]['start_time'] = substr($item['start_time'], 0,5);
            $list[$key]['end_time'] = substr($item['end_time'], 0,5);
            // $list[$key]['service_time_app']=json_decode($item['service_time'],true);
        }
        $weekarray=array("日","一","二","三","四","五","六");
        foreach ($list as $key => $item) {
            // foreach ($item['service_time_app']['list'] as $k => $val) {
            //     $list[$key][['service_time_app']['list']][$k]['next_day']=date("Y-m-d",strtotime("+".$val['day']." day"));
            // }
            $date_time=json_decode($item['service_time'],true);
            
            foreach ($date_time[0]['list'] as $k => $val) {
                $month=date("m",strtotime("+".$val['day']." day"));
                $day=date("d",strtotime("+".$val['day']." day"));
                $date_time[0]['list'][$k]['next_day']=$month."月".$day."日";
                $date_time[0]['list'][$k]['next_day_date']=date('Y-m-d',strtotime("+".$val['day']." day"));
                
                $date_time[0]['list'][$k]['week']="周".$weekarray[date("w",strtotime(date("Y-m-d",strtotime("+".$val['day']." day"))))];

                $start_time=date("H",strtotime($val['start_time']));

                if ($start_time>0&&$start_time<=6){
                    $date_time[0]['list'][$k]['start_time']="凌晨".$val['start_time'];
                }
                if ($start_time>6&&$start_time<12){
                    $date_time[0]['list'][$k]['start_time']="上午".$val['start_time'];
                }
                if ($start_time>=12&&$start_time<=18){
                    $date_time[0]['list'][$k]['start_time']="下午".$val['start_time'];
                }
                if ($start_time>18&&$start_time<=24){
                    $date_time[0]['list'][$k]['start_time']="晚上".$val['start_time'];
                }
                $date_time[0]['list'][$k]['start_time_date']=$val['start_time'];
                $end_time=date("H",strtotime($val['end_time']));
                
                if ($end_time>0&&$end_time<=6){
                    $date_time[0]['list'][$k]['end_time']="凌晨".$val['end_time'];
                }
                if ($end_time>6&&$end_time<12){
                    $date_time[0]['list'][$k]['end_time']="上午".$val['end_time'];
                }
                if ($end_time>=12&&$end_time<=18){
                    $date_time[0]['list'][$k]['end_time']="下午".$val['end_time'];
                }
                if ($end_time>18&&$end_time<=24){
                    $date_time[0]['list'][$k]['end_time']="晚上".$val['end_time'];
                }
                $date_time[0]['list'][$k]['end_time_date']=$val['end_time'];
            }
            $list[$key]['service_time_app']=$date_time[0];
        }
        
        return new BaseApiResponse((object)[
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list,
            ],
        ]);
    }
}