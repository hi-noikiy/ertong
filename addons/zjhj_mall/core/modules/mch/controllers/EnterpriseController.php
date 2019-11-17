<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:53
 */

namespace app\modules\mch\controllers;

use app\models\User;
use app\models\Enterprise;
use app\modules\mch\models\UserListForm;
use app\modules\mch\models\EnterpriseListForm;
use yii\helpers\ArrayHelper;
class EnterpriseController extends Controller
{
    /**
     * @return string
     * 企业用户列表
     */
    public function actionIndex()
    {
        $form = new EnterpriseListForm();

        $form->store_id = $this->store->id;
        $arr = $form->search();
        foreach ($arr['list'] as $key => $val) {
            $user=User::find()->where(['id'=>$val['parent_user_id']])->asArray()->one();
            $arr['list'][$key]['nickname']=$user['username'];
        }

        // print_r($arr['list']);die;
        return $this->render('index', [
            'row_count' => $arr['row_count'],
            'pagination' => $arr['pagination'],
            'list' => $arr['list'],
        ]);
    }
    /**
     * @return string
     * 企业用户单个详情
     */
    public function actionDetail($id = null)
    {

        $list = Enterprise::find()->alias('e')->where(['e.store_id' => $this->store->id,'e.id'=>$id])->leftJoin(User::tableName() . ' u', 'u.id=e.user_id')->select(['e.*','u.username username','u.binding mobile','u.parent_user_id parent_user_id','u.binding binding'])->asArray()->one();
        $list['addtime']=date('Y-m-d H:i:s',$list['create_time']);
        
        $user=User::find()->where(['id'=>$list['parent_user_id']])->asArray()->one();
        $list['nickname']=$list['username'];
        // print_r($list);die;
        return $this->render('detail', [
            'list' => $list,
        ]);
    }
    /**
     * @return string
     * 企业用户修改状态
     */
    public function actionEditStatus()
    {
        $id = \Yii::$app->request->post('id');
        $status = \Yii::$app->request->post('status');
        $reason = \Yii::$app->request->post('reason');
        $parent_id = \Yii::$app->request->post('parent_id');


        $form = Enterprise::find()->where(['id' => $id])->one();
        if(!$form){
            return [
                'code' => 1,
                'msg' => '没找到该商家或已审核完毕',
            ];
        }else{
            
            $form->status = $status;
            $form->reason = $reason;
            if ($form->save()) {
                if(isset($parent_id)){
                    $user_id=$form->user_id;
                    $user=User::find()->where(['id' => $user_id])->one();
                    $user->parent_user_id=$parent_id;
                    $user->save();
                }
                
                return [
                    'code' => 0,
                    'msg' => '操作成功',
                ];
            }else{
                return [
                    'code' => 1,
                    'msg' => '操作失败',
                ];
            }
        }
    }
    //查找自提柜地址
    public function actionInvitationCodeList()
    {
        $model=\Yii::$app->request->post();
        $invit_code = $model['invit_code'];
        $user_id = $model['user_id'];
        $query = User::find()->where(['is_delete' => 0, 'store_id' => $this->store->id]);
        $query->andWhere(['<>', 'id', $user_id]);
        $query->andWhere(['like', 'invitation_code', $invit_code.'%', false]);

        $list = $query->asArray()->all();

        if ($list) {
            foreach ($list as $key => $val) {
                $list[$key]['invitation_code']=str_replace($invit_code, '<font color="red">' .$invit_code. '</font>', $val['invitation_code']);
            }
            return [
                'code' => 0,
                'data' => $list,
                'msg' => '成功',
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '当前有未完结订单，不可删除',
            ];
            
        }
    }
}
