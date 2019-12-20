<?php
defined('YII_ENV') or exit('Access Denied');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 14:57
 */
/* @var $pagination yii\data\Pagination */

/* @var $setting \app\models\Setting */

use yii\widgets\LinkPager;

$urlManager = Yii::$app->urlManager;
$this->title = '意见反馈管理';
$this->params['active_nav_group'] = 2;

$returnUrl = Yii::$app->request->referrer;
if (!$returnUrl) {
    $returnUrl = $urlManager->createUrl([get_plugin_url() . '/index']);
}
$urlStr = get_plugin_url();
// $urlPlatform = Yii::$app->controller->route;
?>

<style>
    .table tbody tr td {
        vertical-align: middle;
    }

    .msg {
        height: 59.25px;
        line-height: 59.25px;
    }

    .badge {
        font-size: 100%;
    }

    .cell {
        padding-left: 10.5px;
    }

    hr {
        margin: 5.25px 0;
    }
    td{
        text-align: center;
    }
</style>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form class="uto-form" method="get">

                    <?php $_s = ['keyword','type_id','status', 'page', 'per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div class="col-sm-6" style="height: 2rem;">
                        <div class="input-group" style="width: 25%;float: left;">
                            <input class="form-control" placeholder="用户账户关键词搜索" name="keyword"  value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                        </div>
                        <div class="input-group" style="width: 18%;float: left;padding-left: 1%;">
                            <span>营销类型</span>
                            <select class="form-control province" name="type_id">
                                <option value="0">全部</option>
                                <?php foreach ($feedbacktype_list as $p) : ?>
                                    <?php if ($_GET['type_id']== $p['id']): ?>
                                        <option value="<?= $p['id'] ?>" selected><?= $p['type_name'] ?></option>
                                    <?php else : ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['type_name'] ?></option>
                                    <?php endif; ?>
                                    
                                <?php endforeach; ?>
                            </select>
                            
                        </div>
                        <div class="input-group" style="width: 18%;float: left;padding-left: 1%;">
                            <span>状态</span>
                            <select class="form-control province" name="status">
                                <option value="0">全部</option>
                                <?php if ($_GET['status']== 1): ?>
                                    <option value="1" selected>未解决</option>
                                    <option value="2">已解决</option>
                                <?php elseif ($_GET['status']== 2) : ?>
                                    <option value="1">未解决</option>
                                    <option value="2" selected>已解决</option>
                                <?php else : ?>
                                    <option value="1">未解决</option>
                                    <option value="2">已解决</option>
                                <?php endif; ?>
                            </select>
                            
                        </div>
                        <div class="input-group" style="width: 15%;float: right;">
                            <span class="input-group-btn">
                                <button class="btn btn-primary">搜索</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        
        <table class="table table-bordered bg-white">
            <tr>
                <td>ID</td>
                <td>用户账号</td>
                <td>反馈时间</td>
                <td>反馈类型</td>
                <td>解决状态</td>
                <td>操作</td>
            </tr>
            <?php foreach ($list as $index => $value) : ?>
                <tr>
                    <td><?= $value['id'] ?></td>
                    
                    <td><?= $value['username'] ?></td>
                    <td><?= $value['addtime'] ?></td>
                    <td><?= $value['type_name'] ?></td>
                    <td><?= $value['status_name'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary mt-2" href="<?= $urlManager->createUrl([$urlStr . '/detail', 'id' => $value['id']]) ?>" >查看</a>
                        <?php if ($value['status'] == 1) : ?>
                            <a class="btn btn-sm btn-info mt-2 solve" href="<?= $urlManager->createUrl(['mch/feedback/detail-save', 'id' => $value['id']]) ?>">已解决</a>
                        <?php endif; ?>
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
    $(document).on('click', '.solve', function () {
        if (confirm("是否已解决？")) {
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    alert(res.msg);
                    if (res.code == 0) {
                        // window.history.back();
                        window.location.reload();
                    }
                }
            });
        }
        return false;
    });
</script>