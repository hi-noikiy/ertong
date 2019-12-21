<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/23
 * Time: 9:29
 */

namespace app\modules\api\models\integralmall;

use app\models\Attr;
use app\models\AttrGroup;
use app\models\Cabinet;
use app\models\common\api\CommonOrder;
use app\models\common\CommonFormId;
use app\models\IntegralOrderSub;
use app\models\Model;
use app\models\MsOrderSub;
use app\models\Option;
use app\models\Order;
use app\models\PtOrderDetail;
use app\modules\api\models\cabinet\CabinetPlatForm;
use app\utils\PayNotify;
use app\modules\api\models\ApiModel;
use app\models\Store;
use app\models\Address;
use app\models\IntegralGoods;
use app\models\PostageRules;
use app\models\Shop;
use app\models\IntegralOrder;
use app\models\User;
use app\models\IntegralOrderDetail;
use app\models\FormId;
use app\models\Mch;
use app\utils\Sms;
use app\models\Register;
use Alipay\AlipayRequestFactory;

class OrderSubmitPreviewForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $goods_info;
    public $longitude;
    public $latitude;
    public $address_id;
    public $offline;
    public $content;
    public $address_name;
    public $address_mobile;
    public $shop_id;
    public $express_price;
    public $version;
    public $type;
    public $attr;
    public $order_id;
    public $user;
    public $integral;
    public $wechat;
    public $order;
    public $status;
    public $limit;
    public $page;
    public $formId;

    public $service_day;
    public $service_time;
    public $cabinet_id;

    public function rules()
    {
        return [
            [['goods_info', 'content', 'address_name', 'address_mobile', 'attr', 'formId', 'service_day', 'service_time'], 'string'],
            [['address_id', 'offline', 'shop_id', 'type', 'user_id', 'status', 'order_id','cabinet_id'], 'integer'],
            [['express_price'], 'number'],
//            [['address_mobile'],'match','pattern' =>Model::MOBILE_PATTERN , 'message'=>'手机号错误']
        ];
    }

    public function search()
    {
        $store = Store::findOne($this->store_id);
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->goods_info == 'undefined') {
            return [
                'code' => 0,
                'msg' => '商品信息错误：undefined。',
            ];
        }
        $goods_info = \Yii::$app->serializer->decode($this->goods_info);
        $goods_item = IntegralGoods::findOne(['id' => $goods_info->goods_id, 'is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1,]);
        if (!$goods_item) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架',
            ];
        }
        $attr_id_list = [];
        foreach ($goods_info->attr as $item) {
            $attr_id_list[] = $item['attr_id'];
        }
        $goods = [];
        $goods['attr'] = $goods_info->attr;
        $goods['num'] = 1;
        $goods['cover_pic'] = $goods_item['cover_pic'];
        $goods['name'] = $goods_item['name'];
        $attr_info = $goods_item->getAttrInfo($attr_id_list);
        $goods_item['integral'] = intval($goods_item['integral']);
        $attr_info['integral'] = intval($attr_info['integral']);
        if ($goods_item['use_attr'] == 1) {
            $total_price = $attr_info['price'];
            $integral = $attr_info['integral'];
            $goods['attr_price'] = $attr_info['price'];
            $goods['attr_integral'] = $attr_info['integral'];
        } else {
            $total_price = $goods_item['price'];
            $integral = $goods_item['integral'];
            $goods['attr_price'] = $goods_item['price'];
            $goods['attr_integral'] = $goods_item['integral'];
        }

        $address = Address::find()->select('id,name,mobile,province_id,province,city_id,city,district_id,district,detail,is_default')->where([
            'id' => $this->address_id,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
        ])->asArray()->one();
        if (!$address) {
            $address = Address::find()->select('id,name,mobile,province_id,province,city_id,city,district_id,district,detail,is_default')->where([
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
                'is_delete' => 0,
            ])->orderBy('is_default DESC,addtime DESC')->asArray()->one();
        }
        if ($store->send_type != 1) {
            $shop_list = $this->getShopList();
            $is_shop = Shop::find()->where(['is_default' => 1, 'store_id' => $this->store_id])->asArray()->one();
        } else {
            $shop_list = [];
            $is_shop = '';
        }
        if ($address) {
            $express_price = PostageRules::getExpressPrice($this->store_id, $address['city_id'], $goods_item, 1, $address['province_id']);
        } else {
            $express_price = 0;
        }
        return [
            'code' => 0,
            'data' => [
                'goods' => $goods,
                'address' => $address,
                'shop_list' => $shop_list,
                'is_shop' => $is_shop,
                'express_price' => $express_price,
                'send_type' => $store->send_type,
                'total_price' => $total_price,
                'integral' => intval($integral)
            ],
        ];
    }

    private function getShopList()
    {
        $list = Shop::find()->select(['address', 'mobile', 'id', 'name', 'longitude', 'latitude'])
            ->where(['store_id' => $this->store_id, 'is_delete' => 0])->asArray()->all();
        $distance = array();
        foreach ($list as $index => $item) {
            $list[$index]['distance'] = -1;
            if ($item['longitude'] && $this->longitude) {
                $from = [$this->longitude, $this->latitude];
                $to = [$item['longitude'], $item['latitude']];
                $list[$index]['distance'] = $this->get_distance($from, $to, false, 2);
            }
            $distance[] = $list[$index]['distance'];
        }
        array_multisort($distance, SORT_ASC, $list);
        $min = min(count($list), 30);
        $list_arr = array();
        foreach ($list as $index => $item) {
            if ($index <= $min) {
                $list[$index]['distance'] = $this->distance($item['distance']);
                array_push($list_arr, $list[$index]);
            }
        }
        return $list;
    }

    function get_distance($from, $to, $km = true, $decimal = 2)
    {
        sort($from);
        sort($to);
        $EARTH_RADIUS = 6370.996; // 地球半径系数

        $distance = $EARTH_RADIUS * 2 * asin(sqrt(pow(sin(($from[0] * pi() / 180 - $to[0] * pi() / 180) / 2), 2) + cos($from[0] * pi() / 180) * cos($to[0] * pi() / 180) * pow(sin(($from[1] * pi() / 180 - $to[1] * pi() / 180) / 2), 2))) * 1000;

        if ($km) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }

    private static function distance($distance)
    {
        if ($distance == -1) {
            return -1;
        }
        if ($distance > 1000) {
            $distance = round($distance / 1000, 2) . 'km';
        } else {
            $distance .= 'm';
        }
        return $distance;
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $res = CommonOrder::checkOrder([
            'mobile' => $this->address_mobile
        ]);
        if (empty($this->cabinet_id)){
            return [
                'code' => 1,
                'msg' => '下单时间不能为空',
            ];
        }
        if ($res['code'] === 1) {
            return $res;
        }

        if ($this->offline == 2) {
            $shop = Shop::findOne(['id' => $this->shop_id, 'store_id' => $this->store_id, 'is_delete' => 0]);
            if (!$shop) {
                return [
                    'code' => 1,
                    'msg' => '门店已关闭或不存在',
                ];
            }
        } else {
            $address = Address::findOne([
                'id' => $this->address_id,
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
            ]);
            if (!$address) {
//                return [
//                    'code' => 1,
//                    'msg' => '收货地址不存在',
//                ];
            }
            $option = Option::getList('mobile_verify', \Yii::$app->controller->store->id, 'admin', 1);
            if ($option['mobile_verify']) {
                if (!preg_match(Model::MOBILE_VERIFY, $address->mobile)) {
//                    return [
//                        'code' => 1,
//                        'msg' => '请输入正确的手机号'
//                    ];
                }
            }
        }
        $goods_info = \Yii::$app->serializer->decode($this->goods_info);
        $goods = IntegralGoods::findOne(['id' => $goods_info->goods_id, 'is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1]);
        $goods_list = IntegralGoods::find()->where(['id' => $goods_info->goods_id, 'is_delete' => 0, 'store_id' => $this->store_id, 'status' => 1])->all();
        $user = User::findOne(['id' => $this->user->id, 'store_id' => $this->store_id]);
        if (!$user) {
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在或已下架',
            ];
        }
        $count = IntegralOrder::find()->where(['store_id' => $this->store_id, 'user_id' => $user->id, 'goods_id' => $goods->id, 'is_pay' => 1])->count();
        if ($count >= $goods->user_num) {
            return [
                'code' => 1,
                'msg' => '超出购买上限'
            ];
        }

        $attr_id_list = [];
        foreach ($goods_info->attr as $item) {
            $attr_id_list[] = $item['attr_id'];
        }
        $attr_info = $goods->getAttrInfo($attr_id_list);
        $goods_num = $attr_info['num'];
        $goods_pic = $attr_info['pic'] ? $attr_info['pic'] : $goods->cover_pic;
        $goods_no = $attr_info['no'];
        if ($goods['use_attr'] == 1) {
            $total_price = $attr_info['price'];
            $integral = $attr_info['integral'];
        } else {
            $total_price = $goods['price'];
            $integral = $goods['integral'];
        }
        if ($address) {
            $express_price = PostageRules::getExpressPrice($this->store_id, $address['city_id'], $goods, 1, $address['province_id']);
        } else {
            $express_price = 0;
        }
        if ($total_price > 0 || $express_price > 0) {
            $this->type = 0;
        } else {
            $this->type = 1;
        }
        $integral = intval($integral);
        if ($user->integral < $integral) {
            return [
                'code' => 1,
                'msg' => '积分不足，无法兑换'
            ];
        }

        $attr_list = Attr::find()->alias('a')
            ->select('a.id attr_id,ag.attr_group_name,a.attr_name')
            ->leftJoin(['ag' => AttrGroup::tableName()], 'a.attr_group_id=ag.id')
            ->where(['a.id' => $attr_id_list])
            ->asArray()->all();

        foreach ($attr_list as &$i) {
            $i['no'] = isset($goods_no) ? $goods_no : 0;
        }
        unset($i);

        if ($goods_num <= 0) {
            return [
                'code' => 1,
                'msg' => '所选商品库存不足',
            ];
        }
        $cabinet = Cabinet::findOne([
            'id' => $this->cabinet_id,
            'store_id' => $this->store_id,
        ]);
        $t = \Yii::$app->db->beginTransaction();
        $order = new IntegralOrder();
        $order->store_id = $this->store_id;
        $order->goods_id = $goods->id;
        $order->user_id = $this->user->id;
        $order->order_no = $this->getOrderNo();

        $order->cabinet_id = $this->cabinet_id;
        $order->service_day = $this->service_day;
        $order->service_time = $this->service_time;

        if ($this->offline == 2) {
            $order->name = $user->nickname;
            $order->mobile = $user->binding;
            $order->shop_id = $shop->id;
        } else {
            $order->name = $user->nickname;
            $order->mobile = $user->binding;
            $order->address = $cabinet->address;
            $order->address_data = json_encode([
                'province' => $cabinet->province,
                'city' => $cabinet->city,
                'district' => '',
                'detail' => $cabinet->address,
            ], JSON_UNESCAPED_UNICODE);
        }
        $order->remark = $this->content ? $this->content : '';
        $order->addtime = time();
        $order->is_offline = $this->offline;
        $order->integral = $integral;
        $order->version = $this->version;
        if ($this->offline == 2) {
            $total_price = $total_price;
        } else {
            $total_price = $total_price + $this->express_price;
        }
        if ($this->type == 1) {
            $order->is_pay = 1;
            $order->pay_type = 1;
            $order->pay_time = time();
        } else {
            $order->is_pay = 0;
            $order->pay_type = 0;
            $order->total_price = $total_price;
            $order->pay_price = $total_price;
            $order->express_price = $this->express_price ? $this->express_price : '0';
        }
        if ($order->save()) {
            $this->order = $order;
            //记录prepay_id发送模板消息用到
            $res = CommonFormId::save([
                [
                    'form_id' => $this->formId
                ]
            ]);
            //减少库存
            $goods->numSub($attr_id_list, 1);
            //根据商品
            $cabGroup = self::array_group_by($goods_list,'storage_type');
            $dev = [];
            $allTotal = 0;
            foreach ($cabGroup as $k=>$goodsLists){
                $subPrice = 0.00;
                $order_sub = new IntegralOrderSub();
                $goods_total_pay_price = $order->pay_price - $order->express_price;
                $goods_total_price = 0.00;
                foreach ($goods_list as $goods) {
                    $allTotal += 1;
                    $goods_total_price += $goods->price;
                }
                $total = 0;
                $devGoodsName = [];
                //var_dump($goodsLists);die;
                foreach ($goodsLists as $goods){
                    $subPrice += doubleval(sprintf('%.2f', $goods_total_pay_price * $goods->price / $goods_total_price));
                    $devGoodsName[] = $goods->name;
                    $total += $goods->num;

                }
                $subOrderNo = $this->getOrderNo();
                $order_sub->store_id = $this->store_id;
                $order_sub->user_id = $this->user->id;
                $order_sub->order_no = $subOrderNo;
                $order_sub->cabinet_id = $this->cabinet_id;
                $order_sub->service_day = $this->service_day;
                $order_sub->service_time = $this->service_time;
                $order_sub->pay_price = $subPrice;
                $order_sub->order_id = $order->id;
                $order_sub->total_price = $subPrice;
                $order_sub->addtime = time();
                if ($this->offline == 0) {
                    $order_sub->address = $cabinet->address;
                    $order_sub->mobile = $user->binding;
                    $order_sub->name = $user->nickname;
                    $order_sub->address_data = json_encode([
                        'province' => $cabinet->province,
                        'city' => $cabinet->city,
                        'district' => $address->district,
                        'detail' => $cabinet->address,
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    $order_sub->name = $user->nickname;
                    $order_sub->mobile = $user->binding;
                    //$order_sub->shop_id = $this->shop_id;
                }
                $order_sub->first_price = 0;
                $order_sub->second_price = 0;
                $order_sub->third_price = 0;
                $order_sub->content = $this->content;
                $order_sub->is_offline = $this->offline;
                $order_sub->version = $this->version;
                $order_sub->origin_order_no = $order->order_no;
                $order_sub->discount = 1;
                if (!$order_sub->save()){
                    $t->rollBack();
                    return [
                        'code' => 1,
                        'msg' => '订单提交失败，请稍后再重试!',
                    ];
                }
                $coolType = 0;
                if ($k == 1){
                    $coolType = 0;
                }
                if ($k == 2){
                    $coolType = 1;
                }
                if ($k == 3){
                    $coolType = 2;
                }
                //$emptyData = $this->queryEmptyCell($cabinet->cabinet_id, $coolType);
                $dev[$k]['deliverNo'] = $subOrderNo;
                $dev[$k]['coolType'] = $coolType;
                $goodStr = implode('|', $devGoodsName);
                $dev[$k]['goods'] = $goodStr;
                $dev[$k]['quantity'] = $total;
                foreach ($goodsLists as $goods) {
                    $order_detail = new IntegralOrderDetail();
                    $order_detail->order_id = $order->id;
                    $order_detail->goods_id = $goods->id;
                    $order_detail->num = 1;
                    $order_detail->total_price = $total_price;
                    $order_detail->addtime = time();
                    $order_detail->is_delete = 0;
                    $order_detail->attr = \Yii::$app->serializer->encode($attr_list);
                    $order_detail->pic = $goods_pic;
                    $order_detail->pay_integral = $integral;
                    $order_detail->user_id = $this->user->id;
                    $order_detail->store_id = $this->store_id;
                    $order_detail->goods_name = $goods->name;
                    $order_detail->order_sub_id = $order_sub->id;

                    if ($order_detail->save()) {
                        $re = $this->createOrder($order->order_no, $cabinet->cabinet_id, $dev, $allTotal);
                        if ($re['code'] != 0){
                            $t->rollBack();
                            return [
                                'code' => 1,
                                'msg' => $re['message'],
                            ];
                        }
                        if ($this->type == 1) {
                            $user->integral -= $integral;
                            $register = new Register();
                            $register->store_id = $this->store_id;
                            $register->user_id = $user->id;
                            $register->register_time = '..';
                            $register->addtime = time();
                            $register->continuation = 0;
                            $register->type = 11;
                            $register->integral = '-' . $integral;
                            $register->order_id = $order->id;
                            $register->save();
                            if ($user->save()) {
                                $t->commit();
                                return [
                                    'code' => 0,
                                    'type' => 1,
                                    'msg' => '兑换成功'
                                ];

                            } else {
                                $t->rollBack();
                                return [
                                    'code' => 1,
                                    'msg' => $user->getErrors()
                                ];
                            }
                        } else {
                            $t->commit();
                            $this->wechat = $this->getWechat();
                            $body = "充值";

                            if (\Yii::$app->fromAlipayApp()) {
                                $request = AlipayRequestFactory::create('alipay.trade.create', [
                                    'notify_url' => pay_notify_url('/in-alipay-notify.php'),
                                    'biz_content' => [
                                        'body' => $body, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                                        'subject' => $body, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
                                        'out_trade_no' => $this->order->order_no, // 商户网站唯一订单号
                                        'total_amount' => $this->order->pay_price, // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
                                        'buyer_id' => $this->user->wechat_open_id, // 购买人的支付宝用户 ID

                                    ],
                                ]);

                                $aop = $this->getAlipay();
                                $res = $aop->execute($request)->getData();

                                return [
                                    'code' => 0,
                                    'data' => $res,
                                    'res' => $res,
                                    'type' => 2,
                                ];
                            }

                            $res = $this->unifiedOrder($body);
                            if (isset($res['code']) && $res['code'] == 1) {
                                return $res;
                            }
                            //记录prepay_id发送模板消息用到
                            FormId::addFormId([
                                'store_id' => $this->store_id,
                                'user_id' => $this->user->id,
                                'wechat_open_id' => $this->user->wechat_open_id,
                                'form_id' => $res['prepay_id'],
                                'type' => 'prepay_id',
                                'order_no' => $this->order->order_no,
                            ]);
                            $pay_data = [
                                'appId' => $this->wechat->appId,
                                'timeStamp' => '' . time(),
                                'nonceStr' => md5(uniqid()),
                                'package' => 'prepay_id=' . $res['prepay_id'],
                                'signType' => 'MD5',
                            ];
                            $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
                            return [
                                'code' => 0,
                                'data' => (object)$pay_data,
                                'res' => $res,
                                'type' => 2,
                            ];
                        }
                    }
                }

            }

        } else {
            return $this->getErrorResponse($order);
        }
    }

    public function getOrderNo()
    {
        $store_id = empty($this->store_id) ? 0 : $this->store_id;
        $order_no = null;
        while (true) {
            $order_no = 'G' . date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = IntegralOrder::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no) {
                break;
            }
        }
        return $order_no;
    }

    private function unifiedOrder($body)
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' => $body,
            'out_trade_no' => $this->order->order_no,
            'total_fee' => $this->order->pay_price * 100,
            'notify_url' => pay_notify_url('/in-pay-notify.php'),
            'trade_type' => 'JSAPI',
            'openid' => $this->user->wechat_open_id,
        ]);
        if (!$res) {
            return [
                'code' => 1,
                'msg' => '支付失败',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '支付失败，' . (isset($res['return_msg']) ? $res['return_msg'] : ''),
                'res' => $res,
            ];
        }
        if ($res['result_code'] != 'SUCCESS') {
            if ($res['err_code'] == 'INVALID_REQUEST') {//商户订单号重复
                $this->order->order_no = $this->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($body);
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败，' . (isset($res['err_code_des']) ? $res['err_code_des'] : ''),
                    'res' => $res,
                ];
            }
        }
        return $res;
    }

    public function orderList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = IntegralOrder::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'user_id' => $this->user_id
            ])->with(['user' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                ]);
            }])->with(['detail' => function ($query) {
                $query->where([
                    'is_delete' => 0,
                ]);
            }]);

