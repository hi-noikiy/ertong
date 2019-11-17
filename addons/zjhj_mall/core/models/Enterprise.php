<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%enterprise}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $parent_id
 * @property string $enterprise_name
 * @property string $enterprise_license
 * @property integer $create_time
 * @property integer $status
 */
class Enterprise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%enterprise}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'create_time'], 'required'],
            [['store_id', 'user_id', 'create_time', 'status'], 'integer'],
            [['enterprise_name', 'enterprise_license'], 'string'],
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
            'user_id' => 'user_id',
            'enterprise_name' => 'enterprise_name',
            'enterprise_license' => 'enterprise_license',
            'create_time' => 'create_time',
            'status' => 'status',
        ];
    }
}
