<?php
defined('YII_ENV') or exit('Access Denied');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29
 * Time: 9:50
 */

use yii\widgets\LinkPager;

/* @var \app\models\User $user */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '订单列表';
$this->params['active_nav_group'] = 3;
$status = Yii::$app->request->get('status');
$is_recycle = Yii::$app->request->get('is_recycle');
$user_id = Yii::$app->request->get('user_id');
$condition = ['user_id' => $user_id, 'clerk_id' => $_GET['clerk_id'], 'shop_id' => $_GET['shop_id'], 'platform' => $_GET['platform']];
if ($status === '' || $status === null || $status == -1) {
    $status = -1;
}
if ($is_recycle == 1) {
    $status = 12;
}
$urlPlatform = Yii::$app->controller->route;
?>
<style>
    .titleColor{
        color: #888888;
    }

    .order-item {
        border: 1px solid transparent;
        margin-bottom: 1rem;
    }

    .order-item table {
        margin: 0;
    }

    .order-item:hover {
        border: 1px solid #3c8ee5;
    }

    .goods-item {
        /* margin-bottom: .75rem; */
        border: 1px solid #ECEEEF;
        padding: 10px;
        margin-top: -1px;
    }


    .table tbody tr td{
        vertical-align: middle;
    }

    .goods-item:last-child {
        margin-bottom: 0;
    }

    .goods-pic {
        width: 5.5rem;
        height: 5.5rem;
        display: inline-block;
        background-color: #ddd;
        background-size: cover;
        background-position: center;
        margin-right: 1rem;
    }

    .goods-name {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .order-tab-1 {
        width: 40%;
    }

    .order-tab-2 {
        width: 20%;
        text-align: center;
    }

    .order-tab-3 {
        width: 10%;
        text-align: center;
    }

    .order-tab-4 {
        width: 20%;
        text-align: center;
    }

    .order-tab-5 {
        width: 10%;
        text-align: center;
    }

    .status-item.active {
        color: inherit;
    }
</style>
<script language="JavaScript" src="<?= $statics ?>/mch/js/LodopFuncs.js"></script>
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0 style="display: none">
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div class="p-4 bg-shaixuan">
                <form method="get">
                    <?php $_s = ['keyword', 'keyword_1', 'date_start', 'date_end','page','per-page'] ?>
                    <?php foreach ($_GET as $_gi => $_gv) :
                        if (in_array($_gi, $_s)) {
                            continue;
                        } ?>
                        <input type="hidden" name="<?= $_gi ?>" value="<?= $_gv ?>">
                    <?php endforeach; ?>
                    <div flex="dir:left">
                        <div class="ml-3 mr-4">
                            <div class="form-group row">
                                <div>
                                    <label class="col-form-label">下单时间：</label>
                                </div>
                                <div>
                                    <div class="input-group">
                                        <input class="form-control" id="date_start" name="date_start"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_start']) ? trim($_GET['date_start']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_start" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                        <span class="middle-center input-group-addon" style="padding:0 4px">至</span>
                                        <input class="form-control" id="date_end" name="date_end"
                                               autocomplete="off"
                                               value="<?= isset($_GET['date_end']) ? trim($_GET['date_end']) : '' ?>">
                                        <span class="input-group-btn">
                                            <a class="btn btn-secondary" id="show_date_end" href="javascript:">
                                                <span class="iconfont icon-daterange"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <div class="middle-center">
                                    <a href="javascript:" class="new-day btn btn-primary" data-index="7">近7天</a>
                                    <a href="javascript:" class="new-day btn btn-primary    " data-index="30">近30天</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div flex="dir:left">
                        <div class="mr-4">
                            <div class="form-group row w-30">
                                <div class="col-4">
                                    <select class="form-control" name="keyword_1">
                                        <option value="1" <?= $_GET['keyword_1'] == 1 ? "selected" : "" ?>>订单号</option>
                                        <option value="3" <?= $_GET['keyword_1'] == 3 ? "selected" : "" ?>>收货人</option>
                                    </select>
                                </div>
                                <div class="col-8">
                                    <input class="form-control"
                                           name="keyword"
                                           autocomplete="off"
                                           value="<?= isset($_GET['keyword']) ? trim($_GET['keyword']) : null ?>">
                                </div>
                            </div>
                        </div>
                        <div class="mr-4">
                            <div class="form-group">
                                <button class="btn btn-primary mr-4">筛选</button>
                                <a class="btn btn-secondary export-btn"
                                   href="javascript:">批量导出</a>
                                <a class="btn btn-secondary del"
                                   href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/integralmall/integralmall/delete-all']) ?>"
                                   data-content="是否清空回收站？">清空回收站</a>
                            </div>
                        </div>
                    </div>
                    <div flex="dir:left">
                        <div class="mr-4">
                            <?php if ($user) : ?>
                                <span class="status-item mr-3">会员：<?= $user->nickname ?>的订单</span>
                            <?php endif; ?>
                            <?php if ($clerk) : ?>
                                <span class="status-item mr-3">核销员：<?= $clerk->nickname ?>的订单</span>
                            <?php endif; ?>
                            <?php if ($shop) : ?>
                                <span class="status-item mr-3">门店：<?= $shop->name ?>的订单</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div flex="dir:left">
                        <label class="col-form-label">来源平台：</label>
                        <div class="dropdown float-right">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($_GET['platform'] === '1') :
                                    ?>支付宝
                                <?php elseif ($_GET['platform'] === '0') :
                                    ?>微信
                                <?php elseif ($_GET['platform'] == '') :
                                    ?>全部订单
                                <?php else : ?>
                                <?php endif; ?>
                            </button>
                            <div class="dropdown-menu" style="min-width:8rem">
                                <a class="dropdown-item" href="<?= $urlManager->createUrl([$urlPlatform]) ?>">全部订单</a>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 1]) ?>">支付宝</a>
                                <a class="dropdown-item"
                                   href="<?= $urlManager->createUrl([$urlPlatform, 'platform' => 0]) ?>">微信</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="mb-4">
            <ul class="nav nav-tabs status">
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == -1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => -1])) ?>">全部</a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 0 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 0])) ?>">未付款<?= $store_data['status_count']['status_0'] ? '(' . $store_data['status_count']['status_0'] . ')' : null ?></a>

                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 4 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 4])) ?>">待确认<?= $store_data['status_count']['status_4'] ? '(' . $store_data['status_count']['status_4'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 7 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 7])) ?>">备货中<?= $store_data['status_count']['status_7'] ? '(' . $store_data['status_count']['status_7'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item nav-link <?= $status == 1 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 1])) ?>">配送中<?= $store_data['status_count']['status_1'] ? '(' . $store_data['status_count']['status_1'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 2 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 2])) ?>">待自提<?= $store_data['status_count']['status_2'] ? '(' . $store_data['status_count']['status_2'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 9 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 9])) ?>">待评价<?= $store_data['status_count']['status_9'] ? '(' . $store_data['status_count']['status_9'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 3 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 3])) ?>">已完成<?= $store_data['status_count']['status_3'] ? '(' . $store_data['status_count']['status_3'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 6 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 6])) ?>">待处理<?= $store_data['status_count']['status_6'] ? '(' . $store_data['status_count']['status_6'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 5 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 5])) ?>">已取消<?= $store_data['status_count']['status_5'] ? '(' . $store_data['status_count']['status_5'] . ')' : null ?></a>
                </li>
                <li class="nav-item">
                    <a class="status-item  nav-link <?= $status == 8 ? 'active' : null ?>"
                       href="<?= $urlManager->createUrl(array_merge([$_GET['r']], $condition, ['status' => 8])) ?>">回收站</a>
                </li>
            </ul>
        </div>
        <table class="table table-bordered bg-white">
            <tr>
                <th class="order-tab-1">商品信息</th>
                <th class="order-tab-2">金额</th>
                <th class="order-tab-3">实际付款</th>
                <th class="order-tab-4">订单状态</th>
                <th class="order-tab-5">操作</th>
            </tr>
        </table>
        <?php if ($list != null) : ?>
            <?php foreach ($list as $k => $order_item) : ?>
            <div class="order-item" style="">
                <table class="table table-bordered bg-white">
                    <tr>
                        <td colspan="5">
                            <span class="mr-3"><span class="titleColor">下单时间：</span><?= date('Y-m-d H:i:s', $order_item['addtime']) ?></span>
                            <span class="mr-1">
                                <?php if ($order_item['is_pay'] == 1) : ?>
                                    <span class="badge badge-success">已付款</span>
                                <?php else : ?>
                                    <span class="badge badge-default">未付款</span>
                                <?php endif; ?>
                            </span>
                            <?php if ($order_item['is_send'] == 1) : ?>
                                <span class="mr-1">
                                    <?php if ($order_item['put_status'] == 1) : ?>
                                        <span class="badge badge-success">配送中</span>
                                    <?php elseif ($order_item['put_status'] == 2)  : ?>
                                        <span class="badge badge-default">待自提</span>
                                    <?php elseif ($order_item['put_status'] == 3) : ?>
                                        <?php if ($order_item['is_comment'] == 0) : ?>
                                            <span class="badge badge-default">待评价</span>
                                        <?php else : ?>
                                            <span class="badge badge-default">已完成</span>
                                        <?php endif; ?>
                                        
                                    <?php endif; ?>
                                </span>
                            <?php else : ?>
                            <?php if ($order_item['is_pay'] == 1) : ?>
                                <span class="mr-1">
                                    <?php if ($order_item['is_send'] == 1) : ?>
                                        <span class="badge badge-success">已发货</span>
                                    <?php else : ?>
                                        <?php if ($order_item['is_order_confirm'] == 1) : ?>
                                            <span class="badge badge-default">备货中</span>
                                        <?php else : ?>
                                            <span class="badge badge-default">待确认</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                                    <?php endif; ?>
                            <span class="mr-5"><span class="titleColor">订单号：</span><?= $order_item['order_no'] ?></span>
                            <span class="mr-5"><span class='titleColor'>
                                用户名(ID)：</span><?= $order_item['user']['nickname'] ?> <span class='titleColor'>(<?= $order_item['user_id'] ?>)</span>
                                <?php if (isset($order_item['user']['platform']) && intval($order_item['user']['platform']) === 0): ?>
                                    <span class="badge badge-success">微信</span>
                                <?php elseif (isset($order_item['user']['platform']) && intval($order_item['user']['platform']) === 1): ?>
                                    <span class="badge badge-primary">支付宝</span>
                                <?php else: ?>
                                    <span class="badge badge-default">未知</span>
                                <?php endif; ?>
                            </span>
                            <?php if ($order_item['apply_delete'] == 1) : ?>
                                    <span class="mr-1 titleColor">
                                            申请取消该订单：
                                        <?php if ($order_item['is_delete'] == 0) : ?>
                                            <span class="badge badge-warning">申请中</span>
                                        <?php else : ?>
                                            <span class="badge badge-warning">申请成功</span>
                                        <?php endif; ?>
                                        </span>
                                <?php endif; ?>

                                <?php if ($order_item['apply_delete'] == 1) : ?>
                                    <?php if ($order_item['is_delete'] == 0) : ?>
                                        <span>
                                                <a class="btn btn-sm btn-info apply-status-btn"
                                                   href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/apply-delete-status', 'id' => $order_item['id'], 'status' => 1]) ?>">同意取消</a>
                                            </span>
                                        <span>
                                                <a class="btn btn-sm refuse btn-danger  apply-status-btn"
                                                   href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/apply-delete-status', 'id' => $order_item['id'], 'status' => 0]) ?>">拒绝取消</a>
                                            </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-tab-1">
                            <div class="goods-item" flex="dir:left box:first">
                                <div class="fs-0">
                                    <div class="goods-pic"
                                         style="background-image: url('<?= $order_item['detail']['pic'] ?>')"></div>
                                </div>
                                <div class="goods-info">
                                    <div class="goods-name"><?= $order_item['detail']['goods_name'] ?></div>
                                    <div class="mt-1">
                                    <span class="fs-sm">
                                        规格：
                                    <span class="text-danger">
                                        <?php $attr_list = json_decode($order_item['detail']['attr']); ?>
                                        <?php if (is_array($attr_list)) :
                                            foreach ($attr_list as $attr) : ?>
                                            <span class="mr-3"><?= $attr->attr_group_name ?>
                                                :<?= $attr->attr_name ?></span>
                                            <?php endforeach;
                                            ;
                                        endif; ?>
                                    </span>
                                    </span>
                                    <span class="fs-sm">数量：
                                        <span
                                            class="text-danger"><?= $order_item['detail']['num'] ?></span>
                                    </span>
                                    <div class="fs-sm">小计：
                                        <span class="text-danger"><?= $order_item['detail']['total_price'] ?>元</span></div>
                                </div>
                            </div>
                            </div>
                        </td>
                        <td class="order-tab-2">
                            <div">
                                <span class="titleColor">总金额：<span style="color:blue"><?= $order_item['total_price'] ?></span>元</span>
                                <span class="titleColor">（含运费：<span style="color:green"><?= $order_item['express_price'] ?></span>元）</span>
                            </div>
                        </td>
                        <td class="order-tab-3">
                            <div class="titleColor"><span style="color:blue;"><?= $order_item['pay_price'] ?></span>元</div>
                            <div class="titleColor"><span style="color:red;"><?= $order_item['integral'] ?></span>积分</div>
                        </td>
                        <td class="order-tab-4">
                            <?php if ($order_item['pay_type'] == 1) : ?>
                                <div>
                                    支付方式：
                                    <span class="badge badge-success">线上支付</span>
                                </div>
                            <?php endif; ?>
                            
                        </td>
                        <td class="order-tab-5">
                            <?php if (($order_item['is_pay'] == 1 ) && $order_item['put_status'] == 1 && $order_item['apply_delete'] == 0) : ?>
                            <div>

                                <?php if ($order_item['is_send'] == 1 && $order_item['is_cancel'] == 0 && $order_item['is_delete'] == 0) : ?>
                                        <a class="btn btn-sm btn-primary edit-address"
                                           data-index="<?= $k ?>"
                                           data-order-type="store" href="javascript:">修改自提柜</a>
                                    <?php endif; ?>
                                    <?php if ($order_item['is_send'] == 0 && $order_item['is_cancel'] == 0 && $order_item['is_delete'] == 0 && $order_item['is_order_confirm'] == 1) : ?>
                                        <a class="btn btn-sm btn-primary send-btn mt-2" href="javascript:"
                                           data-id="<?= $order_item['id'] ?>">
                                            发货
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($order_item['is_send'] == 0 && $order_item['is_cancel']==0 && $order_item['is_pay'] == 1 && $order_item['is_delete'] == 0 && $order_item['is_order_confirm'] == 0) : ?>
                                        <a class="btn btn-sm btn-primary mt-2 admin-order-confirm" href="javascript:"
                                           data-id="<?= $order_item['id'] ?>">
                                            确认
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($order_item['is_send'] == 0 && $order_item['is_cancel'] == 0 && $order_item['is_delete'] == 0) : ?>
                                        <a class="btn btn-sm btn-primary mt-2 admin-order-cancel"
                                           data-id="<?= $order_item['id'] ?>"
                                           href='<?= $urlManager->createUrl([$urlStr . '/apply-delete-status']) ?>'>取消</a>
                                    <?php endif; ?>
                                    <?php if (($order_item['is_pay'] == 1 || $order_item['pay_type'] == 2) && $order_item['is_offline'] == 1 && $order_item['is_send'] != 1 && $order_item['is_cancel'] == 0 && $order_item['is_delete'] == 0) : ?>
                                        <a class="btn btn-sm btn-primary clerk-btn mt-2" href="javascript:"
                                           data-order-id="<?= $order_item['id'] ?>">核销</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div>
                            <div>
                                <a class="btn btn-sm btn-info mt-2 del" href="javascript:"
                                   data-url="<?= $urlManager->createUrl([$urlStr . '/print-order', 'order_id' => $order_item['id'], 'order_type' => 0]) ?>"
                                   data-content="是否打印">小票打印</a>
                            </div>
                                <a class="btn btn-sm btn-primary mt-2"
                               href="<?= $urlManager->createUrl(['mch/integralmall/integralmall/detail', 'order_id' => $order_item['id']]) ?>">详情</a>
                            <?php if ($order_item['pay_type'] == 2 && $order_item['is_send'] == 1 && $order_item['is_confirm'] != 1 && $order_item['is_delete'] == 0) : ?>
                            </div>
                            <div>
                                <a href="javascript:" class="btn btn-sm btn-primary mt-2 del"
                                   data-url="<?= $urlManager->createUrl(['mch/integralmall/integralmall/confirm', 'order_id' => $order_item['id']]) ?>"
                                   data-content="是否确认收货？">确认收货</a>
                            </div>
                            <?php endif; ?>
                            <?php if ($order_item['is_recycle'] == 1) : ?>
                                <div>
                                    <a class="btn btn-sm btn-primary del mt-2" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/integralmall/integralmall/recycle', 'order_id' => $order_item['id'], 'is_recycle' => 0]) ?>"
                                   data-content="是否移出回收站">移出回收站</a>
                                </div>
                                <div>
                                <a class="btn btn-sm btn-danger del mt-2" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/integralmall/integralmall/delete', 'order_id' => $order_item['id']]) ?>"
                                       data-content="是否删除">删除订单</a>
                                </div>
                            <?php else : ?>
                                <div>
                                    <a class="btn btn-sm btn-danger del mt-2" href="javascript:"
                                   data-url="<?= $urlManager->createUrl(['mch/integralmall/integralmall/recycle', 'order_id' => $order_item['id'], 'is_recycle' => 1]) ?>"
                                   data-content="是否移入回收站">移入回收站</a>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <span>
                                <span class="mr-2">收货人：<?= $order_item['name'] ?></span>
                                <span class="mr-2">电话：<?= $order_item['mobile'] ?></span>
                                <?php if ($order_item['is_offline'] == 1) : ?>
                                    <span class="mr-3">地址：<?= $order_item['address'] ?></span>
                                <?php endif; ?>
                                <?php if ($order_item['is_send'] == 0) : ?>
                                    <a class="btn btn-sm btn-primary edit-address"
                                       data-index="<?= $k ?>"
                                       data-order-type="integral" href="javascript:">修改自提柜</a>
                                <?php endif; ?>
                            </span>
                            <?php if ($order_item['is_send'] == 1) : ?>
                                <?php if ($order_item['is_offline'] == 1 || $order_item['express']) : ?>
                                    <?php if ($order_item['is_offline'] == 0 || $order_item['express']) : ?>
                                    <span>
                                        <span class="titleColor"><?= $order_item['express'] ?></span>
                                        <span class="titleColor">快递单号：</span>
                                                <a href="https://www.baidu.com/s?wd=<?= $order_item['express_no'] ?>"
                                                 target="_blank"><?= $order_item['express_no'] ?></a></span>
                                    <?php endif; ?>
                                <?php elseif ($order_item['is_offline'] == 2) : ?>
                                    <span>核销员：<?= $order_item['clerk_name'] ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div <?= $order_item['remark'] ? '' : 'hidden' ?>>
                                用户备注：<?= $order_item['remark'] ?>
                            </div>
                            <?php if ($order_item['shop_id'] && $order_item['shop_id'] > 0) : ?>
                                <div>
                                    <span class="mr-3">门店名称：<?= $order_item['shop']['name'] ?></span>
                                    <span class="mr-3">门店地址：<?= $order_item['shop']['address'] ?></span>
                                    <span class="mr-3">电话：<?= $order_item['shop']['mobile'] ?></span>
                                </div>
                            <?php endif; ?>

                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach; ?>
        <?php endif ?>

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
            <div class="text-muted">共<?= $row_count ?>条数据</div>
        </div>

        <!-- 发货 -->
        <div class="modal fade send-modal" data-backdrop="static">
            <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
                <div class="modal-content">
                    <div class="modal-header">
                        <b class="modal-title">物流信息</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="send-form" method="post">
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label class=" col-form-label">物流选择</label>
                                </div>
                                <div class="col-9">
                                    <div class="pt-1">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1" value="1" checked
                                                   name="is_express" type="radio"
                                                   class="custom-control-input is-express">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">快递</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" value="0" name="is_express" type="radio"
                                                   class="custom-control-input is-express">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">无需物流</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="is-true-express">
                                <input class="form-control" type="hidden" autocomplete="off" name="order_id">
                                <label>快递公司</label>
                                <div class="input-group mb-3">
                                    <input class="form-control" placeholder="请输入快递公司" type="text" autocomplete="off"
                                           name="express" readonly>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             style="max-height: 250px;overflow: auto">
                                            <?php if (count($express_list['private'])) : ?>
                                                <?php foreach ($express_list['private'] as $item) : ?>
                                                    <a class="dropdown-item" href="javascript:"><?= $item ?></a>
                                                <?php endforeach; ?>
                                                <div class="dropdown-divider"></div>
                                            <?php endif; ?>
                                            <?php foreach ($express_list['public'] as $item) : ?>
                                                <a class="dropdown-item" href="javascript:"><?= $item ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <label>收件人邮编</label>
                                <input class="form-control" placeholder="请输入收件人邮编" type="text" autocomplete="off"
                                       name="post_code">
                                <label><a class="print" href="javascript:">打印面单</a></label>
                                <label><a href='http://www.c-lodop.com/download.html' target='_blank'>下载插件</a></label>
                                <label>快递单号</label>
                                <input class="form-control" placeholder="请输入快递单号" type="text" autocomplete="off"
                                       name="express_no">
                                <div class="text-danger mt-3 form-error" style="display: none"></div>
                            </div>
                            <div class="mt-2">
                                <label>商家留言（选填）</label>
                                <textarea class="form-control" name="words"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary send-confirm-btn">提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 修改价格 -->
