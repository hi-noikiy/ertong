<?php
/**
 * 模块微站定义
 * @优呀精品社区
 * @url bbs.uuuya.com
 */
defined('IN_IA') or exit('Access Denied');
define("AMOUSE_WXAPP_CARD", "amouse_wxapp_card");
define("RES", "../addons/".AMOUSE_WXAPP_CARD."/style/");
error_reporting(0);
class Amouse_Wxapp_CardModuleSite extends WeModuleSite {
    //   https://你的域名/app/index.php?i=3&j=3&c=entry&do=AutoFinishVip&m=amouse_wxapp_card
    public function doMobileAutoFinishVip(){
        global $_GPC;
        $uniacid = $_GPC['i'] ;
        $sets = pdo_fetchall("SELECT uniacid,collect_tpl,zan_tpl FROM " . tablename('amouse_wxapp_sysset'));
        load()->func('file');
        foreach ($sets as $set) {
            $sweid = $set['uniacid'];
            if (empty($sweid)) {
                continue;
            }
            $autos=pdo_fetchall("SELECT id,openid,createtime,username,vip FROM ".tablename('amouse_wxapp_card')." where vip=1 and createtime<unix_timestamp()  order by createtime desc ");
            foreach ($autos as $k => $auto) {
                pdo_update('amouse_wxapp_card', array('createtime'=>time(),'vip'=>0,'clazz'=>'default'), array('id' =>$auto['id']));
                $sendFromid = $this->getFormId($uniacid, $auto['openid']);
                if ($sendFromid) {
                    $send['first'] = array('value' => '会员等级变更通知', 'color' => '#000');
                    $send['keyword1'] = array('value' => 'VIP会员', 'color' => '#000');
                    $send['keyword2'] = array('value' => '普通会员', 'color' => '#000');
                    $send['keyword3'] = array('value' => date('Y/m/d H:i:s', time()), 'color' => '#000');
                    $send['keyword4'] = array('value' => '您的会员等级已降级', 'color' => '#000');
                    $send['keyword5'] = array('value' => '如有疑问，请询客服热线。', 'color' => '#000');
                    $this->sendTplNotice($uniacid,$auto['openid'], $set['zan_tpl'], 'amouse_wxapp_card/pages/card/home/home', $sendFromid, $send, 'keyword1.DATA');
                }
            }
            var_dump($autos);
        }

    }
    private function _get($uniacid, $openId) {
        $tplcodes = pdo_fetchall("SELECT * FROM " . tablename('amouse_wxapp_tplcode') . " WHERE `uniacid`= :weid and `openid`=:openid and status=0 ", array(':weid' => $uniacid, ':openid' => $openId));
        return $tplcodes;
    }
    //取出用户可用的formid
    public function getFormId($uniacid, $openId){
        $res = $this->_get($uniacid, $openId);
        if($res){
            if(!count($res)){
                return FALSE;
            }
            $result = FALSE;
            for($i = 0;$i < count($res);$i++){
                if($res[$i]['createtime'] > time()){
                    $result = $res[$i]['code'];//得到一个可用的formId
                }
            }
            return $result;
        }else{
            return FALSE;
        }
    }
    public function payResult($params) {
        global $_W;
        load()->func('logging');
        $order = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_order') . " WHERE `id`= :id AND `uniacid`= :weid ", array(':id' => $params['tid'], ':weid' => intval($_W['uniacid'])));
        $orderData = array('status' => $params['result'] == 'success' ? 1 : 0);
        if ($params['type'] == 'wechat') {
            $orderData['transid'] = $params['tag']['transaction_id'];
        }
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            if ($params['fee'] == $order['price']) {
                pdo_update('amouse_wxapp_order', $orderData, array('id' => $params['tid']));
                $openid = $order['openid'];
                $top_day  =$order['top_day'];
                $card= pdo_fetch("SELECT * FROM ".tablename('amouse_wxapp_card')." WHERE `openid` = :id AND `uniacid` = :weid ", array(':id' =>$openid, ':weid' => intval($_W['uniacid'])));
                if ($card['createtime'] >= time()) {//延时
                    $nextWeek = $card['createtime'] + ($top_day * 24 * 60 * 60);
                }else{
                    $nextWeek = TIMESTAMP + ($top_day * 24 * 60 * 60);
                }
                $data2['createtime'] = $nextWeek;
                $data2['vip']= 1;//置顶标志
                pdo_update('amouse_wxapp_card', $data2, array('id' =>$card['id']));
                //模板消息
                $sys = pdo_fetch("SELECT `id`,`pay_credit`,`collect_tpl`,`public_credit` FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => intval($_W['uniacid'])));
                $zan_tpl= pdo_fetchcolumn("SELECT zan_tpl FROM " . tablename('amouse_wxapp_sysset')." where `uniacid`=:uniacid limit 1 " ,array(":uniacid"=>intval($_W['uniacid'])));
                if (!empty($order['formid'])) {
                    $end_time = date('Y/m/d H:i:s', $nextWeek);
                    $send['first'] = array('value' => '会员等级变更通知', 'color' => '#000');
                    $send['keyword1'] = array('value' => '普通会员', 'color' => '#000');
                    $send['keyword2'] = array('value' => 'VIP会员', 'color' => '#000');
                    $send['keyword3'] = array('value' => $end_time, 'color' => '#000');
                    $send['keyword4'] = array('value' => '您的会员等级已升级', 'color' => '#000');
                    $send['keyword5'] = array('value' => '赶紧去使用VIP功能吧。', 'color' => '#000');
                    $this->sendTplNotice(intval($_W['uniacid']),$card['openid'], $zan_tpl, 'amouse_wxapp_card/pages/cooperation/cooperation', $order['formid'], $send, 'keyword1.DATA');
                }
                if($sys['pay_credit']>0){//支付赠送积分
                    $this->setFansCredit($order['openid'],'credit1',$sys['pay_credit'],$sys['limit_credit'],"{$order['openid']}支付成功赠送积分".$sys['pay_credit']) ;
                }
            }
        }
    }

