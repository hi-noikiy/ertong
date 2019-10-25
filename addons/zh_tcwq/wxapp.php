<?php

defined('IN_IA') or exit('Access Denied');
class Zh_tcwqModuleWxapp extends WeModuleWxapp {
	    //获取openid
	public function doPageOpenid(){
		global $_W, $_GPC;
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$code=$_GPC['code'];
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
		function httpRequest($url,$data = null){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			if (!empty($data)){
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行
			$output = curl_exec($curl);
			curl_close($curl);
			return $output;
		}
		$res=httpRequest($url);
		print_r($res);
	}

     //登录用户信息
	public function doPageLogin(){
		global $_GPC, $_W;
		$openid=$_GPC['openid'];
		$res=pdo_get('zhtc_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
		if($openid and $openid!='undefined'){
			if($res){
				$user_id=$res['id'];
				if($_GPC['openid']){
					$data['openid']=$_GPC['openid'];
				}
				if($_GPC['img']){
					$data['img']=$_GPC['img'];
				}
				if($_GPC['name']){
					$data['name']=$_GPC['name'];
				}

				$res = pdo_update('zhtc_user', $data, array('id' =>$user_id));
				$user=pdo_get('zhtc_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
				echo json_encode($user);
			}else{
				$data['openid']=$_GPC['openid'];
				$data['img']=$_GPC['img'];
				$data['name']=$_GPC['name'];
				$data['uniacid']=$_W['uniacid'];
				$data['time']=time();
				$res2=pdo_insert('zhtc_user',$data);
				$user=pdo_get('zhtc_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
				echo json_encode($user);
			}
		}
	}
    //轮播图
	public function doPageAd(){
		global $_GPC, $_W;
		$where=" where uniacid=:uniacid and status=1";
		if($_GPC['cityname']){
			$where.=" and (cityname LIKE  concat('%', :name,'%') or cityname='全国版')";  
			$data[':name']=$_GPC['cityname'];
		}
		$data[':uniacid']=$_W['uniacid'];
		$sql="select * from ".tablename('zhtc_ad').$where." order by orderby asc";
		$res=pdo_fetchall($sql,$data);
		echo json_encode($res);
	}
	public function doPageUrl(){
		global $_GPC, $_W;
		echo $_W['attachurl'];
	}
//url
	public function doPageUrl2(){
		global $_W, $_GPC;
		echo $_W['siteroot'];
	}
    //主分类
	public function  doPageType(){
		global $_GPC, $_W;
		$res=pdo_getall('zhtc_type',array('uniacid'=>$_W['uniacid'],'state'=>1),array(),'','num asc');
		echo json_encode($res);
	}
    //子分类
	public function  doPageType2(){
		global $_GPC, $_W;
		$res=pdo_getall('zhtc_type2',array('type_id'=>$_GPC['id']),array(),'','num asc');
		echo json_encode($res);
	}
    //发帖
	public function doPagePosting(){
		global $_GPC, $_W;
		$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
     //   $data['details']=base64_encode($_GPC['details']);//帖子内容
        $data['details']=$_GPC['details'];//帖子内容
        $data['img']=$_GPC['img'];//帖子图片
        $data['user_id']=$_GPC['user_id'];//用户id
        $data['user_name']=$_GPC['user_name'];//姓名
        $data['user_tel']=$_GPC['user_tel'];//电话
        $data['type2_id']=$_GPC['type2_id'];//子分类id
        $data['type_id']=$_GPC['type_id'];//主分类id
        $data['money']=$_GPC['money'];//价格
        $data['top_type']=$_GPC['type'];//置顶类型
        $data['address']=$_GPC['address'];//帖子地址
        $data['store_id']=$_GPC['store_id'];
        $data['cityname']=$_GPC['cityname'];
        $data['lat']=$_GPC['lat'];
        $data['lng']=$_GPC['lng'];
        if($_GPC['type']){
        	$data['top']=1;
        }else{
        	$data['top']=2;
        }
        $data['time']=time();
        $data['uniacid']=$_W['uniacid'];
        if($system['tz_audit']==2){

        	$data['sh_time']=time();
        	if($_GPC['type']==1){
        		$data['dq_time']=$data['sh_time']+24*60*60;
        	}elseif($_GPC['type']==2){
        		$data['dq_time']=$data['sh_time']+24*60*60*7;
        	}elseif($_GPC['type']==3){
        		$data['dq_time']=$data['sh_time']+24*60*60*30;
        	}
        	$data['state']=2;
        }else{
        	$data['state']=1;
        }
      $data['hb_money']=$_GPC['hb_money'];//红包金额
      $data['hb_num']=$_GPC['hb_num'];//红包个数
      $data['hb_type']=$_GPC['hb_type'];//红包类型1.普通 2.口令 
      $data['hb_keyword']=$_GPC['hb_keyword'];//红包口令
      $data['hb_random']=$_GPC['hb_random'];//随机1.是 2否
      if($_GPC['hb_random']==1){
      	function sendRandBonus($total=0, $count=3){

      		$input     = range(0.01, $total, 0.01);
      		if($count>1){
      			$rand_keys = (array) array_rand($input, $count-1);
      			$last    = 0;
      			foreach($rand_keys as $i=>$key){
      				$current  = $input[$key]-$last;
      				$items[]  = $current;
      				$last    = $input[$key];
      			}
      		}
      		$items[]    = $total-array_sum($items);
      		return $items;
      	}
      	$hong=json_encode(sendRandBonus($_GPC['hb_money'],$_GPC['hb_num']));
      	$data['hong']= $hong;
      }

      $res=pdo_insert('zhtc_information',$data);
      $tz_id=pdo_insertid();
      $post_id=pdo_insertid();
      $a=json_decode(html_entity_decode($_GPC['sz']));
      $sz=json_decode(json_encode($a),true);
     // print_r($sz);die;
      if($res){
      	for($i=0;$i<count($sz);$i++){
      		$data2['label_id']=$sz[$i]['label_id'];
      		$data2['information_id']=$post_id ;
      		$res2=pdo_insert('zhtc_mylabel',$data2);
      	}
      //echo '1';

      	echo  $tz_id;
      }else{
      	echo '2';
      }
  }
//发帖加积分
  public function doPageAddScore(){
  	global $_GPC, $_W;
  	$user_id=$_GPC['user_id'];
  	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  	if($system['is_jf']==1 and $system['integral']>0){
  		$res= pdo_update('zhtc_user',array('total_score +='=>$system['integral']),array('id'=>$user_id));
  		if($res){
  			$data3['score']=$system['integral'];
  			$data3['user_id']=$user_id;
  			$data3['note']='发帖';
  			$data3['type']=1;
  			$data3['cerated_time']=date('Y-m-d H:i:s');
	        $data3['uniacid']=$_W['uniacid'];//小程序id
	        pdo_insert('zhtc_integral',$data3);//添加积分明细 
	    }
	}
}

      //修改帖子
public function doPageUpdPost(){
	global $_GPC, $_W;
	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	$tz=pdo_get('zhtc_information',array('id'=>$_GPC['id']));

	if($tz['details']!=$_GPC['details']){
           $data['details']=$_GPC['details'];//帖子内容
       }
       if($tz['img']!=$_GPC['img']){
            $data['img']=$_GPC['img'];//帖子图片
        }
        if($tz['user_name']!=$_GPC['user_name']){
           $data['user_name']=$_GPC['user_name'];//姓名
       }
       if($tz['user_tel']!=$_GPC['user_tel']){
           $data['user_tel']=$_GPC['user_tel'];//电话
       }
       if($tz['address']!=$_GPC['address']){
          $data['address']=$_GPC['address'];//帖子地址
      }
      if($tz['cityname']!=$_GPC['cityname']){
          $data['cityname']=$_GPC['cityname'];//帖子地址
      }

      $data['uniacid']=$_W['uniacid'];
      if($system['tz_audit']==2){
      	$data['state']=2;
      }else{
      	$data['state']=1;
      }
      $res=pdo_update('zhtc_information',$data,array('id'=>$_GPC['id']));
      pdo_delete('zhtc_mylabel',array('information_id'=>$_GPC['id']));
     //  $a=json_decode(html_entity_decode($_GPC['sz']));
     //  $sz=json_decode(json_encode($a),true);
     // // print_r($sz);die;
      if($res){
        //  for($i=0;$i<count($sz);$i++){
        //   $data2['label_id']=$sz[$i]['label_id'];
        //   $data2['information_id']=$_GPC['id'] ;
        //   $res2=pdo_insert('zhtc_mylabel',$data2);
        // }
      	echo '1';
      }else{
      	echo '2';
      }
  }
//删除帖子
  public function doPageDelPost(){
  	global $_GPC, $_W;
  	$res=pdo_update('zhtc_information',array('del'=>1),array('id'=>$_GPC['id']));
  	if($res){
  		echo '1';
  	}else{
  		echo '2';
  	}
  }






  //帖子点赞
  public function doPageLike(){
  	global $_GPC, $_W;
  	$data['information_id']=$_GPC['information_id'];
  	$data['user_id']=$_GPC['user_id'];
  	$res=pdo_get('zhtc_like',$data);
  	if($res){
  		echo  '不能重复点赞!';
  	}else{
  		pdo_insert('zhtc_like',$data);
  		pdo_update('zhtc_information',array('givelike +='=>1),array('id'=>$_GPC['information_id']));
file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=dzMessage&m=zh_tcwq&user_id=".$_GPC['user_id']."&information_id=".$_GPC['information_id']);//模板消息
echo '1';
}

}
 //资讯点赞
public function doPageZxLike(){
	global $_GPC, $_W;
	$data['zx_id']=$_GPC['zx_id'];
	$data['user_id']=$_GPC['user_id'];
	$res=pdo_get('zhtc_like',$data);
	if($res){
		echo  '不能重复点赞!';
	}else{
		$res2= pdo_insert('zhtc_like',$data);
		if($res2){
			echo '1';
		}else{
			echo  '2';
		}

	}

}
//资讯点赞列表
public function doPageZxLikeList(){
	global $_GPC, $_W;
	$sql="select a.*,b.img as user_img ,b.name as user_name from " . tablename("zhtc_like") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.zx_id=:zx_id  ORDER BY a.id DESC";
	$res=pdo_fetchall($sql,array(':zx_id'=>$_GPC['zx_id']));
	echo json_encode($res);

}


    //查看我的帖子
public function doPageMyPost(){
	global $_GPC, $_W;
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=$_GPC['pagesize'];
	$sql="select a.*,b.type_name,c.name as type2_name from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_type") . " b on b.id=a.type_id  " . " left join " . tablename("zhtc_type2") . " c on a.type2_id=c.id   WHERE a.user_id=:user_id and a.del=2 ORDER BY a.id DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($select_sql,array(':user_id'=>$_GPC['user_id']));
	echo json_encode($res);
}
//查看商家的帖子
public function doPageStorePost(){
	global $_GPC, $_W;
	$res=pdo_getall('zhtc_information',array('store_id'=>$_GPC['store_id'],'del'=>2));
	echo json_encode($res);
}
  //查看商家帖子列表
public function doPageStorePostList(){
	global $_GPC, $_W;
	$sql="select a.*,b.logo from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  WHERE a.uniacid=:uniacid and a.store_id!='' and a.del=2 ORDER BY a.sh_time DESC";
	$res=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid']));
    //  $res=pdo_getall('zhtc_information',array('uniacid'=>$_W['uniacid'],'store_id !='=>''));
	echo json_encode($res);
}
    //查看帖子详情
public function doPagePostInfo(){
	global $_GPC, $_W;
	pdo_update('zhtc_information',array('views +='=>1),array('id'=>$_GPC['id']));
	$sql="select a.*,b.type_name,c.name as type2_name,d.img as user_img,e.logo,e.coordinates from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_type") . " b on b.id=a.type_id  " . " left join " . tablename("zhtc_type2") . " c on a.type2_id=c.id " . " left join " . tablename("zhtc_user") . " d on a.user_id=d.id". " left join " . tablename("zhtc_store") . " e on a.store_id=e.id  WHERE a.id=:id  ORDER BY a.id DESC";
	$res=pdo_fetch($sql,array(':id'=>$_GPC['id']));
//$res['details']=base64_decode($res['details']);
	$sql2="select a.*,b.img as user_img from " . tablename("zhtc_like") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.information_id=:id  ORDER BY a.id DESC";
	$res2=pdo_fetchall($sql2,array(':id'=>$_GPC['id']));

	$pl="select a.id,a.details,a.time,b.name as user_name,b.img as user_img from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id where a.information_id={$_GPC['id']} and a.bid=0 and a.hf_id=0 order by a.time ASC";
	$pl=pdo_fetchall($pl);
    //$res=pdo_getall('zhtc_testpl',array('tid'=>$_GPC['tid'],'bid'=>0,'hf_id'=>0),array(),'','time ASC');
	for($i=0;$i<count($pl);$i++){
		$pl2="select a.details,FROM_UNIXTIME(a.time) as time,a.user_id,b.name as user_name,b.img as user_img,c.name as hf_name from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id left join " . tablename("zhtc_user") . " c on c.id=a.hf_id where a.bid={$pl[$i]['id']} order by a.time ASC";
		$pl2=pdo_fetchall($pl2);
		$pl[$i]['hf']=$pl2;
	}

    // $sql3="select a.*,b.img as user_img,b.name from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.information_id=:id  ORDER BY a.id DESC";
    // $res3=pdo_fetchall($sql3,array(':id'=>$_GPC['id']));

	$sql4="select a.*,b.label_name from " . tablename("zhtc_mylabel") . " a"  . " left join " . tablename("zhtc_label") . " b on b.id=a.label_id  WHERE a.information_id=:id  ORDER BY a.id DESC";
	$res4=pdo_fetchall($sql4,array(':id'=>$_GPC['id']));
	$data['tz']=$res;
	$data['dz']=$res2;
	$data['pl']=$pl;
	$data['label']=$res4;
	echo json_encode($data);
}
    //查看二级分类下的帖子
public function doPagePostList(){
	global $_GPC, $_W;
	$time=time();
  // pdo_update('zhtc_information',array('top'=>2),array('dq_time <'=>time(),'state'=>2,'uniacid'=>$_W['uniacid']));
	$list=pdo_getall('zhtc_information',array('uniacid'=>$_W['uniacid'],'state'=>2));
	$where=" WHERE a.type2_id=:type2_id and a.state=:state and a.del=2";
	$data[':type2_id']=$_GPC['type2_id'];
	$data[':state']=2;

	if($_GPC['cityname']){
		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='') ";  
		$data[':name']=$_GPC['cityname'];
	}
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=10;
	$sql="select a.*,b.img as user_img,c.logo from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id". " left join " . tablename("zhtc_store") . " c on c.id=a.store_id".$where." ORDER BY a.top asc,a.sh_time DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($select_sql,$data);

	$sql2="select a.*,b.label_name from " . tablename("zhtc_mylabel") . " a"  . " left join " . tablename("zhtc_label") . " b on b.id=a.label_id";
	$res2=pdo_fetchall($sql2);

  // $res2=pdo_getall('zhtc_label',array('uniacid'=>$_W['uniacid']));
	$data2=array();

	for($i=0;$i<count($res);$i++){
		$data=array();
		for($k=0;$k<count($res2);$k++){
			if($res[$i]['id']==$res2[$k]['information_id']){
				$data[]=array(
					'label_name'=>$res2[$k]['label_name']
					);
			}  
		}
		$data2[]=array(
			'tz'=>$res[$i],
			'label'=>$data
			);
	}


	echo json_encode($data2);
}

    //大分类帖子列表
public function doPageList(){
	global $_GPC, $_W;
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=10;
	$time=time();

	$where=" where a.type_id=:type_id and a.state=:state and a.del=2 ";
	$data[':type_id']=$_GPC['type_id'];
	$data[':state']=2;
	if($_GPC['cityname']){
		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='')";  
		$data[':name']=$_GPC['cityname'];
	}
	$sql="select a.*,b.img as user_img,c.logo from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id". " left join " . tablename("zhtc_store") . " c on c.id=a.store_id".$where." ORDER BY a.top asc,a.sh_time DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($select_sql,$data);
	$sql2="select a.*,b.label_name from " . tablename("zhtc_mylabel") . " a"  . " left join " . tablename("zhtc_label") . " b on b.id=a.label_id";
	$res2=pdo_fetchall($sql2);
	$data2=array();
	for($i=0;$i<count($res);$i++){
		$data=array();
		for($k=0;$k<count($res2);$k++){
			if($res[$i]['id']==$res2[$k]['information_id']){
				$data[]=array(
					'label_name'=>$res2[$k]['label_name']
					);
			}  
		}
		$data2[]=array(
			'tz'=>$res[$i],
			'label'=>$data
			);
	}

	echo json_encode($data2);
}
//所有帖子列表
public function doPageList2(){
	global $_GPC, $_W;
	$time=time();
	$gx=" update " . tablename("zhtc_information") . " a inner join (select id from " . tablename("zhtc_information") . "  where dq_time<{$time} and  state=2 and  top=1 and uniacid={$_W['uniacid']} order by dq_time ASC limit 0,100 ) b on a.id =b.id set a.top =2"; 
	pdo_query($gx);
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=10;
	$where=" WHERE a.state=:state and a.del=2  and a.user_id != 0 and a.uniacid=:uniacid";
	$data[':state']=2;
	$data[':uniacid']=$_W['uniacid'];
	if($_GPC['fj_tz']==1){
		$lat=$_GPC['lat'];
        $lng=$_GPC['lng'];
		$sql="select a.*,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-lat*PI()/180)/2),2)+COS($lat*PI()/180)*COS(lat*PI()/180)*POW(SIN(($lng*PI()/180-lng*PI()/180)/2),2)))*1000) AS juli,b.img as user_img,c.type_name,d.name as type2_name  from" . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id " . " left join " . tablename("zhtc_type") . " c on a.type_id=c.id " . " left join " . tablename("zhtc_type2") . " d on a.type2_id=d.id ".$where." ORDER BY juli asc ";
	}else{
	if($_GPC['keywords']){
		$where.=" and (a.details LIKE  concat('%', :name,'%') || a.user_name LIKE  concat('%', :name,'%')) ";  
		$data[':name']=$_GPC['keywords'];
	}
	if($_GPC['type_id']){
		$where.=" and  a.type_id=:type_id ";  
		$data[':type_id']=$_GPC['type_id'];
	}
	if($_GPC['cityname']){
		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='')";  
		$data[':name']=$_GPC['cityname'];
	}

	$sql="select a.*,b.img as user_img,c.type_name,d.name as type2_name  from" . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id " . " left join " . tablename("zhtc_type") . " c on a.type_id=c.id " . " left join " . tablename("zhtc_type2") . " d on a.type2_id=d.id ".$where." ORDER BY a.top asc,a.sh_time DESC";
}
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($select_sql,$data);
	$sql2="select a.*,b.label_name from " . tablename("zhtc_mylabel") . " a"  . " left join " . tablename("zhtc_label") . " b on b.id=a.label_id";
	$res2=pdo_fetchall($sql2);
//$res2=pdo_getall('zhtc_label',array('uniacid'=>$_W['uniacid']));
	$data2=array();
	for($i=0;$i<count($res);$i++){
		$data=array();
		for($k=0;$k<count($res2);$k++){
			if($res[$i]['id']==$res2[$k]['information_id']){
				$data[]=array(
					'label_name'=>$res2[$k]['label_name']
					);
			}  
		}
		$data2[]=array(
			'tz'=>$res[$i],
			'label'=>$data
			);
	}
	$name_list='';
	$name_list = array_map('array_shift', $res);
  //更新所有帖子阅读数量
	$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'sj_max');
	$rand=empty($sys['sj_max'])?1:$sys['sj_max'];
	pdo_update('zhtc_information',array('views +='=>rand(1,$rand)),array('id'=>$name_list));
	echo json_encode($data2);
}
    //查看二级分类下的标签
public function doPageLabel(){
	global $_GPC, $_W;
	$res=pdo_getall('zhtc_label',array('type2_id'=>$_GPC['type2_id']));
	echo json_encode($res);
}
    //帖子评论
public function doPageComments(){
	global $_GPC, $_W;
	$data['information_id']=$_GPC['information_id'];
	$data['user_id']=$_GPC['user_id'];
	$data['details']=$_GPC['details'];
	if($_GPC['bid']){
		$data['bid']=$_GPC['bid'];
	}
	if($_GPC['hf_id']){
		$data['hf_id']=$_GPC['hf_id'];
	}
	$data['time']=time();
	$res=pdo_insert('zhtc_comments',$data);
	$assess_id=pdo_insertid();
	if($res){
		if($_GPC['bid'] || $_GPC['hf_id']){
     file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=hpMessage2&m=zh_tcwq&id=".$assess_id);//模板消息
 }
   file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=TzhfMessage&m=zh_tcwq&pl_id=".$assess_id);//模板消息
   echo $assess_id;
}else{
	echo 'error';
}
}
//帖子评论成功模板消息
public function doPageTzhfMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息

	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res2=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$sql="select a.user_id,a.details,a.information_id,a.time,b.name as user_name,b.openid from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.id=:id ";
		$res=pdo_fetch($sql,array(':id'=>$_GPC['pl_id']));
		$tz=pdo_get('zhtc_information',array('id'=>$res['information_id']));
		if($tz['user_id']){
			$user=pdo_get('zhtc_user',array('id'=>$tz['user_id']));
			$userid=$tz['user_id'];
		}else{
			$store=pdo_get('zhtc_store',array('id'=>$tz['store_id']));
			$userid=$store['user_id'];
			$user=pdo_get('zhtc_user',array('id'=>$store['user_id']));
		}
		$time=date("Y-m-d H:i:s",$res['time']);
		$time2=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$userid} and UNIX_TIMESTAMP(time)>={$time2} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res2["tid3"].'",
			"page":"zh_tcwq/pages/infodetial/infodetial?id='.$res['information_id'].'",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "'.$res['details'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value":"'.$res['user_name'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$time.'",
					"color": "#173177"
				}

			}   
		}';
             // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);
}


//测试评论
public function doPageTestPl(){
	global $_W, $_GPC;
	$sql="select a.id,a.details,a.time,b.name as user_name,b.img as user_img from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id where a.information_id={$_GPC['tid']} and a.bid=0 and a.hf_id=0 order by a.time ASC";
	$res=pdo_fetchall($sql);
    //$res=pdo_getall('zhtc_testpl',array('tid'=>$_GPC['tid'],'bid'=>0,'hf_id'=>0),array(),'','time ASC');
	for($i=0;$i<count($res);$i++){
		$sql2="select a.details,a.time,b.name as user_name,b.img as user_img,c.name as hf_name from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id left join " . tablename("zhtc_user") . " c on c.id=a.hf_id where a.bid={$res[$i]['id']} order by a.time ASC";
		$res2=pdo_fetchall($sql2);
		$res[$i]['hf']=$res2;
	}
	echo json_encode($res);
}
//测试评论
public function doPageTestPl2(){
	global $_W, $_GPC;
	$sql="select a.id,a.content,a.time,b.name as user_name,b.img as user_img from " . tablename("zhtc_testpl") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id where a.tid={$_GPC['tid']} and a.bid=0 and a.hf_id=0 order by a.time ASC";
	$res=pdo_fetchall($sql);
    //$res=pdo_getall('zhtc_testpl',array('tid'=>$_GPC['tid'],'bid'=>0,'hf_id'=>0),array(),'','time ASC');
	for($i=0;$i<count($res);$i++){
		$sql2="select a.content,a.time,b.name as user_name,b.img as user_img,c.name as hf_name from " . tablename("zhtc_testpl") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id left join " . tablename("zhtc_user") . " c on c.id=a.hf_id where a.bid={$res[$i]['id']} order by a.time ASC";
		$res2=pdo_fetchall($sql2);
		$res[$i]['hf']=$res2;
	}
	print_r($res);
	echo json_encode($res);
}


     //商家评分
public function doPageStoreComments(){
	global $_GPC, $_W;

	$data['store_id']=$_GPC['store_id'];
	$data['user_id']=$_GPC['user_id'];
	$data['details']=$_GPC['details'];
	$data['score']=$_GPC['score'];
	$data['time']=time();
	$res=pdo_insert('zhtc_comments',$data);
	$assess_id=pdo_insertid();
	if($res){
		$total=pdo_get('zhtc_comments', array('store_id'=>$_GPC['store_id']), array('sum(score) as total'));
		$count=pdo_get('zhtc_comments', array('store_id'=>$_GPC['store_id']), array('count(id) as count'));
		if($total['total']>0 and $count['count']>0){
			$pf=($total['total']/$count['count']);
		}else{
			$pf=0;
		}
		pdo_update('zhtc_store',array('score'=>$pf),array('id'=>$_GPC['store_id']));
		echo $assess_id;
	}else{
		echo '2';
	}
}
    //回复
public function doPageReply(){
	global $_GPC, $_W;
	$data['reply']=$_GPC['reply'];
	$data['hf_time']=time();
	$res=pdo_update('zhtc_comments',$data,array('id'=>$_GPC['id']));
	if($res){
file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=hpMessage&m=zh_tcwq&id=".$_GPC['id']);//模板消息
echo '1';
}else{
	echo '2';
}
}
    //总浏览量
public function doPageViews(){
	global $_W, $_GPC;
	$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($sys['zfwl_max']){
		$rand=$sys['zfwl_max'];
	}else{
		$rand=1;
	}
	pdo_update('zhtc_system',array('total_num +='=>rand(1,$rand)),array('uniacid'=>$_W['uniacid']));
	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
}
   //帖子总量
public function doPageNum(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_information',array('uniacid'=>$_W['uniacid']));
	$total=count($res);
	$res2=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	echo count($res)+$res2['tz_num'];

}

   //置顶
public function doPageTop(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_top',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
	echo json_encode($res);
}

    //支付
