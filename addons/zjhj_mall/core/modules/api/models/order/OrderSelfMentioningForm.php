<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 10:25
 */

namespace app\modules\api\models\order;


use app\model\Goods;
use app\models\Cabinet;
use app\models\common\api\CommonShoppingList;
use app\models\IntegralOrder;
use app\models\IntegralOrderDetail;
use app\models\IntegralOrderSub;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderSub;
use app\models\OrderDetail;
use app\models\OrderSub;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderSub;
use app\utils\PinterOrder;
use app\models\Level;
use app\models\Order;
use app\models\PrinterSetting;
use app\models\User;
use app\utils\Sms;
use app\utils\TaskCreate;

class OrderSelfMentioningForm extends OrderForm
{
    public $store_id;
    public $user_id;
    public $order_id;
    public $orderNo;
    public $pickupCode;
    public $machineId;
    public $status;
    public $deliverNo;

    public function rules()
    {
        return [
            [['orderNo', 'machineId', 'status', 'deliverNo'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        //$t = \Yii::$app->db->beginTransaction();
        $substr = substr( $this->deliverNo, 0, 1 );
        if ($substr == 'M'){
            $subOrder = MsOrderSub::findOne(['order_no' => $this->deliverNo]);
        }elseif ($substr == 'P'){
            $subOrder = PtOrderSub::findOne(['order_no' => $this->deliverNo]);
        }elseif ($substr == 'G'){
            $subOrder = IntegralOrderSub::findOne(['order_no' => $this->deliverNo]);
        }else{
            $subOrder = OrderSub::findOne(['order_no' => $this->deliverNo]);

        }
        if (!$subOrder){
            return [
                'code' => 1,
                'msg' => '配送单号有误'
            ];
        }

//        $order->is_confirm = 1;
//        $order->confirm_time = time();
//        if ($order->pay_type == 2) {
//            $order->is_pay = 1;
//            $order->pay_time = time();
//        }

/*
        $user = User::findOne(['id' => $order->user_id, 'store_id' => $this->store_id]);
        $order_money = Order::find()->where(['store_id' => $this->store_id, 'user_id' => $user->id, 'is_delete' => 0])
            ->andWhere(['is_pay' => 1, 'is_confirm' => 1])->select([
                'sum(pay_price)'
            ])->scalar();
        $next_level = Level::find()->where(['store_id' => $this->store_id, 'is_delete' => 0,'status'=>1])
            ->andWhere(['<', 'money', $order_money])->orderBy(['level' => SORT_DESC])->asArray()->one();
        if ($user->level < $next_level['level']) {
            $user->level = $next_level['level'];
            $user->save();
        }
*/
        $orderStr = substr( $this->orderNo, 0, 1 );
        if ($orderStr == 'M'){
            $order = MsOrder::findOne([
                'store_id' => $this->store_id,
                'order_no' => $this->orderNo,
                'is_delete' => 0,
            ]);
            $goodsId = $order->goods_id;
            $goods = MsGoods::findOne(
                [
                    'id' => $goodsId
                ]
            );
            $goods_name = [$goods->name];
        }elseif ($orderStr == 'P'){
            $order = PtOrder::findOne([
                'store_id' => $this->store_id,
                'order_no' => $this->orderNo,
                'is_delete' => 0,
            ]);
            $orderDetails = PtOrderDetail::find()->where(
                [
                    'order_id' => $order->id,
                ]
            )->asArray()->all();
            $goods_name = [];
            foreach ($orderDetails as $k => $value){
                $goods = \app\models\Goods::findOne(['id' => $value['goods_id']]);
                $goods_name[] = $goods->name;
            }
            $goods_name = implode('、', $goods_name);
        }
        elseif ($orderStr == 'G'){
            $order = IntegralOrder::findOne([
                'store_id' => $this->store_id,
                'order_no' => $this->orderNo,
                'is_delete' => 0,
            ]);
            $orderDetails = IntegralOrderDetail::find()->where(
                [
                    'order_id' => $order->id,
                ]
            )->asArray()->all();
            $goods_name = [];
            foreach ($orderDetails as $k => $value){
                $goods = \app\models\IntegralGoods::findOne(['id' => $value['goods_id']]);
                $goods_name[] = $goods->name;
            }
            $goods_name = implode('、', $goods_name);
        }else{
            $order = Order::findOne([
                'store_id' => $this->store_id,
                'order_no' => $this->orderNo,
                'is_delete' => 0,
            ]);
            $orderDetails = OrderDetail::find()->where(
                [
                    'order_id' => $order->id,
                ]
            )->asArray()->all();
            $goods_name = [];
            foreach ($orderDetails as $k => $value){
                $goods = \app\models\Goods::findOne(['id' => $value['goods_id']]);
                $goods_name[] = $goods->name;
            }
            $goods_name = implode('、', $goods_name);
        }

        if (!$order) {
            return [
                'code' => 1,
                'msg' => '主订单不存在'
            ];
        }
        if ($order->put_status == 3){
            return [
                'code' => 1,
                'msg' => '商品已被取出'
            ];
        }
        $cab = Cabinet::findOne(['cabinet_id' => $this->machineId]);
        if (!$cab){
            return [
                'code' => 1,
                'msg' => '云柜错误'
            ];
        }
        $cabInfo = $cab->address;


        //$content = '亲，您购买的'.$goods_name.'已存放到'.$cabInfo.'，提货码为'.$this->pickupCode.'，请及时取出。';
        $content['name'] = $goods_name;
        $content['address'] = $cabInfo;
        $content['code'] = $this->pickupCode;
        $content = json_encode($content, true);
        $subOrder->put_status = $this->status;
        if ($subOrder->save()) {
//            $subLists = OrderSub::find()->where(['origin_order_no' => $this->orderNo])->asArray()->all();
//            $sum = 0;
//            foreach ($subLists as $subList){
//                if ($subList['put_status'] != $this->status){
//                    $sum += 1;
//                }
//            }
//            if ($sum == 0){
//                $order->put_status = $this->status;
////                if (!$order->save()){
////                    $t->rollBack();
////                    return [
////                        'code' => 1,
////                        'msg' => '业务执行失败'
////                    ];
////                }
//            }
            //$printer_order = new PinterOrder($this->store_id, $order->id, 'confirm', 0);
            //$a = $this->sendSms($order->mobile, $this->store_id, $content);
            //$res = $printer_order->print_order();
            $wechatAccessToken = $this->getWechat()->getAccessToken();
            //$res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, 0, 100);
            return [
                'code' => 0,
                'msg' => '业务执行成功!'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '业务执行失败!'
            ];
        }

    }

    public function sendSms($mobile, $store_id, $content){
        $form = new Sms();
        //$content = '亲，您购买的正大鸡蛋、三全水饺已存放到上海市静安区456号云柜，提货码为46478912，请及时取出。';
        $form->sendSms($store_id, $content, $mobile, 'SMS_176942676');
    }
}
