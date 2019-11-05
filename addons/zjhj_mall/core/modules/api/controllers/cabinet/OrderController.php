<?php


namespace app\modules\api\controllers\cabinet;


use app\hejiang\BaseApiResponse;
use app\modules\api\controllers\Controller;
use app\modules\api\models\order\OrderSelfMentionForm;
use app\modules\api\models\order\OrderSelfMentioningForm;

class OrderController extends Controller
{
    //
    public function actionWaitingDelivery(){
        $form = new OrderSelfMentioningForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->save());
    }

    public function actionDeliverySuccess(){
        $form = new OrderSelfMentionForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        return new BaseApiResponse($form->save());
    }
}