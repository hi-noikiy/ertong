<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( $_GPC['op'] == 'bar' ){
		$tid = intval( $_GPC['temp'] ) > 0 ? intval( $_GPC['temp'] ) : '';
		$data['bar'] = model_temp::getBar( $tid );
		$this->result(0, '',$data);

	}elseif( $_GPC['op'] == 'set' ){

		$set = Util::getModuleConfig();
		$set['kefuimg'] = tomedia( $set['kefuimg'] );


		unset( $set['frompass'],$set['mail'] );

		$this->result(0, '',$set);
	}
	
