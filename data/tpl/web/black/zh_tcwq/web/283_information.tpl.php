<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">

<ul class="nav nav-tabs">
  <span class="ygxian"></span>
  <div class="ygdangq">当前位置:</div> 
 <li   <?php  if($type=='wait') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('information',array('type'=>wait,'state'=>1));?>">待审核</a></li>
 <li   <?php  if($type=='ok') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('information',array('type'=>ok,'state'=>2));?>">已确认</a></li>
 <li   <?php  if($type=='no') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('information',array('type'=>no,'state'=>3));?>">已拒绝</a></li>
 <li  <?php  if($type=='all') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('information',array('type'=>all));?>">全部帖子</a></li>
</ul>
<div class="row ygrow">
    <div class="col-lg-12">
    <div class="panel panel-default ygbody">
        <div class="panel-body">
            <p class="yangshi">跳转帖子页面地址,id在帖子列表中获取:&nbsp;&nbsp;<a>../infodetial/infodetial?id=1</a></p>
        </div>
    </div>
        <form action="" method="get" class="col-md-3">
          <input type="hidden" name="c" value="site" />
          <input type="hidden" name="a" value="entry" />
          <input type="hidden" name="m" value="zh_tcwq" />
          <input type="hidden" name="do" value="information" />
            <div class="input-group" style="width: 300px">
                <input type="text" name="keywords" class="form-control" value="<?php  echo $_GPC['keywords'];?>" placeholder="请输入帖子联系人名称/电话/内容/城市名称/发布人">
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit" value="查找"/>
                </span>
            </div>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
           <input type="hidden" name="type" value="all"/>
        </form>
         <form action="" method="get" class="col-md-3">
          <input type="hidden" name="c" value="site" />
          <input type="hidden" name="a" value="entry" />
          <input type="hidden" name="m" value="zh_tcwq" />
          <input type="hidden" name="do" value="information" />
            <div class="input-group" style="width: 100px">
                <?php  echo tpl_form_field_daterange('time',$_GPC['time']);?>
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit2" value="查找"/>
                     <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                       <input type="hidden" name="type" value="all"/>
                </span>
            </div><!-- /input-group -->
        </form>
        <form action="" method="get" class="col-md-3">
          <input type="hidden" name="c" value="site" />
          <input type="hidden" name="a" value="entry" />
          <input type="hidden" name="m" value="zh_tcwq" />
          <input type="hidden" name="do" value="information" />
            <div class="input-group" style="width: 100px">
               <div class="col-md-3 yg5_key">
                <div></div>
                <select class="input-group" style="width: 200px" name="type_id">  
                <option value="">不限</option>
                     <?php  if(is_array($tztype)) { foreach($tztype as $row) { ?>  
                     <?php  if($_GPC['type_id']==$row['id']) { ?>                            
                    <option value="<?php  echo $row['id'];?>" selected><?php  echo $row['type_name'];?></option>
                    <?php  } else { ?>
                    <option value="<?php  echo $row['id'];?>"><?php  echo $row['type_name'];?></option>
                    <?php  } ?>
                       <?php  } } ?>
                </select>
                </div>
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit2" value="查找"/>
                     <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                       <input type="hidden" name="type" value="all"/>
                </span>
            </div><!-- /input-group -->
        </form>
        <form action="" method="get" class="col-md-2">
            <div class="input-group" style="width: 100px">
            <input type="hidden" name="c" value="site" />
	          <input type="hidden" name="a" value="entry" />
	          <input type="hidden" name="m" value="zh_tcwq" />
	          <input type="hidden" name="do" value="information" />
	          <input type="hidden" name="top" value="1"/>
	          <input type="hidden" name="type" value="all"/>
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit3" value="置顶搜索"/>
                     <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                </span>
            </div><!-- /input-group -->
        </form>
        
        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
    </div><!-- /.col-lg-6 -->
