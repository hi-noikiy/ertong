<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link href="../addons/<?php  echo $_GPC['m'];?>/resource/css/poster.css?v=1.0.1" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<style>
    .btns a,.btns2 a{
        margin-bottom: 5px;
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
            <li role="presentation" class="active"><a>店员海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'mall_poster','version_id'=>$_GPC['version_id']));?>">商城海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'package_poster','version_id'=>$_GPC['version_id']));?>">套餐海报配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'ads','version_id'=>$_GPC['version_id']));?>">广告配置</a></li>
        </ul>
        <form class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/announcement',array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <input type="hidden" name="xkey" value="<?php  echo $xkey;?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">状态</label>
                <div class="col-sm-8">
                    <?php  if($xc['status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12">海报</label>
                <div class="col-md-10 col-sm-9 col-xs-12">
                    <div class="contain flex-display">
                        <div id="editdiv" data-field="content" data-id="count" data-list="list"><?php  echo $xc['content'];?></div>
                        <div class="pane2 flex-flex1">
                            <h1>海报元素</h1>
                            <div class="btns">
                                <a href="javascript:;" class="adduimg">头像</a>
                                <a href="javascript:;" class="addtitle">名称</a>
                                <a href="javascript:;" class="addstore">门店</a>
                                <a href="javascript:;" class="addduty">职称</a>
                                <a href="javascript:;" class="addqr">二维码</a>
                                <a href="javascript:;" class="addtext">文本</a>
                                <a href="javascript:;" class="addnimg">图片</a>
                            </div>
                            <div class="hideBlock hb2">
                                <h1>文字颜色</h1>
                                <div class="inputBlock flex-display flex-alignC">
                                    <div class="input colorIPT">
                                        <input id="coloript" type="text" value=''>
                                    </div>
                                    <div class="colorBlock" id="colorshow" style="background-color:;"></div>
                                </div>
                            </div>
                            <div class="hideBlock hb2">
                                <h1>文字大小</h1>
                                <div class="inputBlock flex-display flex-alignC">
                                    <div class="input colorIPT">
                                        <input id="pxipt" type="text" value=''>
                                    </div>
                                    <div class="colorBlock">px</div>
                                </div>
                            </div>
                            <div class="hideBlock hb2">
                                <h1>文字内容</h1>
                                <div class="inputBlock flex-display flex-alignC">
                                    <div class="input colorIPT" style="width: 100%;">
                                        <input id="contentpt" type="text" value=''>
                                    </div>
                                </div>
                            </div>
                            <div class="hideBlock hb3">
                                <h1>二维码尺寸</h1>
                                <select class="sizeselect">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                            <div class="hideBlock hb4">
                                <h1>图片设置</h1>
                                <?php  echo tpl_form_field_image('wwww');?>
                            </div>

                            <div class="hideBlock hb5">
                                <h1>元素操作</h1>
                                <div class="btns2">
                                    <a href="javascript:;" class="cprev">调整到上层</a>
                                    <a href="javascript:;" class="cnext">调整到下层</a>
                                    <a href="javascript:;" class="cfirst">调整到最顶层</a>
                                    <a href="javascript:;" class="clast">调整到最低层</a>
                                    <a href="javascript:;" class="cdelete">删除元素</a>
                                    <a href="javascript:;" class="cblod">文字加粗</a>
                                    <a href="javascript:;" class="ccenter">文字居中</a>
                                    <a href="javascript:;" class="cdefault">重置元素大小</a>
                                </div>
                            </div>
                        </div>
                    </div>
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
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("input[name='submit']").click(function(){
                var  $postdate=$(".form-horizontal").serializeArray();
                $postmodel = {};
                for (var i = 0; i < $postdate.length; i++) {
                    $postmodel[$postdate[i]["name"]] = $postdate[i]["value"];
                }
                $("#editdiv").find(".resize-item .resize-panel").remove();
                var parametermdoel=$("#editdiv").html();
                if(parametermdoel!=""){
                    $postmodel[$("#editdiv").attr("data-field")] = parametermdoel;
                    $postmodel[$("#editdiv").attr("data-id")]=addcount;
                }
                var parametermdoel = [];
                $("#editdiv").find(".resize-item").each(function(){
                    var parameter_li = {};
                    parameter_li['type']=$(this).attr("data-type");
                    parameter_li['width']=$(this).css("width");
                    parameter_li['height']=$(this).css("height");
                    parameter_li['top']=$(this).css("top");
                    parameter_li['left']=$(this).css("left");
                    parameter_li['z-index']=$(this).css("z-index");
                    if($(this).hasClass("text")){
                        parameter_li['color']=$(this).attr("data-color");
                        parameter_li['px']=$(this).attr("data-px");
                        parameter_li['bold']=$(this).attr("data-bold");
                        parameter_li['center']=$(this).attr("data-center");
                        if($(this).attr("data-type")=="text"){
                            parameter_li['content']=$(this).text();
                        }
                    }else if($(this).attr("data-type")=="img"){
                        parameter_li['content']=$(this).attr("data-src");
                    }
                    parametermdoel.push(parameter_li);
                });
                if(parametermdoel.length>0){
                    $postmodel[$("#editdiv").attr("data-list")] = parametermdoel;
                }
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'saveposter','version_id'=>$_GPC['version_id']));?>",
                    data:$postmodel,
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            swal('操作成功!', '操作成功!', 'success');
                            $("#editdiv").find(".resize-item").each(function(){
                                new ZResize({
                                    stage: "#editdiv", //舞台
                                    item: '#'+$(this).attr("id") //可缩放的类名
                                });
                            });
                        }else{
                            swal('操作失败!', '操作失败!', 'error');
                        }
                    }
                })
            });
        })
    });
