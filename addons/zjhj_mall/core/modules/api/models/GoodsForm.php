<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/15
 * Time: 9:56
 */

namespace app\modules\api\models;

use app\models\Cat;
use app\models\common\CommonGoods;
use app\models\Coupon;
use app\models\CouponAutoSend;
use app\models\Level;
use app\models\Option;
use app\models\UserCoupon;
use app\modules\mch\models\LevelListForm;
use app\utils\GetInfo;
use app\hejiang\ApiResponse;
use app\models\Favorite;
use app\models\Goods;
use app\models\GoodsPic;
use app\models\Mch;
use app\models\MiaoshaGoods;
use app\modules\api\models\mch\ShopDataForm;

class GoodsForm extends ApiModel
{
    public $id;
    public $user_id;
    public $store_id;

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['user_id'], 'safe'],
        ];
    }

    /**
     * 排序类型$sort   1--综合排序 2--销量排序
     */
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $goods = Goods::findOne([
            'id' => $this->id,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
            'type' => get_plugin_type()
        ]);
        if (!$goods) {
            return new ApiResponse(1, '商品不存在或已下架');
        }
        $mch = null;
        if ($goods->mch_id) {
            $mch = $this->getMch($goods);
            if (!$mch) {
                return new ApiResponse(1, '店铺已经打烊了哦~');
            }
        }
        $pic_list = GoodsPic::find()->select('pic_url')->where(['goods_id' => $goods->id, 'is_delete' => 0])->asArray()->all();
        $is_favorite = 0;
        if ($this->user_id) {
            $exist_favorite = Favorite::find()->where(['user_id' => $this->user_id, 'goods_id' => $goods->id, 'is_delete' => 0])->exists();
            if ($exist_favorite) {
                $is_favorite = 1;
            }
        }
        $service_list = explode(',', $goods->service);
        // 默认商品服务
        if (!$goods->service) {
            $option = Option::get('good_services', $this->store->id, 'admin', []);
            foreach ($option as $item) {
                if ($item['is_default'] == 1) {
                    $service_list = explode(',', $item['service']);
                    break;
                }
            }
        }
        $new_service_list = [];
        //获取service_json
        $serviceJson = Option::find()->select('value')->where(['store_id' => $this->store_id, 'name' => 'good_services'])->asArray()->all();
        $service_listArr = json_decode($serviceJson[0]['value'], true);
        if (is_array($service_list)) {
            foreach ($service_list as $k => $item) {
                foreach ($service_listArr as $key => $value){
                    $item = trim($item);
                    if ($item && $item == $value['service']) {
                        $new_service_list[$k]['service_name'] = $item;
                        $new_service_list[$k]['service_desc'] = $value['service_desc'];
                    }
                }

            }
        }
        $new_service_list = array_values($new_service_list);
        $price = [];
        foreach (json_decode($goods->attr) as $v) {
            if ($v->price > 0) {
                $price[] = $v->price;
            } else {
                $price[] = floatval($goods->price);
            }
        }

        $res_url = GetInfo::getVideoInfo($goods->video_url);
        $goods->video_url = $res_url['url'];

        if ($goods->is_negotiable) {
            $min_price = Goods::GOODS_NEGOTIABLE;
        } else {
            $min_price = sprintf('%.2f', min($price));
        }

        $res = CommonGoods::getMMPrice([
            'attr' => $goods['attr'],
            'attr_setting_type' => $goods['attr_setting_type'],
            'share_type' => $goods['share_type'],
            'share_commission_first' => $goods['share_commission_first'],
            'price' => $goods['price'],
            'individual_share' => $goods['individual_share'],
            'mch_id' => $goods['mch_id'],
            'is_level' => $goods['is_level'],
        ]);

        $attr = json_decode($goods->attr, true);
        $goodsPrice = $goods->price;
        $isMemberPrice = false;
        if ($res['user_is_member'] === true && count($attr) === 1 && $attr[0]['attr_list'][0]['attr_name'] == '默认') {
            $goodsPrice = $res['min_member_price'] ? $res['min_member_price'] : $goods->price;
            $isMemberPrice = true;
        }

        // 多商户商品无会员价
        if ($res['is_mch_goods'] === true) {
            $isMemberPrice = false;
        }

        $data = [
            'id' => $goods->id,
            'pic_list' => $pic_list,
            'attr' => $goods->attr,
            'is_negotiable' => $goods->is_negotiable,
            'max_price' => sprintf('%.2f', max($price)),
            'min_price' => $min_price,
            'name' => $goods->name,
            'cat_id' => $goods->cat_id,
            'price' => sprintf('%.2f', $goodsPrice),
            'detail' => $goods->detail,
            'sales' => $goods->getSalesVolume() + $goods->virtual_sales,
            'attr_group_list' => $goods->getAttrGroupList(),
            'num' => $goods->getNum(),
            'is_favorite' => $is_favorite,
            'service_list' => array_column($new_service_list,'service_name'),
            'service_info' => $new_service_list,
            'original_price' => sprintf('%.2f', $goods->original_price),
            'video_url' => $goods->video_url,
            'unit' => $goods->unit,
            'use_attr' => intval($goods->use_attr),
            'mch' => $mch,
            'storage_type' =>$goods->storage_type,
            'max_share_price' => sprintf('%.2f', $res['max_share_price']),
            'min_member_price' => sprintf('%.2f', $res['min_member_price']),
            'is_share' => $res['is_share'],
            'is_level' => $res['is_level'],
            'is_member_price' => $isMemberPrice,
        ];
        return new ApiResponse(0, 'success', $data);
    }

    //获取商品秒杀数据
    public function getMiaoshaData($goods_id)
    {
        $miaosha_goods = MiaoshaGoods::findOne([
            'goods_id' => $goods_id,
            'is_delete' => 0,
            'start_time' => intval(date('H')),
            'open_date' => date('Y-m-d'),
        ]);
        if (!$miaosha_goods) {
            return null;
        }
        $attr_data = json_decode($miaosha_goods->attr, true);
        $total_miaosha_num = 0;
        $total_sell_num = 0;
        $miaosha_price = 0.00;
        foreach ($attr_data as $i => $attr_data_item) {
            $total_miaosha_num += $attr_data_item['miaosha_num'];
            $total_sell_num += $attr_data_item['sell_num'];
            if ($miaosha_price == 0) {
                $miaosha_price = $attr_data_item['miaosha_price'];
            } else {
                $miaosha_price = min($miaosha_price, $attr_data_item['miaosha_price']);
            }
        }
        return [
            'miaosha_num' => $total_miaosha_num,
            'sell_num' => $total_sell_num,
            'miaosha_price' => (float)$miaosha_price,
            'begin_time' => strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':00:00'),
            'end_time' => strtotime($miaosha_goods->open_date . ' ' . $miaosha_goods->start_time . ':59:59'),
            'now_time' => time(),
        ];
    }


    // 快速给购买商品
    public function quickGoods($twocatid)
    {
        $goods = Goods::find()
            ->where([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'status' => 1,
                'quick_purchase' => 1
            ])
            ->andWhere([

                'in', 'cat_id', $twocatid
            ])->asArray()
            ->all();
        foreach ($goods as $key => &$value) {
            $value['attr'] = json_decode($value['attr']);
            foreach ($value['attr'] as $key2 => $value2) {
                foreach ($value2->attr_list as $key3 => $value3) {
                    $value['attr_name'] = $value3->attr_name;
                }
                // $value['attr_num'][] = $value2->num;
                // $value['attr_price'][] = $value2->price;
                // $value['attr_no'][] = $value2->no;
                // $value['attr_pic'][] = $value2->pic;
                $value['num'] = 0;
            }
            // unset($value['attr']);
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $goods,
            ],
        ];
    }

    /**
     * @param Goods $goods
     */
    public function getMch($goods)
    {
        $f = new ShopDataForm();
        $f->mch_id = $goods->mch_id;
        $shop = $f->getShop();
        if (isset($shop['code']) && $shop['code'] == 1) {
            return null;
        }
        return $shop;
    }

    public function getCouponList()
    {
        $goods = Goods::findOne([
            'id' => $this->id,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
            'type' => get_plugin_type()
        ]);
        $goodsPrice = $goods->price;
        $list = UserCoupon::find()->alias('uc')
            ->leftJoin(['c' => Coupon::tableName()], 'uc.coupon_id=c.id')
            ->leftJoin(['cas' => CouponAutoSend::tableName()], 'uc.coupon_auto_send_id=cas.id')
            ->where([
                'AND',
                ['uc.is_delete' => 0],
                ['uc.is_use' => 0],
                ['uc.is_expire' => 0],
                ['uc.user_id' => $this->user_id],
                ['<=', 'c.min_price', $goodsPrice],
            ])
            ->select('uc.id user_coupon_id,c.sub_price,c.min_price,cas.event,uc.begin_time,uc.end_time,uc.type,c.appoint_type,c.cat_id_list,c.goods_id_list')
            ->asArray()->all();
        $events = [
            0 => '平台发放',
            1 => '分享红包',
            2 => '购物返券',
            3 => '领券中心',
        ];
        $new_list = [];
        foreach ($list as $i => $item) {
            if ($item['begin_time'] > (strtotime(date('Y-M-d')) + 86400) || $item['end_time'] < time()) {
                continue;
            }
            $list[$i]['status'] = 0;
            if ($item['is_use']) {
                $list[$i]['status'] = 1;
            }

            if ($item['is_expire']) {
                $list[$i]['status'] = 2;
            }

            $list[$i]['min_price_desc'] = $item['min_price'] == 0 ? '无门槛' : '满' . $item['min_price'] . '元可用';
            $list[$i]['begin_time'] = date('Y.m.d H:i', $item['begin_time']);
            $list[$i]['end_time'] = date('Y.m.d H:i', $item['end_time']);
            if (!$item['event']) {
                if ($item['type'] == 2) {
                    $list[$i]['event'] = $item['event'] = 3;
                } else {
                    $list[$i]['event'] = $item['event'] = 0;
                }
            }
            $list[$i]['event_desc'] = $events[$item['event']];
            $list[$i]['min_price'] = doubleval($item['min_price']);
            $list[$i]['sub_price'] = doubleval($item['sub_price']);

            if ($list[$i]['appoint_type'] == 1) {
                $list[$i]['cat_id_list'] = json_decode($list[$i]['cat_id_list']);
                if ($list[$i]['cat_id_list'] != null) {
                    if (!array_intersect($list[$i]['cat_id_list'], [$goods->getCat()])) {
                        unset($list[$i]);
                        continue;
                    }
                }
            } elseif ($list[$i]['appoint_type'] == 2) {
                $list[$i]['goods_id_list'] = json_decode($list[$i]['goods_id_list']);
                if ($list[$i]['goods_id_list'] != null) {
                    if (!array_intersect($list[$i]['goods_id_list'], [$this->id])) {
                        unset($list[$i]);
                        continue;
                    }
                }
            }

            $new_list[] = $list[$i];
        }
        return new ApiResponse(0, 'success', $new_list);
    }

    public function couponListV2(){
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $goods = Goods::findOne([
            'id' => $this->id,
            'is_delete' => 0,
            'status' => 1,
            'store_id' => $this->store_id,
            'type' => get_plugin_type()
        ]);
        $goodsPrice = $goods->price;

        if (!$this->user_id) {
            $this->user_id = 0;
        }
        $coupon_list = Coupon::find()->alias('c')->where([
            'c.is_delete' => 0, 'c.is_join' => 2, 'c.store_id' => $this->store_id,
        ])
            ->andWhere(['!=', 'c.total_count', 0])->andWhere(
                [ '<=', 'c.min_price', $goodsPrice])
            ->leftJoin(UserCoupon::tableName() . ' uc', "uc.coupon_id=c.id and uc.user_id ={$this->user_id} and uc.type = 2 and uc.is_delete=0")->select([
                'c.*', '(case when isnull(uc.id) then 0 else 1 end) as is_receive'
            ])
            ->orderBy('is_receive ASC,sort ASC,id DESC')->asArray()->all();
        $new_list = [];
        foreach ($coupon_list as $index => $value) {
            if ($value['min_price'] >= 100) {
                $coupon_list[$index]['min_price'] = (int)$value['min_price'];
            }
            if ($value['sub_price'] >= 100) {
                $coupon_list[$index]['sub_price'] = (int)$value['sub_price'];
            }
            $coupon_list[$index]['begintime'] = date('Y.m.d', $value['begin_time']);
            $coupon_list[$index]['endtime'] = date('Y.m.d', $value['end_time']);
            $coupon_list[$index]['content'] = "适用范围：全场通用";
            if ($value['appoint_type'] == 1 && $value['cat_id_list'] !== 'null') {
                $coupon_list[$index]['cat'] = Cat::find()->select('id,name')->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>json_decode($value['cat_id_list'])])->asArray()->all();
                $cat_list = [];
                foreach ($coupon_list[$index]['cat'] as $item) {
                    $cat_list[] = $item['name'];
                }
                $coupon_list[$index]['content'] = "适用范围：仅限分类：".implode('、', $cat_list)."使用";
                $coupon_list[$index]['goods'] = [];
            } elseif ($value['appoint_type'] == 2 && $value['goods_id_list'] !== 'null') {
                $coupon_list[$index]['goods'] = Goods::find()->select('id')->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>json_decode($value['goods_id_list'])])->asArray()->all();
                $coupon_list[$index]['cat'] = [];
                $coupon_list[$index]['content'] = "指定商品使用 点击查看指定商品";
            } else {
                $coupon_list[$index]['goods'] = [];
                $coupon_list[$index]['cat'] = [];
            }
            if($value['is_receive'] == 0){
                $coupon_list[$index]['receive_content'] = '立即领取';
            }else{
                $coupon_list[$index]['receive_content'] = '已领取';
            }
            $coupon_count = UserCoupon::find()->where([
                'store_id'=>$this->store_id,'is_delete'=>0,'coupon_id'=>$value['id'],'type'=>2
            ])->count();
            if ($coupon_list[$index]['appoint_type'] == 1) {
                $coupon_list[$index]['cat_id_list'] = json_decode($coupon_list[$index]['cat_id_list']);
                if ($coupon_list[$index]['cat_id_list'] != null) {
                    if (!in_array( $goods->getCat(), $coupon_list[$index]['cat_id_list'])) {
                        unset($coupon_list[$index]);
                        continue;
                    }
                }
            } elseif ($coupon_list[$index]['appoint_type'] == 2) {
                $coupon_list[$index]['goods_id_list'] = json_decode($coupon_list[$index]['goods_id_list']);
                if ($coupon_list[$index]['goods_id_list'] != null) {
                    if (!in_array($this->id, $coupon_list[$index]['goods_id_list'])) {
                        unset($coupon_list[$index]);
                        continue;
                    }
                }
            }
            if ($value['total_count'] > $coupon_count || $value['total_count'] == -1) {
                if ($value['expire_type'] == 2) {
                    if ($value['end_time'] >= time()) {
                        $new_list[] = $coupon_list[$index];
                    }
                } else {
                    $new_list[] = $coupon_list[$index];
                }
            }




        }
        return $new_list;
    }
}
