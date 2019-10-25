<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common', TEMPLATE_INCLUDEPATH)) : (include template('common', TEMPLATE_INCLUDEPATH));?>

<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>
<ul class="nav nav-tabs">
	
	<li <?php  if($operation == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('newhouse', array('op' => 'display'))?>">房屋买卖管理</a></li>
</ul>
<?php  if($operation == 'post') { ?>

<?php  } else if($operation == 'detail') { ?>
<div class="main">
	<form class="form-horizontal form" action="" method="post" enctype="multipart/form-data">
		
		<input type="hidden" name="dispatchid" value="<?php  echo $dispatch['id'];?>" />
		<div class="panel panel-default">
			<div class="panel-heading">
				详情信息
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">信息类型 :</label>
					<div class="col-sm-9 col-xs-12">
						<p class="form-control-static"><?php  echo $saleinfo['type'];?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名 :</label>
					<div class="col-sm-9 col-xs-12">
						<p class="form-control-static"><?php  echo $saleinfo['name'];?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">电话 :</label>
					<div class="col-sm-9 col-xs-12">
						<p class="form-control-static"><?php  echo $saleinfo['tel'];?></p>
					</div>
				</div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">内容  :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php  echo $saleinfo['content'];?></p>
                    </div>
                </div>
				
			
			
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">创建时间 :</label>
					<div class="col-sm-9 col-xs-12">
						<p class="form-control-static"><?php  echo date('Y-m-d H:i:s', $saleinfo['createtime'])?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">图片 :</label>
					<div class="col-sm-9 col-xs-12">
				<?php  if($piclist) { ?>
<?php  if(is_array($piclist)) { foreach($piclist as $vo) { ?>
<a href="<?php  echo $vo;?>" target="_blank"><img src="<?php  echo $vo;?>" style="width:150px; height: 150px;" /></a>
<?php  } } ?>
				<?php  } ?>
			
					</div>
				</div>

 <a href="javascript:void();" onclick="history.go(-1);"
						   class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="审核通过">返回信息页</a>
			</div>
		</div>

</form></div>
<?php  } else if($operation == 'display') { ?>

<div class="main">
	<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form action="./index.php" method="get" class="form-horizontal" role="form">
			<input type="hidden" name="c" value="site" />
			<input type="hidden" name="a" value="entry" />
			<input type="hidden" name="m" value="weixinmao_house" />
			<input type="hidden" name="do" value="salelist" />
			<input type="hidden" name="op" value="display" />
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">手机号</label>
				<div class="col-xs-8 col-sm-8 col-lg-9">
					<input class="form-control" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
					
				</div>
					<div class="col-xs-4 col-sm-2 col-lg-2">
					<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
				</div>
			</div>
		
		</form>
	</div>
</div>
<style>
.label{cursor:pointer;}
</style>
<div class="panel panel-default">
	<div class="panel-body table-responsive">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:5%;">ID</th>
					<th style="width:10%;">信息类型</th>
					<th style="width:10%;">姓名</th>
					<th style="width:10%;">电话</th>
					<th style="width:20%;">内容</th>
					<th style="width:10%;">浏览量</th>
					<th style="width:15%;">创建时间</th>
					<th style="width:10%;">状态</th>
                    <th style="width:10%;">支付状态 </th>
					<th style="text-align:right; width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td><?php  echo $item['id'];?></td>
					<td><?php  echo $item['type'];?></td>
					<td><?php  echo $item['name'];?></td>
					<td><?php  echo $item['tel'];?></td>
				    <td><?php  echo $item['content'];?></td>
					<td><?php  echo $item['hits'];?></td>
					<td><?php  echo date('Y-m-d',$item['createtime']);?></td>
				    <td><?php  if($item['ischeck'] == 1) { ?>已审核 <?php  } else { ?>  未审核   <?php  } ?> </td>
				  	 <td><?php  if($item['toplistid'] >0) { ?>  <?php  if($item['paid'] ==1) { ?> 已支付<?php  } else { ?>未支付 <?php  } ?>  <?php  } else { ?>  非置顶信息   <?php  } ?> </td>

					<td style="text-align:right;">
		<?php  if($item['ischeck'] == 0) { ?> <a href="<?php  echo $this->createWebUrl('salelist', array('id' => $item['id'], 'op' => 'done'))?>" onclick="return confirm('此操作不可恢复，确认审核通过？');"
						   class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="审核通过">审核通过</a>
  <?php  } ?>
<a href="<?php  echo $this->createWebUrl('salelist', array('id' => $item['id'], 'op' => 'detail'))?>" 
						   class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="查看详情">查看详情</a>
						<a href="<?php  echo $this->createWebUrl('salelist', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
	</div>
</div>

<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
