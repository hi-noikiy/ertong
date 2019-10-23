<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	

	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_appointorder');
		Util::deleteCache('alld','all');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	$op = intval( $_GPC['op'] );
	if( $op > 0 ){	

		$where = array('uniacid'=>$_W['uniacid']);
		$str = '';
		$where['status'] = $op-1;
		
		//if( $op == 1 ) $str = ' AND `paytype` = 0 ';
		//if( $op == 2 ) $str = ' OR ( `status` = 0 AND `paytype` = 1 ) ';

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appointorder',$where,$_GPC['page'],10,' `id` DESC ',false,true,' * ',$str);
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ){
			foreach ( $list as &$v ) {
				
				$v['appoint'] = model_appoint::getAppoint( $v['aid'] );
				if( !empty( $v['form'] ) ) {
					$v['form'] = iunserializer( $v['form'] );
				}
				$v['user'] = model_user::getSingleUser( $v['openid'] );
			}
		}
	}
	


	if($_GPC['op'] == 'info'){

		$id = intval($_GPC['id']);
		$order = pdo_get('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid'],'id'=>$id));
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
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_appointorder');
		Util::deleteCache('alld','all');
		if($res) itoast('删除成功','','success');
	}
	
	

	include $this->pTemplate('order');