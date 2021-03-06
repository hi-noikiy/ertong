<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<style>
    .sort_status{
        overflow: hidden;
        position: absolute;
        top:0;
        left: 100%;
        width: 62px;
        height: 32px;
        padding-left: 10px;
        text-align: left;
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
            <li role="presentation" class="active"><a>网站配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'theme','version_id'=>$_GPC['version_id']));?>">主题配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'ad','version_id'=>$_GPC['version_id']));?>">公告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'open_ad','version_id'=>$_GPC['version_id']));?>">广告配置</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'map','version_id'=>$_GPC['version_id']));?>">地图配置</a></li>
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
                    <input type="hidden" name="sort">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="title" id="title" value="<?php  echo $list['content']['title'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">流量主</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['ad_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="ad_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="ad_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">广告单元id</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="unit_id" id="unit_id" value="<?php  echo $list['content']['unit_id'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">客服功能</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['online_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="online_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="online_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">客服图标（100*100）</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('online_simg',$list['content']['online_simg']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">管理中心功能</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['store_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="store_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="store_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">学期课时</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['ke_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="ke_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="ke_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">导航关闭</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['nav_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="nav_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="nav_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">IOS虚拟支付关闭</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['pay_ios']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="pay_ios">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="pay_ios" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">地址关闭</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['map_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="map_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="map_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">点名功能</label>
                <div class="col-sm-8">
                    <?php  if($list['content']['sign_status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="sign_status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="sign_status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">运费</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="fee" id="fee" value="<?php  echo $list['content']['fee'];?>">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">版权</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="copyright" id="copyright" value="<?php  echo $list['content']['copyright'];?>">
                </div>
            </div>
            <div class="form-group" style="display: none;">
                <label  class="col-sm-2 control-label">首页图片(375*250)</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('wwww');?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">报名顶部图片</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('sign_bimg',$list['content']['sign_bimg']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">砍价顶部图片</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_multi_image('cut_bimg',$list['content']['cut_bimg']);?>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">砍价分享标题</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="cut_share" id="cut_share" value="<?php  echo $list['content']['cut_share'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">公开课图片(375*130)</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('g_class',$list['content']['g_class']);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">新开课图片(375*130)</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_image('x_class',$list['content']['x_class']);?>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">单次预约人数限制</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="online_limit" id="online_limit" value="<?php  echo $list['content']['online_limit'];?>">
                    <span class="help-block">不填不限制</span>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">预定成功模板<br/>（ID AT0104）</label>
                <div class="col-sm-8">
                    <input type="text" placeholder="(姓名、电话、项目、时间)" class="form-control"  name="template_id" id="template_id" value="<?php  echo $list['content']['template_id'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">底部导航<br/>（图标50*50）</label>
                <div class="col-sm-8">
                    <div style="position: relative;">
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[0][icon]" value="<?php  echo $list['content']['footer'][0]['icon'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（未选）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][0]['icon'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[0][select]" value="<?php  echo $list['content']['footer'][0]['select'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（选中）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][0]['select'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button">底部文字</button>
                            </span>
                            <input type="text" name="footer[0][text]" value="<?php  echo $list['content']['footer'][0]['text'];?>" class="form-control">
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <input type="text" name="footer[0][link]" value="<?php  echo $list['content']['footer'][0]['link'];?>" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#selectUrl">选择链接</button>
                            </span>
                        </div>
                        <div class="sort_status">
                            <?php  if($list['content']['footer'][0]['status']==1) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="footer[0][status]">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" value="1" name="footer[0][status]">
                            <?php  } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="margin-top: 10px;position: relative;">
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group ">
                                <input type="text" name="footer[1][icon]" value="<?php  echo $list['content']['footer'][1]['icon'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（未选）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][1]['icon'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[1][select]" value="<?php  echo $list['content']['footer'][1]['select'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（选中）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][1]['select'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button">底部文字</button>
                            </span>
                            <input type="text" name="footer[1][text]" value="<?php  echo $list['content']['footer'][1]['text'];?>" class="form-control">
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <input type="text" name="footer[1][link]" value="<?php  echo $list['content']['footer'][1]['link'];?>" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#selectUrl">选择链接</button>
                            </span>
                        </div>
                        <div class="sort_status">
                            <?php  if($list['content']['footer'][1]['status']!=0) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="footer[1][status]">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" value="1" name="footer[1][status]">
                            <?php  } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="margin-top: 10px;position: relative;">
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[2][icon]" value="<?php  echo $list['content']['footer'][2]['icon'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（未选）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][2]['icon'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[2][select]" value="<?php  echo $list['content']['footer'][2]['select'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（选中）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][2]['select'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button">底部文字</button>
                            </span>
                            <input type="text" name="footer[2][text]" value="<?php  echo $list['content']['footer'][2]['text'];?>" class="form-control">
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <input type="text" name="footer[2][link]" value="<?php  echo $list['content']['footer'][2]['link'];?>" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#selectUrl">选择链接</button>
                            </span>
                        </div>
                        <div class="sort_status">
                            <?php  if($list['content']['footer'][2]['status']!=0) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="footer[2][status]">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" value="1" name="footer[2][status]">
                            <?php  } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="margin-top: 10px;position: relative;">
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[3][icon]" value="<?php  echo $list['content']['footer'][3]['icon'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（未选）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][3]['icon'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[3][select]" value="<?php  echo $list['content']['footer'][3]['select'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（选中）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][3]['select'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button">底部文字</button>
                            </span>
                            <input type="text" name="footer[3][text]" value="<?php  echo $list['content']['footer'][3]['text'];?>" class="form-control">
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <input type="text" name="footer[3][link]" value="<?php  echo $list['content']['footer'][3]['link'];?>" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#selectUrl">选择链接</button>
                            </span>
                        </div>
                        <div class="sort_status">
                            <?php  if($list['content']['footer'][3]['status']!=0) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="footer[3][status]">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" value="1" name="footer[3][status]">
                            <?php  } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="margin-top: 10px;position: relative;">
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[4][icon]" value="<?php  echo $list['content']['footer'][4]['icon'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（未选）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][4]['icon'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <div class="input-group">
                                <input type="text" name="footer[4][select]" value="<?php  echo $list['content']['footer'][4]['select'];?>" class="form-control" autocomplete="off">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="showImageDialog(this);">图标（选中）</button>
                                </span>
                            </div>
                            <div class="input-group " style="margin-top:.5em;">
                                <img src="<?php  echo tomedia($list['content']['footer'][4]['select'])?>" onerror="this.src='./resource/images/nopic.jpg'; this.title='图片未找到.'" class="img-responsive img-thumbnail" style="width: 50px;height: 50px;">
                                <em class="close" style="position:absolute; top: 0px; right: -14px;" title="删除这张图片" onclick="deleteImage(this)">×</em>
                            </div>
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <span class="input-group-btn">
                            <button class="btn btn-default" type="button">底部文字</button>
                            </span>
                            <input type="text" name="footer[4][text]" value="<?php  echo $list['content']['footer'][4]['text'];?>" class="form-control">
                        </div>
                        <div class="input-group" style="width: 50%;float:left;">
                            <input type="text" name="footer[4][link]" value="<?php  echo $list['content']['footer'][4]['link'];?>" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default link" type="button" data-toggle="modal" data-target="#selectUrl">选择链接</button>
                            </span>
                        </div>
                        <div class="sort_status">
                            <?php  if($list['content']['footer'][4]['status']==1) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="footer[4][status]">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" value="1" name="footer[4][status]">
                            <?php  } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                    <button class="btn btn-primary upsql" type="button">一键更新</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--底部链接选择-->
<div class="modal fade" id="selectUrl"><div class="modal-dialog">
    <style>
        #selectUrl .modal-body {padding: 10px 15px;}
        #selectUrl .tab-pane {margin-top: 5px; min-height: 400px; max-height: 400px; overflow-y: auto;}
        #selectUrlTab{margin-bottom: 10px;}
    </style>
    <div class="modal-content">
        <div class="modal-header">
            <button data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title">选择链接</h4>
        </div>
        <div class="modal-body">
            <ul class="nav nav-tabs" id="selectUrlTab">
                <li role="presentation" class="active"><a href="#sut_shop">页面</a></li>
                <li role="presentation"><a href="#sut_app">小程序</a></li>
            </ul>
            <div class="tab-content ">
                <div class="tab-pane active" id="sut_shop">
                    <nav data-dismiss="modal" data-href="../index/index" class="btn btn-default btn-sm" title="首页">首页</nav>
                    <nav data-dismiss="modal" data-href="../active/list" class="btn btn-default btn-sm" title="优惠活动">优惠活动</nav>
                    <nav data-dismiss="modal" data-href="../video/video" class="btn btn-default btn-sm" title="视频">视频</nav>
                    <nav data-dismiss="modal" data-href="../user/user" class="btn btn-default btn-sm" title="我的">我的</nav>
                    <nav data-dismiss="modal" data-href="../news/news" class="btn btn-default btn-sm" title="新闻">新闻</nav>
                    <nav data-dismiss="modal" data-href="../contact/contact" class="btn btn-default btn-sm" title="联系我们">联系我们</nav>
                    <nav data-dismiss="modal" data-href="../service/index" class="btn btn-default btn-sm" title="课程">课程</nav>
                    <nav data-dismiss="modal" data-href="../coupon/coupon" class="btn btn-default btn-sm" title="优惠券">优惠券</nav>
                    <nav data-dismiss="modal" data-href="../cut/list" class="btn btn-default btn-sm" title="砍价">砍价</nav>
                    <nav data-dismiss="modal" data-href="../mall/mall" class="btn btn-default btn-sm" title="商城">商城</nav>
                </div>
                <div class="tab-pane" id="sut_app">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">小程序 appId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control"  name="appId" id="appId">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input data-dismiss="modal" type="button" name="app_btn" class="btn btn-default" value="确定">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" id="select-close" type="button">关闭</button>
        </div>
        <script>
            var obj='';
            $(function(){
                $(".link").click(function(){
                    obj=this;
                });
                $("#selectUrl").find('#selectUrlTab a').click(function(e) {
                    $('#tab').val($(this).attr('href'));
                    e.preventDefault();
                    $(this).tab('show');
                });
                $("#sut_shop").find("nav").click(function(){
                    $(obj).parent().prev().val($(this).attr("data-href"));
                });
                $("input[name='app_btn']").click(function(){
                    var appid=$("input[name='appId']").val();
                    if(appid!=""){
                        $(obj).parent().prev().val(appid);
                    }
                });
            });
        </script>
    </div>
</div></div>
<!--底部链接选择 end-->
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("input[name='submit']").click(function(){
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>",
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
            $(".upsql").click(function(){
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/UpSql',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>",
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            swal('更新成功!', '更新成功!', 'success');
                        }else{
                            swal('操作失败!', '操作失败!', 'error');
                        }
                    }
                })
            });
        })
    })
</script>