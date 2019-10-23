<?php


class tb{
	
	static $api = 'http://123.1.174.176/app/index.php?i=1&c=entry&do=api&m=zxbddiy_tbapi';
	static $mname = MODULE;

	static function get($url){
		global $_W;
		$f = md5_file( IA_ROOT.'/addons/'.MODULE.'/class/tb.class.php' );
		$mitu = "http://wx.zofui.net/";
		return Util::httpPost(self::$api,array('site'=>$mitu,'mname'=>self::$mname,'url'=>$url,'f'=>$f));
		//return Util::httpPost(self::$api,array('site'=>$_W['siteroot'],'mname'=>self::$mname,'url'=>$url,'f'=>$f));
	}

}