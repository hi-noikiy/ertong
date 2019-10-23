<?php 
	global $_W,$_GPC;

	load()->model('account');
	
	$uniacccount = WeAccount::create($_W['acid']);	

	if( $_GPC['op'] == 'admin' ) {
		$res = Util::wxappQrcode( $uniacccount,$_GPC['id'],'zxbddiy_sitetemp/pages/page/page' );

	}elseif( $_GPC['op'] == 'formlist' ) {

		$res = Util::wxappQrcode( $uniacccount,TIMESTAMP,'zxbddiy_sitetemp/pages/form/form' );

	}elseif( $_GPC['op'] == 'bindadmin' ) {

		$code = md5( $_W['account']['secret'].$_W['uniacid'].rand(111,999) );

		//$code = '123';

		Util::setCache('adminkey','all',array('t'=>TIMESTAMP,'plug'=>$_GPC['plug'],'code'=>$code));

		$res = Util::wxappQrcode( $uniacccount,$code,'zxbddiy_sitetemp/pages/bindadmin/bindadmin' );

	}/*elseif( $_GPC['op'] == 'bindcardadmin' ) {

		$code = md5( $_W['account']['secret'].$_W['uniacid'] );
		$res = Util::wxappQrcode( $uniacccount,$code,'zxbddiy_sitetemp/pages/bindadmin/bindcardadmin' );		

	}*/elseif( $_GPC['op'] == 'good' ) {

		$res = Util::wxappQrcode( $uniacccount,$_GPC['gid'],'zxbddiy_sitetemp/pages/good/good' );
	
	}elseif( $_GPC['op'] == 'temp' ) {

		$res = Util::wxappQrcode( $uniacccount,$_GPC['tid'],'zxbddiy_sitetemp/pages/page/page' );
	
	}elseif( $_GPC['op'] == 'desk' ) {

		$res = Util::wxappQrcode( $uniacccount,$_GPC['did'],'zxbddiy_sitetemp/pages/deskorder/exbuy' );
	}elseif( $_GPC['op'] == 'appointadmin' ) {

		$res = Util::wxappQrcode( $uniacccount,$_GPC['aid'],'zxbddiy_sitetemp/pages/bindappoint/bindappoint' );
	}elseif( $_GPC['op'] == 'adminlist' ) {

		$res = Util::wxappQrcode( $uniacccount,TIMESTAMP,'zxbddiy_sitetemp/pages/appoint/adminlist' );
	
	}elseif( $_GPC['op'] == 'card' ) {

		$res = Util::wxappQrcode( $uniacccount,$_GPC['cid'],'zxbddiy_sitetemp/pages/card/info' );
	}elseif( $_GPC['op'] == 'page' ) {

		$res = Util::wxappQrcode( $uniacccount,'p'.$_GPC['id'],'zxbddiy_sitetemp/pages/page/page' );
	}
	
	
	//var_dump( $res );
	header("content-type: image/jpeg");
	//header("Content-Disposition:attachment; filename=订单列表.png");
	echo $res;
	
	
