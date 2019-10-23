<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;

	if( $_GPC['op'] == 'list' ) {
		
		$where = array('uniacid' => $_W['uniacid'],'status'=>0);

		if( !empty( $_GPC['sid'] ) ) $where['sortid'] = $_GPC['sid'];

		$order = ' `number` DESC ';
		$str = '';

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appoint',$where,$_GPC['page'],10,$order,false,false,' `id`,`name`,`desc`,`price`,ispay,num,thumb ',$str);
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/appoint/info?aid='.$v['id'];
				$v['thumb'] = tomedia( $v['thumb'] );

			}
		}
		
		$data['list'] = $list;

		$this->result(0, '',$data);
	
	}elseif( $_GPC['op'] == 'info' ){

		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');

		$appoint = model_appoint::getAppoint( $_GPC['aid'] );
		$appoint['thumb'] = tomedia( $appoint['thumb'] );
		if( $appoint['istime'] ){
			$appoint['structtime'] = model_appoint::structTime( $appoint['timedata'],$appoint['id'] );
		}

		if( !empty( $appoint['content'] )) {
			$appoint['content'] = htmlspecialchars_decode( $appoint['content'] );
		}else{
			unset($appoint['content']);
		}
		

		if( empty( $appoint ) || $appoint['status'] != 0 ) $this->result(1, '预约项目不存在');

		$data['appoint'] = $appoint;

		// 删除之前提交记录
		Util::deleteCache('oldapay',$_W['openid']);

		$this->result(0, '',$data);



	}elseif( $_GPC['op'] == 'sub' ){

		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
		$appoint = model_appoint::getAppoint( $_GPC['aid'] );

		if( empty( $appoint ) || $appoint['status'] != 0 ) $this->result(1, '预约项目不存在');		

		if( empty( $_GPC['tel'] ) && $appoint['istel'] == 1 ) $this->result(1, '请填写手机号');

		if( $appoint['istime'] ){
			if( $appoint['timedata']['timetype'] == 0 ) {
				if( empty( $_GPC['actttime'] ) ) $this->result(1, '请选择预约时间');
			}
			if( $appoint['timedata']['timetype'] == 1 ) {
				if( empty( $_GPC['actleft'] ) || empty( $_GPC['actrs'] ) || empty( $_GPC['actre'] ) ) $this->result(1, '请选择预约时间');
			}
		}

		if( !empty( $appoint['form'] ) ){
			$form = json_decode( htmlspecialchars_decode( $_GPC['form'] ),true );

			if( empty( $form ) ) $this->result(1, '请先填写数据');	

			$arr = array();
			foreach ( $appoint['form'] as $v ) {
				$item = array();
				foreach ( $form as $kk => $vv ) {
					if( $kk == $v['id'] ) {
							$item['name'] = $v['name'];
							$item['type'] = $v['type'];
						if( $v['type'] == 'img' ) {

							$img = json_decode( $vv,true );
							if( empty( $img ) ) {
								$this->result(1, '请设置'.$v['name']);
							}
							$imgarr = array();
							foreach ($img as $vvv) {
								$imgarr[] = $vvv['att'];
							}
							$item['value'] = $imgarr;
						}else{
							$item['value'] = $vv;
						}
						continue;
					}
				}

				if( empty( $item ) ) $this->result(1, '请填写'.$v['name']);
				$arr[] = $item;
			}

			$subform = iserializer( $arr );
		}

		$ispay = $appoint['ispay'] == 1 && $appoint['price'] > 0 ? 1 : 0;


		$title = $appoint['name'];
		$fee = $appoint['price'];
		$orderid = pay::createOrderId( $_W['fans']['fanid'] );

		$params = pay::returnPay( $orderid,$fee,$title,$_W['openid'] );
		
		$appdata = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid'],
			'orderid' => $orderid,
			'fee' => $fee,
			'title' => $ispay ? $params['title'] : $appoint['name'],
			'ispay' => $ispay,
			'createtime' => TIMESTAMP,
			'formid' => $_GPC['formid'],
			'form' => $subform,
			'aid' => $_GPC['aid'],
			'status' => $ispay ? 0 : ( $this->module['config']['isautotake'] ? 2 : 1 ),
			'tel' => $appoint['istel'] == 1 ? $_GPC['tel'] : '',
		);
		if( $appoint['istime'] ){
			if( $appoint['timedata']['timetype'] == 0 ) {
				$appdata['apptime'] = $_GPC['actttime'];
			}
			if( $appoint['timedata']['timetype'] == 1 ) {
				$appdata['apptime'] = $_GPC['actleft'].' '.$_GPC['actrs'].'-'.$_GPC['actre'];
			}
		}

		$res = pdo_insert('zxbddiy_sitetemp_appointorder',$appdata);
		$id = pdo_insertid();

		// 删除旧数据
		$oldorder = Util::getCache('oldapay',$_W['openid']);
		if( !empty( $oldorder ) ){
			$res = pdo_delete('zxbddiy_sitetemp_appointorder',array('orderid'=>$oldorder,'status'=>0,'ispay'=>1,'uniacid'=>$_W['uniacid']));
			if( $res ){
				pdo_delete('core_paylog',array('uniacid'=>$_W['uniacid'],'status'=>0,'tid'=>$oldorder));
			}
		}
		Util::setCache('oldapay',$_W['openid'],$orderid);
		Util::deleteCache('apd',$order['openid']);
		Util::deleteCache('allapp','all');
		
		if( $ispay == 1 ) { // 在线支付

			$pay_params = $this->pay($params);

			if (is_error($pay_params)) {
				$this->result(1, '支付失败1，原因：'.$pay_params['message']);
			}
			$pay_params['orderid'] = $orderid;

			$this->result(0, '',$pay_params);

		}else{ // 不需支付

			$pay_params['orderid'] = $orderid;
			$order = pdo_get('zxbddiy_sitetemp_appointorder',array('id'=>$id));

			$up = array('num'=>1,'realnum'=>1);
			Util::addOrMinusOrUpdateData('zxbddiy_sitetemp_appoint',$up,$appoint['id']);
			Util::deleteCache( 'appoint',$appoint['id'] );
			Util::deleteCache('apd',$order['openid']);
			Util::deleteCache('allapp','all');

			model_appoint::sendMess( $order,$this->module['config'],$order['status'],$appoint['tips'] );
			$this->result(0, '',$pay_params);
		}

		$this->result(0, '',$data);

	
	}elseif( $_GPC['op'] == 'orderinfo' ) {

		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');

		$where = array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid'],'openid'=>$_W['openid']);
		if( !empty( $_GPC['pass'] ) ) {
			if( $_GPC['pass'] !== $this->module['config']['frompass'] ) $this->result(1,'验证码不正确');
			unset( $where['openid'] );
		}

		$order = pdo_get('zxbddiy_sitetemp_appointorder',$where);
		if( empty( $order ) ) $this->result(1, '预约不存在');	

		$order['form'] = iunserializer( $order['form'] );

		foreach ($order['form'] as $k => $v) {
			if( $v['type'] == 'img' ){
				foreach ((array)$v['value'] as $kk => $vv) {
					$order['form'][$k]['value'][$kk] = tomedia( $vv );
				}
			}
		}

		$order['createtime'] = date('Y-m-d H:i:s',$order['createtime']);

		$order['appoint'] = model_appoint::getAppoint( $order['aid'] );
		$order['appoint']['thumb'] = tomedia( $order['appoint']['thumb'] );

		$data['order'] = $order;

		$set = $this->module['config'];
		unset( $set['frompass'],$set['mail'] );

		$set['apkefuimg'] = tomedia( $set['apkefuimg'] );
		$data['set'] = $set;

		$this->result(0, '',$data);


	// 列表
	}elseif( $_GPC['op'] == 'olist' ) {

		if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');


		$where = array('uniacid' => $_W['uniacid'],'openid'=>$_W['openid']);

		$order = ' `id` DESC ';
		$str = '';

		if( $_GPC['type'] == 1 ) $where['status'] = 0;
		if( $_GPC['type'] == 2 ) $where['status'] = 1;
		if( $_GPC['type'] == 3 ) $where['status'] = 2;
		if( $_GPC['type'] == 4 ) $where['status'] = 3;
		if( $_GPC['type'] == 5 ) $where['status'] = 4;
		if( $_GPC['type'] == 6 ) $where['status'] = 5;

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appointorder',$where,$_GPC['page'],10,$order,false,false,' id,orderid,status,aid,fee ',$str);
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/appoint/orderinfo?oid='.$v['orderid'];

				if( $v['status'] == 0 ) $v['statusstr'] = '待支付';
				if( $v['status'] == 1 ) $v['statusstr'] = '待接单';
				if( $v['status'] == 2 ) $v['statusstr'] = '已接单';
				if( $v['status'] == 3 ) $v['statusstr'] = '已完成';
				if( $v['status'] == 4 ) $v['statusstr'] = '已取消';
				if( $v['status'] == 5 ) $v['statusstr'] = '已退款';

				$v['app'] = model_appoint::getAppoint( $v['aid'] );
				$v['app']['thumb'] = tomedia( $v['app']['thumb'] );
			}
		}
		
		$data['list'] = $list;
		
		if( $_GPC['initpage'] == 0 ){

			$set = $this->module['config'];
			$set['apkefuimg'] = tomedia( $set['apkefuimg'] );
			unset( $set['frompass'],$set['mail'] );

			$data['set'] = $set;

			$mynum = model_user::myAppointNum( $_W['openid'] );
			$data['mynum'] = $mynum;
		}

		$this->result(0, '',$data);
	

	// 再支付
	}elseif( $_GPC['op'] == 'pay' && $_W['ispost'] ) {

		$order = pdo_get('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'status'=>0,'orderid'=>$_GPC['oid']));

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
	
	}elseif( $_GPC['op'] == 'com' && $_W['ispost'] ){

		$where = array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'status'=>2,'orderid'=>$_GPC['oid']);

		if( !empty( $_GPC['password'] ) ){
			unset( $where['openid'] );
			if( $_GPC['password'] != $this->module['config']['frompass'] ) $this->result(1, '验证码不正确');
		}

		$order = pdo_get('zxbddiy_sitetemp_appointorder',$where);

		if( empty( $order ) ) $this->result(1, '未找到订单');

		pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>3),array('id'=>$order['id']));
		
		Util::deleteCache('apd',$order['openid']);
		Util::deleteCache('allapp','all');
		
		$this->result(0,'已完成');
	
	}elseif( $_GPC['op'] == 'cancel' && $_W['ispost'] ){

		if( $_GPC['password'] != $this->module['config']['frompass'] ) $this->result(1, '验证码不正确');

		$order = pdo_get('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');

		pdo_delete('zxbddiy_sitetemp_appointorder',array('id'=>$order['id']));
		
		Util::deleteCache('apd',$order['openid']);
		Util::deleteCache('allapp','all');
		
		$this->result(0,'已删除');		

	}elseif( $_GPC['op'] == 'take' && $_W['ispost'] ){

		if( $_GPC['password'] != $this->module['config']['frompass'] ) $this->result(1, '验证码不正确');

		$order = pdo_get('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));

		if( empty( $order ) ) $this->result(1, '未找到订单');

		pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>2),array('id'=>$order['id']));
		
		Util::deleteCache('apd',$order['openid']);
		Util::deleteCache('allapp','all');
		
		$this->result(0,'已接单');	


	}elseif( $_GPC['op'] == 'encrypt' && $_W['ispost'] ){

		$encrypt_data = $_GPC['encryptedData'];
		$iv = $_GPC['iv'];
		if (empty($_SESSION['session_key']) || empty($encrypt_data) || empty($iv)) {
			$this->result(1,'请先登录');
		}
		
		$aesKey=base64_decode( $_SESSION['session_key'] );
		$aesIV=base64_decode($iv);
		$aesCipher=base64_decode($encrypt_data);
		$result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
		$dataObj=json_decode( $result,true );
		
		if( empty( $dataObj ) ) $this->result(1,'获取手机失败');

		$this->result(0,'好',$dataObj['phoneNumber']);
	
	}elseif( $_GPC['op'] == 'checkpass' && $_W['ispost'] ){

		if( empty($_GPC['pass']) || $_GPC['pass'] !== $this->module['config']['frompass'] ) $this->result(1,'验证码不正确');
		$this->result(0,'好');
		
	
	}elseif( $_GPC['op'] == 'adminlist' ){

		if( empty( $_GPC['password'] ) ) $this->result(1, '验证码不正确');
		if( $_GPC['password'] != $this->module['config']['frompass'] ) $this->result(1, '验证码不正确');

		$where = array('uniacid' => $_W['uniacid']);
		$order = ' `id` DESC ';
		$str = '';

		if( $_GPC['type'] == 1 ) $where['status'] = 0;
		if( $_GPC['type'] == 2 ) $where['status'] = 1;
		if( $_GPC['type'] == 3 ) $where['status'] = 2;
		if( $_GPC['type'] == 4 ) $where['status'] = 3;
		if( $_GPC['type'] == 5 ) $where['status'] = 4;
		if( $_GPC['type'] == 6 ) $where['status'] = 5;

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appointorder',$where,$_GPC['page'],10,$order,false,false,' id,orderid,status,aid,fee,tel ',$str);
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/appoint/orderinfoadmin?oid='.$v['orderid'].'&pass='.$_GPC['password'];

				if( $v['status'] == 0 ) $v['statusstr'] = '待支付';
				if( $v['status'] == 1 ) $v['statusstr'] = '待接单';
				if( $v['status'] == 2 ) $v['statusstr'] = '已接单';
				if( $v['status'] == 3 ) $v['statusstr'] = '已完成';
				if( $v['status'] == 4 ) $v['statusstr'] = '已取消';
				if( $v['status'] == 5 ) $v['statusstr'] = '已退款';

				$v['app'] = model_appoint::getAppoint( $v['aid'] );
				$v['app']['thumb'] = tomedia( $v['app']['thumb'] );
			}
		}
		
		$data['list'] = $list;
		
		if( $_GPC['initpage'] == 0 ){

			$mynum = model_appoint::orderData();
			$data['mynum'] = $mynum;
		}

		$this->result(0, '',$data);

	}

	