//        if ($this->status == 0) {//待付款
//            $query->andWhere([
//                'is_pay' => 0,
//            ]);
//
//        }
        if ($this->status === '0') {//待付款
            $query->andWhere([
                'is_pay' => 0,
                'is_cancel' => 0
            ]);
        }
        if ($this->status == 1) {//备货中
            $query->andWhere([
                'is_send' => 0,
                'is_order_confirm' => 1,
                'is_cancel' => 0
            ])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]]);
        }
        if ($this->status == 2) {//已发货
            $query->andWhere([
                'is_send' => 1,
                'put_status' => 1,
                'is_cancel' => 0
            ]);
        }
        if ($this->status == 3){//待自提
            $query->andWhere([
                'is_send' => 1,
                'put_status' => 2,
                'is_cancel' => 0
            ]);
        }
        if ($this->status == 4) {//已完成
            $query->andWhere([
                'put_status' => 3,
                'is_cancel' => 0,
                'is_comment' => 1
            ]);
        }
        if ($this->status == 6){
            $query->andWhere([
                'is_cancel' => 1,
            ]);
        }
//        if ($this->status == 5) {//售后订单
//            return $this->getRefundList();
//        }
        if ($this->status == 7) {//待确认
            $query->andWhere([
                'is_send' => 0,
                'is_pay' => 1,
                'is_order_confirm' => 0,
                'is_cancel' => 0,
                'put_status' => 1
            ]);
        }

        if ($this->status == 8){//待评价
            $query->andWhere([
                'put_status' => 3,
                'is_cancel' => 0,
                'is_confirm' => 0
            ]);
        }
        $list = $query
            ->orderBy('addtime DESC')
            ->asArray()
            ->all();
        foreach ($list as $key => &$value) {
            if ($value['mch_id'] == 0) {
                $value['mch'] = [
                    'id' => 0,
                    'name' => '平台自营',
                    'logo' => '',
                ];
            } else {
                $mch = Mch::findOne($value['mch_id']);
                $value['mch'] = [
                    'id' => $mch->id,
                    'name' => $mch->name,
                    'logo' => $mch->logo,
                ];
            }
            $value['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
            $value['detail']['attr'] = json_decode($value['detail']['attr']);
            $order_status = null;
            $order = IntegralOrder::findOne(['id' => $value['id']]);
            if ($order->is_pay == 0 && $order->is_cancel!=1) {
                $status = '待付款';
                $order_status = 0;
            } elseif ($order->is_pay == 1 && $order->is_cancel == 0 && $order->is_send == 0 && $order->is_order_confirm == 1 && $order->put_status == 1) {
                $status = '备货中';
                $order_status = 1;
            } elseif ($order->is_send == 1 && $order->is_cancel == 0 &&  $order->is_confirm == 0 && $order->put_status == 1) {
                $status = '已发货';
                $order_status = 2;
            } elseif ($order->is_send == 1 && $order->is_cancel == 0 && $order->put_status == 2){
                $status = '待自提';
                $order_status = 3;
            } elseif ($order->put_status == 3 && $order->is_cancel == 0 && $order->is_comment == 1) {
                $status = '已完成';
                $order_status = 4;
            }elseif ($order->is_cancel == 1){
                $status = '已取消';
                $order_status = 6;
            }elseif ($order->is_pay == 1 && $order->is_order_confirm == 0 && $order->is_send == 0 && $order->is_cancel == 0){
                $status = '待确认';
                $order_status = 7;
            }else if ($order->put_status == 3 && $order->is_cancel == 0 && $order->is_comment == 0){
                $status = '待评价';
                $order_status = 8;
            }
            $list[$key]['status'] = $status;
            $list[$key]['order_status'] = $order_status;
            $list[$key]['put_code'] = $order->put_code;
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list
            ]
        ];
    }

    public function revoke()
    {
        $order = IntegralOrder::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_send' => 0,
            'is_delete' => 0,
            'is_cancel' => 0
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
            ];
        }
        if ($order->is_pay == 1) {
            $order->apply_delete = 1;
            Sms::send_refund($order->store_id, $order->order_no);
            if ($order->save()) {
                return [
                    'code' => 0,
                    'msg' => '订单取消申请已提交，请等候管理员审核'
                ];
            } else {
                return $this->getErrorResponse($order);
            }
        }
        $order_detail = IntegralOrderDetail::find()->where(['order_id' => $order->id, 'is_delete' => 0])->one();
        $order_detail_attr = json_decode($order_detail->attr, true);
        foreach ($order_detail_attr as $k => &$v) {
            unset($v->attr_group_id);
            unset($v->attr_group_name);
        }
        $goods = IntegralGoods::findOne($order_detail->goods_id);
        $order->is_delete = 1;
        $order_detail->is_delete = 1;
        //        商品库存恢复
        $attr_id_list = [];
        foreach ($order_detail_attr as $value) {
            $attr_id_list[] = $value['attr_id'];
        }
        $goods->numAdd($attr_id_list, 1);
        //     删除订单
        if ($order->save()) {
            $cabinetPlatform = new CabinetPlatForm(null);
            $re = $cabinetPlatform->cancelOrder($order->order_no);
            if ($re['code'] !=0){
                //$t->rollBack();
                return [
                    'code' => 1,
                    'msg' => $re['message']
                ];
            }
            //删除订单详情
            $order_detail->save();
            return [
                'code' => 0,
                'msg' => '订单取消成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $this->getErrorResponse($order)
            ];
        }
    }


    public function orderPay()
    {
        $order = IntegralOrder::findOne(['id' => $this->order_id, 'user_id' => $this->user->id, 'store_id' => $this->store_id, 'is_pay' => 0]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '成功'
            ];
        }
        $count = IntegralOrder::find()->where(['store_id' => $this->store_id, 'user_id' => $this->user->id, 'goods_id' => $order->goods_id, 'is_pay' => 1])->count();
        $goods = IntegralGoods::findOne(['store_id' => $this->store_id, 'id' => $order->goods_id]);
        if (!$goods) {
            return [
                'code' => 1,
                'msg' => '商品不存在'
            ];
        }
        if ($count >= $goods->user_num) {
            return [
                'code' => 1,
                'msg' => '超出购买上限'
            ];
        }
        $this->wechat = $this->getWechat();
        $this->order = $order;
        $body = "充值";
        $res = $this->unifiedOrder($body);

        if (\Yii::$app->fromAlipayApp()) {
            $request = AlipayRequestFactory::create('alipay.trade.create', [
                'notify_url' => pay_notify_url('/in-alipay-notify.php'),
                'biz_content' => [
                    'body' => $body, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                    'subject' => $body, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
                    'out_trade_no' => $this->order->order_no, // 商户网站唯一订单号
                    'total_amount' => $this->order->pay_price, // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
                    'buyer_id' => $this->user->wechat_open_id, // 购买人的支付宝用户 ID

                ],
            ]);

            $aop = $this->getAlipay();
            $res = $aop->execute($request)->getData();

            return [
                'code' => 0,
                'data' => $res,
                'res' => $res,
                'type' => 3,
            ];
        }

        if (isset($res['code']) && $res['code'] == 1) {
            return $res;
        }
        //记录prepay_id发送模板消息用到
        FormId::addFormId([
            'store_id' => $this->store_id,
            'user_id' => $this->user->id,
            'wechat_open_id' => $this->user->wechat_open_id,
            'form_id' => $res['prepay_id'],
            'type' => 'prepay_id',
            'order_no' => $this->order->order_no,
        ]);
        $pay_data = [
            'appId' => $this->wechat->appId,
            'timeStamp' => '' . time(),
            'nonceStr' => md5(uniqid()),
            'package' => 'prepay_id=' . $res['prepay_id'],
            'signType' => 'MD5',
        ];
        $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
        return [
            'code' => 0,
            'data' => (object)$pay_data,
            'res' => $res,
            'type' => 3,
        ];
    }

    public function confirm()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = IntegralOrder::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_send' => 1,
            'is_delete' => 0,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
            ];
        }
        $order->is_confirm = 1;
        $order->confirm_time = time();
        if ($order->save()) {
            return [
                'code' => 0,
                'msg' => '已确认收货'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '确认收货失败'
            ];
        }
    }

    public static function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value->$key][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }
    /**
     * 操作云柜
     */
    protected function queryEmptyCell($machineId, $coolType, $timestamp = null, $sign = null){
        $cabPlatform = new CabinetPlatForm($machineId);

        $result = $cabPlatform->queryEmptyCell($coolType);
        return $result;
    }

    /**
     * @param $orderNo
     * @param $machineId
     * @param $delivers
     * @param $total
     * @return mixed
     * @desc 创建订单
     */
    protected function createOrder($orderNo, $machineId, $delivers, $total){
        $cabPlatform = new CabinetPlatForm($machineId);
        $delivers = array_values($delivers);
        $result = $cabPlatform->createOrder($orderNo,$delivers,$total);
        return $result;
    }
}
