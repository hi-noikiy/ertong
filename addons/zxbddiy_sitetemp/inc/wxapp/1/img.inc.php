<?php 
	global $_W,$_GPC;

	

	load()->model('account');
	$uniacccount = WeAccount::create($_W['acid']);	

	if( $_GPC['op'] == 'hexiao' && $_GPC['oid'] ) {
		$res = Util::wxappQrcode( $uniacccount,$_GPC['oid'],'zxbddiy_sitetemp/pages/hexiao/hexiao' );
	}

	if( $_GPC['op'] == 'hexiaocard' && $_GPC['cid'] ) {
		$res = Util::wxappQrcode( $uniacccount,$_GPC['cid'],'zxbddiy_sitetemp/pages/hexiao/hexiaocard' );
	}		

	//var_dump( $res );
	header("content-type: image/jpeg"); 
	echo $res;

	


	
	
