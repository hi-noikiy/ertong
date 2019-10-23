<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( $_GPC['op'] == 'getlist' ) {

		
		$where = array('uniacid' => $_W['uniacid'],'type'=>intval( $_GPC['plug'] ),'status'=>0);
		if( !empty( $_GPC['sortid'] ) ){

			if( $_GPC['sorttype'] == 1 ) $where['sortone'] = intval( $_GPC['sortid'] );
			if( $_GPC['sorttype'] == 2 ) $where['sorttwo'] = intval( $_GPC['sortid'] );
		} 

		if( isset( $_GPC['for'] ) ) {
			$where['title@'] = $_GPC['for'];
		}

		$order = ' `number` DESC ';
		if( $_GPC['otype'] == 1 ) $order = ' `sales` DESC ';
		if( $_GPC['otype'] == 2 ) $order = ' `createtime` DESC ';
		if( $_GPC['otype'] == 3 ) {

			if( $_GPC['priceo'] == 'true' ) $order = ' `price` DESC ';
			if( $_GPC['priceo'] == 'false' ) $order = ' `price` ASC ';
		}
		
		
		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_good',$where,$_GPC['page'],10,$order,false,false,' id,thumb,title,price,oldprice,sales ');
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/good/good?gid='.$v['id'];
				$v['thumb'] = tomedia( $v['thumb'] );
			}
			unset( $v );
		}		
		$data['list'] = $list;
		
		$data['set'] = array(
			'listprice' => intval( $this->module['config']['listprice'] ),
			'listtitle' => intval( $this->module['config']['listtitle'] ),
			'titlepos' => $this->module['config']['titlepos'],
		);
		
		if( empty( $_GPC['sortid'] ) && empty( $_GPC['isinit'] ) ) {
			$allsort = model_goodsort::getSort(0);
			$sort = array();
			foreach ( $allsort as $v ) {
				if( !empty( $v['down'] ) ){
					foreach ($v['down'] as $vv) {
						$sort[] = $vv;
					}
					
				}
			}
			$data['twosort'] = $sort;
		}

		$this->result(0, '',$data);
		
	}

	