<?php
/**
 * 企业小程序模块小程序接口定义
 *
 * @author weixinmao
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Weixinmao_houseModuleWxapp extends WeModuleWxapp {
	
	
	
	public function GetSiteUrl()
	{
		
		global $_GPC, $_W;

			if($_W['attachurl']!=""){
			
				$siteurl = $_W['attachurl'];
				
			}else{
				
				$siteurl  = $_W['siteroot'].'attachment/';
			}
		return $siteurl;
		
	}

	public function IsCheck()
		{

			global $_GPC, $_W;
			$intro = pdo_fetch("SELECT ischeck FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
			return $intro['ischeck'];

		}


public function IsCheck2()
		{

			global $_GPC, $_W;
			$intro = pdo_fetch("SELECT ischeck2 FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
			return $intro['ischeck2'];

		}

	public function doPageTalk(){
			global $_GPC, $_W;

			$token = 'abc123abc';
			//echo $token;
			 $echoStr = $_GET["echostr"];
			  $this->responseMsg();
			 if ($this->checkSignature($token)) {
			 echo $echoStr;
			 exit;

			}

	}



   public  function checkSignature($token) //官方的验证函数
			{
			 $signature = $_GET["signature"];
			 $timestamp = $_GET["timestamp"];
			 $nonce = $_GET["nonce"];
			 $token = $token;
			 $tmpArr = array($token, $timestamp, $nonce);
			 sort($tmpArr, SORT_STRING);
			 $tmpStr = implode( $tmpArr );
			 $tmpStr = sha1( $tmpStr );
			 if( $tmpStr == $signature ){
			 return true;
			 }else{
			 return false;
			 }
}





		
public function responseMsg()
    {

    	 $fromUsername = $postArr['FromUserName'];   //发送者openid
                $content = '您好，有什么能帮助你?';
                $data=array(
                    "touser"=>$fromUsername,
                    "msgtype"=>"text",
                    "text"=>array("content"=>$content)
                );
                $json = json_encode($data,JSON_UNESCAPED_UNICODE);  //php5.4+
                
                $access_token = $this->get_accessToken();
                /* 
                 * POST发送https请求客服接口api
                 */
                $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
                //以'json'格式发送post的https请求
                error_reporting(0);
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
                if (!empty($json)){
                    curl_setopt($curl, CURLOPT_POSTFIELDS,$json);
                }
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
                $output = curl_exec($curl);
                if (curl_errno($curl)) {
                    echo 'Errno'.curl_error($curl);//捕抓异常
                }
                curl_close($curl);
                if($output == 0){
                    echo 'success';exit;
                }
                


    }




  public function doPagedelSaleinfo(){

  			global $_GPC, $_W;

			$id = $_GPC['id'];

			pdo_delete('weixinmao_house_saleinfo', array('id' => $id));

			return $this->result(0, 'success', $list);


  }

    public function doPagedelOldhouseinfo(){

			global $_GPC, $_W;

			$id = $_GPC['id'];

			pdo_delete('weixinmao_house_oldhouseinfo', array('id' => $id));

			return $this->result(0, 'success', $list);

	}


