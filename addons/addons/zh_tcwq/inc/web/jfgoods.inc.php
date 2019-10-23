<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select a.* ,b.name as type_name from " . tablename("zhtc_jfgoods") . " a"  . " left join " . tablename("zhtc_jftype") . " b on b.id=a.type_id where a.uniacid=".$_W['uniacid']."  order by num asc";
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list = pdo_fetchall($select_sql);	   
$total=pdo_fetchcolumn("select count(*) from " . tablename("zhtc_jfgoods") . " a"  . " left join " . tablename("zhtc_jftype") . " b on b.id=a.type_id where a.uniacid=".$_W['uniacid']."");
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='delete'){
    $res=pdo_delete('zhtc_jfgoods',array('id'=>$_GPC['id']));
    if($res){
        message('删除成功',$this->createWebUrl('jfgoods',array()),'success');
    }else{
        message('删除失败','','error');
    }
}
if($_GPC['op']=='change'){
    $res=pdo_update('zhtc_jfgoods',array('is_open'=>$_GPC['is_open']),array('id'=>$_GPC['id']));
    if($res){
      message('修改成功！', $this->createWebUrl('jfgoods'), 'success');
    }else{
      message('修改失败！','','error');
    }
}
include $this->template('web/jfgoods');