public function doPagePay(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/wxpay2.php';
	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($res['pt_name']){
		$res['pt_name']=$res['pt_name'];
	}else{
		$res['pt_name']='同城小程序';
	}
	
	$appid=$res['appid'];
        $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
        $mch_id=$res['mchid'];
        $key=$res['wxkey'];
        $out_trade_no = $mch_id. time();
        $total_fee =$_GPC['money'];
            if(empty($total_fee)) //押金
            {
            	$body = $res['pt_name'];
            	$total_fee = floatval(99*100);
            }else{
            	$body = $res['pt_name'];
            	$total_fee = floatval($total_fee*100);
            }
            if($_GPC['order_id']){
            	pdo_update('zhtc_order',array('out_trade_no'=>$out_trade_no),array('id'=>$_GPC['order_id']));
            }
            $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);
            $return=$weixinpay->pay();
            echo json_encode($return);
        }
   //商家入驻
        public function doPageStore(){
        	global $_W, $_GPC;
        	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
     		$data['user_id']=$_GPC['user_id'];//用户id
     		$data['store_name']=$_GPC['store_name'];//商家名称
     		$data['address']=$_GPC['address'];//地址
     		$data['announcement']=$_GPC['announcement'];//公告
     		$data['storetype_id']=$_GPC['storetype_id'];//行业分类id
     		$data['storetype2_id']=$_GPC['storetype2_id'];//之行业分类id
     		$data['area_id']=$_GPC['area_id'];//区域id
     		$data['start_time']=$_GPC['start_time'];//营业时间
     		$data['end_time']=$_GPC['end_time'];//营业时间
     		$data['keyword']=$_GPC['keyword'];//关键字
     		$data['skzf']=$_GPC['skzf'];//刷卡支付
     		$data['wifi']=$_GPC['wifi'];//wifi
     		$data['mftc']=$_GPC['mftc'];//免费停车
     		$data['jzxy']=$_GPC['jzxy'];//禁止吸烟
     		$data['tgbj']=$_GPC['tgbj'];//提供包间
     		$data['sfxx']=$_GPC['sfxx'];//沙发休闲
     		$data['tel']=$_GPC['tel'];//电话
     		$data['logo']=$_GPC['logo'];//商家logo
            $data['vr_link']=$_GPC['vr_link'];//vr
         	$data['weixin_logo']=$_GPC['weixin_logo'];//老板微信
         	$data['ad']=$_GPC['ad'];//轮播图
            $data['img']=$_GPC['img'];//商家图片
            $data['yyzz_img']=$_GPC['yyzz_img'];
            $data['sfz_img']=$_GPC['sfz_img'];
            $data['start_time']=$_GPC['start_time'];
            $data['end_time']=$_GPC['end_time'];
            $data['cityname']=$_GPC['cityname'];
            if($system['sj_audit']==2){
            	$data['sh_time']=time();

            	if($_GPC['type']==1){
            		$data['dq_time']=$data['sh_time']+24*60*60*7;
            	}elseif($_GPC['type']==2){
            		$data['dq_time']=$data['sh_time']+24*60*60*182;
            	}elseif($_GPC['type']==3){
            		$data['dq_time']=$data['sh_time']+24*60*60*365;
            	}
            	$data['state']=2;
            }else{
            	$data['state']=1;
            }
            if($_GPC['type']){
            	$data['type']=$_GPC['type'];//入驻类型
            	$data['time_over']=2;
            }
            $data['time']=date('Y-m-d H:i:s',time());
         		$data['money']=$_GPC['money'];//付款价格
         		$data['details']=$_GPC['details'];//商家简介
            $data['coordinates']=$_GPC['coordinates'];//坐标
            $data['uniacid']=$_W['uniacid'];
            $res=pdo_insert('zhtc_store',$data);
            $store_id=pdo_insertid();
            if($res){
              //echo '1';
            	echo $store_id;
            }else{
            	echo '2';
            }
        }
          //修改入驻
        public function doPageUpdStore(){
        	global $_W, $_GPC;
        	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
            //$data['user_id']=$_GPC['user_id'];//用户id
            $data['store_name']=$_GPC['store_name'];//商家名称
            $data['address']=$_GPC['address'];//地址
            $data['announcement']=$_GPC['announcement'];//公告
            $data['storetype_id']=$_GPC['storetype_id'];//行业分类id
            $data['storetype2_id']=$_GPC['storetype2_id'];//之行业分类id
            $data['area_id']=$_GPC['area_id'];//区域id
            $data['start_time']=$_GPC['start_time'];//营业时间
         	  $data['end_time']=$_GPC['end_time'];//营业时间
            $data['keyword']=$_GPC['keyword'];//关键字
            $data['skzf']=$_GPC['skzf'];//刷卡支付
            $data['wifi']=$_GPC['wifi'];//wifi
            $data['mftc']=$_GPC['mftc'];//免费停车
            $data['jzxy']=$_GPC['jzxy'];//禁止吸烟
            $data['tgbj']=$_GPC['tgbj'];//提供包间
            $data['sfxx']=$_GPC['sfxx'];//沙发休闲
            $data['tel']=$_GPC['tel'];//电话
            $data['logo']=$_GPC['logo'];//商家logo
            $data['vr_link']=$_GPC['vr_link'];//vr
            $data['weixin_logo']=$_GPC['weixin_logo'];//老板微信
            $data['ad']=$_GPC['ad'];//轮播图
            $data['img']=$_GPC['img'];//商家图片
            if($system['sj_audit']==2){ 
            	$data['state']=2;
            }else{
            	$data['state']=1;
            }
            $data['money']=$_GPC['money'];//付款价格
            $data['details']=$_GPC['details'];//商家简介
            $data['coordinates']=$_GPC['coordinates'];//坐标
            $data['uniacid']=$_W['uniacid'];
            $res=pdo_update('zhtc_store',$data,array('id'=>$_GPC['id']));
            if($res){
            	echo '1';
            }else{
            	echo '2';
            }

        }
   //商家列表
        public function doPageStoreList(){
        	global $_W, $_GPC;
        	$time=time();
        	$gx=" update " . tablename("zhtc_store") . " a inner join (select id from " . tablename("zhtc_store") . "  where dq_time<={$time} and time_over =2 and state=2 and uniacid={$_W['uniacid']} order by dq_time ASC limit 0,100 ) b on a.id =b.id set a.time_over =1"; 
        	pdo_query($gx);

        	$where=" where uniacid=:uniacid and time_over !=1 and state=2";
        	$data[':uniacid']=$_W['uniacid'];
        	if($_GPC['lat']){
		  	$lat=$_GPC['lat'];
			  }else{
			  	$lat='30.592760';
			  }
			  if($_GPC['lng']){
			  	$lng=$_GPC['lng'];
			  }else{
			  	$lng='114.305250';
			  }
        	if($_GPC['storetype_id']){
        		$where.=" and storetype_id=".$_GPC['storetype_id'];
        	}
        	if($_GPC['storetype2_id']){
        		$where.=" and storetype2_id=".$_GPC['storetype2_id'];
        	}
        	if($_GPC['keywords']){
        		$where.=" and (store_name LIKE  concat('%', :name,'%') or keyword LIKE  concat('%', :name,'%'))";  
        		$data[':name']=$_GPC['keywords'];
        	}
        	if($_GPC['cityname']){
        		$where.=" and (cityname LIKE  concat('%', :name,'%') || cityname='')";  
        		$data[':name']=$_GPC['cityname'];
        	}
        	$pageindex = max(1, intval($_GPC['page']));
        	if($_GPC['pagesize']){
        		$pagesize=$_GPC['pagesize'];
        	}else{
        		$pagesize=10;
        	}
        	 if($_GPC['type']==1){
        		$sql= "select *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)/2),2)+COS($lat*PI()/180)*COS(SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)*POW(SIN(($lng*PI()/180-SUBSTRING_INDEX(coordinates, ',', -1)*PI()/180)/2),2)))*1000) AS juli from".tablename('zhtc_store').$where." order by is_top asc,juli asc";
        	}elseif($_GPC['type']==2){
        		$sql= "select *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)/2),2)+COS($lat*PI()/180)*COS(SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)*POW(SIN(($lng*PI()/180-SUBSTRING_INDEX(coordinates, ',', -1)*PI()/180)/2),2)))*1000) AS juli from".tablename('zhtc_store').$where." order by is_top asc,sh_time DESC";
        	}elseif($_GPC['type']==3){
        		$sql= "select *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)/2),2)+COS($lat*PI()/180)*COS(SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)*POW(SIN(($lng*PI()/180-SUBSTRING_INDEX(coordinates, ',', -1)*PI()/180)/2),2)))*1000) AS juli from".tablename('zhtc_store').$where." order by is_top asc,views DESC";
        	}else{
        		$sql= "select *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)/2),2)+COS($lat*PI()/180)*COS(SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)*POW(SIN(($lng*PI()/180-SUBSTRING_INDEX(coordinates, ',', -1)*PI()/180)/2),2)))*1000) AS juli from".tablename('zhtc_store').$where." order by is_top asc";
        	}
        	// echo $sql;die;

        	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        	$res = pdo_fetchall($select_sql,$data);
        	$name_list='';
        	$name_list = array_map('array_shift', $res);
        	$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'sj_max2');
        	$rand=empty($sys['sj_max2'])?1:$sys['sj_max2'];
        	pdo_update('zhtc_store',array('views +='=>rand(1,$rand)),array('id'=>$name_list));
          // $res=pdo_getall('zhtc_store',array('uniacid'=>$_W['uniacid'],'time_over !='=>1),array(),'','num asc');
        	echo json_encode($res);	
        }
       //热门商家
        public function doPageRmStore(){
        	global $_W, $_GPC;
        	$where=" where uniacid=:uniacid and time_over !=1 and state=2 and is_rm=1";
        	$data[':uniacid']=$_W['uniacid'];
        	if($_GPC['cityname']){
        		$where.=" and (cityname LIKE  concat('%', :name,'%') || cityname='')";  
        		$data[':name']=$_GPC['cityname'];
        	}
        	$sql= "select * from".tablename('zhtc_store').$where." order by is_top,sh_time DESC,num asc"; 
        	$res = pdo_fetchall($sql,$data);
        	echo json_encode($res);
        }
    //分类下商家列表
        public function doPageTypeStoreList(){
        	global $_W, $_GPC;
        	$pageindex = max(1, intval($_GPC['page']));
        	$pagesize=$_GPC['pagesize'];
        	$time=time();
         //pdo_update('zhtc_store',array('time_over'=>1),array('dq_time <='=>time(),'state'=>2,'uniacid'=>$_W['uniacid']));
        	if($_GPC['storetype2_id']){
        		$sql= "select * from".tablename('zhtc_store')." where uniacid=:uniacid and time_over !=1 and storetype2_id=:storetype_id and state=2 order by id DESC"; 
        		$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        		$res = pdo_fetchall($select_sql,array(':uniacid'=>$_W['uniacid'],':storetype_id'=>$_GPC['storetype2_id']));
        	}else{
        		$sql= "select * from".tablename('zhtc_store')." where uniacid=:uniacid and time_over !=1 and storetype_id=:storetype_id and state=2 order by id DESC"; 
        		$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        		$res = pdo_fetchall($select_sql,array(':uniacid'=>$_W['uniacid'],':storetype_id'=>$_GPC['storetype_id']));
        	}
        	echo json_encode($res);
        }
   //查看我的商家信息
        public function doPageMyStore(){
        	global $_W, $_GPC;
        	$sql="select a.*,b.type_name,c.name as type2_name from " . tablename("zhtc_store") . " a"  . " left join " . tablename("zhtc_storetype") . " b on b.id=a.storetype_id  " . " left join " . tablename("zhtc_storetype2") . " c on a.storetype2_id=c.id  WHERE a.id=:store_id  ORDER BY a.id DESC";
        	$res=pdo_fetch($sql,array(':store_id'=>$_GPC['user_id']));
        	echo json_encode($res);
        }
//商家微信登录
        public function doPageSjdLogin(){
        	global $_W, $_GPC;
        	$sql="select a.*,b.type_name,c.name as type2_name from " . tablename("zhtc_store") . " a"  . " left join " . tablename("zhtc_storetype") . " b on b.id=a.storetype_id  " . " left join " . tablename("zhtc_storetype2") . " c on a.storetype2_id=c.id  WHERE a.user_id=:user_id and a.uniacid=:uniacid  ORDER BY a.id DESC";
        	$res=pdo_fetch($sql,array(':user_id'=>$_GPC['user_id'],':uniacid'=>$_W['uniacid']));
        	echo json_encode($res);
        }
   //商家详情
        public function doPageStoreInfo(){
        	global $_W, $_GPC;
        	pdo_update('zhtc_store',array('views +='=>1),array('id'=>$_GPC['id']));
        	$res=pdo_getall('zhtc_store',array('id'=>$_GPC['id']));
        	function video($video) {
        		$vid = trim(strrchr($video, '/'), '/');
        		$vid = substr($vid, 0, -5);
        		$json = file_get_contents("http://vv.video.qq.com/getinfo?vids=" . $vid . "&platform=101001&charge=0&otype=json");
            // echo $json;die;
        		$json = substr($json, 13);
        		$json = substr($json, 0, -1);
        		$a = json_decode(html_entity_decode($json));
        		$sz = json_decode(json_encode($a), true);
            // print_R($sz);die;
        		$url = $sz['vl']['vi']['0']['ul']['ui']['0']['url'];
        		$fn = $sz['vl']['vi']['0']['fn'];
        		$fvkey = $sz['vl']['vi']['0']['fvkey'];
        		$url = $url . $fn . '?vkey=' . $fvkey;
        		return $url;
        	}
        	$res[0]['video']=video($res[0]['video']);
        	$sql2="select a.*,b.img as user_img,b.name from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.store_id=:id  ORDER BY a.id DESC";
        	$res2=pdo_fetchall($sql2,array(':id'=>$_GPC['id']));
        	$data['store']=$res;
        	$data['pl']=$res2;
        	echo json_encode($data);
        }

   //区域信息
        public function doPageArea(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_area',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
        	echo json_encode($res);
        }
   //行业分类
        public function doPageStoreType(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_storetype',array('uniacid'=>$_W['uniacid'],'state'=>1),array(),'','num asc');
        	echo json_encode($res);
        }
   //二级行业分类
        public function doPageStoreType2(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_storetype2',array('type_id'=>$_GPC['type_id'],'state'=>1),array(),'','num asc');
        	echo json_encode($res);
        }
         //黄页分类
        public function doPageYellowType(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_yellowtype',array('uniacid'=>$_W['uniacid'],'state'=>1),array(),'','num asc');
        	echo json_encode($res);
        }
        //二级黄页分类
        public function doPageYellowType2(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_yellowtype2',array('type_id'=>$_GPC['type_id'],'state'=>1),array(),'','num asc');
        	echo json_encode($res);
        }

   //地图
        public function doPageMap() {
        	global $_GPC, $_W;
        	$op=$_GPC['op'];
        	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
        	$url="https://apis.map.qq.com/ws/geocoder/v1/?location=".$op."&key=".$res['mapkey']."&get_poi=0&coord_type=1";
        	$html = file_get_contents($url);
        	echo  $html;
        }
    //系统设置
        public function doPageSystem(){
        	global $_W, $_GPC;
        	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
        	echo json_encode($res);
        }
//公告列表
        public function doPageNews(){
        	global $_GPC, $_W;
        	$where=" where uniacid=:uniacid and state=1";
        	if($_GPC['cityname']){
        		$where.=" and (cityname LIKE  concat('%', :name,'%') || cityname='全国版')";  
        		$data[':name']=$_GPC['cityname'];
        	}
        	$data[':uniacid']=$_W['uniacid'];
        	$sql="select * from ".tablename('zhtc_news').$where." order by num asc";
        	$res=pdo_fetchall($sql,$data);
        	echo json_encode($res);
        }
    //公告详情
        public function doPageNewsInfo(){
        	global $_W, $_GPC;
        	$res=pdo_get('zhtc_news',array('id'=>$_GPC['id']));
        	echo json_encode($res);
        }

//收藏
        public function doPageCollection(){
        	global $_W, $_GPC;
        	if($_GPC['information_id']){
        		$data['information_id']=$_GPC['information_id'];
        	}
        	if($_GPC['store_id']){
        		$data['store_id']=$_GPC['store_id'];
        	}
        	$data['user_id']=$_GPC['user_id'];
        	$list=pdo_get('zhtc_share',$data);
        	if($list){
        		pdo_delete('zhtc_share',$data);
        	}else{
        		$res=pdo_insert('zhtc_share',$data);
        		if($res){
        			echo '1';
        		}else{
        			echo '2';
        		}
        	} 
        }
  //查看是否收藏
        public function doPageIsCollection(){
        	global $_W, $_GPC;
        	if($_GPC['information_id']){
        		$data['information_id']=$_GPC['information_id'];
        	}
        	if($_GPC['store_id']){
        		$data['store_id']=$_GPC['store_id'];
        	}
        	$data['user_id']=$_GPC['user_id'];
        	$list=pdo_get('zhtc_share',$data);
        	if($list){
        		echo '1';
        	}else{
        		echo '2';
        	}
        }
    //我的收藏
        public function doPageMyCollection(){
        	global $_W, $_GPC;
        	$sql="select a.*,b.img,b.details,b.time,b.top,b.user_img2,c.type_name,d.name as type2_name,e.img as user_img,b.user_tel from" . tablename("zhtc_share") . " a"  . " left join " . tablename("zhtc_information") . " b on b.id=a.information_id " . " left join " . tablename("zhtc_type") . " c on b.type_id=c.id " . " left join " . tablename("zhtc_type2") . " d on b.type2_id=d.id " . " left join " . tablename("zhtc_user") . " e on b.user_id=e.id WHERE a.user_id=:user_id  ORDER BY b.top DESC,b.id DESC";
        	$res=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));
        	echo json_encode($res);
        }
    //我的商家收藏
        public function doPageMyStoreCollection(){
        	global $_W, $_GPC;
        	$sql="select a.*,b.store_name,b.address,b.tel,b.logo,b.score,b.views,b.coordinates from" . tablename("zhtc_share") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  WHERE a.user_id=:user_id  ORDER BY a.id DESC";
        	$res=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));
        	echo json_encode($res);
        }

//入驻费用
        public function doPageInMoney(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_in',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
        	echo json_encode($res);
        }

//帮助中心
        public function doPageGetHelp(){
        	global $_W, $_GPC;
        	$res= pdo_getall('zhtc_help',array('uniacid'=>$_W['uniacid']),array() , '' , 'sort ASC');
        	echo json_encode($res);
        }

        public function doPageSms(){
        	global $_W, $_GPC;
        	$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
        	$tpl_id=$res['tpl_id'];
        	$tel=$_GPC['tel'];
        	$code=$_GPC['code'];
        	$key=$res['appkey'];
        	$url = "http://v.juhe.cn/sms/send?mobile=".$tel."&tpl_id=".$tpl_id."&tpl_value=%23code%23%3D".$code."&key=".$key;
        	$data=file_get_contents($url);
        	print_r($data);
        }
        public function doPageSms2(){
        	global $_W, $_GPC;
        	$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
        	$store=pdo_get('zhtc_store',array('id'=>$_GPC['store_id']));
        	$tpl_id=$res['tpl2_id'];
        	$tel=$store['tel'];
        	$key=$res['appkey'];
        	$url = "http://v.juhe.cn/sms/send?mobile=".$tel."&tpl_id=".$tpl_id."&tpl_value=%23code%23%3D654654&key=".$key;
        	$data=file_get_contents($url);
        	print_r($data);
        }

//我的分享码
        public function doPageHxCode(){
        	global $_W, $_GPC;
        	function  getCoade($user_id){
        		function getaccess_token(){
        			global $_W, $_GPC;
        			$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
        			$appid= $res['appid'];
        			$secret=$res['appsecret'];
            /*$appid='wx80fa1d36c435231a';
            $secret='41d8f6e7fad1a13cfa2e6de8acf14280';*/
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
            $data = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($data,true);
            return $data['access_token'];
        }

        function set_msg($user_id){
        	$access_token = getaccess_token();
        	$data2=array(
        		"scene"=>$user_id,
        		"width"=>100
        		);
        	$data2 = json_encode($data2);
        //$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        	$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL,$url);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        	curl_setopt($ch, CURLOPT_POST,1);
        	curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
        	$data = curl_exec($ch);
        	curl_close($ch);
        	return $data;
        }
        $img=set_msg($user_id);

        $img=base64_encode($img);
        return $img;

    }
    echo getCoade($_GPC['user_id']);

}
//扫码进来
public function  doPageCodeIn(){
	global $_W, $_GPC;
	$user=pdo_get('zhtc_user',array('opneid'=>$_GPC['openid']));
	if(!$user){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$date['user_id']=$_GPC['user_id'];
		$date['zf_user_id']=$_GPC['zf_user_id'];
		$date['money']=$res['fx_money'];
		$date['uniacid']=$_W['uniacid'];
		$list=pdo_get('zhtc_fx',$data);
		if(!$list){
			$date['time']=time();
			$res2=pdo_insert('zhtc_fx',$data);
			pdo_update('zhtc_user',array('money +='=>$date['money']),array('id'=>$_GPC['user_id']));
		}
	}  
}
  //领取红包
public function doPageGetHong(){
	global $_W, $_GPC;
  		$res=pdo_get('zhtc_information',array('id'=>$_GPC['id']));//查找帖子
      //判断红包个数
  		$list=pdo_getall('zhtc_hblq',array('tz_id'=>$_GPC['id'],'user_id'=>$_GPC['user_id']));
  		$user=pdo_getall('zhtc_hblq',array('tz_id'=>$_GPC['id']));
  		if(!$list and count($user)<$res['hb_num']){
  			if($res['hb_random']==1){
  				$hong=json_decode($res['hong']);		
  				$num=count($user);
  				$money=$hong[$num];
  				$data['user_id']=$_GPC['user_id'];
  				$data['tz_id']=$_GPC['id'];
  				$data['money']=$money;
  				$data['time']=time();
  				$data['uniacid']=$_W['uniacid'];
  				$get=pdo_insert('zhtc_hblq',$data);
  				if($get){
  					pdo_update('zhtc_user',array('money +='=>$hong[$num]),array('id'=>$_GPC['user_id']));
  					echo $hong[$num];
  				}
  			}else if($res['hb_random']==2){
  				$data['user_id']=$_GPC['user_id'];
  				$data['tz_id']=$_GPC['id'];
  				$data['money']=$res['hb_money'];
  				$data['time']=time();
  				$data['uniacid']=$_W['uniacid'];
  				$get=pdo_insert('zhtc_hblq',$data);
  				if($get){
  					pdo_update('zhtc_user',array('money +='=>$data['money']),array('id'=>$_GPC['user_id']));
  					echo  $data['money'];
  				}
  			}
  		}else{
  			echo 'error';
  		}


  	}
  //领取列表
  	public function doPageHongList(){
  		global $_W, $_GPC;
  		$sql="select a.*,b.img,b.name from" . tablename("zhtc_hblq") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.tz_id=:tz_id  ORDER BY a.id DESC";
  		$list=pdo_fetchall($sql,array(':tz_id'=>$_GPC['id']));
  		echo json_encode($list);
  	}
//红包
  	public function doPageHong(){
  		global $_W, $_GPC;
  		function hongbao($money,$number,$ratio = 1){
        $res = array(); //结果数组
        $min = 0.01;   //最小值
        $max = ($money / $number) * (1 + $ratio);//最大值
        /*--- 第一步：分配低保 ---*/
        for($i=0;$i<$number;$i++){
        	$res[$i] = $min;
        }
        $money = $money - $min * $number;
        /*--- 第二步：随机分配 ---*/
        $randRatio = 100;
        $randMax = ($max - $min) * $randRatio;
        for($i=0;$i<$number;$i++){
            //随机分钱
        	$randRes = mt_rand(0,$randMax);
        	$randRes = $randRes / $randRatio;
            if($money >= $randRes){ //余额充足
            	$res[$i]    += $randRes;
            	$money      -= $randRes;
            }
            elseif($money > 0){     //余额不足
            	$res[$i]    += $money;
            	$money      -= $money;
            }
            else{                   //没有余额
            	break;
            }
        }
        /*--- 第三步：平均分配上一步剩余 ---*/
        if($money > 0){
        	$avg = $money / $number;
        	for($i=0;$i<$number;$i++){
        		$res[$i] += $avg;
        	}
        	$money = 0;
        }
        /*--- 第四步：打乱顺序 ---*/
        shuffle($res);
        /*--- 第五步：格式化金额(可选) ---*/
        foreach($res as $k=>$v){
            //两位小数，不四舍五入
        	preg_match('/^\d+(\.\d{1,2})?/',$v,$match);
        	$match[0]   = number_format($match[0],2);
        	$res[$k]    = $match[0];
        }

        return $res;
    }

    print_r(hongbao(1,5));
}


//提现
public function doPageTiXian(){
	global $_W, $_GPC;
	if($_GPC['method']==1){
		$user=pdo_get('zhtc_user',array('id'=>$_GPC['user_id']),'money');
		if($user['money']>=$_GPC['tx_cost']){
		$data['name']=$_GPC['name'];//真实姓名
      $data['username']=$_GPC['username'];//账号
      $data['type']=$_GPC['type'];//type(1支付宝 2.微信 3.银行)
      $data['tx_cost']=$_GPC['tx_cost'];//提现金额
      $data['sj_cost']=$_GPC['sj_cost'];//实际到账金额
      $data['user_id']=$_GPC['user_id'];//用户id
      $data['store_id']=$_GPC['store_id'];//商家id
      $data['bank']=$_GPC['bank'];//银行
      $data['method']=$_GPC['method'];//1.红包  2.商家
      $data['time']=time();
      $data['state']=1;
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_insert('zhtc_withdrawal',$data);
      $txsh_id=pdo_insertid();
      if($res){
      	pdo_update('zhtc_user',array('money -='=>$_GPC['tx_cost']),array('id'=>$_GPC['user_id']));

      }
  }
}
if($_GPC['method']==2){
	$store=pdo_get('zhtc_store',array('id'=>$_GPC['store_id']),'wallet');
	if($store['wallet']>=$_GPC['tx_cost']){
      $data['name']=$_GPC['name'];//真实姓名
      $data['username']=$_GPC['username'];//账号
      $data['type']=$_GPC['type'];//type(1支付宝 2.微信 3.银行)
      $data['tx_cost']=$_GPC['tx_cost'];//提现金额
      $data['sj_cost']=$_GPC['sj_cost'];//实际到账金额
      $data['user_id']=$_GPC['user_id'];//用户id
      $data['store_id']=$_GPC['store_id'];//商家id
      $data['bank']=$_GPC['bank'];//银行
      $data['method']=$_GPC['method'];//1.红包  2.商家
      $data['time']=time();
      $data['state']=1;
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_insert('zhtc_withdrawal',$data);
      $txsh_id=pdo_insertid();
      if($res){
      	pdo_update('zhtc_store',array('wallet -='=>$_GPC['tx_cost']),array('id'=>$_GPC['store_id']));
      	pdo_insert('zhtc_store_wallet',array('store_id'=>$_GPC['store_id'],'money'=>$_GPC['tx_cost'],'note'=>'提现申请','type'=>2,'time'=>date("Y-m-d H:i:s"),'tx_id'=>$txsh_id));

      }
  }
}

echo $txsh_id;

}
  //我的提现
  public function doPageMyTiXian(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_withdrawal',array('user_id'=>$_GPC['user_id']));
  	echo json_encode($res);
  }
  //商家的提现
  public function doPageStoreTiXian(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_withdrawal',array('store_id'=>$_GPC['store_id']));
  	echo json_encode($res);
  }
//红包明细
  public function doPageHbmx(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_hblq',array('user_id'=>$_GPC['user_id']),array(),'','time DESC');
  	echo json_encode($res);
  }
//短信信息
  public function doPageIsSms(){
  	global $_W, $_GPC;
  	$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
  	echo $res['is_open'];
  }

//解密
  public function doPageJiemi(){
  	global $_W, $_GPC;
  	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  	include_once IA_ROOT.'/addons/zh_tcwq/wxBizDataCrypt.php';
  	$appid = $res['appid'];
  	$sessionKey = $_GPC['sessionKey'];

  	$encryptedData=$_GPC['data'];

  	$iv = $_GPC['iv'];

  	$pc = new WXBizDataCrypt($appid, $sessionKey);
  	$errCode = $pc->decryptData($encryptedData, $iv, $data );


  	if ($errCode == 0) {
        //echo json_encode($data);
  		print($data . "\n");
  	} else {
  		print($errCode . "\n");
  	}
  }
  //资讯分类
  public function doPageZxType(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_zx_type',array('uniacid'=>$_W['uniacid']),array(),'','sort asc');
  	echo json_encode($res);
  }
  //资讯
  public function doPageZx(){
  	global $_W, $_GPC;
      $data['type_id']=$_GPC['type_id'];//分类id
      $data['type']=1;//1前台发布
      $data['user_id']=$_GPC['user_id'];//发布人id
      $data['title']=$_GPC['title'];//标题
      $data['content']=$_GPC['content'];//内容
      $data['imgs']=$_GPC['imgs'];//图片
      $data['time']=date('Y-m-d H:i:s');//发布时间
      $data['cityname']=$_GPC['cityname'];
      $data['video']=$_GPC['video'];
      $system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
      if($system['is_zx']==1){
      	$data['state']=1;
      }else{
      	$data['state']=2;
      }
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_insert('zhtc_zx',$data);
      if($res){
      	echo  '1';
      }else{
      	echo  '2';
      }
  }
  //资讯列表
  public function doPageZxList(){
  	global $_W, $_GPC;
    //查看资讯设置
  	$zxset=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'zx_type');
  	$pageindex = max(1, intval($_GPC['page']));
  	$pagesize=10;
  	$where=" where a.uniacid=:uniacid and  a.state=2";
  	$data[':uniacid']=$_W['uniacid'];
  	if($_GPC['type_id']){
  		$where.=" and  a.type_id=:type_id";  
  		$data[':type_id']=$_GPC['type_id'];
  	}
  	if($zxset['zx_type']==2){
  		if($_GPC['cityname']){
  			$where.=" and a.cityname LIKE  concat('%', :name,'%') ";  
  			$data[':name']=$_GPC['cityname'];
  		}
  	}   
  	$sql="select a.*,b.img,b.name,c.type_name from" . tablename("zhtc_zx") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id " . " left join " . tablename("zhtc_zx_type") . " c on a.type_id=c.id".$where."  ORDER BY a.id DESC";
  	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
  	$res = pdo_fetchall($select_sql,$data);
  	$name_list='';
  	$name_list = array_map('array_shift', $res);
  	$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'sj_max');
  	$rand=empty($sys['sj_max'])?1:$sys['sj_max'];
  	pdo_update('zhtc_zx',array('yd_num +='=>rand(1,$rand)),array('id'=>$name_list));
  	echo json_encode($res);
  }
  //资讯详情
  public function doPageZxInfo(){
  	global $_W, $_GPC;
  	pdo_update('zhtc_zx',array('yd_num +='=>1),array('id'=>$_GPC['id']));
  	$sql="select a.*,b.img,b.name,c.type_name from" . tablename("zhtc_zx") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id " . " left join " . tablename("zhtc_zx_type") . " c on a.type_id=c.id WHERE a.id=:id  ORDER BY a.id DESC";
  	$res=pdo_fetch($sql,array(':id'=>$_GPC['id']));
        //查看是否点赞
  	$dz=pdo_get('zhtc_like',array('zx_id'=>$_GPC['id'],'user_id'=>$_GPC['user_id']));
  	if($dz){
  		$res['dz']=1;
  	}else{
  		$res['dz']=2;
  	}
  	function video($video) {
  		$vid = trim(strrchr($video, '/'), '/');
  		$vid = substr($vid, 0, -5);
  		$json = file_get_contents("http://vv.video.qq.com/getinfo?vids=" . $vid . "&platform=101001&charge=0&otype=json");
            // echo $json;die;
  		$json = substr($json, 13);
  		$json = substr($json, 0, -1);
  		$a = json_decode(html_entity_decode($json));
  		$sz = json_decode(json_encode($a), true);
            // print_R($sz);die;
  		$url = $sz['vl']['vi']['0']['ul']['ui']['0']['url'];
  		$fn = $sz['vl']['vi']['0']['fn'];
  		$fvkey = $sz['vl']['vi']['0']['fvkey'];
  		$url = $url . $fn . '?vkey=' . $fvkey;
  		return $url;
  	}
  	$res['video']=video($res['video']);
  	echo json_encode($res);
  }

