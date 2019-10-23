<?php 
	global $_W,$_GPC;

	$menu = array(
		'index' => 12,
		'name' => '使用说明',
		'do' => 'help',
		'p' => 'help',
		'op' => 'wqa',
		'leftbar' => array(
		  	'help' => array(
		  		'name' => '帮助系统',
		  		'p' => 'sysset',
		  		'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_statistics.png',
		  		'list'=>array(
		  			array('op'=>'wqa','name'=>'常见问题','url'=>self::pwUrl('help','help',array('op'=>'wqa')) ),
		  			array('op'=>'verify','name'=>'配置检测','url'=>self::pwUrl('help','help',array('op'=>'verify')) ),
		  			array('op'=>'step','name'=>'设置步骤','url'=>self::pwUrl('help','help',array('op'=>'step')) ),
		  			array('op'=>'url','name'=>'页面路径','url'=>self::pwUrl('help','help',array('op'=>'url')) ),
		  		),
		  		'toplist' => array()
		  	),

		),
	);

	return $menu;