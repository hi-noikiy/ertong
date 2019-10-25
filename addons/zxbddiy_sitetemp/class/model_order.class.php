<?php 


class model_order {
	
	static function istimeLimit( $type = 0,$set ){
		global $_W;
		$res = array('status'=>true,'res'=>'不在购买的时间段');
		$h = date('H',TIMESTAMP);
		$m = date('i',TIMESTAMP);

		$nowm = $h*60 + $m;

		$starth = $type == 0 ? $set['shopsh'] : ( $type == 1 ? $set['xuansh'] : $set['oshopsh'] );
		$startm = $type == 0 ? $set['shopsm'] : ( $type == 1 ? $set['xuansm'] : $set['oshopsm'] );
		$endh = $type == 0 ? $set['shopeh'] : ( $type == 1 ? $set['xuaneh'] : $set['oshopeh'] );
		$endm = $type == 0 ? $set['shopem'] : ( $type == 1 ? $set['xuanem'] : $set['oshopem'] );

		$allstartm = $starth*60 + $startm;
		$allendm = $endh*60 + $endm;

		if( $allstartm <= 0 && $allendm <= 0 ) return array('status'=>false,'res'=>'');

		if( $nowm >= $allstartm && $nowm <= $allendm ) {
			return array('status'=>false,'res'=>'');
		}

		$smstr = empty( $startm ) ? '' : $startm.'分';
		$emstr = empty( $endm ) ? '' : $endm.'分';

		$res = array('status'=>true,'res'=>'只能在'.$starth.'点'.$smstr.'至'.$endh.'点'.$emstr.'之间购买');

		return $res;
	}

	static function orderData( $plug=0 ){
		global $_W;
		$cache = Util::getCache('alld'.$plug,'all');
		if( empty( $cache ) || $cache['expire'] < TIMESTAMP ){
			$cache = array();
			
			$cache['ordering'] = $cache['orderpayed'] = $cache['ordersend'] = $cache['ordercom'] = $cache['refund'] = 0;

			$all = pdo_getall('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'plug'=>$plug),array('status','paytype'));

			if( !empty( $all ) ) {
				foreach ( $all as $v ) {
					if( $v['status'] == 0 && $v['paytype'] == 0 ) $cache['ordering'] ++;
					if( $v['status'] == 1 || ( $v['status'] == 0 && $v['paytype'] == 1 ) ) $cache['orderpayed'] ++;
					if( $v['status'] == 2 ) $cache['ordersend'] ++;
					if( $v['status'] == 3 ) $cache['ordercom'] ++;
					if( $v['status'] == 4 ) $cache['refund'] ++;
				}
			}
			$cache['expire'] = TIMESTAMP + 300;
			Util::setCache('alld'.$plug,'all',$cache);
		}
		return $cache;
	}

	// 取消删除订单
	static function cancelOrder( $oid,$openid='' ){
		global $_W;

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$oid,'status'=>0),array('id','openid','plug'));
		if( empty( $order ) ) return false;
		if( !empty( $openid ) && $openid != $order['openid'] ) return false;
		
		pdo_delete('zxbddiy_sitetemp_order',array('id'=>$order['id']));
		pdo_delete('zxbddiy_sitetemp_ordergood',array('oid'=>$oid,'uniacid'=>$_W['uniacid']));
		Util::deleteCache('d',$order['openid']);
		Util::deleteCache('alld','all'.$order['plug']);

