<?php 
	global $_GPC,$_W;

	$menu = array(
		'index' => 1,
		'name' => '参数设置',
		'do' => 'set',
		'p' => 'set',
		'op' => 'basic',
		'leftbar' => array(
		  	'set' => array(
		  		'name' => '参数设置',
		  		'p' => 'set',
		  		'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_setup.png',
		  		'list'=>array(
		  			array('op'=>'basic','name'=>'参数设置','url'=>self::pwUrl('set','set',array('op'=>'basic')) ),
		  		),
		  		'toplist' => array()
		  	),
		),
	);

	return $menu;