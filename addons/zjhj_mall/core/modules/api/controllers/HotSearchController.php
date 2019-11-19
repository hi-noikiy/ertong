<?php


namespace app\modules\api\controllers;


use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\HotSearchForm;

class HotSearchController extends Controller
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
        $form = new HotSearchForm();
        $form->store_id = $this->store->id;
        return $form->search();die;
        return new BaseApiResponse($form->search());
    }
}