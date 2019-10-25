<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$info = pdo_get('zhtc_qggoods',array('uniacid' => $_W['uniacid'],'id'=>$_GPC['id']),array(),'',array('id DESC'));
$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'xgsh');
$store=pdo_get('zhtc_store',array('id'=>$storeid,'uniacid'=>$_W['uniacid']),'cityname');
if($info['img']){
	if(strpos($info['img'],',')){
		$img= explode(',',$info['img']);
	} else{
		$img=array(
			0=>$info['img']
			);
	}
}

if($info['details_img']){
	if(strpos($info['details_img'],',')){
		$details_img= explode(',',$info['details_img']);
	} else{
		$details_img=array(
			0=>$info['details_img']
			);
	}
}
$type = pdo_getall('zhtc_qgtype',array('uniacid' => $_W['uniacid']));
if(checksubmit('submit')){
	$data['name']=$_GPC['name'];
	if($info['logo']!=$_GPC['logo']){
		$data['logo']=$_GPC['logo'];
	} else{
		$data['logo']=$_GPC['logo'];
	}
	if($_GPC['img']){
		$data['img']=implode(",",$_GPC['img']);
	} else{
		$data['img']='';
	}
	if($_GPC['details_img']){
		$data['details_img']=implode(",",$_GPC['details_img']);
	} else{
		$data['details_img']='';
	}
	$data['money']=$_GPC['money'];
	$data['price']=$_GPC['price'];
	$data['type_id']=$_GPC['type_id'];
	$data['num']=$_GPC['num'];
	$data['number']=$_GPC['number'];
	$data['start_time']=$_GPC['start_time'];
	$data['end_time']=$_GPC['end_time'];
	$data['content']=$_GPC['content'];
	$data['consumption_time']=$_GPC['consumption_time'];
	$data['details']=html_entity_decode($_GPC['details']);
	$data['uniacid']=$_W['uniacid'];
	$data['store_id']=$storeid;
	$data['cityname']=$store['cityname'];
	if($_GPC['id']==''){
		$data['surplus']=$_GPC['number'];
		if($sys['xgsh']==1){
			$data['is_tg']=1;
		}
		if($sys['xgsh']==2){
			$data['is_tg']=2;
		}
		$res=pdo_insert('zhtc_qggoods',$data);
		if($res){
			message('添加成功',$this->createWebUrl2('sjqggoodall',array()),'success');
		} else{
			message('添加失败','','error');
		}
	} else{
		$res = pdo_update('zhtc_qggoods', $data, array('id' => $_GPC['id']));
		if($res){
			message('编辑成功',$this->createWebUrl2('sjqggoodall',array()),'success');
		} else{
			message('编辑失败','','error');
		}
	}
}
include $this->template('web/sjaddqggoodall');