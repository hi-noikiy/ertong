<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            轮播图>编辑
        </h3>
    </div>
    <div class="panel-body">
        <form id="sign-form" class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="name" id="name" value="<?php  echo $list['name'];?>">
                    <input type="hidden" name="id" value="<?php  echo $list['id'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">图片（375*160）</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('bimg',$list['bimg']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">链接</label>
                <div class="col-sm-8">
                    <div class="input-group ">
                        <input type="text" class="form-control"  name="link" value="<?php  echo $list['link'];?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#sort_link">选择文章</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">状态</label>
                <div class="col-sm-8">
                    <?php  if($list['status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">排序</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="sort" value="<?php  echo $list['sort'];?>">
					<div style="color: red !important;">&#x6B64;&#x6E90;&#x7801;&#x7531;&#xFF1A;&#x65B0;&#x777F;&#x6E90;&#x7801;&#x793E;&#x533A;www.010xr.com&#x53D1;&#x5E03;</div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                    <a class="btn btn-default" href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m']));?>">返回</a>
                    <input id="res" name="res" type="reset" style="display:none;" />
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
                    <nav data-dismiss="modal" data-href="../active/list" class="btn btn-default btn-sm" title="优惠活动">优惠活动</nav>
                    <nav data-dismiss="modal" data-href="../video/video" class="btn btn-default btn-sm" title="视频">视频</nav>
                    <nav data-dismiss="modal" data-href="../user/user" class="btn btn-default btn-sm" title="我的">我的</nav>
                    <nav data-dismiss="modal" data-href="../news/news" class="btn btn-default btn-sm" title="新闻">新闻</nav>
                    <nav data-dismiss="modal" data-href="../contact/contact" class="btn btn-default btn-sm" title="联系我们">联系我们</nav>
                    <nav data-dismiss="modal" data-href="../service/index" class="btn btn-default btn-sm" title="课程">课程</nav>
                    <nav data-dismiss="modal" data-href="../coupon/coupon" class="btn btn-default btn-sm" title="优惠券">优惠券</nav>
                    <nav data-dismiss="modal" data-href="../cut/list" class="btn btn-default btn-sm" title="砍价">砍价</nav>
                </div>
                <div class="tab-pane" id="sut_art">
                    <iframe width="100%" height="395" frameborder="no" border="0" scrolling="no" allowtransparency="yes" src="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'article'));?>"></iframe>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" id="sort_close" type="button">关闭</button>
        </div>
        <script>
            $(function(){
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
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>",
                    data:data,
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            if($("input[name='id']").val()==""){
                                $("input[name='res']").click();
                                $("body").find(".img-responsive.img-thumbnail").attr("src","");
                            }
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