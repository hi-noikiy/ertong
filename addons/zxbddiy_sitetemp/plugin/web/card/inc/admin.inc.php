<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	
	
	// 管理员
	$admin = pdo_getall('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'type'=>1));

	if( !empty( $admin ) ) {
		foreach ( $admin as &$v ) {
			$v['user'] = model_user::getSingleUser($v['openid']);
		}
	}
	
	
	include $this->pTemplate('admin');
