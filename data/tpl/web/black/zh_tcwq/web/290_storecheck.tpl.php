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
    <li class="active"><a href="javascript:void(0);">审核设置</a></li>

</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                审核设置
            </div>
            <div class="panel-body">
                 <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">商家审核</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="emailwy1" name="sj_audit" value="1" <?php  if($item['sj_audit']==1 || empty($item['sj_audit'])) { ?>checked<?php  } ?> />
                            <label for="emailwy1">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy2" name="sj_audit" value="2" <?php  if($item['sj_audit']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy2">关闭</label>
                        </label>
                        <span class="help-block">*是否开启商家入驻信息审核，关闭则为信息免审核状态</span>
                    </div>
                </div>
                <!--   <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">优惠券审核</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="is_yhqsh1" name="is_yhqsh" value="1" <?php  if($item['is_yhqsh']==1 || empty($item['is_yhqsh'])) { ?>checked<?php  } ?> />
                            <label for="is_yhqsh1">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="is_yhqsh2" name="is_yhqsh" value="2" <?php  if($item['is_yhqsh']==2) { ?>checked<?php  } ?> />
                            <label for="is_yhqsh2">关闭</label>
                        </label>
                        <span class="help-block">*是否开启优惠券审核，关闭则商家发券不需要后台审核</span>
                    </div>
                </div> -->
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">全国版</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="emailwy7" name="is_qgb2" value="1" <?php  if($item['is_qgb2']==1 || empty($item['is_qgb2'])) { ?>checked<?php  } ?> />
                            <label for="emailwy7">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy8" name="is_qgb2" value="2" <?php  if($item['is_qgb2']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy8">关闭</label>
                        </label>
                        <span class="help-block">*关闭后商家入驻不显示全国版选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">店内设施</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="is_dnss7" name="is_dnss" value="1" <?php  if($item['is_dnss']==1 || empty($item['is_dnss'])) { ?>checked<?php  } ?> />
                            <label for="is_dnss7">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="is_dnss8" name="is_dnss" value="2" <?php  if($item['is_dnss']==2) { ?>checked<?php  } ?> />
                            <label for="is_dnss8">关闭</label>
                        </label>
                        <span class="help-block">*开启后入驻商家时显示店内设施选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">VR演示</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="emailwy9" name="is_vr" value="1" <?php  if($item['is_vr']==1 || empty($item['is_vr'])) { ?>checked<?php  } ?> />
                            <label for="emailwy9">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy10" name="is_vr" value="2" <?php  if($item['is_vr']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy10">关闭</label>
                        </label>
                        <span class="help-block">*开启后入驻商家时显示VR演示选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">营业时间</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="emailwy11" name="is_yysj" value="1" <?php  if($item['is_yysj']==1 || empty($item['is_yysj'])) { ?>checked<?php  } ?> />
                            <label for="emailwy11">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy12" name="is_yysj" value="2" <?php  if($item['is_yysj']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy12">关闭</label>
                        </label>
                        <span class="help-block">*开启后入驻商家时显示营业时间选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">身份证和营业执照上传</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="emailwy3" name="is_img" value="1" <?php  if($item['is_img']==1 || empty($item['is_img'])) { ?>checked<?php  } ?> />
                            <label for="emailwy3">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy4" name="is_img" value="2" <?php  if($item['is_img']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy4">关闭</label>
                        </label>
                        <span class="help-block">*开启后小程序端商家入驻不在有上传身份证和营业执照的选项</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启入驻入口</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="emailwy5" name="is_rz" value="1" <?php  if($item['is_rz']==1 || empty($item['is_rz'])) { ?>checked<?php  } ?> />
                            <label for="emailwy5">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy6" name="is_rz" value="2" <?php  if($item['is_rz']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy6">关闭</label>
                        </label>
                        <span class="help-block">*关闭后小程序端不显示我要入驻入口</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">品质商家</label>
                    <div class="col-sm-10">
                         <label class="radio-inline">
                            <input type="radio" id="is_pzsj" name="is_pzsj" value="1" <?php  if($item['is_pzsj']==1 || empty($item['is_pzsj'])) { ?>checked<?php  } ?> />
                            <label for="is_pzsj">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="is_pzsj2" name="is_pzsj" value="2" <?php  if($item['is_pzsj']==2) { ?>checked<?php  } ?> />
                            <label for="is_pzsj2">关闭</label>
                        </label>
                        <span class="help-block">*开启后首页显示品质商家板块</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">入驻须知</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_ueditor('rz_xuz',$item['rz_xuz']);?>
                        <span class="help-block">*请输入商家入驻须知</span>
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
        $("#frame-0").show();
        $("#yframe-0").addClass("wyactive");
    })
</script>