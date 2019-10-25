<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$types=pdo_getall('zhtc_grouptype',array('uniacid'=>$_W['uniacid']),array('name','id'),'','num asc');
$where=" WHERE a.uniacid=:uniacid ";
$data[':uniacid']=$_W['uniacid'];
if($_GPC['keywords']){
	$where.=" and (a.name LIKE  concat('%', :name,'%') || c.store_name LIKE  concat('%', :name,'%'))";
	$op=$_GPC['keywords'];
	$data[':name']="%$op%";

}
if($_GPC['type_id']){
	$where .=" and a.type_id=:type_id";
	$data[':type_id']=$_GPC['type_id'];
}
if($_GPC['is_shelves2']){
	$where .=" and a.is_shelves=:cid";
	$data[':cid']=$_GPC['is_shelves2'];
}
if($_GPC['state']){
	$where.=" and a.state={$_GPC['state']} ";  
}
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$type=isset($_GPC['type'])?$_GPC['type']:'all';
$sql="select a.* ,b.name as type_name,c.store_name  from " . tablename("zhtc_groupgoods") . " a"  . " left join " . tablename("zhtc_grouptype") . " b on b.id=a.type_id  left join " . tablename("zhtc_store") . " c on c.id=a.store_id".$where." order by num asc";
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list = pdo_fetchall($select_sql,$data);	   
$total=pdo_fetchcolumn("select count(*) from " . tablename("zhtc_groupgoods") . " a"  . " left join " . tablename("zhtc_grouptype") . " b on b.id=a.type_id left join " . tablename("zhtc_store") . " c on c.id=a.store_id".$where,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['id']){
	$data2['is_shelves']=$_GPC['is_shelves'];
	$res=pdo_update('zhtc_groupgoods',$data2,array('id'=>$_GPC['id']));
	if($res){
		message('设置成功',$this->createWebUrl('groupgoods',array('page'=>$_GPC['page'],'keywords'=>$_GPC['keywords'],'dishes_type'=>$_GPC['dishes_type'],'type_id'=>$_GPC['type_id'],'is_show2'=>$_GPC['is_show2'])),'success');
	}else{
		message('设置失败','','error');
	}
}
if($_GPC['op']=='delete'){
	$result = pdo_delete('zhtc_groupgoods', array('id'=>$_GPC['delid']));
	if($result){
		message('删除成功',$this->createWebUrl('groupgoods',array()),'success');
	}else{
		message('删除失败','','error');
	}
}
if($_GPC['op']=='play'){
	$data2['display']=$_GPC['display'];
	$res=pdo_update('zhtc_groupgoods',$data2,array('id'=>$_GPC['did']));
	if($res){
		message('设置成功',$this->createWebUrl('groupgoods',array('page'=>$_GPC['page'],'keywords'=>$_GPC['keywords'],'dishes_type'=>$_GPC['dishes_type'],'type_id'=>$_GPC['type_id'])),'success');
	}else{
		message('设置失败','','error');
	}
}
if($_GPC['op']=='tg'){
    $res=pdo_update('zhtc_groupgoods',array('state'=>2),array('id'=>$_GPC['cid']));
    if($res){
         message('通过成功！', $this->createWebUrl('groupgoods'), 'success');
        }else{
              message('通过失败！','','error');
        }
}
if($_GPC['op']=='jj'){
    $res=pdo_update('zhtc_groupgoods',array('state'=>3),array('id'=>$_GPC['cid']));
    if($res){
         message('拒绝成功！', $this->createWebUrl('groupgoods'), 'success');
        }else{
         message('拒绝失败！','','error');
        }
}

include $this->template('web/groupgoods');
