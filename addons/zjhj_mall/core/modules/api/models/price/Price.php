<?php


namespace app\modules\api\models\price;


use app\models\Goods;
use app\models\User;

class Price
{
    public function getPrice($userId, $goodsId){
        $user = User::findOne(['user_id']);
        $goods = Goods::findOne(['id' => $goodsId]);
        $price = $goods->price;
        if ($user->is_enterprise == 2){
            $price = $goods->merchant_price;
        }
        return $price;
    }
}