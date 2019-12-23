<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 14:34
 */

namespace app\modules\mch\models;

use app\models\common\admin\order\CommonOrderSearch;
use app\models\common\CommonGoods;
use app\models\Goods;
use app\models\Order;
use app\models\Cabinet;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\Shop;
use app\models\User;
use app\modules\mch\extensions\Export;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\models\GoodsPic;

class OrderListForm extends MchModel
{
    public $store_id;
    public $user_id;
    public $keyword;
    public $status;
    public $is_recycle;
    public $page;
    public $limit;

    public $flag;//是否导出
    public $is_offline;
    public $clerk_id;
    public $parent_id;
    public $shop_id;

    public $date_start;
    public $date_end;
    public $express_type;
    public $keyword_1;
    public $seller_comments;

    public $fields;
    public $type;

    public $platform;//所属平台

    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['status', 'is_recycle', 'page', 'limit', 'user_id', 'is_offline', 'clerk_id', 'shop_id', 'keyword_1', 'platform'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page',], 'default', 'value' => 1],
            [['flag', 'date_start', 'date_end', 'express_type'], 'trim'],
            [['flag'], 'default', 'value' => 'no'],
            [['seller_comments'], 'string'],
            [['fields'], 'safe']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Order::find()->alias('o')->where([
            'o.store_id' => $this->store_id,
            'o.mch_id' => 0
        ])->leftJoin(['u' => User::tableName()], 'u.id = o.user_id')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=o.id')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')->groupBy('o.id')
            ->leftJoin(['c' => Cabinet::tableName()], 'o.cabinet_id=c.id');

        switch ($this->status) {
            case 0:
                $query->andWhere(['o.is_pay' => 0]);//is_pay=0 && is_cancal!=1   未付款
                break;
            case 1:
                $query->andWhere([
                    'o.is_send' => 0,'o.is_order_confirm' => 1,'o.is_cancel'=>0
                ])->andWhere(['or', ['o.is_pay' => 1]]);//is_pay=1 && is_send=0  备货中
                break;
            case 2:
                $query->andWhere([
                    'o.is_send' => 1,
                    'o.put_status' => 1,
                    'o.is_cancel' => 0,
                ])->andWhere(['or', ['o.is_pay' => 1]]);//is_send=1 && is_confirm=0   配送中
                break;
            case 3:
                $query->andWhere([
                    'o.is_send' => 1,
                    'o.is_cancel'=>0,
                    'o.put_status'=>3,
                    'o.is_comment'=>1,
                ])->andWhere(['or', ['o.is_pay' => 1]]);//is_confirm=1   已完成
                break;
            case 4:
                $query->andWhere([
                    'o.is_send' => 0,'o.is_pay' => 1,'o.is_order_confirm' => 0
                ]);//is_pay=1 && is_send=0  待确认
                //待确认
                break;
            case 5:
                break;
            case 6:
                $query->andWhere(['o.apply_delete' => 1]);//待处理
                break;
            case 7:
                $query->andWhere(['o.is_send' => 1,'o.put_status' => 2,'o.is_cancel'=>0]);//is_send=1 && put_status=2   待自提
                break;
            case 8:
                $query->andWhere(['o.is_send' => 1,'o.put_status' => 3,'o.is_cancel'=>0,'o.is_comment'=>0]);//   待评价
                break;
            default:
                break;
        }

        if ($this->status == 5) {//已取消订单
            $query->andWhere(['or', ['o.is_cancel' => 1], ['o.is_delete' => 1]]);
        } else {
            if ($this->is_recycle != 1) {
                $query->andWhere(['o.is_cancel' => 0, 'o.is_delete' => 0]);
            }
        }

        //TODO 搜索 持续优化中...
        $commonOrderSearch = new CommonOrderSearch();
        $query = $commonOrderSearch->search($query, $this);
        $query = $commonOrderSearch->keyword($query, $this->keyword_1, $this->keyword);


        if ($this->type) {
            $query->andWhere(['o.type' => $this->type]);
        } else {
            if (get_plugin_type() != 0) {
                $query->andWhere(['o.type' => get_plugin_type()]);
            } else {
                $query->andWhere(['o.type' => 0]);
            }
        }
        if ($this->is_recycle == 1) {
            $query->andWhere(['o.is_recycle' => 1]);
        } else {
            $query->andWhere(['o.is_recycle' => 0]);
        }

