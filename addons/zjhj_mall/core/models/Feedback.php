<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $information
 * @property string $pic_url
 * @property string $content
 * @property integer $type_id
 * @property integer $status
 * @property integer $addtime
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id','user_id', 'type_id', 'status', 'addtime'], 'integer'],
            [['pic_list', 'content'], 'string'],
            [['information'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => '用户ID',
            'information' => '用户账号',
            'pic_list' => '图片',
            'content' => '用户留言',
            'type_id' => '反馈类型id',
            'status' => '留言状态',
            'addtime' => 'Addtime',
        ];
    }
    
}
