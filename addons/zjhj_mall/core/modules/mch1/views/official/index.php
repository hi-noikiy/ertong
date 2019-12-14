<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:50
 */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$imgurl = Yii::$app->request->baseUrl;
$this->title = '新闻动态列表';
$this->params['active_nav_group'] = 2;
$urlStr = get_plugin_url();

?>
<style>
    .modal-dialog {
        position: fixed;
        top: 20%;
        left: 45%;
        width: 240px;
    }

    /*.modal-content {*/
        /*width: 240px;*/
    /*}*/

    .modal-body {
        /*height:200px;*/
    }

    table {
        table-layout: fixed;
    }

    th {
        text-align: center;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td {
        text-align: center;
        line-height: 30px;
    }

    .ellipsis {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    td.nowrap {
        white-space: nowrap;
        overflow: hidden;
    }

    .goods-pic {
        margin: 0 auto;
        width: 3rem;
        height: 3rem;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="float-left">
                <a href="<?= $urlManager->createUrl([$urlStr . '/official-edit']) ?>" class="btn btn-primary"><i
                        class="iconfont icon-playlistadd"></i>添加新闻</a>

                <div class="dropdown float-right ml-2">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if ($_GET['is_show'] === '1') :
                            ?>已显示
                        <?php elseif ($_GET['is_show'] === '0') :
                            ?>未显示
                        <?php elseif ($_GET['is_show'] == '') :
                            ?>全部
                        <?php else : ?>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu" style="min-width:8rem">
                        <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlStr . '/index']) ?>">全部</a>
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl([$urlStr . '/index', 'is_show' => 1]) ?>">已显示</a>
                        <a class="dropdown-item"
                           href="<?= $urlManager->createUrl([$urlStr . '/index', 'is_show' => 0]) ?>">未显示</a>
                    </div>
                </div>
            </div>
            <div class="float-right">
                <form method="get">

                    <?php $_s = ['keyword', 'page', 'per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>

                    <div class="input-group">
                        <input class="form-control" placeholder="新闻标题" name="keyword"
                               value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        <span class="input-group-btn">
                    <button class="btn btn-primary">搜索</button>
                </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered bg-white table-hover">
            <thead>
            <tr>
                <th>
                    <span class="label-text">新闻ID</span>
                </th>
                <th>新闻标题</th>
                <th>列表图片</th>
                <th>状态</th>
                <th>排序</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <col style="width: 6%">
            <col style="width: 5%">
            <col style="width: 8%">
            <col style="width: 8%">
            <col style="width: 5%">
            <col style="width: 8%">
            <col style="width: 16.5%">
            <tbody>
            <?php foreach ($list as $index => $official) : ?>
                <tr>
                    <td data-toggle="tooltip"
                        data-placement="top" title="<?= $official['id'] ?>">
                        <span class="label-text"><?= $official['id'] ?></span>
                    </td>
                    <td class="nowrap">
                        <?= $official['official_title'] ?>
                    </td>
                    <td class="p-0" style="vertical-align: middle">
                        <div class="goods-pic"
                             style="background-image: url(<?= $official['official_image'] ?>)"></div>
                    </td>
                    <td class="nowrap">
                        <?php if ($official['is_show'] == 1) : ?>
                            <span class="badge badge-success"><?= $official['status'] ?></span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $official['id'] ?>,'down');">隐藏</a>
                        <?php else : ?>
                            <span class="badge badge-default"><?= $official['status'] ?></span>
                            |
                            <a href="javascript:" onclick="upDown(<?= $official['id'] ?>,'up');">显示</a>
                        <?php endif ?>
                    </td>
                    <td class="nowrap">
                        <?= $official['official_sort'] ?>
                    </td>
                    <td class="nowrap">
                        <?= $official['addtime'] ?>
                    </td>
                    <td class="nowrap">

                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl([$urlStr . '/official-edit', 'id' => $official['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del"
                           href="<?= $urlManager->createUrl([$urlStr . '/official-del', 'id' => $official['id']]) ?>">删除</a>
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
        if (confirm("是否删除？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });

    function upDown(id, type) {
        var text = '';
        if (type == 'up') {
            text = "显示";
        } else {
            text = '隐藏';
        }

        var url = "<?= $urlManager->createUrl([$urlStr . '/official-up-down']) ?>";
        layer.confirm("是否" + text + "？", {
            btn: [text, '取消'] //按钮
        }, function () {
            layer.msg('加载中', {
                icon: 16
                , shade: 0.01
            });
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                data: {id: id, type: type},
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
                        if (res.return_url) {
                            location.href = res.return_url;
                        }
                    }
                }
            });
        });
        return false;
    }
</script>