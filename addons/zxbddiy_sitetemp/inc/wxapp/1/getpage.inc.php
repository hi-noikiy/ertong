<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	$_W['set'] = $this->module['config'];
	$data = array();
	
	if( !empty( $_GPC['scene'] ) ) {
		if( strstr($_GPC['scene'], 'p') !== false ){
			$pid = trim($_GPC['scene'],'p');
		}else{
			$tid = intval( $_GPC['scene'] ); // 扫码
		}
	}
	if( !empty( $_GPC['pid'] ) ) {
		$pid = intval( $_GPC['pid'] );
	}
	
//	var_dump($_GPC['pid']);die;
	//$where['uniacid'] = $_W['uniacid'];
	if( $pid > 0 ) { // 指定的页面
		$where['id'] = $pid;
	}else{ // 默认的页面
		$tid = $tid > 0 ? $tid : 'all'; // all是使用的模板
		$bar = model_temp::getBar( $tid );

		if( !empty( $bar ) ) {
			$bar['data'] = iunserializer( $bar['data'] );

			if( empty( $_GPC['pindex'] ) ){
				$id = intval( $bar['data']['data'][0]['pageid'] );
			}else{
				$id = intval( $bar['data']['data'][$_GPC['pindex'] - 1]['pageid'] );
			}			
			if( $id > 0 ) $where['id'] = $id;

			// 跳转到其他的页面
			if( $id == -1 ) $this->result(0, '', array('headto'=>1,'url'=>$bar['data']['data'][0]['url'] ));

		}
	}

	$page = pdo_get('zxbddiy_sitetemp_page',$where);
	$page['params'] = iunserializer( $page['params'] );
	if( $page['params']['basic']['isaudio'] == 1 ) {
		$page['params']['basic']['audiourl'] = tomedia( $page['params']['basic']['audiourl'] );
	}else{
		$page['params']['basic']['isaudio'] = 0;
	}		
	
	if( !empty( $page['params']['data'] ) ) {

		foreach ( $page['params']['data'] as $k => &$v ) {
			if( $v['name'] == 'text' ) {
				$v['params']['content'] = urldecode( $v['params']['content'] );
			}

			$article = array();
			if( $v['name'] == 'article' ) {

				if( $v['params']['showtype'] == 1 ){
					$v['params']['data'] = array();

					$num = empty( $v['params']['goodnum'] ) ? 4 : $v['params']['goodnum'];
					$where = array('uniacid'=>$_W['uniacid'],'sortid'=>$v['params']['sortid']);
					$order = '`id` DESC';
					if( $v['params']['ordertype'] == 1 ) $order = '`number` DESC';
						
					$article = Util::getAllDataInSingleTable('zxbddiy_sitetemp_article',$where,1,$num,$order,false,false,' id ');
					if( !empty( $article[0] ) ) {
						foreach ($article[0] as $kk => $vv) {
							$article[0][$kk]['gid'] = $vv['id'];
						}
					}
					$v['params']['data'] = $article[0];
				}

				$v['artlist'] = array();
				if( !empty( $v['params']['data'] ) ) {
					foreach ($v['params']['data'] as $vv) {
						$article = model_article::getArticle( $vv['gid'] );
						if( !empty( $article ) ) {

							$art['title'] = $article['title'];
							$art['img'] = tomedia( $article['img'] );
							if( empty( $article['type'] ) ) {
								$art['url'] = '/zxbddiy_sitetemp/pages/art/art?aid='.$article['id'];
							}elseif( $article['type'] == 1 ){
								$art['url'] = '/zxbddiy_sitetemp/pages/webview/webview?url='.urlencode( $article['url'] );
							}
							
							$art['time'] = $article['time'];

							$v['artlist'][] = $art;
						}
					}
				}
			}

			$product = array();
			if( $v['name'] == 'product' ) {

				if( $v['params']['showtype'] == 1 ){
					$v['params']['data'] = array();

					$num = empty( $v['params']['goodnum'] ) ? 4 : $v['params']['goodnum'];
					$where = array('uniacid'=>$_W['uniacid'],'sortid'=>$v['params']['sortid']);
					$order = '`id` DESC';
					if( $v['params']['ordertype'] == 1 ) $order = '`number` DESC';
						
					$product = Util::getAllDataInSingleTable('zxbddiy_sitetemp_product',$where,1,$num,$order,false,false,' id ');
					if( !empty( $product[0] ) ) {
						foreach ($product[0] as $kk => $vv) {
							$product[0][$kk]['gid'] = $vv['id'];
						}
					}
					$v['params']['data'] = $product[0];
				}

				$v['artlist'] = array();
				if( !empty( $v['params']['data'] ) ) {
					foreach ($v['params']['data'] as $vv) {
						$product = model_product::getProduct( $vv['gid'] );
						if( !empty( $product ) ) {

							$art['title'] = $product['title'];
							$art['img'] = tomedia( $product['img'] );
							$art['url'] = '/zxbddiy_sitetemp/pages/product/product?aid='.$product['id'];
							$art['time'] = $product['time'];

							$v['artlist'][] = $art;
						}
					}
				}
			}


			if( $v['name'] == 'goods' || $v['name'] == 'ogoods') {

				if( $v['params']['showtype'] == 1 ){
					$v['params']['data'] = array();

					$num = empty( $v['params']['goodnum'] ) ? 4 : $v['params']['goodnum'];
					$where = array('uniacid'=>$_W['uniacid'],'status'=>0,'sorttwo'=>$v['params']['sortid']);
					$order = '`id` DESC';
					if( $v['params']['ordertype'] == 1 ) $order = '`number` DESC';
						
					$good = Util::getAllDataInSingleTable('zxbddiy_sitetemp_good',$where,1,$num,$order,false,false,' id ');
					if( !empty( $good[0] ) ) {
						foreach ($good[0] as $kk => $vv) {
							$good[0][$kk]['gid'] = $vv['id'];
						}
					}
					$v['params']['data'] = $good[0];
				}

				if( !empty( $v['params']['data'] ) ){

					foreach ( $v['params']['data'] as $kk => $vv ) {
						$good = model_good::getGood( $vv['gid'] );
						
						if( empty( $good ) || $good['status'] == 1 ){
							unset( $v['params']['data'][$kk] );
						}else{
							$v['params']['data'][$kk]['title'] = $good['title'];
							$v['params']['data'][$kk]['name'] = $good['title'];
							$v['params']['data'][$kk]['price'] = $good['price'];
							$v['params']['data'][$kk]['thumb'] = tomedia($good['thumb']);
							$v['params']['data'][$kk]['sales'] = $good['sales'];
							$v['params']['data'][$kk]['oldprice'] = $good['oldprice'];
							$v['params']['data'][$kk]['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$vv['gid'];

							if( $good['type'] == 1 ){
								$v['params']['data'][$kk]['url'] = '/zxbddiy_sitetemp/pages/deskorder/good?gid='.$vv['gid'];
							}
						}
					}
				}
				if( empty( $v['params']['data'] ) ) unset( $page['params']['data'][$k] );
			}

			if( $v['name'] == 'appoint' ) {

				if( $v['params']['showtype'] == 1 ){
					$v['params']['data'] = array();

					$num = empty( $v['params']['goodnum'] ) ? 4 : $v['params']['goodnum'];
					$where = array('uniacid'=>$_W['uniacid'],'status'=>0,'sortid'=>$v['params']['sortid']);
					$order = '`id` DESC';
					if( $v['params']['ordertype'] == 1 ) $order = '`number` DESC';
						
					$appoint = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appoint',$where,1,$num,$order,false,false,' `id` ');
					if( !empty( $appoint[0] ) ) {
						foreach ($appoint[0] as $kk => $vv) {
							$appoint[0][$kk]['gid'] = $vv['id'];
							//$appoint[0][$kk]['thumb'] = tomedia($vv['thumb']);
						}
					}
					$v['params']['data'] = $appoint[0];
				}

				if( !empty( $v['params']['data'] ) ){

					foreach ( $v['params']['data'] as $kk => $vv ) {
						$appoint = model_appoint::getAppoint( $vv['gid'] );
												
						if( empty( $appoint ) || $appoint['status'] == 1 ){
							unset( $v['params']['data'][$kk] );
						}else{
							$v['params']['data'][$kk]['title'] = $appoint['name'];
							$v['params']['data'][$kk]['name'] = $appoint['name'];
							$v['params']['data'][$kk]['desc'] = $appoint['desc'];
							$v['params']['data'][$kk]['thumb'] = tomedia($appoint['thumb']);
							$v['params']['data'][$kk]['num'] = $appoint['num'];
							$v['params']['data'][$kk]['price'] = $appoint['price'];
							$v['params']['data'][$kk]['ispay'] = $appoint['ispay'];
							
							$v['params']['data'][$kk]['url'] = '/zxbddiy_sitetemp/pages/appoint/info?aid='.$vv['gid'];
						}
					}
				}
				if( empty( $v['params']['data'] ) ) unset( $page['params']['data'][$k] );
			}			

			if( $v['name'] == 'actcard' ) {

				if( $v['params']['showtype'] == 1 ){
					$v['params']['data'] = array();

					$num = empty( $v['params']['goodnum'] ) ? 4 : $v['params']['goodnum'];
					$where = array('uniacid'=>$_W['uniacid'],'status'=>0,'usetype'=>$v['params']['sortid']);
					$order = '`id` DESC';
						
					$card = Util::getAllDataInSingleTable('zxbddiy_sitetemp_card',$where,1,$num,$order,false,false,' id ');
					
					if( !empty( $card[0] ) ) {
						foreach ($card[0] as $kk => $vv) {
							$card[0][$kk]['gid'] = $vv['id'];
						}
					}
					$v['params']['data'] = $card[0];
				}
				
				if( !empty( $v['params']['data'] ) ){
					foreach ( $v['params']['data'] as $kk => $vv ) {
											
						$card = model_card::getCard( $vv['gid'] );	

						if( empty( $card ) || $card['status'] == 1 ){
							unset( $v['params']['data'][$kk] );
						}else{
							$v['params']['data'][$kk]['name'] = $card['name'];
							$v['params']['data'][$kk]['value'] = $card['value'];
							$v['params']['data'][$kk]['thumb'] = tomedia($card['thumb']);
							$v['params']['data'][$kk]['stock'] = $card['stock'];
							$v['params']['data'][$kk]['useleast'] = $card['useleast'];
							$v['params']['data'][$kk]['type'] = $card['type'];
							
							$v['params']['data'][$kk]['url'] = '/zxbddiy_sitetemp/pages/card/info?cid='.$vv['gid'];
						}
					}
				}
				if( empty( $v['params']['data'] ) ) unset( $page['params']['data'][$k] );
			}	

		}
	}
	
	$data['page'] = $page;

	// footer
	if( empty( $bar ) ) {
		$bar =pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$page['tempid']));
		$bar['data'] = iunserializer( $bar['data'] );
	}

	$data['bar'] = $bar['data'];

	// copyright
	$auth = model_auth::authList();
	if( empty( $auth['isclosecopy'] ) ){
		$copy = pdo_get('zxbddiy_sitetemp_copyright');
		if( !empty( $copy ) && $copy['status'] == 0 ) {
			$copy['content'] = htmlspecialchars_decode( $copy['content'] );
			$picarr = iunserializer( $copy['pic'] );
			$copy['pic'] = array();
			$copy['params'] = iunserializer( $copy['params'] );

			if( !empty( $picarr ) ) {
				
				foreach ( $picarr as &$pic ) {
					$copy['pic'][]= array( 'url'=>tomedia( $pic ) );
				}
			}

			$data['copy'] = $copy;
		}
	}
	
	$sysset = model_sysset::getSet();
	if( $sysset['isvicead'] == 1 ){		
		$isset = pdo_get('uni_account_users',array('uniacid'=>$_W['uniacid'],'role'=>'vice_founder'));
		if( !empty( $isset ) ) {
			$ad = pdo_get('zxbddiy_sitetemp_vicead',array('uid'=>$isset['uid']));
						
			if( !empty( $ad ) && $ad['status'] == 1 ) {
				$ad['content'] = htmlspecialchars_decode( $ad['content'] );
				$ad['params'] = iunserializer( $ad['params'] );

				if( !empty( $ad['params']['pic'] ) ) {
					$picc = $ad['params']['pic'];
					$ad['params']['pic'] = array();
					foreach ( $picc as $v ) {
						$ad['params']['pic'][]= array( 'url'=>tomedia( $v ) );
					}
				}

				$data['vicead'] = $ad;
			}
		}
	}


	$this->result(0, '', $data);