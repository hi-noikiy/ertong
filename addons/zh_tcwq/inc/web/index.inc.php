<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$system=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
$time=time()-$system['sh_time']*24*60*60;
if($system['sh_time']>0){
	$res=pdo_getall('zhtc_order',array('state'=>3,'fh_time <='=>$time,'uniacid'=>$_W['uniacid']));
	for($i=0;$i<count($res);$i++){
		pdo_update('zhtc_order',array('state'=>4,'complete_time'=>time()),array('id'=>$res[$i]['id']));
		pdo_update('zhtc_store',array('wallet +='=>$res[$i]['money']),array('id'=>$res[$i]['store_id']));
         $data['store_id']=$res[$i]['store_id'];
         $data['money']=$res[$i]['money'];
         $data['note']='商品订单';
         $data['type']=1;
         $data['time']=date("Y-m-d H:i:s");
         pdo_insert('zhtc_store_wallet',$data);

        if($system['good_jf']>0){
            pdo_update('zhtc_user',array('total_score +='=>$system['good_jf']),array('id'=>$res[$i]['user_id']));
            $data2['score']=$system['good_jf'];
            $data2['user_id']=$res[$i]['user_id'];
            $data2['tid']=$res[$i]['id'];
            $data2['note']='商品订单';
            $data2['type']=1;
            $data2['cerated_time']=date('Y-m-d H:i:s');
            $data2['uniacid']=$_W['uniacid'];//小程序id
            pdo_insert('zhtc_integral',$data2);//添加积分明细 
          }
	}
}

$fx=pdo_getall('zhtc_distribution',array('pay_state'=>2,'state'=>2,'is_log'=>1));
for($j=0;$j<count($fx);$j++){
	$data2['user_id']=$fx[$j]['user_id'];
	$data2['money']=$fx[$j]['money'];
	$data2['time']=date("Y-m-d H:i:s",$fx[$j]['time']);
	$data2['level']=$fx[$j]['level'];
	$data2['state']=2;
	$data2['note']='申请合伙人';
	pdo_insert('zhtc_fxlog',$data2);
	pdo_update('zhtc_distribution',array('is_log'=>2),array('id'=>$fx[$j]['id']));
}
//会员信息
$time=date("Y-m-d");
$time2=date("Y-m-d",strtotime("-1 day"));
$time3=date("Y-m");
//会员总数
$totalhy=pdo_get('zhtc_user', array('uniacid'=>$_W['uniacid']), array('count(id) as count'));
//今日新增会员
$sql=" select a.* from (select  id,FROM_UNIXTIME(time) as time  from".tablename('zhtc_user')." where uniacid={$_W['uniacid']}) a where time like '%{$time}%' ";
$jir=count(pdo_fetchall($sql));
//昨日新增
$sql2=" select a.* from (select  id,FROM_UNIXTIME(time) as time  from".tablename('zhtc_user')." where uniacid={$_W['uniacid']}) a where time like '%{$time2}%' ";
$zuor=count(pdo_fetchall($sql2));
//本月新增
$sql3=" select a.* from (select  id,FROM_UNIXTIME(time) as time  from".tablename('zhtc_user')." where uniacid={$_W['uniacid']}) a where time like '%{$time3}%' ";
$beny=count(pdo_fetchall($sql3));
//商品一览
//商品总数
$goodstotal=pdo_get('zhtc_goods', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));
//新增商品
$sql4=" select a.* from (select  id,FROM_UNIXTIME(time) as time  from".tablename('zhtc_goods')." where uniacid={$_W['uniacid']}) a where time like '%{$time}%' ";
$jrgoods=count(pdo_fetchall($sql4));
//总共订单
$totalorder=pdo_get('zhtc_order', array('uniacid'=>$_W['uniacid']), array('count(id) as count'));
//代发货订单
$dfhorder=pdo_get('zhtc_order', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));