    public function setFansCredit($openid, $credittype,$credit ,$limit,$remark) {
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($openid);
        $fans = mc_fetch($uid, array($credittype));
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            $log[2] = $this->modulename;
            $date=date('Y-m-d');
            $record=pdo_fetchcolumn("SELECT sum(num) FROM ".tablename('mc_credits_record')." WHERE uniacid = :weid and uid = :uid and date_format(FROM_UNIXTIME(createtime), '%Y-%m-%d') = :date", array(':weid'=>$_W['uniacid'], ':wid'=>$uid, ':date'=>$date));
            if($limit==0 || $limit>$record){
                return mc_credit_update($uid, $credittype, $credit, $log);
            }
        }
    }
    protected function sendTplNotice($uniacid,$touser, $template_id, $page = '', $form_id, $postdata, $emphasis_keyword = NULL) {
        load()->model('mc');
        load()->func('communication');
        $account_api = WeAccount::create($uniacid);
        $accesstoken = $account_api->getAccessToken();
        if (is_error($accesstoken)) {
            return $accesstoken;
        }
        if (empty($touser)) {
            return error(-1, '参数错误,粉丝openid不能为空');
        }
        if (empty($template_id)) {
            return error(-1, '参数错误,模板标示不能为空');
        }
        if (empty($postdata) || !is_array($postdata)) {
            return error(-1, '参数错误,请根据模板规则完善消息内容');
        }
        $data = array();
        $data['touser'] = $touser;
        $data['template_id'] = trim($template_id);
        $data['page'] = trim($page);
        $data['form_id'] = trim($form_id);
        if ($emphasis_keyword) {
            $send['emphasis_keyword'] = $emphasis_keyword;
        }
        $data['data'] = $postdata;
        $data = json_encode($data);
        $templateUrl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$accesstoken}";
        $response = ihttp_request($templateUrl, $data);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        WeUtility::logging('sendTplNotice', var_export($result, true));
        if (empty($result)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }
        return true;
    }

    private function radian($d) {
        return $d * PI / 180.0;
    }

    public function distanceBetween($longitude1, $latitude1, $longitude2, $latitude2) {
        $radLat1 = $this->radian($latitude1);
        $radLat2 = $this->radian($latitude2);
        $a = $this->radian($latitude1) - $this->radian($latitude2);
        $b = $this->radian($longitude1) - $this->radian($longitude2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * EARTH_RADIUS; //乘上地球半径，单位为公里
        $s = round($s * 10000) / 10000; //单位为公里(km)
        return $s * 1000; //单位为km
    }
}