<div class="modal fade" data-backdrop="static" id="price">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <b class="modal-title">价格修改</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="order-id" type="hidden">
                <div class="form-group row">
                    <label class="col-4 text-right col-form-label">商品价格修改：</label>
                    <div class="col-8">
                        <input class=" form-control money" type="number" placeholder="请填写增加或减少的价格">
                        <div class="fs-sm">注：商品价格修改，改的是订单中所有商品的总价格</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 text-right col-form-label">运费修改：</label>
                    <div class="col-8">
                        <input class=" form-control update-express" type="number" placeholder="请填写增加或减少的价格">
                        <div class="fs-sm">注：运费修改，改的是订单中运费的价格</div>
                    </div>
                </div>
                <div class="text-danger form-error mb-3" style="display: none">错误信息</div>
            </div>
            <div class="modal-footer">
                <a href="javascript:" class="btn btn-primary add-price" data-type="1">加价</a>
                <a href="javascript:" class="btn btn-primary add-price" data-type="2">优惠</a>
            </div>
        </div>
    </div>
</div>

<!--修改地址-->
<div class="modal fade" id="editAddress">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">修改自提柜地址</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">收件人</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control name" value="">
                    </div>
                </div>

                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">电话</label>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control mobile" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">发件人地区</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <select class="form-control province" style="float: left;" name="province">
                                <?php foreach ($province_arr as $key => $val) : ?>
                                    <option value="<?= $val['name'] ?>" data-index="index"><?= $val['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="form-control city" style="float: left;" name="city">
                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div style="margin-right: 10px;" class="form-group-label col-sm-2 text-right">
                        <label class="col-form-label required">柜子ID</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <select class="form-control cabinet" name="cabinet_id">
                                <!-- <option v-for="(item,index) in cabinet"
                                        :value="item.id" :data-index="index">{{item.address}}({{item.cabinet_id}})
                                </option> -->
                            </select>
                        </div>
                    </div>
                </div>
                <input style="display: none;" class="order-id" name="orderId" value="">
                <input style="display: none;" class="order-type" name="orderType" value="">
                <div class="form-group row">
                    <div class="form-group-label col-sm-2 text-right">
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-primary update-address" href="javascript:">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 后台订单确认 -->
    <div class="modal fade" data-backdrop="static" id="adminOrderConfirm">
        <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
            <div class="modal-content">
                <div class="modal-header">
                    <b class="modal-title">是否确认订单</b>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input class="order-id" type="hidden">
                    <input class="url" type="hidden">
                    <div class="form-group row">
                        <label class="col-4 text-right col-form-label">添加备注信息：</label>
                        <div class="col-11" style="margin-left: 1rem;">
                        <textarea id="order_cancel_remark-2" name="seller_comments" cols="90"
                                  rows="3"
                                  style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary order-confirm">提交</button>
                </div>
            </div>
        </div>
    </div>
<!-- 后台订单取消 -->
<div class="modal fade" data-backdrop="static" id="adminOrderCancel">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <b class="modal-title">订单取消</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="order-id" type="hidden">
                <input class="url" type="hidden">
                <div class="form-group row">
                    <label class="col-4 text-right col-form-label">填写取消理由：</label>
                    <div class="col-11" style="margin-left: 1rem;">
                    <textarea id="order_cancel_remark-1" name="seller_comments" cols="90"
                              rows="3"
                              style="width: 100%;"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary order-cancel">提交</button>
            </div>
        </div>
    </div>
</div>
<?= $this->render('/layouts/ss', [
    'exportList'=>$exportList,
    'list' => $list
]) ?>
<script type="text/javascript">
    $(document).on("click", ".edit-address", function () {
        var orderList=<?= Yii::$app->serializer->encode($list) ?>;
        var province_arr=<?= Yii::$app->serializer->encode($province_arr) ?>;
        var cabinet=<?= Yii::$app->serializer->encode($cabinet) ?>;
        var orderType = $(this).data('orderType');
        var index = $(this).data('index');
        var orderId = orderList[index].id;
        var name = orderList[index].name;
        var mobile = orderList[index].mobile;
        var cabinet_id = orderList[index].cabinet_id;
        var city_arr = orderList[index].city_arr;
        var address_arr = orderList[index].address_arr;

        $('.name').val(name);
        $('.mobile').val(mobile);
        $('.order-id').val(orderId);
        $('.order-type').val(orderType);

        var sender_province = orderList[index].province
        var sender_city = orderList[index].city
        
        $('.province').find('option').each(function (i) {
            if ($(this).val() == sender_province) {
                $(this).prop('selected', 'selected');
                return true;
            }
        });
        var cityHtml="";
        for (var i = 0; i < city_arr.length; i++) {
            if(sender_city==city_arr[i]['city']){
                cityHtml+="<option value="+city_arr[i]['city']+" data-index='i' selected >"+city_arr[i]['city']+"</option>";
            }else{
                cityHtml+="<option value="+city_arr[i]['city']+" data-index='i'>"+city_arr[i]['city']+"</option>";
            }
        }
        $(".city").html(cityHtml)
        var cabinetHtml="";
        for (var i = 0; i < address_arr.length; i++) {
            if(cabinet_id==address_arr[i]['id']){
                cabinetHtml+="<option value="+address_arr[i]['id']+" data-index='i' selected >"+address_arr[i]['address']+"("+address_arr[i]['cabinet_id']+")"+"</option>";
            }else{
                cabinetHtml+="<option value="+address_arr[i]['id']+" data-index='i'>"+address_arr[i]['address']+"("+address_arr[i]['cabinet_id']+")"+"</option>";
            }
        }
        $(".cabinet").html(cabinetHtml)
        
        $('#editAddress').modal('show');
    });
    $(document).on('change', '.province', function () {
        var province = $(this).find('option:selected').val();
        $.ajax({
            type: "POST",
            url: '<?= $urlManager->createUrl(['mch/order/select-city']) ?>',
            dataType: "json",
            data: {
                'province': province,
                _csrf: _csrf
            }, 
            cache: false,
            success: function(data) {
                if(data.code==0){
                    $(".city").empty();
                    $(".cabinet").empty();
                    var cityHtml="";
                    for (var i = 0; i < data.city_arr.length; i++) {
                        
                        cityHtml+="<option value="+data.city_arr[i]['city']+" data-index='i'>"+data.city_arr[i]['city']+"</option>";
                        
                    }
                    var cabinetHtml="";
                    for (var i = 0; i < data.address_arr.length; i++) {
                        
                        cabinetHtml+="<option value="+data.address_arr[i]['id']+" data-index='i'>"+data.address_arr[i]['address']+"("+data.address_arr[i]['cabinet_id']+")"+"</option>";
                        
                    }
                    $(".cabinet").html(cabinetHtml)
                    $(".city").html(cityHtml)
                }else{
                    alert(data.msg)
                }
            }
        });
        
    });
    $(document).on('change', '.city', function () {
        var city = $(this).find('option:selected').val();
        $.ajax({
            type: "POST",
            url: '<?= $urlManager->createUrl(['mch/order/select-address']) ?>',
            dataType: "json",
            data: {
                'city': city,
                _csrf: _csrf
            }, 
            cache: false,
            success: function(data) {
                if(data.code==0){
                    $(".cabinet").empty();
                    var cabinetHtml="";
                    for (var i = 0; i < data.address_arr.length; i++) {
                        
                        cabinetHtml+="<option value="+data.address_arr[i]['id']+" data-index='i'>"+data.address_arr[i]['address']+"("+data.address_arr[i]['cabinet_id']+")"+"</option>";
                        
                    }
                    $(".cabinet").html(cabinetHtml)
                }else{
                    alert(data.msg)
                }
            }
        });
    });
    // 提交更新
    $(document).on('click', '.update-address', function () {
        $('.update-address').btnLoading('更新中');
        var href = '<?= $urlManager->createUrl(['mch/order/update-order-address']) ?>';
        $.ajax({
            url: href,
            type: "post",
            data: {
                orderId: $('.order-id').val(),
                orderType: $('.order-type').val(),
                name: $('.name').val(),
                mobile: $('.mobile').val(),
                cabinet_id: $('.cabinet option:selected').val(),
                _csrf: _csrf
            },
            dataType: "json",
            success: function (res) {
                $('.update-address').btnReset();
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
        return false;
    });
    // 后台订单确认
    var intDiff = parseInt(60*2); //倒计时总秒数量
    var cancelUrl = '';
    var confirmId = 0;
    $(document).on("click", ".admin-order-confirm", function () {
        timer(parseInt(60*30));
        var a = $(this);
        confirmId = a.data('id');
        $('#adminOrderConfirm').modal('show');
        return false;
    });
    // 后台订单确认
    $(document).on("click", ".order-confirm", function () {
        var urlStr="<?= $urlManager->createUrl(['mch/integralmall/integralmall/apply-confirm-status']) ?>";
        
        var remark = $('#order_cancel_remark-2').val();
        
        var btn = $(this);
        btn.btnLoading(btn.text());
        $.ajax({
            url: urlStr,
            dataType: "json",
            data: {
                remark: remark,
                id: confirmId,
                type: 1
            },
            success: function (res) {
                console.log(res)
                $.myLoadingHide();
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        btn.btnReset();
                        if (res.code == 0)
                            location.reload();
                    }
                });
            }
        });
        return false;
    });
    function timer(intDiff) {
        window.setInterval(function () {
            var day = 0,
            hour = 0,
            minute = 0,
            second = 0; //时间默认值
            if (intDiff == 0) {
                var urlStr="<?= $urlManager->createUrl(['mch/integralmall/integralmall/apply-confirm-status']) ?>";
                var remark = '';
                $.ajax({
                    url: urlStr,
                    dataType: "json",
                    data: {
                        remark: remark,
                        id: confirmId,
                        type: 1
                    },
                    success: function (res) {
                        if (res.code == 0)
                            location.reload();
                    }
                });
                return false;
            }

            if(intDiff == 0){
                return false;
            }
            intDiff--;
        }, 1000);
    }
    
    // 后台点击取消
    var cancelUrl = '';
    var cancelId = 0;
    $(document).on("click", ".admin-order-cancel", function () {
        var a = $(this);
        cancelId = a.data('id');
        cancelUrl = a.attr('href');
        $('#adminOrderCancel').modal('show');
        return false;
    });
    // 后台主动取消订单
    $(document).on("click", ".order-cancel", function () {
        var remark = $('#order_cancel_remark-1').val();
        var btn = $(this);
        btn.btnLoading(btn.text());
        $.ajax({
            url: cancelUrl,
            dataType: "json",
            data: {
                remark: remark,
                id: cancelId,
                status: 1,
                type: 1
            },
            success: function (res) {
                $.myLoadingHide();
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        btn.btnReset();
                        if (res.code == 0)
                            location.reload();
                    }
                });
            }
        });
        return false;
    });
    $(document).on("click", ".send-btn", function () {
        var a = $(this);
        var order_id = a.data('id');
        if(confirm("是否确认发货?")){
            var urlStr="<?=$urlManager->createUrl(['mch/integralmall/integralmall/send'])?>";
            var btn = $(this);
            // console.log(order_id)
            btn.btnLoading("正在提交");
            $.ajax({
                url: urlStr,
                type: "post",
                dataType: "json",
                data: {
                    order_id: order_id,
                    _csrf: _csrf,
                },
                success: function (res) {
                    console.log(res)
                    $.myLoadingHide();
                    $.myAlert({
                        content: res.msg,
                        confirm: function () {
                            btn.btnReset();
                            if (res.code == 0)
                                location.reload();
                        }
                    });
                }
            });
            return false;
        　　
        }else{
            return false
        }
    });
