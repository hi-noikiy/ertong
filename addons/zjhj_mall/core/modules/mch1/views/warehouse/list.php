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
$this->title = '仓库管理';
$this->params['active_nav_group'] = 5;
$urlPlatform = Yii::$app->controller->route;
$urlStr = get_plugin_url();
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
</style>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">

            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div>
                            <div class="input-group">
                                <input class="form-control"
                                       placeholder="仓库名称"
                                       name="keyword"
                                       autocomplete="off"
                                       value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                                <span class="input-group-btn">
                                        <button class="btn btn-primary">搜索</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="<?= $urlManager->createUrl([$urlStr . '/warehouse-edit']) ?>" class="btn btn-primary" style="margin-top: 1rem;"><i
                        class="iconfont icon-playlistadd"></i>添加仓库</a>
            </div>

        </div>
        
        <table class="table table-bordered bg-white">
            <tr>
                <td style="text-align: center;">ID</td>
                <td style="text-align: center;">仓库名称</td>
                <td style="text-align: center;">是否使用</td>
                <td style="text-align: center;">创建时间</td>
                <td style="text-align: center;">操作</td>
            </tr>
            <?php foreach ($houseList as $index => $value) : ?>
                <tr>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['id'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['warehouse_name'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?php if ($value['is_delete'] === 1) :
                            ?>未使用
                        <?php else : ?>
                            已使用
                        <?php endif; ?>

                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['create_time'] ?>
                    </td>
                    <td class="nowrap">
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl([$urlStr . '/warehouse-edit', 'id' => $value['id']]) ?>">修改</a>
                        <?php if ($value['is_delete'] === 1) : ?>
                            <a class="btn btn-sm btn-danger del" attr-del='0' attr-id="<?= $value['id']?>">启用</a>
                        <?php else : ?>
                            <a class="btn btn-sm btn-danger del" attr-del='1' attr-id="<?= $value['id']?>">停用</a>
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

        <!-- 下线 -->
        
    </div>
</div>
<script>
    $(document).on('click', '.del', function () {
        
        if (confirm("确认此操作？")) {
            $.ajax({
                url: '<?= $urlManager->createUrl(['mch/warehouse/warehouse-del'])?>',
                type: 'get',
                dataType: 'json',
                data: {
                    id: $(this).attr('attr-id'),
                    is_delete: $(this).attr('attr-del'),
                },
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    }
                }
            });
        }
        return false;
                
    });
</script>