//资讯评论
  public function doPageZxPl(){
  	global $_W, $_GPC;
      $data['zx_id']=$_GPC['zx_id'];//资讯id
      $data['content']=$_GPC['content'];//回复内容
      $data['cerated_time']=date("Y-m-d H:i:s");
      $data['user_id']=$_GPC['user_id'];//用户id
      $data['status']=2;
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_insert('zhtc_zx_assess',$data);
      if($res){
      	echo '1';
      }else{
      	echo '2';
      }

  }
  //回复
  public function doPageZxHf(){
  	global $_W, $_GPC;
      $data['reply']=$_GPC['reply'];//回复内容
      $data['status']=1;
      $data['reply_time']=date("Y-m-d H:i:s");
      $res=pdo_update('zhtc_zx_assess',$data,array('id'=>$_GPC['id']));
      if($res){
      	echo '1';
      }else{
      	echo '2';
      }
  }
  //评论列表
  public function doPageZxPlList(){
  	global $_W, $_GPC;
  	$sql="select a.*,b.img as user_img,b.name from " . tablename("zhtc_zx_assess") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.zx_id=:zx_id  ORDER BY a.id DESC";
  	$res=pdo_fetchall($sql,array(':zx_id'=>$_GPC['zx_id']));
  	echo json_encode($res);
  }
  //足迹
  public function doPageFootprint(){
  	global $_W, $_GPC;
  	$data['user_id']=$_GPC['user_id'];
  	$data['zx_id']=$_GPC['zx_id'];
  	$data['uniacid']=$_W['uniacid'];
  	$data['time']=time();
  	$list=pdo_get('zhtc_zx_zj',array('user_id'=>$_GPC['user_id'],'zx_id'=>$_GPC['zx_id']));
  	if($list){
  		$res=pdo_update('zhtc_zx_zj',array('time'=>time()),array('id'=>$list['id']));
  		if($res){
  			echo '1';
  		}else{
  			echo '2';
  		}
  	}else{
  		$res=pdo_insert('zhtc_zx_zj',$data);
  		if($res){
  			echo '1';
  		}else{
  			echo '2';
  		}
  	}

  }
//我的足迹
  public function doPageMyFootprint(){
  	global $_W, $_GPC;
  	$sql="select a.*,b.title,b.imgs,b.time as zx_time,c.type_name,d.name as user_name,d.img as user_img from " . tablename("zhtc_zx_zj") . " a"  . " left join " . tablename("zhtc_zx") . " b on b.id=a.zx_id " . " left join " . tablename("zhtc_zx_type") . " c on b.type_id=c.id  " . " left join " . tablename("zhtc_user") . " d on b.user_id=d.id WHERE a.user_id=:user_id  ORDER BY a.time DESC";
  	$res=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));
  	echo json_encode($res); 
  }

//商家二维码
  public function doPageStoreCode(){
  	global $_W, $_GPC;
  	function  getCoade($storeid){
  		function getaccess_token(){
  			global $_W, $_GPC;
  			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
  			$appid=$res['appid'];
  			$secret=$res['appsecret'];
              // print_r($res);die;
  			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
  			$ch = curl_init();
  			curl_setopt($ch, CURLOPT_URL,$url);
  			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
  			$data = curl_exec($ch);
  			curl_close($ch);
  			$data = json_decode($data,true);
  			return $data['access_token'];
  		}
  		function set_msg($storeid){
  			$access_token = getaccess_token();
  			$data2=array(
  				"scene"=>$storeid,
  				"page"=>"zh_tcwq/pages/sellerinfo/sellerinfo",
  				"width"=>400
  				);
  			$data2 = json_encode($data2);
  			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
  			$ch = curl_init();
  			curl_setopt($ch, CURLOPT_URL,$url);
  			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
  			curl_setopt($ch, CURLOPT_POST,1);
  			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
  			$data = curl_exec($ch);
  			curl_close($ch);
  			return $data;
  		}

  		$img=set_msg($storeid);
  		$img=base64_encode($img);
  		return $img;
  	}
  	$base64= getCoade($_GPC['store_id']);
  	$base64_image_content="data:image/jpeg;base64,".$base64;
  	$ename='tcsj'.$_GPC['store_id'];
  	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
  		$type = $result[2];
  		$new_file = IA_ROOT ."/addons/zh_tcwq/inc/upload/";
  		if(!file_exists($new_file))
  		{
//检查是否有该文件夹，如果没有就创建，并给予最高权限
  			mkdir($new_file, 0777);
  		}
  		$wname=$ename.".{$type}";
//$wname="1511.jpeg";
  		$new_file = $new_file.$wname;
//$new_file = $new_file.$ename;
  		if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){

//echo  $new_file;
  		}else{
  			echo '新文件保存失败';
  		}
  	}
  	echo $_W['siteroot']."addons/zh_tcwq/inc/upload/tcsj{$_GPC['store_id']}.jpeg";

  }

  //商家商品二维码
  public function doPageStoreGoodCode(){
  	global $_W, $_GPC;
  	function  getCoade($storeid){
  		function getaccess_token(){
  			global $_W, $_GPC;
  			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
  			$appid=$res['appid'];
  			$secret=$res['appsecret'];
  			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
  			$ch = curl_init();
  			curl_setopt($ch, CURLOPT_URL,$url);
  			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
  			$data = curl_exec($ch);
  			curl_close($ch);
  			$data = json_decode($data,true);
  			return $data['access_token'];
  		}
  		function set_msg($storeid){
  			$access_token = getaccess_token();
  			$data2=array(
  				"scene"=>$storeid,
  				"page"=>"zh_tcwq/pages/sellerinfo/good_info",
  				"width"=>400
  				);
  			$data2 = json_encode($data2);
  			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
  			$ch = curl_init();
  			curl_setopt($ch, CURLOPT_URL,$url);
  			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
  			curl_setopt($ch, CURLOPT_POST,1);
  			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
  			$data = curl_exec($ch);
  			curl_close($ch);
  			return $data;
  		}

  		$img=set_msg($storeid);
  		$img=base64_encode($img);
  		return $img;
  	}
  	$base64= getCoade($_GPC['store_id']);
  	$base64_image_content="data:image/jpeg;base64,".$base64;
  	$ename='tcsp'.$_GPC['store_id'];
  	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
  		$type = $result[2];
  		$new_file = IA_ROOT ."/addons/zh_tcwq/inc/upload/";
  		if(!file_exists($new_file))
  		{
//检查是否有该文件夹，如果没有就创建，并给予最高权限
  			mkdir($new_file, 0777);
  		}
  		$wname=$ename.".{$type}";
//$wname="1511.jpeg";
  		$new_file = $new_file.$wname;
//$new_file = $new_file.$ename;
  		if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){

//echo  $new_file;
  		}else{
  			echo '新文件保存失败';
  		}
  	}
  	echo $_W['siteroot']."addons/zh_tcwq/inc/upload/tcsp{$_GPC['store_id']}.jpeg";

  }
//查看标签
  public function doPageCarTag(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_car_tag',array('typename'=>$_GPC['typename'],'uniacid'=>$_W['uniacid']));
  	echo json_encode($res);
  }
//发布拼车
  public function doPageCar(){
  	global $_W, $_GPC;
  	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  	$data['user_id']=$_GPC['user_id'];
  	$data['start_place']=$_GPC['start_place'];
  	$data['end_place']=$_GPC['end_place'];
  	$data['start_time']=$_GPC['start_time'];
  	$data['start_time2']=strtotime($_GPC['start_time']);
  	$data['num']=$_GPC['num'];
  	$data['link_name']=$_GPC['link_name'];
  	$data['link_tel']=$_GPC['link_tel'];
  	$data['typename']=$_GPC['typename'];
  	$data['other']=$_GPC['other'];
  	$data['tj_place']=$_GPC['tj_place'];
  	$data['hw_wet']=$_GPC['hw_wet'];
  	$data['star_lat']=$_GPC['star_lat'];
  	$data['star_lng']=$_GPC['star_lng'];
  	$data['end_lat']=$_GPC['end_lat'];
  	$data['end_lng']=$_GPC['end_lng'];
  	$data['cityname']=$_GPC['cityname'];
  	$data['is_open']=1;
  	$data['time']=time();
  	$data['uniacid']=$_W['uniacid'];
  	if($system['is_car']==1){
  		$data['state']=1;
  	}else{
  		$data['state']=2;
  		$data['sh_time']=time();
  	}
  	$res=pdo_insert('zhtc_car',$data);
  	$post_id=pdo_insertid();
  	$a=json_decode(html_entity_decode($_GPC['sz']));
  	$sz=json_decode(json_encode($a),true);
     // print_r($sz);die;
  	if($res){
  		for($i=0;$i<count($sz);$i++){
  			$data2['tag_id']=$sz[$i]['tag_id'];
  			$data2['car_id']=$post_id ;
  			$res2=pdo_insert('zhtc_car_my_tag',$data2);
  		}
  		echo $post_id;
  	}else{
  		echo '2';
  	}

  }

//我的拼车
  public function doPageMyCar(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_car',array('user_id'=>$_GPC['user_id'],'is_delete'=>2));
  	echo json_encode($res);
  }
  //拼车列表
  public function doPageCarList(){
  	global $_W, $_GPC;
         //UNIX_TIMESTAMP
  	$time=time();
  	pdo_update('zhtc_car',array('is_open'=>2),array('start_time2 <='=>$time));
  	$pageindex = max(1, intval($_GPC['page']));
  	$pagesize=10;
  	$where=" where uniacid=:uniacid and state=2 and is_delete=2";
  	$data[':uniacid']=$_W['uniacid'];
  	if($_GPC['cityname']){
  		$where.=" and cityname LIKE  concat('%', :name,'%') ";  
  		$data[':name']=$_GPC['cityname'];
  	}
  	$sql=" select * from ".tablename('zhtc_car').$where." order by id DESC";
  	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
  	$res=pdo_fetchall($select_sql,$data);
         //$res=pdo_getall('zhtc_car',array('uniacid'=>$_W['uniacid'],'state'=>2),array(),'','id DESC');
  	$sql2="select a.*,b.tagname from " . tablename("zhtc_car_my_tag") . " a"  . " left join " . tablename("zhtc_car_tag") . " b on b.id=a.car_id";
  	$res2=pdo_fetchall($sql2);
        // $res2=pdo_getall('zhtc_label',array('uniacid'=>$_W['uniacid']));
  	$data2=array();
  	for($i=0;$i<count($res);$i++){
  		$data=array();
  		for($k=0;$k<count($res2);$k++){
  			if($res[$i]['id']==$res2[$k]['car_id']){
  				$data[]=array(
  					'tagname'=>$res2[$k]['tagname']
  					);
  			}  
  		}
  		$data2[]=array(
  			'tz'=>$res[$i],
  			'label'=>$data
  			);
  	}


  	echo json_encode($data2);
  }
  //分类拼车列表
  public function doPageTypeCarList(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_car',array('uniacid'=>$_W['uniacid'],'typename'=>$_GPC['typename'],'state'=>2),array(),'','id DESC');
  	$sql2="select a.*,b.tagname from " . tablename("zhtc_car_my_tag") . " a"  . " left join " . tablename("zhtc_car_tag") . " b on b.id=a.tag_id";
  	$res2=pdo_fetchall($sql2);
        // $res2=pdo_getall('zhtc_label',array('uniacid'=>$_W['uniacid']));
  	$data2=array();
  	for($i=0;$i<count($res);$i++){
  		$data=array();
  		for($k=0;$k<count($res2);$k++){
  			if($res[$i]['id']==$res2[$k]['car_id']){
  				$data[]=array(
  					'tagname'=>$res2[$k]['tagname']
  					);
  			}  
  		}
  		$data2[]=array(
  			'tz'=>$res[$i],
  			'label'=>$data
  			);
  	}

  	echo json_encode($data2);
  }
  //拼车详情
  public function doPageCarInfo(){
  	global $_W, $_GPC;
  	$sql="select a.*,b.name as user_name,b.img as user_img from " . tablename("zhtc_car") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id where a.id =:id";
  	$res=pdo_fetch($sql,array(':id'=>$_GPC['id']));
  	$sql2="select a.*,b.tagname from " . tablename("zhtc_car_my_tag") . " a"  . " left join " . tablename("zhtc_car_tag") . " b on b.id=a.tag_id where a.
  	car_id=:car_id";
  	$res2=pdo_fetchall($sql2,array(':car_id'=>$_GPC['id']));
     // $res=pdo_getall('zhtc_car',array('id'=>$_GPC['id']));
  	$data['pc']=$res;
  	$data['tag']=$res2;
  	echo json_encode($data);
  }
//关闭
  public function doPageCarShut(){
  	global $_W, $_GPC;
  	$res=pdo_update('zhtc_car',array('is_open'=>2),array('id'=>$_GPC['id']));
  	if($res){
  		echo  '1';
  	}else{
  		echo '2';
  	}
  }
  //规格分类
  public function doPageSpec(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_goods_spec',array('uniacid'=>$_W['uniacid']));
  	echo json_encode($res);
  }
//发布商品
  public function  doPageAddGoods(){
  	global $_W, $_GPC;
  	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
      $data['store_id']=$_GPC['store_id'];//商家id
      $data['goods_name']=$_GPC['goods_name'];//商品名称
      $data['goods_num']=$_GPC['goods_num'];//商品数量
      $data['goods_cost']=$_GPC['goods_cost'];//商品价格
      $data['freight']=$_GPC['freight'];//运费
      $data['delivery']=$_GPC['delivery'];//关于发货
      $data['quality']=$_GPC['quality'];//正品1是,2否
      $data['free']=$_GPC['free'];//包邮1是,2否
      $data['all_day']=$_GPC['all_day'];//24小时发货1是,2否
      $data['service']=$_GPC['service'];//售后服务1是,2否
      $data['refund']=$_GPC['refund'];//极速退款1是,2否
      $data['weeks']=$_GPC['weeks'];//7天包邮1是,2否
      $data['lb_imgs']=$_GPC['lb_imgs'];//轮播图
      $data['imgs']=$_GPC['imgs'];//商品介绍图
      $data['goods_details']=$_GPC['goods_details'];//商品详细
      $data['vr']=$_GPC['vr'];//vr

      $data['is_xs']=$_GPC['is_xs'];//抢购
      $data['end_time']=$_GPC['end_time'];//结束时间
      if($system['is_goods']==1){
      	$data['state']=1;
      }else{
      	$data['state']=2;
      }
      $data['time']=time();
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_insert('zhtc_goods',$data);//
      $post_id=pdo_insertid();
      if($_GPC['sz']){
      	$a=json_decode(html_entity_decode($_GPC['sz']));
      	$sz=json_decode(json_encode($a),true);
      }

     // print_r($sz);die;
      if($res){
      	if($_GPC['sz']){
      		for($i=0;$i<count($sz);$i++){
      			$data2['spec_id']=$sz[$i]['spec_id'];
      			$data2['money']=$sz[$i]['money'];
      			$data2['name']=$sz[$i]['name'];
      			$data2['num']=$sz[$i]['num'];
      			$data2['goods_id']=$post_id ;
      			$res2=pdo_insert('zhtc_spec_value',$data2);
      		}
      	}

      	echo '1';
      }else{
      	echo '2';
      }


  }

  //修改商品
  public function  doPageUpdGoods(){
  	global $_W, $_GPC;
  	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
      $data['store_id']=$_GPC['store_id'];//商家id
      $data['goods_name']=$_GPC['goods_name'];//商品名称
      $data['goods_num']=$_GPC['goods_num'];//商品数量
      $data['goods_cost']=$_GPC['goods_cost'];//商品价格
      $data['freight']=$_GPC['freight'];//运费
      $data['delivery']=$_GPC['delivery'];//关于发货
      $data['quality']=$_GPC['quality'];//正品1是,2否
      $data['free']=$_GPC['free'];//包邮1是,2否
      $data['all_day']=$_GPC['all_day'];//24小时发货1是,2否
      $data['service']=$_GPC['service'];//售后服务1是,2否
      $data['refund']=$_GPC['refund'];//极速退款1是,2否
      $data['weeks']=$_GPC['weeks'];//7天包邮1是,2否
      $data['lb_imgs']=$_GPC['lb_imgs'];//轮播图
      $data['imgs']=$_GPC['imgs'];//商品介绍图
      $data['goods_details']=$_GPC['goods_details'];//商品详细
      $data['vr']=$_GPC['vr'];//商品详细
      if($system['is_goods']==1){
      	$data['state']=1;
      }else{
      	$data['state']=2;
      }
      $data['time']=time();
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_update('zhtc_goods',$data,array('id'=>$_GPC['good_id']));//
      $post_id=pdo_insertid();
      // if($_GPC['sz']){
      //    $a=json_decode(html_entity_decode($_GPC['sz']));
      //   $sz=json_decode(json_encode($a),true);
      // }

     // print_r($sz);die;
      if($res){
      //   if($_GPC['sz']){
      //     for($i=0;$i<count($sz);$i++){
      //     $data2['spec_id']=$sz[$i]['spec_id'];
      //     $data2['money']=$sz[$i]['money'];
      //     $data2['name']=$sz[$i]['name'];
      //     $data2['num']=$sz[$i]['num'];
      //     $data2['goods_id']=$post_id ;
      //     $res2=pdo_insert('zhtc_spec_value',$data2);
      // }
      //   }

      	echo '1';
      }else{
      	echo '2';
      }


  }
  //删除商品
  public function doPageDelGood(){
  	global $_W, $_GPC;
  	$res=pdo_delete('zhtc_goods',array('id'=>$_GPC['good_id']));
  	if($res){
  		$res=pdo_delete('zhtc_spec_value',array('goods_id'=>$_GPC['good_id']));
  		echo '1';
  	}else{
  		echo '2';
  	}
  }
  //下架
  public function doPageDownGood(){
  	global $_W, $_GPC;
  	$res=pdo_update('zhtc_goods',array('is_show'=>2),array('id'=>$_GPC['good_id']));
  	if($res){
  		echo '1';
  	}else{
  		echo '2';
  	}
  }
  //上架
  public function doPageUpGood(){
  	global $_W, $_GPC;
  	$res=pdo_update('zhtc_goods',array('is_show'=>1),array('id'=>$_GPC['good_id']));
  	if($res){
  		echo '1';
  	}else{
  		echo '2';
  	}
  }

//商品列表
  public function doPageGoodList(){
  	global $_W, $_GPC;
  	$where=" WHERE  uniacid={$_W['uniacid']} and state=2 and is_show=1 and store_id={$_GPC['store_id']}";
	if($_GPC['keywords']){
	    $op=$_GPC['keywords'];
	    $where.=" and goods_name LIKE  concat('%', :name,'%') ";  
	    $data[':name']=$op;
	}
	$sql="select * from " . tablename("zhtc_goods") . " " .$where."  order by id desc ";
	$res=pdo_fetchall($sql,$data);
  	echo json_encode($res);
  }





//商家商品列表
  public function doPageStoreGoodList(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_goods',array('store_id'=>$_GPC['store_id'],'state'=>2),array(),'','id DESC');
  	echo json_encode($res);
  }
  //商家商品列表
  public function doPageStoreGoodList2(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_goods',array('store_id'=>$_GPC['store_id'],'state'=>2,'is_show'=>1),array(),'','id DESC');
  	echo json_encode($res);
  }
  //商品详情
  public function doPageGoodInfo(){
  	global $_W, $_GPC;
  	$res=pdo_get('zhtc_goods',array('id'=>$_GPC['id']));
  	$sql="select a.*,b.spec_name from " . tablename("zhtc_spec_value") . " a"  . " left join " . tablename("zhtc_goods_spec") . " b on b.id=a.spec_id  WHERE a.goods_id=:goods_id";
  	$res2=pdo_fetchall($sql,array(':goods_id'=>$_GPC['id']));
  	$data['good']=$res;
  	$data['spec']=$res2;
  	echo json_encode($data);
  }
 //下订单
  public function doPageAddOrder(){
  	global $_W, $_GPC;
		  $data['user_id']=$_GPC['user_id'];//用户id
	      $data['store_id']=$_GPC['store_id'];//商家id
	      $data['money']=$_GPC['money'];//订单金额
	      $data['user_name']=$_GPC['user_name'];//用户名称
	      $data['address']=$_GPC['address'];//地址
	      $data['tel']=$_GPC['tel'];//电话
	      $data['good_id']=$_GPC['good_id'];//商品id
	      $data['good_name']=$_GPC['good_name'];//商品名称
	      $data['good_img']=$_GPC['good_img'];//商品图片
	      $data['good_money']=$_GPC['good_money'];//商品金额
	      $data['good_spec']=$_GPC['good_spec'];//商品规格
	      $data['freight']=$_GPC['freight'];//运费
          $data['note']=$_GPC['note'];//备注
          $data['good_num']=$_GPC['good_num'];//商品数量
          $data['uniacid']=$_W['uniacid'];
	      $data['time']=time();//下单时间
	      $data['order_num']=date("YmdHis").rand(1111,9999);//订单号
	      $data['state']=1;//状态待付款
	      $data['del']=2;
	      $data['del2']=2;
	      $data['is_zt']=$_GPC['is_zt'];
	      $data['zt_time']=$_GPC['zt_time'];
	      $res=pdo_insert('zhtc_order',$data);
	      $post_id=pdo_insertid();
	      if($res){
	      	echo  $post_id;
	      }else{
	      	echo  '下单失败';
	      }
	  }
//付款改变订单状态
	  public function doPagePayOrder(){
	  	global $_W, $_GPC;
      //获取订单信息
	  	$orderinfo=pdo_get('zhtc_order',array('id'=>$_GPC['order_id']));
	  	pdo_update('zhtc_goods',array('goods_num -='=>$orderinfo['good_num'],'sales +='=>$orderinfo['good_num']),array('id'=>$orderinfo['good_id']));
	  	$res=pdo_update('zhtc_order',array('state'=>2,'pay_time'=>time()),array('id'=>$_GPC['order_id']));
	  	if($res){
          file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=xdMessage&m=zh_tcwq&order_id=".$_GPC['order_id']);//模板消息
          echo  '1';
      }else{
      	echo  '2';
      }
  }
  //发货
  public function doPageDeliveryOrder(){
  	global $_W, $_GPC;
  	$res=pdo_update('zhtc_order',array('state'=>3,'fh_time'=>time(),'kd_num'=>$_GPC['kd_num'],'kd_name'=>$_GPC['kd_name']),array('id'=>$_GPC['order_id']));
  	if($res){
          file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=fhMessage&m=zh_tcwq&order_id=".$_GPC['order_id']);//模板消息
          echo '1';
      }else{
      	echo '2';
      }
  }

//确认收货
  public function doPageCompleteOrder(){
  	global $_W, $_GPC;
  	$order=pdo_get('zhtc_order',array('id'=>$_GPC['order_id']));
  	$res=pdo_update('zhtc_order',array('state'=>4,'complete_time'=>time()),array('id'=>$_GPC['order_id']));
  	if($res){
  		pdo_update('zhtc_store',array('wallet +='=>$order['money']),array('id'=>$order['store_id']));
  		$data['store_id']=$order['store_id'];
  		$data['money']=$order['money'];
  		$data['note']='商品订单';
  		$data['type']=1;
  		$data['time']=date("Y-m-d H:i:s");
  		pdo_insert('zhtc_store_wallet',$data);


  		$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  		if($system['good_jf']>0){
  			pdo_update('zhtc_user',array('total_score +='=>$system['good_jf']),array('id'=>$order['user_id']));
  			$data2['score']=$system['good_jf'];
  			$data2['user_id']=$order['user_id'];
  			$data2['tid']=$order['id'];
  			$data2['note']='商品订单';
  			$data2['type']=1;
  			$data2['cerated_time']=date('Y-m-d H:i:s');
            $data2['uniacid']=$_W['uniacid'];//小程序id
            pdo_insert('zhtc_integral',$data2);//添加积分明细 
        }
        echo  '1';
    }else{
    	echo  '2';
    }
}

  //查看商家余额明细
public function doPageStoreWallet(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_store_wallet',array('store_id'=>$_GPC['store_id']),array(),'','id DESC');
	echo json_encode($res);
}
//查看我的订单
public function doPageMyOrder(){
	global $_W, $_GPC;
      // $pageindex = max(1, intval($_GPC['page']));
      // $pagesize=10;
	$sql="select a.*,b.store_name from " . tablename("zhtc_order") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  WHERE a.user_id=:user_id and a.del=2 order by id DESC";
      // $select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));

    //  $res=pdo_getall('zhtc_order',array('user_id'=>$_GPC['user_id'],'del'=>2));
	echo json_encode($res);
}
  //查看订单详情
public function doPageOrderInfo(){
	global $_W, $_GPC;
	$sql="select a.*,b.store_name from " . tablename("zhtc_order") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  WHERE a.id=:id ";
	$res=pdo_fetch($sql,array(':id'=>$_GPC['order_id']));

	function  getCoade($storeid){
		function getaccess_token(){
			global $_W, $_GPC;
			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
			$appid=$res['appid'];
			$secret=$res['appsecret'];
		              // print_r($res);die;
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data,true);
			return $data['access_token'];
		}
		function set_msg($storeid){
			$access_token = getaccess_token();
			$data2=array(
				"scene"=>$storeid,
				"page"=>"zh_tcwq/pages/logs/hxorder",
				"width"=>400
				);
			$data2 = json_encode($data2);
			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		$img=set_msg($storeid);
		$img=base64_encode($img);
		return $img;
	}


	$res['code_url']=getCoade($_GPC['order_id']);
	echo json_encode($res);
}
  //核销订单
public function doPageHxOrder(){
	global $_W, $_GPC;
	$order=pdo_get('zhtc_order',array('id'=>$_GPC['order_id']));
	if($order['store_id']==$_GPC['store_id']){
		$res=pdo_update('zhtc_order',array('state'=>4),array('id'=>$_GPC['order_id']));
		if($res){
			pdo_update('zhtc_store',array('wallet +='=>$order['money']),array('id'=>$order['store_id']));
			$data['store_id']=$order['store_id'];
			$data['money']=$order['money'];
			$data['note']='商品订单';
			$data['type']=1;
			$data['time']=date("Y-m-d H:i:s");
			pdo_insert('zhtc_store_wallet',$data);
			echo  '核销成功!';
		}else{
			echo  '核销失败!';
		}

	}else{
		echo '无核销权限!';
	}
}

