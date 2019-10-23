<?php 

class model_tempsort {



	static function getSort(){
		global $_W;

		$cache = Util::getCache('tempsort','all',false);
		if( empty( $cache ) ){
			$where = array('uniacid>'=>0);
			$data = Util::getAllDataInSingleTable('zxbddiy_sitetemp_tempsort',$where,1,1010,' `number` DESC ',false,false,' * ','','',false);
			
			$cache =  $data[0];
			Util::setCache('tempsort','all',$cache,false);
		}
		return $cache;
	}
	

}