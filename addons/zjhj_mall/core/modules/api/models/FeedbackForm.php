<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 15:25
 */

namespace app\modules\api\models;

use app\models\Feedback;
use app\models\FeedbackType;
/**
 * @property \app\models\Card $card;
 */
class FeedbackForm extends ApiModel
{
    public $keyword;
    public $status;
    public $type_id;
    public $feedback_id;
    public $store_id;
    public $feedback_desc;
    public $user_id;
    public $content;
    public $information;
    public $pic_list;

    public function rules()
    {
        return [
            [['store_id','user_id', 'type_id', 'status', 'addtime'], 'integer'],
            [['pic_list', 'content'], 'string'],
            [['information'], 'string', 'max' => 255],
        ];
    }
    
    public function save(){
        
        if($this->type_id && $this->type_id==''){
            return [
                'code' => 1,
                'msg' => '反馈类型不能为空'
            ];
        }
        if($this->content && $this->content==''){
            return [
                'code' => 1,
                'msg' => '反馈内容不能为空'
            ];
        }

        $store_id = $this->store_id;
        $user_id=$this->user_id;
        $type_id = $this->type_id;
        $information=$this->information;
        $content = $this->content;
        $pic_list=$this->pic_list;

        $feedback = new Feedback();

        $feedback->store_id = $store_id;
        $feedback->user_id=$user_id;
        $feedback->type_id = $type_id;
        $feedback->information=$information;
        $feedback->content = $content;
        $feedback->pic_list=$pic_list;
        $feedback->status = 1;
        $feedback->addtime=time();

        if ($feedback->save()) {
                return [
                    'code'=>0,
                    'msg'=>'成功'
                ];
            } else {
                return $this->errorResponse;
            }

    }
}
