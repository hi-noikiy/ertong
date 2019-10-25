<?php 


class model_sysset {
	
	
	static function getSet(){
		global $_W;
		$info = pdo_get('zofui_sitetemp_set');
		return iunserializer( $info['params'] );
	}

}