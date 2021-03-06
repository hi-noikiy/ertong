<?php
global $_GPC, $_W;

$GLOBALS['frames'] = $this->getMainMenu();
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$type=isset($_GPC['type'])?$_GPC['type']:'all';
$type2=isset($_GPC['type2'])?$_GPC['type2']:'today';
$where=" where a.uniacid=:uniacid ";
$data[':uniacid']=$_W['uniacid']; 
if(isset($_GPC['keywords'])){
  $where.=" and (a.goods_name LIKE  concat('%', :name,'%') || a.order_num LIKE  concat('%', :name,'%') || b.name LIKE  concat('%', :name,'%') || c.store_name LIKE  concat('%', :name,'%'))";
  $data[':name']=$_GPC['keywords']; 
  $type='all';  
}
if($_GPC['time']){
  $start=strtotime($_GPC['time']['start']);
  $end=strtotime($_GPC['time']['end']);
  $where.=" and a.time >='{$start}' and a.time<='{$end}'";
  $type='all';
}else{
 if($type=='wait'){
  $where.=" and a.state=1";
}
if($type=='pay'){
  $where.=" and a.state=2";
}

if($type=='complete'){
  $where.=" and a.state=3";
}
if($type=='close'){
  $where.=" and a.state=4";
}
if($type=='invalid'){
  $where.=" and a.state=5";
} 

}
$sql="SELECT a.*,b.name as nick_name,c.store_name FROM ".tablename('zhtc_grouporder'). " a"  . " left join " . tablename("zhtc_user") . " b on a.user_id=b.id  left join " . tablename("zhtc_store") . " c on a.store_id=c.id " .$where." ORDER BY a.id DESC";
$total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('zhtc_grouporder'). " a"  . " left join " . tablename("zhtc_user") . " b on a.user_id=b.id left join " . tablename("zhtc_store") . " c on a.store_id=c.id " .$where,$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;

$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);

if($_GPC['op']=='delete'){
  $res=pdo_delete('zhtc_grouporder',array('id'=>$_GPC['id']));
  if($res){
   message('删除成功！', $this->createWebUrl('grouporder'), 'success');
 }else{
  message('删除失败！','','error');
}
}
include $this->template('web/grouporder');