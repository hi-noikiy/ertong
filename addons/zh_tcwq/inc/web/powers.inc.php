<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('zhtc_system',array('uniacid'=>$_GPC['uniacid']));
if(checksubmit('submit')){
   $data['is_pcqx']=$_GPC['is_pcqx'];
   $data['is_hyqx']=$_GPC['is_hyqx'];
   $data['is_yhqqx']=$_GPC['is_yhqqx'];
   $data['is_syqx']=$_GPC['is_syqx'];
   $data['is_hdqx']=$_GPC['is_hdqx'];
   $data['is_hbqx']=$_GPC['is_hbqx'];
   $data['is_hhrqx']=$_GPC['is_hhrqx'];
   $data['is_dcsqx']=$_GPC['is_dcsqx'];
   $data['is_jfqx']=$_GPC['is_jfqx'];
   $data['is_spqx']=$_GPC['is_spqx'];
   $data['is_qgqx']=$_GPC['is_qgqx'];
   $data['g_qx']=$_GPC['g_qx'];
   $data['is_message']=$_GPC['is_message'];
   $data['is_video']=$_GPC['is_video'];
   $data['uniacid']=$_GPC['uniacid'];
   if($_GPC['id']==''){                
    $res=pdo_insert('zhtc_system',$data);
    if($res){
        message('添加成功',$this->createWebUrl('powers',array('uniacid'=>$_GPC['uniacid'])),'success');
    }else{
        message('添加失败','','error');
    }
}else{
    $res = pdo_update('zhtc_system', $data, array('id' => $_GPC['id']));
    if($res){
        message('编辑成功',$this->createWebUrl('powers',array('uniacid'=>$_GPC['uniacid'])),'success');
    }else{
        message('编辑失败','','error');
    }
}
}
include $this->template('web/powers');