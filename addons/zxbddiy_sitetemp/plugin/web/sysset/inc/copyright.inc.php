<?php 
	global $_W,$_GPC;
	


	//保存广告
	if(checksubmit('createad')){
		if( $_W['role'] != 'vice_founder' ) die;

		$temp = pdo_get('zxbddiy_sitetemp_vicead',array('uid'=>$_W['uid']));

		$params = array(
			'url' => $_GPC['url'],
			'appid' => $_GPC['appid'],
			'appurl' => $_GPC['appurl'],
			'weburl' => $_GPC['weburl'],
			'tel' => $_GPC['tel'],
			'pic' => $_GPC['pic'],
		);

		$data = array(
			'uid' => $_W['uid'],
			'status' => $_GPC['status'],
			'content' => $_GPC['content'],
			'type' => $_GPC['type'],
			'params' => iserializer( $params ),
		);

		if( empty( $temp ) ) {
			pdo_insert('zxbddiy_sitetemp_vicead',$data);
		}else{
			pdo_update('zxbddiy_sitetemp_vicead',$data,array('id'=>$temp['id']));
		}
		itoast('已保存','','success');
	}

	//批量删除
	if(checksubmit('deleteall')){
		if( $_W['role'] != 'founder' ) die;

		if( empty( $_GPC['checkall'] ) ) itoast('请先选择数据','','error');
		foreach ( $_GPC['checkall'] as $v ) {
			pdo_delete('zxbddiy_sitetemp_tempsort',array('id'=>$v));
		}
		Util::deleteCache('tempsort','all',false);
		itoast('已删除','','success');
	}

	// 保存短信
	if(checksubmit('savesms')){
		if( $_W['role'] != 'founder' ) die;

		$data = array(
			'key' => $_GPC['smskey'],
			'secret' => $_GPC['smssecret'],
			'signature' => $_GPC['smssignature'],
			'template' => iserializer( array('paysuc'=>$_GPC['paysuc'],'apptem'=>$_GPC['apptem']) ),
		);
		
		$isset = pdo_get('zxbddiy_sitetemp_sms');

		if( empty( $isset ) ) {
			pdo_insert('zxbddiy_sitetemp_sms',$data);
		}else{
			pdo_update('zxbddiy_sitetemp_sms',$data,array('id'=>$isset['id']));
		}
		itoast('已保存','','success');
	}

	// 保存设置
	if(checksubmit('saveset')){
		if( $_W['role'] != 'founder' ) die;

		$params = array(
			'viceisauth' => intval( $_GPC['viceisauth'] ),
			'isvicead' => intval( $_GPC['isvicead'] ),
			'istoseys' => intval( $_GPC['istoseys'] ),
			'deauth' => intval( $_GPC['deauth'] ),
		);
		
		$data = array( 'params'=>iserializer($params) );

		$isset = pdo_get('zxbddiy_sitetemp_set');

		if( empty( $isset ) ) {
			pdo_insert('zxbddiy_sitetemp_set',$data);
		}else{
			pdo_update('zxbddiy_sitetemp_set',$data,array('id'=>$isset['id']));
		}
		itoast('已保存','','success');
	}

	// 版权
	if( $_GPC['op'] == 'list' ){
		if( $_W['role'] != 'founder' ) die;

		$temp = pdo_get('zxbddiy_sitetemp_copyright');
		if( !empty( $temp ) ) {
			$temp['pic'] = iunserializer( $temp['pic'] );
			$temp['params'] = iunserializer( $temp['params'] );
		}

		//批量删除
		if(checksubmit('create')){

			$params = array(
				'url' => $_GPC['url'],
				'appid' => $_GPC['appid'],
				'appurl' => $_GPC['appurl'],
				'weburl' => $_GPC['weburl'],
			);

			$data = array(
				'status' => $_GPC['status'],
				'content' => $_GPC['content'],
				'type' => $_GPC['type'],
				'tel' => $_GPC['tel'],
				'pic' => iserializer( $_GPC['pic'] ),
				'params' => iserializer( $params ),
			);
			if( empty( $temp ) ) {
				pdo_insert('zxbddiy_sitetemp_copyright',$data);
			}else{
				pdo_update('zxbddiy_sitetemp_copyright',$data);
			}
			itoast('已保存','','success');
		}
	
	}elseif( $_GPC['op'] == 'mail' ){
		if( $_W['role'] != 'founder' ) die;

		$temp = pdo_get('zxbddiy_sitetemp_smtp');

		if(checksubmit('savemail')){
			
			$data = array(
				'type' => $_GPC['type'],
				'account' => $_GPC['account'],
				'pass' => $_GPC['pass'],
				'name' => $_GPC['name'],
				'sign' => $_GPC['sign'],
			);

			if( empty( $temp ) ) {
				pdo_insert('zxbddiy_sitetemp_smtp',$data);
			}else{
				pdo_update('zxbddiy_sitetemp_smtp',$data);
			}

			itoast('已保存','','success');

		}

	// 权限设置
	}elseif( $_GPC['op'] == 'auth' ){
		if( !in_array($_W['role'], array('founder','vice_founder')) ) die;

		$topbar = topbal::wxappList();

		$where = array('uniacid>'=>0);

		if( !empty( $_GPC['name'] ) ) $where['name@'] = $_GPC['name'];

		if( $_W['role'] == 'founder' ){
			$info = Util::getAllDataInSingleTable('account_wxapp',$where,$_GPC['page'],10,' `acid` DESC ',false,true,' * ','','',false);
		}elseif( $_W['role'] == 'vice_founder' ){

			$dataa = Util::structWhereStringOfAnd($where,'a',false);
			$str = ' AND b.uid = '.$_W['uid'];
			$select = ' a.* ';

			$commonstr = tablename('account_wxapp') ." AS a LEFT JOIN ".tablename('uni_account_users')." AS b ON a.`uniacid` = b.`uniacid` WHERE ".$dataa[0].$str ;
			
			$countStr = "SELECT COUNT(*) FROM ".$commonstr;
			$selectStr =  "SELECT $select FROM ".$commonstr;
			$info = Util::fetchFunctionInCommon($countStr,$selectStr,$dataa[1],$_GPC['page'],10,' a.`acid` DESC ',true);


			/*$where['uid'] = $_W['uid'];
			$info = Util::getAllDataInSingleTable('uni_account_users',$where,$_GPC['page'],10,' `id` DESC ',false,true,' * ','','',false);*/
		}
		
		
		$list = $info[0];
		$pager = $info[1];

		if( !empty( $list ) ) {
			foreach ( $list as $k => &$v ) {
				$app = pdo_get('account',array('uniacid'=>$v['uniacid']));

				if( $app['isdeleted'] == 1 ) {
					unset( $list[$k] );
					continue;
				}

				$auth = pdo_get('zxbddiy_sitetemp_auth',array('uniacid'=>$v['uniacid']));
				$v['auth'] = $auth;
			}
		}

		$set = model_sysset::getSet();
		
	}elseif( $_GPC['op'] == 'tempsort' ){
		if( $_W['role'] != 'founder' ) die;

		$list = model_tempsort::getSort();


	}else if( $_GPC['op'] == 'deletesort' ){
		if( $_W['role'] != 'founder' ) die;

		pdo_delete('zxbddiy_sitetemp_tempsort',array('id'=>$_GPC['id']));

		Util::deleteCache('tempsort','all',false);
		itoast('已删除','','success');
	
	}else if( $_GPC['op'] == 'sms' ){
		if( $_W['role'] != 'founder' ) die;

		$info = pdo_get('zxbddiy_sitetemp_sms');
		$info['template'] = iunserializer( $info['template'] );

	}else if( $_GPC['op'] == 'basic' ){
		if( $_W['role'] != 'founder' ) die;

		$info = model_sysset::getSet();
		

	}elseif( $_GPC['op'] == 'ad' ){

		$temp = pdo_get('zxbddiy_sitetemp_vicead',array('uid'=>$_W['uid']));
		if( !empty( $temp ) ) {
			$temp['params'] = iunserializer( $temp['params'] );
		}

	}


	include $this->pTemplate('copyright');