public function doPagedelLethouseinfo()
	{
			global $_GPC, $_W;
			$id = $_GPC['id'];

			pdo_delete('weixinmao_house_lethouseinfo', array('id' => $id));

			return $this->result(0, 'success', $list);

	}


	public function doPageGetcity()
	{
		global $_GPC, $_W;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
		
		return $this->result(0, 'success', $arealist);
		
		
	}
  public function httpGet($url) {
  	error_reporting(0);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
  }


	public function doPageGetphone()
		{

			global $_GPC, $_W;
			include "inc/wxBizDataCrypt.php";
		//var_dump($is);
		//	$code          = $_GET['code'];
			$iv            = $_GPC['iv'];
			$encryptedData = $_GPC['encryptedData'];
			$appid      = $_W['uniaccount']['key'];;//小程序唯一标识   (在微信小程序管理后台获取)
			$appsecret  = $_W['uniaccount']['secret'];//小程序的 app secret (在微信小程序管理后台获取)


		//	$grant_type = "authorization_code"; //授权（必填）
			 
		//	$params = "appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=".$grant_type;
		//	$url = "https://api.weixin.qq.com/sns/jscode2session?".$params;
			 
		//	$res = json_decode($this->httpGet($url),true);
			//json_decode不加参数true，转成的就不是array,而是对象。 下面的的取值会报错  Fatal error: Cannot use object of type stdClass as array in
		//	$sessionKey = $res['session_key'];//取出json里对应的值
			 
				//echo $_SESSION['session_key'];
			$pc = new WXBizDataCrypt($appid, $_SESSION['session_key']);
		//	var_dump($pc);

		//	echo $encryptedData, $iv;

			$errCode = $pc->decryptData($encryptedData, $iv, $data);
			 $obj = json_decode($data);
			 $uid = $_GPC['uid'];
			 $tel = $obj->phoneNumber;
			// echo $tel;
			 pdo_update('weixinmao_house_userinfo',array('tel'=>$tel),array('uid'=>$uid));
		
         return $this->result(0, 'success', array());

		}


		public function doPageGetcitylist()
		{
			global $_GPC, $_W;
		
			
			
			$condition_hot = " WHERE  ishot = 1 AND  uniacid=:uniacid  ORDER BY sort DESC ";
			$sql = 'SELECT id ,name FROM ' . tablename('weixinmao_house_city') . $condition_hot ;
			$hotlist = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid']));
			
			$condition = " WHERE   uniacid=:uniacid GROUP BY firstname ORDER BY firstname ,sort DESC";
			$sql = 'SELECT firstname FROM ' . tablename('weixinmao_house_city') . $condition ;
			$firstnamelist = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid']));
			
			
			$conditionlist = " WHERE   uniacid=:uniacid AND firstname=:firstname ";
			$sql = 'SELECT id ,name FROM ' . tablename('weixinmao_house_city') . $conditionlist ;
			foreach($firstnamelist AS $k=>$v)
			{
				$list = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid'],':firstname'=>$v['firstname']));

				$firstnamelist[$k]['firstlist']= $list;
			
				
				
			}
		//	print_r($firstnamelist);
			
			$data = array('hotlist'=>$hotlist,'firstnamelist'=>$firstnamelist);

			return $this->result(0, 'success', $data);
			
		}
			public function doPageGetFxSysInit()
			{

				global $_GPC, $_W;

				$city = $_GPC['city'];
				
				$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

				if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}
					

				$banner = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_adv') ."WHERE  enabled =1 AND weid=:weid ORDER BY displayorder ASC  ",array(":weid" => $_W['uniacid']));
				if($banner)
				{
					foreach($banner as $k=>$v)
					{
						$banner[$k]['thumb'] = tomedia($v['thumb']);
						
					}
				}


				$intro = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
				$intro['logo'] = tomedia($intro['logo']);
				$intro['indexadv'] = tomedia($intro['indexadv']);
				//$intro['description']=html_entity_decode($intro['content']);
				$intro['description'] = $intro['content'];
	 			$intro['content'] = trim(html_entity_decode(strip_tags($intro['content'])),chr(0xc2).chr(0xa0));  
				
				


		$condition = ' WHERE `uniacid` = :uniacid AND isrecommand = 1 AND `cityid` = :cityid AND fxmoney>0   ORDER BY sort DESC ';
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') .$condition ;
		
		$newhouselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE  `uniacid` = :uniacid AND `cityid` = :cityid  ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']));

		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($newhouselist)
		{
			foreach($newhouselist as $k=>$v)
				{
					$newhouselist[$k]['thumb'] = tomedia($v['thumb']);
					$newhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$newhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		
		/*二手房*/
		$condition = ' WHERE `uniacid` = :uniacid  AND isrecommand = 1 AND status =0   AND `cityid` = :cityid AND fxmoney>0    ORDER BY sort DESC ';
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;

		$oldhouselist = pdo_fetchall($sql, $params);
		
		//$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		//$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($oldhouselist)
		{
			foreach($oldhouselist as $k=>$v)
				{
					$oldhouselist[$k]['thumb'] = tomedia($v['thumb']);
					$oldhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$oldhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}




     $condition = ' WHERE `uniacid` = :uniacid  AND isrecommand = 1 AND status =0 AND fxmoney>0  ORDER BY sort DESC ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;

		$lethouselist = pdo_fetchall($sql, $params);
		

		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($lethouselist)
		{
			foreach($lethouselist as $k=>$v)
				{
					$lethouselist[$k]['thumb'] = tomedia($v['thumb']);
					$lethouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$lethouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}


			
      $condition = ' WHERE `uniacid` = :uniacid AND enabled = 1   ORDER BY sort DESC ';

      $params = array(':uniacid' => $_W['uniacid']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_agent') .$condition ;

		$agentlist = pdo_fetchall($sql, $params);

	if($agentlist)
		{
			foreach($agentlist as $k=>$v)
				{
					$agentlist[$k]['thumb'] = tomedia($v['thumb']);
				}
		}


	$condition = ' WHERE `weid` = :uniacid AND enabled = 1  ORDER BY displayorder DESC LIMIT 8 ';
	$params = array(':uniacid' => $_W['uniacid']);
	$sql = 'SELECT * FROM ' . tablename('weixinmao_house_nav') .$condition ;
	$navlist = pdo_fetchall($sql, $params);

if($navlist)
		{
			foreach($navlist as $k=>$v)
				{
					$navlist[$k]['thumb'] = tomedia($v['thumb']);
				}
		}

return $this->result(0, 'success', array('banner'=>$banner, 'intro'=>$intro,'newhouselist'=>$newhouselist,'oldhouselist'=>$oldhouselist,'agentlist'=>$agentlist,'lethouselist'=>$lethouselist,'navlist'=>$navlist,'cityinfo'=>$cityinfo));




}
		public function doPageGetSysInit()
			{

				global $_GPC, $_W;

				$city = $_GPC['city'];
				
				$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

				if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}
					

				$banner = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_adv') ."WHERE  enabled =1 AND weid=:weid ORDER BY displayorder ASC  ",array(":weid" => $_W['uniacid']));
				if($banner)
				{
					foreach($banner as $k=>$v)
					{
						$banner[$k]['thumb'] = tomedia($v['thumb']);
						
					}
				}


				$intro = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
				$intro['logo'] = tomedia($intro['logo']);
				$intro['indexadv'] = tomedia($intro['indexadv']);
				//$intro['description']=html_entity_decode($intro['content']);
				$intro['description'] = $intro['content'];
	 			$intro['content'] = trim(html_entity_decode(strip_tags($intro['content'])),chr(0xc2).chr(0xa0));  
				
				


		$condition = ' WHERE `uniacid` = :uniacid AND isrecommand = 1 AND `cityid` = :cityid  ORDER BY sort DESC LIMIT  '.$intro['newlimit'];
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') .$condition ;
		
		$newhouselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE  `uniacid` = :uniacid AND `cityid` = :cityid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']));

		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($newhouselist)
		{
			foreach($newhouselist as $k=>$v)
				{
					$newhouselist[$k]['thumb'] = tomedia($v['thumb']);
					$newhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$newhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		
		/*二手房*/
		$condition = ' WHERE `uniacid` = :uniacid  AND isrecommand = 1 AND status =0   AND `cityid` = :cityid   ORDER BY sort DESC LIMIT  '.$intro['oldlimit'];
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;

		$oldhouselist = pdo_fetchall($sql, $params);
		
		//$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		//$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($oldhouselist)
		{
			foreach($oldhouselist as $k=>$v)
				{
					$oldhouselist[$k]['thumb'] = tomedia($v['thumb']);
					$oldhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$oldhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}




     $condition = ' WHERE `uniacid` = :uniacid  AND isrecommand = 1 AND status =0  ORDER BY sort DESC LIMIT  '.$intro['letlimit'];
		$params = array(':uniacid' => $_W['uniacid']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;

		$lethouselist = pdo_fetchall($sql, $params);
		

		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($lethouselist)
		{
			foreach($lethouselist as $k=>$v)
				{
					$lethouselist[$k]['thumb'] = tomedia($v['thumb']);
					$lethouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$lethouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}


			
      $condition = ' WHERE `uniacid` = :uniacid AND enabled = 1  ORDER BY sort DESC ';

      $params = array(':uniacid' => $_W['uniacid']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_agent') .$condition ;

		$agentlist = pdo_fetchall($sql, $params);

	if($agentlist)
		{
			foreach($agentlist as $k=>$v)
				{
					$agentlist[$k]['thumb'] = tomedia($v['thumb']);
				}
		}


	$condition = ' WHERE `weid` = :uniacid AND enabled = 1  ORDER BY displayorder DESC LIMIT 8 ';
	$params = array(':uniacid' => $_W['uniacid']);
	$sql = 'SELECT * FROM ' . tablename('weixinmao_house_nav') .$condition ;
	$navlist = pdo_fetchall($sql, $params);

if($navlist)
		{
			foreach($navlist as $k=>$v)
				{
					$navlist[$k]['thumb'] = tomedia($v['thumb']);
				}
		}

return $this->result(0, 'success', array('banner'=>$banner, 'intro'=>$intro,'newhouselist'=>$newhouselist,'oldhouselist'=>$oldhouselist,'agentlist'=>$agentlist,'lethouselist'=>$lethouselist,'navlist'=>$navlist,'cityinfo'=>$cityinfo));




}



	public function doPageGetstorelist()
	{
		global $_GPC, $_W;
	

	
		$cateid = $_GPC['cateid'];
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_store') ." WHERE  uniacid=:weid AND cateid=:cateid ORDER BY sort DESC",array(":weid" => $_W['uniacid'],":cateid"=>$cateid));
		
		foreach($list  as $k=>$v)
			{
			
				$list[$k]['logo'] = tomedia($v['logo']);
			
				
			}



		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));



	    $data = array('list'=>$list, 'intro'=>$intro);
		
		return $this->result(0, 'success', $data);
		
	}
	
  public function doPageGetstoredetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_store') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
		$list['logo'] = tomedia($list['logo']);

			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			

		

            $intro = pdo_fetch("SELECT maincolor,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

            $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
         

			$data = array('list'=>$list,'intro'=>$intro);
			
			
			return $this->result(0, 'success', $data);
			
		
	}

		public function doPageGetpubinit()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$cityid = $_GPC['cityid'];
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid` =:cityid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$cityid));
		
		if($arealist)
		{
			foreach($arealist as $k=>$v)
				{
					$buildarealist = pdo_getall('weixinmao_house_buildarea',array('uniacid'=>$_W['uniacid'],'aid'=>$v['id']));

					$buildareainfo[$v['id']] = $buildarealist;
				}
		
		}

		$sql = 'SELECT id FROM ' . tablename('weixinmao_house_agent') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid AND `enabled`=1';
		
		$messageinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));
		
		if($messageinfo['id']>0)
		{
			
			$ismaster = 1;
		}else{
			$ismaster = 0;
		
		}
		
		//$money = 0.01;
		
		//$toplist = array(array('id'=>1,'money'=>0.01,'title'=>'0.01元/1天'),array('id'=>1,'money'=>0.02,'title'=>'0.02元/2天'));		

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_toplist') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$toplist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		foreach($toplist as $k=>$v)
			{
				$toplist[$k]['title']  = $v['money'].'元/'.$v['days'].'天';
			}

 $intro = pdo_fetch("SELECT ispay, maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 	if($intro['ispay'] == 0)
	 	{
			$firsttop = array('id'=>0,title=>"不置顶直接发布",money=>0);
			array_unshift($toplist,$firsttop); 

		}
 	




		$data = array('arealist'=>$arealist,'ismaster'=>$ismaster,'toplist'=>$toplist,'intro'=>$intro,'buildareainfo'=>$buildareainfo);
		return $this->result(0, 'success', $data);
		
		
	}
	public function doPageGetagentlist()
	{
		global $_GPC, $_W;
		//$siteurl = $this->GetSiteUrl();
		//
		$city = trim($_GPC['city']);
				
				$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

				if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}

	//	print_r($cityinfo);

		$condition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid  AND enabled = 1 ';
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityinfo['id']);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*100;
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_agent') .$condition ;
		
		$list = pdo_fetchall($sql, $params);
		
		if($list){
				foreach($list as $k=>$v)
					{
						$list[$k]['thumb'] = tomedia($v['thumb']);

					$list[$k]['intro']= mb_substr($v['intro'],0,30,'utf-8').'...'; 
					}
		}

		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

	//   $list['intro'] = $intro;

	  $data = array('list'=>$list, 'intro'=>$intro,'cityinfo'=>$cityinfo);
		
		return $this->result(0, 'success', $data);
		
		
	}
	
	
	public function doPageGetagentdetail()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_agent') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
		//	$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['thumb'] = tomedia($list['thumb']);
			$view = $list['view'] + 1;
			pdo_update('weixinmao_house_agent',array('view'=>$view),array('uniacid'=>$_W['uniacid'],'id'=>$id));
			
			
			$condition = " WHERE  tel=:tel  AND  uniacid=:uniacid  ORDER BY createtime DESC ";
			$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') . $condition ;
			$oldhouselist = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid'],':tel'=>$list['tel']));
			if($oldhouselist)
			{
					foreach($oldhouselist as $k=>$v)
					{
						$oldhouselist[$k]['thumb'] = tomedia($v['thumb']);
						$oldhouselist[$k]['money'] = $v['saleprice'];
					}
			}

			 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			$data = array('list'=>$list,'oldhouselist'=>$oldhouselist,'intro'=>$intro);

			return $this->result(0, 'success', $data);
			
		}
	public function doPageGetagenthouse()
	{
		global $_GPC, $_W;
	//	$siteurl = $this->GetSiteUrl();
		$pid = $_GPC['pid'];
		$tel = $_GPC['tel'];
		
		$condition = " WHERE  tel=:tel  AND  uniacid=:uniacid ORDER BY createtime DESC  ";
		if($pid ==1)
		{
			$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') . $condition ;
		}else{
			
			$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') . $condition ;
		}
	
		$houselist = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid'],':tel'=>$tel));
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
					if($pid ==1)
					$houselist[$k]['money'] = $v['saleprice'];
					else
					 $houselist[$k]['money'] = $v['money'];
				}
		}
		$data = array('houselist'=>$houselist);
		return $this->result(0, 'success', $data);
	}
	
	
	
	public function doPageGetsalelist()
	{
		global $_GPC, $_W;
		//$siteurl = $this->GetSiteUrl();
			$cityid = $_GPC['cityid'];

			$condition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid AND ischeck = 1 AND (toplistid =0 OR endtime < '.time().')';
			$topcondition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid AND ischeck = 1 AND toplistid>0 AND endtime > '.time();
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$topcondition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['housetype']>0) {
				$condition .= ' AND `type` = :housetype';
				$topcondition .= ' AND `type` = :housetype';
				$params[':housetype'] = $_GPC['housetype'] ;
			}
		
		$sort =' ORDER BY createtime DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_saleinfo') .$condition. $limit ;
		
	
		$salelist = pdo_fetchall($sql, $params);

        $topsql =  'SELECT * FROM ' . tablename('weixinmao_house_saleinfo') .$topcondition. $limit ;
	    $topsalelist = pdo_fetchall($topsql, $params);

	
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid  ORDER BY `sort` DESC';
		

		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':cityid'=>$cityid));
		
	


		if($salelist)
		{
			foreach($salelist as $k=>$v)
				{
					
					$sql = 'SELECT name,tel,avatarUrl FROM ' . tablename('weixinmao_house_userinfo') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ';
		
					$userinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$v['uid']));
					//$salelist[$k]['name'] =  $userinfo['name'];
					//$salelist[$k]['tel'] =  $userinfo['tel'];
					$salelist[$k]['avatarUrl'] =  $userinfo['avatarUrl'];
					$salelist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$salelist[$k]['createtime'] = date('Y-m-d H:m:s',$v['createtime']);
					if($v['type'] ==1)
					{
						$salelist[$k]['type'] = '出售';
					}elseif($v['type'] == 2){
						
						$salelist[$k]['type'] = '出租';

					}elseif($v['type'] == 3){
						
						$salelist[$k]['type'] = '求购';

					}elseif($v['type'] == 4){
						
						$salelist[$k]['type'] = '求租';

					}


					if($v['thumb_url'])
					{
						$piclist1 = unserialize($v['thumb_url']);
						$piclist = array();
					
						if(is_array($piclist1)){
							foreach($piclist1 as $p)
									{
										if($p)
										{
												$piclist[] = tomedia($p);
										}
									}
							}

						$salelist[$k]['piclist'] = $piclist;

					}

					if($v['special']){
				 	$salelist[$k]['special']  = explode(',',$v['special']);
				 	}



					
				}
			}

		//置顶信息
		
		if($topsalelist)
		{
			foreach($topsalelist as $k=>$v)
				{
					
					$sql = 'SELECT name,tel,avatarUrl FROM ' . tablename('weixinmao_house_userinfo') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ';
		
					$userinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$v['uid']));
					$topsalelist[$k]['name'] =  $userinfo['name'];
					$topsalelist[$k]['tel'] =  $userinfo['tel'];
					$topsalelist[$k]['avatarUrl'] =  $userinfo['avatarUrl'];
					$topsalelist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$topsalelist[$k]['createtime'] = date('Y-m-d H:m:s',$v['createtime']);
					if($v['type'] ==1)
					{
						$topsalelist[$k]['type'] = '出售';
					}elseif($v['type'] == 2){
						
						$topsalelist[$k]['type'] = '出租';

					}elseif($v['type'] == 3){
						
						$topsalelist[$k]['type'] = '求购';

					}elseif($v['type'] == 4){
						
						$topsalelist[$k]['type'] = '求租';

					}


					if($v['thumb_url'])
					{
						$piclist1 = unserialize($v['thumb_url']);
						$piclist = array();
					
						if(is_array($piclist1)){
							foreach($piclist1 as $p)
									{
										if($p)
										{
												$piclist[] = tomedia($p);
										}
									}
							}

						$topsalelist[$k]['piclist'] = $piclist;

					}

					if($v['special']){
				 	$topsalelist[$k]['special']  = explode(',',$v['special']);
				 	}



					
				}
			}

		$data = array('salelist'=>$salelist, 'topsalelist'=>$topsalelist);
		return $this->result(0, 'success', $data);

	}
	
	
	
	public function doPageGetsaledetail()
	{
		
			global $_GPC, $_W;
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_saleinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = $list['specialliststr'] = explode(',',$list['productspecial']);
			$list['specialliststr'] = implode('、',$list['specialliststr']);
			$salestatus=array(1=>'出售',2=>'出租',3=>'求购',4=>'求租');
			$list['type'] = $salestatus[$list['type']];
			 $sql = 'SELECT name,tel,avatarUrl FROM ' . tablename('weixinmao_house_userinfo') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ';
		
					$userinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$list['uid']));
					//$list['name'] =  $userinfo['name'];
					//$list['tel'] =  $userinfo['tel'];
					$list['avatarUrl'] =  $userinfo['avatarUrl'];
			
		if($list['special']){
				 	$list['special']  = explode(',',$list['special']);
				 	}

			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		
			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
							if($p)
							{
									$piclist[] = tomedia($p);
							}
						}
				}
			$hits = $list['hits'] +1;
			
			pdo_update('weixinmao_house_saleinfo', array('hits'=>$hits), array('id' => $id));

		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			
			$data = array('list'=>$list,'piclist'=>$piclist,'intro'=>$intro);

			return $this->result(0, 'success', $data);
			
		
	}
	
	
	
	
	public function doPageGetnewhouselist()
	{
		global $_GPC, $_W;
		//$siteurl = $this->GetSiteUrl();
		$cityid = $_GPC['cityid'];
			$condition = ' WHERE `uniacid` = :uniacid  AND `cityid` = :cityid ';
		$params = array(':uniacid' => $_W['uniacid'] ,':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['housetype']>0) {
				$condition .= ' AND `housetype` = :housetype';
				$params[':housetype'] = $_GPC['housetype'] ;
			}
		if ($_GPC['bid']>0) {
				$condition .= ' AND `bid` = :bid';
				$params[':bid'] = $_GPC['bid'] ;
			}

		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_houseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `houseprice` >  '.$priceinfo['beginprice'].'  AND `houseprice` <= '.$priceinfo['endprice'];
			}
			
			
		}
		$sort =' ORDER BY  sort DESC , createtime DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
				foreach($arealist as $k=>$v)
				{
						$areainfo[$v['id']] = $v['name'];			
				}
		}
		
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] =tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
			}
		return $this->result(0, 'success', $houselist);

	}
	





	
	
	
	
public function doPageGetoldhouselist()
	{
		global $_GPC, $_W;

		
			$cityid = $_GPC['cityid'];
		

		$condition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid'] ,':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['housetype']>0) {
				$condition .= ' AND `housetype` = :housetype';
				$params[':housetype'] = $_GPC['housetype'] ;
			}

		if ($_GPC['bid']>0) {
				$condition .= ' AND `bid` = :bid';
				$params[':bid'] = $_GPC['bid'] ;
			}
		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_oldhouseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `saleprice` >  '.$priceinfo['beginprice'].'  AND `saleprice` <= '.$priceinfo['endprice'];
			}
		
			
		}
		$sort =' ORDER BY  sort DESC , createtime DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist){
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] =tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

	}
	
public function doPageGetoldsalehouselist()
	{
		global $_GPC, $_W;

		
			$cityid = $_GPC['cityid'];
		

		$condition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid'] ,':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['letway']>0) {
				$condition .= ' AND `housetype` = :letway';
				$params[':letway'] = $_GPC['letway'] ;
			}
        if ($_GPC['housetype']>0) {
				$condition .= ' AND `roomid` = :roomid';
				$params[':roomid'] = $_GPC['housetype'] ;
			}

		if ($_GPC['bid']>0) {
				$condition .= ' AND `bid` = :bid';
				$params[':bid'] = $_GPC['bid'] ;
			}
		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_oldhouseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `saleprice` >  '.$priceinfo['beginprice'].'  AND `saleprice` <= '.$priceinfo['endprice'];
			}
		
			
		}
		$sort =' ORDER BY  sort DESC , createtime DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldsalehouseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist){
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] =tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

	}

	public function doPageGetoldpayhouselist()
	{
		global $_GPC, $_W;

		
			$cityid = $_GPC['cityid'];
		

		$condition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid'] ,':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['letway']>0) {
				$condition .= ' AND `housetype` = :letway';
				$params[':letway'] = $_GPC['letway'] ;
			}
        if ($_GPC['housetype']>0) {
				$condition .= ' AND `roomid` = :roomid';
				$params[':roomid'] = $_GPC['housetype'] ;
			}
		if ($_GPC['bid']>0) {
				$condition .= ' AND `bid` = :bid';
				$params[':bid'] = $_GPC['bid'] ;
			}
		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_oldhouseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `saleprice` >  '.$priceinfo['beginprice'].'  AND `saleprice` <= '.$priceinfo['endprice'];
			}
		
			
		}
		$sort =' ORDER BY  sort DESC , createtime DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldpayhouseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist){
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] =tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

	}

	public function doPageGetbusinesshouselist()
	{
		global $_GPC, $_W;

		
			$cityid = $_GPC['cityid'];
		

		$condition = ' WHERE `uniacid` = :uniacid AND `cityid` = :cityid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid'] ,':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['housetype']>0) {
				$condition .= ' AND `housetype` = :housetype';
				$params[':housetype'] = $_GPC['housetype'] ;
			}
		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_oldhouseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `saleprice` >  '.$priceinfo['beginprice'].'  AND `saleprice` <= '.$priceinfo['endprice'];
			}
		
			
		}
		$sort =' ORDER BY  sort DESC , createtime DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_businesshouseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist){
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] =tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

	}
	
	public function doPageGetlethouselist()
	{
		global $_GPC, $_W;

		//$siteurl = $this->GetSiteUrl();
		//
		$cityid = $_GPC['cityid'];
		$condition = ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid AND status =0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';

		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['housetype']>0) {
				$condition .= ' AND `roomid` = :roomid';
				$params[':roomid'] = $_GPC['housetype'] ;
			}
		if ($_GPC['bid']>0) {
				$condition .= ' AND `bid` = :bid';
				$params[':bid'] = $_GPC['bid'] ;
			}
		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_lethouseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `money` >  '.$priceinfo['beginprice'].'  AND `money` <= '.$priceinfo['endprice'];
			}		
		}
		if ($_GPC['letway']>0) {
				$condition .= ' AND `letway` = :letway';
				$params[':letway'] = $_GPC['letway'] ;
			}
		
		$sort =' ORDER BY  sort DESC , createtime DESC  ';

		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

	}
	

	public function doPageGetletbusinesshouselist()
	{
		global $_GPC, $_W;

		//$siteurl = $this->GetSiteUrl();
		//
		$cityid = $_GPC['cityid'];
		$condition = ' WHERE `uniacid` = :uniacid AND `cityid`=:cityid AND status =0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';

		$areatype= array(1=>'0-100㎡',2=>'100-200㎡',3=>'200-300㎡',4=>'300-500㎡',5=>'500㎡-1000㎡',6=>'1000㎡以上');



 
		$params = array(':uniacid' => $_W['uniacid'],':cityid'=>$cityid);
		if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
		if ($_GPC['houseareaid']>0) {
				$condition .= ' AND `houseareaid` = :houseareaid';
				$params[':houseareaid'] = $_GPC['houseareaid'] ;
			}
		if ($_GPC['housetype']>0) {
				$housetypeinfo = explode('-',$areatype[$_GPC['housetype']]);

				$condition .= ' AND `area` >  '.$housetypeinfo[0].'  AND `area` <= '.$housetypeinfo[1];
				

			}
       if ($_GPC['letway']>0) {
				$condition .= ' AND `housetype` = :housetype';
				$params[':housetype'] = $_GPC['letway'] ;
			}

		if ($_GPC['bid']>0) {
				$condition .= ' AND `bid` = :bid';
				$params[':bid'] = $_GPC['bid'] ;
			}
		if($_GPC['housepriceid']>0)
		{	$housepriceid = $_GPC['housepriceid'];
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_lethouseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `money` >  '.$priceinfo['beginprice'].'  AND `money` <= '.$priceinfo['endprice'];
			}		
		}
	
		
		$sort =' ORDER BY  sort DESC , createtime DESC  ';

		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_letbusinesshouseinfo') .$condition. $limit ;
		
		$houselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

	}

public function doPageGetMoneyLable()
		{

			global $_GPC, $_W;
			$companyid = $_GPC['companyid'];
			$uid = $_GPC['uid'];	
			$sql = 'SELECT *  FROM ' . tablename('weixinmao_house_paylist') . ' WHERE `uniacid` = :uniacid  ORDER BY `sort` DESC';

		$moneylist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
   
if($moneylist)
		{
			foreach($moneylist as $k=>$v)
				{
					$moneylist[$k]['chongzhi'] ='￥'.$v['money'];
					$moneylist[$k]['song'] =$v['title'];

					//$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					//$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
			}


		$sql = 'SELECT *  FROM ' . tablename('weixinmao_house_scorerecord') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid  ORDER BY `createtime` DESC';

		$scorelist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));

		foreach ($scorelist as $k => $v) {
			# code...
			$scorelist[$k]['createtime'] = date('Y-m-d',$v['createtime']);
		}

					 $intro = pdo_fetch("SELECT maincolor ,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

		$data = array('moneylist'=>$moneylist,'intro'=>$intro,'scorelist'=>$scorelist);

		return $this->result(0, 'success', $data);

		
		}


	public function doPageGethousedetail()
	{
		
			global $_GPC, $_W;
		//	$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];

			$typeid = $_GPC['typeid'];
			if($typeid ==0)
			{
					$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
					
					$house_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_house') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
					if($house_list)
					{
							foreach($house_list as $k=>$v)
							{
								$house_list[$k]['thumb'] = tomedia($v['thumb']);
								
							}
					}

			}elseif($typeid == 1)
			{

					$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));

					$list['housename'] = $list['title'] ;
			}
			elseif($typeid == 2)
			{

					$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));

					$list['housename'] = $list['title'] ;
			}


	

       

			 $intro = pdo_fetch("SELECT maincolor ,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			$data = array('list'=>$list,'houseplan'=>$house_list,'intro'=>$intro);

			return $this->result(0, 'success', $data);
			
		
	}
	
	
	public function doPageGetnewhousedetail()
	{
		
			global $_GPC, $_W;
		//	$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			//$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = $list['specialliststr'] = explode(',',$list['productspecial']);
			$list['specialliststr'] = implode('、',$list['specialliststr']);
			$salestatus=array(1=>'在售',2=>'待售',3=>'售完',4=>'打折');
			$list['salestatus_str'] = $salestatus[$list['housestatus']];
			 
			
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		
			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}
			
			
			
			$case_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_case') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
			if($case_list){
				foreach($case_list as $k=>$v)
				{
					$case_list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			
			$house_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_house') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
			if($house_list)
			{
					foreach($house_list as $k=>$v)
					{
						$house_list[$k]['thumb'] = tomedia($v['thumb']);
						
					}
			}


			$active_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_active') ." WHERE  uniacid=:weid  AND pid=:pid ORDER BY createtime DESC",array(":weid" => $_W['uniacid'],":pid"=>$list['id']));
			if($active_list)
			{
				foreach($active_list as $k=>$v)
				{
					$active_list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$active_list[$k]['thumb'] = tomedia($v['thumb']);
					$baolist = pdo_fetchall("SELECT id FROM " . tablename('weixinmao_house_baoming') ." WHERE uniacid=:uniacid  AND aid=:aid",array(":uniacid" => $_W['uniacid'],':aid'=>$v['id']));
					$totalnum = count($baolist);
					$active_list[$k]['totalnum'] = $totalnum;
					
				}
			}



     //  $condition = ' WHERE c.uniacid = :uniacid AND n.id =:newhouseid  ';
    //  $params = array(':uniacid' => $_W['uniacid'],':newhouseid'=>$id);
      $condition = ' WHERE c.uniacid = '.$_W['uniacid']. '  AND h.id= '.$id ;
   //   $params = array(':uniacid' => $_W['uniacid']);

			$sqllist = 'SELECT c.score as score  ';
		
			
		$sql = " FROM " . tablename('weixinmao_house_comment') . " AS c ";
			
		//$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_housemsg') . " AS m ON m.id = c.houseid ";
			
		$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_houseinfo') . " AS h ON h.id = c.pid ";

	//	echo $sqllist.$sql.$condition;
			
		$scorelist = pdo_fetchall($sqllist.$sql.$condition);

		$totalscore = count($scorelist);
		foreach($scorelist as  $k=>$v)
				{

					$score_sum = $score_sum + $v['score'];
				}

		//echo $totalscore;
		//echo $score_sum;
if($totalscore>0)
		{
		$score =  sprintf("%.1f",$score_sum/$totalscore );
	}else{

		$score = 0.0;
	}

$condition = ' WHERE c.uniacid = '.$_W['uniacid']. '  AND h.id= '.$id.' ORDER BY c.createtime DESC '; 

		$sqllist = 'SELECT c.id AS id, c.content AS content,u.avatarUrl AS avatarUrl,u.wechaname AS wechaname,c.createtime AS createtime ';
	
		$sql = " FROM " . tablename('weixinmao_house_comment') . " AS c ";
			
		//$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_housemsg') . " AS m ON m.id = c.houseid ";
			
		$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_houseinfo') . " AS h ON h.id = c.pid ";

		$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_userinfo') . " AS u ON u.uid = c.uid ";

			
		$commentlist  = pdo_fetchall($sqllist.$sql.$condition);

		//print_r($commentlist);

       foreach($commentlist as $k=>$v){

       				$commentlist[$k]['createtime'] = date('Y-m-d',$v['createtime']);

       }

			 $intro = pdo_fetch("SELECT maincolor ,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			$data = array('list'=>$list,'housepic'=>$case_list,'houseplan'=>$house_list,'piclist'=>$piclist,'intro'=>$intro,'active_list'=>$active_list,'score'=>$score,'commentlist'=>$commentlist);

			return $this->result(0, 'success', $data);
			
		
	}

	public function Convert_BD09_To_GCJ02($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
}

   public function doPageGetoldhousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		  //  $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
		//	$list['lat'] = $map['lat'];
		//	$list['lng'] = $map['lng'];
			$list['video'] = tomedia(	$list['video']);

			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}

            $intro = pdo_fetch("SELECT maincolor,name,tel,logo FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

            $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
            if(!$agentinfo)
            	{
            		$agentinfo['tel'] = $intro['tel'];
            		$agentinfo['thumb'] = $intro['logo'];
            		$agentinfo['id'] = 0 ; 
            		$agentinfo['name'] = $intro['name'];


            	}
			$agentinfo['thumb'] = tomedia($agentinfo['thumb']);
$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));



			if($saveinfo)

					$issave = 1;

			else

					$issave = 0;



			$condition = ' WHERE id<> '.$id.'  AND houseareaid = '.$list['houseareaid'].' AND  `uniacid` = :uniacid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		
		$sort =' ORDER BY createtime DESC LIMIT 10  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;
		
		$houselist = pdo_fetchall($sql, $params);

			if($houselist)
			{

				foreach ($houselist as $k => $v) {
					# code...
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
				}
			}


			$data = array('list'=>$list,'piclist'=>$piclist,'intro'=>$intro,'agentinfo'=>$agentinfo,'issave'=>$issave,'houselist'=>$houselist);
			
			
			return $this->result(0, 'success', $data);
			
		
	}
	


	   public function doPageGetoldpayhousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldpayhouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		  //  $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
		//	$list['lat'] = $map['lat'];
		//	$list['lng'] = $map['lng'];
			$list['video'] = tomedia(	$list['video']);

			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}

            $intro = pdo_fetch("SELECT maincolor,name,tel,logo FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

            $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
            if(!$agentinfo)
            	{
            		$agentinfo['tel'] = $intro['tel'];
            		$agentinfo['thumb'] = $intro['logo'];
            		$agentinfo['id'] = 0 ; 
            		$agentinfo['name'] = $intro['name'];


            	}
			$agentinfo['thumb'] = tomedia($agentinfo['thumb']);
$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));



			if($saveinfo)

					$issave = 1;

			else

					$issave = 0;



			$condition = ' WHERE id<> '.$id.'  AND houseareaid = '.$list['houseareaid'].' AND  `uniacid` = :uniacid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		
		$sort =' ORDER BY createtime DESC LIMIT 10  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldpayhouseinfo') .$condition ;
		
		$houselist = pdo_fetchall($sql, $params);

			if($houselist)
			{

				foreach ($houselist as $k => $v) {
					# code...
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
				}
			}


			$data = array('list'=>$list,'piclist'=>$piclist,'intro'=>$intro,'agentinfo'=>$agentinfo,'issave'=>$issave,'houselist'=>$houselist);
			
			
			return $this->result(0, 'success', $data);
			
		
	}
	

	   public function doPageGetoldsalehousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldsalehouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		  //  $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
		//	$list['lat'] = $map['lat'];
		//	$list['lng'] = $map['lng'];
			$list['video'] = tomedia(	$list['video']);

			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}

            $intro = pdo_fetch("SELECT maincolor,name,tel,logo FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

            $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
            if(!$agentinfo)
            	{
            		$agentinfo['tel'] = $intro['tel'];
            		$agentinfo['thumb'] = $intro['logo'];
            		$agentinfo['id'] = 0 ; 
            		$agentinfo['name'] = $intro['name'];


            	}
			$agentinfo['thumb'] = tomedia($agentinfo['thumb']);
$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));



			if($saveinfo)

					$issave = 1;

			else

					$issave = 0;



			$condition = ' WHERE id<> '.$id.'  AND houseareaid = '.$list['houseareaid'].' AND  `uniacid` = :uniacid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		
		$sort =' ORDER BY createtime DESC LIMIT 10  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldsalehouseinfo') .$condition ;
		
		$houselist = pdo_fetchall($sql, $params);

			if($houselist)
			{

				foreach ($houselist as $k => $v) {
					# code...
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
				}
			}


			$data = array('list'=>$list,'piclist'=>$piclist,'intro'=>$intro,'agentinfo'=>$agentinfo,'issave'=>$issave,'houselist'=>$houselist);
			
			
			return $this->result(0, 'success', $data);
			
		
	}
  
     public function doPageGetbusinesshousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_businesshouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		  
			$list['video'] = tomedia(	$list['video']);

			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}

            $intro = pdo_fetch("SELECT maincolor,name,tel,logo FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

            $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
            if(!$agentinfo)
            	{
            		$agentinfo['tel'] = $intro['tel'];
            		$agentinfo['thumb'] = $intro['logo'];
            		$agentinfo['id'] = 0 ; 
            		$agentinfo['name'] = $intro['name'];


            	}
			$agentinfo['thumb'] = tomedia($agentinfo['thumb']);
$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));



			if($saveinfo)

					$issave = 1;

			else

					$issave = 0;



			$condition = ' WHERE id<> '.$id.'  AND houseareaid = '.$list['houseareaid'].' AND  `uniacid` = :uniacid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		
		$sort =' ORDER BY createtime DESC LIMIT 10  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_businesshouseinfo') .$condition ;
		
		$houselist = pdo_fetchall($sql, $params);

			if($houselist)
			{

				foreach ($houselist as $k => $v) {
					# code...
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
				}
			}


			$data = array('list'=>$list,'piclist'=>$piclist,'intro'=>$intro,'agentinfo'=>$agentinfo,'issave'=>$issave,'houselist'=>$houselist);
			
			
			return $this->result(0, 'success', $data);
			
		
	}



   public function doPageGetletbusinesshousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$uid = $_GPC['uid'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_letbusinesshouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
		//	$list['content'] = html_entity_decode(stripslashes($list['content']));
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$list['houselabel'] = explode(',',$list['houselabel']);
		
			$list['video'] = tomedia($list['video']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
			
			$orderinfo = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') ." WHERE uniacid=:uniacid AND uid=:uid AND status =1 AND pid=".$id,array(":uniacid" => $_W['uniacid'],":uid"=>$uid));
		
			if($orderinfo)
			{
				$ispay = 1;
			}else{
				
				$ispay = 0;
			}

			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}
			 $intro = pdo_fetch("SELECT maincolor,name,tel,logo FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));



			 $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
            if(!$agentinfo)
            	{
            		$agentinfo['tel'] = $intro['tel'];
            		$agentinfo['thumb'] = $intro['logo'];
            		$agentinfo['id'] = 0 ; 
            		$agentinfo['name'] = $intro['name'];


            	}
			$agentinfo['thumb'] = tomedia($agentinfo['thumb']);
$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));



			if($saveinfo)

					$issave = 1;

			else

					$issave = 0;



		$condition = ' WHERE id<> '.$id.'  AND houseareaid = '.$list['houseareaid'].' AND  `uniacid` = :uniacid AND status =0 AND (ispub = 1 AND ischeck =1 OR ispub = 0) ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		
		$sort =' ORDER BY createtime DESC LIMIT 10  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_letbusinesshouseinfo') .$condition ;
		
		$houselist = pdo_fetchall($sql, $params);

			if($houselist)
			{

				foreach ($houselist as $k => $v) {
					# code...
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
				}
			}

			

			$data = array('list'=>$list,'piclist'=>$piclist,'ispay'=>$ispay,'intro'=>$intro,'agentinfo'=>$agentinfo,'issave'=>$issave,'lethouselist'=>$houselist);
			return $this->result(0, 'success', $data);
			
		
	}


   public function doPageGetlethousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$uid = $_GPC['uid'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
		//	$list['content'] = html_entity_decode(stripslashes($list['content']));
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$list['houselabel'] = explode(',',$list['houselabel']);
		
			$list['video'] = tomedia($list['video']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
			
			$orderinfo = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') ." WHERE uniacid=:uniacid AND uid=:uid AND status =1 AND pid=".$id,array(":uniacid" => $_W['uniacid'],":uid"=>$uid));
		
			if($orderinfo)
			{
				$ispay = 1;
			}else{
				
				$ispay = 0;
			}

			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}
			 $intro = pdo_fetch("SELECT maincolor,name,tel,logo FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));



			 $agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'tel'=>$list['tel']));
            if(!$agentinfo)
            	{
            		$agentinfo['tel'] = $intro['tel'];
            		$agentinfo['thumb'] = $intro['logo'];
            		$agentinfo['id'] = 0 ; 
            		$agentinfo['name'] = $intro['name'];


            	}
			$agentinfo['thumb'] = tomedia($agentinfo['thumb']);
$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));



			if($saveinfo)

					$issave = 1;

			else

					$issave = 0;



		$condition = ' WHERE id<> '.$id.'  AND houseareaid = '.$list['houseareaid'].' AND  `uniacid` = :uniacid AND status =0 AND (ispub = 1 AND ischeck =1 OR ispub = 0) ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		
		$sort =' ORDER BY createtime DESC LIMIT 10  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;
		
		$houselist = pdo_fetchall($sql, $params);

			if($houselist)
			{

				foreach ($houselist as $k => $v) {
					# code...
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
				}
			}

			

			$data = array('list'=>$list,'piclist'=>$piclist,'ispay'=>$ispay,'intro'=>$intro,'agentinfo'=>$agentinfo,'issave'=>$issave,'lethouselist'=>$houselist);
			return $this->result(0, 'success', $data);
			
		
	}
	
	
	public function doPageSearch()
	{
		global $_GPC, $_W;
		global $_GPC, $_W;
		//$siteurl = $this->GetSiteUrl();
		$condition = ' WHERE `uniacid` = :uniacid ';
		$params[':uniacid'] = $_W['uniacid'];
		if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `housename` LIKE :housename ';
				$params[':housename'] = '%' . trim($_GPC['keyword']) . '%';
			}
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') .$condition ;
	
		$list_newhouse = pdo_fetchall($sql, $params);
	
		if($list_newhouse){
			foreach($list_newhouse as $k=>$v)
				{
					$list_newhouse[$k]['thumb'] = tomedia($v['thumb']);
					$list_newhouse[$k]['type'] = 'newhousedetail';
					$list_newhouse[$k]['title'] = $v['housename']; 
				}
		}
		
		
		
		$condition = ' WHERE `uniacid` = :uniacid   AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0) ';
		
		if (!empty($_GPC['keyword'])) {
				$condition .= ' AND ( `title` LIKE :title  OR `housearea` LIKE :housearea ) ';
				$params2[':title'] = '%' . trim($_GPC['keyword']) . '%';
				$params2[':housearea'] = '%' . trim($_GPC['keyword']) . '%';
				$params2[':uniacid'] = $_W['uniacid'];
			}
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;
		
		$list_oldhouse = pdo_fetchall($sql, $params2);
		if($list_oldhouse){
			foreach($list_oldhouse as $k=>$v)
				{
					$list_oldhouse[$k]['thumb'] = tomedia($v['thumb']);
					$list_oldhouse[$k]['type'] = 'oldhousedetail';
				}
		}
		
		
		
		$condition = ' WHERE `uniacid` = :uniacid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		if (!empty($_GPC['keyword'])) {
				$condition .= ' AND ( `title` LIKE :title  OR `housearea` LIKE :housearea  ) ';
				$params3[':title'] = '%' . trim($_GPC['keyword']) . '%';
				$params3[':housearea'] = '%' . trim($_GPC['keyword']) . '%';
				$params3[':uniacid'] = $_W['uniacid'];
			}

		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') .$condition ;
	
		$list_lethouse = pdo_fetchall($sql, $params3);
		if($list_lethouse){
			foreach($list_lethouse as $k=>$v)
				{
					$list_lethouse[$k]['thumb'] = tomedia($v['thumb']);
					$list_lethouse[$k]['type'] = 'lethousedetail';
					
				}
		}
		
		$list = array_merge($list_newhouse,$list_oldhouse,$list_lethouse);
		//print_r($list);
		
		
		return $this->result(0, 'success', $list);
		
	}
	
	
	
	public function doPageGetinitinfo()
	{
		global $_GPC, $_W;
		
		//$sql = "SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ";

		
			$city = trim($_GPC['city']);
				
				$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

				if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}


		$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid AND cityid = :cityid ORDER BY sort DESC  ",array(":uniacid" => $_W['uniacid'],":cityid"=>$cityinfo['id']));


		foreach($arealist as $k=>$v)
		{
			$buildarealist = pdo_getall('weixinmao_house_buildarea',array('uniacid'=>$_W['uniacid'],'aid'=>$v['id']));

			$buildarea[$v['id']] = $buildarealist;
		}
		

		$housepricelist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_houseprice') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));

		$intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

		return $this->result(0, 'success', array('arealist'=>$arealist,'housepricelist'=>$housepricelist,'intro'=>$intro,'cityinfo'=>$cityinfo,'buildarea'=>$buildarea));
		
		
		
	}


	
	
	public function doPageGetinitoldinfo()
	{
		global $_GPC, $_W;
		
		//$sql = "SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ";

		$city = trim($_GPC['city']);
				
		$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

		if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}

		$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid AND cityid = :cityid ORDER BY sort DESC  ",array(":uniacid" => $_W['uniacid'],":cityid"=>$cityinfo['id']));

		foreach($arealist as $k=>$v)
		{
			$buildarealist = pdo_getall('weixinmao_house_buildarea',array('uniacid'=>$_W['uniacid'],'aid'=>$v['id']));

			$buildarea[$v['id']] = $buildarealist;
		}
		
		//$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') );
		$housepricelist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_oldhouseprice') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));

		$intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

		return $this->result(0, 'success', array('arealist'=>$arealist,'housepricelist'=>$housepricelist,'intro'=>$intro,'cityinfo'=>$cityinfo,'buildarea'=>$buildarea));
		
	}
	public function doPageGetinitletinfo()
	{
		global $_GPC, $_W;

			$city = trim($_GPC['city']);
				
		$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

		if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}

		$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND cityid =:cityid AND uniacid=:uniacid ORDER BY sort DESC  ",array(":uniacid" => $_W['uniacid'],":cityid"=>$cityinfo['id']));

		foreach($arealist as $k=>$v)
		{
			$buildarealist = pdo_getall('weixinmao_house_buildarea',array('uniacid'=>$_W['uniacid'],'aid'=>$v['id']));

			$buildarea[$v['id']] = $buildarealist;
		}

		$housepricelist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_lethouseprice') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));


		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));


		return $this->result(0, 'success', array('arealist'=>$arealist,'housepricelist'=>$housepricelist,'intro'=>$intro,'cityinfo'=>$cityinfo,'buildarea'=>$buildarea));
		
		
		
	}
	
	public function doPageGetbanner()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_adv') ."WHERE  enabled =1 AND weid=:weid ORDER BY displayorder ASC  ",array(":weid" => $_W['uniacid']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
	
	
	public function doPageIntro()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
			$list['logo'] = tomedia($list['logo']);
			//$list['description']=html_entity_decode($list['content']);
			$list['description']=$list['content'];

 			$list['content'] = trim(html_entity_decode(strip_tags($list['content'])),chr(0xc2).chr(0xa0));  
			
			
			return $this->result(0, 'success', $list);
		}
	
	
	public function doPageGetIndexList()
	{
		
		global $_GPC, $_W;
		$siteurl = $this->GetSiteUrl();
		
		/*新楼盘*/
		
		$condition = ' WHERE `uniacid` = :uniacid AND isrecommand = 1  ORDER BY sort DESC LIMIT 10 ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') .$condition ;
		
		$newhouselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE  `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($newhouselist)
		{
			foreach($newhouselist as $k=>$v)
				{
					$newhouselist[$k]['thumb'] = tomedia($v['thumb']);
					$newhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$newhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		
		/*二手房*/
		$condition = ' WHERE `uniacid` = :uniacid  AND isrecommand = 1  ORDER BY sort DESC LIMIT 10 ';
		$params = array(':uniacid' => $_W['uniacid']);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') .$condition ;

		$oldhouselist = pdo_fetchall($sql, $params);
		
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		if($arealist)
		{
			foreach($arealist as $k=>$v)
			{
					$areainfo[$v['id']] = $v['name'];			
			}
		}
	
		$housetypeinfo= array(1=>'住宅',2=>'别墅',3=>'公寓',4=>'商铺',5=>'写字楼',6=>'酒店',7=>'厂房');
		if($oldhouselist)
		{
			foreach($oldhouselist as $k=>$v)
				{
					$oldhouselist[$k]['thumb'] = $siteurl.$v['thumb'];
					$oldhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$oldhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
			
		return $this->result(0, 'success', array('newhouselist'=>$newhouselist,'oldhouselist'=>$oldhouselist));
	}
	
	public function doPageAbout()
	{
		global $_GPC, $_W;
	
		$list = pdo_fetch("SELECT * FROM " . tablename('fc_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
		//$list['content'] = html_entity_decode($list['content']);
		$list['content'] = $list['content'];

		return $this->result(0, 'success', $list);


	}
	
	public function doPageNav()
		{
			global $_GPC, $_W;
			
			//$siteurl = $this->GetSiteUrl();
			
			$list = pdo_fetchall("SELECT * FROM " . tablename('fc_category') ."WHERE weid=:weid AND isrecommand =1 AND parentid =0",array(":weid" => $_W['uniacid']));
			if($list)
			{
					foreach($list as $k=>$v)
					{
						$list[$k]['thumb'] = tomedia($v['thumb']);
						
					}
			}
			return $this->result(0, 'success', $list);
			
		}
	
	public function doPageGetlist()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			
			$pid = $_GPC['pid'];
			$list = pdo_fetchall("SELECT * FROM " . tablename('fc_content') ." WHERE uniacid=:uniacid AND pid=".$pid ,array(":uniacid" => $_W['uniacid']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
	
	public function doPageGetcase()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$list = pdo_fetchall("SELECT * FROM " . tablename('fc_case') ." WHERE uniacid=:uniacid AND isrecommand = 1 ORDER BY sort DESC LIMIT 4",array(":uniacid" => $_W['uniacid']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['yeartime'] = date('Y年m月',$v['createtime']);
					$list[$k]['daytime'] = date('d',$v['createtime']);
					$list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
		
		
	public function doPageGetcaselist()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$list = pdo_fetchall("SELECT * FROM " . tablename('fc_case') ." WHERE uniacid=:uniacid ORDER BY sort DESC, createtime DESC",array(":uniacid" => $_W['uniacid']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['yeartime'] = date('Y年m月',$v['createtime']);
					$list[$k]['daytime'] = date('d',$v['createtime']);
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
		
	public function doPageGetteam()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$list = pdo_fetchall("SELECT * FROM " . tablename('fc_team') ." WHERE uniacid=:uniacid AND isrecommand = 1 ORDER BY sort DESC ",array(":uniacid" => $_W['uniacid']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
	public function doPageGetteamlist()
		{
			global $_GPC, $_W;
			if($_W['attachurl']!=""){
			
				$picurl = $_W['attachurl'];
				
			}else{
				
				$picurl  = $_W['siteroot'].'attachment/';
			}
			$id = $_GPC['id'];
			
			$list = pdo_fetchall("SELECT * FROM " . tablename('fc_team') ." WHERE uniacid=:uniacid AND istype=".$id." ORDER BY sort DESC ,createtime DESC",array(":uniacid" => $_W['uniacid']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$list[$k]['thumb'] = $picurl.$v['thumb'];
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
	
	
	
	public function doPageGetsecondlist()
		{
			global $_GPC, $_W;
		

			$pid = $_GPC['pid'];

			if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;
			
			$category_info = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_category') ." WHERE  weid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
			if($category_info['parentid'] == 0)
			{
				$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE   uniacid=:weid AND pid=".$pid." ORDER BY createtime DESC ".$limit,array(":weid" => $_W['uniacid']));
			}else{
				
				$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE   uniacid=:weid AND sid=".$pid." ORDER BY createtime DESC ".$limit,array(":weid" => $_W['uniacid']));

				
				
			}
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			return $this->result(0, 'success', $list);
			
		}
		
	public function doPageGetmodel()
		{
			global $_GPC, $_W;
			
			$pid = $_GPC['pid'];
			$list = pdo_fetch("SELECT model FROM " . tablename('fc_category') ." WHERE weid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
			return $this->result(0, 'success', $list);
			
		}
		
	public function doPageGetcontent()
		{
			global $_GPC, $_W;
			
			$pid = $_GPC['pid'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('fc_content') ." WHERE  uniacid=:uniacid AND  pid=".$pid,array(":uniacid" => $_W['uniacid']));
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			//$list['content'] = html_entity_decode($list['content']);
				$list['content'] = $list['content'];

			//$list['content'] = htmlspecialchars($list['content']);
			
			return $this->result(0, 'success', $list);
			
		}
		
	public function doPageGetnewsdetail()
		{
			global $_GPC, $_W;
			
			$id = $_GPC['id'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
				$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
             $intro = pdo_fetch("SELECT maincolor,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 $list['intro'] = $intro;

			return $this->result(0, 'success', $list);
			
		}

	
		
		
	
		
		
	public function doPageGetpicdetail()
		{
			global $_GPC, $_W;
	
			$id = $_GPC['id'];
			$typeid = $_GPC['typeid'];
			if($typeid ==0)
					$table = 'weixinmao_house_house';
			else
					$table = 'weixinmao_house_case';
			$list = pdo_fetch("SELECT * FROM " . tablename($table) ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			if($list)
			{
					$houseinfo = pdo_fetch("SELECT housename FROM " . tablename('weixinmao_house_houseinfo') ." WHERE uniacid=:uniacid AND id=".$list['teamid'],array(":uniacid" => $_W['uniacid']));

			}
	
		//	$list['content'] = html_entity_decode($list['content']);

			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

					$data = array('list'=>$list ,'houseinfo'=>$houseinfo,'intro'=>$intro);
			return $this->result(0, 'success', $data);
			
		}
		
	
	public function doPageGetcasedetail()
		{
			global $_GPC, $_W;
			if($_W['attachurl']!=""){
			
				$picurl = $_W['attachurl'];
				
			}else{
				
				$picurl  = $_W['siteroot'].'attachment/';
			}
			$id = $_GPC['id'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('fc_case') ." WHERE  uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			$team = pdo_fetch("SELECT * FROM " . tablename('fc_team') ." WHERE  uniacid=:uniacid AND id=".$list['teamid'],array(":uniacid" => $_W['uniacid']));

			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['thumb'] = $picurl.$list['thumb'];
			$list['teamname'] = $team['title'];
			$list['logo'] = $_W['siteroot'].'attachment/'.$team['thumb'];
		//	$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];

			 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 $list['intro'] = $intro;
			return $this->result(0, 'success', $list);
			
		}
		
	public function doPageGetteamdetail()
		{
			global $_GPC, $_W;
			if($_W['attachurl']!=""){
			
				$picurl = $_W['attachurl'];
				
			}else{
				
				$picurl  = $_W['siteroot'].'attachment/';
			}
			$id = $_GPC['id'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('fc_team') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));

			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['thumb'] = $picurl.$list['thumb'];
		//	$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$case_list = pdo_fetchall("SELECT * FROM " . tablename('fc_case') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
			if($case_list)
			{
				foreach($case_list as $k=>$v)
				{
					$case_list[$k]['thumb'] = $picurl.$v['thumb'];
					
				}
			}
			
			$house_list = pdo_fetchall("SELECT * FROM " . tablename('fc_house') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
			if($house_list)
			{
				foreach($house_list as $k=>$v)
				{
					$house_list[$k]['thumb'] = $picurl.$v['thumb'];
					
				}
			}
			
			$data = array('list'=>$list,'case_list'=>$case_list,'house_list'=>$house_list);
			
			return $this->result(0, 'success', $data);
			
		}
		
	public function doPageGetarticle()
		{
			global $_GPC, $_W;
			if($_GPC['page']) 
			$page = $_GPC['page'];
		else 
			$page =1;
		$limit  = ' LIMIT 0,'.$page*10;

			$pid = $_GPC['pid'];
			$category_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_category') ." WHERE weid=:weid ORDER BY displayorder DESC ",array(":weid" => $_W['uniacid']));
			
			$content  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE  uniacid=:uniacid AND pid=".$category_list[0]['id']."  ORDER BY createtime DESC ".$limit,array(":uniacid" => $_W['uniacid']));
			if($content)
			{
				foreach($content as $k=>$v)
				{
					$content[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$content[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			$list = array('category'=>$category_list,'article'=>$content,'activeCategoryId'=>$category_list[0]['id'],'intro'=>$intro);
			return $this->result(0, 'success', $list);
			
		}
	
		public function doPageSavemessage()
		{
			global $_GPC, $_W;
			$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'uid' => $_GPC['uid'],
					'companyname' => $_GPC['companyname'],
					'createtime' => TIMESTAMP,
					'status'=>0
					);
	    $uid = $_GPC['uid'];
		$sql = 'SELECT id,name,tel FROM ' . tablename('weixinmao_house_message') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid';
		$masterinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));
		
		if($masterinfo)
		{
			$list = array('msg'=>'您已提交过申请信息','error'=>1);
			
		}else{
			pdo_insert('weixinmao_house_message', $data);
			$id = pdo_insertid();
			if($id)
			{
				$list = array('msg'=>'提交成功','error'=>0);
			
			}else{
				
				$list = array('msg'=>'提交失败','error'=>1);
			}
		}
		return $this->result(0, 'success', $list);
			
		}


	public function doPageSavecomplain()
		{
			global $_GPC, $_W;
			$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'uid' => $_GPC['uid'],
					'content' => $_GPC['content'],
					'agentid' => $_GPC['id'],
					'createtime' => TIMESTAMP
					);
	 
			pdo_insert('weixinmao_house_complain', $data);
			$id = pdo_insertid();
			if($id)
			{
				$list = array('msg'=>'提交成功','error'=>0);
			
			}else{
				
				$list = array('msg'=>'提交失败','error'=>1);
			}
	
		return $this->result(0, 'success', $list);
			
		}




	public function doPageUpdateuserinfo()
		{
			global $_GPC, $_W;
			$uid = $_GPC['uid'];
			$fans = pdo_get('mc_mapping_fans',array('uid'=>$uid));
			$openid = $fans['openid'];
			$data = array(
					'uniacid' => $_W['uniacid'],
					'wechaname' => $_GPC['nickname'],
					'uid' => $_GPC['uid'],
					'avatarUrl' => $_GPC['avatarUrl'],
					'openid'=>$openid,
					'createtime' => TIMESTAMP
					);
	 		
	 		$userinfo = pdo_get('weixinmao_house_userinfo',array('uid'=>$uid));
		
	 		if($userinfo)
	 		{

				pdo_update('weixinmao_house_userinfo', $data, array('uid' => $uid));
			}else{
				$userinfo['score'] =0;
				pdo_insert('weixinmao_house_userinfo', $data);
				$id = pdo_insertid();

			}
			
			if($id)
			{
				$list = array('msg'=>'提交成功','error'=>0,'userinfo'=>$userinfo);
			
			}else{
				
				$list = array('msg'=>'提交失败','error'=>1);
			}
	
		return $this->result(0, 'success', $list);
			
		}




	public function doPageSavecomment()
		{
			global $_GPC, $_W;
			$houseplan = pdo_get('weixinmao_house_housemsg',array('id'=>$_GPC['houseid']));
			$data = array(
					'uniacid' => $_W['uniacid'],
					'houseid' => $_GPC['houseid'],
					'type' => $_GPC['type'],
					'uid' => $_GPC['uid'],
					'content' => $_GPC['content'],
					'score' => $_GPC['score'],
					'createtime' => TIMESTAMP,
					'pid'=>$_GPC['houseid'],
					'status'=>0
					);
	    $uid = $_GPC['uid'];
	    $houseid = $_GPC['houseid'];
		$sql = 'SELECT id FROM ' . tablename('weixinmao_house_comment') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid AND `houseid` =:houseid ';
		$masterinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid,':houseid'=>$houseid));
		
		if($masterinfo)
		{
			$list = array('msg'=>'您已评论过','error'=>1);
			
		}else{
			pdo_insert('weixinmao_house_comment', $data);
			$id = pdo_insertid();
			if($id)
			{
				$list = array('msg'=>'评论成功','error'=>0);
			
			}else{
				
				$list = array('msg'=>'提交失败','error'=>1);
			}
		}
		return $this->result(0, 'success', $list);
			
		}



	  public function doPageGethousemsg()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$housemsg = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_housemsg') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			
			$houseplan = array();
			if($housemsg['type'] == 'newhouse')
			{

				$houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$housemsg['houseid']));
				$houseplan = pdo_get('weixinmao_house_house',array('id'=>$housemsg['toplistid']));

			}elseif($housemsg['type'] == 'lethouse'){


				$houseinfo = pdo_get('weixinmao_house_lethouseinfo',array('id'=>$housemsg['houseid']));
				$houseinfo['housename'] = $houseinfo['title'];
				//$houseplan = pdo_get('weixinmao_house_house',array('id'=>$housemsg['toplistid']));
				//
				$houseplan = array('title'=>'二手房','money'=>$houseinfo['money'],'dmoney'=>$houseinfo['price']);

			}


            $intro = pdo_fetch("SELECT maincolor,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

         	$list = array('housemsg'=>$housemsg,'newhouse'=>$houseinfo,'houseplan'=>$houseplan);

			$data = array('list'=>$list,'intro'=>$intro);
			
			
			return $this->result(0, 'success', $data);
			
		
	}






   		public function doPageSavehousemessage()
		{
			global $_GPC, $_W;
			$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'uid' => $_GPC['uid'],
					'houseid' => $_GPC['houseid'],
					'toplistid' => $_GPC['toplistid'],
					'createtime' => TIMESTAMP,
					'status'=>0,
					'type'=>$_GPC['type'],
					'content'=>$_GPC['content']
					);
	    $uid = $_GPC['uid'];
	    $houseid = $_GPC['houseid'];
		$sql = 'SELECT id,name,tel FROM ' . tablename('weixinmao_house_housemsg') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid AND `houseid` = :houseid';
		$masterinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid,':houseid'=>$houseid));
		
		if($masterinfo)
		{
			$list = array('msg'=>'您已提交过信息','error'=>1);
			
		}else{
			pdo_insert('weixinmao_house_housemsg', $data);
			$id = pdo_insertid();
			if($id)
			{
				$list = array('msg'=>'提交成功','error'=>0);
			
			}else{
				
				$list = array('msg'=>'提交失败','error'=>1);
			}
		}
		return $this->result(0, 'success', $list);
			
		}





   	public function doPageSavecoupon()
		{
			global $_GPC, $_W;

			$tid = $_GPC['tid'];
			$fid = 0 ;
			if($tid>0)
				{

						$sql = 'SELECT tid FROM ' . tablename('weixinmao_house_coupon') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid';
						$tj_coupon = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$tid));	
						if($tj_coupon)
						{
							$fid = $tj_coupon['tid'];
						}

				}
			$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'uid' => $_GPC['uid'],
					'tid' => $tid,
					'fid'=>$fid,
					'weixin' => $_GPC['weixin'],
					'card' => $_GPC['card'],
              'bank' => $_GPC['bank'],
              'bankaddress' => $_GPC['bankaddress'],
					'createtime' => TIMESTAMP,
					'status'=>0
					);
	    $uid = $_GPC['uid'];
		$sql = 'SELECT id,name,tel FROM ' . tablename('weixinmao_house_coupon') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid';
		$couponinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));
		
		if($couponinfo)
		{
			$list = array('msg'=>'您已提交过申请信息','error'=>1);
			
		}else{
			pdo_insert('weixinmao_house_coupon', $data);

			$id = pdo_insertid();
			if($id)
			{
				
				


				$userinfodata = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$uid,
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'avatarUrl'=>$_GPC['avatarUrl'],
					'createtime' => TIMESTAMP
					);
		
				$sql = 'SELECT id FROM ' . tablename('weixinmao_house_userinfo') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid';
				$userinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));	
								
				if($userinfo)
					{
						  pdo_update('weixinmao_house_userinfo', array('uniacid' => $_W['uniacid'],
								'name' => $_GPC['name'],
								'tel' => $_GPC['tel'],
								'avatarUrl'=>$_GPC['avatarUrl'],
								), array('uid' => $uid));

						
					}else{

				    pdo_insert('weixinmao_house_userinfo', $userinfodata);
					$id = pdo_insertid();
					}



				$list = array('msg'=>'提交成功','error'=>0);
			
			}else{
				
				$list = array('msg'=>'提交失败','error'=>1);
			}
		}
		return $this->result(0, 'success', $list);
			
		}

	
	public function doPageSendmsg()
	{
		global $_GPC, $_W;
		
		
		
		$appid = 'wx6122ff24a5ebe0b1';
        $appsecret = '89a817edc3dd527ea2e41848f8bd5cff';
        $access_token = '';

       // $openid = isset($_POST['openid']) ? trim($_POST['openid']) : ''; //小程序的openid
	   $openid='oAoMR0Y9PplkJdqXzYOfbGRPtobE';
        if(empty($openid)){
            $ret['errMsg'] = '却少参数openid';
            exit(json_encode($ret));
        }
        //表单提交场景下，为 submit 事件带上的 formId；支付场景下，为本次支付的 prepay_id
        $formid = isset($_GPC['form_id']) ? trim($_GPC['form_id']) : '';
        if(empty($formid)){
            $ret['errMsg'] = '却少参数form_id';
            exit(json_encode($ret));
        }

        //消息模板id
        $temp_id = 'NsxoDlp0qvnqkz7tF3RRLDRZUCbthMy-brxB1DsebBk';
	
        //获取access_token, 做缓存，expires_in：7200
       $this-> generate_token($access_token, $appid, $appsecret);

        $data['touser'] = $openid;
        $data['template_id'] = $temp_id;
        $data['page'] =  'pages/index/index'; //该字段不填则模板无跳转
        $data['form_id'] = $formid;
        $data['data'] = array(
            'keyword1' => array('value' => 'areanameb'),
            'keyword2' => array('value' => 'dd-dd'),
            'keyword3' => array('value' => 'ccc'),
        );
        $data['emphasis_keyword'] = 'keyword5.DATA'; //模板需要放大的关键词，不填则默认无放大
	//	echo  $access_token;
        $send_url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        $str =$this->request($send_url, 'post', $data);
        $json = json_decode($this->request($send_url, 'post', $data));
        if(!$json){
            $ret['errMsg'] = $str;
            exit(json_encode($ret));
        }else if(isset($json->errcode) && $json->errcode){
            $ret['errMsg'] = $json->errcode.', '.$json->errmsg;
            exit(json_encode($ret));
        }
        $ret['resultCode'] = 0;
        exit(json_encode($ret));
       // break;
		

		
		$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'companyname' => $_GPC['companyname'],
					'createtime' => TIMESTAMP
					);
		
	    pdo_insert('fc_message', $data);
		$id = pdo_insertid();
		if($is)
		{
			$list = array('msg'=>'提交成功','error'=>0);
		
		}else{
			
			$list = array('msg'=>'提交失败','error'=>1);
		}
		return $this->result(0, 'success', $list);
	}
	
	
	function generate_token(&$access_token, $appid, $appsecret){
    $token_file = '/tmp/token';
    $general_token = true;
    if(file_exists($token_file) && ($info = json_decode(file_get_contents($token_file)))){
        if(time() < $info->create_time + $info->expires_in - 200){
            $general_token = false;
            $access_token = $info->access_token;
        }
    }
    if($general_token){
        $this->new_access_token($access_token, $token_file, $appid, $appsecret);
    }
}

function new_access_token(&$access_token, $token_file, $appid, $appsecret){
    $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    $str = file_get_contents($token_url);
    $json = json_decode($str);
    if(isset($json->access_token)){
        $access_token = $json->access_token;
        file_put_contents($token_file, json_encode(array('access_token' => $access_token, 'expires_in' => $json->expires_in, 'create_time' => time())));
    }else{
        file_put_contents('/tmp/error', date('Y-m-d H:i:s').'-Get Access Token Error: '.print_r($json, 1).PHP_EOL, FILE_APPEND);
    }
}

function request($url, $method, array $data, $timeout = 30) {
	error_reporting(0);
    try {
        $ch = curl_init();
        /*支持SSL 不验证CA根验证*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        /*重定向跟随*/
        if (ini_get('open_basedir') == '' && !ini_get('safe_mode')) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        //设置 CURLINFO_HEADER_OUT 选项之后 curl_getinfo 函数返回的数组将包含 cURL
        //请求的 header 信息。而要看到回应的 header 信息可以在 curl_setopt 中设置
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_URL, $url);
               
        //CURLOPT_HEADER 选项为 true
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);

        //fail the request if the HTTP code returned is equal to or larger than 400
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        $header = array("Content-Type:application/json;charset=utf-8;", "Connection: keep-alive;");
        switch (strtolower($method)) {
            case "post":
            case "put":
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
            case "delete":
                curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($data));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case "get":
                curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($data));
                break;
            case "new_get":
                curl_setopt($ch, CURLOPT_URL, $url."?para=".urlencode(json_encode($data)));
                break;
            default:
                throw new Exception('不支持的HTTP方式');
                break;
        }
        $result = curl_exec($ch);
        if (curl_errno($ch) > 0) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    } catch (Exception $e) {
        return "CURL EXCEPTION: ".$e->getMessage();
    }
}
	
	public function doPageGetactivelist()
		{
			global $_GPC, $_W;
		//	$siteurl = $this->GetSiteUrl();
		//	
			$city = trim($_GPC['city']);
				
				$cityinfo = pdo_get('weixinmao_house_city',array('name'=>$city,'uniacid'=>$_W['uniacid']));

				if(!$cityinfo)
				{

					$cityinfo = pdo_get('weixinmao_house_city',array('uniacid'=>$_W['uniacid'],'ison'=>1));

				}

	//	print_r($cityinfo);


			
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_active') ." WHERE  uniacid=:weid AND `cityid` = :cityid ORDER BY sort ASC",array(":weid" => $_W['uniacid'],':cityid'=>$cityinfo['id']));
			if($list)
			{
				foreach($list as $k=>$v)
				{
					$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$list[$k]['thumb'] = tomedia($v['thumb']);
					$active_list = pdo_fetchall("SELECT id FROM " . tablename('weixinmao_house_baoming') ." WHERE uniacid=:uniacid  AND aid=:aid",array(":uniacid" => $_W['uniacid'],':aid'=>$v['id']));
					$totalnum = count($active_list);
					$list[$k]['totalnum'] = $totalnum;
					
					
				}
			}

			   $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			  // $list['intro'] = $intro;
			   $data = array('list'=>$list,'intro'=>$intro,'cityinfo'=>$cityinfo);
			return $this->result(0, 'success', $data);
			
		}
	
	
public function doPageGetactivedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_active') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));

			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE uniacid=:uniacid AND id=".$activeinfo['pid'],array(":uniacid" => $_W['uniacid']));
			//$list['content'] = html_entity_decode($list['content']);
			$list['content'] = $list['content'];
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = $list['specialliststr'] = explode(',',$list['productspecial']);
			$list['specialliststr'] = implode('、',$list['specialliststr']);
			$salestatus=array(1=>'在售',2=>'待售',3=>'售完',4=>'打折');
			$list['salestatus_str'] = $salestatus[$list['housestatus']];
			
			
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		
			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] =tomedia($p);
						}
				}
			
			
			
			$case_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_case') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
			if($case_list)
			{
				foreach($case_list as $k=>$v)
				{
					$case_list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			
			$house_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_house') ." WHERE uniacid=:uniacid AND teamid=".$list['id'],array(":uniacid" => $_W['uniacid']));
			if($house_list)
			{
				foreach($house_list as $k=>$v)
				{
					$house_list[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}

			$active_list = pdo_fetchall("SELECT id FROM " . tablename('weixinmao_house_baoming') ." WHERE uniacid=:uniacid AND pid=:pid AND aid=:aid",array(":uniacid" => $_W['uniacid'],':pid'=>$list['id'],':aid'=>$id));
		
			$totalnum = count($active_list);

			 $intro = pdo_fetch("SELECT maincolor,name FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			$data = array('list'=>$list,'housepic'=>$case_list,'houseplan'=>$house_list,'piclist'=>$piclist,'activeinfo'=>$activeinfo,'totalnum'=>$totalnum,'intro'=>$intro);
			


			return $this->result(0, 'success', $data);
			
		
	}
	
	
 public function doPageSavebaoming()
	{
		global $_GPC, $_W;
		
		$data = array(
					'uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'aid' => $_GPC['aid'],
					'pid' => $_GPC['pid'],
					'createtime' => TIMESTAMP
					);
		
	    pdo_insert('weixinmao_house_baoming', $data);
		$id = pdo_insertid();
		
		if($id)
		{

			$active_list = pdo_fetchall("SELECT id FROM " . tablename('weixinmao_house_baoming') ." WHERE uniacid=:uniacid AND pid=:pid AND aid=:aid",array(":uniacid" => $_W['uniacid'],':pid'=>$_GPC['pid'],':aid'=>$_GPC['aid']));
			$totalnum = count($active_list);
			
			$list = array('msg'=>'提交成功','error'=>0,'totalnum'=>$totalnum);
		
		}else{
			
			$list = array('msg'=>'提交失败','error'=>1);
		}
		return $this->result(0, 'success', $list);
	}
	
	
	public function doPageSaveletpubinfo()
	{
		global $_GPC, $_W;
		$uploadimagelist_str = $_GPC['uploadimagelist_str'];
		

		$ischeck = $this->IsCheck();
		if($ischeck == 1)
				{

					$enabled = 0;
				}else{

					$enabled = 1;
				}		

		$imagelistarray = explode('@',$uploadimagelist_str);
		foreach($imagelistarray as $k=>$v)
				{
					if($v!="")
					{
						$imagelist[]  = $v;
					}
				}
		$uid = $_GPC['uid'];
		$sql = 'SELECT id,name,tel FROM ' . tablename('weixinmao_house_agent') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid';
		
		$masterinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));
		$data = array(
					'uniacid' => $_W['uniacid'],
					'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'money'=>$_GPC['money'],
					'roomtype'=>$_GPC['roomtype'],
					'housetype'=>$_GPC['housetype'],
					'payway'=>$_GPC['payway'],
					'roomid'=>$_GPC['roomid'],
					'letway'=>$_GPC['letway'],
					'special'=>$_GPC['special'],
					'houselabel'=>$_GPC['houselabel'],
					'houseareaid'=>$_GPC['houseareaid'],
					'bid'=>$_GPC['bid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>$_GPC['special'],
					'lng'=>$_GPC['longitude'],
					'lat'=>$_GPC['latitude'],
					'thumb'=>$imagelist[0],
					'thumb_url'=>serialize($imagelist),
					'ispub'=>1,
					'source'=>1,
					'ischeck'=>$enabled,
					'uid'=>$_GPC['uid'],
					'name'=>$masterinfo['name'],
					'tel'=>$masterinfo['tel'],
					'content'=>$_GPC['content'],
                   'createtime' => TIMESTAMP,
					);
					
		//print_r($data);
		
	    pdo_insert('weixinmao_house_lethouseinfo', $data);
		$id = pdo_insertid();
	    if($id)
		{

			
			$list = array('msg'=>'发布成功','error'=>0,'totalnum'=>$totalnum);
		
		}else{
			
			$list = array('msg'=>'发布失败','error'=>1);
		}
		return $this->result(0, 'success', $list);
		
	}
	


public function doPageSavesaleinfo()
	{
		global $_GPC, $_W;
		$uploadimagelist_str = $_GPC['uploadimagelist_str'];
		$imagelist = explode('@',$uploadimagelist_str);
		$uid = $_GPC['uid'];
		$ischeck = $this->IsCheck2();
		if($ischeck == 1)
				{

					$ischeck = 0;
				}else{

					$ischeck = 1;
				}

		if(intval($_GPC['toplistid'])>0)
				{
					$toplistinfo = pdo_fetch("SELECT days  FROM " . tablename('weixinmao_house_toplist') . " WHERE id = :id", array(':id' => $_GPC['toplistid']));
					$endtime = $toplistinfo['days'] * 60*60*24+time();

				}else{
					$endtime = 0 ;

				}
		$data = array(
					'uniacid' => $_W['uniacid'],
					'content'=>$_GPC['content'],
					'special'=>$_GPC['special'],
					'thumb_url'=>serialize($imagelist),
					'ispub'=>1,
					'ischeck'=>$ischeck,
					'type'=>$_GPC['saletype'],
					'uid'=>$_GPC['uid'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'houseareaid'=>$_GPC['houseareaid'],
					'cityid'=>$_GPC['cityid'],
					'toplistid'=>$_GPC['toplistid'],
                   'createtime' => TIMESTAMP,
                   'endtime'=>$endtime
					);
		
	    pdo_insert('weixinmao_house_saleinfo', $data);
		$saleinfoid = pdo_insertid();
	    if($saleinfoid)
		{
			
				$userinfodata = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$uid,
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'avatarUrl'=>$_GPC['avatarUrl'],
					'createtime' => TIMESTAMP
					);
		
		$userinfo = pdo_fetch("SELECT id  FROM " . tablename('weixinmao_house_userinfo') . " WHERE uid = :id", array(':id' => $uid));
		if($userinfo)
		{
			  pdo_update('weixinmao_house_userinfo', array('uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'avatarUrl'=>$_GPC['avatarUrl'],
					), array('uid' => $uid));

			
		}else{

	    pdo_insert('weixinmao_house_userinfo', $userinfodata);
		$id = pdo_insertid();
		}
		
			

			
			$list = array('msg'=>'发布成功','error'=>0,'totalnum'=>$totalnum,'saleinfoid'=>$saleinfoid);
		
		}else{
			
			$list = array('msg'=>'发布失败','error'=>1);
		}
		return $this->result(0, 'success', $list);
		
	}
	
	


	public function doPageSavehouse(){



			global $_GPC, $_W;

			$id = $_GPC['pid'];

			$uid = $_GPC['uid'];

			$housetype = $_GPC['housetype'];

			$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uid=:uid AND housetype=:housetype AND uniacid=:uniacid AND pid=".$id,array(":uid"=>$uid,":housetype"=>$housetype,":uniacid" => $_W['uniacid']));

			if(!$saveinfo)

				{

					$data['pid'] = $id;

					$data['uid'] = $uid;

					$data['housetype'] = $housetype;

					$data = array(

                    'uniacid' => $_W['uniacid'],

					'pid'=>$id,

					'uid'=>$uid,

					'housetype'=>$housetype,

                    'createtime' => TIMESTAMP,

                	);

					pdo_insert('weixinmao_house_save', $data);

                    $id = pdo_insertid();

                    $issave = 1;

				}else{



					pdo_delete('weixinmao_house_save', array('id' => $saveinfo['id']));



					$issave = 0 ;





				}

		$list = array('issave'=>$issave);



       return $this->result(0, 'success', $list);

	}
	
		
	public function doPageSavepubinfo()
	{
		global $_GPC, $_W;
		$uploadimagelist_str = $_GPC['uploadimagelist_str'];
		//$imagelist = explode('@',$uploadimagelist_str);


		$ischeck = $this->IsCheck();
		if($ischeck == 1)
				{

					$enabled = 0;
				}else{

					$enabled = 1;
				}

		$imagelistarray = explode('@',$uploadimagelist_str);
		foreach($imagelistarray as $k=>$v)
				{
					if($v!="")
					{
						$imagelist[]  = $v;
					}
				}

		$uid = $_GPC['uid'];
		$sql = 'SELECT id,name,tel FROM ' . tablename('weixinmao_house_agent') . ' WHERE `uniacid` = :uniacid AND `uid` =:uid';
		$masterinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$uid));
		$data = array(
					'uniacid' => $_W['uniacid'],
					'cityid'=>$_GPC['cityid'],
					'title'=>$_GPC['title'],
					'saleprice'=>$_GPC['saleprice'],
					'perprice'=>$_GPC['perprice'],
					'housestyle'=>$_GPC['housestyle'],
					'housetype'=>$_GPC['housetype'],
					'houseareaid'=>$_GPC['houseareaid'],
					'area'=>$_GPC['area'],
					'floor'=>$_GPC['floor'],
					'direction'=>$_GPC['direction'],
					'decorate'=>$_GPC['decorate'],
					'year'=>$_GPC['year'],
					'housearea'=>$_GPC['housearea'],
					'address'=>$_GPC['address'],
					'special'=>$_GPC['special'],
					'lng'=>$_GPC['longitude'],
					'lat'=>$_GPC['latitude'],
					'thumb'=>$imagelist[0],
					'thumb_url'=>serialize($imagelist),
					'ispub'=>1,
					'source'=>1,
					'ischeck'=>$enabled,
					'uid'=>$_GPC['uid'],
					'bid'=>$_GPC['bid'],
					'name'=>$masterinfo['name'],
					'tel'=>$masterinfo['tel'],
					'content'=>$_GPC['content'],
                   'createtime' => TIMESTAMP,
					);
		
	    pdo_insert('weixinmao_house_oldhouseinfo', $data);
		$id = pdo_insertid();
	    if($id)
		{

			
			$list = array('msg'=>'发布成功','error'=>0,'totalnum'=>$totalnum);
		
		}else{
			
			$list = array('msg'=>'发布失败','error'=>1);
		}
		return $this->result(0, 'success', $list);
		
	}
	
	
	
	
	
	
	
	
	 public function doPageUpload()
	 {
		 
		 global $_GPC, $_W;
		 
	
		 //$filename =str_replace( array('attachment; filename=', '"',' '),'',$response['headers']['Content-disposition']);
		//$filename = 'images/'.$_W['uniacid'].'/diamondvote/'.date('Y/m/').$filename;
			load()->func('file');
			
		$log = json_encode($_FILES);
	
		$res = file_upload($_FILES['file']);
			$data = array(
					'weid' => $_W['uniacid'],
					'content'=>json_encode($res)
					);
		
	   // pdo_insert('weixinmao_house_log', $data);
		
			//file_write($filename, $response['content']);
			//file_image_thumb(ATTACHMENT_ROOT.$filename,ATTACHMENT_ROOT.$filename,$media['width']);
		 return $this->result(0, 'success', $res);
		
	 }
	 
	 
	/*以下为会员中心功能*/
	 public function doPageSaveuserinfo()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
	
		if(!$uid || $uid <=0 )
		{
			return $this->result(1, '用户未授权');
		}
		$data = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$uid,
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'createtime' => TIMESTAMP
					);
		
		$userinfo = pdo_fetch("SELECT id  FROM " . tablename('weixinmao_house_userinfo') . " WHERE uid = :id", array(':id' => $uid));
		if($userinfo)
		{
			  pdo_update('weixinmao_house_userinfo', array('uniacid' => $_W['uniacid'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					), array('uid' => $uid));

			
		}else{

	    pdo_insert('weixinmao_house_userinfo', $data);
		$id = pdo_insertid();
		}
		
		
	     $list = array('msg'=>'提交成功','error'=>0);
		
		
		return $this->result(0, 'success', $list);
	}
	
	
	

	/*以下为会员中心功能*/
	 public function doPageSavefxmessage()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$tid =  $_GPC['tid'];
		$pid = $_GPC['pid'];
		if(!$uid || $uid <=0 )
		{
			return $this->result(1, '用户未授权');
		}
  		$fxuid1 = 0 ;
  		$fxuid2 = 0 ;
        $coupon = pdo_get('weixinmao_house_coupon',array('uid'=>$tid,'uniacid'=> $_W['uniacid']));
        if($coupon)
        {	
      
        			$fxuid1 = $coupon['tid'];//推荐人的推荐人
        			$fxuid2 = $coupon['fid'];//推荐人的推荐人推荐人

        		

        }

		$data = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$uid,
					'tid'=>$tid,
					'fxuid1'=>$fxuid1,
					'fxuid2'=>$fxuid2,
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'pid' => $pid,
					'fxmoney' => $_GPC['fxmoney'],
					'status'=>0,
					'type'=>$_GPC['type'],
					'createtime' => TIMESTAMP
					);


		
		$userinfo = pdo_fetch("SELECT id  FROM " . tablename('weixinmao_house_fxmessage') . " WHERE uid = :uid AND pid = :pid", array(':uid' => $uid,':pid'=>$pid));
		if($userinfo)
		{
			   $list = array('msg'=>'您已提交过信息','error'=>1);
			
		}else{

	    pdo_insert('weixinmao_house_fxmessage', $data);

		$id = pdo_insertid();
		
		if($id)
			{

				//处理分销啦
				$this->dealfxrecord($id);
				
			}
		    $list = array('msg'=>'提交成功','error'=>0);
			
		}
		
		
	 
		
		
		return $this->result(0, 'success', $list);
	}


	public function dealfxrecord($orderid)
		{
		global $_GPC, $_W;
			$orderid = $orderid;
			$fxmessage = pdo_get('weixinmao_house_fxmessage',array('id'=>$orderid));

			$intro = pdo_get('weixinmao_house_intro',array('uniacid'=>$_W['uniacid']));

			$tid = $fxmessage['tid'];
			$coupon = pdo_get('weixinmao_house_coupon',array('uniacid'=>$_W['uniacid'],'uid'=>$tid));

			$fxuid1 = $fxmessage['fxuid1'];
			$fxuid2 = $fxmessage['fxuid2'];


			if($fxuid1==0&&$fxuid2 == 0)
				{
					$content = '推广客户';
					$data = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$tid,
					'orderid' => $orderid,
					'money' => $fxmessage['fxmoney'],
					'status'=>0,
					'content'=>$content,
					'createtime' => TIMESTAMP
					);


					pdo_insert('weixinmao_house_fxrecord', $data);

					$dmoney =  $fxmessage['fxmoney']+$coupon['dmoney'];

				    pdo_update('weixinmao_house_coupon', array('dmoney'=>$dmoney), array('uid' => $tid));



				}elseif($fxuid2>0){
					$content = '推广客户';
					$content1 ='线下一级分销'.$coupon['name'].'推广用户' ;
					$content2 ='线下二级分销'.$coupon['name'].'推广用户' ;
					$money1 =  $fxmessage['fxmoney']*$intro['rate1'];
                    $money2 =  $fxmessage['fxmoney']*$intro['rate2'];
                  
                    $money = $fxmessage['fxmoney'] - $money1-$money2;
					$data = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$tid,
					'orderid' => $orderid,
					'money' => $money,
					'content'=>$content,
					'status'=>0,
					'createtime' => TIMESTAMP
					);
			    
					$data1 = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$fxuid1,
					'orderid' => $orderid,
					'money' => $money1,
					'status'=>0,
						'content'=>$content1,
					'createtime' => TIMESTAMP
					);
                   
                   $data2 = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$fxuid2,
					'orderid' => $orderid,
					'money' => $money2,
					'status'=>0,
					'content'=>$content2,
					'createtime' => TIMESTAMP
					);

                  	//pdo_insert('weixinmao_house_fxrecord', $data);
                  	pdo_insert('weixinmao_house_fxrecord', $data1);
                  	pdo_insert('weixinmao_house_fxrecord', $data2);


                  	$dmoney =  $money+$coupon['dmoney'];

				    pdo_update('weixinmao_house_coupon', array('dmoney'=>$dmoney), array('uid' => $tid));
				    
				    ////////
				    
				    $coupon1 = 	 pdo_get('weixinmao_house_coupon',array('uniacid'=>$_W['uniacid'],'uid'=>$fxuid1));
				    $dmoney1 =  $money1 + $coupon1['dmoney'];
				    pdo_update('weixinmao_house_coupon', array('dmoney'=>$dmoney1), array('uid' => $fxuid1));

				    $coupon2 = 	 pdo_get('weixinmao_house_coupon',array('uniacid'=>$_W['uniacid'],'uid'=>$fxuid2));
				    $dmoney2 =  $money2 + $coupon2['dmoney'];
				    pdo_update('weixinmao_house_coupon', array('dmoney'=>$dmoney2), array('uid' => $fxuid2));




				}else{

				$content = '推广客户';
					$content1 ='线下一级分销'.$coupon['name'].'推广用户' ;
				  $money1 =  $fxmessage['fxmoney']*$intro['rate1'];
                  $money = $fxmessage['fxmoney'] - $money1;
                  $data = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$tid,
					'orderid' => $orderid,
					'money' => $money,
					'content'=>$content,
					'status'=>0,
					'createtime' => TIMESTAMP
					);

					$data1 = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$fxuid1,
					'orderid' => $orderid,
					'money' => $money1,
					'content'=>$content1,
					'status'=>0,
					'createtime' => TIMESTAMP
					);
					pdo_insert('weixinmao_house_fxrecord', $data);
                  	pdo_insert('weixinmao_house_fxrecord', $data1);



                  	$dmoney =  $money+$coupon['dmoney'];

				    pdo_update('weixinmao_house_coupon', array('dmoney'=>$dmoney), array('uid' => $tid));
				    
				    ////////
				    
				    $coupon1 = 	 pdo_get('weixinmao_house_coupon',array('uniacid'=>$_W['uniacid'],'uid'=>$fxuid1));
				    $dmoney1 =  $money1+$coupon1['dmoney'];
				    pdo_update('weixinmao_house_coupon', array('dmoney'=>$dmoney1), array('uid' => $fxuid1));

				}




		}
		

	public function doPageCheckuserinfo()
	{
		global $_GPC, $_W;
		$sessionid = $_GPC['sessionid'];
		$uid = $_GPC['uid'];
		if($sessionid !=$_W['session_id'])
		{
			//return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

	
		
		
		if($uid >0)
		{
			$fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
        
          
			if(!$fans){
				
				//return $this->result(1, '用户未授权');
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			}else{
				$userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
				if($userinfo)
				{
					//return $this->result(3, '用户已绑定');
					return $this->result(0, 'success',  array('msg'=>'用户已绑定','error'=>2));
					
				}else{
					
					return $this->result(0, 'success',  array('msg'=>'用户未绑定','error'=>0));
				}
				
				
			}
			
		}else{
			
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));


		}
		
		
	}
	
	
	
	public function doPageGetuserinfo(){
		
		global $_GPC, $_W;
		$sessionid = $_GPC['sessionid'];
		$uid = $_GPC['uid'];
		
		
		$userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($userinfo)
				{
					//return $this->result(3, '用户已绑定');
					return $this->result(0, 'success',  $userinfo);
					
				}else{
					
				
				}
		
		
	}
	

