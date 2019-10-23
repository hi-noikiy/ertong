<?php 

class model_appsort {



	static function getSort(){
		global $_W;

		$cache = Util::getCache('appsort','all');
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid']);
			$data = Util::getAllDataInSingleTable('zxbddiy_sitetemp_appsort',$where,1,1010,' `number` DESC ',false,false);
			
			$cache =  $data[0];
			Util::setCache('appsort','all',$cache);
		}
		return $cache;
	}
	

}