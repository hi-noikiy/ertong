<?php
defined('YII_ENV') or exit('Access Denied');
$this->title = '修改客服信息';
$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post"
              action="<?= $urlManager->createUrl(['mch/permission/servicer/update', 'id' => $edit['id']]) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">客服姓名</label>
                </div>
                <div class="col-sm-6">
                    <?= $edit['name'] ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">员工编号</label>
                </div>
                <div class="col-sm-6">
                    <?= $edit['user_id_str'] ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">创建时间</label>
                </div>
                <div class="col-sm-6">
                    <?= $edit['create_time'] ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">手机号</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control cat-name" value="<?= $edit['mobile'] ?>" name="mobile">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">入职时间</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control" id="addtime" name="entry_time" value="<?= $edit['entry_time'] ?>">
                </div>
            </div>
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
<script type="text/javascript">

      (function () {
        $.datetimepicker.setLocale('zh');
        $('#addtime').datetimepicker({
            format: 'Y-m-d H:i:s',
            timepicker:true,
            onShow: function (ct) {
                this.setOptions({
                    // maxDate: $('#addtime').val() ? $('#addtime').val() : false
                })
            },
        });
    })();
</script>