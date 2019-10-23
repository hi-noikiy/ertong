<?php
defined('YII_ENV') or exit('Access Denied');

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '商城首页轮播图';
$this->params['active_nav_group'] = 1;
?>
<style>
    .goods-pic {
        margin: 0 auto;
        width: 8rem;
        height: 8rem;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header">
        <span><?= $this->title ?></span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link" href="<?= $urlManager->createUrl(['mch/store/slide-edit']) ?>">添加轮播图</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <table class="table table-bordered bg-white table-hover">
            <thead>
            <tr>
                <th>
                    <span class="label-text">ID</span>
                </th>
                <th>轮播图</th>
                <th>标题</th>
                <th>链接</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 2%">
            <col style="width: 2%">
            <col style="width: 5%">
            <col style="width: 8%">
            <col style="width: 5%">
            <col style="width: 16.5%">
            <tbody>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td data-toggle="tooltip" style="vertical-align: middle"
                        data-placement="top" title="<?= $value['id'] ?>">
                        <span class="label-text"><?= $value['id'] ?></span>
                    </td>
                    <td class="p-0" style="vertical-align: middle">
                        <div class="goods-pic"
                             style="background-image: url(<?= $value['pic_url'] ?>);background-size: cover;background-position: center"></div>
                    </td>
                    <td class="nowrap text-danger" style="vertical-align: middle"><?= $value['title'] ?></td>
                    <td class="nowrap" style="vertical-align: middle">
                        <?= $value['page_url'] ?>
                    </td>
                    <td class="nowrap" style="vertical-align: middle">
                        <?= $value['is_show_name'] ?>
                    </td>
                    <td class="nowrap" style="vertical-align: middle">

                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/store/slide-edit', 'id' => $value['id']]) ?>">编辑</a>
                        
                        <a class="btn btn-sm btn-danger del"
                           href="<?= $urlManager->createUrl(['mch/store/slide-del', 'id' => $value['id']]) ?>">删除</a>
                        <?php if ($value['is_show'] === '1') :?>
                            <a class="btn btn-sm btn-primary is_show" style="color: #fff" onclick="upDown(<?= $value['id'] ?>,2);">不显示</a>
                        <?php elseif ($value['is_show'] === '2') :?>
                            <a class="btn btn-sm btn-primary is_show" style="color: #fff" onclick="upDown(<?= $value['id'] ?>,1);">显示</a>
                        <?php endif; ?>
                        
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>

        </table>
        <div class="text-center">
            <nav aria-label="Page navigation example">
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'prevPageLabel' => '上一页',
                    'nextPageLabel' => '下一页',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'pagination',
                    ],
                    'prevPageCssClass' => 'page-item',
                    'pageCssClass' => "page-item",
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => [
                        'class' => 'page-link',
                    ],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ])
                ?>
            </nav>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.confirm({
            content: '确认删除？',
            confirm: function () {
                $.loading();
                $.ajax({
                    url: a.attr('href'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        $.loadingHide();
                        $.alert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            }
        });
        return false;
    });
    
    function upDown(id, is_show) {
        var url = "<?= $urlManager->createUrl(['mch/store/slide-update']) ?>";
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            data: {id: id, is_show: is_show},
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                }
                if (res.code == 1) {
                    layer.alert(res.msg, {
                        skin: 'layui-layer-molv'
                        , closeBtn: 0
                        , anim: 4 //动画类型
                    });
                }
            }
        });
        return false;
    }
</script>