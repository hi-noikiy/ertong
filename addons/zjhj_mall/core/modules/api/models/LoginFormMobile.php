<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 11:15
 */

namespace app\modules\api\models;

use app\models\User;
use Curl\Curl;
use app\hejiang\ApiResponse;
use yii\helpers\VarDumper;

use Alipay\AlipayRequestFactory;
// use app\hejiang\ApiResponse;
use app\models\alipay\MpConfig;
use app\models\Share;
// use app\models\User;
use app\modules\api\models\wxbdc\WXBizDataCrypt;
// use Curl\Curl;
use Alipay\Exception\AlipayException;
class LoginFormMobile extends ApiModel
{
    public $contact_way;

    public $password;

    public $store_id;

    public function rules()

    {

        return [

            [['contact_way', 'password',], 'required'],

        ];

    }
    private $ErrorData = array(
        "0"     =>  "",
        "1"     =>  "",
        "2"     =>  "",
        "101"   =>  "手机号格式不正确",
        "102"   =>  "密码格式不正确",
        "103"   =>  "手机号或密码错误",
        "104"   =>  "注册失败",
        "105"   =>  "密码修改失败",
        "106"   =>  "该手机号已被注册",
        "107"   =>  "请输入手机号",
        "200"   =>  "短信发送成功",
        "201"   =>  "短信验证成功",
        "202"   =>  "短信发送失败",
        "203"   =>  "短信验证失败",
        "204"   =>  "您今日已发短信到达上线！",
        "205"   =>  "请输入短信验证码",
        "206"   =>  "请勿频繁发送短信",
        "207"   =>  "短信通知服务未开启",
        "208"   =>  "未设置验证码短信",

    );
    
    //登录
    public function searchOne()
    {


        $contact_way = $this->contact_way;
        $password    = $this->password;
        
        $preg_phone = "/^1[23456789]\d{9}$/";
        $preg_pw    = "/^[A-Za-z0-9]{6,20}$/";

        if(!preg_match($preg_phone,$contact_way)){//验证手机号
            return new ApiResponse(101,$this->ErrorData[101]);
            // return $this->ResultReturn(101);
        }
        if(!preg_match($preg_pw,$password)){//验证密码
            return new ApiResponse(102,$this->ErrorData[102]);
            
        }

        $list=array();
        $list = User::find()->select(['id', 'type', 'username', 'password', 'auth_key', 'access_token', 'addtime', 'is_delete', 'wechat_open_id', 'wechat_union_id', 'nickname','avatar_url','store_id','is_distributor','parent_id','time','total_price','price','is_clerk','we7_uid','shop_id','level','integral','total_integral','money','contact_way','comments','binding','wechat_platform_open_id','platform'])
            ->where(['store_id'=>$this->store_id, 'contact_way' => $contact_way, 'password' => md5($password)])->asArray()->one();
        
        if($list){
            
            return new ApiResponse(0,'success', $list);
            
        }else{
            return new ApiResponse(103,$this->ErrorData[103]);
            
        }
        
        
    }
    //注册
    public function register()
    {
        $contact_way = $this->contact_way;
        $password    = $this->password;
        $preg_phone = "/^1[23456789]\d{9}$/";
        $preg_pw    = "/^[A-Za-z0-9]{6,20}$/";
        if(!preg_match($preg_phone,$contact_way)){//验证手机号
            return new ApiResponse(101,$this->ErrorData[101]);
            // return $this->ResultReturn(101);
        }
        if(!preg_match($preg_pw,$password)){//验证密码
            return new ApiResponse(102,$this->ErrorData[102]);
            return $this->ResultReturn(102);
        }
        
        

        $list = User::find()->where(['contact_way'=>$contact_way])->one();
        if($list){
            return new ApiResponse(106,$this->ErrorData[106]);
            // return $this->ResultReturn(106);
        }
        $list_user_info=array();
        $User = new User();
        $User->type        =1;
        $User->contact_way =$contact_way;
        $User->password    =md5($password);
        $User->username = "呵呵哒";
        $User->auth_key = \Yii::$app->security->generateRandomString();
        $User->access_token = \Yii::$app->security->generateRandomString();
        $User->addtime = time();
        $User->is_delete = 0;
        $User->wechat_open_id = '2123';
        $User->nickname = "呵呵哒";
        $User->avatar_url = "123456";
        $User->store_id = 1;
        $User->platform = 1; // 支付宝
        
        if($User->save()){
            $list_user_info = User::find()->where(['id'=>$User->id])->asArray()->one();
            return new ApiResponse(0,'success', $list_user_info);
            // return $this->ResultReturn(0,$list_user_info);
        }else{
            return new ApiResponse(104,$this->ErrorData[104]);

        }
        
    }
    
    //忘记密码
    public function forgetPassword()
    {
        $contact_way = $this->contact_way;
        $password    = $this->password;
        $preg_phone = "/^1[23456789]\d{9}$/";
        $preg_pw    = "/^[A-Za-z0-9]{6,20}$/";
        if(!preg_match($preg_phone,$contact_way)){//验证手机号
            return new ApiResponse(101,$this->ErrorData[101]);
            // return $this->ResultReturn(101);
        }
        if(!preg_match($preg_pw,$password)){//验证密码
            return new ApiResponse(102,$this->ErrorData[102]);
            return $this->ResultReturn(102);
        }

        $list = User::find()->where(['contact_way'=>$contact_way])->one();
        $list_user_info=array();
        if($list){
            $list->password=md5($password);
            if($list->save()){
                $list_user_info = User::find()->where(['id'=>$list->id])->asArray()->one();
                return new ApiResponse(0,'success', $list_user_info);
                // return $this->ResultReturn(0,$list_user_info);
            }else{
                return new ApiResponse(105,$this->ErrorData[105]);
                // return $this->ResultReturn(105);
            }
            
        }else{
            return new ApiResponse(103,$this->ErrorData[103]);
            // return $this->ResultReturn(103);
        }
    }

    public function log($error_array){
        $time = microtime(true);
        $log = new FileTarget();
        $log->logFile = Yii::$app->getRuntimePath() . '/logs/songlin.log';
        $log->messages[] = ['这个地方出问题了'.PHP_EOL ,1,json_encode($error_array),$time];
        $log->export();
}
}
