<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'is_goods');
$info=pdo_get('zhtc_goods',array('id'=>$_GPC['id']));
if($info['imgs']){
			if(strpos($info['imgs'],',')){
			$imgs= explode(',',$info['imgs']);
		}else{
			$imgs=array(
				0=>$info['imgs']
				);
		}
		}
if($info['lb_imgs']){
			if(strpos($info['lb_imgs'],',')){
			$lb_imgs= explode(',',$info['lb_imgs']);
		}else{
			$lb_imgs=array(
				0=>$info['lb_imgs']
				);
		}
		}		
if(checksubmit('submit')){
		if($_GPC['imgs']){
			$data['imgs']=implode(",",$_GPC['imgs']);
		}else{
			$data['imgs']='';
		}
		if($_GPC['lb_imgs']){
			$data['lb_imgs']=implode(",",$_GPC['lb_imgs']);
		}else{
			$data['lb_imgs']='';
		}
			$data['goods_name']=$_GPC['goods_name'];
			$data['goods_num']=$_GPC['goods_num'];
			$data['goods_cost']=$_GPC['goods_cost'];
			$data['freight']=$_GPC['freight'];
			$data['delivery']=$_GPC['delivery'];
			$data['quality']=$_GPC['quality'];
			$data['free']=$_GPC['free'];
			$data['all_day']=$_GPC['all_day'];
			$data['service']=$_GPC['service'];
			$data['refund']=$_GPC['refund'];
			$data['weeks']=$_GPC['weeks'];
			$data['store_id']=$storeid;
			$data['goods_details']=$_GPC['goods_details'];
			$data['time']=time();
			$data['uniacid']=$_W['uniacid'];
			if($_GPC['id']==''){
				if($sys['is_goods']==1){
					$data['state']=1;
				}
				if($sys['is_goods']==2){
					$data['state']=2;
				}
				$res = pdo_insert('zhtc_goods', $data);
			}else{
				$res = pdo_update('zhtc_goods', $data, array('id' => $_GPC['id']));
			}			
				if($res){
					message('编辑成功',$this->createWebUrl2('sjgoods',array()),'success');
				}else{
					message('编辑失败','','error');
				}
		}
include $this->template('web/sjaddgoods');