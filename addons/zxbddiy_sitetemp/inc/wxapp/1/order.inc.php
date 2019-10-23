<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
	$plug = intval( $_GPC['plug'] );

	if( $_GPC['op'] == 'getlist' ) {
		
		$where = array('uniacid' => $_W['uniacid'],'openid'=>$_W['openid'],'plug'=>$plug);
		
		$order = ' `id` DESC ';
		$str = '';
		
		if( $_GPC['type'] == 1 ) $where['status'] = 0;
		if( $_GPC['type'] == 2 ) $where['status'] = 1;
		if( $_GPC['type'] == 3 ) $where['status'] = 2;
		if( $_GPC['type'] == 4 ) $where['status'] = 3;
		if( $_GPC['type'] == 5 ) $where['status'] = 4;

		if( $_GPC['type'] == 1 ) $str = ' AND `paytype` = 0 ';
		if( $_GPC['type'] == 2 ){
			unset( $where['status'] );
			$str = ' AND ( ( `status` =  1 ) OR ( `status` = 0 AND `paytype` = 1 ) ) ';
		}

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_order',$where,$_GPC['page'],10,$order,false,false,' id,orderid,status,fee,taketype,paytype ',$str);
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/orderinfo/orderinfo?oid='.$v['orderid'];
				if( $plug == 1 ) $v['url'] = '/zxbddiy_sitetemp/pages/deskorder/orderinfo?oid='.$v['orderid'];

				$v['num'] = 0;
				$v['express'] = 0;

				$all = pdo_getall('zxbddiy_sitetemp_ordergood',array('oid'=>$v['orderid']));
				if( !empty( $all ) ){
					foreach ( $all as $vv ) {
						$good = model_good::getGood( $vv['gid'] );
						$item['id'] = $vv['id'];
						$item['num'] = $vv['num'];
						$item['money'] = $vv['money'];
						$item['rulename'] = $vv['rulename'];
						$item['thumb'] = tomedia( $good['thumb'] );
						$item['title'] = $good['title'];
						$item['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$good['id'];

						if( $plug == 1 ) $item['url'] = '/zxbddiy_sitetemp/pages/deskorder/good?gid='.$good['id'];

						$v['list'][] = $item;
						$v['num'] += $vv['num'];
						$v['express'] += $vv['costexpress'];

						if( $v['status'] == 0 && $v['paytype'] == 0 ) $v['statusstr'] = '待支付';
						if( $v['status'] == 0 && $v['paytype'] == 1 ) $v['statusstr'] = '待配送，待付款';
						if( $v['status'] == 1 && $v['taketype'] == 1 ) 
							$v['statusstr'] =  $this->module['config']['lawyer'] == 1 ? '待履行' : '待发货';
						if( $v['status'] == 1 && $v['taketype'] == 2 ) $v['statusstr'] = '待上店自提';
						if( $v['status'] == 2 ) 
							$v['statusstr'] = $this->module['config']['lawyer'] == 1 ? '正在履行' : '已发货';
						if( $v['status'] == 3 ) $v['statusstr'] = '已完成';
						if( $v['status'] == 4 ) $v['statusstr'] = '已退款';

						if( $v['deskid'] > 0 ) $v['statusstr'] .= '，扫码点餐';
					}
				}
			}
		}
		
		$data['list'] = $list;
		
		if( $_GPC['initpage'] == 0 ){

			$set = Util::getModuleConfig();
			$set['kefuimg'] = tomedia( $set['kefuimg'] );
			$set['okefuimg'] = tomedia( $set['okefuimg'] );
			unset( $set['frompass'],$set['mail'] );

			$data['set'] = $set;

			$mynum = model_user::myNum( $_W['openid'],$plug );
			$data['mynum'] = $mynum;
		}

		$data['lawyer'] = intval( $this->module['config']['lawyer'] );

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

		$res = model_order::comOrder( $_GPC['oid'],$_W['openid'] );

		if( $res ){
			$this->result(0, '');
		}else{
			$this->result(1, '完成失败');
		}

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
	
	}elseif( $_GPC['op'] == 'express' && $_W['ispost'] ){

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'orderid'=>$_GPC['oid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');
		if( empty( $order['expressnum'] ) || empty( $order['expressname'] ) ) $this->result(1, '该订单未填写快递信息');

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

			$this->result(0, '',array('pro'=>$order['progress']));

		}else{
			$res = model_express::getExpressInfo( $order['expressname'],$order['expressnum'] );
			if( $res['status'] == 0 ) {
				$this->result(1, '查询失败，原因：'.$res['data']);
			}else{
				$this->result(0, '',$res);
			}
		}


	}elseif( $_GPC['op'] == 'addprogress' && $_W['ispost'] ){

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['oid'],'openid'=>$_W['openid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');
		if( empty( $_GPC['proname'] )  ) $this->result(1, '还没填写进度名称');

		$order['progress'] = iunserializer( $order['progress'] );
				
		if( empty( $order['progress'] ) ){
			$arr = array();
		}else{
			$arr = $order['progress'];
		}

		$img = array();
		$proimg = json_decode( htmlspecialchars_decode( $_GPC['proimg'] ) ,true );
				
		if( !empty( $proimg ) ){
			foreach ($proimg as $v) {
				$img[] = $v['att'];
			}
		}

		$arr[] = array(
			'key' => TIMESTAMP,
			'text' => $_GPC['proname'],
			'time' => TIMESTAMP,
			'type' => 1,
			'img' => $img
		);

		$sort = array();
		foreach ($arr as $v) {
			$sort[] = $v['time'];
		}

		array_multisort($sort,SORT_DESC,$arr);

		pdo_update('zxbddiy_sitetemp_order',array('progress'=> iserializer( $arr ) ),array('id'=>$order['id']));

		$this->result(0, '已增加',$res);

	}

	