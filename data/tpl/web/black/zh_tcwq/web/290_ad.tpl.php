<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="<?php  echo $this->createWebUrl('ad')?>">广告管理</a></li>
    <li><a href="<?php  echo $this->createWebUrl('addad')?>">添加广告</a></li>
    <li style="float: right;">
        <div class="row ygrow">

    <div class="col-lg-12">
     <form action="" method="get" class="col-md-3">
      <input type="hidden" name="c" value="site" />
      <input type="hidden" name="a" value="entry" />
      <input type="hidden" name="m" value="zh_tcwq" />
      <input type="hidden" name="do" value="ad" />
      <div class="input-group" style="width: 100px">
       <div class="col-md-3 yg5_key">
        <div></div>
        <select class="input-group" style="width: 200px" name="type_id">  
         <option value="1" <?php  if($type_id==1) { ?>selected<?php  } ?>>首页幻灯片</option>
         <option value="7" <?php  if($type_id==1) { ?>selected<?php  } ?>>首页广告位</option>
         <option value="10" <?php  if($type_id==10) { ?>selected<?php  } ?>>首页中部广告位</option>
         <option value="2" <?php  if($type_id==2) { ?>selected<?php  } ?>>商家页面幻灯片</option>
         <option value="3" <?php  if($type_id==3) { ?>selected<?php  } ?>>资讯页面幻灯片</option>
         <option value="4" <?php  if($type_id==4) { ?>selected<?php  } ?>>拼车页面幻灯片</option>
         <!--                             <option value="5" <?php  if($info['type']==5) { ?>selected<?php  } ?>>开屏广告</option> -->
         <option value="6" <?php  if($type_id==6) { ?>selected<?php  } ?>>黄页广告</option>
         <option value="8" <?php  if($type_id==8) { ?>selected<?php  } ?>>分类广告位</option>
         <option value="9" <?php  if($type_id==9) { ?>selected<?php  } ?>>积分商城广告位</option>  
         <option value="11" <?php  if($type_id==11) { ?>selected<?php  } ?>>活动中心广告位</option>  
         <option value="12" <?php  if($type_id==12) { ?>selected<?php  } ?>>限时抢购广告位</option>
         <option value="13" <?php  if($type_id==13) { ?>selected<?php  } ?>>拼团广告位</option>  
         <option value="14" <?php  if($type_id==14) { ?>selected<?php  } ?>>优惠券中心广告位</option>
     </select>
 </div>
 <span class="input-group-btn">
    <input type="submit" class="btn btn-default" name="submit2" value="查找"/>
    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
    <input type="hidden" name="type" value="all"/>
</span>
</div><!-- /input-group -->
</form>
       <!--  <div class="col-md-6">
           <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
       </div> -->
   </div><!-- /.col-lg-6 -->
</div> 
    </li>
</ul>

<div class="main">
    <div class="panel panel-default">
        <div class="panel-heading">
            广告管理
        </div>
        <div class="panel-body" style="padding: 0px 15px;">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                    <tr class="yg5_tr1">
                        <th class="store_td1 col-md-1">排序</th>
                        <th class="col-md-1">城市</th>
                        <th class="col-md-2">标题</th>
                        <th class="col-md-2">图片</th>
                        <!--    <th class="col-md-2">链接地址</th> -->
                        <th class="col-md-1">类型</th>
                        
                        <th class="col-md-1">状态</th>
                        <th class="col-md-2">操作</th>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <tr class="yg5_tr2">
                        <!-- <td><div class="type-parent"><?php  echo $row['orderby'];?>&nbsp;&nbsp;</div></td> -->
                        <tr class="yg5_tr2">
                            <td class="num<?php  echo $row['id'];?>">
                                <span class="numspan<?php  echo $row['id'];?>"><?php  echo $row['orderby'];?></span>
                                <input style="display: none;width: 100%;" type="number" name="num<?php  echo $row['id'];?>" class="numinp<?php  echo $row['id'];?>" value="<?php  echo $row['orderby'];?>" />
                                <script type="text/javascript">
                                    $(function(){
                                        $(".num<?php  echo $row['id'];?>").each(function(index){
                                         $(this).dblclick(function(){
                                            $(".numinp<?php  echo $row['id'];?>").eq(index).show().focus();
                                            $(".numspan<?php  echo $row['id'];?>").eq(index).hide();                                         
                                        });
                                     });
                                        $(".numinp<?php  echo $row['id'];?>").each(function(index){
                                            $(this).blur(function(){       
                                                $(".numinp<?php  echo $row['id'];?>").eq(index).hide();
                                                $(".numspan<?php  echo $row['id'];?>").eq(index).show();
                                                var text = $(".numspan<?php  echo $row['id'];?>").html();
                                                var inp = $(" input[ name='num<?php  echo $row['id'];?>' ] ").val();
                                                $(".numspan<?php  echo $row['id'];?>").html(inp);
                                                console.log(inp);
                                                id = <?php  echo $row['id'];?>;
                                                num = inp;
                                                $.ajax({
                                                    type:"post",
                                                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=UpdAd&m=zh_tcwq",
                                                    dataType:"text",
                                                    data:{id:id,num:num},
                                                    success:function(data){
                                                        console.log(data);
                                                    }
                                                })

                                            });
                                        });
                                    })
                                </script>
                            </td>
                            <td><div class="type-parent"><?php  echo $row['cityname'];?>&nbsp;&nbsp;</div></td>
                            <td><?php  echo $row['title'];?></td>
                            <td><div class="type-parent"><img height="40" src="<?php  echo tomedia($row['logo']);?>">&nbsp;&nbsp;</div></td>
                            <!--   <td><div class="type-parent"><?php  echo $row['src'];?>&nbsp;&nbsp;</div></td> -->
                            <td><div class="type-parent"><?php  if($row['type']==1) { ?>首页幻灯片<?php  } else if($row['type']==2) { ?>商家页面幻灯片<?php  } else if($row['type']==3) { ?>资讯页面幻灯片<?php  } else if($row['type']==4) { ?>拼车页面幻灯片<?php  } else if($row['type']==5) { ?>开屏广告<?php  } else if($row['type']==6) { ?>黄页广告<?php  } else if($row['type']==7) { ?>首页广告位<?php  } else if($row['type']==8) { ?>分类广告位<?php  } else if($row['type']==9) { ?>积分商城广告位<?php  } else if($row['type']==10) { ?>首页中部广告位<?php  } else if($row['type']==11) { ?>活动中心广告位<?php  } else if($row['type']==12) { ?>限时抢购广告位<?php  } else if($row['type']==13) { ?>拼团广告位<?php  } else if($row['type']==14) { ?>优惠券中心广告位<?php  } ?></div></td>

                            <?php  if($row['status']==1) { ?>
                            <td><button type="button" class="btn ygyouhui2 btn-xs" data-toggle="modal" data-target="#myModal2<?php  echo $row['id'];?>">点击禁用</button></td>
                            <?php  } else if($row['status']==2) { ?>
                            <td><button type="button" class="btn storegrey2 btn-xs" data-toggle="modal" data-target="#myModal3<?php  echo $row['id'];?>">点击启用</button></td>
                            <?php  } ?>
                            <td>
                                <a href="<?php  echo $this->createWebUrl('addad', array('id' => $row['id']))?>" class="storespan btn btn-xs">
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
                                <a href="<?php  echo $this->createWebUrl('ad', array('op' => 'delete', 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
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
                        <a href="<?php  echo $this->createWebUrl('ad', array('status' => 2, 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
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
                <a href="<?php  echo $this->createWebUrl('ad', array('status' => 1, 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
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