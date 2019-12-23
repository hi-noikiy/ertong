<?php


namespace app\hejiang\task;


use app\models\Order;
use app\modules\api\models\cabinet\CabinetPlatForm;
use yii\base\BaseObject;

class OrderExpireTask extends BaseObject implements \yii\queue\JobInterface
{
    public $orderId;
    public function execute($queue)
    {
        echo '开始执行队列'.$this->orderId.PHP_EOL;
        $order = Order::findOne(
            [
                'id' => $this->orderId,
            ]
        );
        $order->is_cancel = 1;

        //if ($order->save()){
            $cab = new CabinetPlatForm(null);
            $re = $cab->cancelOrder($order->order_no);
        if ($re['code'] == 0){
            echo '取消云柜订单成功-'.$order->order_no;
        }else{
            echo '取消云柜订单失败'.$re['message'].PHP_EOL;
        }
        //}
        $order->save();
        return true;
    }
}