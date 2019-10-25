<?php 
	global $_W,$_GPC;
	$_GPC['op'] = isset($_GPC['op'])?$_GPC['op']:'list';
	
	
	
	//批量删除
	if(checksubmit('deleteall')){
		$res = WebCommon::deleteDataInWeb($_GPC['checkall'],'zxbddiy_sitetemp_desk');
		itoast('操作完成,成功删除'.$res[0].'项，失败'.$res[1].'项','','success');
	}


	if($_GPC['op'] == 'list'){	

		$where = array('uniacid'=>$_W['uniacid'],'plug'=>0);
		$order = ' `number` DESC ';
		
		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_desk',$where,$_GPC['page'],1111,$order);
		$list = $info[0];
		$pager = $info[1];
		
		if( !empty( $list ) ) {
			foreach ( $list as &$v ) {
				$v['urlimg'] = $this->createWebUrl('img',array('did'=>$v['id'],'op'=>'desk'));
			}
		}
		
	}
	

	if($_GPC['op'] == 'edit'){
		$id = intval($_GPC['id']);
		$info = pdo_get('zxbddiy_sitetemp_desk',array('uniacid'=>$_W['uniacid'],'id'=>$id));
	}
	
	if($_GPC['op'] == 'delete'){
		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_desk');
		if($res) itoast('删除成功','','success');
	}
	
	if( $_GPC['op'] == 'downqr' ){

		$desk = pdo_get('zxbddiy_sitetemp_desk',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		if( !empty( $desk ) ) {
			load()->model('account');
			$uniacccount = WeAccount::create($_W['acid']);	

			$res = Util::wxappQrcode( $uniacccount,$_GPC['id'],'zxbddiy_sitetemp/pages/deskorder/exbuy' );

			header("content-type: image/jpeg");
			header("Content-Disposition:attachment; filename=".$desk['name'].".jpg");	
			echo $res;
		  	die;
		}
		
	}

	include $this->pTemplate('desk');