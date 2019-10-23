<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">

<script type="text/javascript">
    $(function(){
        $("#frame-0").show();
        $("#yframe-0").addClass("wyactive");
    })
</script>
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="<?php  echo $this->createWebUrl('in')?>">商家入驻管理</a></li>
    <li><a href="<?php  echo $this->createWebUrl('addin')?>">添加商家入驻</a></li>
</ul>
<div class="main">
    <div class="panel panel-default ygbody">
        <div class="panel-body">
            <p class="yangshi">请设置商家入驻的时间和价格</p>
        </div>
    </div>
    <!-- 门店列表部分开始 -->
        <div class="panel panel-default">
            <div class="panel-heading">
                商家入驻管理
            </div>
            <div class="panel-body" style="padding: 0px 15px;">
                <div class="row">
                    <table class="yg5_tabel col-md-12">
                        <tr class="yg5_tr1">
                            <td class="store_td1 col-md-1">排序</td>
                            <td class="col-md-2">期限</td>
                            <td class="col-md-2">本地价格</td>
                            <td class="col-md-2">全国版价格</td>
                            <td class="col-md-3">操作</td>
                        </tr>
                        <?php  if(is_array($list)) { foreach($list as $row) { ?>
                        <tr class="yg5_tr2">
                            <td><div><?php  echo $row['num'];?></div></td>
                            <td>
                            <?php  if($row['type']==1) { ?>一周<?php  } else if($row['type']==2) { ?>半年<?php  } else if($row['type']==3) { ?>一年<?php  } ?>              
                            </td>
                            <td><?php  echo $row['money'];?></td>
                             <td><?php  echo $row['money2'];?></td>
                            <td>
                                <a href="<?php  echo $this->createWebUrl('addin', array('id' => $row['id']))?>" class="storespan btn btn-xs">
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
                            <!-- <a class="btn btn-warning btn-xs" href="<?php  echo $this->createWebUrl('addin', array('id' => $row['id']))?>" title="编辑">改</a>&nbsp;&nbsp;
                           <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $row['id'];?>">删</button> -->
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
                            <a href="<?php  echo $this->createWebUrl('in', array('id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
                        <?php  } } ?>
                       <?php  if(empty($list)) { ?>
                        <tr class="yg5_tr2">
                        <td colspan="12">
                              暂无商家信息
                          </td>
                      </tr>
                      <?php  } ?>
                    </table>
                </div>
            </div>
        </div>
    <?php  echo $pager;?>
</div>
<!-- <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?> -->