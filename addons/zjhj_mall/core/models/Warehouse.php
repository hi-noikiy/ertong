<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%warehouse}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $warehouse_name
 * @property integer $is_delete
 * @property integer $addtime
 */
class Warehouse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%warehouse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'warehouse_name'], 'required'],
            [['store_id', 'addtime', 'is_delete'], 'integer'],
            [['warehouse_name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '商城id',
            'warehouse_name' => '仓库名称',
            'is_delete' => '是否删除：0=未删除，1=已删除',
            'addtime' => '添加时间',
        ];
    }
}
