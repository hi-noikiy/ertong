<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common', TEMPLATE_INCLUDEPATH)) : (include template('common', TEMPLATE_INCLUDEPATH));?>

<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('newhouse', array('op' => 'post'))?>">添加楼盘</a></li>
	<li <?php  if($operation == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('newhouse', array('op' => 'display'))?>">管理楼盘</a></li>
</ul>
<?php  if($operation == 'post') { ?>
<link type="text/css" rel="stylesheet" href="../addons/ewei_shopping/images/uploadify_t.css" />
<style type='text/css'>
	.tab-pane {padding:20px 0 20px 0;}
</style>
<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" onsubmit='return formcheck()'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php  if(empty($item['id'])) { ?>添加楼盘<?php  } else { ?>编辑楼盘<?php  } ?>
			</div>
			<div class="panel-body">
				
				<div class="tab-content">
					<div class="tab-pane  active" id="tab_basic"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('newhouse_basic', TEMPLATE_INCLUDEPATH)) : (include template('newhouse_basic', TEMPLATE_INCLUDEPATH));?></div>
					
				</div>
			</div>
		</div>
		<div class="form-group col-sm-12">
			<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</form>
</div>

<script type="text/javascript">
	var category = <?php  echo json_encode($children)?>;

	$(function () {
		window.optionchanged = false;
		$('#myTab a').click(function (e) {
			e.preventDefault();//阻止a链接的跳转行为
			$(this).tab('show');//显示当前选中的链接及关联的content
		})
	});

	function formcheck(){
	
		if($("#housename").isEmpty()) {
			util.message("请输入楼盘名称");
			return false;
		}
		if($("#companyname").isEmpty()) {
			util.message("请输入开发商");
			return false;
		}
		
		if($("#housetype").isEmpty()) {
			util.message("请选择楼盘类型");
			return false;
		}
		if($("#houseprice").isEmpty()) {
			util.message("请输入楼盘均价");
			return false;
		}
		if($("#houseareaid").val() == 0) {
			util.message("请选择所属地区");
			return false;
		}
		if($("#tel").isEmpty()) {
			util.message("请输入售楼电话");
			return false;
		}
		if($("#houseaddress").isEmpty()) {
			util.message("请输入项目地址");
			return false;
		}
		if($("#housesaleaddress").isEmpty()) {
			util.message("请输入售楼地址");
			return false;
		}
		if($("#houserate").isEmpty()) {
			util.message("请输入容积率");
			return false;
		}
		if($("#housegreenrate").isEmpty()) {
			util.message("请输入绿化率");
			return false;
		}
		if($("#housecovered").isEmpty()) {
			util.message("请输入占地面积");
			return false;
		}
		if($("#buildarea").isEmpty()) {
			util.message("请输入建筑面积");
			return false;
		}
		if($("#opensaletime").isEmpty()) {
			util.message("请输入开盘时间");
			return false;
		}
		if($("#staytime").isEmpty()) {
			util.message("请输入入驻时间");
			return false;
		}
		if($("#productspecial").isEmpty()) {
		//	util.message("请输入项目特色");
		//	return false;
		}
		if($("#houseschool").isEmpty()) {
			util.message("请输入所属学区");
			return false;
		}
		if($("#housebus").isEmpty()) {
			util.message("请输入附近公交");
			return false;
		}
		if($("#housestatus").value()) {
			util.message("请选择楼盘状态");
			return false;
		}
	
		
		var content = $("textarea[name='content']").val();

		if (content == '') {
			util.message("请输入详情");
			return false;
		}
		
		
		
		var full = checkoption();
		if(!full){return false;}
	
		return true;
	}
	
	function checkoption(){
		
		var full = true;
		if( $("#hasoption").get(0).checked){
			$(".spec_title").each(function(i){
				if( $(this).isEmpty()) {
					$('#myTab a[href="#tab_option"]').tab('show');
					Tip.focus(".spec_title:eq(" + i + ")","请输入规格名称!","top");
					full =false;
					return false;
				}
			});
			$(".spec_item_title").each(function(i){
				if( $(this).isEmpty()) {
					$('#myTab a[href="#tab_option"]').tab('show');
					Tip.focus(".spec_item_title:eq(" + i + ")","请输入规格项名称!","top");
					full =false;
					return false;
				}
			});
		}
		if(!full) { return false; }
		return full;
	}

</script>

<?php  } else if($operation == 'display') { ?>

<div class="main">
	<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form action="./index.php" method="get" class="form-horizontal" role="form">
			<input type="hidden" name="c" value="site" />
			<input type="hidden" name="a" value="entry" />
			<input type="hidden" name="m" value="weixinmao_house" />
			<input type="hidden" name="do" value="newhouse" />
			<input type="hidden" name="op" value="display" />
			<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">关键字</label>
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
					<th style="width:10%;">城市</th>
					<th style="width:10%;">地区</th>
					<th style="width:15%;">楼盘名称</th>
					<th style="width:10%;">楼盘房型</th>
					<th style="width:10%;">楼盘均价</th>
					<th style="width:15%;">创建时间</th>
					<th style="width:10%;">是否推荐</th>
					<th style="width:10%;">排序</th>
					<th style="text-align:right; width:15%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td><?php  echo $item['id'];?></td>
					<td><?php  echo $item['cityname'];?></td>
					<td><?php  echo $item['areaname'];?></td>
					<td><?php  echo $item['housename'];?></td>
					<td><?php  echo $item['housetypename'];?></td>
				    <td><?php  echo $item['houseprice'];?>元/㎡</td>
					<td><?php  echo date('Y-m-d',$item['createtime']);?></td>
					<td>
					<?php  if($item['isrecommand']==1) { ?>
								<span class='label label-success'>是</span>
								<?php  } else { ?>
								<span class='label label-danger'>否</span>
								<?php  } ?>
					</td>
					
					<td><?php  echo $item['sort'];?>	</td>
					<td style="text-align:right;">
						<a href="<?php  echo $this->createWebUrl('newhouse', array('id' => $item['id'], 'op' => 'post'))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
						<a href="<?php  echo $this->createWebUrl('newhouse', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
		<?php  echo $pager;?>
	</div>
	</div>
</div>
<script type="text/javascript">
	require(['bootstrap'],function($){
		$('.btn').hover(function(){
			$(this).tooltip('show');
		},function(){
			$(this).tooltip('hide');
		});
	});

	var category = <?php  echo json_encode($children)?>;
	function setProperty(obj,id,type){
		$(obj).html($(obj).html() + "...");
		$.post("<?php  echo $this->createWebUrl('setgoodsproperty')?>"
			,{id:id,type:type, data: obj.getAttribute("data")}
			,function(d){
				$(obj).html($(obj).html().replace("...",""));
				if(type=='type'){
				 $(obj).html( d.data=='1'?'实体物品':'虚拟物品');
				}
				if(type=='status'){
				 $(obj).html( d.data=='1'?'上架':'下架');
				}
				$(obj).attr("data",d.data);
				if(d.result==1){
					$(obj).toggleClass("label-info");
				}
			}
			,"json"
		);
	}

</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