//帖子数量
$tztotal=pdo_get('zhtc_information', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));
//商户数量
$shtotal=pdo_get('zhtc_store', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));
//拼车数量
$pctotal=pdo_get('zhtc_car', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));
//黄页数量
$hytotal=pdo_get('zhtc_yellowstore', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));
//资讯数量
$zxtotal=pdo_get('zhtc_zx', array('uniacid'=>$_W['uniacid'],'state'=>2), array('count(id) as count'));

//数据概况
//今日新增帖子
$sql5=" select a.* from (select  id,hb_money,hb_num,hb_type,hb_random,FROM_UNIXTIME(sh_time) as time  from".tablename('zhtc_information')." where uniacid={$_W['uniacid']} and state=2) a where time like '%{$time}%' ";
$tzinfo=pdo_fetchall($sql5);
$jrtz=count($tzinfo);
//今日新增商户
$sql6=" select a.* from (select  id,FROM_UNIXTIME(sh_time) as time  from".tablename('zhtc_store')." where uniacid={$_W['uniacid']} and state=2) a where time like '%{$time}%' ";
$jrsh=count(pdo_fetchall($sql6));
//获取今日红包金额
$jrhb=0;
if($tzinfo){
foreach($tzinfo as $v){
	if($v['hb_random']==1){
		$jrhb+=$v['hb_money'];
	}
	if($v['hb_random']==2){
		$jrhb+=$v['hb_money']*$v['hb_num'];
	}
}
}
$jrtmoney=0;
//今日销售金额
//今日订单销售金额
$sql7=" select  sum(a.money) as ordermoney from (select  id,money,FROM_UNIXTIME(time) as time  from".tablename('zhtc_order')." where uniacid={$_W['uniacid']} and state in (2,3,4,5,7)) a  where time like '%{$time}%' ";
$ordermoney=pdo_fetch($sql7);
//商家入驻的钱
$sql8=" select sum(money) as storemoney from".tablename('zhtc_storepaylog')." where uniacid={$_W['uniacid']} and  time like '%{$time}%' ";
$storemoney=pdo_fetch($sql8);  
//帖子入驻加置顶
$sql9=" select sum(money) as tzmoney from".tablename('zhtc_tzpaylog')." where uniacid={$_W['uniacid']} and  time like '%{$time}%' ";
$tzmoney=pdo_fetch($sql9); 
//拼车发布的钱
$sql10=" select sum(money) as pcmoney from".tablename('zhtc_carpaylog')." where uniacid={$_W['uniacid']} and  time like '%{$time}%' ";
$pcmoney=pdo_fetch($sql10); 
//114入驻的钱
$sql11=" select sum(money) as hymoney from".tablename('zhtc_yellowpaylog')." where uniacid={$_W['uniacid']} and  time like '%{$time}%' ";
$hymoney=pdo_fetch($sql11);
//合伙人的钱
// $zttime=strtotime(date("Y-m-d",strtotime("+1 day")));
// $jttime=strtotime(date("Y-m-d"));
// $sql12=" select sum(money) as hhrmoney from".tablename('zhtc_distribution')." where uniacid={$_W['uniacid']} and  time < ".$zttime." and pay_state=2 and time >= ".$jttime;
// $hhrmoney=pdo_fetch($sql12);  
$where=" and  a.time like '%{$time}%'";
$zttime=strtotime(date("Y-m-d",strtotime("+1 day")));
$jttime=strtotime(date("Y-m-d"));
$sql12=" select sum(a.money) as hhrmoney from".tablename('zhtc_fxlog')." a"  . " left join " . tablename("zhtc_user") . " b on b.id=a.user_id where b.uniacid={$_W['uniacid']} and a.state=2".$where;


$hhrmoney=pdo_fetch($sql12);  

//今日总金额
$jrtmoney=$ordermoney['ordermoney']+$storemoney['storemoney']+$tzmoney['tzmoney']+$pcmoney['pcmoney']+$hymoney['hymoney']+$hhrmoney['hhrmoney'];
include $this->template('web/index');