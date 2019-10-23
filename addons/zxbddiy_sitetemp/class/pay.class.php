<?php 

class pay {

	//file_put_contents(MODULE_ROOT."/params.log", var_export($params, true).PHP_EOL, FILE_APPEND);
	static function payResult($params,$set){
		global $_W;
		if($params['result'] == 'success' && $params['from'] == 'notify'){

			$orderinfo = pdo_get('zxbddiy_sitetemp_order',array('status'=>0,'orderid'=>$params['tid']));
			$_W['uniacid'] = $orderinfo['uniacid'];
			$type = 1;
			if( empty( $orderinfo ) ) {
				$orderinfo = pdo_get('zxbddiy_sitetemp_appointorder',array('status'=>0,'orderid'=>$params['tid']));
				$_W['uniacid'] = $orderinfo['uniacid'];
				$type = 2;
			}

			if( !empty($orderinfo) &&  sprintf('%.2f',$orderinfo['fee']) == sprintf('%.2f',$params['fee']) ){
				
				if( $type == 1 ) {

					$res = pdo_update('zxbddiy_sitetemp_order',array('status' => 1,'uorderid'=>$params['uniontid'],'paytime'=>TIMESTAMP),array('uniacid' => $_W['uniacid'],'orderid' => $params['tid']));
					$ordergood = pdo_getall('zxbddiy_sitetemp_ordergood',array('uniacid'=>$_W['uniacid'],'oid'=>$params['tid']));

					foreach ( $ordergood as $v ) {
						$good = model_good::getGood( $v['gid'] );
						
						$up = array('realsales'=>$v['num'],'sales'=>$v['num']);
						if( $good['isrule'] == 0 ) {
							$up['stock'] = -$v['num'];
						}else{
							// 减规格库存
//file_put_contents(MODULE_ROOT."/params.log", var_export( $good['rulearray']['rulemap'] , true).PHP_EOL, FILE_APPEND);							
							foreach ( $good['rulearray']['rulemap'] as $kk => $vv ) {							
								if( $vv['id'] == $v['ruleid'] ){									
									$now = $vv['stock'] >= $v['num'] ? ( $vv['stock'] - $v['num'] ) : 0;
									$good['rulearray']['rulemap'][$kk]['stock'] = $now;
									$se = iserializer( $good['rulearray'] );
									pdo_update('zxbddiy_sitetemp_good',array('rulearray'=>$se),array('id'=>$good['id']));
								}
							}

						}

						// 加销量，减库存
						Util::addOrMinusOrUpdateData('zxbddiy_sitetemp_good',$up,$good['id']);
						Util::deleteCache('good',$good['id']);

						// 删除购物车
						if( !empty( $v['cartid'] ) ) {
							pdo_delete('zxbddiy_sitetemp_cart',array('id'=>$v['cartid']));
							Util::deleteCache('cart',$orderinfo['openid']);
						}

					}
					
					Util::deleteCache('d',$orderinfo['openid']);
					Util::deleteCache('alld','all');
					model_order::sendMess( $orderinfo,$set );

				// 预约订单
				}elseif( $type == 2 ){
					$status = $set['isautotake'] ? 2 : 1;
					$res = pdo_update('zxbddiy_sitetemp_appointorder',array('status' => $status,'uorderid'=>$params['uniontid']),array('uniacid' => $_W['uniacid'],'orderid' => $params['tid']));

					// 加预约量
					$up = array('num'=>1,'realnum'=>1);
					Util::addOrMinusOrUpdateData('zxbddiy_sitetemp_appoint',$up,$orderinfo['aid']);
					Util::deleteCache('appoint',$orderinfo['aid']);
					Util::deleteCache('apd',$orderinfo['openid']);
					Util::deleteCache('allapp','all');

					$app = pdo_get('zxbddiy_sitetemp_appoint',array('id'=>$orderinfo['aid']),array('tips'));

					model_appoint::sendMess( $orderinfo,$set,$status,$app['tips'] );
					
				}

			}
		}
				
		
	}

	static function createOrderId($uid){
		$time = date('YmdHis',TIMESTAMP);
		return $uid.$time.rand(10,99);
	}
	
	static function returnPay($orderid,$fee,$title,$openid){
		global $_W;
		$params['tid'] = $orderid;
		$params['user'] = $openid;
		$params['fee'] = $fee;
		$params['title'] = cutstr($title,40, false);
		return $params;
	}

	



}