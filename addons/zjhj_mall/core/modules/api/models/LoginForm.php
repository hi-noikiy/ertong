<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/1
 * Time: 16:52
 */

namespace app\modules\api\models;

use Alipay\AlipayRequestFactory;
use app\hejiang\ApiResponse;
use app\models\alipay\MpConfig;
use app\models\Share;
use app\models\User;
use app\models\CouponAutoSend;
use app\models\Coupon;
use app\models\UserCoupon;
use app\modules\api\models\wxbdc\WXBizDataCrypt;
use Curl\Curl;
use Alipay\Exception\AlipayException;

class LoginForm extends ApiModel
{
    public $wechat_app;

    public $code;
    public $user_info;
    public $encrypted_data;
    public $iv;
    public $signature;

    public $store_id;

    public function rules()
    {
        return [
            [['wechat_app', 'code', 'user_info', 'encrypted_data', 'iv', 'signature'], 'required'],
        ];
    }

    public function loginAlipay()
    {
        try {
            $aop = $this->getAlipay();
        } catch (\InvalidArgumentException $ex) {
            return new ApiResponse(1, $ex->getMessage());
        }
        try {
            $request = AlipayRequestFactory::create('alipay.system.oauth.token', [
                'grant_type' => 'authorization_code',
                'code' => $this->code,
            ]);
            $response = $aop->execute($request);
            $dataToken = $response->getData();

            $request = AlipayRequestFactory::create('alipay.user.info.share', [
                'auth_token' => $dataToken['access_token'],
            ]);
            $response = $aop->execute($request);
            $dataInfo = $response->getData();

            $data = array_merge($dataToken, $dataInfo);
        } catch (AlipayException $ex) {
            return new ApiResponse(2, $ex->getMessage());
        }

        $user = User::findOne(['wechat_open_id' => $data['user_id'], 'store_id' => $this->store_id]);
        if (!$user) {
            $user = new User();
            $user->type = 1;
            $user->username = $data['user_id'];
            $user->password = \Yii::$app->security->generatePasswordHash(\Yii::$app->security->generateRandomString(), 5);
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->addtime = time();
            $user->is_delete = 0;
            $user->wechat_open_id = $data['user_id'];
            // $user->wechat_union_id = isset($data['unionId']) ? $data['unionId'] : '';
            $user->nickname = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nick_name']);
            $user->avatar_url = $data['avatar'] ? $data['avatar'] : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/avatar.png';
            $user->store_id = $this->store_id;
            $user->invitation_code = mt_rand(100000, 999999);
            $user->platform = 1; // 支付宝
            $user->save();
        } else {
            $user->nickname = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nick_name']);
            $user->avatar_url = $data['avatar'];
            $user->save();
        }
        $share = Share::findOne(['user_id' => $user->parent_id]);
        $share_user = User::findOne(['id' => $share->user_id]);
        $data = [
            'access_token' => $user->access_token,
            'nickname' => $user->nickname,
            'avatar_url' => $user->avatar_url,
            'is_distributor' => $user->is_distributor ? $user->is_distributor : 0,
            'parent' => $share->id ? ($share->name ? $share->name : $share_user->nickname) : '总店',
            'id' => $user->id,
            'is_clerk' => $user->is_clerk === null ? 0 : $user->is_clerk,
            'integral' => $user->integral === null ? 0 : $user->integral,
            'money' => $user->money === null ? 0 : $user->money,
            'level' => $user->level,
            'blacklist' => $user->blacklist,
        ];
        return new ApiResponse(0, 'success', $data);
    }

