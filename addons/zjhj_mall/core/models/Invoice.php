<?php

namespace app\models;

use Yii;

class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id','order_no','email'], 'required'],
            [['store_id', 'user_id', 'addtime','status','type'], 'integer'],
            [['corporate_name','email','number','order_no','project_name','project_coding','fpDm','fpHm'], 'string'],
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
            'user_id' => '用户id',
            'corporate_name' => '公司名称',
            'total_sum' => '总价格',
            'email' => '邮箱',
            'number' => '纳税人识别号',
            'order_no' => '订单流水号',
            'project_name' => '项目名称',
            'project_coding' => '项目编码',
            'fpDm' => '蓝字发票代码',
            'fpHm' => '蓝字发票号码',
            'type' => '发票类型1蓝票2红票',
            'status' => '发票状态0申请失败1待审核2已审核3审核未通过4已删除',
            'addtime' => '添加时间',
        ];
    }
}
