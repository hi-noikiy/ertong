<?php

defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();

$item=pdo_get('zhtc_sensitive',array('uniacid'=>$_W['uniacid']));
$arr=explode(',', $item['content']);
if(checksubmit('submit')){
	$data['content']=trim($_GPC['content']);  
	$data['uniacid']=trim($_W['uniacid']);
	if($_GPC['id']==''){                
		$res=pdo_insert('zhtc_sensitive',$data);
		if($res){
			message('添加成功',$this->createWebUrl('sensitive',array()),'success');
		}else{
			message('添加失败','','error');
		}
	}else{
		$res = pdo_update('zhtc_sensitive', $data, array('id' => $_GPC['id']));
		if($res){
			message('编辑成功',$this->createWebUrl('sensitive',array()),'success');
		}else{
			message('编辑失败','','error');
		}
	}
}
include $this->template('web/sensitive');