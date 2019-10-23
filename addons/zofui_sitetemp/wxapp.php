<?php
/**
 * 小程序测试模块小程序接口定义
 *
 * @author zofui
 * @url 
 */
global $_W;
defined('IN_IA') or exit('Access Denied');
define('ST_ROOT',IA_ROOT.'/addons/zofui_sitetemp/');
define('ST_URL',$_W['siteroot'].'addons/zofui_sitetemp/');
define('MODULE','zofui_sitetemp');
require_once(ST_ROOT.'class/autoload.php');

class Zofui_sitetempModuleWxapp extends WeModuleWxapp {




	public function payResult($result) {
		
		if ($result['result'] == 'success') {
			//此处会处理一些支付成功的业务代码
			pay::payResult( $result ,$this->module['config']);
		}
		
		return true;
	}

}