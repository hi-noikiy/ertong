<?php
/**
 * Created by PhpStorm.
 * User: shizhongying
 * QQ : 214983937
 * Date: 7/21/15
 * Time: 09:47
 */
global $_W, $_GPC;
$weid = $_W['uniacid'];
$op = empty($_GPC['op']) ? 'base' : trim($_GPC['op']);
load()->func('tpl');
$set = pdo_fetch("select * from " . tablename('amouse_wxapp_sysset') . ' where `uniacid`=:uniacid limit 1', array(':uniacid' => $weid));
$rules = unserialize($set['rule']);
$sets  = unserialize($set['sets']);
if (checksubmit('submit')) {
    if ($op == 'base') {
        $data2['logo'] = trim($_GPC['logo']);
        $data2['copyright'] = trim($_GPC['copyright']);
        $data2['systel'] = trim($_GPC['systel']);
        $data2['enable'] = intval($_GPC['enable']) ;
        $data2['isenable'] = intval($_GPC['isenable']) ;
        $data2['isshare'] = intval($_GPC['isshare']) ;
        $data2['iscreate'] =  intval($_GPC['iscreate']) ;
        $data2['share_thumb']  =trim($_GPC['share_thumb']);
        $data2['mp']  =trim($_GPC['mp']);
        $data2['appid']  =trim($_GPC['appid']);
        $data2['qqmap_ak']  =trim($_GPC['qqmap_ak']);
        $data2['appname']  =trim($_GPC['appname']);
        $data2['wxapp_name1']  =trim($_GPC['wxapp_name1']);
        $data2['wxapp_url1']  =trim($_GPC['wxapp_url1']);$data2['wxapp_url2']  =trim($_GPC['wxapp_url2']);
        $data2['wxapp_name2']  =trim($_GPC['wxapp_name2']);
        $data2['bgcolor']  =trim($_GPC['bgcolor']); $data2['indexname']  =trim($_GPC['indexname']);
        $data2['public_status'] = trim($_GPC['public_status']);
        $data2['exchange'] = trim($_GPC['exchange']);
        $data2['is_public'] = trim($_GPC['is_public']);
        $set2['sys'] = is_array($_GPC['sys']) ? $_GPC['sys'] : array();
    } else if ($op == 'tpl') {
        $data2['mobile_verify_status'] = trim($_GPC['mobile_verify_status']);
        $data2['sms_user'] = trim($_GPC['sms_user']);
        $data2['sms_secret'] = trim($_GPC['sms_secret']);
        $data2['sms_type'] = trim($_GPC['sms_type']);
        $data2['sms_template_code'] = trim($_GPC['sms_template_code']);
        $data2['sms_free_sign_name'] = trim($_GPC['sms_free_sign_name']);
        $data2['reg_sms_code'] = trim($_GPC['reg_sms_code']);
        $data2['notice_sms_code'] = trim($_GPC['notice_sms_code']);
        $data2['share_title'] = trim($_GPC['share_title']);
        $data2['share_desc'] = trim($_GPC['share_desc']);

        $data2['collect_tpl']  =trim($_GPC['collect_tpl']);
        $data2['zan_tpl']  =trim($_GPC['zan_tpl']);
        $data2['save_tpl'] = trim($_GPC['save_tpl']);
    }else if($op == 'credit') {
        $data2['public_price'] =  trim($_GPC['public_price']) ;
        $data2['public_credit'] = trim($_GPC['public_credit']);
        $data2['pay_credit'] = trim($_GPC['pay_credit']);
        $data2['share_credit'] = trim($_GPC['share_credit']);
        $data2['limit_credit'] = trim($_GPC['limit_credit']);
        $cs = array();
        $top_days = $_GPC['top_days'];
        $top_amouts = $_GPC['top_amounts'];
        if (is_array($top_days)) {
            foreach ($top_days as $key => $value) {
                $d = array('day' => $top_days[$key], 'amount' => $top_amouts[$key]);
                $cs[] = $d;
            }
        }
        if (!empty($cs)) {
            $_GPC['rule'] = iserializer($cs);
        }
        $data2['rule'] = trim($_GPC['rule']);
    }else if($op == 'level') {
        $set2['level'] = is_array($_GPC['level']) ? $_GPC['level'] : array();
    }

    $data2['sets']= iserializer($set2);
    if (empty($set)) {
        $data2['uniacid'] = $weid;
        pdo_insert('amouse_wxapp_sysset', $data2);
    } else {
        pdo_update('amouse_wxapp_sysset', $data2, array('uniacid' => $weid));
    }
    message('更新参数设置成功！', 'refresh');
}

/*if (checksubmit('confrimprint')) {
    $rnd = random(6, 1);
    require_once IA_ROOT . '/addons/amouse_wxapp_card/AliyunSms.class.php';
    $txt = "【微信验证】您的本次操作的验证码为：" . $rnd . ".十分钟内有效";
    if ($set['sms_free_sign_name'] && $set['sms_template_code']) {
        $sms = new \AliyunSms();
        $sms_param = "{\"number\":\"$rnd\"}";
        if ($set['sms_type'] == 1) {
           $result =  $sms->_sendNewDySms('15852511994', $set['sms_user'], $set['sms_secret'], $set['sms_free_sign_name'], $set['reg_sms_code'], $sms_param, $rnd);

        } else {
            $sms->_sendAliDaYuSms('17601500531', $set['sms_user'], $set['sms_secret'], $set['sms_free_sign_name'], $set['reg_sms_code'], $sms_param);
        }
    }
    message('xxxxxxxx！'.$set['sms_type'], 'refresh');
}*/
include $this->template('web/_set');