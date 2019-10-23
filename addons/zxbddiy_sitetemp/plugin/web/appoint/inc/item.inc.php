<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	

	//添加，编辑
	if(checksubmit('create')){
		$_GPC = Util::trimWithArray($_GPC);
		
		$data['name'] = $_GPC['title'];
		$data['desc'] = $_GPC['desc'];
		$data['number'] = intval( $_GPC['number'] );
		$data['uniacid'] = $_W['uniacid'];
		$data['thumb'] = $_GPC['thumb'];
		$data['tips'] = $_GPC['tips'];
		$data['pic'] = iserializer( $_GPC['pic'] );

		$data['ispay'] = intval( $_GPC['ispay'] );
		$data['price'] = sprintf('%.2f',$_GPC['price']);

		$data['status'] = intval( $_GPC['status'] );
		$data['num'] = intval( $_GPC['num'] );
		$data['istel'] = intval( $_GPC['istel'] );
		$data['tel'] = $_GPC['tel'];
		$data['sortid'] = intval( $_GPC['sortid'] );
		$data['content'] = str_replace('?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1', '', $_GPC['content']);
		$data['istime'] = intval( $_GPC['istime'] );

		$timedata = array(
			'aheadtime' => sprintf('%.2f',$_GPC['aheadtime']),
			'days' => intval( $_GPC['days'] ),
			'numlimit' => intval( $_GPC['numlimit'] ),
			'timetype' => intval( $_GPC['timetype'] ),
			'weektype' => intval( $_GPC['weektype'] ),
		);
		

		if( !empty( $_GPC['ltimesh'] ) ) {
			$timedata['limittime'] = array();	
			foreach ($_GPC['ltimesh'] as $k => $v) {
				$timedata['limittime'][] = array(
					'ltimesh' => $v,
					'ltimesm' => $_GPC['ltimesm'][$k],
					'ltimeh' => $_GPC['ltimeh'][$k],
					'ltimeem' => $_GPC['ltimeem'][$k],
				);
			}
		}
		$data['timedata'] = iserializer( $timedata );

		// 验证
		if( empty( $data['name'] ) ) itoast('未填写名称','','error');
		if( empty( $_GPC['aid'] ) ) itoast('填写表单内容','','error');

		$form = array();
		foreach($_GPC['aid'] as $v){
			$item = array();
			$item['id'] = $v;
			$item['name'] = $_GPC['name'][$v];
			$item['type'] = $_GPC['type'][$v];
			$item['pla'] = $_GPC['pla'][$v];
			$item['sitem'] = $_GPC['sitem'][$v];
			$item['maxnum'] = intval( $_GPC['maxnum'][$v] );

			$form[] = $item;
		}
		
		$data['form'] = iserializer( $form );


		if(!empty($_GPC['id'])){
			$id = intval($_GPC['id']);
			$res = pdo_update('zxbddiy_sitetemp_appoint',$data,array('uniacid'=>$_W['uniacid'],'id'=>$id));	
		}else{
			$data['createtime'] = TIMESTAMP;

			$res = Util::inserData('zxbddiy_sitetemp_appoint',$data);
			$id = pdo_insertid();
		}
		Util::deleteCache('appoint',$id);
		message('操作成功',self::pwUrl('appoint','item',array('op'=>'list','page'=>$_GPC['page'])),'success');
	}
	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_appoint');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	//批量下架
	if(checksubmit('downall')){
		
		if( empty( $_GPC['checkall'] ) ) itoast('先选择操作的商品','','error');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_appoint',array('status'=>1),array('id'=>$v,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('appoint',$v);
		}
		itoast('操作完成','','success');
	}

	//批量上架
	if(checksubmit('upall')){
		
		if( empty( $_GPC['checkall'] ) ) itoast('先选择操作的商品','','error');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_update('zxbddiy_sitetemp_appoint',array('status'=>0),array('id'=>$v,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('appoint',$v);
		}
		itoast('操作完成','','success');
	}	

	$appsort = model_appsort::getSort();
	
	if($_GPC['op'] == 'list'){	

		$where = array('uniacid'=>$_W['uniacid']);

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appoint',$where,$_GPC['page'],10,' `status` ASC,`number` DESC ');
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ) {
			foreach ( $list as &$v ) {
				$v['urlimg'] = $this->createWebUrl('img',array('op'=>'appointadmin','aid'=>$v['id']));
			}
		}

	}
	
	$auth = model_auth::authList();

	if($_GPC['op'] == 'edit'){
		$id = intval($_GPC['id']);
		$info = pdo_get('zxbddiy_sitetemp_appoint',array('id'=>$_GPC['id']));
		$info['form'] = iunserializer( $info['form'] );
		$info['pic'] = iunserializer( $info['pic'] );
		$info['timedata'] = iunserializer( $info['timedata'] );

		if( empty( $info ) ) itoast('未找到预约项目','','error');
	}
	

	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_appoint');
		if($res) itoast('删除成功','','success');
	}
	
	if($_GPC['op'] == 'down'){
		pdo_update('zxbddiy_sitetemp_appoint',array('status'=>1),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		Util::deleteCache('appoint',$_GPC['id']);

		itoast('已下架','','success');
	}	
	
	if($_GPC['op'] == 'up'){
		pdo_update('zxbddiy_sitetemp_appoint',array('status'=>0),array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		Util::deleteCache('appoint',$_GPC['id']);

		itoast('已上架','','success');
	}	
	

	include $this->pTemplate('item');