		return true;
	}

	// 订单完成
	static function comOrder( $oid,$openid='' ){
		global $_W;

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$oid),array('id','openid','taketype','status','type','paytype','plug'));
		
		if( empty( $order ) ) return false;
		
		if( !empty( $openid ) && $openid != $order['openid'] ) return false;
		
		if( $order['taketype'] == 1 && $order['type'] == 0 && $order['status'] != 2 && $order['paytype'] == 0 ) return false;
				
		//if( $order['taketype'] == 1 && $order['type'] == 1 && ( $order['status'] != 1 && $order['paytype'] == 1 ) ) return false;

		if( $order['taketype'] == 2 && $order['status'] != 1 ) return false;
		
		pdo_update('zxbddiy_sitetemp_order',array('status'=>3,'comtime'=>TIMESTAMP),array('id'=>$order['id']));
		
		Util::deleteCache('d',$order['openid']);
		Util::deleteCache('alld','all'.$order['plug']);

		return true;

	}
	
	// 发货处理
	static function sendOrder($oid,$expressname,$expressnum){
		global $_W;

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$oid));
		
		if( empty( $order ) ) return false;
		if( empty($order['status']) ) return false;
		
		if( $order['taketype'] == 2 ) return false;
		
		pdo_update('zxbddiy_sitetemp_order',array('status'=>2,'sendtime'=>TIMESTAMP,'expressname'=>$expressname,'expressnum'=>$expressnum),array('id'=>$order['id']));
		
		Util::deleteCache('d',$order['openid']);
		Util::deleteCache('alld','all'.$order['plug']);
		
		return true;

	}

	static function sendMess( $orderinfo,$set ){
		global $_W;

		//发送支付成功通知
		$url = 'zxbddiy_sitetemp/pages/orderinfo/orderinfo?oid='.$orderinfo['orderid'];
		if( $orderinfo['plug'] == 1 ) $url = 'zxbddiy_sitetemp/pages/deskorder/orderinfo?oid='.$orderinfo['orderid'];
		
		Message::orderMessage($orderinfo['openid'],$set['ordermid'],$url,$orderinfo['formid'],$orderinfo['title'],$orderinfo['fee'],'已支付');
		
		// 短信通知管理员
		$issms = 0;
		if( empty($set['paysms']) && !empty( $set['paysuctel'] ) ){
			
			$auth = model_auth::authList();

			if( $auth['sms'] > 0 ){

				$sms = pdo_get('zxbddiy_sitetemp_sms');
				$sms['template'] = iunserializer( $sms['template'] );

				if( !empty( $sms ) ){

					$sms['template'] = iunserializer( $sms['template'] );
					$type = $orderinfo['type'] == 0 ? ( $orderinfo['taketype'] == 1 ? '快递配送' : '上店自提' ) : '上门配送';
					$params = '{"title":"'.$orderinfo['title'].'","fee":"'.$orderinfo['fee'].'","type":"'.$type.'"}';

					$smsjoj = new model_sms();
					$res = $smsjoj->sendSms($sms['key'],$sms['secret'],$sms['signature'], $sms['template']['paysuc'], $set['paysuctel'], $params);
					$res = json_decode($res,true);
											
					if( $res['Code'] == 'OK' ){
						$issms = 1;
						// 减短信数量
						Util::addOrMinusOrUpdateData( 'zxbddiy_sitetemp_auth',array('sms'=>-1),$auth['id'] );
					}
				}
			}
		}


		if( $issms == 0 && !empty( $set['mail'] ) ){ // 只发一次通知
			// 管理员邮件通知
			$body = array(
				'卖出商品通知',
				'订单编号:'.$orderinfo['id'],
				'订单名称:'.$orderinfo['title'],
				'订单金额:'.$orderinfo['fee'],
			);
			$res = Util::ihttp_email($set['mail'], '订单通知', $body);
		}
		
		$type = empty($orderinfo['plug']) ? 0 : 2;

		// 发送打印数据
		model_print::printOrder( $orderinfo['orderid'],$set,$type );

	}



	// taketype 1 快递配送 2自提
	static function initOrder( $buyarr,$taketype ,$type = '',$deskid='',$plug=0){
		global $_W;

		if( empty( $buyarr ) || !is_array( $buyarr ) ) return array('status'=>false,'res'=>'请选择商品');

		$res = array('status'=>true);

		$iscanself = 1; // 默认允许选择自提
		$iscanexpress = 1; // 默认允许选择快递
		$taketype = $taketype; // 1快递配送 2自提
		$list = array();
		$total = 0; // 总的价格
		$totalexpress = 0;

		foreach ($buyarr as $v) {
			
			$good = model_good::getGood( $v['gid'] );
			if( empty( $good ) ) return array('status'=>false,'res'=>'您购买的商品不存在');
			if( $good['status'] == 1 ) return array('status'=>false,'res'=>'您购买的商品已下架');

			$item = array();
			$item['gid'] = $good['id'];
			$item['title'] = $good['title'];
			$item['thumb'] = tomedia( $good['thumb'] );
			$item['cartid'] = $v['cartid'];
			
			// 商品有规格
			if( $good['isrule'] ){
				if( empty( $v['rule'] ) ) return array('status'=>false,'res'=>'请选择商品规格');

				$isin = 0;
				foreach ( $good['rulearray']['rulemap'] as $vv ) {
					if( $vv['id'] == $v['rule'] ){
						$isin = 1;

						if( $v['num'] > $vv['stock'] ) return array('status'=>false,'res'=>'商品库存不足');

						$item['num'] = $v['num'];
						$item['price'] = $vv['nowprice'];
						$item['ruleid'] = $v['rule'];
						$item['rulename'] = str_replace(':','，', $vv['name']);
						$item['money'] = $v['num']*$vv['nowprice'];
						$item['expressmoney'] = $good['expressmoney'];
						$item['isaddex'] = $good['isaddex'];

						$total += $item['money'];
					}
				}

				// 规格不存在
				if( $isin == 0 ) return array('status'=>false,'res'=>'请选择商品规格');

			// 无规格
			}else{

				if( $v['num'] > $good['stock'] ) return array('status'=>false,'res'=>'商品库存不足');

				$item['num'] = $v['num'];
				$item['price'] = $good['price'];
				$item['money'] = $v['num']*$good['price'];
				$item['expressmoney'] = $good['expressmoney'];
				$item['isaddex'] = $good['isaddex'];

				$total += $item['money'];
			}
			$list[] = $item;

			// 是否开放自提
			if( $good['isselftake'] == 0 ) $iscanself = 0;
			if( $good['isexpress'] == 1 )  $iscanexpress = 0;

		}
		
		// 选择自提时候
		if( $taketype == 2 ){
			if( $iscanself == 0 ) { // 不能自提强制改成快递配送
				$taketype = 1;
			}

		}

		// 快递配送的时候
		if( $taketype == 1 ){ 
			if( $iscanexpress == 0 && $iscanself == 1 ){ // 不能快递发货，而且可以自提的时候选择自提
				$taketype = 2;
			}
		}

		//经过上步判断后计算快递费和总的价格
		if( $taketype == 1 ){

			if( $type == 'ex' ){ // 快速下单界面

				$set = Util::getModuleConfig();

				if( empty( $plug ) ) {
					if( $set['sendmoney'] > 0 && empty( $deskid ) ) { // 配送费 ，不是扫码点餐的时候才收取
						$totalexpress = $set['sendmoney'];
						if( $set['freesend'] > 0 && $total >= $set['freesend'] ){
							$totalexpress = 0;
						}
						$total += $totalexpress;
					}
				}elseif( $plug == 1 ){
					if( $set['osendmoney'] > 0 && empty( $deskid ) ) { // 配送费 ，不是扫码点餐的时候才收取
						$totalexpress = $set['osendmoney'];
						if( $set['ofreesend'] > 0 && $total >= $set['ofreesend'] ){
							$totalexpress = 0;
						}
						$total += $totalexpress;
					}
				}

			}else{
				
				foreach ($list as &$v) {
					
					if( $v['isaddex'] == 1 ){ // 不叠加
						$v['costexpress'] = $v['expressmoney'];
					}else{
						$v['costexpress'] = $v['expressmoney']*$v['num'];
					}

					$v['money'] += $v['costexpress'];

					$total += $v['costexpress'];
					$totalexpress += $v['costexpress'];
				}
				unset( $v );

			}
		}

		return array(
			'type' => $type == 'ex' ? 1 : 0,
			'status' => true,
			'iscanself' => $iscanself,
			'iscanexpress' => $iscanexpress,
			'taketype' => $taketype,
			'list' => $list,
			'total' => $total,
			'totalexpress' => $totalexpress,
		);

	}
	





}
