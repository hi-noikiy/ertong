<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH));?>

	
<?php  if($_GPC['op'] == 'my' || $_GPC['op'] == 'sys' || $_GPC['op'] == 'module') { ?>
	<?php  if($_GPC['op'] == 'my') { ?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/topbar', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/topbar', TEMPLATE_INCLUDEPATH));?>

	<div class="tr fr">
		<a href="javascript:;" class="add_form_btn topbar_jsbtn" js="addtemp">添加模板</a>
		<?php  if($_W['role'] == 'founder') { ?>
   			<a href="javascript:;" class="add_form_btn " id="tempcloud" >云模板</a>
   		<?php  } ?>
 		<?php  if($_W['siteroot'] == 'http://wx.zofui.net/' && $_W['role'] == 'founder') { ?>
   			<a href="javascript:;" class="add_form_btn " id="tomycloud" >推送模板</a>
   		<?php  } ?>
	</div>
	<?php  } ?>
	<?php  if($_GPC['op'] == 'sys') { ?>
		
		<?php  if(!empty( $tempsort )) { ?>
			<?php  if(is_array($tempsort)) { foreach($tempsort as $item) { ?>
				<a href="<?php  echo self::pwUrl('site','temp',array('op'=>'sys','sort'=>$item['id']))?>" class="tempsortbtn <?php  if($_GPC['sort'] == $item['id']) { ?>tempsortbtn_act<?php  } ?>"><?php  echo $item['name'];?></a>
			<?php  } } ?>
		<?php  } ?>
		<?php  if(!empty( $cloudsort )) { ?>
			<?php  if(is_array($cloudsort)) { foreach($cloudsort as $item) { ?>
				<a href="<?php  echo self::pwUrl('site','temp',array('op'=>'sys','csort'=>$item['id']))?>" class="tempsortbtn <?php  if($_GPC['csort'] == $item['id']) { ?>tempsortbtn_act<?php  } ?>"><?php  echo $item['name'];?></a>
			<?php  } } ?>
		<?php  } ?>
	<?php  } ?>	
	<?php  if(!empty( $list )) { ?>
		<div class="temp_page_box">
			<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<div class="temp_page_item ">
					
					<div class="temp_item_thumb">
						<?php  if($item['isact'] == 1) { ?>
							<div class="temp_page_actitem">使用中</div>
						<?php  } ?>
						<?php  if($item['issystem'] == 1 && $item['issetsystem'] == 1) { ?>
							<div class="temp_page_actitem temp_page_system">系统模板</div>
							<?php  if($_W['role'] == 'founder' && empty( $item['iscloud'] )) { ?>
								<div class="temp_page_settemp temp_page_editsystem">
									<p>
										<a class="editsystem edit_listitem settemp_btnr" type="sys" id="<?php  echo $item['id'];?>" href="javascript:;">编辑模板</a>
									</p>
									<p>
										<a class="  settemp_btnr" href="<?php  echo self::pwUrl('site','page',array('op'=>'add','tid'=>$item['id']))?>">设计页面</a>
									</p>
									<?php  if($_W['role'] == 'founder') { ?>
									<p>
										<a class="editsystem settemp_btnr tocloud" id="<?php  echo $item['id'];?>" href="javascript:;">推至云端</a>
									</p>
									<?php  } ?>
								</div>
							<?php  } ?>
						<?php  } ?>
						<img src="<?php  echo tomedia( $item['img'] )?>">
						<div class="temp_item_name"><?php  echo $item['name'];?></div>
						<?php  if($item['issystem'] == 0) { ?>
						<div class="temp_page_settemp">
							<?php  if($item['isact'] == 0) { ?>
							<p>
								<a href="javascript:;" class="settemp_btn" type="use" id="<?php  echo $item['id'];?>">使用模板</a>
							</p>
							<?php  } ?>
							<p>
								<a class="  settemp_btnr" href="<?php  echo self::pwUrl('site','page',array('op'=>'add','tid'=>$item['id']))?>">设计页面</a>
							</p>
							<?php  if($_W['role'] == 'founder') { ?>
							<div class="opclass" >
								<a class="editsystem settemp_btnr tocloud" id="<?php  echo $item['id'];?>" href="javascript:;">推至云端</a>
							</div>
							<?php  } ?>							
						</div>
						<?php  } ?>
					</div>
					<?php  if($item['issystem'] == 1) { ?>
						<?php  if($item['issetsystem'] == 1) { ?> 
							<!-- 不是模块模板 -->
							<div class="item_cell_box temp_item_bot">
								<li class="item_cell_flex"><a href="javascript:;" class="temptopage" type="tomy" id="<?php  echo $item['id'];?>" ctype="<?php  echo $item['iscloud'];?>">导出使用</a></li>
								<?php  if(empty( $item['iscloud'] )) { ?>
									<li class="good_qrcode_box">
										<a href="javascript:;" class="show_good_qrcode">预览模板</a>
										<?php  if(empty( $item['showqr'] )) { ?>
											<img src="<?php  echo $item['urlimg'];?>" width="200px" height="200px">
										<?php  } else { ?>
											<img src="<?php  echo tomedia($item['showqr'])?>" width="200px" height="200px">
										<?php  } ?>
									</li>
									<?php  if($_W['role'] == 'founder') { ?>
									<li class="item_cell_flex opclass tr">
				                        <a href="javascript:;" class="drop_down_editbtn" style="display: inherit;">修改分类</a>
				                        <div class="dropdown_menu_box dropdown_menu dropdown_checkbox has_goods_group" >
				                            <div class="dropdown_data_container jsDropdownsList" style="display: none;right: 0;">
				                                <div class="dropdown_data_list" style="">
				                                    <ul class="drop_down_inputitem">
				                                        <li class="">选择分类</li>
				                                        <li class="">
				                                            <select name="sort" style="width: 100%;">
				                                            	<option></option>
				                                            	<?php  if(is_array($tempsort)) { foreach($tempsort as $in) { ?>
				                                            		<option <?php  echo $item['sort'];?> <?php  if($in['id'] == $item['sort']) { ?>selected="selected"<?php  } ?> value="<?php  echo $in['id'];?>"><?php  echo $in['name'];?></option>
				                                            	<?php  } } ?>
				                                            </select>
				                                        </li>
				                                    </ul>
				                                    <div class="dropdown_tool_bar"> 
				                                        <a href="javascript:;" class="btn js_btn btn_primary confirm_changetempsort" id="<?php  echo $item['id'];?>" style="padding:0;">确定</a>
				                                        <a href="javascript:;" class="btn js_btn btn_default dropdown_edit_cancel" style="padding:0;">取消</a>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
									</li>
									<?php  } ?>
								<?php  } ?>
							</div>
							<?php  if(empty( $item['iscloud'] )) { ?>
							<div class="item_cell_box temp_item_bot">
								<?php  if($_W['role'] == 'founder') { ?>
								<li>
									<a href="<?php  echo self::pwUrl('site','page',array('op'=>'list','tid'=>$item['id']))?>">页面列表</a>
								</li>
								<li class="item_cell_flex tc">
									<a href="<?php  echo self::pwUrl('site','page',array('op'=>'add','opp'=>'bar','tid'=>$item['id']))?>">设置导航</a>
								</li>
								<li class="deletesystem" id="<?php  echo $item['id'];?>" type="sys"><a href="javascript:;" >删除模板</a></li>
								<?php  } ?>
							</div>
							<?php  } ?>
						<?php  } else { ?>
							<!-- 模块模板 -->
							<div class="item_cell_box temp_item_bot">
								<li class="item_cell_flex">
									<a href="javascript:;" class="tosystem" id="<?php  echo $item['id'];?>" sys="1">设为系统模板</a>
								</li>
							</div>
							<div class="item_cell_box temp_item_bot">
							</div>
						<?php  } ?>
					<?php  } else { ?>
						<div class="item_cell_box temp_item_bot">
							<li><a href="<?php  echo self::pwUrl('site','page',array('op'=>'list','tid'=>$item['id']))?>">页面列表</a></li>
							<li class="item_cell_flex tc"><a href="<?php  echo self::pwUrl('site','page',array('op'=>'add','tid'=>$item['id']))?>">设计页面</a></li>
							<li><a href="<?php  echo self::pwUrl('site','page',array('op'=>'add','opp'=>'bar','tid'=>$item['id']))?>">设置导航</a></li>
						</div>
						<div class="item_cell_box temp_item_bot">
							<li><a href="javascript:;" class="edit_listitem" id="<?php  echo $item['id'];?>">编辑模板</a></li>
							<li class="deletesystem item_cell_flex <?php  if($_W['role'] == 'founder' | ( $_W['role'] == 'vice_founder' && $set['istoseys'] == 1 )) { ?>tc<?php  } else { ?>tr<?php  } ?>" id="<?php  echo $item['id'];?>" >
								<a href="javascript:;" >删除模板</a>
							</li>
							<?php  if($_W['role'] == 'founder' || ( $_W['role'] == 'vice_founder' && $set['istoseys'] == 1 )) { ?>
								<!-- <li class="tosystem" id="<?php  echo $item['id'];?>"><a href="javascript:;" >设为系统</a></li> -->
								<li class=" opclass tr">
			                        <a href="javascript:;" class="drop_down_editbtn" style="display: inherit;">设为系统</a>
			                        <div class="dropdown_menu_box dropdown_menu dropdown_checkbox has_goods_group" >
			                            <div class="dropdown_data_container jsDropdownsList" style="display: none;right: 0;">
			                                <div class="dropdown_data_list" style="">
			                                    <ul class="drop_down_inputitem">
			                                        <li class="">选择类型</li>
			                                        <li class="">
			                                            <select name="type" style="width: 100%;">
			                                            	<option></option>
			                                            	<option value="1">官网系统模板</option>
			                                            	<option value="2">商城系统模板</option>
			                                            	<option value="4">点餐系统模板</option>
			                                            	<option value="3">预约系统模板</option>
			                                            </select>
			                                        </li>
			                                    </ul>
			                                    <div class="dropdown_tool_bar"> 
			                                        <a href="javascript:;" class="btn js_btn btn_primary tosystem" id="<?php  echo $item['id'];?>" sys="0" style="padding:0;">确定</a>
			                                        <a href="javascript:;" class="btn js_btn btn_default dropdown_edit_cancel" style="padding:0;">取消</a>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
								</li>

							<?php  } ?>
						</div>	
					<?php  } ?>					
				</div>
			<?php  } } ?>
		</div>
		<div class="tr">
			<?php  echo $pager;?>
		</div>
    <?php  } else { ?>
    	<div class="no_data">没有找到数据</div>
    <?php  } ?>

