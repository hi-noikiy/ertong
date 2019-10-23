<?php
/**
 * zh_cjpt模块定义
 *
 * @author 新睿社区
 * @url http://www.010xr.com/
 */
defined('IN_IA') or exit('Access Denied');

class Zh_cjptModule extends WeModule {

    public function welcomeDisplay()
    {   
        global $_GPC, $_W;

            $url = $this->createWebUrl('index');
	    	//$url = $this->createWebUrl('gaikuangdata');
	        Header("Location: " . $url);
    	}
    

}