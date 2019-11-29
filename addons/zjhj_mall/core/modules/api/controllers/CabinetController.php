<?php


namespace app\modules\api\controllers;


use app\hejiang\BaseApiResponse;
use app\models\Cabinet;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\CabinetListForm;

class CabinetController extends Controller
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
        $form = new CabinetListForm();
        $form->locations = \Yii::$app->request->post('locations');
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
//        $list = Cabinet::find()->select('id,cabinet_id,cabinet_type,province,city,address')->where([
//            'store_id' => $this->store->id,
//            'is_delete' => 0,
//        ])->orderBy('addtime DESC')->asArray()->all();
//        foreach ($list as $i => $item) {
//            $list[$i]['cabinetList'] = $item['cabinet_id'].$item['cabinet_type'].$item['province'] . $item['city'].$item['address'];
//        }
        return new BaseApiResponse($form->getList());
    }

    public function actionCommonUseList(){
        $form = new CabinetListForm();
        $form->locations = \Yii::$app->request->post('locations');
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }
}