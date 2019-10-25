<?php 
	global $_W,$_GPC;


	$menu = array(
		'index' => 4,
		'name' => '卡券系统',
		'p' => 'card',
		'do' => 'card',
		'op' => 'list',
		'leftbar' => array(
	  		'card' => array(
	  			'name' => '卡券管理',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'add','name'=>'添加卡券','url'=> self::pwUrl('card','card',array('op'=>'add')) ),
	  				array('op'=>'list','name'=>'卡券列表','url'=> self::pwUrl('card','card',array('op'=>'list')) ),
	  			),
	  			'toplist' => array('add','list'),
	  		),
	  		'uselog' => array(
	  			'name' => '使用记录',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'taked','name'=>'还未使用','url'=> self::pwUrl('card','uselog',array('op'=>'taked'))),
	  				array('op'=>'used','name'=>'已经使用','url'=> self::pwUrl('card','uselog',array('op'=>'used'))),
	  				array('op'=>'waitcheck','name'=>'等待核销','url'=> self::pwUrl('card','uselog',array('op'=>'waitcheck'))),
	  				array('op'=>'passed','name'=>'已经过期','url'=> self::pwUrl('card','uselog',array('op'=>'passed'))),
	  			),
	  			'toplist' => array('taked','waitcheck','used','passed'),
	  		),
	  		'admin' => array(
	  			'name' => '核销人员',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'list','name'=>'人员列表','url'=> self::pwUrl('card','admin',array('op'=>'list')) ),
	  			),
	  			'toplist' => array('list'),
	  		),
		),
	);

	return $menu;