</script>
<script>
    $(document).on('click', '.del', function () {
        var a = $(this);
        $.myConfirm({
            content: a.data('content'),
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: a.data('url'),
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            $.myAlert({
                                content:res.msg,
                                confirm:function(res){
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.myAlert({
                                content: res.msg
                            });
                        }
                    },
                    complete:function(res){
                        $.myLoadingHide();
                    }
                });
            }
        });
        return false;
    });

    $(document).on("click", ".refuse", ".apply-status-btn", function () {
        var url = $(this).attr("href");
        $.myConfirm({
            content: "确认拒绝取消该订单？",
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        $.myLoadingHide();
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0)
                                    location.reload();
                            }
                        });
                    }
                });
            }
        });
        return false;
    });

    $(document).on("click", ".btn-info", ".apply-status-btn", function () {
        var url = $(this).attr("href");
        $.myConfirm({
            content: "确认同意取消该订单并退款？",
            confirm: function () {
                $.myLoading();
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function (res) {
                        $.myLoadingHide();
                        $.myAlert({
                            content: res.msg,
                            confirm: function () {
                                if (res.code == 0)
                                    location.reload();
                            }
                        });
                    }
                });
            }
        });
        return false;
    });

    // $(document).on("click", ".send-btn", function () {
    //     var order_id = $(this).attr("data-order-id");
    //     $(".send-modal input[name=order_id]").val(order_id);
    //     var express_no = $(this).attr("data-express-no");
    //     $(".send-modal input[name=express_no]").val(express_no);
    //     var express = $(this).attr("data-express");
    //     $(".send-modal input[name=express]").val(express);
    //     $(".send-modal").modal("show");
    // });

    $(document).on("click", ".send-confirm-btn", function () {
        var btn = $(this);
        var error = $(".send-form").find(".form-error");
        btn.btnLoading("正在提交");
        error.hide();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/integralmall/integralmall/send'])?>",
            type: "post",
            data: $(".send-form").serialize(),
            dataType: "json",
            success: function (res) {
                if (res.code == 0) {
                    btn.text(res.msg);
                    location.reload();
                    $(".send-modal").modal("hide");
                }
                if (res.code == 1) {
                    btn.btnReset();
                    error.html(res.msg).show();
                }
            }
        });
    });


