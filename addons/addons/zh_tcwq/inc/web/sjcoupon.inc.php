<?php

global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$where=" WHERE  b.uniacid=:uniacid and a.store_id=:store_id";
$data[':uniacid']=$_W['uniacid'];
$data[':store_id']=$storeid;
if($_GPC['keywords']){
    $op=$_GPC['keywords'];
      $where.=" and (a.name LIKE  concat('%', :name,'%') || b.store_name LIKE  concat('%', :name,'%'))";  
       $data[':name']=$op;
}
if(!empty($_GPC['time'])){
   $start=strtotime($_GPC['time']['start']);
   $end=strtotime($_GPC['time']['end']);
  $where.=" and UNIX_TIMESTAMP(a.time) >={$start} and UNIX_TIMESTAMP(a.time)<={$end}";

}
if($_GPC['state']){
      $where.=" and a.state={$_GPC['state']} ";  
}
$state=$_GPC['state'];
$type=isset($_GPC['type'])?$_GPC['type']:'all';
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;

  $sql="select a.*,b.store_name,c.type_name from " . tablename("zhtc_coupons") . " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id left join " . tablename("zhtc_coupontype") . " c on a.type_id=c.id " .$where."  order by a.time desc ";
  $total=pdo_fetchcolumn("select count(*) as wname from " . tablename("zhtc_coupons") . " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id left join " . tablename("zhtc_coupontype") . " c on a.type_id=c.id ".$where."  order by a.time desc ",$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='delete'){
    $res=pdo_delete('zhtc_coupons',array('id'=>$_GPC['id']));
    if($res){
       message('删除成功！', $this->createWebUrl2('sjcoupon'), 'success');
      }else{
            message('删除失败！','','error');
      }
}
if($_GPC['op']=='updshow'){
    $res=pdo_update('zhtc_coupons',array('is_show'=>$_GPC['is_show']),array('id'=>$_GPC['id']));
    if($res){
       message('操作成功！', $this->createWebUrl2('sjcoupon'), 'success');
      }else{
            message('操作失败！','','error');
      }
}
if($_GPC['op']=='updshow2'){
    $res=pdo_update('zhtc_coupons',array('is_pt'=>$_GPC['is_pt']),array('id'=>$_GPC['id']));
    if($res){
       message('操作成功！', $this->createWebUrl2('sjcoupon'), 'success');
      }else{
            message('操作失败！','','error');
      }
}
if($_GPC['op']=='tg'){
    $res=pdo_update('zhtc_coupons',array('state'=>2),array('id'=>$_GPC['id']));
    if($res){
         message('通过成功！', $this->createWebUrl2('sjcoupon'), 'success');
        }else{
              message('通过失败！','','error');
        }
}
if($_GPC['op']=='jj'){
    $res=pdo_update('zhtc_coupons',array('state'=>3),array('id'=>$_GPC['id']));
    if($res){
         message('拒绝成功！', $this->createWebUrl2('sjcoupon'), 'success');
        }else{
         message('拒绝失败！','','error');
        }
}
include $this->template('web/sjcoupon');