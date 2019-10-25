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

    <li><a href="<?php  echo $this->createWebUrl('llz')?>">流量主管理</a></li>

    <li class="active"><a href="<?php  echo $this->createWebUrl('addllz')?>">添加流量主</a></li>

</ul>

<div class="main ygmain">

    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">

        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->

        <div class="panel panel-default ygdefault">

            <div class="panel-heading wyheader">

                内容编辑

            </div>

            <div class="panel-body">

                <div class="form-group">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">流量主类型</label>

                    <div class="col-sm-9">

                            <select name="type" class="col-md-5">

                            <option value="1" <?php  if($info['type']==1) { ?>selected<?php  } ?>>帖子详情流量主</option>
                            <option value="2" <?php  if($info['type']==2) { ?>selected<?php  } ?>>首页流量主</option>
                            <option value="3" <?php  if($info['type']==3) { ?>selected<?php  } ?>>黄页流量主</option>
                            <option value="4" <?php  if($info['type']==4) { ?>selected<?php  } ?>>顺风车流量主</option>
                            <option value="5" <?php  if($info['type']==5) { ?>selected<?php  } ?>>视频详情流量主</option>
                            <option value="6" <?php  if($info['type']==6) { ?>selected<?php  } ?>>活动详情流量主</option>

                            </select>

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">标题</label>

                    <div class="col-sm-9">

                        <input type="text" name="name" class="form-control" value="<?php  echo $info['name'];?>" />

                    </div>

                </div>               





                <div class="form-group ygyi2">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">广告位ID</label>

                    <div class="col-sm-9">

                        <input type="text" name="src" class="form-control" value="<?php  echo $info['src'];?>" />

                       <!--  <span class="font1">*此链接为网页外部跳转链接，需要在小程序后台配置业务域名。</span> -->

                    </div>

                </div>

                <div class="form-group ygyi2">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">所属城市</label>

                    <div class="col-sm-9">

                        <input type="text" name="cityname" class="form-control" value="<?php  echo $info['cityname'];?>" />

                       <!--  <span class="font1">*此链接为网页外部跳转链接，需要在小程序后台配置业务域名。</span> -->

                    </div>

                </div>

                <div class="form-group">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否禁用</label>

                    <div class="col-sm-9">

                        <label class="radio-inline">

                            <input type="radio" id="emailwy1" name="status" value="1" <?php  if($info['status']==1 || empty($info['status'])) { ?>checked<?php  } ?> />

                            <label for="emailwy1">启用</label>

                        </label>

                        <label class="radio-inline">

                            <input type="radio" id="emailwy2" name="status" value="2" <?php  if($info['status']==2) { ?>checked<?php  } ?> />

                            <label for="emailwy2">禁用</label>

                        </label>    

                    </div>

                </div>

            </div>



        </div>



        <div class="form-group">

            <input type="submit" name="submit" value="提交" class="btn col-lg-3" style="color: white;background-color: #44ABF7;"/>

            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />

            <input type="hidden" name="id" value="<?php  echo $info['id'];?>" />

        </div>

    </form>

</div>

<script type="text/javascript">

    $(function(){



        $("#frame-6").show();

        $("#yframe-6").addClass("wyactive");

    })

</script>