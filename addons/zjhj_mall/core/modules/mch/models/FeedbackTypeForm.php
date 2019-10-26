<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 15:25
 */

namespace app\modules\mch\models;

use app\models\FeedbackType;

/**
 * @property \app\models\Card $card;
 */
class FeedbackTypeForm extends MchModel
{
    public $store_id;
    public $type_name;
    public $status;

    public function attributeLabels()
    {
        return [
            'type_name'=>'意见反馈类型',
            'status'=>'',
        ];
    }

    public function findOne()
    {
        
    }
}
