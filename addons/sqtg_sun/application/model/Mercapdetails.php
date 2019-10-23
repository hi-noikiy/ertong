<?php
namespace app\model;

use app\base\model\Base;

class Mercapdetails extends Base
{
    public $order = 'create_time desc';//默认排序
    protected static function init()
    {
        parent::init();

//        账单变动
        self::beforeInsert(function ($model) {
            $store = new Store();
            $store->onMercapdetailsAdd($model);
        });
    }

    public function onOrderFinish(&$order){
        $stores = Ordergoods::where('order_id',$order->id)
            ->distinct('store_id')
            ->field('store_id')
            ->select();
        foreach ($stores as $store) {
            $store2 = Store::get($store->store_id);
            $data = [
                'type'=>1,
                'store_id'=>$store->store_id,
                'store_name'=>$store2->name,
                'money'=>Ordergoods::where('order_id',$order->id)
                    ->where('store_id',$store->store_id)
                    ->sum('amount'),
                'sign'=>1,
                'order_id'=>$order->id,
            ];
            $info = new Mercapdetails($data);
            $info->isUpdate(false)->allowField(true)->save();
        }
    }
    public function onOrdergoodsFinish(&$ordergoods){
        $store = Store::get($ordergoods->store_id);
        $data = [
            'type'=>1,
            'store_id'=>$ordergoods->store_id,
            'store_name'=>$store['name'],
            'money'=>$ordergoods->pay_amount - $ordergoods->share_amount - $ordergoods->distribution_money + $ordergoods->delivery_fee,
            'sign'=>1,
            'order_id'=>$ordergoods->id,
        ];
        $info = new Mercapdetails($data);
        $info->isUpdate(false)->allowField(true)->save();
    }
    public function onPingoodsFinish(&$ordergoods){
        $store = Store::get($ordergoods->store_id);
        $data = [
            'type'=>2,
            'store_id'=>$ordergoods->store_id,
            'store_name'=>$store['name'],
//            'money'=>$ordergoods->pay_amount - $ordergoods->share_amount - $ordergoods->distribution_money + $ordergoods->delivery_fee,
            'money'=>$ordergoods->order_amount-$ordergoods->share_amount,
            'sign'=>1,
            'order_id'=>$ordergoods->id,
        ];
        $info = new Mercapdetails($data);
        $info->isUpdate(false)->allowField(true)->save();
    }
}
