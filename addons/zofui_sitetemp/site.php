<?php
/**
 * 模块微站定义
 *
 * @author 众惠科技
 * @url http://bbs.we7.cc/ 
 */


global $_W,$_GPC;
defined('IN_IA') or exit('Access Denied');
define('ST_ROOT',IA_ROOT.'/addons/zofui_sitetemp/');
define('ST_URL',$_W['siteroot'].'addons/zofui_sitetemp/');
define('MODULE','zofui_sitetemp');
require_once(ST_ROOT.'class/autoload.php');

class Zofui_sitetempModuleSite extends plugin {
	
	
	function __call($name, $arguments){
		global $_GPC,$_W;
		$_W['set'] = $this->module['config'];
		
		$this->pInit( $_GPC['p'] ) || parent::__call($name, $arguments);
	}
	
	
	
}
