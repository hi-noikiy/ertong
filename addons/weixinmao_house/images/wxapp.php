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

	public function doPageGetcity()
	{
		global $_GPC, $_W;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
		
		return $this->result(0, 'success', $arealist);
		
		
	}
	
		public function doPageGetSysInit()
			{

				global $_GPC, $_W;

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
				$intro['description']=html_entity_decode($intro['content']);
	 			$intro['content'] = trim(html_entity_decode(strip_tags($intro['content'])),chr(0xc2).chr(0xa0));  
				
				
				$map = $this->Convert_BD09_To_GCJ02($intro['lat'],$intro['lng']);
				$intro['lat'] = $map['lat'];
				$intro['lng'] = $map['lng'];


		$condition = ' WHERE `uniacid` = :uniacid AND isrecommand = 1  ORDER BY sort DESC ';
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
		$condition = ' WHERE `uniacid` = :uniacid  AND isrecommand = 1 AND status =0  ORDER BY sort DESC  ';
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
					$oldhouselist[$k]['thumb'] = tomedia($v['thumb']);
					$oldhouselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$oldhouselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
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



	return $this->result(0, 'success', array('banner'=>$banner, 'intro'=>$intro,'lethouselist'=>$newhouselist,'oldhouselist'=>$oldhouselist,'agentlist'=>$agentlist));




}



		public function doPageGetpubinit()
	{
		global $_GPC, $_W;
		$uid = $_GPC['uid'];
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		
		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
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
		$firsttop = array('id'=>0,title=>"不置顶直接发布",money=>0);
		array_unshift($toplist,$firsttop); 
		$data = array('arealist'=>$arealist,'ismaster'=>$ismaster,'toplist'=>$toplist);
		return $this->result(0, 'success', $data);
		
		
	}
	public function doPageGetagentlist()
	{
		global $_GPC, $_W;
		//$siteurl = $this->GetSiteUrl();
		$condition = ' WHERE `uniacid` = :uniacid AND enabled = 1 ';
		$params = array(':uniacid' => $_W['uniacid']);
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
					
					}
		}
		
		return $this->result(0, 'success', $list);
		
		
	}
	
	
	public function doPageGetagentdetail()
		{
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_agent') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['thumb'] = tomedia($list['thumb']);
			
			
			$condition = " WHERE  uid=:uid  AND  uniacid=:uniacid ";
			$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') . $condition ;
			$oldhouselist = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid'],':uid'=>$list['uid']));
			if($oldhouselist)
			{
					foreach($oldhouselist as $k=>$v)
					{
						$oldhouselist[$k]['thumb'] = tomedia($v['thumb']);
						$oldhouselist[$k]['money'] = $v['saleprice'];
					}
			}
			$data = array('list'=>$list,'oldhouselist'=>$oldhouselist,'lethouselist'=>$lethouselist);

			return $this->result(0, 'success', $data);
			
		}
	public function doPageGetagenthouse()
	{
		global $_GPC, $_W;
	//	$siteurl = $this->GetSiteUrl();
		$pid = $_GPC['pid'];
		$id = $_GPC['id'];
		$agentinfo = pdo_fetch("SELECT uid FROM " . tablename('weixinmao_house_agent') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
		$condition = " WHERE  uid=:uid  AND  uniacid=:uniacid ";
		if($pid ==1)
		{
						$sql = 'SELECT * FROM ' . tablename('weixinmao_house_houseinfo') . $condition ;

		}elseif($pid == 2){
						$sql = 'SELECT * FROM ' . tablename('weixinmao_house_oldhouseinfo') . $condition ;

		}else{

						$sql = 'SELECT * FROM ' . tablename('weixinmao_house_lethouseinfo') . $condition ;

		}
	
		$houselist = pdo_fetchall($sql,array(":uniacid" => $_W['uniacid'],':uid'=>$agentinfo['uid']));
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
					if($pid ==2)
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
			$condition = ' WHERE `uniacid` = :uniacid AND ischeck = 1 AND (toplistid =0 OR endtime < '.time().')';
			$topcondition = ' WHERE `uniacid` = :uniacid AND ischeck = 1 AND toplistid>0 AND endtime > '.time();
		$params = array(':uniacid' => $_W['uniacid']);
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

	
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_area') . ' WHERE `uniacid` = :uniacid ORDER BY `sort` DESC';
		

		$arealist = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		
	


		if($salelist)
		{
			foreach($salelist as $k=>$v)
				{
					
					$sql = 'SELECT name,tel,avatarUrl FROM ' . tablename('weixinmao_house_userinfo') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ';
		
					$userinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$v['uid']));
					$salelist[$k]['name'] =  $userinfo['name'];
					$salelist[$k]['tel'] =  $userinfo['tel'];
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
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = $list['specialliststr'] = explode(',',$list['productspecial']);
			$list['specialliststr'] = implode('、',$list['specialliststr']);
			$salestatus=array(1=>'出售',2=>'出租',3=>'求购',4=>'求租');
			$list['type'] = $salestatus[$list['type']];
			 $sql = 'SELECT name,tel,avatarUrl FROM ' . tablename('weixinmao_house_userinfo') . ' WHERE `uniacid` = :uniacid AND `uid` = :uid ';
		
					$userinfo = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'],':uid'=>$list['uid']));
					$list['name'] =  $userinfo['name'];
					$list['tel'] =  $userinfo['tel'];
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

			$condition = ' WHERE c.uniacid = :uniacid AND c.sid = :sid';
			$params = array(':uniacid' => $_W['uniacid'],':sid'=>$id);

			$sqlcount = 'SELECT  c.id AS id, c.createtime AS createtime,c.content AS content, u.nickname AS nickname ';
		
			
			
			$sql = " FROM " . tablename('weixinmao_house_comment') . " AS c ";
			
			$sql .= "  LEFT JOIN  " . tablename('weixinmao_house_saleinfo') . " as s ON s.id = c.sid ";
			
			$sql .= "  LEFT JOIN  " . tablename('mc_mapping_fans') . " as u ON u.uid = c.uid ";

		//	$sql .= "  LEFT JOIN  " . tablename('mc_members') . " as m ON m.uid = c.uid ";

			
			$commentlist = pdo_fetchall($sqlcount.$sql.$condition, $params);
			$commentcount = count($commentlist);


			
			$data = array('list'=>$list,'piclist'=>$piclist,'commentlist'=>$commentlist,'commentcount'=>$commentcount);

			return $this->result(0, 'success', $data);
			
		
	}
	
	
	
	
	public function doPageGetnewhouselist()
	{
		global $_GPC, $_W;
		//$siteurl = $this->GetSiteUrl();
			$condition = ' WHERE `uniacid` = :uniacid ';
		$params = array(':uniacid' => $_W['uniacid']);
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
			$priceinfo = pdo_fetch("SELECT beginprice,endprice FROM " . tablename('weixinmao_house_houseprice')." WHERE   uniacid=:uniacid AND id=:id",array(":uniacid" => $_W['uniacid'],":id"=>$housepriceid));
			if($priceinfo)
			{
				$condition .= ' AND `houseprice` >  '.$priceinfo['beginprice'].'  AND `houseprice` <= '.$priceinfo['endprice'];
			}
			
			
		}
		$sort =' ORDER BY sort DESC  ';
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

		//$siteurl = $this->GetSiteUrl();
		$condition = ' WHERE `uniacid` = :uniacid AND status = 0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';
		$params = array(':uniacid' => $_W['uniacid']);
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
		$sort =' ORDER BY sort DESC  ';
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
	
	
	public function doPageGetlethouselist()
	{
		global $_GPC, $_W;

		//$siteurl = $this->GetSiteUrl();
		$condition = ' WHERE `uniacid` = :uniacid AND status =0 AND (ispub = 1 AND ischeck =1 OR ispub = 0)  ';

		$params = array(':uniacid' => $_W['uniacid']);
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
		
		$sort =' ORDER BY sort DESC  ';
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
	
	
	
	public function doPageGetnewhousedetail()
	{
		
			global $_GPC, $_W;
		//	$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$uid = $_GPC['uid'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = $list['specialliststr'] = explode(',',$list['productspecial']);
			$list['specialliststr'] = implode('、',$list['specialliststr']);
			$salestatus=array(1=>'在售',2=>'待售',3=>'售完',4=>'打折');
			$list['salestatus_str'] = $salestatus[$list['housestatus']];
			 $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
			$list['lat'] = $map['lat'];
			$list['lng'] = $map['lng'];
			$agentinfo = pdo_fetch("SELECT tel FROM " . tablename('weixinmao_house_agent') ." WHERE uniacid=:uniacid AND uid=".$list['uid'],array(":uniacid" => $_W['uniacid']));
            
			$list['agenttel'] = $agentinfo['tel'];
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


			$orderinfo = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_order') ." WHERE uniacid=:uniacid AND uid=:uid AND status =1 AND pid=".$id,array(":uniacid" => $_W['uniacid'],":uid"=>$uid));
		
			if($orderinfo)
			{
				$ispay = 1;
			}else{
				
				$ispay = 0;
			}




	$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'newhouse'));

			if($saveinfo)
					$issave = 1;
			else
					$issave = 0;
			$data = array('list'=>$list,'housepic'=>$case_list,'houseplan'=>$house_list,'piclist'=>$piclist,'ispay'=>$ispay,'issave'=>$issave);

			return $this->result(0, 'success', $data);
			
		
	}

	


		public function doPageGetguesthouselist()
	{
		global $_GPC, $_W;

		//$siteurl = $this->GetSiteUrl();
		$condition = ' WHERE `uniacid` = :uniacid   ';

		$params = array(':uniacid' => $_W['uniacid']);
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
		
		$sort =' ORDER BY sort DESC  ';
		$condition .= $sort;
		$sql = 'SELECT * FROM ' . tablename('weixinmao_house_guesthouseinfo') .$condition. $limit ;
		
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
		$typeinfo = array(1=>'出售',2=>'出租',3=>'求购',4=>'求租');
		if($houselist)
		{
			foreach($houselist as $k=>$v)
				{
					$houselist[$k]['thumb'] = tomedia($v['thumb']);
					$houselist[$k]['typename'] = $typeinfo[$v['letway']];
					$houselist[$k]['areaname'] =  $areainfo[$v['houseareaid']];
					$houselist[$k]['housetypename'] =  $housetypeinfo[$v['housetype']];
				}
		}
		
		return $this->result(0, 'success', $houselist);

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
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$piclist1 = unserialize($list['thumb_url']);
			$piclist = array();
		    $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
			$list['lat'] = $map['lat'];
			$list['lng'] = $map['lng'];
			$agentinfo = pdo_fetch("SELECT tel FROM " . tablename('weixinmao_house_agent') ." WHERE uniacid=:uniacid AND uid=".$list['uid'],array(":uniacid" => $_W['uniacid']));
            
			$list['agenttel'] = $agentinfo['tel'];
			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] = tomedia($p);
						}
				}


			$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'oldhouse'));

			if($saveinfo)
					$issave = 1;
			else
					$issave = 0;

			$data = array('list'=>$list,'piclist'=>$piclist,'issave'=>$issave);
			
			
			return $this->result(0, 'success', $data);
			
		
	}
	
	public function doPageSavecomment()
		{

			global $_GPC, $_W;
			$uid = $_GPC['uid'];
			$sid = $_GPC['sid'];
			$content = $_GPC['content'];
			 $data = array(
                    'uniacid' => $_W['uniacid'],
					'uid'=>$uid,
					'sid'=>$sid,
                    'content' => $_GPC['content'],
                    'createtime' => TIMESTAMP,
                );
          pdo_insert('weixinmao_house_comment', $data);
          $id = pdo_insertid();
          return $this->result(0, 'success', array());

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

			$data = array('list'=>$list, 'housetype'=>$housetype);
		
		return $this->result(0, 'success', $data);


   	}
	
   public function doPageGetlethousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$uid = $_GPC['uid'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$list['houselabel'] = explode(',',$list['houselabel']);
			 $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
			$list['lat'] = $map['lat'];
			$list['lng'] = $map['lng'];
            $agentinfo = pdo_fetch("SELECT tel FROM " . tablename('weixinmao_house_agent') ." WHERE uniacid=:uniacid AND uid=".$list['uid'],array(":uniacid" => $_W['uniacid']));
            
			$list['agenttel'] = $agentinfo['tel'];

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

				$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'lethouse'));

			if($saveinfo)
					$issave = 1;
			else
					$issave = 0;

			$data = array('list'=>$list,'piclist'=>$piclist,'ispay'=>$ispay,'issave'=>$issave);
			return $this->result(0, 'success', $data);
			
		
	}
	 public function doPageGetguesthousedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$uid = $_GPC['uid'];
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_guesthouseinfo') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
			$list['speciallist'] = explode(',',$list['special']);
			$list['houselabel'] = explode(',',$list['houselabel']);
			 $map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
			$list['lat'] = $map['lat'];
			$list['lng'] = $map['lng'];
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

				$saveinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_save') ." WHERE uniacid=:uniacid AND housetype=:housetype AND pid=".$id,array(":uniacid" => $_W['uniacid'],":housetype"=>'lethouse'));

			if($saveinfo)
					$issave = 1;
			else
					$issave = 0;

			$data = array('list'=>$list,'piclist'=>$piclist,'ispay'=>$ispay,'issave'=>$issave);
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
		
		
		
		$condition = ' WHERE `uniacid` = :uniacid ';
		
		if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title  OR `housearea` LIKE :housearea  ';
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
		
		
		
		$condition = ' WHERE `uniacid` = :uniacid ';
		if (!empty($_GPC['keyword'])) {
				$condition .= ' AND `title` LIKE :title  OR `housearea` LIKE :housearea  ';
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

		
		$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));
		//$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') );
		$housepricelist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_houseprice') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));

		return $this->result(0, 'success', array('arealist'=>$arealist,'housepricelist'=>$housepricelist));
		
		
		
	}
	
	public function doPageGetinitoldinfo()
	{
		global $_GPC, $_W;
		
		//$sql = "SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ";

		
		$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));
		//$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') );
		$housepricelist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_oldhouseprice') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));

		return $this->result(0, 'success', array('arealist'=>$arealist,'housepricelist'=>$housepricelist));
		
	}

   

   public function doPageGetinitguestinfo()
	{
		global $_GPC, $_W;
		
		$id  = $_GPC['id'];
		$housetype = $_GPC['housetype'];

		$houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE  uniacid=:uniacid AND id =:id ",array(":uniacid" => $_W['uniacid'],":id"=>$id));


		return $this->result(0, 'success', array('houseinfo'=>$houseinfo));
		
	}


	public function doPageGetinitletinfo()
	{
		global $_GPC, $_W;

		$arealist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_area') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));
		$housepricelist = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_lethouseprice') ." WHERE  enabled =1 AND uniacid=:uniacid ORDER BY sort ASC  ",array(":uniacid" => $_W['uniacid']));

		return $this->result(0, 'success', array('arealist'=>$arealist,'housepricelist'=>$housepricelist));
		
		
		
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
			$list['description']=html_entity_decode($list['content']);
 			$list['content'] = trim(html_entity_decode(strip_tags($list['content'])),chr(0xc2).chr(0xa0));  
			
			
			$map = $this->Convert_BD09_To_GCJ02($list['lat'],$list['lng']);
			$list['lat'] = $map['lat'];
			$list['lng'] = $map['lng'];
			
			
			
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
		$list['content'] = html_entity_decode($list['content']);
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
			if($_W['attachurl']!=""){
			
				$picurl = $_W['attachurl'];
				
			}else{
				
				$picurl  = $_W['siteroot'].'attachment/';
			}
			$pid = $_GPC['pid'];
			
			$category_info = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_category') ." WHERE  weid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
			if($category_info['parentid'] == 0)
			{
				$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE   uniacid=:weid AND pid=".$pid." ORDER BY sort DESC",array(":weid" => $_W['uniacid']));
			}else{
				
				$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE   uniacid=:weid AND sid=".$pid." ORDER BY sort DESC",array(":weid" => $_W['uniacid']));

				
				
			}
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
			$list['content'] = html_entity_decode($list['content']);
			//$list['content'] = htmlspecialchars($list['content']);
			
			return $this->result(0, 'success', $list);
			
		}
		
	public function doPageGetnewsdetail()
		{
			global $_GPC, $_W;
			
			$id = $_GPC['id'];
			
			$list = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
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
	
			$list['content'] = html_entity_decode($list['content']);
			$list['createtime'] = date('Y-m-d',$list['createtime']);
					$data = array('list'=>$list ,'houseinfo'=>$houseinfo);
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
			$list['content'] = html_entity_decode($list['content']);
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
			$list['content'] = html_entity_decode($list['content']);
			
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
			
			$pid = $_GPC['pid'];
			$category_list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_category') ." WHERE weid=:weid ORDER BY displayorder DESC ",array(":weid" => $_W['uniacid']));
			
			$content  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_content') ." WHERE  uniacid=:uniacid AND pid=".$category_list[0]['id']."  ORDER BY createtime DESC",array(":uniacid" => $_W['uniacid']));
			if($content)
			{
				foreach($content as $k=>$v)
				{
					$content[$k]['createtime'] = date('Y-m-d',$v['createtime']);
					$content[$k]['thumb'] = tomedia($v['thumb']);
					
				}
			}
			$list = array('category'=>$category_list,'article'=>$content,'activeCategoryId'=>$category_list[0]['id']);
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
	
	public function doPageSavemessage2()
	{
		global $_GPC, $_W;
		
		
		
		$appid = 'wx90834c0de40eab47';
        $appsecret = '5c77bc7fd2ec9f502e96d7de7201cac3';
        $access_token = '';

       // $openid = isset($_POST['openid']) ? trim($_POST['openid']) : ''; //小程序的openid
	   $openid='o7voM0QImQmXJQOJZX0CST4Z12hQ';
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
        $temp_id = 'kYVRYAK15a6Xnyksp1iFyWODF_s6e0slIaLiwBa3les';
	
        //获取access_token, 做缓存，expires_in：7200
       $this-> generate_token($access_token, $appid, $appsecret);

        $data['touser'] = $openid;
        $data['template_id'] = $temp_id;
        $data['page'] =  'pages/index/index'; //该字段不填则模板无跳转
        $data['form_id'] = $formid;
        $data['data'] = array(
            'keyword1' => array('value' => '134134131'),
            'keyword2' => array('value' => '进口金枕头榴莲'),
            'keyword3' => array('value' => '进口金枕头榴莲'),
            'keyword4' => array('value' => time() * 1000),
            'keyword5' => array('value' => date('Y-m-d H:i:s')),
            'keyword6' => array('value' => date('Y-m-d H:i:s')),
        );
        $data['emphasis_keyword'] = 'keyword5.DATA'; //模板需要放大的关键词，不填则默认无放大
		echo  $access_token;
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
        break;
		

		
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
        //CURLOPT_HEADER 选项为 true
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);

        //fail the request if the HTTP code returned is equal to or larger than 400
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        $header = array("Content-Type:application/json;charset=utf-8;", "Connection: keep-alive;");
        switch (strtolower($method)) {
            case "post":
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
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
			
			$list = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_active') ." WHERE  uniacid=:weid ORDER BY sort ASC",array(":weid" => $_W['uniacid']));
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
			return $this->result(0, 'success', $list);
			
		}
	
	
public function doPageGetactivedetail()
	{
		
			global $_GPC, $_W;
			//$siteurl = $this->GetSiteUrl();
			$id = $_GPC['id'];
			$activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_active') ." WHERE uniacid=:uniacid AND id=".$id,array(":uniacid" => $_W['uniacid']));

	
			
			
			$piclist1 = unserialize($activeinfo['thumb_url']);
			$piclist = array();
		
			if(is_array($piclist1)){
				foreach($piclist1 as $p)
						{
									$piclist[] =tomedia($p);
						}
				}
			
			
			
			
	

			$active_list = pdo_fetchall("SELECT id FROM " . tablename('weixinmao_house_baoming') ." WHERE uniacid=:uniacid AND pid=:pid AND aid=:aid",array(":uniacid" => $_W['uniacid'],':pid'=>$list['id'],':aid'=>$id));
		
			$totalnum = count($active_list);
			
			$data = array('piclist'=>$piclist,'activeinfo'=>$activeinfo,'totalnum'=>$totalnum);
			
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
	
	

   
    public function doPageSaveguestbao()
	{
		global $_GPC, $_W;
		
		$data = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $_GPC['uid'],
					'project' => $_GPC['project'],
					'source' => $_GPC['source'],
					'houseid' => $_GPC['houseid'],
					'company' => $_GPC['company'],
					'name' => $_GPC['name'],
					'tel' => $_GPC['tel'],
					'housetype' => $_GPC['housetype'],
					'baotime' => $_GPC['baotime'],
					'looktime' => $_GPC['looktime'],
					'status'=>0,
					'createtime' => TIMESTAMP
					);
		
	    pdo_insert('weixinmao_house_guestbao', $data);
		$id = pdo_insertid();
		
		if($id)
		{

			
			
			$list = array('msg'=>'提交成功','error'=>0);
		
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
					'ischeck'=>0,
					'type'=>$_GPC['saletype'],
					'uid'=>$_GPC['uid'],
					'name'=>$_GPC['name'],
					'tel'=>$_GPC['tel'],
					'houseareaid'=>$_GPC['houseareaid'],
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
	
	
	public function doPageCheckuserinfo()
	{
		global $_GPC, $_W;
		$sessionid = $_GPC['sessionid'];
		$uid = $_GPC['uid'];
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			
		}

	
		
		
		if($uid >0)
		{
			$fans = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
			if(!$fans){
				
				//return $this->result(1, '用户未授权');
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

			}else{
				//$userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
				$userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_agent') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
				if($userinfo)
				{
					if($userinfo['enabled'] ==0)
						{

					return $this->result(0, 'success',  array('msg'=>'正在审核中','error'=>3));


						}else{
					return $this->result(0, 'success',  array('msg'=>'用户已绑定','error'=>2));
				}
					
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
		if($sessionid !=$_W['session_id'])
		{
			return $this->result(0, 'success',  array('msg'=>'用户未授权','error'=>1));

		}
		
		
		$userinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_userinfo') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($userinfo)
				{
					//return $this->result(3, '用户已绑定');
					return $this->result(0, 'success',  $userinfo);
					
				}else{
					
				
				}
		
		
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
		
		return $this->result(0, 'success', $list);
		
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
	
	public function doPageMyagenthouse()
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
		{
			$tab = 'weixinmao_house_houseinfo';
			$housetype = 'houseinfo';
		}
		elseif($ordertype == 2)
		{
			$tab = 'weixinmao_house_oldhouseinfo';
				$housetype = 'oldhouseinfo';
		}
		elseif($ordertype == 3)
		{
			$tab = 'weixinmao_house_lethouseinfo';
				$housetype = 'lethouseinfo';

		}


			
		//echo $condition;
	//	$sql = "SELECT * FROM " . tablename($tab) ." WHERE  " .$condition. "  uniacid=:weid AND status =5 AND uid=".$uid." ORDER BY createtime DESC";
	//	echo $sql;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename($tab) ." WHERE  " .$condition. "  uniacid=:weid AND status =5 AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
		foreach($list  as $k=>$v)
			{
				
				
				$list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
				$list[$k]['thumb'] = tomedia($v['thumb']); 
				
				if($v['ischeck']==0){
				
						$list[$k]['statusStr'] = '审核中';
				
					
				}else{
				
						$list[$k]['statusStr'] = '已上架';

				}
				
			}
		$data = array('list'=>$list, 'housetype'=>$housetype);
		
		return $this->result(0, 'success', $data);
		
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

		if($ordertype == 1)
			$condition = " ispub = 1 AND ischeck =0 AND ";
		elseif($ordertype == 2)
			$condition = " paid = 1 AND status =0 AND ";
		elseif($ordertype == 3)
			$condition = " ispub = 1 AND ischeck =1 AND ";
		elseif($ordertype == 4)
			$condition = " status =-1 AND ";
			
		//echo $condition;
		
	    $list  = pdo_fetchall("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  " .$condition. "  uniacid=:weid AND uid=".$uid." ORDER BY createtime DESC",array(":weid" => $_W['uniacid']));
		
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
		
		return $this->result(0, 'success', $list);
		
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
	
	public function doPagePay() {
		global $_GPC, $_W;
	 //  $uid = $_SESSION['uid'];
	  $uid = $_GPC['uid'];
	   $pid = $_GPC['pid'];
       $ordertype = $_GPC['ordertype'];
       if($ordertype == 'lethouse')
       {
	   $activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));
	
	}elseif($ordertype == 'newhouse')
		{
  $activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));

		}elseif($ordertype == 'oldhouse')
		{
  $activeinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE  uniacid=:weid AND id=".$pid,array(":weid" => $_W['uniacid']));

		}
	   
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

	   if($ordertype == 'lethouse' || $ordertype =='oldhouse')
       {

       	 $title = $activeinfo['title'];

       }elseif($ordertype == 'newhouse')
		{
 			$title = $activeinfo['housename'];

		}
	  

	   
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

		$agnet = pdo_fetch("SELECT id FROM " . tablename('weixinmao_house_agent') ." WHERE  uniacid=:weid AND uid=".$uid,array(":weid" => $_W['uniacid']));
		if($agnet)
			{
				$list = array('msg'=>'保存失败','error'=>1);
			}else{

				$list = array('msg'=>'提交成功','error'=>0);

			}
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
					//'sex'=>$_GPC['sex'],
					'qq'=>$_GPC['qq'],
					'address'=>$_GPC['address'],
					//'email'=>$_GPC['email'],
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
		
		    $orderinfo =  pdo_fetch("SELECT id,pid ,type,uid FROM " . tablename('weixinmao_house_order') ." WHERE  uniacid=:weid AND orderid=".$tid,array(":weid" => $_W['uniacid']));


			
			 pdo_update('weixinmao_house_order',array('paid' => 1,'status' => 1,'paytime'=>TIMESTAMP), array('orderid'=>$tid));

			if($orderinfo['type'] == 'puboldhouse')
					{

						pdo_update('weixinmao_house_saleinfo',array('paid' => 1,'status' => 1), array('id'=>$orderinfo['pid']));

					}

			if($orderinfo['type'] == 'lethouse')
					{

			pdo_update('weixinmao_house_order',array('paid' => 1,'status' => 1), array('id'=>$orderinfo['id']));

			pdo_update('weixinmao_house_lethouseinfo',array('uid' => $orderinfo['uid'],'status' => 5), array('id'=>$orderinfo['pid']));

					}

			if($orderinfo['type'] == 'newhouse')
					{

			pdo_update('weixinmao_house_order',array('paid' => 1,'status' => 1), array('id'=>$orderinfo['id']));

			pdo_update('weixinmao_house_houseinfo',array('uid' => $orderinfo['uid'],'status' => 5), array('id'=>$orderinfo['pid']));

					}

			if($orderinfo['type'] == 'oldhouse')
					{

			pdo_update('weixinmao_house_order',array('paid' => 1,'status' => 1), array('id'=>$orderinfo['id']));

			pdo_update('weixinmao_house_oldhouseinfo',array('uid' => $orderinfo['uid'],'status' => 5), array('id'=>$orderinfo['pid']));

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
				$path="weixinmao_house/pages/oldhousedetail/index?id=".$id;
				$width=430;
				$post_data='{"path":"'.$path.'","width":'.$width.'}';
				$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_oldhouseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo);
				    return $this->result(0, 'success', $data);

		}



	public function doPagegetQrcodelethouse()
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
				$path="weixinmao_house/pages/lethousedetail/index?id=".$id;
				$width=430;
				$post_data='{"path":"'.$path.'","width":'.$width.'}';
				$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_lethouseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo);
				    return $this->result(0, 'success', $data);

		}


public function doPagegetQrcodenewhouse()
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
				$path="weixinmao_house/pages/newhousedetail/index?id=".$id;
				$width=430;
				$post_data='{"path":"'.$path.'","width":'.$width.'}';
				$url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
				$result=$this->api_notice_increment($url,$post_data,'POST');

				$uniacid = intval($_W['uniacid']);
					$path = "images/{$uniacid}/" . date('Y/m/');
					mkdirs(ATTACHMENT_ROOT . '/' . $path);
					$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, 'jpg');
					$filepath = $path . $filename;
					
				    file_put_contents('../attachment/'.$filepath, $result);
				     $houseinfo = pdo_fetch("SELECT * FROM " . tablename('weixinmao_house_houseinfo') ." WHERE  uniacid=:weid AND id=".$id,array(":weid" => $_W['uniacid']));
				     $houseinfo['thumb'] =  tomedia($houseinfo['thumb']);
				    $data =  array('myqrcode'=>tomedia($filepath),'houseinfo'=>$houseinfo);
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