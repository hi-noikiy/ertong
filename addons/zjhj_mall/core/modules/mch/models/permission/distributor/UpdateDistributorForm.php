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

class UpdateDistributorForm extends MchModel
{
    public $id;
    public $entry_time;
    public $mobile;

    public function rules()
    {
        return [
            [['entry_time', 'mobile'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'entry_time' => '入职时间',
            'mobile' => '配送员手机号',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $distributor = Distributor::find()->where(['store_id' => $this->getCurrentStoreId(), 'id' => $this->id])->one();
        if (!$distributor) {
            return [
                'code' => 1,
                'msg' => '没有查到配送员！'
            ];
        }

        $model = Distributor::findOne($this->id);
        $model->attributes = $this->attributes;

        if ($model->save()) {

            return [
                'code' => 0,
                'msg' => '更新成功'
            ];
        }
        return $this->getErrorResponse($model);
    }
}
