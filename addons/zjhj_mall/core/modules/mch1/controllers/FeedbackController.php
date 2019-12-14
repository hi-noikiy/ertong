<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25
 * Time: 9:25
 */

namespace app\modules\mch\controllers;

use app\models\Feedback;
use app\models\FeedbackType;
use app\modules\mch\models\FeedbackForm;
use app\modules\mch\models\FeedbackTypeForm;

class FeedbackController extends Controller
{
    /**
     * 意见反馈列表
     */
    public function actionIndex($keyword = null, $status = null, $type_id= null)
    {
        // var_dump($status);die;
        $form = new FeedbackForm();
        $form->store_id = $this->store->id;
        if(\Yii::$app->request->isGet){
            $form->keyword = $keyword;
            $form->status = $status;
            $form->type_id = $type_id;
        }
        $res = $form->getList();
        // print_r($res);die;
        return $this->render('index', [
            'list' => $res['list'],
            'feedbacktype_list' => $res['feedbacktype_list'],
            'pagination' => $res['pagination'],
        ]);
    }

    /**
     * 意见反馈详情
     */
    public function actionDetail($id =null)
    {
        $form = new FeedbackForm();
        $form->store_id = $this->store->id;
        if(\Yii::$app->request->isGet){
            $form->feedback_id = $id;
        }else{
            $form = new FeedbackForm();
            $form->store_id = $this->store->id;
            $form->feedback_id = \Yii::$app->request->post('id');
            $form->feedback_desc = \Yii::$app->request->post('feedback_desc');
            return $form->save();
        }
        $res = $form->getOneDetail();
        // print_r($res);die;
        return $this->render('detail', [
            'list' => $res['list'],
        ]);
    }
    /**
     * 修改状态
     */
    public function actionDetailSave($id = 0)
    {
        $feedback = Feedback::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$feedback) {
            return [
                'code' => 1,
                'msg' => '意见反馈不存在'
            ];
        }

        $feedback->status = 2;
        if ($feedback->save()) {
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '失败'
            ];
        }
    }
    
}
