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
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'ad','version_id'=>$_GPC['version_id']));?>">公告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'open_ad','version_id'=>$_GPC['version_id']));?>">广告配置</a></li>
            <li role="presentation" class="active"><a>地图配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'sms','version_id'=>$_GPC['version_id']));?>">短信配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'print','version_id'=>$_GPC['version_id']));?>">打印配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'service_poster','version_id'=>$_GPC['version_id']));?>">课程海报配置</a></li>
        </ul>
        <form class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="name" id="name" value="<?php  echo $list['name'];?>">
                    <input type="hidden" name="id" value="<?php  echo $list['id'];?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">服务电话</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="mobile" id="mobile" value="<?php  echo $list['content']['mobile'];?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">服务时间</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="service" id="service" value="<?php  echo $list['content']['service'];?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">微信</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="weixin" id="weixin" value="<?php  echo $list['content']['weixin'];?>">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">邮箱</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="mail" id="mail" value="<?php  echo $list['content']['mail'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">联系我们顶部图片</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('bimg',$list['content']['bimg']);?>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">地址</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control"  name="address" id="address" value="<?php  echo $list['content']['address'];?>">
                </div>
                <div class="col-sm-4">
                    <button type="button" class="btn btn-default search" style="margin-right:5px;">查询</button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">地图</label>
                <div class="col-sm-10">
                    <div id="container" style="width: 100%;height: 400px;"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label"></label>
                <label for="name" class="col-sm-2 control-label" style="width: auto;padding-left: 15px;">经度</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control"  name="longitude" value="<?php  echo $list['content']['longitude'];?>">
                </div>
                <label for="name" class="col-sm-2 control-label" style="width: auto;">纬度</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control"  name="latitude" value="<?php  echo $list['content']['latitude'];?>">
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
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=CE3BZ-ZH6AW-TDQRN-ORJH6-HSPIE-7XB3W"></script>
<script>
    $(function(){
        var markers=[];
        var longitude=$("input[name='longitude']").val();
        var latitude=$("input[name='latitude']").val();
        if(longitude!="" && latitude!=""){
            var center = new qq.maps.LatLng(latitude, longitude);
        }else{
            var center=new qq.maps.LatLng(39.916527,116.397128);
        }
        var map = new qq.maps.Map(document.getElementById('container'),{
            center: center,
            zoom: 16
        });
        var marker = new qq.maps.Marker({
            map:map,
            position: center
        });
        markers.push(marker);
        //调用地址解析类
        geocoder = new qq.maps.Geocoder({
            complete : function(result){
                console.log(result);
                if(result.detail.location!=""){
                    map.setCenter(result.detail.location);
                    console.log(result.detail.location);
                    $("input[name='longitude']").val(result.detail.location.lng);
                    $("input[name='latitude']").val(result.detail.location.lat);
                    clearOverlays(markers);
                    var marker = new qq.maps.Marker({
                        map:map,
                        position: result.detail.location
                    });
                    markers.push(marker);
                }
                console.log(result);
                if(result.detail.address!=""){
                    $("input[name='address']").val(result.detail.address);
                }
            }
        });
        if($("input[name='address']").val()==""){
            geocoder.getAddress(center);
        }
        qq.maps.event.addListener(
                map,
                'click',
                function(event) {
                    $("input[name='longitude']").val(event.latLng.getLng());
                    $("input[name='latitude']").val(event.latLng.getLat());
                    var center = new qq.maps.LatLng(event.latLng.getLat(), event.latLng.getLng());
                    clearOverlays(markers);
                    var marker = new qq.maps.Marker({
                        map:map,
                        position: center
                    });
                    markers.push(marker);
                    geocoder.getAddress(center);
                }
        );
        $(".search").click(function(){
            var content=$("input[name='address']").val();
            console.log(content);
            if(content!=""){
                geocoder.getLocation(content);
            }
        });
    })
    function clearOverlays(overlays) {
        var overlay;
        if(overlays.length>0){
            while (overlay = overlays.pop() ) {
                overlay.setMap(null);
            }
        }

    }
</script>
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("input[name='submit']").click(function(){
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemap','version_id'=>$_GPC['version_id']));?>",
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
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>