</script>
<script src="../addons/<?php  echo $_GPC['m'];?>/resource/js/zresize.js?v=1.0.1"></script>
<script type="text/javascript">
    /*new ZResize({
     stage: "#editdiv", //舞台
     item: '.resize-item', //可缩放的类名
     });*/

    /*new ZResize({
     stage: "#mydiv", //舞台
     item: '#div2', //可缩放的类名
     });*/

    document.onselectstart = function(){
        return false;
    };

    $("#editdiv").find(".resize-item").each(function(){
        new ZResize({
            stage: "#editdiv", //舞台
            item: '#'+$(this).attr("id"), //可缩放的类名
        });
    });

    //模拟背景上传
    $("#file1").click(function(){
        var tmp = 'images/folder.jpg';//模拟上传后文件地址
        $("#fileshow1").val(tmp);
        $("#imgpre1").attr("src",tmp);
        $("#editdiv .bg").attr("src",tmp);
    });

    $("#closepre1").click(function(){
        $("#fileshow1").val('');
        $("#imgpre1").attr("src",'images/default-pic.jpg');
        $("#editdiv .bg").attr("src",'');
    });

    var addcount =parseInt("<?php  echo $xc['count'];?>");
    if(addcount=="" || isNaN(addcount)){
        addcount=0;
    }

    $(".adduimg").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item uimg" data-type="bimg">\
            <img src="../addons/xc_beauty/resource/images/logo.jpg">\
        </div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $(".addtext").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item text" data-color="#000" data-px="16" data-bold="-1" data-center="-1" data-type="text">自定义内容</div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $(".addtitle").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item text" data-color="#000" data-px="16" data-bold="-1" data-center="-1" data-type="name">名称</div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $(".addstore").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item text" data-color="#000" data-px="16" data-bold="-1" data-center="-1" data-type="store">门店</div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $(".addduty").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item text" data-color="#000" data-px="16" data-bold="-1" data-center="-1" data-type="duty">职称</div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $(".addqr").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item qr" data-size="3" data-type="code">\
            <img src="../addons/xc_beauty/resource/images/baseqr.png">\
        </div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $(".addnimg").click(function(){

        addcount++;

        var nid = 'ri'+addcount;
        var nidc = '#'+nid;

        $("#editdiv").append('<div id="'+nid+'" class="resize-item nimg" data-src="" data-type="img">\
        	<img src="">\
    	</div>');
        new ZResize({
            stage: "#editdiv", //舞台
            item: nidc, //可缩放的类名
        });
    });

    $("body").on("click",'#material-Modal .modal-footer .btn.btn-primary',function(){
        var tmp =$("body").find("input[name='wwww']").attr("url");//模拟上传后文件地址
        that.attr("data-src",tmp).find("img").attr("src",tmp);
    });

    var that = '';

    $("#editdiv").on("click",".resize-item",function(){
        that = $(this);
        $(".hideBlock").hide();
        $(".hb5").show();
        if(that.hasClass("text")){
            $(".hb2").show();
            $("#coloript").val(that.attr("data-color"));
            $("#colorshow").css("background-color",that.attr("data-color"));
            $("#pxipt").val(that.attr("data-px"));
            $("#contentpt").val(that.text());
        }
        else if(that.hasClass("qr")){
            $(".hb3").show();
            $(".sizeselect").val(that.attr("data-size"))
        }
        else if(that.hasClass("nimg")){
            $(".hb4").show();
            $("#fileshow2").val(that.attr("data-src"));
            $("body").find("input[name='wwww']").attr("url",that.attr("data-src"));
            $("body").find("input[name='wwww']").val(that.attr("data-src"));
            $("body").find(".img-responsive.img-thumbnail").attr("src",that.attr("data-src"));
        }
    });

    $("#coloript").change(function(){
        var cc = $(this).val();
        $("#colorshow").css("background-color",cc);
        that.attr("data-color",cc).css("color",cc);
    });

    $("#pxipt").change(function(){
        var cc = $(this).val();
        that.attr("data-px",cc).css("font-size",cc+'px');
    });

    $("#contentpt").change(function(){
        var cc = $(this).val();
        if(that.attr("data-type")=="text"){
            that.empty();
            new ZResize({
                stage: "#editdiv", //舞台
                item: "#"+that.attr("id"), //可缩放的类名
            });
            that.find(".resize-panel").before(cc);
        }
    });

    $(".sizeselect").change(function(){
        var cc = $(this).val();
        that.attr("data-size",cc);
    });

    $("#file2").click(function(){
        var tmp = 'images/folder.jpg';//模拟上传后文件地址
        $("#fileshow2").val(tmp);
        $("#imgpre2").attr("src",tmp);
        that.attr("data-src",tmp).find("img").attr("src",tmp);
    });

    $("#closepre2").click(function(){
        $("#fileshow2").val('');
        $("#imgpre2").attr("src",'images/default-pic.jpg');
        that.attr("data-src","").find("img").attr("src","images/default-pic.jpg");
    });

    $(".cprev").click(function(){
        var zi = parseInt(that.css("z-index"));
        zi++;
        that.css("z-index",zi>999?999:zi);
    });

    $(".cnext").click(function(){
        var zi = parseInt(that.css("z-index"));
        zi--;
        that.css("z-index",zi<1?1:zi);
    });

    $(".cfirst").click(function(){
        that.css("z-index",999);
    });

    $(".clast").click(function(){
        that.css("z-index",1);
    });

    $(".cblod").click(function(){
        that.attr("data-bold",1);
        that.css("font-weight",'bold');
    });

    $(".ccenter").click(function(){
        that.attr("data-center",1);
        that.css("text-align",'center');
    });

    $(".cdelete").click(function(){
        that.remove();
        that = '';
        $(".hideBlock").hide();
    });

    $(".cdefault").click(function(){
        that.css({"width":"80px","height":"80px",'font-weight':'inherit'});
        that.children('div').css({"width":"80px","height":"80px"});
        if(that.hasClass("text")){
            that.css("height","40px");
            that.attr("data-bold",-1);
            that.css("text-align",'left');
            that.attr("data-center",-1);
            that.children('div').css("height","40px");
        }
    });

</script>