<?php
global $_GPC, $_W;
$GLOBALS['frames'] = $this->getMainMenu();
 $item=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']));
 if($item['gs_img']){
 if(strpos($item['gs_img'],',')){
    $gs_img= explode(',',$item['gs_img']);
}else{
    $gs_img=array(
      0=>$item['gs_img']
    );
}
}
 if($item['kp_img']){
 if(strpos($item['kp_img'],',')){
    $kp_img= explode(',',$item['kp_img']);
}else{
    $kp_img=array(
      0=>$item['kp_img']
    );
}
}
// print_r($item);die;
    if(checksubmit('submit')){
            $data['pt_name']=$_GPC['pt_name'];
            $data['tel']=$_GPC['tel'];
            $data['is_kf']=$_GPC['is_kf'];
            $data['details']=html_entity_decode($_GPC['details']);
            $data['uniacid']=$_W['uniacid'];       
            $data['total_num']=$_GPC['total_num'];
            $data['sj_max']=$_GPC['sj_max'];
             $data['sj_max2']=$_GPC['sj_max2'];
            $data['zfwl_max']=$_GPC['zfwl_max'];
            $data['zfwl_open']=$_GPC['zfwl_open'];
            $data['tc_img']=$_GPC['tc_img'];
            $data['tc_gg']=$_GPC['tc_gg']; 
            $data['gs_details']=html_entity_decode($_GPC['gs_details']);
            $data['gs_add']=$_GPC['gs_add'];
            $data['gs_time']=$_GPC['gs_time'];
            $data['gs_tel']=$_GPC['gs_tel'];
            $data['gs_zb']=$_GPC['gs_zb'];  
            $data['model']=$_GPC['model'];  
            $data['is_city']=$_GPC['is_city']; 
            $data['zf_title']=$_GPC['zf_title']; 
            $data['kp_url']=$_GPC['kp_url']; 
            $data['is_pgzf']=$_GPC['is_pgzf']; 
           
             if($_GPC['kp_img']){
            $data['kp_img']=implode(",",$_GPC['kp_img']);
            }else{
                $data['kp_img']='';
            }   
            $data['kp_time']=$_GPC['kp_time']; 
            if($_GPC['gs_img']){
            $data['gs_img']=implode(",",$_GPC['gs_img']);
            }else{
                $data['gs_img']='';
            }

            if($_GPC['color']){
                $data['color']=$_GPC['color'];
            }else{
                $data['color']="#ED414A";
            }
            if($_GPC['id']==''){                
                $res=pdo_insert('zhtc_system',$data);
                if($res){
                    message('添加成功',$this->createWebUrl('settings',array()),'success');
                }else{
                    message('添加失败','','error');
                }
            }else{
                $res = pdo_update('zhtc_system', $data, array('id' => $_GPC['id']));
                if($res){
                    message('编辑成功',$this->createWebUrl('settings',array()),'success');
                }else{
                    message('编辑失败','','error');
                }
            }
        }
include $this->template('web/settings');