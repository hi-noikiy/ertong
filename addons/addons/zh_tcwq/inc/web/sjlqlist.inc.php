<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
if(!$_GPC['type']){
    $_GPC['type']='all';
}
$where=" where a.coupons_id=:coupons_id and a.pay_type=2";
$data[':coupons_id']=$_GPC['id']; 
if($_GPC['type']=='ok'){
    $where .= " and a.state=1";
}
if($_GPC['type']=='no'){
    $where .= " and a.state=2";
}
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="SELECT a.*,b.name as user_name,b.img as user_img FROM ".tablename('zhtc_usercoupons'). " a"  . " left join " . tablename("zhtc_user") . " b on a.user_id=b.id  ".$where." ORDER BY a.id DESC";
$total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('zhtc_usercoupons'). " a"  . " left join " . tablename("zhtc_user") . " b on a.user_id=b.id ".$where." ORDER BY a.id DESC",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;

$list=pdo_fetchall($select_sql,$data);
include $this->template('web/sjlqlist');