//更新用户地址信息
public function doPageUpdAdd(){
	global $_W, $_GPC;
	$data['user_name']=$_GPC['user_name'];
	$data['user_tel']=$_GPC['user_tel'];
	$data['user_address']=$_GPC['user_address'];
	$res=pdo_update('zhtc_user',$data,array('id'=>$_GPC['user_id']));
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}
//用户删除订单
public function doPageDelOrder(){
	global $_W, $_GPC;
	$res=pdo_update('zhtc_order',array('del'=>1),array('id'=>$_GPC['order_id']));
	if($res){
		echo  '1';
	}else{
		echo  '2';
	}
}
//商家删除订单
public function doPageDelOrder2(){
	global $_W, $_GPC;
	$res=pdo_update('zhtc_order',array('del2'=>1),array('id'=>$_GPC['order_id']));
	if($res){
		echo  '1';
	}else{
		echo  '2';
	}
}
 //商家订单列表
public function doPageStoreOrder(){
	global $_W, $_GPC;
	$sql="select a.*,b.name,b.img from " . tablename("zhtc_order") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.store_id=:store_id and a.del2=2";
	$res=pdo_fetchall($sql,array(':store_id'=>$_GPC['store_id']));
	echo json_encode($res);
}
 //商家订单详情
public function doPageStoreOrderInfo(){
	global $_W, $_GPC;
	$sql="select a.*,b.name,b.img from " . tablename("zhtc_order") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.id=:order_id and a.del2=2";
	$res=pdo_fetch($sql,array(':order_id'=>$_GPC['order_id']));
	echo json_encode($res);
}
//申请退款
public function doPageTuOrder(){
	global $_W, $_GPC;
	$res=pdo_update('zhtc_order',array('state'=>5),array('id'=>$_GPC['order_id']));
	if($res){
		echo  '1';
	}else{
		echo  '2';
	}
}

 //申请分销商
public function doPageDistribution(){
	global $_W, $_GPC;
	pdo_delete('zhtc_distribution',array('user_id'=>$_GPC['user_id']));
	$set=pdo_get('zhtc_fxset',array('uniacid'=>$_W['uniacid']));
	$data['user_id']=$_GPC['user_id'];
	$data['user_name']=$_GPC['user_name'];
	$data['user_tel']=$_GPC['user_tel'];
	$data['level']=$_GPC['level'];
	$data['cityname']=$_GPC['cityname'];
	$data['money']=$_GPC['money'];
	$data['is_log']=2;
	$data['time']=time();
	if($set['is_fx']==1){
		$data['state']=2;
	}else{
		$data['state']=1;
	}
	if($_GPC['money']){
		$data['pay_state']=1;
	}else{
		$data2['user_id']=$_GPC['user_id'];
		$data2['money']=$_GPC['money'];
		$data2['time']=date("Y-m-d H:i:s");
		$data2['level']=$_GPC['level'];
		$data2['uniacid']=$_W['uniacid'];
		$data2['state']=2;
		$data2['note']='申请分销商';
		pdo_insert('zhtc_fxlog',$data2);


		$data['pay_state']=2;
	}
	$data['uniacid']=$_W['uniacid'];
	$res=pdo_insert('zhtc_distribution',$data);
	$order=pdo_insertid();
	if($res){
		$fx=pdo_get('zhtc_fxuser',array('fx_user'=>$_GPC['user_id']));
		if($set['is_fx']==1 and !$fx){
			pdo_insert("zhtc_fxuser",array('user_id'=>0,'fx_user'=>$_GPC['user_id'],'time'=>time()));
		}
		echo  $order;
	}else{
		echo '下单失败!';
	}
}
  //分销商支付
public function doPagePay2(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
   // include 'wxpay.php';
	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($res['pt_name']){
		$res['pt_name']=$res['pt_name'];
	}else{
		$res['pt_name']='同城小程序';
	}
	$appid=$res['appid'];
  $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
  $mch_id=$res['mchid'];
  $key=$res['wxkey'];
  $out_trade_no = $mch_id. time();
  $root=$_W['siteroot'];
  pdo_update('zhtc_distribution',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
  $rst=pdo_get('zhtc_distribution',array('id'=>$_GPC['order_id']),'money');
  //$total_fee =$_GPC['money'];
   $total_fee =$rst['money'];
    if(empty($total_fee)) //押金
    {
    	$body = $res['pt_name'];
    	$total_fee = floatval(99*100);
    }else{
    	$body = $res['pt_name'];
    	$total_fee = floatval($total_fee*100);
    }
    $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
    $return=$weixinpay->pay();
    echo json_encode($return);
}
//查看我的申请
public function doPageMyDistribution(){
	global $_W, $_GPC;
	$sql="select a.* ,b.name,d.name as  sj_name from " . tablename("zhtc_distribution") . " a"  . " left join " . tablename("zhtc_fxlevel") . " b on b.id=a.level left join " . tablename("zhtc_fxuser") . " c on c.fx_user=a.user_id left join " . tablename("zhtc_user") . " d on d.id=c.user_id WHERE a.user_id=:user_id and a.pay_state=2";
	$res=pdo_fetch($sql,array(':user_id'=>$_GPC['user_id']));
	echo json_encode($res);
}
//分销设置
public function doPageFxSet(){
	global $_W, $_GPC;
	$res=pdo_get('zhtc_fxset',array('uniacid'=>$_W['uniacid']));
	echo json_encode($res);
}

  //分销等级
public function doPageFxLevel(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_fxlevel',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
	echo json_encode($res);
}
  //查看我的上线
public function doPageMySx(){
	global $_W, $_GPC;
	$sql="select a.* ,b.name from " . tablename("zhtc_fxuser") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id   WHERE a.fx_user=:fx_user ";
	$res=pdo_fetch($sql,array(':fx_user'=>$_GPC['user_id']));
	echo json_encode($res);
}
   //查看我的佣金收益
public function doPageEarnings(){
	global $_W, $_GPC;
	$sql="select a.* ,b.name,b.img from " . tablename("zhtc_earnings") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.son_id   WHERE a.user_id=:user_id order by id DESC";
	$res=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));
	echo json_encode($res);
}
//我的二维码
public function doPageMyCode(){
	global $_W, $_GPC;
	function  getCoade($storeid){
		function getaccess_token(){
			global $_W, $_GPC;
			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
			$appid=$res['appid'];
			$secret=$res['appsecret'];
              // print_r($res);die;
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data,true);
			return $data['access_token'];
		}
		function set_msg($storeid){
			$access_token = getaccess_token();
			$data2=array(
				"scene"=>$storeid,
				"page"=>"zh_tcwq/pages/index/index",
				"width"=>400
				);
			$data2 = json_encode($data2);
			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		$img=set_msg($storeid);
		$img=base64_encode($img);
		return $img;
	}
	 	$base64_image_content="data:image/jpeg;base64,".getCoade($_GPC['user_id']);
        	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        		$type = $result[2];
        		$new_file = IA_ROOT ."/addons/zh_tcwq/img/";
        		if(!file_exists($new_file))
        		{
    //检查是否有该文件夹，如果没有就创建，并给予最高权限
        			mkdir($new_file, 0777);
        		}
        		$wname="{$_GPC['user_id']}".".{$type}";
    //$wname="1511.jpeg";
        		$new_file = $new_file.$wname;
        		file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)));
        	}
        	echo $_W['siteroot']."/addons/zh_tcwq/img/".$wname;

}
//佣金提现
public function doPageYjtx(){
	global $_W, $_GPC;
	$data['user_id']=$_GPC['user_id'];
     $data['type']=$_GPC['type'];//类型
     $data['user_name']=$_GPC['user_name'];//姓名
     $data['account']=$_GPC['account'];//账号
     $data['tx_cost']=$_GPC['tx_cost'];//提现金额
     $data['sj_cost']=$_GPC['sj_cost'];//实际到账金额
     $data['bank']=$_GPC['bank'];//银行
     $data['state']=1;
     $data['time']=time();
     $data['uniacid']=$_W['uniacid'];
     $res=pdo_insert('zhtc_commission_withdrawal',$data);
     if($res){
     	pdo_update('zhtc_user',array('commission -='=>$_GPC['tx_cost']),array('id'=>$_GPC['user_id']));
     	echo '1';
     }else{
     	echo '2';
     }
 }
//提现明细
 public function doPageYjtxList(){
 	global $_W, $_GPC;
 	$res=pdo_getall('zhtc_commission_withdrawal',array('user_id'=>$_GPC['user_id']),array(),'','id DESC');
 	echo json_encode($res);
 }

//绑定分销商
 public function doPageBinding(){
 	global $_W, $_GPC;
 	$set=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
 	$res=pdo_get('zhtc_fxuser',array('fx_user'=>$_GPC['fx_user']));
 	$res2=pdo_get('zhtc_fxuser',array('user_id'=>$_GPC['fx_user'],'fx_user'=>$_GPC['user_id']));
 	if($set['is_hhr']==1){
 		if($_GPC['user_id']==$_GPC['fx_user']){
 			echo '自己不能绑定自己';
 		}elseif($res || $res2){
 			echo '不能重复绑定';
 		}else{
 			$res3=pdo_insert('zhtc_fxuser',array('user_id'=>$_GPC['user_id'],'fx_user'=>$_GPC['fx_user'],'time'=>time()));
 			if($res3){
 				echo  '1';
 			}else{
 				echo  '2';
 			}
 		}
 	}


 }

//查看我的团队
 public function doPageMyTeam(){
 	global $_W, $_GPC;
 	$sql="select a.* ,b.name,b.img from " . tablename("zhtc_fxuser") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.fx_user   WHERE a.user_id=:user_id order by id DESC";
 	$res=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));
 	$res2=array();
 	for($i=0;$i<count($res);$i++){
 		$sql2="select a.* ,b.name,b.img from " . tablename("zhtc_fxuser") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.fx_user   WHERE a.user_id=:user_id order by id DESC";
 		$res3=pdo_fetchall($sql2,array(':user_id'=>$res[$i]['fx_user']));
 		$res2[]=$res3;

 	}

 	$res4=array();
 	for($k=0;$k<count($res2);$k++){
 		for($j=0;$j<count($res2[$k]);$j++){
 			$res4[]=$res2[$k][$j]; 
 		}

 	}
 	$data['one']=$res;
 	$data['two']=$res4;
  // print_r($data);die;
 	echo json_encode($data);
 }

//查看佣金
 public function doPageMyCommission(){
 	global $_W, $_GPC;
    $system=pdo_get('zhtc_fxset',array('uniacid'=>$_W['uniacid']));//tx_money
    $user=pdo_get('zhtc_user',array('id'=>$_GPC['user_id']));
    if($user['commission']<$system['tx_money']){
    	$ke=0.00;
    }else{
    	$ke=$user['commission'];
    }
    $sq = "select sum(tx_cost) as tx_cost from " . tablename("zhtc_commission_withdrawal")." WHERE  user_id=".$_GPC['user_id'];
    $sq = pdo_fetch($sq);
    $sq= $sq['tx_cost'];

    $cg = "select sum(tx_cost) as tx_cost from " . tablename("zhtc_commission_withdrawal")." WHERE  state=2 and user_id=".$_GPC['user_id'];
    $cg = pdo_fetch($cg);
    $cg= $cg['tx_cost'];

    $lei = "select sum(money) as tx_cost from " . tablename("zhtc_earnings")." WHERE  user_id=".$_GPC['user_id'];
    $lei = pdo_fetch($lei);
    $lei= $lei['tx_cost'];

    $data['ke']=$ke;
    $data['sq']=$sq;
    $data['cg']=$cg;
    $data['lei']=$lei;
    echo json_encode($data);
}

//添加佣金
public function doPageFx(){
	global $_W, $_GPC;
   // echo $_GPC['money'];die;
	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	$set=pdo_get('zhtc_fxset',array('uniacid'=>$_W['uniacid']));
	if($system['is_hhr']==1){
            if($set['is_ej']==2){//不开启二级分销
            	$user=pdo_get('zhtc_fxuser',array('fx_user'=>$_GPC['user_id']));
            	if($user){
            		$sx=pdo_get('zhtc_distribution',array('user_id'=>$user['user_id']));
            		$level=pdo_get('zhtc_fxlevel',array('id'=>$sx['level']));
            $userid=$user['user_id'];//上线id
            $money=$_GPC['money']*($level['commission']/100);//一级佣金
            pdo_update('zhtc_user',array('commission +='=>$money),array('id'=>$userid));
            $data6['user_id']=$userid;//上线id
            $data6['son_id']=$_GPC['user_id'];//下线id
            $data6['money']=$money;//金额
            $data6['time']=time();//时间
            $data6['uniacid']=$_W['uniacid'];
            pdo_insert('zhtc_earnings',$data6);
        }
      }else{//开启二级
      	$user=pdo_get('zhtc_fxuser',array('fx_user'=>$_GPC['user_id']));
          $user2=pdo_get('zhtc_fxuser',array('fx_user'=>$user['user_id']));//上线
          if($user){
          	$sx=pdo_get('zhtc_distribution',array('user_id'=>$user['user_id']));
          	$level=pdo_get('zhtc_fxlevel',array('id'=>$sx['level']));
       		//echo $level['commission'];die;
            $userid=$user['user_id'];//上线id
            $money=$_GPC['money']*($level['commission']/100);//一级佣金
            pdo_update('zhtc_user',array('commission +='=>$money),array('id'=>$userid));
            $data6['user_id']=$userid;//上线id
            $data6['son_id']=$_GPC['user_id'];//下线id
            $data6['money']=$money;//金额
            $data6['time']=time();//时间
            $data6['uniacid']=$_W['uniacid'];
            pdo_insert('zhtc_earnings',$data6);
        }
        if($user2){
        	$sx2=pdo_get('zhtc_distribution',array('user_id'=>$user2['user_id']));
        	$level2=pdo_get('zhtc_fxlevel',array('id'=>$sx2['level']));
            $userid2=$user2['user_id'];//上线的上线id
            $money=$_GPC['money']*($level2['commission2']/100);//二级佣金
            pdo_update('zhtc_user',array('commission +='=>$money),array('id'=>$userid2));
            $data7['user_id']=$userid2;//上线id
            $data7['son_id']=$_GPC['user_id'];//下线id
            $data7['money']=$money;//金额
            $data7['time']=time();//时间
            $data7['uniacid']=$_W['uniacid'];
            pdo_insert('zhtc_earnings',$data7);
        }
    }
}
}

//入驻黄页
public function doPageYellowPage(){
	global $_W, $_GPC;
	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	$data['user_id']=$_GPC['user_id'];
	$data['logo']=$_GPC['logo'];
	$data['company_name']=$_GPC['company_name'];
	$data['company_address']=$_GPC['company_address'];
	$data['type_id']=$_GPC['type_id'];
	$data['type2_id']=$_GPC['type2_id'];
	$data['link_tel']=$_GPC['link_tel'];
	$data['rz_type']=$_GPC['rz_type'];
	$data['coordinates']=$_GPC['coordinates'];
	$data['content']=$_GPC['content'];
	$data['imgs']=$_GPC['imgs'];
	$data['tel2']=$_GPC['tel2'];
	$data['cityname']=$_GPC['cityname'];
	$data['uniacid']=$_W['uniacid'];
	$data['time_over']=2;
	if($system['is_hyset']==1){
		$data['state']=1;
	}else{
		$data['state']=2;
		$data['sh_time']=time();
		$rz=pdo_get('zhtc_yellowset',array('id'=>$_GPC['rz_type']));
		$data['dq_time']=$data['sh_time']+24*60*60*$rz['days'];
	}
	$res=pdo_insert('zhtc_yellowstore',$data);
	$hy_id=pdo_insertid();
	if($res){
		echo  $hy_id;
	}else{
		echo  '2';
	}
}
  //查看我入驻的黄页
public function doPageMyYellowPage(){
	global $_W, $_GPC;
	$sql="select a.* ,b.type_name,c.name as  type2_name from " . tablename("zhtc_yellowstore") . " a"  . " left join " . tablename("zhtc_yellowtype") . " b on b.id=a.type_id left join " . tablename("zhtc_yellowtype2") . " c on c.id=a.type2_id  WHERE a.user_id=:user_id  and a.is_delete=2 order by a.id desc ";
	$res=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));

	echo json_encode($res);
}
  //查看黄页列表
public function doPageYellowPageList(){
	global $_W, $_GPC;
    //修改以前的数据
	$time=time();
	$gx=" update " . tablename("zhtc_yellowstore") . " a inner join (select id from " . tablename("zhtc_yellowstore") . "  where dq_time<={$time} and  state=2 and time_over =2 and  uniacid={$_W['uniacid']} order by dq_time ASC limit 0,100 ) b on a.id =b.id set a.time_over =1"; 
	pdo_query($gx);

     // $rst=pdo_update('zhtc_yellowstore',array('time_over'=>1),array('dq_time <='=>time(),'state'=>2,'uniacid'=>$_W['uniacid']));
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=10;
	$where=" WHERE a.uniacid=:uniacid and a.state=2 and a.time_over=2 and a.is_delete=2 ";
	if($_GPC['cityname']){
		$where.=" and a.cityname LIKE  concat('%', :name,'%') ";  
		$data[':name']=$_GPC['cityname'];
	}
	if($_GPC['keywords']){
		$where.=" and a.company_name LIKE  concat('%', :name,'%') ";  
		$data[':name']=$_GPC['keywords'];
	}
	if($_GPC['type_id']){
		$where.=" and a.type_id=:type_id";  
		$data[':type_id']=$_GPC['type_id'];
	}
	if($_GPC['type2_id']){
		$where.=" and a.type2_id=:type2_id";  
		$data[':type2_id']=$_GPC['type2_id'];
	}
	$data[':uniacid']=$_W['uniacid'];
	$sql="select a.* ,b.type_name,c.name as  type2_name from " . tablename("zhtc_yellowstore") . " a"  . " left join " . tablename("zhtc_yellowtype") . " b on b.id=a.type_id left join " . tablename("zhtc_yellowtype2") . " c on c.id=a.type2_id ".$where." order by id DESC";
   // $res=pdo_fetch($sql,array(':uniacid'=>$_W['uniacid']));
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($select_sql,$data);
	echo json_encode($res);
}
  //查看黄页详情
public function doPageYellowPageInfo(){
	global $_W, $_GPC;
	pdo_update('zhtc_yellowstore',array('views +='=>1),array('id'=>$_GPC['id']));
	$sql="select a.* ,b.type_name,c.name as  type2_name from " . tablename("zhtc_yellowstore") . " a"  . " left join " . tablename("zhtc_yellowtype") . " b on b.id=a.type_id left join " . tablename("zhtc_yellowtype2") . " c on c.id=a.type2_id  WHERE a.id=:id";
	$res=pdo_fetch($sql,array(':id'=>$_GPC['id']));

	echo json_encode($res);
}
//查看黄页入驻设置
public function doPageYellowSet(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_yellowset',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
	echo json_encode($res);
}

//登录
public function doPageStoreLogin(){
	global $_W, $_GPC;
	$res=pdo_get('zhtc_store',array('user_name'=>$_GPC['user_name']));
	$res2=pdo_get('zhtc_store',array('user_name'=>$_GPC['user_name'],'pwd'=>md5($_GPC['pwd'])));
	if(!$res){
		echo '账号不存在!';
	}elseif(!$res2){
		echo '密码不正确!';
	}elseif($res2){
		echo json_encode($res2);
	}

}






















    //上传图片
public function doPageUpload(){
	global $_W, $_GPC;
	$uptypes=array(  
		'image/jpg',  
		'image/jpeg',  
		'image/png',  
		'image/pjpeg',  
		'image/gif',  
		'image/bmp',  
		'image/x-png'  
		);  
    $max_file_size=2000000;     //上传文件大小限制, 单位BYTE  
    $destination_folder="../attachment/zh_tcwq/".date(Y)."/".date(m)."/".date(d)."/"; //上传文件路径  
 //$destination_folder="../attachment/"; //上传文件路径  
    $watermark=2;      //是否附加水印(1为加水印,其他为不加水印);  
    $watertype=1;      //水印类型(1为文字,2为图片)  
    $waterposition=1;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);  
    $waterstring="666666";  //水印字符串  
    // $waterimg="xplore.gif";    //水印图片  
    $imgpreview=1;      //是否生成预览图(1为生成,其他为不生成);  
    $imgpreviewsize=1/2;    //缩略图比例 
    if (!is_uploaded_file($_FILES["upfile"]['tmp_name']))  
    //是否存在文件  
    {  
    	echo "图片不存在!";  
    	exit;  
    }
    $file = $_FILES["upfile"];
    if($max_file_size < $file["size"])
    //检查文件大小  
    {
    	echo "文件太大!";
    	exit;
    }
    // if(!in_array($file["type"], $uptypes))  
    // //检查文件类型
    // {
    // 	echo "文件类型不符!".$file["type"];
    // 	exit;
    // }
    if (!file_exists($destination_folder)){
    	mkdir ($destination_folder,0777,true);
    } 
    $filename=$file["tmp_name"];  
    $image_size = getimagesize($filename);  
    $pinfo=pathinfo($file["name"]);  
    $ftype=$pinfo['extension'];  
    $destination = $destination_folder.str_shuffle(time().rand(111111,999999)).".".$ftype;  
    if (file_exists($destination) && $overwrite != true)  
    {  
    	echo "同名文件已经存在了";  
    	exit;  
    }  
    if(!move_uploaded_file ($filename, $destination))  
    {  
    	echo "移动文件出错";  
    	exit;
    }
    $pinfo=pathinfo($destination);  
    $fname="zh_tcwq/".date(Y)."/".date(m)."/".date(d)."/".$pinfo['basename'];  
    echo $fname;
    @require_once (IA_ROOT . '/framework/function/file.func.php');
    @$filename=$fname;
    @file_remote_upload($filename); 
}
/////////////////////////////////////////



   //提现金额模板消息
public function doPageTxMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$tx=pdo_get('zhtc_withdrawal',array('id'=>$_GPC['txsh_id']));
		if($tx['type']==1){
			$typename="支付宝";
		}
		if($tx['type']==2){
			$typename="微信";
		}
		if($tx['type']==3){
			$typename="银行卡";
		}
		$time=date('Y-m-d H:i:s',$tx['time']);
		$formwork ='{
			"touser": "'.$_GET["openid"].'",
			"template_id": "'.$res["tid2"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$_GET['form_id'].'",
			"data": {
				"keyword1": {
					"value": "'.$tx['name'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value":"'.$tx['username'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$tx['tx_cost'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value": "'.$tx['sj_cost'].'",
					"color": "#173177"
				},
				"keyword5": {
					"value":  "'. $typename.'",
					"color": "#173177"
				},
				"keyword6": {
					"value":  "'. $time.'",
					"color": "#173177"
				}
			}   
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	echo set_msg($_W,$_GPC);
}


 //商家入驻模板消息
public function doPageRzMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息

	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res2=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$sql="select a.store_name,a.time,a.state,b.name as user_name from " . tablename("zhtc_store") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.id=:store_id";
		$res=pdo_fetch($sql,array(':store_id'=>$_GPC['store_id']));
		$type="待审核";
		$note="1-3日完成审核";
		$formwork ='{
			"touser": "'.$_GET["openid"].'",
			"template_id": "'.$res2["tid1"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$_GET['form_id'].'",
			"data": {
				"keyword1": {
					"value": "'.$res['store_name'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value":"'.$res['user_name'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$res['time'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value": "'.$type.'",
					"color": "#173177"
				},
				"keyword5": {
					"value":  "'. $note.'",
					"color": "#173177"
				}
			}   
		}';
             // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	echo set_msg($_W,$_GPC);
}


//保存商家支付记录
public function doPageSaveStorePayLog(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/yj.php';
	$data['store_id']=$_GPC['store_id'];
	$data['money']=$_GPC['money'];
	$data['note']=$_GPC['note'];
	$data['time']=date('Y-m-d H:i:s');
	$data['uniacid']=$_W['uniacid'];
	$res=pdo_insert('zhtc_storepaylog',$data);
	if($res){
      //代理商佣金
		$cityname=Yj::getStoreCity($_GPC['store_id']);
		$yjset=Yj::getYjSet($_W['uniacid']);
		if($yjset['type']==1){
			$money=$_GPC['money']*$yjset['typer']/100;
		}else{
			$money=$_GPC['money']*$yjset['sjper']/100;
		}
		pdo_update('zhtc_account',array('money +='=>$money),array('cityname'=>$cityname));
		echo  '1';
	}else{
		echo  '2';
	}
}

  //保存帖子支付记录
public function doPageSaveTzPayLog(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/yj.php';
    if($_GPC['money1']){//发帖价格
    	$data['tz_id']=$_GPC['tz_id'];
    	$data['note']='发帖费用';
    	$data['money']=$_GPC['money1'];
    	$data['time']=date('Y-m-d H:i:s');
    	$data['uniacid']=$_W['uniacid'];
    	$res=pdo_insert('zhtc_tzpaylog',$data);
    }
     if($_GPC['money2']){//置顶价格
     	$data['tz_id']=$_GPC['tz_id'];
     	$data['note']='发帖置顶';
     	$data['money']=$_GPC['money2'];
     	$data['time']=date('Y-m-d H:i:s');
     	$data['uniacid']=$_W['uniacid'];
     	$res=pdo_insert('zhtc_tzpaylog',$data);
     }
     if($_GPC['money3']){//红包
     	$data['tz_id']=$_GPC['tz_id'];
     	$data['note']='红包福利';
     	$data['money']=$_GPC['money3'];
     	$data['time']=date('Y-m-d H:i:s');
     	$data['uniacid']=$_W['uniacid'];
     	$res=pdo_insert('zhtc_tzpaylog',$data);
     }
      if($_GPC['money4']){//置顶续费
      	$data['tz_id']=$_GPC['tz_id'];
      	$data['note']='置顶续费';
      	$data['money']=$_GPC['money4'];
      	$data['time']=date('Y-m-d H:i:s');
      	$data['uniacid']=$_W['uniacid'];
      	$res=pdo_insert('zhtc_tzpaylog',$data);
      }
       if($_GPC['money5']){//帖子刷新
       	$data['tz_id']=$_GPC['tz_id'];
       	$data['note']='帖子刷新';
       	$data['money']=$_GPC['money5'];
       	$data['time']=date('Y-m-d H:i:s');
       	$data['uniacid']=$_W['uniacid'];
       	$res=pdo_insert('zhtc_tzpaylog',$data);
       }
       if($res){
       //代理商佣金
       	$cityname=Yj::getTzCity($_GPC['tz_id']);
      //var_dump($cityname);die;
       	$yjset=Yj::getYjSet($_W['uniacid']);
       	if($yjset['type']==1){
       		$money=$_GPC['money']*$yjset['typer']/100;
       	}else{
       		$money=$_GPC['money']*$yjset['sjper']/100;
       	}
       	pdo_update('zhtc_account',array('money +='=>$money),array('cityname'=>$cityname));
       	echo  '1';
       }else{
       	echo  '2';
       }
   }

    //保存拼车支付记录
   public function doPageSaveCarPayLog(){
   	global $_W, $_GPC;
   	include IA_ROOT.'/addons/zh_tcwq/yj.php';
   	$data['car_id']=$_GPC['car_id'];
   	$data['money']=$_GPC['money'];
   	$data['time']=date('Y-m-d H:i:s');
   	$data['uniacid']=$_W['uniacid'];
   	$res=pdo_insert('zhtc_carpaylog',$data);
   	if($res){
        //代理商佣金
   		$cityname=Yj::getCarCity($_GPC['car_id']);
      //var_dump($cityname);die;
   		$yjset=Yj::getYjSet($_W['uniacid']);
   		if($yjset['type']==1){
   			$money=$_GPC['money']*$yjset['typer']/100;
   		}else{
   			$money=$_GPC['money']*$yjset['sjper']/100;
   		}
   		pdo_update('zhtc_account',array('money +='=>$money),array('cityname'=>$cityname));
   		echo  '1';
   	}else{
   		echo  '2';
   	}
   }

