<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/14
 * Time: 9:36
 */

namespace app\models;

use Yii;

class Model extends \yii\base\Model
{
    /**
     * 软删除：已删除
     */
    const IS_DELETE_TRUE = 1;

    /**
     * 软删除：未删除
     */
    const IS_DELETE_FALSE = 0;

    /**
     * 手机号正则表达式
     */
    const MOBILE_PATTERN = "/\+?\d[\d -]{8,12}\d/";


    /**
     * Get model error response
     * @param Model $model
     * @return \app\hejiang\ValidationErrorResponse
     */
    public function getErrorResponse($model = null)
    {
        if (!$model) {
            $model = $this;
        }
        return new \app\hejiang\ValidationErrorResponse($model->errors);
    }

    /**
     * 获取当前用户商城 ID
     * @return mixed
     */
    public function getCurrentStoreId()
    {
        return Yii::$app->controller->store->id;
    }

    /**
     * 获取当前登录用户 ID
     * @param boolean isGuest 是否未登录：false否|true是
     * @return int|string
     */
    public function getCurrentUserId()
    {
        if (Yii::$app->mchRoleAdmin->isGuest == false) {
            return Yii::$app->mchRoleAdmin->id;
        }

        if (Yii::$app->user->isGuest == false) {
            return Yii::$app->user->id;
        }

        if (Yii::$app->admin->isGuest == false) {
            return Yii::$app->admin->id;
        }
    }

    /**
     * 获取当前用户we7Uid,Id === 1 表示总管理员
     * @return mixed
     */
    public function getCurrentWe7Uid()
    {
        if (Yii::$app->user->isGuest == false) {
            return Yii::$app->user->identity->we7_uid;
        }

        if (Yii::$app->admin->isGuest == false) {
            return Yii::$app->admin->id;
        }
    }

    /**
     * @param $type mch:多商户|role:操作员|bind:微信公众号绑定链接
     * @return string
     */
    public function getAdminUrl($type) {
        $storeId = Yii::$app->controller->store->id;
        $urlManager = Yii::$app->urlManager;
        switch ($type) {
            case 'mch':
                $userLoginUrl = $urlManager->hostInfo . $urlManager->baseUrl . '/mch.php?store_id=' . $storeId;
                break;
            case 'role':
                $userLoginUrl = $urlManager->hostInfo . $urlManager->baseUrl . '/role.php?store_id=' . $storeId;
                break;
            case 'bind' :
                $userLoginUrl = Yii::$app->urlManager->createAbsoluteUrl(['wechat-platform/bind-user', 'store_id' => $this->getCurrentStoreId()]);
                break;
            default :
                $userLoginUrl = '';
        }

        return $userLoginUrl;
    }
}
