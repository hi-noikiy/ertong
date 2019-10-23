<?php

global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info=pdo_get('zhtc_plate',array('id'=>$_GPC['id']));
if(checksubmit('submit')){
   $data['type']=$_GPC['type'];
   if(empty($_GPC['name'])){
    message('自定义名称不能为空！','','error');
   }
   $data['name']=$_GPC['name'];
   $data['uniacid']=$_W['uniacid'];
   $data['sort']=$_GPC['sort']; 
   if($_GPC['id']==''){  
    $res=pdo_insert('zhtc_plate',$data);
    if($res){
       message('添加成功！', $this->createWebUrl('plate'), 'success');
   }else{
       message('添加失败！','','error');
   }
}else{
    $res=pdo_update('zhtc_plate',$data,array('id'=>$_GPC['id']));
    if($res){
       message('编辑成功！', $this->createWebUrl('plate'), 'success');
   }else{
       message('编辑失败！','','error');
   }
}

}
include $this->template('web/addplate');