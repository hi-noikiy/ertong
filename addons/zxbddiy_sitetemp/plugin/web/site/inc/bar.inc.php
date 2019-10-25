<?php 
	global $_W,$_GPC;
	
	$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['tid'],'uniacid'=>$_W['uniacid']));
	if( empty( $temp ) ) itoast('请选择模板','','success');


	$page = pdo_get('zxbddiy_sitetemp_bar',array('uniacid'=>$_W['uniacid'],'tempid'=>$_GPC['tid']));
	if( !empty( $page ) ) $page['data'] = iunserializer( $page['data'] );
	
	

	//批量删除
	/*if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_bar');
		message('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项',referer(),'success');
	}

	if($_GPC['op'] == 'list'){
		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_bar',array('uniacid'=>$_W['uniacid']),$_GPC['page'],10,' `id` DESC ',false,true,' id,name,isact ');
		$list = $info[0];
		$pager = $info[1];
		
	}
	
	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_bar');
		if($res) message('删除成功',referer(),'success');
	}

	if( $_GPC['op'] == 'edit' ) {
		$page = pdo_get('zxbddiy_sitetemp_bar',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));

		if( empty( $page ) ) message('未找到数据',referer(),'error');

		$page['data'] = iunserializer( $page['data'] );

	}
	
	if( $_GPC['op'] == 'toact' ) {
		
		pdo_update('zxbddiy_sitetemp_bar',array('isact'=>0),array('uniacid'=>$_W['uniacid']));
		pdo_update('zxbddiy_sitetemp_bar',array('isact'=>1),array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		message('已设置',referer(),'success');
	}*/

	include $this->pTemplate('bar');
	