<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\permission;

use app\models\Distributor;
use app\modules\mch\controllers\Controller;
use app\modules\mch\models\permission\distributor\DistributorForm;
use app\modules\mch\models\permission\distributor\EditDistributorForm;
use app\modules\mch\models\permission\distributor\UpdateDistributorForm;
use app\modules\mch\models\permission\distributor\DestroyDistributorForm;
use app\modules\mch\models\permission\distributor\StoreDistributorForm;


use Yii;

class DistributorController extends Controller
{
    public function actionIndex($keyword = null)
    {
        $model = new DistributorForm();
        if(\Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $data['entry_time']=strtotime($data['entry_time']);
            $preg_phone = "/^1[23456789]\d{9}$/";
            if(!preg_match($preg_phone,$data['mobile'])){//验证手机号
                return [
                    'code' => 1,
                    'msg' => '手机号格式不正确'
                ];
            }
            $model = new StoreDistributorForm();
            $model->store_id = $this->store->id;
            $model->attributes = $data;

            return $model->store();
            
        }else{
            $model->keyword = $keyword;   
        }

        $list = $model->pagination();
        
        return $this->render('index', [
            'list' => $list['list'],
            'pagination' => $list['pagination']
        ]);
    }

    public function actionCreate()
    {

        return $this->render('create');
    }

    public function actionEdit($id)
    {
        $model = new EditDistributorForm();
        $model->id = $id;
        $edit = $model->edit();

        return $this->render('edit', ['edit' => $edit]);
    }

    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $model = new UpdateDistributorForm();
        $data['entry_time']=strtotime($data['entry_time']);
        $preg_phone = "/^1[23456789]\d{9}$/";
        if(!preg_match($preg_phone,$data['mobile'])){//验证手机号
            return [
                'code' => 1,
                'msg' => '手机号格式不正确'
            ];
        }
        $model->attributes = $data;
        $model->id = $id;

        return $model->update();
    }


    public function actionDestroy($id)
    {
        $model = new DestroyDistributorForm();
        $model->id = $id;

        return $model->destroy();
    }
}
