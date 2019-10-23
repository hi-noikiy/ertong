<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;

	if( $_GPC['op'] == 'list' ) {

		$where = array('uniacid' => $_W['uniacid']);
		if( !empty( $_GPC['actsort'] ) ) $where['sortid'] = intval( $_GPC['actsort'] );

		if( !empty( $_GPC['for'] ) ) {
			$where['title@'] = $_GPC['for'];
		}

		$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_product',$where,$_GPC['page'],10,' `number` DESC,`id` DESC ',false,false,' id,img,title ');
		$list = $info[0];
		
		if( !empty( $list ) ) {
			foreach ($list as &$v) {
				$v['url'] = '/zxbddiy_sitetemp/pages/product/product?aid='.$v['id'];
				$v['img'] = tomedia( $v['img'] );
			}
		}
		
		$data['list'] = $list;

		// 分类
		$data['artsort'] = model_prosort::getSort();
		
		$data['set'] = array(
			'prostyle' => intval( $this->module['config']['prostyle'] ),
		);
		
		$this->result(0, '',$data);
	
	}elseif( $_GPC['op'] == 'product' ) {
		
		$info = pdo_get('zxbddiy_sitetemp_product',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		if( !empty( $info ) ) {
			$info['content'] = htmlspecialchars_decode( $info['content'] );
			$info['img'] = tomedia( $info['img'] );
		}else{
			$this->result(1, '产品不存在');
		}
		$this->result(0, '',$info);
		
	}

	