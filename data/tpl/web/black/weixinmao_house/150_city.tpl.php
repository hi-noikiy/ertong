<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('city',array('op' =>'display'))?>">城市列表</a></li>
	<li<?php  if(empty($area['id']) && $operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('city',array('op' =>'post'))?>">添加城市</a></li>
	<?php  if(!empty($area['id']) &&  $operation == 'post') { ?><li  class="active"><a href="<?php  echo $this->createWebUrl('city',array('op' =>'post','id'=>$adv['id']))?>">编辑城市</a></li><?php  } ?>
</ul>

<?php  if($operation == 'display') { ?>

<a href="<?php  echo $this->createWebUrl('city', array('op' => 'donedate'))?>" onclick="return confirm('此操作不可恢复，确认同步？');return false;"  style="color:red;" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="同步旧数据">同步旧数据</a>（将所有房源数据绑定到默认城市上，请谨慎操作）
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>显示顺序</th>					
					<th>城市名称</th>
					<th>是否默认</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['sort'];?></td>
					<td><?php  echo $vo['name'];?></td>
					<td><?php  if($vo['ison']==1) { ?>
								<span class='label label-success'>是</span>
								<?php  } else { ?>
								<span class='label label-danger'>否</span>
								<?php  } ?></td>
					<td style="text-align:left;">
						<a href="<?php  echo $this->createWebUrl('city', array('op' => 'post', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('city', array('op' => 'delete', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
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
		<input type="hidden" name="id" value="<?php  echo $area['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				区域设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="displayorder" class="form-control" value="<?php  echo $area['sort'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>城市名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $area['name'];?>" />
					</div>
				</div>
			    <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>大写首字母</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='firstname' name="firstname" class="form-control" value="<?php  echo $area['firstname'];?>" />
					</div>
				</div>
			
			    <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否默认</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='ison' value=1' <?php  if($area['ison']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='ison' value=0' <?php  if($area['ison']==0) { ?>checked<?php  } ?> /> 否
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否热门</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='ishot' value=1' <?php  if($area['ishot']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='ishot' value=0' <?php  if($area['ishot']==0) { ?>checked<?php  } ?> /> 否
						</label>
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
			Tip.focus("name", "请填写城市名称!");
			return false;
		}
		
		if ($("#firstname").isEmpty()) {
			Tip.focus("firstname", "请填写大写首字母!");
			return false;
		}
		
		return true;
	}
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>