<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>    
    <li class="active"><a href="<?php  echo $this->createWebUrl('zx')?>">资讯管理</a></li>
    <li><a href="<?php  echo $this->createWebUrl('addzx')?>">添加资讯</a></li>
</ul>
<div class="row ygrow">

    <div class="col-lg-12">
     <div class="panel panel-default ygbody">
        <div class="panel-body">
            <p class="yangshi">跳转资讯页面地址:&nbsp;&nbsp;<a>../message/message</a></p>
        </div>
    </div>
        <div class="col-md-1">
            <div class="btn btn-sm ygshouqian2" id="allselect">批量删除</div>
        </div>
        <form action="" method="get" class="col-md-4">
           <input type="hidden" name="c" value="site" />
           <input type="hidden" name="a" value="entry" />
           <input type="hidden" name="m" value="zh_tcwq" />
           <input type="hidden" name="do" value="zx" />
           <div class="input-group" style="width: 300px">
               <input type="text" name="keywords" class="form-control" value="<?php  echo $_GPC['keywords'];?>" placeholder="请输入标题">
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit" value="查找"/>
                </span>
                
            </div>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
        </form>
        <form action="" method="get" class="col-md-4">
            <input type="hidden" name="c" value="site" />
             <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="zh_tcwq" />
            <input type="hidden" name="do" value="zx" />
            <div class="input-group" style="width: 100px">
                <?php  echo tpl_form_field_daterange('time',$_GPC['time']);?>
                <span class="input-group-btn">
                    <input type="submit" class="btn btn-default" name="submit2" value="查找"/>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                </span>
            </div><!-- /input-group -->
        </form>
        
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
    </div><!-- /.col-lg-6 -->
</div> 
<div class="main">
    <div class="panel panel-default">
        <div class="panel-heading">
            资讯管理
        </div>
        <div class="panel-body" style="padding: 0px 15px;">
            <div class="row">
                <table class="yg5_tabel col-md-12">
                    <tr class="yg5_tr1">
                        <th class="store_td1 col-md-1" style="text-align: center;">
                            <input type="checkbox" class="allcheck" />
                            <span class="store_inp">全选</span>                      
                        </th>
                        <th class="col-md-1">id</th>
                        <th class="col-md-2">所属城市</th>
                        <th class="col-md-2">所属分类</th>
                        <th class="col-md-2">发布时间</th>
                        
                        <th class="col-md-2 store_td1">标题</th>
                        <!--  <th class="col-md-2">内容</th> -->
                       <th class="col-md-1">发布人</th>
                        <th class="col-md-2">操作</th>
                    </tr>
                    <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <tr class="yg5_tr2">
                        <td>
                            <input type="checkbox" name="test" value="<?php  echo $row['id'];?>">
                        </td>
                        <td><?php  echo $row['id'];?></td>
                        <th class="col-md-2"><?php  echo $row['cityname'];?></th><td><?php  echo $row['type_name'];?></td>
                        <td><?php  echo $row['time'];?></td>
                        
                        <td><div class="type-parent"><?php  echo $row['title'];?>&nbsp;&nbsp;</div></td>
                      
                        <!-- <td><?php  echo $row['content'];?></td> -->
                         <td><?php echo empty($row['wname'])?'平台发布':$row['wname']?></td>
                       
                        <td>
                            <a href="<?php  echo $this->createWebUrl('addzx', array('id' => $row['id']))?>" class="storespan btn btn-xs">
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
                        <!-- <a class="btn btn-warning btn-xs" href="<?php  echo $this->createWebUrl('addzx', array('id' => $row['id']))?>" title="编辑">改</a>&nbsp;&nbsp;<button type="button" class="btn btnblue btn-xs" data-toggle="modal" data-target="#myModal<?php  echo $row['id'];?>">删</button> -->
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
                            <a href="<?php  echo $this->createWebUrl('zx', array('op' => 'delete', 'id' => $row['id']))?>" type="button" class="btn btn-info" >确定</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  } } ?>
            <?php  if(empty($list)) { ?>
            <tr class="yg5_tr2">
                <td colspan="7">
                      暂无商家信息
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
        $("#frame-3").show();
        $("#yframe-3").addClass("wyactive");
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
                   // console.log("id好"+id)
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
        
        $(".allcheck").on('click',function(){
            var checked = $(this).get(0).checked;
            $("input[type=checkbox]").prop("checked",checked);
        });
    })
</script>