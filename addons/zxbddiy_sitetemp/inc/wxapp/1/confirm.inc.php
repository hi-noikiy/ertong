<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');


	if( $_GPC['op'] == 'confirm' && $_W['ispost'] ) {
		
		$buyarr = Util::getCache('buydata',$_W['openid']);
		if( empty( $buyarr ) || !is_array( $buyarr ) ) $this->result(1, '请先选择需要购买的商品');
		
		$plug = intval( $_GPC['plug'] );

		$res = model_order::initOrder($buyarr,$_GPC['taketype'],'','',$plug);

		if( $res['status'] ){

			$data['res'] = $res;
			$data['shopinfo'] = array(
				'name' => $this->module['config']['shopname'],
				'tel' => $this->module['config']['shoptel'],
				'address' => $this->module['config']['shopaddress'],
			);
			
			if( $_GPC['address'] ){ // 第一次

				$address = pdo_get('zxbddiy_sitetemp_address',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'isact'=>1));
				
				if( !empty( $address['address'] ) ){
					$data['address'] = iunserializer( $address['address'] );
				}
				if( !empty( $address['tel'] ) ){
					$data['tel'] = $address['tel'];
				}

				$form = model_orderform::getForm( $plug );
				if( !empty( $form ) ) $data['form'] = $form;
				
			}

			$this->result(0, '',$data);
		}else{
			$this->result(1, $res['res']);
		}

		
		
	}elseif( $_GPC['op'] == 'pay' && $_W['ispost'] ) {

		$buyarr = Util::getCache('buydata',$_W['openid']);

		if( empty( $buyarr ) || !is_array( $buyarr ) ) $this->result(1, '请先选择需要购买的商品');
		$plug = intval( $_GPC['plug'] );

		$buydata = model_order::initOrder($buyarr,$_GPC['taketype'],'','',$plug);

		// 更新个人的电话和地址
		if( $buydata['taketype'] == 1 ) {
			$address = json_decode( htmlspecialchars_decode( $_GPC['address'] ),true );

			if( empty( $address ) || !is_array( $address ) ) $this->result(1, '请选择地址');
		}else{
			if( empty( $_GPC['tel'] ) ) $this->result(1, '请填写电话');
		}
		

		// 表单
		$form = model_orderform::getForm($plug);
		$formdata = array();
		if( !empty( $form ) ){
			$subform = json_decode( htmlspecialchars_decode( $_GPC['form'] ),true );
			foreach ( $form as $v ) {
				$isin = 0;
				foreach ( $subform as $vv ) {
					if( $v['id'] == $vv['id'] ){
						$formdata[] = array( 'name'=>$v['name'],'value'=>$vv['value'] );
						$isin = 1;
					}
				}
				if( empty( $isin ) ) $this->result(1, '请填写'.$v['name']);
			}
		}


		if( $buydata['status'] ){

			$title = $buydata['list'][0]['title'];
			$fee = $buydata['total'];
			$orderid = pay::createOrderId( $_W['fans']['fanid'] );

			$params = pay::returnPay( $orderid,$fee,$title,$_W['openid'] );
			
			$orderdata = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $_W['openid'],
				'orderid' => $orderid,
				'fee' => $fee,
				'title' => $params['title'],
				'taketype' => $buydata['taketype'],
				'createtime' => TIMESTAMP,
				'mess' => $_GPC['mess'],
				'formid' => $_GPC['formid'],
				'formdata' => iserializer( $formdata ),
				'plug' => $plug,
			);
			if( $buydata['taketype'] == 1 ){

				if( !empty( $address['region'] ) ) {

					$add = $address['region'].'，'.$address['street'];

				}elseif( !empty( $address['province'] ) ){
					$add = $address['province'].'，'.$address['city'].'，'.$address['county'].'，'.$address['detail'];
				}

				$orderdata['address'] = iserializer( array('address'=>$add,'name'=>$address['name'],'tel'=>$address['tel']) );
			}elseif( $buydata['taketype'] == 2 ){
				$orderdata['tel'] = $_GPC['tel'];
			}

			$res = pdo_insert('zxbddiy_sitetemp_order',$orderdata);
			$id = pdo_insertid();

			if( $res ){
				foreach ($buydata['list'] as  $v) {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'oid' => $orderid,
						'gid' => $v['gid'],
						'num' => $v['num'],
						'price' => $v['price'],
						'money' => $v['money'],
						'ruleid' => $v['ruleid'],
						'rulename' => $v['rulename'],
						'costexpress' => $v['costexpress'],
						'cartid' => $v['cartid'],
					);
					pdo_insert('zxbddiy_sitetemp_ordergood',$data);
				}

				// 更新编码
				if( $buydata['taketype'] == 2 ){
					pdo_update('zxbddiy_sitetemp_order',array('code'=>$id.rand(100,999)),array('id'=>$id));
				}
			}

			$pay_params = $this->pay($params);
			if (is_error($pay_params)) {
				$this->result(1, '支付失败1，原因：'.$pay_params['message']);
			}
			$oldorder = Util::getCache('oldpay',$_W['openid']);
			if( !empty( $oldorder ) ){
				$res = pdo_delete('zxbddiy_sitetemp_order',array('orderid'=>$oldorder,'status'=>0,'uniacid'=>$_W['uniacid']));
				if( $res ){
					pdo_delete('zxbddiy_sitetemp_ordergood',array('oid'=>$oldorder,'uniacid'=>$_W['uniacid']));
					pdo_delete('core_paylog',array('uniacid'=>$_W['uniacid'],'status'=>0,'tid'=>$oldorder));
				}
			}
			Util::setCache('oldpay',$_W['openid'],$orderid);
			Util::deleteCache('d'.$plug,$_W['openid']);
			Util::deleteCache('alld'.$plug,'all');
			$pay_params['orderid'] = $orderid;

			$this->result(0, '',$pay_params);

		}else{
			$this->result(1, '支付失败2，原因：'.$res['res']);
		}
		
		

		
		
		

	}

	