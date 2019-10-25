<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
$info = pdo_get('zhtc_distribution',array('id'=>$_GPC['id']));
//自己
$level = pdo_getall('zhtc_fxlevel',array('uniacid'=>$_W['uniacid']));
$fxuser=pdo_get('zhtc_fxuser',array('fx_user'=>$info['user_id']));
//查看我的上级
$fxuser=pdo_get('zhtc_distribution',array('user_id'=>$fxuser['user_id']));
//查看上级分销商信
//一级
$sjuser1=pdo_getall('zhtc_fxuser',array('user_id'=>$info['user_id']),'fx_user');
$sjuser1 = array_map('array_shift', $sjuser1);
//二级
$sjuser2=pdo_getall('zhtc_fxuser',array('user_id'=>$sjuser1),'fx_user');
$sjuser2 = array_map('array_shift', $sjuser2);

$yuser=array_merge($sjuser1,$sjuser2);
//var_dump($yuser);die;


array_push($yuser, $info['user_id']);

$user=pdo_getall('zhtc_distribution',array('uniacid'=>$_W['uniacid'],'user_id !='=>$yuser,'state'=>2));
//print_r($user);die;
if(checksubmit('submit')){
   // echo $_GPC['level'];die;
    $data2['level']=$_GPC['level'];
    $data['user_id']=$_GPC['user_id'];
    $res2=pdo_update('zhtc_distribution', $data2, array('id' => $_GPC['id']));
    $res = pdo_update('zhtc_fxuser', $data, array('fx_user' => $info['user_id']));
    if($res || $res2){
        message('编辑成功',$this->createWebUrl('fxlist',array()),'success');
    } else{
        message('编辑失败','','error');
    }
}
include $this->template('web/fxinfo');