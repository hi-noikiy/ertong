<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/15
 * Time: 13:40
 */

namespace app\modules\api\models;

use app\models\Cart;

class CartEditForm extends ApiModel
{
    public $user_id;
    public $store_id;
    public $num;
    public $car_id;

    public function rules()
    {
        return [

        ];
    }

    public function save()
    {
       //try {

            if (!$this->validate()) {
                return $this->errorResponse;
            }
            $cart = Cart::findOne([
                'id' => $this->car_id,
                'store_id' => $this->store_id,
                'is_delete' => 0,
            ]);
            if (!$cart){
                return [
                    'code' => 1,
                    'msg' => '购物车不存在',
                ];
            }
            $cart->num = $this->num;
            if ($cart->save()){
                return [
                    'code' => 0,
                    'msg' => 'success',
                ];
            }
        //} catch (\Exception $e) {
            //\Yii::warning($e->getMessage() . 'line=' . $e->getLine());
        //}
    }
}
