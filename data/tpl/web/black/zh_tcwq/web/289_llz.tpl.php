<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="<?php  echo $this->createWebUrl('llz')?>">流量主管理</a></li>
    <li><a href="<?php  echo $this->createWebUrl('addllz')?>">添加流量主</a></li>
</ul>
<div class="main">
    <div class="panel panel-default">
        <div class="panel-heading">
            流量主管理
        </div>
        <div class="panel-body" style="padding: 0px 15px;">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                    <tr class="yg5_tr1">
                        <th class="store_td1 col-md-1">城市</th>
                        <th class="col-md-2">标题</th>
                        <th class="col-md-1">类型</th>
                        <th class="col-md-1">广告位ID</th>
                        <th class="col-md-1">状态</th>
                        <th class="col-md-2">操作</th>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <tr class="yg5_tr2">
                        <!-- <td><div class="type-parent"><?php  echo $row['orderby'];?>&nbsp;&nbsp;</div></td> -->
                        <tr class="yg5_tr2">
                        <td><?php  if($row['cityname']) { ?><?php  echo $row['cityname'];?><?php  } else { ?>全国版<?php  } ?></td>
                        <td><?php  echo $row['name'];?></td>
                        <td><?php  if($row['type']==1) { ?>帖子详情流量主<?php  } else if($row['type']==2) { ?>首页流量主<?php  } else if($row['type']==3) { ?>黄页流量主<?php  } else if($row['type']==4) { ?>顺风车流量主<?php  } else if($row['type']==5) { ?>视频详情流量主<?php  } else if($row['type']==6) { ?>活动详情流量主<?php  } ?></td>
                        <td><?php  echo $row['src'];?></td>
                        <?php  if($row['status']==1) { ?>
                        <td><button type="button" class="btn ygyouhui2 btn-xs" data-toggle="modal" data-target="#myModal2<?php  echo $row['id'];?>">点击禁用</button></td>
                        <?php  } else if($row['status']==2) { ?>
                        <td><button type="button" class="btn storegrey2 btn-xs" data-toggle="modal" data-target="#myModal3<?php  echo $row['id'];?>">点击启用</button></td>
                        <?php  } ?>
                        <td>
                            <a href="<?php  echo $this->createWebUrl('addllz', array('id' => $row['id']))?>" class="storespan btn btn-xs">
                                <span class="fa fa-pencil"></span>
                                <span class="bianji">编辑
                                    <span class="arrowdown"></span>
                                </span>                            
                            </a>
                            <a href="javascript:void(0);" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $row['id'];?>">
                                <span class="fa fa-trash-o"></span>
                                <span class="bianji">删除
                                    <span class="arrowdown"></span>
                                </span>
                            </a>
                            <!-- <a class="btn btn-warning btn-xs" href="<?php  echo $this->createWebUrl('addad', array('id' => $row['id']))?>" title="编辑">改</a>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $row['id'];?>">删</button> -->
                        </td>
                    </tr>
                    <div class="modal fade" id="myModal<?php  echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                        </div>
                        <div class="modal-body" style="font-size: 20px">
                            确定删除么？
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a href="<?php  echo $this->createWebUrl('llz', array('op' => 'delete', 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal2<?php  echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                        </div>
                        <div class="modal-body" style="font-size: 20px">
                            确定要禁用么？
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a href="<?php  echo $this->createWebUrl('llz', array('status' => 2, 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal3<?php  echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                        </div>
                        <div class="modal-body" style="font-size: 20px">
                            确定要启用么？
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a href="<?php  echo $this->createWebUrl('llz', array('status' => 1, 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
                    <?php  } } ?>
                     <?php  if(empty($list)) { ?>
                      <tr class="yg5_tr2">
                      <td colspan="12">
                            暂无流量主信息
                        </td>
                    </tr>
                    <?php  } ?>

            
                </table>
            </div>
        </form>
    </div>

</div>
<div class="text-right we7-margin-top">
   <?php  echo $pager;?>
</div>
<script type="text/javascript">
    $(function(){
        $("#frame-6").show();
        $("#yframe-6").addClass("wyactive");
    })
</script>