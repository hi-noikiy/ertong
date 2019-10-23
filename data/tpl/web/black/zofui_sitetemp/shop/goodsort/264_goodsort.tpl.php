<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH));?>

<?php  if($op == 'create' || $op == 'edit') { ?>

	
<?php  } else if($op == 'list' || $op == 'class1' || $op == 'class2') { ?>
<?php  if($this->module['config']['iscutsort'] == 1) { ?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/topbar', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/topbar', TEMPLATE_INCLUDEPATH));?>
<?php  } ?>
<div class="tr fr">
	<a href="javascript:;" class="add_form_btn topbar_jsbtn" js="loadsort">导入分类</a>
	<a href="javascript:;" class="add_form_btn topbar_jsbtn" js="addgoodsort">添加一级分类</a>
</div>


<?php  if(!empty( $list )) { ?>
  <table class="table" cellspacing="0"> 
   <thead class="thead"> 
    	<tr> 
     		<th class="table_cell title td_col_1"> 
     			<label class="frm_checkbox_label" for="selectAll"> 
     				<i class="icon_checkbox"></i> 
     				<span class="lbl_content">id</span> 
     				<input type="checkbox" class="frm_checkbox" id="selectAll" /> 
     			</label>
     		</th> 
     		<th class="table_cell tl td_col_1">分类名称</th>
     		<th class="table_cell tl td_col_1">分类图标</th>     		
     		<th class="table_cell tl td_col_1">排序序号</th>
      		<th class="table_cell tl td_col_1">状态</th>    		
     		<th class="table_cell tr td_col_1">操作</th>
    	</tr> 
   </thead> 
   <tbody class="tbody" id="js_goods">
   <form method="post">
	   <?php  if(is_array($allsort)) { foreach($allsort as $key => $item) { ?>
	    	<tr class="jstr"> 
	     		<td class="table_cell title"> 
	      			<div class="goods_info">
	      			 	<label class="frm_checkbox_label" > 
	       					<i class="icon_checkbox"></i> 
	       					<input type="checkbox" name="checkall[]" class="frm_checkbox" value="<?php  echo $item['id'];?>" /> 
	       					
	       				</label>
	      			</div>
	     		</td>
	     		<td class="table_cell price tl" style="font-size: 14px;font-weight: 700;color: #333;">
	    			<?php  echo $item['name'];?>
	     		</td>
	     		     		
	     		<td class="table_cell price tl">
	    			
	     		</td>
	     				     		
	     		<td class="table_cell price tl">
	    			<span class="edit_number">
	    				<input type="text" id="<?php  echo $item['id'];?>" inputname="goodsortnumber" inputtype="text" value="<?php  echo $item['number'];?>" class="edit_number_input tl">
	    			</span>
	     		</td>	     		
	     		<td class="table_cell price tl">
	    			<?php  if($item['status'] == 0) { ?>
	    				正常
	    			<?php  } else if($item['status'] == 1) { ?>
	    				<span class="font_ff5f27">下架</span>
	    			<?php  } ?>
	     		</td>
			    <td class="table_cell oper last_child tr opclass" style="position: relative;">
			    	<a  href="javascript:;" class="edit_listitem" id="<?php  echo $item['id'];?>">编辑</a>
			    	<a href="<?php  echo self::pwUrl('shop','goodsort',array('op'=>'delete','id'=>$item['id']))?>" onclick="return confirm('删除不能恢复，确定要删除吗？');">删除</a>
			    	<a href="javascript:;" class="adddown" sid="<?php  echo $item['id'];?>">添加下级</a>
			    	<p>
			    		<a href="javascript:;" class="copy_url" data-href="<?php echo '/zofui_sitetemp/pages/goodlist/goodlist?type=1&sid='.$item['id']?>">复制路径</a>
			    	</p>
			    	<p>
			    		<?php  if($key > 0) { ?>
			    			<a style="color: #999;" href="javascript:;" class="showdown" cid=".tr_<?php  echo $key;?>" status="1">查看下级分类</a>
			    		<?php  } else { ?>
			    			<a style="color: #999;" href="javascript:;" class="showdown" cid=".tr_<?php  echo $key;?>" status="0">收起</a>
			    		<?php  } ?>
			    	</p>
			    </td>
	    	</tr>
	    		
	    	<?php  if(is_array($item['down'])) { foreach($item['down'] as $in) { ?>
	    	<tr class="tr_<?php  echo $key;?> <?php  if($key > 0) { ?>hideitem<?php  } ?>" style="color: #999;" >
	     		<td class="table_cell title tr"> 
	      			<div class="goods_info">
	      			 	<label class="frm_checkbox_label" > 
	      			 		<div class="sort_l"></div>
	       					<i class="icon_checkbox"></i> 
	       					<input type="checkbox" name="checkall[]" class="frm_checkbox" value="<?php  echo $in['id'];?>" /> 
	       				</label>
	      			</div>
	     		</td>
	     		<td class="table_cell price tl" style="color: #999;">
	    			<?php  echo $in['name'];?>
	     		</td>    		
	     		<td class="table_cell price tl">
	    			<img src="<?php  echo tomedia($in['img'])?>" width="30px" height="30px">
	     		</td>		     		
	     		<td class="table_cell price tl">
	    			<span class="edit_number">
	    				<input type="text" id="<?php  echo $in['id'];?>" inputname="goodsortnumber" inputtype="text" value="<?php  echo $in['number'];?>" class="edit_number_input tl">
	    			</span>
	     		</td>	     		
	     		<td class="table_cell price tl">
	    			<?php  if($in['status'] == 0) { ?>
	    				正常
	    			<?php  } else if($in['status'] == 1) { ?>
	    				<span class="font_ff5f27">下架</span>
	    			<?php  } ?>
	     		</td>
			    <td class="table_cell oper last_child tr opclass" style="position: relative;">
			    	<a style="color: #666;" href="javascript:;" class="edit_listitem" type="down" id="<?php  echo $in['id'];?>" >编辑</a>
			    	<a style="color: #666;" href="<?php  echo self::pwUrl('shop','goodsort',array('op'=>'delete','id'=>$in['id']))?>" onclick="return confirm('删除不能恢复，确定要删除吗？');">删除</a>
			    	<p>
			    		<a href="javascript:;" class="copy_url" data-href="<?php echo '/zofui_sitetemp/pages/goodlist/goodlist?type=2&sid='.$in['id']?>">复制路径</a>
			    	</p>
			    </td>
	    	</tr>
	    	<?php  } } ?>

	    <?php  } } ?>
   	</tbody>
  	</table>
	<div class="bottom_page item_cell_box">
		<div class="dib tl">
     			<label class="frm_checkbox_label" for="selectAll"> 
     				<i class="icon_checkbox"></i> 
     				<span class="lbl_content">全选</span> 
     				<input type="checkbox" class="frm_checkbox" id="selectAll" /> 
     			</label>
  			<div class="filter_content dropdown_topbar"> 
		   		<div class="dropdown_menu ">
		    		<a href="javascript:;" class="btn dropdown_switch jsDropdownBt">
		    			<label class="jsBtLabel">批量操作</label>
		    			<i class="arrow"></i>
		    		</a> 
		    		<div class="dropdown_data_container jsDropdownList" > 
			     		<ul class="dropdown_data_list"> 
			      			<li class="dropdown_data_item "> 
			      				<input name="deleteall" class="alldeal_btn" value="删除所选" onclick="return confirm('确定要删除选择的吗？');" type="submit">
			      			</li>
			    		</ul> 
		    		</div> 
		   		</div>
  			</div>
		</div>
		<!-- <div class="tr dib item_cell_flex">
			<?php  echo $pager;?>
		</div> -->
	</div>
		<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
	</form>
<?php  } else { ?>
	<div class="no_data">没有找到数据</div>
<?php  } ?>
<div class="my_model" loadsort style="display: none">
    <div class=" ui-draggable " >
        <div class="dialog">
            <div class="dialog_hd">
                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
            </div>
            <div class="dialog_bd info_box item_cell_box" >
                <div class="setlink_l">
                	<li class="setlink_act" >分类列表</li>
                </div>
                <div class="setlink_r item_cell_flex">
                	<?php  if(is_array($temp)) { foreach($temp as $item) { ?>
                		<div class="item_cell_box setlink_r_item" style="margin-top: 10px;">
                			<div class="model_temp_name"><?php  echo $item['name'];?></div>
                			<div class="setlink_r_box item_cell_flex" style="padding-right: 5px;">
                				<span class="loadall" id="<?php  echo $item['id'];?>" style="background:green;">导入整个分类</span>
                			</div>
                		</div>
	                	<div class="item_cell_box setlink_r_item">
	                		<div class="setlink_r_box item_cell_flex " >
	                			<?php  if(is_array($item['down'])) { foreach($item['down'] as $in) { ?>
	                			<div class="loadsort_item item_cell_box setlink_r_item" >
	                				<div>
	                					<img src="<?php  echo $in['img'];?>" width="30px" height="30px">
	                				</div>
	                				<div style="width: 100px; text-align: center;"><?php  echo $in['name'];?></div>
	                				<div>
							  			<div class="filter_content dropdown_topbar"> 
									   		<div class="dropdown_menu ">
									    		<a href="javascript:;" class="btn dropdown_switch jsDropdownBt width_100">
									    			<label class="jsBtLabel">
									    				选择上级
									    			</label>
									    			<i class="arrow"></i>
									    		</a> 
									    		<div class="dropdown_data_container jsDropdownList width_100" > 
										     		<ul class="dropdown_data_list">
										     			<?php  if(is_array($oneClass)) { foreach($oneClass as $item) { ?>
										      				<li class="dropdown_data_item "> <a href="javascript:;" id="<?php  echo $item['id'];?>" class="select_item"><?php  echo $item['name'];?></a> </li> 
										      			<?php  } } ?>
										    		</ul>
									    		</div> 
									    		<input type="hidden" name="sortid" value="<?php  echo $info['sortid'];?>">
									   		</div>
							  			</div>
	                				</div>
	                				<div class="setlink_r_box item_cell_flex " >
					      			 	<label class="frm_checkbox_label" > 
					       					<i class="icon_checkbox"></i> 
					       					<input type="checkbox" name="checkall" class="frm_checkbox" value="<?php  echo $in['id'];?>" /> 
					       					选择
					       				</label>
	                				</div>
	                			</div>
	                			<?php  } } ?>
	                		</div>
	                	</div>
                	<?php  } } ?>
                </div>
            </div>
            <div class="dialog_ft">
            	<span class="btn btn_primary btn_input js_btn_p" id="confirm_load">
            		<button type="button" class="js_btn">导入选择的</button>
            	</span>
                <span class="btn btn_default btn_input js_btn_p model_close" >
                    <button type="button" class="js_btn">取消</button>
                </span>
            </div>
        </div>
    </div>
    <div class="mask ui-draggable" style="display: block;"></div>
