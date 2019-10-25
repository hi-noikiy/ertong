<?php
	
	global $_W;
	defined('IN_IA') or exit('Access Denied');

	function autoLoad ($classname){

		$file = ST_ROOT.'class/'.$classname.".class.php";
		if( file_exists($file) ){
			include_once ($file);
			return true;
		}
		return false;
	}
	spl_autoload_register('autoLoad',false);


	class plugin extends WeModuleSite {


		public function pInit($plugin){
			global $_GPC;
	        $plugin = strtolower($plugin);
	        $action = trim( $_GPC['do'] );

	        if( $action == '' || !isset( $action ) ) $action = 'index';
	        if(defined('IN_SYS')) {
	        	$file = ST_ROOT . "plugin/web/{$plugin}/inc/{$action}.inc.php";
	        	define('PLUGIN_ROOT',ST_ROOT . "plugin/web/{$plugin}/");
	        	define('PLUGIN_URL',ST_URL . "plugin/web/{$plugin}/");
	        }else{
	        	$commfile = ST_ROOT . "plugin/mobile/{$plugin}/common.inc.php";
	        	$file = ST_ROOT . "plugin/mobile/{$plugin}/inc/{$action}.inc.php";
	        	define('PLUGIN_ROOT',ST_ROOT . "plugin/mobile/{$plugin}/");
	        	define('PLUGIN_URL',ST_URL . "plugin/mobile/{$plugin}/");
	        }
			
	        if (!is_file($file)) {
	            return false;
	        }
	       	
	        if (is_file($commfile)) {
	            include $commfile;
	        }		        
			include $file;

			return true;
		}


		public function pTemplate($filename) {
			global $_W,$_GPC;
			$name = strtolower( MODULE );
			if(defined('IN_SYS')) {
				$source = ST_ROOT . "/plugin/web/{$_GPC['p']}/view/{$filename}.html";
				$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$_GPC['p']}/{$_GPC['do']}/{$filename}.tpl.php";
			} else {
				$source = ST_ROOT . "/plugin/mobile/{$_GPC['p']}/view/{$filename}.html";
				$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$name}/{$_GPC['p']}/{$_GPC['do']}/{$filename}.tpl.php";
			}
			if(!is_file($source)) {
				exit("Error: plugin '{$_GPC['p']}' template source '{$filename}' is not exist!");
			}
			$paths = pathinfo($compile);
			$compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
			if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
				template_compile($source, $compile, true);
			}
			
			return $compile;
		}

		public static function pmUrl($plug,$do,$array=array()){
			global $_W;
			$str = '&do='.$do.'&p='.$plug;
			if(!empty($array)){
				foreach($array as $k=>$v){
					$str .= '&'.$k.'='.$v;
				}
			}
			return $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry'.$str.'&m='.MODULE;
		}

		public static function pwUrl($plug,$do,$array=array()){
			global $_W;
			$str = '&do='.$do.'&p='.$plug;
			if(!empty($array)){
				foreach($array as $k=>$v){
					$str .= '&'.$k.'='.$v;
				}
			}
			return $_W['siteroot'].'web/index.php?c=site&a=entry'.$str.'&m='.MODULE;
		}



	}


