<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/css/style.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/bootstrap-select/bootstrap-select.min.css" />
<link rel="stylesheet" type="text/css" href="../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.css" />
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            开课>编辑
        </h3>
    </div>
    <div class="panel-body">
        <form id="sign-form" class="form-horizontal" role="form" method="post" action="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>" name="submit" style="padding: 20px 0;">
            <div class="form-group">
                <label class="col-sm-2 control-label">课程</label>
                <div class="col-sm-8">
                    <select id="pid" class="selectpicker show-tick form-control bs-select-hidden" data-live-search="true" name="pid" style="width: 50%;">
                        <option value="0" hassubinfo="true">请选择课程</option>
                        <?php  if(is_array($class)) { foreach($class as $index => $vo) { ?>
                        <option data-fee="<?php  echo $vo['fee'];?>" value="<?php  echo $vo['id'];?>" <?php  if($vo['id']==$list['pid']) { ?>selected<?php  } ?>><?php  echo $vo['name'];?></option>
                        <?php  } } ?>
                    </select>
                    <input type="hidden" name="id" value="<?php  echo $list['id'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">标识</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="mark" value="<?php  echo $list['mark'];?>">
                    <span class="help-block">免费的课程可选择公开课或新开课，收费课程只能选新开课</span>
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">类型</label>
                <div class="col-sm-8">
                    <label class="radio inline">
                        <input type="radio" class="ui-radio" name="type" id="type1" value="1" <?php  if($list['type']==1) { ?>checked<?php  } ?>>公开课
                    </label>
                    <label class="radio inline">
                        <input type="radio" class="ui-radio" name="type" id="type2" value="2" <?php  if($list['type']==2) { ?>checked<?php  } ?>>新开课
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">开课时间</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_date('start_time',$list['start_time'],true);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">报名/预约截止时间</label>
                <div class="col-sm-8">
                    <?php  echo tpl_form_field_date('end_time',$list['end_time'],true);?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">开课最少人数</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="least_member" value="<?php  echo $list['least_member'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">开课最多人数</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control"  name="more_member" value="<?php  echo $list['more_member'];?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">状态</label>
                <div class="col-sm-8">
                    <?php  if($list['status']==1) { ?>
                    <input type="checkbox" checked class="js-switch" value="1" name="status">
                    <?php  } else { ?>
                    <input type="checkbox" class="js-switch" name="status" value="1">
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" name="submit" class="btn btn-default" value="提交">
                    <a class="btn btn-default" href="<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m']));?>">返回</a>
                    <input id="res" name="res" type="reset" style="display:none;" />
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
    function dd(){
        var index=$('#pid option:selected').attr("data-fee");
        console.log(index);
        if(index==1){
            $("#type2").prop("checked",true);
        }
    }
    require(["../addons/<?php  echo $_GPC['m']?>/resource/swal/dist/sweetalert2.min.js"],function(){
        $(function(){
            $("#pid").on('change',function(){
                dd();
            });
            $("input[name='type']").change(function(){
                dd();
            });
            $("input[name='submit']").click(function(){
                var data=$(".form-horizontal").serialize();
                $.ajax({
                    type:"post",
                    url:"<?php  echo url('site/entry/'.$_GPC['do'],array('m'=>$_GPC['m'],'op'=>'savemodel','version_id'=>$_GPC['version_id']));?>",
                    data:data,
                    dataType:'json',
                    success:function(res){
                        if(res.status==1){
                            if($("input[name='id']").val()==""){
                                $("input[name='res']").click();
                                $("body").find(".img-responsive.img-thumbnail").attr("src","");
                            }
                            swal('操作成功!', '操作成功!', 'success');
                        }else{
                            swal('操作失败!', '操作失败!', 'error');
                        }
                    }
                })
            });
        })
    })
</script>