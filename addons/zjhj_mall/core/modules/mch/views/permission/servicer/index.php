<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by IntelliJ IDEA.
 * User: wxf
 * Date: 2017/6/19
 * Time: 16:52
 */
use yii\widgets\LinkPager;
$this->title = '员工列表';
$urlManager = Yii::$app->urlManager;
?>

<div class="panel mb-3">
    <div class="panel-header">
        <span>客服人员列表</span>
        <ul class="nav nav-right">
            <li class="nav-item">
                <a class="nav-link"
                   href="<?= $urlManager->createUrl('mch/permission/servicer/create') ?>">添加客服人员</a>
            </li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form class="uto-form" method="get">

                    <?php $_s = ['keyword', 'page', 'per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div class="col-sm-6" style="height: 2rem;">
                        <div class="input-group" style="width: 25%;float: left;">
                            关键字
                            <input class="form-control" placeholder="客服人员姓名、手机号" name="keyword"  value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        </div>
                        
                        
                        <div class="input-group" style="width: 15%;float: left;">
                            <span class="input-group-btn">
                                <button class="btn btn-primary">搜索</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <table class="table table-bordered bg-white">
            <thead>
            <tr>
                <th>编号</th>
                <th>姓名</th>
                <th>手机号</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <?php foreach ($list as $item) : ?>
                <tr>
                    <td><?= $item['user_id_str'] ?></td>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['mobile'] ?></td>
                    <td><?= $item['create_time'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl(['mch/permission/servicer/edit', 'id' => $item['id']]) ?>">编辑</a>
                        <a class="btn btn-sm btn-danger article-delete"
                           href="<?= $urlManager->createUrl(['mch/permission/servicer/destroy', 'id' => $item['id']]) ?>">删除</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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
            <div class="text-muted">共<?= $pagination->totalCount ? $pagination->totalCount : 0 ?>条数据</div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", ".article-delete", function () {
        var href = $(this).attr("href");
        $.confirm({
            content: "确认删除？",
            confirm: function () {
                $.loading();
                $.ajax({
                    url: href,
                    dataType: "json",
                    success: function (res) {
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0) {
                                    location.reload();
                                }
                            }
                        })

                    }
                });
            }
        });
        return false;
    });
</script>
