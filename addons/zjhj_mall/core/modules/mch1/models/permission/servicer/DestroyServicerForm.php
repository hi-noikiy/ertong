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

class DestroyServicerForm extends MchModel
{
    public $id;

    public function destroy()
    {

        $model = Servicer::findOne($this->id);

        if ($model) {
            $model->is_delete = 0;
            $model->save();

            return [
                'code' => 0,
                'msg' => '删除成功'
            ];
        }
        return $this->getErrorResponse($model);

    }
}
