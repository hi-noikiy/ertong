<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info = pdo_get('zhtc_qggoods',array('uniacid' => $_W['uniacid'],'id'=>$_GPC['id']),array(),'',array('id DESC'));
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
	$data['cityname']=$_GPC['cityname'];
	$data['content']=$_GPC['content'];
	$data['consumption_time']=$_GPC['consumption_time'];
	$data['details']=html_entity_decode($_GPC['details']);
	$data['uniacid']=$_W['uniacid'];
	if($_GPC['id']==''){
		$data['surplus']=$_GPC['number'];
		$res=pdo_insert('zhtc_qggoods',$data);
		if($res){
			message('添加成功',$this->createWebUrl('qggoodall',array()),'success');
		} else{
			message('添加失败','','error');
		}
	} else{
		$res = pdo_update('zhtc_qggoods', $data, array('id' => $_GPC['id']));
		if($res){
			message('编辑成功',$this->createWebUrl('qggoodall',array()),'success');
		} else{
			message('编辑失败','','error');
		}
	}
}
include $this->template('web/addqggoodall');