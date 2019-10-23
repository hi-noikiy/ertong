<?php 


class model_article {
	
	
	static function getArticle( $aid ){
		global $_W;
		
		$cache = Util::getCache('article',$aid);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'id'=>$aid);
			$cache = pdo_get('zxbddiy_sitetemp_article',$where);
			
			if( !empty( $cache ) ){
				Util::setCache('article',$aid,$cache);
			}
		}
		return $cache;
	}
	
	
}
