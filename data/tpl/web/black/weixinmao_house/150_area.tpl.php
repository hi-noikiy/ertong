<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('area',array('op' =>'display'))?>">区域列表</a></li>
	<li<?php  if(empty($area['id']) && $operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('area',array('op' =>'post'))?>">添加区域</a></li>
	<?php  if(!empty($area['id']) &&  $operation == 'post') { ?><li  class="active"><a href="<?php  echo $this->createWebUrl('area',array('op' =>'post','id'=>$adv['id']))?>">编辑区域</a></li><?php  } ?>

	<?php  if($operation == 'buildarea' || $operation == 'addbuildarea') { ?><li  class="active"><a href="javascript:void(0);">管理片区</a></li><?php  } ?>
</ul>

<?php  if($operation == 'display') { ?>
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>显示顺序</th>					
					<th>区域名称</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['sort'];?></td>
					<td><?php  echo $vo['name'];?></td>
					<td style="text-align:left;">

					<a href="<?php  echo $this->createWebUrl('area', array('op' => 'buildarea', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="管理片区">管理片区</a>
						<a href="<?php  echo $this->createWebUrl('area', array('op' => 'post', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('area', array('op' => 'delete', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
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

<?php  } else if($operation == 'buildarea') { ?>

<div class="main panel panel-default">
	<div class="panel-body table-responsive">
								<a href="<?php  echo $this->createWebUrl('area', array('op' => 'addbuildarea', 'pid' => $pid))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="添加片区">添加片区</a>

		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>显示顺序</th>					
					<th>片区名称</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['sort'];?></td>
					<td><?php  echo $vo['name'];?></td>
					<td style="text-align:left;">

						

						<a href="<?php  echo $this->createWebUrl('area', array('op' => 'addbuildarea', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('area', array('op' => 'deletebuildarea', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
</div>


<?php  } else if($operation == 'addbuildarea') { ?>

<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
		<input type="hidden" name="pid" value="<?php  echo $pid;?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				片区设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="sort" class="form-control" value="<?php  echo $houseprice['sort'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>片区名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $houseprice['name'];?>" />
					</div>
				</div>

			
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=1' <?php  if($houseprice['enabled']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=0' <?php  if($houseprice['enabled']==0) { ?>checked<?php  } ?> /> 否
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
		if ($("#name").isEmpty()) {
			Tip.focus("name", "请填写小区名称!");
			return false;
		}
		/*
		if($("#beginprice").val() == $("#endprice").val())
			{
			   Tip.focus("beginprice", "区间值不能相同");
				return false;
			}

		*/
		return true;
	}
</script>




<?php  } else if($operation == 'post') { ?>

<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
		<input type="hidden" name="id" value="<?php  echo $area['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				区域设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="sort" class="form-control" value="<?php  echo $area['sort'];?>" />
					</div>
				</div>


				<div class="form-group">
	
		<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>所属城市</label>
	<div class="col-sm-8 col-xs-12">
		<select class="form-control tpl-category-parent we7-select" id="cityid" name="cityid">
				<option value="0">请选择所属城市</option>
					<?php  if(is_array($citylist)) { foreach($citylist as $vo) { ?>
				<option value="<?php  echo $vo['id'];?>" <?php  if($area['cityid'] == $vo['id']) { ?> selected <?php  } ?>><?php  echo $vo['name'];?></option>
				<?php  } } ?>
				
				
			</select>
	</div>
</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>区域名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $area['name'];?>" />
					</div>
				</div>
			
			
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=1' <?php  if($area['enabled']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=0' <?php  if($area['enabled']==0) { ?>checked<?php  } ?> /> 否
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
		if ($("#name").isEmpty()) {
			Tip.focus("name", "请填写区域名称!");
			return false;
		}
		return true;
	}
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>