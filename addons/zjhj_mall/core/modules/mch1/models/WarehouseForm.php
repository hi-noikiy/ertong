<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 15:25
 */

namespace app\modules\mch\models;
use Hejiang\Event\EventArgument;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use app\models\Warehouse;
/**
 * @property \app\models\Warehouse $Warehouse;
 */
class WarehouseForm extends MchModel
{
    public $store_id;
    public $keyword;
    public $warehouse;
    public $warehouse_name;
    public $addtime;
    public $is_delete;

    public function rules()
    {
        return [
            [['store_id', 'warehouse_name'], 'required'],
            [['store_id', 'addtime', 'is_delete'], 'integer'],
            [['warehouse_name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '商城id',
            'warehouse_name' => '仓库名称',
            'is_delete' => '是否删除：0=未删除，1=已删除',
            'addtime' => '添加时间',
        ];
    }

    /**
     * 编辑
     * @return array
     */
    public function save()
    {
       if ($this->validate()) {

            if (!$this->warehouse_name || $this->warehouse_name=='') {
                return [
                    'code' => 1,
                    'msg' => '请填写柜子名称',
                ];
            }
            $warehouse = $this->warehouse;
            
            $warehouse->store_id = $this->store_id;
            $warehouse->warehouse_name = $this->warehouse_name;//柜子名称
            $warehouse->addtime = time();
            if ($warehouse->save()) {
                    return [
                        'code' => 0,
                        'msg' => '保存成功',
                    ];
            } else {
                return $this->getErrorResponse($warehouse);
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
        $keyword = $this->keyword;
        $query = Warehouse::find()->where(['store_id' => $this->store->id]);
        
        if (trim($keyword)) {
            $query->andWhere(['LIKE', 'warehouse_name', $keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'route' => \Yii::$app->requestedRoute]);
        $list = $query->orderBy('addtime DESC')
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        $houseList = ArrayHelper::toArray($list);

        foreach ($houseList as $key => $val) {
            $houseList[$key]['create_time']=date('Y-m-d H:i:s',$val['addtime']);
        }
        return [
            'houseList' => $houseList,
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
