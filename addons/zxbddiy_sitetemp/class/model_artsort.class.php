<?php 

class model_artsort {



	static function getSort(){
		global $_W;

		$cache = Util::getCache('artsort','all');
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid']);
			$data = Util::getAllDataInSingleTable('zxbddiy_sitetemp_artsort',$where,1,1010,' `number` DESC ',false,false);
			
			$cache =  $data[0];
			Util::setCache('artsort','all',$cache);
		}
		return $cache;
	}
	

}