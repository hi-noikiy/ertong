<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_cjdianc/template/public/ygcss.css">
<style type="text/css">
    .store_td1{height: 45px;}
    .store_list_img{width: 60px;height: 60px;}
    .yg5_tabel{border-color: #e5e5e5;outline: 1px solid #e5e5e5;}
    .yg5_tr2>td{padding: 15px;border: 1px solid #e5e5e5;text-align: center;}
    .yg5_tr1>th{
        border: 1px solid #e5e5e5;
        padding: 15px;
        background-color: #FAFAFA;
        font-weight: bold;
        text-align: center;
    }
</style>
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li <?php  if($operation == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('help', array('op' => 'display'))?>">帮助管理</a></li>
    <li <?php  if($operation == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('help', array('op' => 'post'))?>">添加帮助信息</a></li>    
</ul>
<?php  if($operation == 'post') { ?>
<div class="main wymain">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                添加帮助信息
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">帮助标题</label>
                    <div class="col-sm-9">
                        <input type="text" name="question" value="<?php  echo $list['question'];?>" class="form-control" placeholder="请输入标题" />
                    </div>
                </div>
            </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">帮助内容</label>
                    <div class="col-sm-9">
                       <?php  echo tpl_ueditor('answer',$list['answer']);?>
                    </div>
                </div>
               
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                    <div class="col-sm-9">
                        <input type="number" name="sort" class="form-control" value="<?php  echo $list['sort'];?>" />
                    </div>
                </div>
           
        <div class="form-group" style="margin-top: 20px;">
            <input type="submit" name="submit" value="保存设置" class="btn col-lg-3" style="color: white;background-color: #44ABF7;"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        </div>
    </form>
</div>
<?php  } else if($operation == 'display') { ?>
<div class="main" style="background-color: #F5F7F9;">
    <!-- <div class="panel panel-default">
        <div class="panel-body">
            <a class="btn btn-primary" href="javascript:location.reload()"><i class="fa fa-refresh"></i>刷新</a>
        </div>
    </div> -->
    <div class="panel panel-default">
        <div class="panel-heading">
                帮助列表
            </div>
        <form action="" method="post" class="form-horizontal form" >
            <input type="hidden" name="storeid" value="<?php  echo $storeid;?>" />
            <div class="table-responsive">
                <table class="col-md-12">
                    <tr class="yg5_tr1">
                        <th class="store_td1">排序</th>
                        <th>标题</th>
                        <th>发布时间</th>
                        <th>操作</th>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <tr class="yg5_tr2">
                        <td><div class="type-parent"><?php  echo $row['sort'];?></div></td>
                        <td>
                            <div class="type-parent"><?php  echo $row['question'];?></div>
                        </td>
                        <td><?php  echo $row['created_time'];?></td>
                        <td>
                            <a href="<?php  echo $this->createWebUrl('help', array('op' => 'post', 'id' => $row['id']))?>" class="storespan btn btn-xs">
                                <span class="fa fa-pencil"></span>
                                <span class="bianji">编辑
                                    <span class="arrowdown"></span>
                                </span>
                            
                            </a>
                            <a href="<?php  echo $this->createWebUrl('help', array('op' => 'delete', 'id' => $row['id']))?>" class="storespan btn btn-xs" onclick="return confirm('确认删除吗？');return false;">
                                <span class="fa fa-trash-o"></span>
                                <span class="bianji">删除
                                    <span class="arrowdown"></span>
                                </span>
                            </a>
                            <!-- <a class="btn btn-info btn-xs" href="<?php  echo $this->createWebUrl('help', array('op' => 'post', 'id' => $row['id']))?>" title="编辑">改</a>&nbsp;&nbsp;<a class="btn btn-danger btn-xs" href="<?php  echo $this->createWebUrl('help', array('op' => 'delete', 'id' => $row['id']))?>" onclick="return confirm('确认删除吗？');return false;" title="删除">删</a></td> -->
                    </tr>
                    <?php  } } ?>
                    <?php  if(!$list) { ?>
                    <tr class="yg5_tr2">
                        <td colspan="5">暂无信息</td>
                    </tr>
                    <?php  } ?>
                </table>
            </div>
        </form>
    </div>
    
</div>
<div class="text-right we7-margin-top"><?php  echo $pager;?></div>
<?php  } ?>
<script type="text/javascript">
    $(function(){
        // $("#frame-14").addClass("in");
        $("#frame-14").show();
        $("#yframe-14").addClass("wyactive");
    })
</script>