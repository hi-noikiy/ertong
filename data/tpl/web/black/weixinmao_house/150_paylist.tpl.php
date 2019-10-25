<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common', TEMPLATE_INCLUDEPATH)) : (include template('common', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('paylist',array('op' =>'display'))?>">支付标签列表</a></li>
	<li<?php  if(empty($toplist['id']) && $operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('paylist',array('op' =>'post'))?>">添加支付标签</a></li>
	<?php  if(!empty($toplist['id']) &&  $operation == 'post') { ?><li  class="active"><a href="<?php  echo $this->createWebUrl('paylist',array('op' =>'post','id'=>$adv['id']))?>">编辑支付标签</a></li><?php  } ?>
</ul>

<?php  if($operation == 'display') { ?>
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>显示顺序</th>	
					 <th>标题</th>				
					<th>金额</th>
					<th>次数</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['sort'];?></td>
					<td><?php  echo $vo['title'];?></td>
					<td><?php  echo $vo['money'];?></td>
					<td><?php  echo $vo['days'];?></td>
					<td style="text-align:left;">
						<a href="<?php  echo $this->createWebUrl('paylist', array('op' => 'post', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('paylist', array('op' => 'delete', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
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
		<input type="hidden" name="id" value="<?php  echo $toplist['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				房屋出租租金设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="sort" class="form-control" value="<?php  echo $toplist['sort'];?>" />
					</div>
				</div>

					<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>标题</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='title' name="title" class="form-control" value="<?php  echo $toplist['title'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>金额</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='money' name="money" class="form-control" value="<?php  echo $toplist['money'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>次数</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='days' name="days" class="form-control" value="<?php  echo $toplist['days'];?>" />
					</div>
				</div>
			
			
			
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=1' <?php  if($toplist['enabled']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=0' <?php  if($toplist['enabled']==0) { ?>checked<?php  } ?> /> 否
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
		if ($("#title").isEmpty()) {
			Tip.focus("title", "请填写标题!");
			return false;
		}
		if ($("#money").isEmpty()) {
			Tip.focus("money", "请填写金额!");
			return false;
		}
		if($("#days").isEmpty() )
			{
			   Tip.focus("days", "请填写次数");
				return false;
			}
		return true;
	}
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>