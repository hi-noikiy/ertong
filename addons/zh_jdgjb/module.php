<?php
/**
 * 志汇酒店营销版模块定义
 *
 * @author 新睿社区
 * @url http://www.010xr.com/
 */
defined('IN_IA') or exit('Access Denied');

class Zh_jdgjbModule extends WeModule {

	public function welcomeDisplay()

    {   
    	global $_GPC, $_W;

        $url = $this->createWebUrl('ptdata');
        if ($_W['role'] == 'operator') {
        	$url = $this->createWebUrl('hotel');
        }

        Header("Location: " . $url);

    }

}