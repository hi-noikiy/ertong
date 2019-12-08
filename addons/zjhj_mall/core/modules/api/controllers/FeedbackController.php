<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/27
 * Time: 1:06
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\modules\api\behaviors\LoginBehavior;
use app\models\Feedback;
use app\models\FeedbackType;
use app\modules\api\models\FeedbackForm;
use app\modules\api\models\FeedbackTypeForm;

class FeedbackController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }
    //意见反馈类型
    public function actionFeedbackTypeList()
    {
        $form = new FeedbackTypeForm();
        return new BaseApiResponse($form->search());
    }
    //提交意见反馈
    public function actionSubmit()
    {
        $form = new FeedbackForm();
        $model = \Yii::$app->request->post();
        
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }
    
}
