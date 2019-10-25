<?php 


class model_sysset {
	
	
	static function getSet(){
		global $_W;
		$info = pdo_get('zxbddiy_sitetemp_set');
		return iunserializer( $info['params'] );
	}

}