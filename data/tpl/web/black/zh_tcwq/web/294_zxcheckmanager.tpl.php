<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
 <li  <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('zxcheckmanager',array('type'=>all));?>">全部资讯</a></li>
 <li   <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('zxcheckmanager',array('type'=>wait,'state'=>1));?>">待审核</a></li>
 <li   <?php  if($type=='ok') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('zxcheckmanager',array('type'=>ok,'state'=>2));?>">已确认</a></li>
 <li   <?php  if($type=='no') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('zxcheckmanager',array('type'=>no,'state'=>3));?>">已拒绝</a></li>
</ul>
<div class="row ygrow">
    <div class="col-lg-12">
        <form action="" method="get" class="col-md-3 ygform">
         <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="zh_tcwq" />
            <input type="hidden" name="do" value="zxcheckmanager" />
            <div class="input-group ygcha">
                <input type="text" name="keywords" class="form-control" placeholder="请输入帖子内容">
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit" value="查找"/>
                </span>

            </div>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </form>
        <div class="col-md-1">
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </div>
    </div><!-- /.col-lg-6 -->
</div>  
<div class="main">
    <div class="panel panel-default">
        <div class="panel-body ygbtn">
            <div class="btn ygshouqian2" id="allselect">批量删除</div>
            <div class="btn ygyouhui2" id="allpass">批量通过</div>
            <div class="btn storegrey2" id="allrefuse">批量拒绝</div>
        </div>        
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            资讯审核管理
        </div>
        <div class="panel-body" style="padding: 0px 15px;">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                    <tr class="yg5_tr1">
                        <td class="store_td1 col-md-1" style="text-align: center;">
                            <input type="checkbox" class="allcheck" />
                            <span class="store_inp">全选</span>                      
                        </td>
                        <td class="store_td1 col-md-1">id</td>
                        <td class=" col-md-1">标题</td>
                        <td class="col-md-1">分类名称</td>
                         <td class="col-md-2">内容</td>
                       <td class="col-md-1">分布人</td>
                        <td class="col-md-2">发布时间</td>
                        <td class="col-md-1">状态</td>
                        <td class="col-md-2">操作</td>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                    <tr class="yg5_tr2">
                        <td>
                            <input type="checkbox" name="test" value="<?php  echo $item['id'];?>">
                        </td>
                        <td><?php  echo $item['id'];?></td>
                        <td><?php  echo $item['title'];?></td>
                        <td><?php  echo $item['type_name'];?></td>
                        <td><?php  echo $item['content'];?></td>
                        <td><?php  echo $item['wname'];?></td>
                        <td><?php  echo $item['time'];?></td>
                        <?php  if($item['state']==1) { ?>
                        <td>
                            <span class="label storered">待审核</span>
                        </td >
                        <?php  } else if($item['state']==2) { ?>
                        <td >
                            <span class="label storeblue">已通过</span>
                        </td>
                        <?php  } else if($item['state']==3) { ?>
                        <td>
                           <span class="label storegrey">已拒绝</span>
                       </td>
                       <?php  } ?> 
                       <td>
                            <?php  if($item['state']==1) { ?>
                           <a href="<?php  echo $this->createWebUrl('zxcheckmanager',array('op'=>'tg','id'=>$item['id']));?>"><button class="btn btn-xs storeblue">通过</button></a>
                           <a href="<?php  echo $this->createWebUrl('zxcheckmanager',array('op'=>'jj','id'=>$item['id']));?>"><button class="btn btn-xs storegrey">拒绝</button></a>
                           <?php  } ?>
                            <a href="<?php  echo $this->createWebUrl('zxcheckinfo',array('id'=>$item['id']));?>" class="storespan btn btn-xs">
                                <span class="fa fa-pencil"></span>
                                <span class="bianji">编辑
                                    <span class="arrowdown"></span>
                                </span>                            
                            </a>
                            <a href="javascript:void(0);" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">
                                <span class="fa fa-trash-o"></span>
                                <span class="bianji">删除
                                    <span class="arrowdown"></span>
                                </span>
                            </a>
                           <!-- <a href="<?php  echo $this->createWebUrl('zxcheckinfo',array('id'=>$item['id']));?>"><button class="btn btn-success">查看</button></a>
                           
                           <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">删</button> -->
                       </td>

                   </tr>
                   <div class="modal fade" id="myModal<?php  echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <a href="<?php  echo $this->createWebUrl('zxcheckmanager', array('op' => 'delete', 'id' => $item['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  } } ?>
            <?php  if(empty($list)) { ?>
            <tr class="yg5_tr2">
            <td colspan="9">
                  暂无帖子信息
              </td>
          </tr>
          <?php  } ?>
      </table>
  </div>
</div>
</div>
</div>
<div class="text-right we7-margin-top">
   <?php  echo $pager;?>
</div>
<script type="text/javascript">
    $(function(){
        // ———————————————批量删除———————————————
        $("#allselect").on('click',function(){
            var check = $("input[type=checkbox][class!=allcheck]:checked");
            if(check.length < 1){
                alert('请选择要删除的资讯!');
                return false;
            }else if(confirm("确认要删除此资讯?")){
                var id = new Array();
                check.each(function(i){
                    id[i] = $(this).val();
                });
                console.log(id)
                $.ajax({
                    type:"post",
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=AlldeleteZx&m=zh_tcwq",
                    dataType:"text",
                    data:{id:id},
                    success:function(data){
                        console.log(data);      
                       location.reload();
                    }
                })
               
            }
        });
        
        // ———————————————批量通过———————————————
        $("#allpass").on('click',function(){
            var check = $("input[type=checkbox][class!=allcheck]:checked");
            if(check.length < 1){
                alert('请选择要通过的帖子!');
                return false;
            }else if(confirm("确认要通过此帖子?")){
                var id = new Array();
                check.each(function(i){
                    id[i] = $(this).val();
                });
                console.log(id)
                $.ajax({
                    type:"post",
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=AdoptZx&m=zh_tcwq",
                    dataType:"text",
                    data:{id:id},
                    success:function(data){
                        console.log(data);      
                       location.reload();
                    }
                })               
            }
        });

        // ———————————————批量拒绝———————————————
        $("#allrefuse").on('click',function(){
            var check = $("input[type=checkbox][class!=allcheck]:checked");
            if(check.length < 1){
                alert('请选择要拒绝的帖子!');
                return false;
            }else if(confirm("确认要拒绝此帖子?")){
                var id = new Array();
                check.each(function(i){
                    id[i] = $(this).val();
                });
                console.log(id)
                $.ajax({
                    type:"post",
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=RejectZx&m=zh_tcwq",
                    dataType:"text",
                    data:{id:id},
                    success:function(data){
                        console.log(data);      
                       location.reload();
                    }
                })               
            }
        });

        $(".allcheck").on('click',function(){
            var checked = $(this).get(0).checked;
            $("input[type=checkbox]").prop("checked",checked);
        });

        $("#frame-3").show();
        $("#yframe-3").addClass("wyactive");
    })
</script>