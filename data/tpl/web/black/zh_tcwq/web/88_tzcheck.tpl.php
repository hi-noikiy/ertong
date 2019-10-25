<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcss.css">
<style type="text/css">
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
    <li class="active"><a href="javascript:void(0);">帖子设置</a></li>
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                帖子设置
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">帖子审核</label>
                    <div class="col-sm-10">
                       <label class="radio-inline">
                        <input type="radio" id="emailwy1" name="tz_audit" value="1" <?php  if($item['tz_audit']==1 || empty($item['tz_audit'])) { ?>checked<?php  } ?> />
                        <label for="emailwy1">开启</label>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" id="emailwy2" name="tz_audit" value="2" <?php  if($item['tz_audit']==2) { ?>checked<?php  } ?> />
                        <label for="emailwy2">关闭</label>
                    </label>
                    <div class="help-block">*是否开启帖子信息审核，关闭则为信息免审核状态</div>
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-sm-2 control-label">全国版</label>
                <div class="col-sm-10">
                   <label class="radio-inline">
                    <input type="radio" id="emailwy3" name="is_qgb" value="1" <?php  if($item['is_qgb']==1 || empty($item['is_qgb'])) { ?>checked<?php  } ?> />
                    <label for="emailwy3">开启</label>
                </label>
                <label class="radio-inline">
                    <input type="radio" id="emailwy4" name="is_qgb" value="2" <?php  if($item['is_qgb']==2) { ?>checked<?php  } ?> />
                    <label for="emailwy4">关闭</label>
                </label>
                <div class="help-block">*关闭后发帖不会出现全国版选项</div>
            </div>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-sm-2 control-label">发帖地址</label>
            <div class="col-sm-10">
               <label class="radio-inline">
                <input type="radio" id="emailwy7" name="is_tzdz" value="1" <?php  if($item['is_tzdz']==1 || empty($item['is_tzdz'])) { ?>checked<?php  } ?> />
                <label for="emailwy7">开启</label>
            </label>
            <label class="radio-inline">
                <input type="radio" id="emailwy8" name="is_tzdz" value="2" <?php  if($item['is_tzdz']==2) { ?>checked<?php  } ?> />
                <label for="emailwy8">关闭</label>
            </label>
            <div class="help-block">*关闭后发帖不用填写地址,帖子列表也不会显示地址和导航功能</div>
        </div>
    </div>
        <div class="form-group">
            <label for="lastname" class="col-sm-2 control-label">帖子附近</label>
            <div class="col-sm-10">
               <label class="radio-inline">
                <input type="radio" id="emailwy9" name="fj_tz" value="1" <?php  if($item['fj_tz']==1 || empty($item['fj_tz'])) { ?>checked<?php  } ?> />
                <label for="emailwy9">显示</label>
            </label>
            <label class="radio-inline">
                <input type="radio" id="emailwy10" name="fj_tz" value="2" <?php  if($item['fj_tz']==2) { ?>checked<?php  } ?> />
                <label for="emailwy10">隐藏</label>
            </label>
            <div class="help-block">*关闭后发帖不用填写地址,帖子列表也不会显示地址和导航功能</div>
        </div>
    </div>
   <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">帖子浏览数</label>
    <div class="col-sm-9">
     <input type="number" name="tz_num"  class="form-control" value="<?php  echo $item['tz_num'];?>" />
     <div class="help-block">*数据为0或不填，则为真实帖子数据</div>
 </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">帖子小程序端文本</label>
    <div class="col-sm-9">
     <input type="text" name="tzmc"  class="form-control" value="<?php  echo $item['tzmc'];?>" />

 </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包小程序端文本</label>
    <div class="col-sm-9">
     <input type="text" name="hb_name"  class="form-control" value="<?php  echo $item['hb_name'];?>" />

 </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">福利小程序端文本</label>
    <div class="col-sm-9">
     <input type="text" name="fl_name"  class="form-control" value="<?php  echo $item['fl_name'];?>" />

 </div>
</div>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">发帖限制</label>
    <div class="col-sm-9">
     <input type="number" name="ft_num"  class="form-control" value="<?php  echo $item['ft_num'];?>" />
     <div class="help-block">*用户每天发帖上限,数据为0或不填,则没有限制</div>
 </div>
</div>
<div class="form-group">
    <label for="lastname" class="col-sm-2 control-label">转发至朋友圈</label>
    <div class="col-sm-10">
       <label class="radio-inline">
        <input type="radio" id="is_zf" name="is_zf" value="1" <?php  if($item['is_zf']==1 || empty($item['is_zf'])) { ?>checked<?php  } ?> />
        <label for="is_zf">开启</label>
    </label>
    <label class="radio-inline">
        <input type="radio" id="is_zf2" name="is_zf" value="2" <?php  if($item['is_zf']==2) { ?>checked<?php  } ?> />
        <label for="is_zf2">关闭</label>
    </label>
    <div class="help-block">*关闭后没有帖子详情页面没有此按钮</div>
</div>
</div>
<div class="form-group">
    <label for="lastname" class="col-sm-2 control-label">拨打电话</label>
    <div class="col-sm-10">
       <label class="radio-inline">
        <input type="radio" id="is_bdtel" name="is_bdtel" value="1" <?php  if($item['is_bdtel']==1 || empty($item['is_bdtel'])) { ?>checked<?php  } ?> />
        <label for="is_bdtel">显示</label>
    </label>
    <label class="radio-inline">
        <input type="radio" id="is_bdtel2" name="is_bdtel" value="2" <?php  if($item['is_bdtel']==2) { ?>checked<?php  } ?> />
        <label for="is_bdtel2">隐藏</label>
    </label>
    <div class="help-block">*关闭后没有帖子没有拨打电话</div>
</div>
</div>
<!--                 <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">查看电话付费</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="is_ff" name="is_ff" value="1" <?php  if($item['is_ff']==1 || empty($item['is_ff'])) { ?>checked<?php  } ?> />
                            <label for="is_ff">是</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="is_ff2" name="is_ff" value="2" <?php  if($item['is_ff']==2) { ?>checked<?php  } ?> />
                            <label for="is_ff2">否</label>
                        </label>
                        <div class="help-block">*关闭后没有帖子没有拨打电话</div>
                    </div>
                </div> -->
             <!--    <div class="form-group"
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包手续费(%)</label>
                    <div class="col-sm-9">
                       <input type="number" name="hb_sxf"  class="form-control" value="<?php  echo $item['hb_sxf'];?>" />
                       <div class="help-block">*发布帖子中，发红包、置顶等所提取的手续费，0或不填则不收取该费用</div>
                    </div>
                </div>
                
               <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包分享群内容设置</label>
                    <div class="col-sm-9">
                        <input type="text" name="hb_content" value="<?php  echo $item['hb_content'];?>" id="review_user" class="form-control" />
                        <div class="help-block">*tyep为分享的帖子所属的分类,name为分享者用户昵称,例如:type,红包来袭,name邀请你赶快来抢吧！</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">红包转发背景图片</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_form_field_image('hb_img', $item['hb_img'])?>
                        <span class="help-block">*建议比例 100*100px</span>
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">福利页面背景图</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_form_field_image('hbbj_img', $item['hbbj_img'])?>
                        <span class="help-block">*建议比例 100*100px</span>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">发帖须知</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_ueditor('ft_xuz',$item['ft_xuz']);?>
                        <span class="help-block">*请输入发帖须知</span>
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
        $("#frame-1").show();
        $("#yframe-1").addClass("wyactive");
    })
</script>