<div class="my_model" url style="display: none">
    <div class=" ui-draggable " >
        <div class="dialog">
            <div class="dialog_hd">
                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
            </div>
            <div class="dialog_bd info_box item_cell_box" >
                <div class="setlink_l">
                	<li class="setlink_act change_item show_item hide_item" showitem=".temp_page" hideitem=".other_page">模板页面</li>
                	<!-- <li class="change_item show_item hide_item" showitem=".other_page" hideitem=".temp_page">其他页面</li> -->
                </div>
                <div class="setlink_r item_cell_flex">
                	<div class="temp_page">
	         			<div class="item_cell_box setlink_r_item">
	        				<div>{{inn.name}}</div>
	        				<div class="setlink_r_box item_cell_flex " >
	        					<span class="select_index" url="111">选择作为首页</span>
	        				</div>
	        			</div>
                	</div>
                	<div class="other_page">
                		
                	</div>

                </div>
            </div>
            <div class="dialog_ft">
                <span class="btn btn_default btn_input js_btn_p model_close" >
                    <button type="button" class="js_btn">取消</button>
                </span>
            </div>
        </div>
    </div>
    <div class="mask ui-draggable" style="display: block;"></div>
</div>


<div class="my_model" addtemp style="display: none;position: relative;z-index: 999;">
    <div class=" ui-draggable " >
        <div class="dialog">
            <div class="dialog_hd">
                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
            </div>
            <div class="dialog_bd info_box" >
                <form>
					<div class="frm_control_group">
						<label for="" class="frm_label">模板名称</label>
						<div class="frm_controls msg">
							<span class="frm_input_box">
								<input type="text" class="frm_input"  name="name" value="<?php  echo $info['name'];?>">
							</span>
							<p class="frm_tips frm_tips_default">设置名称便于辨识不同模板</p>
						</div>
					</div>
					<div class="frm_control_group">
						<label for="" class="frm_label">排序序号</label>
						<div class="frm_controls msg">
							<span class="frm_input_box">
								<input type="text" class="frm_input"  name="number" value="<?php  echo $info['number'];?>">
							</span>
							<p class="frm_tips frm_tips_default">填入数字，越大越前</p>
						</div>
					</div>
					<div class="frm_control_group " >
			  			<label for="" class="frm_label">所属分类</label>
			   			<div class="frm_controls">
			   				<?php  if(is_array($tempsort)) { foreach($tempsort as $item) { ?>
			    			<label class="frm_radio_label">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content"><?php  echo $item['name'];?></span>
			    				<input type="radio" name="sort" value="<?php  echo $item['id'];?>" class="frm_radio" />
			    			</label>
			    			<?php  } } ?>
			   			</div>
			  		</div>
					<div class="frm_control_group single_img_upload thumb">
						<label for="" class="frm_label">模板图标</label>
						<div class="frm_controls">
							<?php  echo  WebCommon::tpl_form_field_image('img',$info['img'])?>
							<p class="frm_tips frm_tips_default"></p>
						</div>
					</div>
					<div class="frm_control_group single_img_upload showqr">
						<label for="" class="frm_label">演示二维码</label>
						<div class="frm_controls">
							<?php  echo  WebCommon::tpl_form_field_image('showqr',$info['showqr'])?>
							<p class="frm_tips frm_tips_default"></p>
						</div>
					</div>

                </form>
            </div>
            <div class="dialog_ft">
                <span class="btn btn_primary btn_input js_btn_p" id="confirm_addform" >
                    <button type="button" class="js_btn">保存</button>
                </span>
                <span class="btn btn_default btn_input js_btn_p model_close" >
                    <button type="button" class="js_btn">取消</button>
                </span>
            </div>
        </div>
    </div>
    <div class="mask ui-draggable" style="display: block;z-index: 222"></div>
