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
$this->title = '企业用户审核详情';
$this->params['active_nav_group'] = 3;
$order_id = $_GET['order_id'];
$urlStr = get_plugin_url();
?>
<style>
    .col-md-6{
        flex: 0 0 100%;
        max-width: 100%;
    }
    tr > td:first-child {
        text-align: right;
        width: 100px;
    }

    tr td {
        word-wrap: break-word;
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
        width:50%;
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
                                <td>ID</td>
                                <td><?= $list['id'] ?></td>
                            </tr>
                            <tr>
                                <td>昵称</td>
                                <td><?= $list['username'] ?></td>
                            </tr>
                            <tr>
                                <td>绑定手机号</td>
                                <td><?= $list['binding'] ?></td>
                            </tr>
                            <tr>
                                <td>上级</td>
                                <td><?= $list['nickname'] ?></td>
                            </tr>
                            <tr>
                                <td>申请时间</td>
                                <td><?= $list['addtime'] ?></td>
                            </tr>
                            <tr>
                                <td>营业执照</td>
                                <td><img class="upload-preview-img" src="<?= $list['enterprise_license'] ?>" style='width: 40rem'><a class="btn btn-sm btn-primary image1" style="color: none;" href="javascript:;">查看大图</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="jump2">
    <div class="jump2_img"></div>
</div>
</div>
<script>
    $(".image1").on("click",function(){
        
        var src_url=$(".upload-preview-img").attr('src')
        console.log(src_url);
        $(".jump2").show()
        $(".jump2_img").html('<img src="'+  src_url +'">')
    })
    $(".jump2").on("click",function(){
        $(".jump2").hide()
        return false;
    })

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

</script>