    public function login()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        
        $res = $this->getOpenid($this->code);
        if (!$res || empty($res['openid'])) {
            return new ApiResponse(1, '获取用户OpenId失败', $res);
        }
        $session_key = $res['session_key'];
        // echo $this->wechat_app->app_id;die;
        // $pc = new WXBizDataCrypt(self::appid, $session_key);
        $pc = new WXBizDataCrypt($this->wechat_app->app_id, $session_key);
        $errCode = $pc->decryptData($this->encrypted_data, $this->iv, $data);
        if ($errCode == 0 || $errCode == -41003) {
            if ($errCode == -41003) {
                $user_info = json_decode($this->user_info, true);
                $data = [
                    'openId' => $res['openid'],
                    'nickName' => $user_info['nickName'],
                    'gender' => $user_info['gender'],
                    'city' => $user_info['city'],
                    'province' => $user_info['province'],
                    'country' => $user_info['country'],
                    'avatarUrl' => $user_info['avatarUrl'],
                    'unionId' => isset($res['unionid']) ? $res['unionid'] : '',
                ];
            } else {
                $data = json_decode($data, true);
            }
            $user = User::findOne(['wechat_open_id' => $data['openId'], 'store_id' => $this->store_id]);
            if (!$user) {
                $user = new User();
                $user->type = 1;
                $user->username = $data['openId'];
                $user->password = \Yii::$app->security->generatePasswordHash(\Yii::$app->security->generateRandomString(), 5);
                $user->auth_key = \Yii::$app->security->generateRandomString();
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->addtime = time();
                $user->is_delete = 0;
                $user->wechat_open_id = $data['openId'];
                $user->wechat_union_id = isset($data['unionId']) ? $data['unionId'] : '';
                $user->nickname = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nickName']);
                $user->avatar_url = $data['avatarUrl'] ? $data['avatarUrl'] : \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/avatar.png';
                $user->store_id = $this->store_id;
                $user->invitation_code = mt_rand(100000, 999999);
                $user->platform = 0; // 微信
                $user->save();
                $same_user = User::find()->select('id')->where([
                    'AND',
                    [
                        'store_id' => $this->store_id,
                        'wechat_open_id' => $data['openId'],
                        'is_delete' => 0,
                    ],
                    ['<', 'id', $user->id],
                ])->one();
                if ($same_user) {
                    $user->delete();
                    $user = null;
                    $user = $same_user;
                }
                $coupon_arr=CouponAutoSend::find()->where(['store_id'=>$this->store_id,'event'=>4,'is_delete'=>0])->asArray()->one();
                $now_time=time();
                if(!empty($coupon_arr)){
                    $coupon=Coupon::find()->where(['store_id'=>$this->store_id,'id'=>$coupon_arr['coupon_id'],'is_delete'=>0])->asArray()->one();
                    if($coupon_arr['send_times']==0){//无限制发放
                        if($coupon['expire_type']==1){//N天有效期
                            $end_time=strtotime("+".$coupon['expire_day']." day");
                        }else{
                            $end_time=$coupon['end_time'];
                        }
                        if($now_time<$end_time){
                            //发放优惠券 自动发放+1
                            $UserCoupon=new UserCoupon();
                            $UserCoupon->store_id=$this->store_id;
                            $UserCoupon->user_id=$user->id;
                            $UserCoupon->coupon_id=$coupon_arr['coupon_id'];
                            $UserCoupon->coupon_auto_send_id=$coupon_arr['id'];
                            $UserCoupon->begin_time=time();
                            $UserCoupon->end_time=$end_time;
                            $UserCoupon->is_expire=0;
                            $UserCoupon->is_use=0;
                            $UserCoupon->is_delete=0;
                            $UserCoupon->addtime=time();
                            $UserCoupon->type=1;
                            $UserCoupon->integral=$coupon['integral'];
                            $UserCoupon->price=$coupon['sub_price'];
                            if($UserCoupon->save()){
                                $CouponAutoSend = CouponAutoSend::findOne(['store_id'=>$this->store_id,'event'=>4,'is_delete'=>0]);
                                $CouponAutoSend->count=$coupon_arr['count']+1;
                                $CouponAutoSend->save();
                            }
                        }
                    }else{
                        if($coupon_arr['count']<$coupon_arr['send_times']){
                            if($coupon['expire_type']==1){//N天有效期
                                $end_time=strtotime("+".$coupon['expire_day']." day");
                            }else{
                                $end_time=$coupon['end_time'];
                            }
                            if($now_time<$end_time){
                                //发放优惠券 自动发放+1
                                $UserCoupon=new UserCoupon();
                                $UserCoupon->store_id=$this->store_id;
                                $UserCoupon->user_id=$user->id;
                                $UserCoupon->coupon_id=$coupon_arr['coupon_id'];
                                $UserCoupon->coupon_auto_send_id=$coupon_arr['id'];
                                $UserCoupon->begin_time=time();
                                $UserCoupon->end_time=$end_time;
                                $UserCoupon->is_expire=0;
                                $UserCoupon->is_use=0;
                                $UserCoupon->is_delete=0;
                                $UserCoupon->addtime=time();
                                $UserCoupon->type=1;
                                $UserCoupon->integral=$coupon['integral'];
                                $UserCoupon->price=$coupon['sub_price'];
                                if($UserCoupon->save()){
                                    $CouponAutoSend = CouponAutoSend::findOne(['store_id'=>$this->store_id,'event'=>4,'is_delete'=>0]);
                                    $CouponAutoSend->count=$coupon_arr['count']+1;
                                    $CouponAutoSend->save();
                                }

                            }
                        }
                    }
                }
            } else {
                $user->nickname = preg_replace('/[\xf0-\xf7].{3}/', '', $data['nickName']);
                $user->avatar_url = $data['avatarUrl'];
                $user->wechat_union_id = isset($data['unionId']) ? $data['unionId'] : '';
                $user->save();
            }
            $share = Share::findOne(['user_id' => $user->parent_id]);
            $share_user = User::findOne(['id' => $share->user_id]);
            $data = [
                'access_token' => $user->access_token,
                'nickname' => $user->nickname,
                'avatar_url' => $user->avatar_url,
                'is_distributor' => $user->is_distributor ? $user->is_distributor : 0,
                'parent' => $share->id ? ($share->name ? $share->name : $share_user->nickname) : '总店',
                'id' => $user->id,
                'is_clerk' => $user->is_clerk === null ? 0 : $user->is_clerk,
                'integral' => $user->integral === null ? 0 : $user->integral,
                'money' => $user->money === null ? 0 : $user->money,
                'errCode' => $errCode,
                'binding' => $user->binding,
                'level' => $user->level,
                'blacklist' => $user->blacklist,
            ];
            return new ApiResponse(0, 'success', $data);
        } else {
            return new ApiResponse(1, '登录失败', $errCode);
        }
    }

    private function getOpenid($code)
    {
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->wechat_app->app_id}&secret={$this->wechat_app->app_secret}&js_code={$code}&grant_type=authorization_code";
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($api);
        $res = $curl->response;
        $res = json_decode($res, true);
        return $res;
    }
}
