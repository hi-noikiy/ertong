<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcss.css">
<style type="text/css">
    .storeset{border-bottom: 1px solid #eee;padding-bottom: 10px;}
    .storesetfont{font-size: 14px;font-weight: bold;}
    .ygstoreimg>.input-group:nth-child(1){float: left;width: 50%;margin-right: 30px;}
    .ygstoreimg>.input-group:nth-child(2){float: left;width: 50px;}
    .btn{padding: 7px 12px;}
    .ygstoreimg>.input-group:nth-child(2)>img{width: 45px;height: 35px;margin-top: -7px;}
    .wyheader{height: 40px;}
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
      <li><a href="<?php  echo $this->createWebUrl('store')?>">门店列表</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('addstore')?>">门店添加</a></li>    
  
    
    <!-- <li><a href="<?php  echo $this->createWebUrl('yg4')?>">门店回收站</a></li> -->
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                <span class="ygxian"></span>
                <div class="ygdangq">门店编辑:</div> 
            </div>
            <div class="panel-body panel">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                    <div class="col-sm-9">
                        <input type="number" name="number" class="form-control" value="<?php  echo $info['number'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><font color="red">*</font>门店名称</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control" value="<?php  echo $info['name'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><font color="red">*</font>门店logo</label>
                    <div class="col-sm-9">
                        <?php  echo tpl_form_field_image('logo',$info['logo'])?>
                    </div>
                </div>
              <!--   <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="margin-right: 15px;"><font color="red">*</font>所属区域</label>
                    <select class="col-sm-8" name="md_area">
                        <?php  if(is_array($area)) { foreach($area as $key => $item2) { ?>
                        <?php  if($item['id']==$info['md_area']) { ?>
                        <option value="<?php  echo $item2['id'];?>" selected="selected"><?php  echo $item2['area_name'];?></option>
                        <?php  } else { ?>
                        <option value="<?php  echo $item2['id'];?>" ><?php  echo $item2['area_name'];?></option>
                        <?php  } ?>
                        <?php  } } ?>
                    </select>
                    <div class="help-block col-md-8 col-md-offset-2">
                        * 请选择商户所在区域
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="margin-right: 15px;"><font color="red">*</font>门店类型</label>
                    <select class="col-sm-8" name="md_type">
                        <?php  if(is_array($type)) { foreach($type as $key => $item) { ?>
                        <?php  if($item['id']==$info['md_type']) { ?>
                        <option value="<?php  echo $item['id'];?>" selected="selected"><?php  echo $item['type_name'];?></option>
                        <?php  } else { ?>
                        <option value="<?php  echo $item['id'];?>" ><?php  echo $item['type_name'];?></option>
                        <?php  } ?>
                        <?php  } } ?>
                    </select>
                    <div class="help-block col-md-8 col-md-offset-2">
                        * 请选择商户门店类型
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">商家支持</label>
                    <div class="col-sm-10">
          
                        <label class="checkbox-inline">
                            <?php  if($infoset['is_dn']==1) { ?>  
                            <input type="checkbox" name="is_dn" id="optionsRadios4"  value="1" checked> 点餐
                              <?php  } else { ?>
                               <input type="checkbox" name="is_dn" id="optionsRadios4"  value="1" > 点餐
                               <?php  } ?>
                        </label>
                        <label class="checkbox-inline">
                           <?php  if($infoset['is_wm']==1) { ?>  
                            <input type="checkbox" name="is_wm" id="optionsRadios4"  value="1" checked> 外卖
                             <?php  } else { ?>
                                <input type="checkbox" name="is_wm" id="optionsRadios4"  value="1"> 外卖
                                 <?php  } ?>
                        </label>
                        <label class="checkbox-inline">
                        <?php  if($infoset['is_yy']==1) { ?>  
                        <input type="checkbox" name="is_yy" id="optionsRadios3" value="1" checked> 预约
                        <?php  } else { ?>
                        <input type="checkbox" name="is_yy" id="optionsRadios3" value="1" > 预约
                        <?php  } ?>
                        </label>
                        <label class="checkbox-inline">
                        <?php  if($infoset['is_sy']==1) { ?>  
                            <input type="checkbox" name="is_sy" id="optionsRadios4"  value="1" checked> 收银
                             <?php  } else { ?>
                            <input type="checkbox" name="is_sy" id="optionsRadios4"  value="1" > 收银
                             <?php  } ?>
                        </label>
                           <label class="checkbox-inline">
                        <?php  if($infoset['is_pd']==1) { ?>  
                            <input type="checkbox" name="is_pd" id="optionsRadios4"  value="1" checked> 排队取号
                             <?php  } else { ?>
                            <input type="checkbox" name="is_pd" id="optionsRadios4"  value="1" > 排队取号
                             <?php  } ?>
                        </label>

                        <?php  if($system['ptgn']==1) { ?>
                         <label class="checkbox-inline">
                        
                        <?php  if($infoset['is_pt']==1) { ?>  
                            <input type="checkbox" name="is_pt" id="is_pt"  value="1" checked> 拼团
                             <?php  } else { ?>
                            <input type="checkbox" name="is_pt" id="is_pt"  value="1" > 拼团
                             <?php  } ?>
                        </label>
                        <?php  } ?>

                        <?php  if($system['qggn']==1) { ?>
                         <label class="checkbox-inline">
 
                        <?php  if($infoset['is_qg']==1) { ?>  
                            <input type="checkbox" name="is_qg" id="is_qg"  value="1" checked> 抢购
                             <?php  } else { ?>
                            <input type="checkbox" name="is_qg" id="is_qg"  value="1" > 抢购
                             <?php  } ?>
                        </label>
                        <?php  } ?>
                        <p></p>
                        <div class="help-block">
                      
                        </div>
                    </div>
                </div>
                <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">是否显示门店</label>
                <div class="col-sm-10">
                    <div class="col-sm-8" style="padding: 0px;">
                        <select class="col-sm-12" name="is_open" style="margin-bottom: 10px;">
                            <?php  if($info['is_open']==1) { ?>
                            <option value="1" selected="selected">是</option>
                            <option value="2">否</option>
                            <?php  } else if($info['is_open']==2) { ?>
                            <option value="1">是</option>
                            <option value="2" selected="selected">否</option>
                            <?php  } else { ?>
                            <option value="1">是</option>
                            <option value="2">否</option>
                            <?php  } ?>
                        </select>
                        <div class="help-block">
                    * 备注：选择否，则小程序端不显示此门店信息
                    </div>
                    </div>
                    
                </div>
            </div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">品质优选</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy3" name="is_brand" value="1" <?php  if($info['is_brand']==1) { ?>checked<?php  } ?> />
                            <label for="emailwy3">是</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy4" name="is_brand" value="2" <?php  if($info['is_brand']==2 || empty($info['is_brand'])) { ?>checked<?php  } ?> />
                            <label for="emailwy4">否</label>
                        </label>
                    </div>
                </div>
                  <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">精选好店</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy9" name="is_select" value="1" <?php  if($info['is_select']==1) { ?>checked<?php  } ?> />
                            <label for="emailwy9">是</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy10" name="is_select" value="2" <?php  if($info['is_select']==2 || empty($info['is_select'])) { ?>checked<?php  } ?> />
                            <label for="emailwy10">否</label>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">余额支付</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy5" name="is_yuepay" value="1" <?php  if($infoset['is_yuepay']==1) { ?>checked<?php  } ?> />
                            <label for="emailwy5">是</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy6" name="is_yuepay" value="2" <?php  if($infoset['is_yuepay']==2 || empty($infoset['is_yuepay'])) { ?>checked<?php  } ?> />
                            <label for="emailwy6">否</label>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商家小程序码跳转路径</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="tz_src3" name="tz_src" value="1" <?php  if($infoset['tz_src']==1) { ?>checked<?php  } ?> />
                            <label for="tz_src3">商家详情</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="tz_src4" name="tz_src" value="2" <?php  if($infoset['tz_src']==2 || empty($infoset['tz_src'])) { ?>checked<?php  } ?> />
                            <label for="tz_src4">商品列表</label>
                        </label>
                    </div>
                </div>
           <!--          <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">拼团功能</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy7" name="is_pt" value="1" <?php  if($infoset['is_pt']==1) { ?>checked<?php  } ?> />
                            <label for="emailwy7">是</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy8" name="is_pt" value="2" <?php  if($infoset['is_pt']==2 || empty($infoset['is_pt'])) { ?>checked<?php  } ?> />
                            <label for="emailwy8">否</label>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">限时抢购</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy9" name="is_qg" value="1" <?php  if($infoset['is_qg']==1) { ?>checked<?php  } ?> />
                            <label for="emailwy9">是</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy10" name="is_qg" value="2" <?php  if($infoset['is_qg']==2 || empty($infoset['is_qg'])) { ?>checked<?php  } ?> />
                            <label for="emailwy10">否</label>
                        </label>
                    </div>
                </div> -->
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">虚拟销量</label>
                    <div class="col-sm-9">
                        <input type="number" name="score" class="form-control" value="<?php  echo $info['score'];?>" />
                    </div>
                </div>
        <!--         <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 control-label">平台手续费</label>
                  <div class="col-sm-9">
                        <div class="input-group">
                             <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" name="poundage" value="<?php  echo $infoset['poundage'];?>" id="points" class="form-control" />
                             <span class="input-group-addon">%</span>
                        </div>
                        <div class="help-block">* 平台收取对应的手续费,如果不填则收取商户对应分类下的手续费</div>
                  </div>
                </div> -->
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">入驻到期时间</label>
                <div class="col-sm-10">
                   <?php  echo tpl_form_field_date('rzdq_time',$info['rzdq_time'],$withtime = true)?>
                   <span class="help-block">*请设置当前商家入驻到期时间</span>
                </div>
            </div>  
                   <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">绑定管理员</label>
                <div class="col-sm-10">
                    <div class="col-sm-12" style="padding: 0px;">
                        <select class="col-sm-6" name="admin_id" id="username2">
                           <?php  if($userinfo2['name']) { ?>
                            <option value="<?php  echo $userinfo2['id'];?>" selected="selected"><?php  echo $userinfo2['name'];?></option>
                            <?php  } ?>
                           <?php  if(!$userinfo2['name']) { ?> <option value="" >请选择</option><?php  } ?>
                            <?php  if(is_array($user2)) { foreach($user2 as $key => $item4) { ?>
                            
                            <option value="<?php  echo $item4['id'];?>" ><?php  echo $item4['name'];?></option>
                            <?php  } } ?>
                        </select>
                        <span class="btn btn-sm storeblue" data-toggle="modal" data-target="#myModal2" style="margin-left: 30px;">搜索管理员</span>
                        <div class="col-sm-9">
                            <div class="help-block">
                                * 用于商户手机端微信登录
                            </div>
                        </div>
                    </div>                    
                </div> 
                           <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h5 class="modal-title" id="myModalLabel" style="font-size: 16px;">提示</h5>
                            </div>
                            <div class="modal-body ygsearch" style="font-size: 20px;padding: 15px 30px;">
                                <input type="text" id="ygsinput2" placeholder="请输入微信昵称/openid">
                                <span class="btn btn-sm storeblue ygbtn2">搜索</span>
                                <div class="searchname2" style="margin-top: 8px;"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 control-label">配送平台手续费</label>
                  <div class="col-sm-9">
                        <p class="input-group">
                             <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" name="ps_poundage" value="<?php  echo $info['ps_poundage'];?>" id="points" class="form-control" />
                             <span class="input-group-addon">%</span>
                        </p>
                         <div class="ygmargin">*配送费对应的手续费</div> 
                  </div>
                </div>
              <!--   <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">消费得积分(%)：</label>
                    <div class="col-sm-9">
                        <input type="number" name="integral2" value="<?php  echo $infoset['integral2'];?>" id="web_name" class="form-control" />
                        <div class="help-block">
                        *例如:填10就表示消费20元得2积分(20*10%)  不填或填0就已平台设置的为主
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">评论得积分：</label>
                    <div class="col-sm-9">
                        <input type="number" name="integral" value="<?php  echo $infoset['integral'];?>" id="web_name" class="form-control" />
                        <div class="help-block">
                        * 不填或填0就已平台设置的为主
                        </div>
                    </div>
                </div> -->
                <div class="form-group storeset">
                    <span class="ygxian"></span>
                    <div class="ygdangq storesetfont">自定义名称:</div>
                </div>
                 <div class="form-group">
                    <label class="col-md-2 control-label">外卖</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="wm_name" class="form-control" value="<?php  echo $infoset['wm_name'];?>" placeholder="请填写外卖名称"/>
                    </div>
                   <div class="col-md-2" style="background: #ccc;padding: 0">
                        <input type="text" name="wmsm" placeholder="请填写外卖说明文字" value="<?php  echo $infoset['wmsm'];?>" class="form-control">
                    </div>                     
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('wm_img', $infoset['wm_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台外卖功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div>
               
                <div class="form-group">
                    <label class="col-md-2 control-label">点餐</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="dn_name" class="form-control" value="<?php  echo $infoset['dn_name'];?>" placeholder="请填写点餐名称"/>
                    </div>
                   <div class="col-md-2" style="background: #ccc;padding: 0">
						<input type="text" name="dnsm" placeholder="请填写点餐说明文字" value="<?php  echo $infoset['dnsm'];?>" class="form-control">
                    </div>                     
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('dn_img', $infoset['dn_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台点餐功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div>
               
                <div class="form-group">
                    <label class="col-md-2 control-label">预约</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="yy_name" class="form-control" value="<?php  echo $infoset['yy_name'];?>" placeholder="请填写预约名称"/>
                    </div>
                   <div class="col-md-2" style="background: #ccc;padding: 0">
                        <input type="text" name="yysm" placeholder="请填写预约说明文字" value="<?php  echo $infoset['yysm'];?>" class="form-control">
                    </div> 
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('yy_img', $infoset['yy_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台预约功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div>
                 
                <div class="form-group">
                    <label class="col-md-2 control-label">收银</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="sy_name" class="form-control" value="<?php  echo $infoset['sy_name'];?>"  placeholder="请填写收银名称"/>
                    </div>
                  	<div class="col-md-2" style="background: #ccc;padding: 0">
						<input type="text" name="sysm" placeholder="请填写收银说明文字" value="<?php  echo $infoset['sysm'];?>" class="form-control">
                    </div>                      
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('sy_img', $infoset['sy_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台收银功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div> 
                <div class="form-group">
                    <label class="col-md-2 control-label">限时抢购</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="qg_name" class="form-control" value="<?php  echo $infoset['qg_name'];?>" placeholder="请填写限时抢购名称"/>
                    </div>
                   <div class="col-md-2" style="background: #ccc;padding: 0">
                        <input type="text" name="qgsm" placeholder="请填写限时抢购说明文字" value="<?php  echo $infoset['qgsm'];?>" class="form-control">
                    </div>                     
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('qg_img', $infoset['qg_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台限时抢购功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div>
                 <div class="form-group">
                    <label class="col-md-2 control-label">拼团</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="pt_name" class="form-control" value="<?php  echo $infoset['pt_name'];?>" placeholder="请填写限时抢购名称"/>
                    </div>
                   <div class="col-md-2" style="background: #ccc;padding: 0">
                        <input type="text" name="ptsm" placeholder="请填写拼团说明文字" value="<?php  echo $infoset['ptsm'];?>" class="form-control">
                    </div>                     
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('pt_img', $infoset['pt_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台拼团功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div>
                   <div class="form-group">
                    <label class="col-md-2 control-label">排队取号</label>
                    <div class="col-md-2" style="padding-right: 0px;">
                        <input type="text" name="pd_name" class="form-control" value="<?php  echo $infoset['pd_name'];?>" placeholder="请填写限时抢购名称"/>
                    </div>
                   <div class="col-md-2" style="background: #ccc;padding: 0">
                        <input type="text" name="pdsm" placeholder="请填写排队取号说明文字" value="<?php  echo $infoset['pdsm'];?>" class="form-control">
                    </div>                     
                    <div class="col-md-6 ygstoreimg" style="padding: 0px;">
                        <?php  echo tpl_form_field_image('pd_img', $infoset['pd_img'])?>
                    </div>
                    <div class="help-block col-md-offset-2 col-md-12">*请设置前台排队取号功能名称，为空或删除为默认值，图标大小63*63px</div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">餐盒费</label>
                    <div class="col-sm-5">
                        <input type="text" name="box_name" value="<?php  echo $infoset['box_name'];?>" class="form-control" id="inputEmail3" placeholder="请填写餐盒费名称">
                        <div class="help-block">*请填写外卖订单页面餐盒费名称，为空或删除为默认值</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">餐具用量</label>
                    <div class="col-sm-5">
                        <input type="text" name="cj_name" value="<?php  echo $infoset['cj_name'];?>" class="form-control" id="inputEmail3" placeholder="请填写餐具用量名称">
                        <div class="help-block">*请填写外卖订单页面餐具用量名称，为空或删除为默认值</div>
                    </div>
                </div>  
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">外卖配送</label>
                    <div class="col-sm-5">
                        <input type="text" name="wmps_name" value="<?php  echo $infoset['wmps_name'];?>" class="form-control" id="inputEmail3" placeholder="请填写外卖配送名称">
                        <div class="help-block">*请填写外卖订单页面外卖配送名称，为空或删除为默认值</div>
                    </div>
                </div>  


                <div class="form-group storeset">
                    <span class="ygxian"></span>
                    <div class="ygdangq storesetfont">自动提现设置:</div>
                </div>
                <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">绑定提现人</label>
                <div class="col-sm-10">
                    <div class="col-sm-12" style="padding: 0px;">
                        <select class="col-sm-6" name="user_id" id="username">
                           <?php  if($userinfo['name']) { ?>
                            <option value="<?php  echo $userinfo['id'];?>" selected="selected"><?php  echo $userinfo['name'];?></option>
                            <?php  } ?>
                            <?php  if(!$userinfo['name']) { ?> <option value="" >请选择</option><?php  } ?>
                            <?php  if(is_array($user)) { foreach($user as $key => $item3) { ?>
                            <option value="<?php  echo $item3['id'];?>" ><?php  echo $item3['name'];?></option>
                            <?php  } } ?>
                        </select>
                        <span class="btn btn-sm storeblue" data-toggle="modal" data-target="#myModal1" style="margin-left: 30px;">搜索管理员</span>
                        <div class="col-sm-9">
                            <div class="help-block">
                                * 用于商户微信提现时直接打款到绑定人的微信钱包，微信支付需开通企业付款到用户功能
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h5 class="modal-title" id="myModalLabel" style="font-size: 16px;">提示</h5>
                            </div>
                            <div class="modal-body ygsearch" style="font-size: 20px;padding: 15px 30px;">
                                <input type="text" id="ygsinput" placeholder="请输入微信昵称/openid">
                                <span class="btn btn-sm storeblue ygbtn">搜索</span>
                                <div class="searchname" style="margin-top: 8px;"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

        <div class="form-group">
            <input type="submit" name="submit" value="提交" class="btn col-lg-3" style="color: white;background-color: #44ABF7;margin-left: 30%;"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        // $("#frame-0").addClass("in");
        $("#frame-0").show();
        $("#yframe-0").addClass("wyactive");
        $(".ygbtn").on("click",function(){
            var ygsinput = $("#ygsinput").val();
            console.log(ygsinput)
            if(ygsinput.length==''){
              $(".searchname").html('');
            }else{
              $(".searchname").html('')  
              var keywords = $("#ygsinput").val()
              $.ajax({
                  type:"post",
                  url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=SelectUser&m=zh_cjdianc",
                  dataType:"text",
                  data:{keywords:keywords},
                  success:function(data){                    
                      var data = eval('(' + data + ')')
                      console.log(data);
                      $(".searchname").show();
                      for(var i=0;i<data.length;i++){
                        $(".searchname").append('<div class="shnbox" data-dismiss="modal" id="'+data[i].id+'"><a href="javascript:void(0);"><p>'+data[i].name+'</p></a></div>')
                      }
                      $(".shnbox").each(function(){
                        $(this).click(function(){
                            // console.log($(this).attr("id"));
                            // $("#username").val($(this).html())
                            var thid = $(this).attr("id");
                            $('#username option[value='+thid+']').attr("selected", true);
                        })
                        
                      })
                      
                  }
              }) 
            }
            
        })

          $(".ygbtn2").on("click",function(){
            var ygsinput2 = $("#ygsinput2").val();
            console.log(ygsinput2)
            if(ygsinput2.length==''){
              $(".searchname2").html('');
            }else{
              $(".searchname2").html('')  
              var keywords = $("#ygsinput2").val()
              $.ajax({
                  type:"post",
                  url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=SelectUser2&m=zh_cjdianc",
                  dataType:"text",
                  data:{keywords:keywords},
                  success:function(data){                    
                      var data = eval('(' + data + ')')
                      console.log(data);
                      $(".searchname2").show();
                      for(var i=0;i<data.length;i++){
                        $(".searchname2").append('<div class="shnbox2" data-dismiss="modal" id="'+data[i].id+'"><a href="javascript:void(0);"><p>'+data[i].name+'</p></a></div>')
                      }
                      $(".shnbox2").each(function(){
                        $(this).click(function(){
                            // console.log($(this).attr("id"));
                            // $("#username").val($(this).html())
                            var thid = $(this).attr("id");
                            $('#username2 option[value='+thid+']').attr("selected", true);
                        })
                        
                      })
                      
                  }
              }) 
            }
            
        })
        
    })
</script>