//保存黄页支付记录
   public function doPageSaveHyPayLog(){
   	global $_W, $_GPC;
   	include IA_ROOT.'/addons/zh_tcwq/yj.php';
   	$data['hy_id']=$_GPC['hy_id'];
   	$data['money']=$_GPC['money'];
   	$data['time']=date('Y-m-d H:i:s');
   	$data['uniacid']=$_W['uniacid'];
   	$res=pdo_insert('zhtc_yellowpaylog',$data);
   	if($res){
    //代理商佣金
   		$cityname=Yj::getYellowCity($_GPC['hy_id']);
   		$yjset=Yj::getYjSet($_W['uniacid']);
   		if($yjset['type']==1){
   			$money=$_GPC['money']*$yjset['typer']/100;
   		}else{
   			$money=$_GPC['money']*$yjset['sjper']/100;
   		}
   		pdo_update('zhtc_account',array('money +='=>$money),array('cityname'=>$cityname));
   		echo  '1';
   	}else{
   		echo  '2';
   	}
   }
//保存定位城市

   public function doPageSaveHotCity(){
   	global $_W, $_GPC;
   	$rst=pdo_get('zhtc_hotcity',array('cityname'=>$_GPC['cityname'],'uniacid'=>$_W['uniacid'],'user_id'=>$_GPC['user_id']));
   	if(empty($rst)){
   		$data['user_id']=$_GPC['user_id'];
   		$data['cityname']=$_GPC['cityname'];
   		$data['time']=time();
   		$data['uniacid']=$_W['uniacid'];
   		$res=pdo_insert('zhtc_hotcity',$data);
   		if($res){
   			echo  '1';
   		}else{
   			echo  '2';
   		}
   	}

   }

//查看是否拉黑

   public function doPageGetUserInfo(){
   	global $_W, $_GPC;
   	$res=pdo_get('zhtc_user',array('id'=>$_GPC['user_id']));
   	echo json_encode($res);
   }

//商家分享数量加一

   public function doPageStoreFxNum(){
   	global $_W, $_GPC;
   	$res=pdo_update('zhtc_store',array('fx_num +='=>1),array('id'=>$_GPC['store_id']));
   	if($res){
   		echo  '1';
   	}else{
   		echo  '2';
   	}
   }

//红包
   public function doPageRedPaperList(){
   	global $_GPC, $_W;
   	$pageindex = max(1, intval($_GPC['page']));
   	$pagesize=$_GPC['pagesize'];
   	$sql="select a.*,b.logo,c.img as user_img from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id"  . " left join " . tablename("zhtc_user") . " c on c.id=a.user_id  WHERE a.uniacid=:uniacid and a.hb_num>0 and a.del=2 and a.state=2 ORDER BY a.id DESC";
   	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
   	$res=pdo_fetchall($select_sql,array(':uniacid'=>$_W['uniacid']));
   	echo json_encode($res);
   }

//获取城市

   public function doPageGetCity(){
   	global $_W, $_GPC;
   	$res=pdo_getall('zhtc_hotcity',array('uniacid'=>$_W['uniacid'],'user_id'=>$_GPC['user_id']));
   	echo json_encode($res);
   }


//保存formid
   public function doPageSaveFormid(){
   	global $_W, $_GPC;
   	$time=time()-60*60*24*7;
   	pdo_query("delete from ".tablename('zhtc_userformid')." where UNIX_TIMESTAMP(time)<={$time}");

   	$data['user_id']=$_GPC['user_id'];
   	$data['form_id']=$_GPC['form_id'];
   	$data['openid']=$_GPC['openid']; 
   	$data['time']=date('Y-m-d H:i:s');
   	$data['uniacid']=$_W['uniacid'];
   	if($_GPC['form_id']!='the formId is a mock one' and $_GPC['form_id']){
   		$res=pdo_insert('zhtc_userformid',$data);
   	}

   	if($res){
   		echo  '1';
   	}else{
   		echo  '2';
   	}
   }

//删除formid
   public function doPageDelFormid(){
   	global $_W, $_GPC;
   	$res=pdo_delete('zhtc_userformid',array('user_id'=>$_GPC['user_id'],'form_id'=>$_GPC['form_id']));
   	if($res){
   		echo  '1';
   	}else{
   		echo  '2';
   	}
   }

  //获取用户的formid
   public function doPageGetFormid(){
   	global $_W, $_GPC;
   	$time=time()-60*60*24*7;
   	$sql="delete from " . tablename("zhtc_userformid") . " where UNIX_TIMESTAMP(time) <=".$time;
   	pdo_query($sql);
   	$res=pdo_getall('zhtc_userformid',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_W['uniacid']));
   	echo json_encode($res);
   } 




//帖子评论成功模板消息
   public function doPageStorehfMessage(){
   	global $_W, $_GPC;
   	function getaccess_token($_W){
   		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
   		$appid=$res['appid'];
   		$secret=$res['appsecret'];
   		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
   		$ch = curl_init();
   		curl_setopt($ch, CURLOPT_URL,$url);
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
   		$data = curl_exec($ch);
   		curl_close($ch);
   		$data = json_decode($data,true);
   		return $data['access_token'];
   	}
      //设置与发送模板信息

   	function set_msg($_W, $_GPC){
   		$access_token = getaccess_token($_W);
   		$res2=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
   		$sql="select a.details,a.store_id,a.time,b.name as user_name from " . tablename("zhtc_comments") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.id=:id ";
   		$res=pdo_fetch($sql,array(':id'=>$_GPC['pl_id']));
   		$time=date("Y-m-d H:i:s",$res['time']);
   		$formwork ='{
   			"touser": "'.$_GET["openid"].'",
   			"template_id": "'.$res2["tid3"].'",
   			"page":"zh_tcwq/pages/sellerinfo/sellerinfo?id='.$res['store_id'].'",
   			"form_id":"'.$_GET['form_id'].'",
   			"data": {
   				"keyword1": {
   					"value": "'.$res['details'].'",
   					"color": "#173177"
   				},
   				"keyword2": {
   					"value":"'.$res['user_name'].'",
   					"color": "#173177"
   				},
   				"keyword3": {
   					"value": "'.$time.'",
   					"color": "#173177"
   				}

   			}   
   		}';
             // $formwork=$data;
   		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
   		$ch = curl_init();
   		curl_setopt($ch, CURLOPT_URL,$url);
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
   		curl_setopt($ch, CURLOPT_POST,1);
   		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
   		$data = curl_exec($ch);
   		curl_close($ch);
   		return $data;
   	}
   	echo set_msg($_W,$_GPC);
   }


    //商家福利
   public function doPageMyPost2(){
   	global $_GPC, $_W;
   	$pageindex = max(1, intval($_GPC['page']));
   	$pagesize=10;
   	$sql="select a.*,b.type_name,c.name as type2_name from " . tablename("zhtc_information") . " a"  . " left join " . tablename("zhtc_type") . " b on b.id=a.type_id  " . " left join " . tablename("zhtc_type2") . " c on a.type2_id=c.id   WHERE a.store_id=:store_id and a.del=2   ORDER BY a.id DESC";
   	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
   	$res = pdo_fetchall($select_sql,array(':store_id'=>$_GPC['user_id']));
   	echo json_encode($res);
   }

//红包分享
   public function doPageHbFx(){
   	global $_GPC, $_W;
   	$res=pdo_update('zhtc_information',array('hbfx_num +='=>1),array('id'=>$_GPC['information_id']));
   	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
   	$jf=pdo_get('zhtc_integral',array('user_id'=>$_GPC['user_id'],'note'=>'分享帖子','tid'=>$_GPC['information_id']));
   	if(!$jf and $system['fx_jf']>0){
   		pdo_update('zhtc_user',array('total_score +='=>$system['fx_jf']),array('id'=>$_GPC['user_id']));
   		$data['score']=$system['fx_jf'];
   		$data['user_id']=$_GPC['user_id'];
   		$data['tid']=$_GPC['information_id'];
   		$data['note']='分享帖子';
   		$data['type']=1;
   		$data['cerated_time']=date('Y-m-d H:i:s');
    $data['uniacid']=$_W['uniacid'];//小程序id
    pdo_insert('zhtc_integral',$data);//添加积分明细 
}
if($res){
	echo 1;
}else{
	echo 2;
}
}


//获取广告详情

public function doPageGetAdInfo(){
	global $_GPC, $_W;
	$res=pdo_get('zhtc_ad',array('id'=>$_GPC['ad_id']));
	echo json_encode($res);
}




//获取导航信息

public function doPageGetNav(){
	global $_GPC, $_W;
	$res=pdo_getall('zhtc_nav',array('uniacid'=>$_W['uniacid'],'status'=>1),array(),'','orderby asc');
	echo json_encode($res);
}

//获取导航详情
public function doPageGetNavInfo(){
	global $_GPC, $_W;
	$res=pdo_get('zhtc_nav',array('id'=>$_GPC['nav_id']));
	echo json_encode($res);
}
//视频分类
public function doPageVideoType(){
	global $_GPC, $_W;
	$res=pdo_getall('zhtc_videotype',array('uniacid'=>$_W['uniacid'],'state'=>1),array(),'','num asc');
	echo json_encode($res);
}
//视频列表
public function doPageVideoList(){
	global $_GPC, $_W;
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=$_GPC['pagesize'];
	$where=" where a.uniacid=:uniacid";
	$data[':uniacid']=$_W['uniacid'];
	if($_GPC['type_id']){
		$where.=" and  a.type_id=:type_id";  
		$data[':type_id']=$_GPC['type_id'];
	}
	if($_GPC['cityname']){
		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='')";  
		$data[':name']=$_GPC['cityname'];
	}
	$sql="select a.* from" . tablename("zhtc_video") . " a"  . " left join " . tablename("zhtc_videotype") . " b on b.id=a.type_id ".$where."  ORDER BY a.num asc,a.time DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res = pdo_fetchall($select_sql,$data);
	function video($video){
		$vid=trim(strrchr($video, '/'),'/');
		$vid=substr($vid,0,-5);
		$json=file_get_contents("http://vv.video.qq.com/getinfo?vids=".$vid."&platform=101001&charge=0&otype=json");
       // echo $json;die;
		$json=substr($json,13);
		$json=substr($json,0,-1);
		$a=json_decode(html_entity_decode($json));
		$sz=json_decode(json_encode($a),true);
       // print_R($sz);die;
		$url=$sz['vl']['vi']['0']['ul']['ui']['0']['url'];
		$fn=$sz['vl']['vi']['0']['fn'];
		$fvkey=$sz['vl']['vi']['0']['fvkey'];
		$url=$url.$fn.'?vkey='.$fvkey;
		return  $url;
	}
	for ($i=0; $i <count($res) ; $i++) { 
		$video=$res[$i]['url'];
		$res[$i]['url']=video($video);
	}





	echo json_encode($res);
}

//视频详情
public function doPageVideoInfo(){
	global $_GPC, $_W;
	$info=pdo_get('zhtc_video',array('id'=>$_GPC['video_id']));
	pdo_update('zhtc_video',array('yd_num +='=>1),array('id'=>$_GPC['video_id']));
	$sql="select a.*,b.name as user_name ,b.img as user_img from " . tablename("zhtc_videodz") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id   WHERE a.video_id=:video_id ORDER BY a.id DESC";
	$dz=pdo_fetchall($sql,array(':video_id'=>$_GPC['video_id']));
	$sql2="select a.*,b.name as user_name ,b.img as user_img from " . tablename("zhtc_videopl") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id   WHERE a.video_id=:video_id ORDER BY a.id DESC";
	$pl=pdo_fetchall($sql2,array(':video_id'=>$_GPC['video_id']));
	function video($video){
		$vid=trim(strrchr($video, '/'),'/');
		$vid=substr($vid,0,-5);
		$json=file_get_contents("http://vv.video.qq.com/getinfo?vids=".$vid."&platform=101001&charge=0&otype=json");
       // echo $json;die;
		$json=substr($json,13);
		$json=substr($json,0,-1);
		$a=json_decode(html_entity_decode($json));
		$sz=json_decode(json_encode($a),true);
       // print_R($sz);die;
		$url=$sz['vl']['vi']['0']['ul']['ui']['0']['url'];
		$fn=$sz['vl']['vi']['0']['fn'];
		$fvkey=$sz['vl']['vi']['0']['fvkey'];
		$url=$url.$fn.'?vkey='.$fvkey;
		return  $url;
	}

	$data['info']=$info;
	$data['info']['url']=video($data['info']['url']);
	$data['dz']=$dz;
	$data['pl']=$pl;
	echo json_encode($data);
}
//视频点赞
public function doPageVideoDz(){
	global $_GPC, $_W;
	$data['user_id']=$_GPC['user_id'];
	$data['video_id']=$_GPC['video_id'];
	$dz=pdo_get('zhtc_videodz',$data);
	if($dz){
		$res=pdo_delete('zhtc_videodz',$data);
		if($res){
			pdo_update('zhtc_video',array('dz_num -='=>1),array('id'=>$_GPC['video_id']));
			echo '取消成功!';
		}else{
			echo '取消失败!';
		}
	}else{
		$res=pdo_insert('zhtc_videodz',$data);
		if($res){
			pdo_update('zhtc_video',array('dz_num +='=>1),array('id'=>$_GPC['video_id']));
			echo '点赞成功!';
		}else{
			echo '点赞失败!';
		}
	}
}
//视频评论
public function doPageVideoPl(){
	global $_GPC, $_W;
	$data['user_id']=$_GPC['user_id'];
	$data['video_id']=$_GPC['video_id'];
	$data['content']=$_GPC['content'];
	$data['time']=date("Y-m-d H:i:s");
	$res=pdo_insert('zhtc_videopl',$data);
	if($res){
		pdo_update('zhtc_video',array('pl_num +='=>1),array('id'=>$_GPC['video_id']));
		echo '评论成功!';
	}else{
		echo '评论失败!';
	}  
}


//帖子续费
public function doPageTzXf(){
	global $_GPC, $_W;
	$list=pdo_get('zhtc_information',array('id'=>$_GPC['id']));
	if($_GPC['type']==1){
		$time=24*60*60;
	}elseif($_GPC['type']==2){
		$time=24*60*60*7;
	}elseif($_GPC['type']==3){
		$time=24*60*60*30;
	}
	if($list['dq_time']==0 || $list['dq_time']<time()){
		$bq_time=time()+$time;
	}else{
		$bq_time=$list['dq_time']+$time;
	}
	if($_GPC['cityname']=='本地'){
		$res=pdo_update('zhtc_information',array('top'=>1,'dq_time'=>$bq_time,'cityname'=>$_GPC['cityname2']),array('id'=>$_GPC['id']));
	}elseif($_GPC['cityname']=='全国版'){
		$res=pdo_update('zhtc_information',array('top'=>1,'dq_time'=>$bq_time,'cityname'=>''),array('id'=>$_GPC['id']));
	}

	if($res){
		echo '1';
	}else{
		echo '2';
	}
}

//商家续费
public function doPageSjXf(){
	global $_GPC, $_W;
	$list=pdo_get('zhtc_store',array('id'=>$_GPC['id']));
	if($_GPC['type']==1){
		$time=24*60*60*7;
	}elseif($_GPC['type']==2){
		$time=24*60*60*182;
	}elseif($_GPC['type']==3){
		$time=24*60*60*365;
	}
	if($list['dq_time']==0 || $list['dq_time']<time()){
		$bq_time=time()+$time;
	}else{
		$bq_time=$list['dq_time']+$time;
	}
	if($_GPC['cityname']=='本地'){
		$res=pdo_update('zhtc_store',array('time_over'=>2,'dq_time'=>$bq_time,'cityname'=>$_GPC['cityname2']),array('id'=>$_GPC['id']));
	}elseif($_GPC['cityname']=='全国版'){
		$res=pdo_update('zhtc_store',array('time_over'=>2,'dq_time'=>$bq_time,'cityname'=>''),array('id'=>$_GPC['id']));
	} 
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}
//黄页续费
public function doPageHyXf(){
	global $_GPC, $_W;
	$list=pdo_get('zhtc_yellowstore',array('id'=>$_GPC['id']));
	$time=24*60*60*$_GPC['day'];
	if($list['dq_time']==0 || $list['dq_time']<time()){
		$bq_time=time()+$time;
	}else{
		$bq_time=$list['dq_time']+$time;
	}
	$res=pdo_update('zhtc_yellowstore',array('time_over'=>2,'dq_time'=>$bq_time),array('id'=>$_GPC['id']));
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}
  //刷新帖子
public function doPageSxTz(){
	global $_GPC, $_W;
	$res=pdo_update('zhtc_information',array('sh_time'=>time()),array('id'=>$_GPC['id']));
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}
  //查看帖子刷新价格
public function doPageSxMoney(){
	global $_GPC, $_W;
	$res=pdo_get('zhtc_type',array('id'=>$_GPC['type_id']));
	echo json_encode($res);
}

//查看发帖次数是否超限
public function doPageFtXz(){
	global $_GPC, $_W;
	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($system['ft_num']!=0){
		$time1=strtotime(date('Y-m-d'));
		$time2=$time1+24*60*60;
		$res=count(pdo_getall('zhtc_information',array('time >='=>$time1,'time <'=>$time2,'user_id'=>$_GPC['user_id'])));
		if($res>=$system['ft_num']){
			echo '今天发帖次数已经超限!';
		}
	}   
}



