<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH));?>


<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp"></script>
<div class="title_tab">
	<ul class="tab_navs title_tab">
		<li class="tab_nav js_top selected" showme="basic_group"><a href="javascript:;">基本参数</a></li>
		<?php  if($auth['isshop'] == 1) { ?>
			<li class="tab_nav js_top " showme="shop_group"><a href="javascript:;">商城参数</a></li>
			<li class="tab_nav js_top " showme="xuan_group"><a href="javascript:;">选购页参数</a></li>
		<?php  } ?>	
		<?php  if($auth['isdesk'] == 1) { ?>
		<li class="tab_nav js_top " showme="food_group"><a href="javascript:;">点餐参数</a></li>
		<?php  } ?>
		<?php  if($auth['isappoint'] == 1) { ?>
		<li class="tab_nav js_top " showme="appoint_group"><a href="javascript:;">预约参数</a></li>
		<?php  } ?>
		<?php  if($auth['isshop'] == 1 || $auth['isdesk'] == 1 || $auth['isappoint'] == 1) { ?>		
			<li class="tab_nav js_top " showme="refund_group"><a href="javascript:;">退款证书</a></li>
		<?php  } ?>
		<?php  if($auth['iscard'] == 1) { ?>
			<li class="tab_nav js_top " showme="card_group"><a href="javascript:;">卡券参数</a></li>
		<?php  } ?>
	</ul>
</div>

