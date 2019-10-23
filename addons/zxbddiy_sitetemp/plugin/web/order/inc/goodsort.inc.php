<?php 
	global $_W,$_GPC;
	$op = isset($_GPC['op'])?$_GPC['op']:'list';
	
	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_goodsort');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}
	
	$allsort = model_goodsort::getSort(1);
	$oneClass = model_goodsort::getOneClass(1);

	$temp = model_goodsort::getTemp();

	$alltemp = pdo_getall('zxbddiy_sitetemp_temp',array('uniacid'=>$_W['uniacid'],'issystem'=>0));

	if($op == 'list' || $op == 'class1' || $op == 'class2'){	

		$topbar = topbal::goodsortList();

		$where = array('uniacid'=>$_W['uniacid'],'plug'=>1);
		if( $this->module['config']['iscutsort'] == 1 ) $where['tempid'] = 0;

		if( $_GPC['type'] == 1 || $op == 'class1' || $op == 'list' ) $where['parent'] = 0;
		if( $_GPC['type'] == 2 || $op == 'class2') $where['parent>'] = 0.1;

		if( $_GPC['parent'] > 0 ) {
			$where['parent'] = $_GPC['parent'];
			unset( $where['tempid'] );
		}
		if( $_GPC['tempid'] > 0 ) $where['tempid'] = $_GPC['tempid'];

		$order = ' `status` ASC,`number` DESC ';

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_goodsort',$where,$_GPC['page'],10,$order);
		$list = $info[0];
		$pager = $info[1];
	}
	
	if($op == 'edit'){
		$id = intval($_GPC['id']);
		$info = pdo_get('zxbddiy_sitetemp_goodsort',array('uniacid'=>$_W['uniacid'],'id'=>$id));

	}
	
	if($op == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_goodsort');
		if($res) itoast('删除成功','','success');
	}
	

	include $this->pTemplate('goodsort');