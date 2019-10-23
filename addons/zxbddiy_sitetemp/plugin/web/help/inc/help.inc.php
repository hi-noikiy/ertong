<?php 
	global $_W,$_GPC;
	
	
	
	$auth = model_auth::authList();

	$urlarr = array(
		array('name'=>'主页','url'=>'/zxbddiy_sitetemp/pages/page/page'),
		array('name'=>'查看表单数据','url'=>'/zxbddiy_sitetemp/pages/form/form'),
		array('name'=>'文章列表','url'=>'/zxbddiy_sitetemp/pages/artlist/artlist'),
		array('name'=>'产品列表','url'=>'/zxbddiy_sitetemp/pages/product/list'),
	);

	if( $auth['isshop'] == 1 ) {
		$urlarr[] = array('name'=>'商品分类','url'=>'/zxbddiy_sitetemp/pages/gsort/gsort');
		$urlarr[] = array('name'=>'商品列表','url'=>'/zxbddiy_sitetemp/pages/goodlist/goodlist');
		$urlarr[] = array('name'=>'购物车','url'=>'/zxbddiy_sitetemp/pages/cart/cart');
		$urlarr[] = array('name'=>'个人中心','url'=>'/zxbddiy_sitetemp/pages/user/user');
		$urlarr[] = array('name'=>'订单列表','url'=>'/zxbddiy_sitetemp/pages/orderlist/orderlist');
		$urlarr[] = array('name'=>'选购界面','url'=>'/zxbddiy_sitetemp/pages/exbuy/exbuy');
	}
	
	if( $auth['isappoint'] == 1 ) {
		$urlarr[] = array('name'=>'预约列表','url'=>'/zxbddiy_sitetemp/pages/appoint/list');
		$urlarr[] = array('name'=>'我的预约','url'=>'/zxbddiy_sitetemp/pages/appoint/olist');
		$urlarr[] = array('name'=>'预约管理(管理员)','url'=>'/zxbddiy_sitetemp/pages/appoint/adminlist');
	}

	if( $auth['iscard'] == 1 ) {
		$urlarr[] = array('name'=>'我的卡券','url'=>'/zxbddiy_sitetemp/pages/card/mycard');
		$urlarr[] = array('name'=>'官网卡券列表','url'=>'/zxbddiy_sitetemp/pages/card/list?type=0');
	}

	if( $_GPC['op'] == 'verify' ) {

		$domain =  str_replace(array('https://','http://','/'),array('','',''),$_W['siteroot']);
		//$domain = 'www.shiyebian.net';
		$api = 'https://qcwss.qcloud.com/ats/ajax?action=getDomainATSStatus&domain='.$domain.'&port=443';
		$res = Util::HttpGet( $api );
		
		$res = json_decode( $res,true );

		//
		if( !empty( $res ) ){

			if( $res['status']['code'] === 0 ) {

				if( $res['result']['data']['ats'] ){
					$httpsres = array('status'=>200,'res'=>'https配置正常');
				}else{
					$resstra = '';
					if( !$res['result']['data']['domain_matched'] ) $resstra .= '证书与域名不匹配，';
					if( !$res['result']['data']['cert_time_valid'] ) $resstra .= '证书已过期，';
					if( !$res['result']['data']['server_gte_tls1_2'] ) $resstra .= '证书不支持TLS1.2，';

					$httpsres = array('status'=>201,'res'=>$resstra);
				}
				
			}else{
				$httpsres = array('status'=>201,'res'=>$res['status']['msg'].'，可能此域名还没配置https');
			}
		}else{
			$httpsres = array('status'=>201,'res'=>'检测失败');
		}
		if( $httpsres['status'] == 200 ){
			$resstr = '<div class="font_green">检测域名：'.$domain.'。检测结果：';
		}else{
			$resstr = '<div class="font_ff5f27">检测域名：'.$domain.'。检测结果：';
		}
		$httpsres['res'] = $resstr.$httpsres['res'].'（若你的小程序域名不是'.$domain.'请忽略此提示）</div>';


		//
		$appurlres = array('status'=>200,'res'=>'小程序域名正常');
		$wxapp  = pdo_get('account_wxapp', array('uniacid'=>$_W['uniacid']));
		$appurl = 'https://'.$domain.'/app/index.php';
		if( $wxapp['appdomain'] != $appurl ){

			load()->model('wxapp');
			$wxapp_info = wxapp_fetch($_W['uniacid']);

			$appurlres = array('status'=>201,'res'=>'小程序域名不正确，需配置成：'.$appurl.'<p><a href="./index.php?c=wxapp&a=front-download&do=domainset&version_id='.$wxapp_info['version']['id'].'">点我去配置</a></p>');
		}
		if( $appurlres['status'] == 200 ){
			$resstr = '<div class="font_green">检测结果：';
		}else{
			$resstr = '<div class="font_ff5f27">检测结果：';
		}
		$appurlres['res'] = $resstr.$appurlres['res'].'（若你的小程序域名不是'.$domain.'请忽略此提示）</div>';

		//
		$tempres = array('status'=>200,'res'=>'模板首页设置正常');
		$issetact = pdo_get('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid'],'isact'=>1));
		if( empty( $issetact ) ){
			$tempres = array('status'=>201,'res'=>'还没使用任何模板，请添加模板并使用模板');
		}
		$bar = pdo_get('zxbddiy_sitetemp_bar',array('uniacid'=>$_W['uniacid'],'tempid'=>$issetact['id']));
		if( empty( $bar ) ) {
			$tempres = array('status'=>201,'res'=>'未找到模板导航，请设置模板的导航<p><a href="'.self::pwUrl('site','page',array('op'=>'design','op'=>'bar','tid'=>$issetact['id'])).'">点我去设置导航</a></p>');
		}

		$data = iunserializer( $bar['data'] );
		$pid = $data['data'][0]['pageid'];
		$purl = $data['data'][0]['url'];	
		
		
		if( strpos($purl, 'zxbddiy_sitetemp/pages/page/page') !== false ){
			$page = pdo_get('zxbddiy_sitetemp_page',array('id'=>$pid));
			if( empty( $page ) || $page['tempid'] != $issetact['id'] ){
				$tempres = array('status'=>201,'res'=>'模板第一个导航链接到的页面不正确，请重新设置第一个导航链接<p><a href="'.self::pwUrl('site','page',array('op'=>'design','op'=>'bar','tid'=>$issetact['id'])).'">点我去设置导航</a></p>');
			}
		}else{
			if( empty( $purl ) ){
				$tempres = array('status'=>201,'res'=>'模板第一个导航链接到的页面不正确，请重新设置第一个导航链接<p><a href="'.self::pwUrl('site','page',array('op'=>'design','op'=>'bar','tid'=>$issetact['id'])).'">点我去设置导航</a></p>');
			}
		}

		if( $tempres['status'] == 200 ){
			$resstr = '<div class="font_green">检测结果：';
		}else{
			$resstr = '<div class="font_ff5f27">检测结果：';
		}
		$tempres['res'] = $resstr.$tempres['res'].'</div>';



	}

	include $this->pTemplate('help');