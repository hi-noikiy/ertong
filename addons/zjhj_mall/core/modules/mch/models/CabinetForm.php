<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/7
 * Time: 12:59
 */

namespace app\modules\mch\models;

use app\models\Cabinet;
use Hejiang\Event\EventArgument;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
class CabinetForm extends MchModel
{
    public $cabinet;

    public $store_id;
    public $cabinet_id;
    public $cabinet_type;
    public $province;
    public $city;
    public $address;
    public $addtime;
    public $is_delete;
    public $keyword;
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['store_id', 'cabinet_id', 'cabinet_type', 'province', 'city', 'address'], 'required'],
            [['store_id', 'cabinet_type'], 'integer'],
            [['cabinet_id', 'province', 'city', 'address'], 'string'],
            
        ];
    }

    public function attributeLabels()
    {
        return [ 
            'id' => 'ID',
            'store_id' => 'Store ID',
            'cabinet_id' => '自提柜ID',
            'cabinet_type' => '自提柜类型',
            'province' => '省',
            'city' => '市',
            'address' => '详细地址',
        ];
    }

    /**
     * 编辑
     * @return array
     */
    public function save()
    {
       if ($this->validate()) {
            $preg = "/^\d{1,12}$/";

            if (!$this->cabinet_id || $this->cabinet_id=='') {
                return [
                    'code' => 1,
                    'msg' => '请填写柜子ID',
                ];
            }

            if (!preg_match($preg,$this->cabinet_id)) {
                return [
                    'code' => 1,
                    'msg' => '请正确填写柜子ID',
                ];
            }
            if (!$this->cabinet_type || $this->cabinet_type==0) {
                return [
                    'code' => 1,
                    'msg' => '请选择类型',
                ];
            }
            if (!$this->province || $this->province=='' || $this->province=='请选择') {
                return [
                    'code' => 1,
                    'msg' => '请选择投放的省份',
                ];
            }
            if (!$this->city || $this->city=='' || $this->city=='请选择') {
                return [
                    'code' => 1,
                    'msg' => '请选择投放的市/区',
                ];
            }
            if (!$this->address || $this->address=='') {
                return [
                    'code' => 1,
                    'msg' => '请填写详细地址',
                ];
            }
            $cabinet = $this->cabinet;
            
            $result=Cabinet::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'cabinet_id'=>$this->cabinet_id])->one();
            if($result){
                return [
                    'code' => 1,
                    'msg' => '柜子ID已存在',
                ];
            }
            $cabinet->store_id = $this->store_id;
            $cabinet->cabinet_id = $this->cabinet_id;//自提柜ID
            $cabinet->cabinet_type = $this->cabinet_type;//自提柜类型
            $cabinet->province = $this->province;//省
            $cabinet->city = $this->city;//市
            $cabinet->address = $this->address;//详细地址
            $cabinet->addtime = time();
            if ($cabinet->save()) {
                    return [
                        'code' => 0,
                        'msg' => '保存成功',
                    ];
            } else {
                return $this->getErrorResponse($cabinet);
            }
        } else {
            return $this->errorResponse;
        } 
    }
    /**
     * 查找
     * @return array
     */
    public function GetList()
    {
        $type=array('1'=>'常温柜','2'=>'冷藏柜','3'=>'冷冻柜');
        $keyword = $this->keyword;
        $cabinet_type = $this->cabinet_type;
        $query = Cabinet::find()->where(['store_id' => $this->store->id, 'is_delete' => 0]);

        if ($cabinet_type != null) {
            $query->andWhere('cabinet_type=:cabinet_type', [':cabinet_type' => $cabinet_type]);
        }
        
        if (trim($keyword)) {
            $query->andWhere(['LIKE', 'cabinet_id', $keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'route' => \Yii::$app->requestedRoute]);
        $list = $query->orderBy('addtime DESC')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        $goodsList = ArrayHelper::toArray($list);
        foreach ($goodsList as $key => $val) {
            $goodsList[$key]['cabinet_type']=$type[$val['cabinet_type']];
            $goodsList[$key]['put_in_time']=date('Y-m-d H:i:s',$val['addtime']);
        }
        return [
            'list' => $list,
            'goodsList' => $goodsList,
            'pagination' => $pagination
        ];
    }
    /**
     * 删除
     * @return array
     */
    public function delete()
    {
        
    }
}
