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
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">网站配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'theme','version_id'=>$_GPC['version_id']));?>">主题配置</a></li>
            <li role="presentation" class="active"><a>公告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'open_ad','version_id'=>$_GPC['version_id']));?>">广告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'map','version_id'=>$_GPC['version_id']));?>">地图配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'sms','version_id'=>$_GPC['version_id']));?>">短信配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'print','version_id'=>$_GPC['version_id']));?>">打印配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'service_poster','version_id'=>$_GPC['version_id']));?>">课程海报配置</a></li>
        </ul>
        <form class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/announcement',array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="name" id="name" value="<?php  echo $list['name'];?>">
                    <input type="hidden" name="id" value="<?php  echo $list['id'];?>">
                </div>
            </div>
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
                <label  class="col-sm-2 control-label">公告</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['list']) { ?>
                    <?php  if(is_array($list['content']['list'])) { foreach($list['content']['list'] as $index => $item) { ?>
                    <div class="input-group" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" name="list[]" value="<?php  echo $item;?>">
                        <span class="input-group-btn" onclick="parameter.parameter_add(this)">
                            <button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                        <span class="input-group-btn" onclick="parameter.parameter_del(this)">
                            <button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <?php  } } ?>
                    <?php  } else { ?>
                    <div class="input-group" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" name="list[]" value="">
                        <span class="input-group-btn" onclick="parameter.parameter_add(this)">
                            <button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                        <span class="input-group-btn" onclick="parameter.parameter_del(this)">
                            <button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <?php  } ?>
                </div>
            </div>
            <script>
                var parameter={
                    parameter_add:function(objc){
                        $(objc).parent().after('<div class="input-group" style="margin-bottom: 10px;"> ' +
                                '<input type="text" class="form-control" name="list[]" value=""> ' +
                                '<span class="input-group-btn" onclick="parameter.parameter_add(this)"> ' +
                                '<button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button> ' +
                                '</span> ' +
                                '<span class="input-group-btn" onclick="parameter.parameter_del(this)"> ' +
                                '<button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button> ' +
                                '</span> ' +
                                '</div>')
                    },
                    parameter_del:function(objc){
                        if($(objc).parent().siblings().length>0){
                            $(objc).parent().remove();
                        }
                    }
                }
            </script>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                </div>
            </div>
        </form>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("input[name='submit']").click(function(){
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savead','version_id'=>$_GPC['version_id']));?>",
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