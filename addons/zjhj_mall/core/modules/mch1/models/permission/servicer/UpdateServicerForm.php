<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\servicer;

use app\models\Servicer;
use app\modules\mch\models\MchModel;
use Yii;

class UpdateServicerForm extends MchModel
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
            'mobile' => '客服手机号',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $servicer = Servicer::find()->where(['store_id' => $this->getCurrentStoreId(), 'id' => $this->id])->one();
        if (!$servicer) {
            return [
                'code' => 1,
                'msg' => '没有查到客服！'
            ];
        }

        $model = Servicer::findOne($this->id);
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
