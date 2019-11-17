<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%servicer}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $name
 * @property integer $mobile
 * @property integer $entry_time
 * @property integer $create_time
 * @property integer $is_delete
 */
class Servicer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%servicer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'mobile', 'name', 'entry_time'], 'required'],
            [['store_id', 'entry_time', 'create_time', 'is_delete'], 'integer'],
            [['name','mobile'], 'string'],
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
            'name' => '客服姓名',
            'mobile' => '客服手机号',
            'entry_time' => '入职时间',
            'create_time' => '创建时间',
            'is_delete' => 'Is Delete',
        ];
    }
}
