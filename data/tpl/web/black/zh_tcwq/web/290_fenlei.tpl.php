<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcss.css">
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<style type="text/css">
    .store_list_img{width: 40px;height: 40px;background-color: pink;}
    .yg5_tabel{border: none;outline: none;}
    .yg5_tr3>td{border-bottom: 1px solid #efefef;padding: 5px 0px;text-align: center;}
    .yg5_tr4>td{border-bottom: 1px solid #efefef;padding: 5px 0px;text-align: center;}
    .yg5_tr2>td{padding: 10px 0px;border-bottom: 1px solid #efefef;text-align: center;
        /*background-color: #EDF6FF;*/
        
    }
    .yg5_tr1{border-bottom: 1px solid #efefef;font-weight: bold;text-align: center;
    }
    .fenleilist1{height: 35px;line-height: 35px;}
    .yg5_tr3>td:nth-child(1),.yg5_tr1>td:nth-child(1){width: 80px;text-align: center;}
    .yg5_tr3>td:nth-child(2),.yg5_tr1>td:nth-child(2){width: 80px;}
    .yg5_tr3>td:nth-child(3),.yg5_tr1>td:nth-child(3){width: 120px;}
    .yg5_tr3>td:nth-child(4),.yg5_tr1>td:nth-child(4){width: auto;}
    .yg5_tr3>td:nth-child(5),.yg5_tr1>td:nth-child(5){width: 120px;}
    .yg5_tr3>td:nth-child(6),.yg5_tr1>td:nth-child(6){width: 120px;}
    .yg5_tr3>td:nth-child(7),.yg5_tr1>td:nth-child(7){width: 150px;}
    .yg5_tr3>td:nth-child(8),.yg5_tr1>td:nth-child(8){width: auto;}
    .yg5_tr3>td:nth-child(9),.yg5_tr1>td:nth-child(9){width: auto;}
    .yg5_tr3>td:nth-child(4){padding-left: 30px;}
    .yg5_tr3:hover{background-color: #EDF6FF;}
    .yg5_tr2:hover{background-color: #EDF6FF;}
    .fxiala{font-size: 16px;cursor: pointer;opacity: 0.5;display: inline-block;width: 50px;height: 20px;text-align: center;}
    .fxiala:hover{color: #333;opacity: 1;}
    .collapse{transition: all 0.5s;}
    .ygsave{margin-top: 50px;}
    .storespan2{font-size: 14px;color: white;margin: 5px;position: relative;background-color: #44abf7;}
    .storespan2:hover{color: #fff;}
    .storespan2:hover .bianji{display: block;}
    .feileibqbox{position: relative;margin-right: 15px;display: inline-block;padding: 5px;}
    .feileibq2{position: absolute;top: -5px;right: -5px;cursor: pointer;}
    .feileibq2>img{width: 25px;height: 25px;}
    .feileibqremark{width: 80px;height: 20px;}
    .storegrey3{width: 120px;height: 35px;padding: 0px 10px;border-radius: 10px;text-align: center;outline: none;}
    .xgsuccess{position: absolute;top: 17%;left: 0%;z-index: 1080;display: none;}
</style>
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="javascript:void(0);">分类列表</a></li>
</ul>
<div class="main">
<div class="panel panel-default ygbody">
        <div class="panel-body">
            <p class="yangshi">跳转帖子分类地址,id和name值在分类列表中获取:&nbsp;&nbsp;<a>../marry/marry?id=1&name=招聘求职</a></p>
        </div>
    </div>
    
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="invitative">
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                分类列表
                <a href="<?php  echo $this->createWebUrl('addtype', array('type2_id' => $son['id']))?>" class="storespan2 btn btn-xs">
                    <span class="fa fa-plus"></span>添加分类
                    <span class="bianji" style="left: 15px;">添加分类
                        <span class="arrowdown"></span>
                    </span>                            
                </a>
            </div>
            <div class="panel-body" style="padding: 0px 15px;">
                <div class="row" id="accordion">
                    <div class="xgsuccess col-md-offset-2 col-md-8 btn btn-success" id="xgsuccess">修改成功</div>
                    <div class="xgsuccess col-md-offset-2 col-md-8 btn btn-success" id="xgsuccess2">修改失败</div>
                    <table class="yg5_tabel col-md-12">
                        <tbody>
                            <tr class="yg5_tr1">
                                <td class="fenleilist1">id</td>
                                <td>顺序</td>
                                <td>图标</td> 
                                <td style="text-align: center;">信息分类名称</td>
                                <td>本地发帖金额</td>
                               	<td>刷新价格</td>
                                <td>状态</td>
                                <td>操作</td>
                                <td></td>
                            </tr>
                        </tbody>
                        <?php  if(is_array($type)) { foreach($type as $row) { ?>
                        <tbody>
                            <tr class="yg5_tr2">
                                <td><?php  echo $row['id'];?></td>
                                <td><?php  echo $row['num'];?></td>
                                <td><img src="<?php  echo tomedia($row['img']);?>" style="width: 30px;height: 30px;"></td> 
                                <td style="text-align: center;"><?php  echo $row['type_name'];?></td>
                                <!-- ————————修改价格———————— -->
                                <td class="money<?php  echo $row['id'];?>">
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
                                </td>
                        		<td class="money2<?php  echo $row['id'];?>">
                        			<span class="moneyspan2<?php  echo $row['id'];?>"><?php  echo $row['sx_money'];?></span>
                        			<input style="display: none;width: 100%;" type="text" name="money2<?php  echo $row['id'];?>" class="moneyinp2<?php  echo $row['id'];?>" value="<?php  echo $row['sx_money'];?>" />
                                    <script type="text/javascript">
                                        $(function(){
                                            $(".money2<?php  echo $row['id'];?>").each(function(index){
                                                 $(this).dblclick(function(){
                                                    $(".moneyinp2<?php  echo $row['id'];?>").eq(index).show().focus();
                                                    $(".moneyspan2<?php  echo $row['id'];?>").eq(index).hide();                                           
                                                });
                                            });
                                            $(".moneyinp2<?php  echo $row['id'];?>").each(function(index){
                                                $(this).blur(function(){            
                                                    $(".moneyinp2<?php  echo $row['id'];?>").eq(index).hide();
                                                    $(".moneyspan2<?php  echo $row['id'];?>").eq(index).show();
                                                    var text = $(".moneyspan2<?php  echo $row['id'];?>").html();
                                                    var inp = $(" input[ name='money2<?php  echo $row['id'];?>' ] ").val();
                                                    $(".moneyspan2<?php  echo $row['id'];?>").html(inp);
                                                    // console.log(inp);
                                                    id = <?php  echo $row['id'];?>;
                                                    money = inp;
                                                    $.ajax({
                                                        type:"post",
                                                        url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=UpdType&m=zh_tcwq",
                                                        dataType:"text",
                                                        data:{id:id,sx_money:money},
                                                        success:function(data){
                                                            console.log(data);
                                                        }
                                                    })
                                            
                                                });
                                            });
                                        })
                                    </script>
                        		</td> 
                                <td>
                                    <?php  if($row['state']==1) { ?>
                                    <a href="<?php  echo $this->createWebUrl('fenlei', array('id' => $row['id'],'op'=>change,'state'=>2))?>">
                                        <span class="btn btn-md ygyouhui2">点击禁用</span>
                                    </a>
                                    <?php  } else if($row['state']==2) { ?>
                                    <a href="<?php  echo $this->createWebUrl('fenlei', array('id' => $row['id'],'op'=>change,'state'=>1))?>">
                                        <span class="btn btn-md storegrey2">点击启用</span>
                                    </a>
                                    <?php  } ?>
                                </td>
                                <td>
                                    <a href="<?php  echo $this->createWebUrl('addtype2', array('type_id' => $row['id']))?>" class="storespan btn btn-xs">
                                        <span class="fa fa-plus"></span>
                                        <span class="bianji" style="left: -30px;">添加二级分类
                                            <span class="arrowdown"></span>
                                        </span>                            
                                    </a>                                    
                                    <a href="<?php  echo $this->createWebUrl('addtype', array('id' => $row['id']))?>" class="storespan btn btn-xs">
                                        <span class="fa fa-pencil"></span>
                                        <span class="bianji">编辑
                                            <span class="arrowdown"></span>
                                        </span>                            
                                    </a>
                                    <?php  if(empty($row['ej'])) { ?>
                                    <a href="#myModal2<?php  echo $row['id'];?>" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $row['id'];?>">
                                        <span class="fa fa-trash-o"></span>
                                        <span class="bianji">删除
                                            <span class="arrowdown"></span>
                                        </span>
                                    </a>
                                    
                                    <?php  } else { ?>
                                        <a href="#myModal2<?php  echo $row['id'];?>" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModal2<?php  echo $row['id'];?>">
                                            <span class="fa fa-trash-o"></span>
                                            <span class="bianji">删除
                                                <span class="arrowdown"></span>
                                            </span>
                                        </a>
                                    <?php  } ?>
                                    <div class="modal fade" id="myModal2<?php  echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                                                </div>
                                                <div class="modal-body" style="font-size: 20px">有二级分类，您不能删除此分类！</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">确定</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="myModal<?php  echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                                                </div>
                                                <div class="modal-body" style="font-size: 20px">确定删除么？</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                                    <a href="<?php  echo $this->createWebUrl('fenlei', array('id' => $row['id'],'op'=>'delete'))?>" type="button" class="btn btn-info" >确定</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </td>
                                <td>
                                    <a class="fxiala" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php  echo $row['id'];?>">
                                        <span class="fa fa-chevron-down"></span>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                     
                        <tbody id="collapse<?php  echo $row['id'];?>" class="panel-collapse collapse">
                        <?php  if(empty($row['ej'])) { ?>
                            <tr class="yg5_tr4"><td colspan="8">暂无二级分类</td></tr>
                            <?php  } else { ?>
                          <?php  if(is_array($row['ej'])) { foreach($row['ej'] as $son) { ?>
                            <tr class="yg5_tr3">
                                <td></td>
                                <td><?php  echo $son['num'];?></td> 
                                <td></td>                            
                                <td style="text-align: left;">--<?php  echo $son['name'];?></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <?php  if($son['state']==1) { ?>                                    
                                        <a href="<?php  echo $this->createWebUrl('fenlei', array('id' => $son['id'],'op'=>change2,'state'=>2))?>">
                                        <span class="btn btn-md ygyouhui2">点击启用</span>
                                        </a>
                                    </span>
                                    <?php  } else if($son['state']==2) { ?>                                    
                                        <a href="<?php  echo $this->createWebUrl('fenlei', array('id' => $son['id'],'op'=>change2,'state'=>1))?>">
                                        <span class="btn btn-md storegrey2">点击禁用</span>
                                        </a>
                                    <?php  } ?>
                                </td>
                                <td>
                                    <a href="<?php  echo $this->createWebUrl('addlabel', array('type2_id' => $son['id']))?>" class="storespan btn btn-xs">
                                        <span class="fa fa-plus"></span>
                                        <span class="bianji" style="left: -15px;">添加标签
                                            <span class="arrowdown"></span>
                                        </span>                            
                                    </a>
                                    <a href="<?php  echo $this->createWebUrl('addtype2', array('id' => $son['id']))?>" class="storespan btn btn-xs">
                                        <span class="fa fa-pencil"></span>
                                        <span class="bianji">编辑
                                            <span class="arrowdown"></span>
                                        </span>                            
                                    </a>
                                    <a href="#myModalson<?php  echo $son['id'];?>" class="storespan btn btn-xs" data-toggle="modal" data-target="#myModalson<?php  echo $son['id'];?>">
                                        <span class="fa fa-trash-o"></span>
                                        <span class="bianji">删除
                                            <span class="arrowdown"></span>
                                        </span>
                                    </a>
                                    <div class="modal fade" id="myModalson<?php  echo $son['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">提示</h4>
                                                </div>
                                                <div class="modal-body" style="font-size: 20px">确定删除么？</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                                    <a href="<?php  echo $this->createWebUrl('fenlei', array('id' => $son['id'],'op'=>'delete2'))?>" type="button" class="btn btn-info" >确定</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="fenleibq">
                                   <a href="#myModalck<?php  echo $son['id'];?>" class="storespan btn btn-xs" id="fenlei<?php  echo $son['id'];?>" data-toggle="modal" data-target="#myModalck<?php  echo $son['id'];?>">                                   
                                        <span class="fa fa-eye"></span>
                                        <span class="bianji" style="left: -15px;">查看标签
                                            <span class="arrowdown"></span>
                                        </span>
                                    </a>
                                    <input type="hidden" value="<?php  echo $son['id'];?>" id="flinp<?php  echo $son['id'];?>">
                                    <div class="modal fade" id="myModalck<?php  echo $son['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">标签列表</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="feileibqremark">点击修改标签</p>
                                                    <?php  if(is_array($son['bq'])) { foreach($son['bq'] as $son2) { ?>
                                                        <div class="feileibqbox">
                                                            <input type="text" class="storegrey3" id="fl<?php  echo $son2['id'];?>" value="<?php  echo $son2['label_name'];?>"/>
                                                            <input type="hidden" value="<?php  echo $son2['id'];?>" id="flinp<?php  echo $son2['id'];?>">
                                                            <div class="feileibq2">
                                                                <img src="../addons/zh_tcwq/template/images/shanchu.png" id="flsc<?php  echo $son2['id'];?>">
                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(function(){
                                                                $("#fl<?php  echo $son2['id'];?>").blur(function(){
                                                                    var finpvalue = $("#fl<?php  echo $son2['id'];?>").val();
                                                                    var flinpid = $("#flinp<?php  echo $son2['id'];?>").val();
                                                                    console.log("值是："+finpvalue+"id是："+flinpid)
                                                                    $.ajax({
                                                                        type:"post",
                                                                        url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=UpdTag&m=zh_tcwq",
                                                                        dataType:"text",
                                                                        data:{tag_id:flinpid,label_name:finpvalue},
                                                                        success:function(data){                    
                                                                            // var data = eval('(' + data + ')')
                                                                            console.log(data);
                                                                            if(data = 1){
                                                                                $("#xgsuccess").fadeIn();
                                                                                setTimeout(function(){
                                                                                    $("#xgsuccess").fadeOut();
                                                                                },1500)             
                                                                            }else{
                                                                                $("#xgsuccess2").fadeIn();
                                                                                setTimeout(function(){
                                                                                    $("#xgsuccess2").fadeOut();
                                                                                },1500) 
                                                                            }
                                                                        }
                                                                    })
                                                                })
                                                                $("#flsc<?php  echo $son2['id'];?>").click(function(){
                                                                    var flinpid = $("#flinp<?php  echo $son2['id'];?>").val();
                                                                    var ret = confirm("您确定删除吗？")
                                                                    if (ret==true){
                                                                        $.ajax({
                                                                            type:"post",
                                                                            url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=DelTag&m=zh_tcwq",
                                                                            dataType:"text",
                                                                            data:{tag_id:flinpid},
                                                                            success:function(data){                    
                                                                                // var data = eval('(' + data + ')')
                                                                                console.log(data);
                                                                                location.reload();
                                                                            }
                                                                        })
                                                                    }                                                                    
                                                                })
                                                            })
                                                        </script>                                                  
                                                    <?php  } } ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">确定</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                              <?php  } } ?>
                              <?php  } ?>
                        </tbody>
                      
                        <?php  } } ?>
                        <?php  if(empty($type)) { ?>
                            <tr class="yg5_tr2">
                                <td colspan="12">
                                暂无提现信息
                                </td>
                            </tr> 
                        <?php  } ?>  
                    </table>
                </div>
            </div>        
            <!-- <div class="form-group ygsave">
                <input type="submit" name="submit" value="保存设置" class="btn col-xs-3" style="color: white;background-color: #44ABF7;" />
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
            </div> -->
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        $("#frame-1").show();
        $("#yframe-1").addClass("wyactive");
        // $("#fenlei<?php  echo $son['id'];?>").click(function(){
        //     var flinp = $("#flinp<?php  echo $son['id'];?>").val();
        //     console.log("id值："+flinp)
        //     // $.ajax({
        //     //     type:"post",
        //     //     url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=QueryTag&m=zh_tcwq",
        //     //     dataType:"text",
        //     //     data:{type2_id:25},
        //     //     success:function(data){                    
        //     //         var data = eval('(' + data + ')')
        //     //         console.log(data);                      
        //     //     }
        //     // })
        // })
        
    })
</script>
