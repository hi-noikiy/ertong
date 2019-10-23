<?php 
	global $_W,$_GPC;

	$orderdata = model_appoint::orderData();

	$menu = array(
		'index' => 4,
		'name' => '预约系统',
		'p' => 'appoint',
		'do' => 'item',
		'op' => 'list',
		'leftbar' => array(
	  		'item' => array(
	  			'name' => '预约项目',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'add','name'=>'添加项目','url'=> self::pwUrl('appoint','item',array('op'=>'add')) ),
	  				array('op'=>'list','name'=>'项目列表','url'=> self::pwUrl('appoint','item',array('op'=>'list')) ),
	  			),
	  			'toplist' => array('add','list'),
	  		),
	  		'order' => array(
	  			'name' => '预约记录',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'1','name'=>'等待支付','url'=> self::pwUrl('appoint','order',array('op'=>'1')),'showtop'=>1,'num'=>$orderdata['ordering']),
	  				array('op'=>'2','name'=>'等待接单','url'=> self::pwUrl('appoint','order',array('op'=>'2')),'showtop'=>1,'num'=>$orderdata['taking']),
	  				array('op'=>'3','name'=>'已经接单','url'=> self::pwUrl('appoint','order',array('op'=>'3')),'num'=>$orderdata['taked']),
	  				array('op'=>'4','name'=>'预约完成','url'=> self::pwUrl('appoint','order',array('op'=>'4')),'num'=>$orderdata['comed']),
	  				array('op'=>'5','name'=>'取消预约','url'=> self::pwUrl('appoint','order',array('op'=>'5')),'num'=>$orderdata['canceled']),
	  				array('op'=>'6','name'=>'已经退款','url'=> self::pwUrl('appoint','order',array('op'=>'6')),'showtop'=>1,'num'=>$orderdata['refund'])
	  			),
	  			'toplist' => array('6','1','2','3','4','5'),
	  		),
	  		'appsort' => array(
	  			'name' => '预约分类',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'list','name'=>'分类列表','url'=> self::pwUrl('appoint','appsort',array('op'=>'list')) ),
	  			),
	  			'toplist' => array('list'),
	  		),	
	  		'print' => array(
	  			'name' => '打印设备',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'list','name'=>'打印设备','url'=> self::pwUrl('appoint','print',array('op'=>'list')) ),
	  			),
	  			'toplist' => array('list'),
	  		),
	  		'app' => array(
	  			'name' => '前端查看',
	  			'icon' => 'https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_management.png',
	  			'list'=>array(
	  				array('op'=>'list','name'=>'前端查看','url'=> self::pwUrl('appoint','app',array('op'=>'list')) ),
	  			),
	  			'toplist' => array('list'),
	  		),

		),
	);

	return $menu;