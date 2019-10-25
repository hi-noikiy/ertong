<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            退款申请
        </h3>
    </div>
    <div class="panel-body">
        <div class="ibox-content">
            <form action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>" id="searchform" method="post">
                <div class="row" style="padding: 0 15px;">
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="订单号" name="out_trade_no" value="<?php  echo $out_trade_no;?>">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="用户id" name="openid" value="<?php  echo $openid;?>">
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
                        <th>订单号</th>
                        <th>用户id</th>
                        <th>余额支付</th>
                        <th>微信支付</th>
                        <th>退款说明</th>
                        <th>状态</th>
                        <th style="text-align:right;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
                    <tr>
                        <td><div class="type-parent"><?php  echo $item['out_trade_no'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['openid'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['canpay'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['wxpay'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['refund_content'];?></div></td>
                        <td><div class="type-parent" style="width: 52px;height: 32px;">
                            <?php  if($item['refund_status']==-1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">待处理</a>
                            <?php  } else if($item['refund_status']==1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">已退款</a>
                            <?php  } ?>
                        </div></td>
                        <td style="text-align:right;">
                            <?php  if($item['refund_status']==-1) { ?>
                            <a class="btn btn-primary btn-xs edit" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>退款</a>
                            <a class="btn btn-danger btn-xs delete" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>拒绝</a>
                            <?php  } ?>
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
<script>
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            //改变状态
            $(".edit").click(function(){
                var id=$(this).attr("data-id");
                var that=$(this);
                swal({
                    title: '确定退款吗?',
                    text: "确定退款吗?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type:"post",
                            url:"<?php  echo url('site/entry/OrderRefund',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>",
                            data:{id:id},
                            dataType:'json',
                            success:function(res){
                                console.log(res);
                                if(res.status==1){
                                    swal('操作成功!','', 'success');
                                    that.parent().prev().find("a").html("已退款");
                                    that.parent().empty();
                                }else{
                                    swal('操作失败!',res.msg, 'error');
                                }
                            }
                        })
                    }
                })
            })
            //删除
            $(".delete").click(function(){
                var that=$(this);
                var id=$(this).attr('data-id');
                swal({
                    title: '确定拒绝吗?',
                    text: "确定拒绝吗?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '确定',
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
                                    swal('操作成功!','', 'success');
                                    that.parent().prev().find("a").html("已拒绝");
                                    that.parent().empty();
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
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>