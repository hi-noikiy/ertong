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
$this->title = '柜子管理';
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
                        <div class="dropdown float-right mr-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($_GET['cabinet_type'] === '1') :
                                    ?>常温
                                <?php elseif ($_GET['cabinet_type'] === '2') :
                                    ?>冷藏
                                <?php elseif ($_GET['cabinet_type'] === '3') :
                                    ?>冷冻
                                <?php elseif ($_GET['cabinet_type'] == '') :
                                    ?>全部
                                <?php else : ?>
                                <?php endif; ?>
                            </button>
                            <div class="dropdown-menu" style="min-width:8rem">
                                <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlPlatform, 'cabinet_type' => '', 'keyword'=>$_GET['keyword']]) ?>">全部</a>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'cabinet_type' => 1, 'keyword'=>$_GET['keyword']]) ?>">常温柜</a>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'cabinet_type' => 2, 'keyword'=>$_GET['keyword']]) ?>">冷藏柜</a>
                                   <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'cabinet_type' => 3, 'keyword'=>$_GET['keyword']]) ?>">冷冻柜</a>
                            </div>
                        </div>
                        <div>
                            <div class="input-group">
                                <input class="form-control"
                                       placeholder="柜子ID"
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
                <a href="<?= $urlManager->createUrl([$urlStr . '/cabinet-edit']) ?>" class="btn btn-primary" style="margin-top: 1rem;"><i
                        class="iconfont icon-playlistadd"></i>添加柜子</a>
            </div>

        </div>
        
        <table class="table table-bordered bg-white">
            <tr>
                <td style="text-align: center;">ID</td>
                <td style="text-align: center; width: 200px;">柜子ID</td>
                <td style="text-align: center;">投放时间</td>
                <td style="text-align: center;">仓库分类</td>
                <td style="text-align: center;">投放城市</td>
                <td style="text-align: center;">经度</td>
                <td style="text-align: center;">纬度</td>
                <td style="text-align: center;">类型</td>
                <td style="text-align: center;">操作</td>
            </tr>
            <?php foreach ($goodsList as $index => $value) : ?>
                <tr>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['id'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['cabinet_id'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['put_in_time'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['warehouse_name'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['province'] ?>-<?= $value['city'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['longitude'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['latitude'] ?>
                    </td>
                    <td class="nowrap" style="text-align: center;">
                        <?= $value['cabinet_type'] ?>
                    </td>
                    <td class="nowrap">
                        <a class="btn btn-sm btn-primary"
                           href="<?= $urlManager->createUrl([$urlStr . '/cabinet-edit', 'id' => $value['id']]) ?>">修改</a>
                        <a class="btn btn-sm btn-danger del" attr-id="<?= $value['id']?>">删除</a>
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
        if (confirm("是否删除？")) {
            $.ajax({
                url: '<?= $urlManager->createUrl(['mch/cabinet/cabinet-del'])?>',
                type: 'get',
                dataType: 'json',
                data: {
                    id: $(this).attr('attr-id'),
                },
                success: function (res) {
                    if (res.code == 0) {
                        window.location.reload();
                    }else{
                        alert(res.msg)
                    }
                }
            });
        }
        return false;
    });
</script>
