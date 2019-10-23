<?php
global $_GPC, $_W;
// $action = 'ad';
// $title = $this->actions_titles[$action];
$GLOBALS['frames'] = $this->getMainMenu();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
 $item=pdo_get('zhtc_sms',array('uniacid'=>$_W['uniacid']));
    if(checksubmit('submit')){
            $data['tid1']=trim($_GPC['tid1']);
            $data['tid2']=trim($_GPC['tid2']);
            $data['tid3']=trim($_GPC['tid3']);
            $data['fh_tid']=trim($_GPC['fh_tid']);
            $data['gm_tid']=trim($_GPC['gm_tid']);
            $data['hp_tid']=trim($_GPC['hp_tid']);
            $data['dz_tid']=trim($_GPC['dz_tid']);
            $data['tg_tid']=trim($_GPC['tg_tid']);
            $data['qf_tid']=trim($_GPC['qf_tid']);


            $data['qg_tid']=trim($_GPC['qg_tid']);
            $data['hd_tid']=trim($_GPC['hd_tid']);
            $data['pt_tid']=trim($_GPC['pt_tid']);
            $data['kq_tid']=trim($_GPC['kq_tid']);
            $data['uniacid']=trim($_W['uniacid']);
            if($_GPC['id']==''){                
                $res=pdo_insert('zhtc_sms',$data);
                if($res){
                    message('添加成功',$this->createWebUrl('template',array()),'success');
                }else{
                    message('添加失败','','error');
                }
            }else{
                $res = pdo_update('zhtc_sms', $data, array('id' => $_GPC['id']));
                if($res){
                    message('编辑成功',$this->createWebUrl('template',array()),'success');
                }else{
                    message('编辑失败','','error');
                }
            }
        }
    include $this->template('web/template');