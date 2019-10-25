<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$where="  WHERE a.uniacid=:uniacid";
$data[':uniacid']=$_W['uniacid'];
$state=$_GPC['state'];
$type=isset($_GPC['type'])?$_GPC['type']:'wait';
if($type=='wait'){
  $state=1;
}
if($state){
    $where.=" and  a.state=:state";
     $data[':state']=$state; 
}
if(!empty($_GPC['keywords'])){
	$where.=" and (a.name LIKE  concat('%', :name,'%') or a.tel LIKE  concat('%', :name,'%'))";
	$data[':name']=$_GPC['keywords'];   
}
if(!empty($_GPC['time'])){
	$start=strtotime($_GPC['time']['start']);
	$end=strtotime($_GPC['time']['end']);
	$where.=" and a.time >={$start} and a.time<={$end} ";
}
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select a.*,b.store_name from " . tablename("zhtc_upgrade") . " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id".$where." order by a.time desc ";
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list = pdo_fetchall($select_sql,$data);	
$total=pdo_fetchcolumn("select count(*) from " . tablename("zhtc_upgrade") . " a"  . " left join " . tablename("zhtc_store") . " b on a.store_id=b.id".$where,$data);
$pager = pagination($total, $pageindex, $pagesize);

if($_GPC['op']=='delete'){
	$res=pdo_delete('zhtc_upgrade',array('id'=>$_GPC['id']));
	if($res){
		message('删除成功！', $this->createWebUrl('upgrade'), 'success');
	}else{
		message('删除失败！','','error');
	}
}
if($_GPC['op']=='tg'){
	$res=pdo_update('zhtc_upgrade',array('state'=>2),array('id'=>$_GPC['id']));
	if($res){
		$rst=pdo_get('zhtc_upgrade',array('id'=>$_GPC['id']),'store_id');
		pdo_update('zhtc_store',array('cityname'=>''),array('id'=>$rst['store_id']));

		message('通过成功！', $this->createWebUrl('upgrade',array('type'=>$_GPC['type'],'page'=>$_GPC['page'])), 'success');
	}else{
		message('通过失败！','','error');
	}
}
if($_GPC['op']=='jj'){
    $res=pdo_update('zhtc_upgrade',array('state'=>3,'sh_time'=>time()),array('id'=>$_GPC['id']));
    if($res){
     message('拒绝成功！', $this->createWebUrl('upgrade',array('type'=>$_GPC['type'],'page'=>$_GPC['page'])), 'success');
    }else{
     message('拒绝失败！','','error');
    }
}
include $this->template('web/upgrade');