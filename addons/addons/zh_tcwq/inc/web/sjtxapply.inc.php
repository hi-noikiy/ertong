<?php
global $_GPC, $_W;
$storeid=$_COOKIE["storeid"];
$cur_store = $this->getStoreById($storeid);
$GLOBALS['frames'] = $this->getNaveMenu2($storeid);
$sys=pdo_get('zhtc_system',array('uniacid'=>$_W['uniacid']),array('tx_type','tx_money'));

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$store=pdo_get('zhtc_store',array('id'=>$storeid,'uniacid'=>$_W['uniacid']),'wallet');
   //可提现金额
   $ktxcost= $store['wallet'];
    if(checksubmit('submit')){
      if(empty($_GPC['tx_cost'])){
        message('提现金额不能为空','','error');
      }
      if($sys['tx_money']> $ktxcost){
        message('未达到最低提现金额','','error');
      }
      if($_GPC['tx_cost']> $ktxcost){
        message('输入金额大于可提现金额','','error');
      }
      $data['name']=$_GPC['name'];//真实姓名
      $data['username']=$_GPC['username'];//账号
      $data['type']=$sys['tx_type'];
      $data['tx_cost']=$_GPC['tx_cost'];//提现金额
      $data['sj_cost']=$_GPC['sj_cost'];//实际到账金额
      $data['store_id']=$storeid;//商家id
      $data['method']=2;
      $data['time']=time();
      $data['state']=1;
      $data['uniacid']=$_W['uniacid'];
      $res=pdo_insert('zhtc_withdrawal',$data);
      $txsh_id=pdo_insertid();
      if($res){
          pdo_update('zhtc_store',array('wallet -='=>$_GPC['tx_cost']),array('id'=>$storeid));
          pdo_insert('zhtc_store_wallet',array('store_id'=>$storeid,'money'=>$_GPC['tx_cost'],'note'=>'提现申请','type'=>2,'time'=>date("Y-m-d H:i:s"),'tx_id'=>$txsh_id));
          message('添加成功',$this->createWebUrl2('sjtxlist',array()),'success');           
        }else{
            message('添加失败','','error');
        }
    }          


include $this->template('web/sjtxapply');