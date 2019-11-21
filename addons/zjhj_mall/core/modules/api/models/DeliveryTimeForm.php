<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29
 * Time: 10:57
 */

namespace app\modules\api\models;

use app\models\DeliveryTime;
use yii\data\Pagination;

class DeliveryTimeForm extends ApiModel
{

    public function rules()
    {
        return [
//            [['user_card_id'], 'integer'],
//            [['page'],'default','value'=>1],
//            [['limit'],'default','value'=>10],
//            [['status'],'default','value'=>1]
        ];
    }
}
