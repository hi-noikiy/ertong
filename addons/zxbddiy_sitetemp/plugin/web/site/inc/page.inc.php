<?php 
	global $_W,$_GPC;
	
	$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['tid']));
	if( empty( $temp ) || ( $temp['issetsystem'] == 1 && $_W['role'] != 'founder' ) ) 
		itoast('请选择模板','','error');


	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_page');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	
	if($_GPC['op'] == 'list'){

		$where = array('uniacid'=>$_W['uniacid'],'isinit'=>0);
		$where['tempid'] = $_GPC['tid'];

		if( $temp['issetsystem'] == 1 ) unset( $where['uniacid'] );
//var_dump( $where );

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_page',$where,$_GPC['page'],10,' `id` DESC ',false,true,' id,name,tempid ','','',false);
		$list = $info[0];
		$pager = $info[1];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['imgurl'] = $this->createWebUrl('img',array('op'=>'page','id'=>$v['id']));
			}
		}

	}
	
	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_page');
		if($res) itoast('删除成功','','success');
	}

	$goodtowsort = model_goodsort::getTwoClass(0);
	$ordertowsort = model_goodsort::getTwoClass(1);
	$allsort = model_artsort::getSort();
	$appsort = model_appsort::getSort();
	$prosort = model_prosort::getSort();
	

	if( $_GPC['op'] == 'edit' ) {
		$where = array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']);
		if( $_W['role'] == 'founder' ) unset( $where['uniacid'] );

		$page = pdo_get('zxbddiy_sitetemp_page',$where);
		$alltemppage = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$page['tempid']),array('id','name'));
		
		if( empty( $page ) ) message('未找到页面',referer(),'error');
		$page['params'] = iunserializer( $page['params'] );
		
		if( !empty( $page['params']['data'] ) ){

			foreach ( $page['params']['data'] as $k => &$v ) {
				
				if( $v['name'] == 'goods' ){
					if( !empty( $v['params']['data'] ) ) {
						foreach ( $v['params']['data'] as $kk => $vv ) {
							
							$good = model_good::getGood( $vv['gid'] );
							
							if( empty( $good ) ){
								unset( $v['params']['data'][$kk] );
							}else{
								$v[$kk]['thumb'] = tomedia( $good['thumb'] );
								$v[$kk]['title'] = $good['title'];
								$v[$kk]['price'] = $good['price'];
								$v[$kk]['oldprice'] = $good['oldprice'];
								$v[$kk]['sales'] = $good['sales'];
							}

						}
					}
				}

			}
			if( empty( $page['params']['data'][$k] ) ) unset( $page['params']['data'][$k] );
		}
		

		$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$page['tempid']));		
		if( !empty( $bar ) ) $bar['data'] = iunserializer( $bar['data'] );

	}

	if( $_GPC['op'] == 'add' ) {

		$where = array('uniacid'=>$_W['uniacid'],'tempid'=>$_GPC['tid'],'isinit'=>1);
		if( $_W['role'] == 'founder' ){
			unset( $where['uniacid'] );
		}
		$page = pdo_get('zxbddiy_sitetemp_page',$where);
		if( empty( $page ) ){
			$indata = array(
				'uniacid' => $_W['uniacid'],
				'isinit' => 1,
				'tempid' => $_GPC['tid'],
			);
			pdo_insert('zxbddiy_sitetemp_page',$indata);
			$indata['id'] = pdo_insertid();
			$page = $indata;
			$page['params'] = array('data'=>array(),'basic'=>false);
		}else{
			$page['params'] = iunserializer( $page['params'] );
		}
		
		

		$alltemppage = pdo_getall('zxbddiy_sitetemp_page',array('tempid'=>$_GPC['tid']),array('id','name'));
		$thistemp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['tid']));

		$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$_GPC['tid']));
		if( !empty( $bar ) ) $bar['data'] = iunserializer( $bar['data'] );

		if( $_GPC['opp'] == 'bar' ) {
			$page = pdo_get('zxbddiy_sitetemp_page',array('uniacid'=>$_W['uniacid'],'tempid'=>$thistemp['id']));
			$page['params'] = iunserializer( $page['params'] );
		}

	}

	if( $_GPC['op'] == 'add' && !empty( $_GPC['lid'] ) ) {
		$page = pdo_get('zxbddiy_sitetemp_page',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['lid']));
		if( empty( $page ) ) itoast('未找到页面','','error');
		$page['params'] = iunserializer( $page['params'] );
		unset( $page['id'] );
	}

	if( $_GPC['op'] == 'bar' ) {
		
		$page = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$_GPC['tid']));
		if( !empty( $page ) ) $page['data'] = iunserializer( $page['data'] );
		
		$thistemp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['tid']));

	}

	if( $_GPC['op'] == 'downqr' ) {
		
		
		load()->model('account');
		$uniacccount = WeAccount::create($_W['acid']);

		$res = Util::wxappQrcode( $uniacccount,'p'.$_GPC['id'],'zxbddiy_sitetemp/pages/page/page' );

		header("content-type: image/jpeg");
		header("Content-Disposition:attachment; filename=".$_GPC['id'].".jpg");	
		echo $res;
	  	die;
		

	}	

	include $this->pTemplate('page');