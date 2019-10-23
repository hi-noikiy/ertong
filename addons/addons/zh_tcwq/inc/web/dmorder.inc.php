<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$where=' WHERE  a.uniacid=:uniacid and a.state=2';
if($_GPC['keywords']){
   $op=$_GPC['keywords'];
   $where.=" and (b.store_name LIKE  concat('%', :order_no,'%') or c.name LIKE  concat('%', :order_no,'%'))";	
   $data[':order_no']=$op;
}	
if($_GPC['time']){
   $start=strtotime($_GPC['time']['start']);
   $end=strtotime($_GPC['time']['end']);
  $where.=" and unix_timestamp(a.time) >={$start} and unix_timestamp(a.time)<={$end}";

}
$sql="SELECT a.*,b.store_name,c.name as user_name FROM ".tablename('zhtc_dmorder') .  " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id left join " . tablename("zhtc_user") . " c on a.user_id=c.id ".$where." ORDER BY a.id DESC";
$data[':uniacid']=$_W['uniacid'];
$total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('zhtc_dmorder') .  " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id left join " . tablename("zhtc_user") . " c  on a.user_id=c.id ".$where." ORDER BY a.id DESC",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);
include $this->template('web/dmorder');