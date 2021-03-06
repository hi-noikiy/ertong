<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$where=" where a.uniacid=:uniacid";
$data[':uniacid']=$_W['uniacid']; 
$type=pdo_getall('zhtc_acttype',array('uniacid'=>$_W['uniacid']));
if(isset($_GPC['keywords'])){
    $where.=" and a.title LIKE  concat('%', :name,'%') ";
    $data[':name']=$_GPC['keywords']; 
     $type='all';   
}

if(!empty($_GPC['time'])){
   $start=strtotime($_GPC['time']['start']);
   $end=strtotime($_GPC['time']['end']);
  $where.=" and UNIX_TIMESTAMP(a.time) >={$start} and UNIX_TIMESTAMP(a.time)<={$end}";
}

if($_GPC['type_id']>0){
  $where.=" and a.type_id=".$_GPC['type_id'];
}




$pageindex = max(1, intval($_GPC['page']));
$pagesize=10;
$sql="select a.*,b.type_name from".tablename('zhtc_activity') ." a"  . " left join " . tablename("zhtc_acttype") . " b on b.id=a.type_id  ".$where." ORDER BY num asc";
$total=pdo_fetchcolumn("select count(*) from".tablename('zhtc_activity')." a"  . " left join " . tablename("zhtc_acttype") . " b on b.id=a.type_id ".$where,$data);
$select_sql =$sql." LIMIT " .($pageindex - 1) * $pagesize.",".$pagesize;
$list=pdo_fetchall($select_sql,$data);
$pager = pagination($total, $pageindex, $pagesize);
if($_GPC['op']=='delete'){
    $res=pdo_delete('zhtc_activity',array('id'=>$_GPC['id']));
    // pdo_delete('zhtc_joinlist')
    if($res){
      message('删除成功！', $this->createWebUrl('activity'), 'success');
    }else{
      message('删除失败！','','error');
    }
}
if($_GPC['op']=='change'){
    $res=pdo_update('zhtc_activity',array('is_bm'=>$_GPC['is_bm']),array('id'=>$_GPC['id']));
    if($res){
      message('修改成功！', $this->createWebUrl('activity'), 'success');
    }else{
      message('修改失败！','','error');
    }
}
include $this->template('web/activity');