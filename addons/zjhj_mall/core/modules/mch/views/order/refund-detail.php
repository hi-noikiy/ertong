<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 14:15
 */
defined('YII_ENV') or exit('Access Denied');


use yii\widgets\LinkPager;

/* @var \app\models\User $user */

$urlManager = Yii::$app->urlManager;
$statics = Yii::$app->request->baseUrl . '/statics';
$this->title = '售后订单详情';
$this->params['active_nav_group'] = 3;
$order_id = $_GET['order_id'];
$urlStr = get_plugin_url();
?>
<style>
    tr > td:first-child {
        text-align: right;
        width: 100px;
    }

    tr td {
        word-wrap: break-word;
    }

    .orderProcess{
        margin-bottom: 1.5rem;
        width: 100%;
        height: 12rem;
        border: 1px solid #ECEEEF;
        position: relative;
        margin-left: 1.1rem;
        margin-right: 1.1rem;
    }

    table .orderProcess ul{
        padding-left: 1rem;
    }

    .orderWord{
        height: 3rem;
    }
    
    .over{
        color: green;
    }

    .noOver{
        color: #888888;
    }

    .orderProcess ul{
        list-style: none;
        position: absolute;
        top:50%;
        left: 50%;
        margin-top: -4rem;
        margin-left: -28rem;
        padding-left: 0;
    }
    ul li{
        float: left;
        text-align: center;
        width: 8rem;
    }

    .orderIcon .iconfont{
        font-size: 2rem;
    }

    li i{
        height: 3.8rem;
        line-height: 3.8rem;
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
    /*查看img图片大图的弹出框样式*/
    .jump2{
        width:100%;
        height:100%;
        position: fixed;
        z-index: 99;
        background: rgba(0,0,0,0.6);
        display: none;
        top: 0;
        left: 0;
    }
    .jump2 .jump2_img{
        display: block;
        width:30%;
        height: 93%;
        margin: 2% auto;
        overflow-y: scroll;
    }
    .jump2 .jump2_img img{
        display: block;
        width:100%;
        height: auto;
    }
</style>
<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <div class="mb-3 clearfix">
            <div style="overflow-x: hidden">
                <div class="row">
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2" style="text-align: center">订单状态</td>
                            </tr>
                            
                            
                            <tr>
                                <td>订单号</td>
                                <td><?= $list['order_refund_no'] ?></td>
                            </tr>
                            <tr>
                                <td>用户</td>
                                <td><?= $list['username'] ?></td>
                            </tr>
                            <tr>
                                <tr>
                                    <td>售后状态</td>
                                    <td>
                                        <?php if ($list['refund_status'] == 0) : ?>
                                            <span class="badge badge-warning">待处理</span>
                                        <?php elseif ($list['refund_status'] == 1) : ?>
                                            <span class="badge badge-success">已处理</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tr>
                            <!-- <tr>
                                <td>支付方式</td>
                                <td>
                                    <?php if ($order['pay_type'] == 2) : ?>
                                        <span class="badge badge-success">货到付款</span>
                                    <?php elseif ($order['pay_type'] == 3) : ?>
                                        <span class="badge badge-success">余额支付</span>
                                    <?php else : ?>
                                        <span class="badge badge-success">线上支付</span>
                                    <?php endif; ?>
                                </td>
                            </tr> -->
                            <tr>
                                <td>收货信息</td>
                                <td>
                                    <div>
                                        <span>收货人：<?= $list['re_name'] ?></span>
                                    </div>
                                    <div>
                                        <span>电话：<?= $list['re_mobile'] ?></span>
                                    </div>
                                    <div>
                                        <span>收货地址：<?= $list['province'] ?><?= $list['city'] ?><?= $list['caddress'] ?></span>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2" style="text-align: center">订单金额</td>
                            </tr>
                            <tr>
                                <td>总金额<br>（含运费）</td>
                                <td><?= $list['pay_price'] ?>元</td>
                            </tr>
                            <tr>
                                <td>运费</td>
                                <td>
                                    <?php if ($list['express_price_1']) : ?>
                                        <div><?= $list['express_price_1'] ?>元</div>
                                        <div class="text-danger">包邮，运费减免</div>
                                    <?php else : ?>
                                        <?= $list['express_price'] ?>元
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php if ($list['is_agree'] == 1 && $list['refund_status']==1) : ?>
                                <table class="table table-bordered" style="border: none;">
                                    <tr>
                                        <td colspan="2" style="border: none;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">处理结果：</td>
                                        <td style="border: none;">同意退款</td>
                                        <!-- <td><?= $list['pay_price'] ?>元</td> -->
                                    </tr>
                                    <tr>
                                        <td style="border: none;">退款金额：</td>
                                        <td style="border: none;"><?= $list['refund_price'] ?>元</td>
                                        <!-- <td><?= $list['pay_price'] ?>元</td> -->
                                    </tr>
                                    <tr>
                                        <td style="border: none;">备注：</td>
                                        <td style="border: none;"><?= $list['refuse_desc'] ?></td>
                                        <!-- <td><?= $list['pay_price'] ?>元</td> -->
                                    </tr>
                                </table>
                            <?php elseif ($list['is_agree'] == 2 && $list['refund_status'] == 3) : ?>
                                <table class="table table-bordered" style="border: none;">
                                    <tr>
                                        <td colspan="2" style="border: none;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">处理结果：</td>
                                        <td style="border: none;">拒绝退款</td>
                                        <!-- <td><?= $list['pay_price'] ?>元</td> -->
                                    </tr>
                                    <tr>
                                        <td style="border: none;">备注：</td>
                                        <td style="border: none;"><?= $list['refuse_desc'] ?></td>
                                        <!-- <td><?= $list['pay_price'] ?>元</td> -->
                                    </tr>
                                </table> 
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="3" style="text-align: center">商品信息</td>
                            </tr>
                            <?php foreach ($goods_list as $index => $value) : ?>
                                <tr>
                                    <?php if ($list['discount'] && $list['discount'] != 10 && $value['is_level'] == 1) : ?>
                                    <td rowspan="5">商品<?= $index + 1 ?></td>
                                    <?php else: ?>
                                        <td rowspan="4">商品<?= $index + 1 ?></td>
                                    <?php endif; ?>
                                    <td class="text-right">商品名</td>
                                    <td><?= $value['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>规格</td>
                                    <td>
                                        <div>
                                        <span class="text-danger">
                                            <?php $attr_list = json_decode($value['attr']); ?>
                                            <?php if (is_array($attr_list)) :
                                                foreach ($attr_list as $attr) : ?>
                                                <span class="mr-3"><?= $attr->attr_group_name ?>
                                                    :<?= $attr->attr_name ?></span>
                                                <?php endforeach;endif; ?>
                                        </span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>数量</td>
                                    <td><?= $value['num'] . $value['unit'] ?></td>
                                </tr>
                                <tr>
                                    <td>小计</td>
                                    <td><?= $value['total_price'] ?>元</td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td>退款理由</td>
                                <td colspan="2"><?= $list['refund_desc'] ?></td>
                            </tr>
                            <!-- <tr>
                                <td>详细说明</td>
                                <td colspan="2"><?= $order['content'] ?></td>
                            </tr> -->
                            
                            <tr>
                                <td>图片</td>
                                <td colspan="2">
                                    <?php foreach ($list['refund_pic_list'] as $index => $value) : ?>
                                        <div class="goods-pic"
                                         style="background-image: url('<?= $value ?>')"
                                         attr-url="<?= $value ?>"></div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- 添加按钮开始 -->
    <div style="margin-left: 0;" class="form-group row text-center">
        <div style="margin:0 auto; height: 5rem;">

            <!-- <a class="btn btn-primary auto-form-btn" href="javascript:" data-toggle="modal" data-target="#retreatModal"
                                               onclick="refund_retreat(<?= $list['order_refund_id'] ?>,<?= $list['refund_price'] ?>)"
                                               data-id="<?= $list['order_refund_id'] ?>"
                                               data-price="<?= $list['refund_price'] ?>">同意退款</a>
            <a class="btn btn-primary auto-form-btn disagree-btn-1" onclick="refund_refuse(<?= $list['order_refund_id'] ?>)" data-id="<?= $order_item['order_refund_id'] ?>" href="javascript:" style="margin-left: 1.5rem">拒绝退款</a> -->
            <?php if ($list['refund_status'] == 0) : ?>
                <a class="btn btn-primary auto-form-btn" href="javascript:" data-toggle="modal" data-target="#retreatModal"
                                               onclick="refund_retreat(<?= $list['order_refund_id'] ?>,<?= $list['refund_price'] ?>)"
                                               data-id="<?= $list['order_refund_id'] ?>"
                                               data-price="<?= $list['refund_price'] ?>">同意退款</a>
                <a class="btn btn-primary auto-form-btn disagree-btn-1" onclick="refund_refuse(<?= $list['order_refund_id'] ?>)" data-id="<?= $order_item['order_refund_id'] ?>" href="javascript:" style="margin-left: 1.5rem">拒绝退款</a>
            <?php elseif ($list['refund_status'] == 1) : ?>
            <?php elseif ($list['refund_status'] == 2) : ?>
            <?php endif; ?>
            <input type="button" class="btn btn-default ml-4"
                   name="Submit" onclick="javascript:history.back(-1);" value="返回">
        </div>
    </div>
    <!-- 添加按钮结束 -->
</div>
<div class="jump2">
    <div class="jump2_img"></div>
</div>
<?= $this->render('/layouts/order-refund', [
    'orderType' => 'STORE'
]) ?>
<script>
    $('.btn-success').on('click', function () {
        var seller_comments = $("#seller_comments").val();
        var btn = $(this);
        btn.btnLoading("正在提交");
        var url = "<?=$urlManager->createUrl([$urlStr.'/seller-comments', 'order_id' => $order_id])?>";
        $.ajax({
            url: url,
            type: "get",
            data: {
                seller_comments: seller_comments,
            },
            dataType: "json",
            success: function (res) {
                $.myAlert({
                    content:res.msg,
                    confirm:function(e){
                        if(res.code == 0){
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });
    $(".goods-pic").on("click",function(){

        var src_url=$(this).attr('attr-url')

        $(".jump2").show()
        $(".jump2_img").html('<img src="'+  src_url +'">')
    })
    $(".jump2").on("click",function(){
        $(".jump2").hide()
        return false;
    })
</script>