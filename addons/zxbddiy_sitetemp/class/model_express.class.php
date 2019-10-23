<?php 


class model_express
{
	
	
	//查询快递
	static function getExpressInfo($expressname,$expressnum){
		
		$res = Util::getCache($expressname,$expressnum);

		if( empty( $res ) ) {

			if( strpos($expressname,'顺丰') !== false ){
				$str = 'shunfeng';
			}elseif( strpos($expressname,'申通') !== false ){
				$str = 'shentong';
			}elseif( strpos($expressname,'韵达') !== false ){
				$str = 'yunda';			
			}elseif(strpos($expressname,'天天') !== false ){
				$str = 'tiantian';			
			}elseif( strpos($expressname,'圆通') !== false){
				$str = 'yuantong';			
			}elseif( strpos($expressname,'中通') !== false){
				$str = 'zhongtong';
			}elseif( strpos($expressname,'ems') !== false){
				$str = 'ems';			
			}elseif( strpos($expressname,'汇通') !== false){
				$str = 'huitongkuaidi';			
			}elseif(strpos($expressname,'全峰') !== false){
				$str = 'quanfengkuaidi';			
			}elseif( strpos($expressname,'宅急') !== false){
				$str = 'zhaijisong';			
			}elseif( strpos($expressname,'德邦') !== false){
				$str = 'debangwuliu';
			}
			
			$res = self::getExpressAPI1($str,$expressnum);
			if( $res['status'] == 0 ){
				$res = self::getExpressAPI2($str,$expressnum);
			}
			if( $res['status'] == 0 ){
				$res = self::getExpressAPI3($str,$expressnum);
			}
			
			if( $res['status'] == 1 ) {
				Util::setCache($expressname,$expressnum,$res);
			}

		}

		return $res;
	}
	
	//默认的接口1 
	static function getExpressAPI1($str,$expressnum){
		$url = 'https://m.kuaidi100.com/query?type='.$str.'&postid='.$expressnum.'&id=1';
		$result = Util::HttpGet($url);
		$data = json_decode($result,true);

		if($data['status'] == '200'){
			return	array('data'=>$data['data'],'status'=>1);
		}else{
			return array('data'=>$data['message'],'status'=>0);
		}
	}
	//接口2
	static function getExpressAPI2($str,$expressnum){
		$res = array('data'=>'未查到物流信息','status'=>0);
		$url = 'http://wap.kuaidi100.com/wap_result.jsp?rand='.rand(1,10).'&id='.$str.'&postid='.$expressnum; 
		$result = Util::HttpGet($url);
		if(empty($result)) return $res;

		preg_match_all('/\<p\>&middot;(.*)\<\/p\>/U', $result, $arr);
				
		if(is_array($arr[1]) && !empty( $arr[1] ) ){
			foreach($arr[1] as $k => $v){
				$list = explode('<br />',$v);
				$data[$k]['time'] = $list[0];
				$data[$k]['context'] = $list[1];
			}
			return array('data'=>array_reverse((array)$data),'status'=>1);
		}
		return $res;
	}
	//
	//接口3
	static function getExpressAPI3($str,$expressnum){
		$url = 'http://m.kuaidi.com/mindex-ajaxselectcourierinfo-'.$expressnum.'-'.$str.'.html';
		
		$result = Util::HttpGet($url);
		$data = json_decode($result,true);
		
		if($data['status'] == 6){
			$res = array('data'=>$data['data'],'status'=>1);
		}else{
			$res = array('data'=>'查询物流信息失败','status'=>0);
		}
		return $res;
	}
	
	
}