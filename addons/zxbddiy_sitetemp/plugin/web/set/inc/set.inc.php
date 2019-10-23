<?php 
	global $_GPC,$_W;	
	$_GPC['op'] = empty( $_GPC['op'] ) ? 'basic' : $_GPC['op'];
	
	$settings = Util::getModuleConfig();
	$settings['shoppic'] = iunserializer( $settings['shoppic'] );
	/*$url = 'https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?resource_id=8188&from_mid=1&query=600887&hilight=disp_data.*.title&sitesign=0fd36183741817c767912ef9cfd3f247&eprop=dayK&_=10255104163474';
	$res = file_get_contents( $url );
	$res = iconv('GB2312','UTF-8', $res);
	$res = json_decode( $res,true );
	var_dump( $res );*/
	

	if(checksubmit('submit')) {
		$_GPC = Util::trimWithArray($_GPC);

		load()->func('file');
        $r = mkdirs(ST_ROOT . '/cert/'.$_W['uniacid']);
		if(!empty($_GPC['rsa_public_key'])) {
            $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/rsa_public_key.pem', trim($_GPC['rsa_public_key']));
            $r = $r && $ret;
        }
		if(!empty($_GPC['rsa_private_key'])) {
            $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/rsa_private_key.pem', trim($_GPC['rsa_private_key']));
            $r = $r && $ret;
        }
		if(!empty($_GPC['cert'])) {
            $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
            $r = $r && $ret;
        }
        if(!empty($_GPC['key'])) {
            $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
            $r = $r && $ret;
        }
        if(!empty($_GPC['rootca'])) {
            $ret = file_put_contents(ST_ROOT.'/cert/'.$_W['uniacid'].'/rootca.pem', trim($_GPC['rootca']));
            $r = $r && $ret;
        }			
		if(!$r) {
            message('证书保存失败, 请保证 /addons/zxbddiy_sitetemp/cert/ 目录可写，如果无法解决请使用上传工具将证书文件上传至'.ST_ROOT . '/cert/'.$_W['uniacid'].'目录下');
        }


		$dat = array(
			'frompass' => $_GPC['frompass'],
			'prostyle' => intval( $_GPC['prostyle'] ),
			'mail' => $_GPC['mail'],
			'isartfoot' => intval( $_GPC['isartfoot'] ),
			'shopname' => $_GPC['shopname'],
			'shoptel' => $_GPC['shoptel'],
			'shopaddress' => $_GPC['shopaddress'],
			'kefutype' => intval( $_GPC['kefutype'] ),
			'kefutel' => $_GPC['kefutel'],
			'kefuurl' => $_GPC['kefuurl'],
			'kefuimg' => $_GPC['kefuimg'],
			'shoplat' => $_GPC['shoplat'],
			'shoplng' => $_GPC['shoplng'],
			'leastmoney' => sprintf( '%.2f',$_GPC['leastmoney'] ),
			'sendmoney' => sprintf( '%.2f',$_GPC['sendmoney'] ), 
			'freesend' => sprintf( '%.2f',$_GPC['freesend'] ),
			'sendtime' => intval( $_GPC['sendtime'] ),
			'shoppic' => iserializer( $_GPC['shoppic'] ),
			'shophead' => $_GPC['shophead'],
			'paysms' => intval( $_GPC['paysms'] ),
			'paysuctel' => $_GPC['paysuctel'],
			'sendedpay' => intval( $_GPC['sendedpay'] ),
			'afterpay' => intval( $_GPC['afterpay'] ),
			'iscutsort' => intval( $_GPC['iscutsort'] ),
			'apkefutype' => intval( $_GPC['apkefutype'] ),
			'apkefutel' => $_GPC['apkefutel'],
			'apkefuurl' => $_GPC['apkefuurl'],
			'apkefuimg' => $_GPC['apkefuimg'],
			'isautotake' => intval( $_GPC['isautotake'] ),
			'cardname' => $_GPC['cardname'],
			'cardimg' => $_GPC['cardimg'],
			'cardtel' => $_GPC['cardtel'],
			'cardaddress' => $_GPC['cardaddress'],
			'cardlat' => $_GPC['cardlat'],
			'cardlng' => $_GPC['cardlng'],
			'cardbg' => $_GPC['cardbg'],
			'sorttype' => intval( $_GPC['sorttype'] ),
			'listprice' => intval( $_GPC['listprice'] ),
			'listtitle' => intval( $_GPC['listtitle'] ),
			'titlepos' => $_GPC['titlepos'],
			'infotitle' => $_GPC['infotitle'],
			'shopsh' => intval( $_GPC['shopsh'] ),
			'shopsm' => intval( $_GPC['shopsm'] ),
			'shopeh' => intval( $_GPC['shopeh'] ),
			'shopem' => intval( $_GPC['shopem'] ),

			'xuansh' => intval( $_GPC['xuansh'] ),
			'xuansm' => intval( $_GPC['xuansm'] ),
			'xuaneh' => intval( $_GPC['xuaneh'] ),
			'xuanem' => intval( $_GPC['xuanem'] ),
			'artstyle' => intval( $_GPC['artstyle'] ),

			'infoprice' => intval( $_GPC['infoprice'] ),
			'infobuybtn' => intval( $_GPC['infobuybtn'] ),

			'oshophead' => $_GPC['oshophead'],
			'oshoppic' => $_GPC['oshoppic'],
			'oshopname' => $_GPC['oshopname'],
			'oshoptel' => $_GPC['oshoptel'],
			'oshopaddress' => $_GPC['oshopaddress'],
			'okefutype' => intval( $_GPC['okefutype'] ),
			'okefutel' => $_GPC['okefutel'],
			'okefuurl' => $_GPC['okefuurl'],
			'okefuimg' => $_GPC['okefuimg'],
			'oshoplat' => $_GPC['oshoplat'],
			'oshoplng' => $_GPC['oshoplng'],
			'opaysms' => intval( $_GPC['opaysms'] ),
			'opaysuctel' => $_GPC['opaysuctel'],

			'oleastmoney' => $_GPC['oleastmoney'],
			'osendmoney' => $_GPC['osendmoney'],
			'ofreesend' => $_GPC['ofreesend'],
			'osendtime' => $_GPC['osendtime'],
			'osendedpay' => $_GPC['osendedpay'],

			'oshopsh' => intval( $_GPC['oshopsh'] ),
			'oshopsm' => intval( $_GPC['oshopsm'] ),
			'oshopeh' => intval( $_GPC['oshopeh'] ),
			'oshopem' => intval( $_GPC['oshopem'] ),

			'lawyer' => intval( $_GPC['lawyer'] ),
			
			//百度支付的参数
			'appkey'=>$_GPC['appkey'],
			'dealid'=>$_GPC['dealid'],
			'bd_appkey'=>$_GPC['bd_appkey'],
			'public_key'=>$_GPC['public_key'],
			'rsa_private_key'=>$_GPC['rsa_private_key'],
			'rsa_public_key'=>$_GPC['rsa_public_key']
        );

		$obj = WeUtility::createModule($_GPC['m']);

		if ($obj->saveSettings($dat)) {
            message('保存成功', 'refresh');
        }
	}

	$auth = model_auth::authList();

	include $this->pTemplate('set');