<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/1
 * Time: 23:33
 */

namespace app\modules\api\models;


use app\hejiang\ApiResponse;
use app\models\HotSearch;

class HotSearchForm extends ApiModel
{
    public $store_id;
    public $number;
    public $keyword;
    public function rules()
    {
        return [
            [['store_id', 'number'], 'integer'],
            [['keyword'], 'string', 'max' => 255],
        ];
    }

    public function search()
    {
        if (!$this->validate())
            return $this->errorResponse;
        $result=HotSearch::find()->where(['store_id'=>$this->store_id])->orderBy('number DESC')->asArray()->each(7);
        
        $data = [
            'list' => $result,
        ];
        return new ApiResponse(0, 'success', $data);
    }

}
