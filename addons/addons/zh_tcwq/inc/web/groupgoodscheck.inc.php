<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$item=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
if(checksubmit('submit')){           
    $data['ggoods_set']=$_GPC['ggoods_set']; 
    $data['g_open']=$_GPC['g_open'];       
    $data['uniacid']=$_W['uniacid'];    
    if($_GPC['id']==''){                
        $res=pdo_insert('zhtc_system',$data);
        if($res){
            message('添加成功',$this->createWebUrl('groupgoodscheck',array()),'success');
        }else{
            message('添加失败','','error');
        }
    }else{
        $res = pdo_update('zhtc_system', $data, array('id' => $_GPC['id']));
        if($res){
            message('编辑成功',$this->createWebUrl('groupgoodscheck',array()),'success');
        }else{
            message('编辑失败','','error');
        }
    }
}
include $this->template('web/groupgoodscheck');