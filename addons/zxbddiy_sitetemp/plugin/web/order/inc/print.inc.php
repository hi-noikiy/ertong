<?php 
	global $_W,$_GPC;
	$op = isset($_GPC['op'])?$_GPC['op']:'list';
	
	
	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_print');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	

	if($op == 'list'){	

		$topbar = topbal::goodsortList();

		$where = array('uniacid'=>$_W['uniacid'],'plug'=>2);

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_print',$where,$_GPC['page'],10,' `id` DESC ');
		$list = $info[0];
		$pager = $info[1];
	}
	

	
	if($op == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_print');
		if($res) itoast('删除成功','','success');
	}
	

	include $this->pTemplate('print');