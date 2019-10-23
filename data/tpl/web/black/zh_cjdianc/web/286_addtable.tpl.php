<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcss.css">
<style type="text/css">
  .addtabel1{margin-left: 20px;}
  .addtabel2{height: 34px;border: 1px solid #e5e5e5;}
</style>
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li><a href="<?php  echo $this->createWebUrl('table')?>">餐桌列表</a></li>
    <li class="active"><a href="<?php  echo $this->createWebUrl('addtable')?>">添加餐桌</a></li>
</ul>
<div class="main">
  <div class="panel panel-default ygdefault">

        <div class="panel-heading wyheader">添加/编辑分类</div>

        <div class="panel-body">

<form class="form-horizontal" action="" method="POST">


<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">餐桌名称</label>
    <div class="col-sm-10">
      <input type="text"  name="name" value="<?php  echo $list['name'];?>" class="form-control" id="inputEmail3" placeholder="请填写餐桌名称">
    </div>
  </div>

 <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">可就餐人数</label>
    <div class="col-sm-10">
      <input type="number"  name="num" value="<?php  echo $list['num'];?>" class="form-control" id="inputEmail3" placeholder="请填写就餐人数">
    </div>
  </div>



   <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">标签</label>
    <div class="col-sm-10">
      <input type="text"  name="tag" value="<?php  echo $list['tag'];?>" class="form-control" id="inputEmail3" placeholder="请填写标签">
    </div>
  </div>

   <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">桌台分类</label>
    <div class="col-sm-10">
       <select class="form-control" name="type_id"> 
    <?php  if(is_array($type)) { foreach($type as $key => $item) { ?>
       <?php  if($item['id']==$list['type_id']) { ?>
      <option value="<?php  echo $item['id'];?>" selected="selected"><?php  echo $item['name'];?></option>
      <?php  } else { ?>
      <option value="<?php  echo $item['id'];?>" ><?php  echo $item['name'];?></option>
      <?php  } ?>
     <?php  } } ?>
    </select>
    </div>
  </div>



<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">顺序</label>
    <div class="col-sm-10">
      <input type="text"  name="orderby" value="<?php  echo $list['orderby'];?>" class="form-control" id="inputEmail3" placeholder="请填写顺序">
    </div>
  </div>
<?php  if($img!='') { ?>
<div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">二维码</label>
    <?php  if(strlen($img)>200) { ?>
    <?php  echo "<img src='data:image/png;base64,".$img."'>"?>
    <?php  } else { ?> 
    <div class="col-sm-10">
 <input type="text" style="color:red" name="orderby" value="该二维码必须在小程序发布后才能生成" class="form-control" id="inputEmail3" readonly>
   </div>
 <?php  } ?>
  </div>
    <?php  } ?>

    <div class="form-group">
          <input type="submit" name="submit" value="提交" class="btn col-lg-3 col-lg-offset-3" style="color: white;background-color: #44ABF7;"/>
          <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
          <input type="hidden" name="id" value="<?php  echo $list['id'];?>" />
      </div>

</form>

</div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        // $("#frame-3").addClass("in");
        $("#frame-3").show();
        $("#yframe-3").addClass("wyactive");
    })
</script>