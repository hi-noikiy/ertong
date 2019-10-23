<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            订单
        </h3>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php  echo url('site/entry/Active',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">优惠活动</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/Prize2',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">奖品</a></li>
            <li role="presentation" class="active"><a>获奖记录</a></li>
        </ul>
        <div class="ibox-content">
            <form action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>" id="searchform" method="get">
                <input type="hidden" class="form-control" name="c" value="site">
                <input type="hidden" class="form-control" name="a" value="entry">
                <input type="hidden" class="form-control" name="do" value="<?php  echo $_GPC['do'];?>">
                <input type="hidden" class="form-control" name="m" value="<?php  echo $_GPC['m'];?>">
                <input type="hidden" class="form-control" name="version_id" value="<?php  echo $_GPC['version_id'];?>">
                <div class="row" style="padding: 0 15px;">
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="用户id" name="openid" value="<?php  echo $openid;?>">
                    </div>
                    <div class="col-sm-3">
                        <select data-placeholder="请选择状态" class="chosen-select" name="use" id="use">
                            <option value="0" hassubinfo="true">请选择状态</option>
                            <option value="-1" hassubinfo="true" <?php  if($use==-1) { ?>selected<?php  } ?>>未使用</option>
                            <option value="1" hassubinfo="true" <?php  if($use==1) { ?>selected<?php  } ?>>已使用</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-default " style="margin-right:5px;">查询</button>
                    </div>
                </div>
            </form>
        </div>
        <form action="" method="post" class="form-horizontal form">
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
            <div class="table-responsive panel-body">
                <table class="table-striped table-bordered table-hover dataTables-example table">
                    <thead class="navbar-inner">
                    <tr>
                        <th>用户id</th>
                        <th>活动</th>
                        <th>奖品</th>
                        <th>状态</th>
                        <th>获奖时间</th>
                        <th>兑换时间</th>
                        <th style="text-align:right;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
                    <tr data-pei="<?php  echo $item['pei'];?>">
                        <td><div class="type-parent"><?php  echo $item['openid'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['title'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['prize'];?></div></td>
                        <td><div class="type-parent" style="width: 52px;height: 32px;">
                            <?php  if($item['use']==1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">已使用</a>
                            <?php  } else { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">未使用</a>
                            <?php  } ?>
                        </div></td>
                        <td><div class="type-parent"><?php  echo $item['prizetime'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['usetime'];?></div></td>
                        <td style="text-align:right;">
                            <?php  if($item['use']==-1) { ?>
                            <a class="btn btn-primary btn-xs edit" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>使用</a>
                            <?php  } ?>
                        </td>
                    </tr>
                    <?php  } } ?>
                    </tbody>
                </table>
                <div style="text-align: right;">
                    <?php  echo $pager;?>
                </div>
            </div>
        </form>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        var objc="";
        $(function(){
            //订单状态
            $("body").on("click",'.edit',function(){
                var that=this;
                objc=this;
                var id=$(this).attr("data-id");
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'statuschange','version_id'=>$_GPC['version_id']));?>",
                    data:{id:id},
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            $(that).parent().prev().find("a").html('已使用');
                            swal('操作成功!', '操作成功!', 'success');
                            $(that).remove();
                        }else{
                            swal('操作失败!', '操作失败!', 'error');
                        }
                    }
                })
            });
            //删除
            $(".delete").click(function(){
                var that=$(this);
                var id=$(this).attr('data-id');
                swal({
                    title: '确定删除吗?',
                    text: "确定删除吗?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '删除',
                    cancelButtonText: '取消',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'delete','version_id'=>$_GPC['version_id']));?>",
                            data:{id:id},
                            dataType:'json',
                            success:function(res){
                                if(res.status==1){
                                    swal('操作成功!', '操作成功!', 'success');
                                    that.parent().parent().remove();
                                }else{
                                    swal('操作失败!', '操作失败!', 'error');
                                }
                            }
                        })
                    }
                })
            });
        })
    })
</script>