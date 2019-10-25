<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	
	
	if( empty( $_W['openid'] ) ) $this->result(2,'您的会员数据不存在');
	

	if( $_W['ispost'] && $_GPC['op'] == 'check' ) {
		
		if( empty( $_GPC['scene'] ) ) $this->result(2,'已过期,请重新扫码');
		
		$code = Util::getCache('adminkey','all');
		if( empty( $code ) || $code['t'] < ( TIMESTAMP - 1800 ) ) {
			$this->result(2,'二维码已过期');
		}
		$type = intval( $code['plug'] );

		if( $_GPC['scene'] != $code['code'] ) $this->result(2,'二维码已过期');

		$isset = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>$type));
		if( !empty( $isset ) ) $this->result(2,'您已经绑定过了');

	}elseif( $_W['ispost'] && $_GPC['op'] == 'bind' ){

		if( empty( $_GPC['scene'] ) ) $this->result(2,'已过期,请重新扫码');
		
		$code = Util::getCache('adminkey','all');
		if( empty( $code ) || $code['t'] < ( TIMESTAMP - 1800 ) ) {
			$this->result(2,'二维码已过期');
		}

		if( $_GPC['scene'] != $code['code'] ) $this->result(2,'二维码已过期');
		$type = intval( $code['plug'] );

		$isset = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>$type));
		if( !empty( $isset ) ) $this->result(1,'您已经绑定过了');

		$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'type'=> $code['plug'],
		);
		$res = pdo_insert('zxbddiy_sitetemp_admin',$data);
		
		if( $res ) {
			$this->result(0, '已绑定');
			Util::deleteCache('adminkey','all');
		}
		$this->result(1, '绑定失败,请扫码重试');
	}


	