//签到
public function doPageSign(){
	global $_W, $_GPC;
	$sign=pdo_get('zhtc_signlist',array('user_id'=>$_GPC['user_id'],'time'=>$_GPC['time']));
	if(!$sign){
		$time2=explode(',',$_GPC['time']);
		$time2=$time2[0]."-".$time2[1]."-".$time2[2];
		$time2=strtotime($time2);
		$data['time2']=$time2;
		$data['time3']=time();
		$data['user_id']=$_GPC['user_id'];
		$data['time']=$_GPC['time'];
		$data['integral']=$_GPC['integral'];
		$data['uniacid']=$_W['uniacid'];
		$res=pdo_insert('zhtc_signlist',$data);
		if($res){
			if($_GPC['one']){
            pdo_update('zhtc_user',array('total_score +='=>$_GPC['one'],'day +='=>1),array('id'=>$_GPC['user_id']));//签到增加积分/签到天数
            $data2['score']=$_GPC['one'];
            $data2['user_id']=$_GPC['user_id'];
            $data2['note']='首次签到';
            $data2['type']=1;
            $data2['cerated_time']=date('Y-m-d H:i:s');
          $data2['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('zhtc_integral',$data2);//添加积分明细
      }else{
             pdo_update('zhtc_user',array('total_score +='=>$_GPC['integral'],'day +='=>1),array('id'=>$_GPC['user_id']));//签到增加积分/签到天数
             $data2['score']=$_GPC['integral'];
             $data2['user_id']=$_GPC['user_id'];
             $data2['note']='每日签到';
             $data2['type']=1;
             $data2['cerated_time']=date('Y-m-d H:i:s');
          $data2['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('zhtc_integral',$data2);//添加积分明细
      }
          $list=pdo_getall('zhtc_continuous',array('uniacid'=>$_W['uniacid']));//连续签到列表

          $my=pdo_getall('zhtc_signlist',array('user_id'=>$_GPC['user_id']),array(),'','time2 DESC');
     // print_r($list);die;
          $time=date('Y,n,j',time());//今天
          $jt=pdo_get('zhtc_signlist',array('user_id'=>$_GPC['user_id'],'time'=>$time));//查看今天有没有签到
          if($jt){//签到了
          	$num=0;
          	for($i=0;$i<count($my);$i++){
                  if(date('Y,n,j',time()-$i*60*60*24)==$my[$i]['time']){//从今天开始匹对
                  	$num=$num+1;
                  }else{
                  	break;
                  }
              }
          }else{
          	$num=0;
          	for($i=0;$i<count($my);$i++){
                  if(date('Y,n,j',time()-($i+1)*60*60*24)==$my[$i]['time']){//从昨天开始匹对
                  	$num=$num+1;
                  }else{
                  	break;
                  }
              }
          }

          for($k=0;$k<count($list);$k++){
          	if($num==$list[$k]['day']){
          		$data3['score']=$list[$k]['integral'];
          		$data3['user_id']=$_GPC['user_id'];
          		$data3['note']="连续签到".$list[$k]['day']."天";
          		$data3['type']=1;
          		$data3['cerated_time']=date('Y-m-d H:i:s');
                  $data3['uniacid']=$_W['uniacid'];//小程序id
                  $qd=pdo_get('zhtc_integral',array('uniacid'=>$_W['uniacid'],'note'=>$data3['note'],'user_id'=>$_GPC['user_id']));
                  if(!$qd){
                    pdo_insert('zhtc_integral',$data3);//添加积分明细
                    pdo_update('zhtc_user',array('total_score +='=>$list[$k]['integral']),array('id'=>$_GPC['user_id']));//连续签到增加积分
                }
                break;
            }
        }
        echo '1';
    }else{
    	echo '2';
    }
}

}
  //补签
public function doPageSign2(){
	global $_W, $_GPC;
	$time2=explode(',',$_GPC['time']);
	$time2=$time2[0]."-".$time2[1]."-".$time2[2];
	$time2=strtotime($time2);
	$data['time2']=$time2;
	$data['time3']=time();
	$data['user_id']=$_GPC['user_id'];
	$data['time']=$_GPC['time'];
	$data['integral']=$_GPC['integral'];
	$data['uniacid']=$_W['uniacid'];
	$res=pdo_insert('zhtc_signlist',$data);
	$res2=pdo_get('zhtc_signset',array('uniacid'=>$_W['uniacid']));
	if($res){
          pdo_update('zhtc_user',array('total_score -='=>$res2['bq_integral']),array('id'=>$_GPC['user_id']));//签到增加积分/签到天数
          $data4['score']=$res2['bq_integral'];
          $data4['user_id']=$_GPC['user_id'];
          $data4['note']='补签';
          $data4['type']=2;
          $data4['cerated_time']=date('Y-m-d H:i:s');
          $data4['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('zhtc_integral',$data4);//添加积分明细

          if($_GPC['one']){
            pdo_update('zhtc_user',array('total_score +='=>$_GPC['one'],'day +='=>1),array('id'=>$_GPC['user_id']));//签到增加积分/签到天数
            $data2['score']=$_GPC['one'];
            $data2['user_id']=$_GPC['user_id'];
            $data2['note']='首次签到';
            $data2['type']=1;
            $data2['cerated_time']=date('Y-m-d H:i:s');
          $data2['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('zhtc_integral',$data2);//添加积分明细
      }else{
             pdo_update('zhtc_user',array('total_score +='=>$_GPC['integral'],'day +='=>1),array('id'=>$_GPC['user_id']));//签到增加积分/签到天数
             $data2['score']=$_GPC['integral'];
             $data2['user_id']=$_GPC['user_id'];
             $data2['note']='每日签到';
             $data2['type']=1;
             $data2['cerated_time']=date('Y-m-d H:i:s');
          $data2['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('zhtc_integral',$data2);//添加积分明细
      }

          $list=pdo_getall('zhtc_continuous',array('uniacid'=>$_W['uniacid']));//连续签到列表

          $my=pdo_getall('zhtc_signlist',array('user_id'=>$_GPC['user_id']),array(),'','time2 DESC');
          $time=date('Y,n,j',time());//今天
          $jt=pdo_get('zhtc_signlist',array('user_id'=>$_GPC['user_id'],'time'=>$time));//查看今天有没有签到
          if($jt){//签到了
          	$num=0;
          	for($i=0;$i<count($my);$i++){
                  if(date('Y,n,j',time()-$i*60*60*24)==$my[$i]['time']){//从今天开始匹对
                  	$num=$num+1;
                  }else{
                  	break;
                  }
              }
          }else{
          	$num=0;
          	for($i=0;$i<count($my);$i++){
                  if(date('Y,n,j',time()-($i+1)*60*60*24)==$my[$i]['time']){//从昨天开始匹对
                  	$num=$num+1;
                  }else{
                  	break;
                  }
              }
          }

          for($k=0;$k<count($list);$k++){
          	if($num==$list[$k]['day']){
          		$data3['score']=$list[$k]['integral'];
          		$data3['user_id']=$_GPC['user_id'];
          		$data3['note']="连续签到".$list[$k]['day']."天";
          		$data3['type']=1;
          		$data3['cerated_time']=date('Y-m-d H:i:s');
                  $data3['uniacid']=$_W['uniacid'];//小程序id
                  $qd=pdo_get('zhtc_integral',array('uniacid'=>$_W['uniacid'],'note'=>$data3['note'],'user_id'=>$_GPC['user_id']));
                  if(!$qd){
                    pdo_insert('zhtc_integral',$data3);//添加积分明细
                    pdo_update('zhtc_user',array('total_score +='=>$list[$k]['integral']),array('id'=>$_GPC['user_id']));//连续签到增加积分
                }
                break;
            }
        }

        echo '1';
    }else{
    	echo '2';
    }
}
  //查看是否补签
public function doPageIsbq(){
	global $_W, $_GPC;
	$time=date('Y-m-d');

	$time="'%$time%'";
     // echo $time;die;
	$sql="select *  from " . tablename("zhtc_integral") ." WHERE  cerated_time LIKE ".$time." and user_id=".$_GPC['user_id']." and note='补签'";
    //  echo $sql;die;
	$res=pdo_fetch($sql);
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}
  //查看我的签到
public function doPageMySign(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_signlist',array('user_id'=>$_GPC['user_id']));
	echo json_encode($res);
}
  //签到排行
public function doPageRank(){
	global $_W, $_GPC;
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=$_GPC['pagesize'];
	$where="where uniacid=:uniacid and day!=''";
	$sql= "select * from".tablename('zhtc_user').$where." order by day DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql,array(':uniacid'=>$_W['uniacid']));
	echo json_encode($res);
}
  //今日排行
public function doPageJrRank(){
	global $_W, $_GPC;
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=$_GPC['pagesize'];
	$sql="select a.*,b.name,b.img from " . tablename("zhtc_signlist") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.uniacid=:uniacid and a.time=:time order by time3 asc";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql,array(':uniacid'=>$_W['uniacid'],':time'=>date('Y,n,j')));
	echo json_encode($res);
}
   //我的今日排行
public function doPageMyJrRank(){
	global $_W, $_GPC;
	$sql="select a.*,b.name,b.img from " . tablename("zhtc_signlist") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.uniacid=:uniacid and a.time=:time order by time3 asc";
	$res=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'],':time'=>date('Y,n,j')));
	for($i=0;$i<count($res);$i++){
		if($_GPC['user_id']==$res[$i]['user_id']){
			$res[$i]['num']=$i+1;
			$list=$res[$i];
		}
	}
	echo json_encode($list);
}
  //查看今天是否签到
public function doPageMyJrSign(){
	global $_W, $_GPC;
	$res=pdo_get('zhtc_signlist',array('user_id'=>$_GPC['user_id'],'time'=>date('Y,n,j')));
	if($res){
		echo '1';
	}else{
		echo  '2';
	}
}
  //查看今日签到所得积分
public function doPageMyJrJf(){
	global $_W, $_GPC;
	$time=date('Y-m-d');
	$time="'%$time%'";
	$sql="select sum(score) as total  from " . tablename("zhtc_integral") ." WHERE  cerated_time LIKE ".$time." and user_id=".$_GPC['user_id']." and (note='每日签到' || note='首次签到' || note LIKE '%连续签到%')";
	$res=pdo_fetch($sql);
	echo $res['total'];
}
  //查看连签奖励
public function doPageContinuousList(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_continuous',array('uniacid'=>$_W['uniacid']),array(),'','day asc');
	echo json_encode($res);
}
  //查看特殊日期奖励
public function doPageSpecial(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_special',array('uniacid'=>$_W['uniacid']));
	echo json_encode($res);
}
  //查看签到规则
public function doPageSignset(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_signset',array('uniacid'=>$_W['uniacid']));
	echo json_encode($res);
}
//查看连续签到天数
public function doPageContinuous(){
	global $_W, $_GPC;
	$my=pdo_getall('zhtc_signlist',array('user_id'=>$_GPC['user_id']),array(),'','time2 desc');
        $time=date('Y,n,j',time());//今天
        $jt=pdo_get('zhtc_signlist',array('user_id'=>$_GPC['user_id'],'time'=>$time));//查看今天有没有签到
        if($jt){//签到了
        	$num=0;
        	for($i=0;$i<count($my);$i++){
                if(date('Y,n,j',time()-$i*60*60*24)==$my[$i]['time']){//从今天开始匹对
                	$num=$num+1;
                }else{
                	break;
                }
            }
        }else{
        	$num=0;
        	for($i=0;$i<count($my);$i++){
                if(date('Y,n,j',time()-($i+1)*60*60*24)==$my[$i]['time']){//从昨天开始匹对
                	$num=$num+1;
                }else{
                	break;
                }
            }
        }
        echo $num;      
    }




//用户信息
    public function doPageUserInfo(){
    	global $_W, $_GPC;
    	$user=pdo_get('zhtc_user',array('id'=>$_GPC['user_id']));
    	echo json_encode($user);
    }











//商品分类
    public function doPageJftype(){
    	global $_W, $_GPC;
    	$res=pdo_getall('zhtc_jftype',array('uniacid'=>$_W['uniacid']),array(),'','num asc');
    	echo json_encode($res);
    }

//商品列表
    public function doPageJfGoods(){
    	global $_W, $_GPC;
    	$res=pdo_getall('zhtc_jfgoods',array('uniacid'=>$_W['uniacid'],'is_open'=>1),array(),'','num asc');
    	echo json_encode($res);
    }
 //商品详情
    public function doPageJfGoodsInfo(){
    	global $_W, $_GPC;
    	$res=pdo_getall('zhtc_jfgoods',array('id'=>$_GPC['id']));
    	echo json_encode($res); 
    }
 //分类下的商品
    public function doPageJftypeGoods(){
    	global $_W, $_GPC;
    	$res=pdo_getall('zhtc_jfgoods',array('type_id'=>$_GPC['type_id'],'is_open'=>1),array(),'','num asc');
    	echo json_encode($res);
    }
//积分商城广告
    public function doPageAd3(){
    	global $_GPC, $_W;
    	$where=" where uniacid=:uniacid and status=1 and type=9";
    	if($_GPC['cityname']){
    		$where.=" and (cityname LIKE  concat('%', :name,'%') or cityname='全国版')";  
    		$data[':name']=$_GPC['cityname'];
    	}
    	$data[':uniacid']=$_W['uniacid'];
    	$sql="select * from ".tablename('zhtc_ad').$where." order by orderby asc";
    	$res=pdo_fetchall($sql,$data);
    	echo json_encode($res);
    }
//兑换商品
    public function doPageExchange(){
    	global $_W, $_GPC;
      $data['user_id']=$_GPC['user_id'];//用户id
      $data['good_id']=$_GPC['good_id'];//商品id
      $data['user_name']=$_GPC['user_name'];//用户名称
      $data['user_tel']=$_GPC['user_tel'];//用户电话
      $data['address']=$_GPC['address'];//地址
      $data['integral']=$_GPC['integral'];//积分
      $data['good_name']=$_GPC['good_name'];//商品名称
      $data['good_img']=$_GPC['good_img'];//商品图片
      $data['time']=date("Y-m-d H:i:s");
      $res=pdo_insert('zhtc_jfrecord',$data);
      if($res){
      	pdo_update('zhtc_jfgoods',array('number -='=>1),array('id'=>$_GPC['good_id']));
          if($_GPC['type']==1){//虚拟红包
          	pdo_update('zhtc_user',array('money +='=>$_GPC['hb_money']),array('id'=>$_GPC['user_id']));
          	$data2['money']=$_GPC['hb_money'];
          	$data2['user_id']=$_GPC['user_id'];
          	$data2['type']=1;
          	$data2['note']='积分兑换';
          	$data2['time']=date('Y-m-d H:i:s');
          	pdo_insert('zhtc_qbmx',$data2);
          }
          $data3['score']=$_GPC['integral'];
          $data3['user_id']=$_GPC['user_id'];
          $data3['note']='兑换商品';
          $data3['type']=2;
          $data3['cerated_time']=date('Y-m-d H:i:s');
          $data3['uniacid']=$_W['uniacid'];//小程序id
          pdo_insert('zhtc_integral',$data3); 
          pdo_update('zhtc_user',array('total_score -='=>$_GPC['integral']),array('id'=>$_GPC['user_id']));
          echo '1';
      }else{
      	echo '2';
      }
  }
  //兑换明细
  public function doPageDhmx(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_jfrecord',array('user_id'=>$_GPC['user_id']),array(),'','id DESC');
  	echo json_encode($res);
  }


//积分明细
  public function doPageJfmx(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_integral',array('user_id'=>$_GPC['user_id']),array(),'','id DESC');
  	echo json_encode($res);
  }


//分类轮播图
  public function doPageStoreTypeAd(){
  	global $_W, $_GPC;
  	if($_GPC['type']==1){
  		$sql="select *  from " . tablename("zhtc_storetypead") ." WHERE status=1 and  type_id={$_GPC['type_id']} and (cityname='{$_GPC['cityname']}' || cityname='全国版') order by orderby asc";
  		$res=pdo_fetchall($sql);
  	}elseif($_GPC['type']==2){
  		$sql="select *  from " . tablename("zhtc_storetypead") ." WHERE status=1 and type_id2={$_GPC['type_id']} and (cityname='{$_GPC['cityname']}' || cityname='全国版') order by orderby asc";
  		$res=pdo_fetchall($sql);
  	}
  	echo json_encode($res);
  }


//活动分类
  public function doPageActType(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_acttype',array('uniacid'=>$_W['uniacid'],'state'=>1),array(),'','num asc');
  	echo json_encode($res);
  }
//活动列表
  public function doPageActivity(){
  	global $_W, $_GPC;
  	$where=" where a.uniacid=".$_W['uniacid'];
  	if($_GPC['type_id']){
  		$where .=" and a.type_id=".$_GPC['type_id'];
  	}
  	if($_GPC['cityname']){
  		$where .=" and (a.cityname='{$_GPC['cityname']}' || a.cityname='')";
  	}
  	$pageindex = max(1, intval($_GPC['page']));
  	$pagesize=$_GPC['pagesize'];
  	$sql=" select a.* ,b.type_name from ".tablename('zhtc_activity') . " a"  . " left join " . tablename("zhtc_acttype") . " b on b.id=a.type_id ".$where." order by a.num asc";
  	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
  	$res=pdo_fetchall($select_sql,$data);
  	echo json_encode($res);
  }
//活动详情
  public function doPageActivityInfo(){
  	global $_W, $_GPC;
  	$res=pdo_get('zhtc_activity',array('id'=>$_GPC['id']));
  	if($res['img']){
  		if(strpos($res['img'],',')){
  			$res['img']= explode(',',$res['img']);
  		}else{
  			$res['img']=array(
  				0=>$res['img']
  				);
  		}
  	} 
  	echo json_encode($res);
  }
//报名
  public function doPageSignUp(){
  	global $_W, $_GPC;
  	$act=pdo_get('zhtc_activity',array('id'=>$_GPC['act_id']));
  	if(($act['number']-$act['sign_num'])>0 and $act['number']!=0){
  		$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  		$data['user_id']=$_GPC['user_id'];
  		$data['act_id']=$_GPC['act_id'];
  		$data['time']=date("Y-m-d H:i:s");
  		$data['money']=$_GPC['money'];
  		$data['user_name']=$_GPC['user_name'];
  		$data['user_tel']=$_GPC['user_tel'];
  		$data['form_id']=$_GPC['form_id'];
  		if($_GPC['money']==0){
  			if($system['is_bm']==1){

  				$data['state']=3;
  			}else{
  				$data['state']=2;
  			} 
  		}else{
  			$data['state']=1;
  		}
  		$data['uniacid']=$_W['uniacid'];
  		$res=pdo_insert('zhtc_joinlist',$data);
  		$id=pdo_insertid();
  		if($res){
  			if($_GPC['money']==0){
              file_get_contents("".$_W['siteroot']."/app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=HdMessage&m=zh_tcwq&id=".$id);//模板消息
              pdo_update('zhtc_activity',array('sign_num +='=>1),array('id'=>$_GPC['act_id'])); 
          }

          echo $id;
      }else{
      	echo '报名失败';
      }
  }else{
  	echo '人数已满';
  }

}
//查看是否能报名
public function doPageIsSignUp(){
	global $_W, $_GPC;
	$res=pdo_get('zhtc_joinlist',array('user_id'=>$_GPC['user_id'],'act_id'=>$_GPC['act_id'],'state'=>array('2','3')));
	if($res){
		echo '2';
	}else{
		echo '1';
	}
}

 //活动支付
public function doPagePay3(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
           // include 'wxpay.php';
	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($res['pt_name']){
		$res['pt_name']=$res['pt_name'];
	}else{
		$res['pt_name']='同城小程序';
	}
	$appid=$res['appid'];
          $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
          $mch_id=$res['mchid'];
          $key=$res['wxkey'];
          $out_trade_no = $mch_id. time();
          $root=$_W['siteroot'];
          pdo_update('zhtc_joinlist',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
          $total_fee =$_GPC['money'];
            if(empty($total_fee)) //押金
            {
            	$body = $res['pt_name'];
            	$total_fee = floatval(99*100);
            }else{
            	$body = $res['pt_name'];
            	$total_fee = floatval($total_fee*100);
            }
            $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
            $return=$weixinpay->pay();
            echo json_encode($return);
        }



//我的报名
        public function doPageMyActivity(){
        	global $_W, $_GPC;
        	$where=" where a.user_id=".$_GPC['user_id']." and a.state!=1 and b.title!=''";
        	$pageindex = max(1, intval($_GPC['page']));
        	$pagesize=$_GPC['pagesize'];
        	$sql=" select a.* ,b.title,b.start_time,b.end_time,b.address,b.coordinate,b.logo from ".tablename('zhtc_joinlist') . " a"  . " left join " . tablename("zhtc_activity") . " b on b.id=a.act_id ".$where." order by a.id desc";
        	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        	$res=pdo_fetchall($select_sql,$data);
        	echo json_encode($res);
        }
//核销码
        public function doPageActCode(){
        	global $_W, $_GPC;
        	function  getCoade($storeid){
        		function getaccess_token(){
        			global $_W, $_GPC;
        			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
        			$appid=$res['appid'];
        			$secret=$res['appsecret'];
		              // print_r($res);die;
        			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
        			$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL,$url);
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        			$data = curl_exec($ch);
        			curl_close($ch);
        			$data = json_decode($data,true);
        			return $data['access_token'];
        		}
        		function set_msg($storeid){
        			$access_token = getaccess_token();
        			$data2=array(
        				"scene"=>$storeid,
        				"page"=>"zh_tcwq/pages/wdbm/hx",
        				"width"=>400
        				);
        			$data2 = json_encode($data2);
        			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        			$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL,$url);
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        			curl_setopt($ch, CURLOPT_POST,1);
        			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
        			$data = curl_exec($ch);
        			curl_close($ch);
        			return $data;
        		}
        		$img=set_msg($storeid);
        		$img=base64_encode($img);
        		return $img;
        	}


        	echo getCoade($_GPC['id']);
        }



//核销
        public function doPageActHx(){
        	global $_W, $_GPC;
        	$join=pdo_get('zhtc_joinlist',array('id'=>$_GPC['id']));
        	$hx=pdo_get('zhtc_acthxlist',array('user_id'=>$_GPC['user_id'],'act_id'=>$join['act_id']));
        	if($join['state']==4){
        		echo '2';
        	}else{
        		if($hx){
        			$res=pdo_update('zhtc_joinlist',array('state'=>4,'hx_time'=>date("Y-m-d H:i:s")),array('id'=>$_GPC['id']));
        			if($res){
        				echo '1'; 
        			}else{
        				echo '2';
        			} 
        		}else{
        			echo '暂无核销权限';
        		}

        	}


        }


 //城市佣金
        public function doPageActYj(){
        	global $_W, $_GPC;
        	$cityname=Yj::getActCity($_GPC['act_id']);
        	$yjset=Yj::getYjSet($_W['uniacid']);
        	include IA_ROOT.'/addons/zh_tcwq/yj.php';
        	if($yjset['type']==1){
        		$money=$_GPC['money']*$yjset['typer']/100;
        	}else{
        		$money=$_GPC['money']*$yjset['sjper']/100;
        	}
        	pdo_update('zhtc_account',array('money +='=>$money),array('cityname'=>$cityname));
        }




//分销二维码
        public function doPageHdPoster(){
        	global $_W, $_GPC;
        	function  getCoade($id){
        		function getaccess_token(){
        			global $_W, $_GPC;
        			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
        			$appid=$res['appid'];
        			$secret=$res['appsecret'];
    			// $appid='wx80fa1d36c435231a';
    			// $secret='9bb4735bd092f092477049bfd7e183f8';
        			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
        			$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL,$url);
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        			$data = curl_exec($ch);
        			curl_close($ch);
        			$data = json_decode($data,true);
        			return $data['access_token'];
        		}
        		function set_msg($id){
        			$access_token = getaccess_token();
        			$data2=array(
        				"scene"=>$id,
        				"page"=>"zh_tcwq/pages/infodetial/infodetial",
        				"width"=>100
        				);
        			$data2 = json_encode($data2);
        			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        			$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL,$url);
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        			curl_setopt($ch, CURLOPT_POST,1);
        			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
        			$data = curl_exec($ch);
        			curl_close($ch);
        			return $data;
        		}
        		$img=set_msg($id);
        		$img=base64_encode($img);
        		return $img;
        	}
        	$base64_image_content="data:image/jpeg;base64,".getCoade($_GPC['id']);
        	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        		$type = $result[2];
        		$new_file = IA_ROOT ."/addons/zh_tcwq/img/";
        		if(!file_exists($new_file))
        		{
    //检查是否有该文件夹，如果没有就创建，并给予最高权限
        			mkdir($new_file, 0777);
        		}
        		$wname="{$_GPC['id']}".".{$type}";
    //$wname="1511.jpeg";
        		$new_file = $new_file.$wname;
        		file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)));
        	}
        	echo "/addons/zh_tcwq/img/".$wname;

        }

//商家收银
        public function doPageAddDmOrder(){
        	global $_W, $_GPC;
        	$data['user_id']=$_GPC['user_id'];
        	$data['store_id']=$_GPC['store_id'];
        	$data['money']=$_GPC['money'];
        	$data['uniacid']=$_W['uniacid'];
        	$data['time']=date("Y-m-d H:i:s");
        	$data['state']=1;
        	$res=pdo_insert('zhtc_dmorder',$data);
        	$order_id=pdo_insertid();
        	if($res){
        		echo $order_id;
        	}else{
        		echo '下单失败';
        	}

        }
 //收银支付
        public function doPagePay4(){
        	global $_W, $_GPC;
        	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
     // include 'wxpay.php';
        	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
        	if($res['pt_name']){
		$res['pt_name']=$res['pt_name'];
	}else{
		$res['pt_name']='同城小程序';
	}
        	$appid=$res['appid'];
    $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
    $mch_id=$res['mchid'];
    $key=$res['wxkey'];
    $out_trade_no = $mch_id. time();
    $root=$_W['siteroot'];
    pdo_update('zhtc_dmorder',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
    $total_fee =$_GPC['money'];
      if(empty($total_fee)) //押金
      {
      	$body = $res['pt_name'];
      	$total_fee = floatval(99*100);
      }else{
      	$body = $res['pt_name'];
      	$total_fee = floatval($total_fee*100);
      }
      $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
      $return=$weixinpay->pay();
      echo json_encode($return);
  }

//发布优惠券
  public function doPageAddCoupon(){
  	global $_W, $_GPC;
  	$store=pdo_get('zhtc_store',array('id'=>$_GPC['store_id']));
  	$data['name']=$_GPC['name'];
  	$data['number']=$_GPC['number'];
  	$data['surplus']=$_GPC['number'];
  	$data['full']=$_GPC['full'];
  	$data['reduce']=$_GPC['reduce'];
  	$data['money']=$_GPC['money'];
  	$data['end_time']=$_GPC['end_time'];
  	$data['details']=$_GPC['details'];
  	$data['store_id']=$_GPC['store_id'];
  	$data['cityname']=$store['cityname'];
  	$data['img']=$_GPC['img'];
  	$data['type_id']=$_GPC['type_id'];
  	$data['time']=date("Y-m-d H:i:s");
  	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  	if($system['is_yhqsh']==1){
  		$data['state']=1;
  	}else{
  		$data['state']=2;
  	}
  	$res=pdo_insert('zhtc_coupons',$data);
  	if($res){
  		echo '1';
  	}else{
  		echo '2';
  	}
  }
//查看店铺优惠券
  public function doPageStoreCoupon(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_coupons',array('store_id'=>$_GPC['store_id'],'is_show'=>1,'state'=>2,'del'=>2));

  	echo json_encode($res);
  }
//查看店铺优惠券
  public function doPageStoreCoupon2(){
  	global $_W, $_GPC;
  	$res=pdo_getall('zhtc_coupons',array('store_id'=>$_GPC['store_id'],'del'=>2));
  	echo json_encode($res);
  }

//上下架
  public function doPageUpdCoupon(){
  	global $_W, $_GPC;
  	$res=pdo_update('zhtc_coupons',array('is_show'=>$_GPC['is_show']),array('id'=>$_GPC['coupon_id']));
  	if($res){
  		echo '1';
  	}else{
  		echo '2';
  	}
  }

//优惠券详情
  public function doPageCouponInfo(){
  	global $_W, $_GPC;
  	$res=pdo_get('zhtc_coupons',array('id'=>$_GPC['coupon_id']));
  	if($res['img']){
  		if(strpos($res['img'],',')){
  			$res['img']= explode(',',$res['img']);
  		}else{
  			$res['img']=array(
  				0=>$res['img']
  				);
  		}
  	}
  	$user=pdo_get('zhtc_usercoupons',array('coupons_id'=>$_GPC['coupon_id'],'user_id'=>$_GPC['user_id'],'pay_type'=>2,'state'=>2));
  	if($user){
  		$user_state=1;
  	}else{
  		$user_state=2;
  	}
  	$res['user_state']=$user_state;
  	echo json_encode($res);
  }
//优惠券详情
  public function doPageCouponInfo2(){
  	global $_W, $_GPC;
  	$res=pdo_get('zhtc_coupons',array('id'=>$_GPC['coupon_id']));
  	if($res['img']){
  		if(strpos($res['img'],',')){
  			$res['img']= explode(',',$res['img']);
  		}else{
  			$res['img']=array(
  				0=>$res['img']
  				);
  		}
  	}
  	$res2=pdo_getall('zhtc_usercoupons',array('coupons_id'=>$_GPC['coupon_id'],'pay_type'=>2));
  	$res3=pdo_getall('zhtc_usercoupons',array('coupons_id'=>$_GPC['coupon_id'],'state'=>1,'pay_type'=>2));
  	$res['lq_num']=count($res2);
  	$res['hx_num']=count($res3);
  	echo json_encode($res);
  }
//领取优惠券
  public function doPageLqCoupon(){
  	global $_W, $_GPC;
  	$data['user_id']=$_GPC['user_id'];
  	$data['coupons_id']=$_GPC['coupons_id'];
  	$data['lq_money']=$_GPC['lq_money'];
  	if($_GPC['lq_money']==0){
  		$_GPC['pay_type']=2;
  	}else{
  		$_GPC['pay_type']=1;
  	}
  	$data['pay_type']=$_GPC['pay_type'];
  	$data['state']=2;
  	$data['time']=date("Y-m-d H:i:s");
  	$res=pdo_insert('zhtc_usercoupons',$data);
  	$order=pdo_insertid();
  	if($res){
  		if($_GPC['lq_money']==0){
    file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=KqMessage&m=zh_tcwq&id=".$order);//模板  
    pdo_update('zhtc_coupons',array('surplus -='=>1),array('id'=>$_GPC['coupons_id']));
}
echo $order;
}else{
	echo '下单失败';
}
}
//我的优惠券
public function doPageMyCoupons(){
	global $_W, $_GPC;
	$sql="select a.* ,b.name as coupons_name,b.end_time,b.full,b.reduce,b.details,c.store_name,c.logo as store_logo,c.id as store_id from " . tablename("zhtc_usercoupons") . " a"  . " left join " . tablename("zhtc_coupons") . " b on b.id=a.coupons_id left join " . tablename("zhtc_store") . " c on b.store_id=c.id  WHERE a.user_id=:user_id and a.pay_type=2 and a.del=2";
	$list=pdo_fetchall($sql,array(':user_id'=>$_GPC['user_id']));
	echo json_encode($list);
}

//优惠券支付
public function doPagePay5(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
     // include 'wxpay.php';
	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($res['pt_name']){
		$res['pt_name']=$res['pt_name'];
	}else{
		$res['pt_name']='同城小程序';
	}
	$appid=$res['appid'];
    $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
    $mch_id=$res['mchid'];
    $key=$res['wxkey'];
    $out_trade_no = $mch_id. time();
    $root=$_W['siteroot'];
    pdo_update('zhtc_usercoupons',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
    $total_fee =$_GPC['money'];
      if(empty($total_fee)) //押金
      {
      	$body = $res['pt_name'];
      	$total_fee = floatval(99*100);
      }else{
      	$body = $res['pt_name'];
      	$total_fee = floatval($total_fee*100);
      }
      $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
      $return=$weixinpay->pay();
      echo json_encode($return);
  }
//升级合伙人
  public function doPagePay6(){
  	global $_W, $_GPC;
  	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
     // include 'wxpay.php';
  	$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  	if($res['pt_name']){
		$res['pt_name']=$res['pt_name'];
	}else{
		$res['pt_name']='同城小程序';
	}
  	$appid=$res['appid'];
    $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
    $mch_id=$res['mchid'];
    $key=$res['wxkey'];
    $out_trade_no = $mch_id. time();
    $root=$_W['siteroot'];
    pdo_update('zhtc_fxlog',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
    $total_fee =$_GPC['money'];
      if(empty($total_fee)) //押金
      {
      	$body = $res['pt_name'];
      	$total_fee = floatval(99*100);
      }else{
      	$body = $res['pt_name'];
      	$total_fee = floatval($total_fee*100);
      }
      $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
      $return=$weixinpay->pay();
      echo json_encode($return);
  }

//核销码
  public function doPageCouponCode(){
  	global $_W, $_GPC;
  	global $_W, $_GPC;
  	function  getCoade($user_id){
  		function getaccess_token(){
  			global $_W, $_GPC;
  			$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
  			$appid= $res['appid'];
  			$secret=$res['appsecret'];
            /*$appid='wx80fa1d36c435231a';
            $secret='41d8f6e7fad1a13cfa2e6de8acf14280';*/
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
            $data = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($data,true);
            return $data['access_token'];
        }

        function set_msg($user_id){
        	$access_token = getaccess_token();
        	$data2=array(
        		"scene"=>$user_id,
        		"width"=>100,
        		'page'=>'zh_tcwq/pages/wdq/hxyhq'
        		);
        	$data2 = json_encode($data2);
        //$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        	$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL,$url);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        	curl_setopt($ch, CURLOPT_POST,1);
        	curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
        	$data = curl_exec($ch);
        	curl_close($ch);
        	return $data;
        }
        $img=set_msg($user_id);

        $img=base64_encode($img);
        return $img;

    }
    echo getCoade($_GPC['id']);
}
  //核销优惠券
public function doPageHxCoupon(){
	global $_W, $_GPC;
	$usercoupons=pdo_get('zhtc_usercoupons',array('id'=>$_GPC['id']));
	$coupons=pdo_get('zhtc_coupons',array('id'=>$usercoupons['coupons_id']));
	if($usercoupons['state']==1){
		echo '不能重复核销';
	}else{
		if($coupons['store_id']==$_GPC['store_id']){
			$res=pdo_update('zhtc_usercoupons',array('state'=>1,'hx_time'=>date("Y-m-d H:i:s")),array('id'=>$_GPC['id']));
			if($res){
				echo  '核销成功';
			}else{
				echo  '核销失败';
			}

		}else{
			echo '无核销权限';
		}
	}
}
//领取列表
public function doPageLqCouponList(){
	global $_W, $_GPC;
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=$_GPC['pagesize'];
	if($_GPC['state']){
		$where=" and a.state=".$_GPC['state'];
	}
	$sql="select a.* ,b.name as user_name,b.img as user_img from " . tablename("zhtc_usercoupons") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  WHERE a.coupons_id=:coupons_id and  a.pay_type=2 ".$where." order by ".$_GPC['orderby'];
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$list=pdo_fetchall($select_sql,array(':coupons_id'=>$_GPC['coupons_id']));
	echo json_encode($list);
}
//商家删除优惠券
public function doPageDelCoupon2(){
	global $_W, $_GPC;
	$res=pdo_update('zhtc_coupons',array('del'=>1),array('id'=>$_GPC['coupon_id']));
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}


//删除优惠券
public function doPageDelCoupon(){
	global $_W, $_GPC;
	$res=pdo_update('zhtc_usercoupons',array('del'=>1),array('id'=>$_GPC['coupon_id']));
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}

//我的优惠券详情
public function doPageMyCouponsInfo(){
	global $_W, $_GPC;
	$res=pdo_get('zhtc_usercoupons',array('id'=>$_GPC['id']));
	echo json_encode($res);
}



//收益统计
public function doPageProfit(){
	global $_W, $_GPC;
	$time = date("Y-m-d");
	$time = "'%$time%'";
	$zttime=date("Y-m-d",strtotime("-1 day"));
	$zttime = "'%$zttime%'";
	$storeid = $_GPC['store_id'];
	function Profit($storeid,$time){
		if($time){
			$where=" FROM_UNIXTIME(complete_time) LIKE " . $time . " and ";
			$where2=" time LIKE " . $time . " and";
			$where3=" a.time LIKE " . $time . " and";
			$where4=" pay_time LIKE " . $time . " and";
		}
		$dd = "select sum(money) as total from " . tablename("zhtc_order") . " WHERE ".$where." store_id=" . $storeid . " and state in (4,7)";
    $dd = pdo_fetch($dd); //今天的订单销售额
    $dd=empty($dd['total'])?'0.00':$dd['total'];
    $dmf = "select sum(money) as total from " . tablename("zhtc_dmorder") . " WHERE ".$where2." store_id=" . $storeid . " and state=2";
    $dmf = pdo_fetch($dmf); //今天的当面付销售额
    $dmf=empty($dmf['total'])?'0.00':$dmf['total'];
    $yhq = "select sum(a.lq_money) as total from " . tablename("zhtc_usercoupons") . " a" . " left join " . tablename("zhtc_coupons") . " b on b.id=a.coupons_id  WHERE ".$where3." b.store_id=" . $storeid . " and a.pay_type=2";
    $yhq = pdo_fetch($yhq); //今天的优惠券销售额
    $yhq=empty($yhq['total'])?'0.00':$yhq['total'];
    $qg = "select sum(money) as total from " . tablename("zhtc_qgorder") ."  WHERE ".$where4."  store_id=" . $storeid . " and state in (2,3)";
    $qg = pdo_fetch($qg); //今天的抢购销售额
    $qg=empty($qg['total'])?'0.00':$qg['total'];
    $pt= "select sum(money) as total from " . tablename("zhtc_store_wallet") ."  WHERE ".$where2." note='拼团订单' and  store_id=" . $storeid;
    $pt = pdo_fetch($pt);
    $pt=empty($pt['total'])?'0.00':$pt['total'];
    $total = $dd + $dmf + $yhq + $qg+$pt; //今天的销售额
    return $total;
}
$data['jt']=Profit($storeid,$time);
$data['zt']=Profit($storeid,$zttime);
$data['all']=Profit($storeid,'');

echo json_encode($data);
}



//升级
public function doPageSjFx(){
	global $_W, $_GPC;
	$data['user_id']=$_GPC['user_id'];
	$data['money']=$_GPC['money'];
	$data['time']=date("Y-m-d H:i:s");
	$data['level']=$_GPC['level'];
	$data['uniacid']=$_W['uniacid'];
	$data['note']='升级合伙人';
	if($_GPC['money']==0){
		$data['state']=2;
	}else{
		$data['state']=1;
	}
	$res=pdo_insert('zhtc_fxlog',$data);
	$id=pdo_insertid();
	if($res){
		if($_GPC['money']==0){
			pdo_update('zhtc_distribution',array('level'=>$_GPC['level']),array('user_id'=>$_GPC['user_id'],'pay_state'=>2,'state'=>2));
		}
		echo $id;
	}else{
		echo '下单失败';
	} 
}

//流量主
public function doPageLlz(){
	global $_W, $_GPC;
	$where=" where uniacid=:uniacid and status=1 and type=".$_GPC['type'];
	if($_GPC['cityname']){
		$where.=" and (cityname LIKE  concat('%', :name,'%') or cityname='')";  
		$data[':name']=$_GPC['cityname'];
	}
	$data[':uniacid']=$_W['uniacid'];
	$sql="select * from ".tablename('zhtc_llz').$where;
	$res=pdo_fetchall($sql,$data);
	echo json_encode($res);

}

//查看我的formid
public function doPageMyFormId(){
	global $_W,$_GPC;
	$time=time()-60*60*24*7;
	$sql="select  count(*) as count  from " . tablename("zhtc_userformid") ." where user_id={$_GPC['user_id']} and UNIX_TIMESTAMP(time)>={$time}";
	$res=pdo_fetch($sql);
	echo  $res['count'];
}




//发货模板消息
public function doPageFhMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$order=pdo_get('zhtc_order',array('id'=>$_GPC['order_id']));
		$user=pdo_get('zhtc_user',array('id'=>$order['user_id']));
		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$order['user_id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["fh_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "您的订单已发货!",
					"color": "#173177"
				},
				"keyword2": {
					"value":"'.$order['order_num'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$order['good_name'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value": "'.$order['money'].'",
					"color": "#173177"
				},
				"keyword5": {
					"value":  "'.$order['kd_name'].'",
					"color": "#173177"
				},
				"keyword6": {
					"value":  "'.$order['kd_num'].'",
					"color": "#173177"
				},
				"keyword7": {
					"value":  "'.$order['user_name'].'",
					"color": "#173177"
				},
				"keyword8": {
					"value":  "'.$order['tel'].'",
					"color": "#173177"
				},
				"keyword9": {
					"value":  "'.$order['address'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);
}



//下单模板消息
public function doPageXdMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$order=pdo_get('zhtc_order',array('id'=>$_GPC['order_id']));
		$store=pdo_get('zhtc_store',array('id'=>$order['store_id']));
		$user=pdo_get('zhtc_user',array('id'=>$store['user_id']));
		if($order['note']){
			$order['note']=$order['note'];
		}else{
			$order['note']='无';
		}
		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$store['user_id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["gm_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "您有新的订单",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$order['order_num'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value":"'.$order['good_name'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value": "'.$order['money'].'",
					"color": "#173177"
				},
				"keyword5": {
					"value": "已支付",
					"color": "#173177"
				},
				"keyword6": {
					"value":  "'.date("Y-m-d H:i:s",$order['pay_time']).'",
					"color": "#173177"
				},
				"keyword7": {
					"value":  "'.$order['user_name'].'",
					"color": "#173177"
				},
				"keyword8": {
					"value":  "'.$order['tel'].'",
					"color": "#173177"
				},
				"keyword9": {
					"value":  "'.$order['address'].'",
					"color": "#173177"
				},
				"keyword10": {
					"value":  "'.$order['note'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);
}




//点赞模板消息
public function doPageDzMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$information=pdo_get('zhtc_information',array('id'=>$_GPC['information_id']));
		$dzuser=pdo_get('zhtc_user',array('id'=>$_GPC['user_id']));
		$user=pdo_get('zhtc_user',array('id'=>$information['user_id']));

		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["dz_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "'.$dzuser['name'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.date('Y-m-d H:i:s').'",
					"color": "#173177"
				},
				"keyword3": {
					"value":"'.$information['details'].'",
					"color": "#173177"
				}
			}
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);
}

//审核通过模板消息
public function doPageTgMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$information=pdo_get('zhtc_information',array('id'=>$_GPC['information_id']));
		$user=pdo_get('zhtc_user',array('id'=>$information['user_id']));

		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["tg_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "帖子已发布成功",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$information['details'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value":"'.$user['name'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value":"'.date("Y-m-d H:i:s",$information['sh_time']).'",
					"color": "#173177"
				},
				"keyword5": {
					"value":"恭喜您,发表的帖子已审核通过",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);
}
//回评通过模板消息
public function doPageHpMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$comments=pdo_get('zhtc_comments',array('id'=>$_GPC['id']));
		$information=pdo_get('zhtc_information',array('id'=>$comments['information_id']));
		$user=pdo_get('zhtc_user',array('id'=>$comments['user_id']));
		$hfuser=pdo_get('zhtc_user',array('id'=>$information['user_id']));
		$hfstore=pdo_get('zhtc_store',array('id'=>$information['store_id']));
		if($hfuser){
			$hf=$hfuser['name'];
		}elseif($hfstore){
			$hf=$hfuser['store_name'];
		}

		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["hp_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "评论已有新回复",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$hf.'",
					"color": "#173177"
				},
				"keyword3": {
					"value":"'.$comments['reply'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value":"'.date("Y-m-d H:i:s",$comments['time']).'",
					"color": "#173177"
				},
				"keyword5": {
					"value":"'.$comments['details'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);
}



