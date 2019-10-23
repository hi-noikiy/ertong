<?php 


class model_appoint {
	
	
	static function getAppoint( $id ){
		global $_W;
		
		$cache = Util::getCache('appoint',$id);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'id'=>$id);
			$cache = pdo_get('zxbddiy_sitetemp_appoint',$where);
			
			if( !empty( $cache ) ){
				$cache['form'] = iunserializer( $cache['form'] );
				$cache['pic'] = iunserializer( $cache['pic'] );
				$cache['timedata'] = iunserializer( $cache['timedata'] );

				if( !empty( $cache['pic'] ) ) {
					foreach ( $cache['pic'] as &$v ) {
						$v = tomedia( $v );
					}
					unset( $v );
				}

				Util::setCache('appoint',$id,$cache);
			}
		}
		return $cache;
	}
	
	static function structTime( $timedata,$aid ){
		global $_W;
		$ahead = TIMESTAMP + $timedata['aheadtime']*3600;

		$time = array();
		$days = empty( $timedata['days'] ) ? 1 : $timedata['days'];
		$issetarr = array();
		if( $timedata['numlimit'] > 0 ){
			$allorder = pdo_getall('zxbddiy_sitetemp_appointorder',array('aid'=>$aid,'status'=>array(1,2)),array('apptime'));		
			if( !empty( $allorder ) ){
				foreach ($allorder as $v) {
					if( empty( $issetarr[$v['apptime']] ) ) {
						$issetarr[$v['apptime']] = 0;
					}
					$issetarr[$v['apptime']] ++;
				}
			}
		}
		
		if( empty( $timedata['timetype'] ) ){

			for ($i=0; $i < $days; $i++) {
				$item = $ahead + $i*3600*24;
				$week = date('w',$item);	
				if( ($timedata['weektype'] == 1 && $week == 0) || ($timedata['weektype'] == 2 && $week == 6) || ($timedata['weektype'] == 3 && in_array( $week ,array(6,0) )) ) {
					$days ++;
					continue;
				}

				$timeitem = array('status'=>0,'d'=>date('Y-m-d',$item));

				if( ($timedata['numlimit'] > 0 && $issetarr[$timeitem['d']] >= $timedata['numlimit']) || $ahead >= $item ){
					$timeitem['status'] = 1;
				}

				$time[] = $timeitem;
			}



		}elseif( $timedata['timetype'] == 1 ){
			
			for ($i=0; $i < $days; $i++) {
				$item = $ahead + $i*3600*24;
				
				$week = date('w',$item);	
				if( ($timedata['weektype'] == 1 && $week == 0) || ($timedata['weektype'] == 2 && $week == 6) || ($timedata['weektype'] == 3 && in_array( $week ,array(6,0) )) ) {
					$days ++;
					continue;
				}

				$time[]['d'] = date('Y-m-d',$item);
			}

			if( !empty( $timedata['limittime'] ) ) {
						
				foreach ($time as $k => $v) {
					$time[$k]['t'] = array();
					foreach ( $timedata['limittime'] as $kk => $vv ) {
						
						$start = strtotime( $v['d'] ) + $vv['ltimesh']*3600 + $vv['ltimesm']*60;
						$end = strtotime( $v['d'] ) + $vv['ltimeh']*3600 + $vv['ltimeem']*60;
						$timeitem = array('status'=>0,'start'=>date('H:i',$start),'end'=>date('H:i',$end),'starts'=>$start,'ends'=>$end );
						
						$key = $v['d'].' '.$timeitem['start'].'-'.$timeitem['end'];
						if( ($timedata['numlimit'] > 0 && $issetarr[$key] >= $timedata['numlimit']) || $ahead >= $end ){
							$timeitem['status'] = 1;
						}
						
						$time[$k]['t'][] = $timeitem;
					}
				}
			}
		}
		return $time;

	}

	static function orderData(){
		global $_W;
		$cache = Util::getCache('allapp','all');
		if( empty( $cache ) || $cache['expire'] < TIMESTAMP ){
			$cache = array();
			$cache['allorder'] = $cache['ordering'] = $cache['taking'] = $cache['taked'] = $cache['comed'] = $cache['canceled'] = $cache['refund'] = 0;

			$all = pdo_getall('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid']),array('status'));

			if( !empty( $all ) ) {
				foreach ( $all as $v ) {
					$cache['allorder'] ++;
					if( $v['status'] == 0 ) $cache['ordering'] ++;
					if( $v['status'] == 1 ) $cache['taking'] ++;
					if( $v['status'] == 2 ) $cache['taked'] ++;
					if( $v['status'] == 3 ) $cache['comed'] ++;
					if( $v['status'] == 4 ) $cache['canceled'] ++;
					if( $v['status'] == 5 ) $cache['refund'] ++;
				}
			}
			$cache['expire'] = TIMESTAMP + 300;
			Util::setCache('allapp','all',$cache);
		}
		return $cache;
	}



	static function sendMess( $orderinfo,$set,$status,$tips ){
		global $_W;

		//发送支付成功通知
		$url = 'zxbddiy_sitetemp/pages/appoint/orderinfo?oid='.$orderinfo['orderid'];
		$statusstr = $status == 1 ? '待接单' : '已接单';
		Message::apporderMessage($orderinfo['openid'],$set['appointmid'],$url,$orderinfo['formid'],$orderinfo['title'],$statusstr,$tips);

		// 短信通知管理员
		$auth = model_auth::authList();
		$app = self::getAppoint( $orderinfo['aid'] );
				
		if( $auth['sms'] > 0 && !empty( $app['tel'] ) ){

			$sms = pdo_get('zxbddiy_sitetemp_sms');
			$sms['template'] = iunserializer( $sms['template'] );

			if( !empty( $sms ) && !empty( $sms['template']['apptem'] ) ){

				$form = iunserializer( $orderinfo['form'] );

				$content = '';
				if( !empty( $orderinfo['tel'] ) ) $content = '电话号码：'.$orderinfo['tel'].'。';
				if( !empty( $form ) ){
					foreach ( $form as $v ) {
						if( is_array( $v['value'] ) ){
							$item = $v['name'].'：';
							foreach ($v['value'] as $vv) {
								$item .= $vv.'，';
							}
							$item = trim($item,'，');
							$content .= $item.'。';
						}else{
							$content .= $v['name'].'：'.$v['value'].'。';
						}
					}
				}
				
				$name = cutstr($app['name'],19, false);
				$content = cutstr($content,19, false);

				$params = '{"name":"'.$app['name'].'","detail":"'.$content.'"}';

				$smsjoj = new model_sms();
				$res = $smsjoj->sendSms($sms['key'],$sms['secret'],$sms['signature'], $sms['template']['apptem'], $app['tel'], $params);
				$res = json_decode($res,true);
										
				if( $res['Code'] == 'OK' ){
					$issms = 1;
					// 减短信数量
					Util::addOrMinusOrUpdateData( 'zxbddiy_sitetemp_auth',array('sms'=>-1),$auth['id'] );
				}
			}
		}
		


		if( !empty( $set['mail'] ) ){ // 只发一次通知
			// 管理员邮件通知
			$body = array(
				'预约通知',
			);
			if( !empty( $orderinfo['tel'] ) ) {
				$body[] = '电话:'.$orderinfo['tel'];
			}
			
			if( !empty( $orderinfo['form'] ) ) {
				$form = iunserializer( $orderinfo['form'] );
				if( !empty( $form ) ) {
					foreach ( $form as $v ) {

						if( is_array( $v['value'] ) ) {
							$str = '';
							foreach ($v['value'] as $vv) {
								$str .= $vv.'，';
							}
							$body[] = $v['name'] .':'.$str;
						}else{
							$body[] = $v['name'] .':'.$v['value'];
						}
						
					}
				}
			}

			$res = Util::ihttp_email($set['mail'], '预约通知', $body);
		}

		model_print::printappOrder( $orderinfo['orderid'],$set );

	}
	
}
