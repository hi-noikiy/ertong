<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if($operation == 'post') { ?>

<?php  } else if($operation == 'display') { ?>
<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
			<input type="hidden" name="id" value="<?php  echo $intro['id'];?>" />
			<input type="hidden" name="op" value="post" />
			
		<div class="panel panel-default">
			<div class="panel-heading">
				系统设置
			</div>
			<div class="panel-body">
	
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>小程序名称</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='name' name="name" class="form-control" value="<?php  echo $intro['name'];?>" />
					</div>
				</div>
 <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>城市</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='city' name="city" class="form-control" value="<?php  echo $intro['city'];?>" />
					</div>
				</div>
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>企业logo</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image('logo', $intro['logo'])?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>推广分销横幅图</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image('fxbanner', $intro['fxbanner'])?>
                    </div>
                </div>
	             
	              <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>首页广告图(580*800px)</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image('indexadv', $intro['indexadv'])?>
                    </div>
                </div>
	
				<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>企业介绍</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_ueditor('content', $intro['content'])?>
                    </div>
                </div>
                 <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>企业地址</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='address' name="address" class="form-control" value="<?php  echo $intro['address'];?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>企业位置</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_form_field_coordinate('location', array('lng' => $intro['lng'], 'lat' => $intro['lat']))?>
					</div>
				</div>
<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>佣金比例</label>
					<div class="col-sm-9 col-xs-12">
						  <div class="row row-fix">
			<div class="col-xs-4 col-sm-4">
			<input type="text" name="rate1" value="<?php  echo $intro['rate1'];?>" placeholder="一级比例" class="form-control">
			</div>
			<div class="col-xs-4 col-sm-4">
				<input type="text" name="rate2" value="<?php  echo $intro['rate2'];?>" placeholder="二级比例" class="form-control">
			</div>
			<div class="col-xs-4 col-sm-4">
			</div>
		</div>
					</div>
				</div>

	
	<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>首页房源数量</label>
					<div class="col-sm-9 col-xs-12">
						  <div class="row row-fix">
			<div class="col-xs-4 col-sm-4">
			新楼盘：<input type="text" name="newlimit" value="<?php  echo $intro['newlimit'];?>" placeholder="新楼盘数量" class="form-control">
			</div>
			<div class="col-xs-4 col-sm-4">
			二手房：<input type="text" name="oldlimit" value="<?php  echo $intro['oldlimit'];?>" placeholder="二手房数量" class="form-control">
			</div>
			<div class="col-xs-4 col-sm-4">
			出租房：<input type="text" name="letlimit" value="<?php  echo $intro['letlimit'];?>" placeholder="出租房数量" class="form-control">
			</div>
		</div>
					</div>
				</div>		 
	




				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>企业电话</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='tel' name="tel" class="form-control" value="<?php  echo $intro['tel'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>QQ</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='qq' name="qq" class="form-control" value="<?php  echo $intro['qq'];?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>Email</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='email' name="email" class="form-control" value="<?php  echo $intro['email'];?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>企业营业时间</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='opentime' name="opentime" class="form-control" value="<?php  echo $intro['opentime'];?>" />
					</div>
				</div>
							
                <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>主色调(旧版色:#3274e5)</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" id='maincolor' name="maincolor" class="form-control" value="<?php  echo $intro['maincolor'];?>" />
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">经纪人入驻审核</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isagent' value=1 <?php  if($intro['isagent']==1) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isagent' value=0 <?php  if($intro['isagent']==0) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">经纪人发布审核</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='ischeck' value=1 <?php  if($intro['ischeck']==1) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='ischeck' value=0 <?php  if($intro['ischeck']==0) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>
      				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">个人发布审核</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='ischeck2' value=1 <?php  if($intro['ischeck2']==1) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='ischeck2' value=0 <?php  if($intro['ischeck2']==0) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">个人发布是否收费</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='ispay' value=1 <?php  if($intro['ispay']==1) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='ispay' value=0 <?php  if($intro['ispay']==0) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>


				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">个人发布二手房开启</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isoldhouse' value=0 <?php  if($intro['isoldhouse']==0) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isoldhouse' value=1 <?php  if($intro['isoldhouse']==1) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>



				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">个人发布出租开启</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='islethouse' value=0 <?php  if($intro['islethouse']==0) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='islethouse' value=1 <?php  if($intro['islethouse']==1) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>



				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">个人发布求购开启</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isbuyhouse' value=0 <?php  if($intro['isbuyhouse']==0) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isbuyhouse' value=1 <?php  if($intro['isbuyhouse']==1) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">个人发布求租开启</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='issalehouse' value=0 <?php  if($intro['issalehouse']==0) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='issalehouse' value=1 <?php  if($intro['issalehouse']==1) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">经纪人发布二手房开启</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isagentoldhouse' value=0 <?php  if($intro['isagentoldhouse']==0) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isagentoldhouse' value=1 <?php  if($intro['isagentoldhouse']==1) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">经纪人发布出租房开启</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isagentlethouse' value=0 <?php  if($intro['isagentlethouse']==0) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isagentlethouse' value=1 <?php  if($intro['isagentlethouse']==1) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>


				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启首页获取授权</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='isgetuser' value=1 <?php  if($intro['isgetuser']==1) { ?>checked<?php  } ?> /> 是
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='isgetuser' value=0 <?php  if($intro['isgetuser']==0) { ?>checked<?php  } ?> /> 否
						 </label>
					</div>
				</div>

			


		       <div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">模板选择</label>
					<div class="col-sm-9 col-xs-12">
						 <label class='radio-inline'>
							 <input type='radio' name='moban' value=0 <?php  if($intro['moban']==0) { ?>checked<?php  } ?> /> 模板一
						 </label>
						 <label class='radio-inline'>
							 <input type='radio' name='moban' value=1 <?php  if($intro['moban']==1) { ?>checked<?php  } ?> /> 模板二
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
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>