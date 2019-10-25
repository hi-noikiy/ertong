<?php
/**
 * 模块小程序接口定义
 * 优呀精品社区  bbs.uuuya.com
 * set_error_handler(function(){})
 * set_exception_handler(function($ex){}}
 */
defined('IN_IA') or exit('Access Denied');
define('PI', 3.1415926535898);
define('EARTH_RADIUS', 6378.137);

class Amouse_Wxapp_CardModuleWxapp extends WeModuleWxapp {

    public function doPageApiGetSlide() {
        global $_W;
        $message = 'success';
        $uniacid = $_W['uniacid'];
        $nowtime = time();
        $slides = pdo_fetchall("SELECT `id`,`name`,thumb,url, 'click', 'status', 'appid', 'qrcode' FROM " . tablename('amouse_wxapp_card_slide') . " WHERE `weid` =:weid and status=0 and endtime >= $nowtime order by displayorder desc ", array(":weid" => $uniacid));
        $ret = array();
        foreach ($slides as $key => $value) {
            $slides[$key]['thumb'] = tomedia($value['thumb']);
            $slides[$key]['qrcode'] = tomedia($value['qrcode']);
        }
        $list = pdo_fetchall("SELECT `id`,`name` FROM " . tablename('amouse_wxapp_category') . " WHERE `uniacid` =:weid ORDER BY displayorder DESC , createtime DESC ", array(":weid" => $uniacid));
        foreach ($list as $key => $value) {
            $list[$key]['name'] = $value['name'];
        }
        $ret['slides'] = $slides;
        $ret['list'] = $list;
        return $this->result(0, $message, $ret);
    }

    /*
     * 获取分类
     */
    public function doPageApiGetCategory() {
        global $_W;
        $message = 'success';
        $uniacid = $_W['uniacid'];
        $list = pdo_fetchall("SELECT `id`,`name` FROM " . tablename('amouse_wxapp_category') . " WHERE `uniacid` =:weid ORDER BY displayorder DESC , createtime DESC ", array(":weid" => $uniacid));
        foreach ($list as $key => $value) {
            $list[$key]['name'] = $value['name'];
        }
        return $this->result(0, $message, $list);
    }