//回评通过模板消息
public function doPageHpMessage2(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$comments=pdo_get('zhtc_comments',array('id'=>$_GPC['id']));
		$information=pdo_get('zhtc_information',array('id'=>$comments['information_id']));

         if($comments['bid']>0 and $comments['hf_id']==0){//回复层主
         	$hfid=pdo_get('zhtc_comments',array('id'=>$comments['bid']));
         	$userid=$hfid['user_id'];
         	$lr=$hfid['details'];
         	$sj=date("Y-m-d H:i:s",$hfid['time']);
         }elseif($comments['hf_id']>0){//回复用户
         	$hfid=pdo_get('zhtc_comments',array('id'=>$comments['hf_id']));
         	$userid=$hfid['user_id'];
         	$lr=$hfid['details'];
         	$sj=date("Y-m-d H:i:s",$hfid['time']);
         }

         $user=pdo_get('zhtc_user',array('id'=>$userid));
         $time=time()-60*60*24*7;
         $form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$userid} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
         $formwork ='{
         	"touser": "'.$user["openid"].'",
         	"template_id": "'.$res["hp_tid"].'",
         	"page":"zh_tcwq/pages/infodetial/infodetial?id='.$comments['information_id'].'",
         	"form_id":"'.$form['form_id'].'",
         	"data": {
         		"keyword1": {
         			"value": "评论已有新回复",
         			"color": "#173177"
         		},
         		"keyword2": {
         			"value": "'.$user['name'].'",
         			"color": "#173177"
         		},
         		"keyword3": {
         			"value":"'.$comments['details'].'",
         			"color": "#173177"
         		},
         		"keyword4": {
         			"value":"'.$sj.'",
         			"color": "#173177"
         		},
         		"keyword5": {
         			"value":"'.$lr.'",
         			"color": "#173177"
         		}
         	},
         	"emphasis_keyword": "keyword1.DATA"  
         }';
       // $formwork=$data;
         $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL,$url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
         curl_setopt($ch, CURLOPT_POST,1);
         curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
         $data = curl_exec($ch);
         curl_close($ch);
       //return $data;
         pdo_delete('zhtc_userformid',array('id'=>$form['id']));
     }
     echo set_msg($_W,$_GPC);
 }



//关键词
 public function doPageGetSensitive(){
 	global $_W, $_GPC;
 	$res=pdo_get('zhtc_sensitive',array('uniacid'=>$_W['uniacid']));
 	echo json_encode($res);
 }



//抢购分类
 public function doPageQgType(){
 	global $_W, $_GPC;
 	$res=pdo_getall('zhtc_qgtype',array('uniacid'=>$_W['uniacid'],'state'=>1));
 	echo json_encode($res);
 }
//抢购商品
 public function doPageQgGoods(){
 	global $_W, $_GPC;
 	$time=time();
 	if($_GPC['type_id']){
 		$where=" and a.type_id=".$_GPC['type_id'];
 	}
 	if($_GPC['store_id']){
 		$where=" and a.store_id=".$_GPC['store_id'];
 	}
 	if($_GPC['cityname']){
 		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='' || a.cityname='全国版') ";  
 		$data[':name']=$_GPC['cityname'];
 	}

 	$pageindex = max(1, intval($_GPC['page']));
 	$pagesize=$_GPC['pagesize'];
 	if($_GPC['type']==1){
 		$sql="select a.*,b.store_name,b.address,b.tel from " . tablename("zhtc_qggoods") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  where a.uniacid={$_W['uniacid']} and UNIX_TIMESTAMP(a.start_time)<={$time} and UNIX_TIMESTAMP(a.end_time)>{$time} and a.state=1 and a.state2=1 and a.is_tg=2 ".$where." order by a.num asc";
 	}else{
 		$sql="select a.*,b.store_name,b.address,b.tel from " . tablename("zhtc_qggoods") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  where a.uniacid={$_W['uniacid']} and UNIX_TIMESTAMP(a.start_time)<={$time} and UNIX_TIMESTAMP(a.end_time)>{$time} and a.state=1  and a.is_tg=2 ".$where." order by a.num asc";
 	}

 	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
 	$res=pdo_fetchall($select_sql,$data);
 	echo json_encode($res);
 }

//商品详情
 public function doPageQgGoodInfo(){
 	global $_W, $_GPC;
 	pdo_update('zhtc_qggoods',array('hot +='=>1),array('id'=>$_GPC['id']));
 	$sql="select a.*,b.store_name,b.address,b.tel from " . tablename("zhtc_qggoods") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  where a.id={$_GPC['id']} order by a.num asc";
 	$res=pdo_fetch($sql);
 	$res['start_time']=strtotime($res['start_time']);
 	$res['end_time']=strtotime($res['end_time']);
 	echo json_encode($res);
 }
//查看购买人数
 public function doPageQgPeople(){
 	global $_W, $_GPC;
 	$sql="select a.pay_time,b.name as user_name,b.img as user_img from " . tablename("zhtc_qgorder") . " a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id  where a.good_id={$_GPC['good_id']} and a.state in(2,3) order by a.id DESC";
 	$res=pdo_fetchall($sql);
 	echo json_encode($res);
 }
//抢购下单
 public function doPageQgOrder(){
 	global $_W, $_GPC;
 	$good=pdo_get('zhtc_qggoods',array('id'=>$_GPC['good_id']));
	if($good['surplus']>0){//还有剩余
		$data['order_num']=date('YmdHis',time()).rand(1111,9999);//订单号
		$data['user_id']=$_GPC['user_id'];
		$data['user_name']=$_GPC['user_name'];
		$data['user_tel']=$_GPC['user_tel'];
		$data['store_id']=$_GPC['store_id'];
		$data['money']=$_GPC['money'];
		$data['good_id']=$_GPC['good_id'];
		$data['good_name']=$_GPC['good_name'];
		$data['good_logo']=$_GPC['good_logo'];
		$data['pay_type']=$_GPC['pay_type'];
		$data['uniacid']=$_W['uniacid'];
		$data['state']=1;
		$data['note']=$_GPC['note'];
		$res=pdo_insert('zhtc_qgorder',$data);
		$id=pdo_insertid();
		if($res){
			pdo_update('zhtc_qggoods',array('surplus -='=>1),array('id'=>$_GPC['good_id']));
			echo $id;
		}else{
			echo '下单失败';
		}
	}else{//没有剩余
		echo '下手慢了';
	}
}

//抢购支付
public function doPageQgPay(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
    //  $res=pdo_get('zhtc_pay',array('uniacid'=>$_W['uniacid']));
	$res2=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($res2['pt_name']){
		$res2['pt_name']=$res2['pt_name'];
	}else{
		$res2['pt_name']='同城小程序';
	}
	$appid=$res2['appid'];
        $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
        $mch_id=$res2['mchid'];
        $key=$res2['wxkey'];
        $out_trade_no = $mch_id. time();
        $root=$_W['siteroot'];
        pdo_update('zhtc_qgorder',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
        $total_fee =$_GPC['money'];
            if(empty($total_fee)) //押金
            {
            	$body = $res2['pt_name'];
            	$total_fee = floatval(99*100);
            }else{
            	$body = $res2['pt_name'];
            	$total_fee = floatval($total_fee*100);
            }
            $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
            $return=$weixinpay->pay();
            echo json_encode($return);
        }

//查看我的订单
        public function doPageMyQgOrder(){
        	global $_W, $_GPC;
        	if($_GPC['state']){
        		$where=" and a.state=".$_GPC['state'];
        	}
        	if($_GPC['type']==1){
        		$where=" and a.dq_time<".time();
        	}
        	$pageindex = max(1, intval($_GPC['page']));
        	$pagesize=$_GPC['pagesize'];
        	$sql="select a.*,b.store_name,b.address,b.tel,b.logo as store_logo from " . tablename("zhtc_qgorder") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id  where a.uniacid={$_W['uniacid']} and a.user_id={$_GPC['user_id']} and a.state!=1 and a.del=2 ".$where." order by a.id DESC";
        	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        	$res=pdo_fetchall($select_sql);
        	echo json_encode($res);

        }
//查看是否购买
        public function doPageIsPay(){
        	global $_W, $_GPC;
        	$res=pdo_get('zhtc_qgorder',array('user_id'=>$_GPC['user_id'],'good_id'=>$_GPC['good_id'],'state'=>array(2,3)));
        	if($res){
        		echo '1';
        	}else{
        		echo '2';
        	}
        }
//订单二维码
        public function doPageQgOrderCode(){
        	global $_W, $_GPC;
        	function  getCoade($order_id){
        		function getaccess_token(){
        			global $_W, $_GPC;
        			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
        			$appid=$res['appid'];
        			$secret=$res['appsecret'];
              // print_r($res);die;
        			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
        			$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL,$url);
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        			$data = curl_exec($ch);
        			curl_close($ch);
        			$data = json_decode($data,true);
        			return $data['access_token'];
        		}
        		function set_msg($order_id){
        			$access_token = getaccess_token();
        			$data2=array(
        				"scene"=>$order_id,
        				"page"=>"zh_tcwq/pages/xsqg/qghx",
        				"width"=>400
        				);
        			$data2 = json_encode($data2);
        			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
        			$ch = curl_init();
        			curl_setopt($ch, CURLOPT_URL,$url);
        			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        			curl_setopt($ch, CURLOPT_POST,1);
        			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
        			$data = curl_exec($ch);
        			curl_close($ch);
        			return $data;
        		}
        		$img=set_msg($order_id);
        		$img=base64_encode($img);
        		return $img;
        	}
        	echo getCoade($_GPC['order_id']);
        }



//核销订单
        public function doPageQgHx(){
        	global $_W, $_GPC;
        	$order=pdo_get('zhtc_qgorder',array('id'=>$_GPC['order_id']));
        	$store=pdo_get('zhtc_store',array('id'=>$order['store_id']));
        	if($order['store_id']==$_GPC['store_id'] || $store['user_id']==$_GPC['user_id']){
        		if($order['state']==3){
        			echo '已经核销过了';
        		}elseif($order['dq_time']<time()){
        			echo '商品已失效';
        		}else{
        			$res=pdo_update('zhtc_qgorder',array('state'=>3,'hx_time'=>date('Y-m-d H:i:s')),array('id'=>$_GPC['order_id']));
        			if($res){
        				echo '核销成功';
        			}else{
        				echo '核销失败';
        			}
        		}	
        	}else{
        		echo '暂无核销权限';
        	}
        }



//删除
        public function doPageDelQgOrder(){
        	global $_W, $_GPC;
        	$res=pdo_update('zhtc_qgorder',array('del'=>1),array('id'=>$_GPC['order_id']));
        	if($res){
        		echo '1';
        	}else{
        		echo '2';
        	}
        }


//商家抢购订单
        public function doPageStoreQgOrder(){
        	global $_W, $_GPC;
        	$where=" where a.uniacid={$_W['uniacid']} and a.state!=1 and a.store_id=".$_GPC['store_id'];
        	if($_GPC['state']){
        		$where .=" and a.state=".$_GPC['state'];
        	}
        	if($_GPC['keywords']){
        		$where.=" and (a.user_name LIKE  concat('%', :name,'%') || a.order_num LIKE  concat('%', :name,'%') || b.store_name LIKE  concat('%', :name,'%'))";
        		$data[':name']=$_GPC['keywords']; 
        	}
        	$pageindex = max(1, intval($_GPC['page']));
        	$pagesize=$_GPC['pagesize'];
        	$sql="select a.*,b.store_name,b.address,b.tel,b.logo as store_logo from " . tablename("zhtc_qgorder") . " a"  . " left join " . tablename("zhtc_store") . " b on b.id=a.store_id ".$where." order by a.id DESC";
        	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        	$res=pdo_fetchall($select_sql,$data);
        	echo json_encode($res);
        }

//最新抢购订单
        public function doPageZbOrder(){
        	global $_W, $_GPC;
        	$sql="select  * from " . tablename("zhtc_qgorder") ." where uniacid={$_W['uniacid']} and state in(2,3) order by pay_time DESC LIMIT 0,10";
        	$res=pdo_fetchall($sql);
        	for($i=0;$i<count($res);$i++){
        		$time=time()-strtotime($res[$i]['pay_time']);
        		if(($time/60)>1440){
        			$time=floor($time/60/60/24).'天';
        		}elseif(($time/60)>60){
        			$time=floor($time/60/60).'小时';
        		}else{
        			$time=floor($time/60).'分钟';
        		}
        		$res[$i]['time2']=$time;
        	}
// /  print_r($res);die;
        	echo json_encode($res);
        }

//发布抢购商品
        public function doPageAddQgGood(){
        	global $_W, $_GPC;
        	$store=pdo_get('zhtc_store',array('id'=>$_GPC['store_id']));
        	if($_GPC['state']){
        		$data['state']=$_GPC['state'];
        	}else{
        		$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
        		$data['name']=$_GPC['name'];
        		$data['logo']=$_GPC['logo'];
        		$data['price']=$_GPC['price'];
        		$data['money']=$_GPC['money'];
        		$data['number']=$_GPC['number'];
        		$data['surplus']=$_GPC['number'];
        		$data['start_time']=$_GPC['start_time'];
        		$data['end_time']=$_GPC['end_time'];
        		$data['consumption_time']=$_GPC['consumption_time'];
        		$data['details']=$_GPC['details'];
        		$data['store_id']=$_GPC['store_id'];
        		$data['type_id']=$_GPC['type_id'];
        		$data['img']=$_GPC['img'];
        		$data['num']=$_GPC['num'];
        		$data['cityname']=$store['cityname'];
        		$data['content']=$_GPC['content'];
        		$data['details_img']=$_GPC['details_img'];
        		$data['uniacid']=$_W['uniacid'];
        		$data['time']=date("Y-m-d H:i:s");
        		if($system['xgsh']==2){

        			$data['is_tg']=2;
        		}
        	}
        	if($_GPC['id']){
        		$res=pdo_update('zhtc_qggoods',$data,array('id'=>$_GPC['id']));
        	}else{
        		$res=pdo_insert('zhtc_qggoods',$data);
        	}

        	if($res){
        		echo '1';
        	}else{
        		echo '2';
        	}
        }
//抢购商品列表
        public function doPageStoreGood(){
        	global $_W, $_GPC;
        	$where=" where uniacid={$_W['uniacid']} and store_id={$_GPC['store_id']} and is_tg=".$_GPC['is_tg'];
        	$pageindex = max(1, intval($_GPC['page']));
        	$pagesize=$_GPC['pagesize'];
        	$sql="select * from " . tablename("zhtc_qggoods") . " ".$where." order by id DESC";
        	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        	$res=pdo_fetchall($select_sql,$data);
        	echo json_encode($res);
        }

//删除
        public function doPageDelQgGood(){
        	global $_W, $_GPC;
        	$res=pdo_delete('zhtc_qggoods',array('id'=>$_GPC['id']));
        	if($res){
        		echo  '1';
        	}else{
        		echo '2';
        	}
        }

/////////////////////////////////////////////////////////////////////
//以下拼团
//拼团分类
        public function doPageGroupType(){
        	global $_W, $_GPC;
        	$res=pdo_getall('zhtc_grouptype',array('uniacid'=>$_W['uniacid']),array(),'','num ASC');
        	echo json_encode($res);
        }

//保存拼团商品
        public function doPageSaveGroupGoods(){
        	global $_W, $_GPC;
        	$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),'ggoods_set');
        	$store=pdo_get('zhtc_store',array('id'=>$_GPC['store_id']));
        	if($system['ggoods_set']==1){
        		$data['state']=1;
        	}
        	if($system['ggoods_set']==2){
        		$data['state']=2;
        	}
        	$data['logo']=$_GPC['logo'];
        	$data['name']=$_GPC['name'];
        	$data['img']=$_GPC['img'];
        	$data['type_id']=$_GPC['type_id'];
        	$data['inventory']=$_GPC['inventory'];
        	$data['start_time']=strtotime($_GPC['start_time']);
        	$data['end_time']=strtotime($_GPC['end_time']);
        	$data['xf_time']=strtotime($_GPC['xf_time']);		
        	$data['pt_price']=$_GPC['pt_price'];
        	$data['y_price']=$_GPC['y_price'];
        	$data['dd_price']=$_GPC['dd_price'];
        	$data['ycd_num']=$_GPC['ycd_num'];
        	$data['ysc_num']=$_GPC['ysc_num'];
        	$data['people']=$_GPC['people'];
        	$data['is_shelves']=$_GPC['is_shelves'];
        	$data['store_id']=$_GPC['store_id'];
        	$data['details']=html_entity_decode($_GPC['details']);
        	$data['details_img']=$_GPC['details_img'];
        	$data['num']=$_GPC['num'];
        	$data['introduction']=$_GPC['introduction'];
        	$data['time']=time();
        	$data['cityname']=$store['cityname'];
        	$data['uniacid']=$_W['uniacid'];
        	if($_GPC['id']){
        		$res=pdo_update('zhtc_groupgoods',$data,array('id'=>$_GPC['id']));
        	}else{
        		$res=pdo_insert('zhtc_groupgoods',$data);
        	}	
        	if($res){
        		echo '1';
        	}else{
        		echo '2';
        	}
        }


//拼团商品
        public function doPageGroupGoods(){
        	global $_W, $_GPC;
        	$time=time();
        	$where= " where a.uniacid={$_W['uniacid']} and a.end_time >{$time} and a.is_shelves=1 and a.state=2";
        	if($_GPC['type_id']){
        		$where.=" and a.type_id=".$_GPC['type_id'];
        	}
        	if($_GPC['store_id']){
        		$where.=" and a.store_id=".$_GPC['store_id'];
        	}
        	if($_GPC['display']){
        		$where.=" and a.display=1";
        	}
        	if($_GPC['cityname']){
        		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='' || a.cityname='全国版') ";  
        		$data[':name']=$_GPC['cityname'];
        	}
        	$pageindex = max(1, intval($_GPC['page']));
        	$pagesize=empty($_GPC['pagesize'])?10:$_GPC['pagesize'];
        	$sql="select a.*,b.store_name ,b.address,b.tel,b.logo as store_logo from " . tablename("zhtc_groupgoods"). " a  left join " . tablename("zhtc_store") . " b on b.id=a.store_id" .$where." order by num asc";
        	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
        	$res=pdo_fetchall($select_sql,$data);
        	echo json_encode($res);
	 file_get_contents("".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&a=wxapp&do=UpdateGroup&m=zh_tcwq&store_id=".$_GPC['store_id']);//模板
	}


//商品详情
	public function  doPageGoodsInfo(){
		global $_W, $_GPC;
		$gsql=" select a.*,b.logo as store_logo from".tablename('zhtc_groupgoods')." a left join ".tablename('zhtc_store')." b on a.store_id=b.id where a.id=:id";
		$goods=pdo_fetch($gsql,array(':id'=>$_GPC['goods_id']));
	//拼团情况
		$sql=" select a.id,a.kt_num,a.yg_num,a.user_id,b.name,b.img  from".tablename('zhtc_group')." a left join ".tablename('zhtc_user')." b on a.user_id=b.id  where a.goods_id=:goods_id and a.state=1 and a.uniacid=:uniacid";
		$group=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'],':goods_id'=>$_GPC['goods_id']));
		$goodsInfo['goods']=$goods;
		$goodsInfo['group']=$group;
		echo json_encode($goodsInfo);


	}


//下单
	public function doPageSaveGroupOrder(){
		global $_W, $_GPC;
		$good=pdo_get('zhtc_groupgoods',array('id'=>$_GPC['goods_id']));
		if($good['inventory']>=$_GPC['goods_num']){
			if($_GPC['type']==1){
		$data['order_num']=date('YmdHis',time()).rand(1111,9999);//订单号
		$data['user_id']=$_GPC['user_id'];
		$data['goods_id']=$_GPC['goods_id'];
		$data['group_id']=0;
		$data['logo']=$_GPC['logo'];
		$data['store_id']=$_GPC['store_id'];
		$data['goods_name']=$_GPC['goods_name'];
		$data['goods_type']=$_GPC['goods_type'];
		$data['goods_name']=$_GPC['goods_name'];
		$data['price']=$_GPC['price'];
		$data['goods_num']=$_GPC['goods_num'];
		$data['money']=$_GPC['money'];
		$data['receive_name']=$_GPC['receive_name'];
		$data['receive_tel']=$_GPC['receive_tel'];
		$data['receive_address']=$_GPC['receive_address'];
		$data['note']=$_GPC['note'];
		$data['time']=time();
		$data['xf_time']=$_GPC['xf_time'];
		$data['uniacid']=$_W['uniacid'];
		$data['pay_type']=$_GPC['pay_type'];
		$data['state']=1;
		$res=pdo_insert('zhtc_grouporder',$data);
		$id=pdo_insertid();
		if($res){
			echo $id;
		}else{
			echo '下单失败';
		}

	}
	if($_GPC['type']==2){
		//生产团
		if($_GPC['group_id']==''){
			$data2['store_id']=$_GPC['store_id'];
			$data2['goods_id']=$_GPC['goods_id'];
			$data2['goods_logo']=$_GPC['logo'];
			$data2['goods_name']=$_GPC['goods_name'];
			$data2['kt_num']=$_GPC['kt_num'];		
			$data2['kt_time']=time();
			$data2['dq_time']=$_GPC['dq_time'];
			$data2['state']=0;
			$data2['user_id']=$_GPC['user_id'];
			$data2['uniacid']=$_W['uniacid'];
			$rst=pdo_insert('zhtc_group',$data2);
			$group_id=pdo_insertid();
		}else{
			$group=pdo_get('zhtc_group',array('id'=>$_GPC['group_id']));
		}
		if($_GPC['group_id']==''&&$rst or $_GPC['group_id']&&$group['state']==1){
		$data['order_num']=date('YmdHis',time()).rand(1111,9999);//订单号
		$data['user_id']=$_GPC['user_id'];
		$data['goods_id']=$_GPC['goods_id'];
		$data['group_id']=empty($_GPC['group_id'])?$group_id:$_GPC['group_id'];
		$data['logo']=$_GPC['logo'];
		$data['store_id']=$_GPC['store_id'];
		$data['goods_name']=$_GPC['goods_name'];
		$data['goods_type']=$_GPC['goods_type'];
		$data['goods_name']=$_GPC['goods_name'];
		$data['price']=$_GPC['price'];
		$data['goods_num']=$_GPC['goods_num'];
		$data['money']=$_GPC['money'];
		$data['receive_name']=$_GPC['receive_name'];
		$data['receive_tel']=$_GPC['receive_tel'];
		$data['receive_address']=$_GPC['receive_address'];
		$data['note']=$_GPC['note'];
		$data['time']=time();
		$data['xf_time']=$_GPC['xf_time'];
		$data['uniacid']=$_W['uniacid'];
		$data['pay_type']=$_GPC['pay_type'];
		$data['state']=1;
		$res=pdo_insert('zhtc_grouporder',$data);
		$id=pdo_insertid();
		if($res){
			echo $id;
		}else{
			echo '下单失败';
		}
	}else{//没有剩余
		echo '商品已销售完毕或拼团已失效';
	}
}
}else{
	echo '商品已销售完毕或拼团已失效';
}

}

