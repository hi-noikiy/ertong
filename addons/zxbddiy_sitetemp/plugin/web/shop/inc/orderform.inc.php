<?php 
	global $_W,$_GPC;
	$op = isset($_GPC['op'])?$_GPC['op']:'list';
	
	
	
	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_orderform');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}


	if($op == 'list'){	

		$where = array('uniacid'=>$_W['uniacid'],'plug'=>0);
		$order = ' `number` DESC ';

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_orderform',$where,$_GPC['page'],1111,$order);
		$list = $info[0];
		$pager = $info[1];
	}
	
	if($op == 'edit'){
		$id = intval($_GPC['id']);
		$info = pdo_get('zxbddiy_sitetemp_orderform',array('uniacid'=>$_W['uniacid'],'id'=>$id));

	}
	
	if($op == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_orderform');
		if($res) itoast('删除成功','','success');
	}
	

	include $this->pTemplate('orderform');