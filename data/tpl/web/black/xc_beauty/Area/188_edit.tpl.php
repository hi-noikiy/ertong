<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            新人专区>编辑
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
                <label for="name" class="col-sm-2 control-label">副标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="title" id="title" value="<?php  echo $list['title'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">上背景图</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('bimgt',$list['bimgt']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">下背景图</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('bimgb',$list['bimgb']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">满立减功能</label>
                <div class="col-sm-8">
                    <?php  if($list['down_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="down_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="down_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">满立减小标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="down_title" id="down_title" value="<?php  echo $list['down_title'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">下单送券功能</label>
                <div class="col-sm-8">
                    <?php  if($list['order_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="order_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="order_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">下单送券小标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="order_title" id="order_title" value="<?php  echo $list['order_title'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">充值优惠功能</label>
                <div class="col-sm-8">
                    <?php  if($list['pay_type']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="pay_type">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="pay_type" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">充值优惠小标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="pay_title" id="pay_title" value="<?php  echo $list['pay_title'];?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">活动规则</label>
                <div class="col-sm-8">
                    <textarea name="content" class="form-control" rows="10"><?php  echo $list['content'];?></textarea>
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
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                    <a class="btn btn-default" href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m']));?>">返回</a>
                    <input id="res" name="res" type="reset" style="display:none;" />
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
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
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>