<?php 


class model_auth {
	
	
	static function authList(){
		global $_W;
		
		$cache = Util::getCache('auth',$_W['uniacid'],false);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid']);
			$cache = pdo_get('zxbddiy_sitetemp_auth',$where);

			if( empty( $cache ) ){
				$sysset = model_sysset::getSet();
				if( $sysset['deauth'] == 1 ){
					return array(
						'isshop' => 1,
						'sms' => 1,
						'isclosecopy' => 1,
						'isdesk' => 1,
						'isappoint' => 1,
						'iscard' => 1,
					);
				}
			}

			Util::setCache('auth',$_W['uniacid'],$cache,false);
		}
		return $cache;
	}
	
	
}
