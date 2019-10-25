<?php 
	global $_W,$_GPC;
	$_W['set'] = $this->module['config'];
	if($_GPC['op'] == 'deletecache'){ 
		$res = cache_clean();
		die('1');
	}elseif($_GPC['op'] == 'editvalue'){ //修改
		$id = intval($_GPC['id']);		
		if($_GPC['name'] == 'goodnumber'){
			pdo_update('zxbddiy_sitetemp_good',array('number'=>intval( $_GPC['value'] )),array('id'=>$id,'uniacid'=>$_W['uniacid']));
			Util::deleteCache('good',$id);

		}elseif($_GPC['name'] == 'goodsortnumber'){
			pdo_update('zxbddiy_sitetemp_goodsort',array('number'=>intval( $_GPC['value'] )),array('id'=>$id,'uniacid'=>$_W['uniacid']));
			
			Util::deleteCache('goodsort','all0');
			Util::deleteCache('goodsort','one0');
			Util::deleteCache('goodsort','two0');

			Util::deleteCache('goodsort','all1');
			Util::deleteCache('goodsort','one1');
			Util::deleteCache('goodsort','two1');

		}elseif($_GPC['name'] == 'orderformnumber'){
			pdo_update('zxbddiy_sitetemp_orderform',array('number'=>intval( $_GPC['value'] )),array('id'=>$id,'uniacid'=>$_W['uniacid']));
			
			Util::deleteCache('orderform','all0');
			Util::deleteCache('orderform','all1');

		}elseif($_GPC['name'] == 'desknumber'){
			pdo_update('zxbddiy_sitetemp_desk',array('number'=>intval( $_GPC['value'] )),array('id'=>$id,'uniacid'=>$_W['uniacid']));
			
			Util::deleteCache('desk','all');

		}elseif($_GPC['name'] == 'appointnumber'){
			pdo_update('zxbddiy_sitetemp_appoint',array('number'=>intval( $_GPC['value'] )),array('id'=>$id,'uniacid'=>$_W['uniacid']));
			
			Util::deleteCache('appoint',$id);
		}



		
	}

	// 保存页面
	elseif ($_GPC['op'] == 'savepage') {

		$getdata = htmlspecialchars_decode($_GPC['data']);
		$getdata = json_decode($getdata,true);

		$basic = htmlspecialchars_decode($_GPC['basic']);
		$basic = json_decode($basic,true);
		
		$name = $basic['name'];

		$id = intval($_GPC['id']);		

		$tid = intval($_GPC['tid']);
		$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$tid));
		
		if( empty( $temp ) || ( !empty( $temp ) && $temp['uniacid'] != $_W['uniacid'] && $_W['role'] != 'founder' ) )
			Util::echoResult(201,'请返回重新选择模板');
		
		if( empty( $name ) ) Util::echoResult(201,'请设置备注名称，在基本参数内设置');
			
		$content = iserializer(array('basic' => $basic,'data' => $getdata));

		$data = array(
			'uniacid' => $_W['uniacid'],
			'tempid' => $tid,
			'params'=>$content,
			'createtime'=>TIMESTAMP,
			'name' => $name,
			'isinit' => 0,
		);	
		
		if($id > 0){
			$thispage = pdo_get('zxbddiy_sitetemp_page',array('id'=>$id));
			if( $thispage['name'] != $name ) {
				$isset = pdo_get('zxbddiy_sitetemp_page',array('name'=>$name,'uniacid'=>$_W['uniacid'],'tempid'=>$tid));
				if( !empty( $isset ) ) Util::echoResult(201,'备注名称已被占用，请换一个备注名称(基本信息内修改)');
			}
			unset( $data['uniacid'] );
			$res = pdo_update('zxbddiy_sitetemp_page',$data,array('id'=>$id));
			$pid = $id;
		}else{

			$isset = pdo_get('zxbddiy_sitetemp_page',array('name'=>$name,'uniacid'=>$_W['uniacid'],'tempid'=>$tid));
			if( !empty( $isset ) ) Util::echoResult(201,'备注名称已被占用，请换一个备注名称(基本信息内修改)');

			$res = pdo_insert('zxbddiy_sitetemp_page',$data);
			$id = pdo_insertid();
			$pid = $id;
		}
		if($res){
			Util::deletecache('page',$id);
			Util::deletecache('botbar','all');
			Util::deletecache('botbar',$tid);
			//Util::echoResult(200,'已保存',array('name'=>$name,'id'=>$id));
		}


		// 保存导航
		if( $_GPC['bar']['bartype'] == 1 ) {

			$json['pages'] = array(
				"zxbddiy_sitetemp/pages/page/pagea",
				"zxbddiy_sitetemp/pages/page/page",
				"zxbddiy_sitetemp/pages/goodlist/goodlist",
				
				"zxbddiy_sitetemp/pages/orderinfo/orderinfo",
				"zxbddiy_sitetemp/pages/orderlist/orderlist",
				
				"zxbddiy_sitetemp/pages/user/user",
				"zxbddiy_sitetemp/pages/user/address",
				"zxbddiy_sitetemp/pages/good/good",
				"zxbddiy_sitetemp/pages/cart/cart",
				"zxbddiy_sitetemp/pages/card/info",
				"zxbddiy_sitetemp/pages/card/mycard",
				"zxbddiy_sitetemp/pages/card/list",
				"zxbddiy_sitetemp/pages/confirm/confirm",
				"zxbddiy_sitetemp/pages/exconfirm/exconfirm",
				"zxbddiy_sitetemp/pages/gsort/gsort",
				
				"zxbddiy_sitetemp/pages/artlist/artlist",
				"zxbddiy_sitetemp/pages/art/art",
				"zxbddiy_sitetemp/pages/form/form",
				"zxbddiy_sitetemp/pages/hexiao/hexiao",
				"zxbddiy_sitetemp/pages/hexiao/hexiaocard",
				"zxbddiy_sitetemp/pages/bindadmin/bindadmin",
				"zxbddiy_sitetemp/pages/bindadmin/bindcardadmin",		
				"zxbddiy_sitetemp/pages/exbuy/exbuy",
				"zxbddiy_sitetemp/pages/adminshop/orderlist",
				"zxbddiy_sitetemp/pages/adminshop/orderinfo",
				"zxbddiy_sitetemp/pages/webview/webview",

				"zxbddiy_sitetemp/pages/appoint/list",
				"zxbddiy_sitetemp/pages/appoint/info",
				"zxbddiy_sitetemp/pages/appoint/orderinfo",
				"zxbddiy_sitetemp/pages/appoint/adminlist",
				"zxbddiy_sitetemp/pages/appoint/olist",
				"zxbddiy_sitetemp/pages/appoint/orderinfoadmin",
				"zxbddiy_sitetemp/pages/share/shareweb",
				"zxbddiy_sitetemp/pages/page/pageb",
				"zxbddiy_sitetemp/pages/page/pagec",
				"zxbddiy_sitetemp/pages/page/paged",
				"zxbddiy_sitetemp/pages/page/pagee",
			    "zxbddiy_sitetemp/pages/deskorder/exbuy",
			    "zxbddiy_sitetemp/pages/deskorder/confirm",
			    "zxbddiy_sitetemp/pages/deskorder/exconfirm",
			    "zxbddiy_sitetemp/pages/deskorder/good",
			    "zxbddiy_sitetemp/pages/deskorder/orderinfo",
			    "zxbddiy_sitetemp/pages/deskorder/orderlist",
			    "zxbddiy_sitetemp/pages/deskorder/user",
			    "zxbddiy_sitetemp/pages/deskorder/adminolist",
			    "zxbddiy_sitetemp/pages/deskorder/adminoinfo",
    			"zxbddiy_sitetemp/pages/product/product",
    			"zxbddiy_sitetemp/pages/product/list"

			);
			$json['window'] = array(
				'navigationBarTextStyle' => empty($_GPC['bar']['topcolor']) ? '#ffffff' : $_GPC['bar']['topcolor'],
				'navigationBarTitleText' => '',
				'navigationBarBackgroundColor' => $_GPC['bar']['topbg'],
				'backgroundColor' => '#ffffff',
				'onReachBottomDistance' => 50,
				'enablePullDownRefresh' => true,
			);
			$json['tabBar']['isSystemTabBar'] = "1";
			$json['tabBar']['borderStyle'] = empty($_GPC['bar']['wxbjcolor']) ? 'black' : $_GPC['bar']['wxbjcolor'];
			$json['tabBar']['color'] = empty($_GPC['bar']['color']) ? '#000000' : $_GPC['bar']['color'];
			$json['tabBar']['selectedColor'] = empty($_GPC['bar']['actcolor']) ? '#000000' : $_GPC['bar']['actcolor'];
			$json['tabBar']['backgroundColor'] = empty($_GPC['bar']['bgcolor']) ? '#ffffff' : $_GPC['bar']['bgcolor'];
			//$json['tabBar']['color'] = '';
			//$json['tabBar']['selectedColor'] = '';
			//$json['tabBar']['backgroundColor'] = '';

			$barlist = array();
			for ($i=0; $i < $_GPC['bar']['num']; $i++) { 
				if( empty( $_GPC['bar']['data'][$i]['name'] ) ){
					Util::echoResult(201,'第'.($i+1).'个导航没有设置名称');
				}
				if( strpos($_GPC['bar']['data'][$i]['url'],'zxbddiy_sitetemp/pages/page/page') !== false || empty( $_GPC['bar']['data'][$i]['url'] ) ){
					if( $i == 0 ) $url = 'zxbddiy_sitetemp/pages/page/pagea';
					if( $i == 1 ) $url = 'zxbddiy_sitetemp/pages/page/pageb';
					if( $i == 2 ) $url = 'zxbddiy_sitetemp/pages/page/pagec';
					if( $i == 3 ) $url = 'zxbddiy_sitetemp/pages/page/paged';
					if( $i == 4 ) $url = 'zxbddiy_sitetemp/pages/page/pagee';
				}else{
					$list = explode('?',$_GPC['bar']['data'][$i]['url']);
					$url = trim($list[0],'/');
				}

				// 设置图标尺寸
				$fontsizesys = intval( $_GPC['bar']['fontsizesys'] );

				if( empty( $_GPC['bar']['data'][$i]['img'] ) ) {
					Util::echoResult(201,'第'.($i+1).'个导航没有设置默认图标');
				}else{
					$img = $_GPC['bar']['data'][$i]['img'];
					
					if( $fontsizesys > 0 ) {
						$img_info = getimagesize($img);
						if( $img_info[0] != $fontsizesys || $img_info[1] != $fontsizesys ) {
							$img = Util::wxapp_code_path_convert( $img,$fontsizesys );
						}
					}
					$_GPC['bar']['data'][$i]['img'] = $img;
					
				}
				if( empty( $_GPC['bar']['data'][$i]['img'] ) ) {
					Util::echoResult(201,'第'.($i+1).'个导航没有设置激活图标');
				}else{
					$actimg = $_GPC['bar']['data'][$i]['actimg'];

					if( $fontsizesys > 0 ) {
						$img_info = getimagesize($actimg);
						if( $img_info[0] != $fontsizesys || $img_info[1] != $fontsizesys ) {
							$actimg = Util::wxapp_code_path_convert( $actimg,$fontsizesys );
						}
					}
					$_GPC['bar']['data'][$i]['actimg'] = $actimg;
				}

				$barlist[] = array(
			        "iconSelectedPath" => "",
			        "iconPath" => $img,
			        "pagePath" => $url,
			        "text" => $_GPC['bar']['data'][$i]['name'],
			        "selectedIconPath" => $actimg,
		      	);
			}
			if( count( $barlist ) <= 0 ) Util::echoResult(201,'还没有设置导航');
			$json['tabBar']['list'] = $barlist;

			$firsturl = $barlist[0]['pagePath'];
			foreach ($json['pages'] as $k => $v) {
				if( $v == $firsturl ){
					unset( $json['pages'][$k] );
				}
			}
			array_unshift($json['pages'], $firsturl);
				
			pdo_update('wxapp_versions', array('appjson' => iserializer($json), 'use_default' => 0), array('id' => $_GPC['version_id']));

		}else{
			$v = pdo_get('wxapp_versions',array('id' => $_GPC['version_id']));
			$json = iunserializer( $v['appjson'] );
			$json['tabBar']['isSystemTabBar'] = "0";
			pdo_update('wxapp_versions', array('appjson' => iserializer($json)), array('id' => $_GPC['version_id']));
		}


		$data = array(
			'uniacid' => $_W['uniacid'],
			'data'=> iserializer( $_GPC['bar'] ),
			'createtime'=>TIMESTAMP,
			'tempid' => $tid,
		);
		
		$isset = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$tid),array('id'));

		if( !empty( $isset['id'] ) ){
			$resa = pdo_update('zxbddiy_sitetemp_bar',$data,array('id'=>$isset['id']));
			$id = $isset['id'];
		}else{
			$resa = pdo_insert('zxbddiy_sitetemp_bar',$data);
			$id = pdo_insertid();
		}
		if($resa){

			Util::deletecache('bar',$id);
			Util::deletecache('botbar','all');
			Util::deletecache('botbar',$tid);
			
		}
		$str = '';
		if( $_GPC['bar']['bartype'] == 1 && $resa ){
			$str = '，请在模块主页面点击‘上传微信审核’重新上传代码审核';
		}

		Util::echoResult(200,'已保存'.$str,array('name'=>$name,'id'=>$pid));



		//Util::echoResult(201,'保存失败');
	
	// 旧的保存导航
	}elseif ($_GPC['op'] == 'savebar') {

		$tid = intval($_GPC['tid']);	
		$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$tid));

		if( empty( $temp ) || ( !empty( $temp ) && $temp['uniacid'] != $_W['uniacid'] && $_W['role'] != 'founder' ) ) 
			Util::echoResult(201,'请返回重新选择模板');

		if( $_GPC['data']['bartype'] == 1 ) {

			$json['pages'] = array(
				"zxbddiy_sitetemp/pages/page/pagea",
				"zxbddiy_sitetemp/pages/page/page",
				"zxbddiy_sitetemp/pages/goodlist/goodlist",
				
				"zxbddiy_sitetemp/pages/orderinfo/orderinfo",
				"zxbddiy_sitetemp/pages/orderlist/orderlist",
				
				"zxbddiy_sitetemp/pages/user/user",
				"zxbddiy_sitetemp/pages/user/address",
				"zxbddiy_sitetemp/pages/good/good",
				"zxbddiy_sitetemp/pages/cart/cart",
				"zxbddiy_sitetemp/pages/card/info",
				"zxbddiy_sitetemp/pages/card/mycard",
				"zxbddiy_sitetemp/pages/card/list",
				"zxbddiy_sitetemp/pages/confirm/confirm",
				"zxbddiy_sitetemp/pages/exconfirm/exconfirm",
				"zxbddiy_sitetemp/pages/gsort/gsort",
				
				"zxbddiy_sitetemp/pages/artlist/artlist",
				"zxbddiy_sitetemp/pages/art/art",
				"zxbddiy_sitetemp/pages/form/form",
				"zxbddiy_sitetemp/pages/hexiao/hexiao",
				"zxbddiy_sitetemp/pages/hexiao/hexiaocard",
				"zxbddiy_sitetemp/pages/bindadmin/bindadmin",
				"zxbddiy_sitetemp/pages/bindadmin/bindcardadmin",		
				"zxbddiy_sitetemp/pages/exbuy/exbuy",
				"zxbddiy_sitetemp/pages/adminshop/orderlist",
				"zxbddiy_sitetemp/pages/adminshop/orderinfo",
				"zxbddiy_sitetemp/pages/webview/webview",

				"zxbddiy_sitetemp/pages/appoint/list",
				"zxbddiy_sitetemp/pages/appoint/info",
				"zxbddiy_sitetemp/pages/appoint/orderinfo",
				"zxbddiy_sitetemp/pages/appoint/adminlist",
				"zxbddiy_sitetemp/pages/appoint/olist",
				"zxbddiy_sitetemp/pages/appoint/orderinfoadmin",
				"zxbddiy_sitetemp/pages/share/shareweb",
				"zxbddiy_sitetemp/pages/page/pageb",
				"zxbddiy_sitetemp/pages/page/pagec",
				"zxbddiy_sitetemp/pages/page/paged",
				"zxbddiy_sitetemp/pages/page/pagee",
			    "zxbddiy_sitetemp/pages/deskorder/exbuy",
			    "zxbddiy_sitetemp/pages/deskorder/confirm",
			    "zxbddiy_sitetemp/pages/deskorder/exconfirm",
			    "zxbddiy_sitetemp/pages/deskorder/good",
			    "zxbddiy_sitetemp/pages/deskorder/orderinfo",
			    "zxbddiy_sitetemp/pages/deskorder/orderlist",
			    "zxbddiy_sitetemp/pages/deskorder/user",
			    "zxbddiy_sitetemp/pages/deskorder/adminolist",
			    "zxbddiy_sitetemp/pages/deskorder/adminoinfo",
    			"zxbddiy_sitetemp/pages/product/product",
    			"zxbddiy_sitetemp/pages/product/list"

			);
			$json['window'] = array(
				'navigationBarTextStyle' => empty($_GPC['data']['topcolor']) ? '#ffffff' : $_GPC['data']['topcolor'],
				'navigationBarTitleText' => '',
				'navigationBarBackgroundColor' => $_GPC['data']['topbg'],
				'backgroundColor' => '#ffffff',
				'onReachBottomDistance' => 50,
				'enablePullDownRefresh' => true,
			);
			$json['tabBar']['isSystemTabBar'] = "1";
			$json['tabBar']['borderStyle'] = empty($_GPC['data']['wxbjcolor']) ? 'black' : $_GPC['data']['wxbjcolor'];
			$json['tabBar']['color'] = empty($_GPC['data']['color']) ? '#000000' : $_GPC['data']['color'];
			$json['tabBar']['selectedColor'] = empty($_GPC['data']['actcolor']) ? '#000000' : $_GPC['data']['actcolor'];
			$json['tabBar']['backgroundColor'] = empty($_GPC['data']['bgcolor']) ? '#ffffff' : $_GPC['data']['bgcolor'];
			//$json['tabBar']['color'] = '';
			//$json['tabBar']['selectedColor'] = '';
			//$json['tabBar']['backgroundColor'] = '';

			$barlist = array();
			for ($i=0; $i < $_GPC['data']['num']; $i++) { 
				if( empty( $_GPC['data']['data'][$i]['name'] ) ){
					Util::echoResult(201,'第'.($i+1).'个导航没有设置名称');
				}
				if( strpos($_GPC['data']['data'][$i]['url'],'zxbddiy_sitetemp/pages/page/page') !== false || empty( $_GPC['data']['data'][$i]['url'] ) ){
					if( $i == 0 ) $url = 'zxbddiy_sitetemp/pages/page/pagea';
					if( $i == 1 ) $url = 'zxbddiy_sitetemp/pages/page/pageb';
					if( $i == 2 ) $url = 'zxbddiy_sitetemp/pages/page/pagec';
					if( $i == 3 ) $url = 'zxbddiy_sitetemp/pages/page/paged';
					if( $i == 4 ) $url = 'zxbddiy_sitetemp/pages/page/pagee';
				}else{
					$list = explode('?',$_GPC['data']['data'][$i]['url']);
					$url = trim($list[0],'/');
				}

				// 设置图标尺寸
				$fontsizesys = intval( $_GPC['data']['fontsizesys'] );

				if( empty( $_GPC['data']['data'][$i]['img'] ) ) {
					Util::echoResult(201,'第'.($i+1).'个导航没有设置默认图标');
				}else{
					$img = $_GPC['data']['data'][$i]['img'];
					
					if( $fontsizesys > 0 ) {
						$img_info = getimagesize($img);
						if( $img_info[0] != $fontsizesys || $img_info[1] != $fontsizesys ) {
							$img = Util::wxapp_code_path_convert( $img,$fontsizesys );
						}
					}
					$_GPC['data']['data'][$i]['img'] = $img;
					
				}
				if( empty( $_GPC['data']['data'][$i]['img'] ) ) {
					Util::echoResult(201,'第'.($i+1).'个导航没有设置激活图标');
				}else{
					$actimg = $_GPC['data']['data'][$i]['actimg'];

					if( $fontsizesys > 0 ) {
						$img_info = getimagesize($actimg);
						if( $img_info[0] != $fontsizesys || $img_info[1] != $fontsizesys ) {
							$actimg = Util::wxapp_code_path_convert( $actimg,$fontsizesys );
						}
					}
					$_GPC['data']['data'][$i]['actimg'] = $actimg;
				}

				$barlist[] = array(
			        "iconSelectedPath" => "",
			        "iconPath" => $img,
			        "pagePath" => $url,
			        "text" => $_GPC['data']['data'][$i]['name'],
			        "selectedIconPath" => $actimg,
		      	);
			}
			if( count( $barlist ) <= 0 ) Util::echoResult(201,'还没有设置导航');
			$json['tabBar']['list'] = $barlist;

			$firsturl = $barlist[0]['pagePath'];
			foreach ($json['pages'] as $k => $v) {
				if( $v == $firsturl ){
					unset( $json['pages'][$k] );
				}
			}
			array_unshift($json['pages'], $firsturl);
		//var_dump( $json );	
			pdo_update('wxapp_versions', array('appjson' => iserializer($json), 'use_default' => 0), array('id' => $_GPC['version_id']));

		}else{
			$v = pdo_get('wxapp_versions',array('id' => $_GPC['version_id']));
			$json = iunserializer( $v['appjson'] );
			$json['tabBar']['isSystemTabBar'] = "0";
			pdo_update('wxapp_versions', array('appjson' => iserializer($json)), array('id' => $_GPC['version_id']));
		}


		$data = array(
			'uniacid' => $_W['uniacid'],
			'data'=> iserializer( $_GPC['data'] ),
			'createtime'=>TIMESTAMP,
			'tempid' => $tid,
		);
		
		$isset = pdo_get('zxbddiy_sitetemp_bar',array('uniacid'=>$_W['uniacid'],'tempid'=>$tid),array('id'));

		if( !empty( $isset['id'] ) ){
			$res = pdo_update('zxbddiy_sitetemp_bar',$data,array('id'=>$isset['id']));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_bar',$data);
			$id = pdo_insertid();
		}
		if($res){

			Util::deletecache('bar',$id);
			Util::deletecache('botbar','all');
			Util::deletecache('botbar',$tid);
			
		}
		$str = '';
		if( $_GPC['data']['bartype'] == 1 ){
			$str = '，请在模块主页面点击‘上传微信审核’重新上传代码审核';
		}

		Util::echoResult(200,'已保存'.$str);
		

		//return pdo_update('wxapp_versions', array('appjson' => serialize($json), 'use_default' => 0), array('id' => $version_id));

	// 查询链接
	}elseif( $_GPC['op'] == 'getlink'){		
		
		set_time_limit(0);

		$page = array();

		$where = array('id'=>$_GPC['tid']);
		$temp = pdo_get('zxbddiy_sitetemp_temp',$where,array('id','name'));
		$temp['name'] = $temp['name'].'(当前模板)';

		$temp['page'] = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$_GPC['tid'],'isinit'=>0),array('id','name'));

		$page[] = $temp;

		$alltemp = pdo_getall('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid']));
		if( !empty( $alltemp ) ) {
			foreach ( $alltemp as &$v ) {
				if( $v['id'] != $temp['id'] ) {
					$v['page'] = pdo_getall('zxbddiy_sitetemp_page',array('uniacid'=>$_W['uniacid'],'tempid'=>$v['id'],'isinit'=>0),array('id','name'));
					
					$page[] = $v;
				}
			}

		}


		if( !empty( $page ) ){
			foreach ( $page as &$v ) {
				if( !empty( $v['page'] ) ) {
					foreach ($v['page'] as $kk => $vv) {
						$v['page'][$kk]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$vv['id'];
					}
					
				}else{
					$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$v['id']));
					$data = iunserializer( $bar['data'] );
					if( $data['data'][0]['pageid'] == '-1' ){
						$v['page'] = array( array( 'name'=>'首页','url'=>'/zxbddiy_sitetemp/pages/page/page?scene='.$v['id'] ) );
					}
				}
			}
			unset( $v );
		}
		


		$news = pdo_getall('zxbddiy_sitetemp_article',array('uniacid'=>$_W['uniacid']),array('id','title'));
		if( !empty( $news ) ){
			foreach ( $news as &$v ) {
				$v['url'] = '/zxbddiy_sitetemp/pages/art/art?aid='.$v['id'];
			}
			unset( $v );
		}

		$allgoods = pdo_getall('zxbddiy_sitetemp_good',array('uniacid'=>$_W['uniacid'],'status'=>0),array('id','title','thumb','price','oldprice','sales','type'));

		$goods = $ordergoods = array();
		if( !empty( $allgoods ) ){
			foreach ( $allgoods as &$v ) {
				if( empty($v['type']) ){
					$v['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$v['id'].'&tid='.$_GPC['tid'];
					$goods[] = $v;
				}elseif( $v['type'] == 1 ){
					$v['url'] = '/zxbddiy_sitetemp/pages/deskorder/good?gid='.$v['id'].'&tid='.$_GPC['tid'];
					$ordergoods[] = $v;
				}
			}
			unset( $v );
		}

		

		$sort = model_goodsort::getSort(0);
		$sortone = array();
		$sorttwo = array();

		if( !empty( $sort ) ){
			foreach ($sort as $v) {
				$item = array();
				$item['url'] = '/zxbddiy_sitetemp/pages/goodlist/goodlist?type=1&sid='.$v['id'].'&sortname='.$v['name'].'&tid='.$_GPC['tid'];
				$item['name'] = $v['name'];
				$item['id'] = $v['id'];
				$sortone[] = $item;

				if( !empty( $v['down'] ) ){
					foreach ( $v['down'] as $vv ) {
						$in = array();
						$item['url'] = '/zxbddiy_sitetemp/pages/goodlist/goodlist?type=2&sid='.$vv['id'].'&sortname='.$vv['name'].'&tid='.$_GPC['tid'];
						$item['name'] = $vv['name'];
						$item['id'] = $vv['id'];
						$sorttwo[] = $item;
					}
					unset( $v );
				}

			}
		}

		$sort = model_goodsort::getSort(1);
		$ordersortone = array();
		$ordersorttwo = array();

		if( !empty( $sort ) ){
			foreach ($sort as $v) {
				$item = array();
				$item['url'] = '/zxbddiy_sitetemp/pages/ogoodlist/goodlist?type=1&sid='.$v['id'].'&sortname='.$v['name'].'&tid='.$_GPC['tid'];
				$item['name'] = $v['name'];
				$item['id'] = $v['id'];
				$ordersortone[] = $item;

				if( !empty( $v['down'] ) ){
					foreach ( $v['down'] as $vv ) {
						$in = array();
						$item['url'] = '/zxbddiy_sitetemp/pages/ogoodlist/goodlist?type=2&sid='.$vv['id'].'&sortname='.$vv['name'].'&tid='.$_GPC['tid'];
						$item['name'] = $vv['name'];
						$item['id'] = $vv['id'];
						$ordersorttwo[] = $item;
					}
					unset( $v );
				}

			}
		}		

		// 文章分类
		$artsort = model_artsort::getSort();
		if( !empty( $artsort ) ) {
			foreach ($artsort as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/artlist/artlist?sid='.$v['id'].'&tid='.$_GPC['tid'];
			}
			unset( $v );
		}

		// 产品分类
		$prosort = model_prosort::getSort();
		if( !empty( $prosort ) ) {
			foreach ($prosort as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/product/list?sid='.$v['id'].'&tid='.$_GPC['tid'];
			}
			unset( $v );
		}

		$product = pdo_getall('zxbddiy_sitetemp_product',array('uniacid'=>$_W['uniacid']),array('id','title'));
		if( !empty( $product ) ){
			foreach ( $product as &$v ) {
				$v['url'] = '/zxbddiy_sitetemp/pages/product/product?aid='.$v['id'].'&tid='.$_GPC['tid'];
			}
			unset( $v );
		}


		$appoint = pdo_getall('zxbddiy_sitetemp_appoint',array('uniacid'=>$_W['uniacid'],'status'=>0),array('id','thumb','name','desc','num','price','ispay'));
		if( !empty( $appoint ) ){
			foreach ( $appoint as &$v ) {
				$v['url'] = '/zxbddiy_sitetemp/pages/appoint/info?aid='.$v['id'].'&tid='.$_GPC['tid'];
				$v['img'] = tomedia( $v['thumb'] );
				$v['title'] = $v['name'];
			}
			unset( $v );
		}

		// 预约分类
		$appsort = model_appsort::getSort();
		if( !empty( $appsort ) ) {
			foreach ($appsort as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/appoint/list?sid='.$v['id'].'&tid='.$_GPC['tid'];
			}
			unset( $v );
		}



		Util::echoResult(200,'好',array(
			'appoint'=>$appoint,
			'page'=>$page,
			'news'=>$news,
			'sortone'=>$sortone,
			'sorttwo'=>$sorttwo,
			'goods'=>$goods,
			'artsort'=>$artsort,
			'ordergoods'=>$ordergoods,
			'ordersortone'=>$ordersortone,
			'ordersorttwo'=>$ordersorttwo,
			'prosort'=>$prosort,
			'product'=>$product,
			'appsort' => $appsort,
		));
	
	// 查询模板的页面
	}elseif( $_GPC['op'] == 'gettemppage' ){

		
		/*if( !empty( $allpage ) ) {
			foreach ( $allpage as &$v ) {
				$v['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$_GPC['id'];
			}
		}*/
		if( $_GPC['ctype'] == 1 ) Util::echoResult(200,'好');

		$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$_GPC['id']));	
		$bardata = iunserializer( $bar['data'] );
		if( !empty( $bardata ) ) {
			if( strpos( $bardata['data'][0]['url'] , 'pages/page/page' ) >= 0 ){
				$allpage = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$_GPC['id']),array('id','name'));
			}
		}

		Util::echoResult(200,'好',$allpage);

	}elseif( $_GPC['op'] == 'loadpagelist' ){

		$temp = pdo_getall('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid']),array('name','id'));

		if( !empty( $temp ) ) {
			foreach ($temp as &$v) {
				$v['page'] = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$v['id']),array('id','name'));
			}
		}
		
		Util::echoResult(200,'好',$temp);

	}elseif( $_GPC['op'] == 'loadpagebyid' ){

		$page = pdo_get('zxbddiy_sitetemp_page',array('id'=>$_GPC['id']));

		if( empty( $page ) ) {
			Util::echoResult(201,'页面不存在');
		}
		$page['params'] = iunserializer( $page['params'] );

		Util::echoResult(200,'好',$page);

	}elseif( $_GPC['op'] == 'deletepage' ){

		$page = pdo_get('zxbddiy_sitetemp_page',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));

		if( empty( $page ) ) {
			Util::echoResult(201,'页面不存在');
		}

		pdo_delete('zxbddiy_sitetemp_page',array('id'=>$page['id']));

		Util::echoResult(200,'已删除');

	}elseif( $_GPC['op'] == 'getgoods' ){

		$good = pdo_getall('zxbddiy_sitetemp_good',array('uniacid'=>$_W['uniacid'],'status'=>0,'type'=>$_GPC['type']),array('id','thumb','title','desc','price','oldprice','sales'));

		if( !empty( $good ) ) {
			foreach ($good as &$v) {
				$v['thumb'] = tomedia( $v['thumb'] );
			}
		}
		
		Util::echoResult(200,'好',$good);

	}elseif( $_GPC['op'] == 'getarticle' ){

		$article = pdo_getall('zxbddiy_sitetemp_article',array('uniacid'=>$_W['uniacid']),array('id','img','title','time'));

		if( !empty( $article ) ) {
			foreach ($article as &$v) {
				$v['img'] = tomedia( $v['img'] );
			}
		}
		
		Util::echoResult(200,'好',$article);	

	}elseif( $_GPC['op'] == 'getproduct' ){

		$product = pdo_getall('zxbddiy_sitetemp_product',array('uniacid'=>$_W['uniacid']),array('id','img','title'));

		if( !empty( $product ) ) {
			foreach ($product as &$v) {
				$v['img'] = tomedia( $v['img'] );
			}
		}
		
		Util::echoResult(200,'好',$product);

	}elseif( $_GPC['op'] == 'getappoint' ){

		$appoint = pdo_getall('zxbddiy_sitetemp_appoint',array('uniacid'=>$_W['uniacid'],'status'=>0),array('id','thumb','name','desc','num','price','ispay'));

		if( !empty( $appoint ) ) {
			foreach ($appoint as &$v) {
				$v['img'] = tomedia( $v['thumb'] );
				$v['title'] = $v['name'];
			}
		}
		
		Util::echoResult(200,'好',$appoint);

	}elseif( $_GPC['op'] == 'getcard' ){

		$appoint = pdo_getall('zxbddiy_sitetemp_card',array('uniacid'=>$_W['uniacid'],'status'=>0),array('id','thumb','name','value','stock','type','usetype','useleast'));

		if( !empty( $appoint ) ) {
			foreach ($appoint as &$v) {
				$v['img'] = tomedia( $v['thumb'] );
				$v['title'] = $v['name'];
			}
		}
		
		Util::echoResult(200,'好',$appoint);


	}elseif( $_GPC['op'] == 'toreaded' ){

		$id = intval( $_GPC['fid'] );

		pdo_update('zxbddiy_sitetemp_form',array('isread'=>1),array('id'=>$id,'uniacid'=>$_W['uniacid']));

		Util::echoResult(200,'已记为已审核');
		
	}elseif( $_GPC['op'] == 'pagetoindex' ){

		$id = intval( $_GPC['pid'] );
		$page = pdo_get('zxbddiy_sitetemp_page',array('id'=>$id,'uniacid'=>$_W['uniacid']));

		if( empty( $page ) ) Util::echoResult(201,'页面不存在');

		pdo_update('zxbddiy_sitetemp_page',array('isindex'=>0),array('tempid'=>$page['tempid']));
		pdo_update('zxbddiy_sitetemp_page',array('isindex'=>1),array('tempid'=>$page['tempid'],'id'=>$page['id']));

		Util::echoResult(200,'已更改');

	}elseif( $_GPC['op'] == 'baidutoten' ){

		$ak = empty( $this->module['config']['ak'] ) ? 'F51571495f717ff1194de02366bb8da9' : $this->module['config']['ak'];

		$tourl = 'http://api.map.baidu.com/geoconv/v1/?coords='.$_GPC['lng'].','.$_GPC['lat'].'&from=1&to=5&ak='.$ak;
		$tores = Util::httpGet($tourl);
		$tores = json_decode($tores,true);

		if( $tores['status'] == '0' ){
			
			$data['lat'] = sprintf('%.5f',$tores['result'][0]['y']);
			$data['lng'] = sprintf('%.4f',$tores['result'][0]['x']);
			
			Util::echoResult(200,'已记为已审核',$data);
			
		}

		Util::echoResult(201,'设置坐标失败');

	}elseif( $_GPC['op'] == 'addtempform' ){

		$id = intval( $_GPC['fid'] );

		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['name'],
			'number' => intval( $_GPC['number'] ),
			'img' => $_GPC['img'],
			'sort' => intval( $_GPC['sort'] ),
			'showqr' => $_GPC['showqr'],
		);

		if( empty( $data['name'] ) ) Util::echoResult(201,'请填写模板名称');
		if( empty( $data['img'] ) ) Util::echoResult(201,'请设置模板图标');	

		if( $id > 0 ) {
			$temp = pdo_get( 'zxbddiy_sitetemp_temp',array('id'=>$id));
			if( $temp['issystem'] == 1 && $temp['issetsystem'] == 1  ){

				if( $_W['role'] != 'founder' ) Util::echoResult(201,'你无权限');
				$res = pdo_update( 'zxbddiy_sitetemp_temp',$data ,array('id'=>$id));
			}else{
				$res = pdo_update( 'zxbddiy_sitetemp_temp',$data ,array('id'=>$id,'uniacid'=>$_W['uniacid']));
			}
			
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_temp',$data);
		}

		if( $res ) {
			Util::deleteCache('temp','all');
			Util::deleteCache('temp',$id);
			Util::echoResult(200,'已保存');
		}

		Util::echoResult(201,'保存失败');

	// 编辑模板
	}elseif( $_GPC['op'] == 'findtemp' ){

		if( $_GPC['type'] == 'sys' ) {
			if( $_W['role'] != 'founder' ) Util::echoResult(201,'你无权限');
			$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['fid']));
		}else{
			$temp = pdo_get('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['fid']));
		}

		if( empty( $temp ) ) Util::echoResult(201,'没有找到模板');

		$temp['showimg'] = tomedia( $temp['img'] );
		$temp['showqr'] = tomedia( $temp['showqr'] );

		Util::echoResult(200,'好',$temp);

	// 使用模板
	}elseif( $_GPC['op'] == 'setacttemp' ){		

		$id = intval( $_GPC['id'] );
		$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['id']));
		if( empty( $temp ) ) Util::echoResult(201,'没有找到模板');

		if( $temp['issystem'] == 1 ) Util::echoResult(201,'不能使用系统模板');

		pdo_update('zxbddiy_sitetemp_temp',array('isact'=>0),array('uniacid'=>$_W['uniacid'],'issystem'=>0));
		pdo_update('zxbddiy_sitetemp_temp',array('isact'=>1),array('uniacid'=>$_W['uniacid'],'issystem'=>0,'id'=>$id));

		$pid = intval( $_GPC['pageid'] );
		if( !empty( $pid ) ) {
			$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$temp['id']));
			
			if( !empty( $bar['data'] ) ) {
				$bardata = iunserializer( $bar['data'] );
				if( !empty( $bardata ) ) {
					$bardata['data'][0]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$pid;
					$bardata['data'][0]['pageid'] = $pid;
					
					$bardata = iserializer( $bardata );
					pdo_update('zxbddiy_sitetemp_bar',array('data'=>$bardata),array('id'=>$bar['id']));
				}
			}
		}

		Util::deleteCache('temp','all');
		Util::deleteCache('temp',$id);

		Util::deletecache('botbar','all');
		Util::deletecache('botbar',$id);

		Util::echoResult(200,'已使用');


	}elseif( $_GPC['op'] == 'temptopage' ){

		$id = intval( $_GPC['id'] );
		$ctype = intval( $_GPC['ctype'] );

		if( empty( $ctype ) ) { // 本地的

			$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['id']));
			if( empty( $temp ) ) Util::echoResult(201,'没有找到模板');
			
			$issetact = pdo_get('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid'],'isact'=>1));

			$tdata = array(
				'uniacid' => $_W['uniacid'],
				'name' => '新复制的模板',
				'number' => 1,
				'img' => $temp['img'],
				'isact' => empty( $issetact ) ? 1 : 0,
			);

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			$tid = pdo_insertid();

			if( $tid ){
				// bar
				$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$temp['id']));
				$allpage = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$temp['id']));

				$pbox = array();

				if( !empty( $allpage ) ) {
					foreach ($allpage as $v) {
						$data = array(
							'uniacid' => $_W['uniacid'],
							'params' => $v['params'],
							'createtime' => TIMESTAMP,
							'name' => $v['name'],
							'tempid' => $tid,
						);
						pdo_insert('zxbddiy_sitetemp_page',$data);
						$pid = pdo_insertid();
						$pbox[] = array('name'=>$v['name'],'id'=>$pid);
						if( !empty($_GPC['pageid']) && $_GPC['pageid'] == $v['id'] ) {
							$newid = $pid;
						}
					}
				}

				if( !empty( $newid ) ) {
					$bardata = iunserializer( $bar['data'] );
					if( !empty( $bardata ) ) {
						$bardata['data'][0]['url'] = '/zxbddiy_sitetemp/pages/page/page?pid='.$newid;
						$bardata['data'][0]['pageid'] = $newid;
						
						$bar['data'] = iserializer( $bardata );
					}
				}

				if( !empty( $bar ) ) {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'data' => $bar['data'],
						'createtime' => TIMESTAMP,
						'tempid' => $tid,
					);
					pdo_insert('zxbddiy_sitetemp_bar',$data);
				}

				// 重置页面链接
				model_temp::setTempUrl($tid,$pbox,false);
				
			}else{
				Util::echoResult(201,'导出模板失败');
			}

			Util::echoResult(200,'已导出到我的模板内，请编辑模板的导航和各个页面的点击动作',array('tid'=>$tid));	

		}else{ // 远程的

			$temp = model_cloud::getTemp( 1,$id,'info',60 );
			
			if( $temp['status'] == 200 ) {

				$arr = htmlspecialchars_decode( $temp['data'] );
				$arr = iunserializer( $arr );
				if( is_array( $arr ) && !empty( $arr ) ){
					$issetact = pdo_get('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid'],'isact'=>1));

					$tdata = array(
						'uniacid' => $_W['uniacid'],
						'name' => $arr['temp']['name'],
						'number' => 1,
						'img' => $arr['temp']['img'],
						'isact' => empty( $issetact ) ? 1 : 0,
					);

					$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
					$tid = pdo_insertid();

					if( $tid ){

						$pbox = array();

						if( !empty( $arr['pages'] ) ) {
							foreach ($arr['pages'] as $v) {
								$data = array(
									'uniacid' => $_W['uniacid'],
									'params' => $v['params'],
									'createtime' => TIMESTAMP,
									'name' => $v['name'],
									'tempid' => $tid,
								);
								pdo_insert('zxbddiy_sitetemp_page',$data);
								$pid = pdo_insertid();
								$pbox[] = array('name'=>$v['name'],'id'=>$pid);
								
							}
						}

						if( !empty( $arr['bar'] ) ) {
							$data = array(
								'uniacid' => $_W['uniacid'],
								'data' => $arr['bar']['data'],
								'createtime' => TIMESTAMP,
								'tempid' => $tid,
							);
							pdo_insert('zxbddiy_sitetemp_bar',$data);
						}
						// 重置页面链接
						model_temp::setTempUrl($tid,$pbox,true);


					}else{
						Util::echoResult(201,'导出模板失败');
					}
					Util::echoResult(200,'已导出到我的模板内');
				}else{
					Util::echoResult(201,'此模板没有数据');
				}
			}else{
				Util::echoResult(201,$temp['res']);
			}
		}


	}elseif( $_GPC['op'] == 'tomycloud' ){		

		set_time_limit(0);

		$all = pdo_getall('zxbddiy_sitetemp_temp',array('uniacid'=>94));
		if( !empty( $all ) ) {
			foreach ($all as $v) {

				$todata = array();
				
				$v['img'] = tomedia( $v['img'] );
				$todata['temp'] = $v;

				$pages = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$v['id']));
				$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$v['id']));
				$todata['pages'] = $pages;
				$todata['bar'] = $bar;

				$sestr = iserializer( $todata );
				$url = 'http://api.zofui.net/app/index.php?c=sitetemp&a=tomycloud';
				$res = Util::httpPost($url,array('data'=>$sestr,'code'=>$_W['config']['setting']['authkey']));
				$res = json_decode( $res,true );
				if( $res['status'] != 200 ) {
					Util::echoResult(201,$res['res']);
				}
			}
		}
		Util::echoResult(200,'已完成');

	// 查询小程序
	}elseif( $_GPC['op'] == 'getapp' ){
		load()->model('wxapp');
		load()->model('account');
		
		$pindex = 1;
		$psize = 9999;

		$account_table = table('account');
		$account_table->searchWithType(array(ACCOUNT_TYPE_APP_NORMAL));

		$account_table->searchWithPage($pindex, $psize);
		$wxapp_lists = $account_table->searchAccountList();


		if (!empty($wxapp_lists)) {
			foreach ($wxapp_lists as &$account) {
				$account = uni_fetch($account['uniacid']);
				$account['versions'] = wxapp_get_some_lastversions($account['uniacid']);
				if (!empty($account['versions'])) {
					foreach ($account['versions'] as $version) {
						if (!empty($version['current'])) {
							$account['current_version'] = $version;
						}
					}
				}
			}

			$data = array();
			foreach ( $wxapp_lists as $v ) {
				$in = array();
				$in['appname'] = $v['name'];
				$in['logo'] = $v['logo'];
				$in['appid'] = $v['key'];

				$wxapp_modules = pdo_getcolumn('wxapp_versions', array('id' => $v['current_version']['id']), 'modules');
				
				if (!empty($wxapp_modules)) {
					$module_info = iunserializer($wxapp_modules);
					$module_info = pdo_getall('modules_bindings', array('module' => array_keys($module_info), 'entry' => 'page'));
					
					if( !empty( $module_info ) ) {
						$list = array();
						foreach ($module_info as $vv) {
							$listin = array();
							$listin['title'] = $vv['title'];
							$listin['url'] = $vv['do'];
							$list[] = $listin;
						}
						$in['list'] = $list;
					}
				}
				$data[] = $in;
			}

		}

		
		Util::echoResult(200,'好',$data);	


	// 查询二维码
	}elseif( $_GPC['op'] == 'getqrcode' ){
		
		
		$scene = Util::getCache('scene','all');

		if( empty( $scene ) || $scene['time'] <= TIMESTAMP ){
			$id = TIMESTAMP.rand(1111,999);
			$scene = array( 'time' => (TIMESTAMP + 60) , 'id' => $id );
			Util::setCache( 'scene','all',$scene );

			Util::echoResult(202,'好',array('url'=>$this->createWebUrl('img',array('id'=>$id))));
		}

		Util::echoResult(200,'好');

	}elseif( $_GPC['op'] == 'checkqrcode' ){

		load()->model('account');
		
		$uniacccount = WeAccount::create($_W['acid']);

		$url = 'zxbddiy_sitetemp/pages/form/form';
		if( $_GPC['page'] == 'bindadmin' ) $url = 'zxbddiy_sitetemp/pages/bindadmin/bindadmin';
		if( $_GPC['page'] == 'adminlist' ) $url = 'zxbddiy_sitetemp/pages/appoint/adminlist';

		$res = Util::wxappQrcode( $uniacccount,'123456',$url );
		
		
		if( strpos( $res, '{"errcode":' ) !== false || is_error($res) ){
			if( is_error($res) ){
				$res = implode(':', $res);
			}
			Util::echoResult(201,'生成二维码失败，请确保小程序的appid和secret正确，并且程序已上传审核通过。具体原因'.$res);
		}else {
			Util::echoResult(200,'好');
		}

	// 普通模板和模块模板转为系统模板
	}elseif( $_GPC['op'] == 'tosystem' ){
		
		$set = model_sysset::getSet();
		
		if( $_W['role'] == 'founder' ){

		}elseif( $_W['role'] == 'vice_founder' ){
			if( empty($set['istoseys']) ) Util::echoResult(201,'你无权限1');
		}else{
			Util::echoResult(201,'你无权限2');
		}
		
		
		$type = intval( $_GPC['type'] );
		$id = intval( $_GPC['id'] );


		if( empty( $_GPC['sys'] ) ){
			$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['id']));
			if( empty( $temp ) ) Util::echoResult(201,'没有找到模板');
			$type = $type - 1;

		}elseif( $_GPC['sys'] == 1 ){ // 模块模板

			$all = model_systemp::getTemp();			
			foreach ( $all['temp'] as $v ) {
				if( $v['id'] == $id ) {
					$temp = $v;
				}
			}

			$type = 0; // 官网系统模板
			if( $temp['name'] == '普通商城系统模板' && $temp['issetsystem'] == 0 ){ // 商城系统模板
				$type = 1;
			}

			// 预约系统模板 
			if( ( $temp['name'] == '美发预约系统模板' ) && $temp['issetsystem'] == 0 ){ // 商城系统模板
				$type = 2;
			}

			if( $temp['name'] == '在线点餐系统模板' && $temp['issetsystem'] == 0 ){ // 点餐系统模板
				$type = 3;
			}

		}
		
		$data = array(
			'uniacid' => 0,
			'createtime' => TIMESTAMP,
			'name' => $temp['name'],
			'number' => 1,
			'img' => $temp['img'],
			'isact' => 0,
			'issystem' => 1,
			'issetsystem' => 1,
			'sort' => $temp['sort'],
			'type' => $type,
		);
				
		$res = pdo_insert('zxbddiy_sitetemp_temp',$data);

		if( $res ){
			$tid = pdo_insertid();

			if( $_GPC['sys'] == 1 ){ // 模块模板

				model_systemp::initPage($tid,$temp['fn']);
				Util::echoResult(200,'已保存成系统模板');
				
			}

			$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$temp['id']));
			if( !empty( $bar ) ) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'data' => $bar['data'],
					'createtime' => TIMESTAMP,
					'tempid' => $tid,
				);
				pdo_insert('zxbddiy_sitetemp_bar',$data);
			}

			$allpage = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$temp['id']));

			if( !empty( $allpage ) ) {

				foreach ($allpage as $v) {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'params' => $v['params'],
						'createtime' => TIMESTAMP,
						'name' => $v['name'],
						'tempid' => $tid,
					);
					pdo_insert('zxbddiy_sitetemp_page',$data);
				}

			}

			Util::echoResult(200,'已保存成系统模板');
		}

		Util::echoResult(201,'保存系统模板失败');

	}elseif( $_GPC['op'] == 'deletesystem' ){

		$id = intval( $_GPC['id'] );

		if( $_GPC['type'] == 'sys' ) {
			if( $_W['role'] != 'founder' ) Util::echoResult(201,'你无权限');

			$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['id']));
			if( empty( $temp ) ) Util::echoResult(201,'没有找到模板');

			if( $temp['issystem'] != 1 ) Util::echoResult(201,'不是系统模板');
			if( $temp['issetsystem'] != 1 ) Util::echoResult(201,'此系统模板不能删除');
			
		}else{
			$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
			if( empty( $temp ) ) Util::echoResult(201,'没有找到模板');

			if( $temp['issystem'] == 1 ) Util::echoResult(201,'此模板不能删除');
			if( $temp['isact'] == 1 ) Util::echoResult(201,'使用中的模板不能删除');
		}
		
		pdo_delete('zxbddiy_sitetemp_temp',array('id'=>$id));
		pdo_delete('zxbddiy_sitetemp_bar',array('tempid'=>$id));
		pdo_delete('zxbddiy_sitetemp_page',array('tempid'=>$id));

		Util::echoResult(200,'已删除');


	}elseif( $_GPC['op'] == 'usemail' ){

		if( $_GPC['type'] == 'sms' ) {

			$sms = pdo_get('zxbddiy_sitetemp_sms');
			$sms['template'] = iunserializer( $sms['template'] );

			if( empty( $sms ) ) Util::echoResult(201,'请先填写下方的发短信参数');
			if( empty( $_GPC['value'] ) ) Util::echoResult(201,'请填写手机号码');	

			$params = '{"title":"测试订单","fee":"1.01","type":"上店自提"}';
			
			$smsjoj = new model_sms();
			$res = $smsjoj->sendSms($sms['key'],$sms['secret'],$sms['signature'], $sms['template']['paysuc'], $_GPC['value'], $params);
			$res = json_decode($res,true);

			if( $res['Code'] == 'OK' ){
				Util::echoResult(200,'已发送');
			}else{
				Util::echoResult(201,'发送失败，原因：'.$res['Message']);
			}

		}else if( $_GPC['type'] == 'email' ){

			load()->func('communication');
			if( empty( $_GPC['value'] ) ) Util::echoResult(200,'请填写收件邮箱');
			$res = Util::ihttp_email($_GPC['value'], '测试邮件', array('测试邮件','测试邮件','测试邮件'));
			
			
		}


		if( $res['status'] == 200 ) {
			Util::echoResult(200,'已发送');
		}else{
			Util::echoResult(201,'发送失败，原因：'.$res['res']);
		}


	}elseif( $_GPC['op'] == 'addartsort' ){

		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_artsort',array('uniacid'=>$_W['uniacid'],'id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['number'] = $_GPC['number'];
		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];

		if( $fid > 0 ){ 
			$res = pdo_update('zxbddiy_sitetemp_artsort',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_artsort',$data);
		}
		if( $res ){
			Util::deleteCache('artsort','all');
			Util::echoResult(200,'已保存');
		} 
		Util::echoResult(201,'保存失败');


	}elseif( $_GPC['op'] == 'findartsort' ){

		$form = pdo_get('zxbddiy_sitetemp_artsort',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['fid']));
		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');

		Util::echoResult(200,'好',$form);

	}elseif( $_GPC['op'] == 'addprosort' ){

		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_prosort',array('uniacid'=>$_W['uniacid'],'id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['number'] = $_GPC['number'];
		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];
		$data['img'] = $_GPC['img'];

		if( $fid > 0 ){ 
			$res = pdo_update('zxbddiy_sitetemp_prosort',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_prosort',$data);
		}
		if( $res ){
			Util::deleteCache('prosort','all');
			Util::echoResult(200,'已保存');
		} 
		Util::echoResult(201,'保存失败');


	}elseif( $_GPC['op'] == 'findprosort' ){

		$form = pdo_get('zxbddiy_sitetemp_prosort',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['fid']));
		$form['showimg'] = tomedia( $form['img'] );
		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');

		Util::echoResult(200,'好',$form);

	}elseif( $_GPC['op'] == 'addappsort' ){

		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_appsort',array('uniacid'=>$_W['uniacid'],'id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['number'] = $_GPC['number'];
		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];

		if( $fid > 0 ){ 
			$res = pdo_update('zxbddiy_sitetemp_appsort',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_appsort',$data);
		}
		if( $res ){
			Util::deleteCache('appsort','all');
			Util::echoResult(200,'已保存');
		} 
		Util::echoResult(201,'保存失败');


	}elseif( $_GPC['op'] == 'findappsort' ){

		$form = pdo_get('zxbddiy_sitetemp_appsort',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['fid']));
		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');

		Util::echoResult(200,'好',$form);

	// 商品分类
	}elseif( $_GPC['op'] == 'addgoodsort' ){
		
		
		
		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_goodsort',array('uniacid'=>$_W['uniacid'],'id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['number'] = $_GPC['number'];
		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];
		$data['status'] = $_GPC['status'];
		$data['img'] = $_GPC['img'];
		$data['parent'] = intval( $_GPC['parent'] );
		$data['plug'] = intval( $_GPC['plug'] );

		if( $data['parent'] > 0 ){
			$parent = pdo_get('zxbddiy_sitetemp_goodsort',array('id'=>$data['parent']));
			$data['tempid'] = $parent['tempid'];

		}else{
			$data['tempid'] = intval( $_GPC['tempid'] );
		}

		if( $fid > 0 ){ 
			$res = pdo_update('zxbddiy_sitetemp_goodsort',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_goodsort',$data);
		}
		

		if( $data['parent'] <= 0 && $fid > 0 ) {
			pdo_update('zxbddiy_sitetemp_goodsort',array('tempid'=>$_GPC['tempid']),array('parent'=>$fid));
		}

		Util::deleteCache('goodsort','all'.$_GPC['plug']);
		Util::deleteCache('goodsort','one'.$_GPC['plug']);
		Util::deleteCache('goodsort','two'.$_GPC['plug']);
		Util::echoResult(200,'已保存');
		
		

	}elseif( $_GPC['op'] == 'findgoodsort' ){

		$form = pdo_get('zxbddiy_sitetemp_goodsort',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['fid']));
		$form['showimg'] = tomedia( $form['img'] );
		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');

		Util::echoResult(200,'好',$form);

	// 导入分类
	}elseif( $_GPC['op'] == 'loadgoodsort' ){

		if( empty( $_GPC['data'] ) ) Util::echoResult(201,'请选择分类');

		$temp = model_goodsort::getTemp();

		foreach ($_GPC['data'] as $v) {
			
			foreach ( $temp as $vv ) {
				foreach ($vv['down'] as $vvv ) {
					if( $vvv['id'] == $v['id'] ){
						$data['uniacid'] = $_W['uniacid'];
						$data['name'] = $vvv['name'];
						$data['img'] = $vvv['img'];
						$data['parent'] = $v['pid'];
						$data['plug'] = intval( $_GPC['plug'] );
						pdo_insert('zxbddiy_sitetemp_goodsort',$data);
					}
				}
			}

		}
		Util::deleteCache('goodsort','all'.$_GPC['plug']);
		Util::deleteCache('goodsort','one'.$_GPC['plug']);
		Util::deleteCache('goodsort','two'.$_GPC['plug']);		
		Util::echoResult(200,'已导入');		

	}elseif( $_GPC['op'] == 'loadallgoodsort' ){

		if( empty( $_GPC['id'] ) ) Util::echoResult(201,'请选择分类');

		$temp = model_goodsort::getTemp();

		foreach ( $temp as $v ) {

			if( $v['id'] == $_GPC['id'] ){

				$data['uniacid'] = $_W['uniacid'];
				$data['name'] = $v['name'];
				$data['plug'] = intval( $_GPC['plug'] );
				pdo_insert('zxbddiy_sitetemp_goodsort',$data);
				$pid = pdo_insertid();

				foreach ($v['down'] as $vv ) {
					
					$data['uniacid'] = $_W['uniacid'];
					$data['name'] = $vv['name'];
					$data['img'] = $vv['img'];
					$data['parent'] = $pid;
					$data['plug'] = intval( $_GPC['plug'] );
					pdo_insert('zxbddiy_sitetemp_goodsort',$data);
					
				}

			}

		}
		Util::deleteCache('goodsort','all'.$_GPC['plug']);
		Util::deleteCache('goodsort','one'.$_GPC['plug']);
		Util::deleteCache('goodsort','two'.$_GPC['plug']);
		Util::echoResult(200,'已导入');

	}elseif( $_GPC['op'] == 'gettb' ){

		if( empty( $_GPC['arr'] ) ) Util::echoResult(201,'填写商品链接');	

		$sid = intval( $_GPC['sortid'] );
		if( empty( $sid ) ) Util::echoResult(201,'填选择商品分类');

		$sort = pdo_get( 'zxbddiy_sitetemp_goodsort' , array('id'=>$sid) );

		set_time_limit(0);
		$collected = 0;
		
		foreach($_GPC['arr'] as $k => $v){
			$data = array(); //清空上次数据
			
			if(strpos($v,'item.taobao.com') !== false){
				$urltype = 'taobao';
			}elseif(strpos($v,'detail.tmall.com') !== false){
				$urltype = 'tmall';
			}elseif(strpos($v,'detail.1688.com') !== false){
				$urltype = 'alibaba';				
			}else{
				Util::echoResult(201,'不支持采集提交的链接');
			}

			if( $urltype == 'alibaba' ){

				preg_match('/(\\d+).html/i', $v, $matches);

				if (isset($matches[1])) {
					$id = $matches[1];
				}

				$itemUrl = 'http://m.1688.com/offer/' . $id . '.html';

				$html = Util::httpGet($itemUrl);
		
				preg_match('/window\\.wingxViewData=window\\.wingxViewData\\|\\|{};window\\.wingxViewData\\[0\\]=(.+)<\\/script>/', $html, $res);
				$json1 = $res[1];

				$json1 = json_decode($json1, true);
				if (empty($json1['detailUrl'])) 
				{
					$jsonstr = tb::get( $v );

					$resarr = json_decode((string)$jsonstr,true);

					if( $resarr['status'] == 201 ){
						Util::echoResult(201,'添加失败，原因:'.$resarr['res']);
					}
					$html = htmlspecialchars_decode( $resarr['res'] );

					preg_match('/window\\.wingxViewData=window\\.wingxViewData\\|\\|{};window\\.wingxViewData\\[0\\]=(.+)<\\/script>/', $html, $res);
					$json1 = $res[1];

					$json1 = json_decode($json1, true);
					if (empty($json1['detailUrl'])) 
					{
						Util::echoResult(201,'采集商品失败');
					}
				}
				$detailUrl = $json1['detailUrl'];
				load()->func('communication');
				$detail = ihttp_get($detailUrl);
				$detail = iconv('GBK', 'UTF-8', $detail['content']);
				preg_match('/var offer_details=(.+);$/', $detail, $detailStr);
				$detail_temp = json_decode($detailStr[1], true);
				if (empty($detail_temp)) 
				{
					preg_match('/var desc=\'(.+)\';$/', $detail, $detailStr);
					unset($detail);
					$detail['content'] = $detailStr[1];
				}
				else 
				{
					$detail = $detail_temp;
				}
				
				$pic = array();
				foreach ($json1['imageList'] as $k => $v) 
				{
					$pic[] = $v['originalImageURI'];
				}

				preg_match_all('/<img.*?src=[\\\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\\\'|\\"].*?[\\/]?>/', $detail['content'], $imgs);
				if (isset($imgs[1])) 
				{
					foreach ($imgs[1] as $img) 
					{
						$catchimg = $img;
						if (substr($catchimg, 0, 2) == '//') 
						{
							$img = 'http://' . substr($img, 2);
						}
						$images[] = $img;
					}
				}
				if (isset($images)) 
				{
					foreach ($images as $img) 
					{
						$detail['content'] = str_replace($img['catchimg'], $img['system'], $detail['content']);
					}
				}

				$data['content'] = htmlspecialchars($detail['content']);

				$data['title'] = $json1['subject'];
				$data['pic'] = iserializer( $pic );

				$data['thumb'] = $pic[0];

				$data['price'] = $json1['price'];
				$data['oldprice'] = $json1['price'];
				
				$data['sales'] = $json1['saledCount'];
				$data['stock'] = 100;

				$data['uniacid'] = $_W['uniacid'];
				$data['createtime'] = TIMESTAMP;

				$taobaorule =  $json1['skuProps'];
				if(!empty($taobaorule) && is_array($taobaorule)){
					//获取规格数组
					$rule = array();
					foreach($taobaorule as $k => $v){
						$ruleitem = array();
						$ruleitem['pro'] = array('id'=>$k+1,'title'=>$v['prop']);
						
						foreach($v['value'] as $kk => $vv){
						
							$ruleitem['data'][$kk]['id'] = ($k+1).($kk+1);
							$ruleitem['data'][$kk]['name'] = $vv['name'];
						}
						$rule[] = $ruleitem;
					}
					
					//获取价格数组
					$taobaorulearr = $json1['skuMap'];
				
					foreach($taobaorulearr as $k => $v){
						
						$basicmap = array();
						$basicmap['nowprice'] = empty($v['price']) ? $data['price'] : $v['price'];
						$basicmap['stock'] = $v['canBookCount'];
						
						$keyarr = explode('&gt;', $k);

						$keystr = '';
						foreach($keyarr as $kk => $vv){
							
							foreach ($rule as $kkk => $vvv) {
								foreach ($vvv['data'] as $kkkk => $vvvv) {
									if( $vv == $vvvv['name'] ){
										$keystr .= $vvvv['id'].':';
									}
								}
							}
						}

						$basicmap['id'] = trim($keystr,':');
						
						$map[] = $basicmap;
					}
					
					$rulearray['rule'] = $rule;
					$rulearray['rulemap'] = $map;
					$data['rulearray'] = iserializer($rulearray);
					$data['isrule'] = 1;
					
				}
				

			}else{

				preg_match('/&id=(\d+)/', $v, $id);
				$id = $id[1];
				if( empty( $id ) ) {
					preg_match('/\?id=(\d+)/', $v, $id);
					$id = $id[1];
				}
				
				if( empty( $id ) ) Util::echoResult(201,'商品链接异常，未找到商品id');

				$taourl = 'http://hws.m.taobao.com/cache/wdetail/5.0/?id='.$id;
				$jsonstr = Util::httpGet($taourl);
				$arr = json_decode((string)$jsonstr,true);
				
				if(empty($arr['data']['itemInfoModel'])) {
					$jsonstr = tb::get( $v );
					$arr = json_decode((string)$jsonstr,true);

					if( $arr['status'] == 201 ){
						Util::echoResult(201,'添加失败，原因:'.$arr['res']);
					}
					$arr = $arr['res']['data'];

					$data['title'] = $arr['item']['title'];

					foreach ($arr['item']['images'] as &$i) {

						if( strpos($i, 'http') === false ) {
							$i = 'http:'.$i;
						}
					}
					$data['pic'] = iserializer($arr['item']['images']);
					$data['thumb'] = $arr['item']['images'][0];

					$sku = json_decode($arr['apiStack'][0]['value'],true);
					$data['price']  = $sku['price']['price']['priceText'];
					$data['oldprice'] = $sku['price']['extraPrices'][0]['priceText'];
					
					$data['sales'] = $sku['item']['sellCount'];
	//var_dump( $arr );
					
					//$data['stock'] = $sku['data']['itemInfoModel']['quantity'];

					$taobaorule =  $arr['skuBase'];
					
					if(!empty($taobaorule) && is_array($taobaorule)){

						//获取规格数组
						$rule = array();
						foreach($taobaorule['props'] as $k => $v){
							$ruleitem = array();
							$ruleitem['pro'] = array('id'=>$v['pid'],'title'=>$v['name']);
							
							foreach($v['values'] as $kk => $vv){
							
								$ruleitem['data'][$kk]['id'] = $vv['vid'];
								$ruleitem['data'][$kk]['name'] = $vv['name'];
							}					
							$rule[] = $ruleitem;
						}

						//获取价格数组
						$taobaorulearr = json_decode($arr['mockData'],true);
						$taobaorulearr = $taobaorulearr['skuCore']['sku2info'];
						
						$map = array();
						foreach($taobaorule['skus'] as $k => $v){
							$rulemodel = array();
							foreach ($taobaorulearr as $kk => $vv) {
								if( $v['skuId'] == $kk ){
									$keyarr = explode(';',$v['propPath']);
									
									if( !empty( $keyarr ) ) {
										$key = '';
										$keyname = '';
										foreach ($keyarr as $kkk => $vvv) {
											$keyarrin = explode(':',$vvv);
											$key .= $keyarrin[1].':';

											foreach ($rule as $kkkk => $vvvv) {
												if( $vvvv['pro']['id'] == $keyarrin[0] ) {
													foreach ( $vvvv['data'] as $ruledata ) {
														if( $ruledata['id'] == $keyarrin[1] ){
															$keyname .= $ruledata['name'].':';
														}
													}
												}
											}

										}
										$keyname = trim( $keyname,':' );
										$key = trim( $key,':' );
									}

									$rulemodel = array(
										'id' => $key,
										'name' => $keyname,
										'nowprice' => $vv['price']['priceMoney']/100,
										'stock' => $vv['quantity'],
									);

								}
							}
							
							$map[] = $rulemodel;

						}					
										
						$rulearray['rule'] = $rule;
						$rulearray['rulemap'] = $map;
						$data['rulearray'] = iserializer($rulearray);
						$data['isrule'] = 1;
						
					}


				}else{

					if(empty($arr['data']['itemInfoModel'])) Util::echoResult(201,'未找到商品信息');
					
					$data['title'] = $arr['data']['itemInfoModel']['title'];

					$data['thumb'] = $arr['data']['itemInfoModel']['picsPath'][0];
					$data['pic'] = iserializer($arr['data']['itemInfoModel']['picsPath']);
					
					$taobaorule =  $arr['data']['skuModel']['skuProps'];
					
					if(!empty($taobaorule) && is_array($taobaorule)){
						//获取规格数组
						$rule = array();
						foreach($taobaorule as $k => $v){
							$ruleitem = array();
							$ruleitem['pro'] = array('id'=>$v['propId'],'title'=>$v['propName']);
							
							foreach($v['values'] as $kk => $vv){
							
								$ruleitem['data'][$kk]['id'] = $vv['valueId'];
								$ruleitem['data'][$kk]['name'] = $vv['name'];
							}
												
							$rule[] = $ruleitem;
						}
						
						//获取价格数组
						$taobaorulearr = json_decode($arr['data']['apiStack'][0]['value'],true);
								
						foreach($taobaorulearr['data']['skuModel']['skus'] as $k => $v){
							$rulemodel = array();
								
							$rulemodel['key'] = $k;
							$rulemodel['nowprice'] = $v['priceUnits'][0]['price'];
							$rulemodel['stock'] = $v['quantity'];
							$taobbasicmap[] = $rulemodel;

						}
									
						foreach($arr['data']['skuModel']['ppathIdmap'] as $k => $v){
							
							foreach($taobbasicmap as $kk => $vv){
								if($v == $vv['key']){
									$basicmap = array();
									$basicmap['nowprice'] = $vv['nowprice'];
									$basicmap['stock'] = $vv['stock'];
									$keyarr = explode(';',$k);

									$keystr = '';
									$keyname = '';
									$keystrarr = array();
									foreach($keyarr as $kkk => $vvv){
										$keyarr2 = explode(':',$vvv);

										$keystr .= $keyarr2[1].':';
										$keystrarr[] = $keyarr2[1];
									}
									$basicmap['id'] = trim($keystr,':');
									

									foreach ($rule as $vvvv) {
										foreach ($vvvv['data'] as $vvvvv) {
											foreach ($keystrarr as $vvvvvv) {
												if( $vvvvvv == $vvvvv['id'] ) {
													$keyname .= $vvvvv['name'].':';
												}
											}
										}
									}
									$basicmap['name'] = trim($keyname,':');
									$map[] = $basicmap;
								}
							}
						}
										
						$rulearray['rule'] = $rule;
						$rulearray['rulemap'] = $map;
						$data['rulearray'] = iserializer($rulearray);
						$data['isrule'] = 1;
						
					}
					

					
					$sku = json_decode($arr['data']['apiStack'][0]['value'],true);
					$data['price'] = $sku['data']['itemInfoModel']['priceUnits'][0]['price'];
					$data['oldprice'] = $sku['data']['itemInfoModel']['priceUnits'][1]['price'];
					
					$data['sales'] = $sku['data']['itemInfoModel']['totalSoldQuantity'];
					$data['stock'] = $sku['data']['itemInfoModel']['quantity'];

				}
				
				
				//详情
				$descurl = 'http://hws.m.taobao.com/cache/wdesc/5.0/?id=' . $id;
				$content = Util::httpGet($descurl);
				$content = iconv("gb2312", "utf-8",$content);
				
				$content = Util::cut($content,"tfsContent : '","anchors");
				$content = substr($content, 0, -4);
				$content = str_replace('width','widthed',$content);
				$data['content'] = str_replace('<img class="desc_anchor" id="desc-module-1" src="//assets.alicdn.com/kissy/1.0.0/build/imglazyload/spaceball.gif">','',$content);

			}
			
			
			
			$data['uniacid'] = $_W['uniacid'];
			$data['createtime'] = TIMESTAMP;
			$data['sortone'] = $sort['parent'];
			$data['sorttwo'] = $sort['id'];

			$res = pdo_insert('zxbddiy_sitetemp_good',$data);
			$collected ++;
		}

		Util::echoResult(200,'成功采集'.$collected.'项');

	// 提醒支付
	}elseif( $_GPC['op'] == 'remindpay' ){

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));

		if( empty( $order ) ) Util::echoResult(201,'未找到订单');
		if( $order['createtime'] < (TIMESTAMP - 3600*24*7) )	Util::echoResult(201,'订单已超时，不能再发提醒消息');

		$url = '/zxbddiy_sitetemp/pages/orderinfo/orderinfo?oid='.$order['orderid'];
		Message::orderMessage($order['openid'],$this->module['config']['ordermid'],$url,$order['formid'],$order['title'],$order['fee'],'等待支付');

		Util::echoResult(200,'已发送');

	// 发货处理
	}elseif( $_GPC['op'] == 'sendgood' ){

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');
		
		$res = model_order::sendOrder($_GPC['oid'],$_GPC['expressname'],$_GPC['expressnum']);	

		if( $res ){
			Util::echoResult(200,'已发货');
		}else{
			Util::echoResult(201,'发货处理失败');
		}

	// 履行
	}elseif( $_GPC['op'] == 'lawyerdo' ){

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');	

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));
		$res = pdo_update('zxbddiy_sitetemp_order',array('status'=>2,'sendtime'=>TIMESTAMP),array('id'=>$order['id']));
		
		Util::deleteCache('d',$order['openid']);
		Util::deleteCache('alld','all'.$order['plug']);

		if( $res ){
			Util::echoResult(200,'已设为履行');
		}else{
			Util::echoResult(201,'处理失败');
		}

	// 完成订单
	}elseif( $_GPC['op'] == 'comorder' ){

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');
		
		$res = model_order::comOrder($_GPC['oid']);	

		if( $res ){
			Util::echoResult(200,'已完成');
		}else{
			Util::echoResult(201,'完成处理失败');
		}

	// 修改地址
	}elseif( $_GPC['op'] == 'editaddress' ){

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');
		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));
		if( empty( $order ) ) Util::echoResult(201,'未找到订单');

		$add = iserializer( array('name'=>$_GPC['name'],'tel'=>$_GPC['tel'],'address'=>$_GPC['add']) );	

		pdo_update('zxbddiy_sitetemp_order',array('address'=>$add),array('id'=>$order['id']));

		Util::echoResult(200,'已修改');


	// 删除核销员
	}elseif( $_GPC['op'] == 'deleteadmin' ){

		if( empty( $_GPC['id'] ) ) Util::echoResult(201,'请选择核销员');
		$admin = pdo_get('zxbddiy_sitetemp_admin',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		if( empty( $admin ) ) Util::echoResult(201,'未找到核销员');

		pdo_delete('zxbddiy_sitetemp_admin',array('id'=>$admin['id']));

		Util::echoResult(200,'已删除');

	// 退款
	}elseif( $_GPC['op'] == 'refundorder' ){

		if( empty( $_GPC['oid'] ) ) Util::echoResult(201,'未找到订单');
		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'orderid'=>$_GPC['oid']));
		if( empty( $order ) ) Util::echoResult(201,'未找到订单');

		if( $order['status'] != 1 && $order['status'] != 2 ) Util::echoResult(201,'订单不能再退款');

		$pay = new WeixinPay();
		
		$arr['uorderid'] = $order['uorderid'];
		$arr['totalmoney'] = $order['fee'];
		$arr['refundmoney'] = sprintf('%.2f',$_GPC['money']);

		$res = $pay->refund( $arr );

		if($res['result_code']=='SUCCESS'){

			$res = pdo_update('zxbddiy_sitetemp_order',array('status'=>4,'refundtime'=>TIMESTAMP,'refundmoney'=>$arr['refundmoney']),array('id'=>$order['id']));
			
			Util::echoResult(200,'已退款');
		}else{
			Util::echoResult(201,'退款失败，原因：'.$res['err_code_des'].'，'.$res['return_msg']);
		}
		//2018-12-6修改
		//先获取token
