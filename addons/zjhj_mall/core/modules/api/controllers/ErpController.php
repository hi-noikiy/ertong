<?php


namespace app\modules\api\controllers;


use app\hejiang\BaseApiResponse;
use app\models\ErpPlatform;

class ErpController extends Controller
{
    public function actionTest()
    {
        $form = new ErpPlatform();
        $order = \Yii::$app->request->post('data');

        $data = $form->insertOrder($order);
        return new BaseApiResponse($data);
    }
}