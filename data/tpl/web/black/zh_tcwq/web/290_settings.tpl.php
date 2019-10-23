<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcss.css">
<style type="text/css">
.dizhi{margin-top: 10px;color: #44ABF7;}

</style>
<ul class="nav nav-tabs">    
<span class="ygxian"></span>
<div class="ygdangq">当前位置:</div>
<li class="active"><a href="javascript:void(0);">基本信息</a></li>
</ul>
<div class="main">
<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
  <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
  <div class="panel panel-default ygdefault">
    <div class="panel-heading wyheader">
      基本信息
    </div>
    <div class="panel-body">
      <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台名称</label>
        <div class="col-sm-9">
         <input type="text" name="pt_name" class="form-control" value="<?php  echo $item['pt_name'];?>" />
       </div>
     </div>  
     <div class="form-group">
      <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台电话</label>
      <div class="col-sm-9">
        <input type="text" name="tel"  class="form-control" value="<?php  echo $item['tel'];?>" />
      </div>
    </div>       
    <div class="form-group">
      <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台风格颜色</label>
      <div class="col-sm-9">
       <?php  echo tpl_form_field_color('color', $item['color'])?> 
       <span class="help-block">*不填写会默认选中红色</span>
     </div>
   </div> 
   <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页转发自定义标题</label>
    <div class="col-sm-9">
      <input type="text" name="zf_title"  class="form-control" value="<?php  echo $item['zf_title'];?>" />
    </div>
  </div>  

  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台模式</label>
    <div class="col-sm-9">

      <span class="radio-inline">
        <input id="moredan1" type="radio" name="model" value="1" <?php  if($item['model']==1) { ?>checked<?php  } ?> /> 
        <!-- <input id="moredan1" type="radio" name="model" value="1"/>  -->
        <label for="moredan1">运营版</label>
      </span>
      <span class="radio-inline">
        <input id="moredan2" type="radio" name="model" value="2" <?php  if($item['model']==2) { ?>checked<?php  } ?> /> 
        <!-- <input id="moredan2" type="radio" name="model" value="2"/>  -->
        <label for="moredan2">过审版</label>
      </span>
    </div>
  </div>
  <div class="form-group Operate">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">开屏广告</label>
    <div class="col-sm-9">
     <?php  echo tpl_form_field_multi_image('kp_img',$kp_img);?>
     <span class="help-block">* 建议尺寸大小:563*878px</span>
   </div>
 </div>  
 <div class="form-group Operate">
  <label class="col-xs-12 col-sm-3 col-md-2 control-label">开屏广告链接</label>
  <div class="col-sm-9">
   <input type="text" name="kp_url"  class="form-control" value="<?php  echo $item['kp_url'];?>" />
   <!--         <span class="help-block">*空或不填则为默认值</span> -->
 </div>
</div> 
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">开屏广告倒计时(秒)</label>
<div class="col-sm-9">
 <input type="number" name="kp_time"  class="form-control" value="<?php  echo $item['kp_time'];?>" />
 <span class="help-block">*空或不填则为默认值</span>
</div>
</div>
<div class="form-group trial">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页幻灯片</label>
<div class="col-sm-9">
 <?php  echo tpl_form_field_multi_image('gs_img',$gs_img);?>
 <span class="ygmargin">* 建议尺寸大小:750*360px</span>
</div>
</div>
<div class="form-group trial">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页电话</label>
<div class="col-sm-9">
 <input type="text" name="gs_tel" class="form-control" value="<?php  echo $item['gs_tel'];?>" />
</div>
</div>
<div class="form-group trial">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页营业时间</label>
<div class="col-sm-9">
 <input type="text" name="gs_time" class="form-control" value="<?php  echo $item['gs_time'];?>" />
</div>
</div>
<div class="form-group trial">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页地址</label>
<div class="col-sm-9">
 <input type="text" name="gs_add" class="form-control" value="<?php  echo $item['gs_add'];?>" />
</div>
</div>
<div class="form-group trial">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页地址坐标</label>
<div class="col-sm-10">
 <!-- <?php  echo tpl_form_field_coordinate('op',$list['coordinates'])?> -->
 <input type="text" name="gs_zb" class="form-control dizhiname" value="<?php  echo $item['gs_zb'];?>" placeholder="例如:30.527540,114.346430" />
 <a href="http://lbs.qq.com/tool/getpoint/" target="_blank">
   <p class="dizhi">*点击获取地理位置</p></a>
 </div>
</div>
<div class="form-group trial">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页详情</label>
<div class="col-sm-9">
 <?php  echo tpl_ueditor('gs_details',$item['gs_details']);?>
</div>
</div>  
<div class="form-group Operate">
<label for="lastname" class="col-sm-2 control-label">首页标题城市是否显示</label>
<div class="col-sm-10">
<label class="radio-inline">
 <input type="radio" name="is_city" value="1" <?php  if($item['is_city']==1 ) { ?>checked<?php  } ?> />是
</label>
<label class="radio-inline">
 <input type="radio" name="is_city" value="2" <?php  if($item['is_city']==2|| empty($item['is_city'])) { ?>checked<?php  } ?> />否
</label>
</div>
</div>
<div class="form-group Operate">
<label for="lastname" class="col-sm-2 control-label">天气和总访问量是否显示</label>
<div class="col-sm-10">
<label class="radio-inline">
 <input type="radio" name="zfwl_open" value="1" <?php  if($item['zfwl_open']==1 || empty($item['zfwl_open']) ) { ?>checked<?php  } ?> />是
</label>
<label class="radio-inline">
 <input type="radio" name="zfwl_open" value="2" <?php  if($item['zfwl_open']==2) { ?>checked<?php  } ?> />否
</label>
</div>
</div> 
<div class="form-group Operate">
<label for="lastname" class="col-sm-2 control-label">客服按钮</label>
<div class="col-sm-10">
<label class="radio-inline">
 <input type="radio" name="is_kf" value="1" <?php  if($item['is_kf']==1 ) { ?>checked<?php  } ?> />显示
</label>
<label class="radio-inline">
 <input type="radio" name="is_kf" value="2" <?php  if($item['is_kf']==2|| empty($item['is_kf'])) { ?>checked<?php  } ?> />隐藏
</label>
</div>
</div>

<div class="form-group Operate">
<label for="lastname" class="col-sm-2 control-label">苹果手机支付</label>
<div class="col-sm-10">
<label class="radio-inline">
 <input type="radio" name="is_pgzf" value="1" <?php  if($item['is_pgzf']==1 ) { ?>checked<?php  } ?> />开启
</label>
<label class="radio-inline">
 <input type="radio" name="is_pgzf" value="2" <?php  if($item['is_pgzf']==2|| empty($item['is_pgzf'])) { ?>checked<?php  } ?> />关闭
</label>
</div>
</div> 
          
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">平台访问量</label>
<div class="col-sm-9">
 <input type="number" name="total_num"  class="form-control" value="<?php  echo $item['total_num'];?>" />
 <span class="help-block">*空或不填则为真实访问数据</span>
</div>
</div>
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">帖子资讯最大随机数设置</label>
<div class="col-sm-9">
 <input type="number" name="sj_max"  class="form-control" value="<?php  echo $item['sj_max'];?>" />
 <span class="help-block">*最大只能输入9(用于生成帖子和资讯的阅读量,不输入值此功能无效)</span>
</div>
</div>
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">商家浏览量最大随机数设置</label>
<div class="col-sm-9">
 <input type="number" name="sj_max2"  class="form-control" value="<?php  echo $item['sj_max2'];?>" />
 <span class="help-block">*最大只能输入9(用于生成商家的浏览量,不输入值此功能无效)</span>
</div>
</div>
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">总访问量最大随机数设置</label>
<div class="col-sm-9">
 <input type="number" name="zfwl_max"  class="form-control" value="<?php  echo $item['zfwl_max'];?>" />
 <span class="help-block">*最大只能输入9(用于生成总访问量,不输入值此功能无效)</span>
</div>
</div>  
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">首页弹框图片</label>
<div class="col-sm-9">
  <?php  echo tpl_form_field_image('tc_img', $item['tc_img'])?>
  <span class="help-block">*建议尺寸380*300</span>
</div>
</div>
<div class="form-group Operate">
<label class="col-xs-12 col-sm-3 col-md-2 control-label">首页弹框公告</label>
<div class="col-sm-9">
 <input type="text" name="tc_gg"  class="form-control" value="<?php  echo $item['tc_gg'];?>" />
</div>
</div>

              <!--   <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">是否开启商家入驻</label>
                <div class="col-sm-10">
                     <label class="radio-inline">
                        <input type="radio" name="is_sjrz" value="1" <?php  if($item['is_sjrz']==1 || empty($item['is_sjrz'])) { ?>checked<?php  } ?> />开启
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_sjrz" value="2" <?php  if($item['is_sjrz']==2) { ?>checked<?php  } ?> />关闭
                    </label>
                </div>
                              </div>
                              <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">是否开启拼车</label>
                <div class="col-sm-10">
                     <label class="radio-inline">
                        <input type="radio" name="is_pcfw" value="1" <?php  if($item['is_pcfw']==1 || empty($item['is_pcfw'])) { ?>checked<?php  } ?> />开启
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_pcfw" value="2" <?php  if($item['is_pcfw']==2) { ?>checked<?php  } ?> />关闭
                    </label>
                </div>
                              </div>
                              <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">是否开启合伙人</label>
                <div class="col-sm-10">
                     <label class="radio-inline">
                        <input type="radio" name="is_hhr" value="1" <?php  if($item['is_hhr']==1 || empty($item['is_hhr'])) { ?>checked<?php  } ?> />开启
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_hhr" value="2" <?php  if($item['is_hhr']==2) { ?>checked<?php  } ?> />关闭
                    </label>
                </div>
                              </div>
                              <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">是否开启红包福利</label>
                <div class="col-sm-10">
                     <label class="radio-inline">
                        <input type="radio" name="is_hbfl" value="1" <?php  if($item['is_hbfl']==1 || empty($item['is_hbfl'])) { ?>checked<?php  } ?> />开启
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_hbfl" value="2" <?php  if($item['is_hbfl']==2) { ?>checked<?php  } ?> />关闭
                    </label>
                </div>
                              </div>
                               <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">是否开启帖子</label>
                <div class="col-sm-10">
                     <label class="radio-inline">
                        <input type="radio" name="is_tzopen" value="1" <?php  if($item['is_tzopen']==1 || empty($item['is_tzopen'])) { ?>checked<?php  } ?> />开启
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_tzopen" value="2" <?php  if($item['is_tzopen']==2) { ?>checked<?php  } ?> />关闭
                    </label>
                </div>
                              </div>
                               <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">是否开启黄页114</label>
                <div class="col-sm-10">
                     <label class="radio-inline">
                        <input type="radio" name="is_pageopen" value="1" <?php  if($item['is_pageopen']==1 || empty($item['is_pageopen'])) { ?>checked<?php  } ?> />开启
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_pageopen" value="2" <?php  if($item['is_pageopen']==2) { ?>checked<?php  } ?> />关闭
                    </label>
                </div>
              </div> -->
              
              <div class="form-group Operate">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台简介</label>
                <div class="col-sm-9">
                 <?php  echo tpl_ueditor('details',$item['details']);?>
               </div>
             </div>
             
           </div>

         </div>

         <div class="form-group">
          <input type="submit" name="submit" value="提交" class="btn col-lg-3" style="color: white;background-color: #44ABF7;"/>
          <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
          <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
        </div>
      </form>
    </div>
    <script type="text/javascript">
      $(function(){
        $("#frame-12").show();
        $("#yframe-12").addClass("wyactive");
        trial($('input:radio:checked').val())
        function trial(val){
         if(val==1){
          $(".trial").hide()
          $(".Operate").show()
        }else{
          $(".trial").show()
          $(".Operate").hide()
        }
      }
      $("#moredan1").click(function(){
      	console.log($("#moredan1").val())
      	trial($("#moredan1").val())
      })
      $("#moredan2").click(function(){
      	console.log($("#moredan2").val())
      	trial($("#moredan2").val())
      })
      
    })
  </script>