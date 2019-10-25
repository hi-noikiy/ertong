<?php 

/*
	用户表类
*/
class model_user 
{	
	

	//初始化用户数据
	static function initUserInfo(){
		global $_W,$_GPC;
		
		/*if( empty($_W['openid']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false ) self::alertWechatLogin();
		
		// ip限制
		if($_W['set']['isiplimit'] == 1 && !$_W['isajax'] ){
			$ip = getip();
			
			if( empty($_SESSION['limitarea']) ){
				$_SESSION['limitarea'] = $area = Util::getarea($ip);
			}else{
				$area = $_SESSION['limitarea'];
			}

			if(!$area && $ip != '127.0.0.1') die;
			$city = $area['city'];
			
			$limit = iunserializer( $_W['set']['iplimit'] );
			foreach ((array)$limit as $v) {
				$limitstr .= $v.',';
			}
			if(strpos($limitstr,$city) !== false && strpos($_W['set']['whiteip'],$ip) === false ){
				if( $_W['set']['iplimittype'] == 0){
					echo $ip;die;
				}else{
					header('Location: http://www.baidu.com/');exit();
				}
			}
		}

		// 随机分享域名
		$_W['randsite'] =  empty( $_W['set']['mothersite'] ) || $_W['set']['randsite'] == 0  ? $_W['siteroot'] : $_W['set']['mothersite'];
		if( $_W['set']['randsite'] == 1 && $_W['siteroot'] == $_W['set']['mothersite'] && !in_array($_GPC['do'], array('confirm','ajaxdeal','pagelist',)) ){
			$sitearr = iunserializer( $_W['set']['childsite'] );
			if( !empty( $sitearr ) ){
				
				$newsitearr = array();
				foreach ($sitearr as $v) {
					if( !empty( $v['site1'] ) ) $newsitearr[] = $v['site1'];
					if( !empty( $v['site2'] ) ) $newsitearr[] = $v['site2'];
				}
				if( !empty( $newsitearr ) ) {

					$randnum = shuffle( $newsitearr );
					$randsite = $newsitearr[0];
					$purl = parse_url( $_W['siteurl'] );
					$newurl = $randsite. trim($purl['path'],'/') . '?'.$purl['query'];

					header('Location: '.$newurl);exit();

				}
			}
		}


		$userinfo = self::getSingleUser( $_W['openid'] ,1);
		// 清理更新之前的缓存
		if( empty( $userinfo['logintime'] ) ) Util::deleteCache('u',$_W['openid']);

		if(!empty($userinfo)){
			if($userinfo['status'] == 1){
						
				header( "Location: http://www.baidu.com" );
				exit();
			}

			if($userinfo['logintime'] < time()-12*3600 || empty( $userinfo['headimgurl'] ) ){
				load() -> model('mc');
				$oauthinfo = mc_oauth_userinfo();


				if( !empty( $oauthinfo['nickname'] ) ){
					$data = array('logintime'=>TIMESTAMP,'nickname' => stripcslashes($oauthinfo['nickname']),'headimgurl' => $oauthinfo['headimgurl']);

					pdo_update('zxbddiy_countprice_user', $data, array('id' => $userinfo['id'],'openid'=>$_W['openid']));
					Util::deleteCache('u',$_W['openid']);
					$userinfo = self::getSingleUser($_W['openid'],1);
				}
			}

		}else{
			load() -> model('mc');
			$oauthinfo = mc_oauth_userinfo();
			
			$userinfo = pdo_get('zxbddiy_countprice_user',array('openid'=>$_W['openid'],'uniacid'=>$_W['uniacid']));				
			if (empty($userinfo['id']) && !empty($_W['openid'])) {
				
				$data = array(
					'uniacid' => $_W['uniacid'],
					'openid' => $_W['openid'],
					'nickname' => stripcslashes($oauthinfo['nickname']),
					'headimgurl' => $oauthinfo['headimgurl'],
					'logintime' => TIMESTAMP,
					'createtime' => TIMESTAMP,
				);
				pdo_insert('zxbddiy_countprice_user', $data);
				$userinfo = self::getSingleUser($_W['openid'],1);
			}
		}

		// 是否获取地理位置
		$_W['islimit'] = 0;
		$sessionstr = $_W['openid'].'a'.$_W['uniacid'];
		if( empty( $_SESSION[$sessionstr] ) ){
			$_W['islimit'] = 1;
		}
		

		// 提示关注
		$_W['issub'] = 1;
		if( $_W['set']['issub'] == 1 ){
			$fans = pdo_get('mc_mapping_fans',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']),array('follow'));
			if( $fans['follow'] == 0 ) $_W['issub'] = 0;

		}
		
		// 强制关注
		if( $_W['set']['ismustsub'] == 1 && !$_W['isajax'] ){
			if( empty( $fans ) ) {
				$fans = pdo_get('mc_mapping_fans',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']),array('follow'));
			}
			
			if( $fans['follow'] == 0 ) {

				$subcache = array(
					'do' => $_GPC['do'],
					'op' => $_GPC['op'],
					'url' => $_W['siteurl'],
					'time' => TIMESTAMP,
				);
				Util::setCache('sub',$_W['openid'],$subcache);

				if( $_W['set']['subtype'] == 1 && !empty( $_W['set']['suburl'] ) ){
					header('Location: '.$_W['set']['suburl']);
					exit();
				}
				$_W['mustsub'] = 1;
			}
		}*/

		return $userinfo;

	}
	

