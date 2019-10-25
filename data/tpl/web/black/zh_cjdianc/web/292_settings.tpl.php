<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcss.css">
<style type="text/css">
    .dizhi{margin-top: 10px;color: #44ABF7;}
    .storeset{border-bottom: 1px solid #eee;padding-bottom: 10px;}
    .storesetfont{font-size: 14px;font-weight: bold;}
    .ygstoreimg>.input-group:nth-child(1){float: left;width: 50%;margin-right: 30px;}
    .ygstoreimg>.input-group:nth-child(2){float: left;width: 50px;}
    .btn{padding: 7px 12px;}
    .ygstoreimg>.input-group:nth-child(2)>img{width: 45px;height: 35px;margin-top: -7px;}
    .wyheader{height: 40px;}
</style>
<style type="text/css">
    .ygmargin{margin-top: 10px;color: #999;}
    input[type="radio"] + label::before {
        content: "\a0"; /*不换行空格*/
        display: inline-block;
        vertical-align: middle;
        font-size: 16px;
        width: 1em;
        height: 1em;
        margin-right: .4em;
        border-radius: 50%;
        border: 2px solid #ddd;
        text-indent: .15em;
        line-height: 1; 
    }
    input[type="radio"]:checked + label::before {
        background-color: #44ABF7;
        background-clip: content-box;
        padding: .1em;
        border: 2px solid #44ABF7;
    }
    input[type="radio"] {
        position: absolute;
        clip: rect(0, 0, 0, 0);
    }
</style>
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="javascript:void(0);">系统信息</a></li>
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                <span class="ygxian"></span>
                <div class="ygdangq storesetfont">系统信息</div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台名称</label>
                    <div class="col-sm-9">
                       <input type="text" name="url_name" class="form-control" value="<?php  echo $item['url_name'];?>" />
                    </div>
                </div>  

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台电话</label>
                    <div class="col-sm-9">
                       <input type="number" name="tel" class="form-control" value="<?php  echo $item['tel'];?>" />
                    </div>
                </div> 
         
               <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台风格颜色</label>
                    <div class="col-sm-9">
                       <?php  echo tpl_form_field_color('color', $item['color'])?> 
                        <div class="ygmargin">*不填写会默认选中蓝色</div>
                    </div>
                </div> 

               <?php  if($item['msgn']==1) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台模式</label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="mored" type="radio" name="model" value="1" <?php  if($item['model']==1) { ?>checked<?php  } ?> />
                            <label for="mored">多店版</label>              
                        </span>

                        <span class="radio-inline">
                            <input id="moredan1" type="radio" name="model" value="2" <?php  if($item['model']==2) { ?>checked<?php  } ?> /> 
                            <label for="moredan1">单店版</label>
                        </span>
                        <span class="radio-inline">
                            <input id="moredan2" type="radio" name="model" value="4" <?php  if($item['model']==4) { ?>checked<?php  } ?> /> 
                            <label for="moredan2">单店点菜版</label>
                        </span>

                        <span class="radio-inline">
                            <input id="moredan3" type="radio" name="model" value="3" <?php  if($item['model']==3 || empty($item['model'])) { ?>checked<?php  } ?> /> 
                            <label for="moredan3">过审版</label>
                        </span>
                    </div>
                </div>

                <?php  } ?> 
                <?php  if($item['msgn']==2) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台模式</label>
                    <div class="col-sm-9">

                        <span class="radio-inline">
                            <input id="moredan1" type="radio" name="model" value="2" <?php  if($item['model']==2) { ?>checked<?php  } ?> /> 
                            <label for="moredan1">单店版</label>
                        </span>
                        <span class="radio-inline">
                            <input id="moredan2" type="radio" name="model" value="4" <?php  if($item['model']==4) { ?>checked<?php  } ?> /> 
                            <label for="moredan2">单店点菜版</label>
                        </span>

                        <span class="radio-inline">
                            <input id="moredan3" type="radio" name="model" value="3" <?php  if($item['model']==3 || empty($item['model'])) { ?>checked<?php  } ?> /> 
                            <label for="moredan3">过审版</label>
                        </span>
                    </div>
                </div>

                <?php  } ?>
                <?php  if($item['model']==2 || $item['model']==4) { ?>
                <div class="form-group moren">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">默认门店</label>
                    <div class="col-sm-10 col-lg-9">
                        <select name="default_store" class="form-control" id="groupid">
                            <?php  if(is_array($stores)) { foreach($stores as $row) { ?>
                            <option value="<?php  echo $row['id'];?>" <?php  if($item['default_store']==$row['id']) { ?>selected<?php  } ?>><?php  echo $row['name'];?></option>
                            <?php  } } ?>
                        </select>
                        <span class="help-block">默认显示的门店</span>
                    </div>
                </div>
                <?php  } ?>
                
                <div class="form-group duodian">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">多店跳转模式 </label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="is_tzms1" type="radio" name="is_tzms" value="1" <?php  if($item['is_tzms']==1) { ?>checked<?php  } ?> />
                            <label for="is_tzms1">商户版</label>              
                        </span>
                        <span class="radio-inline">
                            <input id="is_tzms2" type="radio" name="is_tzms" value="2" <?php  if($item['is_tzms']==2 || empty($item['is_tzms'])) { ?>checked<?php  } ?> /> 
                            <label for="is_tzms2">外卖版</label>
                        </span>
                        <span class="help-block">*此模式只能在多店版使用,默认为跳转到商家外卖下单界面</span>
                        
                    </div>
                </div>
                <div class="form-group duodian">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">品质优选 </label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="is_psxx1" type="radio" name="is_brand" value="1" <?php  if($item['is_brand']==1) { ?>checked<?php  } ?> />
                            <label for="is_psxx1">开启</label>              
                        </span>
                        <span class="radio-inline">
                            <input id="is_psxx2" type="radio" name="is_brand" value="2" <?php  if($item['is_brand']==2 || empty($item['is_brand'])) { ?>checked<?php  } ?> /> 
                            <label for="is_psxx2">关闭</label>
                        </span>
                        <span class="help-block">*关闭后首页的品质优选模块隐藏</span>
                        
                    </div>
                </div>
                <?php  if($item['qggn']==1) { ?>
                <div class="form-group duodian">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">限时抢购 </label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="is_qg1" type="radio" name="is_qg" value="1" <?php  if($item['is_qg']==1) { ?>checked<?php  } ?> />
                            <label for="is_qg1">开启</label>              
                        </span>
                        <span class="radio-inline">
                            <input id="is_qg2" type="radio" name="is_qg" value="2" <?php  if($item['is_qg']==2 || empty($item['is_qg'])) { ?>checked<?php  } ?> /> 
                            <label for="is_qg2">关闭</label>
                        </span>
                        <span class="help-block">*关闭后首页的限时抢购模块隐藏</span>
                        
                    </div>
                </div>
                <?php  } ?>
                <div class="form-group duodian">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">周边买啥 </label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="is_zb1" type="radio" name="is_zb" value="1" <?php  if($item['is_zb']==1) { ?>checked<?php  } ?> />
                            <label for="is_zb1">开启</label>              
                        </span>
                        <span class="radio-inline">
                            <input id="is_zb2" type="radio" name="is_zb" value="2" <?php  if($item['is_zb']==2 || empty($item['is_zb'])) { ?>checked<?php  } ?> /> 
                            <label for="is_zb2">关闭</label>
                        </span>
                        <span class="help-block">*关闭后首页的周边买啥模块隐藏</span>
                        
                    </div>
                </div>
                   <div class="form-group duodian">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">多店首页店铺列表营业状态 </label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="isyykg1" type="radio" name="isyykg" value="1" <?php  if($item['isyykg']==1) { ?>checked<?php  } ?> />
                            <label for="isyykg1">显示</label>              
                        </span>
                        <span class="radio-inline">
                            <input id="isyykg2" type="radio" name="isyykg" value="2" <?php  if($item['isyykg']==2 || empty($item['isyykg'])) { ?>checked<?php  } ?> /> 
                            <label for="isyykg2">隐藏</label>
                        </span>
                        
                    </div>
                </div>
                 <div class="form-group no_guo">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">开屏广告倒计时</label>
                    <div class="col-sm-9">
                       <input type="number" name="countdown" class="form-control" value="<?php  echo $item['countdown'];?>" />
                    </div>
                </div> 
                <div class="form-group no_guo">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页分享自定义标题</label>
                    <div class="col-sm-9">
                       <input type="text" name="fx_title" class="form-control" value="<?php  echo $item['fx_title'];?>" />
                    </div>
                </div> 
                <div class="form-group no_guo">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页店铺范围设置(米)</label>
                    <div class="col-sm-9">
                       <input type="number" name="distance" class="form-control" value="<?php  echo $item['distance'];?>" />
                        <span class="help-block">*设置10000米,则首页商家列表只显示商家离用户10000米范围内的商家,不填或填零则全部显示</span>
                    </div>
                </div>
                <!--  <div class="form-group no_guo">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">配送名称</label>
                    <div class="col-sm-9">
                       <input type="text" name="ps_name" class="form-control" value="<?php  echo $item['ps_name'];?>" />
                        <div class="ygmargin">*不填写会默认为超级跑腿</div>
                    </div>
                </div>  --> 
                <div class="form-group no_guo">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">首页分类排版 </label>
                    <div class="col-sm-9">
                        <span class="radio-inline">
                            <input id="is_psxx3" type="radio" name="fl_more" value="1" <?php  if($item['fl_more']==1) { ?>checked<?php  } ?> />
                            <label for="is_psxx3">一排四个</label>              
                        </span>
                        <span class="radio-inline">
                            <input id="is_psxx4" type="radio" name="fl_more" value="2" <?php  if($item['fl_more']==2 || empty($item['fl_more'])) { ?>checked<?php  } ?> /> 
                            <label for="is_psxx4">一排五个</label>
                        </span>
                    </div>
                </div>
                <div class="form-group guoshen">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页幻灯片</label>
                    <div class="col-sm-9">
                       <?php  echo tpl_form_field_multi_image('gs_img',$gs_img);?>
                       <span class="ygmargin">* 建议尺寸大小:750*360px</span>
                    </div>
                </div>
                <div class="form-group guoshen">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页电话</label>
                    <div class="col-sm-9">
                       <input type="text" name="gs_tel" class="form-control" value="<?php  echo $item['gs_tel'];?>" />
                    </div>
                </div>
                <div class="form-group guoshen">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页营业时间</label>
                    <div class="col-sm-9">
                       <input type="text" name="gs_time" class="form-control" value="<?php  echo $item['gs_time'];?>" />
                    </div>
                </div>
                <div class="form-group guoshen">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页地址</label>
                    <div class="col-sm-9">
                       <input type="text" name="gs_add" class="form-control" value="<?php  echo $item['gs_add'];?>" />
                    </div>
                </div>
                <div class="form-group guoshen">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页地址坐标</label>
                    <div class="col-sm-10">
                     <!-- <?php  echo tpl_form_field_coordinate('op',$list['coordinates'])?> -->
                     <input type="text" name="gs_zb" class="form-control dizhiname" value="<?php  echo $item['gs_zb'];?>" placeholder="例如:30.527540,114.346430" />
                     <a href="http://lbs.qq.com/tool/getpoint/" target="_blank">
                     <p class="dizhi">*点击获取地理位置</p></a>
                    </div>
                </div>
                <div class="form-group guoshen">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">过审页详情</label>
                    <div class="col-sm-9">
                         <?php  echo tpl_ueditor('gs_details',$item['gs_details']);?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台简介</label>
                    <div class="col-sm-9">
                         <?php  echo tpl_ueditor('details',$item['details']);?>
                    </div>
                </div>
                <div class="form-group storeset">
                    <span class="ygxian"></span>
                    <div class="ygdangq storesetfont">订单页面自定义名称:</div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">外卖</label>
                    <div class="col-sm-9">
                        <input type="text" name="wm_name" class="form-control" value="<?php  echo $item['wm_name'];?>" />
                        <div class="help-block">
                        * 请设置前台外卖订单页面名称，为空或删除为默认值
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">点餐</label>
                    <div class="col-sm-9">
                        <input type="text" name="dc_name" class="form-control" value="<?php  echo $item['dc_name'];?>" />
                        <div class="help-block">
                        * 请设置前台点餐订单页面名称，为空或删除为默认值
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">预定</label>
                    <div class="col-sm-9">
                        <input type="text" name="yd_name" class="form-control" value="<?php  echo $item['yd_name'];?>" />
                        <div class="help-block">
                        * 请设置前台预定订单页面名称，为空或删除为默认值
                        </div>
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
        // $("#frame-14").addClass("in");
        $("#frame-14").show();
        $("#yframe-14").addClass("wyactive");
        var val = $("input[name='model']:checked").val();
        console.log(val)
        guo(val)
        $("input[name=model]").click(function(){
        	 var val = $("input[name='model']:checked").val();
        	console.log(val)
        	guo(val)
        })
        function guo(val){
        	if(val==1){
        		$(".guoshen").hide()
        		$(".no_guo").show()
        		$(".moren").hide()
        		$(".duodian").show()
        	}else if(val==3){
        		$(".guoshen").show()
        		$(".no_guo").hide()
        		$(".moren").hide()
        		$(".duodian").hide()
        	}else if(val==2||val==4){
        		$(".guoshen").hide()
        		$(".no_guo").show()
        		$(".moren").show()
        		$(".duodin").hide()
        	}
        }
    })
</script>