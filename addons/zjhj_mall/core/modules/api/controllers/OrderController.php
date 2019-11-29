<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/17
 * Time: 11:47
 */

namespace app\modules\api\controllers;

use app\hejiang\ApiResponse;
use app\hejiang\BaseApiResponse;
use app\models\ActionLog;
use app\models\Model;
use app\models\Order;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\models\ExpressDetailForm;
use app\modules\api\models\LocationForm;
use app\modules\api\models\OrderClerkForm;
use app\modules\api\models\OrderCommentForm;
use app\modules\api\models\OrderCommentPreview;
use app\modules\api\models\OrderConfirmForm;
use app\modules\api\models\OrderDetailForm;
use app\modules\api\models\OrderListForm;
use app\modules\api\models\OrderPayDataForm;
use app\modules\api\models\OrderRefundDetailForm;
use app\modules\api\models\OrderRefundForm;
use app\modules\api\models\OrderRefundPreviewForm;
use app\modules\api\models\OrderRefundSendForm;
use app\modules\api\models\OrderRevokeForm;
use app\modules\api\models\OrderSubmitForm;
use app\modules\api\models\OrderSubmitPreviewForm;
use app\modules\api\models\QrcodeForm;

class OrderController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
        ]);
    }

    //订单提交前的预览页面
    public function actionSubmitPreview()
    {
        $form = new OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //订单提交
    public function actionSubmit()
    {
        $form = new OrderSubmitForm();
        $model = \Yii::$app->request->post();
        if ($model['offline'] == 0) {
            $form->scenario = "EXPRESS";
        } else {
            $form->scenario = "OFFLINE";
        }
        $form->attributes = $model;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->version = hj_core_version();
        return new BaseApiResponse($form->save());
    }

    //新-订单提交前的预览页面
    public function actionNewSubmitPreview()
    {
        $form = new \app\modules\api\models\order\OrderSubmitPreviewForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->store = $this->store;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //新-订单提交
    public function actionNewSubmit()
    {
        $form = new \app\modules\api\models\order\OrderSubmitForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store = $this->store;
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->save());
    }

    //订单支付数据
    public function actionPayData()
    {
        $form = new OrderPayDataForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user = \Yii::$app->user->identity;
        return new BaseApiResponse($form->search());
    }

    //订单列表
    public function actionList()
    {
        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //订单取消
    public function actionRevoke()
    {
        $form = new OrderRevokeForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //订单确认收货
    public function actionConfirm()
    {
        $form = new OrderConfirmForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //订单各个状态数量
    public function actionCountData()
    {
        $res = OrderListForm::getCountData($this->store->id, \Yii::$app->user->id);
        return new BaseApiResponse([
            'code' => 0,
            'msg' => 'success',
            'data' => $res,
        ]);
    }

    //订单详情
    public function actionDetail()
    {
        $form = new OrderDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //售后页面
    public function actionRefundPreview()
    {
        $form = new OrderRefundPreviewForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //售后提交
    public function actionRefund()
    {
        $form = new OrderRefundForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //售后订单详情
    public function actionRefundDetail()
    {
        $form = new OrderRefundDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //评论预览页面
    public function actionCommentPreview()
    {
        $form = new OrderCommentPreview();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //评论提交
    public function actionComment()
    {
        $form = new OrderCommentForm();
        $form->attributes = \Yii::$app->request->post();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //订单物流信息
    public function actionExpressDetail()
    {
        $form = new ExpressDetailForm();
        $form->attributes = \Yii::$app->request->get();
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->search());
    }

    //核销订单
    public function actionClerk()
    {
        $form = new OrderClerkForm();
        $form->order_id = \Yii::$app->request->get('order_id');
        $form->order_no = \Yii::$app->request->get('order_no');
        $form->store_id = $this->store->id;
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    //核销订单详情
    public function actionClerkDetail()
    {
        if (\Yii::$app->user->identity->is_clerk != 1) {
            return new BaseApiResponse([
                'code' => 1,
                'msg' => '不是核销员禁止核销'
            ]);
        }
        $form = new OrderDetailForm();
        $form->order_no = \Yii::$app->request->get('order_no');
        $form->store_id = $this->store->id;
//        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->clerk());
    }

    public function actionGetQrcode()
    {
        $order_no = \Yii::$app->request->get('order_no');
        $form = new QrcodeForm();
        $form->page = "pages/clerk/clerk";
        $form->width = 100;
        if (\Yii::$app->fromAlipayApp()) {
            $form->scene = "order_no={$order_no}";
        } else {
            $form->scene = "{$order_no}";
        }
        $form->store = $this->store;
        $res = $form->getQrcode();
        return new BaseApiResponse($res);
    }

    public function actionLocation()
    {
        $form = new LocationForm();
        $form->store_id = $this->store->id;
        $form->attributes = \Yii::$app->request->get();
        return new BaseApiResponse($form->search());
    }

    //售后订单用户发货
    public function actionRefundSend()
    {
        $form = new OrderRefundSendForm();
        $form->attributes = \Yii::$app->request->post();
        $form->user_id = \Yii::$app->user->id;
        return new BaseApiResponse($form->save());
    }

    public function AuthAction(){

    }

    public function actionCreate(){
        $orderNo =  \Yii::$app->request->post('order_no');
        //$goods = \Yii::$app->request->get('goods');
        $machineId = \Yii::$app->request->post('machineId');
        $delivers = [];
        $delivers['deliverNo'] = \Yii::$app->request->post('deliverNo');
        $delivers['coolType'] = \Yii::$app->request->post('coolType');
        $delivers['goods'] = \Yii::$app->request->post('goods');
        $delivers['quantity'] = \Yii::$app->request->post('quantity');
        $total = \Yii::$app->request->post('total');

        $appId='19103111555648';
        $appScret='105ef16489204001b046ab742b4acb7c';
        $loginUrl="http://open.iwuyi.net/api/authorization/login";
        $array=array(
            'appId'=>$appId,
            'appScret'=>$appScret,
        );
        $createArr = [
            'machineId' => $machineId,
            'order_no' => $orderNo,
            'delivers' => $delivers,
            'total' => $total
        ];
        $result=$this->getCurl($loginUrl,json_encode($array));
        $result_array=json_decode($result,true);
        $authorizToken='';
        if($result_array['code']==0){
            $authorizToken=$result_array['data']['authorizToken'];
        }
        $locationUrl="http://open.iwuyi.net/api/express/createOrder";
        $list = $this->getCurl($locationUrl, json_encode($createArr), $authorizToken);
        $list = json_decode($list, true);
        return new BaseApiResponse($list);


    }

    public function sign($sign_array, $appScret){
        $sign_str='';
        ksort($sign_array);
        foreach ($sign_array as $key => $val) {
            if(!isset($sign_array[$key])){
                unset($sign_array[$key]);
            }
            $sign_str.=$key."=".$val."&";
        }
        if(isset($sign_str)){
            $sign=substr($sign_str,0,strlen($sign_str)-1);
        }
        return md5($sign.$appScret);
    }
    public function getCurl($url, $jsonStr, $authorizToken=null){
        if(isset($authorizToken)){
            $httpHeader=array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
                'authorizToken:'.$authorizToken,
            );
        }else{
            $httpHeader=array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr),
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        $response = curl_exec($ch);
        //$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;
    }
}
