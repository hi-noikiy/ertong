<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25
 * Time: 9:25
 */

namespace app\modules\mch\controllers;

use app\models\Warehouse;
use app\modules\mch\models\WarehouseForm;
use yii\helpers\ArrayHelper;
class WarehouseController extends Controller
{
    /**
     * 仓库不存在* @return string
     */
    public function actionList($keyword = null)
    {
        $form = new WarehouseForm();
        $form->store = $this->store;
        $form->keyword = $keyword;
        $res = $form->getList();

        return $this->render('list', [
            'houseList' => $res['houseList'],
            'pagination' => $res['pagination'],
        ]);
    }

    /**
     * 仓库不存在* @param int $id
     * @return string
     */
    public function actionWarehouseEdit($id = 0)
    {

        $warehouse = Warehouse::findOne(['id' => $id, 'store_id' => $this->store->id]);

        if (!$warehouse) {
            $warehouse = new Warehouse();
        }

        $form = new WarehouseForm();
        if (\Yii::$app->request->isPost) {
            $model = \Yii::$app->request->post('model');
            $model['store_id'] = $this->store->id;
            $form->attributes = $model;
            $form->warehouse = $warehouse;
            return $form->save();
        }
        $list = ArrayHelper::toArray($warehouse);
        return $this->render('warehouse-edit', [
            'list' => $list,
        ]);
    }

    /**
     * 删除（逻辑）
     * @param int $id
     */
    public function actionWarehouseDel($id = 0,$is_delete=1)
    {
        $warehouse = Warehouse::findOne(['id' => $id, 'store_id' => $this->store->id]);
        if (!$warehouse) {
            return [
                'code' => 1,
                'msg' => '仓库不存在',
            ];
        }
        $warehouse->is_delete = $is_delete;
        if ($warehouse->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($warehouse->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }

}
