<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myheader', TEMPLATE_INCLUDEPATH));?>
	<?php  if($_GPC['op'] == 'wqa') { ?>
<style type="text/css">
.step_item{
	line-height: 30px;
	margin-bottom: 10px;
}
.tip_title{
	font-size: 18px;
	font-weight: 700;
}
</style>		
		<div class="step_item">
			<div class="tip_title">注意事项</div>
			<div>一、第一个页脚导航链接的页面将作为进入小程序后的展现页面。</div>
			<div>二、富文本编辑模块内只能使用文字和图片功能，不能添加视频、音频、表情、超链接等特殊的编辑内容。</div>
		</div>
		<div class="step_item">
			<div class="tip_title">一、为什么添加模板后打开页面是空白的？</div>
			<div>1:可能你的小程序后台没有设置域名，在小程序后台-设置-开发设置内绑定此站点域名。</div>
			<div>2:可能你的小程序后台配置的域名和代码内的域名不一致，保持此站点域名和小程序后台配置的域名一致。</div>
			<div>3:可能你的域名没有配置https，请自行百度配置https。</div>
			<div>4:可能你设置的模板没有设置导航，请重新设置导航，把第一个导航设置成首页链接。</div>
			<div>5:还出现空白的情况请换个https证书试下，或过段时间再试下。</div>

			<div>
				<a href="<?php  echo self::pwUrl('help','help',array('op'=>'verify','tid'=>$issetact['id']))?>">点击查看配置检测结果</a>
			</div>

		</div>
		<div class="step_item">
			<div class="tip_title">二、什么是系统模板？</div>
			<div>系统模板是平台预先设置的、各个小程序共用的页面模板，在系统模板内选择各个模板可导出到我的模板内，即可在我的模板内使用编辑模板。</div>
		</div>
		<?php  if($_W['role'] == 'founder') { ?>
			<div class="step_item">
				<div class="tip_title">三、什么是模块模板？</div>
				<div>模块模板是本模块自带的模板，只有管理员账户才能看到模块模板，而且可以在模块模板内将模板设为系统模板供各个小程序使用。</div>
			</div>
			<div class="step_item">
				<div class="tip_title">四、系统设置的作用是什么？</div>
				<div>系统设置只有站点管理员账户才能显示和使用，是设置所有小程序共用的参数设置和对小程序进行权限控制。</div>
			</div>
			
		<?php  } ?>
	<?php  } else if($_GPC['op'] == 'verify') { ?>

		<div class="panel_box">
			<div class="panel_title">1、https检测</div>
			<div class="panel_item">
				<div>
					<?php  if($httpsres['status'] == 200) { ?>
						<?php  echo $httpsres['res'];?>
					<?php  } else { ?>
						<?php  echo $httpsres['res'];?>
					<?php  } ?>
				</div>
			</div>
		</div>
		<div class="panel_box">
			<div class="panel_title">2、接口域名检测</div>
			<div class="panel_item">
				<div>
					<?php  if($appurlres['status'] == 200) { ?>
						<?php  echo $appurlres['res'];?>
					<?php  } else { ?>
						<?php  echo $appurlres['res'];?>
					<?php  } ?>
				</div>
			</div>
		</div>
		<div class="panel_box">
			<div class="panel_title">3、模板首页检测</div>
			<div class="panel_item">
				<div>
					<?php  if($tempres['status'] == 200) { ?>
						<?php  echo $tempres['res'];?>
					<?php  } else { ?>
						<?php  echo $tempres['res'];?>
					<?php  } ?>
				</div>
			</div>
		</div>
		<div class="panel_box">
			<div class="panel_title">4、服务器域名配置检测</div>
			<div class="panel_item">
				<div>
					<div>此步骤需要手动检查</div>
					<div>登录微信小程序后台-设置-开发设置-服务器域名中将域名设置成<?php  echo $domain;?>(若不使用此域名请忽略此提示)</div>
					<div>
						<img width="100%" src="../addons/zofui_sitetemp/public/images/verify1.png">
					</div>
				</div>
			</div>
		</div>

	<?php  } else if($_GPC['op'] == 'step') { ?>

	<div class="intro od_msg item_cell_box">
		<div>后台配置步骤</div>
		<div class="item_cell_flex" style="margin-left: 40px">
			<div class="step_item">
				<div>1、在模板管理内添加一个模板</div>
				<div>
					<img width="700px" src="../addons/zofui_sitetemp/public/images/step1.png">
				</div>
			</div>
			<div class="step_item">
				<div>2、添加模板后，点击模板设置使用模板</div>
				<div>
					<img width="700px" src="../addons/zofui_sitetemp/public/images/step2.png">
				</div>
			</div>
			<div class="step_item">
				<div>3、点击模板下方的添加页面，添加各个页面</div>
				<div>
					<img width="700px" src="../addons/zofui_sitetemp/public/images/step3.png">
				</div>
			</div>
			<div class="step_item">
				<div>4、添加好各个页面后，点击设置导航，设置好模板的导航，设置完成后后台配置就完成了。</div>
				<div>
					<img width="700px" src="../addons/zofui_sitetemp/public/images/step4.png">
				</div>
			</div>		
		</div>
	</div>

	<?php  } else if($_GPC['op'] == 'url') { ?>
	  <table class="table" cellspacing="0"> 
	   <thead class="thead"> 
	    	<tr>  
	     		<th class="table_cell tl td_col_1">页面名称</th> 
	     		<th class="table_cell tl td_col_1">路径</th> 
	     		<th class="table_cell tr td_col_1">操作</th>
	    	</tr> 
	   </thead> 
	   <tbody class="tbody" id="js_goods">
	   <form method="post">
		   <?php  if(is_array($urlarr)) { foreach($urlarr as $item) { ?>
		    	<tr> 	    		
		    		<td class="table_cell tl td_col_1">
		    			<?php  echo $item['name'];?>
		    		</td>
		    		<td class="table_cell tl td_col_1">
		    			<?php  echo $item['url'];?>
		    		</td>		    			    		
				    <td class="td_col_1 table_cell oper last_child tr" style="position: relative;">
				    	<a href="javascript:;" class="copy_url" data-href="<?php  echo $item['url'];?>">复制路径</a>
				    </td>
		    	</tr>
		    <?php  } } ?>
	   	</tbody> 
	  	</table>
	<?php  } ?>
	
	
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH)) : (include template('../../../addons/'.MODULE.'/template/web/common/myfooter', TEMPLATE_INCLUDEPATH));?>