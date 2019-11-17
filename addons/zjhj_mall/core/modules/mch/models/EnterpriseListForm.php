<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/3
 * Time: 13:52
 */

namespace app\modules\mch\models;

use app\hejiang\ApiResponse;
use app\models\Enterprise;
use app\models\User;
use yii\data\Pagination;

class EnterpriseListForm extends MchModel
{
    public $store_id;
    public $page;
    public $id;

    public function rules()
    {
        return [
            [['store_id', 'user_id', 'create_time'], 'required'],
            [['store_id', 'user_id', 'parent_id', 'create_time', 'status'], 'integer'],
            [['enterprise_name', 'enterprise_license'], 'string'],
        ];
    }

    public function search()
    {
        $query = Enterprise::find()->alias('e')->where(['e.store_id' => $this->store_id])->leftJoin(User::tableName() . ' u', 'u.id=e.user_id');

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->select(['e.*','u.username username','u.binding mobile','u.parent_user_id parent_user_id'])->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time DESC')->asArray()->all();
        
        foreach ($list as $key => $val) {
            $list[$key]['addtime']=date('Y-m-d H:i:s',$val['create_time']);
        }
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }
    public function searchOne()
    {
        $list = Enterprise::find()->alias('e')->where(['e.store_id' => $this->store_id,'e.id'=>$this->id])->leftJoin(User::tableName() . ' u', 'u.id=e.user_id')->select(['e.*','u.username username','u.binding mobile','u.parent_user_id parent_user_id'])->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time DESC')->asArray()->one();
        return $list;
        
        $list['addtime']=date('Y-m-d H:i:s',$list['create_time']);
        
        return [
            'list' => $list,
        ];
    }
}
