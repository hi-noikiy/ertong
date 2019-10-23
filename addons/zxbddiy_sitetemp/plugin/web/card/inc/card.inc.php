<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	

	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_card');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	//批量下架
	if(checksubmit('downall')){
		
		if( empty( $_GPC['checkall'] ) ) itoast('先选择操作的卡券','','success');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_card',array('status'=>1),array('id'=>$v,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('good',$v);
		}
		itoast('操作完成','','success');
	}

	//批量上架
	if(checksubmit('upall')){
		
		if( empty( $_GPC['checkall'] ) ) message('先选择操作的卡券');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_card',array('status'=>0),array('id'=>$v,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('good',$v);
		}
		itoast('操作完成','','success');
	}	

	
	if($_GPC['op'] == 'list'){

		$topbar = topbal::cardList();

		$where = array('uniacid'=>$_W['uniacid']);
		$where['status'] = intval( $_GPC['status'] );

		if( !empty( $_GPC['type'] ) ) $where['type'] = intval( $_GPC['type'] ) - 1;
		if( !empty( $_GPC['use'] ) ) $where['usetype'] = intval( $_GPC['use'] ) - 1;

		if( !empty( $_GPC['title'] ) ) $where['name@'] = $_GPC['title'];

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_card',$where,$_GPC['page'],10,' `id` DESC ');
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ) {

			foreach ( $list as &$v ) {
				$v['urlimg'] = $this->createWebUrl('img',array('op'=>'card','cid'=>$v['id']));
				
			}

		}

	}
	
	if($_GPC['op'] == 'edit'){
		$id = intval($_GPC['id']);
		$info = model_card::getCard( $id );
		$info['rangetime']['start'] = date( 'Y-m-d H:i:s',$info['start'] );
		$info['rangetime']['end'] = date( 'Y-m-d H:i:s',$info['end'] );

		if( empty( $info ) ) itoast('未找到卡券','','success');
	}
	
	if($_GPC['op'] == 'edit' || $_GPC['op'] == 'add'){
		$auth = model_auth::authList();
	}	

	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_card');
		if($res) itoast('删除成功','','success');
	}
	
	if($_GPC['op'] == 'down'){
		pdo_update('zxbddiy_sitetemp_card',array('status'=>1),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		Util::deleteCache('good',$_GPC['id']);

		itoast('已下架','','success');
	}	
	
	if($_GPC['op'] == 'up'){
		pdo_update('zxbddiy_sitetemp_card',array('status'=>0),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		Util::deleteCache('good',$_GPC['id']);

		itoast('已下架','','success');
	}	
	

	include $this->pTemplate('card');
