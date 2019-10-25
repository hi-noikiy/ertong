<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<style>
    .type1,.type2,.type3,.type4{
        display: none;
    }
</style>
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
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'ad','version_id'=>$_GPC['version_id']));?>">公告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'open_ad','version_id'=>$_GPC['version_id']));?>">广告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'map','version_id'=>$_GPC['version_id']));?>">地图配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'sms','version_id'=>$_GPC['version_id']));?>">短信配置</a></li>
            <li role="presentation" class="active"><a>打印配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'service_poster','version_id'=>$_GPC['version_id']));?>">课程海报配置</a></li>
        </ul>
        <form class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'saveprint','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="name" id="name" value="<?php  echo $list['name'];?>">
                    <input type="hidden" name="id" value="<?php  echo $list['id'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">打印状态</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">打印接口</label>
                <div class="col-sm-8">
                    <label class="radio inline">
                        <input type="radio" class="ui-radio" name="type" id="type1" value="1" <?php  if($list['content']['type']==1) { ?>checked<?php  } ?>>易联云
                    </label>
                    <label class="radio inline" style="width:60px;">
                        <input type="radio" class="ui-radio" name="type" id="type2" value="2" <?php  if($list['content']['type']==2) { ?>checked<?php  } ?>>飞鹅云
                    </label>
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">API 密钥</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="apikey" id="apikey" value="<?php  echo $list['content']['apikey'];?>">
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">打印机终端号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="machine_code" id="machine_code" value="<?php  echo $list['content']['machine_code'];?>">
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">打印机终端密钥</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="msign" id="msign" value="<?php  echo $list['content']['msign'];?>">
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">用户id</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="partner" id="partner" value="<?php  echo $list['content']['partner'];?>">
                </div>
            </div>
            <div class="form-group type2">
                <label  class="col-sm-2 control-label">用户名</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="user" id="user" value="<?php  echo $list['content']['user'];?>">
                </div>
            </div>
            <div class="form-group type2">
                <label  class="col-sm-2 control-label">UKEY</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="ukey" id="ukey" value="<?php  echo $list['content']['ukey'];?>">
                </div>
            </div>
            <div class="form-group type2">
                <label  class="col-sm-2 control-label">打印机编号</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="sn" id="sn" value="<?php  echo $list['content']['sn'];?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                    <button type="button" class="btn btn-default test">测试</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    if($("#type1").is(":checked")){
        $(".type1").show();
    }
    if($("#type2").is(":checked")){
        $(".type2").show();
    }
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("input[name='type']").change(function(){
                if($("#type1").is(":checked")){
                    $(".type1").show();
                }else{
                    $(".type1").hide();
                }
                if($("#type2").is(":checked")){
                    $(".type2").show();
                }else{
                    $(".type2").hide();
                }
            });
            $(".test").click(function(){
                var type;
                if($("#type1").is(":checked")){
                    type=1;
                }
                if($("#type2").is(":checked")){
                    type=2;
                }
                if(type==1){
                    var apikey=$("#apikey").val();
                    var msign=$("#msign").val();
                    var machine_code=$("#machine_code").val();
                    var partner=$("#partner").val();
                    if(apikey!="" && msign!="" && machine_code!="" && partner!=""){
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'printtest','version_id'=>$_GPC['version_id']));?>",
                            data:{apikey:apikey,msign:msign,machine_code:machine_code,partner:partner,type:type},
                            dataType:'json',
                            success:function(res){
                                console.log(res);
                                swal('发送成功!', '', 'success');
                            }
                        });
                    }else{
                        swal('发送失败!', '', 'error');
                    }
                }else if(type==2){
                    var user=$("#user").val();
                    var ukey=$("#ukey").val();
                    var sn=$("#sn").val();
                    if(user!="" && ukey!="" && sn!=""){
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'printtest','version_id'=>$_GPC['version_id']));?>",
                            data:{user:user,ukey:ukey,sn:sn,type:type},
                            dataType:'json',
                            success:function(res){
                                console.log(res);
                                swal('发送成功!', '', 'success');
                            }
                        });
                    }else{
                        swal('发送失败!', '', 'error');
                    }
                }
            });
            $("input[name='submit']").click(function(){
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'saveprint','version_id'=>$_GPC['version_id']));?>",
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