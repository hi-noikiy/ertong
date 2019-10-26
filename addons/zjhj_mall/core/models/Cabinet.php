<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cabinet}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string  $cabinet_id
 * @property integer $cabinet_type
 * @property integer $province
 * @property integer $addtime
 * @property string  $is_delete
 * @property string  $city
 * @property string  $address
 */
class Cabinet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cabinet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'cabinet_id', 'cabinet_type'], 'required'],
            [['store_id', 'cabinet_type', 'addtime', 'is_delete'], 'integer'],
            [['cabinet_id', 'city', 'province', 'address'], 'string'],
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
            'cabinet_id' => '自提柜ID',
            'cabinet_type' => '自提柜类型',
            'province' => '省',
            'city' => '市',
            'address' => '详细地址',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
