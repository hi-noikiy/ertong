<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 10:56
 */

namespace app\modules\mch\controllers;

use app\models\Cabinet;
use app\models\Order;
use app\models\Warehouse;
use app\modules\mch\models\CabinetForm;
use Hejiang\Event\EventArgument;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
/**
 * Class CabinetController
 * @package app\modules\mch\controllers
 * 自提柜
 */
class CabinetController extends Controller
{

    /**
     * 自提柜管理
     * @return string
     */
    public function actionList($keyword = null, $cabinet_type = null)
    {
        $form = new CabinetForm();
        $form->store = $this->store;
        $form->keyword = $keyword;
        $form->cabinet_type = $cabinet_type;
        $res = $form->getList();
        $house_list=array();
        $warehouse_list=Warehouse::find()->where(['is_delete'=>0, 'store_id' => $this->store->id])->orderBy('addtime DESC')->asArray()->all();
        foreach ($warehouse_list as $key => $val) {
            $house_list[$val['id']]=$val['warehouse_name'];
        }
        foreach ($res['goodsList'] as $key => $val) {
            if(isset($val['wherehouse_id']) && $val['wherehouse_id']!=''){
                $res['goodsList'][$key]['warehouse_name']=$house_list[$val['wherehouse_id']];
            }else{
                $res['goodsList'][$key]['warehouse_name']='';
            }
            
        }
        return $this->render('list', [
            'list' => $res['list'],
            'warehouse_list' => $warehouse_list,
            'goodsList' => $res['goodsList'],
            'pagination' => $res['pagination'],
        ]);
    }

    /**
     * 自提柜修改
     * @param int $id
     * @return string
     */
    public function actionCabinetEdit($id = 0)
    {

        $cabinet = Cabinet::findOne(['id' => $id, 'store_id' => $this->store->id]);

        if (!$cabinet) {
            $cabinet = new Cabinet();
        }

        $form = new CabinetForm();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form->attributes = $model;
            $form->cabinet = $cabinet;
            return $form->save();
        }

        $list = ArrayHelper::toArray($cabinet);
        $warehouse_list=Warehouse::find()->where(['is_delete'=>0, 'store_id' => $this->store->id])->orderBy('addtime DESC')->asArray()->all();
        
        
        $province_arr=array();
        $city_arr=array();
        $province_arr=Cabinet::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->groupBy('province')->asArray()->all();

        $city_arr=Cabinet::find()->where(['store_id' => $this->store->id, 'is_delete' => 0, 'province' => $list['province']])->groupBy('city')->asArray()->all();
        
        return $this->render('cabinet-edit', [
            'list' => $list,
            'city_arr' => $city_arr,
            'province_arr' => $province_arr,
            'warehouse_list' => $warehouse_list,
        ]);
    }

    /**
     * 删除（逻辑）
     * @param int $id
     */
    public function actionCabinetDel($id = 0)
    {
        $cabinet = Cabinet::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => $this->store->id]);
        if (!$cabinet) {
            return [
                'code' => 1,
                'msg' => '自提柜删除失败或已删除',
            ];
        }
        $cabinet->is_delete = 1;
        if ($cabinet->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($cabinet->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

    //查找自提柜是否可删除
    public function actionCabinetOrder($id = 0)
    {
        $cabinet = Order::find()->where(['cabinet_id' => $id, 'store_id' => $this->store->id])->asArray()->all();
        if (!$cabinet) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        }else{
            foreach ($cabinet as $key => $val) {
                $put_status_arr[]=$val['put_status'];
                $is_confirm_arr[]=$val['is_confirm'];
            }
            if(!in_array(1,$put_status_arr) && !in_array(0,$is_confirm_arr)){
                return [
                    'code' => 0,
                    'msg' => '成功',
                ];
                
            }else{
                return [
                    'code' => 1,
                    'msg' => '当前有未完结订单，不可删除',
                ]; 
            }
            
        }
    }
    //查找自提柜地址
    public function actionCabinetAddress()
    {
        $model=\Yii::$app->request->post();
        $address = $model['address'];
        $province = $model['province'];
        $city = $model['city'];

        $query = Cabinet::find()->where(['province' => $province, 'city' => $city, 'is_delete' => 0, 'store_id' => $this->store->id]);
        $query->andWhere(['like', 'address', $address.'%', false]);
        $cabinet = $query->asArray()->all();

        if ($cabinet) {
            foreach ($cabinet as $key => $val) {
                $cabinet[$key]['address']=str_replace($address, '<font color="red">' .$address. '</font>', $val['address']);
            }
            return [
                'code' => 0,
                'sea' => $cabinet,
                'msg' => '成功',
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '当前有未完结订单，不可删除',
            ];
            
        }
    }
}