public function doPageMyteamlist()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
        
        $condition = ' WHERE c.uniacid = :uniacid AND  ';
        $params = array(':uniacid' => $_W['uniacid'],':uid'=>$uid);



		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition .= " c.tid = :uid  ";
		elseif($ordertype == 2)
			$condition .= " c.fid = :uid  ";


		
	 // $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_order') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		
		$sqllist = 'SELECT  u.name as name,u.tel as tel ,c.createtime as createtime, u.avatarUrl as avatarUrl   ';
		
			
		$sql = " FROM " . tablename('weixinmao_house_coupon') . " AS c ";
			
		$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_userinfo') . " as u ON u.uid = c.uid ";
			
	//	$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_active') . " as a ON a.id = b.aid ";
			
		$list = pdo_fetchall($sqllist.$sql.$condition, $params);


		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
				
				
			}

 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

 $data = array('list'=>$list , 'intro'=>$intro);
		
		return $this->result(0, 'success', $data);
		
	}





	
		public function doPageMyorderlist()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition = " paid = 0 AND status =0 AND ";
		elseif($ordertype == 2)
			$condition = " paid = 1 AND status =0 AND ";
		elseif($ordertype == 3)
			$condition = " paid = 1 AND status =1 AND ";
		elseif($ordertype == 4)
			$condition = " status =-1 AND ";
			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_order') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
				if($v['paid']==0){
					if($v['status'] ==-1)
						$list[$k]['statusStr'] = '已取消';
					else
						$list[$k]['statusStr'] = '未支付';
					
				}else{
					if($v['status'] ==0)
					{
						$list[$k]['statusStr'] = '已付款待完成';
					}elseif($v['status']==1){
						$list[$k]['statusStr'] = '已完成';
						
					}
					
				}
				
			}
		
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 		//$list['intro'] = $intro;
		$data = array('list'=>$list ,'intro'=>$intro);


		return $this->result(0, 'success', $data);
		
	}
	public function doPageMyguestmsg()
		{
         global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$ordertype = $_GPC['ordertype'];
		if($ordertype ==1)
			{
				$housetype = 'oldhouse';
				$db= 'weixinmao_house_oldhouseinfo';

			}else{

				$housetype = 'lethouse';
				$db= 'weixinmao_house_lethouseinfo';
			}
		$agentinfo = pdo_get('weixinmao_house_agent',array('uniacid'=>$_W['uniacid'],'uid'=>$uid));
		$tel = $agentinfo['tel'];
		$condition = ' WHERE h.uniacid = :uniacid AND h.tel = :tel AND m.type = :housetype ';

		$sql = " FROM " . tablename('weixinmao_house_housemsg') . " AS m ";

	
		$sql .= "  RIGHT JOIN  " . tablename($db) . " as h ON h.id = m.houseid ";

								
		$sqlall = 'SELECT m.id AS id,m.name AS name,m.tel AS tel, h.title AS housename ,m.content as content, m.createtime AS createtime ' . $sql .$condition.' ORDER BY  m.createtime  DESC ' ;

		$list = pdo_fetchall($sqlall,array(':uniacid'=>$_W['uniacid'],':tel'=>$tel,':housetype'=>$housetype));
	
foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);

		}
	 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

	$data = array('list'=>$list ,'intro'=>$intro);
    return $this->result(0, 'success', $data);

		}

