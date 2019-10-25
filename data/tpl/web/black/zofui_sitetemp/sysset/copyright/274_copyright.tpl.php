<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH));?>
	<?php  if($_GPC['op'] == 'list') { ?>
		<form method="post" action="">
			<div class="frm_control_group">
				<label for="" class="frm_label"></label>
				<div class="frm_controls">
				</div>
			</div>
			<div class="frm_control_group">
	  			<label for="" class="frm_label">状态</label>
	   			<div class="frm_controls">
	    			<label class="frm_radio_label">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">开启</span>
	    				<input type="radio" name="status" value="0" class="frm_radio" <?php  if($temp['status'] == 0) { ?>checked="checked"<?php  } ?> />
	    			</label>
	    			<label class="frm_radio_label" show="limitbox">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">关闭</span>
	    				<input type="radio" name="status" value="1" class="frm_radio" <?php  if($temp['status'] == 1) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	    			<div class="frm_tips">提示：版权只有站点管理员才能设置，一旦设置，本平台下的所有小程序页面底部都会有版权</div>
	   			</div>
	  		</div>
			<div class="frm_control_group">
	  			<label for="" class="frm_label">点击动作</label>
	   			<div class="frm_controls">
			    	<label class="frm_radio_label show_item hide_item" showitem=".type1" hideitem=".type2,.type3,.type4,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">拨打电话</span>
			    		<input type="radio" name="type" value="tel" class="frm_radio"  <?php  if($temp['type'] == 'tel') { ?>checked="checked"<?php  } ?>  /> 
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type2" hideitem=".type1,.type3,.type4,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">打开图片</span>
			    		<input type="radio" name="type" value="images" class="frm_radio"  <?php  if($temp['type'] == 'images') { ?>checked="checked"<?php  } ?> />
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type3" hideitem=".type1,.type2,.type4,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">跳转页面</span>
			    		<input type="radio" name="type" value="navigateto" class="frm_radio"  <?php  if($temp['type'] == 'navigateto') { ?>checked="checked"<?php  } ?> />
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type4" hideitem=".type1,.type2,.type3,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">其他小程序</span>
			    		<input type="radio" name="type" value="otherapp" class="frm_radio"  <?php  if($temp['type'] == 'otherapp') { ?>checked="checked"<?php  } ?> />
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type5" hideitem=".type1,.type2,.type3,.type4">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">跳转网页</span>
			    		<input type="radio" name="type" value="toweburl" class="frm_radio"  <?php  if($temp['type'] == 'toweburl') { ?>checked="checked"<?php  } ?> />
			    	</label>			    	
	   			</div>
	  		</div>
			<div class="frm_control_group type1 hideitem" <?php  if($temp['type'] == 'tel') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">电话号码</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_200">
						<input type="text" class="frm_input"  name="tel" value="<?php  echo $temp['tel'];?>">
					</span>
				</div>
			</div>
			<div class="frm_control_group type2 single_img_upload hideitem" <?php  if($temp['type'] == 'images') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">图片列表</label>
				<div class="frm_controls good_images">
					<?php  echo WebCommon::tpl_form_field_multi_image('pic', $temp['pic'],'');?>
					<p class="frm_tips">可拖拽图片排序</p>
				</div>
			</div>
			<div class="frm_control_group type3 hideitem" <?php  if($temp['type'] == 'navigateto') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">页面路径</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="url" value="<?php  echo $temp['params']['url'];?>">
					</span>
					<p class="frm_tips">路径可在模板的页面列表里复制</p>
				</div>
			</div>
			<div class="frm_control_group type4 hideitem" <?php  if($temp['type'] == 'otherapp') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">小程序appid</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="appid" value="<?php  echo $temp['params']['appid'];?>">
					</span>
					<p class="frm_tips">需要跳转的小程序必须关联在同一个公众号下</p>
				</div>
			</div>
			<div class="frm_control_group type4 hideitem" <?php  if($temp['type'] == 'otherapp') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">页面路径</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="appurl" value="<?php  echo $temp['params']['appurl'];?>">
					</span>
				</div>
			</div>			
			<div class="frm_control_group type5 hideitem" <?php  if($temp['type'] == 'toweburl') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">网页链接</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="weburl" value="<?php  echo $temp['params']['weburl'];?>">
					</span>
					<p class="frm_tips">网页链接的域名必须在微信小程序后台设置好业务域名</p>
				</div>
			</div>

			<div class="frm_control_group textarea_item">
				<label for="" class="frm_label">版权内容</label>
				<div class="frm_controls">
					<?php  echo Util::tpl_ueditor('content', htmlspecialchars_decode($temp['content']));?>
					<span class="frm_tips">建议：只设置图片或只设置文字。若设置图片，图片将在前端自动被拉伸成100%屏幕宽度。</span>
				</div>
			</div>
			<div class="tool_bar">
				<input type="submit" name="create" class="btn btn_primary" value="确定" >
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</div>
		</form>
	<?php  } else if($_GPC['op'] == 'ad') { ?>
		<form method="post" action="">
			<div class="frm_control_group">
				<label for="" class="frm_label"></label>
				<div class="frm_controls">
				</div>
			</div>
			<div class="frm_control_group">
	  			<label for="" class="frm_label">广告状态</label>
	   			<div class="frm_controls">
	    			<label class="frm_radio_label">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">关闭</span>
	    				<input type="radio" name="status" value="0" class="frm_radio" <?php  if($temp['status'] == 0) { ?>checked="checked"<?php  } ?> />
	    			</label>
	    			<label class="frm_radio_label" >
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">开启</span>
	    				<input type="radio" name="status" value="1" class="frm_radio" <?php  if($temp['status'] == 1) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	    			<div class="frm_tips">提示：一旦设置，你账户下的所有小程序页面顶部都会显示此广告</div>
	   			</div>
	  		</div>
			<div class="frm_control_group">
	  			<label for="" class="frm_label">点击动作</label>
	   			<div class="frm_controls">
			    	<label class="frm_radio_label show_item hide_item" showitem=".type1" hideitem=".type2,.type3,.type4,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">拨打电话</span>
			    		<input type="radio" name="type" value="tel" class="frm_radio"  <?php  if($temp['type'] == 'tel') { ?>checked="checked"<?php  } ?>  /> 
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type2" hideitem=".type1,.type3,.type4,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">打开图片</span>
			    		<input type="radio" name="type" value="images" class="frm_radio"  <?php  if($temp['type'] == 'images') { ?>checked="checked"<?php  } ?> />
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type3" hideitem=".type1,.type2,.type4,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">跳转页面</span>
			    		<input type="radio" name="type" value="navigateto" class="frm_radio"  <?php  if($temp['type'] == 'navigateto') { ?>checked="checked"<?php  } ?> />
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type4" hideitem=".type1,.type2,.type3,.type5">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">其他小程序</span>
			    		<input type="radio" name="type" value="otherapp" class="frm_radio"  <?php  if($temp['type'] == 'otherapp') { ?>checked="checked"<?php  } ?> />
			    	</label>
			    	<label class="frm_radio_label show_item hide_item" showitem=".type5" hideitem=".type1,.type2,.type3,.type4">
			    		<i class="icon_radio"></i>
			    		<span class="lbl_content">跳转网页</span>
			    		<input type="radio" name="type" value="toweburl" class="frm_radio"  <?php  if($temp['type'] == 'toweburl') { ?>checked="checked"<?php  } ?> />
			    	</label>			    	
	   			</div>
	  		</div>
			<div class="frm_control_group type1 hideitem" <?php  if($temp['type'] == 'tel') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">电话号码</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_200">
						<input type="text" class="frm_input"  name="tel" value="<?php  echo $temp['params']['tel'];?>">
					</span>
				</div>
			</div>
			<div class="frm_control_group type2 single_img_upload hideitem" <?php  if($temp['type'] == 'images') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">图片列表</label>
				<div class="frm_controls good_images">
					<?php  echo WebCommon::tpl_form_field_multi_image('pic', $temp['params']['pic'],'');?>
					<p class="frm_tips">可拖拽图片排序</p>
				</div>
			</div>
			<div class="frm_control_group type3 hideitem" <?php  if($temp['type'] == 'navigateto') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">页面路径</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="url" value="<?php  echo $temp['params']['url'];?>">
					</span>
					<p class="frm_tips">路径可在模板的页面列表里复制</p>
				</div>
			</div>
			<div class="frm_control_group type4 hideitem" <?php  if($temp['type'] == 'otherapp') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">小程序appid</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="appid" value="<?php  echo $temp['params']['appid'];?>">
					</span>
					<p class="frm_tips">需要跳转的小程序必须关联在同一个公众号下</p>
				</div>
			</div>
			<div class="frm_control_group type4 hideitem" <?php  if($temp['type'] == 'otherapp') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">页面路径</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="appurl" value="<?php  echo $temp['params']['appurl'];?>">
					</span>
				</div>
			</div>			
			<div class="frm_control_group type5 hideitem" <?php  if($temp['type'] == 'toweburl') { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">网页链接</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_300">
						<input type="text" class="frm_input"  name="weburl" value="<?php  echo $temp['params']['weburl'];?>">
					</span>
					<p class="frm_tips">网页链接的域名必须在微信小程序后台设置好业务域名</p>
				</div>
			</div>

			<div class="frm_control_group textarea_item">
				<label for="" class="frm_label">广告内容</label>
				<div class="frm_controls">
					<?php  echo tpl_ueditor('content', htmlspecialchars_decode($temp['content']));?>
					<span class="frm_tips">建议：只设置图片或只设置文字。若设置图片，图片将在前端自动被拉伸成100%屏幕宽度。</span>
				</div>
			</div>
			<div class="tool_bar">
				<input type="submit" name="createad" class="btn btn_primary" value="确定" >
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</div>
		</form>

	<?php  } else if($_GPC['op'] == 'mail' ) { ?>

		<form method="post" action="">
			<div class="frm_control_group">
				<label for="" class="frm_label"></label>
				<div class="frm_controls">
				</div>
			</div>
		<div class="frm_control_group " > 
	  		<label for="" class="frm_label">测试发邮件</label> 

			<div class="frm_controls opclass">
				<a class="btn btn_primary drop_down_editbtn" href="javascript:;">发送测试邮件</a>      
                <div class="dropdown_menu_box dropdown_menu dropdown_checkbox has_goods_group" >
                    <div class="dropdown_data_container jsDropdownsList" style="display: none;right: inherit;">
                        <div class="dropdown_data_list" style="">
                            <ul class="drop_down_inputitem">
                                <li class="">接收邮件</li>
                                <li class="">
                                    <input type="text" class="drop_down_input expressname" name="name" value="<?php  echo $order['expressname'];?>">
                                </li>
                            </ul>
                            <div class="dropdown_tool_bar"> 
                                <a href="javascript:;" class="btn js_btn btn_primary usemail" type="email" style="padding:0;">确定</a>
                                <a href="javascript:;" class="btn js_btn btn_default dropdown_edit_cancel" style="padding:0;">取消</a>
                            </div>
                        </div>
                    </div>
                </div>
			</div>

	  	</div>
		<div class="frm_control_group " > 
	  		<label for="" class="frm_label">SMTP服务器</label> 
	   		<div class="frm_controls"> 
	    		<label class="frm_radio_label  " > 
	    			<i class="icon_radio"></i> 
	    			<span class="lbl_content">网易邮箱服务器</span>
	    			<input type="radio" name="type" value="0" class="frm_radio"  <?php  if($temp['type'] == 0) { ?>checked="checked"<?php  } ?> />
	    		</label> 
	    		<label class="frm_radio_label " >
	    			<i class="icon_radio"></i>
	    			<span class="lbl_content">qq邮箱服务器</span>
	    			<input type="radio" name="type" value="1" class="frm_radio" <?php  if($temp['type'] == 1) { ?>checked="checked"<?php  } ?> /> 
	    		</label>	    		
	    		<p class="frm_tips">SMTP服务器为发送邮件的服务器，系统内置了网易和qq的邮件服务器的信息。目前能接收到邮件通知的场景：会员提交表单数据。重要：若你的服务器是腾讯云，请使用QQ邮箱服务器。</p>
	   		</div>
	  	</div>

			<div class="frm_control_group" >
				<label for="" class="frm_label">发送帐号</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="account" value="<?php  echo $temp['account'];?>">
					</span>
					<p class="frm_tips">指定发送邮件的用户名，例如：test@163.com</p>
				</div>
			</div>
			<div class="frm_control_group" >
				<label for="" class="frm_label">smtp客户端授权密码</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="pass" value="<?php  echo $temp['pass'];?>">
					</span>
					<p class="frm_tips">指定发送邮件的密码，
					<a href="http://service.mail.qq.com/cgi-bin/help?subtype=1&amp;&amp;id=28&amp;&amp;no=1001256" target="_Blank" id="code">QQ邮箱获取授权码方法</a>，<a href="http://jingyan.baidu.com/article/495ba841ecc72c38b30ede38.html" target="_Blank" id="code">网易邮箱获取授权码方法</a></p>
				</div>
			</div>
			<div class="frm_control_group" >
				<label for="" class="frm_label">发件人名称</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="name" value="<?php  echo $temp['name'];?>">
					</span>
					<p class="frm_tips">指定发送邮件发信人名称</p>
				</div>
			</div>
			
			<div class="frm_control_group">
				<label for="" class="frm_label">邮件签名</label>
				<div class="frm_controls msg">
					<span class="frm_textarea_box textarea_60px">
						<textarea  name="sign" class="frm_textarea" rows="6" placeholder=""><?php  echo $temp['sign'];?></textarea>
					</span>
					<p class="frm_tips">指定邮件末尾添加的签名信息</p>
				</div>
			</div>
			<div class="tool_bar">
				<input type="submit" name="savemail" class="btn btn_primary" value="保存" >
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</div>
		</form>

	<?php  } else if($_GPC['op'] == 'basic' ) { ?>
		<form method="post" action="">
			<div class="frm_control_group">
				<label for="" class="frm_label"></label>
				<div class="frm_controls">
				</div>
			</div>
			<div class="frm_control_group">
				<label for="" class="frm_label">修复图片链接</label>
				<div class="frm_controls opclass">
					<a class="btn btn_primary drop_down_editbtn" href="javascript:;" >修复图片链接</a>      
                    <div class="dropdown_menu_box dropdown_menu dropdown_checkbox has_goods_group" >
                        <div class="dropdown_data_container jsDropdownsList" style="display: none;right: inherit;">
                            <div class="dropdown_data_list" style="">
                                <ul class="drop_down_inputitem">
                                    <li class="">旧域名</li>
                                    <li class="">
                                        <input type="text" class="drop_down_input expressname" name="oldsite" value="<?php  echo $order['expressname'];?>">
                                    </li>
                                </ul>
                                <ul class="drop_down_inputitem">
                                    <li class="">新域名</li>
                                    <li class="">
                                        <input type="text" class="drop_down_input expressname" name="newsite" value="<?php  echo $order['expressname'];?>">
                                    </li>
                                </ul>
                                <div class="dropdown_tool_bar"> 
                                    <a href="javascript:;" class="btn js_btn btn_primary fixsite" style="padding:0;">确定</a>
                                    <a href="javascript:;" class="btn js_btn btn_default dropdown_edit_cancel" style="padding:0;">取消</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="frm_tips">提示：此功能是当系统换域名后导致页面图片无法显示后可使用此功能修复。若站点图片能正常显示请谨慎使用，否则造成图片无法显示。</p>
				</div>
			</div>
			<div class="frm_control_group " > 
		  		<label for="" class="frm_label">默认权限设置</label> 
		   		<div class="frm_controls"> 
		    		<label class="frm_radio_label  " > 
		    			<i class="icon_radio"></i> 
		    			<span class="lbl_content">只可使用官网功能</span>
		    			<input type="radio" name="deauth" value="0" class="frm_radio"  <?php  if($info['deauth'] == 0) { ?>checked="checked"<?php  } ?> />
		    		</label> 
		    		<label class="frm_radio_label " >
		    			<i class="icon_radio"></i>
		    			<span class="lbl_content">默认小程序可使用所有功能</span>
		    			<input type="radio" name="deauth" value="1" class="frm_radio" <?php  if($info['deauth'] == 1) { ?>checked="checked"<?php  } ?> /> 
		    		</label>	    		
		    		<p class="frm_tips">如果选择第二个，新创建的小程序默认有所有功能</p>
		   		</div>
		  	</div>			
			<div class="frm_control_group " > 
		  		<label for="" class="frm_label">副创始人权限</label> 
		   		<div class="frm_controls"> 
		    		<label class="frm_radio_label  " > 
		    			<i class="icon_radio"></i> 
		    			<span class="lbl_content">不可以分配功能权限</span>
		    			<input type="radio" name="viceisauth" value="0" class="frm_radio"  <?php  if($info['viceisauth'] == 0) { ?>checked="checked"<?php  } ?> />
		    		</label> 
		    		<label class="frm_radio_label " >
		    			<i class="icon_radio"></i>
		    			<span class="lbl_content">可以分配功能权限</span>
		    			<input type="radio" name="viceisauth" value="1" class="frm_radio" <?php  if($info['viceisauth'] == 1) { ?>checked="checked"<?php  } ?> /> 
		    		</label>	    		
		    		<p class="frm_tips">若系统没有副创始人功能可忽略此设置。选择可分配权限后，副创始人可进‘系统设置’-‘权限设置’为他和他的子账户创建的小程序分配功能权限</p>
		   		</div>
		  	</div>
			<div class="frm_control_group " > 
		  		<label for="" class="frm_label">副创始人设置广告</label> 
		   		<div class="frm_controls"> 
		    		<label class="frm_radio_label  " > 
		    			<i class="icon_radio"></i> 
		    			<span class="lbl_content">不可以设置统一顶部广告</span>
		    			<input type="radio" name="isvicead" value="0" class="frm_radio"  <?php  if($info['isvicead'] == 0) { ?>checked="checked"<?php  } ?> />
		    		</label> 
		    		<label class="frm_radio_label " >
		    			<i class="icon_radio"></i>
		    			<span class="lbl_content">可以设置统一顶部广告</span>
		    			<input type="radio" name="isvicead" value="1" class="frm_radio" <?php  if($info['isvicead'] == 1) { ?>checked="checked"<?php  } ?> /> 
		    		</label>	    		
		    		<p class="frm_tips">若开启，创始人可为其下的所有小程序设置统一的顶部广告。建议关闭</p>
		   		</div>
		  	</div>
		  	<div class="frm_control_group " > 
		  		<label for="" class="frm_label">副创始人设置系统模板</label> 
		   		<div class="frm_controls"> 
		    		<label class="frm_radio_label  " > 
		    			<i class="icon_radio"></i> 
		    			<span class="lbl_content">不可以设置普通模板为系统模板</span>
		    			<input type="radio" name="istoseys" value="0" class="frm_radio"  <?php  if($info['istoseys'] == 0) { ?>checked="checked"<?php  } ?> />
		    		</label> 
		    		<label class="frm_radio_label " >
		    			<i class="icon_radio"></i>
		    			<span class="lbl_content">可以设置为系统模板</span>
		    			<input type="radio" name="istoseys" value="1" class="frm_radio" <?php  if($info['istoseys'] == 1) { ?>checked="checked"<?php  } ?> /> 
		    		</label>	    		
		    		<p class="frm_tips">若开启，创始人可将普通模板设置为系统模板</p>
		   		</div>
		  	</div>
		  	<!-- <div class="frm_control_group " > 
		  		<label for="" class="frm_label">开关云模板</label> 
		   		<div class="frm_controls"> 
		    		<label class="frm_radio_label  " > 
		    			<i class="icon_radio"></i> 
		    			<span class="lbl_content">开启</span>
		    			<input type="radio" name="isytemp" value="0" class="frm_radio"  <?php  if($info['isytemp'] == 0) { ?>checked="checked"<?php  } ?> />
		    		</label> 
		    		<label class="frm_radio_label " >
		    			<i class="icon_radio"></i>
		    			<span class="lbl_content">关闭</span>
		    			<input type="radio" name="isytemp" value="1" class="frm_radio" <?php  if($info['isytemp'] == 1) { ?>checked="checked"<?php  } ?> /> 
		    		</label>	    		
		    		<p class="frm_tips">若关闭，只有管理员账户才能查看到云模板功能</p>
		   		</div>
		  	</div> -->
			<div class="tool_bar">
				<input type="submit" name="saveset" class="btn btn_primary" value="保存" >
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</div>
		</form>


	<?php  } else if($_GPC['op'] == 'auth') { ?>
		<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/topbar', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/topbar', TEMPLATE_INCLUDEPATH));?>
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
	            <th class="table_cell tl td_col_2">小程序名称</th>
	            <th class="table_cell tl td_col_5">权限列表</th>
	            <?php  if($_W['role'] == 'founder') { ?>
	            <th class="table_cell tl td_col_1">剩余短信</th>
	            <?php  } ?>
	        </tr> 
	   </thead>
	   <tbody class="tbody" id="js_goods">
	   <form method="post">	
	       	<?php  if(is_array($list)) { foreach($list as $item) { ?>
	            <tr> 
		     		<td class="table_cell title td_col_1"> 
		      			<div class="goods_info">
		      			 	<label class="frm_checkbox_label" > 
		       					<i class="icon_checkbox"></i> 
		       					<input type="checkbox" name="checkall[]" class="frm_checkbox" value="<?php  echo $item['uniacid'];?>" /> 
		       					<?php  echo $item['uniacid'];?>
		       				</label>
		      			</div>
		     		</td>
	                <td class="table_cell price tl td_col_1">
	                    <?php  echo $item['name'];?>
	                </td> 
                    <td class="table_cell price tl td_col_5">
		     			<label class="frm_checkbox_label auth_change" uid="<?php  echo $item['uniacid'];?>" type="isshop">
		     				<i class="icon_checkbox"></i>
		     				<span class="lbl_content">普通商城</span>
		     				<input type="checkbox" class="frm_checkbox" value="1" <?php  if($item['auth']['isshop'] || ( empty( $item['auth'] ) && $set['deauth'] == 1 )) { ?>checked="checked"<?php  } ?>  name="isshop[]" />
		     			</label>
		     			<label class="frm_checkbox_label auth_change" uid="<?php  echo $item['uniacid'];?>" type="isdesk">
		     				<i class="icon_checkbox"></i>
		     				<span class="lbl_content">点餐系统</span>
		     				<input type="checkbox" class="frm_checkbox" value="1" <?php  if($item['auth']['isdesk'] || ( empty( $item['auth'] ) && $set['deauth'] == 1 )) { ?>checked="checked"<?php  } ?>  name="isshop[]" />
		     			</label>
		     			<?php  if($_W['role'] == 'founder') { ?>
		     			<label class="frm_checkbox_label auth_change" uid="<?php  echo $item['uniacid'];?>" type="isclosecopy">
		     				<i class="icon_checkbox"></i>
		     				<span class="lbl_content">关闭版权</span>
		     				<input type="checkbox" class="frm_checkbox" value="1" <?php  if($item['auth']['isclosecopy'] || ( empty( $item['auth'] ) && $set['deauth'] == 1 )) { ?>checked="checked"<?php  } ?>  name="isshop[]" />
		     			</label>
		     			<?php  } ?>
		     			<label class="frm_checkbox_label auth_change" uid="<?php  echo $item['uniacid'];?>" type="isappoint">
		     				<i class="icon_checkbox"></i>
		     				<span class="lbl_content">预约系统</span>
		     				<input type="checkbox" class="frm_checkbox" value="1" <?php  if($item['auth']['isappoint'] || ( empty( $item['auth'] ) && $set['deauth'] == 1 )) { ?>checked="checked"<?php  } ?>  name="isshop[]" />
		     			</label>
		     			<label class="frm_checkbox_label auth_change" uid="<?php  echo $item['uniacid'];?>" type="iscard">
		     				<i class="icon_checkbox"></i>
		     				<span class="lbl_content">卡券系统</span>
		     				<input type="checkbox" class="frm_checkbox" value="1" <?php  if($item['auth']['iscard'] || ( empty( $item['auth'] ) && $set['deauth'] == 1 )) { ?>checked="checked"<?php  } ?>  name="iscard[]" />
		     			</label>		     			
                    </td>
                    <?php  if($_W['role'] == 'founder') { ?>
	                <td class="table_cell price tl td_col_1">
						<div class="opclass">
							<?php  echo $item['auth']['sms'];?> <a class="drop_down_editbtn" href="javascript:;" type="sms">增减</a>      
		                    <div class="dropdown_menu_box dropdown_menu dropdown_checkbox has_goods_group" >
		                        <div class="dropdown_data_container jsDropdownsList" style="display: none;right: 0;">
		                            <div class="dropdown_data_list" style="">
		                                <ul class="drop_down_inputitem">
		                                    <li class="">增减数量，前面加-是减</li>
		                                    <li class="">
		                                        <input type="text" class="drop_down_input expressname" name="num" value="">
		                                    </li>
		                                </ul>
		                                <div class="dropdown_tool_bar"> 
		                                    <a href="javascript:;" class="btn js_btn btn_primary addsms" uid="<?php  echo $item['uniacid'];?>" style="padding:0;">确定</a>
		                                    <a href="javascript:;" class="btn js_btn btn_default dropdown_edit_cancel" style="padding:0;">取消</a>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
						</div>
	                </td>
	                <?php  } ?>
	            </tr>
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
				      				<input name="deleteall" class="alldeal_btn dealauth" act="isshop" value="开通商城权限"  type="button">
				      			</li>
				      			<li class="dropdown_data_item "> 
				      				<input name="deleteall" class="alldeal_btn dealauth"  act="isdesk" value="开通扫码点餐"  type="button">
				      			</li>
				      			<?php  if($_W['role'] == 'founder') { ?>
				      			<li class="dropdown_data_item "> 
				      				<input name="deleteall" class="alldeal_btn dealauth"  act="isclosecopy" value="关闭显示版权"  type="button">
				      			</li>
				      			<?php  } ?>
				      			<li class="dropdown_data_item "> 
				      				<input name="deleteall" class="alldeal_btn dealauth"  act="isappoint" value="开通预约系统"  type="button">
				      			</li>
				      			<li class="dropdown_data_item "> 
				      				<input name="deleteall" class="alldeal_btn dealauth"  act="iscard" value="开通卡券系统"  type="button">
				      			</li>
				      			<li class="dropdown_data_item "> 
				      				<input name="deleteall" class="alldeal_btn dealauth"  act="openall" value="开通所有权限"  type="button">
				      			</li>
				      			<li class="dropdown_data_item "> 
				      				<input name="deleteall" class="alldeal_btn dealauth"  act="closeall" value="关闭所有权限"  type="button">
				      			</li>				      							      			
				    		</ul> 
			    		</div> 
			   		</div>
	  			</div>
			</div>
	        <div class="tr dib item_cell_flex">
	            <?php  echo $pager;?>
	        </div>
	    </div>
	    </form>
	    <p class="frm_tips">提示：勾选后相应的小程序才拥有使用权限</p>


	<?php  } else if($_GPC['op'] == 'tempsort') { ?>

<div class="tr">
	<a href="javascript:;" class="add_form_btn topbar_jsbtn" js="addguysort">添加模板分类</a>
</div>
<?php  if(!empty( $list )) { ?>
  <table class="table" cellspacing="0"> 
   <thead class="thead"> 
    	<tr> 
     		<th class="table_cell title"> 
     			<label class="frm_checkbox_label" for="selectAll"> 
     				<i class="icon_checkbox"></i> 
     				<span class="lbl_content">id</span> 
     				<input type="checkbox" class="frm_checkbox" id="selectAll" /> 
     			</label>
     		</th> 
     		<th class="table_cell tl">分类名称</th>
     		<th class="table_cell tl">排序序号</th>
     		<th class="table_cell tr">操作</th>
    	</tr> 
   </thead> 
   <tbody class="tbody" id="js_goods">
   <form method="post">
	   <?php  if(is_array($list)) { foreach($list as $item) { ?>
	    	<tr> 
	     		<td class="table_cell title"> 
	      			<div class="goods_info">
	      			 	<label class="frm_checkbox_label" > 
	       					<i class="icon_checkbox"></i> 
	       					<input type="checkbox" name="checkall[]" class="frm_checkbox" value="<?php  echo $item['id'];?>" /> 
	       					<?php  echo $item['id'];?>
	       				</label>
	      			</div>
	     		</td> 
	     		<td class="table_cell price tl">
	    			<?php  echo $item['name'];?>
	     		</td>	     		
	     		<td class="table_cell price tl">
	    			<?php  echo $item['number'];?>
	     		</td>	     		     		
			    <td class="table_cell oper last_child tr opclass" style="position: relative;">
			    	<a  href="javascript:;" class="edit_listitem" id="<?php  echo $item['id'];?>">编辑</a>
			    	<a href="<?php  echo self::pwUrl('sysset','copyright',array('op'=>'deletesort','id'=>$item['id']))?>" onclick="return confirm('删除不能恢复，确定要删除吗？');">删除</a>
			    </td>
	    	</tr>
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
		<div class="tr dib item_cell_flex">
			<?php  echo $pager;?>
		</div>
	</div>
		<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
	</form>
<?php  } else { ?>
	<div class="no_data">没有找到数据</div>
<?php  } ?>

<div class="my_model" addguysort style="display: none">
    <div class=" ui-draggable " >
        <div class="dialog">
            <div class="dialog_hd">
                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
            </div>
            <div class="dialog_bd info_box addform_item" >
                
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
		var fid = 0;
		$('.edit_listitem').click(function(){
			var nowfid = $(this).attr('id');
			Http('post','json','findtempsort',{fid:nowfid},function(data){
				if(data.status == 200){
					fid = nowfid; // 防止取消后再添加异常
					$('input[name=name]').val(data.obj.name);
					$('input[name=number]').val(data.obj.number);
					
					//var act = $('.select_item[id="'+data.obj.formtype+'"]');
					//var name = act.text();
					//$('.my_model[addform]').find('.jsBtLabel').text(name);
					$('.my_model[addguysort]').show();
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
			};
			Http('post','json','addtempsort',postdata,function(data){
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

	});
</script>

	<?php  } else if($_GPC['op'] == 'sms' ) { ?>
	
		<form method="post" action="">
			<div class="frm_control_group">
				<label for="" class="frm_label">&nbsp;</label>
				<div class="frm_controls">
					<div>目前使用到短信通知的情况：
						<p>1.通知小程序管理员有会员下单成功。</p>
					</div>
					<p>提示：填好后需要在‘权限设置’内为小程序增加短信数量。小程序的短信数量小于1，无法发送短信通知。</p>
				</div>
			</div>
			<div class="frm_control_group">
				<label for="" class="frm_label">测试通知</label>
				<div class="frm_controls opclass">
					<a class="btn btn_primary drop_down_editbtn" href="javascript:;" type="sms">发送测试短信</a>      
                    <div class="dropdown_menu_box dropdown_menu dropdown_checkbox has_goods_group" >
                        <div class="dropdown_data_container jsDropdownsList" style="display: none;right: inherit;">
                            <div class="dropdown_data_list" style="">
                                <ul class="drop_down_inputitem">
                                    <li class="">手机号码</li>
                                    <li class="">
                                        <input type="text" class="drop_down_input expressname" name="name" value="<?php  echo $order['expressname'];?>">
                                    </li>
                                </ul>
                                <div class="dropdown_tool_bar"> 
                                    <a href="javascript:;" class="btn js_btn btn_primary usemail" type="sms" style="padding:0;">确定</a>
                                    <a href="javascript:;" class="btn js_btn btn_default dropdown_edit_cancel" style="padding:0;">取消</a>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
			</div>			
			<div class="frm_control_group">
				<label for="" class="frm_label">AccessKey</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="smskey" value="<?php  echo $info['key'];?>">
					</span>
					<p class="frm_tips">短信通知是通知到小程序参数设置内设置的管理员手机，填阿里云申请的App Key</p>
				</div>
			</div>
			<div class="frm_control_group">
				<label for="" class="frm_label">AccessSecret</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="smssecret" value="<?php  echo $info['secret'];?>">
					</span>
					<p class="frm_tips">短信通知是通知到小程序参数设置内设置的管理员手机，填阿里云申请的App Secret</p>
				</div>
			</div>
			<div class="frm_control_group">
				<label for="" class="frm_label">短信签名名称</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="smssignature" value="<?php  echo $info['signature'];?>">
					</span>
					<p class="frm_tips">在阿里云后台：短信签名中查看。</p>
				</div>
			</div>
			<div class="frm_control_group">
				<label for="" class="frm_label">订单通知模板ID</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="paysuc" value="<?php  echo $info['template']['paysuc'];?>">
					</span>
					<p class="frm_tips">这个是会员下单成功发给小程序管理员的通知，申请的模板内容设置为：会员下单成功，订单名称：${title}，订单金额：${fee}，配送方式：${type}。请为对方备货。申请后在阿里云后台：短信模板管理中将‘模版CODE’值填入此处。</p>
				</div>
			</div>
			<div class="frm_control_group">
				<label for="" class="frm_label">预约通知模板ID</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="apptem" value="<?php  echo $info['template']['apptem'];?>">
					</span>
					<p class="frm_tips">这个是会员预约成功发给预约项目内填写的管理员手机号的通知，申请的模板内容设置为：客户提交预约信息，预约项目名称：${name}，预约项目内容：${detail}。申请后在阿里云后台：短信模板管理中将‘模版CODE’值填入此处。</p>
				</div>
			</div>

			<div class="tool_bar">
				<input type="submit" name="savesms" class="btn btn_primary" value="保存" >
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</div>
		</form>


	<?php  } ?>
	
	
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH));?>