</div>

<div class="my_model" tocloud style="display: none;position: relative;z-index: 999;">
    <div class=" ui-draggable " >
        <div class="dialog">
            <div class="dialog_hd">
                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
            </div>
            <div class="dialog_bd info_box" style="height: 250px;">
                <form>
					<div class="frm_control_group">
						<label for="" class="frm_label">出售云币</label>
						<div class="frm_controls msg">
							<span class="frm_input_box">
								<input type="text" class="frm_input"  name="fee" value="<?php  echo $info['fee'];?>">
							</span>
							<p class="frm_tips frm_tips_default">别人购买模板需要支付的云币</p>
							<p class="frm_tips frm_tips_default">推至云端是将模板推送至云模板内，别人可购买使用你的推送的模板，你可获得云币，请不要推送垃圾模板和模块模板，否则可能封号处理</p>
						</div>
					</div>
                </form>
            </div>
            <div class="dialog_ft">
                <span class="btn btn_primary btn_input js_btn_p" id="confirm_tocloud" >
                    <button type="button" class="js_btn">提交</button>
                </span>
                <span class="btn btn_default btn_input js_btn_p model_close" >
                    <button type="button" class="js_btn">取消</button>
                </span>
            </div>
        </div>
    </div>
    <div class="mask ui-draggable" style="display: block;z-index: 222"></div>
