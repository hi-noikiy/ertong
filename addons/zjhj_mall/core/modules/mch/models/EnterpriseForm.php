<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 13:54
 */

namespace app\modules\mch\models;

/**
 * @property \app\models\User $user
 */
class EnterpriseForm extends MchModel
{
    public $store_id;
    

    public function rules()
    {
        return [
            [['store_id', 'user_id', 'create_time'], 'required'],
            [['store_id', 'user_id', 'create_time', 'status'], 'integer'],
            [['enterprise_name', 'enterprise_license'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => 'user_id',
            'enterprise_name' => 'enterprise_name',
            'enterprise_license' => 'enterprise_license',
            'create_time' => 'create_time',
            'status' => 'status',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $this->user->level = $this->level;
        $this->user->contact_way = trim($this->contact_way);
        $this->user->comments = trim($this->comments);
        $this->user->parent_id = $this->parent_id;
        $resetPrice = $this->user->price - $this->price;
        $this->user->total_price = $this->user->total_price - $resetPrice;
        $this->user->price =  $this->price;
        $this->user->blacklist =  $this->blacklist;

        if ($this->user->save()) {
            return [
                'code'=>0,
                'msg'=>'成功'
            ];
        } else {
            return $this->getErrorResponse($this->user);
        }
    }
}
