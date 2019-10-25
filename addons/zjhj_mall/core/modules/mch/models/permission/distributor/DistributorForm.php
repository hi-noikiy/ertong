<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\distributor;


use app\models\Model;
use app\models\Distributor;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class DistributorForm extends MchModel
{
    public $limit;
    public $page;
    public $keyword;

    public function rules()
    {
        return [
            [['limit', 'page'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
        ];
    }

    public function pagination()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $keyword = $this->keyword;

        $model = Distributor::find()->andWhere(['is_delete' => 1, 'store_id' => $this->getCurrentStoreId()]);

        
        if (trim($keyword)) {

            $model->andWhere(['or',['LIKE', 'name', $keyword],['LIKE', 'mobile', $keyword]]);
        }
        $pagination = new Pagination(['totalCount' => $model->count(), 'pageSize' => $this->limit]);
        

        $list = $model->limit($this->limit)->offset($pagination->offset)->orderBy(['create_time'=>SORT_ASC])->asArray()->all();
        if($list){
            foreach ($list as $key => $val) {
                $list[$key]['create_time']=date('Y-m-d H:i',$val['create_time']);
                $list[$key]['entry_time']=date('Y-m-d H:i',$val['entry_time']);

                $len=strlen($val['id']);
                if($len==1){
                    $user_id_str="ps000".$val['id'];
                }
                if($len==2){
                    $user_id_str="ps00".$val['id'];
                }
                if($len==3){
                    $user_id_str="ps0".$val['id'];
                }
                if($len==4){
                    $user_id_str="ps".$val['id'];
                }
                $list[$key]['user_id_str']=$user_id_str;
            }
        }
        return [
            'list' => $list,
            'pagination' => $pagination
        ];
    }
}