	static function myNum( $openid,$plug=0 ){
		global $_W;
		$cache = Util::getCache('d'.$plug,$openid);
		if( empty( $cache ) || $cache['expire'] < TIMESTAMP ){
			$cache = array();
			
			$cache['ordering'] = $cache['orderpayed'] = $cache['ordersend'] = $cache['ordercom'] = $cache['refund'] = 0;

			$all = pdo_getall('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'openid'=>$openid,'plug'=>$plug));

			if( !empty( $all ) ) {
				foreach ( $all as $v ) {
					if( $v['status'] == 0 && $v['paytype'] == 0 ) $cache['ordering'] ++;
					if( $v['status'] == 1 || ( $v['paytype'] == 1 && $v['status'] == 0 ) ) $cache['orderpayed'] ++;
					if( $v['status'] == 2 ) $cache['ordersend'] ++;
					if( $v['status'] == 3 ) $cache['ordercom'] ++;
					if( $v['status'] == 4 ) $cache['refund'] ++;
				}
			}
			$cache['myorder'] = $cache['ordering'] + $cache['orderpayed'] + $cache['ordersend'] + $cache['ordercom'] + $cache['refund'];
			$cache['expire'] = TIMESTAMP + 60;
			Util::setCache('d'.$plug,$openid,$cache);
		}
		return $cache;
	}
	
	static function myAppointNum($openid){
		global $_W;
		$cache = Util::getCache('apd',$openid);
		if( empty( $cache ) || $cache['expire'] < TIMESTAMP ){
			$cache = array();
			$cache['ordering'] = $cache['taking'] = $cache['taked'] = $cache['comed'] = $cache['canceled'] = $cache['refund'] = 0;

			$all = pdo_getall('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid'],'openid'=>$openid));

			if( !empty( $all ) ) {
				foreach ( $all as $v ) {
					if( $v['status'] == 0 ) $cache['ordering'] ++;
					if( $v['status'] == 1 ) $cache['taking'] ++;
					if( $v['status'] == 2 ) $cache['taked'] ++;
					if( $v['status'] == 3 ) $cache['comed'] ++;
					if( $v['status'] == 4 ) $cache['canceled'] ++;
					if( $v['status'] == 5 ) $cache['refund'] ++;
				}
			}
			$cache['myorder'] = $cache['ordering'] + $cache['taking'] + $cache['taked'] + $cache['comed'] + $cache['canceled'] + $cache['refund'];
			$cache['expire'] = TIMESTAMP + 60;
			Util::setCache('apd',$openid,$cache);
		}
		return $cache;
	}

	//查询一条用户数据,传入openid
	static function getSingleUser($openid){
		global $_W;
		
		$cache = Util::getCache('u',$openid);
		
		if( empty( $cache['id'] ) ){
			$cache = pdo_get('mc_mapping_fans',array('openid'=>$openid));
					
			if( !empty( $cache ) ) {
				$cache['tag'] = iunserializer( base64_decode( $cache['tag'] ) );
				$cache['headimgurl'] = $cache['tag']['headimgurl'];
				unset( $cache['tag'] );

				Util::setCache('u',$openid,$cache);
			}
		}



		return $cache;
		//需删除缓存
	}
	
	

	//查询会员余额和积分
	static function getUserCredit($openid){	
		global $_W;
		load() -> model('mc');
		$uid = mc_openid2uid($openid);
		$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
		$credtis =  mc_credit_fetch($uid);
		$cache = array('uid'=>$uid,'credit1'=>$credtis[$setting['creditbehaviors']['activity']],'credit2'=>$credtis[$setting['creditbehaviors']['currency']]);; // 1是积分 2是余额
		return $cache;
	}

	// 改变会员余额 和 积分 type 1积分 2余额
	static function updateUserCredit($openid,$value,$type,$from,$mark='zxbddiy_countprice'){
		global $_W;
		load() -> model('mc');
		$uid = mc_openid2uid($openid);
		$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
		if( $type == 1 ){
			$creditbehaviors = $setting['creditbehaviors']['activity'];
		}elseif( $type == 2 ){
			$creditbehaviors = $setting['creditbehaviors']['currency'];
		}else{
			return false;
		}
		$result = mc_credit_update($uid, $creditbehaviors, $value,array($uid,$mark,'zxbddiy_countprice',$from));
		
		$res = is_error($result);
		return !$res;
	}


	//非微信端提示
	static function alertWechatLogin(){
		global $_W;
		$falg = '';
		
		$qrcode = tomedia('qrcode_'.$_W['acid'].'.jpg');
		die("<!DOCTYPE html>
            <html><head><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                <title>提示</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
            </head>
            <body>
            <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>请在微信公众号内打开链接".$falg."。</h4><br><img width='200px' src='".$qrcode."'></div></body></html></div></div></div>
            </body></html>");
	}
	

	
}
	
	
	
