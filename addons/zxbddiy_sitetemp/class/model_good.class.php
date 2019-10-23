<?php 


class model_good {
	
	
	static function getGood( $gid ){
		global $_W;
		
		$cache = Util::getCache('good',$gid);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'id'=>$gid);
			$cache = pdo_get('zxbddiy_sitetemp_good',$where);
			
			if( !empty( $cache ) ){
				$cache['pic'] = iunserializer( $cache['pic'] );
				$cache['rulearray'] = iunserializer( $cache['rulearray'] );
				$cache['icon'] = iunserializer( $cache['icon'] );
				Util::setCache('good',$gid,$cache);
			}
		}
		return $cache;
	}
		


	
}