public function doPageMyhousemsg()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_housemsg') ." WHERE   uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
				
					if($v['paystatus'] ==0)
					{
						$list[$k]['statusStr'] = '未支付';
					}elseif($v['paystatus']==1){
						$list[$k]['statusStr'] = '已支付定金';
						
					}elseif($v['paystatus']==2){
						$list[$k]['statusStr'] = '已支付全款';
						
					}
					
					
				if($v['type'] == 'newhouse')
				{

					$houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['houseid']));
					$list[$k]['title'] = $houseinfo['housename'];
					$houseplan = pdo_get('weixinmao_house_house',array('id'=>$v['toplistid']));

					$list[$k]['houseplan'] = $houseplan['title'];

				}elseif($v['type'] == 'oldhouse'){
					$houseinfo = pdo_get('weixinmao_house_oldhouseinfo',array('id'=>$v['houseid']));
					$list[$k]['title'] = $houseinfo['title'];
				

				}else{

$houseinfo = pdo_get('weixinmao_house_lethouseinfo',array('id'=>$v['houseid']));
					$list[$k]['title'] = $houseinfo['title'];

				}
				
			}
		
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 		//$list['intro'] = $intro;
		$data = array('list'=>$list ,'intro'=>$intro);


		return $this->result(0, 'success', $data);
		
	}
	
  

  	public function doPageMycomment()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE   uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				if($v['housetype'] =='newhouse')
				{
					$houseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['pid']));
					$title = $houseinfo['housename'];
				}elseif ($v['housetype'] =='oldhouse') {
					# code...
					$houseinfo = pdo_get('weixinmao_house_oldhouseinfo',array('id'=>$v['pid']));
					$title = $houseinfo['title'];

				}elseif ($v['housetype'] =='lethouse') {
					# code...
					$houseinfo = pdo_get('weixinmao_house_lethouseinfo',array('id'=>$v['pid']));
					$title = $houseinfo['title'];

				}
				$list[$k]['title'] = $title;

				
			}
		
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 		//$list['intro'] = $intro;
		$data = array('list'=>$list ,'intro'=>$intro);


		return $this->result(0, 'success', $data);
		
	}


	public function doPageMycomplain()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_complain') ." WHERE   uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
			
				$agentinfo = pdo_get('weixinmao_house_agent',array('id'=>$v['agentid']));
				$list[$k]['agentname'] = $agentinfo['name'];

				
				
			}
		
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 		//$list['intro'] = $intro;
		$data = array('list'=>$list ,'intro'=>$intro);


		return $this->result(0, 'success', $data);
		
	}



	public function doPageMyfxrecord()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition = "  ";
		elseif($ordertype == 2)
			$condition = "  status =0 AND ";
		elseif($ordertype == 3)
			$condition = " status =1 AND ";
	
			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_fxmessage') ." WHERE  " .$condition. "  uniacid=:weid AND tid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$newhouse = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['pid']));
				$list[$k]['housename'] = $newhouse['housename'];

				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
			
					if($v['status'] ==0)
					{
						$list[$k]['statusStr'] = '未成交';
					}elseif($v['status']==1){
						$list[$k]['statusStr'] = '已成交';
						
					}
					
			
				
			}
		
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 		//$list['intro'] = $intro;
		$data = array('list'=>$list ,'intro'=>$intro);


		return $this->result(0, 'success', $data);
		
	}
	

