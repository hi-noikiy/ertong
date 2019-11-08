<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/30
 * Time: 13:22
 */

namespace app\modules\api\models;

use app\models\Favorite;

class FavoriteCheckForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $goods_id;

    public function rules()
    {
        return [
            [['goods_id'], 'required',],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $existFavorite = Favorite::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'goods_id' => $this->goods_id,
            'is_delete' => 0,
        ]);
        if ($existFavorite) {
            return [
                'code' => 0,
                'msg' => true,
            ];
        }else{
            return [
                'code' => 0,
                'msg' => false,
            ];
        }
    }
}
