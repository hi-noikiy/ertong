<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 13:59
 */
defined('YII_ENV') or exit('Access Denied');

/* @var \app\models\Sender $sender */

use yii\widgets\LinkPager;

$statics = Yii::$app->request->baseUrl . '/statics';
$urlManager = Yii::$app->urlManager;
$this->title = '送达时间列表';
$this->params['active_nav_group'] = 1;
?>


<div class="panel mb-3">
    <div class="panel-header">
        <span>送达时间列表</span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/delivery-time/delivery-time-edit']) ?>">添加下单时间</a>
            </li>
            
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white">
            <tr>
                <th>下单时间区间</th>
                <th>送达时间</th>
                <th>操作</th>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['start_time'] ?>-<?= $value['end_time'] ?></td>
                    <td>
                        <?php foreach ($value['service_time_app']['list'] as $key => $val) : ?>
                            <?= $val['next_day'] ?>:<?= $val['start_time'] ?>-<?= $val['end_time'] ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/delivery-time/delivery-time-edit', 'id' => $value['id']]) ?>">修改</a>

                        <a class="del btn btn-sm btn-danger" href="javascript:"
                           data-content="是否删除？"
                           data-url="<?= $urlManager->createUrl(['mch/delivery-time/delivery-time-del', 'id' => $value['id']]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.confirm({
            title: "",
            content: a.data('content'),
            confirm: function () {
                $.loading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        $.loadingHide();
                        if (res.code == 0) {
                            window.location.reload();
                        } else {
                            $.alert({
                                title: res.msg
                            });
                        }
                    }
                });

            }
        });
        return false;
    });
</script>