<form action="" method="POST">
	<div class="basic_group settings_group">
		<div class="frm_control_group" >
			<label for="" class="frm_label">删除缓存</label>
			<div class="frm_controls">
				<a class="btn btn_primary deletecache"  type="cache" href="javascript:;">删除缓存</a>
				<p class="frm_tips tips_width_200">重装模块后必须删除缓存</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">数据验证码</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="frompass" value="<?php  echo $settings['frompass'];?>">
				</span>
				<p class="frm_tips">输入别人不知道的字符，前端查看表单数据时，需输入此验证码才能查看</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">接收通知邮箱</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="mail" value="<?php  echo $settings['mail'];?>">
				</span>
				<p class="frm_tips">填写你的电子邮箱，接收表单通知等。例如：test@163.com</p>
			</div>
		</div>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">文章列表样式</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">横排(左图右标题)</span>
    				<input type="radio" name="artstyle" value="0" class="frm_radio" <?php  if($settings['artstyle'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">横排(左标题右时间)</span>
    				<input type="radio" name="artstyle" value="1" class="frm_radio" <?php  if($settings['artstyle'] == 1) { ?>checked="checked"<?php  } ?> />
    			</label>

    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">竖排(上图下标题)</span>
    				<input type="radio" name="artstyle" value="2" class="frm_radio" <?php  if($settings['artstyle'] == 2) { ?>checked="checked"<?php  } ?> /> 
    			</label>
   			</div>
  		</div>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">文章显示导航</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">不显示</span>
    				<input type="radio" name="isartfoot" value="0" class="frm_radio" <?php  if($settings['isartfoot'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">显示</span>
    				<input type="radio" name="isartfoot" value="1" class="frm_radio" <?php  if($settings['isartfoot'] == 1) { ?>checked="checked"<?php  } ?> />
    			</label>
   			</div>
  		</div>  
        <div class="frm_control_group" >
            <label for="" class="frm_label">产品列表样式</label>
            <div class="frm_controls">
                <label class="frm_radio_label" >
                    <i class="icon_radio"></i>
                    <span class="lbl_content">横排(左图右标题)</span>
                    <input type="radio" name="prostyle" value="0" class="frm_radio" <?php  if($settings['prostyle'] == 0) { ?>checked="checked"<?php  } ?> />
                </label>
                <label class="frm_radio_label" >
                    <i class="icon_radio"></i>
                    <span class="lbl_content">只显示标题</span>
                    <input type="radio" name="prostyle" value="1" class="frm_radio" <?php  if($settings['prostyle'] == 1) { ?>checked="checked"<?php  } ?> />
                </label>
                <label class="frm_radio_label" >
                    <i class="icon_radio"></i>
                    <span class="lbl_content">竖排(上图下标题)</span>
                    <input type="radio" name="prostyle" value="2" class="frm_radio" <?php  if($settings['prostyle'] == 2) { ?>checked="checked"<?php  } ?> /> 
                </label>
            </div>
        </div>	
		<div class="tool_bar">
			<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>

	</div>

	<div class="shop_group settings_group">
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店名称</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="shopname" value="<?php  echo $settings['shopname'];?>">
				</span>
				<p class="frm_tips">这个参数的作用是会员下单选择上店自提时，提示自提的门店名称</p>
			</div>
		</div>		
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店电话</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="shoptel" value="<?php  echo $settings['shoptel'];?>">
				</span>
				<p class="frm_tips">这个参数的作用是会员下单选择上店自提时，提示自提的门店电话</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店地址</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="shopaddress" value="<?php  echo $settings['shopaddress'];?>">
				</span>
				<p class="frm_tips">这个参数的作用是会员下单选择上店自提时，提示自提的门店地址</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店坐标</label>
			<div class="frm_controls">
				<span class="location">
					<?php  echo $settings['shoplat'];?>，<?php  echo $settings['shoplng'];?>
					<input type="hidden" name="shoplat" value="<?php  echo $settings['shoplat'];?>">
					<input type="hidden" name="shoplng" value="<?php  echo $settings['shoplng'];?>">
				</span>
				<a href="javascript:;" class="showmap" latname="shoplat" lngname="shoplng" >选择</a>
				<p class="frm_tips">点击选择后选择到门店位置</p>
			</div>
		</div>

		<div class="frm_control_group" >
  			<label for="" class="frm_label">商城客服</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label hide_item" hideitem=".kefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="kefutype" value="0" class="frm_radio" <?php  if($settings['kefutype'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item show_item" showitem=".kefutype1" hideitem=".kefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">电话客服</span>
    				<input type="radio" name="kefutype" value="1" class="frm_radio" <?php  if($settings['kefutype'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label hide_item" hideitem=".kefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">客服会话</span>
    				<input type="radio" name="kefutype" value="2" class="frm_radio" <?php  if($settings['kefutype'] == 2) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label hide_item show_item" showitem=".kefutype3" hideitem=".kefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">跳转链接</span>
    				<input type="radio" name="kefutype" value="3" class="frm_radio" <?php  if($settings['kefutype'] == 3) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label hide_item show_item" showitem=".kefutype4" hideitem=".kefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">个人微信</span>
    				<input type="radio" name="kefutype" value="4" class="frm_radio" <?php  if($settings['kefutype'] == 4) { ?>checked="checked"<?php  } ?> /> 
    			</label>
   			</div>
  		</div>

		<div class="frm_control_group hideitem kefutype kefutype1" <?php  if($settings['kefutype'] == 1) { ?>style="display:block"<?php  } ?>>
			<label for="" class="frm_label">电话号码</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="kefutel" value="<?php  echo $settings['kefutel'];?>">
				</span>
				<p class="frm_tips">填写客服电话号码</p>
			</div>
		</div>	
		<div class="frm_control_group hideitem kefutype kefutype3" <?php  if($settings['kefutype'] == 3) { ?>style="display:block"<?php  } ?>>
			<label for="" class="frm_label">网页链接</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="kefuurl" value="<?php  echo $settings['kefuurl'];?>">
				</span>
				<p class="frm_tips">这个网页链接一定要在微信小程序后台设置业务域名</p>
			</div>
		</div>
		<div class="frm_control_group hideitem kefutype single_img_upload kefutype4" <?php  if($settings['kefutype'] == 4) { ?>style="display:block"<?php  } ?>>
			<label for="" class="frm_label">微信二维码</label>
			<div class="frm_controls">
				<?php  echo  WebCommon::tpl_form_field_image('kefuimg',$settings['kefuimg'])?>
				<p class="frm_tips tips_width_200">上传客服微信二维码</p>
			</div>
		</div>	
		<div class="frm_control_group" >
  			<label for="" class="frm_label">下单短信通知</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label show_item" showitem=".paysms">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">开启</span>
    				<input type="radio" name="paysms" value="0" class="frm_radio" <?php  if($settings['paysms'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item"  hideitem=".paysms">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="paysms" value="1" class="frm_radio" <?php  if($settings['paysms'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<p>当前剩余短信数量：<?php  echo $auth['sms'];?>条</p>
   			</div>
  		</div>
		<div class="frm_control_group paysms" <?php  if($settings['paysms'] == 1) { ?>style="display:none"<?php  } ?>>
			<label for="" class="frm_label">接收通知手机号</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="paysuctel" value="<?php  echo $settings['paysuctel'];?>">
				</span>
				<p class="frm_tips">填写你的手机号，会员下单成功会给这个手机发短信通知</p>
			</div>
		</div>
		<?php  if(in_array($_W['siteroot'],array('http://127.0.0.6/','http://wx.zofui.net/','https://wx.zofui.net/')) && $_W['role'] == 'founder' ) { ?>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">分隔商品分类</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">不分隔</span>
    				<input type="radio" name="iscutsort" value="0" class="frm_radio" <?php  if($settings['iscutsort'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">分隔</span>
    				<input type="radio" name="iscutsort" value="1" class="frm_radio" <?php  if($settings['iscutsort'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<p class="frm_tips"> <font class="font_ff5f27">这个参数不用管。</font>分隔商品分类后，可在商品分类内将分类设置到各个模板内，作用是在不同模板内展示和显示不同类型的商品分类和商品。</p>
   			</div>
  		</div>
  		<?php  } ?>

		<div class="frm_control_group" >
  			<label for="" class="frm_label">商品分类页样式</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">同时展示一、二级分类</span>
    				<input type="radio" name="sorttype" value="0" class="frm_radio" <?php  if($settings['sorttype'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">只展示二级分类</span>
    				<input type="radio" name="sorttype" value="1" class="frm_radio" <?php  if($settings['sorttype'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>

    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">标题文字居左</span>
    				<input type="radio" name="titlepos" value="left" class="frm_radio" <?php  if($settings['titlepos'] == 'left') { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">标题文字居中</span>
    				<input type="radio" name="titlepos" value="center" class="frm_radio" <?php  if($settings['titlepos'] == 'center') { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">标题文字居右</span>
    				<input type="radio" name="titlepos" value="right" class="frm_radio" <?php  if($settings['titlepos'] == 'right') { ?>checked="checked"<?php  } ?> /> 
    			</label>  			
   			</div>			
  		</div>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">商品列表页样式</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">显示价格</span>
    				<input type="radio" name="listprice" value="0" class="frm_radio" <?php  if($settings['listprice'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">不显示价格</span>
    				<input type="radio" name="listprice" value="1" class="frm_radio" <?php  if($settings['listprice'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">显示标题</span>
    				<input type="radio" name="listtitle" value="0" class="frm_radio" <?php  if($settings['listtitle'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">不显示标题</span>
    				<input type="radio" name="listtitle" value="1" class="frm_radio" <?php  if($settings['listtitle'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
   			</div>			
  		</div>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">商品详情页样式</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">标题距左</span>
    				<input type="radio" name="infotitle" value="left" class="frm_radio" <?php  if($settings['infotitle'] == 'left') { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">标题距中</span>
    				<input type="radio" name="infotitle" value="center" class="frm_radio" <?php  if($settings['infotitle'] == 'center') { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">显示价格</span>
    				<input type="radio" name="infoprice" value="0" class="frm_radio" <?php  if($settings['infoprice'] == '0') { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">不显示价格</span>
    				<input type="radio" name="infoprice" value="1" class="frm_radio" <?php  if($settings['infoprice'] == '1') { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">显示购买按钮</span>
    				<input type="radio" name="infobuybtn" value="0" class="frm_radio" <?php  if($settings['infobuybtn'] == '0') { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">不显示购买按钮</span>
    				<input type="radio" name="infobuybtn" value="1" class="frm_radio" <?php  if($settings['infobuybtn'] == '1') { ?>checked="checked"<?php  } ?> /> 
    			</label>
   			</div>			
  		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">营业时间</label>
			<div class="frm_controls">
				<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="shopsh" value="<?php  echo $settings['shopsh'];?>">
				</span>时，<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="shopsm" value="<?php  echo $settings['shopsm'];?>">
				</span>分至
				<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="shopeh" value="<?php  echo $settings['shopeh'];?>">
				</span>时，<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="shopem" value="<?php  echo $settings['shopem'];?>">
				</span>分
				<p class="frm_tips">根据提示填，数字。不在范围内商城不能下单。以24小时制算。例如分别填8 30 20 30,那么营业时间是早上8点半到晚上20点半。都填0或不填，那么不做限制。</p>
			</div>
		</div>
        <?php  if(in_array( $_W['account']['key'] , array('wx62d27e699793d000','wx652ae1685089f995'))) { ?>
        <div class="frm_control_group" >
            <label for="" class="frm_label">律师</label>
            <div class="frm_controls">
                <label class="frm_radio_label">
                    <i class="icon_radio"></i>
                    <span class="lbl_content">否</span>
                    <input type="radio" name="lawyer" value="0" class="frm_radio" <?php  if($settings['lawyer'] == 0) { ?>checked="checked"<?php  } ?> />
                </label>
                <label class="frm_radio_label" >
                    <i class="icon_radio"></i>
                    <span class="lbl_content">是</span>
                    <input type="radio" name="lawyer" value="1" class="frm_radio" <?php  if($settings['lawyer'] == 1) { ?>checked="checked"<?php  } ?> /> 
                </label>
                
            </div>          
        </div>
        <?php  } ?>
		<div class="tool_bar">
			<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>

	</div>

	<div class="xuan_group settings_group">
		<div class="frm_control_group single_img_upload">
			<label for="" class="frm_label">门店头像</label>
			<div class="frm_controls">
				<?php  echo  WebCommon::tpl_form_field_image('shophead',$settings['shophead'])?>
			</div>
		</div>		
		<div class="frm_control_group single_img_upload">
			<label for="" class="frm_label">门店图片</label>
			<div class="frm_controls good_images">
				<?php  echo WebCommon::tpl_form_field_multi_image('shoppic', $settings['shoppic'],'');?>
				<p class="frm_tips">可拖拽图片排序</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">起送金额</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="leastmoney" value="<?php  echo $settings['leastmoney'];?>">
				</span>
				<p class="frm_tips">这个参数在快速选购界面下单才有效，作用：餐馆、超市等门店设置起送金额限制底金额订单，设置成0不限制</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">配送费用</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="sendmoney" value="<?php  echo $settings['sendmoney'];?>">
				</span>
				<p class="frm_tips">这个参数在快速选购界面下单才有效，作用：餐馆、超市等门店设置每次配送费用，设置成0无配送费</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">免配送费条件</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="freesend" value="<?php  echo $settings['freesend'];?>">
				</span>
				<p class="frm_tips">这个参数在快速选购界面下单才有效，当订单金额大于等于此数值，减免配送费，设置成0不免配送费</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">送达时间</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="sendtime" value="<?php  echo $settings['sendtime'];?>">
				</span>
				<p class="frm_tips">这个参数在快速选购界面顶部展示，单位/分钟</p>
			</div>
		</div>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">货到付款</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label show_item" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="sendedpay" value="0" class="frm_radio" <?php  if($settings['sendedpay'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item"  >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">开启</span>
    				<input type="radio" name="sendedpay" value="1" class="frm_radio" <?php  if($settings['sendedpay'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<p class="frm_tips">这个参数在快速选购界面下单才有效，开启后可以选择货到付款</p>
   			</div>
  		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">营业时间</label>
			<div class="frm_controls">
				<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="xuansh" value="<?php  echo $settings['xuansh'];?>">
				</span>时，<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="xuansm" value="<?php  echo $settings['xuansm'];?>">
				</span>分至
				<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="xuaneh" value="<?php  echo $settings['xuaneh'];?>">
				</span>时，<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="xuanem" value="<?php  echo $settings['xuanem'];?>">
				</span>分
				<p class="frm_tips">根据提示填，数字。不在范围内不能选购(包括扫码点餐)。以24小时制算。例如分别填8 30 20 30,那么营业时间是早上8点半到晚上20点半。都填0或不填，那么不做限制。</p>
			</div>
		</div>
		<div class="tool_bar">
			<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</div>

	<div class="food_group settings_group">

		<div class="frm_control_group single_img_upload">
			<label for="" class="frm_label">门店头像</label>
			<div class="frm_controls">
				<?php  echo  WebCommon::tpl_form_field_image('oshophead',$settings['oshophead'])?>
			</div>
		</div>
		<div class="frm_control_group single_img_upload">
			<label for="" class="frm_label">门店图片</label>
			<div class="frm_controls good_images">
				<?php  echo WebCommon::tpl_form_field_multi_image('oshoppic', $settings['oshoppic'],'');?>
				<p class="frm_tips">可拖拽图片排序</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店名称</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="oshopname" value="<?php  echo $settings['oshopname'];?>">
				</span>
				<p class="frm_tips"></p>
			</div>
		</div>		
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店电话</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="oshoptel" value="<?php  echo $settings['oshoptel'];?>">
				</span>
				<p class="frm_tips"></p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店地址</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="oshopaddress" value="<?php  echo $settings['oshopaddress'];?>">
				</span>
				<p class="frm_tips"></p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">门店坐标</label>
			<div class="frm_controls">
				<span class="location">
					<?php  echo $settings['oshoplat'];?>，<?php  echo $settings['oshoplng'];?>
					<input type="hidden" name="oshoplat" value="<?php  echo $settings['oshoplat'];?>">
					<input type="hidden" name="oshoplng" value="<?php  echo $settings['oshoplng'];?>">
				</span>
				<a href="javascript:;" class="showmap" latname="oshoplat" lngname="oshoplng" >选择</a>
				<p class="frm_tips">点击选择后选择到门店位置</p>
			</div>
		</div>

		<div class="frm_control_group" >
			<label for="" class="frm_label">起送金额</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="oleastmoney" value="<?php  echo $settings['oleastmoney'];?>">
				</span>
				<p class="frm_tips">作用：餐馆、超市等门店设置起送金额限制底金额订单，设置成0不限制</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">配送费用</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="osendmoney" value="<?php  echo $settings['osendmoney'];?>">
				</span>
				<p class="frm_tips">作用：餐馆、超市等门店设置每次配送费用，设置成0无配送费</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">免配送费条件</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="ofreesend" value="<?php  echo $settings['ofreesend'];?>">
				</span>
				<p class="frm_tips">当订单金额大于等于此数值，减免配送费，设置成0不免配送费</p>
			</div>
		</div>
		<div class="frm_control_group" >
			<label for="" class="frm_label">送达时间</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="osendtime" value="<?php  echo $settings['osendtime'];?>">
				</span>
				<p class="frm_tips">这个参数在界面顶部展示，单位/分钟</p>
			</div>
		</div>

		<div class="frm_control_group" >
  			<label for="" class="frm_label">商城客服</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label hide_item" hideitem=".okefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="okefutype" value="0" class="frm_radio" <?php  if($settings['okefutype'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item show_item" showitem=".okefutype1" hideitem=".okefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">电话客服</span>
    				<input type="radio" name="okefutype" value="1" class="frm_radio" <?php  if($settings['okefutype'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label hide_item" hideitem=".okefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">客服会话</span>
    				<input type="radio" name="okefutype" value="2" class="frm_radio" <?php  if($settings['okefutype'] == 2) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label hide_item show_item" showitem=".okefutype3" hideitem=".okefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">跳转链接</span>
    				<input type="radio" name="okefutype" value="3" class="frm_radio" <?php  if($settings['okefutype'] == 3) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<label class="frm_radio_label hide_item show_item" showitem=".okefutype4" hideitem=".okefutype">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">个人微信</span>
    				<input type="radio" name="okefutype" value="4" class="frm_radio" <?php  if($settings['okefutype'] == 4) { ?>checked="checked"<?php  } ?> /> 
    			</label>
   			</div>
  		</div>

		<div class="frm_control_group hideitem okefutype okefutype1" <?php  if($settings['okefutype'] == 1) { ?>style="display:block"<?php  } ?>>
			<label for="" class="frm_label">电话号码</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="okefutel" value="<?php  echo $settings['okefutel'];?>">
				</span>
				<p class="frm_tips">填写客服电话号码</p>
			</div>
		</div>
		<div class="frm_control_group hideitem okefutype okefutype3" <?php  if($settings['okefutype'] == 3) { ?>style="display:block"<?php  } ?>>
			<label for="" class="frm_label">网页链接</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="okefuurl" value="<?php  echo $settings['okefuurl'];?>">
				</span>
				<p class="frm_tips">这个网页链接一定要在微信小程序后台设置业务域名</p>
			</div>
		</div>
		<div class="frm_control_group hideitem okefutype single_img_upload okefutype4" <?php  if($settings['okefutype'] == 4) { ?>style="display:block"<?php  } ?>>
			<label for="" class="frm_label">微信二维码</label>
			<div class="frm_controls">
				<?php  echo  WebCommon::tpl_form_field_image('okefuimg',$settings['okefuimg'])?>
				<p class="frm_tips tips_width_200">上传客服微信二维码</p>
			</div>
		</div>	
		<div class="frm_control_group" >
  			<label for="" class="frm_label">下单短信通知</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label show_item" showitem=".opaysms">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">开启</span>
    				<input type="radio" name="opaysms" value="0" class="frm_radio" <?php  if($settings['opaysms'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item"  hideitem=".opaysms">
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="opaysms" value="1" class="frm_radio" <?php  if($settings['opaysms'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<p>当前剩余短信数量：<?php  echo $auth['sms'];?>条</p>
   			</div>
  		</div>
		<div class="frm_control_group opaysms" <?php  if($settings['opaysms'] == 1) { ?>style="display:none"<?php  } ?>>
			<label for="" class="frm_label">接收通知手机号</label>
			<div class="frm_controls">
				<span class="frm_input_box">
					<input type="text" class="frm_input" name="opaysuctel" value="<?php  echo $settings['opaysuctel'];?>">
				</span>
				<p class="frm_tips">填写你的手机号，会员下单成功会给这个手机发短信通知</p>
			</div>
		</div>


		<div class="frm_control_group" >
			<label for="" class="frm_label">营业时间</label>
			<div class="frm_controls">
				<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="oshopsh" value="<?php  echo $settings['oshopsh'];?>">
				</span>时，<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="oshopsm" value="<?php  echo $settings['oshopsm'];?>">
				</span>分至
				<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="oshopeh" value="<?php  echo $settings['oshopeh'];?>">
				</span>时，<span class="frm_input_box frm_input_box_50">
					<input type="text" class="frm_input" name="oshopem" value="<?php  echo $settings['oshopem'];?>">
				</span>分
				<p class="frm_tips">根据提示填，数字。不在范围内商城不能下单。以24小时制算。例如分别填8 30 20 30,那么营业时间是早上8点半到晚上20点半。都填0或不填，那么不做限制。</p>
			</div>
		</div>

		<div class="frm_control_group" >
  			<label for="" class="frm_label">当面付款</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label show_item" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="osendedpay" value="0" class="frm_radio" <?php  if($settings['osendedpay'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item"  >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">开启</span>
    				<input type="radio" name="osendedpay" value="1" class="frm_radio" <?php  if($settings['osendedpay'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<p class="frm_tips">开启后可以选择送达后当面付款</p>
   			</div>
  		</div>
		<div class="frm_control_group" >
  			<label for="" class="frm_label">餐后付款</label>
   			<div class="frm_controls">
    			<label class="frm_radio_label show_item" >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">关闭</span>
    				<input type="radio" name="afterpay" value="0" class="frm_radio" <?php  if($settings['afterpay'] == 0) { ?>checked="checked"<?php  } ?> />
    			</label>
    			<label class="frm_radio_label hide_item"  >
    				<i class="icon_radio"></i>
    				<span class="lbl_content">开启</span>
    				<input type="radio" name="afterpay" value="1" class="frm_radio" <?php  if($settings['afterpay'] == 1) { ?>checked="checked"<?php  } ?> /> 
    			</label>
    			<p class="frm_tips">这个参数在扫码点餐支付时候才有效，开启后确认支付时可选择餐后付款</p>
   			</div>
  		</div>		
		<div class="tool_bar">
			<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</div>

		<div class="appoint_group settings_group">
			<div class="frm_control_group" >
	  			<label for="" class="frm_label">客服</label>
	   			<div class="frm_controls">
	    			<label class="frm_radio_label hide_item" hideitem=".apkefutype">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">关闭</span>
	    				<input type="radio" name="apkefutype" value="0" class="frm_radio" <?php  if($settings['apkefutype'] == 0) { ?>checked="checked"<?php  } ?> />
	    			</label>
	    			<label class="frm_radio_label hide_item show_item" showitem=".apkefutype1" hideitem=".apkefutype">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">电话客服</span>
	    				<input type="radio" name="apkefutype" value="1" class="frm_radio" <?php  if($settings['apkefutype'] == 1) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	    			<label class="frm_radio_label hide_item" hideitem=".apkefutype">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">客服会话</span>
	    				<input type="radio" name="apkefutype" value="2" class="frm_radio" <?php  if($settings['apkefutype'] == 2) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	    			<label class="frm_radio_label hide_item show_item" showitem=".apkefutype3" hideitem=".apkefutype">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">跳转链接</span>
	    				<input type="radio" name="apkefutype" value="3" class="frm_radio" <?php  if($settings['apkefutype'] == 3) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	    			<label class="frm_radio_label hide_item show_item" showitem=".apkefutype4" hideitem=".apkefutype">
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">个人微信</span>
	    				<input type="radio" name="apkefutype" value="4" class="frm_radio" <?php  if($settings['apkefutype'] == 4) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	   			</div>
	  		</div>

			<div class="frm_control_group hideitem apkefutype apkefutype1" <?php  if($settings['apkefutype'] == 1) { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">电话号码</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="apkefutel" value="<?php  echo $settings['apkefutel'];?>">
					</span>
					<p class="frm_tips">填写客服电话号码</p>
				</div>
			</div>	
			<div class="frm_control_group hideitem apkefutype apkefutype3" <?php  if($settings['apkefutype'] == 3) { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">网页链接</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="apkefuurl" value="<?php  echo $settings['apkefuurl'];?>">
					</span>
					<p class="frm_tips">这个网页链接一定要在微信小程序后台设置业务域名</p>
				</div>
			</div>
			<div class="frm_control_group hideitem apkefutype single_img_upload apkefutype4" <?php  if($settings['apkefutype'] == 4) { ?>style="display:block"<?php  } ?>>
				<label for="" class="frm_label">微信二维码</label>
				<div class="frm_controls">
					<?php  echo  WebCommon::tpl_form_field_image('apkefuimg',$settings['apkefuimg'])?>
					<p class="frm_tips tips_width_200">上传客服微信二维码</p>
				</div>
			</div>
			<div class="frm_control_group" >
	  			<label for="" class="frm_label">是否自动接单</label>
	   			<div class="frm_controls">
	    			<label class="frm_radio_label" >
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">不自动接单</span>
	    				<input type="radio" name="isautotake" value="0" class="frm_radio" <?php  if($settings['isautotake'] == 0) { ?>checked="checked"<?php  } ?> />
	    			</label>
	    			<label class="frm_radio_label" >
	    				<i class="icon_radio"></i>
	    				<span class="lbl_content">自动接单</span>
	    				<input type="radio" name="isautotake" value="1" class="frm_radio" <?php  if($settings['isautotake'] == 1) { ?>checked="checked"<?php  } ?> /> 
	    			</label>
	    			<p class="frm_tips tips_width_200">设置自动接单后，前端提交的预约会自动变成已接单状态</p>
	   			</div>
	  		</div>			

			<div class="tool_bar">
				<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>			
		</div>	

		<div class="card_group settings_group">
			

			<div class="frm_control_group" >
				<label for="" class="frm_label">卡券页背景颜色</label>
				<div class="frm_controls">
					<span class="frm_input_box frm_input_box_100">
						<input class="frm_input" type="color" name="cardbg" value="<?php  echo $settings['cardbg'];?>" />
					</span>
				</div>
			</div>
			<div class="frm_control_group" >
				<label for="" class="frm_label">门店名称</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="cardname" value="<?php  echo $settings['cardname'];?>">
					</span>
					<p class="frm_tips">展示在卡券内的名称</p>
				</div>
			</div>	
			<div class="frm_control_group single_img_upload ">
				<label for="" class="frm_label">门店头像</label>
				<div class="frm_controls">
					<?php  echo  WebCommon::tpl_form_field_image('cardimg',$settings['cardimg'])?>
					<p class="frm_tips tips_width_200">展示在卡券内的头像</p>
				</div>
			</div>
			<div class="frm_control_group" >
				<label for="" class="frm_label">门店电话</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="cardtel" value="<?php  echo $settings['cardtel'];?>">
					</span>
				</div>
			</div>
			<div class="frm_control_group" >
				<label for="" class="frm_label">门店地址</label>
				<div class="frm_controls">
					<span class="frm_input_box">
						<input type="text" class="frm_input" name="cardaddress" value="<?php  echo $settings['cardaddress'];?>">
					</span>
				</div>
			</div>
			<div class="frm_control_group" >
				<label for="" class="frm_label">门店坐标</label>
				<div class="frm_controls">
					<span class="location">
						<?php  echo $settings['cardlat'];?>，<?php  echo $settings['cardlng'];?>
						<input type="hidden" name="cardlat" value="<?php  echo $settings['cardlat'];?>">
						<input type="hidden" name="cardlng" value="<?php  echo $settings['cardlng'];?>">
					</span>
					<a href="javascript:;" class="showmap" latname="cardlat" lngname="cardlng" >选择</a>
					<p class="frm_tips">点击选择后选择门店位置</p>
				</div>
			</div>
					

			<div class="tool_bar">
				<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>			
		</div>	

	<div class="refund_group settings_group">
		<div class="frm_control_group">
			<label for="" class="frm_label">cert证书</label>
			<div class="frm_controls">
				<span class="frm_textarea_box">
					<textarea  name="cert" class="frm_textarea" rows="6" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接粘贴"></textarea>
				</span>
				<p class="frm_tips">从商户平台上下载支付证书, 解压并取得其中的 apiclient_cert.pem 用记事本打开并复制文件内容, 填至此处</p>
			</div>
		</div>
		<div class="frm_control_group">
			<label for="" class="frm_label">key证书</label>
			<div class="frm_controls">
				<span class="frm_textarea_box">
					<textarea  name="key" class="frm_textarea" rows="6" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接粘贴"></textarea>
				</span>
				<p class="frm_tips">从商户平台上下载支付证书, 解压并取得其中的 apiclient_key.pem 用记事本打开并复制文件内容, 填至此处</p>
			</div>
		</div>
		<div class="frm_control_group">
			<label for="" class="frm_label">rootca证书</label>
			<div class="frm_controls">
				<span class="frm_textarea_box">
					<textarea  name="rootca" class="frm_textarea" rows="6" placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接粘贴"></textarea>
				</span>
				<p class="frm_tips">没有可不填。从商户平台上下载支付证书, 解压并取得其中的 rootca.pem 用记事本打开并复制文件内容, 填至此处</p>
			</div>
		</div>

		<div class="tool_bar">
			<button type="submit" name="submit" value="123" class="btn btn_primary" >保存</button>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>

	</div>

	<div class="my_model" map style="display: none">
	    <div class=" ui-draggable " >
	        <div class="dialog">
	            <div class="dialog_hd">
	                <a href="javascript:;" class="icon16_opr closed pop_closed model_close" >关闭</a>
	            </div>
	            <div class="dialog_bd info_box" >
	            	<div class="font_mini">左键点击位置出现标记，点击确定即可</div>
					<div class="map_box" style="margin: 0 auto;">
						<!-- <div class="map_search">
							<span class="frm_input_box frm_input_box_100">
								<input type="text" class="frm_input"  name="searaddress" value="">
							</span><a href="javascript:;" id="find_address">搜索</a>
						</div>
						<div class="baidu_map" id="map"></div> -->
						<div class="map_search">
							<span class="frm_input_box frm_input_box_100">
								<input type="text" class="frm_input"  name="searaddress" id="searaddress" value="">
							</span><a href="javascript:;" id="find_address">搜索</a>
						</div>
						<div class="baidu_map" id="map"></div>
					</div>
	            </div>
	            <div class="dialog_ft">
	            	<span class="btn btn_primary btn_input js_btn_p" >
	            		<button type="button" class="js_btn setLocation">确定</button>
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
		Http('post','json','checkqrcode',{page:'bindadmin'},function(data){
			if( data.status == 201 ){
				$('.qrcode_tips').text(data.res);
			}
		},false);
	</script>
</form>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH));?>