public function doPageMymoneyrecord()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition = "  ";
		elseif($ordertype == 2)
			$condition = "  status =0 AND ";
		elseif($ordertype == 3)
			$condition = " status =1 AND ";
	
			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_fxrecord') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				//$newhouse = pdo_get('weixinmao_house_houseinfo',array('id'=>$v['pid']));
				//$list[$k]['housename'] = $newhouse['housename'];

				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
			
				if($v['status'] ==0)
					{
						$list[$k]['statusStr'] = '未获得';
					}elseif($v['status']==1){
						$list[$k]['statusStr'] = '已获得';
						
					}
					
			
				
			}
		
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 		//$list['intro'] = $intro;
		$data = array('list'=>$list ,'intro'=>$intro);


		return $this->result(0, 'success', $data);
		
	}



   public function doPageMySavelist()

   	{



         	global $_GPC, $_W;

         	$uid = $_GPC['uid'];

         	$ordertype = $_GPC['ordertype'];

         	if($ordertype == 1)

				{

					$table = 'weixinmao_house_houseinfo';

					$housetype = 'newhouse';

				}elseif($ordertype == 2)

				{



					$table = 'weixinmao_house_oldhouseinfo';

					$housetype = 'oldhouse';

				}else{



					$table = 'weixinmao_house_lethouseinfo';

					$housetype = 'lethouse';

				}

   			$condition = ' WHERE s.uniacid = :uniacid AND s.uid = :uid AND s.housetype = :housetype ';

			$params = array(':uniacid' => $_W['uniacid'],':uid'=>$uid,':housetype'=>$housetype);

			



			

			

			$sqllist = 'SELECT  *  ';

		

			

			

			$sql = " FROM " . tablename('weixinmao_house_save') . " AS s ";

			

			$sql .= "  LEFT JOIN  " . tablename($table) . " as h ON h.id = s.pid ";

			

			

			$list = pdo_fetchall($sqllist.$sql.$condition, $params);



			foreach($list as $k=>$v)

				{

					$list[$k]['thumb'] = tomedia($v['thumb']);

				}

$intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

			$data = array('list'=>$list, 'housetype'=>$housetype,'intro'=>$intro);

		

		return $this->result(0, 'success', $data);





   	}


