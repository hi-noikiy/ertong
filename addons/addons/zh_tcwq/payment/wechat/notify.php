<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../../../framework/bootstrap.inc.php';
global $_W, $_GPC;
$input = file_get_contents('php://input');
$isxml = true;
if (!empty($input) && empty($_GET['out_trade_no'])) {
	$obj = isimplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	$res = $data = json_decode(json_encode($obj), true);
	if (empty($data)) {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => ''
		);
		echo array2xml($result);
		exit;
	}
	if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => empty($data['return_msg']) ? $data['err_code_des'] : $data['return_msg']
		);
		echo array2xml($result);
		exit;
	}
	$get = $data;
} else {
	$isxml = false;
	$get = $_GET;
}
load()->web('common');
load()->model('mc');
load()->func('communication');
$_W['uniacid'] = $_W['weid'] = intval($get['attach']);

$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
$_W['acid'] = $_W['uniaccount']['acid'];
$paySetting = uni_setting($_W['uniacid'], array('payment'));
if($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS' ){
	$logno = trim($res['out_trade_no']);
	if (empty($logno)) {
		exit;
	}
$str=$_W['siteroot'];
	$n = 0;
for($i = 1;$i <= 3;$i++) {
    $n = strpos($str, '/', $n);
    $i != 3 && $n++;
}
$url=substr($str,0,$n);

	$order=pdo_get('zhtc_distribution',array('code'=>$logno));

	$hdorder=pdo_get('zhtc_joinlist',array('code'=>$logno));
	$dmorder=pdo_get('zhtc_dmorder',array('code'=>$logno));
	$usercoupons=pdo_get('zhtc_usercoupons',array('code'=>$logno));
	$fxlog=pdo_get('zhtc_fxlog',array('code'=>$logno));
	$qgorder=pdo_get('zhtc_qgorder',array('code'=>$logno));
	$grouporder=pdo_get('zhtc_grouporder',array('code'=>$logno));
	if($grouporder['state']==1){
	pdo_update('zhtc_grouporder',array('state'=>2,'pay_time'=>time()),array('id'=>$grouporder['id']));
	//改变商品
	pdo_update('zhtc_groupgoods',array('ysc_num +='=>$grouporder['goods_num'],'inventory -='=>$grouporder['goods_num']),array('id'=>$grouporder['goods_id']));
	if($grouporder['group_id']>0){
	$count=pdo_get('zhtc_grouporder', array('group_id'=>$grouporder['group_id'],'state '=>2), array('count(user_id) as count'));
	$group=pdo_get('zhtc_group',array('id'=>$grouporder['group_id']));
	if($group['kt_num']==$count['count']){
		$state=2;
	}else{
		$state=1;
	}
	//改变团状态
	pdo_update('zhtc_group',array('state'=>$state,'yg_num +='=>1),array('id'=>$grouporder['group_id']));
	if($state==2 or $grouporder['group_id']==0){
		file_get_contents("".$url."/app/index.php?i=".$grouporder['uniacid']."&c=entry&a=wxapp&do=InsertStoreWallet&m=zh_tcwq&group_id=".$grouporder['group_id']."&order_id=".$grouporder['id']);//分销
		  file_get_contents("".$url."/app/index.php?i=".$grouporder['uniacid']."&c=entry&a=wxapp&do=PtMessage&m=zh_tcwq&group_id=".$grouporder['group_id']);//模板消息
	}
	}			
	}
if($qgorder['state']==1){
		$time=time();
		$good=pdo_get('zhtc_qggoods',array('id'=>$qgorder['good_id']));
		$dq_time=$time+$good['consumption_time']*60*60*24;
		pdo_update('zhtc_qgorder',array('state'=>2,'dq_time'=>$dq_time,'pay_time'=>date('Y-m-d H:i:s',$time)),array('id'=>$qgorder['id']));
		pdo_update('zhtc_store',array('wallet +='=>$qgorder['money']),array('id'=>$qgorder['store_id']));
         $data3['store_id']=$qgorder['store_id'];
         $data3['money']=$qgorder['money'];
         $data3['note']='抢购订单';
         $data3['type']=1;
         $data3['time']=date("Y-m-d H:i:s");
         pdo_insert('zhtc_store_wallet',$data3);

         file_get_contents("".$url."/app/index.php?i=".$qgorder['uniacid']."&c=entry&a=wxapp&do=QgMessage&m=zh_tcwq&order_id=".$qgorder['id']);//模板消息

	}
	if($fxlog['state']==1){
		pdo_update('zhtc_fxlog',array('state'=>2),array('code'=>$logno));
		pdo_update('zhtc_distribution',array('level'=>$fxlog['level']),array('user_id'=>$fxlog['user_id'],'pay_state'=>2,'state'=>2));
		file_get_contents("".$url."/app/index.php?i=".$fxlog['uniacid']."&c=entry&a=wxapp&do=Fx&m=zh_tcwq&user_id=".$fxlog['user_id']."&money=".$fxlog['money']);//分销

	}
	if($usercoupons['pay_type']==1){
		pdo_update('zhtc_usercoupons',array('pay_type'=>2),array('code'=>$logno));
		pdo_update('zhtc_coupons',array('surplus -='=>1),array('id'=>$usercoupons['coupons_id']));
		$coupon=pdo_get('zhtc_coupons',array('id'=>$usercoupons['coupons_id']));
		$store=pdo_get('zhtc_store',array('id'=>$coupon['store_id']));
		pdo_update('zhtc_store',array('wallet +='=>$coupon['lq_money']),array('id'=>$coupon['store_id']));
         $data4['store_id']=$coupon['store_id'];
         $data4['money']=$coupon['money'];
         $data4['note']='优惠券';
         $data4['type']=1;
         $data4['time']=date("Y-m-d H:i:s");
         pdo_insert('zhtc_store_wallet',$data4);
         file_get_contents("".$url."/app/index.php?i=".$store['uniacid']."&c=entry&a=wxapp&do=HdMessage&m=zh_tcwq&id=".$usercoupons['id']);//分销
	}
	if($dmorder['state']==1){
		pdo_update('zhtc_dmorder',array('state'=>2),array('code'=>$logno));
		pdo_update('zhtc_store',array('wallet +='=>$dmorder['money']),array('id'=>$dmorder['store_id']));
         $data2['store_id']=$dmorder['store_id'];
         $data2['money']=$dmorder['money'];
         $data2['note']='收银订单';
         $data2['type']=1;
         $data2['time']=date("Y-m-d H:i:s");
         pdo_insert('zhtc_store_wallet',$data2);
	}
	if($order['pay_state']==1){
		$res=pdo_update('zhtc_distribution',array('pay_state'=>2),array('code'=>$logno));
			if($res){
				$yjset=pdo_get('zhtc_yjset',array('uniacid'=>$uniacid));
			if($yjset['type']==1){
	          $money=$order['money']*$yjset['typer']/100;
		    }else{
		      $money=$order['money']*$yjset['sjper']/100;
		    }
		      $data2['user_id']=$order['user_id'];
			  $data2['money']=$order['money'];
			  $data2['time']=date("Y-m-d H:i:s");
			  $data2['level']=$order['level'];
			  $data2['state']=2;
			  $data2['note']='申请合伙人';
			  pdo_insert('zhtc_fxlog',$data2);
			pdo_update('zhtc_account',array('money +='=>$money),array('cityname'=>$order['cityname']));
			file_get_contents("".$url."/app/index.php?i=".$order['uniacid']."&c=entry&a=wxapp&do=Fx&m=zh_tcwq&user_id=".$order['user_id']."&money=".$order['money']);//分销
		}
		
	}
	if($hdorder['state']==1){
		 pdo_update('zhtc_activity',array('sign_num +='=>1),array('id'=>$hdorder['act_id'])); 
		$system=pdo_get('zhtc_system',array('uniacid'=>$hdorder['uniacid']));
		if($system['is_bm']==1){
			file_get_contents("".$url."/app/index.php?i=".$hdorder['uniacid']."&c=entry&a=wxapp&do=HdMessage&m=zh_tcwq&id=".$hdorder['id']);//模板消息
			pdo_update('zhtc_joinlist',array('state'=>3),array('code'=>$logno));
		}else{
			pdo_update('zhtc_joinlist',array('state'=>2),array('code'=>$logno));
		}
		file_get_contents("".$url."/app/index.php?i=".$hdorder['uniacid']."&c=entry&a=wxapp&do=Fx&m=zh_tcwq&user_id=".$hdorder['user_id']."&money=".$hdorder['money']);//分销
		file_get_contents("".$url."/app/index.php?i=".$hdorder['uniacid']."&c=entry&a=wxapp&do=ActYj&m=zh_tcwq&act_id=".$hdorder['act_id']."&money=".$hdorder['money']);//城市佣金

	}
	$result = array(
		'return_code' => 'SUCCESS',
		'return_msg' => 'OK'
	);
	echo array2xml($result);
	exit;
	
	}else{
		//订单已经处理过了
		$result = array(
			'return_code' => 'SUCCESS',
			'return_msg' => 'OK'
		);
		echo array2xml($result);
		exit;
	}
