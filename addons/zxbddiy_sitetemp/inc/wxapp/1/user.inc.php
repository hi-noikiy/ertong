<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
	$plug = intval( $_GPC['plug'] );

	if( $_GPC['op'] == 'info' && $_W['ispost'] ) {
		
		$num = model_user::myNum( $_W['openid'],$plug );
		$data['num'] = $num;

		$data['lawyer'] = $this->module['config']['lawyer'] == 1 ? 1 : 0;

		$set = $this->module['config'];
		$set['kefuimg'] = tomedia( $set['kefuimg'] );
		$set['okefuimg'] = tomedia( $set['okefuimg'] );
		

		unset( $set['frompass'],$set['mail'] );

		$data['set'] = $set;
		
		// isadmin
		$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>$plug == 1 ? 2 : 0));
		$data['isadmin'] = empty( $isadmin ) ? 0 : 1;


		// copyright
		$auth = model_auth::authList();
		if( empty( $auth['isclosecopy'] ) ){
			$copy = pdo_get('zxbddiy_sitetemp_copyright');
			if( !empty( $copy ) && $copy['status'] == 0 ) {
				$copy['content'] = htmlspecialchars_decode( $copy['content'] );
				$picarr = iunserializer( $copy['pic'] );
				$copy['pic'] = array();

				if( !empty( $picarr ) ) {
					
					foreach ( $picarr as &$pic ) {
						$copy['pic'][]= array( 'url'=>tomedia( $pic ) );
					}
				}
				
				$data['copy'] = $copy;
			}
		}


		$this->result(0, '',$data);

	}elseif( $_GPC['op'] == 'address' ){

		$where = array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']);

		$alli = Util::getAllDataInSingleTable('zxbddiy_sitetemp_address',$where,1,20,' isact DESC,`id` DESC ',0,false,' * ');
		$alls = $alli[0];

		if( !empty( $alls ) ) {
			foreach ($alls as $k => &$v) {
				$v['params'] = iunserializer( $v['address'] );
				unset( $alls[$k]['openid'] );
			}
		}

		$data['list'] = $alls;



	}elseif( $_GPC['op'] == 'addaddress' ){

		if( empty( $_GPC['name'] ) || $_GPC['name'] == 'undefined' ) $this->result(1, '请填写姓名');
		if( empty( $_GPC['tel'] ) || $_GPC['name'] == 'undefined' ) $this->result(1, '请填写电话');
		if( empty( $_GPC['street'] ) || $_GPC['name'] == 'undefined' ) $this->result(1, '请填写街道地址');
		if( empty( $_GPC['region'] ) || $_GPC['name'] == 'undefined' ) $this->result(1, '请选择地区');
		
		$id = intval( $_GPC['actid'] );

		$num = Util::countDataNumber('zxbddiy_sitetemp_address',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']));
		if( $num >= 10 ) $this->result(1, '你的地址已经很多了，不能再添加');

		$params = array(
			'name' => $_GPC['name'],
			'tel' => $_GPC['tel'],
			'region' => $_GPC['region'],
			'street' => $_GPC['street'],
		);

		$issetact = pdo_get('zxbddiy_sitetemp_address',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'isact'=>1));

		$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'address' => iserializer( $params ),
			'tel' => $_GPC['tel'],
		);

		if( !empty( $id ) ){
			if( $num <= 1 ) $data['isact'] = 1;
			pdo_update('zxbddiy_sitetemp_address',$data,array('id'=>$id));
		}else{
			if( $num <= 0 ) $data['isact'] = 1;
			pdo_insert('zxbddiy_sitetemp_address',$data);
		}

		$this->result(0, '已保存');

	}elseif( $_GPC['op'] == 'deleteadd' ){

		$res = pdo_delete('zxbddiy_sitetemp_address',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'id'=>$_GPC['id']));

		if( $res ){
			$this->result(0, '已删除');
		}else{
			$this->result(1, '删除失败');
		}

	}elseif( $_GPC['op'] == 'toactadd' ){

		pdo_update('zxbddiy_sitetemp_address',array('isact'=>0),array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']));

		$res = pdo_update('zxbddiy_sitetemp_address',array('isact'=>1),array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'id'=>$_GPC['id']));
		if( $res ){
			$this->result(0, '已保存');
		}else{
			$this->result(1, '保存失败');
		}

	}

	$this->result(0, '',$data);