public function doPageMysalelist()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition = " type = 1 AND  ";
		elseif($ordertype == 2)
			$condition = " type = 2 AND ";
		elseif($ordertype == 3)
			$condition = " type = 3 AND ";
		elseif($ordertype == 4)
			$condition = " type = 4 AND ";
			
		//echo $condition;
		
		//echo "SELECT * FROM " . tablename('weixinmao_house_saleinfo') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC";
		//exit;



	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_saleinfo') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));

		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
				if($v['ischeck']==0){
				
						$list[$k]['statusStr'] = '审核中';
				
					
				}else{
				
						$list[$k]['statusStr'] = '已上架';

				}
				
			}
		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

		 $data = array('list'=>$list, 'intro'=>$intro);
		return $this->result(0, 'success', $data);
		
	}



	
	
	
public function doPageMypublist()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition = " ispub = 1 AND ischeck =0 AND ";
		elseif($ordertype == 2)
			$condition = " paid = 1 AND status =0 AND ";
		elseif($ordertype == 3)
			$condition = " ispub = 1 AND ischeck =1 AND ";
		elseif($ordertype == 4)
			$condition = " status =-1 AND ";
			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
				if($v['ischeck']==0){
				
						$list[$k]['statusStr'] = '审核中';
				
					
				}else{
				
						$list[$k]['statusStr'] = '已上架';

				}
				
			}
		
		return $this->result(0, 'success', $list);
		
	}
	
	
	
	public function doPageMyletpublist()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

