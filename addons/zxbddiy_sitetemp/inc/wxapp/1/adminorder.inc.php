<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
	$plug = intval( $_GPC['plug'] );

	$isadmin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'type'=>$plug == 1 ? 2 : 0));
	if( empty( $isadmin ) ) $this->result(1, '你不能查看订单');

	if( $_GPC['op'] == 'getlist' ) {
		
		$where = array('uniacid' => $_W['uniacid'],'plug'=>$plug);

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

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_order',$where,$_GPC['page'],10,$order,false,false,' id,orderid,status,fee,taketype,paytype,deskid ',$str);
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/adminshop/orderinfo?oid='.$v['orderid'];

				if( $plug == 1 ) $v['url'] = '/zxbddiy_sitetemp/pages/deskorder/adminoinfo?oid='.$v['orderid'];

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

			$mynum = model_order::orderData($plug);
			$data['mynum'] = $mynum;
		}

		$data['lawyer'] = $this->module['config']['lawyer'] == 1 ? 1 : 0;

		$this->result(0, '',$data);
		
	}elseif( $_GPC['op'] == 'cancel' && $_W['ispost'] ) {

		$res = model_order::cancelOrder( $_GPC['oid'] );
		if( $res ){
			$this->result(0, '');
		}else{
			$this->result(1, '删除失败');
		}

	// 完成订单
	}elseif( $_GPC['op'] == 'com' && $_W['ispost'] ) {

		$res = model_order::comOrder( $_GPC['oid'] );

		if( $res ){
			$this->result(0, '');
		}else{
			$this->result(1, '完成失败');
		}

	// 发货
	}elseif( $_GPC['op'] == 'send' && $_W['ispost'] ) {

		$res = model_order::sendOrder($_GPC['oid'],$_GPC['name'],$_GPC['num']);
		
		if( $res ){
			$this->result(0, '已发货');
		}else{
			$this->result(1, '发货失败');
		}

	// 退款
	}elseif( $_GPC['op'] == 'refund' && $_W['ispost'] ) {

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');
		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));
		if( empty( $order ) ) Util::echoResult(201,'未找到订单');

		if( $order['status'] != 1 && $order['status'] != 2 && $order['paytype'] != 0 ) Util::echoResult(201,'订单不能再退款');
		
		$pay = new WeixinPay();
		
		$arr['uorderid'] = $order['uorderid'];		
		$arr['totalmoney'] = $order['fee'];
		$arr['refundmoney'] = $order['fee'];

		$res = $pay->refund( $arr );

		if($res['result_code']=='SUCCESS'){
			
			$res = pdo_update('zxbddiy_sitetemp_order',array('status'=>4,'refundtime'=>TIMESTAMP,'refundmoney'=>$arr['refundmoney']),array('id'=>$order['id']));
			$this->result(0, '已退款');
			
		}else{

			$this->result(1, '退款失败，原因：'.$res['err_code_des'].'，'.$res['return_msg']);
			
		}

		

	}elseif( $_GPC['op'] == 'express' && $_W['ispost'] ){

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));

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

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['oid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');
		if( empty( $_GPC['proname'] )  ) $this->result(1, '还没填写进度名称');

		$order['progress'] = iunserializer( $order['progress'] );
				
		if( empty( $order['progress'] ) ){
			$arr = array();
		}else{
			$arr = $order['progress'];
		}

		$img = array();
		$proimg = json_decode( htmlspecialchars_decode( $_GPC['proimg'] ),true );
		if( !empty( $proimg ) ){
			foreach ($proimg as $v) {
				$img[] = $v['att'];
			}
		}

		$arr[] = array(
			'key' => TIMESTAMP,
			'text' => $_GPC['proname'],
			'time' => strtotime( $_GPC['prodate'].' '.$_GPC['protime'] ),
			'type' => 0,
			'img' => $img	
		);

		$sort = array();
		foreach ($arr as $v) {
			$sort[] = $v['time'];
		}

		array_multisort($sort,SORT_DESC,$arr);

		pdo_update('zxbddiy_sitetemp_order',array('progress'=> iserializer( $arr ) ),array('id'=>$order['id']));

		$this->result(0, '已增加',$res);

	}elseif( $_GPC['op'] == 'deleteprogress' && $_W['ispost'] ){

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['oid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');

		$order['progress'] = iunserializer( $order['progress'] );	

		$oarr = array();
		if( !empty( $order['progress'] ) ) {
			foreach ($order['progress'] as $k => $v) {
				if( $v['key'] != $_GPC['key'] ) {
					$oarr[] = $v;
				}
			}
		}

		pdo_update('zxbddiy_sitetemp_order',array('progress'=> iserializer( $oarr ) ),array('id'=>$order['id']));

		$this->result(0, '已删除',$res);

	}

	
