<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$type=isset($_GPC['type'])?$_GPC['type']:'all';
$where=" where a.uniacid=:uniacid  and a.state>0";
$data[':uniacid']=$_W['uniacid']; 
if(isset($_GPC['keywords'])){
	$where.=" and ( a.goods_name LIKE  concat('%', :name,'%') || a.id LIKE  concat('%', :name,'%') || b.store_name LIKE  concat('%', :name,'%'))";
	$data[':name']=$_GPC['keywords']; 
	$type='all';  
}
if($_GPC['time']){
	$start=strtotime($_GPC['time']['start']);
	$end=strtotime($_GPC['time']['end']);
	$where.=" and a.kt_time >='{$start}' and a.kt_time<='{$end}'";
	$type='all';
}else{
	if($type=='ing'){
		$where.=" and a.state=1";
	}
	if($type=='success'){
		$where.=" and a.state=2";
	}
	if($type=='fail'){
		$where.=" and a.state=3";
	}

}
$sql="SELECT a.*,b.store_name FROM ".tablename('zhtc_group')." a left join".tablename('zhtc_store')." b on a.store_id=b.id ".$where." ORDER BY a.id DESC";
$total=pdo_fetchcolumn("SELECT * FROM ".tablename('zhtc_group')." a left join".tablename('zhtc_store')." b on a.store_id=b.id " .$where,$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);

//打印
include $this->template('web/sjgroup');