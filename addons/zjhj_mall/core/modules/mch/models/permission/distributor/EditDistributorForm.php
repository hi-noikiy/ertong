<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\distributor;

use app\models\Distributor;
use app\modules\mch\models\MchModel;

class EditDistributorForm extends MchModel
{
    public $id;

    public function edit()
    {
        $edit = Distributor::find()->andWhere(['id' => $this->id])->asArray()->one();
        $edit['create_time']=date('Y-m-d H:i',$edit['create_time']);
        $edit['entry_time']=date('Y-m-d H:i',$edit['entry_time']);
        $len=strlen($edit['id']);
        if($len==1){
            $user_id_str="ps000".$edit['id'];
        }
        if($len==2){
            $user_id_str="ps00".$edit['id'];
        }
        if($len==3){
            $user_id_str="ps0".$edit['id'];
        }
        if($len==4){
            $user_id_str="ps".$edit['id'];
        }
        $edit['user_id_str']=$user_id_str;

        return $edit;
    }
}
