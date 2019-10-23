<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcss.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li><a href="<?php  echo $this->createWebUrl('coupons')?>">红包管理</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('addcoupons')?>">添加/编辑红包</a></li>
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                红包管理
            </div>
        <div class="panel-body">
                <form class="form-horizontal" action="" method="POST">
  <div class="form-group">

    <label for="inputEmail3" class="col-sm-2 control-label">红包名称</label>

    <div class="col-sm-10">

      <input type="text"  name="name" value="<?php  echo $list['name'];?>" class="form-control" id="inputEmail3" placeholder="请填写红包名称">

    </div>

  </div>
  
   <div class="form-group">

    <label for="inputEmail3" class="col-sm-2 control-label">优惠条件金额</label>

    <div class="col-sm-5">
      <div class="input-group">
          <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" name="full" value="<?php  echo $list['full'];?>" class="form-control" id="inputEmail3" placeholder="请填写优惠条件金额">
          <span class="input-group-addon">元</span>
      </div>
    </div>

  </div>

<div class="form-group">

    <label for="inputEmail3" class="col-sm-2 control-label">优惠金额</label>

    <div class="col-sm-5">
      <div class="input-group">
        <input type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" name="reduce" value="<?php  echo $list['reduce'];?>" class="form-control" id="inputEmail3" placeholder="请填写优惠金额">
        <span class="input-group-addon">元</span>
      </div>
    </div>

  </div>
  <div class="form-group">

    <label for="inputEmail3" class="col-sm-2 control-label">红包数量</label>

    <div class="col-sm-10">

      <input type="number"  name="number" value="<?php  echo $list['number'];?>" class="form-control" id="inputEmail3" placeholder="请填写红包数量">

    </div>

  </div>
    <?php  if($list['id']) { ?>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">红包剩余数量</label>
    <div class="col-sm-10">
      <input type="number"  name="stock" value="<?php  echo $list['stock'];?>" class="form-control" id="inputEmail3" placeholder="请填写红包剩余数量">
    </div>
  </div>
  <?php  } ?>

<div class="form-group">

    <label for="inputEmail3" class="col-sm-2 control-label">红包时间</label>

    <div class="col-sm-10">

    <?php  echo tpl_form_field_daterange('time',array('start' =>$list['start_time'], 'end' =>$list['end_time']));?> 

    </div>

  </div>

   <div class="form-group">
      <label class="col-xs-12 col-sm-3 col-md-2 control-label" style="margin-right: 15px;">门店分类</label>
      <select class="col-sm-8" name="type_id">
      <option value="0" selected="selected">全平台</option>
          <?php  if(is_array($type)) { foreach($type as $key => $item) { ?>
          <?php  if($item['id']==$list['type_id']) { ?>
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
      <label for="inputEmail3" class="col-sm-2 control-label">设置使用范围</label>
      <div class="col-sm-10">
        <select class="form-control" name="type"> 
          <?php  if($list['type']==1) { ?>  
            <option value="1" selected="selected">仅限外卖使用</option>  
            <option value="2">仅限店内使用</option>
            <option value="3">两者都可使用</option>
          <?php  } else if($list['type']==2) { ?>
            <option value="1" >仅限外卖使用</option>    
            <option value="2" selected="selected">仅限店内使用</option>
            <option value="3">两者都可使用</option>
          <?php  } else if($list['type']==3) { ?>
            <option value="1" >仅限外卖使用</option>  
            <option value="2">仅限店内使用</option>
            <option value="3" selected="selected">两者都可使用</option>
          <?php  } else { ?>
            <option value="1" >仅限外卖使用</option>
            <option value="2">仅限店内使用</option>
            <option value="3">两者都可使用</option>
          <?php  } ?>
        </select>
      </div>
    </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">使用说明</label>
    <div class="col-sm-10">
      <input type="text"  name="instruction" value="<?php  echo $list['instruction'];?>" class="form-control" id="inputEmail3" placeholder="请填写使用说明">
    </div>
  </div>
   <input type="hidden" name="id" value="<?php  echo $list['id'];?>"/>

  <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>

  <div class="form-group" style="margin-top: 20px;">
      <input type="submit" name="submit" value="提交" class="btn col-lg-3" style="color: white;background-color: #44ABF7;"/>

  </div>
</form>

</div>
<script type="text/javascript">
    $(function(){
        $("#frame-5").show();
        $("#yframe-5").addClass("wyactive");
    })
</script>