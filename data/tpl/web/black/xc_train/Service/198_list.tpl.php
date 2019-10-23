<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/bootstrap-select/bootstrap-select.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            服务项目
        </h3>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php  echo url('site/entry/Service_class',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">分类</a></li>
            <li role="presentation" class="active"><a>列表</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/Teacher',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">名师</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/Service_team',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">开课</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/School',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">分校管理</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/Cut',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">砍价</a></li>
            <li role="presentation"><a href="<?php  echo url('site/entry/Mall',array('m'=>$_GPC['m'],'version_id'=>$_GPC['version_id']));?>">商城</a></li>
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
                        <input type="text" class="form-control" placeholder="名称" name="xname" value="<?php  echo $xname;?>">
                    </div>
                    <div class="col-sm-3">
                        <select class="selectpicker show-tick form-control bs-select-hidden" data-live-search="true" name="cid" style="width: 50%;">
                            <option value="0" hassubinfo="true">请选择分类</option>
                            <?php  if(is_array($class)) { foreach($class as $index => $vo) { ?>
                            <option value="<?php  echo $vo['id'];?>" <?php  if($vo['id']==$cid) { ?>selected<?php  } ?>><?php  echo $vo['name'];?></option>
                            <?php  } } ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-default " style="margin-right:5px;">查询</button>
                        <a class="btn btn-primary" href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'edit','version_id'=>$_GPC['version_id']));?>">增加</a>
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
                        <th>名称</th>
                        <th>分类</th>
                        <th>封面</th>
                        <th>价格</th>
                        <th>评论数</th>
                        <th>点赞数</th>
                        <th>浏览量</th>
                        <th>排序</th>
                        <th>状态</th>
                        <th style="text-align:right;">操作</th>
                    </tr>
                    </thead>
                    <tbody id="level-list">
                    <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
                    <tr>
                        <td><div class="type-parent"><?php  echo $item['name'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['cidname'];?></div></td>
                        <td><div class="type-parent"><img src="<?php  echo tomedia($item['bimg']);?>" width="75" height="32"/></div></td>
                        <td><div class="type-parent"><?php  if($item['price']) { ?><?php  echo $item['price'];?><?php  } else { ?>免费<?php  } ?></div></td>
                        <td><div class="type-parent"><?php  echo $item['discuss'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['zan'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['click'];?></div></td>
                        <td><div class="type-parent"><?php  echo $item['sort'];?></div></td>
                        <td><div class="type-parent" style="width: 52px;height: 32px;">
                            <?php  if($item['status']==1) { ?>
                            <input type="checkbox" checked class="js-switch" value="1" name="status" data-id="<?php  echo $item['id'];?>" data-name="status">
                            <?php  } else { ?>
                            <input type="checkbox" class="js-switch" name="status" value="1" data-id="<?php  echo $item['id'];?>" data-name="status">
                            <?php  } ?>
                        </div></td>
                        <td style="text-align:right;">
                            <a href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'edit','id'=>$item['id']));?>" class="btn btn-primary btn-xs edit"><i class="fa fa-edit"></i>修改</a>
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
    require(["../addons/<?php  echo $_GPC['m']?>/resource/bootstrap-select/bootstrap-select.min.js"],function(){

    })
</script>
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
                    status=0;
                }
                var id=$(this).attr("data-id");
                var name=$(this).attr("data-name");
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'statuschange','version_id'=>$_GPC['version_id']));?>",
                    data:{id:id,status:status,name:name},
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