</script>
<!--打印函数-->
<script>
    var LODOP; //声明为全局变量
    //检测是否含有插件
    function CheckIsInstall() {
        try {
            var LODOP = getLodop();
            if (LODOP.VERSION) {
                if (LODOP.CVERSION)
                    $.myAlert({
                        content: "当前有C-Lodop云打印可用!\n C-Lodop版本:" + LODOP.CVERSION + "(内含Lodop" + LODOP.VERSION + ")"
                    });
                else
                    $.myAlert({
                        content: "本机已成功安装了Lodop控件！\n 版本号:" + LODOP.VERSION
                    });
            }
        } catch (err) {
        }
    }

    $(document).on('click', '.print', function () {
        var id = $(".send-modal input[name=order_id]").val();
        var express = $(".send-modal input[name=express]").val();
        var post_code = $(".send-modal input[name=post_code]").val();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/integralmall/integralmall/print'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                id: id,
                express: express,
                post_code: post_code
            },
            success: function (res) {
                if (res.code == 0) {
                    $(".send-modal input[name=express_no]").val(res.data.Order.LogisticCode);
                    LODOP.PRINT_INIT("");
                    LODOP.ADD_PRINT_HTM(10, 50, '100%', '100%', res.data.PrintTemplate);
                    LODOP.PRINT_DESIGN();
                } else {
                    $.myAlert({
                        content: res.msg
                    });
                }
            }
        });
    });

