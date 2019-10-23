<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$type=empty($_GPC['type']) ? 'all' :$_GPC['type'];
$state=$_GPC['state'];
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$where=' WHERE  uniacid=:uniacid and store_id=:store_id';
$data[':uniacid']=$_W['uniacid'];
$data[':store_id']=$storeid;
if($_GPC['keywords']){
    $op=$_GPC['keywords'];
    $where.=" and name LIKE  concat('%', :name,'%') ";    
    $data[':name']=$op;
}
if($type=='all'){    
  $sql="SELECT * FROM ".tablename('zhtc_withdrawal') .  "  ". $where." ORDER BY time DESC";
  $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('zhtc_withdrawal') ."".$where." ORDER BY time DESC",$data);
}else{
    $where.= " and state=".$state;
    $sql="SELECT * FROM ".tablename('zhtc_withdrawal') .  " ".$where." ORDER BY time DESC";
    $data[':uniacid']=$_W['uniacid'];
    $total=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('zhtc_withdrawal') .  " ".$where." ORDER BY time DESC",$data);    
}
$list=pdo_fetchall( $sql,$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($operation=='delete'){
     $id=$_GPC['id'];
     $res=pdo_delete('zhtc_withdrawal',array('id'=>$id));
     if($res){
        message('删除成功',$this->createWebUrl2('sjtxlist',array()),'success');
    }else{
        message('删除失败','','error');
    }

}

include $this->template('web/sjtxlist');