</div>

<div class="cloudbox">
	<div class="mask ui-draggable"></div>
	<div class="cloudbox_in" ></div>
	<div class="closecloud">×</div>
</div>

<script type="text/javascript">
	$(function(){

		var fid = 0;
		$('.edit_listitem').click(function(){
			var nowfid = $(this).attr('id');
			var type = $(this).attr('type');
			Http('post','json','findtemp',{fid:nowfid,type:type},function(data){
				if(data.status == 200){
					fid = nowfid; // 防止取消后再添加异常
					$('input[name=name]').val(data.obj.name);
					$('input[name=number]').val(data.obj.number);
					if( data.obj.img ) {
						$('input[name=img]').val(data.obj.img);
						if( data.obj.img ) $('.thumb .img-thumbnail').attr('src',data.obj.showimg).parent().show();
					}else{
						$('input[name=img]').val('');
						$('.thumb .img-thumbnail').attr('src','').parent().hide();
					}
					
					if( data.obj.issetsystem == 1 && data.obj.issystem == 1 ){
						$('.showqr').show();
						if( data.obj.showqr ) {
							$('input[name=showqr]').val(data.obj.showqr);
							if( data.obj.showqr ) $('.showqr .img-thumbnail').attr('src',data.obj.showqr).parent().show();
						}else{
							$('input[name=showqr]').val('');
							$('.showqr .img-thumbnail').attr('src','').parent().hide();
						}
					}else{
						$('.showqr').hide();
					}

					var act = $('input[name="sort"][value="'+data.obj.sort+'"]');
					act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
					act.parent().addClass('selected');
					
					$('.my_model[addtemp]').show();

				}else{
					webAlert(data.res);
				}
			},true);
		});
		
		$('#confirm_addform').click(function(){
		console.log( $('input[name=name]').val() );
			var postdata = {
				fid : fid,
				name : $('input[name=name]').val(),
				number : $('input[name=number]').val(),
				img : $('input[name=img]').val(),
				showqr : $('input[name=showqr]').val(),
				sort : $('input[name=sort]:checked').val(),
			};
			
			Http('post','json','addtempform',postdata,function(data){
				if(data.status == 200){
					webAlert(data.res);
					setTimeout(function(){
						location.href = '';
					},500);
				}else{
					webAlert(data.res);
				}
			},true);

		});

		$('.change_item').click(function(){
			$('.change_item').removeClass('setlink_act');
			$(this).addClass('setlink_act');
		});
	});
</script>

<?php  } ?>
	
	
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH));?>