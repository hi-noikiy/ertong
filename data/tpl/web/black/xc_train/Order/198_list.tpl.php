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
            <li role="presentation" class="active"><a>课程订单</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'mall','version_id'=>$_GPC['version_id']));?>">商城订单</a></li>
        </ul>
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
                        <input type="text" class="form-control" placeholder="手机号" name="mobile" value="<?php  echo $mobile;?>">
                    </div>
                    <div class="col-sm-3">
                        <select data-placeholder="请选择状态" class="chosen-select" name="use" id="use">
                            <option value="0" hassubinfo="true">请选择状态</option>
                            <option value="-1" hassubinfo="true" <?php  if($use==-1) { ?>selected<?php  } ?>>未使用</option>
                            <option value="1" hassubinfo="true" <?php  if($use==1) { ?>selected<?php  } ?>>已使用</option>
                        </select>
                    </div>
                    <div class="col-sm-3" style="margin-top:5px;">
                        <button type="submit" class="btn btn-default " style="margin-right:5px;">查询</button>
                        <a class="btn btn-primary" data-toggle="modal" data-target="#selectUrl">导出Excel</a>
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
                            <?php  if($item['store']) { ?>
                            校区：<?php  echo $item['store_name'];?><br/>
                            <?php  } ?>
                            服务项目：<?php  echo $item['title'];?><br/>
                            人数：<?php  echo $item['total'];?><br/>
                        </div></td>
                        <td><div class="type-parent">
                            订单号：<?php  echo $item['out_trade_no'];?><br/>
                            微信订单号：<?php  echo $item['wx_out_trade_no'];?><br/>
                            应付款：<?php  if($item['amount']) { ?><?php  echo $item['amount'];?>元<?php  } else { ?>免费<?php  } ?><br/>
                            <?php  if($item['o_amount']) { ?>实付款：<?php  echo $item['o_amount'];?>元<br/><?php  } ?>
                            <?php  if($item['coupon_price']) { ?>优惠：<?php  echo $item['coupon_price'];?>元<br/><?php  } ?>
                            添加时间：<?php  echo $item['createtime'];?><br/>
                        </div></td>
                        <td><div class="type-parent">
                            用户id：<?php  echo $item['openid'];?><br/>
                            姓名：<?php  echo $item['name'];?><br/>
                            手机号：<?php  echo $item['mobile'];?><br/>
                            <?php  if($item['content']) { ?>
                            备注：<?php  echo $item['content'];?><br/>
                            <?php  } ?>
                            <?php  if($item['tui']) { ?>
                            推荐人：<?php  echo $item['tui'];?><br/>
                            <?php  } ?>
                        </div></td>
                        <td><div class="type-parent" style="width: 100px;height: 32px;">
                            <?php  if($item['use']==1) { ?>
                            <a class="btn   btn-xs btn-rounded btn-success">已使用</a>
                            <?php  } else { ?>
                            <a class="btn   btn-xs btn-rounded btn-success" data-min="<?php  echo $item['is_use'];?>" data-max="<?php  echo $item['can_use'];?>">未使用(<span class="is_use"><?php  echo $item['is_use'];?></span>/<?php  echo $item['can_use'];?>)</a>
                            <?php  } ?>
                        </div></td>
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
<!--底部链接选择-->
<div class="modal fade" id="selectUrl">
    <div class="modal-dialog">
        <style>
            #selectUrl .modal-body {padding: 10px 15px;  }
            #selectUrl .tab-pane {  margin-top: 5px;  min-height: 400px;  max-height: 400px;  overflow-y: auto;  }
            #selectUrlTab {  margin-bottom: 10px;  }
            #selectUrl .modal-body nav {  margin-bottom: 5px; }
        </style>
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">选择导出字段</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="selectUrlTab">
                    <li role="presentation" class="active"><a href="#sut_shop">字段</a></li>
                </ul>
                <div class="tab-content ">
                    <div class="tab-pane active" id="sut_shop">
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="store" title="校区">校区</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="title" title="服务项目">服务项目</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="total" title="人数">人数</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="out_trade_no" title="订单号">订单号</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="wx_out_trade_no" title="微信订单号">微信订单号</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="amount" title="应付款">应付款</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="o_amount" title="实付款">实付款</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="openid" title="用户id">用户id</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="name" title="姓名">姓名</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="mobile" title="手机号">手机号</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="status" title="状态">状态</nav>
                        <nav class="btn btn-default btn-sm" data-status="-1" data-name="createtime" title="添加时间">添加时间</nav>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="select-btn" type="button">导出</button>
                <button data-dismiss="modal" class="btn btn-default" id="select-close" type="button">关闭</button>
            </div>
            <script>
                $(function(){
                    $("#selectUrl .modal-body nav").click(function(){
                        var status=$(this).attr("data-status");
                        if(status==1){
                            $(this).removeClass("btn-primary").addClass("btn-default");
                        }else if(status==-1){
                            $(this).removeClass("btn-default").addClass("btn-primary");
                        }
                        $(this).attr("data-status",-status);
                    });
                    $("#select-btn").click(function(){
                        var data='';
                        $("#selectUrl .modal-body").find("nav").each(function(){
                            var status=$(this).attr("data-status");
                            var name=$(this).attr("data-name");
                            data=data+'&'+name+'='+status;
                        });
                        var url="<?php  echo url('site/entry/Export',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>";
                        url=url+data;
                        location.href=url;
                        $("#select-close").click();
                    });
                })
            </script>
        </div>
    </div>
</div>
<!--底部链接选择 end-->
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
                            var is_use=$(that).parent().prev().find("a").attr("data-min");
                            var can_use=$(that).parent().prev().find("a").attr("data-max");
                            if(parseInt(is_use)+1==parseInt(can_use)){
                                $(that).parent().prev().find("a").html('已使用');
                                $(that).remove();
                            }else{
                                $(that).parent().prev().find("a").attr("data-min",parseInt(is_use)+1);
                                $(that).parent().prev().find(".is_use").html(parseInt(is_use)+1);
                            }
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
        })
    })
</script>