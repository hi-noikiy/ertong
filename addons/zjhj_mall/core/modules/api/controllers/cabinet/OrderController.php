<?php


namespace app\modules\api\controllers\cabinet;


use app\hejiang\BaseApiResponse;
use app\modules\api\controllers\Controller;
use app\modules\api\models\cabinet\CabinetPlatForm;
use app\modules\api\models\order\OrderSelfMentionForm;
use app\modules\api\models\order\OrderSelfMentioningForm;

class OrderController extends Controller
{
    //
    public function actionUpdateDelivery(){
        $form = new OrderSelfMentioningForm();
        $form->orderNo = \Yii::$app->request->post('orderNo');
        $form->deliverNo = \Yii::$app->request->post('deliverNo');
        $form->machineId = \Yii::$app->request->post('machineId');
        $form->status = \Yii::$app->request->post('status');
        //$form->pickupCode = \Yii::$app->request->post('pickupCode');
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->save());
    }

    public function actionUpdateOrder(){
        $form = new OrderSelfMentionForm();
        $form->orderNo = \Yii::$app->request->post('orderNo');
        $form->machineId = \Yii::$app->request->post('machineId');
        $form->pickupCode = \Yii::$app->request->post('pickupCode');
        $form->status = \Yii::$app->request->post('status');
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->save());
    }

    public function actionDetail(){
        $orderNo = \Yii::$app->request->post('orderNo');
        $platform = new CabinetPlatForm(null);
        $re = $platform->detail($orderNo);
        if ($re['code'] == 0){
            $data = [
              'code' => 0,
              'msg' => 'success',
              'data' => $re['data']
            ];
        }else{
            $data = [
                'code' => 1,
                'msg' => $re['message'],
            ];
        }
        return new BaseApiResponse($data);
    }
}