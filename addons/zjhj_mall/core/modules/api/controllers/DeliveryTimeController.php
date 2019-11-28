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