<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
	$plug = intval( $_GPC['plug'] );

	if( !empty( $_GPC['isadmin'] ) ){
		$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>$plug == 1 ? 2 :0));
		if( empty( $isadmin ) ) $this->result(1, '您不能查看订单');
	}

	if( $_GPC['op'] == 'info' ) {
		
		$where = array('uniacid' => $_W['uniacid'],'openid'=>$_W['openid'],'orderid'=>$_GPC['oid']);
		if( !empty( $_GPC['isadmin'] ) ) unset( $where['openid'] );

		$order = pdo_get('zxbddiy_sitetemp_order',$where);
		
		
		if( empty( $order ) ) $this->result(1, '未找到订单');
		unset( $order['openid'] );

		$order['express'] = 0;
		$order['address'] = iunserializer( $order['address'] );
		$order['formdata'] = iunserializer( $order['formdata'] );
		$order['progress'] = iunserializer( $order['progress'] );
		$order['isprogress'] = 0;
		if( !empty( $order['progress'] ) ) {
			$order['isprogress'] = 1;
			foreach ($order['progress'] as $k => $v) {
				$order['progress'][$k]['time'] = date('Y-m-d H:i',$v['time']);
				if( !empty( $v['img'] ) ) {
					foreach ($v['img'] as $kk => $vv) {
						$order['progress'][$k]['img'][$kk] = tomedia( $vv );
					}
				}
			}
		}
		
		if( !empty( $order['formdata'] ) ) {
			foreach ( $order['formdata'] as $k => $v ) {
				if( is_array( $v['value'] ) ) {
					$order['formdata'][$k]['isarray'] = 1;
				}
			}
		}

		$all = pdo_getall('zxbddiy_sitetemp_ordergood',array('oid'=>$order['orderid']));
		if( !empty( $all ) ) {
			foreach ( $all as &$v ) {
				$good = model_good::getGood( $v['gid'] );
				$v['title'] = $good['title'];
				$v['thumb'] = tomedia( $good['thumb'] );
				$v['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$good['id'];

				if( $plug == 1 ) $v['url'] = '/zxbddiy_sitetemp/pages/deskorder/good?gid='.$good['id'];

				$order['express'] += $v['costexpress'];
				$order['num'] += $v['num'];
			}
		}
		$order['list'] = $all;

		if( $order['createtime'] > 0 ) $order['log'][] = array( 's'=>'创建订单','t'=>date('Y-m-d H:i',$order['createtime']) );
		if( $order['paytime'] > 0 ) $order['log'][] = array( 's'=>'支付订单','t'=>date('Y-m-d H:i',$order['paytime']) );
		if( $order['sendtime'] > 0 ) $order['log'][] = array( 's'=>'卖家发货','t'=>date('Y-m-d H:i',$order['sendtime']) );
		if( $order['comtime'] > 0 ) $order['log'][] = array( 's'=>'订单完成','t'=>date('Y-m-d H:i',$order['comtime']) );
		if( $order['refundtime'] > 0 ) $order['log'][] = array( 's'=>'完成退款','t'=>date('Y-m-d H:i',$order['refundtime']) );	


		if( $order['deskid'] > 0 ){
			$order['desk'] = pdo_get('zxbddiy_sitetemp_desk',array('id'=>$order['deskid']));
		}

		$data['order'] = $order;

		$set = Util::getModuleConfig();
		$set['kefuimg'] = tomedia( $set['kefuimg'] );
		$set['okefuimg'] = tomedia( $set['okefuimg'] );
		unset( $set['frompass'],$set['mail'] );
		
		$data['set'] = $set;

		$data['time'] = array(
			'prodate' => date('Y-m-d',TIMESTAMP),
			'protime' => date('H:i:s',TIMESTAMP),
		);

		$data['lawyer'] = $this->module['config']['lawyer'] == 1 ? 1 : 0;

		$this->result(0, '',$data);
		
	}elseif( $_GPC['op'] == 'cancel' && $_W['ispost'] ) {

		$res = model_order::cancelOrder( $_GPC['oid'],$_W['openid'] );
		if( $res ){
			$this->result(0, '');
		}else{
			$this->result(1, '取消失败');
		}

	// 完成订单
	}elseif( $_GPC['op'] == 'com' && $_W['ispost'] ) {

		

		$this->result(0, '');

	// 再支付
	}elseif( $_GPC['op'] == 'pay' && $_W['ispost'] ) {

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'status'=>0,'orderid'=>$_GPC['oid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');

		$params['tid'] = $order['orderid'];
		$params['user'] = $order['openid'];
		$params['fee'] = $order['fee'];
		$params['title'] = $order['title'];

		$pay_params = $this->pay($params);

		if (is_error($pay_params)) {
			$this->result(1, '支付失败1，原因：'.$pay_params['message']);
		}

		$this->result(0,'',$pay_params);
	}

	