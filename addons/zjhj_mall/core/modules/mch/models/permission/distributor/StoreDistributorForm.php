<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\distributor;

use app\models\Distributor;
use app\modules\mch\models\MchModel;
use Yii;

class StoreDistributorForm extends MchModel
{
    public $store_id;
    public $name;
    public $mobile;
    public $entry_time;

    public function rules()
    {
        return [
            [['name', 'mobile', 'entry_time'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '配送员姓名',
            'mobile' => '配送员手机号',
            'entry_time' => '入职时间',
        ];
    }

    public function store()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = new Distributor();
        $model->attributes = $this->attributes;
        $model->create_time = time();
        $model->is_delete = 1;
        $model->store_id = $this->store_id;

        if ($model->save()) {

            return [
                'code' => 0,
                'msg' => '添加成功'
            ];
        }
        return $this->getErrorResponse($model);

    }
}