    public function doPageApiGetResList() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['pageIndex']));
        $psize = max(10, intval($_GPC['pageSize']));
        $category_id = intval($_GPC['category_id']);
        $contain = " WHERE `uniacid`=:weid and status=0 and audit_status=0 ";
        if ($category_id > 0) {
            $contain .= " and categoryId={$category_id} ";
        }
        $openid = $_W['openid'];
        $start = ($pindex - 1) * $psize; //vip
        $mid = pdo_fetchcolumn("SELECT `id` FROM " . tablename('amouse_wxapp_card') . "  WHERE `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $openid));
        $sql = "SELECT `id`,`uniacid`,`status`,`audit_status`,`categoryId`,`openid`,`mobile`,`username`,`weixin`,`company`,`job`,`vip`,`industry`,`desc`,`avater`,`createtime`,case when vip=1 then 1 else listorder end paixun  FROM " . tablename('amouse_wxapp_card') . $contain . " ORDER BY paixun DESC,createtime DESC  limit $start,$psize";
        $list = pdo_fetchall($sql, array(":weid" => $uniacid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_wxapp_card') . $contain, array(":weid" => $uniacid));
        $tpage = ceil($total / $psize);
        $return = array();
        $return['mid'] = $mid;
        foreach ($list as $key => $value) {
            $list[$key]['avater'] = tomedia($value['avater']);
        }
        $return['list'] = $list;
        $return['gtotal'] = $tpage;
        return $this->result(0, $sql, $return);

    }

    public function doPageApiGetSys() {
        global $_W;
        $uniacid = $_W['uniacid'];
        $set = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => $uniacid));
        $return = array();
        $set['logo'] = tomedia($set['logo']);
        $rules = unserialize($set['rule']);
        $set['share_thumb'] = tomedia($set['share_thumb']);
        $set['mp'] = tomedia($set['mp']);
        $set['rules'] = $rules;
        $return['set'] = $set;
        $account_info = pdo_get('account_wxapp', array('uniacid' => $uniacid));
        $from = $_W['fans']['openid'];
        $wxappname = $account_info['name'];
        $return['wxappname'] = empty($set['wxapp_name1']) ? "再次使用：发现-小程序-搜索 “" . $wxappname . "”" : $set['wxapp_name1'];
        $return['from'] = $from;
        return $this->result(0, '', $return);
    }

    public function doPageApiGetMyCard() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        // $res = $this->_get($uniacid, $openid);
        $cardId = intval($_GPC['cardid']);
        $fredidId = intval($_GPC['fcardid']);
        WeUtility::logging('doPageApiGetMyCard', var_export($openid, true));
        $contion = " WHERE `uniacid`=:weid and `openid`=:openid ";
        if ($cardId > 0) {
            $contion .= " and id='{$cardId}' ";
        }
        $sql = "SELECT `id`,`uniacid`,`status`,`audit_status`,`categoryId`,`openid`,`mobile`,`username`,`email`,`weixin`,`company`,`job`,`fromId`,`qq`,`industry`,`department`,`desc`,`imgs`,`vip`,`zan`,`clazz`,`view`,`collect`,`avater`,`weixinImg`,`listorder`,`qrcode`,`qrcode2`,`lat`,`lng`,`address` FROM " . tablename('amouse_wxapp_card') . "{$contion} limit 1";
        $card = pdo_fetch($sql, array(":weid" => $uniacid, ":openid" => $openid));
        if (!empty($card)) {
            if ($card['imgs']) {
                $imgs = iunserializer($card['imgs']);
                if (is_array($imgs) || is_object($imgs)) {
                    foreach ($imgs as $key => $imgid) {
                        $imgs[$key] = tomedia($imgid);
                    }
                }
            }
            if ($card['categoryId'] > 0) {
                $category = pdo_fetch("SELECT `name`,`id` FROM " . tablename('amouse_wxapp_category') . "  WHERE `uniacid`=:weid and `id`=:id limit 1", array(":weid" => $uniacid, ":id" => $card['categoryId']));
                $card['categoryName'] = $category['name'];
            }
            $card['imgs'] = $imgs;
            $card['avater'] = tomedia($card['avater']);
            $card['qrcode'] = tomedia($card['qrcode']);
            $card['qrcode2'] = tomedia($card['qrcode2']);
            $card['weixinImg'] = tomedia($card['weixinImg']);
            $card['openid'] = $openid;
            return $this->result(0, $openid, $card);
        } else {
            $card['id'] = 0;
            $card['openid'] = $openid;
            return $this->result(0, $sql, $card);
        }
    }

    //保存当前人的小程序模板消息发送码
    public function doPageApiSaveFormIds() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openId = $_W['openid'];
        $formIds = $_GPC['formIds'];;//获取formIds数组
        $formIds = str_replace("&quot;", "\"\"", $formIds);
        $formIds = str_replace("\"\"formId\"\"", "\"formId\"", $formIds);
        $formIds = str_replace("\"\"expire\"\"", "\"expire\"", $formIds);
        // $formIds = str_replace("\"\"",'',$formIds);
        $formIds2 = str_replace('""', '"', $formIds);
        WeUtility::logging('$===1111', var_export($formIds2, true));
        $jformIds = json_decode($formIds2, true);
        WeUtility::logging('$===json_decode', var_export($jformIds, true));
        foreach ($jformIds as $row) {
            if (!empty($row['formId'])) {
                pdo_insert('amouse_wxapp_tplcode', array('uniacid' => $uniacid, 'openid' => $openId, 'code' => $row['formId'], 'createtime' => $row['expire'], 'status' => 0));
            }
        }
        return $this->result(0, '', 'doPageApiSaveFormIds==' . $openId);
        // $this->_saveFormIdsArray($uniacid, $openId, $jformIds);//保存
    }

    private function _saveFormIdsArray($uniacid, $openId, $arr) {
        $res = $this->_get($uniacid, $openId);
        if ($res) {
            $new = array_merge($res, $arr);//合并数组
            return $this->_save($uniacid, $openId, $new);
        } else {
            $result = $arr;
            return $this->_save($uniacid, $openId, $result);
        }
    }

    private function _get($uniacid, $openId) {
        /*$cacheKey =  'user_formId_' . $uniacid .'_'. $openId;
        $data = cache_read($cacheKey);
        WeUtility::logging('doPageApiGetMyCard', var_export($data, true));
        if($data){
            return json_decode($data,true);
        }else{
            WeUtility::logging('_get', '111');
            return FALSE;
        }*/
        $tplcodes = pdo_fetchall("SELECT `uniacid`,`id`,`code`,`openid`,`status`,`createtime` FROM " . tablename('amouse_wxapp_tplcode') . " WHERE `uniacid`= :weid and `openid`=:openid and status=0 ", array(':weid' => $uniacid, ':openid' => $openId));
        return $tplcodes;
    }

    private function _save($uniacid, $openId, $data) {
        $cacheKey = 'user_formId_' . $uniacid . '_' . $openId;
        // return  cache_write($cacheKey, json_encode($data),60*60*24*7);
    }

    //取出用户可用的formid
    public function getFormId($uniacid, $openId) {
        $res = $this->_get($uniacid, $openId);
        if ($res) {
            if (!count($res)) {
                return FALSE;
            }
            $newData = array();
            $result = FALSE;
            for ($i = 0; $i < count($res); $i++) {
                if ($res[$i]['createtime'] > time()) {
                    $result = $res[$i]['code'];//得到一个可用的formId
                }
            }
            // $this->_save($uniacid, $openId,$newData);
            return $result;
        } else {
            return FALSE;
        }
    }

    private function processCommission($openid, $qr, $qrmember) {
        if (!empty($qr) && $fans['level_first_id'] == 0) {//开启三级返利
            pdo_update('amouse_wxapp_member', array('level_first_id' => $qrmember['id']), array('id' => $fans['id']));
            if ($fans['level_second_id'] == 0) {
                $second_member = pdo_fetch('select id,level_second_id,level_first_id,openid from ' . tablename('amouse_wxapp_member') . ' where `uniacid`=:uniacid and `id`=:id ', array(':uniacid' => $uid, ':id' => $qrmember['level_first_id']));
                pdo_update('amouse_wxapp_member', array('level_second_id' => $second_member['id']), array('id' => $fans['id']));
                if ($second_member['level_first_id'] > 0 && $fans['level_three_id'] == 0) { /*第三级*/
                    $three_member = pdo_fetch('select * from ' . tablename('amouse_wxapp_member') . ' where uniacid=:uniacid and id=:id ', array(':uniacid' => $uid, ':id' => $second_member['level_first_id']));
                    pdo_update('amouse_wxapp_member', array('level_three_id' => $three_member['id'], 'createtime' => time()), array('id' => $fans['id']));
                }
            }
        }
    }

    private function radian($d) {
        return $d * PI / 180.0;
    }

    private function distanceBetween($longitude1, $latitude1, $longitude2, $latitude2) {
        $radLat1 = $this->radian($latitude1);
        $radLat2 = $this->radian($latitude2);
        $a = $this->radian($latitude1) - $this->radian($latitude2);
        $b = $this->radian($longitude1) - $this->radian($longitude2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * EARTH_RADIUS; //乘上地球半径，单位为公里
        $s = round($s * 10000) / 10000; //单位为公里(km)
        return $s * 1000; //单位为km
    }

    //点赞 收藏
    public function doPageApiPostClick() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];//当前用户
        $friend_user_id = intval($_GPC['friend_user_id']);//好友的Id
        $view_type = $_GPC['view_type'];
        $fcard = pdo_fetch("SELECT `id`,`audit_status`,`openid`, `mobile`, `email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address`,`vip` FROM " . tablename('amouse_wxapp_card') . " WHERE `uniacid`=:weid  and id=:id limit 1", array(":weid" => $uniacid, ":id" => $friend_user_id));
        $mcard = pdo_fetch("SELECT `id`,`audit_status`,`openid`, `mobile`, `email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address`,`vip` FROM " . tablename('amouse_wxapp_card') . " WHERE `uniacid`=:weid  and openid=:openid limit 1", array(":weid" => $uniacid, ":openid" => $openid));
        if ($fcard['id'] != $mcard['id']) {
            $set = pdo_fetch("SELECT `id`,`save_tpl`,`collect_tpl` FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => $uniacid));
            $collect = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_card_history') . " WHERE from_user=:openid and cardid=:cardid and uniacid=:uniacid and sms_type=2 limit 1 ", array(':openid' => $openid, ':cardid' => $friend_user_id, ':uniacid' => $uniacid));
            if ($view_type == 'collect') {
                if (empty($collect)) {
                    $insert = array('cardid' => $friend_user_id, 'sms_type' => 2, 'uniacid' => $_W['uniacid'], 'zan_cid' => $zan['zan_cid'] + 1,
                        'from_user' => $openid, 'to_user' => $fcard['openid']);
                    pdo_insert('amouse_wxapp_card_history', $insert);
                    pdo_update('amouse_wxapp_card', 'collect=collect+1', array('id' => $friend_user_id));
                    $fcard['is_collect'] = 1;
                    $fcard['collect'] = $fcard['collect'] + 1;
                    $sendFromid = $this->getFormId($uniacid, $fcard['openid']);//获取被收藏人的formid
                    if ($sendFromid) {
                        $send['first'] = array('value' => '名片新增收藏通知', 'color' => '#000');
                        $send['keyword1'] = array('value' => $mcard['username'], 'color' => '#000');
                        $send['keyword2'] = array('value' => $mcard['company'], 'color' => '#000');
                        $send['keyword3'] = array('value' => $mcard['job'], 'color' => '#000');
                        $send['keyword4'] = array('value' => '您的名片已被收入名片册，点击即可查看对方名片', 'color' => '#000');
                        $send['keyword5'] = array('value' => $mcard['mobile'], 'color' => '#000');
                        $flag = $this->sendTplNotice($fcard['openid'], $set['collect_tpl'], 'amouse_wxapp_card/pages/card/info/info?cid=' . $mcard['id'], $sendFromid, $send, 'keyword1.DATA');
                        if ($flag) {
                            pdo_delete('amouse_wxapp_tplcode', array('code' => $sendFromid, 'uniacid' => $uniacid));
                        }
                    }
                } else {
                    pdo_update('amouse_wxapp_card', 'collect=collect-1', array('id' => $friend_user_id));
                    pdo_delete('amouse_wxapp_card_history', array('id' => $collect['id']));
                    $fcard['is_collect'] = 0;
                    $fcard['collect'] = $fcard['collect'] - 1;
                }
                if (!empty($zan)) {
                    $fcard['is_like'] = 1;
                }
            } elseif ($view_type == 'zan') {
                $zan = pdo_fetch("SELECT `id`,`uniacid`,`cardid`,`from_user`,`zan_mid`,`zan_cid`,`to_user`,`sms_type` FROM " . tablename('amouse_wxapp_card_history') . " WHERE from_user=:openid and cardid=:cardid and uniacid=:uniacid and sms_type=1 limit 1 ", array(':openid' => $openid, ':cardid' => $friend_user_id, ':uniacid' => $uniacid));
                if (empty($zan)) {
                    $fcard['is_delete'] = 0;
                    $insert = array('cardid' => $friend_user_id, 'sms_type' => 1, 'uniacid' => $_W['uniacid'], 'zan_mid' => $zan['zan_mid'] + 1,
                        'from_user' => $openid, 'to_user' => $fcard['openid']);
                    pdo_insert('amouse_wxapp_card_history', $insert);
                    pdo_update('amouse_wxapp_card', 'zan=zan+1', array('id' => $friend_user_id));
                    $fcard['is_like'] = 1;
                    $fcard['zan'] = $fcard['zan'] + 1;
                } else {
                    $fcard['is_delete'] = 1;
                    pdo_update('amouse_wxapp_card', 'zan=zan-1', array('id' => $friend_user_id));
                    pdo_delete('amouse_wxapp_card_history', array('id' => $zan['id']));
                    $fcard['is_like'] = 0;
                    $fcard['zan'] = $fcard['zan'] - 1;
                }
                if (!empty($collect)) {
                    $fcard['is_collect'] = 1;
                }
            }
            if ($fcard['imgs']) {
                if (is_array($fcard['imgs']) || is_object($fcard['imgs'])) {
                    $imgs = iunserializer($fcard['imgs']);
                    foreach ($imgs as $key => $imgid) {
                        $imgs[$key] = tomedia($imgid);
                    }
                }
            }

            $fcard['weixinImg'] = tomedia($fcard['weixinImg']);
            $fcard['imgs'] = $imgs;
            $fcard['mid'] = $mcard['id'];
            $fcard['avater'] = tomedia($fcard['avater']);
            $lat = $fcard['lat'];
            $lng = $fcard['lng'];
            if (!empty($lng) && !empty($lat)) {
                $distance = $this->distanceBetween($mcard['lng'], $mcard['lat'], $lng, $lat);
                $distance = round($distance / 1000, 1);
            } else {
                $distance = 0;
            }
            $fcard['distance'] = "离您" . $distance . "Km";

            return $this->result(0, '111222', $fcard);
        } else {
            return $this->result(0, '', $fcard);
        }
    }

    public function doPageApiGetTop() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $type = $_GPC['type'];
        $pindex = max(1, intval($_GPC['pageIndex']));
        $psize = max(10, intval($_GPC['psize']));
        $start = ($pindex - 1) * $psize;
        if ($type == 'view') {
            $orderby = " ORDER BY view DESC ";//这边查
        } elseif ($type == 'zan') {
            $orderby = " ORDER BY zan DESC ";
        } elseif ($type == 'collect') {
            $orderby = " ORDER BY collect DESC ";
        }
        $sql = "SELECT `id`,`openid`, `mobile`, `status`,`audit_status`,`email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address` FROM " . tablename('amouse_wxapp_card') . " WHERE `uniacid` =:weid " . $orderby . " limit $start,$psize";
        $list = pdo_fetchall($sql, array(":weid" => $uniacid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_wxapp_card') . "  WHERE `uniacid` =:weid  ", array(":weid" => $uniacid));
        $tpage = ceil($total / $psize);
        $return = array();
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                $list[$key]['avater'] = empty($value['avater']) ? tomedia($value['weixinImg']) : tomedia($value['avater']);
            }
            $return['list'] = $list;
            $return['gtotal'] = $tpage;
            return $this->result(0, '', $return);
        } else {
            return $this->result(1, '', 0);
        }

    }

    public function doPageApiGetMyList() {
        global $_GPC, $_W;
        $uniacid = intval($_W['uniacid']);
        $type = $_GPC['type'];
        $openid = $_W['openid'];//当前用户
        $pindex = max(1, intval($_GPC['pageIndex']));
        $psize = max(10, intval($_GPC['psize']));
        $start = ($pindex - 1) * $psize;
        $where = " and h.uniacid =:weid and h.to_user=:to_user ";
        if ($type == 'view') {
            $where .= " and h.sms_type=0  ";//这边查
        } elseif ($type == 'zan') {
            $where .= " and h.sms_type=1 ";
        } elseif ($type == 'collect') {
            $where .= " and h.sms_type=2 ";
        }
        $sql = "SELECT DISTINCT(c.username), c.* FROM " . tablename('amouse_wxapp_card_history') . " as h ," . tablename('amouse_wxapp_card') . " as c where h.from_user=c.openid and c.openid<>'' " . $where;
        $list = pdo_fetchall($sql, array(":weid" => $uniacid, ":to_user" => $openid));
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                $list[$key]['weixinImg'] = tomedia($value['weixinImg']);
                $list[$key]['avater'] = tomedia($value['avater']);
            }
            return $this->result(0, $sql, $list);
        } else {
            return $this->result(1, $sql, 0);
        }
    }

    public function doPageApiGetMyHolder() {
        global $_GPC, $_W;
        $uniacid = intval($_W['uniacid']);
        $openid = $_W['openid'];//当前用户
        $key = $_GPC['s_key'];
        if (!empty($key)) {
            $where = " AND card.username LIKE '%{$key}%' or card.company LIKE '%{$key}%'  ";
        }
        $sql = "SELECT card.* from " . tablename('amouse_wxapp_card') .
            " as card where card.openid in ( SELECT h.to_user  FROM " . tablename('amouse_wxapp_card_history') .
            "  as h ," . tablename('amouse_wxapp_card') . "  as c where h.from_user=c.openid and h.uniacid =:weid and h.from_user=:to_user and h.sms_type=2 )" . $where;
        $list = pdo_fetchall($sql, array(":weid" => $uniacid, ":to_user" => $openid));
        foreach ($list as $key => $value) {
            $list[$key]['weixinImg'] = tomedia($value['weixinImg']);
            $list[$key]['avater'] = tomedia($value['avater']);
        }
        return $this->result(0, $sql, $list);
    }

    public function doPageApiSendMsg() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $_mobile_val = trim($_GPC['_mobile_val']);
        $set = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => $uniacid));
        if (empty($set)) {
            return $this->result(1, '短信业务配置不正确', $_mobile_val);
        }
        if ($_mobile_val == '') {
            return $this->result(1, '请输入手机号', "");
        } elseif (!preg_match("/(^1[3|4|5|7|8][0-9]{9}$)/", $_mobile_val)) {
            return $this->result(1, '您输入的手机号格式错误', "");
        }
        $card = pdo_fetch("select `id`,`openid`, `mobile` from " . tablename('amouse_wxapp_card') . " where `mobile`=:mobile and uniacid=:weid limit 1", array(":mobile" => $_mobile_val, ":weid" => $uniacid));
        if (!empty($card)) {
            return $this->result(1, '此号码已经存在', $_mobile_val);
        }
        $code = random(6, true);
        $userVerifyCode = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_sms') . " WHERE `uniacid`= :weid and `mobile`=:mobile limit 1 ", array(':weid' => $uniacid, ':mobile' => $_mobile_val));
        if (!empty($userVerifyCode)) {
            if ($userVerifyCode['total'] > 3) {
                return $this->result(1, '您的操作过于频繁,请稍后再试', '');
            }
            $mins = intval((time() - $userVerifyCode['createtime']) % 86400 % 3600 / 60);
            if ($mins < 1) {
                return $this->result(1, '您的操作过于频繁,请稍后再试', $mins);
            }
            $record['total'] = $userVerifyCode['total'] + 1;
            $record['createtime'] = time();
            pdo_update('amouse_wxapp_sms', $record, array('id' => $userVerifyCode['id']));
        } else {
            $data = array('uniacid' => $uniacid, 'code' => $code, 'mobile' => $_mobile_val, 'createtime' => time(),);
            pdo_insert('amouse_wxapp_sms', $data);
        }

        include_once IA_ROOT . '/addons/amouse_wxapp_card/AliyunSms.class.php';
        $sms = new \AliyunSms();
        $sms_param = "{\"number\":\"$code\"}";
        if ($set['sms_type'] == 1) {
            $acsResponse = $sms->_sendNewDySms($_mobile_val, $set['sms_user'], $set['sms_secret'], $set['sms_free_sign_name'], $set['reg_sms_code'], $sms_param, $code);
        } else { //阿里大于老接口
            $acsResponse = $sms->_sendAliDaYuSms($_mobile_val, $set['sms_user'], $set['sms_secret'], $set['sms_free_sign_name'], $set['reg_sms_code'], $sms_param);
        }
        return $this->result(0, '', $code);
    }

    public function doPageApiGetCardById() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        $cardId = intval($_GPC['_card_id']);
        $contion = " WHERE `uniacid`=:weid   ";
        if ($cardId > 0) {
            $contion .= " and id='{$cardId}' ";
        }
        $sql = "SELECT `id`,`openid`, `mobile`, `email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address`,`vip`,`qrcode`,`status`,`audit_status`,`qrcode2` FROM " . tablename('amouse_wxapp_card') . "{$contion} limit 1";
        $fcard = pdo_fetch($sql, array(":weid" => $uniacid));
        $mcard = pdo_fetch("SELECT `id`,`openid`, `mobile`, `email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`status`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address`,`audit_status`,`vip` FROM " . tablename('amouse_wxapp_card') . " WHERE `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $openid));
        if ($mcard['id'] != $fcard['id']) {
            $zan = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('amouse_wxapp_card_history') . " WHERE from_user=:openid and cardid=:cardid and uniacid=:uniacid and sms_type=1 limit 1 ", array(':openid' => $openid, ':cardid' => $cardId, ':uniacid' => $uniacid));
            $collect = pdo_fetchcolumn("SELECT count(id) FROM " . tablename('amouse_wxapp_card_history') . " WHERE from_user=:openid and cardid=:cardid and uniacid=:uniacid and sms_type=2 limit 1 ", array(':openid' => $openid, ':cardid' => $cardId, ':uniacid' => $uniacid));
            $view = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_card_history')." WHERE from_user=:openid and cardid=:cardid and uniacid=:uniacid and sms_type=0 limit 1 ", array(':openid' => $openid, ':cardid' => $cardId, ':uniacid' => $uniacid));
            if (empty($view)) {
                $insert = array('cardid' => $cardId, 'sms_type' => 0, 'uniacid' => $_W['uniacid'], 'from_user' => $openid, 'to_user' => $fcard['openid']);
                pdo_insert('amouse_wxapp_card_history', $insert);
                pdo_update('amouse_wxapp_card', array('view' => $fcard['view'] + 1), array("id" => $cardId));
                $fcard['view'] = $fcard['view'] + 1;
            }
            $is_zan = $zan > 0 ? 1 : 0;
            $is_collect = $collect > 0 ? 1 : 0;
            $fcard['is_like'] = $is_zan;
            $fcard['is_collect'] = $is_collect;
        }
        if ($fcard['imgs']) {
            $imgs = iunserializer($fcard['imgs']);
            if (is_array($imgs) || is_object($imgs)) {
                foreach ($imgs as $key => $imgid) {
                    $imgs[$key] = tomedia($imgid);
                }
            }
        }
        $fcard['weixinImg'] = tomedia($fcard['weixinImg']);
        $fcard['imgs'] = $imgs;
        if (empty($fcard['qrcode'])) {
            $fcard['qrcode'] = tomedia($fcard['qrcode']);
        }
        if (empty($fcard['qrcode2'])) {
            $fcard['qrcode2'] = tomedia($fcard['qrcode2']);
        }
        $lng = $fcard['lng'];
        $lat = $fcard['lat'];
        if (!empty($lng) && !empty($lat)) {
            $distance = $this->distanceBetween($mcard['lng'], $mcard['lat'], $lng, $lat);
            $distance = round($distance / 1000, 1);
            WeUtility::logging('$distance-m=', var_export($distance, true));
        } else {
            $distance = 0;
        }
        $fcard['distance'] = "离您" . $distance . "Km";
        $fcard['avater'] = tomedia($fcard['avater']);
        $fcard['mid'] = $mcard['id'];//我自己的id
        return $this->result(0, $sql, $fcard);
    }
    //提交名片
    public function doPageApiPostMyCard() {
        global $_GPC, $_W;
        $from = $_W['fans']['openid'];
        $uniacid = $_W['uniacid'];
        $nickname = $_W['fans']['nickname'];
        $avatar = empty($_GPC['avatarUrl']) ? $_W['fans']['avatar'] : $_GPC['avatarUrl'];
        $apiCardid = $_GPC['cardid'];
        $set = pdo_fetch("SELECT `id`,`save_tpl`,`collect_tpl`,`public_credit`,`enable`,`qqmap_ak`,`mobile_verify_status`,`is_public` FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => $uniacid));
        $images = $_GPC['imgs'];
        $imgs = explode('|', $images);
        $down_images = array();
        foreach ($imgs as $imgid) {
            $down_images[] = $imgid;
        }
        $images = iserializer($down_images);
        $username = empty($_GPC['username']) ? $nickname : trim($_GPC['username']);
        $data = array('uniacid' => $uniacid, 'openid' => $from,
            'mobile' => trim($_GPC['mobile']), 'avater' => $avatar,
            'email' => $_GPC['email'], 'job' => $_GPC['job'], 'username' => $username,
            'weixin' => $_GPC['weixin'], 'company' => $_GPC['company'],
            'categoryId' => intval($_GPC['categoryId']), 'address' => $_GPC['address'],
            'desc' => $_GPC['desc'], 'weixinImg' => $_GPC['weixinImg'], 'imgs' => $images
        );
        $fromid = trim($_GPC['fromId']);
        if ($apiCardid > 0) {
            $card = pdo_fetch("select `id`,`openid`, `mobile`, `email`, `audit_status`,`weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`audit_status`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address` from " . tablename('amouse_wxapp_card') . " where id=:id and uniacid=:weid limit 1", array(":id" => $apiCardid, ":weid" => $uniacid));
            if (empty($_GPC['weixinImg'])) {
                $data['weixinImg'] = $card['weixinImg'];
            }
            if ($_GPC['imgs']) {
                $data['imgs'] = $images;
            } else {
                $data['imgs'] = $card['imgs'];
            }
            if (empty($_GPC['categoryId'])) {
                $data['categoryId'] = $card['categoryId'];
            }
            if ($card['address'] != $_GPC['address'] && $set['enable'] == 1 && $set['qqmap_ak']) {
                $address = trim($_GPC['address']);
                $url = "http://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key=" . $set['qqmap_ak'];
                load()->func('communication');
                $ret = ihttp_request($url);
                $content = @json_decode($ret['content'], true);
                if ($content['status'] == 0) {
                    $result = $content['result'];
                    $lng = $result['location']['lng'];
                    $lat = $result['location']['lat'];
                    $data['lat'] = $lat;
                    $data['lng'] = $lng;
                }
            } else {
                $data['lat'] = $_GPC['lat'];
                $data['lng'] = $_GPC['lng'];
            }
            load()->func('file');

            $msg = "发布成功";
            if ($set && $set['is_public'] == 0) {//开启审核
                $data['audit_status'] = 1;
                $msg .= ",请等待管理员审核";
            }
            pdo_update('amouse_wxapp_card', $data, array('id' => $apiCardid));
            $account_info = pdo_get('account_wxapp', array('uniacid' => $uniacid));
            $data['imgs'] = empty($images) ? $card['imgs'] : $images;
            $data['weixinImg'] = empty($_GPC['weixinImg']) ? $card['weixinImg'] : $_GPC['weixinImg'];
            $send['first'] = array('value' => '名片保存成功通知', 'color' => '#000');
            $send['keyword1'] = array('value' => $data['username'], 'color' => '#000');
            $send['keyword2'] = array('value' => $data['company'], 'color' => '#000');
            $send['keyword3'] = array('value' => $data['job'], 'color' => '#000');
            $send['keyword4'] = array('value' => $data['mobile'], 'color' => '#000');
            $send['keyword5'] = array('点击【' . $account_info['name'] . '】进行查看', 'color' => '#000');
            $this->sendTplNotice($from, $set['save_tpl'], 'amouse_wxapp_card/pages/card/home/home', $fromid, $send, 'keyword1.DATA');
        } else {
            $data['listorder'] = 0;
            $data['status'] = 0;
            if ($set['enable'] == 1 && $set['qqmap_ak']) {
                $address=trim($_GPC['address']);
                $url = "http://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key=" . $set['qqmap_ak'];
                load()->func('communication');
                $ret = ihttp_request($url);
                $content = @json_decode($ret['content'], true);
                if ($content['status'] == 0) {
                    $result = $content['result'];
                    $lng = $result['location']['lng'];
                    $lat = $result['location']['lat'];
                    $data['lat'] = $lat;
                    $data['lng'] = $lng;
                }
            } else {
                $data['lat'] = $_GPC['lat'];
                $data['lng'] = $_GPC['lng'];
            }
            $msg = "发布成功";
            if ($set && $set['is_public'] == 0) {//开启审核
                $data['audit_status'] = 1;
                $msg .= ",请等待管理员审核";
            }
            if ($set && $set['mobile_verify_status'] == 1) {
                $phone_code = trim($_GPC['phone_code']);
                $userVerifyCode = pdo_fetch("SELECT `id`,`code`,`createtime`,`status` FROM " . tablename('amouse_wxapp_sms') . " WHERE `uniacid`= :uniacid and `mobile`=:mobile limit 1", array(':uniacid' => $uniacid, ':mobile' => trim($_GPC['mobile'])));
                if (!empty($userVerifyCode)) {
                    $mins = intval((time() - $userVerifyCode['createtime']) % 86400 % 3600 / 60);
                    if ($mins > 30) {
                        //  return $this->result(1, '此验证码已经失效', $mins);
                    }
                    if ($userVerifyCode['status'] == 1) {
                        return $this->result(1, '此验证码已经被使用', $userVerifyCode['status']);
                    }
                    if ($userVerifyCode['code'] == $phone_code) {
                        pdo_update('amouse_wxapp_sms', array('status' => 1), array('id' => $userVerifyCode['id']));
                        pdo_insert('amouse_wxapp_card', $data);
                        $apiCardid = pdo_insertid();
                        $send['first'] = array('value' => '名片保存成功通知', 'color' => '#000');
                        $send['keyword1'] = array('value' => $data['username'], 'color' => '#000');
                        $send['keyword2'] = array('value' => $data['company'], 'color' => '#000');
                        $send['keyword3'] = array('value' => $data['job'], 'color' => '#000');
                        $send['keyword4'] = array('value' => $data['mobile'], 'color' => '#000');
                        $this->sendTplNotice($from, $set['save_tpl'], 'amouse_wxapp_card/pages/card/home/home', $fromid, $send, 'keyword1.DATA');

                    } else {
                        return $this->result(1, '验证码不正确，请确认验证', $userVerifyCode['status']);
                    }
                } else {
                    return $this->result(1, '您输入的手机号码不正确，请确认输入', $phone_code);
                }
            } else {
                pdo_insert('amouse_wxapp_card', $data);
                $apiCardid = pdo_insertid();
                if ($set['public_credit'] > 0) {//赠送积分
                    $this->setFansCredit($from, 'credit1', $set['public_credit'], $set['limit_credit'], "发布信息赠送积分" . $set['public_credit']);
                }
                $send['first'] = array('value' => '名片保存成功通知', 'color' => '#000');
                $send['keyword1'] = array('value' => $data['username'], 'color' => '#000');
                $send['keyword2'] = array('value' => $data['company'], 'color' => '#000');
                $send['keyword3'] = array('value' => $data['job'], 'color' => '#000');
                $send['keyword4'] = array('value' => $data['mobile'], 'color' => '#000');
                $this->sendTplNotice($from, $set['save_tpl'], 'amouse_wxapp_card/pages/card/home/home', $fromid, $send, 'keyword1.DATA');
            }
            $poster = pdo_fetch("SELECT `id`,`uniacid`,`bg`,`data` from " . tablename('wxapp_card_poster') . "  where `uniacid`=:weid limit 1 ", array(":weid" => $uniacid));
            if ($poster && $poster['bg']) {
                $poster['cardid'] = $apiCardid;
                $poster['mobile'] = $_GPC['mobile'];
                $poster['company'] = $_GPC['company'];
                $poster['uniacid'] = $uniacid;
                $poster['from_user'] = $from;
                $poster['nickname'] = $_GPC['username'];
                $poster['avatar'] = tomedia($avatar);
                $qrcode = $this->makeActivityCode('m:card,cid:' . $apiCardid, "amouse_wxapp_card/pages/card/index/index");
                $ret = createCodeUnlimit($poster, $qrcode);
                if ($ret['code'] == 1) {
                    pdo_update('amouse_wxapp_card', array('qrcode' => $ret['qr_img']), array('id' => $apiCardid));
                }
            }
        }
        return $this->result(0, $msg, $apiCardid);
    }

    public function doPageApiModifyAvatar() {
        global $_GPC, $_W;
        $from = $_W['fans']['openid'];
        $uniacid = $_W['uniacid'];
        $avatar = $_GPC['avatarUrl'];
        $apiCardid = $_GPC['cardid'];
        if ($apiCardid > 0) {
            $data['avatar'] = $avatar;
            pdo_update('amouse_wxapp_card', $data, array('id' => $apiCardid));
        }
        return $this->result(0, '', $apiCardid);
    }

    //更换号码
    public function doPageApiPostChangeMobile() {
        global $_GPC, $_W;
        $uniacid = intval($_W['uniacid']);
        $apiCardid = intval($_GPC['cardid']);
        if ($apiCardid > 0) {
            $data = array('mobile' => trim($_GPC['mobile']));
            $mobile_verify_status = pdo_fetchcolumn("SELECT `mobile_verify_status` FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => $uniacid));
            if ($mobile_verify_status && $mobile_verify_status == 1) {
                $phone_code = trim($_GPC['phone_code']);
                $userVerifyCode = pdo_fetch("SELECT `id`,`code`,`createtime`,`status` FROM " . tablename('amouse_wxapp_sms') . " WHERE `uniacid`= :uniacid and `mobile`=:mobile limit 1 ", array(':uniacid' => $uniacid, ':mobile' => trim($_GPC['mobile'])));
                if (!empty($userVerifyCode)) {
                    $mins = intval((time() - $userVerifyCode['createtime']) % 86400 % 3600 / 60);
//                    if ($mins > 30) {
//                        return $this->result(1, '此验证码已经失效', $mins);
//                    }
                    if ($userVerifyCode['status'] == 1) {
                        return $this->result(1, '此验证码已经被使用', $userVerifyCode['status']);
                    }
                    if ($userVerifyCode['code'] == $phone_code) {
                        pdo_update('amouse_wxapp_sms', array('status' => 1), array('id' => $userVerifyCode['id']));
                        pdo_update('amouse_wxapp_card', $data, array('id' => $apiCardid));
                    } else {
                        return $this->result(1, '验证码不正确，请确认验证', $userVerifyCode['status']);
                    }
                } else {
                    return $this->result(1, '手机号码不正确，请确认验证', $phone_code);
                }
            } else {
                pdo_update('amouse_wxapp_card', $data, array('id' => $apiCardid));
            }
            return $this->result(0, '', $apiCardid);
        } else {
            return $this->result(1, '', $apiCardid);
        }
    }

    public function doPageApiPostUser() {
        global $_GPC, $_W;
        $from = $_W['fans']['openid'];
        $uniacid = intval($_W['uniacid']);
        $uid = intval($_GPC['uid']);
        $data = array('realname' => trim($_GPC['realname']), 'mobile' => trim($_GPC['mobile']),
            'address' => trim($_GPC['address']), 'openid' => $from, 'status' => 0, 'sex' => trim($_GPC['sex']),
            'desc' => trim($_GPC['desc'])
        );
        if ($uid > 0) {
            pdo_update('amouse_wxapp_member', $data, array('id' => $uid));
            return $this->result(0, '', $uid);
        } else {
            $data['uniacid'] = $uniacid;
            $data['createtime'] = time();
            pdo_insert('amouse_wxapp_member', $data);
            $uid = pdo_insertid();
            return $this->result(0, '', $uid);
        }
    }

    public function doPageApiGetUserInfo() {
        global $_W,$_GPC;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        $member = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_member') . " WHERE `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $openid));
        $data = array('realname' => trim($_GPC['realname']), 'mobile' => trim($_GPC['mobile']),
            'address' => trim($_GPC['address']), 'openid' => $from, 'status' => 0, 'sex' => trim($_GPC['sex']),
            'desc' => trim($_GPC['desc'])
        );
        if (!empty($member)) {
            pdo_update('amouse_wxapp_member', $data, array('id' => $uid));
            return $this->result(0, '', $member);
        } else {
            $data['uniacid'] = $uniacid;
            $data['createtime'] = time();
            pdo_insert('amouse_wxapp_member', $data);
            $uid = pdo_insertid();
            return $this->result(0, '', $member);
        }
    }

    //支付
    public function doPageApiWxPay() {
        global $_GPC, $_W;
        $price = $_GPC['top_amount'];
        $top_day = intval($_GPC['top_day']);
        $formid = $_GPC['formid'];
        $paytype = 1;
        $uniacid = $_W['uniacid'];
        $from = $_W['openid'];
        $card = pdo_fetch("SELECT `id`,`openid`, `mobile`, `email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address` FROM " . tablename('amouse_wxapp_card') . "where `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $from));
        if (!empty($card)) {
            $orderData = array('uniacid' => $uniacid, 'openid' => $from, 'module' => $this->modulename,
                'ordersn' => date('ymd') . sprintf("%04d", $_W['fans']['id']) . random(4, 1),
                'status' => 0, 'paytype' => $paytype, 'price' => $price, 'top_day' => $top_day,
                'createtime' => TIMESTAMP, 'formid' => $formid,
            );
            pdo_insert('amouse_wxapp_order', $orderData);

            $orderId = pdo_insertid();
            $orderPayData = array(
                'tid' => intval($orderId),
                'user' => $from, 'fee' => $orderData['price'],
                'title' => '置顶' . $orderData['top_day'] . '-付款' . $orderData['price'],
            );
            $pay_params = $this->pay($orderPayData);
            $pay_params['orderid'] = $orderId;
            if (is_error($pay_params)) {
                return $this->result(0, '', $pay_params);
            }
            $prepay_id = str_replace("prepay_id=", "", $pay_params['package']);
            pdo_update('amouse_wxapp_order', array('prepay_id' => $prepay_id), array('id' => $orderId));

            $this->_saveFormIdsArray($uniacid, $from, $formid);//保存

            return $this->result(0, '', $pay_params);
        } else {
            return $this->result(1, '您还没发布名片，不能支付', '');
        }
    }

    public function payResult($params) {
        global $_W;
        $orderData = array('status' => $params['result'] == 'success' ? 1 : 0);
        if ($params['type'] == 'wechat') {
            $orderData['transid'] = $params['tag']['transaction_id'];
        }
        $order = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_order') . " WHERE `id`= :id AND `uniacid`= :weid ", array(':id' => $params['tid'], ':weid' => intval($_W['uniacid'])));
        WeUtility::logging('payResult==', var_export($params, true));
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            if ($params['fee'] == $order['price']) {
                pdo_update('amouse_wxapp_order', $orderData, array('id' => $params['tid']));
                $openid = $order['openid'];
                $set = pdo_fetch("select `adv_fee`,`zan_tpl` from " . tablename('amouse_wxapp_sysset') . ' where `weid`=:uniacid limit 1', array(':uniacid' => $uniacid));

                if ($order['paytype'] == 1) {
                    $top_day = $order['top_day'];
                    $card = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_card') . " WHERE `openid` = :id AND `uniacid` = :weid ", array(':id' => $openid, ':weid' => intval($_W['uniacid'])));
                    if ($card['createtime'] >= time()) {//延时
                        $nextWeek = $card['createtime'] + ($top_day * 24 * 60 * 60);
                    } else {
                        $nextWeek = TIMESTAMP + ($top_day * 24 * 60 * 60);
                    }
                    $data['createtime'] = $nextWeek;
                    $data['vip'] = 1;//置顶标志
                    pdo_update('amouse_wxapp_card', $data, array('id' => $card['id']));

                    if (!empty($order['formid'])) {
                        $end_time = date('Y/m/d H:i:s', $nextWeek);
                        $send['first'] = array('value' => '会员等级变更通知', 'color' => '#000');
                        $send['keyword1'] = array('value' => '普通会员', 'color' => '#000');
                        $send['keyword2'] = array('value' => 'VIP会员', 'color' => '#000');
                        $send['keyword3'] = array('value' => $end_time, 'color' => '#000');
                        $send['keyword4'] = array('value' => '您的会员等级已升级', 'color' => '#000');
                        $send['keyword5'] = array('value' => '赶紧去使用VIP功能吧。', 'color' => '#000');
                        $this->sendTplNotice($card['openid'], $set['zan_tpl'], 'amouse_wxapp_card/pages/cooperation/cooperation', $order['formid'], $send, 'keyword1.DATA');
                    }
                } else if ($order['paytype'] == 6) {//代理等级
                }if ($order['paytype'] == 5) {//广告
                    $adv = pdo_fetch("SELECT `id`,`endtime`  FROM " . tablename('amouse_wxapp_card_slide') . " WHERE `uniacid`=:weid and id=:id limit 1", array(":weid" => $_W['uniacid'], ':id' => $order['house_id']));
                    $endtime = $adv['endtime'] + ($set['adv_day'] * 24 * 60 * 60);
                    pdo_update('amouse_wxapp_card_slide', array('endtime' => $endtime, 'status' => 0), array('id' => $adv['id']));
                }

            }
        }
    }

    //是否禁用
    public function doPageApiPostStatus() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        $status = trim($_GPC['sval']);
        $card = pdo_fetch("SELECT `id`,`openid`,`mobile`, `email`, `status`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address` FROM " . tablename('amouse_wxapp_card') . "  WHERE `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $openid));
        if (!empty($card)) {
            $sql = "UPDATE " . tablename('amouse_wxapp_card') . " SET status=$status where `id`={$card['id']} ";
            $res = pdo_query($sql);
            return $this->result(0, $sql, $card);
        } else {
            return $this->result(0, '', $openid);
        }
    }

    //修改模板
    public function doPageApiPostTpl() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $cardId = intval($_GPC['cardid']);
        $tpl = trim($_GPC['tpl']);
        $card = pdo_fetch("SELECT `id`,`openid`, `mobile`, `status`,`email`, `weixin`,`weixinImg`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address` FROM " . tablename('amouse_wxapp_card') . "WHERE `uniacid`=:weid and id=:id limit 1", array(":weid" => $uniacid, ":id" => $cardId));
        if (!empty($card)) {
            pdo_update('amouse_wxapp_card', array('clazz' => $tpl), array('id' => $card['id']));
            return $this->result(0, '', $card);
        } else {
            return $this->result(0, '', $cardId);
        }
    }

    public function doPageApiSendLinkMsg() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        load()->func('communication');
        $openId = $_W['openid'];
        /*$set = pdo_fetch("SELECT `wxapp_url1`,`wxapp_name2`,`wxapp_url2` FROM " . tablename('amouse_wxapp_sysset') . " WHERE `uniacid`= :weid  limit 1 ", array(':weid' => $uniacid));
        $account_api = WeAccount::create();
        $accesstoken = $account_api->getAccessToken();
        if (is_error($accesstoken)) {
            return $accesstoken;
        }
        if (empty($openId)) {
            return error(-1, '参数错误,粉丝openid不能为空');
        }
        $post = '{"touser":"' . $openId . '","msgtype":"link","link":{"title":"' . $set['wxapp_name2'] . '","description":"' . $set['wxapp_name2'] . '","url":"' . $set['wxapp_url2'] . '","thumb_url":"' . tomedia($set['wxapp_url1']) . '"}}';
        $customUrl = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accesstoken}";
        $response = ihttp_request($customUrl, $post);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty($result)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }*/
        return $this->result(0, '', $openId);
    }

    protected function sendTplNotice($touser, $template_id, $page = '', $form_id, $postdata, $emphasis_keyword = NULL) {
        load()->func('communication');
        $account_api = WeAccount::create();
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

    public function doPageApiPostAdv() {
        global $_GPC, $_W;
        $from = $_W['fans']['openid'];
        $uniacid = $_W['uniacid'];
        $logo = $_GPC['logo'];
        $title = trim($_GPC['title']);
        $formid = trim($_GPC['formid']);
        $outlink = trim($_GPC['outlink']);
        $data = array('weid' => $uniacid, 'openid' => $from, 'endtime' => time(),
            'title' => $title, 'status' => 1, 'thumb' => $logo, 'url' => $outlink
        );
        $set = pdo_fetch("select `adv_fee` from " . tablename('amouse_wxapp_sysset') . ' where `weid`=:uniacid limit 1', array(':uniacid' => $uniacid));
        pdo_insert('amouse_wxapp_card_slide', $data);
        $apiAdvid = pdo_insertid();
        if ($set['adv_fee'] > 0) {//开启支付
            $orderData = array('uniacid' => $uniacid, 'openid' => $from, 'module' => $this->modulename,
                'ordersn' => date('ymd') . sprintf("%04d", $_W['fans']['id']) . random(4, 1),
                'status' => 0, 'paytype' => 5, 'price' => $set['adv_fee'], 'top_day' => 0,
                'createtime' => TIMESTAMP, 'house_id' => $apiAdvid,
            );
            pdo_insert('amouse_wxapp_order', $orderData);
            $orderId = pdo_insertid();
            $orderPayData = array(
                'tid' => intval($orderId),
                'user' => $from, 'fee' => $set['adv_fee'],
                'title' => '商家自费广告-付款' . $set['adv_fee'],
            );
            $pay_params = $this->pay($orderPayData);
            $pay_params['orderid'] = $orderId;
            if (is_error($pay_params)) {
                return $this->result(0, '', $pay_params);
            }
            $prepay_id = str_replace("prepay_id=", "", $pay_params['package']);
            pdo_update('amouse_wxapp_order', array('prepay_id' => $prepay_id), array('id' => $orderId));
            return $this->result(0, '', $pay_params);
        } else {
            return $this->result(0, '', $apiAdvid);
        }
    }

    public function doPageWxUpload() {
        global $_W;
        $path = ATTACHMENT_ROOT . 'images/';
        load()->func('file');
        $pathinfo = pathinfo($_FILES['file']['name']);
        $exename = strtolower($pathinfo['extension']);
        if ($exename != 'png' && $exename != 'jpg' && $exename != 'gif') {
            //return $this->result(1, '不允许的扩展名', $exename);
        }
        if (!empty($_FILES['file'])) {//获取扩展名
            $tmpP = $_FILES["file"]["tmp_name"];
            if ($tmpP) {
                $p = "jpeg";
                $filename = $_W['uniacid'] . '/' . date('Y/m/');
                if (!is_dir($path . $filename)) {
                    mkdirs(dirname($path . $filename));
                    mkdir($path . $filename);
                }
                $filename = $filename . file_random_name($filename, $p);
                $uploadfile = $path . $filename;
                $fp = $uploadfile;
                if (move_uploaded_file($tmpP, $fp)) {//保存文件
                    $url = 'images/' . $filename;
                    if (!empty($_W['setting']['remote']['type'])) { // 判断系统是否开启了远程附件
                        $remotestatus = file_remote_upload($url); //上传图片到远程
                        if (is_error($remotestatus)) {
                            file_delete($url);
                            return $this->result(0, '远程附件上传失败，请检查配置并重新上传', $url);
                        } else {
                            file_delete($url);
                            $url = tomedia($url, false);  // 远程图片的访问URL
                        }
                    }
                    return $this->result(0, '', $url);
                }
            }
        } else {
            return $this->result(1, '服务器出问题了', '');
        }
    }

    private function makeActivityCode($scene, $page) {
        $code = $this->getWxAcodeunlimit($scene, $page);
        if (is_error($code)) {
            return $code;
        }
        $path = $this->fileSave($code, 'jpg');
        return $path;
    }

    private function getWxAcodeunlimit($scene, $page, $width = '430', $option = array()) {
        if (!preg_match('/[0-9a-zA-Z\!\#\$\&\'\(\)\*\+\,\/\:\;\=\?\@\-\.\_\~]{1,32}/', $scene)) {
            return error(1, '场景值不合法');
        }
        load()->func('communication');
        $account_api = WeAccount::create();
        $accesstoken = $account_api->getAccessToken();
        if (is_error($accesstoken)) {
            return $accesstoken;
        }
        $data = array(
            'scene' => $scene,
            'width' => intval($width),
        );
        if (!empty($data['auto_color'])) {
            $data['auto_color'] = intval($data['auto_color']);
        }
        if (!empty($option['line_color'])) {
            $data['line_color'] = array(
                'r' => $option['line_color']['r'],
                'g' => $option['line_color']['g'],
                'b' => $option['line_color']['b'],
            );
            $data['auto_color'] = false;
        }
        $data['page'] = $page;
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $accesstoken;
        WeUtility::logging('doPageApiCreateImage——requestWxappApi', var_export($data, true));
        $response = $this->requestWxappApi($url, json_encode($data));
        if (is_error($response)) {
            return $response;
        }
        return $response['content'];
    }

    protected function requestWxappApi($url, $post = '') {
        load()->func('communication');
        $response = ihttp_request($url, $post);
        $result = @json_decode($response['content'], true);
        if (is_error($response)) {
            return error($result['errcode'], "访问公众平台接口失败, 错误详情: {$this->errorCode($result['errcode'])}");
        }
        if (empty($result)) {
            return $response;
        } elseif (!empty($result['errcode'])) {
            return error($result['errcode'], "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
        }
        return $result;
    }

    //菜单上生成推广二维码
    public function doPageApiCreateImage() {
        global $_W;
        load()->func('file');
        ignore_user_abort(true);
        require_once IA_ROOT . "/addons/amouse_wxapp_card/inc/common.php";
        $uniacid = $_W["uniacid"];
        $openid = $_W['openid'];
        $card = pdo_fetch("SELECT `id`,`openid`, `mobile`, `email`, `weixin`,`qrcode`, `company`, `job`,`qq`,`industry`,`department`,`desc`,`imgs`,`zan`,`view`,`collect`,`avater`,`username`,`clazz`,`lat`,`lng`,`address` FROM " . tablename('amouse_wxapp_card') . " WHERE `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $openid));
        if (empty($card['qrcode'])) {
            $poster = pdo_fetch("SELECT `id`,`uniacid`,`bg`,`data` from " . tablename('wxapp_card_poster') . "  where `uniacid`=:weid limit 1 ", array(":weid" => $uniacid));
            $poster['cardid'] = $card['id'];
            $poster['mobile'] = $card['mobile'];
            $poster['company'] = $card['company'];
            $poster['uniacid'] = $uniacid;
            $poster['from_user'] = $card['openid'];
            $poster['nickname'] = $card['username'];
            $poster['avatar'] = tomedia($card['avater']);
            $qrcode = $this->makeActivityCode('m:card,cid:' . $card['id'], "amouse_wxapp_card/pages/card/index/index");
            $ret = createCodeUnlimit($poster, $qrcode);
            if ($ret['code'] == 1) {
                $qrcode = $ret['qr_img'];
                pdo_update('amouse_wxapp_card', array('qrcode' => $ret['qr_img']), array('id' => $card['id']));
            } else {
                return $this->result(1, '创建二维码出错了', $ret['qr_img']);
            }
        } else {
            $qrcode = tomedia($card['qrcode']);
        }
        return $this->result(0, '', $qrcode);
    }

    public function doPageApiCreateQrImage() {
        global $_W, $_GPC;
        load()->func('file');
        ignore_user_abort(true);
        require_once IA_ROOT . "/addons/amouse_wxapp_card/inc/common.php";
        require_once IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
        $uniacid = $_W["uniacid"];
        $cardid = $_GPC['id'];
        $card = pdo_fetch("SELECT `id`,`qrcode2`, `mobile`, `email`, `weixin`,  `company`, `job`,`qq`,`industry`,`department`,`username`,`address`,`avater` FROM " . tablename('amouse_wxapp_card') . " WHERE `uniacid`=:weid and `id`=:id limit 1", array(":weid" => $uniacid, ":id" => $cardid));
        if (empty($card['qrcode2'])) {
            load()->func('file');
            $poster = pdo_fetch("SELECT `id`,`uniacid`,`bg`,`data` from " . tablename('wxapp_card_poster') . "  where `uniacid`=:weid limit 1 ", array(":weid" => $uniacid));
            $poster['cardid'] = $card['id'];
            $poster['uniacid'] = $uniacid;
            $poster['from_user'] = $card['openid'];
            $poster['nickname'] = $card['username'];
            $poster['avatar'] = tomedia($card['avater']);
            $path = ATTACHMENT_ROOT . 'images/';
            $filename = $_W['uniacid'] . '/' . $card['openid'] . ".png";
            if (!is_dir($path . $filename)) {
                mkdirs(dirname($path . $filename));
            }
            $chl = "BEGIN:VCARD\nVERSION:3.0" . //vcard头信息
                "\nFN:" . $card['username'] .
                "\nTEL:" . $card['mobile'] .
                "\nEMAIL:" . $card['email'] .
                "\nTITLE:" . $card['job'] .
                //   "\nPHOTO;VALUE=URL;TYPE=GIF:".$poster['avatar'] .
                "\nORG:" . $card['company'] .
                "\nROLE:" . $card['department'] .
                "\nX-QQ:" . $card['qq'] .
                "\nADR;WORK;POSTAL:" . $card['address'] .
                "\nEND:VCARD"; //vcard尾信息

            QRcode::png($chl, $path . $filename, QR_ECLEVEL_L, 100);
            $url = 'images/' . $filename;
            $ret = createCodeUnlimit($poster, $url);
            if ($ret['code'] == 1) {
                $qrcode = $ret['qr_img'];
                pdo_update('amouse_wxapp_card', array('qrcode2' => $qrcode), array('id' => $card['id']));
            } else {
                return $this->result(1, '创建二维码出错了', $ret['qr_img']);
            }
        } else {
            $qrcode = tomedia($card['qrcode2']);
        }
        return $this->result(0, '', $qrcode);
    }

    //=======积分商场部分
    public function setFansCredit($openid, $credittype, $credit, $limit, $remark) {
        load()->model('mc');
        load()->func('compat.biz');
        global $_W;
        $uid = mc_openid2uid($openid);
        $fans = mc_fetch($uid, array($credittype));
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            $log[2] = $this->modulename;
            $date = date('Y-m-d');
            $sql = 'SELECT sum(num) as maxCredit FROM ' . tablename('mc_credits_record') . ' WHERE uniacid =:weid and uid =:uid and date_format(FROM_UNIXTIME(createtime), \'%Y-%m-%d\') =:date ';
            $record = pdo_fetch($sql, array(":weid" => $_W['uniacid'], ':uid' => $uid, ':date' => $date));
            if ($limit == 0 || $limit > $record['maxCredit']) {
                return mc_credit_update($uid, $credittype, $credit, $log);
            } else {
                return false;
            }
        }
    }

    public function getFansCredit($openid, $credittype) {
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($openid);
        $fans = mc_fetch($uid, array($credittype));
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $mc = mc_credit_fetch($uid, array($credittype));
            $credit = $mc[$credittype];
            return $credit;
        }
    }

    public function doPageApiGetGoodsList() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = max(10, intval($_GPC['pageSize']));
        $start = ($pindex - 1) * $psize;
        $sql = "SELECT * FROM " . tablename('amouse_wxapp_creditshop_goods') . " WHERE `uniacid` =:weid and status=0  order by displayorder desc limit $start,$psize ";
        $list = pdo_fetchall($sql, array(":weid" => $uniacid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_wxapp_creditshop_goods') . " WHERE `uniacid` =:weid and status=0 ", array(":weid" => $uniacid));
        $tpage = ceil($total / $psize);
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                if ($value['thumb']) {
                    $imgs = iunserializer($value['thumb']);
                    $list[$key]['logo'] = tomedia($imgs[0]);
                }

            }
            return $this->result(0, $sql, $list);
        } else {
            return $this->result(1, '', 0);
        }
    }

    public function doPageApiGetGoodsDetail() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $id = intval($_GPC['id']);
        $from = $_W['fans']['openid'];
        $sql = "SELECT * FROM " . tablename('amouse_wxapp_creditshop_goods') . " WHERE `uniacid` =:weid and status=0  and `id`=:id limit 1 ";
        $info = pdo_fetch($sql, array(":weid" => $uniacid, ":id" => $id));
        $imgs = iunserializer($info['thumb']);
        $info['logo'] = tomedia($imgs[0]);
        $piclist = array();
        $piclist1 = iunserializer($info['thumb']);
        if (is_array($piclist1)) {
            foreach ($piclist1 as $key => $p) {
                $piclist[] = is_array($p) ? $p['attachment'] : tomedia($p);
            }
        }
        $info['gallery'] = $piclist;
        $log = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_creditshop_log') . " WHERE `uniacid` =:weid and `openid`=:openid  and `goodsid`=:goodsid limit 1 ", array(":weid" => $uniacid, ":openid" => $from, ":goodsid" => $id));
        if (time() > $info['endtime']) {
            $info['is_end_time'] = 0;//
        } else {
            $info['is_end_time'] = 1;
        }
        $info['endtime'] = date('Y-m-d H:i', $info['endtime']);
        return $this->result(0, $sql, $info);

    }

    public function doPageApiExchangeGoods() {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $gid = $_GPC['gid'];
        $from = $_W['fans']['openid'];
        $goods = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_creditshop_goods') . " WHERE `id`=:id AND `uniacid`=:weid ", array(":id" => $gid, ":weid" => $weid));
        if (empty($goods)) {
            return $this->result(0, '您要兑换的商品不存在', '');
        }
        $total_credit = $this->getFansCredit($from, 'credit1');
        if ($total_credit - $goods['credit'] < 0) {
            return $this->result(0, '您的积分不够兑换此商品', '');
        }
        if ($goods['stock'] < 0) {
            return $this->result(0, '此商品的库存不足', '');
        }
        $data2=array(
            'uniacid'=>$weid,
            'openid'=>$from,
            'address_name'=>trim($_GPC['address_name']),
            'address'=>trim($_GPC['address']),
            'address_phone'=>trim($_GPC['address_phone']),
            'goodsid'=>$gid,'status'=>0,
            'createtime'=>TIMESTAMP);

        if ($goods['stock'] != 0) {
            $temp = pdo_query("UPDATE " . tablename('amouse_wxapp_creditshop_goods') . " SET stock=stock-1 WHERE uniacid=:uniacid and id=:id", array(':uniacid' => $weid, ":id" => $gid));
        }
        /*if($goods['type']==1){//兑换名片VIP
            $card = pdo_fetch('select vipstatus,id,createtime from '.tablename('amouse_board_card').' where weid=:weid AND openid=:openid LIMIT 1',array(':weid'=>$weid, ':openid'=>$openid));
            if($card['createtime']>=time()) {//延时
                $nextWeek = $card['createtime']+($goods['totalday'] * 24 * 60 * 60);
            }else{
                $nextWeek =TIMESTAMP + (intval($goods['totalday']) * 24 * 60 * 60);
            }
            $data=array();
            if($card['vipstatus']!=2){
                $data['createtime']=$nextWeek;
                $data['vipstatus']=1;
            }else{
                $data['vipstatus']=$card['vipstatus'];
            }
            $data['uptime']=time();
            pdo_update('amouse_board_card',$data, array('id' =>$card['id']));
        }elseif($goods['type']==3){
            $groupid=$_GPC['groupid'];
            $g= pdo_fetch('select * from '.tablename('amouse_board_group_mp').' where weid=:weid AND id=:groupid LIMIT 1',array(':weid'=>$weid,
                ':groupid'=>$groupid));

            if(empty($g)){
                $res['code'] = 502;
                $res['msg'] = '您要置顶的微信群不存在或者被删除!';
                die(json_encode($res));
            }

            $nextWeek =TIMESTAMP + (intval($goods['totalday']) * 24 * 60 * 60);
            if($g['mealid']>0){
                $nextWeek =$nextWeek + ($goods['totalday'] * 24 * 60 * 60);
            }

            pdo_update('amouse_board_group_mp', array('svip'=>1,'createtime'=>$nextWeek), array('id' =>$g['id']));

        }elseif($goods['type']==4){//超级VIP
            $day = $goods['totalday'];
            $card = pdo_fetch('select * from '.tablename('amouse_board_card').' where weid=:weid AND openid=:openid LIMIT 1',array(':weid'=>$weid, ':openid'=>$openid));
            $data['vipstatus']=2;

            $maxvip=pdo_fetchcolumn('select max(endtime) from '.tablename('amouse_board_card').' where weid=:weid AND vipstatus=2 and (sviptime > 0 AND endtime > 0) ',array
            (':weid'=>$weid));
            $timer=time();
            if ($set['isrand']==0) {//随机开始时间 为当前时间
                if($card['vipstatus']==2){//延时
                    $svipStartTime =$card['sviptime'];
                    $svipEndTime =$card['endtime']+($day*60);
                }else{
                    $svipStartTime =$timer ;
                    $svipEndTime=$svipStartTime+$day*60;
                }
            }else{
                if($maxvip>0 && $maxvip>=$timer) {//根据VIP当前时间排队
                    $svipStartTime =$maxvip+$day*60;
                    if($card['vipstatus']==2){//延时
                        $svipStartTime = $card['sviptime']+ $day*60;
                        $svipEndTime =$card['endtime']+($day*60);
                    } else{
                        $svipStartTime=	$maxvip;
                        $svipEndTime =$svipStartTime+($day*60);
                    }
                    $svipEndTime=$svipStartTime+$day*60;
                }else{
                    if($card['vipstatus']==2){//延时
                        $svipStartTime = $card['sviptime'];
                        $svipEndTime =$card['endtime']+($day*60);
                    }else{
                        $svipStartTime = $card['sviptime']==0?$timer:$card['sviptime'];
                        $svipEndTime=$svipStartTime+$day*60;
                    }
                }
            }
            $data['sviptime']=$svipStartTime;
            $data['endtime']=$svipEndTime;
            pdo_update('amouse_board_card',$data, array('id' =>$card['id']));
        }elseif($goods['type']==5){//现金红包
            $settings=$this->getRedpacksSysset($weid);
            require_once IA_ROOT . "/addons/amouse_hufen/inc/common.php";
            $credit2 = $goods['credit2'];//兑换到账金额
            $temp=pdo_fetch("select wtx_money,user_status,tx_money from ".tablename("amouse_board_member")." where weid=$weid and openid='$openid'");
            if (!empty($temp) && $temp['user_status']==0) {//拉黑
                $res['code'] = 201;
                $res['msg'] = "你因为违规操作，已经被拉黑。请联系管理员吧";
                return json_encode($res);
            }
            $date=date('Y-m-d');
            $creditshop_log=pdo_fetch("SELECT * FROM ".tablename('amouse_board_creditshop_log')." WHERE uniacid = :weid and openid = :wid and date_format(FROM_UNIXTIME(createtime), '%Y-%m-%d') = :date", array(':weid'=>$weid, ':wid'=>$openid, ':date'=>$date));
            if(empty($creditshop_log)){
                $ret= send_cash_task($openid,$credit2,$settings);//发送红包
                if ($ret['code']==0) {
                    logging_run("doMobileAjaxExchangeDo->send_cash_task:ret>code=====" . $ret['code']);
                    $res['code']=200;
                    $res['msg']="提交成功，红包已兑换成功，稍后请关注公众号提示信息！";
                }else{
                    $res['code']=201;
                    $res['msg']=$ret['msg'];
                    return json_encode($res);
                }
            } else {
                $res['code']=201;
                $res['msg']="今天已经兑换过红包了，给其他好友留点机会吧！";
                return json_encode($res);
            }
        }*/

        $this->setFansCredit($from, 'credit1', -$goods['credit'], 0, "兑换商品-" . $goods['credit']);
        pdo_insert("amouse_wxapp_creditshop_log", $data2);
        $logid = pdo_insertid();
        if (time() > $goods['endtime']) {
            $goods['is_end_time'] = 0;//
        } else {
            $goods['is_end_time'] = 1;
        }
        $imgs = iunserializer($goods['thumb']);
        $goods['logo'] = tomedia($imgs[0]);
        $goods['endtime'] = date('Y-m-d H:i', $goods['endtime']);
        return $this->result(0, '兑换成功', $goods);
    }

    //兑换记录
    public function doPageApiGetExchages() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = max(10, intval($_GPC['pageSize']));
        $start = ($pindex - 1) * $psize;
        $openid = $_W['openid'];
        $sql = "SELECT log.*,goods.title,goods.description,goods.endtime,goods.id as goods_id,goods.thumb FROM " . tablename('amouse_wxapp_creditshop_log') . " as log left join " . tablename('amouse_wxapp_creditshop_goods') . " as goods on goods.id=log.goodsid WHERE log.openid =:uid and log.uniacid=:weid order by log.createtime desc limit $start,$psize ";
        $list = pdo_fetchall($sql, array(":uid" => $openid, ":weid" => $uniacid));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('amouse_wxapp_creditshop_log') . " as log left join " . tablename('amouse_wxapp_creditshop_goods') . " as goods on goods.id=log.goodsid WHERE log.openid =:uid and log.uniacid =:weid ", array(":uid" => $openid, ":weid" => $uniacid));
        $tpage = ceil($total / $psize);
        foreach ($list as $key => $value) {
            if (time() > $value['endtime']) {
                $list[$key]['is_end_time'] = 0;//
            } else {
                $list[$key]['is_end_time'] = 1;
            }
            $imgs = iunserializer($value['thumb']);
            $list[$key]['logo'] = tomedia($imgs[0]);
            $list[$key]['endtime'] = date('Y-m-d H:i', $value['endtime']);
        }
        return $this->result(0, $sql, $list);
    }

    public function doPageApiGetLogs() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = max(10, intval($_GPC['pageSize']));
        $start = ($pindex - 1) * $psize;
        $openid = $_W['openid'];
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($openid);
        $sql = "SELECT * FROM " . tablename('mc_credits_record') . " WHERE `uid` =:uid and `uniacid`=:uniacid and module=:module  order by createtime desc limit $start,$psize ";
        $list = pdo_fetchall($sql, array(":uid" => $uid, ":uniacid" => $uniacid, ":module" => 'amouse_wxapp_card'));
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_credits_record') . " WHERE `uid` =:uid and `uniacid` =:weid and module=:module ", array(":uid" => $uid, ":weid" => $uniacid, ":module" => 'amouse_wxapp_card'));
        $tpage = ceil($total / $psize);
        foreach ($list as $key => $value) {
            $list[$key]['createtime'] = date('Y-m-d H:i', $value['createtime']);
        }
        return $this->result(0, $sql, $list);
    }

    //
    public function doPageApiShare() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $from = $_W['fans']['openid'];
        $date = date('Y-m-d');
        $set = pdo_fetch("select `share_credit`,`limit_credit`,`exchange`,`wxapp_name2` from " . tablename('amouse_wxapp_sysset') . ' where `uniacid`=:uniacid limit 1', array(':uniacid' => $uniacid));
        $record = pdo_fetchcolumn('SELECT count(id) FROM ' . tablename('amouse_wxapp_card_history') . ' WHERE uniacid = :weid and sms_type=5 and from_user = :uid and date_format(FROM_UNIXTIME(createtime), \'%Y-%m-%d\') =:date ', array(':weid' => $_W['uniacid'], ':uid' => $from, ':date' => $date));
        if ($set['exchange']==0 && $record <= 0) {
            $insert = array('uniacid' => $uniacid, 'sms_type' => 5, 'createtime' => time(), 'from_user' => $from);
            pdo_insert('amouse_wxapp_card_history', $insert);
            $card = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_card') . " WHERE `openid` = :id AND `uniacid` = :weid ", array(':id' => $from, ':weid' => intval($_W['uniacid'])));
            if ($card['createtime'] >= time()) {//延时
                $nextWeek = $card['createtime'] + (1 * 24 * 60 * 60);
            } else {
                $nextWeek = TIMESTAMP + (1 * 24 * 60 * 60);
            }
            $data2['createtime'] = $nextWeek;
            $data2['vip'] = 1;//置顶标志
            pdo_update('amouse_wxapp_card', $data2, array('id' => $card['id']));
        }
        $message="分享成功";
        if ($set['wxapp_name2']==0 && $set['share_credit'] > 0) {//赠送积分
            $res = $this->setFansCredit($from, 'credit1', $set['share_credit'], $set['limit_credit'], "转发赠送积分" . $set['share_credit']);
            if ($res) {
                return $this->result(0, $message.'，获得' . $set['share_credit'] . '积分', $from);
            } else {
                return $this->result(0, $message, $from);
            }
        }
        return $this->result(0, $message, $from);
    }

    public function doPageApiGetMyInfo() {
        global $_W;
        $uniacid = $_W['uniacid'];
        $from = $_W['fans']['openid'];
        $config = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_sysset') . " WHERE uniacid=:weid limit 1", array(':weid' => $uniacid));
        $credit1 = $this->getFansCredit($from, 'credit1');
        $config['credit1'] = empty($credit1) ? 0 : $credit1;
        return $this->result(0, $from, $config);
    }

    //===============导航接口==========
    public function doPageApiGetRecommend() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $sql = "SELECT * FROM " . tablename('amouse_wxapp_navs') . " WHERE  `uniacid`=:uniacid and recommend=0  order by displayorder desc  ";
        $list = pdo_fetchall($sql, array(":uniacid" => $uniacid));
        foreach ($list as $key => $value) {
            $list[$key]['qrcode'] = tomedia($value['qrcode']);
            $list[$key]['thumb'] = tomedia($value['thumb']);
        }
        return $this->result(0, $sql, $list);
    }
    //

    //
    public function doPageApiGetAllNav() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['pageIndex']));
        $psize = max(10, intval($_GPC['pageSize']));
        $start = ($pindex - 1) * $psize;
        $orderby = "";
        $where = " WHERE `uniacid` =:weid and  recommend=1 ";
        $params = array();
        $params[':weid'] = $uniacid;
        if (!empty($_GPC['keyword'])) {
            $keyword = $_GPC['keyword'];
            $where .= " AND `title` LIKE :keyword  ";
            $params[':keyword'] = "%{$keyword}%";
        }
        $sql = "select *  from " . tablename('amouse_wxapp_navs') . $where . $orderby . " limit $start,$psize";
        $list = pdo_fetchall($sql, $params);
        $return = array();
        foreach ($list as $key => $value) {
            $list[$key]['qrcode'] = tomedia($value['qrcode']);
            $list[$key]['thumb'] = tomedia($value['thumb']);
        }
        $return['list'] = $list;
        return $this->result(0, $sql, $return);
    }

    //新增战队
    public function doPageApiPostZhanDui() {
        global $_GPC, $_W;
        $from = $_W['fans']['openid'];
        $uniacid = $_W['uniacid'];
        $avatar = $_GPC['avater'];
        $apiCardid = $_GPC['cardid'];
        $data = array('uniacid' => $uniacid, 'openid' => $from, 'createtime' => time(),
            'avater' => $avatar, 'name' => $_GPC['title'], 'desc' => $_GPC['desc']
        );
        $fromid = trim($_GPC['fromId']);
        $order = pdo_fetch("select `id` from " . tablename('amouse_wxapp_order') . " where status=1 and paytype=6 and `openid`=:openid limit 1", array(":openid" => $from));
        if (empty($order)) {
            return $this->result(1, '购买代理才能创建站队', $fromid);
        }
        if ($apiCardid > 0) {
            $card = pdo_fetch("select `id`,`openid`, `name`, `desc`, `avater` from " . tablename('amouse_wxapp_zhandui') . " where id=:id and uniacid=:weid limit 1", array(":id" => $apiCardid, ":weid" => $uniacid));
            if (empty($_GPC['avater'])) {
                $data['avater'] = $card['avater'];
            }
            pdo_update('amouse_wxapp_zhandui', $data, array('id' => $apiCardid));
        } else {
            pdo_insert('amouse_wxapp_zhandui', $data);
            $apiCardid = pdo_insertid();
        }
        return $this->result(0, '', $apiCardid);
    }

    //获取战队
    public function doPageApiGetAllWings() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $pindex = max(1, intval($_GPC['pageIndex']));
        $psize = max(10, intval($_GPC['pageSize']));
        $type = intval($_GPC['type']);
        $start = ($pindex - 1) * $psize;
        $from = $_W['fans']['openid'];
        $orderby = "";
        $where = " WHERE `uniacid` =:weid ";
        $params = array();
        $params[':weid'] = $uniacid;
        if (!empty($_GPC['keyword'])) {
            $keyword = $_GPC['keyword'];
            $where .= " AND `name` LIKE :keyword  ";
            $params[':keyword'] = "%{$keyword}%";
        }
        $return = array();
        $myZhandui = pdo_fetch("select `id`,`openid`, `name`, `desc`, `avater` from " . tablename('amouse_wxapp_zhandui') . " where `openid`=:openid and uniacid=:weid limit 1", array(":openid" => $from, ":weid" => $uniacid));
        if ($myZhandui) {
            $return['mywing'] = $myZhandui;
        } else {
            $return['mywing']['id'] = 0;
        }
        if ($type == 1) { //我的战队

        } elseif ($type == 2) {//我加入的战队
            $sql = "select *  from " . tablename('amouse_wxapp_zhandui_log') . " WHERE `uniacid` =:weid and `openid`=:openid limit $start,$psize";
            $params2 = array();
            $params2[':weid'] = $uniacid;
            $params2[':openid'] = $from;
            $list = pdo_fetchall($sql, $params2);
            foreach ($list as $key => $value) {
                $myZhandui = pdo_fetch("select `id`,`openid`, `name`, `desc`, `avater` from " . tablename('amouse_wxapp_zhandui') . " where `id`=:id and uniacid=:weid limit 1", array(":id" => $value['zhandui_id'], ":weid" => $uniacid));
                $list[$key]['name'] = $myZhandui['name'];
                $list[$key]['desc'] = $myZhandui['desc'];
                $list[$key]['avater'] = tomedia($myZhandui['avater']);
            }
            $return['list'] = $list;
        } elseif ($type == 0) {//所有战队
            $sql = "select *  from " . tablename('amouse_wxapp_zhandui') . $where . $orderby . " limit $start,$psize";
            $list = pdo_fetchall($sql, $params);
            foreach ($list as $key => $value) {
                $list[$key]['avater'] = tomedia($value['avater']);
            }
            $return['list'] = $list;
        }
        return $this->result(0, $sql, $return);
    }

    //
    public function doPageApiGetAllAgents() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $params = array();
        $params[':weid'] = $uniacid;
        $list = pdo_fetchall("select * from " . tablename('wxapp_wxapp_agent_set') . " where  uniacid=:weid order by displayorder desc ", array(":weid" => $uniacid));
        return $this->result(0, '', $list);
    }

    public function doPageApiGetMyZhanDui() {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        $id = intval($_GPC['id']);
        $card = pdo_fetch("SELECT * FROM " . tablename('amouse_wxapp_zhandui') . " WHERE `uniacid`=:weid and id=:id limit 1", array(":weid" => $uniacid, ":id" => $id));
        if (!empty($card)) {
            $card['avater'] = tomedia($card['avater']);
            $card['openid'] = $openid;
            return $this->result(0, $openid, $card);
        } else {
            $card['id'] = 0;
            $card['openid'] = $openid;
            return $this->result(0, '', $card);
        }
    }

    public function doPageApiAddAgent() {
        global $_GPC, $_W;
        $price = $_GPC['top_amount'];
        $agentid = $_GPC['agentid'];
        $formid = trim($_GPC['formid']);
        $uniacid = $_W['uniacid'];
        $from = $_W['openid'];
        $card = pdo_fetch("SELECT `id`,`openid` FROM " . tablename('amouse_wxapp_card') . "where `uniacid`=:weid and `openid`=:openid limit 1", array(":weid" => $uniacid, ":openid" => $from));
        if (!empty($card)) {
            $orderData = array('uniacid' => $uniacid, 'openid' => $from, 'module' => $this->modulename,
                'ordersn' => date('ymd') . sprintf("%04d", $_W['fans']['id']) . random(4, 1),
                'status' => 0, 'paytype' => 6, 'price' => $price, 'house_id' => $agentid,
                'createtime' => time(), 'formid' => $formid,
            );
            $agent_set = pdo_fetch("SELECT `id`,`title` FROM " . tablename('wxapp_wxapp_agent_set') . " where `uniacid`=:weid and `id`=:id limit 1", array(":weid" => $uniacid, ":id" => $from));
            pdo_insert('amouse_wxapp_order', $orderData);
            $orderId = pdo_insertid();
            $orderPayData = array(
                'tid' => intval($orderId),
                'user' => $from, 'fee' => $orderData['price'],
                'title' => '购买代理' . $agent_set['title'] . '-付款' . $orderData['price'],
            );
            $pay_params = $this->pay($orderPayData);
            $pay_params['orderid'] = $orderId;
            if (is_error($pay_params)) {
                return $this->result(0, '', $pay_params);
            }
            $prepay_id = str_replace("prepay_id=", "", $pay_params['package']);
            pdo_update('amouse_wxapp_order', array('prepay_id' => $prepay_id), array('id' => $orderId));
            $this->_saveFormIdsArray($uniacid, $from, $formid);//保存
            return $this->result(0, '', $pay_params);
        } else {
            return $this->result(1, '您还没发布名片，不能支付', '');
        }
    }

}