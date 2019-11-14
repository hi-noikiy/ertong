<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/9/27
 * Time: 9:50
 */

namespace app\modules\mch\controllers;

use app\models\Official;
use app\modules\mch\models\OfficialForm;

class OfficialController extends Controller
{
    
    public function actionIndex($keyword = null, $is_show = null)
    {
        $form = new OfficialForm();
        $form->keyword = $keyword;
        $form->is_show = $is_show;
        $res = $form->getList();
        // print_r($res);die;
        return $this->render('index', [
            'list' => $res['list'],
            'pagination' => $res['pagination'],
        ]);
    }

    public function actionOfficialEdit($id = null){

        $model = Official::findOne(['id'=>$id,'is_delete'=>0]);
        if (!$model) {
            $model = new Official();
        }
        if (\Yii::$app->request->isPost) {
            $form = new OfficialForm();
            // print_r(\Yii::$app->request->post());die;
            $form->attributes = \Yii::$app->request->post();
            $form->official = $model;
            return $form->save();
        } else {
            $Official = Official::find()->where(['id' => $id, 'is_delete' => 0])->one();

            return $this->render('official-edit', [
                'model' => $Official,
            ]);
        }
    }
    //新闻显示隐藏
    public function actionOfficialUpDown($id = 0, $type = 'down')
    {
        if ($type == 'down') {
            $official = Official::findOne(['id' => $id, 'is_delete' => 0, 'is_show' => 1]);
            if (!$official) {
                return [
                    'code' => 1,
                    'msg' => '新闻已删除或已隐藏',
                ];
            }
            $official->is_show = 0;
        } elseif ($type == 'up') {
            $official = Official::findOne(['id' => $id, 'is_delete' => 0, 'is_show' => 0]);

            if (!$official) {
                return [
                    'code' => 1,
                    'msg' => '新闻已删除或已显示',
                ];
            }
            $official->is_show = 1;
        } else {
            return [
                'code' => 1,
                'msg' => '参数错误',
            ];
        }
        if ($official->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($official->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }
    /**
     * 删除（逻辑）
     * @param int $id
     */
    public function actionOfficialDel($id = 0)
    {
        $official = Official::findOne(['id' => $id, 'is_delete' => 0]);
        if (!$official) {
            return [
                'code' => 1,
                'msg' => '新闻删除失败或已删除',
            ];
        }
        $official->is_delete = 1;
        if ($official->save()) {
            return [
                'code' => 0,
                'msg' => '成功',
            ];
        } else {
            foreach ($official->errors as $errors) {
                return [
                    'code' => 1,
                    'msg' => $errors[0],
                ];
            }
        }
    }
    // public function actionEdit($id = null)
    // {
    //     $model = Topic::findOne([
    //         'id' => $id,
    //         'store_id' => $this->store->id,
    //         'is_delete' => 0,
    //     ]);

    //     if (!$model) {
    //         $model = new Topic();
    //     }
    //     if (\Yii::$app->request->isPost) {
    //         $form = new TopicEditForm();
    //         $form->store_id = $this->store->id;
    //         $form->attributes = \Yii::$app->request->post();
    //         $form->model = $model;
    //         return $form->save();
    //     } else {
    //         $TopicType = TopicType::find()->where(['store_id' => $this->store->id, 'is_delete' => 0])->all();
    //         $select = array();
    //         foreach ($TopicType as $k => $v) {
    //             $select[$k] = (object) [
    //                 'value' => $v['id'],
    //                 'name' => $v['name'],
    //             ];
    //         }
    //         foreach ($model as $index => $value) {
    //             $model[$index] = str_replace("\"", "&quot;", $value);
    //         }

    //         return $this->render('edit', [
    //             'model' => $model,
    //             'select' => $select,
    //         ]);
    //     }
    // }

    // public function actionDelete($id)
    // {
    //     $model = Topic::findOne([
    //         'id' => $id,
    //         'store_id' => $this->store->id,
    //         'is_delete' => 0,
    //     ]);
    //     if ($model) {
    //         $model->is_delete = 1;
    //         $model->save();
    //     }
    //     return [
    //         'code' => 0,
    //         'msg' => '操作成功！',
    //     ];
    // }

    // public function actionSearchGoods($keyword = null)
    // {
    //     $query = Goods::find()->where([
    //         'store_id' => $this->store->id,
    //         'is_delete' => 0,
    //     ]);
    //     if ($keyword) {
    //         $query->andWhere(['LIKE', 'name', $keyword]);
    //     }

    //     $list = $query->orderBy('sort ASC,addtime DESC')->limit(10)->all();
    //     $new_list = [];
    //     foreach ($list as $item) {
    //         $new_list[] = [
    //             'id' => $item->id,
    //             'name' => $item->name,
    //             'price' => $item->price,
    //             'cover_pic' => $item->getGoodsCover(),
    //         ];
    //     }
    //     return [
    //         'code' => 0,
    //         'data' => [
    //             'list' => $new_list,
    //         ],
    //     ];
    // }

    // public function actionSearchVideo($keyword = null)
    // {
    //     $query = Video::find()->where([
    //         'store_id' => $this->store->id,
    //         'is_delete' => 0,
    //     ]);
    //     if ($keyword) {
    //         $query->andWhere(['LIKE', 'title', $keyword]);
    //     }

    //     $list = $query->orderBy('sort ASC,addtime DESC')->limit(10)->all();
    //     $new_list = [];
    //     foreach ($list as $item) {
    //         $new_list[] = [
    //             'id' => $item->id,
    //             'name' => $item->title,
    //             'src' => $item->url,
    //             'cover_pic' => $item->pic_url,
    //         ];
    //     }
    //     return [
    //         'code' => 0,
    //         'data' => [
    //             'list' => $new_list,
    //         ],
    //     ];
    // }
}
