<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            评论
        </h3>
    </div>
    <div class="panel-body">
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
                    <div class="col-sm-2">
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
                        <th style="width: 10%;">用户id</th>
                        <th style="width: 10%;">课程</th>
                        <th style="width: 30%;">评论内容</th>
                        <th>状态</th>
                        <th style="width: 10%;">添加时间</th>
                        <th style="width: 10%;" style="text-align: right;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
                    <tr>
                        <td><div class="type-parent"><?php  echo $item['openid'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['pname'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['content'];?></div></td>
                        <td><div class="type-parent" style="width: 52px;height: 32px;">
                            <?php  if($item['status']==1) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="status" data-id="<?php  echo $item['id'];?>">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" name="status" value="1" data-id="<?php  echo $item['id'];?>">
                            <?php  } ?>
                        </div></td>
                        <td><div class="type-parent"><?php  echo $item['createtime'];?></div></td>
                        <td>
                            <a class="btn btn-danger btn-xs delete" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>删除</a>
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
        $(function(){
            //改变状态
            $(".js-switch").change(function(){
                var status;
                var that=$(this);
                if($(this).is(":checked")){
                    status=1;
                }else{
                    status=-1;
                }
                var id=$(this).attr("data-id");
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'statuschange','version_id'=>$_GPC['version_id']));?>",
                    data:{id:id,status:status},
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            swal('操作成功!', '操作成功!', 'success');
                        }else{
                            that.prop("checked",!that.is(":checked"));
                            swal('操作失败!', '操作失败!', 'error');
                        }
                    }
                })
            })
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