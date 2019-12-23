<?php


namespace app\hejiang\task;


use app\models\IntegralOrder;
use app\models\MsOrder;
use app\models\Order;
use app\models\PtOrder;
use app\modules\api\models\cabinet\CabinetPlatForm;
use yii\base\BaseObject;

class OrderExpireTask extends BaseObject implements \yii\queue\JobInterface
{
    public $orderId;
    public $type;
    public function execute($queue)
    {
        echo date('Y-m-d H:i:s').'开始执行队列'.$this->orderId.PHP_EOL;
        if ($this->type == 'Nor'){
            $order = Order::findOne(
                [
                    'id' => $this->orderId,
                    'is_cancel' => 0,
                    'is_pay' => 0

                ]
            );
        }elseif ($this->type == 'M'){
            $order = MsOrder::findOne(
                [
                    'id' => $this->orderId,
                    'is_cancel' => 0,
                    'is_pay' => 0

                ]
            );
        }elseif ($this->type == 'P'){
            $order = PtOrder::findOne(
                [
                    'id' => $this->orderId,
                    'is_cancel' => 0,
                    'is_pay' => 0

                ]
            );
        }elseif ($this->type == 'G'){
            $order = IntegralOrder::findOne(
                [
                    'id' => $this->orderId,
                    'is_cancel' => 0,
                    'is_pay' => 0

                ]
            );
        }

        $order->is_cancel = 1;

        //if ($order->save()){
            $cab = new CabinetPlatForm(null);
            $re = $cab->cancelOrder($order->order_no);
        if ($re['code'] == 0){
            echo date('Y-m-d H:i:s').'取消云柜订单成功-'.$order->order_no.PHP_EOL;
        }else{
            echo date('Y-m-d H:i:s').'取消云柜订单失败'.$re['message'].PHP_EOL;
        }
        //}
        $order->save();
        return true;
    }
}