//		$url="https://openapi.baidu.com/oauth/2.0/token";
//		$data['grant_type']="client_credentials";
//		$data['scope']="smartapp_snsapi_base";
//		$settings = Util::getModuleConfig();
//		$data['client_id']=$settings['appkey'];
//		$data['client_secret']=$_W['account']['secret'];
//		$res=Util::httpPost($url, $data);
//		$res=json_decode($res,TRUE);
//		$token=$res['access_token'];
//		$appkey=$settings['bd_appkey'];
//		//获取签名
//
//		$rsaPrivateKey=file_get_contents($_W['siteroot'].'addons/zxbddiy_sitetemp/cert/'.$_W['uniacid'].'/rsa_private_key.pem');
//		$requestApiParamsArr = array('appKey'=>$appkey,'token'=>$token,'sign'=>'');
//		//签名	
//		$rsaSign = NuomiRsaSign::genSignWithRsa($requestApiParamsArr ,$rsaPrivateKey);
//		
//		$userdata=array(
//			'appKey'=>$appkey,
//			'sign'=>$rsaSign,
//			'token'=>$token
//		);
//		var_dump($userdata);
//		$user=Util::httpPost("https://dianshang.baidu.com/platform/userinfo/tokenhandler/getuserinfo", $userdata);
//		
//		$user=json_decode($user,TRUE);
//		var_dump($user);
		
		
	// 修改权限
	}elseif( $_GPC['op'] == 'changeauth' ){

		if( !in_array( $_W['role'] , array('founder','vice_founder')) ) {
			Util::echoResult(201,'您无权操作');
		}
		$set = model_sysset::getSet();
		if( $_W['role'] == 'vice_founder' && empty( $set['viceisauth'] ) ){
			Util::echoResult(201,'您无权操作');
		}

		$uid = intval( $_GPC['uid'] );

		if( $_W['role'] == 'vice_founder' ){
			$ismy = pdo_get('uni_account_users',array('uniacid'=>$uid,'uid'=>$_W['uid']));
			if( empty( $ismy ) ) Util::echoResult(201,'您无权操作');
		}

		$auth = pdo_get('zxbddiy_sitetemp_auth',array('uniacid'=>$uid));


		$isshop	= empty($auth) ? ( empty( $set['deauth'] ) ? 0 : 1 ) : $auth['isshop'];
		$isclosecopy	= empty( $auth ) ? ( empty( $set['deauth'] ) ? 0 : 1 ) : $auth['isclosecopy'];
		$isdesk	= empty( $auth ) ? ( empty( $set['deauth'] ) ? 0 : 1 ) : $auth['isdesk'];
		$isappoint	= empty( $auth ) ? ( empty( $set['deauth'] ) ? 0 : 1 ) : $auth['isappoint'];
		$iscard	= empty( $auth ) ? ( empty( $set['deauth'] ) ? 0 : 1 ) : $auth['iscard'];
		
		
		$data = array(
			'uniacid' => $uid,
			'isshop' => $_GPC['type'] == 'isshop' ? $_GPC['status'] : $isshop,
			'isclosecopy' => $_GPC['type'] == 'isclosecopy' ? $_GPC['status'] : $isclosecopy,
			'isdesk' => $_GPC['type'] == 'isdesk' ? $_GPC['status'] : $isdesk,
			'isappoint' => $_GPC['type'] == 'isappoint' ? $_GPC['status'] : $isappoint,
			'iscard' => $_GPC['type'] == 'iscard' ? $_GPC['status'] : $iscard,
		);
		
		if( empty( $auth['id'] ) ) {

			pdo_insert('zxbddiy_sitetemp_auth',$data);

		}else{
			pdo_update('zxbddiy_sitetemp_auth',$data,array('id'=>$auth['id']));
		}

		Util::deleteCache('auth',$uid,false);
		Util::echoResult(200,'操作成功');

	// 批量修改权限
	}elseif( $_GPC['op'] == 'changeauthall' ){

		if( !in_array( $_W['role'] , array('founder','vice_founder')) ) {
			Util::echoResult(201,'您无权操作');
		}
		$set = model_sysset::getSet();
		if( $_W['role'] == 'vice_founder' && empty( $set['viceisauth'] ) ){
			Util::echoResult(201,'您无权操作');
		}

		if( !empty( $_GPC['arr'] ) ) {
			foreach ( $_GPC['arr'] as $v ) {

				if( $_W['role'] == 'vice_founder' ){
					$ismy = pdo_get('uni_account_users',array('uniacid'=>$v,'uid'=>$_W['uid']));
					if( empty( $ismy ) ) Util::echoResult(201,'您无权操作');
				}

				$auth = pdo_get('zxbddiy_sitetemp_auth',array('uniacid'=>$v));

				if( $_GPC['type'] == 'openall' ){
					$data = array(
						'uniacid' => $v,
						'isshop' => 1,
						'isclosecopy' => 1,
						'isdesk' => 1,
						'isappoint' => 1,
						'iscard' => 1,
					);
				}elseif( $_GPC['type'] == 'closeall' ){
					$data = array(
						'uniacid' => $v,
						'isshop' => 0,
						'isclosecopy' => 0,
						'isdesk' => 0,
						'isappoint' => 0,
						'iscard' => 0,
					);
				
				}else{
					$data = array(
						'uniacid' => $v,
						$_GPC['type'] => 1,
					);
				}

				// 取消副创始人的
				if( $_W['role'] == 'vice_founder' ){
					unset( $data['isclosecopy'] );
				}

				if( empty( $auth ) ) {
					pdo_insert('zxbddiy_sitetemp_auth',$data);

				}else{
					pdo_update('zxbddiy_sitetemp_auth',$data,array('id'=>$auth['id']));
				}

				Util::deleteCache('auth',$v,false);

			}
		}

		Util::echoResult(200,'操作成功');		


	}elseif( $_GPC['op'] == 'addtempsort' ){

		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_tempsort',array('id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['number'] = $_GPC['number'];
		$data['name'] = $_GPC['name'];

		if( $fid > 0 ){ 
			$res = pdo_update('zxbddiy_sitetemp_tempsort',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_tempsort',$data);
		}
		if( $res ){
			Util::deleteCache('tempsort','all',false);
			Util::echoResult(200,'已保存');
		} 
		Util::echoResult(201,'保存失败');


	}elseif( $_GPC['op'] == 'findtempsort' ){

		$form = pdo_get('zxbddiy_sitetemp_tempsort',array('id'=>$_GPC['fid']));
		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');

		Util::echoResult(200,'好',$form);	

	}elseif( $_GPC['op'] == 'changetempsort' ){

		if( $_W['role'] != 'founder' ) die;

		pdo_update('zxbddiy_sitetemp_temp',array('sort'=>$_GPC['target']),array('id'=>$_GPC['id']));

		Util::echoResult(200,'已更改',$form);	

	// 增减短信
	}elseif( $_GPC['op'] == 'addsms' ){

		if( $_W['role'] != 'founder' ) die;

		$uid = intval( $_GPC['uid'] );
		$value = intval( $_GPC['value'] );
		if( $value == 0 ) Util::echoResult(201,'请填写增减数值');

		$app = pdo_get('zxbddiy_sitetemp_auth',array('uniacid'=>$uid));

		if( empty( $app ) ){
			$data = array(
				'uniacid' => $uid,
			);
			pdo_insert('zxbddiy_sitetemp_auth',$data);
			$app = pdo_get('zxbddiy_sitetemp_auth',array('uniacid'=>$uid));
		}
		
		$v = $app['sms'] + $value;
		if( $v < 0 ) $v = 0;

		pdo_update('zxbddiy_sitetemp_auth',array('sms'=>$v),array('uniacid'=>$uid));

		Util::deleteCache('auth',$uid,false);
		Util::echoResult(200,'已更改');


	// 添加打印设备
	}elseif( $_GPC['op'] == 'addprint' ){

		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_print',array('id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];
		$data['code'] = $_GPC['code'];
		$data['type'] = $_GPC['type'];
		$data['status'] = $_GPC['status'];
		$data['key'] = $_GPC['key'];
		$data['devno'] = $_GPC['devno'];
		$data['plug'] = intval( $_GPC['plug'] );
		$data['times'] = intval( $_GPC['times'] );
		$data['isbig'] = intval( $_GPC['isbig'] );

		if( $_GPC['times'] > 5 ) Util::echoResult(201,'联数最多5联');

		if( $fid > 0 ){
			$res = pdo_update('zxbddiy_sitetemp_print',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_print',$data);
		}
		if( $res ){
			Util::deleteCache('print',0);
			Util::deleteCache('print',1);
			Util::deleteCache('print',2);
		} 
		Util::echoResult(200,'已保存');


	}elseif( $_GPC['op'] == 'findprint' ){

		$form = pdo_get('zxbddiy_sitetemp_print',array('id'=>$_GPC['fid']));
		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');

		Util::echoResult(200,'好',$form);	

	}elseif( $_GPC['op'] == 'testprint' ){

		$p = intval( $_GPC['plug'] );

		if( empty( $p ) ){
			$res = model_print::printOrder( -1,$this->module['config'],0 );
		}elseif ( $p == 1 ){

			$res = model_print::printappOrder( -1,$this->module['config'] );

		}elseif ( $p == 2 ){

			$res = model_print::printOrder( -1,$this->module['config'],2 );
		}
		
				

		if( empty( $res ) ) Util::echoResult(201,'没有发送打印数据，请添加打印设备');

		$resstr = '';
		$suc = 0;
		$fail = 0;
		foreach ( $res as $v ) {
			if( $v['res'] == 200 ) $suc ++;
			if( $v['res'] == 201 ){
				$fail ++;
				$resstr .= $v['msg'].'。';
			} 
		}

		Util::echoResult(200,'成功打印'.$suc.'项，失败'.$fail.'项。'.$resstr);


	// 导入商品
	}elseif( $_GPC['op'] == 'loadgoodtemp' ){	

		$good = data_good::loadhomeGood();

		Util::echoResult(200,'',$good);

	// 导入商品
	}elseif( $_GPC['op'] == 'loadgoodtemp1' ){	

		$good = data_good::loadfoodGood();

		Util::echoResult(200,'',$good);

	}elseif( $_GPC['op'] == 'loadgood' ){	

		if( empty( $_GPC['data'] ) ) Util::echoResult(201,'先选择需要导入的');

		foreach ( $_GPC['data'] as $v ) {
						

			$data = array(
				'uniacid' => $_W['uniacid'],
				'price' => $v['good']['price'],
				'oldprice' => $v['good']['oldprice'],
				'title' => $v['good']['title'],
				'desc' => $v['good']['desc'],
				'thumb' => $v['good']['thumb'],
				'pic' => iserializer( array($v['good']['thumb']) ),
				'stock' => 10000,
				'createtime' => TIMESTAMP,
				'type' => intval( $_GPC['type'] ),
			);
			
			if( !empty( $v['pid'] ) ) {
				$data['sorttwo'] = $v['pid'];
				$sort = pdo_get('zxbddiy_sitetemp_goodsort',array('id'=>$v['pid']));
				if( !empty( $sort ) ){
					$data['sortone'] = $sort['parent'];
				}
			}

			if( $v['type'] == 1 ){

				$apiurl = 'https://maicai.api.ddxq.mobi/ProductApi/productDetail?api_version=8.7.0&app_client_id=1&buildVersion=639&channel=App%20Store&device_id=153185668f0efc035d6f553db467560ae812aa64&device_model=iPhone7%2C2&device_name=iPhone%206&id='.$v['good']['id'].'&latitude=31.260026&longitude=121.532993&os_version=10.3.3&sign=eac5f1d234e004fdd40e269d40741237&station_id=574bec82916edf6f5c8fd8fd&time='.TIMESTAMP.'&uid=58ab9afbe1d4536b1622c1fb';
				
				$detarr = json_decode( file_get_contents( $apiurl ), true);

				if( $detarr['code'] == 0 ){
					$content = '';

					foreach ($detarr['data']['detail']['image_details'] as $vv ) {
						if( $vv != 'https://ddimg.ddxq.mobi/ba8fd00f1cf5a1515228957103.png?width=750&height=1042' ) {
							$content .= '<img src="'.$vv.'" width="100%">';
						}
					}

					$data['content'] = $content;
				}
				
			}elseif( $v['type'] == 2 ){

				$data['content'] = $data['desc'];

			}
			
			pdo_insert('zxbddiy_sitetemp_good',$data);
		}
		
		Util::echoResult(200,'已保存');

	// 添加订单表单
	}elseif( $_GPC['op'] == 'addorderform' ){


		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_orderform',array('id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];
		$data['type'] = $_GPC['type'];
		$data['number'] = $_GPC['number'];
		$data['pla'] = $_GPC['pla'];
		$data['ismust'] = intval( $_GPC['ismust'] );
		$data['plug'] = intval( $_GPC['plug'] );

		if( $data['type'] == 5 || $data['type'] == 6 ){
			$data['sitem'] = iserializer( $_GPC['arr'] );
		}

		if( $fid > 0 ){
			$res = pdo_update('zxbddiy_sitetemp_orderform',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_orderform',$data);
		}
		if( $res ){
			Util::deleteCache('orderform','all0');
			Util::deleteCache('orderform','all1');
			
		} 
		Util::echoResult(200,'已保存');


	}elseif( $_GPC['op'] == 'findorderform' ){

		$form = pdo_get('zxbddiy_sitetemp_orderform',array('id'=>$_GPC['fid']));
		$form['sitem'] = iunserializer( $form['sitem'] );

		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
			
		Util::echoResult(200,'好',$form);	
		
		
	// 添加桌子
	}elseif( $_GPC['op'] == 'adddesk' ){


		$fid = intval( $_GPC['fid'] );
		if( $fid > 0 ){
			$form = pdo_get('zxbddiy_sitetemp_desk',array('id'=>$fid));
			if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
		}
		if( empty( $_GPC['name'] ) ) Util::echoResult(201,'请填写名称');

		$data['uniacid'] = $_W['uniacid'];
		$data['name'] = $_GPC['name'];
		$data['number'] = $_GPC['number'];
		$data['plug'] = $_GPC['plug'];

		if( $fid > 0 ){
			$res = pdo_update('zxbddiy_sitetemp_desk',$data,array('id'=>$fid));
		}else{
			$res = pdo_insert('zxbddiy_sitetemp_desk',$data);
		}
		if( $res ){
			Util::deleteCache('desk','all');
			
		} 
		Util::echoResult(200,'已保存');


	}elseif( $_GPC['op'] == 'finddesk' ){

		$form = pdo_get('zxbddiy_sitetemp_desk',array('id'=>$_GPC['fid']));

		if( empty( $form ) ) Util::echoResult(201,'没有找到数据');
			
		Util::echoResult(200,'好',$form);			
	
	// 接单
	}elseif( $_GPC['op'] == 'dealapporder' ){

		$order = pdo_get('zxbddiy_sitetemp_appointorder',array('orderid'=>$_GPC['oid'],'uniacid'=>$_W['uniacid']));
		if( empty( $order ) ) Util::echoResult(201,'订单不存在');

		// 接单
		if( $_GPC['type'] == 'take' ) {
			if( $order['status'] != 1 ) Util::echoResult(201,'订单不能处理');
			pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>2),array('id'=>$order['id'],'uniacid'=>$_W['uniacid']));

			Util::deletecache('allapp','all');
			Util::deletecache('apd',$order['openid']);
			Util::echoResult(200,'已设为接单');
		}

		// 取消
		if( $_GPC['type'] == 'cancel' ) {
			if( $order['status'] != 1 && $order['status'] != 2 ) Util::echoResult(201,'订单不能处理');
			pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>4),array('id'=>$order['id'],'uniacid'=>$_W['uniacid']));

			Util::deletecache('allapp','all');
			Util::deletecache('apd',$order['openid']);
			Util::echoResult(200,'已设为取消状态');
		}			

		// 删除
		if( $_GPC['type'] == 'delete' ) {
			pdo_delete('zxbddiy_sitetemp_appointorder',array('id'=>$order['id'],'uniacid'=>$_W['uniacid']));	

			Util::deletecache('allapp','all');
			Util::deletecache('apd',$order['openid']);
			Util::echoResult(200,'已删除');
		}	
		
		// 完成
		if( $_GPC['type'] == 'com' ) {
			if( $order['status'] != 2 ) Util::echoResult(201,'订单不能处理');
			pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>3),array('id'=>$order['id'],'uniacid'=>$_W['uniacid']));

			Util::deletecache('allapp','all');
			Util::deletecache('apd',$order['openid']);
			Util::echoResult(200,'已设为完成状态');
		}	

		// 退款
		if( $_GPC['type'] == 'refund' ) {
			if( $order['ispay'] != 1 || $order['fee'] <= 0 || $order['status'] == 0 ) Util::echoResult(201,'订单不能处理');

			$pay = new WeixinPay();
			
			$arr['uorderid'] = $order['uorderid'];
			$arr['totalmoney'] = $order['fee'];
			$arr['refundmoney'] = sprintf('%.2f',$_GPC['money']);

			$res = $pay->refund( $arr );

			if($res['result_code'] == 'SUCCESS'){

				pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>5),array('id'=>$order['id'],'uniacid'=>$_W['uniacid']));
				
				Util::deletecache('allapp','all');
				Util::deletecache('apd',$order['openid']);
				Util::echoResult(200,'已退款');
			}else{
				Util::echoResult(201,'退款失败，原因：'.$res['err_code_des'].'，'.$res['return_msg']);
			}
			
		}			

	}elseif( $_GPC['op'] == 'savecard' ){

		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['name'],
			'start' => strtotime( $_GPC['rangetime']['start'] ),
			'end' => strtotime( $_GPC['rangetime']['end'] ),
			'thumb' => $_GPC['thumb'],
			'usetype' => $_GPC['usetype'],
			'isbind' => $_GPC['isbind'],
			'type' => intval( $_GPC['type'] ),
			'value' => sprintf('%.2f',$_GPC['value']),
			'useleast' => sprintf('%.2f',$_GPC['useleast']),
			'content' => $_GPC['content'],
			'status' => intval( $_GPC['status'] ),
			'stock' => intval( $_GPC['stock'] ),
			'usetime' => sprintf('%.2f',$_GPC['usetime']),
			'limittime' => intval( $_GPC['limittime'] ),
			'isshare' => intval( $_GPC['isshare'] ),
		);


		if( empty( $data['name'] ) ) Util::echoResult(201,'请填写名称');
		if( $data['value'] <= 0 ) Util::echoResult(201,'面值必须大于0');
		if( $data['type'] == 0 && $data['value'] >= $data['useleast'] ) Util::echoResult(201,'面值必须小于使用条件');
		if( $data['type'] == 1 && ( $data['value'] >= 10 || $data['value'] <= 0 ) ) Util::echoResult(201,'面值需在0-10之间');	
		if( $data['usetime'] <= 0 ) Util::echoResult(201,'可使用时间必须大于0');

		if( empty( $_GPC['id'] ) ) {

			pdo_insert('zxbddiy_sitetemp_card',$data);
			$id = pdo_insertid();
		}else{
			$id = intval( $_GPC['id'] );
			pdo_update('zxbddiy_sitetemp_card',$data,array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		}

		Util::deletecache('card',$id);
		Util::echoResult(200,'已保存');

	// 处理卡券
	}elseif( $_GPC['op'] == 'dealcard' ){

		$cardlog = pdo_get('zxbddiy_sitetemp_cardlog',array('id'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		if( empty( $cardlog ) ) Util::echoResult(201,'卡券不存在');

		if( $_GPC['type'] == 'delete' ) {
			pdo_delete('zxbddiy_sitetemp_cardlog',array('id'=>$cardlog['id']));
			Util::echoResult(200,'已删除');
		}

		if( $_GPC['type'] == 'com' ) {
			pdo_update('zxbddiy_sitetemp_cardlog',array('status'=>1,'usetime'=>TIMESTAMP),array('id'=>$cardlog['id']));
			Util::echoResult(200,'已核销');
		}		
		if( $_GPC['type'] == 'to1' ) {
			pdo_update('zxbddiy_sitetemp_cardlog',array('status'=>1,'usetime'=>TIMESTAMP),array('id'=>$cardlog['id']));
			Util::echoResult(200,'操作完成');
		}		
		if( $_GPC['type'] == 'to0' ) {
			pdo_update('zxbddiy_sitetemp_cardlog',array('status'=>0,'usetime'=>0),array('id'=>$cardlog['id']));
			Util::echoResult(200,'操作完成');
		}			

	}elseif( $_GPC['op'] == 'deletebar' ){

		if( $_W['role'] == 'founder' ){
			$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$_GPC['id']));
		}else{
			$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$_GPC['id'],'uniacid'=>$_W['uniacid']));
		}
		if( empty( $bar ) ) Util::echoResult(201,'导航不存在');

		pdo_delete('zxbddiy_sitetemp_bar',array('id'=>$bar['id']));

		Util::echoResult(200,'已删除');


	}elseif( $_GPC['op'] == 'tempcloud' ){
		set_time_limit(0);
		session_start();

		$res = model_cloud::getContent( $_GPC['data'],$_SESSION['zfuser'] );
		
		if( $res['status'] == 200 ) {
			if( $_GPC['data']['type'] == 'checklogin' ) {
				$_SESSION['zfuser'] = $res['obj']['user'];
				
			}elseif( $_GPC['data']['type'] == 'loadtemp' ) {
				
				$temp = iunserializer( htmlspecialchars_decode( $res['obj']['temp'] ),true );

				if( is_array( $temp ) && !empty( $temp['temp'] ) ) {
					
					Util::echoResult(200,'已提交加载，在我的模板内，刷新页面可看到',array('data'=>$res['obj']['temp']));
					
				}
				Util::echoResult(201,'加载失败');

			}elseif( $_GPC['data']['type'] == 'loginout' ){
				$_SESSION['zfuser'] = null;
			}


			Util::echoResult(200,$res['msg'],array('html'=>$res['obj']['html']));
		}else{
			Util::echoResult(201,$res['msg']);
		}

	}elseif( $_GPC['op'] == 'httploadtemp' ){

		$temp = iunserializer( htmlspecialchars_decode( $_GPC['temp'] ),true );
		if( is_array( $temp ) && !empty( $temp['temp'] ) ) {
			model_temp::loadTemp( $temp );
		}
		
		
	}elseif( $_GPC['op'] == 'tocloud' ){
		set_time_limit(0);

		if( $_W['role'] != 'founder' ) die;
		$id = intval( $_GPC['id'] );

		$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$id));

		if( empty( $temp ) ) Util::echoResult(201,'模板不存在');
		$page = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$temp['id']));	
		if( empty( $page ) ) Util::echoResult(201,'模板还没有页面');
		
		if( !is_numeric( $_GPC['fee'] ) ) Util::echoResult(201,'出售云币需设置数字');

		$temp['img'] = tomedia( $temp['img'] );
		$temp['showqr'] = tomedia( $temp['showqr'] );

		$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$temp['id']));

		$data = array(
			'temp' => $temp,
			'bar' => $bar,
			'page' => $page,
		);

		$data = iserializer( $data );
		session_start();

		$hdata = array(
			'data' => $data,
			'type' => 'subtemp',
			'site' => $_W['siteroot'],
			'tid' => $temp['id'],
			'fee' => $_GPC['fee'],
		);

		$res = model_cloud::getContent( $hdata,$_SESSION['zfuser'],2 );
		
		if( $res['status'] == 201 ) {
			Util::echoResult(201,$res['msg']);
		}elseif( $res['status'] == 210 ) {
			Util::echoResult(210,$res['msg'],array('html'=>$res['obj']['html']));			
		}else{
			Util::echoResult(200,'已提交');
		}

	}elseif( $_GPC['op'] == 'fixsite' ){

		if( empty( $_GPC['oldsite'] ) || empty( $_GPC['newsite'] ) ){
			Util::echoResult(201,'请填写完整的新旧域名');
		}
		set_time_limit(0);
		if( $_W['role'] != 'founder' ) die;

		$allpage = pdo_getall('zxbddiy_sitetemp_page');
		foreach ( $allpage as $v ) {
			$params = str_replace(array($_GPC['oldsite'],urlencode( $_GPC['oldsite'] )), array($_GPC['newsite'],$_GPC['newsite']), $v['params']);
			pdo_update('zxbddiy_sitetemp_page',array('params'=>$params),array('id'=>$v['id']));
		}

		$allbar = pdo_getall('zxbddiy_sitetemp_bar');
		foreach ( $allpage as $v ) {
			$data = str_replace($_GPC['oldsite'], $_GPC['newsite'], $v['data']);
			pdo_update('zxbddiy_sitetemp_bar',array('data'=>$data),array('id'=>$v['id']));
		}

		$allgood = pdo_getall('zxbddiy_sitetemp_good');
		foreach ( $allgood as $v ) {
			$params = str_replace(array($_GPC['oldsite'],urlencode( $_GPC['oldsite'] ),htmlspecialchars( $_GPC['oldsite'] )), array($_GPC['newsite'],$_GPC['newsite'],$_GPC['newsite']), $v['content']);
			pdo_update('zxbddiy_sitetemp_good',array('content'=>$params),array('id'=>$v['id']));
		}

		$allgood = pdo_getall('zxbddiy_sitetemp_appoint');
		foreach ( $allgood as $v ) {
			$params = str_replace(array($_GPC['oldsite'],urlencode( $_GPC['oldsite'] ),htmlspecialchars( $_GPC['oldsite'] )), array($_GPC['newsite'],$_GPC['newsite'],$_GPC['newsite']), $v['content']);
			pdo_update('zxbddiy_sitetemp_appoint',array('content'=>$params),array('id'=>$v['id']));
		}

		$allgood = pdo_getall('zxbddiy_sitetemp_card');
		foreach ( $allgood as $v ) {
			$params = str_replace(array($_GPC['oldsite'],urlencode( $_GPC['oldsite'] ),htmlspecialchars( $_GPC['oldsite'] )), array($_GPC['newsite'],$_GPC['newsite'],$_GPC['newsite']), $v['content']);
			pdo_update('zxbddiy_sitetemp_card',array('content'=>$params),array('id'=>$v['id']));
		}	

		$allgood = pdo_getall('zxbddiy_sitetemp_product');
		foreach ( $allgood as $v ) {
			$params = str_replace(array($_GPC['oldsite'],urlencode( $_GPC['oldsite'] ),htmlspecialchars( $_GPC['oldsite'] )), array($_GPC['newsite'],$_GPC['newsite'],$_GPC['newsite']), $v['content']);
			pdo_update('zxbddiy_sitetemp_product',array('content'=>$params),array('id'=>$v['id']));
		}

		$allgood = pdo_getall('zxbddiy_sitetemp_article');
		foreach ( $allgood as $v ) {
			$params = str_replace(array($_GPC['oldsite'],urlencode( $_GPC['oldsite'] ),htmlspecialchars( $_GPC['oldsite'] )), array($_GPC['newsite'],$_GPC['newsite'],$_GPC['newsite']), $v['content']);
			pdo_update('zxbddiy_sitetemp_article',array('content'=>$params),array('id'=>$v['id']));
		}


		Util::echoResult(200,'已修复');

	}elseif( $_GPC['op'] == 'addorderpro' ){

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['oid']));
		if( empty( $order ) ) Util::echoResult(201,'订单不存在');

		
		$order['progress'] = iunserializer( $order['progress'] );
		if( empty( $order['progress'] ) ){
			$arr = array();
		}else{
			$arr = $order['progress'];
		}
		$arr[] = array(
			'key' => TIMESTAMP,
			'text' => $_GPC['name'],
			'time' => strtotime( $_GPC['time'] ),
			'img' => $_GPC['img'],
			'type' => 0,
		);

		$sort = array();
		foreach ($arr as $v) {
			$sort[] = $v['time'];
		}

		array_multisort($sort,SORT_DESC,$arr);

		pdo_update('zxbddiy_sitetemp_order',array('progress'=> iserializer( $arr ) ),array('id'=>$order['id']));
		Util::echoResult(200,'已增加');

	}elseif( $_GPC['op'] == 'deletepro' ){

		$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['oid']));
		if( empty( $order ) ) Util::echoResult(201,'订单不存在');

		$order['progress'] = iunserializer( $order['progress'] );	

		$oarr = array();
		if( !empty( $order['progress'] ) ) {
			foreach ($order['progress'] as $k => $v) {
				if( $v['key'] != $_GPC['tid'] ) {
					$oarr[] = $v;
				}
			}
		}

		pdo_update('zxbddiy_sitetemp_order',array('progress'=> iserializer( $oarr ) ),array('id'=>$order['id']));

		Util::echoResult(200,'已删除');

	}elseif( $_GPC['op'] == 'deleteform' ){	

		$type = intval( $_GPC['type'] ) - 1;

		$where = array('uniacid'=>$_W['uniacid'],'isread'=>$type);

		pdo_delete('zxbddiy_sitetemp_form',$where);

		Util::echoResult(200,'已删除');
		
	}elseif( $_GPC['op'] == 'queue' ){

		for( $i = 0;$i<3;$i++ ){
			$cache = Util::getCache('queue','q');
			if( empty( $cache ) || $cache['time'] < ( time() - 40 ) ){
				if( $i == 2 ){
					$url = Util::createModuleUrl('message',array('op'=>1));
					$res = Util::httpGet($url,'', 1);
					die('2');
				}
				sleep(1);
			}else{
				die('1');
			}			
			
		}
	}