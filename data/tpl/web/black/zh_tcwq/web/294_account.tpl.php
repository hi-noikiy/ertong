<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
 <?php  if($_W['role'] != 'operator') { ?>

<ul class="nav nav-tabs">    
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li class="active"><a href="javascript:void(0);">账号管理</a></li>
    <li><a href="<?php  echo $this->createWebUrl('countadd')?>">添加/编辑账号</a></li>
</ul>
<?php  } ?>

<div class="main">
    <div class="panel panel-default ygbody">
        <div class="panel-body">
            <p class="yangshi">商户后台登陆地址:&nbsp;&nbsp;<a href="<?php  echo $_W['siteroot'];?>web/city.php?c=user&a=login" target="_blank"><?php  echo $_W['siteroot'];?>web/city.php?c=user&a=login</a></p>
        </div>
    </div>
    <div class="panel panel-default">

        <div class="panel-heading">

            账号管理

        </div>

        <div class="panel-body" style="padding: 0px 15px;">

            <div class="row">

                <table class="yg5_tabel col-md-12">

                    <tr class="yg5_tr1">

                        <th class="store_td1 col-md-2">顺序</th>

                        <th class="col-md-1">(ID)账号</th>

                        <th class="col-md-1">所属城市</th>

                      <!--   <th class="col-md-2">角色</th> -->

                       <th class="col-md-2">状态</th>

                        <th class="col-md-2">操作</th>
                        <th class="col-md-1">管理入口</th>

                    </tr>

                       <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>

                    <tr class="yg5_tr2">

                        <td><div class="type-parent"> <?php  echo $key+1?></div></td>

                        <td><div class="type-parent"><?php  echo $item['username'];?></div></td>

                        <td><div class="type-parent"><?php  echo $item['cityname'];?></div></td>

                     <!--    <td><a class="btn btn-info btn-sm" href="javascript:void(0);">店长</a></td>
                      -->
                        <?php  if($item['status']==2) { ?>

                      <td><a class="btn storeblue btn-xs" href="javascript:void(0);">启用</a></td> 

                      <?php  } else if($item['status']==1) { ?>

                       <td><a class="btn storegrey btn-xs" href="javascript:void(0);">禁用</a></td> 

                       <?php  } ?>
                       <?php  if($_W['role'] == 'operator') { ?>
                       <td>
                       <a class="btn storered btn-xs" href="<?php  echo $this->createWebUrl('inindex', array('cityname' => $item['cityname']))?>" title="编辑">管理</a></a>
                        </td>
                        <?php  } else { ?>
                        <td>
                        <a href="<?php  echo $this->createWebUrl('countadd', array('id' => $item['id']))?>" class="storespan btn btn-xs">
                            <span class="fa fa-pencil"></span>
                            <span class="bianji">编辑
                                <span class="arrowdown"></span>
                            </span>                            
                        </a>
                        <a href="<?php  echo $this->createWebUrl('account', array('op' => 'delete', 'id' => $item['id']))?>" class="storespan btn btn-xs" onclick="return confirm('确认删除吗？');return false;">
                            <span class="fa fa-trash-o"></span>
                            <span class="bianji">删除
                                <span class="arrowdown"></span>
                            </span>
                        </a>
                        <!-- <a class="btn btn-warning btn-xs" href="<?php  echo $this->createWebUrl('countadd', array('id' => $item['id']))?>" title="编辑">改</a>&nbsp;&nbsp;
                       <a class="btn btn-danger btn-xs" href="<?php  echo $this->createWebUrl('account', array('op' => 'delete', 'id' => $item['id']))?>" onclick="return confirm('确认删除吗？');return false;" title="删除">删</a> -->
                        </td>
                        <?php  } ?>
                        <td>
                          <a class="btn ygshouqian2 btn-xs" href="<?php  echo $this->createWebUrl('inindex', array('cityname' => $item['cityname'],'account_id'=> $item['uid']))?>" title="编辑">管理</a>
                        </td>
                     
                    </tr>

                      <?php  } } ?>
                   <!--  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                       <div class="modal-dialog" role="document">
                           <div class="modal-content">
                               <div class="modal-header">
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                               </div>
                               <div class="modal-body" style="font-size: 20px">
                                   <span style="color: #333;font-size: 14px;">您确定删除吗？</span>
                               </div>
                               <div class="modal-footer">
                                   <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                       <a href="<?php  echo $this->createWebUrl('account', array('op' => 'delete', 'id' => $item['id']))?>" type="button" class="btn btn-info">确定</a>
                               </div>
                           </div>
                       </div>
                   </div> -->
             

              <?php  if(empty($list)) { ?>

             <tr class="yg5_tr2">

                <td colspan="12">

                  暂无账号信息

                </td>

              </tr>

             

              <?php  } ?>                    

                </table>

            </div>

        </form>

    </div>



</div>
<div class="text-right we7-margin-top"><?php  echo $pager;?></div>
<script type="text/javascript">
    $(function(){
        $("#frame-15").show();
        $("#yframe-15").addClass("wyactive");
    })
</script>