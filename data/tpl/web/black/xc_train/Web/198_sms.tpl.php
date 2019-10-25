<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<style>
    .type1,.type2,.type3,.type4{
        display: none;
    }
    .parameter{
        overflow: hidden;
    }
    .parameter>input:nth-child(1){
        width: 30%;
        float: left;
    }
    .parameter>input:nth-child(2){
        width: 70%;
        float: left;
    }
    .parameter+.parameter{
        margin-top: 5px;
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
            <li role="presentation" class="active"><a>短信配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'print','version_id'=>$_GPC['version_id']));?>">打印配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'service_poster','version_id'=>$_GPC['version_id']));?>">课程海报配置</a></li>
        </ul>
        <form class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savesms','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="name" id="name" value="<?php  echo $list['name'];?>">
                    <input type="hidden" name="id" value="<?php  echo $list['id'];?>">
                    <input type="hidden" name="customize">
                    <input type="hidden" name="post">
                    <input type="hidden" name="get">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">短信接口</label>
                <div class="col-sm-8">
                    <label class="radio inline">
                        <input type="radio" class="ui-radio" name="type" id="type1" value="1" <?php  if($list['content']['type']==1) { ?>checked<?php  } ?>>阿里云
                    </label>
                    <label class="radio inline" style="width:60px;">
                        <input type="radio" class="ui-radio" name="type" id="type2" value="2" <?php  if($list['content']['type']==2) { ?>checked<?php  } ?>>聚合数据
                    </label>
                    <label class="radio inline" style="width: 100px;">
                        <input type="radio" class="ui-radio" name="type" id="type3" value="3" <?php  if($list['content']['type']==3) { ?>checked<?php  } ?>>自定义接口
                    </label>
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">AccessKeyId</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="AccessKeyId" id="AccessKeyId" value="<?php  echo $list['content']['AccessKeyId'];?>">
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">AccessKeySecret</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="AccessKeySecret" id="AccessKeySecret" value="<?php  echo $list['content']['AccessKeySecret'];?>">
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">短信签名</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="sign" id="sign" value="<?php  echo $list['content']['sign'];?>">
                </div>
            </div>
            <div class="form-group type1">
                <label  class="col-sm-2 control-label">模版CODE</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="code" id="code" value="<?php  echo $list['content']['code'];?>">
                </div>
            </div>
            <div class="form-group type2">
                <label  class="col-sm-2 control-label">APPKEY</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="key" id="key" value="<?php  echo $list['content']['key'];?>">
                </div>
            </div>
            <div class="form-group type2">
                <label  class="col-sm-2 control-label">短信模板ID</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="tpl_id" id="tpl_id" value="<?php  echo $list['content']['tpl_id'];?>">
                </div>
            </div>
            <div class="form-group type3">
                <label  class="col-sm-2 control-label">接口地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="url" id="url" value="<?php  echo $list['content']['url'];?>">
                    <span class="help-block">参数{{webnamex}}为小程序名，{{trade}}为订单号，{{amount}}为总价，{{namex}}为姓名，{{phonex}}为手机号，{{service}}为课程</span>
                </div>
            </div>
            <div class="form-group type3 customize">
                <label  class="col-sm-2 control-label">自定义参数</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['customize']) { ?>
                    <?php  if(is_array($list['content']['customize'])) { foreach($list['content']['customize'] as $index => $item) { ?>
                    <div class="input-group parameter">
                        <input type="text" class="form-control"  name="attr" value="<?php  echo $item['attr'];?>">
                        <input type="text" class="form-control"  name="value" value="<?php  echo $item['value'];?>">
                        <span class="input-group-btn" onclick="parameter.parameter_add(this)">
                            <button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                        <span class="input-group-btn" onclick="parameter.parameter_del(this)">
                            <button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <?php  } } ?>
                    <?php  } else { ?>
                    <div class="input-group parameter">
                        <input type="text" class="form-control"  name="attr" value="">
                        <input type="text" class="form-control"  name="value" value="">
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
            <div class="form-group type3 post">
                <label  class="col-sm-2 control-label">post</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['post']) { ?>
                    <?php  if(is_array($list['content']['post'])) { foreach($list['content']['post'] as $index => $item) { ?>
                    <div class="input-group parameter">
                        <input type="text" class="form-control"  name="attr" value="<?php  echo $item['attr'];?>">
                        <input type="text" class="form-control"  name="value" value="<?php  echo $item['value'];?>">
                        <span class="input-group-btn" onclick="parameter.parameter_add(this)">
                            <button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                        <span class="input-group-btn" onclick="parameter.parameter_del(this)">
                            <button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <?php  } } ?>
                    <?php  } else { ?>
                    <div class="input-group parameter">
                        <input type="text" class="form-control"  name="attr" value="">
                        <input type="text" class="form-control"  name="value" value="">
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
            <div class="form-group type3 get">
                <label  class="col-sm-2 control-label">get</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['get']) { ?>
                    <?php  if(is_array($list['content']['get'])) { foreach($list['content']['get'] as $index => $item) { ?>
                    <div class="input-group parameter">
                        <input type="text" class="form-control"  name="attr" value="<?php  echo $item['attr'];?>">
                        <input type="text" class="form-control"  name="value" value="<?php  echo $item['value'];?>">
                        <span class="input-group-btn" onclick="parameter.parameter_add(this)">
                            <button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
                        </span>
                        <span class="input-group-btn" onclick="parameter.parameter_del(this)">
                            <button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button>
                        </span>
                    </div>
                    <?php  } } ?>
                    <?php  } else { ?>
                    <div class="input-group parameter">
                        <input type="text" class="form-control"  name="attr" value="">
                        <input type="text" class="form-control"  name="value" value="">
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
                        $(objc).parent().after('<div class="input-group parameter"> <input type="text" class="form-control"  name="attr" value=""> <input type="text" class="form-control"  name="value" value=""> <span class="input-group-btn" onclick="parameter.parameter_add(this)"> <button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button> </span> <span class="input-group-btn" onclick="parameter.parameter_del(this)"> <button class="btn btn-danger" type="button"><i class="fa fa-remove"></i></button> </span></div>');
                    },
                    parameter_del:function(objc){
                        if($(objc).parent().siblings().length>0){
                            $(objc).parent().remove();
                        }
                    }
                }
            </script>
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
            <div class="form-group type4">
                <label  class="col-sm-2 control-label">手机号</label>
                <div class="col-sm-8">
                    <div class="input-group ">
                        <input type="text" placeholder="接收预约消息" class="form-control"  name="mobile" id="mobile" value="<?php  echo $list['content']['mobile'];?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default test" type="button">短信测试</button>
                        </span>
                    </div>
                    <span class="help-block type1"><a target="_blank" href="https://help.aliyun.com/document_detail/59210.html?spm=5176.sms-account.109.13.3d6e12dbByb91i">请在阿里云（https://help.aliyun.com）开通短信业务<br/>
                        模板变量${webnamex}替换成小程序名，${trade}替换成订单号，${amount}替换成总价，${namex}替换成姓名，${phonex}替换成手机号，${service}替换成课程<br/></a>
                    </span>
                    <span class="help-block type2">请在聚合数据平台（https://www.juhe.cn）开通短信业务<br/>
                        模板变量#webnamex#替换成小程序名，#trade#替换成订单号，#amount#替换成总价，#namex#替换成姓名，#phonex#替换成手机号，#service#替换成课程<br/>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                    <button type="button" class="btn btn-default type3 test">测试</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    if($("#type1").is(":checked")){
        $(".type1").show();
        $(".type4").show();
    }
    if($("#type2").is(":checked")){
        $(".type2").show();
        $(".type4").show();
    }
    if($("#type3").is(":checked")){
        $(".type3").show();
        $(".type4").hide();
    }else{
        $(".type3").hide();
    }
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("input[name='type']").change(function(){
                if($("#type1").is(":checked")){
                    $(".type1").show();
                    $(".type4").show();
                }else{
                    $(".type1").hide();
                }
                if($("#type2").is(":checked")){
                    $(".type2").show();
                    $(".type4").show();
                }else{
                    $(".type2").hide();
                }
                if($("#type3").is(":checked")){
                    $(".type3").show();
                    $(".type4").hide();
                }else{
                    $(".type3").hide();
                }
            });
            $(".test").click(function(){
                var type=1;
                if($("#type1").is(":checked")){
                    type=1;
                }
                if($("#type2").is(":checked")){
                    type=2;
                }
                if($("#type3").is(":checked")){
                    type=3;
                }
                if(type==1){
                    var AccessKeyId=$("#AccessKeyId").val();
                    var AccessKeySecret=$("#AccessKeySecret").val();
                    var sign=$("#sign").val();
                    var code=$("#code").val();
                    var mobile=$("#mobile").val();
                    if(AccessKeyId!="" && AccessKeySecret!="" && sign!="" && code!="" && mobile!=""){
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'smstest','version_id'=>$_GPC['version_id']));?>",
                            data:{AccessKeyId:AccessKeyId,AccessKeySecret:AccessKeySecret,sign:sign,code:code,mobile:mobile,type:type},
                            dataType:'json',
                            success:function(res){
                                console.log(res);
                                if(res.Code=='OK'){
                                    swal('发送成功!', '', 'success');
                                }else{
                                    swal('发送失败!', '', 'error');
                                }
                            }
                        });
                    }else{
                        swal('发送失败!', '', 'error');
                    }
                }else if(type==2){
                    var key=$("#key").val();
                    var tpl_id=$("#tpl_id").val();
                    var mobile=$("#mobile").val();
                    if(key!="" && tpl_id!="" && mobile!=""){
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'smstest','version_id'=>$_GPC['version_id']));?>",
                            data:{type:type,key:key,tpl_id:tpl_id,mobile:mobile},
                            dataType:'json',
                            success:function(res){
                                console.log(res);
                                if(res.error_code==0){
                                    swal('发送成功!', '', 'success');
                                }else{
                                    swal('发送失败!', '', 'error');
                                }
                            }
                        });
                    }else{
                        swal('发送失败!', '', 'error');
                    }
                }else if(type==3){
                    var url=$("#url").val();
                    if(url!=""){
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/post',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>",
                            data:{type:type,url:url},
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
                var customize=[];
                $(".customize").find(".parameter").each(function(){
                    var data={};
                    if($(this).find("input[name='attr']").val()!="" && $(this).find("input[name='value']").val()!=""){
                        data.attr=$(this).find("input[name='attr']").val();
                        data.value=$(this).find("input[name='value']").val();
                        customize.push(data);
                    }
                });
                var post=[];
                $(".post").find(".parameter").each(function(){
                    var data={};
                    if($(this).find("input[name='attr']").val()!="" && $(this).find("input[name='value']").val()!=""){
                        data.attr=$(this).find("input[name='attr']").val();
                        data.value=$(this).find("input[name='value']").val();
                        post.push(data);
                    }
                });
                var get=[];
                $(".get").find(".parameter").each(function(){
                    var data={};
                    if($(this).find("input[name='attr']").val()!="" && $(this).find("input[name='value']").val()!=""){
                        data.attr=$(this).find("input[name='attr']").val();
                        data.value=$(this).find("input[name='value']").val();
                        get.push(data);
                    }
                });
                $("input[name='customize']").val(JSON.stringify(customize));
                $("input[name='post']").val(JSON.stringify(post));
                $("input[name='get']").val(JSON.stringify(get));
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savesms','version_id'=>$_GPC['version_id']));?>",
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