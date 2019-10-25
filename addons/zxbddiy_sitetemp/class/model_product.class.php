<?php 


class model_product {
	
	
	static function getProduct( $aid ){
		global $_W;
		
		$cache = Util::getCache('product',$aid);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'id'=>$aid);
			$cache = pdo_get('zxbddiy_sitetemp_product',$where);
			
			if( !empty( $cache ) ){
				Util::setCache('product',$aid,$cache);
			}
		}
		return $cache;
	}
	
	
}
