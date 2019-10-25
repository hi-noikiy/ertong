<?php
global $_GPC, $_W;
if($_GPC['account_id']){
$role=pdo_get('zhtc_account',array('uid'=>$_GPC['account_id'],'weid'=>$_W['uniacid']));
setcookie('storeid',$role['storeid']);
$storeid=$role['storeid'];
}else{
	$storeid=$_COOKIE["storeid"];
}
$cur_store = $this->getStoreById($storeid);
$store=pdo_get('zhtc_store',array('id'=>$storeid,'uniacid'=>$_W['uniacid']),array('wallet','dq_time','views'));
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
  $time = date("Y-m-d");
  $time = "'%$time%'";
  $zttime=date("Y-m-d",strtotime("-1 day"));
  $zttime = "'%$zttime%'";
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

//今日订单
$sql="select count(id) as total from " . tablename("zhtc_order") . " WHERE store_id=:store_id and del2=2 and  FROM_UNIXTIME(time) LIKE " . $time ;
$order=pdo_fetch($sql,array(':store_id'=>$storeid));

//订单统计
$sql2="select count(case when state=2 then 1 end) as dfh,count( case when state=1 then 1 end) as dfk, count( case when state=4 then 1 end) as ywc, count( case when state=5 then 1 end) as dtk from  ".tablename('zhtc_order')." where uniacid=:uniacid  and store_id=:store_id and del2=2";

$count=pdo_fetch($sql2,array('uniacid'=>$_W['uniacid'],':store_id'=>$storeid));

include $this->template('web/sjstatistics');