</div>	

<div class="my_model" addgoodsort style="display: none;position: relative;z-index: 111">
    <div class=" ui-draggable " >
        <div class="dialog">
            <div class="dialog_hd">
                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
            </div>
            <div class="dialog_bd info_box" >
                <form>
					<div class="frm_control_group">
						<label for="" class="frm_label">分类名称</label>
						<div class="frm_controls msg">
							<span class="frm_input_box">
								<input type="text" class="frm_input"  name="name" value="<?php  echo $info['name'];?>">
							</span>
							<p class="frm_tips frm_tips_default">建议在4个字内</p>
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
					<div class="frm_control_group hideitem" >
			  			<label for="" class="frm_label">分类类型</label>
			   			<div class="frm_controls">
			    			<label class="frm_radio_label hide_item show_item" hideitem=".type" showitem=".tempid">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content">一级类型</span>
			    				<input type="radio" name="type" readonly value="0" class="frm_radio" <?php  if($info['parent'] == 0) { ?>checked="checked"<?php  } ?> />
			    			</label>
			    			<label class="frm_radio_label hide_item show_item" showitem=".type"  hideitem=".tempid">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content">二级分类</span>
			    				<input type="radio" name="type" readonly value="1" class="frm_radio" <?php  if($info['parent'] > 0) { ?>checked="checked"<?php  } ?> /> 
			    			</label>
			   			</div>
			  		</div>
			  		
			  		<?php  if($this->module['config']['iscutsort'] == 1) { ?>
					<div class="frm_control_group tempid" >
			  			<label for="" class="frm_label">所属模板</label>
			   			<div class="frm_controls">
			    			<label class="frm_radio_label">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content">不设置模板</span>
			    				<input type="radio" name="tempid" value="0" class="frm_radio" checked="checked" />
			    			</label>
			   				<?php  if(is_array($alltemp)) { foreach($alltemp as $item) { ?>
			    			<label class="frm_radio_label">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content"><?php  echo $item['name'];?></span>
			    				<input type="radio" name="tempid" value="<?php  echo $item['id'];?>" class="frm_radio" />
			    			</label>
			    			<?php  } } ?>
			    			<p class="frm_tips frm_tips_default">设置所属模板后，只有在对应的模板内才显示此分类</p>
			   			</div>
			  		</div>
			  		<?php  } ?>

			  		<div class="hideitem type">
			  			
						<div class="frm_control_group single_img_upload">
							<label for="" class="frm_label">图标</label>
							<div class="frm_controls">
								<?php  echo  WebCommon::tpl_form_field_image('img',$info['img'])?>
								<p class="frm_tips frm_tips_default">提示：若需要设置圆形导航图片，请将图片处理成圆形图片。也可全部不设置图片，那么前端只显示文字</p>
							</div>
						</div>
						<div class="frm_control_group hideitem" >
				  			<label for="" class="frm_label">上级分类</label>
				   			<div class="frm_controls">
				   				<?php  if(is_array($oneClass)) { foreach($oneClass as $item) { ?>
				    			<label class="frm_radio_label">
				    				<i class="icon_radio"></i>
				    				<span class="lbl_content"><?php  echo $item['name'];?></span>
				    				<input type="radio" name="parent" value="<?php  echo $item['id'];?>" class="frm_radio" />
				    			</label>
				    			<?php  } } ?>
				   			</div>
				  		</div>

			  		</div>			  						
					<div class="frm_control_group change_item" item="arealimit_item">
			  			<label for="" class="frm_label">状态</label>
			   			<div class="frm_controls">
			    			<label class="frm_radio_label">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content">上架</span>
			    				<input type="radio" name="status" value="0" class="frm_radio" <?php  if($info['status'] == 0) { ?>checked="checked"<?php  } ?> />
			    			</label>
			    			<label class="frm_radio_label" show="limitbox">
			    				<i class="icon_radio"></i>
			    				<span class="lbl_content">下架</span>
			    				<input type="radio" name="status" value="1" class="frm_radio" <?php  if($info['status'] == 1) { ?>checked="checked"<?php  } ?> /> 
			    			</label>	    			
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
    <div class="mask ui-draggable" style="display: block;"></div>
