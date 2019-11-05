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
use app\models\OrderDetail;
use app\utils\PinterOrder;
use app\models\Level;
use app\models\Order;
use app\models\PrinterSetting;
use app\models\User;
use app\utils\Sms;
use app\utils\TaskCreate;

class OrderSelfMentionForm extends OrderForm
{
    public $store_id;
    public $user_id;
    public $order_id;
    public $orderNo;
    public $pickupCode;
    public $machineId;

    public function rules()
    {
        return [
            [['orderNo', 'pickupCode', 'machineId'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = Order::findOne([
            'store_id' => $this->store_id,
            'order_no' => $this->orderNo,
            'is_delete' => 0,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
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
        $cab = Cabinet::findOne(['id' => $order->cabinet_id]);
        if ($this->machineId != $cab->cabinet_id){
            return
            [
                'code' => 1,
                'msg' => '云柜错误'
            ];
        }
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
        //$goods_name = implode('、', $goods_name);
        //$content = '亲，您购买的'.$goods_name.'已存放到'.$cabInfo.'，提货码为'.$this->pickupCode.'，请及时取出。';
        $order->put_status = 3;

        if ($order->save()) {
            $printer_order = new PinterOrder($this->store_id, $order->id, 'confirm', 0);
            //$a = $this->sendSms($order->mobile, $this->store_id, $content);
            $res = $printer_order->print_order();
            $wechatAccessToken = $this->getWechat()->getAccessToken();
           //$res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, 0, 100);
            return [
                'code' => 0,
                'msg' => '已取出'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '取出失败'
            ];
        }

    }
}