//拼团支付
public function doPageGroupPay(){
	global $_W, $_GPC;
	include IA_ROOT.'/addons/zh_tcwq/wxpay.php';
	$res2=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
	if($res2['pt_name']){
		$res2['pt_name']=$res2['pt_name'];
	}else{
		$res2['pt_name']='同城小程序';
	}
	$appid=$res2['appid'];
    $openid=$_GPC['openid'];//oQKgL0ZKHwzAY-KhiyEEAsakW5Zg
    $mch_id=$res2['mchid'];
    $key=$res2['wxkey'];
    $out_trade_no = $mch_id. time();
    $root=$_W['siteroot'];
    pdo_update('zhtc_grouporder',array('code'=>$out_trade_no),array('id'=>$_GPC['order_id']));  
    $total_fee =$_GPC['money'];
    if(empty($total_fee)) //押金
    {
    	$body = $res2['pt_name'];
    	$total_fee = floatval(99*100);
    }else{
    	$body = $res2['pt_name'];
    	$total_fee = floatval($total_fee*100);
    }
    $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$root);
    $return=$weixinpay->pay();
    echo json_encode($return);
}


//查看团员信息
public function doPageGetGroupUserInfo(){
	global $_W, $_GPC;
	$sql=" select a.id,b.name,b.img from".tablename('zhtc_grouporder')." a left join ".tablename('zhtc_user')."b on a.user_id=b.id where a.group_id=:group_id and a.state=2";
	$group=pdo_fetchall($sql,array(':group_id'=>$_GPC['group_id']));
	echo json_encode($group);
}

//分销二维码
public function doPageGoodsCode(){
	global $_W, $_GPC;
	function  getCoade($goods_id,$group_id){
		function getaccess_token(){
			global $_W, $_GPC;
			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
			$appid=$res['appid'];
			$secret=$res['appsecret'];
              // print_r($res);die;
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data,true);
			return $data['access_token'];
		}
		function set_msg($goods_id,$group_id){
			$access_token = getaccess_token();
			$data2=array(
				"scene"=>$goods_id.",".$group_id,
				"page"=>"zh_tcwq/pages/collage/info",
				"width"=>400
				);
			$data2 = json_encode($data2);
			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		$img=set_msg($goods_id,$group_id);
		$img=base64_encode($img);
		return $img;
	}
	$base64_image_content="data:image/jpeg;base64,".getCoade($_GPC['goods_id'],$_GPC['group_id']='');
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
		$type = $result[2];
		$new_file = IA_ROOT ."/addons/zh_tcwq/img/";
		if(!file_exists($new_file))
		{
    //检查是否有该文件夹，如果没有就创建，并给予最高权限
			mkdir($new_file, 0777);
		}
		$wname="pt{$_GPC['goods_id']}".".{$type}";
    //$wname="1511.jpeg";
		$new_file = $new_file.$wname;
		file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)));
	}
	echo "/addons/zh_tcwq/img/".$wname;

}

//团详情
public function  doPageGroupInfo(){
	global $_W, $_GPC;
	$sql="select a.*,b.img,b.name from".tablename('zhtc_group')." a left join".tablename('zhtc_user')." b on a.user_id=b.id where a.id=:group_id";
	$group=pdo_fetch($sql,array(':group_id'=>$_GPC['group_id']));
	echo json_encode($group);

}

//我的团购订单
public function doPageMyGroupOrder(){
	global $_W, $_GPC;
	$where=" where a.uniacid={$_W['uniacid']} and a.user_id={$_GPC['user_id']} and a.state!=1 ";
	if($_GPC['state']){
		$where.=" and b.state=".$_GPC['state'];
	}
	if($_GPC['type']){//单独够订单
		$where.=" and a.group_id=0";
	}
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=empty($_GPC['pagesize'])?10:$_GPC['pagesize'];
	$sql="select a.*,b.state as g_state,c.store_name from " .tablename("zhtc_grouporder"). " a left join ".tablename('zhtc_group') ." b on a.group_id=b.id left join ".tablename('zhtc_store') ." c on a.store_id=c.id" .$where." order by a.id DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql);
	echo json_encode($res);
	pdo_update('zhtc_grouporder',array('state'=>5,'cz_time'=>time()),array('xf_time <='=>time(),'uniacid'=>$_W['uniacid'],'state'=>2));

}

//订单详情
public function doPageGroupOrderInfo(){
	global $_W, $_GPC;
	$sql="select a.*,b.state as g_state from " . tablename("zhtc_grouporder")." a left join ".tablename('zhtc_group') ." b on a.group_id=b.id where a.id=:order_id  ";
	$res=pdo_fetchall($sql,array(':order_id'=>$_GPC['order_id']));
	echo json_encode($res);
}


//订单二维码
public function doPageOrderCode(){
	global $_W, $_GPC;
	function  getCoade($order_id){
		function getaccess_token(){
			global $_W, $_GPC;
			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
			$appid=$res['appid'];
			$secret=$res['appsecret'];
              // print_r($res);die;
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			$data = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($data,true);
			return $data['access_token'];
		}
		function set_msg($order_id){
			$access_token = getaccess_token();
			$data2=array(
				"scene"=>$order_id,
				"page"=>"zh_tcwq/pages/collage/yz_code",
				"width"=>400
				);
			$data2 = json_encode($data2);
			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		$img=set_msg($order_id);
		$img=base64_encode($img);
		return $img;
	}
	echo getCoade($_GPC['order_id']);
}


//核销订单
public function doPageGroupVerification(){
	global $_W, $_GPC;
	$order=pdo_get('zhtc_grouporder',array('id'=>$_GPC['order_id']));
	$store=pdo_get('zhtc_store',array('id'=>$order['store_id']));
	if($order['store_id']==$_GPC['store_id'] || $store['user_id']==$_GPC['user_id']){
		if($order['state']==3 or $order['state']==5){
			echo '已经核销过了或订单已失效';
		}else{
			$res=pdo_update('zhtc_grouporder',array('state'=>3,'cz_time'=>time()),array('id'=>$_GPC['order_id']));
			if($res){
				echo '核销成功';
			}else{
				echo '核销失败';
			}
		}	
	}else{
		echo '暂无核销权限';
	}
}

    //拼团失败退款
public function doPageUpdateGroup(){
	global $_W, $_GPC;
	$ids=pdo_getall('zhtc_group',array('dq_time <='=>time(),'state'=>1,'uniacid'=>$_W['uniacid'],'store_id'=>$_GPC['store_id']),'id');	
	if($ids){ 
	$uids = array_map('array_shift', $ids);
	$orders=pdo_getall('zhtc_grouporder',array('group_id'=>$uids,'state'=>2,'pay_type'=>1),'id');
    	//var_dump($orders);die;
	foreach ($orders as $key => $value) {
		include_once IA_ROOT . '/addons/zh_tcwq/cert/WxPay.Api.php';
		load()->model('account');
		load()->func('communication');
		$refund_order =pdo_get('zhtc_grouporder',array('id'=>$value));  
		$WxPayApi = new WxPayApi();
		$input = new WxPayRefund();
		$path_cert = IA_ROOT . "/addons/zh_tcwq/cert/".'apiclient_cert_' .$_W['uniacid'] . '.pem';
		$path_key = IA_ROOT . "/addons/zh_tcwq/cert/".'apiclient_key_' . $_W['uniacid'] . '.pem';
		$account_info = $_W['account'];   
		$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid'])); 
		$appid=$system['appid'];
		$key=$system['wxkey'];
		$mchid=$system['mchid']; 
		$out_trade_no=$refund_order['code'];
		$fee = $refund_order['money'] * 100;
		$input->SetAppid($appid);
		$input->SetMch_id($mchid);
		$input->SetOp_user_id($mchid);
		$input->SetRefund_fee($fee);
		$input->SetTotal_fee($fee);
           // $input->SetTransaction_id($refundid);
		$input->SetOut_refund_no($refund_order['order_num']);
		$input->SetOut_trade_no($out_trade_no);
		$result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $key);

 ////////////////////////////////////
    if ($result['result_code'] == 'SUCCESS' || $tkres) {//退款成功
        //更改订单操作
    	pdo_update('zhtc_grouporder',array('state'=>4),array('id'=>$value));
    }

}

$group=pdo_update('zhtc_group',array('state'=>3),array('id'=>$uids));
}

}

public function GETSiteroot(){
	global $_W, $_GPC;
	echo $_W['siteroot'];
}

///////////////////////////////
//手机后台
//商家商品列表
public function doPageGroupGoodsList(){
	global $_W, $_GPC;
	$where=" where uniacid={$_W['uniacid']} and store_id={$_GPC['store_id']} and state=".$_GPC['state'];
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=$_GPC['pagesize'];
	$sql="select * from " . tablename("zhtc_groupgoods") . " ".$where." order by id DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql,$data);
	echo json_encode($res);
}

//下架商品
public function doPageOperateGood(){
	global $_W, $_GPC;
	$res=pdo_update('zhtc_groupgoods',array('is_shelves'=>$_GPC['is_shelves']),array('id'=>$_GPC['goods_id']));
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}


//删除
public function doPageDelGroupGood(){
	global $_W, $_GPC;
	$res=pdo_delete('zhtc_groupgoods',array('id'=>$_GPC['goods_id']));
	if($res){
		echo  '1';
	}else{
		echo '2';
	}
}

//商品详情
public function doPageGetGoodDetails(){
	global $_W, $_GPC;
	$res=pdo_get('zhtc_groupgoods',array('id'=>$_GPC['goods_id']));
	echo json_encode($res);
}


public function doPageInsertStoreWallet(){
	global $_W, $_GPC;
	if($_GPC['group_id']>0){
		$res=pdo_get('zhtc_grouporder', array('group_id'=>$_GPC['group_id']), array('sum(money) as total','store_id'));
	}else{
		$res=pdo_get('zhtc_grouporder', array('id'=>$_GPC['order_id']), array('sum(money) as total','store_id'));
	}

	pdo_update('zhtc_store',array('wallet +='=>$res['total']),array('id'=>$res['store_id']));
	$data['store_id']=$res['store_id'];
	$data['money']=$res['total'];
	$data['note']='拼团订单';
	$data['type']=1;
	$data['time']=date("Y-m-d H:i:s");
	pdo_insert('zhtc_store_wallet',$data);
}




//商家订单
public function doPagePtStoreOrderList(){
	global $_W, $_GPC;
	$data[':uniacid']=$_W['uniacid'];
	$where=" where uniacid=:uniacid and store_id={$_GPC['store_id']}";
	if($_GPC['state']){
		$where.=" and state=".$_GPC['state'];
	}
	if($_GPC['keywords']){
		$where.=" and (goods_name LIKE  concat('%', :keywords,'%') || order_num LIKE  concat('%', :keywords,'%') || receive_name LIKE  concat('%', :keywords,'%'))";
		$data[':keywords']=$_GPC['keywords'];
	}
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=empty($_GPC['pagesize'])?10:$_GPC['pagesize'];
	$sql="select * from " .tablename("zhtc_grouporder") .$where." order by id DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql,$data);
	echo json_encode($res);
}

//商家团
public function doPagePtStoreGroupList(){
	global $_W, $_GPC;
	$where=" where uniacid=:uniacid and store_id={$_GPC['store_id']} and state !=0";
	$data[':uniacid']=$_W['uniacid'];
	if($_GPC['state']){
		$where.=" and state=".$_GPC['state'];
	}
	if($_GPC['keywords']){
		$where.=" and (goods_name LIKE  concat('%',:keywords,'%'))";
		$data[':keywords']=$_GPC['keywords'];

	}
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=empty($_GPC['pagesize'])?10:$_GPC['pagesize'];
	$sql="select * from " .tablename("zhtc_group") .$where." order by id DESC";
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql,$data);
	echo json_encode($res);
}

//团订单

public function doPagePtGroupOrderInfo(){
	global $_W, $_GPC;
	$sql=" select a.*,b.name as nick_name,b.img,c.store_name from".tablename('zhtc_group')." a left join ".tablename('zhtc_user')." b on a.user_id=b.id left join ".tablename('zhtc_store')." c on a.store_id=c.id where a.id=:id";
	$group=pdo_fetch($sql,array(':id'=>$_GPC['group_id']));
	$sql2=" select a.*,b.name as nick_name from".tablename('zhtc_grouporder')." a left join ".tablename('zhtc_user')." b on a.user_id=b.id where a.group_id=:id";
	$order=pdo_fetchall($sql2,array(':id'=>$_GPC['group_id']));
	$info['group']=$group;
	$info['order']=$order;
	echo json_encode($info);
}


//优惠券分类
public function doPageCouponType(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_coupontype',array('uniacid'=>$_W['uniacid'],'state'=>1));
	echo json_encode($res);
}
//优惠券列表
public function doPageCouponList(){
	global $_W, $_GPC;
	$where= " where b.uniacid={$_W['uniacid']} and a.is_show=1 and a.is_pt=1 and a.state=2 and a.del=2";
	if($_GPC['type_id']){
		$where.=" and a.type_id=".$_GPC['type_id']." and a.is_pt=1 ";
	}
	if($_GPC['store_id']){
		$where.=" and a.store_id=".$_GPC['store_id'];
	}
	if($_GPC['cityname']){
		$where.=" and (a.cityname LIKE  concat('%', :name,'%') || a.cityname='' || a.cityname='全国版') ";  
		$data[':name']=$_GPC['cityname'];
	}
	$pageindex = max(1, intval($_GPC['page']));
	$pagesize=empty($_GPC['pagesize'])?10:$_GPC['pagesize'];
	$sql="select a.*,b.store_name ,b.address,b.tel,b.logo as store_logo from " . tablename("zhtc_coupons"). " a  left join " . tablename("zhtc_store") . " b on b.id=a.store_id" .$where;
	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
	$res=pdo_fetchall($select_sql,$data);
	echo json_encode($res);
}
//最新领券
public function doPageZbCoupons(){
	global $_W, $_GPC;
	$sql="select  a.*,b.name as coupon_name,c.name as user_name from " . tablename("zhtc_usercoupons") ." a  left join " . tablename("zhtc_coupons") . " b on b.id=a.coupons_id left join " . tablename("zhtc_user") . " c on c.id=a.user_id  where c.uniacid={$_W['uniacid']} and a.pay_type=2 order by a.id DESC LIMIT 0,10";
	$res=pdo_fetchall($sql);
	for($i=0;$i<count($res);$i++){
		$time=time()-strtotime($res[$i]['time']);
		if(($time/60)>1440){
			$time=floor($time/60/60/24).'天';
		}elseif(($time/60)>60){
			$time=floor($time/60/60).'小时';
		}else{
			$time=floor($time/60).'分钟';
		}
		$res[$i]['time2']=$time;
	}
	echo json_encode($res);
}




//添加分类
public function doPageAddType(){
	global $_W, $_GPC;
	$data['type_name']=$_GPC['type_name'];
	$data['store_id']=$_GPC['store_id'];
	$data['order_by']=$_GPC['order_by'];
	$data['uniacid']=$_W['uniacid'];
	$res=pdo_insert('zhtc_goodtype',$data);
	if($res){
		echo '1';
	}else{
		echo '2';
	}
}
//分类列表
public function doPageGoodType(){
	global $_W, $_GPC;
	$res=pdo_getall('zhtc_goodtype',array('store_id'=>$_GPC['store_id'],'uniacid'=>$_W['uniacid']));
	echo json_encode($res);
}

//商品列表
public function doPageGoodsList(){
	global $_W, $_GPC;
	$sql=" select * from".tablename('zhtc_goodtype')." where  uniacid={$_W['uniacid']} and store_id={$_GPC['store_id']} and is_open=1 and id in(select type_id from".tablename('zhtc_goods')." where uniacid={$_W['uniacid']} and is_show=1 and type !={$_GPC['type']} and store_id={$_GPC['store_id']}) order by order_by asc";
	$type=pdo_fetchall($sql);
	$list = pdo_getall('zhtc_goods', array('uniacid' => $_W['uniacid'], 'is_show' => 1,'state' => 2, 'store_id' => $_GPC['store_id']), array(), '', 'num ASC');
	$data2 = array();
	for ($i = 0;$i < count($type);$i++) {
		$data = array();
		for ($k = 0;$k < count($list);$k++) {
			if ($type[$i]['id'] == $list[$k]['type_id']) {
				$data[] = $list[$k];
			}
		}
		$data2[] = array('id' => $type[$i]['id'], 'type_name' => $type[$i]['type_name'], 'good' => $data);
	}
	echo json_encode($data2);
}


//抢购模板消息
public function doPageQgMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$qgorder=pdo_get('zhtc_qgorder',array('id'=>$_GPC['order_id']));
		$store=pdo_get('zhtc_store',array('id'=>$qgorder['store_id']));
		$user=pdo_get('zhtc_user',array('id'=>$qgorder['user_id']));

		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["qg_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "'.$qgorder['good_name'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$qgorder['money'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value":"'.$qgorder['pay_time'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value":"1个",
					"color": "#173177"
				},
				"keyword5": {
					"value":"'.$qgorder['order_num'].'",
					"color": "#173177"
				},
				"keyword6": {
					"value":"'.date("Y-m-d H:i:s",$qgorder['dq_time']).'",
					"color": "#173177"
				},
				"keyword7": {
					"value":"'.$store['store_name'].'",
					"color": "#173177"
				}
			}
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);

}



//活动参与成功模板消息
public function doPageHdMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$joinlist=pdo_get('zhtc_joinlist',array('id'=>$_GPC['id']));
		$activity=pdo_get('zhtc_activity',array('id'=>$joinlist['act_id']));
		$acttype=pdo_get('zhtc_acttype',array('id'=>$activity['type_id']));
		$user=pdo_get('zhtc_user',array('id'=>$joinlist['user_id']));

		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["hd_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$joinlist['form_id'].'",
			"data": {
				"keyword1": {
					"value":"报名成功!",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$activity['title'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$acttype['type_name'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value":"'.$activity['address'].'",
					"color": "#173177"
				},
				"keyword5": {
					"value":"'.$activity['start_time'].'---'.$activity['end_time'].'",
					"color": "#173177"
				},
				"keyword6": {
					"value":"'.$activity['tel'].'",
					"color": "#173177"
				},
				"keyword7": {
					"value":"'.$joinlist['money'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);

}




//卡券领取成功模板消息
public function doPageKqMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$usercoupons=pdo_get('zhtc_usercoupons',array('id'=>$_GPC['id']));
		$coupons=pdo_get('zhtc_coupons',array('id'=>$usercoupons['coupons_id']));
		$user=pdo_get('zhtc_user',array('id'=>$usercoupons['user_id']));
		$store=pdo_get('zhtc_store',array('id'=>$coupons['store_id']));

		$time=time()-60*60*24*7;
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["kq_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value":"'.$coupons['name'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$user['name'].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "1张",
					"color": "#173177"
				},
				"keyword4": {
					"value":"满'.$coupons['full'].'减'.$coupons['reduce'].'",
					"color": "#173177"
				},
				"keyword5": {
					"value":"'.$usercoupons['time'].'",
					"color": "#173177"
				},
				"keyword6": {
					"value":"请在'.$coupons['end_time'].'前使用",
					"color": "#173177"
				},
				"keyword7": {
					"value":"点击查看更多优惠信息！",
					"color": "#173177"
				},
				"keyword8": {
					"value":"'.$store['store_name'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}
	echo set_msg($_W,$_GPC);

}


//拼团成功消息
public function doPagePtMessage(){
	global $_W, $_GPC;
	function getaccess_token($_W){
		$res=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
		$appid=$res['appid'];
		$secret=$res['appsecret'];
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data,true);
		return $data['access_token'];
	}
      //设置与发送模板信息
	function set_msg($_W, $_GPC,$user_id,$group_id,$order_num,$goods_name,$goods_num,$price,$pay_time){
		$access_token = getaccess_token($_W);
		$res=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
		$group=pdo_get('zhtc_group',array('id'=>$group_id));
		$store=pdo_get('zhtc_store',array('id'=>$group['store_id']));
		$user=pdo_get('zhtc_user',array('id'=>$user_id));
		$time=time()-60*60*24*7;
		$pay_time=date('Y-m-d H:i:s',$pay_time);
		$form=pdo_fetch("select * from ".tablename('zhtc_userformid')." where user_id={$user['id']} and UNIX_TIMESTAMP(time)>={$time} order by id asc");
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["pt_tid"].'",
			"page":"zh_tcwq/pages/index/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value":"'.$order_num.'",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$goods_name.'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$group['kt_num'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value":"'.$goods_num.'",
					"color": "#173177"
				},
				"keyword5": {
					"value":"'.$price.'",
					"color": "#173177"
				},
				"keyword6": {
					"value":"'.$price.'",
					"color": "#173177"
				},
				"keyword7": {
					"value":"'.$pay_time.'",
					"color": "#173177"
				},
				"keyword8": {
					"value":"'.$store['store_name'].'",
					"color": "#173177"
				},
				"keyword9": {
					"value":"'.$store['address'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"
		}';
       // $formwork=$data;
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
       //return $data;
		pdo_delete('zhtc_userformid',array('id'=>$form['id']));
	}	
	$order=pdo_getall('zhtc_grouporder',array('group_id'=>$_GPC['group_id']));
	foreach ($order as $key => $value) {
		echo set_msg($_W,$_GPC,$value['user_id'],$_GPC['group_id'],$value['order_num'],$value['goods_name'],$value['goods_num'],$value['price'],$value['pay_time']);
	}

}



//抢购商品二维码
    public function doPageQgCode(){
    	global $_W, $_GPC;
    	function  getCoade($order_id){
    		function getaccess_token(){
    			global $_W, $_GPC;
    			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
    			$appid=$res['appid'];
    			$secret=$res['appsecret'];
              // print_r($res);die;
    			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
    			$ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL,$url);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    			$data = curl_exec($ch);
    			curl_close($ch);
    			$data = json_decode($data,true);
    			return $data['access_token'];
    		}
    		function set_msg($order_id){
    			$access_token = getaccess_token();
    			$data2=array(
    				"scene"=>$order_id,
      				"page"=>"zh_tcwq/pages/xsqg/xsqgxq",
    				"width"=>400
    				);
    			$data2 = json_encode($data2);
    			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
    			$ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL,$url);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    			curl_setopt($ch, CURLOPT_POST,1);
    			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
    			$data = curl_exec($ch);
    			curl_close($ch);
    			return $data;
    		}
    		$img=set_msg($order_id);
    		$img=base64_encode($img);
    		return $img;
    	}
    	$base64_image_content="data:image/jpeg;base64,".getCoade($_GPC['id']);
        	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        		$type = $result[2];
        		$new_file = IA_ROOT ."/addons/zh_tcwq/img/";
        		if(!file_exists($new_file))
        		{
    //检查是否有该文件夹，如果没有就创建，并给予最高权限
        			mkdir($new_file, 0777);
        		}
        		$wname="qg{$_GPC['id']}".".{$type}";
    //$wname="1511.jpeg";
        		$new_file = $new_file.$wname;
        		file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)));
        	}
        	echo $_W['siteroot']."addons/zh_tcwq/img/".$wname;
    }


//抢购商品二维码
    public function doPageHdCode(){
    	global $_W, $_GPC;
    	function  getCoade($order_id){
    		function getaccess_token(){
    			global $_W, $_GPC;
    			$res=pdo_get('zhtc_system',array('uniacid' => $_W['uniacid']));
    			$appid=$res['appid'];
    			$secret=$res['appsecret'];
              // print_r($res);die;
    			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
    			$ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL,$url);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    			$data = curl_exec($ch);
    			curl_close($ch);
    			$data = json_decode($data,true);
    			return $data['access_token'];
    		}
    		function set_msg($order_id){
    			$access_token = getaccess_token();
    			$data2=array(
    				"scene"=>$order_id,
      				"page"=>"zh_tcwq/pages/hdzx/hdzxinfo",
    				"width"=>400
    				);
    			$data2 = json_encode($data2);
    			$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token."";
    			$ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL,$url);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    			curl_setopt($ch, CURLOPT_POST,1);
    			curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
    			$data = curl_exec($ch);
    			curl_close($ch);
    			return $data;
    		}
    		$img=set_msg($order_id);
    		$img=base64_encode($img);
    		return $img;
    	}
    	$base64_image_content="data:image/jpeg;base64,".getCoade($_GPC['id']);
        	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        		$type = $result[2];
        		$new_file = IA_ROOT ."/addons/zh_tcwq/img/";
        		if(!file_exists($new_file))
        		{
    //检查是否有该文件夹，如果没有就创建，并给予最高权限
        			mkdir($new_file, 0777);
        		}
        		$wname="hd{$_GPC['id']}".".{$type}";
    //$wname="1511.jpeg";
        		$new_file = $new_file.$wname;
        		file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)));
        	}
        	echo $_W['siteroot']."addons/zh_tcwq/img/".$wname;
    }

  //删除114
    public  function  doPageDelYellowStore(){
    	global $_W, $_GPC;
    	$res=pdo_update('zhtc_yellowstore',array('is_delete'=>1),array('id'=>$_GPC['y_id']));
    	if($res){
    		echo 1;
    	}else{
    		echo 2;
    	}
    }

      //删除拼车
    public  function  doPageDelCar(){
    	global $_W, $_GPC;
    	$res=pdo_update('zhtc_car',array('is_delete'=>1),array('id'=>$_GPC['car_id']));
    	if($res){
    		echo 1;
    	}else{
    		echo 2;
    	}
    }


    //查找电话金额
    public function doPageGetTelMoney(){
    	global $_W, $_GPC;
    	if($_GPC['type_id']){
    		$res=pdo_get('zhtc_type',array('id'=>$_GPC['type_id']),'tel_money');
    	}
    	if($_GPC['type_id']){
    		$res=pdo_get('zhtc_type2',array('id'=>$_GPC['type2_id']),'tel_money');
    	}
    	echo json_encode($res);
    }

   //电话支付记录
    public function doPageSaveTelPayLog(){
    	global $_W, $_GPC;
    	$data['tz_id']=$_GPC['tz_id'];
    	$data['user_id']=$_GPC['user_id'];
    	$data['money']=$_GPC['tel_money'];
    	$data['time']=date('Y-m-d H:i:s');
    	$data['uniacid']=$_W['uniacid'];
    	$res=pdo_insert('zhtc_telpaylog',$data);

    }

    //板块导航
    public function doPageGetPlate(){
    	global $_GPC, $_W;
    	$res=pdo_getall('zhtc_plate',array('uniacid'=>$_W['uniacid']),array(),'','sort asc');
    	echo json_encode($res);
    }
   


  
        //商家列表
        public function doPageStoreGoodsList(){
    	global $_W, $_GPC;
    	$time=time();
    	$where=" where uniacid=:uniacid and time_over !=1 and state=2";
    	$data[':uniacid']=$_W['uniacid'];
    	$lat=empty($_GPC['lat'])?'30.592760':$_GPC['lat'];
    	$lng=empty($_GPC['lng'])?'114.305250':$_GPC['lng'];
    	$pageindex = max(1, intval($_GPC['page']));
    	$pagesize=empty($_GPC['pagesize'])?10:$_GPC['pagesize'];
    	if($_GPC['cityname']){
    		$where.=" and (cityname LIKE  concat('%', :name,'%') || cityname='')";  
    		$data[':name']=$_GPC['cityname'];
    	}
    	$sql= "select id,store_name,ROUND(6378.138*2*ASIN(SQRT(POW(SIN(($lat*PI()/180-SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)/2),2)+COS($lat*PI()/180)*COS(SUBSTRING_INDEX(coordinates, ',', 1)*PI()/180)*POW(SIN(($lng*PI()/180-SUBSTRING_INDEX(coordinates, ',', -1)*PI()/180)/2),2)))*1000) AS juli from".tablename('zhtc_store').$where." order by is_top asc,juli asc";       
    	$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
    	$res = pdo_fetchall($select_sql,$data);
      //var_dump($res);die;
    	foreach ($res as $key => $value) {
   			$store_id=$value['id'];
   			$sql2="select * from " . tablename("zhtc_qggoods") . "  where  UNIX_TIMESTAMP(start_time)<={$time} and UNIX_TIMESTAMP(end_time)>{$time} and state=1 and state2=1 and is_tg=2 and store_id={$store_id} order by num asc";
   			$res2 = pdo_fetch($sql2);
   			if($res2){
   				$res[$key]['goodsInfo']=$res2;
   			}else{
   				unset($res[$key]);

   			}	

    	}
      $res=array_values($res);

    	echo json_encode($res);	
    }

    //导航
    public function doPageGetBottom()
    {
        global $_W, $_GPC;
        $res = pdo_getall('zhtc_bottom', array('uniacid' => $_W['uniacid'], 'state' => 1), array(), '', 'num asc');
        echo json_encode($res);
    }

}