/*
		if($ordertype == 1)
			$condition = " ispub = 1 AND ischeck =0 AND ";
		elseif($ordertype == 2)
			$condition = " paid = 1 AND status =0 AND ";
		elseif($ordertype == 3)
			$condition = " ispub = 1 AND ischeck =1 AND ";
		elseif($ordertype == 4)
			$condition = " status =-1 AND ";
*/	
		
			if($ordertype == 1){

				$tablename = 'weixinmao_house_oldhouseinfo';

			}else{


				$tablename = 'weixinmao_house_lethouseinfo';


			}
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename($tablename) ." WHERE  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				
				if($v['ischeck']==0){
				
						$list[$k]['statusStr'] = '审核中';
				
					
				}else{
				
						$list[$k]['statusStr'] = '已上架';

				}
				
			}

		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

		 $data = array('list'=>$list, 'intro'=>$intro);
		
		return $this->result(0, 'success', $data);
		
	}
	
	
	public function doPageMysalepublist()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sessionid = $_GPC['sessionid'];
		$ordertype = $_GPC['ordertype']? $_GPC['ordertype'] : 1;
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

		if($ordertype == 1)
			$condition = " ischeck =0 AND ";
		elseif($ordertype == 2)
			$condition = " ischeck =1  AND ";
		elseif($ordertype == 3)
			$condition = " ischeck = 1 AND ";
		elseif($ordertype == 4)
			$condition = " status =-1 AND ";
			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_saleinfo') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				if($v['type'] ==1)
					{
						$list[$k]['type'] = '出售';
					}elseif($v['type'] == 2){
						
						$list[$k]['type'] = '出租';

					}elseif($v['type'] == 3){
						
						$list[$k]['type'] = '求购';

					}elseif($v['type'] == 4){
						
						$list[$k]['type'] = '求租';

					}
				
				if($v['ischeck'] ==1)
					{
$list[$k]['statusStr'] = '审核通过';
					}else{
$list[$k]['statusStr'] = '审核中';

					}
			}
		
		return $this->result(0, 'success', $list);
		
	}
	
	
	
	
	
	
	public function doPagedelOrder()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$id = $_GPC['id'];
		$sessionid = $_GPC['sessionid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

		}
		pdo_update('weixinmao_house_order', array('status'=>-1), array('id' => $id));
		return $this->result(0, 'success', array());
		
	}


	public function doPagedelComplain()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$id = $_GPC['id'];
		$sessionid = $_GPC['sessionid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

		}
		pdo_delete('weixinmao_house_complain', array('id' => $id));

		return $this->result(0, 'success', array());
		
	}

	public function doPagedelComment()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$id = $_GPC['id'];
		$sessionid = $_GPC['sessionid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

		}
		pdo_delete('weixinmao_house_comment', array('id' => $id));

		
		
	}
	

public function doPagePaylookhouse() {
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
	   $pid = $_GPC['pid'];
       $ordertype = $_GPC['ordertype'];
       $userinfo = pdo_get('weixinmao_house_userinfo',array('uid'=>$uid));

	   $activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));


       if($userinfo['score'] < $activeinfo['dmoney'])
       		{

				return $this->result(0, 'success',  array('msg'=>'余额不足','error'=>1));

       		}else{

       			$score = $userinfo['score'] - $activeinfo['dmoney'];


       			//print_r($userinfo);

       			//echo $score;

       			pdo_update('weixinmao_house_userinfo',array('score' => $score), array('uid'=>$uid));

       			$data = array(
							'uniacid' => $_W['uniacid'],
							'uid'=>$uid,
							'score'=>-$activeinfo['dmoney'],
							'totalscore'=>$score,
							'content' =>'查看二手房源：'.$activeinfo['title'] ,
						    'type'=> 1,//消费金额
							'createtime'=>TIMESTAMP
						);

				pdo_insert('weixinmao_house_scorerecord', $data);
				$id = pdo_insertid();
  $orderid = date("YmdHis"). rand(100000, 999999);
				$myorder = array(
			'uniacid' => $_W['uniacid'],
			'pid'=>$pid,
			'uid'=>$uid,
			'name'=>$userinfo['name'],
			'tel'=>$userinfo['tel'],
			'orderid' => $orderid,
			'money' => $activeinfo['dmoney'],
			'pid' => $pid,
		    'type'=> 'lethouse',
			'title' => '查看二手房源：'.$activeinfo['title'],
			'paid'=>1,
			'status'=>1,
			'createtime'=>TIMESTAMP
		);
		//print_r($myorder);
		 pdo_insert('weixinmao_house_order', $myorder);

           		return $this->result(0, 'success',  array('msg'=>'支付成功','error'=>0));


       		}


      

	}

	public function doPagePay() {
		global $_GPC, $_W;
	 //  $uid = $_SESSION['uid'];
	  $uid = $_GPC['uid'];
	   $pid = $_GPC['pid'];
       $ordertype = $_GPC['ordertype'];
	   $activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
	   if(!$activeinfo)
	   {
		   return $this->result(1, '支付失败，请重试');
	   }
	   
	   
	  $isorder = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') ." WHERE  pid = :pid AND uniacid=:weid AND status =1 AND uid=".$uid,array(":weid" => $_W['uniacid'],":pid" => $pid));
	if($isorder)
	{
		return $this->result(1, '支付失败，请重试');
	}
	   




	   $money = $activeinfo['dmoney'];
	   
	

	  
	   $orderid = date("YmdHis"). rand(100000, 999999);
	   $title = $activeinfo['title'];
	   
	   $userinfo = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		//构造订单信息，此处订单随机生成，业务中应该把此订单入库，支付成功后，根据此订单号更新用户是否支付成功
		$order = array(
			'tid' => $orderid,
			'user' => $userinfo['openid'],
			'fee' => $money,
			'title' => $title
		);
	
		$pay_params = $this->pay($order);
	
		$weixinmao_userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		$myorder = array(
			'uniacid' => $_W['uniacid'],
			'pid'=>$pid,
			'uid'=>$uid,
			'name'=>$weixinmao_userinfo['name'],
			'tel'=>$weixinmao_userinfo['tel'],
			'orderid' => $orderid,
			'money' => $money,
			'pid' => $pid,
		    'type'=> $ordertype,
			'title' => $title,
			'createtime'=>TIMESTAMP
		);
		//print_r($myorder);
		 pdo_insert('weixinmao_house_order', $myorder);
		//var_dump($pay_params); 
		if (is_error($pay_params)) {
			return $this->result(1, '支付失败，请重试');
		}
		return $this->result(0, 'success', $pay_params);
		
		
	}

	
   

   public function doPagePayhousemsg() {
		global $_GPC, $_W;
	 //  $uid = $_SESSION['uid'];
	  $uid = $_GPC['uid'];
	   $pid = $_GPC['pid'];
       $ordertype = $_GPC['ordertype'];
          $paytype	 = $_GPC['paytype'];
	   $activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_housemsg') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
	   if(!$activeinfo)
	   {
		   return $this->result(1, '支付失败，请重试a');
	   }
	   
	   
	  $isorder = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') ." WHERE  pid = :pid AND uniacid=:weid AND status =1 AND uid=".$uid,array(":weid" => $_W['uniacid'],":pid" => $pid));
	if($isorder)
	{
		return $this->result(1, '支付失败，请重试');
	}
	 
	if($activeinfo['type'] =='newhouse')
			{
				$housetypeinfo = pdo_get('weixinmao_house_house',array('id'=>$activeinfo['toplistid'])); 
				$newhouseinfo = pdo_get('weixinmao_house_houseinfo',array('id'=>$activeinfo['houseid'])); 
			}elseif($activeinfo['type'] =='lethouse'){

				$newhouseinfo = pdo_get('weixinmao_house_lethouseinfo',array('id'=>$activeinfo['houseid'])); 
				$newhouseinfo['housename'] = $newhouseinfo['title'];
				$housetypeinfo['money']= $newhouseinfo['money'];
				$housetypeinfo['dmoney'] = $newhouseinfo['price'];
			}
	if($paytype == 1)
			{
	   
	   			$money = $housetypeinfo['money'];

 	 			$title = '支付全款'.$money;
	   		
	   		}else{

				$money = $housetypeinfo['dmoney'];
				$title = '支付定金'.$money;
	   		}
	   
	
	   	$title.= $newhouseinfo['housename'].time();
	  
	   $orderid = date("YmdHis"). rand(100000, 999999);
	 
	   
	   $userinfo = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		//构造订单信息，此处订单随机生成，业务中应该把此订单入库，支付成功后，根据此订单号更新用户是否支付成功
		$order = array(
			'tid' => $orderid,
			'user' => $userinfo['openid'],
			'fee' => $money,
			'title' => $title
		);
	
		$pay_params = $this->pay($order);
	
		$weixinmao_userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		$myorder = array(
			'uniacid' => $_W['uniacid'],
			'pid'=>$pid,
			'uid'=>$uid,
			'name'=>$activeinfo['name'],
			'tel'=>$activeinfo['tel'],
			'orderid' => $orderid,
			'money' => $money,
			'pid' => $pid,
		    'type'=> $ordertype,
			'title' => $title,
			'createtime'=>TIMESTAMP
		);
		//print_r($myorder);
		 pdo_insert('weixinmao_house_order', $myorder);
	//	var_dump($pay_params); 
		if (is_error($pay_params)) {
			return $this->result(1, '支付失败，请重试b');
		}
		return $this->result(0, 'success', $pay_params);
		
		
	}