        if ($this->flag == "EXPORT") {
            $query_ex = clone $query;
            $list_ex = $query_ex;
            $export = new ExportList();
            $export->is_offline = $this->is_offline;
            $export->order_type = 0;
            $export->fields = $this->fields;
            $export->dataTransform_new($list_ex);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1, 'route' => \Yii::$app->requestedRoute]);

        $clerkQuery = User::find()
            ->select('nickname')
            ->where(['store_id' => $this->store_id])
            ->andWhere('id = o.clerk_id');

        $refundQuery1 = OrderRefund::find()->alias('or')
            ->select('or.status, or.order_id, or.addtime')
            ->where(['or.store_id' => $this->store_id, 'or.is_delete' => 0]);
        $refundQuery = (new Query())->from(['or' => $refundQuery1])->where('`or`.order_id=o.id')
            ->select('or.status')
            ->orderBy(['or.addtime' => SORT_DESC])
            ->limit(1);

        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('o.addtime DESC')
            ->select(['o.id','o.store_id','o.user_id','o.order_no','o.total_price','o.pay_price','o.express_price','o.name','o.mobile','o.address','o.remark','o.is_pay','o.pay_type','o.pay_time','o.is_send','o.send_time','o.express','o.express_no','o.is_confirm','o.confirm_time','o.is_comment','o.apply_delete','o.addtime','o.is_delete','o.is_price','o.parent_id','o.first_price','o.second_price','o.third_price','o.coupon_sub_price','o.content','o.is_offline','o.clerk_id','o.address_data','o.is_cancel','o.offline_qrcode','o.before_update_price','o.shop_id','o.discount','o.user_coupon_id','o.integral','o.give_integral','o.parent_id_1','o.parent_id_2','o.is_sale','o.words','o.version','o.express_price_1','o.mch_id','o.is_recycle','o.seller_comments','o.order_union_id','o.rebate','o.before_update_express','o.is_transfer','o.type','o.share_price','o.is_show','o.currency','o.put_status','o.cabinet_id','o.service_day','o.service_time','o.put_code','o.is_order_confirm', 'c.province', 'c.city', 'c.address', 'u.nickname', 'u.platform', 'clerk_name' => $clerkQuery, 'refund' => $refundQuery])->asArray()->all();

        $listArray = ArrayHelper::toArray($list);
        foreach ($listArray as $i => &$item) {

            $item['goods_list'] = $this->getOrderGoodsList($item['id']);

            //此处考虑将 Order 和 Shop 模型使用 hasOne 关联，查询时使用 with 预查询 -- wi1dcard
            if ($item['shop_id'] && $item['shop_id'] != 0) {
                $shop = Shop::find()->where(['store_id' => $this->store_id, 'id' => $item['shop_id']])->asArray()->one();
                $item['shop'] = $shop;
            }
            $item['integral'] = json_decode($item['integral'], true);

            if (isset($item['address_data'])) {
                $item['address_data'] = \Yii::$app->serializer->decode($item['address_data']);
            }
            $item['flag'] = 0;
            $city_arr=Cabinet::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'province' => $item['province']])->groupBy('city')->asArray()->all();
            $listArray[$i]['city_arr']=$city_arr;
            $address_arr=Cabinet::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'city' => $item['city']])->groupBy('address')->asArray()->all();
            $listArray[$i]['address_arr']=$address_arr;
        }
        //查找云柜地址
        $cabinet=Cabinet::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->groupBy('cabinet_id')->asArray()->all();
        $cabinet_arr=Cabinet::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->groupBy('province')->asArray()->all();
        foreach ($cabinet_arr as $key => $val) {
            $province[]=$val['province'];
        }

        $province=array_unique($province);
        
        $province_arr=array();
        $city=array();
        foreach ($province as $key => $val) {
            $cabinet_province=Cabinet::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'province' => $val])->groupBy('city')->asArray()->all();
            foreach ($cabinet_province as $k => $v) {
                $city[$key][]=array(
                        'id' => $v['id']+1,
                        'level' => "city",
                        'list' => array(),
                        'name' => $v['city'],
                        'parent_id' => $key+1,
                );
            }
        }
        foreach ($province as $key => $val) {
            foreach ($city as $k => $v) {
                if($key==$k){
                    $province_arr[]=array(
                        'id' => $key+1,
                        'level' => "province",
                        'list' => $city[$k],
                        'name' => $val,
                        'parent_id' => 1,
                    );
                }
            }
        }
        // return $listArray;
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $listArray,
            'cabinet' => $cabinet,
            'province_arr' => $province_arr,
        ];
    }

    /**
     * @param $data array 需要处理的数据
     */
    public function dataTransform($data)
    {
        //TODO 测试数据 需要换成真实的字段
        $newFields = [];
        foreach ($this->fields as &$item) {
            if ($this->is_offline == 1) {
                if (in_array($item['key'], ['clerk_name', 'shop_name'])) {
                    $item['selected'] = 1;
                }
            } else {
                if (in_array($item['key'], ['express_price', 'express_no', 'express'])) {
                    $item['selected'] = 1;
                }
            }
            if (isset($item['selected']) && $item['selected'] == 1) {
                $newFields[$item['key']] = $item['value'];
            }
        }

        $newList = [];
        foreach ($data as $datum) {
            $newItem = [];
            $newItem['order_no'] = $datum->order_no;
            $newItem['nickname'] = $datum->user->nickname;
            $newItem['name'] = $datum->name;
            $newItem['mobile'] = $datum->mobile;
            $newItem['address'] = $datum->address;
            $newItem['total_price'] = $datum->total_price;
            $newItem['pay_price'] = $datum->pay_price;
            $newItem['pay_time'] = $datum->pay_time ? date('Y-m-d H:i', $datum->pay_time) : '';
            $newItem['send_time'] = $datum->send_time ? date('Y-m-d H:i', $datum->send_time) : '';
            $newItem['confirm_time'] = $datum->confirm_time ? date('Y-m-d H:i', $datum->confirm_time) : '';
            $newItem['words'] = $datum->words;
            $newItem['goods_list'] = $this->getOrderGoodsList($datum['id']);
            $newItem['is_pay'] = $datum['is_pay'] == 1 ? "已付款" : "未付款";
            $newItem['apply_delete'] = ($datum['apply_delete'] == 1) ? "取消中" : "无";
            $newItem['is_send'] = ($datum['is_send'] == 1) ? "已发货" : "未发货";
            $newItem['is_confirm'] = ($datum['put_status'] == 3) ? "已收货" : "未收货";
            $newItem['addtime'] = date('Y-m-d H:i', $datum['addtime']);
            $newItem['express_price'] = $datum['express_price'] . "元";

            //是否到店自提 0--否 1--是
            if ($datum['is_offline']) {
                $newItem['clerk_name'] = $datum->clerk ? $datum->clerk->nickname : '';
                $newItem['shop_name'] = $datum->shop ? $datum->shop->name : '';
            } else {
                $newItem['express_price'] = $datum->express_price;
                $newItem['express_no'] = $datum->express_no;
                $newItem['express'] = $datum->express;
            }

            if ($datum->orderForm) {
                $str = '';
                foreach ($datum->orderForm as $key => $item) {
                    $str .= $item['key'] . ':' . $item['value'] . ',';
                }
                $newItem['content'] = rtrim($str, ',');
            } else {
                $newItem['content'] = $datum->content;
            }

            $newList[] = $newItem;
        }
        Export::order_3($newList, $newFields);
    }

    public function getOrderGoodsList($order_id)
    {
        $picQuery = GoodsPic::find()
            ->alias('gp')
            ->select('pic_url')
            ->andWhere('gp.goods_id = od.goods_id')
            ->andWhere(['is_delete' => 0])
            ->limit(1);
        $orderDetailList = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select(['od.num', 'od.total_price', 'od.attr', 'od.is_level', 'g.name', 'g.unit', 'goods_pic' => $picQuery])->asArray()->all();
        foreach ($orderDetailList as &$item) {
            $item['attr_list'] = json_decode($item['attr'], true);
        }

        return $orderDetailList;
    }

    public static function getCountData($store_id)
    {
        $form = new OrderListForm();
        $form->limit = 0;
        $form->store_id = $store_id;
        $data = [];

        $form->status = -1;
        $res = $form->search();
        $data['all'] = $res['row_count'];

        $form->status = 0;
        $res = $form->search();
        $data['status_0'] = $res['row_count'];

        $form->status = 1;
        $res = $form->search();
        $data['status_1'] = $res['row_count'];

        $form->status = 2;
        $res = $form->search();
        $data['status_2'] = $res['row_count'];

        $form->status = 3;
        $res = $form->search();
        $data['status_3'] = $res['row_count'];

        $form->status = 5;
        $res = $form->search();
        $data['status_5'] = $res['row_count'];

        return $data;
    }
}