</div>  
<div class="main">
  <div class="panel panel-default">
        <div class="panel-body ygbtn">
             <div class="btn ygshouqian2" id="allselect">批量删除</div>
             <div class="btn ygyouhui2" id="allpass">批量通过</div>
             <div class="btn storegrey2" id="allrefuse">批量拒绝</div>
             <div class="btn storegreen" id="allshuax">批量刷新 </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            帖子管理
        </div>
        <div class="panel-body" style="padding: 0px 15px;">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                    <tr class="yg5_tr1">
                       <th class="store_td1 col-md-1" style="width: 50px" style="text-align: center;">
	                      <input type="checkbox" class="allcheck" />
	                      <span class="store_inp">全选</span>                      
	                  </th>
                        <td style="width: 10px">帖子id</td>  
                       <td class="col-md-1">所属城市</td>
                       <td class="col-md-1">所属分类</td>
                          <td class="col-md-1">发布时间</td>
                        <td class="col-md-1">发布人(id)</td>
                        <td class="col-md-1">发布人电话</td>
                         <td class="col-md-1">帖子简介</td>
                        <td class="col-md-1">是否置顶</td>
                       
                         <td class="col-md-1">置顶期限</td>
                          
                        <td class="col-md-1">状态</td>
                        <td class="col-md-2">操作</td>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
                    <tr class="yg5_tr2">
                       <td>
	                      <input type="checkbox" name="test" value="<?php  echo $item['id'];?>">
	                     </td>
                        <td><?php  echo $item['id'];?></td>
                        <td><?php  if($item['cityname']) { ?><?php  echo $item['cityname'];?><?php  } else { ?>全国版<?php  } ?></td>
                        <td><?php  echo $item['type_name'];?></td>
                        <td><?php  echo date("Y-m-d H:i:s",$item['time'])?></td>
                        <td><?php  echo $item['user_name'];?>（<?php  echo $item['user_id'];?>）</td>
                        <td><?php  echo $item['user_tel'];?></td>
                         <td><?php  echo substr($item['details'],0,100)?></td>
                       <!--  <td><?php  if($item['hot']==1) { ?><span class="label label-info">是</span><?php  } else { ?>否<?php  } ?></td> -->
                        <td><?php  if($item['top']==1) { ?><span class="label storeblue">是</span><?php  } else { ?>
                        <span class="label storegrey">否</span>
                        <?php  } ?></td>
                     
                          <td><?php  if($item['dq_time']) { ?><?php  echo date("Y-m-d H:i:s",$item['dq_time'])?><?php  } ?></td>
                            
                          
                        <?php  if($item['state']==1) { ?>
                        <td>
                            <span class="label storered">待审核</span>
                            <?php  if($item['hb_money']>0) { ?><span class="label storered">红包</span><?php  } ?>
                        </td >
                        <?php  } else if($item['state']==2) { ?>
                        <td >
                            <span class="label storeblue">已通过</span>
                            <?php  if($item['hb_money']>0) { ?><span class="label storered">红包</span><?php  } ?>
                        </td>
                        <?php  } else if($item['state']==3) { ?>
                        <td>
                           <span class="label storegrey">已拒绝</span>
                           <?php  if($item['hb_money']>0) { ?><span class="label storered">红包</span><?php  } ?>
                       </td>
                       <?php  } ?> 
                       <td>
                          <?php  if($item['state']==1) { ?>
                           <a href="<?php  echo $this->createWebUrl('information',array('op'=>'tg','id'=>$item['id'],'type'=>$_GPC['type'],'page'=>$_GPC['page']));?>"><button class="btn ygyouhui2 btn-xs">通过</button></a>
                           <a href="<?php  echo $this->createWebUrl('information',array('op'=>'jj','id'=>$item['id'],'type'=>$_GPC['type'],'page'=>$_GPC['page']));?>"><button class="btn ygshouqian2 btn-xs">拒绝</button></a>
                           <?php  } ?>
                           <?php  $user=pdo_get('zhtc_user',array('id'=>$item['user_id']));?>
                           <?php  if($user['state']==1) { ?>
                      <a href="" data-toggle="modal" data-target="#myModala<?php  echo $item['user_id'];?>"><button class="btn storegrey2 btn-xs">拉黑</button></a>
                      <?php  } else if($user['state']==2) { ?>
                      <a href="" data-toggle="modal" data-target="#myModalb<?php  echo $item['user_id'];?>"><button class="btn storegrey2 btn-xs">取消拉黑</button></a>
                      <?php  } ?>
                          <a href="<?php  echo $this->createWebUrl('informationinfo',array('id'=>$item['id'],'type'=>$_GPC['type'],'page'=>$_GPC['page']));?>" class="storespan btn btn-xs">
                              <span class="fa fa-pencil"></span>
                              <span class="bianji">编辑
                                  <span class="arrowdown"></span>
                              </span>                            
                          </a>
                          <a href="" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">
                              <span class="fa fa-trash-o"></span>
                              <span class="bianji">删除
                                  <span class="arrowdown"></span>
                              </span>
                          </a>
                           <!-- <a href="<?php  echo $this->createWebUrl('informationinfo',array('id'=>$item['id']));?>"><button class="btn btn-success btn-xs">查看</button></a>
                           
                           <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>">删</button> -->
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
                            <a href="<?php  echo $this->createWebUrl('information', array('op' => 'delete', 'id' => $item['id'],'type'=>$_GPC['type'],'page'=>$_GPC['page']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModala<?php  echo $item['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                        </div>
                        <div class="modal-body" style="font-size: 20px">
                            确定要拉黑么？
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a href="<?php  echo $this->createWebUrl('information', array('op' => 'defriend', 'id' => $item['user_id'],'type'=>$_GPC['type'],'page'=>$_GPC['page']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModalb<?php  echo $item['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                        </div>
                        <div class="modal-body" style="font-size: 20px">
                            确定要取消拉黑么？
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <a href="<?php  echo $this->createWebUrl('information', array('op' => 'relieve', 'id' => $item['user_id'],'type'=>$_GPC['type'],'page'=>$_GPC['page']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  } } ?>
            <?php  if(empty($list)) { ?>
            <tr class="yg5_tr2">
            <td colspan="11">
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
        $("#frame-1").show();
        $("#yframe-1").addClass("wyactive");
    	// ———————————————批量删除———————————————
        $("#allselect").on('click',function(){
            var check = $("input[type=checkbox][class!=allcheck]:checked");
            if(check.length < 1){
                alert('请选择要删除的帖子!');
                return false;
            }else if(confirm("确认要删除此帖子?")){
                var id = new Array();
                check.each(function(i){
                    id[i] = $(this).val();
                });
                console.log(id)
                $.ajax({
                    type:"post",
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=alldeleteinfo&m=zh_tcwq",
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
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=AdoptInfo&m=zh_tcwq",
                    dataType:"text",
                    data:{id:id},
                    success:function(data){
                        console.log(data);      
                       location.reload();
                    }
                })               
            }
        });


         // ———————————————批量刷新———————————————
        $("#allshuax").on('click',function(){
            var check = $("input[type=checkbox][class!=allcheck]:checked");
            if(check.length < 1){
                alert('请选择要刷新的帖子!');
                return false;
            }else if(confirm("确认要刷新此帖子?")){
                var id = new Array();
                check.each(function(i){
                    id[i] = $(this).val();
                });
                console.log(id)
                $.ajax({
                    type:"post",
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=Refresh&m=zh_tcwq",
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
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=RejectInfo&m=zh_tcwq",
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
        
    })
</script>
