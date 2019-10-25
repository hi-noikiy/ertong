<?php 

class model_orderform {



	static function getForm( $plug = 0 ){
		global $_W;

		$cache = Util::getCache('orderform','all'.$plug);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'plug'=>$plug);
			$data = Util::getAllDataInSingleTable('zxbddiy_sitetemp_orderform',$where,1,1010,' `number` DESC ',false,false);
			$cache =  $data[0];

			if( !empty( $cache ) ) {
				foreach ( $cache as &$v ) {
					$v['sitem'] = iunserializer( $v['sitem'] );
				}
			}

			Util::setCache('orderform','all'.$plug,$cache);
		}
		return $cache;
	}
	

}