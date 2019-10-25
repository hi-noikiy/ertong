<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="<?php  echo $this->createWebUrl('zxtype')?>">资讯分类管理</a></li>
    <li><a href="<?php  echo $this->createWebUrl('addzxtype')?>">添加信息分类</a></li>
</ul>
<div class="main">
    <!-- 门店列表部分开始 -->

        <div class="panel panel-default">

            <div class="panel-heading">
                资讯分类管理
            </div>
            <div class="panel-body" style="padding: 0px 15px;">
                <div class="row">
                    <table class="yg5_tabel col-md-12">
                        <tr class="yg5_tr1">
                            <td class="store_td1 col-md-1">顺序</td>
                            <td class="col-md-2">分类图标</td>
                            <td class="col-md-2">分类名称</td>
                            <!-- <td class="col-md-2">价格</td> -->
                            <td class="col-md-3">操作</td>
                        </tr>
                        <?php  if(is_array($list)) { foreach($list as $row) { ?>
                        <tr class="yg5_tr2">
                            <td class="num<?php  echo $row['id'];?>">
                            	<span class="numspan<?php  echo $row['id'];?>"><?php  echo $row['sort'];?></span>
                        		<input style="display: none;width: 100%;" type="number" name="num<?php  echo $row['id'];?>" class="numinp<?php  echo $row['id'];?>" value="<?php  echo $row['num'];?>" />
                            <!--     <script type="text/javascript">
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
                                                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=UpdType&m=zh_tcwq",
                                                    dataType:"text",
                                                    data:{id:id,num:num},
                                                    success:function(data){
                                                        console.log(data);
                                                    }
                                                })
                                        
                                            });
                                        });
                                    })
                                </script> -->
                            </td>
                            <td>
                                <img class="store_list_img" src="<?php  echo tomedia($row['icon']);?>" alt=""/>                                
                            </td>
                            <td><?php  echo $row['type_name'];?></td>
                        <!--     <td class="money<?php  echo $row['id'];?>">
                            <span class="moneyspan<?php  echo $row['id'];?>"><?php  echo $row['money'];?></span>
                                <input style="display: none;width: 100%;" type="text" name="money<?php  echo $row['id'];?>" class="moneyinp<?php  echo $row['id'];?>" value="<?php  echo $row['money'];?>" />
                                <script type="text/javascript">
                                    $(function(){
                                        $(".money<?php  echo $row['id'];?>").each(function(index){
                                             $(this).dblclick(function(){
                                                $(".moneyinp<?php  echo $row['id'];?>").eq(index).show().focus();
                                                $(".moneyspan<?php  echo $row['id'];?>").eq(index).hide();                                            
                                            });
                                        });
                                        $(".moneyinp<?php  echo $row['id'];?>").each(function(index){
                                            $(this).blur(function(){            
                                                $(".moneyinp<?php  echo $row['id'];?>").eq(index).hide();
                                                $(".moneyspan<?php  echo $row['id'];?>").eq(index).show();
                                                var text = $(".moneyspan<?php  echo $row['id'];?>").html();
                                                var inp = $(" input[ name='money<?php  echo $row['id'];?>' ] ").val();
                                                $(".moneyspan<?php  echo $row['id'];?>").html(inp);
                                                // console.log(inp);
                                                id = <?php  echo $row['id'];?>;
                                                money = inp;
                                                $.ajax({
                                                    type:"post",
                                                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=UpdType&m=zh_tcwq",
                                                    dataType:"text",
                                                    data:{id:id,money:money},
                                                    success:function(data){
                                                        console.log(data);
                                                    }
                                                })
                                        
                                            });
                                        });
                                    })
                                </script>
                        </td> -->
                            <td>
                                <a href="<?php  echo $this->createWebUrl('addzxtype', array('id' => $row['id']))?>" class="storespan btn btn-xs">
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
                            <!-- <a class="btn btn-warning btn-xs" href="<?php  echo $this->createWebUrl('addzxtype', array('id' => $row['id']))?>" title="编辑">改</a>&nbsp;&nbsp;
                           <button type="button" class="btn btnblue btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $row['id'];?>">删</button> -->
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
                            <a href="<?php  echo $this->createWebUrl('zxtype', array('op'=>'delete','id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
                        <?php  } } ?>
                       <?php  if(empty($list)) { ?>
                            <tr class="yg5_tr2">
                            <td colspan="4">
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
<script type="text/javascript">
    $(function(){
        $("#frame-3").show();
        $("#yframe-3").addClass("wyactive");
    })
</script>