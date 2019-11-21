<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%delivery_time}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $start_time
 * @property string $end_time
 * @property integer $is_delete
 * @property string $is_default
 */
class DeliveryTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%delivery_time}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'start_time', 'end_time'], 'required'],
            [['store_id', 'is_delete'], 'integer'],
            [['start_time', 'end_time', 'service_time'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Attr Group ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'service_time' => '送达时间',
            'is_delete' => 'Is Delete',
            
        ];
    }
}
