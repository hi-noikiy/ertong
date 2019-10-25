<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$pageindex = max(1, intval($_GPC['page']));
$pagesize=8;
$type=isset($_GPC['type'])?$_GPC['type']:'all';
$where=" where a.uniacid=:uniacid and a.state!=1";
$data[':uniacid']=$_W['uniacid']; 
if(isset($_GPC['keywords'])){
  $where.=" and (a.user_name LIKE  concat('%', :name,'%') || a.order_num LIKE  concat('%', :name,'%') || b.store_name LIKE  concat('%', :name,'%'))";
  $data[':name']=$_GPC['keywords']; 
  $type='all';  
}
if($_GPC['time']){
  $start=$_GPC['time']['start'];
  $end=$_GPC['time']['end'];
  $where.=" and a.time >='{$start}' and a.time<='{$end}'";
  $type='all';
}else{
if($type=='now'){
  $where.=" and a.state=2";
}

if($type=='complete'){
  $where.=" and a.state=4";
}
if($type=='ok'){
  $where.=" and a.state=3";
}
}



$sql="SELECT a.*,b.store_name as md_name FROM ".tablename('zhtc_qgorder'). " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id  ".$where." ORDER BY a.id DESC";
$total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('zhtc_qgorder'). " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id ".$where." ORDER BY a.id DESC",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$pager = pagination($total, $pageindex, $pagesize);
$list=pdo_fetchall($select_sql,$data);
//print_R($list);die;
include $this->template('web/qgorder');