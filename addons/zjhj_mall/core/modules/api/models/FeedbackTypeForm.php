<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 15:25
 */

namespace app\modules\api\models;

use app\models\FeedbackType;

/**
 * @property \app\models\Card $card;
 */
class FeedbackTypeForm extends ApiModel
{

    public function attributeLabels()
    {
        return [
            'type_name'=>'意见反馈类型',
            'status'=>'',
        ];
    }

    public function search()
    {
        $feedbacktype_list=FeedbackType::find()->where(['status'=>1])->asArray()->all();
        return [
            'code'=>0,
            'msg'=> '',
            'data'=> [
                'list'=>$feedbacktype_list,
            ]
        ];
    }
}