public function doPagePayscore() {
		global $_GPC, $_W;
	 //  $uid = $_SESSION['uid'];
	  $uid = $_GPC['uid'];
	   $pid = $_GPC['pid'];
       $ordertype = $_GPC['ordertype'];
          $paytype	 = $_GPC['paytype'];
	   $paylist = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_paylist') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
	   if(!$paylist)
	   {
		   return $this->result(1, '支付失败，请重试aa');
	   }
	   
	   
	
	
		$money = $paylist['money'];
		$title = '积分充值'.$money.time();
	   		
	   
	
	  
	   $orderid = date("YmdHis"). rand(100000, 999999);
	 
	   
	   $userinfo = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		//构造订单信息，此处订单随机生成，业务中应该把此订单入库，支付成功后，根据此订单号更新用户是否支付成功
		$order = array(
			'tid' => $orderid,
			'user' => $userinfo['openid'],
			'fee' => $money,
			'title' => $title
		);
	
		$pay_params = $this->pay($order);
	
		$weixinmao_userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		$myorder = array(
			'uniacid' => $_W['uniacid'],
			'pid'=>$pid,
			'uid'=>$uid,
			'name'=>$activeinfo['name'],
			'tel'=>$activeinfo['tel'],
			'orderid' => $orderid,
			'money' => $money,
			'pid' => $pid,
		    'type'=> $ordertype,
			'title' => $title,
			'createtime'=>TIMESTAMP
		);
		//print_r($myorder);
		 pdo_insert('weixinmao_house_order', $myorder);
	//	var_dump($pay_params); 
		if (is_error($pay_params)) {
			return $this->result(1, '支付失败，请重试ccc');
		}
		return $this->result(0, 'success', $pay_params);
		
		
	}



	public function doPageSalepay() {
	   
	   global $_GPC, $_W;
	   $uid = $_GPC['uid'];
	   $pid = $_GPC['pid'];
	   $toplistid = $_GPC['toplistid'];
       $ordertype = $_GPC['ordertype'];
	   $saleinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_saleinfo') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
	   if(!$saleinfo)
	   {
		   return $this->result(1, '支付失败，请重试');
	   }
	   
	   
	  $toplistinfo = pdo_fetch("SELECT id,money FROM " . tablename('weixinmao_house_toplist') ." WHERE  id = :id AND uniacid=:weid ",array(":weid" => $_W['uniacid'],":id" => $toplistid));
	if(!$toplistinfo)
	{
		return $this->result(1, '支付失败，请重试');
	}
	   
	   $money = $toplistinfo['money'];
	   
	

	  
	   $orderid = date("YmdHis"). rand(100000, 999999);
	   $title = $ordertype.$orderid;
	   
	   $userinfo = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		//构造订单信息，此处订单随机生成，业务中应该把此订单入库，支付成功后，根据此订单号更新用户是否支付成功
		$order = array(
			'tid' => $orderid,
			'user' => $userinfo['openid'],
			'fee' => $money,
			'title' => $title
		);
	
		$pay_params = $this->pay($order);
	
		$weixinmao_userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		$myorder = array(
			'uniacid' => $_W['uniacid'],
			'pid'=>$pid,
			'uid'=>$uid,
			'name'=>$weixinmao_userinfo['name'],
			'tel'=>$weixinmao_userinfo['tel'],
			'orderid' => $orderid,
			'money' => $money,
			'pid' => $pid,
		    'type'=> $ordertype,
			'title' => $title,
			'createtime'=>TIMESTAMP
		);
		//print_r($myorder);
		pdo_insert('weixinmao_house_order', $myorder);
		//var_dump($pay_params); 
		if (is_error($pay_params)) {
			return $this->result(1, '支付失败，请重试');
		}
		return $this->result(0, 'success', $pay_params);
		
		
	}



public function doPageGetagentinit()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];

		$agnet = pdo_fetch("SELECT id,enabled FROM " . tablename('weixinmao_house_agent') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($agnet)
			{
				if($agnet['enabled'] == 1)
					{

				$list = array('msg'=>'已审核','error'=>1);

					}else{
				$list = array('msg'=>'正在审核','error'=>2);


					}
			}else{

				$list = array('msg'=>'提交成功','error'=>0);

			}

	   $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

	   $list['intro'] = $intro;

	   return $this->result(0, 'success', $list);
	}

public function doPageCheckagent()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];

		$agnet = pdo_fetch("SELECT id,enabled FROM " . tablename('weixinmao_house_agent') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($agnet)
			{
				if($agnet['enabled'] == 1)
					{

				$list = array('msg'=>'您已经成功入驻平台','error'=>1);

					}else{
				$list = array('msg'=>'您的入驻经纪人信息正在审核','error'=>2);


					}
			}else{

				$list = array('msg'=>'请先入驻成为经纪人','error'=>0);

			}

	

	   return $this->result(0, 'success', $list);
	}




public function doPageCheckcoupon()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];

		$agnet = pdo_fetch("SELECT id,status FROM " . tablename('weixinmao_house_coupon') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($agnet)
			{
				if($agnet['status'] == 1)
					{

				$list = array('msg'=>'您已经成功入驻分销商','error'=>1);

					}else{
				$list = array('msg'=>'您的入驻分销商信息正在审核','error'=>2);


					}
			}else{

				$list = array('msg'=>'请先入驻成为分销商','error'=>0);

			}

	

	   return $this->result(0, 'success', $list);
	}

public function doPageGetcouponinit()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];

		$agnet = pdo_fetch("SELECT id,status FROM " . tablename('weixinmao_house_coupon') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($agnet)
			{
				if($agnet['status'] == 1)
					{

				$list = array('msg'=>'已审核','error'=>1);

					}else{
				$list = array('msg'=>'正在审核','error'=>2);


					}
			}else{

				$list = array('msg'=>'提交成功','error'=>0);

			}


		 $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
 $list['intro'] = $intro;
	   return $this->result(0, 'success', $list);
	}

public function doPageSaveagent()
	{
		global $_GPC, $_W;
		$uploadimagelist_str = $_GPC['uploadimagelist_str'];
	//	$imagelist = explode('@',$uploadimagelist_str);
		$uid = $_GPC['uid'];
		$ischeck = $this->IsCheck();
		if($ischeck == 1)
				{

					$enabled = 0;
				}else{

					$enabled = 1;
				}
		$data = array(
					'uniacid' => $_W['uniacid'],
					'uid'=>$_GPC['uid'],
					'name'=>$_GPC['name'],
					'cityid'=>$_GPC['cityid'],
					'qq'=>$_GPC['qq'],
					'address'=>$_GPC['address'],
					//'email'=>$_GPC['email'],
					  'card'=>$_GPC['card'],
			          'bank'=>$_GPC['bank'],
			          'bankaddress'=>$_GPC['bankaddress'],
					'tel'=>$_GPC['tel'],
					'intro'=>$_GPC['content'],
					'enabled'=>$enabled,
                  'thumb'=>$uploadimagelist_str,
                  'createtime'=>TIMESTAMP
					);
		
	
			
			

				    pdo_insert('weixinmao_house_agent', $data);
					$id = pdo_insertid();
				    if($id)
					{

						
						$list = array('msg'=>'提交成功','error'=>0,'totalnum'=>$totalnum);
					
					}else{
						
						$list = array('msg'=>'保存失败','error'=>1);
					}

	
		return $this->result(0, 'success', $list);
		
	}






	
	
	public function doPageRepay() {
		global $_GPC, $_W;
	   $uid = $_SESSION['uid'];
	   $id = $_GPC['id'];
	   $orderinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_order') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
	   if(!$orderinfo)
	   {
		   return $this->result(1, '支付失败，请重试');
	   }
	   $money = $orderinfo['money'];
	   $orderid = $orderinfo['orderid'];
	   $title = $orderinfo['title'];
	   
	   $userinfo = pdo_fetch("SELECT openid FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));

		//构造订单信息，此处订单随机生成，业务中应该把此订单入库，支付成功后，根据此订单号更新用户是否支付成功
		$order = array(
			'tid' => $orderid,
			'user' => $userinfo['openid'],
			'fee' => $money,
			'title'=>$title
		);
	
		$pay_params = $this->pay($order);
		
		if (is_error($pay_params)) {
			return $this->result(1, '支付失败，请重试');
		}
		return $this->result(0, 'success', $pay_params);
	}
	
	
	
	
	
	
	
	
	
	public function payResult($pay_result) {
		global $_GPC, $_W;
		if ($pay_result['result'] == 'success') {
			//此处会处理一些支付成功的业务代码
					
			
			$tid = $pay_result['tid'];
		
		    $orderinfo =  pdo_fetch("SELECT pid ,type,uid FROM " . tablename('weixinmao_house_order') ." WHERE  uniacid=:weid AND orderid=".$tid,array(":weid" => $_W['uniacid']));


			
			 pdo_update('weixinmao_house_order',array('paid' => 1,'status' => 1,'paytime'=>TIMESTAMP), array('orderid'=>$tid));

			if($orderinfo['type'] == 'puboldhouse')
					{

						pdo_update('weixinmao_house_saleinfo',array('paid' => 1,'status' => 1), array('id'=>$orderinfo['pid']));

					}elseif($orderinfo['type'] == 'puboldhouse'){



					}elseif($orderinfo['type'] == 'payscore'){

						$userinfo = pdo_get('weixinmao_house_userinfo',array('uid'=>$orderinfo['uid']));

						$paylist = pdo_get('weixinmao_house_paylist',array('id'=>$orderinfo['pid']));

						$score = $userinfo['score'] + $paylist['days'];

					   pdo_update('weixinmao_house_userinfo',array('score' =>$score), array('uid'=>$orderinfo['uid']));


					   $data = array(
							'uniacid' => $_W['uniacid'],
							'uid'=>$orderinfo['uid'],
							'score'=>$paylist['days'],
							'totalscore'=>$score,
							'content' =>$paylist['title'] ,
						    'type'=> 0,
							'createtime'=>TIMESTAMP
						);

					   pdo_insert('weixinmao_house_scorerecord', $data);
						$id = pdo_insertid();

					}
					
		}
	//	print_r($pay_result);
		return true;
	}
	
	
	

	public function doPagegetQrcode()
		{
			     global $_GPC, $_W;
			
				load()->func('file');
 					
				$id = $_GPC['id'];
				$appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;
				
				$width=430;
				
				$data = array();
			    $data['scene'] = $id."@".$uid;
			    $data['page'] = "weixinmao_house/pages/fxoldhousedetail/index";
			    $post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);

				    
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);

				      $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);

		}



	public function doPagegetQrcodelethouse()
		{
				global $_GPC, $_W;
			
				load()->func('file');
 					
				$id = $_GPC['id'];
				$uid = $_GPC['uid'];
				 $appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;
				
				$width=430;
				
				$data = array();
			    $data['scene'] = $id."@".$uid;
			    $data['page'] = "weixinmao_house/pages/fxlethousedetail/index";
			    $post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				      $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);

		}


public function doPagegetQrcodenewhouse()
		{

			global $_GPC, $_W;
			
				load()->func('file');
 					
				$id = $_GPC['id'];
				$uid = $_GPC['uid'];
          
               $appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;
				
				$width=430;
				
				$data = array();
			    $data['scene'] = $id."@".$uid;
			    $data['page'] = "weixinmao_house/pages/fxnewhousedetail/index";
			    $post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');


				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    // $houseinfo['housename'] = $url_path;
				    // 
				     $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);


		}



public function doPagegetFxQrcodenewhouse()
		{

			global $_GPC, $_W;
			
				load()->func('file');
 					
				$id = $_GPC['id'];
				$uid = $_GPC['uid'];

				$appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;
				
				$width=430;
				//$post_data='{"path":"'.$path.'","width":'.$width.'}';
				//$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
				//$result=$this->api_notice_increment($url,$post_data,'POST');
				
					$data = array();
			        $data['scene'] = $id."@".$uid;
			        $data['page'] = "weixinmao_house/pages/fxnewhousedetail/index";
			        $post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');
				

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    // $houseinfo['housename'] = $url_path;
				    // 
				     $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);


		}

public function doPagegetFxQrcodeoldhouse()
		{

			global $_GPC, $_W;
			
				load()->func('file');
 					
				$id = $_GPC['id'];
				$uid = $_GPC['uid'];

				$appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;
				
				$width=430;
				//$post_data='{"path":"'.$path.'","width":'.$width.'}';
				//$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
				//$result=$this->api_notice_increment($url,$post_data,'POST');
				
					$data = array();
			        $data['scene'] = $id."@".$uid;
			        $data['page'] = "weixinmao_house/pages/fxoldhousedetail/index";
			        $post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');
				

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    // $houseinfo['housename'] = $url_path;
				    // 
				     $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);


		}
  
  
  public function doPagegetFxQrcodelethouse()
		{

			global $_GPC, $_W;
			
				load()->func('file');
 					
				$id = $_GPC['id'];
				$uid = $_GPC['uid'];

				$appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;
				
				$width=430;
				//$post_data='{"path":"'.$path.'","width":'.$width.'}';
				//$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
				//$result=$this->api_notice_increment($url,$post_data,'POST');
				
					$data = array();
			        $data['scene'] = $id."@".$uid;
			        $data['page'] = "weixinmao_house/pages/fxlethousedetail/index";
			        $post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');
				

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    // $houseinfo['housename'] = $url_path;
				    // 
				     $intro = pdo_fetch("SELECT maincolor FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);


		}



public function doPagemyspread()
		{

			global $_GPC, $_W;
			
				load()->func('file');
 					
				$uid =$_GPC['uid'];
          
                $path_url="weixinmao_house/pages/joinuser/index";

				$appid = $_W['uniaccount']['key'];
				$secret = $_W['uniaccount']['secret'];
				$tokenUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
				$result=$this->api_notice_increment($tokenUrl);
				$tokenArr = json_decode($result);  				
   				$access_token=$tokenArr->access_token;

				$width=430;
				$data = array();
       			$data['scene'] = "uid=" . $uid;
        		$data['page'] = "weixinmao_house/pages/joinuser/index";
        	   
        		$post_data = json_encode($data);

			    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');


				$uniacid = intval($_W['uniacid']);
				$path = "images/{$uniacid}/" . date('Y/m/');
				mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				    $intro = pdo_fetch("SELECT maincolor,fxbanner FROM " . tablename('weixinmao_house_intro')." WHERE   uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($intro['fxbanner']);
				     $houseinfo['housename'] = $path_url;

				   

				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo,'intro'=>$intro);
				    return $this->result(0, 'success', $data);


		}









public function send_post($url, $post_data,$method='POST') {
    $postdata = http_build_query($post_data);
    $options = array(
      'http' => array(
        'method' => $method, //or GET
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 15 * 60 // 超时时间（单位:s）
      )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
  }
	
public function api_notice_increment($url, $data="",$method='GET'){
	error_reporting(0);
    $ch = curl_init();
    $header = "Accept-Charset: utf-8";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
   
    if (curl_errno($ch)) {
      return false;
    }else{
      return $tmpInfo;
    }
  }
	
}