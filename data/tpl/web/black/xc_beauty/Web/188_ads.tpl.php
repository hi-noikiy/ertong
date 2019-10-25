<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php  echo $xtitle;?>
        </h3>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'web','version_id'=>$_GPC['version_id']));?>">网站配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'sort','version_id'=>$_GPC['version_id']));?>">首页排版</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'map','version_id'=>$_GPC['version_id']));?>">地图配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'theme','version_id'=>$_GPC['version_id']));?>">主题配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'sms','version_id'=>$_GPC['version_id']));?>">短信配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'print','version_id'=>$_GPC['version_id']));?>">打印配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'card','version_id'=>$_GPC['version_id']));?>">会员卡配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'refund','version_id'=>$_GPC['version_id']));?>">退款配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'rotate','version_id'=>$_GPC['version_id']));?>">抽奖配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'share','version_id'=>$_GPC['version_id']));?>">分销配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'ad','version_id'=>$_GPC['version_id']));?>">公告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'group','version_id'=>$_GPC['version_id']));?>">退款地址</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'online','version_id'=>$_GPC['version_id']));?>">客服配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'audit','version_id'=>$_GPC['version_id']));?>">过审配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'service_poster','version_id'=>$_GPC['version_id']));?>">分销海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'store_poster','version_id'=>$_GPC['version_id']));?>">门店海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'member_poster','version_id'=>$_GPC['version_id']));?>">店员海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'mall_poster','version_id'=>$_GPC['version_id']));?>">商城海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'package_poster','version_id'=>$_GPC['version_id']));?>">套餐海报配置</a></li>
            <li role="presentation" class="active"><a>广告配置</a></li>
        </ul>
        <form class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/announcement',array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label class="col-sm-2 control-label">状态</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">图片</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('bimg',$list['content']['bimg']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">链接</label>
                <div class="col-sm-8">
                    <div class="input-group ">
                        <input type="text" class="form-control"  name="link" value="<?php  echo $list['content']['link'];?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#sort_link">选择链接</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">频次</label>
                <div class="col-sm-8">
                    <label class="radio inline" style="width:100px;">
                        <input type="radio" class="ui-radio" name="type" id="type1" value="1" <?php  if($list['content']['type']==1) { ?>checked<?php  } ?>>首次登录弹出
                    </label>
                    <label class="radio inline" style="width:100px;">
                        <input type="radio" class="ui-radio" name="type" id="type2" value="2" <?php  if($list['content']['type']==2) { ?>checked<?php  } ?>>每天弹出一次
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="sort_link"><div class="modal-dialog">
    <style>
        #sort_link .modal-body {padding: 10px 15px;}
        #sort_link .tab-pane {margin-top: 5px; min-height: 400px; max-height: 400px; overflow-y: auto;}
        #sort_tab{margin-bottom: 10px;}
    </style>
    <div class="modal-content">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title">链接</h4>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs" id="selectUrlTab">
                <li role="presentation" class="active"><a href="#sut_shop">页面</a></li>
                <li role="presentation"><a href="#sut_art">文章</a></li>
            </ul>
            <div class="tab-content ">
                <div class="tab-pane active" id="sut_shop">
                    <nav data-dismiss="modal" data-href="../index/index" class="btn btn-default btn-sm" title="首页">首页</nav>
                    <nav data-dismiss="modal" data-href="../service/service" class="btn btn-default btn-sm" title="项目">项目</nav>
                    <nav data-dismiss="modal" data-href="../rotate/rotate" class="btn btn-default btn-sm" title="抽奖">抽奖</nav>
                    <nav data-dismiss="modal" data-href="../store/porder" class="btn btn-default btn-sm" title="预约">预约</nav>
                    <nav data-dismiss="modal" data-href="../user/user" class="btn btn-default btn-sm" title="我的">我的</nav>
                    <nav data-dismiss="modal" data-href="../../pages/card/card" class="btn btn-default btn-sm" title="会员卡">会员卡</nav>
                    <nav data-dismiss="modal" data-href="../../pages/over/over" class="btn btn-default btn-sm" title="余额">余额</nav>
                    <nav data-dismiss="modal" data-href="../../pages/score/score" class="btn btn-default btn-sm" title="积分">积分</nav>
                    <nav data-dismiss="modal" data-href="../../pages/coupon/coupon" class="btn btn-default btn-sm" title="优惠券">优惠券</nav>
                    <nav data-dismiss="modal" data-href="../../pages/order/buy" class="btn btn-default btn-sm" title="买单">买单</nav>
                    <nav data-dismiss="modal" data-href="../../pages/share/index" class="btn btn-default btn-sm" title="分销">分销</nav>
                    <nav data-dismiss="modal" data-href="../../pages/address/address" class="btn btn-default btn-sm" title="地址">地址</nav>
                    <nav style="margin-top: 5px;" data-dismiss="modal" data-href="../../pages/store/index" class="btn btn-default btn-sm" title="门店列表">门店列表</nav>
                    <nav style="margin-top: 5px;" data-dismiss="modal" data-href="../../pages/mall/mall" class="btn btn-default btn-sm" title="商城">商城</nav>
                    <nav style="margin-top: 5px;" data-dismiss="modal" data-href="../../pages/package/package" class="btn btn-default btn-sm" title="套餐">套餐</nav>
                </div>
                <div class="tab-pane" id="sut_art">
                    <iframe width="100%" height="395" frameborder="no" border="0" scrolling="no" allowtransparency="yes" src="<?php  echo $http_type;?>://<?php  echo $_SERVER['HTTP_HOST'];?>/web/index.php?c=site&a=entry&do=banner&m=<?php  echo $_GPC['m'];?>&op=article&version_id=<?php  echo $_GPC['version_id'];?>"></iframe>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" id="sort_close" type="button">关闭</button>
        </div>
        <script>
            var sort_objc='';
            $(function(){
                $(".sort_customize").on('click','.sort_link',function(){
                    var id=$(this).attr("data-id");
                    if(id==2){
                        $("#sort_link").find("#sort_tab li").eq(1).hide();
                    }else{
                        $("#sort_link").find("#sort_tab li").eq(1).show();
                    }
                    sort_objc=this;
                });
                $("#sort_link").find('#selectUrlTab a').click(function(e) {
                    $('#tab').val($(this).attr('href'));
                    e.preventDefault();
                    $(this).tab('show');
                });
                $("#sut_shop").find("nav").click(function(){
                    $(objc).parent().prev().val($(this).attr("data-href"));
                });
            });
        </script>
    </div>
</div></div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    var objc="";
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("body").on("click",'.link',function(){
                objc=this;
            });
            $("input[name='submit']").click(function(){
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'saveads','version_id'=>$_GPC['version_id']));?>",
                    data:data,
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            swal('操作成功!', '操作成功!', 'success');
                        }else{
                            swal('操作失败!', '操作失败!', 'error');
                        }
                    }
                })
            });
        })
    })
</script>