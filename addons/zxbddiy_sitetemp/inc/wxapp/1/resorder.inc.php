<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( empty( $_W['openid'] ) ) $this->result(1, '会员不存在');
	$rsaSign=$_GPC['rsaSign'];
	$rsaSign||$this->result(1, '签名不存在!');
	$order = pdo_get('zxbddiy_sitetemp_order',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'status'=>0,'orderid'=>$_GPC['oid']));
	//预约订单处理
	if($_GPC['ordertype']==1){
	$order = pdo_get('zxbddiy_sitetemp_appointorder',array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid'],'status'=>0,'orderid'=>$_GPC['oid']));
	}
	if( empty( $order ) ) $this->result(1, '未找到订单');
	//生成签名对比
	$settings = Util::getModuleConfig();
	$rsaPrivateKey=file_get_contents($_W['siteroot'].'addons/zxbddiy_sitetemp/cert/'.$_W['uniacid'].'/rsa_private_key.pem');
	$totalAmount=intval(floatval($order['fee'])*100);
	$requestApiParamsArr = array("appKey"=> $settings['bd_appkey'],'dealId'=>$settings['dealid'],'tpOrderId'=>$order['orderid'],'totalAmount'=>$totalAmount);
	//签名
	$rsaSign1 = NuomiRsaSign::genSignWithRsa($requestApiParamsArr ,$rsaPrivateKey);
	$rsaSign1==$rsaSign||$this->result(1, '签名错误!');
	//修改充值记录
	$sql1=pdo_update('core_paylog',array('status'=>1),array('plid'=>$_GPC['paylogid']));
	//修改订单
	if($_GPC['ordertype']==1){
	$sql=pdo_update('zxbddiy_sitetemp_appointorder',array('status'=>1),array('id'=>$order['id']));
	}else{$sql=pdo_update('zxbddiy_sitetemp_order',array('status'=>1,'paytime'=>time()),array('id'=>$order['id']));};
	
	
	//获取token
	