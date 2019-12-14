<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/10
 * Time: 22:53
 */

namespace app\modules\mch\models;

use app\models\Express;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\Cabinet;
use app\models\RefundAddress;
class OrderRefundDetailForm extends MchModel
{
    public $store_id;
    public $order_refund_id;

    public function rules()
    {
        return [
            [['order_refund_id'], 'required'],
        ];
    }

    public function search()
    {
        $order_refund = OrderRefund::find()->alias('or')
            ->leftJoin(['od' => OrderDetail::tableName()], 'or.order_detail_id=od.id')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->leftJoin(['r' => RefundAddress::tableName()],'or.address_id=r.id')
            ->leftJoin(['o' => Order::tableName()], 'or.order_id=o.id')
            ->leftJoin(['c' => Cabinet::tableName()], 'o.cabinet_id=c.id')
            ->where([
                'or.id' => $this->order_refund_id,
                'or.is_delete' => 0,
            ])
            ->select('or.id order_refund_id,or.order_refund_no,or.order_id,od.num,od.total_price,od.attr,or.desc refund_desc,or.type refund_type,or.status refund_status,or.refuse_desc,or.pic_list refund_pic_list,or.refund_price,or.is_agree,or.is_user_send,or.user_send_express,or.user_send_express_no,r.name as re_name,r.mobile as re_mobile,r.address as re_address,o.order_no, o.addtime order_time, o.name username, o.mobile, o.pay_price, o.express_price, o.express_price_1, o.discount,c.province,c.city,c.address as caddress')
            ->asArray()->one();
        if (!$order_refund) {
            return [
                'code' => 1,
                'msg' => '售后单不存在'
            ];
        }
        $form = new OrderListForm();
        $goods_list = $form->getOrderGoodsList($order_refund['order_id']);
        
        $order_refund['goods_pic'] = Goods::getGoodsPicStatic($order_refund['goods_id'])->pic_url;
        $order_refund['attr'] = json_decode($order_refund['attr']);
        $order_refund['refund_pic_list'] = json_decode($order_refund['refund_pic_list']);
        $order_refund['express_list'] = Express::getExpressList();
        $order_refund['order_refund_status_bg'] = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/order-refund-status-bg.png';
        // return [
        //     'code' => 0,
        //     'msg' => 'success',
        //     'data' => $order_refund,
        // ];

        return [
            'list' => $order_refund,
            'goods_list' => $goods_list,
        ];


    }
    /*public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = OrderRefund::find()->alias('or')
            ->leftJoin(['o' => Order::tableName()], 'or.order_id=o.id')
            ->leftJoin([
                'od' => OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')->select('od.id,od.total_price,od.goods_id,od.attr,od.num,g.name,g.cover_pic')
            ], 'or.order_detail_id=od.id')
            ->where([
                'or.id' => $this->order_refund_id,
                'or.is_delete' => 0,
            ]);
        $item = $query
            ->select([
                'o.order_no', 'o.addtime order_time', 'o.name username', 'o.mobile', 'o.pay_price',
                'od.name', 'od.cover_pic', 'od.attr', 'od.num', 'od.total_price',
                'or.id', 'or.order_refund_no', 'or.refund_price', 'or.addtime order_refund_time', 'or.desc', 'or.type', 'or.pic_list', 'or.status', 'or.refuse_desc',
            ])
            ->asArray()->one();
        if (!$item) {
            return [
                'code' => 1,
                'msg' => '订单不存在。',
            ];
        }
        $item['refund_order'] = true;
        $item['attr'] = json_decode($item['attr'], true);
        $item['pic_list'] = json_decode($item['pic_list'], true);
        $item['order_time'] = date('Y-m-d H:i', $item['order_time']);
        $item['order_refund_time'] = date('Y-m-d H:i', $item['order_refund_time']);
        $item['refund_type'] = $item['type'] == 1 ? '退货退款' : '换货';
        // $item['status_text'] = self::$status_text_list[$item['status']];
        return [
            'code' => 0,
            'data' => $item,
        ];
    }*/
}