</div>


<script type="text/javascript">
	$(function(){

		$('.topbar_jsbtn[js="addgoodsort"]').click(function(){

		});

		var fid = 0;
		$('.edit_listitem').click(function(){
			var nowfid = $(this).attr('id');
			var type = $(this).attr('type');
			Http('post','json','findgoodsort',{fid:nowfid,plug:0},function(data){
				if(data.status == 200){
					fid = nowfid; // 防止取消后再添加异常
					$('input[name=name]').val(data.obj.name);
					$('input[name=number]').val(data.obj.number);
					if( data.obj.img ) {
						$('input[name=img]').val(data.obj.img);
						if( data.obj.img ) $('.img-thumbnail').attr('src',data.obj.showimg).parent().show();
					}else{
						$('input[name=img]').val('');
						$('.img-thumbnail').attr('src','').parent().hide();
					}
					
					if( data.obj.parent > 0 ) {
						var act = $('input[name="type"][value="1"]');
						act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
						act.parent().addClass('selected');
						$('.hideitem.type').show();

						var act = $('input[name="parent"][value="'+data.obj.parent+'"]');
						act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
						act.parent().addClass('selected');

					}else{
						var act = $('input[name="type"][value="0"]');
						act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
						act.parent().addClass('selected');
						$('.hideitem.type').hide();
					}

					if( data.obj.parent <= 0 ) {
						var act = $('input[name="tempid"][value="'+data.obj.tempid+'"]');
						act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
						act.parent().addClass('selected');
						$('.tempid').show();
					}else{
						$('.tempid').hide();
					}

					var act = $('input[name="status"][value="'+data.obj.status+'"]');
					act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
					act.parent().addClass('selected');

					if( type == 'down' ) {

					}

					$('.my_model[addgoodsort]').show();

				}else{
					webAlert(data.res);
				}
			},true);
		});
		
		$('#confirm_addform').click(function(){

			var postdata = {
				fid : fid,
				name : $('input[name=name]').val(),
				number : $('input[name=number]').val(),
				img : $('input[name=img]').val(),
				status : $('input[name=status]:checked').val(),
				parent : $('input[name=parent]:checked').val(),
				type : $('input[name=type]:checked').val()*1,
				tempid : $('input[name=tempid]:checked').val()*1,
				plug:0,
			};
			if( postdata.type == 0 ){
				delete postdata.parent;
			}

			if( postdata.type == 1 && (postdata.parent == 0 || typeof postdata.parent == 'undefined') ){
				webAlert('请选择上级分类');return false;
			}
			

			Http('post','json','addgoodsort',postdata,function(data){
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
		$('.topbar_jsbtn').click(function(){
			fid = 0;
			var act = $('input[name="type"][value="0"]');
			act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
			act.parent().addClass('selected');
			$('.hideitem.type').hide();
		});

		$('.adddown').click(function(){
			var sid = $(this).attr('sid');
			var act = $('input[name="parent"][value="'+sid+'"]');
			act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
			act.parent().addClass('selected');

			var act = $('input[name="type"][value="1"]');
			act.prop('checked',true).parents('.frm_controls').find('.frm_radio_label').removeClass('selected');
			act.parent().addClass('selected');

			$('.hideitem.type').show();
			$('.my_model[addgoodsort]').show();

		});

		$('#confirm_load').click(function(){

			var arr = [];
			var isall = true;
			$('input[name=checkall]:checked').each(function(){
				var _this = $(this);
				var id = _this.val();
				var pid = _this.parents('.loadsort_item').find('input[name=sortid]').val();
				arr.push({id:id,pid:pid});
				if( pid == '' ){
					isall = false;
				}
			});

			if( !isall ) {
				webAlert('请将勾选的分类选择好上级分类，若没有上级请点击添加商品分类后，添加一级分类。'); return false;
			}
			
			if( confirm('确定导入吗？') ) {
				Http('post','json','loadgoodsort',{data:arr,plug:0},function(data){
					if(data.status == 200){
						webAlert(data.res);
						setTimeout(function(){
							location.href = '';
						},500);
					}else{
						webAlert(data.res);
					}
				},true);
			}
		});

		$('.loadall').click(function(){
			var id = $(this).attr('id');

			if( confirm('这个分类和这个分类的所有子分类将被导入，确定导入吗？') ) {
				Http('post','json','loadallgoodsort',{id : id,plug:0},function(data){
					if(data.status == 200){
						webAlert(data.res);
						setTimeout(function(){
							location.href = '';
						},500);
					}else{
						webAlert(data.res);
					}
				},true);
			}
		});

	});
</script>

<?php  } ?>
	
	
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH));?>