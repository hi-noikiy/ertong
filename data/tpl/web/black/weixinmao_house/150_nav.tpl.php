<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<ul class="nav nav-tabs">
	<li <?php  if($operation == 'display') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('nav',array('op' =>'display'))?>">导航管理 </a></li>
	<li<?php  if(empty($adv['id']) && $operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('nav',array('op' =>'post'))?>">添加导航</a></li>
	<?php  if(!empty($adv['id']) &&  $operation == 'post') { ?><li  class="active"><a href="<?php  echo $this->createWebUrl('nav',array('op' =>'post','id'=>$adv['id']))?>">编辑导航</a></li><?php  } ?>
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
					<th>连接</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $adv) { ?>
				<tr>
					<td><?php  echo $adv['id'];?></td>
					<td><?php  echo $adv['displayorder'];?></td>
					<td><?php  echo $adv['advname'];?></td>
					<td><?php  echo $adv['link'];?></td>
					<td style="text-align:left;">
						<a href="<?php  echo $this->createWebUrl('nav', array('op' => 'post', 'id' => $adv['id']))?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="修改"><i class="fa fa-edit"></i></a>
						<a href="<?php  echo $this->createWebUrl('nav', array('op' => 'delete', 'id' => $adv['id']))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
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
		<input type="hidden" name="id" value="<?php  echo $adv['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				导航设置
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="displayorder" class="form-control" value="<?php  echo $adv['displayorder'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>导航标题</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='advname' name="advname" class="form-control" value="<?php  echo $adv['advname'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">导航图片</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_form_field_image('thumb', $adv['thumb'])?>
					</div>
				</div>
				 <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">导航连接</label>
					<div class="col-sm-9 col-xs-12">
		


			<select class="form-control tpl-category-parent we7-select" id="link" name="link">
				<option value="">请选择类型 </option>
				<option value="toNewHouse" <?php  if($adv['link'] == 'toNewHouse' ) { ?> selected<?php  } ?>>新房模型</option>
				<option value="toOldHouse" <?php  if($adv['link'] == 'toOldHouse') { ?> selected<?php  } ?>>二手房模型</option>
				<option value="toLethouse" <?php  if($adv['link'] == 'toLethouse') { ?> selected<?php  } ?>>房屋出租模型</option>
				<option value="toArticle" <?php  if($adv['link'] == 'toArticle') { ?> selected<?php  } ?>>房屋买卖模型</option>
				<option value="toMessage" <?php  if($adv['link'] == 'toMessage') { ?> selected<?php  } ?>>申请入驻模型</option>
				<option value="toAgentlist" <?php  if($adv['link'] == 'toAgentlist') { ?> selected<?php  } ?>>经纪人模型</option>
				<option value="toActive" <?php  if($adv['link'] == 'toActive') { ?> selected<?php  } ?>>购房活动模型</option>
			<option value="goPub" <?php  if($adv['link'] == 'goPub') { ?> selected<?php  } ?>>发布房源模型</option>
				<option value="toStorelist" <?php  if($adv['link'] == 'toStorelist') { ?> selected<?php  } ?>>门店模型</option>
				<option value="toHousemoney" <?php  if($adv['link'] == 'toHousemoney') { ?> selected<?php  } ?>>房货器模型</option>
               	<option value="toBusinesshouselist" <?php  if($adv['link'] == 'toBusinesshouselist') { ?> selected<?php  } ?>>商业地产模型</option>
               	<option value="toOldSaleHouse" <?php  if($adv['link'] == 'toOldSaleHouse') { ?> selected<?php  } ?>>急售房源模型</option>
               	<option value="toOldPayHouse" <?php  if($adv['link'] == 'toOldPayHouse') { ?> selected<?php  } ?>>拍卖房源模型</option>
               	<option value="toLetBusinessHouse" <?php  if($adv['link'] == 'toLetBusinessHouse') { ?> selected<?php  } ?>>商业出租模型</option>

               	<option value="toInnerUrl" <?php  if($adv['link'] == 'toInnerUrl') { ?> selected<?php  } ?>>内连接模型</option>
				<option value="toMenuUrl" <?php  if($adv['link'] == 'toMenuUrl') { ?> selected<?php  } ?>>菜单连接模型</option>
		<option value="toWxapp" <?php  if($adv['link'] == 'toWxapp') { ?> selected<?php  } ?>>小程序跳转模型</option>


			</select>
					</div>
				</div>


				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red"></span>连接</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='innerurl' name="innerurl" class="form-control" value="<?php  echo $adv['innerurl'];?>" />
											<span style="font-size:12px;color:red;">仅内连接、跳转小程序、菜单连接模型用到</span>

					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red"></span>appid</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='appid' name="appid" class="form-control" value="<?php  echo $adv['appid'];?>" />
											<span style="font-size:12px;color:red;">跳转小程序appid</span>

					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red"></span>关联ID</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='cateid' name="cateid" class="form-control" value="<?php  echo $adv['cateid'];?>"  style="width:100px;"/>
											<span style="font-size:12px;color:red;">目前仅门店模型用到</span>

					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
					<div class="col-sm-9 col-xs-12">
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=1' <?php  if($adv['enabled']==1) { ?>checked<?php  } ?> /> 是
						</label>
						<label class='radio-inline'>
							<input type='radio' name='enabled' value=0' <?php  if($adv['enabled']==0) { ?>checked<?php  } ?> /> 否
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
		if ($("#advname").isEmpty()) {
			Tip.focus("advname", "请填写幻灯片名称!");
			return false;
		}
		return true;
	}
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>