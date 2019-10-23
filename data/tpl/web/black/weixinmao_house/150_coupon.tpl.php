<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('coupon',array('op' =>'display'))?>">分销商列表</a></li>
	<li <?php  if($operation == 'fxmessage') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('coupon',array('op' =>'fxmessage'))?>">分销记录列表</a></li>
	<li <?php  if($operation == 'fxrecord') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('coupon',array('op' =>'fxrecord'))?>">分销收益列表</a></li>
	<!--
	<li<?php  if(empty($area['id']) && $operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('agent',array('op' =>'post'))?>">添加经纪人</a></li>

	-->
	<?php  if(!empty($agent['id']) &&  $operation == 'post') { ?><li  class="active"><a href="<?php  echo $this->createWebUrl('coupon',array('op' =>'post','id'=>$agent['id']))?>">编辑分销商</a></li><?php  } ?>
</ul>

<?php  if($operation == 'display') { ?>
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>姓名</th>
					<th>电话</th>
					<th>微信号</th>
					<th>创建时间</th>
					<th>状态</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['name'];?></td>
					<td><?php  echo $vo['tel'];?></td>
					<td><?php  echo $vo['weixin'];?></td>
					<td><?php  echo date('Y-m-d',$vo['createtime']);?></td>
					<td> <?php  if($vo['status'] == 1) { ?> <span style="color:blue;">审核通过</span><?php  } else { ?> <span style="color:red;">待审核</span><?php  } ?></td>
					<td style="text-align:left;">
						<a href="<?php  echo $this->createWebUrl('coupon', array('op' => 'post', 'id' => $vo['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('coupon', array('op' => 'delete', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
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

<?php  } else if($operation == 'fxmessage') { ?>
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>分享者</th>
					<th>分享房源</th>
					<th>客户姓名</th>
					<th>客户电话</th>
					<th>创建时间</th>
					<th>状态</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['couponname'];?></td>
					<td><?php  echo $vo['housetitle'];?></td>
					<td><?php  echo $vo['name'];?></td>
					<td><?php  echo $vo['tel'];?></td>
					<td><?php  echo date('Y-m-d',$vo['createtime']);?></td>
					<td> <?php  if($vo['status'] == 1) { ?> <span style="color:blue;">交易完成</span><?php  } else { ?> <span style="color:red;">未成交</span><?php  } ?></td>
					<td style="text-align:left;">
					<?php  if($vo['status'] == 0) { ?>
					         <a href="<?php  echo $this->createWebUrl('coupon', array('op' => 'donedeal', 'id' => $vo['id']))?>" onclick="return confirm('此操作不可恢复，确认成交？');return false;"  class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="成交交易">成交交易</a>
					    <?php  } ?>

						<a href="<?php  echo $this->createWebUrl('coupon', array('op' => 'delfxmessage', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
</div>


<?php  } else if($operation == 'fxrecord') { ?>
<div class="main panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:30px;">ID</th>
					<th>收益者</th>
					<th>收益者电话</th>
					<th>收益金额</th>
					<th>来源</th>
					<th>创建时间</th>
					<th>状态</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $vo) { ?>
				<tr>
					<td><?php  echo $vo['id'];?></td>
					<td><?php  echo $vo['couponname'];?></td>
					<td><?php  echo $vo['coupontel'];?></td>
					<td><?php  echo $vo['money'];?></td>
					<td><?php  echo $vo['content'];?></td>
					<td><?php  echo date('Y-m-d',$vo['createtime']);?></td>
					<td> <?php  if($vo['status'] == 1) { ?> <span style="color:blue;">已获得</span><?php  } else { ?> <span style="color:red;">未获得</span><?php  } ?></td>
					<td style="text-align:left;">
					

					

						<a href="<?php  echo $this->createWebUrl('coupon', array('op' => 'delfxrecord', 'id' => $vo['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
</div>
<?php  } else if($operation == 'post') { ?>

<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
		<input type="hidden" name="id" value="<?php  echo $agent['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				分销商设置
			</div>
			<div class="panel-body">
				
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>姓名</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $agent['name'];?>" />
					</div>
				</div>
				
               
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>电话</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='tel' name="tel" class="form-control" value="<?php  echo $agent['tel'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>微信号</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='weixin' name="weixin" class="form-control" value="<?php  echo $agent['weixin'];?>" />
					</div>
				</div>
				
			
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否审核通过</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='status' value=1' <?php  if($agent['status']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='status' value=0' <?php  if($agent['status']==0) { ?>checked<?php  } ?> /> 否
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