<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/27
 * Time: 11:36
 */

$this->title = '编辑仓库';
$this->params['active_nav_group'] = 2;
$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl([get_plugin_url() . '/goods']);
}
$commonDistrict = new \app\models\common\CommonDistrict();
$district = Yii::$app->serializer->encode($commonDistrict->search());
?>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/selectCity/assets/js/data.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/selectCity/assets/js/prettify.js"></script>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/mch/js/selectCity/dist/jquery.city.select.min.js"></script>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="Address">
        <form class="auto-form" method="post" return="<?= $returnUrl ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">仓库名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="model[warehouse_name]" value="<?= $list['warehouse_name'] ?>">
                </div>
            </div>
            <input class="form-control" type="hidden" name="model[id]" value="<?= $list['id'] ?>" >
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>