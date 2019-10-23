<?php defined('IN_IA') or exit('Access Denied');?> <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>


<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>
<ul class="nav nav-tabs">
	<li <?php  if($operation == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('store', array('op' => 'post'))?>">添加门店</a></li>
	<li <?php  if($operation == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('store', array('op' => 'display'))?>">管理门店</a></li>
</ul>
<?php  if($operation == 'post') { ?>
<style type='text/css'>
	.tab-pane {padding:20px 0 20px 0;}
</style>
<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" onsubmit='return formcheck()'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php  if(empty($item['id'])) { ?>添加门店<?php  } else { ?>编辑门店<?php  } ?>
			</div>
			<div class="panel-body">
				
				<div class="tab-content">
					<div class="tab-pane  active" id="tab_basic"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('store_basic', TEMPLATE_INCLUDEPATH)) : (include template('store_basic', TEMPLATE_INCLUDEPATH));?></div>
					
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
	
		var name = $("#name").val();
	var logo = $("input[name='logo']").val();
	var address = $("#address").val();
	var tel = $("#tel").val();
	var qq = $("#qq").val();
	var email = $("#email").val();
	var opentime = $("#opentime").val();
	
	var content = $("textarea[name='content']").val();
	
		if (name == '') {
			util.message("请输入门店名称");
			return false;
		}
		
		if (logo == '') {
			util.message("请上传门店LOGO");
			return false;
		}
		
		if (content == '') {
			util.message("请输入企业介绍");
			return false;
		}
		
		if (address == '') {
			util.message("请输入门店地址");
			return false;
		}
		if (tel == '') {
			util.message("请输入电话");
			return false;
		}
		if (qq == '') {
			util.message("请输入QQ号");
			return false;
		}
		if (email == '') {
			util.message("请输入邮箱");
			return false;
		}
		if (opentime == '') {
			util.message("请输入门店营业时间");
			return false;
		}
	
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
					<th style="width:25%;">门店名称</th>
					<th style="width:10%;">门店电话</th>
					<th style="width:15%;">创建时间</th>
					<th style="width:10%;">排序</th>
					<th style="text-align:right; width:10%;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td><?php  echo $item['id'];?></td>
					<td><?php  echo $item['name'];?></td>
					<td><?php  echo $item['tel'];?></td>
					<td><?php  echo date('Y-m-d',$item['createtime']);?></td>
				
					<td><?php  echo $item['sort'];?>	</td>
					<td style="text-align:right;">
						<a href="<?php  echo $this->createWebUrl('store', array('id' => $item['id'], 'op' => 'post'))?>"class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="编辑"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
						<a href="<?php  echo $this->createWebUrl('store', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="删除"><i class="fa fa-times"></i></a>
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
