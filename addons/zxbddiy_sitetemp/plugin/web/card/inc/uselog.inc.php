<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	

	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_cardlog');
		Util::deleteCache('alld','all');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	
	if( in_array($_GPC['op'], array('taked','used','waitcheck','passed')) ){

		$where = array('uniacid'=>$_W['uniacid']);

		if( $_GPC['op'] == 'taked' ) {
			$where['status'] = 0;
			$where['end>'] = TIMESTAMP;
		}

		if( $_GPC['op'] == 'used' ) {
			$where['status'] = 1;
		}	
		if( $_GPC['op'] == 'waitcheck' ) {
			$where['status'] = 0;
			$where['end>'] = TIMESTAMP;
			$where['usetype'] = 0;
		}	
		if( $_GPC['op'] == 'passed' ) {
			$where['status'] = 0;
			$where['end<'] = TIMESTAMP;
		}	

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_cardlog',$where,$_GPC['page'],10,' `id` DESC ',false,true,' * ',$str);
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ){
			foreach ( $list as &$v ) {
				$v['card'] = model_card::getCard( $v['cardid'] );

				$v['user'] = model_user::getSingleUser( $v['openid'] );
			}
		}
	}
	


	if($_GPC['op'] == 'info'){

		$id = intval($_GPC['id']);
		$order = pdo_get('zxbddiy_sitetemp_cardlog',array('uniacid'=>$_W['uniacid'],'id'=>$id));
		if( empty( $order ) ) itoast('未找到订单','','error');
		
		$order['address'] = iunserializer( $order['address'] );
		$order['formdata'] = iunserializer( $order['formdata'] );
		

		$user = pdo_get('mc_mapping_fans',array('openid'=>$order['openid']));
		$tag = iunserializer( base64_decode( $user['tag'] ) );
		$user['headimgurl'] = $tag['headimgurl'];

		if( !empty( $order['deskid'] ) ){
			$desk = pdo_get('zxbddiy_sitetemp_desk',array('uniacid'=>$_W['uniacid'],'id'=>$order['deskid']));
		}
		
	}
	
	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_cardlog');
		Util::deleteCache('alld','all');
		if($res) itoast('删除成功','','success');

	}
	
	

	include $this->pTemplate('uselog');