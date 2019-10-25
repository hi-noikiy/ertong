<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/zh_tcwq/template/public/ygcsslist.css">
<ul class="nav nav-tabs">    
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li class="active"><a href="javascript:void(0);">评论列表</a></li>
</ul>
 <div class="row" style="padding: 10px;">
   <form action="" method="get">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="zh_tcwq" />
    <input type="hidden" name="do" value="zxpinglun" />
    <div class="col-md-3">
      <div class="input-group">
          <input type="text" name="keywords" class="form-control" placeholder="请输入评论内容">
          <span class="input-group-btn">
             <input type="submit" class="btn btn-default" name="submit" value="查找"/>
          </span>
      </div>
    </div>
    <div class="col-md-5">
      <div class="btn btn-default btnblue" id="allselect">批量删除</div>
    </div>
   <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
  </form>
</div>
<div class="main" style="margin-top: 0px;">
    <!-- 门店列表部分开始 -->
        <div class="panel panel-default">
            <div class="panel-heading">
                评论列表
            </div>
            <div class="panel-body" style="padding: 0px 15px;">
                <div class="row">
                    <table class="col-md-12" >
              	<tr class="yg5_tr1">
                  <th class="store_td1 col-md-1" style="text-align: center;">
                      <input type="checkbox" class="allcheck" />
                      <span class="store_inp">全选</span>                      
                  </th>
                	<th class="col-md-2 store_td1">资讯名称</th>
	                <th class="col-md-2">评价内容</th>
	               <!--  <th class="col-md-1">评价分数</th> -->
	                <th class="col-md-2">评价时间</th>
	                <th class="col-md-1">状态</th>
	                <th class="col-md-2">查看</th>
	                <th class="col-md-2">操作</th>
              </tr>
              <?php  if(is_array($list)) { foreach($list as $key => $item) { ?>
              <tr class="yg5_tr2">
                <td>
                    <input type="checkbox" name="test" value="<?php  echo $item['id'];?>">
                </td>
                <td>
                <?php  echo $item['title'];?>
                </td>
               
                 <td style="text-align: left;">
                <?php  echo $item['content'];?>
                </td>
              <!--   <td>
                <?php  echo $item['score'];?>
              </td> -->
                <td>
                  <?php  echo $item['cerated_time'];?>
                </td>
                <?php  if($item['status']==1) { ?>
                <td>
                <span class="label storeblue">未回复</span>
                </td>
                <?php  } else if($item['status']==2) { ?>
                <td>
                <span class="label storegrey">已回复</span>
                
                </td>
                <?php  } ?>
                <td>
                  <a href="<?php  echo $this->createWebUrl('pingluninfo', array('id'=>$item['id'],'op'=>'info'));?>"" ><span class="label storeblue">查看</span> </a>
                </td>
                  <td>
                  <?php  if($item['status']==1) { ?>
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal<?php  echo $item['id'];?>"><span class="label storeblue">回复</span> </a>
                  <?php  } else { ?>
                <span class="label label-xs storegrey">已回复</span>
                  <?php  } ?>
                  <a href="<?php  echo $this->createWebUrl('zxpinglun', array('id'=>$item['id'],'op'=>'delete'));?>" class="storespan btn btn-xs" onclick="return confirm('确认删除吗？');return false;">
                      <span class="fa fa-trash-o"></span>
                      <span class="bianji">删除
                          <span class="arrowdown"></span>
                      </span>
                  </a>
                  <!-- <a onclick="if(!confirm('删除后将不可恢复,确定删除吗?')) return false;" href="<?php  echo $this->createWebUrl('zxpinglun', array('id'=>$item['id'],'op'=>'delete'));?>" class="btn btn-xs btnblue" data-toggle="tooltip" data-placement="top" title="" data-original-title="删除"><i class="fa fa-times"></i> </a> -->
                </td>
              </tr>
              <?php  } } ?>
              <?php  if(empty($list)) { ?>
             <tr class="yg5_tr2">
                <td colspan="7">
                  暂无评论信息
                </td>
              </tr>
             
              <?php  } ?>
                <div class="modal fade" id="myModal<?php  echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                          <form action="" method="post" enctype="multipart/form-data">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title" id="myModalLabel" style="font-size: 20px;">编辑回复内容</h4>
                                  </div>
                                  <div class="modal-body" style="font-size:20px">
                                      <input type="text" name="reply" class="accout_inp" placeholder="请输入回复内容">
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                      <input type="submit" name="submit2" class="btn btn-info" value="确定">
                                      <input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
                                      <input type="hidden" name="id" value="<?php  echo $item['id'];?>"/>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              
              
          </table>
                </div>
            </div>
        </div>
    
</div>
<div class="text-right we7-margin-top"><?php  echo $pager;?></div>
<script type="text/javascript">
    $(function(){
        $("#frame-3").show();
        $("#yframe-3").addClass("wyactive");
        $(".allcheck").on('click',function(){
            var checked = $(this).get(0).checked;
            $("input[type=checkbox]").prop("checked",checked);
        });
        // ———————————————批量删除———————————————
        $("#allselect").on('click',function(){
            var check = $("input[type=checkbox][class!=allcheck]:checked");
            if(check.length < 1){
                alert('请选择要删除的评论!');
                return false;
            }else if(confirm("确认要删除此评论?")){
                var id = new Array();
                check.each(function(i){
                    id[i] = $(this).val();
                });
                console.log(id)
                $.ajax({
                    type:"post",
                    url:"<?php  echo $_W['siteroot'];?>/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&do=DeleteZxAssess&m=zh_tcwq",
                    dataType:"text",
                    data:{id:id},
                    success:function(data){
                        console.log(data);      
                       location.reload();
                    }
                })
               
            }
        });
    })
</script>