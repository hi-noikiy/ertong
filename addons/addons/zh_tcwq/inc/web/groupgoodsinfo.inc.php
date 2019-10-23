<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info=pdo_get('zhtc_groupgoods',array('id'=>$_GPC['id']));
if($info['img']){
	if(strpos($info['img'],',')){
		$img= explode(',',$info['img']);
	}else{
		$img=array(
			0=>$info['img']
			);
	}
}
if($info['details_img']){
	if(strpos($info['img'],',')){
		$details_img= explode(',',$info['details_img']);
	} else{
		$details_img=array(
			0=>$info['details_img']
		);
	}
}
$type=pdo_getall('zhtc_grouptype',array('uniacid'=>$_W['uniacid']),array(),'','num asc');

if(!$type){
	message('请先添加分类',$this->createWebUrl('adddishestype',array()),'error');
}
if(checksubmit('submit')){
	if($_GPC['img']){
		$data['img']=implode(",",$_GPC['img']);
	}else{
		$data['img']='';
	}
	if($_GPC['details_img']){
		$data['details_img']=implode(",",$_GPC['details_img']);
	} else{
		$data['details_img']='';
	}
	$data['name']=$_GPC['name'];
	$data['type_id']=$_GPC['type_id'];
	$data['logo']=$_GPC['logo'];
	$data['inventory']=$_GPC['inventory'];
	$data['start_time']=strtotime($_GPC['start_time']);
	$data['end_time']=strtotime($_GPC['end_time']);
	$data['xf_time']=strtotime($_GPC['xf_time']);		
	$data['pt_price']=$_GPC['pt_price'];
	$data['y_price']=$_GPC['y_price'];
	$data['dd_price']=$_GPC['dd_price'];
	$data['ycd_num']=$_GPC['ycd_num'];
	$data['ysc_num']=$_GPC['ysc_num'];
	$data['people']=$_GPC['people'];
	$data['is_shelves']=$_GPC['is_shelves'];
	$data['details']=html_entity_decode($_GPC['details']);
	$data['num']=$_GPC['num'];
	$data['introduction']=$_GPC['introduction'];
	$data['cityname']=$_GPC['cityname'];
	$data['uniacid']=$_W['uniacid'];
	if($_GPC['id']==''){
		$res=pdo_insert('zhtc_groupgoods',$data);
		if($res){
			message('添加成功',$this->createWebUrl('groupgoods',array()),'success');
		}else{
			message('添加失败','','error');
		}
	}else{
		$res = pdo_update('zhtc_groupgoods', $data, array('id' => $_GPC['id']));
		if($res){
			message('编辑成功',$this->createWebUrl('groupgoods',array()),'success');
		}else{
			message('编辑失败','','error');
		}
	}
}

include $this->template('web/groupgoodsInfo');