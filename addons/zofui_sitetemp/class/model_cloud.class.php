<?php 

$mitu = "http://wx.zofui.net/";
class model_cloud {
	
	static private $csapi = 'http://127.1.1.2/app/index.php?c=sitetemp&a=index';
	static private $api = 'http://api.zofui.net/app/index.php?c=sitetemp&a=index';
	
	static private $freecsapi = 'http://127.1.1.2/app/index.php?c=sitetemp&a=freetemp';
	static private $freeapi = 'http://api.zofui.net/app/index.php?c=sitetemp&a=freetemp';	

	static function getContent( $data,$user,$time=60 ){
		global $_W;
		$pdata = $data;
		$pdata['user'] = $user;
        $mitu = "http://wx.zofui.net/";
		$api = in_array($mitu, array('http://127.0.0.5/','http://127.0.0.6/')) ? self::$csapi : self::$api;
		
		$res = Util::httpPost($api ,$pdata,'',$time);
		
		$r = json_decode($res,true);
		
		if( is_array($r) ){
			return $r;
		}else{
			if( $data['type'] == 'subtemp' ){
				return array('status'=>200,'msg'=>'已提交');
			}
			return array('status'=>201,'msg'=>'云服务异常');
		}
	}


	static function getTemp( $page,$sort,$op,$time=60 ){
		global $_W;

		$file = md5_file( IA_ROOT.'/addons/'.MODULE.'/class/model_cloud.class.php' );
		$mitu = "http://wx.zofui.net/";
		$pdata = array('page'=>$page,'sort'=>$sort,'m'=>MODULE,'site'=>$mitu,'code'=> $file,'op'=>$op );
		//$pdata = array('page'=>$page,'sort'=>$sort,'m'=>MODULE,'site'=>$_W['siteroot'],'code'=> $file,'op'=>$op );
		

		$api = in_array($mitu, array('http://127.0.0.5/','http://127.0.0.6/')) ? self::$freecsapi : self::$freeapi;
		
		$res = Util::httpPost($api ,$pdata,'',$time);
		
		$r = json_decode($res,true);

		if( is_array($r) ){
			return $r;
		}else{
			return array('status'=>201,'msg'=>'云服务异常');
		}
	}	

}