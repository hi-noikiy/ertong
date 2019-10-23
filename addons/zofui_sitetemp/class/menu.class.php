<?php 

	class menu extends plugin{

		static function init(){

			return self::loadPlugin();

		}

		static function loadPlugin(){
			global $_W,$_GPC;

			$params = array();
			$sort = array();
			if ($handle = opendir(ST_ROOT.'plugin/web/')) {
			    while (false !== ($entry = readdir($handle))) {

			       if($entry != '.' && $entry != '..'){
						$file = ST_ROOT.'plugin/web/'.$entry.'/params.php';
						if( is_file( $file ) ) {
							$menu = include ($file);
							$menu['url'] = self::pwUrl($menu['p'],$menu['do'],array('op'=>$menu['op']));
							$sort[] = $menu['index'];
							$params[] = $menu;
						}
			       }
			    }
			    closedir($handle);
			}
			
			if( !empty( $params ) ) {

				array_multisort($sort,SORT_ASC,$params);

				$auth = model_auth::authList();
				$set = model_sysset::getSet();

				foreach ( $params as $k => $v ) {

					// 系统设置
					if( $v['p'] == 'sysset' ) {
						if( !in_array($_W['role'],array('founder','vice_founder')) || (  empty( $set['isvicead'] ) && empty($set['viceisauth']) && $_W['role'] == 'vice_founder' ) ){
							unset( $params[$k] );
						}
					}

					if( !empty( $auth ) ) {

						// 权限控制
						if( $v['p'] == 'shop' ) {
							
							if( empty( $auth['isshop'] ) ) {
								unset( $params[$k] );
							}
						}

						if( $v['p'] == 'order' ) {
							
							if( empty( $auth['isdesk'] ) ){
								unset( $params[$k] );
							}
						}

						if( $v['p'] == 'appoint' ) {
							if( empty( $auth['isappoint'] ) ) {
								unset( $params[$k] );
							}
						}
						if( $v['p'] == 'card' ) {
							if( empty( $auth['iscard'] ) ) {
								unset( $params[$k] );
							}
						}

					}

				}

			}

			

			return $params;
		}



	}