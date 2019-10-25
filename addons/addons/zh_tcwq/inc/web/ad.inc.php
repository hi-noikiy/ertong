<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$where=" where uniacid=:uniacid";
$data[':uniacid']=$_W['uniacid'];
$type_id=$_GPC['type_id'];
if($_GPC['type_id']>0){
	$where.=" and type=:type";
	$data[':type']=$type_id;

}
$sql="select * from".tablename('zhtc_ad').$where."  ORDER BY orderby ASC";

$total=pdo_fetchcolumn("select count(*) from".tablename('zhtc_ad').$where,$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);

$pager = pagination($total, $pageindex, $pagesize);
//$list=pdo_getall('zhtc_ad',array('uniacid'=>$_W['uniacid']),array(),'','orderby ASC');
if($_GPC['op']=='delete'){
	$res=pdo_delete('zhtc_ad',array('id'=>$_GPC['id']));
	if($res){
		message('删除成功！', $this->createWebUrl('ad'), 'success');
	}else{
		message('删除失败！','','error');
	}
}
if($_GPC['status']){
	$data2['status']=$_GPC['status'];
	$res=pdo_update('zhtc_ad',$data2,array('id'=>$_GPC['id']));
	if($res){
		message('编辑成功！', $this->createWebUrl('ad',array('type_id'=>$type_id)), 'success');
	}else{
		message('编辑失败！','','error');
	}
}
include $this->template('web/ad');