</script>
<script>
    $(document).on('click', '.update', function () {
        var order_id = $(this).data('id');
        $('.order-id').val(order_id);
    });
    $(document).on('click', '.add-price', function () {
        var btn = $(this);
        var order_id = $('.order-id').val();
        var price = $('.money').val();
        var update_express = $('.update-express').val();
        var type = btn.data('type');
        var error = $('.form-error');
        btn.btnLoading(btn.text());
        error.hide();
        $.ajax({
            url: "<?=$urlManager->createUrl(['mch/order/add-price'])?>",
            type: 'get',
            dataType: 'json',
            data: {
                order_id: order_id,
                price: price,
                type: type,
                update_express: update_express
            },
            success: function (res) {
                if (res.code == 0) {
                    window.location.reload();
                } else {
                    error.html(res.msg).show()
                }
            },
            complete: function (res) {
                btn.btnReset();
            }
        });
    });
    $(document).on('click', '.is-express', function () {
        if ($(this).val() == 0) {
            $('.is-true-express').prop('hidden', true);
        } else {
            $('.is-true-express').prop('hidden', false);
        }
    });

    $(document).on('click', '.clerk-btn', function () {
        $('.order_id').val($(this).data('order-id'));
        $('.clerk-modal').modal('show');
    });
</script>
