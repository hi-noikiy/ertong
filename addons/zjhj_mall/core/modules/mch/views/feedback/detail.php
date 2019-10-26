<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/19
 * Time: 16:52
 */

$urlManager = Yii::$app->urlManager;
$this->title = '意见反馈详情';
$this->params['active_nav_group'] = 1;
?>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <form class="auto-form" method="post" return="<?= $urlManager->createUrl(['mch/feedback/index']) ?>">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">用户账号</label>
                </div>
                <div class="col-sm-6">
                    <?= $list['username'] ?>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #ccc"><?= $list['addtime'] ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">反馈类型</label>
                </div>
                <div class="col-sm-6">
                    <?= $list['type_name'] ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">问题描述</label>
                </div>
                <div class="col-sm-6">
                    <?= $list['content'] ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">联系方式</label>
                </div>
                <div class="col-sm-6">
                    <?= $list['information'] ?>
                    <div>
                        <?php foreach ($list['pic_list'] as $index => $value) : ?>
                            <img src="<?= $value->pic ?>" width="120rem" height="100rem">
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">备注</label>
                </div>
                <div class="col-sm-6">
                        <textarea rows="6" cols="50" name="feedback_desc"><?= $list['feedback_desc'] ?></textarea>
                </div>
            </div>
            <input value="<?= $list['id'] ?>" name="id" id="feedback_id" type="hidden">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary auto-form-btn" href="javascript:">保存</a>
                    <?php if ($list['status']== 1): ?>
                    <a class="btn btn-primary solve" href="<?= $urlManager->createUrl(['mch/feedback/detail-save', 'id' => $list['id']]) ?>" style="margin-left: 1.5rem">已解决</a>
                    <?php endif; ?>
                    <input type="button" class="btn btn-default ml-4" 
                           name="Submit" onclick="javascript:history.back(-1);" value="返回">
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('click', '.solve', function () {
        if (confirm("是否已解决？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        window.history.back();
                        // window.location.reload();
                    }
                }
            });
        }
        return false;
    });
</script>