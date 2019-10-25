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
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">课程订单</a></li>
            <li role="presentation" class="active"><a>商城订单</a></li>
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
                        <th>项目信息</th>
                        <th>订单信息</th>
                        <th>用户信息</th>
                        <th>状态</th>
                        <th style="text-align:right;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
                    <tr data-pei="<?php  echo $item['pei'];?>">
                        <td><div class="type-parent">
                            商品：<?php  echo $item['title'];?><br/>
                            数量：<?php  echo $item['total'];?><br/>
                            <?php  if($item['store']) { ?>
                            提货校区：<?php  echo $item['store_name'];?><br/>
                            <?php  } ?>
                        </div></td>
                        <td><div class="type-parent">
                            订单号：<?php  echo $item['out_trade_no'];?><br/>
                            微信订单号：<?php  echo $item['wx_out_trade_no'];?><br/>
                            应付款：<?php  if($item['amount']) { ?><?php  echo $item['amount'];?>元<?php  } else { ?>免费<?php  } ?><br/>
                            <?php  if($item['o_amount']) { ?>实付款：<?php  echo $item['o_amount'];?>元<br/><?php  } ?>
                            <?php  if($item['coupon_price']) { ?>优惠：<?php  echo $item['coupon_price'];?>元<br/><?php  } ?>
                            配送方式：<?php  if($item['pei_type']==1) { ?>商家配送<?php  } else if($item['pei_type']==2) { ?>自提<?php  } ?><br/>
                            <?php  if($item['fee']) { ?>运费：<?php  echo $item['fee'];?>元<br/><?php  } ?>
                            添加时间：<?php  echo $item['createtime'];?><br/>
                        </div></td>
                        <td><div class="type-parent">
                            用户id：<?php  echo $item['openid'];?><br/>
                            姓名：<?php  echo $item['userinfo']['name'];?><br/>
                            手机号：<?php  echo $item['userinfo']['mobile'];?><br/>
                            <?php  if($item['userinfo']['address']) { ?>
                            地址:<?php  echo $item['userinfo']['address'];?> <?php  if($item['userinfo']['content']) { ?><?php  echo $item['userinfo']['content'];?><?php  } ?><br/>
                            <?php  } ?>
                            <?php  if($item['content']) { ?>
                            备注：<?php  echo $item['content'];?><br/>
                            <?php  } ?>
                            <?php  if($item['status']==2 && $item['tui_content']) { ?>
                            退款原因：<?php  echo $item['tui_content'];?>
                            <?php  } ?>
                        </div></td>
                        <td><div class="type-parent" style="width: 100px;height: 32px;">
                            <?php  if($item['status']==1) { ?>
                            <?php  if($item['mall_type']==2) { ?>
                            <?php  if($item['group_status']==-1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">拼团中（<?php  echo $item['group_list']['is_member'];?>/<?php  echo $item['group_list']['member'];?>）</a>
                            <?php  } else if($item['group_status']==1) { ?>
                            <?php  if($item['order_status']==-1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">未核销</a>
                            <?php  } else { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">已核销</a>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  } else { ?>
                            <?php  if($item['order_status']==-1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">未核销</a>
                            <?php  } else { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">已核销</a>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  } else if($item['status']==2) { ?>
                            <?php  if($item['tui_status']==-1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">退款中</a>
                            <?php  } else if($item['status']==1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">已退款</a>
                            <?php  } ?>
                            <?php  } ?>
                        </div></td>
                        <td style="text-align:right;">
                            <?php  if($item['status']==1) { ?>
                            <?php  if($item['mall_type']==2) { ?>
                            <?php  if($item['group_status']==1) { ?>
                            <?php  if($item['order_status']==-1) { ?>
                            <a class="btn btn-primary btn-xs edit" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>核销</a>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  } else { ?>
                            <?php  if($item['order_status']==-1) { ?>
                            <a class="btn btn-primary btn-xs edit" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>核销</a>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  } else if($item['status']==2) { ?>
                            <a class="btn btn-primary btn-xs tui" data-id="<?php  echo $item['id'];?>"><i class="fa fa-edit"></i>退款</a>
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
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'orderchange','version_id'=>$_GPC['version_id']));?>",
                    data:{id:id},
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            $(that).parent().prev().find("a").html('已核销');
                            $(that).remove();
                            swal('操作成功!', '操作成功!', 'success');
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
            $("body").on('click','.tui',function(){
                var that=this;
                objc=this;
                var id=$(this).attr("data-id");
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'tui','version_id'=>$_GPC['version_id']));?>",
                    data:{id:id},
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            $(that).parent().prev().find("a").html('已退款');
                            $(that).remove();
                            swal('操作成功!', '操作成功!', 'success');
                        }else{
                            swal('操作失败!',res.msg, 'error');
                        }
                    }
                })
            });
        })
    })
</script>