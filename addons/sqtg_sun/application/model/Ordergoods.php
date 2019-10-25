<?php
namespace app\model;

use app\base\model\Base;
use think\Exception;
use think\Hook;
use wx\Refund;

class Ordergoods extends Base
{
    public $order = 'create_time desc';//默认排序
    protected static function init()
    {
        parent::init();

        self::beforeUpdate(function ($model) {
            $old_info = self::get($model->id);
//            退款申请通过
            if ($old_info['check_state'] == 1 && $model['check_state'] == 2){
                $system = System::get_curr();
                $payrecord = Payrecord::get(['source_id'=>$model['order_id'],'source_type'=>'Order','callback_time'=>['<>','null']]);
                $model['refund_no']= date("YmdHis") .rand(11111, 99999);
                $model['state']= 6;

                global $_W;
                $certpath = IA_ROOT . '/addons/sqtg_sun/cert/apiclient_cert_'.$_W['uniacid'].'.pem';
                $keypath = IA_ROOT . '/addons/sqtg_sun/cert/apiclient_key_'.$_W['uniacid'].'.pem';

                $order = Order::get($model['order_id']);
                $total_fee = intval(($order['pay_amount']+$order['delivery_fee']) * 100 . '');
                $refund_fee = intval(($model['pay_amount']+$model['delivery_fee']) * 100 . '');

                $refund = new Refund(
                    $system['appid'],$system['mchid'],$system['wxkey']
                    ,$payrecord['no'],$model['refund_no']
                    ,$total_fee,$refund_fee
                    ,$certpath,$keypath);

                $ret = $refund->run();
                if ($ret['result_code'] != 'SUCCESS') {
                    error_json($ret['err_code_des']);
                }
                Hook::listen('on_ordergoods_refund',$model);
            }else if($old_info['check_state'] == 1 && $model['check_state'] == 3){
                Hook::listen('on_ordergoods_refunderror',$model);
            }

//        开始配送
            if ($old_info['state'] == 2 && $model['state'] == 3) {
                $order = new Order();
                $order->onOrdergoodsSend($model);
//        配送类订单，收货
            } else if ($old_info['state'] == 3 && $model['state'] == 5) {
                Hook::listen('on_ordergoods_receive2', $model);
//        团长收货
            } else if ($old_info['state'] == 3 && $model['state'] == 4) {
                Hook::listen('on_ordergoods_receive', $model);
//        团长核销
            } else if ($old_info['state'] == 4 && $model['state'] == 5) {
                Hook::listen('on_ordergoods_confirm', $model);
            }
        });

        self::afterInsert(function ($model){
            Hook::listen('on_ordergoods_added', $model);
        });
    }

    public function onOrderPay($order)
    {
        $list = Ordergoods::where('order_id', $order->id)->select();
        foreach ($list as $item) {
            $goods = Goods::get($item->goods_id);
            $item->batch_no = $goods->batch_no;

            $item->state = 2;
//            $item->coupon_money = $order->coupon_money * $item->amount / $order->amount;
//            $item->pay_amount = $order->pay_amount * $item->amount / $order->amount;
            $item->save();
        }
    }

    public function onDeliveryordergoodsAdd($goods)
    {
        $num = $goods->num;
        $where = [
            'state' => 2,
            'store_id' => $goods->store_id,
            'goods_id' => $goods->goods_id,
            'batch_no' => $goods->batch_no,
        ];
        if ($goods->attr_ids) {
            $where['attr_ids'] = $goods->attr_ids;
        }

        $list = Ordergoods::where('leader_id', $goods->leader_id)
            ->where($where)
            ->limit($goods->num)
            ->order('create_time')
            ->select();

        foreach ($list as $item) {
            if ($item->num > $num) {
                continue;
            }
            $item->state = 3;
            $item->save();
            $num -= $item->num;
        }
    }

    public function orderby($field, $order = null){
        parent::order($field,$order);
    }
    public function order(){
        return $this->hasOne('Order','id','order_id')->bind([
            'order_no',
            'order_time'=>'create_time',
            'order_pay_amount'=>'pay_amount',
            'order_coupon_money'=>'coupon_money',
        ]);
    }
    public function user(){
        return $this->hasOne('User','id','user_id')->bind([
            'name',
            'img',
        ]);
    }
    public function leader(){
        return $this->hasOne('Leader','id','leader_id')->bind([
            'leader_name'=>'name',
            'leader_tel'=>'tel',
            'leader_address'=>'address',
        ]);
    }
    public function onOrderCancel($order){
        $ordergoodses = Ordergoods::where('order_id',$order->id)->select();
        foreach ($ordergoodses as $ordergoods) {
            $ordergoods->state = 6;
            $ordergoods->save();
        }
    }
    public function distributionrecords(){
        return $this->hasMany('Distributionrecord','goods_id','id');
    }
    public static function applyRefund($id){
        $ordergoods = self::get($id);
        $ordergoods->check_state = 1;
        $ordergoods->apply_time = time();
        return $ordergoods->save();
    }
    public static function cancelRefund($id){
        $ordergoods = self::get($id);
        if($ordergoods->check_state == 2){
            throw new Exception('您的申请已通过，不能取消');
        }
        $ordergoods->check_state = 0;
        $ordergoods->apply_time = null;
        return $ordergoods->save();
    }
    public function getApplyTimeAttr($value)
    {
        return $value?date('Y-m-d H:i:d',$value):'';
    }
}