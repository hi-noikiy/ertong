<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');

	if( $_GPC['op'] == 'info' ) {
		
		$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>0));
		if( empty( $isadmin ) ) $this->result(1, '你不是核销员，不能核销订单');

		$where = array('uniacid' => $_W['uniacid'],'orderid'=>$_GPC['oid']);
		$order = pdo_get('zxbddiy_sitetemp_order',$where);

		if( empty( $order ) ) $this->result(1, '未找到订单');
		unset( $order['openid'] );

		$order['express'] = 0;
		$order['address'] = iunserializer( $order['address'] );

		$all = pdo_getall('zxbddiy_sitetemp_ordergood',array('oid'=>$order['orderid']));
		if( !empty( $all ) ) {
			foreach ( $all as &$v ) {
				$good = model_good::getGood( $v['gid'] );
				$v['title'] = $good['title'];
				$v['thumb'] = tomedia( $good['thumb'] );
				$v['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$good['id'];

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

		$data['order'] = $order;

		$set = Util::getModuleConfig();
		$set['kefuimg'] = tomedia( $set['kefuimg'] );
		unset( $set['frompass'],$set['mail'] );
		
		$data['set'] = $set;

		$this->result(0, '',$data);
	

	// 完成订单
	}elseif( $_GPC['op'] == 'com' && $_W['ispost'] ) {

		$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>0));
		if( empty( $isadmin ) ) $this->result(1, '你不是核销员，不能核销订单');

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));
		if( empty( $order ) ) $this->result(1, '未找到订单');

		if( $order['status'] != 1 ) $this->result(1, '此订单不能核销');
		if( $order['taketype'] != 2 ) $this->result(1, '此订单不能核销');
		
		$res = model_order::comOrder( $_GPC['oid'] );
		
		if( $res ){
			pdo_update('zxbddiy_sitetemp_order',array('hexiaoer'=>$_W['openid']),array('id'=>$order['id']));
			Util::deleteCache('alld','all');
			$this->result(0, '核销完成');
		}else{
			$this->result(1, '核销失败');
		}
		
	}

	