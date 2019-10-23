<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 15:25
 */

namespace app\modules\mch\models;

use app\models\Feedback;
use app\models\User;
use app\models\FeedbackType;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * @property \app\models\Card $card;
 */
class FeedbackForm extends MchModel
{
    public $keyword;
    public $status;
    public $type_id;
    public $feedback_id;
    public $store_id;
    public $feedback_desc;

    public function getList()
    {
        $keyword = $this->keyword;
        $status = $this->status;
        $type_id = $this->type_id;
        // return $status;
        $query = Feedback::find()->alias('f')->where(['f.store_id'=>$this->store_id]);
        $query->leftJoin(['u' => User::tableName()], 'u.id=f.user_id');
        $query->leftJoin(['t' => FeedbackType::tableName()], 't.id=f.type_id');
        $query->andWhere(['t.status'=>1]);
        if (trim($keyword)) {
            $query->andWhere(['LIKE', 'u.username', $keyword]);
        }
        if ($status != 0) {
            $query->andWhere('f.status=:status', [':status' => $status]);
        }
        if ($type_id != 0) {
            $query->andWhere('f.type_id=:type_id', [':type_id' => $type_id]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'route' => \Yii::$app->requestedRoute]);
        $list = $query->select(['f.*','u.username','u.contact_way','u.binding','t.type_name'])
            ->orderBy(['f.addtime' => SORT_DESC])
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->asArray()
            ->all();
        foreach ($list as $key => $val) {
            $list[$key]['addtime']=date('Y-m-d H:i:s',$val['addtime']);
            if($val['status']==1){
                $list[$key]['status_name']="未解决";
            }
            if($val['status']==2){
                $list[$key]['status_name']="已解决";
            }
        }
        $feedbacktype_list=FeedbackType::find()->where(['status'=>1])->asArray()->all();

        return [
            'list'=>$list,
            'feedbacktype_list'=>$feedbacktype_list,
            'pagination' => $pagination
        ];
    }
    public function getOneDetail(){
        $feedback_id = $this->feedback_id;

        $query = Feedback::find()->alias('f')->where(['f.store_id'=>$this->store_id,'f.id'=>$feedback_id]);
        $query->leftJoin(['u' => User::tableName()], 'u.id=f.user_id');
        $query->leftJoin(['t' => FeedbackType::tableName()], 't.id=f.type_id');
        $query->andWhere(['t.status'=>1]);

        $list = $query->select(['f.*','u.username','u.contact_way','u.binding','t.type_name'])->asArray()->one();
        $list['addtime']=date('Y-m-d H:i:s',$list['addtime']);
        $list['pic_list']=json_decode($list['pic_list']);
        return [
            'list'=>$list,
        ];
    }
    public function save(){
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $feedback_id = $this->feedback_id;
        $feedback_desc=$this->feedback_desc;

        $result=Feedback::find()->where(['store_id'=>$this->store_id,'id'=>$feedback_id])->one();
        
        if($result){
            $result->feedback_desc=$feedback_desc;
            if ($result->save()) {
                return [
                    'code'=>0,
                    'msg'=>'成功'
                ];
            } else {
                return $this->errorResponse;
            }
        }

    }
}
