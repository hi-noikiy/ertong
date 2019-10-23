<?php
global $_GPC, $_W;
$action = 'start';
$GLOBALS['frames'] = $this->getNaveMenu($_COOKIE['cityname'], $action);
$list = pdo_getall('zhtc_acthxlist',array('act_id' => $_GPC['act_id']),array(),'','id DESC');
if($_GPC['op']=='delete'){
    $res=pdo_delete('zhtc_acthxlist',array('id'=>$_GPC['id']));
    if($res){
        message('删除成功',$this->createWebUrl2('dlinacthx',array('act_id' => $_GPC['act_id'])),'success');
    }else{
        message('删除失败','','error');
    }
}

include $this->template('web/dlinacthx');