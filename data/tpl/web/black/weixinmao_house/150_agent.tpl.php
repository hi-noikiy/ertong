<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('agent',array('op' =>'display'))?>">经纪人列表</a></li>
	<!--
	<li<?php  if(empty($area['id']) && $operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('agent',array('op' =>'post'))?>">添加经纪人</a></li>

	-->
	<?php  if(!empty($agent['id']) &&  $operation == 'post') { ?><li  class="active"><a href="<?php  echo $this->createWebUrl('agent',array('op' =>'post','id'=>$agent['id']))?>">编辑经纪人</a></li><?php  } ?>
</ul>

<?php  if($operation == 'display') { ?>
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>显示顺序</th>	
						<th>城市</th>					
					<th>姓名</th>
					<th>电话</th>
					<th>QQ</th>
					<th>二手房总数</th>
					<th>出租房总数</th>
					<th> 今日二手房</th>
					<th>今日出租房</th>
					<th>创建时间</th>
					<th>状态</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['sort'];?></td>
					<td><?php  echo $vo['cityname'];?></td>
					<td><?php  echo $vo['name'];?></td>
					<td><?php  echo $vo['tel'];?></td>
					<td><?php  echo $vo['qq'];?></td>
					<td><?php  echo $vo['oldhousecount'];?></td>
					<td><?php  echo $vo['lethousecount'];?></td>
					<td><?php  echo $vo['todayoldhousecount'];?></td>
					<td><?php  echo $vo['todaylethousecount'];?></td>
					<td><?php  echo date('Y-m-d',$vo['createtime']);?></td>
					<td> <?php  if($vo['enabled'] == 1) { ?> <span style="color:blue;">审核通过</span><?php  } else { ?> <span style="color:red;">待审核</span><?php  } ?></td>
					<td style="text-align:left;">
						<a href="<?php  echo $this->createWebUrl('agent', array('op' => 'post', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('agent', array('op' => 'delete', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
</div>
<script>
	require(['bootstrap'],function($){
		$('.btn').hover(function(){
			$(this).tooltip('show');
		},function(){
			$(this).tooltip('hide');
		});
	});
</script>
<?php  } else if($operation == 'post') { ?>

<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
		<input type="hidden" name="id" value="<?php  echo $agent['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				经纪人设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="sort" class="form-control" value="<?php  echo $agent['sort'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>姓名</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $agent['name'];?>" />
					</div>
				</div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>照片</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image('thumb', $agent['thumb'])?>
                    </div>
                </div>
                 <div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">简介</label>
	<div class="col-sm-9 col-xs-12">
		<textarea cols="70" rows="8" name="intro" id="intro"><?php  echo $agent['intro'];?></textarea>

	</div>
</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>电话</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='tel' name="tel" class="form-control" value="<?php  echo $agent['tel'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>qq</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='qq' name="qq" class="form-control" value="<?php  echo $agent['qq'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>中介地址</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='address' name="address" class="form-control" value="<?php  echo $agent['address'];?>" />
					</div>
				</div>
			<!--
			    <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>详情</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_ueditor('content', $agent['content'])?>
                    </div>
                </div>
               -->
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否审核通过</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=1' <?php  if($agent['enabled']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=0' <?php  if($agent['enabled']==0) { ?>checked<?php  } ?> /> 否
						</label>
					</div>
				</div>
			</div>
		</div>
<div class="form-group col-sm-12">
	<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
	</div>
	</form>
</div>

<script language='javascript'>
	function formcheck() {
	
		if ($("#name").val()=='') {
			util.message("请输入姓名");
			return false;
		}
		if ($("#intro").val()=='') {
			util.message("请输入简介");
			return false;
		}
		if ($("#tel").val()=='') {
		    util.message("请输入手机号");
			return false;
		}
		if ($("#qq").val()=='') {
			util.message("请输入QQ");
			return false;
		}
		if ($("#address").val()=='') {
			util.message("请输入中介地址");
			return false;
		}
		if ($("textarea[name='content']").val()=='') {
		//util.message("请输入详情");
		//	return false;
		}
		return true;
	}
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>