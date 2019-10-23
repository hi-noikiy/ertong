<?php 


class model_card {
	
	
	static function getCard( $cid ){
		global $_W;
		
		$cache = Util::getCache('card',$cid);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'id'=>$cid);
			$cache = pdo_get('zxbddiy_sitetemp_card',$where);
			
			if( !empty( $cache ) ){
				Util::setCache('card',$cid,$cache);
			}
		}
		return $cache;
	}
	
	
}
