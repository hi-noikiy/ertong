<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;
	if( $_GPC['op'] == 'gsort' ) {
		$plug = intval( $_GPC['plug'] );
		if( empty($plug) ) {
			$allsort = model_goodsort::getSort( 0 );
			$sort = array();
			if( !empty( $allsort ) ) {
				foreach ($allsort as $k => &$v) {

					// 分隔各个模板的分类
					if( $this->module['config']['iscutsort'] == 1 && $_GPC['tid'] != $v['tempid'] ){
						continue;
					}

					if( !empty( $v['down'] ) ) {
						foreach ($v['down'] as $kk => $vv) {
							$v['down'][$kk]['img'] = tomedia( $vv['img'] );
							$v['down'][$kk]['url'] = '/zxbddiy_sitetemp/pages/goodlist/goodlist?sid='.$vv['id'];
						}
						$sort[] = $v;
					}
				}
				unset( $v );
			}
			$data['allsort'] = $sort;
			
			$data['set'] = array(
				'sorttype' => intval( $this->module['config']['sorttype'] ),
			);

		}elseif( $plug == 1 ){

			$allsort = model_goodsort::getSort(1);
			$sort = array();
			if( !empty( $allsort ) ) {
				foreach ($allsort as $k => &$v) {

					if( !empty( $v['down'] ) ) {
						foreach ($v['down'] as $kk => $vv) {
							$v['down'][$kk]['img'] = tomedia( $vv['img'] );
							$v['down'][$kk]['url'] = '/zxbddiy_sitetemp/pages/deskorder/goodlist?sid='.$vv['id'];
						}
						$sort[] = $v;
					}
				}
				unset( $v );
			}
			

			$data['allsort'] = $sort;
			
			$data['set'] = array(
				'sorttype' => intval( $this->module['config']['osorttype'] ),
			);
		}
		
		
		$this->result(0, '',$data);
	
	}elseif( $_GPC['op'] == 'article' ) {
		
		$info = pdo_get('zxbddiy_sitetemp_article',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		if( !empty( $info ) ) {
			$info['content'] = htmlspecialchars_decode( $info['content'] );
			$info['img'] = tomedia( $info['img'] );
		}

		Util::addOrMinusOrUpdateData('zxbddiy_sitetemp_article',array('readed'=>1),$info['id']);

		$this->result(0, '',$info);
		
	}

	