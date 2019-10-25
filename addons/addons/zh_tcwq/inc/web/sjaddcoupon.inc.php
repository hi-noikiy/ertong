<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$list = pdo_get('zhtc_coupons',array('id'=>$_GPC['id']));
$type = pdo_getall('zhtc_coupontype',array('uniacid'=>$_W['uniacid']));
$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'is_yhqsh');
if($list['img']){
	if(strpos($list['img'],',')){
		$img= explode(',',$list['img']);
	}else{
		$img=array(
			0=>$list['img']
			);
	}
}
if(checksubmit('submit')){
	$data['name']=$_GPC['name'];
	$data['number']=$_GPC['number'];
	$data['surplus']=$_GPC['number'];
	$data['full']=$_GPC['full'];
	$data['reduce']=$_GPC['reduce'];
	$data['money']=$_GPC['money'];
	$data['end_time']=$_GPC['end_time'];
	$data['details']=$_GPC['details'];
	$data['store_id']=$storeid;
	$data['img']=implode(",",$_GPC['img']);
	$data['type_id']=$_GPC['type_id'];
	$data['time']=date("Y-m-d H:i:s");
	if($_GPC['id']==''){
		if($system['is_yhqsh']==1){
			$data['state']=1;
		}else{
			$data['state']=2;
		}
		$res=pdo_insert('zhtc_coupons',$data);
	}else{
		$res = pdo_update('zhtc_coupons', $data, array('id' => $_GPC['id']));		
	}
	if($res){
		message('编辑成功',$this->createWebUrl2('sjcoupon',array()),'success');
	}else{
		message('编辑失败','','error');
	}
}
include $this->template('web/sjaddcoupon');