<?php

defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/addons/xc_beauty/common/function.php';
class Xc_beautyModuleSite extends WeModuleSite
{
    public function doWebWeb()
    {
        $ops = array("edit", "savemodel", "map", "savemap", "sort", "savesort", "sort_service", "sms", "savesms", "smstest", "print", "saveprint", "printtest", "refund", "saverefund", "card", "savecard", "theme", "savetheme", "rotate", "saverotate", "share", "saveshare", "ad", "savead", "group", "online", "saveonline", "getnewuserlist", "saveonline", "testsmsv2", "audit", "saveaudit");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'edit';
        $tablename = 'xc_beauty_config';
        switch ($op) {
            case 'edit':
                $xtitle = '网站配置';
                $xkey = 'web';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                if (!empty($list['content']['footer'])) {
                    foreach ($list['content']['footer'] as &$x) {
                        if (empty($x['status'])) {
                            $x['status'] = 1;
                        }
                    }
                } else {
                    $list['content']['footer'] = '';
                    for ($i = 0; $i < 5; $i++) {
                        $list['content']['footer'][$i] = array("icon" => "", "select" => "", "text" => "", "link" => "", "status" => 1);
                    }
                }
                if (empty($list['content']['group_refund'])) {
                    $list['content']['group_refund'] = 1;
                }
                $tiangong = -1;
                $text = '../addons/xc_beauty/resource/tiangong.txt';
                if (is_file($text)) {
                    $str = file_get_contents($text);
                    if (md5($str) == '9c723b18124c30c5fd0ec47513a8f375') {
                        $tiangong = 1;
                    }
                }
                include $this->template('Web/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'web';
                $condition['name'] = $_GPC['name'];
                $footer = $_GPC['footer'];
                if (!empty($footer) && is_array($footer)) {
                    foreach ($footer as &$x) {
                        if (empty($x['status'])) {
                            $x['status'] = -1;
                        }
                    }
                }
                $content = array("title" => $_GPC['title'], "copyright" => $_GPC['copyright'], "footer" => $footer, "template_id" => $_GPC['template_id'], "online_simg" => $_GPC['online_simg'], "member_title" => $_GPC['member_title'], "coupon_text" => $_GPC['coupon_text'], "coupon_bg" => $_GPC['coupon_bg'], "rotate_bg" => $_GPC['rotate_bg'], "total_limit" => $_GPC['total_limit'], "group_refund" => $_GPC['group_refund'], "share_index_title" => $_GPC['share_index_title'], "share_index_img" => $_GPC['share_index_img'], "share_service_title" => $_GPC['share_service_title'], "share_service_img" => $_GPC['share_service_img'], "share_group_title" => $_GPC['share_group_title'], "share_group_img" => $_GPC['share_group_img'], "mobile_simg" => $_GPC['mobile_simg'], "mobile" => $_GPC['mobile'], "order_fail" => $_GPC['order_fail'], "order_do" => $_GPC['order_do'], "group_success" => $_GPC['group_success'], "group_fail" => $_GPC['group_fail'], "xcmessage" => $_GPC['xcmessage'], "sub_appid" => $_GPC['sub_appid'], "sub_mch_id" => $_GPC['sub_mch_id'], "sub_key" => $_GPC['sub_key']);
                if (!empty($_GPC['sub_status'])) {
                    $content['sub_status'] = $_GPC['sub_status'];
                } else {
                    $content['sub_status'] = -1;
                }
                if (!empty($_GPC['audit'])) {
                    $content['audit'] = $_GPC['audit'];
                } else {
                    $content['audit'] = -1;
                }
                if (!empty($_GPC['online_status'])) {
                    $content['online_status'] = $_GPC['online_status'];
                } else {
                    $content['online_status'] = -1;
                }
                if (!empty($_GPC['refund_status'])) {
                    $content['refund_status'] = $_GPC['refund_status'];
                } else {
                    $content['refund_status'] = -1;
                }
                if (!empty($_GPC['mobile_status'])) {
                    $content['mobile_status'] = $_GPC['mobile_status'];
                } else {
                    $content['mobile_status'] = -1;
                }
                if (!empty($_GPC['map_status'])) {
                    $content['map_status'] = $_GPC['map_status'];
                } else {
                    $content['map_status'] = -1;
                }
                if (!empty($_GPC['member_status'])) {
                    $content['member_status'] = $_GPC['member_status'];
                } else {
                    $content['member_status'] = -1;
                }
                if (!empty($_GPC['home_status'])) {
                    $content['home_status'] = $_GPC['home_status'];
                } else {
                    $content['home_status'] = -1;
                }
                if (!empty($_GPC['time_status'])) {
                    $content['time_status'] = $_GPC['time_status'];
                } else {
                    $content['time_status'] = -1;
                }
                if (!empty($_GPC['group_time'])) {
                    $content['group_time'] = $_GPC['group_time'];
                } else {
                    $content['group_time'] = -1;
                }
                if (!empty($_GPC['online_time'])) {
                    $content['online_time'] = $_GPC['online_time'];
                } else {
                    $content['online_time'] = -1;
                }
                if (!empty($_GPC['store_change'])) {
                    $content['store_change'] = $_GPC['store_change'];
                } else {
                    $content['store_change'] = -1;
                }
                if (!empty($_GPC['buy_sale_status'])) {
                    $content['buy_sale_status'] = $_GPC['buy_sale_status'];
                } else {
                    $content['buy_sale_status'] = -1;
                }
                if (!empty($_GPC['ti_status'])) {
                    $content['ti_status'] = $_GPC['ti_status'];
                } else {
                    $content['ti_status'] = -1;
                }
                if (!empty($_GPC['tiangong'])) {
                    $content['tiangong'] = $_GPC['tiangong'];
                    $content['AppKey'] = $_GPC['AppKey'];
                    $content['AppSecret'] = $_GPC['AppSecret'];
                    $content['agent_id'] = $_GPC['agent_id'];
                    $content['user_id'] = $_GPC['user_id'];
                } else {
                    $content['tiangong'] = -1;
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "web", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $list['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'map':
                $xtitle = '网站配置';
                $xkey = 'map';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                include $this->template('Web/map');
                break;
            case 'savemap':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'map';
                $condition['name'] = $_GPC['name'];
                $content = array("mobile" => $_GPC['mobile'], "address" => $_GPC['address'], "longitude" => $_GPC['longitude'], "latitude" => $_GPC['latitude']);
                if (!empty($_GPC['store'])) {
                    $content['store'] = $_GPC['store'];
                } else {
                    $content['store'] = -1;
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "map", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $list['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'sort':
                $xtitle = '网站配置';
                $xkey = 'sort';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                    $pclass = -1;
                    $nav = -1;
                    $ad = -1;
                    foreach ($list['content']['sort'] as &$x) {
                        if ($x['name'] == 'display') {
                            $x['value'] = htmlspecialchars(json_encode($x['value']));
                        } elseif ($x['name'] == 'pclass') {
                            $pclass = 1;
                        } elseif ($x['name'] == 'nav') {
                            $nav = 1;
                        } elseif ($x['name'] == 'ad') {
                            $ad = 1;
                        }
                    }
                    if ($pclass == -1) {
                        $list['content']['sort'][] = array("name" => "pclass", "status" => -1);
                    }
                    if ($nav == -1) {
                        $list['content']['sort'][] = array("name" => "nav", "status" => -1);
                    }
                    if ($ad == -1) {
                        $list['content']['sort'][] = array("name" => "ad", "status" => 1);
                    }
                }
                include $this->template('Web/sort');
                break;
            case 'savesort':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'sort';
                $condition['name'] = $_GPC['name'];
                $content = array("sort" => json_decode(htmlspecialchars_decode($_GPC['sort']), true));
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "sort", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $list['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'sort_service':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                if (!empty($_GPC['cid'])) {
                    $cid = $_GPC['cid'];
                    $condition['cid'] = $_GPC['cid'];
                }
                $request = pdo_getall('xc_beauty_service', $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall('xc_beauty_service', $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                $class = pdo_getall('xc_beauty_service_class', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
                $datalist = array();
                if ($class) {
                    foreach ($class as $x) {
                        $datalist[$x['id']] = $x['name'];
                    }
                }
                if ($list) {
                    foreach ($list as &$y) {
                        $y['cidname'] = $datalist[$y['cid']];
                    }
                }
                include $this->template('Web/sort_service');
                break;
            case 'sms':
                $xtitle = '网站配置';
                $xkey = 'sms';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                if (empty($list['content']['type'])) {
                    $list['content']['type'] = 1;
                }
                include $this->template('Web/sms');
                break;
            case 'savesms':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'sms';
                $condition['name'] = $_GPC['name'];
                $content = array("AccessKeyId" => $_GPC['AccessKeyId'], "AccessKeySecret" => $_GPC['AccessKeySecret'], "sign" => $_GPC['sign'], "code" => $_GPC['code'], "mobile" => $_GPC['mobile'], "type" => $_GPC['type'], "key" => $_GPC['key'], "tpl_id" => $_GPC['tpl_id'], "url" => $_GPC['url'], "customize" => json_decode(htmlspecialchars_decode($_GPC['customize']), true), "post" => json_decode(htmlspecialchars_decode($_GPC['post']), true), "get" => json_decode(htmlspecialchars_decode($_GPC['get']), true));
                if (!empty($_GPC['status'])) {
                    $content['status'] = $_GPC['status'];
                } else {
                    $content['status'] = -1;
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "sms", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'theme':
                $xtitle = '网站配置';
                $xkey = 'theme';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                } else {
                    $list['content']['theme'] = 1;
                }
                include $this->template('Web/theme');
                break;
            case 'savetheme':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'theme';
                $condition['name'] = $_GPC['name'];
                $content = array("theme" => $_GPC['theme']);
                if ($_GPC['theme'] == 2) {
                    if (empty($_GPC['color'])) {
                        $content['color'] = '#e74479';
                    } else {
                        $content['color'] = $_GPC['color'];
                    }
                    $content['icon'] = $_GPC['icon'];
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "theme", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'smstest':
                if ($_GPC['type'] == 1) {
                    require_once IA_ROOT . '/addons/xc_beauty/resource/sms/sendSms.php';
                    set_time_limit(0);
                    header('Content-Type: text/plain; charset=utf-8');
                    $templateParam = array("webnamex" => "美容", "trade" => "20171127101100000017", "amount" => "199元", "namex" => "张三", "phonex" => "18888888888", "addrx" => "中国北京", "datex" => date('Y-m-d H:i'), "service" => "买蛋糕", "store" => "门店");
                    $send = new sms();
                    $result = $send->sendSms($_GPC['AccessKeyId'], $_GPC['AccessKeySecret'], $_GPC['sign'], $_GPC['code'], $_GPC['mobile'], $templateParam);
                    echo json_encode($result);
                } elseif ($_GPC['type'] == 2) {
                    header('content-type:text/html;charset=utf-8');
                    $sendUrl = 'http://v.juhe.cn/sms/send';
                    $smsConf = array("key" => $_GPC['key'], "mobile" => $_GPC['mobile'], "tpl_id" => $_GPC['tpl_id'], "tpl_value" => '#webnamex#=美容&#trade#=1220171127101100000017&#amount#=199元&#namex#=张三&#phonex#=18888888888&#addrx#=中国北京&#datex#=' . date('Y-m-d H:i'));
                    $content = juhecurl($sendUrl, $smsConf, 1);
                    if ($content) {
                        $result = json_decode($content, true);
                        $error_code = $result['error_code'];
                        echo json_encode($result);
                    } else {
                        echo json_encode('请求发送短信失败');
                    }
                }
                break;
            case 'print':
                $xtitle = '网站配置';
                $xkey = 'print';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                include $this->template('Web/print');
                break;
            case 'saveprint':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'print';
                $condition['name'] = $_GPC['name'];
                $content = array("apikey" => $_GPC['apikey'], "machine_code" => $_GPC['machine_code'], "msign" => $_GPC['msign'], "partner" => $_GPC['partner'], "type" => $_GPC['type'], "user" => $_GPC['user'], "ukey" => $_GPC['ukey'], "sn" => $_GPC['sn']);
                if (!empty($_GPC['status'])) {
                    $content['status'] = $_GPC['status'];
                } else {
                    $content['status'] = -1;
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "print", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'printtest':
                if ($_GPC['type'] == 1) {
                    $partner = $_GPC['partner'];
                    $apikey = $_GPC['apikey'];
                    $machine_code = $_GPC['machine_code'];
                    $msign = $_GPC['msign'];
                    $time = time();
                    $content = '';
                    $content .= '<table>';
                    $content .= '<tr><td>订单号：1111111111</td></tr>';
                    $content .= '</table>';
                    $sign = strtoupper(md5($_GPC['apikey'] . 'machine_code' . $_GPC['machine_code'] . 'partner' . $_GPC['partner'] . 'time' . $time . $msign));
                    $requestUrl = 'http://open.10ss.net:8888';
                    $requestAll = ["partner" => $partner, "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                    $requestInfo = http_build_query($requestAll);
                    $request = push($requestInfo, $requestUrl);
                    echo $request;
                } elseif ($_GPC['type'] == 2) {
                    include IA_ROOT . '/addons/xc_beauty/resource/HttpClient.class.php';
                    define('USER', $_GPC['user']);
                    define('UKEY', $_GPC['ukey']);
                    define('SN', $_GPC['sn']);
                    define('IP', 'api.feieyun.cn');
                    define('PORT', 80);
                    define('PATH', '/Api/Open/');
                    define('STIME', time());
                    define('SIG', sha1(USER . UKEY . STIME));
                    $orderInfo = '<CB>测试打印</CB><BR>';
                    $orderInfo .= '名称　　　　　 单价  数量 金额<BR>';
                    $orderInfo .= '--------------------------------<BR>';
                    $orderInfo .= '饭　　　　　 　10.0   10  10.0<BR>';
                    $orderInfo .= '炒饭　　　　　 10.0   10  10.0<BR>';
                    $orderInfo .= '蛋炒饭　　　　 10.0   100 100.0<BR>';
                    $orderInfo .= '鸡蛋炒饭　　　 100.0  100 100.0<BR>';
                    $orderInfo .= '西红柿炒饭　　 1000.0 1   100.0<BR>';
                    $orderInfo .= '西红柿蛋炒饭　 100.0  100 100.0<BR>';
                    $orderInfo .= '西红柿鸡蛋炒饭 15.0   1   15.0<BR>';
                    $orderInfo .= '备注：加辣<BR>';
                    $orderInfo .= '--------------------------------<BR>';
                    $orderInfo .= '合计：xx.0元<BR>';
                    $orderInfo .= '送货地点：广州市南沙区xx路xx号<BR>';
                    $orderInfo .= '联系电话：13888888888888<BR>';
                    $orderInfo .= '订餐时间：2014-08-08 08:08:08<BR>';
                    $orderInfo .= '<QR>http://www.dzist.com</QR>';
                    $request = wp_print(SN, $orderInfo, 1);
                    echo $request;
                }
                break;
            case 'refund':
                $xtitle = '网站配置';
                $xkey = 'refund';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                    if (!empty($list['content']['cert'])) {
                        $cert_count = strlen($list['content']['cert']);
                        $list['content']['cert'] = '';
                        for ($i = 0; $i < $cert_count; $i++) {
                            $list['content']['cert'] = $list['content']['cert'] . '*';
                        }
                    }
                    if (!empty($list['content']['key'])) {
                        $cert_count = strlen($list['content']['key']);
                        $list['content']['key'] = '';
                        for ($i = 0; $i < $cert_count; $i++) {
                            $list['content']['key'] = $list['content']['key'] . '*';
                        }
                    }
                }
                include $this->template('Web/refund');
                break;
            case 'saverefund':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'refund';
                $condition['name'] = $_GPC['name'];
                if (empty($_GPC['id'])) {
                    $content = array("cert" => $_GPC['cert'], "key" => $_GPC['key']);
                } else {
                    $content = array();
                    $dddd = pdo_get($tablename, array("xkey" => "refund", "uniacid" => $uniacid));
                    $dddd['content'] = json_decode($dddd['content'], true);
                    $cert_count = strlen($dddd['content']['cert']);
                    $cert = '';
                    for ($i = 0; $i < $cert_count; $i++) {
                        $cert = $cert . '*';
                    }
                    if ($cert == $_GPC['cert']) {
                        $content['cert'] = $dddd['content']['cert'];
                    } else {
                        $content['cert'] = $_GPC['cert'];
                    }
                    $cert_count = strlen($dddd['content']['key']);
                    $key = '';
                    for ($i = 0; $i < $cert_count; $i++) {
                        $key = $key . '*';
                    }
                    if ($key == $_GPC['key']) {
                        $content['key'] = $dddd['content']['key'];
                    } else {
                        $content['key'] = $_GPC['key'];
                    }
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => "refund", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'card':
                $xtitle = '网站配置';
                $xkey = 'card';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                include $this->template('Web/card');
                break;
            case 'savecard':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'card';
                $condition['name'] = $_GPC['name'];
                $condition['content'] = array("card" => $_GPC['card'], "card_on" => $_GPC['card_on'], "card_rules" => $_GPC['card_rules'], "AccessKeyId" => $_GPC['AccessKeyId'], "AccessKeySecret" => $_GPC['AccessKeySecret'], "sign" => $_GPC['sign'], "code" => $_GPC['code'], "type" => $_GPC['type'], "key" => $_GPC['key'], "tpl_id" => $_GPC['tpl_id'], "withdraw_amount" => $_GPC['withdraw_amount'], "score_icon" => $_GPC['score_icon'], "score_attr" => $_GPC['score_attr'], "score_value" => $_GPC['score_value'], "discount_icon" => $_GPC['discount_icon'], "discount" => $_GPC['discount'], "recharge" => json_decode(htmlspecialchars_decode($_GPC['recharge']), true), "level" => json_decode(htmlspecialchars_decode($_GPC['level']), true));
                if (!empty($_GPC['code_status'])) {
                    $condition['content']['code_status'] = $_GPC['code_status'];
                } else {
                    $condition['content']['code_status'] = -1;
                }
                if (!empty($_GPC['withdraw'])) {
                    $condition['content']['withdraw'] = $_GPC['withdraw'];
                } else {
                    $condition['content']['withdraw'] = -1;
                }
                if (!empty($_GPC['score_status'])) {
                    $condition['content']['score_status'] = $_GPC['score_status'];
                } else {
                    $condition['content']['score_status'] = -1;
                }
                if (!empty($_GPC['discount_status'])) {
                    $condition['content']['discount_status'] = $_GPC['discount_status'];
                } else {
                    $condition['content']['discount_status'] = -1;
                }
                if (!empty($_GPC['level_status'])) {
                    $condition['content']['level_status'] = $_GPC['level_status'];
                } else {
                    $condition['content']['level_status'] = -1;
                }
                $condition['content'] = json_encode($condition['content']);
                $list = pdo_get($tablename, array("xkey" => "card", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'rotate':
                $xtitle = '网站配置';
                $xkey = 'rotate';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                if (empty($list['content']['status'])) {
                    $list['content']['status'] = 1;
                }
                include $this->template('Web/rotate');
                break;
            case 'saverotate':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'rotate';
                $condition['name'] = $_GPC['name'];
                $condition['content'] = array("sign" => $_GPC['sign'], "rules" => $_GPC['rules']);
                if (!empty($_GPC['status'])) {
                    $condition['content']['status'] = $_GPC['status'];
                } else {
                    $condition['content']['status'] = -1;
                }
                $condition['content'] = json_encode($condition['content']);
                $list = pdo_get($tablename, array("xkey" => "rotate", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'share':
                $xtitle = '网站配置';
                $xkey = 'share';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                } else {
                    $list['content']['level'] = 3;
                }
                if (empty($list['content']['status'])) {
                    $list['content']['status'] = 1;
                }
                include $this->template('Web/share');
                break;
            case 'saveshare':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'share';
                $condition['name'] = $_GPC['name'];
                $condition['content'] = array("rules" => $_GPC['rules'], "level" => $_GPC['level'], "type" => $_GPC['type'], "level_one" => $_GPC['level_one'], "level_two" => $_GPC['level_two'], "level_three" => $_GPC['level_three'], "back" => $_GPC['back'], "withdraw_amount" => $_GPC['withdraw_amount'], "share_text" => $_GPC['share_text'], "share_nick_color" => $_GPC['share_nick_color'], "share_text_color" => $_GPC['share_text_color'], "amount" => $_GPC['amount']);
                if (!empty($_GPC['status'])) {
                    $condition['content']['status'] = $_GPC['status'];
                } else {
                    $condition['content']['status'] = -1;
                }
                $condition['content'] = json_encode($condition['content']);
                $list = pdo_get($tablename, array("xkey" => "share", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'ad':
                $xtitle = '网站配置';
                $xkey = 'ad';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                include $this->template('Web/ad');
                break;
            case 'savead':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'ad';
                $condition['name'] = $_GPC['name'];
                $content = array("list" => json_decode(htmlspecialchars_decode($_GPC['list']), true));
                if (!empty($_GPC['status'])) {
                    $content['status'] = $_GPC['status'];
                } else {
                    $content['status'] = -1;
                }
                $condition['content'] = json_encode($content);
                $list = pdo_get($tablename, array("xkey" => $condition['xkey'], "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $list['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'group':
                $list = array("group" => 'https://' . $_SERVER['HTTP_HOST'] . '/app/index.php?i=' . $uniacid . '&c=entry&do=grouprefund&m=' . $_GPC['m']);
                include $this->template('Web/group');
                break;
            case 'online':
                $keyval = 'online';
                $xc = array();
                $sql = 'SELECT * FROM ' . tablename($tablename) . ' WHERE xkey=:keyval AND uniacid=:uniacid LIMIT 1';
                $params = array(":keyval" => $keyval, ":uniacid" => $_W['uniacid']);
                $idata = array();
                $idata['content'] = 'null';
                $idata['erurl'] = '';
                $mesaurl = array();
                $mesaurl['ident'] = '';
                $mesaurl['url'] = '';
                $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $_W['uniacid']));
                if ($config) {
                    $config['content'] = json_decode($config['content'], true);
                    if (!empty($config['content']) && !empty($config['content']['xcmessage'])) {
                        $acceurl = $config['content']['xcmessage'];
                        $mesaurl['ident'] = uniqid();
                        $referer = $_SERVER['HTTP_REFERER'];
                        $siteroot = $_W['siteroot'];
                        if (substr($referer, 0, 5) == 'https' && substr($siteroot, 0, 5) != 'https') {
                            $siteroot = 'https' . substr($siteroot, 4);
                        }
                        $mesaurl['url'] = $acceurl . '&op=getuserinfo&xcref=' . urlencode($siteroot . 'app/' . $this->createMobileUrl('sysasyn', array("op" => "binguser", "ident" => $mesaurl['ident'])));
                        $contents = ihttp_get($acceurl . '&op=gettempplatelist');
                        $mobalarrr = array("webnamex" => "小程序名", "nick" => "昵称", "content" => "内容", "appid" => "小程序id", "page" => "客服页面");
                        $rselut = json_decode($contents['content'], true);
                        $idata = $rselut['obj'];
                    }
                }
                $mesaurl = json_encode($mesaurl, true);
                $xc = pdo_fetch($sql, $params);
                if (!empty($xc)) {
                    $xc = json_decode($xc['content'], true);
                }
                if (empty($xc['prolevel'])) {
                    $xc['prolevel'] = -1;
                }
                $url = 'https://' . $_SERVER['HTTP_HOST'] . '/app/index.php?i=' . $_W['uniacid'] . '&c=entry&do=online&m=' . $_GPC['m'];
                include $this->template('Web/online');
                exit;
                break;
            case 'saveonline':
                $xc = pdo_get($tablename, array("uniacid" => $_W['uniacid'], "xkey" => $_GPC['xkey']));
                $request = array();
                $condition['uniacid'] = $_W['uniacid'];
                $condition['xkey'] = $_GPC['xkey'];
                $data = $_POST;
                $condition['content'] = $data;
                $condition['content'] = json_encode($condition['content']);
                if ($xc) {
                    $request = pdo_update($tablename, $condition, array("uniacid" => $_W['uniacid'], "xkey" => $_GPC['xkey']));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if ($request) {
                    xc_message(1, null);
                } else {
                    xc_message(-1, null);
                }
                break;
            case 'getnewuserlist':
                $strwhere = array();
                $strwhere['ident'] = $_GPC['ident'];
                $strwhere['uniacid'] = $_W['uniacid'];
                $strwhere['status'] = -1;
                $userlist = pdo_getall('xc_beauty_moban_user', $strwhere);
                if ($userlist) {
                    $arrids = array();
                    foreach ($userlist as &$itemuser) {
                        $itemuser['nickname'] = base64_decode($itemuser['nickname'], true);
                        $arrids[] = $itemuser['id'];
                    }
                    $result = pdo_update('xc_beauty_moban_user', array("status" => 1), array("id" => $arrids, "uniacid" => $_W['uniacid']));
                } else {
                    $userlist = array();
                }
                xc_message(1, $userlist);
                break;
            case 'testsmsv2':
                $templateParam = array("webnamex" => "小程序名", "nick" => "昵称", "content" => "内容", "appid" => $_W['account']['key'], "page" => "xc_beauty/pages/base/base?share=/xc_beauty/ui2/online/index");
                $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $_W['uniacid']));
                if ($config) {
                    $config['content'] = json_decode($config['content'], true);
                }
                $accessurl = $config['content']['xcmessage'] . '&op=sendv2';
                $template = $_GPC['xc'];
                $template['data'] = $_GPC['data'];
                $openids = $_GPC['userlist'];
                $sendopenid = array();
                foreach ($openids as $itemopenis) {
                    $sendopenid[] = $itemopenis['openid'];
                }
                if (empty($openids)) {
                    xc_message(-1, null, '请添加信息接受人');
                }
                $reslut2 = momessv2($accessurl, $template, $sendopenid, $templateParam);
                $temresult = json_decode($reslut2, true);
                xc_message($temresult['status'], null, $temresult['message']);
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($reslut2, true));
                break;
            case 'audit':
                $xtitle = '网站配置';
                $xkey = 'audit';
                $list = pdo_get($tablename, array("xkey" => $xkey, "uniacid" => $uniacid));
                if ($list) {
                    $list['content'] = json_decode($list['content'], true);
                }
                include $this->template('Web/audit');
                break;
            case 'saveaudit':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['xkey'] = 'audit';
                $condition['name'] = $_GPC['name'];
                $condition['content'] = array("bimg" => tomedia($_GPC['bimg']), "store_icon" => tomedia($_GPC['store_icon']), "store_name" => $_GPC['store_name'], "time_icon" => tomedia($_GPC['time_icon']), "store_time" => $_GPC['store_time'], "mobile_icon" => tomedia($_GPC['mobile_icon']), "store_mobile" => $_GPC['store_mobile'], "map_icon" => tomedia($_GPC['map_icon']), "store_map" => $_GPC['store_map'], "content" => $_GPC['content']);
                if (!empty($_GPC['status'])) {
                    $condition['content']['status'] = $_GPC['status'];
                } else {
                    $condition['content']['status'] = -1;
                }
                $condition['content'] = json_encode($condition['content']);
                $list = pdo_get($tablename, array("xkey" => "audit", "uniacid" => $uniacid));
                if ($list) {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                } else {
                    $request = pdo_insert($tablename, $condition);
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => -1, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebBanner()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange", "article");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_banner';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Banner/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Banner/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['bimg'] = $_GPC['bimg'];
                $condition['link'] = $_GPC['link'];
                $condition['appid'] = $_GPC['appid'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'article':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['title'])) {
                    $title = $_GPC['title'];
                    $condition['title LIKE'] = '%' . $_GPC['title'] . '%';
                }
                $request = pdo_getall('xc_beauty_article', $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall('xc_beauty_article', $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                $url = 'https://' . $_SERVER['HTTP_HOST'] . '/app/index.php?i=' . $uniacid . '&c=entry&do=index&m=' . $_GPC['m'];
                if ($list) {
                    foreach ($list as &$x) {
                        $x['url'] = $url;
                    }
                }
                include $this->template('Banner/article');
                break;
        }
    }
    public function doWebArticle()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange", "sort_service");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_article';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['title'])) {
                    $title = $_GPC['title'];
                    $condition['title LIKE'] = '%' . $_GPC['title'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                include $this->template('Article/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Article/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['title'] = $_GPC['title'];
                $condition['content'] = $_GPC['content'];
                $condition['type'] = $_GPC['type'];
                $condition['link'] = $_GPC['link'];
                $condition['btn'] = $_GPC['btn'];
                $condition['link_type'] = $_GPC['link_type'];
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'sort_service':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                if (!empty($_GPC['cid'])) {
                    $cid = $_GPC['cid'];
                    $condition['cid'] = $_GPC['cid'];
                }
                $request = pdo_getall('xc_beauty_service', $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall('xc_beauty_service', $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                $class = pdo_getall('xc_beauty_service_class', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
                $datalist = array();
                if ($class) {
                    foreach ($class as $x) {
                        $datalist[$x['id']] = $x['name'];
                    }
                }
                if ($list) {
                    foreach ($list as &$y) {
                        $y['cidname'] = $datalist[$y['cid']];
                    }
                }
                include $this->template('Article/sort_service');
                break;
        }
    }
    public function doWebUser()
    {
        $ops = array("list", "statuschange", "recharge", "service", "store_bind", "shop", "scorechange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_userinfo';
        switch ($op) {
            case 'list':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['nick'])) {
                    $nick = $_GPC['nick'];
                    $condition['nick LIKE'] = '%' . base64_encode($_GPC['nick']) . '%';
                }
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                if (!empty($_GPC['mobile'])) {
                    $mobile = $_GPC['mobile'];
                    $condition['mobile LIKE'] = '%' . $_GPC['mobile'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                $card = pdo_get('xc_beauty_config', array("xkey" => "card", "uniacid" => $uniacid));
                if ($card) {
                    $card['content'] = json_decode($card['content'], true);
                }
                if ($list) {
                    $store = pdo_getall('xc_beauty_store', array("status" => 1, "uniacid" => $uniacid));
                    $datalist = array();
                    if ($store) {
                        foreach ($store as $s) {
                            $datalist[$s['id']] = $s['name'];
                        }
                    }
                    $user_list = array();
                    foreach ($list as $l) {
                        $l['nick'] = base64_decode($l['nick']);
                        $user_list[$l['openid']] = $l;
                    }
                    foreach ($list as &$x) {
                        $x['nick'] = base64_decode($x['nick']);
                        $x['card_id'] = $x['createtime'];
                        if (!empty($x['store'])) {
                            if (!empty($datalist[$x['store']])) {
                                $x['store_name'] = $datalist[$x['store']];
                            } else {
                                $x['store_name'] = '门店不存在';
                            }
                        } else {
                            $x['store_name'] = '未绑定';
                        }
                        if ($x['shop'] == -1) {
                            $x['shop_name'] = '无权限';
                        } elseif ($x['shop'] == 1) {
                            $x['shop_name'] = '管理员';
                        } elseif ($x['shop'] == 2) {
                            $x['shop_name'] = '店长';
                            if (!empty($datalist[$x['shop_id']])) {
                                $x['shop_name'] .= '<br/>' . $datalist[$x['shop_id']];
                            } else {
                                $x['shop_name'] .= '<br/>门店不存在';
                            }
                        } elseif ($x['shop'] == 3) {
                            $x['shop_name'] = '店员';
                            if (!empty($datalist[$x['shop_id']])) {
                                $x['shop_name'] .= '<br/>' . $datalist[$x['shop_id']];
                            } else {
                                $x['shop_name'] .= '<br/>门店不存在';
                            }
                        }
                        if (!empty($x['share'])) {
                            $x['share_nick'] = $user_list[$x['share']]['nick'];
                        }
                    }
                }
                include $this->template('User/list');
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("shop" => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'scorechange':
                $request = pdo_update($tablename, array("score" => $_GPC['score']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'recharge':
                $userinfo = pdo_get('xc_laundry_userinfo', array("uniacid" => $uniacid, "id" => $_GPC['id']));
                $money = round(floatval($userinfo['money']) + floatval($_GPC['amount']));
                $condition['uniacid'] = $uniacid;
                $condition['openid'] = $userinfo['openid'];
                $condition['tid'] = 1;
                $condition['amount'] = $_GPC['amount'];
                $condition['g_price'] = 0;
                $condition['admin'] = 1;
                $condition['money'] = $money;
                $condition['status'] = 1;
                $request = pdo_insert('xc_laundry_order', $condition);
                if ($request) {
                    $request = pdo_update('xc_laundry_userinfo', array("money" => $money), array("uniacid" => $uniacid, "id" => $_GPC['id']));
                    $json = array("status" => 1, "msg" => "操作成功", "data" => array("money" => $money));
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'service':
                $tablename = 'xc_beauty_store';
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort desc,createtime DESC', array($pageindex, $pagesize));
                include $this->template('User/service');
                break;
            case 'store_bind':
                $request = pdo_update($tablename, array("store" => $_GPC['store']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'shop':
                $condition['shop'] = $_GPC['shop'];
                if (!empty($_GPC['shop_id'])) {
                    $condition['shop_id'] = $_GPC['shop_id'];
                }
                $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebService_class()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_service_class';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Service_class/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Service_class/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['bimg'] = $_GPC['bimg'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (!empty($_GPC['index'])) {
                    $condition['index'] = $_GPC['index'];
                } else {
                    $condition['index'] = -1;
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebService()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange", "service");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_service';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['cid !='] = -1;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                if (!empty($_GPC['cid'])) {
                    $cid = $_GPC['cid'];
                    $condition['cid'] = $_GPC['cid'];
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                $class = pdo_getall('xc_beauty_service_class', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
                $datalist = array();
                if ($class) {
                    foreach ($class as $x) {
                        $datalist[$x['id']] = $x['name'];
                    }
                }
                if ($list) {
                    foreach ($list as &$y) {
                        $y['cidname'] = $datalist[$y['cid']];
                    }
                }
                $theme = 1;
                $config = pdo_get('xc_beauty_config', array("xkey" => "theme", "uniacid" => $uniacid));
                if ($config) {
                    $config['content'] = json_decode($config['content'], true);
                    if (!empty($config['content']['theme'])) {
                        $theme = $config['content']['theme'];
                    }
                }
                include $this->template('Service/list');
                break;
            case 'edit':
                $class = pdo_getall('xc_beauty_service_class', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                    $list['bimg'] = json_decode($list['bimg'], true);
                    $list['kind'] = json_decode($list['kind'], true);
                    $list['content'] = json_decode($list['content'], true);
                    $list['store'] = json_decode($list['store'], true);
                    $list['parameter'] = json_decode($list['parameter'], true);
                    if ($list['flash_status'] == 1) {
                        $list['flash_times'] = array("start" => $list['flash_start'], "end" => $list['flash_end']);
                    }
                } else {
                    $list['status'] = 1;
                    $list['store_status'] = 1;
                    $list['content_type'] = 1;
                }
                include $this->template('Service/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['cid'] = $_GPC['cid'];
                $condition['simg'] = $_GPC['simg'];
                if (!empty($_GPC['xbimg'])) {
                    $condition['bimg'] = json_encode($_GPC['xbimg']);
                }
                $condition['price'] = $_GPC['price'];
                $condition['o_price'] = $_GPC['o_price'];
                $condition['sold'] = $_GPC['sold'];
                $condition['parameter'] = htmlspecialchars_decode($_GPC['parameter']);
                $condition['group_price'] = $_GPC['group_price'];
                $condition['group_number'] = $_GPC['group_number'];
                $condition['group_limit'] = $_GPC['group_limit'];
                $condition['group_total'] = $_GPC['group_total'];
                $condition['type'] = $_GPC['type'];
                $condition['level_one'] = $_GPC['level_one'];
                $condition['level_two'] = $_GPC['level_two'];
                $condition['level_three'] = $_GPC['level_three'];
                $condition['content'] = htmlspecialchars_decode($_GPC['content']);
                $condition['store_status'] = $_GPC['store_status'];
                $condition['store'] = htmlspecialchars_decode($_GPC['store']);
                $condition['service_time'] = $_GPC['service_time'];
                $condition['can_use'] = $_GPC['can_use'];
                $condition['content_type'] = $_GPC['content_type'];
                $condition['content2'] = $_GPC['content2'];
                $condition['flash_price'] = $_GPC['flash_price'];
                $condition['flash_start'] = $_GPC['flash_times']['start'];
                $condition['flash_end'] = $_GPC['flash_times']['end'];
                $condition['flash_member'] = $_GPC['flash_member'];
                $condition['flash_order'] = $_GPC['flash_order'];
                $condition['flash_shu'] = $_GPC['flash_shu'];
                $condition['group_stock'] = $_GPC['group_stock'];
                $condition['group_head_price'] = $_GPC['group_head_price'];
                if (!empty($_GPC['group_head_status'])) {
                    $condition['group_head_status'] = $_GPC['group_head_status'];
                } else {
                    $condition['group_head_status'] = -1;
                }
                if (!empty($_GPC['home'])) {
                    $condition['home'] = $_GPC['home'];
                } else {
                    $condition['home'] = -1;
                }
                if (!empty($_GPC['shop'])) {
                    $condition['shop'] = $_GPC['shop'];
                } else {
                    $condition['shop'] = -1;
                }
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['group_status'])) {
                    $condition['group_status'] = -1;
                } else {
                    $condition['group_status'] = $_GPC['group_status'];
                }
                if (empty($_GPC['group_index'])) {
                    $condition['group_index'] = -1;
                } else {
                    $condition['group_index'] = $_GPC['group_index'];
                }
                if (!empty($_GPC['flash_status'])) {
                    $condition['flash_status'] = $_GPC['flash_status'];
                } else {
                    $condition['flash_status'] = -1;
                }
                if (!empty($_GPC['flash_index'])) {
                    $condition['flash_index'] = $_GPC['flash_index'];
                } else {
                    $condition['flash_index'] = -1;
                }
                if (!empty($_GPC['sale_status'])) {
                    $condition['sale_status'] = $_GPC['sale_status'];
                } else {
                    $condition['sale_status'] = -1;
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'service':
                $tablename = 'xc_beauty_store';
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort desc,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Service/service');
                break;
        }
    }
    public function doWebTimes()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_times';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                if ($list) {
                    foreach ($list as &$x) {
                        switch ($x['week']) {
                            case 1:
                                $x['name'] = '周一';
                                break;
                            case 2:
                                $x['name'] = '周二';
                                break;
                            case 3:
                                $x['name'] = '周三';
                                break;
                            case 4:
                                $x['name'] = '周四';
                                break;
                            case 5:
                                $x['name'] = '周五';
                                break;
                            case 6:
                                $x['name'] = '周六';
                                break;
                            case 7:
                                $x['name'] = '周日';
                                break;
                        }
                    }
                }
                include $this->template('Times/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                    $list['content'] = json_decode($list['content'], true);
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Times/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['week'] = $_GPC['week'];
                $condition['content'] = htmlspecialchars_decode($_GPC['content']);
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebPrize()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange", "coupon");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_prize';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                if ($list) {
                    foreach ($list as &$x) {
                        if ($x['type'] == 1) {
                            $x['type_name'] = '优惠券';
                        } elseif ($x['type'] == 2) {
                            $x['type_name'] = '实物';
                        } elseif ($x['type'] == 3) {
                            $x['type_name'] = '谢谢参与';
                        }
                    }
                }
                include $this->template('Prize/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                    $list['type'] = 1;
                }
                include $this->template('Prize/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['type'] = $_GPC['type'];
                if ($condition['type'] == 1) {
                    $condition['name'] = $_GPC['cid_name'];
                    $condition['cid'] = $_GPC['cid'];
                } elseif ($condition['type'] == 2) {
                    $condition['name'] = $_GPC['prize_name'];
                } elseif ($condition['type'] == 3) {
                    $condition['name'] = $_GPC['empty_name'];
                }
                $condition['simg'] = $_GPC['simg'];
                $condition['times'] = $_GPC['times'];
                $condition['member'] = $_GPC['member'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'coupon':
                $tablename = 'xc_beauty_coupon';
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['type'] = 4;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                if ($list) {
                    foreach ($list as &$x) {
                        $x['times'] = json_decode($x['times'], true);
                        if ($x['type'] == 1) {
                            $x['type_name'] = '通用';
                        } elseif ($x['type'] == 2) {
                            $x['type_name'] = '会员专享';
                        } elseif ($x['type'] == 3) {
                            $x['type_name'] = '积分兑换';
                        } elseif ($x['type'] == 4) {
                            $x['type_name'] = '抽奖';
                        }
                    }
                }
                include $this->template('Prize/coupon');
                break;
        }
    }
    public function doWebCoupon()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_coupon';
        switch ($op) {
            case 'list':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                if ($list) {
                    foreach ($list as &$x) {
                        $x['times'] = json_decode($x['times'], true);
                        if ($x['type'] == 1) {
                            $x['type_name'] = '通用';
                        } elseif ($x['type'] == 2) {
                            $x['type_name'] = '会员专享';
                        } elseif ($x['type'] == 3) {
                            $x['type_name'] = '积分兑换';
                        } elseif ($x['type'] == 4) {
                            $x['type_name'] = '抽奖';
                        }
                    }
                }
                include $this->template('Coupon/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                    $list['times'] = json_decode($list['times'], true);
                } else {
                    $list['status'] = 1;
                    $list['total'] = -1;
                }
                include $this->template('Coupon/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['condition'] = $_GPC['condition'];
                $condition['times'] = json_encode($_GPC['times']);
                $condition['type'] = $_GPC['type'];
                if ($_GPC['type'] == 3) {
                    $condition['score'] = $_GPC['score'];
                } else {
                    $condition['score'] = null;
                }
                if (!empty($_GPC['istotal'])) {
                    $condition['total'] = $_GPC['total'];
                } else {
                    $condition['total'] = -1;
                }
                if (!empty($_GPC['status'])) {
                    $condition['status'] = $_GPC['status'];
                } else {
                    $condition['status'] = -1;
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebDiscuss()
    {
        $ops = array("list", "statuschange", "delete", "list2", "list3", "list4");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_discuss';
        switch ($op) {
            case 'list':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['type'] = 1;
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                $service = pdo_getall('xc_beauty_service', array("uniacid" => $uniacid));
                $datalist = array();
                if ($service) {
                    foreach ($service as $x) {
                        $datalist[$x['id']] = $x;
                    }
                }
                foreach ($list as &$y) {
                    $y['pname'] = $datalist[$y['pid']]['name'];
                    $y['imgs'] = json_decode($y['imgs'], true);
                }
                include $this->template('Discuss/list');
                break;
            case 'list2':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['type'] = 2;
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                $service = pdo_getall('xc_beauty_store_member', array("uniacid" => $uniacid));
                $datalist = array();
                if ($service) {
                    foreach ($service as $x) {
                        $datalist[$x['id']] = $x;
                    }
                }
                foreach ($list as &$y) {
                    $y['name'] = $datalist[$y['pid']]['name'];
                }
                include $this->template('Discuss/list2');
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $discuss = pdo_get($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $service = pdo_get('xc_beauty_service', array("status" => 1, "id" => $discuss['pid']));
                    if ($service) {
                        $data['discuss_total'] = $service['discuss_total'] - 1;
                        if ($discuss['score'] == 1) {
                            $data['good_total'] = $service['good_total'] - 1;
                        } elseif ($discuss['score'] == 2) {
                            $data['middle_total'] = $service['middle_total'] - 1;
                        } elseif ($discuss['score'] == 3) {
                            $data['bad_total'] = $service['bad_total'] - 1;
                        }
                        pdo_update('xc_beauty_service', $data, array("status" => 1, "id" => $discuss['pid']));
                    }
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'list3':
                $tablename = 'xc_beauty_onlines';
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'updatetime DESC,id DESC', array($pageindex, $pagesize));
                include $this->template('Discuss/list3');
                break;
            case 'list4':
                $id = $_GPC['id'];
                $tablename = 'xc_beauty_online_log';
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['pid'] = $_GPC['id'];
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'id DESC', array($pageindex, $pagesize));
                if ($list) {
                    foreach ($list as &$x) {
                        if ($x['type'] == 1) {
                            $x['content'] = base64_decode($x['content']);
                            $x['content'] = emoji($x['content'], $_GPC['m']);
                        }
                    }
                }
                include $this->template('Discuss/list4');
                break;
        }
    }
    public function doWebStore()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange", "store");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_store';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort desc,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Store/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                    $list['map'] = json_decode($list['map'], true);
                    $list['content'] = json_decode($list['content'], true);
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Store/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['simg'] = $_GPC['simg'];
                $condition['mobile'] = $_GPC['mobile'];
                $condition['address'] = $_GPC['address'];
                $condition['map'] = array("longitude" => $_GPC['longitude'], "latitude" => $_GPC['latitude']);
                $condition['map'] = json_encode($condition['map']);
                $condition['plan_date'] = $_GPC['plan_date'];
                $condition['content'] = htmlspecialchars_decode($_GPC['content']);
                $condition['sms'] = $_GPC['sms'];
                $condition['sort'] = $_GPC['sort'];
                $condition['machine_code'] = $_GPC['machine_code'];
                $condition['msign'] = $_GPC['msign'];
                $condition['sn'] = $_GPC['sn'];
                if (!empty($_GPC['print_status'])) {
                    $condition['print_status'] = $_GPC['print_status'];
                } else {
                    $condition['print_status'] = -1;
                }
                if (!empty($_GPC['status'])) {
                    $condition['status'] = $_GPC['status'];
                } else {
                    $condition['status'] = -1;
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'store':
                $tablename = 'xc_beauty_store';
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort desc,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Store/store');
                break;
        }
    }
    public function doWebStore_service()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_store_service';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Store_service/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Store_service/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['price'] = $_GPC['price'];
                $condition['member'] = $_GPC['member'];
                if (!empty($_GPC['home'])) {
                    $condition['home'] = $_GPC['home'];
                } else {
                    $condition['home'] = -1;
                }
                if (!empty($_GPC['shop'])) {
                    $condition['shop'] = $_GPC['shop'];
                } else {
                    $condition['shop'] = -1;
                }
                if (!empty($_GPC['sale_status'])) {
                    $condition['sale_status'] = $_GPC['sale_status'];
                } else {
                    $condition['sale_status'] = -1;
                }
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebStore_member()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange", "service");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_store_member';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                if (!empty($_GPC['cid'])) {
                    $cid = $_GPC['cid'];
                    $condition['cid'] = $_GPC['cid'];
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                $class = pdo_getall('xc_beauty_store', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime');
                if ($list) {
                    $datalist = array();
                    if ($class) {
                        foreach ($class as $c) {
                            $datalist[$c['id']] = $c;
                        }
                    }
                    foreach ($list as &$x) {
                        $x['cidname'] = $datalist[$x['cid']]['name'];
                    }
                }
                include $this->template('Store_member/list');
                break;
            case 'edit':
                $class = pdo_getall('xc_beauty_store', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime');
                $pai_list = pdo_getall('xc_beauty_pai', array("status" => 1, "uniacid" => $uniacid));
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                    if ($list) {
                        $list['service'] = json_decode($list['service'], true);
                    }
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Store_member/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['simg'] = $_GPC['simg'];
                $condition['cid'] = $_GPC['cid'];
                $condition['task'] = $_GPC['task'];
                $condition['service'] = htmlspecialchars_decode($_GPC['service']);
                $condition['tag'] = $_GPC['tag'];
                $condition['content'] = $_GPC['content'];
                $condition['pai1'] = $_GPC['pai1'];
                $condition['pai2'] = $_GPC['pai2'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['pai_status'])) {
                    $condition['pai_status'] = -1;
                } else {
                    $condition['pai_status'] = $_GPC['pai_status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'service':
                $tablename = 'xc_beauty_store_service';
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 6;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Store_member/service');
                break;
        }
    }
    public function doWebStore_pai()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_pai';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'id DESC', array($pageindex, $pagesize));
                include $this->template('Store_pai/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                    if ($list) {
                        $detail = pdo_getall('xc_beauty_pai_detail', array("pid" => $_GPC['id'], "uniacid" => $uniacid));
                        if ($detail) {
                            $content = array();
                            foreach ($detail as &$x) {
                                $content[$x['weeknum']] = $x;
                            }
                            $list['content'] = $content;
                        }
                    }
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Store_pai/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                $condition['midflytime'] = date('Y-m-d H:i:s');
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                    if (!empty($request)) {
                        $id = pdo_insertid();
                        $content = json_decode(htmlspecialchars_decode($_GPC['content']), true);
                        foreach ($content as $cc) {
                            pdo_insert('xc_beauty_pai_detail', array("uniacid" => $uniacid, "pid" => $id, "weeknum" => $cc['weeknum'], "time1start" => $cc['time1start'], "time1end" => $cc['time1end'], "time2start" => $cc['time2start'], "time2end" => $cc['time2end'], "time3start" => $cc['time3start'], "time3end" => $cc['time3end'], "time4start" => $cc['time4start'], "time4end" => $cc['time4end'], "status" => $cc['status']));
                        }
                    }
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                    if ($request) {
                        $content = json_decode(htmlspecialchars_decode($_GPC['content']), true);
                        foreach ($content as $cc) {
                            pdo_update('xc_beauty_pai_detail', array("time1start" => $cc['time1start'], "time1end" => $cc['time1end'], "time2start" => $cc['time2start'], "time2end" => $cc['time2end'], "time3start" => $cc['time3start'], "time3end" => $cc['time3end'], "time4start" => $cc['time4start'], "time4end" => $cc['time4end'], "status" => $cc['status']), array("uniacid" => $uniacid, "pid" => $_GPC['id'], "weeknum" => $cc['weeknum']));
                        }
                    }
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    pdo_delete('xc_beauty_pai_detail', array("uniacid" => $uniacid, "pid" => $_GPC['id']));
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebStore_order()
    {
        $ops = array("list", "statuschange", "add_orders", "timeschange", "yan");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_order';
        switch ($op) {
            case 'list':
                $condition = array();
                $condition['status IN'] = array(-1, 1);
                $condition['order_type'] = 4;
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['out_trade_no'])) {
                    $out_trade_no = $_GPC['out_trade_no'];
                    $condition['out_trade_no LIKE'] = '%' . $_GPC['out_trade_no'] . '%';
                }
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                if (!empty($_GPC['use'])) {
                    $use = $_GPC['use'];
                    $condition['use'] = $_GPC['use'];
                }
                if (!empty($_GPC['content'])) {
                    $content = $_GPC['content'];
                    $condition['userinfo LIKE'] = '%' . $_GPC['content'] . '%';
                }
                if (!empty($_GPC['pay_type'])) {
                    $pay_type = $_GPC['pay_type'];
                    $condition['pay_type'] = $_GPC['pay_type'];
                }
                if (!empty($_GPC['store_id'])) {
                    $store_id = $_GPC['store_id'];
                    $condition['store'] = $_GPC['store_id'];
                }
                if (!empty($_GPC['times'])) {
                    $times = $_GPC['times'];
                    $condition['createtime >='] = $_GPC['times']['start'];
                    $condition['createtime <='] = $_GPC['times']['end'];
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                $store = pdo_getall('xc_beauty_store', array("uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
                $store_list = array();
                if ($store) {
                    foreach ($store as $s) {
                        $store_list[$s['id']] = $s;
                    }
                }
                if ($list) {
                    $service = pdo_getall('xc_beauty_store_service', array("uniacid" => $uniacid));
                    $datalist = array();
                    if ($service) {
                        foreach ($service as $x2) {
                            $datalist[$x2['id']] = $x2;
                        }
                    }
                    $member = pdo_getall('xc_beauty_store_member', array("uniacid" => $uniacid));
                    $member_list = array();
                    if ($member) {
                        foreach ($member as $m) {
                            $member_list[$m['id']] = $m;
                        }
                    }
                    $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                    $title = '';
                    if ($config) {
                        $config['content'] = json_decode($config['content'], true);
                        $title = $config['content']['title'];
                    }
                    foreach ($list as &$x) {
                        $x['userinfo'] = json_decode($x['userinfo'], true);
                        if ($x['pay_type'] == 1) {
                            $x['pay_name'] = '微信支付';
                        } else {
                            if ($x['pay_type'] == 2) {
                                $x['pay_name'] = '余额支付';
                            }
                        }
                        $x['service'] = $datalist[$x['pid']]['name'];
                        if ($x['store'] == -1) {
                            $x['store_name'] = $title;
                        } else {
                            $x['store_name'] = $store_list[$x['store']]['name'];
                        }
                        $x['member_name'] = $member_list[$x['member']]['name'];
                        $x['he_log'] = json_decode($x['he_log'], true);
                    }
                }
                include $this->template('Store_order/list');
                break;
            case 'statuschange':
                $order = pdo_get($tablename, array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC['id']));
                $condition['is_use'] = intval($order['is_use']) + 1;
                if (intval($condition['is_use']) == intval($order['can_use'])) {
                    $condition['use'] = 1;
                }
                if (!empty($order['he_log'])) {
                    $order['he_log'] = json_decode($order['he_log'], true);
                    if (!is_array($order['he_log'])) {
                        $order['he_log'] = array();
                    }
                    $order['he_log'][] = array("name" => "后台", "time" => date('Y-m-d H:i:s'));
                    $condition['he_log'] = json_encode($order['he_log']);
                } else {
                    $order['he_log'] = array();
                    $order['he_log'][] = array("name" => "后台", "time" => date('Y-m-d H:i:s'));
                    $condition['he_log'] = json_encode($order['he_log']);
                }
                $request = pdo_update($tablename, $condition, array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC['id']));
                if ($request) {
                    if (!empty($condition['use'])) {
                        $share = pdo_getall('xc_beauty_share', array("status" => -1, "uniacid" => $uniacid, "out_trade_no" => $order['out_trade_no']));
                        if ($share) {
                            foreach ($share as $x) {
                                $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "uniacid" => $uniacid, "openid" => $x['openid']));
                                if ($userinfo) {
                                    $share_amount = round(floatval($userinfo['share_amount']) + floatval($x['amount']), 2);
                                    $share_o_amount = round(floatval($userinfo['share_o_amount']) + floatval($x['amount']), 2);
                                    pdo_update('xc_beauty_userinfo', array("share_amount" => $share_amount, "share_o_amount" => $share_o_amount), array("status" => 1, "uniacid" => $uniacid, "openid" => $x['openid']));
                                }
                                pdo_update('xc_beauty_share', array("status" => 1), array("uniacid" => $uniacid, "id" => $x['id']));
                            }
                        }
                    }
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'timeschange':
                $request = pdo_update($tablename, array("is_use" => $_GPC['time'], "use" => -1), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'yan':
                $order = pdo_get($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($order) {
                    if (!empty($order['wq_out_trade_no'])) {
                        $pay = pdo_get('core_paylog', array("uniacid" => $uniacid, "openid" => $order['openid'], "tid" => $order['wq_out_trade_no']));
                    } else {
                        $pay = pdo_get('core_paylog', array("uniacid" => $uniacid, "openid" => $order['openid'], "tid" => $order['out_trade_no']));
                    }
                    if ($pay) {
                        if ($pay['status'] == 1) {
                            pdo_update($tablename, array("status" => 1), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                            $json = array("status" => 1, "msg" => "已支付");
                            echo json_encode($json);
                        } else {
                            $setting = uni_setting_load('payment', $_W['uniacid']);
                            $params = array("appid" => $_W['account']['key'], "mch_id" => $setting['payment']['wechat']['mchid'], "nonce_str" => random(32));
                            $params['out_trade_no'] = $pay['uniontid'];
                            ksort($params);
                            $string = array2url($params);
                            $string = $string . "&key={$setting['payment']['wechat']['signkey']}";
                            $string = md5($string);
                            $params['sign'] = strtoupper($string);
                            load()->func('communication');
                            $xml = array2xml($params);
                            $result = ihttp_request('https://api.mch.weixin.qq.com/pay/orderquery', $xml, $params);
                            $result = xml2array($result['content']);
                            if (is_error($result)) {
                                $json = array("status" => 1, "msg" => $result['return_msg']);
                                echo json_encode($json);
                                exit;
                            }
                            if ($result['result_code'] != 'SUCCESS') {
                                $json = array("status" => 1, "msg" => $result['err_code_des']);
                                echo json_encode($json);
                                exit;
                            }
                            if ($result['trade_state'] == 'SUCCESS') {
                                $total_fee = $result['total_fee'] / 100;
                                if (floatval($total_fee) == floatval($order['wxpay'])) {
                                    pdo_update($tablename, array("status" => 1), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                                    $json = array("status" => 1, "msg" => $result['trade_state_desc']);
                                    echo json_encode($json);
                                } else {
                                    $json = array("status" => 1, "msg" => "金额不同");
                                    echo json_encode($json);
                                }
                            } else {
                                $json = array("status" => 1, "msg" => $result['trade_state_desc']);
                                echo json_encode($json);
                                exit;
                            }
                        }
                    } else {
                        $json = array("status" => 1, "msg" => "未支付");
                        echo json_encode($json);
                    }
                } else {
                    $json = array("status" => 1, "msg" => "订单不存在");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebPrize_order()
    {
        $ops = array("list", "statuschange", "add_orders");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_rotate_log';
        switch ($op) {
            case 'list':
                $condition = array();
                $condition['type'] = 2;
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                if (!empty($_GPC['title'])) {
                    $title = $_GPC['title'];
                    $condition['title LIKE'] = '%' . $_GPC['title'] . '%';
                }
                if (!empty($_GPC['status'])) {
                    $status = $_GPC['status'];
                    $condition['status'] = $_GPC['status'];
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                include $this->template('Prize_order/list');
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => 1), array("uniacid" => $uniacid, "id" => $_GPC['id']));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebNav()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_nav';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Nav/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Nav/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['simg'] = $_GPC['simg'];
                $condition['link'] = $_GPC['link'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebPick_class()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_pick_class';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                include $this->template('Pick_class/list');
                break;
            case 'edit':
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                $dm = array("shuang_liu" => 1, "ben_tian" => 1, "lv_chang" => 1);
                include $this->template('Pick_class/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebPick()
    {
        $ops = array("list", "edit", "savemodel", "delete", "statuschange");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_pick_service';
        switch ($op) {
            case 'list':
                $version_id = $_GPC['version_id'];
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['xname'])) {
                    $xname = $_GPC['xname'];
                    $condition['name LIKE'] = '%' . $_GPC['xname'] . '%';
                }
                if (!empty($_GPC['cid'])) {
                    $cid = $_GPC['cid'];
                    $condition['cid'] = $_GPC['cid'];
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'sort DESC,createtime DESC', array($pageindex, $pagesize));
                $class = pdo_getall('xc_beauty_pick_class', array("uniacid" => $uniacid));
                $datalist = array();
                if ($class) {
                    foreach ($class as $c) {
                        $datalist[$c['id']] = $c;
                    }
                }
                if ($list) {
                    foreach ($list as &$x) {
                        $x['cid_name'] = $datalist[$x['cid']]['name'];
                    }
                }
                include $this->template('Pick/list');
                break;
            case 'edit':
                $class = pdo_getall('xc_beauty_pick_class', array("status" => 1, "uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
                if (!empty($_GPC['id'])) {
                    $list = pdo_get($tablename, array("id" => $_GPC['id']));
                } else {
                    $list['status'] = 1;
                }
                include $this->template('Pick/edit');
                break;
            case 'savemodel':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                $condition['name'] = $_GPC['name'];
                $condition['cid'] = $_GPC['cid'];
                $condition['price'] = $_GPC['price'];
                $condition['unit'] = $_GPC['unit'];
                if (empty($_GPC['status'])) {
                    $condition['status'] = -1;
                } else {
                    $condition['status'] = $_GPC['status'];
                }
                if (empty($_GPC['sort'])) {
                    $condition['sort'] = 0;
                } else {
                    $condition['sort'] = $_GPC['sort'];
                }
                if (empty($_GPC['id'])) {
                    $request = pdo_insert($tablename, $condition);
                } else {
                    $request = pdo_update($tablename, $condition, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                }
                if (!empty($request)) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array($_GPC['name'] => $_GPC['status']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebPick_order()
    {
        $ops = array("list", "statuschange", "add_orders");
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'list';
        $tablename = 'xc_beauty_pick_order';
        switch ($op) {
            case 'list':
                $condition = array();
                $condition['uniacid'] = $uniacid;
                if (!empty($_GPC['out_trade_no'])) {
                    $out_trade_no = $_GPC['out_trade_no'];
                    $condition['out_trade_no LIKE'] = '%' . $_GPC['out_trade_no'] . '%';
                }
                if (!empty($_GPC['openid'])) {
                    $openid = $_GPC['openid'];
                    $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
                }
                if (!empty($_GPC['status'])) {
                    $status = $_GPC['status'];
                    $condition['status'] = $_GPC['status'];
                }
                $request = pdo_getall($tablename, $condition);
                $total = count($request);
                if (!isset($_GPC['page'])) {
                    $pageindex = 1;
                } else {
                    $pageindex = intval($_GPC['page']);
                }
                $pagesize = 15;
                $pager = pagination($total, $pageindex, $pagesize);
                $list = pdo_getall($tablename, $condition, array(), '', 'createtime DESC', array($pageindex, $pagesize));
                if ($list) {
                    $service = pdo_getall('xc_beauty_pick_service', array("uniacid" => $uniacid));
                    $datalist = array();
                    if ($service) {
                        foreach ($service as $x2) {
                            $datalist[$x2['id']] = $x2;
                        }
                    }
                    $store = pdo_getall('xc_beauty_store', array("uniacid" => $uniacid));
                    $store_list = array();
                    if ($store) {
                        foreach ($store as $s) {
                            $store_list[$s['id']] = $s;
                        }
                    }
                    foreach ($list as &$x) {
                        $x['store_name'] = $store_list[$x['store']]['name'];
                        $x['pid'] = json_decode($x['pid'], true);
                        if (!empty($x['pid']) && is_array($x['pid'])) {
                            foreach ($x['pid'] as &$pp) {
                                $pp['name'] = $datalist[$pp['id']]['name'];
                            }
                        }
                    }
                }
                include $this->template('Pick_order/list');
                break;
            case 'statuschange':
                $request = pdo_update($tablename, array("status" => 1), array("uniacid" => $uniacid, "id" => $_GPC['id']));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
            case 'delete':
                $request = pdo_delete($tablename, array("id" => $_GPC['id'], "uniacid" => $uniacid));
                if ($request) {
                    $json = array("status" => 1, "msg" => "操作成功");
                    echo json_encode($json);
                } else {
                    $json = array("status" => 0, "msg" => "操作失败");
                    echo json_encode($json);
                }
                break;
        }
    }
    public function doWebExport()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $condition['status'] = 1;
        $condition['order_type'] = 1;
        $condition['uniacid'] = $uniacid;
        if (!empty($_GPC['out_trade_no'])) {
            $condition['out_trade_no LIKE'] = '%' . $_GPC['out_trade_no'] . '%';
        }
        if (!empty($_GPC['openid'])) {
            $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
        }
        if (!empty($_GPC['use'])) {
            $condition['use'] = $_GPC['use'];
        }
        if (!empty($_GPC['content'])) {
            $condition['userinfo LIKE'] = '%' . $_GPC['content'] . '%';
        }
        if (!empty($_GPC['pay_type'])) {
            $condition['pay_type'] = $_GPC['pay_type'];
        }
        if (!empty($_GPC['store_id'])) {
            $condition['store'] = $_GPC['store_id'];
        }
        if (!empty($_GPC['times'])) {
            $times = $_GPC['times'];
            $condition['createtime >='] = $_GPC['times']['start'];
            $condition['createtime <='] = $_GPC['times']['end'];
        }
        $order = pdo_getall('xc_beauty_order', $condition, array(), '', 'createtime DESC');
        if ($order) {
            $store = pdo_getall('xc_beauty_store', array("uniacid" => $uniacid), array(), '', 'sort DESC,createtime DESC');
            $store_list = array();
            if ($store) {
                foreach ($store as $s) {
                    $store_list[$s['id']] = $s;
                }
            }
            $service = pdo_getall('xc_beauty_service', array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2['id']] = $x2;
                }
            }
            $member = pdo_getall('xc_beauty_store_member', array("uniacid" => $uniacid));
            $member_list = array();
            if ($member) {
                foreach ($member as $m) {
                    $member_list[$m['id']] = $m;
                }
            }
            foreach ($order as &$x) {
                $x['service'] = $datalist[$x['pid']]['name'];
                $x['userinfo'] = json_decode($x['userinfo'], true);
                $x['store_name'] = $store_list[$x['store']]['name'];
                $x['member_name'] = $member_list[$x['member']]['name'];
            }
            header('Content-type: application/vnd.ms-excel; charset=utf8');
            header('Content-Disposition: attachment; filename=order.xls');
            $data = '<tr><th>订单号</th><th>订单支付方式</th><th>服务项目</th><th>门店</th><th>店员</th><th>优惠（元）</th><th>应付款（元）</th><th>实付款（元）</th><th>总件数</th><th>姓名</th><th>手机号</th><th>地址</th><th>日期</th><th>备注</th></tr>';
            foreach ($order as $v) {
                if ($v['pay_type'] == 1) {
                    $v['pay_name'] = '余额支付';
                } else {
                    if ($v['pay_type'] == 2) {
                        $v['pay_name'] = '微信支付';
                    }
                }
                $data = $data . '<tr>';
                $data = $data . '<td style=\'vnd.ms-excel.numberformat:@\'>' . $v['out_trade_no'] . '</td>';
                $data = $data . '<td style=\'vnd.ms-excel.numberformat:@\'>' . $v['pay_name'] . '</td>';
                $data = $data . '<td>' . $v['service'] . '</td>';
                $data = $data . '<td>' . $v['store_name'] . '</td>';
                $data = $data . '<td>' . $v['member_name'] . '</td>';
                $data = $data . '<td>' . $v['coupon_price'] . '</td>';
                $data = $data . '<td>' . $v['amount'] . '</td>';
                $data = $data . '<td>' . $v['o_amount'] . '</td>';
                $data = $data . '<td>' . $v['total'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['name'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['mobile'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['address'] . '</td>';
                $data = $data . '<td>' . $v['plan_date'] . '</td>';
                $data = $data . '<td>' . $v['content'] . '</td>';
                $data = $data . '</tr>';
            }
            $data = '<table border=\'1\'>' . $data . '</table>';
            echo $data . '	';
        } else {
            echo '无订单记录';
        }
    }
    public function doWebExport2()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $group_id = array();
        $group = pdo_getall('xc_beauty_group', array("status" => 1, "uniacid" => $uniacid));
        if ($group) {
            foreach ($group as $g) {
                $group_id[] = $g['id'];
            }
        }
        $order = pdo_getall('xc_beauty_order', array("status" => 1, "uniacid" => $uniacid, "order_type" => 3, "group IN" => $group_id), array(), '', 'createtime DESC');
        if ($order) {
            $service = pdo_getall('xc_beauty_service', array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2['id']] = $x2;
                }
            }
            foreach ($order as &$x) {
                $x['service'] = $datalist[$x['pid']]['name'];
                $x['userinfo'] = json_decode($x['userinfo'], true);
            }
            header('Content-type: application/vnd.ms-excel; charset=utf8');
            header('Content-Disposition: attachment; filename=order.xls');
            $data = '<tr><th>订单号</th><th>订单支付方式</th><th>服务项目</th><th>优惠（元）</th><th>应付款（元）</th><th>实付款（元）</th><th>总件数</th><th>姓名</th><th>手机号</th><th>地址</th><th>日期</th><th>备注</th></tr>';
            foreach ($order as $v) {
                if ($v['pay_type'] == 1) {
                    $v['pay_name'] = '余额支付';
                } elseif ($v['pay_type'] == 2) {
                    $v['pay_name'] = '微信支付';
                }
                $data = $data . '<tr>';
                $data = $data . '<td style=\'vnd.ms-excel.numberformat:@\'>' . $v['out_trade_no'] . '</td>';
                $data = $data . '<td style=\'vnd.ms-excel.numberformat:@\'>' . $v['pay_name'] . '</td>';
                $data = $data . '<td>' . $v['service'] . '</td>';
                $data = $data . '<td>' . $v['coupon_price'] . '</td>';
                $data = $data . '<td>' . $v['amount'] . '</td>';
                $data = $data . '<td>' . $v['o_amount'] . '</td>';
                $data = $data . '<td>' . $v['total'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['name'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['mobile'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['address'] . '</td>';
                $data = $data . '<td>' . $v['plan_date'] . '</td>';
                $data = $data . '<td>' . $v['content'] . '</td>';
                $data = $data . '</tr>';
            }
            $data = '<table border=\'1\'>' . $data . '</table>';
            echo $data . '	';
        } else {
            echo '无订单记录';
        }
    }
    public function doWebExport3()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $condition['status'] = 1;
        $condition['order_type'] = 4;
        $condition['uniacid'] = $uniacid;
        if (!empty($_GPC['out_trade_no'])) {
            $condition['out_trade_no LIKE'] = '%' . $_GPC['out_trade_no'] . '%';
        }
        if (!empty($_GPC['openid'])) {
            $condition['openid LIKE'] = '%' . $_GPC['openid'] . '%';
        }
        if (!empty($_GPC['use'])) {
            $condition['use'] = $_GPC['use'];
        }
        if (!empty($_GPC['content'])) {
            $condition['userinfo LIKE'] = '%' . $_GPC['content'] . '%';
        }
        if (!empty($_GPC['pay_type'])) {
            $condition['pay_type'] = $_GPC['pay_type'];
        }
        if (!empty($_GPC['store_id'])) {
            $condition['store'] = $_GPC['store_id'];
        }
        if (!empty($_GPC['times'])) {
            $times = $_GPC['times'];
            $condition['createtime >='] = $_GPC['times']['start'];
            $condition['createtime <='] = $_GPC['times']['end'];
        }
        $order = pdo_getall('xc_beauty_order', $condition, array(), '', 'createtime DESC');
        if ($order) {
            $service = pdo_getall('xc_beauty_store_service', array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2['id']] = $x2;
                }
            }
            $store = pdo_getall('xc_beauty_store', array("uniacid" => $uniacid));
            $store_list = array();
            if ($store) {
                foreach ($store as $s) {
                    $store_list[$s['id']] = $s;
                }
            }
            $member = pdo_getall('xc_beauty_store_member', array("uniacid" => $uniacid));
            $member_list = array();
            if ($member) {
                foreach ($member as $m) {
                    $member_list[$m['id']] = $m;
                }
            }
            $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
            $title = '';
            foreach ($order as &$x) {
                $x['service'] = $datalist[$x['pid']]['name'];
                $x['userinfo'] = json_decode($x['userinfo'], true);
                if ($x['store'] == -1) {
                    $x['store_name'] = $title;
                } else {
                    $x['store_name'] = $store_list[$x['store']]['name'];
                }
                $x['member_name'] = $member_list[$x['member']]['name'];
            }
            header('Content-type: application/vnd.ms-excel; charset=utf8');
            header('Content-Disposition: attachment; filename=order.xls');
            $data = '<tr><th>订单号</th><th>订单支付方式</th><th>服务项目</th><th>门店</th><th>店员</th><th>优惠（元）</th><th>应付款（元）</th><th>实付款（元）</th><th>总件数</th><th>姓名</th><th>手机号</th><th>地址</th><th>日期</th><th>备注</th></tr>';
            foreach ($order as $v) {
                if ($v['pay_type'] == 2) {
                    $v['pay_name'] = '余额支付';
                } elseif ($v['pay_type'] == 1) {
                    $v['pay_name'] = '微信支付';
                }
                $data = $data . '<tr>';
                $data = $data . '<td style=\'vnd.ms-excel.numberformat:@\'>' . $v['out_trade_no'] . '</td>';
                $data = $data . '<td style=\'vnd.ms-excel.numberformat:@\'>' . $v['pay_name'] . '</td>';
                $data = $data . '<td>' . $v['service'] . '</td>';
                $data = $data . '<td>' . $v['store_name'] . '</td>';
                $data = $data . '<td>' . $v['member_name'] . '</td>';
                $data = $data . '<td>' . $v['coupon_price'] . '</td>';
                $data = $data . '<td>' . $v['amount'] . '</td>';
                $data = $data . '<td>' . $v['o_amount'] . '</td>';
                $data = $data . '<td>' . $v['total'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['name'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['mobile'] . '</td>';
                $data = $data . '<td>' . $v['userinfo']['address'] . '</td>';
                $data = $data . '<td>' . $v['plan_date'] . '</td>';
                $data = $data . '<td>' . $v['content'] . '</td>';
                $data = $data . '</tr>';
            }
            $data = '<table border=\'1\'>' . $data . '</table>';
            echo $data . '	';
        } else {
            echo '无订单记录';
        }
    }
    public function doWebPost()
    {
        global $_GPC, $_W;
        load()->func('communication');
        $url = $_GPC['url'];
        $sms = pdo_get('xc_beauty_config', array("xkey" => "sms"));
        if ($sms) {
            $sms['content'] = json_decode($sms['content'], true);
            $customize = $sms['content']['customize'];
            $post = $sms['content']['post'];
            $header = get_headers($url . '&_xcdebug=1', 1);
            if (strpos($header[0], '301') || strpos($header[0], '302')) {
                if (is_array($header['Location'])) {
                    $url = $header['Location'][count($header['Location']) - 1];
                } else {
                    $url = $header['Location'];
                }
            }
            if (is_array($post) && !empty($post)) {
                $post = json_encode($post);
                if (is_array($customize)) {
                    foreach ($customize as $x) {
                        $post = str_replace('{{' . $x['attr'] . '}}', $x['value'], $post);
                    }
                }
                $post = str_replace('{{webnamex}}', '美容', $post);
                $post = str_replace('{{trade}}', '1220171127101100000017', $post);
                $post = str_replace('{{amount}}', '199元', $post);
                $post = str_replace('{{namex}}', '张三', $post);
                $post = str_replace('{{phonex}}', '18888888888', $post);
                $post = str_replace('{{addrx}}', '中国北京', $post);
                $post = str_replace('{{datex}}', date('Y-m-d H:i'), $post);
                $post = json_decode($post, true);
                $data = array();
                foreach ($post as $x2) {
                    $data[$x2['attr']] = $x2['value'];
                }
                $request_post = ihttp_post($url, $data);
            }
            $get = $sms['content']['get'];
            if (is_array($get) && !empty($get)) {
                $get = json_encode($get);
                if (is_array($customize)) {
                    foreach ($customize as $x) {
                        $get = str_replace('{{' . $x['attr'] . '}}', $x['value'], $get);
                    }
                }
                $get = str_replace('{{webnamex}}', '美容', $get);
                $get = str_replace('{{trade}}', '1220171127101100000017', $get);
                $get = str_replace('{{amount}}', '199元', $get);
                $get = str_replace('{{namex}}', '张三', $get);
                $get = str_replace('{{phonex}}', '18888888888', $get);
                $get = str_replace('{{addrx}}', '中国北京', $get);
                $get = str_replace('{{datex}}', date('Y-m-d H:i'), $get);
                $get = json_decode($get, true);
                $url_data = '';
                foreach ($get as $x3) {
                    if (empty($url_data)) {
                        $url_data = urlencode($x3['attr']) . '=' . urlencode($x3['value']);
                    } else {
                        $url_data = $url_data . '&' . urlencode($x3['attr']) . '=' . urlencode($x3['value']);
                    }
                }
                if (strpos($url, '?') !== false) {
                    $url = $url . $url_data;
                } else {
                    $url = $url . '?' . $url_data;
                }
                $request_get = ihttp_get($url);
                echo $request_get['content'];
            }
        }
    }
    public function doWebPage()
    {
        $page = array("status" => "1233");
    }
    public function doWebOrderRefund()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $order = pdo_get('xc_beauty_order', array("id" => $_GPC['id'], "uniacid" => $uniacid));
        if ($order) {
            $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
            if (floatval($order['wxpay']) != 0) {
                $tiangong = -1;
                $AppKey = '';
                $AppSecret = '';
                $agent_id = '';
                $user_id = '';
                $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                if ($config) {
                    $config['content'] = json_decode($config['content'], true);
                    if (!empty($config['content']['tiangong']) && !empty($config['content']['AppKey']) && !empty($config['content']['AppSecret'])) {
                        $tiangong = $config['content']['tiangong'];
                        $AppKey = $config['content']['AppKey'];
                        $AppSecret = $config['content']['AppSecret'];
                        $agent_id = $config['content']['agent_id'];
                        $user_id = $config['content']['user_id'];
                    }
                }
                if ($tiangong == 1) {
                    if (!empty($order['charge_id']) && !empty($AppKey) && !empty($AppSecret)) {
                        $url = 'https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=' . $AppKey . '&client_secret=' . $AppSecret;
                        $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                        $result = vpost($url, $data);
                        $result = json_decode($result, true);
                        if (!empty($result['result'])) {
                            $url = 'https://api.teegon.com/router?method=teegon.payment.charge.refund&app_key=' . $result['result']['key'] . '&client_secret=' . $result['result']['secret'];
                            $data = array("charge_id" => $order['charge_id'], "amount" => $order['wxpay']);
                            if (!empty($_GPC['amount'])) {
                                $data['amount'] = $_GPC['amount'];
                            }
                            $result = vpost($url, $data);
                            $result = json_decode($result, true);
                            if (empty($result['result'])) {
                                $json = array("status" => -1, "msg" => $result['emsg']);
                                echo json_encode($json);
                                exit;
                            }
                        } else {
                            $json = array("status" => -1, "msg" => "失败");
                            echo json_encode($json);
                            exit;
                        }
                    } else {
                        $json = array("status" => -1, "msg" => "失败");
                        echo json_encode($json);
                        exit;
                    }
                } else {
                    $config = pdo_get('uni_settings', array("uniacid" => $uniacid));
                    $cert = pdo_get('xc_beauty_config', array("uniacid" => $uniacid, "xkey" => "refund"));
                    if ($config && $cert) {
                        $cert['content'] = json_decode($cert['content'], true);
                        if (!empty($cert['content']['cert']) && !empty($cert['content']['key'])) {
                            $config['payment'] = unserialize($config['payment']);
                            $appid = $_W['account']['key'];
                            $transaction_id = $order['wx_out_trade_no'];
                            $total_fee = floatval($order['wxpay']) * 100;
                            $refund_fee = floatval($order['wxpay']) * 100;
                            if (!empty($_GPC['amount'])) {
                                $refund_fee = floatval($_GPC['amount']) * 100;
                            }
                            $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
                            $ref = strtoupper(md5('appid=' . $appid . '&mch_id=' . $config['payment']['wechat']['mchid'] . '&nonce_str=123456' . '&out_refund_no=' . $transaction_id . '&out_trade_no=' . $transaction_id . '&refund_fee=' . $refund_fee . '&total_fee=' . $total_fee . '&key=' . $config['payment']['wechat']['signkey']));
                            $refund = array("appid" => $appid, "mch_id" => $config['payment']['wechat']['mchid'], "nonce_str" => "123456", "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee, "sign" => $ref);
                            $xml = arrayToXml($refund);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                            $cert_file = '../addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                            if (($TxtRes = fopen($cert_file, 'w+')) === FALSE) {
                                echo '创建可写文件：' . $cert_file . '失败';
                                exit;
                            }
                            $StrConents = $cert['content']['cert'];
                            if (!fwrite($TxtRes, $StrConents)) {
                                echo '尝试向文件' . $cert_file . '写入' . $StrConents . '失败！';
                                fclose($TxtRes);
                                exit;
                            }
                            fclose($TxtRes);
                            curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                            $key_file = '../addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                            if (($TxtRes = fopen($key_file, 'w+')) === FALSE) {
                                echo '创建可写文件：' . $key_file . '失败';
                                exit;
                            }
                            $StrConents = $cert['content']['key'];
                            if (!fwrite($TxtRes, $StrConents)) {
                                echo '尝试向文件' . $key_file . '写入' . $StrConents . '失败！';
                                fclose($TxtRes);
                                exit;
                            }
                            fclose($TxtRes);
                            curl_setopt($ch, CURLOPT_SSLKEY, $key_file);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                            $data = curl_exec($ch);
                            unlink($cert_file);
                            unlink($key_file);
                            if ($data) {
                                curl_close($ch);
                                $data = xmlToArray($data);
                                if ($data['return_code'] == 'SUCCESS') {
                                    if (!$data['result_code'] == 'SUCCESS') {
                                        $json = array("status" => -1, "msg" => $data['err_code_des']);
                                        echo json_encode($json);
                                        exit;
                                    }
                                } else {
                                    $json = array("status" => -1, "msg" => "操作失败1");
                                    echo json_encode($json);
                                    exit;
                                }
                            } else {
                                $error = curl_errno($ch);
                                curl_close($ch);
                                $json = array("status" => -1, "msg" => "操作失败2");
                                echo json_encode($json);
                                exit;
                            }
                        } else {
                            $json = array("status" => -1, "msg" => "操作失败3");
                            echo json_encode($json);
                            exit;
                        }
                    }
                }
            }
            if (!empty($order['score'])) {
                $score = $userinfo['score'] - $order['score'];
                pdo_update('xc_beauty_userinfo', array("score" => $score), array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
            }
            if (floatval($order['canpay']) != 0) {
                $money = round(floatval($userinfo['money']) + floatval($order['canpay']), 2);
                $request = pdo_update('xc_beauty_userinfo', array("money" => $money), array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
            }
            $share = pdo_get('xc_beauty_share', array("status" => 1, "uniacid" => $uniacid, "openid" => $order['openid'], "out_trade_no" => $order['out_trade_no']));
            if ($share) {
                $share_o_amount = round(floatval($userinfo['share_o_amount']) - floatval($share['amount']), 2);
                $share_empty = round(floatval($userinfo['share_empty']) + floatval($share['amount']));
                pdo_update('xc_beauty_userinfo', array("share_o_amount" => $share_o_amount, "share_empty" => $share_empty), array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
                pdo_update('xc_beauty_share', array("status" => 2), array("status" => 1, "uniacid" => $uniacid, "openid" => $order['openid'], "out_trade_no" => $order['out_trade_no']));
            }
            $request = pdo_update('xc_beauty_order', array("refund_status" => 1, "status" => 2), array("id" => $_GPC['id'], "uniacid" => $uniacid));
            if ($request) {
                $json = array("status" => 1, "msg" => "操作成功");
                echo json_encode($json);
                exit;
            } else {
                $json = array("status" => -1, "msg" => "操作失败5");
                echo json_encode($json);
                exit;
            }
        } else {
            $json = array("status" => -1, "msg" => "操作失败4");
            echo json_encode($json);
            exit;
        }
    }
    function doMobileTGong()
    {
        $input = file_get_contents('php://input');
        $isxml = true;
        $get = '';
        load()->func('logging');
        logging_run('天工');
        if (!empty($input)) {
            $data_dd = explode('&', $input);
            $data = array();
            foreach ($data_dd as &$dddd) {
                $dddd = explode('=', $dddd);
                $data[$dddd[0]] = $dddd[1];
            }
            logging_run('input:' . json_encode($data));
            logging_run('666:' . $data['order_no']);
            $order = pdo_get('xc_beauty_order', array("out_trade_no" => $data['order_no'], "status" => -1));
            logging_run('order:' . json_encode($order));
            if ($order) {
                $url = 'https://api.teegon.com/router?method=teegon.payment.charge.info&app_key=MRpZFBi&client_secret=6A8mDpirh2bhyjwG7GSC';
                $ttt = array("charge_id" => $data['charge_id']);
                $result = vpost($url, $ttt);
                logging_run('result:' . $result);
                $result = json_decode($result, true);
                if (!empty($result['result']) && $result['result']['paid']) {
                    $uniacid = $order['uniacid'];
                    if ($order['order_type'] == 1 || $order['order_type'] == 3 || $order['order_type'] == 4) {
                        if ($order['order_type'] == 4) {
                            $service = pdo_get('xc_beauty_store_service', array("uniacid" => $uniacid, "id" => $order['pid']));
                        } else {
                            $service = pdo_get('xc_beauty_service', array("uniacid" => $uniacid, "id" => $order['pid']));
                        }
                        $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $order['openid']));
                        if (floatval($order['canpay']) != 0) {
                            $moeny = round(floatval($userinfo['money']) - floatval($order['canpay']), 2);
                            pdo_update('xc_beauty_userinfo', array("money" => $moeny), array("status" => 1, "openid" => $order['openid']));
                        }
                        if (!empty($order['coupon_id'])) {
                            $use_coupon = pdo_get('xc_beauty_user_coupon', array("status" => -1, "openid" => $order['openid'], "uniacid" => $uniacid, "cid" => $order['coupon_id']));
                            pdo_update('xc_beauty_user_coupon', array("status" => 1), array("id" => $use_coupon['id'], "uniacid" => $uniacid));
                        }
                        $score = null;
                        $card = pdo_get('xc_beauty_config', array("xkey" => "card", "uniacid" => $uniacid));
                        if ($card) {
                            $card['content'] = json_decode($card['content'], true);
                            if ($card['content']['score_status'] == 1 && !empty($card['content']['score_attr']) && !empty($card['content']['score_value']) && $userinfo['card'] == 1) {
                                $score = intval(floatval($order['o_amount']) / floatval($card['content']['score_attr'])) * $card['content']['score_value'];
                            }
                        }
                        $share_config = array("level" => 3, "type" => "", "one" => "", "two" => "", "three" => "");
                        $share_status = 1;
                        $share = pdo_get('xc_beauty_config', array("xkey" => "share", "uniacid" => $uniacid));
                        if ($share) {
                            $share['content'] = json_decode($share['content'], true);
                            if (!empty($share['content']['status'])) {
                                $share_status = $share['content']['status'];
                            }
                            if (!empty($share['content']['level'])) {
                                $share_config['level'] = $share['content']['level'];
                            }
                            if (!empty($share['content']['type'])) {
                                $share_config['type'] = $share['content']['type'];
                                $share_config['one'] = $share['content']['level_one'];
                                $share_config['two'] = $share['content']['level_two'];
                                $share_config['three'] = $share['content']['level_three'];
                            }
                        }
                        if (!empty($service['type'])) {
                            $share_config['type'] = $service['type'];
                            $share_config['one'] = $service['level_one'];
                            $share_config['two'] = $service['level_two'];
                            $share_config['three'] = $service['level_three'];
                        }
                        $share_condition['status'] = 1;
                        $share_condition['score'] = $score;
                        $share_condition['wx_out_trade_no'] = $order['out_trade_no'];
                        $share_condition['one_openid'] = null;
                        $share_condition['one_amount'] = null;
                        $share_condition['two_openid'] = null;
                        $share_condition['two_amount'] = null;
                        $share_condition['three_openid'] = null;
                        $share_condition['three_amount'] = null;
                        if (!empty($share_config['type']) && $share_status == 1) {
                            if ($share_config['level'] >= 1 && !empty($share_config['one']) && !empty($userinfo['share'])) {
                                $share_condition['one_openid'] = $userinfo['share'];
                                if ($share_config['type'] == 1) {
                                    $share_condition['one_amount'] = round(floatval($order['o_amount']) * floatval($share_config['one']) * 0.01, 2);
                                } elseif ($share_config['type'] == 2) {
                                    $share_condition['one_amount'] = $share_config['one'];
                                }
                                $one = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $userinfo['share'], "uniacid" => $uniacid));
                                if ($share_config['level'] >= 2 && !empty($share_config['two']) && !empty($one['share'])) {
                                    $share_condition['two_openid'] = $one['share'];
                                    if ($share_config['type'] == 1) {
                                        $share_condition['two_amount'] = round(floatval($order['o_amount']) * floatval($share_config['two']) * 0.01, 2);
                                    } elseif ($share_config['type'] == 2) {
                                        $share_condition['two_amount'] = $share_config['two'];
                                    }
                                    $two = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $one['share'], "uniacid" => $uniacid));
                                    if ($share_config['level'] >= 3 && !empty($share_config['three']) && !empty($two['share'])) {
                                        $share_condition['three_openid'] = $two['share'];
                                        if ($share_config['type'] == 1) {
                                            $share_condition['three_amount'] = round(floatval($order['o_amount']) * floatval($share_config['three']) * 0.01, 2);
                                        } elseif ($share_config['type'] == 2) {
                                            $share_condition['three_amount'] = $share_config['three'];
                                        }
                                    }
                                }
                            }
                        }
                        $request = pdo_update('xc_beauty_order', $share_condition, array("id" => $order['id'], "uniacid" => $uniacid));
                        if ($request) {
                            if ($order['order_type'] == 1 || $order['order_type'] == 4) {
                                if ($card && $card['content']['level_status'] == 1 && $userinfo['card'] == 1) {
                                    $level_data = array();
                                    $level_data['card_amount'] = round(floatval($userinfo['card_amount']) + floatval($order['o_amount']), 2);
                                    if (!empty($card['content']['level']) && is_array($card['content']['level'])) {
                                        foreach ($card['content']['level'] as $card_l) {
                                            if (floatval($level_data['card_amount']) >= floatval($card_l['amount'])) {
                                                $level_data['card_name'] = $card_l['name'];
                                                $level_data['card_price'] = $card_l['price'];
                                            }
                                        }
                                    }
                                    pdo_update('xc_beauty_userinfo', $level_data, array("openid" => $order['openid'], "uniacid" => $uniacid));
                                }
                                $count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m')));
                                if ($count) {
                                    $orders = $count['order'] + 1;
                                    $amount = round(floatval($count['amount']) + floatval($order['wxpay']), 2);
                                    pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m')));
                                } else {
                                    pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $order['wxpay']));
                                }
                                if (!empty($order['store']) && $order['store'] != -1) {
                                    $store_count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $order['store']));
                                    if ($store_count) {
                                        $orders = $store_count['order'] + 1;
                                        $amount = round(floatval($store_count['amount']) + floatval($order['wxpay']), 2);
                                        pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $order['store']));
                                    } else {
                                        pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $order['wxpay'], "store" => $order['store']));
                                    }
                                }
                                if ($share_status == 1) {
                                    if (!empty($share_condition['one_openid'])) {
                                        pdo_insert('xc_beauty_share', array("uniacid" => $uniacid, "openid" => $share_condition['one_openid'], "title" => "一级订单结算奖励", "out_trade_no" => $order['out_trade_no'], "amount" => $share_condition['one_amount'], "level" => 1, "status" => -1));
                                    }
                                    if (!empty($share_condition['two_openid'])) {
                                        pdo_insert('xc_beauty_share', array("uniacid" => $uniacid, "openid" => $share_condition['two_openid'], "title" => "二级订单结算奖励", "out_trade_no" => $order['out_trade_no'], "amount" => $share_condition['two_amount'], "level" => 2, "status" => -1));
                                    }
                                    if (!empty($share_condition['three_openid'])) {
                                        pdo_insert('xc_beauty_share', array("uniacid" => $uniacid, "openid" => $share_condition['three_openid'], "title" => "三级订单结算奖励", "out_trade_no" => $order['out_trade_no'], "amount" => $share_condition['three_amount'], "level" => 3, "status" => -1));
                                    }
                                }
                                if (!empty($score)) {
                                    $user_score = $userinfo['score'] + $score;
                                    pdo_update('xc_beauty_userinfo', array("score" => $user_score), array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
                                    pdo_insert('xc_beauty_score', array("uniacid" => $uniacid, "openid" => $order['openid'], "status" => 1, "score" => $score, "over" => $user_score, "title" => "消费"));
                                }
                                $order['userinfo'] = json_decode($order['userinfo'], true);
                                $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                                if ($config) {
                                    $config['content'] = json_decode($config['content'], true);
                                    if (!empty($config['content']['template_id'])) {
                                        require_once dirname(__FILE__) . '/resource/WeChat.class.php';
                                        $wechat = new Wechat();
                                        $token = $wechat->checkAuth('wx71d04d820fea94b1', '2afdad649b8a5bcd61dd1837b424d7cf');
                                        $postdata = array("keyword1" => array("value" => $order['userinfo']['name']), "keyword2" => array("value" => $order['userinfo']['mobile']), "keyword3" => array("value" => $service['name']), "keyword4" => array("value" => date('Y-m-d')));
                                        $post_data['touser'] = $order['openid'];
                                        $post_data['template_id'] = $config['content']['template_id'];
                                        $post_data['page'] = 'xc_beauty/pages/base/base';
                                        $post_data['form_id'] = $order['form_id'];
                                        $post_data['data'] = $postdata;
                                        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $token;
                                        $post_data = json_encode($post_data);
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_POST, 1);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                                        $output = curl_exec($ch);
                                        curl_close($ch);
                                    }
                                }
                                $sms = pdo_get('xc_beauty_config', array("uniacid" => $uniacid, "xkey" => "sms"));
                                if ($sms) {
                                    $sms['content'] = json_decode($sms['content'], true);
                                    if ($sms['content']['status'] == 1) {
                                        if ($sms['content']['type'] == 1) {
                                            require_once '../addons/xc_beauty/resource/sms/sendSms.php';
                                            set_time_limit(0);
                                            header('Content-Type: text/plain; charset=utf-8');
                                            $templateParam = array("webnamex" => $config['content']['title'], "trade" => $order['out_trade_no'], "amount" => $order['o_amount'], "namex" => $order['userinfo']['name'], "phonex" => $order['userinfo']['mobile'], "datex" => $order['plan_date']);
                                            if (!empty($order['userinfo']['address'])) {
                                                $templateParam['addrx'] = $order['userinfo']['address'];
                                            } else {
                                                $templateParam['addrx'] = '无';
                                            }
                                            $send = new sms();
                                            $result = $send->sendSms($sms['content']['AccessKeyId'], $sms['content']['AccessKeySecret'], $sms['content']['sign'], $sms['content']['code'], $sms['content']['mobile'], $templateParam);
                                        } elseif ($sms['content']['type'] == 2) {
                                            header('content-type:text/html;charset=utf-8');
                                            $sendUrl = 'http://v.juhe.cn/sms/send';
                                            $tpl_value = '#webnamex#=' . $config['content']['title'] . '&#trade#=' . $order['out_trade_no'] . '&#amount#=' . $order['o_amount'] . '&#namex#=' . $order['userinfo']['name'] . '&#phonex#=' . $order['userinfo']['mobile'] . '&#datex#=' . $order['plan_date'];
                                            if (!empty($order['userinfo']['address'])) {
                                                $tpl_value = $tpl_value . '&#addrx#=' . $order['userinfo']['address'];
                                            } else {
                                                $tpl_value = $tpl_value . '&#addrx#=无';
                                            }
                                            $smsConf = array("key" => $sms['content']['key'], "mobile" => $sms['content']['mobile'], "tpl_id" => $sms['content']['tpl_id'], "tpl_value" => $tpl_value);
                                            $content = juhecurl($sendUrl, $smsConf, 1);
                                            if ($content) {
                                                $result = json_decode($content, true);
                                                $error_code = $result['error_code'];
                                            }
                                        } elseif ($sms['content']['type'] == 3) {
                                            if (!empty($sms['content']['url'])) {
                                                $header = get_headers($sms['content']['url'] . '&xcdubuge=1', 1);
                                                if (strpos($header[0], '301') || strpos($header[0], '302')) {
                                                    if (is_array($header['Location'])) {
                                                        $sms['content']['url'] = $header['Location'][count($header['Location']) - 1];
                                                    } else {
                                                        $sms['content']['url'] = $header['Location'];
                                                    }
                                                }
                                                $customize = $sms['content']['customize'];
                                                $post = $sms['content']['post'];
                                                if (is_array($post) && !empty($post)) {
                                                    $post = json_encode($post);
                                                    if (is_array($customize)) {
                                                        foreach ($customize as $x) {
                                                            $post = str_replace('{{' . $x['attr'] . '}}', $x['value'], $post);
                                                        }
                                                    }
                                                    $post = str_replace('{{webnamex}}', $config['content']['title'], $post);
                                                    $post = str_replace('{{trade}}', $order['out_trade_no'], $post);
                                                    $post = str_replace('{{amount}}', $order['o_amount'] . '元', $post);
                                                    $post = str_replace('{{namex}}', $order['userinfo']['name'], $post);
                                                    $post = str_replace('{{phonex}}', $order['userinfo']['mobile'], $post);
                                                    if (!empty($order['userinfo']['address'])) {
                                                        $post = str_replace('{{addrx}}', $order['userinfo']['address'], $post);
                                                    } else {
                                                        $post = str_replace('{{addrx}}', '无', $post);
                                                    }
                                                    $post = str_replace('{{datex}}', $order['plan_date'], $post);
                                                    $post = json_decode($post, true);
                                                    $data = array();
                                                    foreach ($post as $x2) {
                                                        $data[$x2['attr']] = $x2['value'];
                                                    }
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_URL, $sms['content']['url']);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                    curl_setopt($ch, CURLOPT_POST, 1);
                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                                    $output = curl_exec($ch);
                                                    curl_close($ch);
                                                }
                                                $get = $sms['content']['get'];
                                                if (is_array($get) && !empty($get)) {
                                                    $get = json_encode($get);
                                                    if (is_array($customize)) {
                                                        foreach ($customize as $x) {
                                                            $get = str_replace('{{' . $x['attr'] . '}}', $x['value'], $get);
                                                        }
                                                    }
                                                    $get = str_replace('{{webnamex}}', $config['content']['title'], $get);
                                                    $get = str_replace('{{trade}}', $order['out_trade_no'], $get);
                                                    $get = str_replace('{{amount}}', $order['o_amount'] . '元', $get);
                                                    $get = str_replace('{{namex}}', $order['userinfo']['name'], $get);
                                                    $get = str_replace('{{phonex}}', $order['userinfo']['mobile'], $get);
                                                    if (!empty($order['userinfo']['address'])) {
                                                        $get = str_replace('{{addrx}}', $order['userinfo']['address'], $get);
                                                    } else {
                                                        $get = str_replace('{{addrx}}', '无', $get);
                                                    }
                                                    $get = str_replace('{{datex}}', $order['plan_date'], $get);
                                                    $get = json_decode($get, true);
                                                    $url_data = '';
                                                    foreach ($get as $x3) {
                                                        if (empty($url_data)) {
                                                            $url_data = urlencode($x3['attr']) . '=' . urlencode($x3['value']);
                                                        } else {
                                                            $url_data = $url_data . '&' . urlencode($x3['attr']) . '=' . urlencode($x3['value']);
                                                        }
                                                    }
                                                    if (strpos($sms['content']['url'], '?') !== false) {
                                                        $sms['content']['url'] = $sms['content']['url'] . $url_data;
                                                    } else {
                                                        $sms['content']['url'] = $sms['content']['url'] . '?' . $url_data;
                                                    }
                                                    $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_URL, $sms['content']['url']);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                    curl_setopt($ch, CURLOPT_HEADER, 0);
                                                    $output = curl_exec($ch);
                                                    curl_close($ch);
                                                }
                                            }
                                        }
                                    }
                                }
                                $print = pdo_get('xc_beauty_config', array("xkey" => "print", "uniacid" => $uniacid));
                                if ($print) {
                                    $print['content'] = json_decode($print['content'], true);
                                    if ($print['content']['status'] == 1) {
                                        if ($print['content']['type'] == 1) {
                                            $service_name = $service['name'];
                                            $time = time();
                                            $content = '';
                                            $content .= '订单号：' . $order['out_trade_no'] . '\\r\\n';
                                            $content .= '小程序名：' . $config['content']['title'] . '\\r\\n';
                                            $content .= '总件数：' . $order['total'] . '\\r\\n';
                                            $content .= '商品：' . $service_name . '\\r\\n';
                                            $content .= '应付款：' . $order['amount'] . '\\r\\n';
                                            $content .= '实付款：' . $order['o_amount'] . '\\r\\n';
                                            $content .= '姓名：' . $order['userinfo']['name'] . '\\r\\n';
                                            $content .= '手机：' . $order['userinfo']['mobile'] . '\\r\\n';
                                            if (!empty($order['userinfo']['address'])) {
                                                $content .= '地址：' . $order['userinfo']['address'] . '\\r\\n';
                                            } else {
                                                $content .= '地址：无\\r\\n';
                                            }
                                            $content .= '日期：' . $order['plan_date'] . '\\r\\n';
                                            $sign = strtoupper(md5($print['content']['apikey'] . 'machine_code' . $print['content']['machine_code'] . 'partner' . $print['content']['partner'] . 'time' . $time . $print['content']['msign']));
                                            $requestUrl = 'http://open.10ss.net:8888';
                                            $requestAll = ["partner" => $print['content']['partner'], "machine_code" => $print['content']['machine_code'], "time" => $time, "content" => $content, "sign" => $sign];
                                            $requestInfo = http_build_query($requestAll);
                                            $request = push($requestInfo, $requestUrl);
                                        } elseif ($print['content']['type'] == 2) {
                                            include dirname(__FILE__) . '/resource/HttpClient.class.php';
                                            define('USER', $print['content']['user']);
                                            define('UKEY', $print['content']['ukey']);
                                            define('SN', $print['content']['sn']);
                                            define('IP', 'api.feieyun.cn');
                                            define('PORT', 80);
                                            define('PATH', '/Api/Open/');
                                            define('STIME', time());
                                            define('SIG', sha1(USER . UKEY . STIME));
                                            $service_list = pdo_getall('xc_cake_service', array("uniacid" => $uniacid));
                                            $service_data = array();
                                            if ($service_list) {
                                                foreach ($service_list as $sl) {
                                                    $service_data[$sl['id']] = $sl;
                                                }
                                            }
                                            $orderInfo = '<CB>订单</CB><BR>';
                                            $orderInfo .= '订单号：' . $order['out_trade_no'] . '<BR>';
                                            $orderInfo .= '小程序名：' . $config['content']['title'] . '<BR>';
                                            $orderInfo .= '--------------------------------<BR>';
                                            $orderInfo .= '服务项目：' . $service['name'] . '<BR>';
                                            $orderInfo .= '--------------------------------<BR>';
                                            $orderInfo .= '总件数：' . $order['total'] . '<BR>';
                                            $orderInfo .= '应付款：' . $order['amount'] . '<BR>';
                                            $orderInfo .= '实付款：' . $order['o_amount'] . '<BR>';
                                            $orderInfo .= '姓名：' . $order['userinfo']['name'] . '<BR>';
                                            $orderInfo .= '手机：' . $order['userinfo']['mobile'] . '<BR>';
                                            if (!empty($order['userinfo']['address'])) {
                                                $orderInfo .= '地址：' . $order['userinfo']['address'] . '<BR>';
                                            } else {
                                                $orderInfo .= '地址：无<BR>';
                                            }
                                            $orderInfo .= '日期：' . $order['plan_date'] . '<BR>';
                                            if (!empty($order['content'])) {
                                                $orderInfo .= '备注：' . $order['content'] . '<BR>';
                                            }
                                            $request = wp_print(SN, $orderInfo, 1);
                                        }
                                    }
                                }
                            } else {
                                if ($order['order_type'] == 3) {
                                    if (!empty($order['group'])) {
                                        $group = pdo_get('xc_beauty_group', array("id" => $order['group'], "uniacid" => $uniacid));
                                        if ($group) {
                                            $group_data = array();
                                            if (!empty($group['team'])) {
                                                $group_data['team'] = json_decode($group['team'], true);
                                                $group_data['team'][] = $order['openid'];
                                            } else {
                                                $group_data['team'] = array($order['openid']);
                                            }
                                            $group_data['team'] = json_encode($group_data['team']);
                                            $group_data['status'] = -1;
                                            $group_data['team_total'] = $group['team_total'] + 1;
                                            if ($group_data['team_total'] == $group['total']) {
                                                $group_data['status'] = 1;
                                            }
                                            $request = pdo_update('xc_beauty_group', $group_data, array("id" => $order['group'], "uniacid" => $uniacid));
                                            if ($request) {
                                                pdo_update('xc_beauty_service', array("group_total" => $service['group_total'] + $order['total']), array("id" => $order['pid'], "uniacid" => $uniacid));
                                                if ($group_data['status'] == 1) {
                                                    $list = pdo_getall('xc_beauty_order', array("status" => 1, "order_type" => 3, "group" => $order['group']));
                                                    if ($list) {
                                                        foreach ($list as $l) {
                                                            if (!empty($l['score'])) {
                                                                $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $l['openid']));
                                                                $user_score = $userinfo['score'] + $l['score'];
                                                                pdo_update('xc_beauty_userinfo', array("score" => $user_score), array("status" => 1, "openid" => $l['openid'], "uniacid" => $uniacid));
                                                                pdo_insert('xc_beauty_score', array("uniacid" => $uniacid, "openid" => $l['openid'], "status" => 1, "score" => $l['score'], "over" => $user_score, "title" => "消费"));
                                                            }
                                                            if ($card && $card['content']['level_status'] == 1 && $userinfo['card'] == 1) {
                                                                $level_data = array();
                                                                $level_data['card_amount'] = round(floatval($userinfo['card_amount']) + floatval($l['o_amount']), 2);
                                                                if (!empty($card['content']['level']) && is_array($card['content']['level'])) {
                                                                    foreach ($card['content']['level'] as $card_l) {
                                                                        if (floatval($level_data['card_amount']) >= floatval($card_l['amount'])) {
                                                                            $level_data['card_name'] = $card_l['name'];
                                                                            $level_data['card_price'] = $card_l['price'];
                                                                        }
                                                                    }
                                                                }
                                                                pdo_update('xc_beauty_userinfo', $level_data, array("openid" => $l['openid'], "uniacid" => $uniacid));
                                                            }
                                                            if (!empty($l['one_openid'])) {
                                                                pdo_insert('xc_beauty_share', array("uniacid" => $uniacid, "openid" => $l['one_openid'], "title" => "一级订单结算奖励", "out_trade_no" => $l['out_trade_no'], "amount" => $l['one_amount'], "level" => 1, "status" => -1));
                                                            }
                                                            if (!empty($l['two_openid'])) {
                                                                pdo_insert('xc_beauty_share', array("uniacid" => $uniacid, "openid" => $l['two_openid'], "title" => "二级订单结算奖励", "out_trade_no" => $l['out_trade_no'], "amount" => $l['two_amount'], "level" => 2, "status" => -1));
                                                            }
                                                            if (!empty($l['three_openid'])) {
                                                                pdo_insert('xc_beauty_share', array("uniacid" => $uniacid, "openid" => $l['three_openid'], "title" => "三级订单结算奖励", "out_trade_no" => $l['out_trade_no'], "amount" => $l['three_amount'], "level" => 3, "status" => -1));
                                                            }
                                                            $count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => -1));
                                                            if ($count) {
                                                                $orders = $count['order'] + 1;
                                                                $amount = round(floatval($count['amount']) + floatval($l['wxpay']), 2);
                                                                pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => -1));
                                                            } else {
                                                                pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $l['wxpay'], "store" => -1));
                                                            }
                                                            if (!empty($l['store']) && $l['store'] != -1) {
                                                                $store_count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $l['store']));
                                                                if ($store_count) {
                                                                    $orders = $store_count['order'] + 1;
                                                                    $amount = round(floatval($store_count['amount']) + floatval($l['wxpay']), 2);
                                                                    pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $l['store']));
                                                                } else {
                                                                    pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $l['wxpay'], "store" => $l['store']));
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $request = pdo_insert('xc_beauty_group', array("uniacid" => $uniacid, "openid" => $order['openid'], "pid" => $order['pid'], "failtime" => $service['group_limit'], "total" => $service['group_number'], "team_total" => 1));
                                        if ($request) {
                                            pdo_update('xc_beauty_service', array("group_total" => $service['group_total'] + $order['total']), array("id" => $order['pid'], "uniacid" => $uniacid));
                                            $group = pdo_getall('xc_beauty_group', array("uniacid" => $uniacid, "openid" => $order['openid']), array(), '', 'id DESC');
                                            pdo_update('xc_beauty_order', array("group" => $group[0]['id']), array("id" => $order['id'], "uniacid" => $uniacid));
                                        }
                                    }
                                    if (!empty($order['member'])) {
                                        $times_log = pdo_get('xc_beauty_times_log', array("uniacid" => $uniacid, "plan_date" => $order['plan_date'], "member" => $order['member'], "createtime >=" => date('Y') . '-01-01 00:00:00'));
                                        if ($times_log) {
                                            $times_log_total = intval($times_log['total']) + 1;
                                            pdo_update('xc_beauty_times_log', array("total" => $times_log_total), array("uniacid" => $uniacid, "id" => $times_log['id']));
                                        } else {
                                            pdo_insert('xc_beauty_times_log', array("uniacid" => $uniacid, "member" => $order['member'], "plan_date" => $order['plan_date'], "total" => 1));
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($order['order_type'] == 2) {
                            $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "uniacid" => $uniacid, "openid" => $order['openid']));
                            $money = floatval($userinfo['money']) + floatval($order['amount']);
                            if (!empty($order['gift'])) {
                                $money = round(floatval($money) + floatval($order['gift']), 2);
                            }
                            $request = pdo_update('xc_beauty_order', array("status" => 1, "money" => $money, "wx_out_trade_no" => $data['out_trade_no']), array("id" => $order['id'], "uniacid" => $uniacid));
                            if ($request) {
                                pdo_update('xc_beauty_userinfo', array("money" => $money), array("status" => 1, "uniacid" => $uniacid, "openid" => $order['openid']));
                                $count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => -1));
                                if ($count) {
                                    $orders = $count['order'] + 1;
                                    $amount = round(floatval($count['amount']) + floatval($order['amount']), 2);
                                    pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => -1));
                                } else {
                                    pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $order['amount'], "store" => -1));
                                }
                                if (!empty($order['store']) && $order['store'] != -1) {
                                    $store_count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $order['amount']));
                                    if ($store_count) {
                                        $orders = $store_count['order'] + 1;
                                        $amount = round(floatval($store_count['amount']) + floatval($order['amount']), 2);
                                        pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $order['store']));
                                    } else {
                                        pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $order['amount'], "store" => $order['store']));
                                    }
                                }
                            }
                        } else {
                            if ($order['order_type'] == 5) {
                                $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $order['openid']));
                                if (floatval($order['canpay']) != 0) {
                                    $moeny = round(floatval($userinfo['money']) - floatval($order['canpay']), 2);
                                    pdo_update('xc_beauty_userinfo', array("money" => $moeny), array("status" => 1, "openid" => $order['openid']));
                                }
                                if (!empty($order['coupon_id'])) {
                                    $use_coupon = pdo_get('xc_beauty_user_coupon', array("status" => -1, "openid" => $order['openid'], "uniacid" => $uniacid, "cid" => $order['coupon_id']));
                                    pdo_update('xc_beauty_user_coupon', array("status" => 1), array("id" => $use_coupon['id'], "uniacid" => $uniacid));
                                }
                                $request = pdo_update('xc_beauty_order', array("status" => 1, "wx_out_trade_no" => $data['out_trade_no']), array("id" => $order['id'], "uniacid" => $uniacid));
                                if ($request) {
                                    $card = pdo_get('xc_beauty_config', array("xkey" => "card", "uniacid" => $uniacid));
                                    if ($card && $card['content']['level_status'] == 1 && $userinfo['card'] == 1) {
                                        $level_data = array();
                                        $level_data['card_amount'] = round(floatval($userinfo['card_amount']) + floatval($order['o_amount']), 2);
                                        if (!empty($card['content']['level']) && is_array($card['content']['level'])) {
                                            foreach ($card['content']['level'] as $card_l) {
                                                if (floatval($level_data['card_amount']) >= floatval($card_l['amount'])) {
                                                    $level_data['card_name'] = $card_l['name'];
                                                    $level_data['card_price'] = $card_l['price'];
                                                }
                                            }
                                        }
                                        pdo_update('xc_beauty_userinfo', $level_data, array("openid" => $order['openid'], "uniacid" => $uniacid));
                                    }
                                    $count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => -1));
                                    if ($count) {
                                        $orders = $count['order'] + 1;
                                        $amount = round(floatval($count['amount']) + floatval($order['wxpay']), 2);
                                        pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => -1));
                                    } else {
                                        pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $order['wxpay'], "store" => -1));
                                    }
                                    if (!empty($order['store']) && $order['store'] != -1) {
                                        $store_count = pdo_get('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $order['store']));
                                        if ($store_count) {
                                            $orders = $store_count['order'] + 1;
                                            $amount = round(floatval($store_count['amount']) + floatval($order['wxpay']), 2);
                                            pdo_update('xc_beauty_count', array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "store" => $order['store']));
                                        } else {
                                            pdo_insert('xc_beauty_count', array("uniacid" => $uniacid, "plan_date" => date('Y-m'), "order" => 1, "amount" => $order['wxpay'], "store" => $order['store']));
                                        }
                                    }
                                }
                            }
                        }
                    }
                    echo 'SUCCESS';
                }
            }
            echo 'SUCCESS';
        }
    }
    public function doWebWxPay()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $order = pdo_get('xc_beauty_withdraw', array("id" => $_GPC['id'], "uniacid" => $uniacid));
        if ($order) {
            $json = array("status" => -1, "msg" => "查询不到订单");
            echo json_encode($json);
            exit;
        }
        $config = pdo_get('uni_settings', array("uniacid" => $uniacid));
        $cert = pdo_get('xc_beauty_config', array("uniacid" => $uniacid, "xkey" => "refund"));
        if ($config && $cert) {
            $cert['content'] = json_decode($cert['content'], true);
            if (!empty($cert['content']['cert']) && !empty($cert['content']['key'])) {
                $config['payment'] = unserialize($config['payment']);
                $appid = $_W['account']['key'];
                $mch_id = $config['payment']['wechat']['mchid'];
                $arr = array();
                $arr['mch_appid'] = $appid;
                $arr['mchid'] = $mch_id;
                $arr['nonce_str'] = '123456';
                $arr['partner_trade_no'] = $order['out_trade_no'];
                $arr['openid'] = $order['openid'];
                $arr['check_name'] = 'NO_CHECK';
                $arr['amount'] = floatval($order['amount']) * 100;
                $desc = '提现';
                $arr['desc'] = $desc;
                $user_IP = $_SERVER['HTTP_VIA'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $user_IP = $user_IP ? $user_IP : $_SERVER['REMOTE_ADDR'];
                $arr['spbill_create_ip'] = $user_IP;
                $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
                ksort($arr);
                $str = '';
                foreach ($arr as $k => $v) {
                    $str .= $k . '=' . $v . '&';
                }
                $str .= 'key=' . $config['payment']['wechat']['signkey'];
                $arr['sign'] = md5($str);
                $xml = arrayToXml($arr);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                $cert_file = IA_ROOT . '/addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                if (($TxtRes = fopen($cert_file, 'w+')) === FALSE) {
                    echo '创建可写文件：' . $cert_file . '失败';
                    exit;
                }
                $StrConents = $cert['content']['cert'];
                if (!fwrite($TxtRes, $StrConents)) {
                    echo '尝试向文件' . $cert_file . '写入' . $StrConents . '失败！';
                    fclose($TxtRes);
                    exit;
                }
                fclose($TxtRes);
                curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                $key_file = IA_ROOT . '/addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                if (($TxtRes = fopen($key_file, 'w+')) === FALSE) {
                    echo '创建可写文件：' . $key_file . '失败';
                    exit;
                }
                $StrConents = $cert['content']['key'];
                if (!fwrite($TxtRes, $StrConents)) {
                    echo '尝试向文件' . $key_file . '写入' . $StrConents . '失败！';
                    fclose($TxtRes);
                    exit;
                }
                fclose($TxtRes);
                curl_setopt($ch, CURLOPT_SSLKEY, $key_file);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                $data = curl_exec($ch);
                unlink($cert_file);
                unlink($key_file);
                if ($data) {
                    curl_close($ch);
                    $data = xmlToArray($data);
                    if ($data['return_code'] == 'SUCCESS') {
                        if ($data['result_code'] == 'SUCCESS') {
                            $request = pdo_update('xc_beauty_withdraw', array("status" => 1, "wx_out_trade_no" => $data['payment_no']), array("id" => $_GPC['id'], "uniacid" => $uniacid));
                            if ($request) {
                                $json = array("status" => 1, "msg" => "提现成功");
                                echo json_encode($json);
                                exit;
                            } else {
                                $json = array("status" => -1, "msg" => "提现成功，数据写入失败");
                                echo json_encode($json);
                                exit;
                            }
                        } else {
                            $json = array("status" => -1, "msg" => $data['err_code'] . '：' . $data['err_code_des']);
                            echo json_encode($json);
                            exit;
                        }
                    } else {
                        $json = array("status" => -1, "msg" => $data['return_msg']);
                        echo json_encode($json);
                        exit;
                    }
                } else {
                    $error = curl_errno($ch);
                    curl_close($ch);
                    $json = array("status" => -1, "msg" => $error);
                    echo json_encode($json);
                    exit;
                }
            } else {
                $json = array("status" => -1, "msg" => "证书未上传");
                echo json_encode($json);
                exit;
            }
        } else {
            $json = array("status" => -1, "msg" => "证书未配置");
            echo json_encode($json);
            exit;
        }
    }
    public function doWebUpSql()
    {
        include_once '../addons/xc_beauty/upsql.php';
        upsql();
    }
    public function doMobileGroupRefund()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        set_time_limit(0);
        ignore_user_abort(true);
        $group = pdo_getall('xc_beauty_group', array("status" => -1, "uniacid" => $uniacid));
        if ($group) {
            $group_refund = 1;
            $group_config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
            if ($group_config) {
                $group_config['content'] = json_decode($group_config['content'], true);
                if (!empty($group_config['content']['group_refund'])) {
                    $group_refund = $group_config['content']['group_refund'];
                }
            }
            foreach ($group as &$g) {
                if (strtotime($g['createtime']) + intval($g['failtime']) * 60 * 60 < time()) {
                    $order = pdo_get('xc_beauty_order', array("status" => 1, "uniacid" => $uniacid, "group" => $g['id'], "openid" => $g['openid']));
                    if ($order) {
                        if ($group_refund == 1) {
                            pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                        } else {
                            if ($group_refund == 2) {
                                $can_list = -1;
                                if (floatval($order['wxpay']) != 0) {
                                    $tiangong = -1;
                                    $AppKey = '';
                                    $AppSecret = '';
                                    $agent_id = '';
                                    $user_id = '';
                                    $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                                    if ($config) {
                                        $config['content'] = json_decode($config['content'], true);
                                        if (!empty($config['content']['tiangong']) && !empty($config['content']['AppKey']) && !empty($config['content']['AppSecret'])) {
                                            $tiangong = $config['content']['tiangong'];
                                            $AppKey = $config['content']['AppKey'];
                                            $AppSecret = $config['content']['AppSecret'];
                                            $agent_id = $config['content']['agent_id'];
                                            $user_id = $config['content']['user_id'];
                                        }
                                    }
                                    if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                                        if (!empty($order['charge_id'])) {
                                            $url = 'https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=' . $AppKey . '&client_secret=' . $AppSecret;
                                            $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                                            $result = vpost($url, $data);
                                            $result = json_decode($result, true);
                                            if (!empty($result['result'])) {
                                                $url = 'https://api.teegon.com/router?method=teegon.payment.charge.refund&app_key=' . $result['result']['key'] . '&client_secret=' . $result['result']['secret'];
                                                $data = array("charge_id" => $order['charge_id'], "amount" => $order['wxpay']);
                                                $result = vpost($url, $data);
                                                $result = json_decode($result, true);
                                                if (empty($result['result'])) {
                                                    $can_list = 1;
                                                }
                                            }
                                        }
                                    } else {
                                        $config = pdo_get('uni_settings', array("uniacid" => $uniacid));
                                        $cert = pdo_get('xc_beauty_config', array("uniacid" => $uniacid, "xkey" => "refund"));
                                        if ($config && $cert) {
                                            $cert['content'] = json_decode($cert['content'], true);
                                            if (!empty($cert['content']['cert']) && !empty($cert['content']['key'])) {
                                                $config['payment'] = unserialize($config['payment']);
                                                $appid = $_W['account']['key'];
                                                $transaction_id = $order['wx_out_trade_no'];
                                                $total_fee = floatval($order['wxpay']) * 100;
                                                $refund_fee = floatval($order['wxpay']) * 100;
                                                $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
                                                $ref = strtoupper(md5('appid=' . $appid . '&mch_id=' . $config['payment']['wechat']['mchid'] . '&nonce_str=123456' . '&out_refund_no=' . $transaction_id . '&out_trade_no=' . $transaction_id . '&refund_fee=' . $refund_fee . '&total_fee=' . $total_fee . '&key=' . $config['payment']['wechat']['signkey']));
                                                $refund = array("appid" => $appid, "mch_id" => $config['payment']['wechat']['mchid'], "nonce_str" => "123456", "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee, "sign" => $ref);
                                                $xml = arrayToXml($refund);
                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_URL, $url);
                                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                                                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                                                $cert_file = '../addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                                                if (($TxtRes = fopen($cert_file, 'w+')) === FALSE) {
                                                    echo '创建可写文件：' . $cert_file . '失败';
                                                    exit;
                                                }
                                                $StrConents = $cert['content']['cert'];
                                                if (!fwrite($TxtRes, $StrConents)) {
                                                    echo '尝试向文件' . $cert_file . '写入' . $StrConents . '失败！';
                                                    fclose($TxtRes);
                                                    exit;
                                                }
                                                fclose($TxtRes);
                                                curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                                                curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                                                $key_file = '../addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                                                if (($TxtRes = fopen($key_file, 'w+')) === FALSE) {
                                                    echo '创建可写文件：' . $key_file . '失败';
                                                    exit;
                                                }
                                                $StrConents = $cert['content']['key'];
                                                if (!fwrite($TxtRes, $StrConents)) {
                                                    echo '尝试向文件' . $key_file . '写入' . $StrConents . '失败！';
                                                    fclose($TxtRes);
                                                    exit;
                                                }
                                                fclose($TxtRes);
                                                curl_setopt($ch, CURLOPT_SSLKEY, $key_file);
                                                curl_setopt($ch, CURLOPT_POST, 1);
                                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                                                $data = curl_exec($ch);
                                                unlink($cert_file);
                                                unlink($key_file);
                                                if ($data) {
                                                    curl_close($ch);
                                                    $data = xmlToArray($data);
                                                    if ($data['return_code'] == 'SUCCESS') {
                                                        if ($data['result_code'] == 'SUCCESS') {
                                                            $can_list = 1;
                                                        }
                                                    }
                                                } else {
                                                    $error = curl_errno($ch);
                                                    curl_close($ch);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $can_list = 1;
                                }
                                if ($can_list == 1) {
                                    if (floatval($order['canpay']) != 0) {
                                        $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
                                        $money = round(floatval($userinfo['money']) + floatval($order['canpay']), 2);
                                        $request = pdo_update('xc_beauty_userinfo', array("money" => $money), array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
                                    }
                                    pdo_update('xc_beauty_order', array("refund_status" => 1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                } else {
                                    pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                }
                            }
                        }
                        $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                        if ($config) {
                            $config['content'] = json_decode($config['content'], true);
                            if (!empty($config['content']['group_fail'])) {
                                require_once IA_ROOT . '/addons/xc_beauty/resource/WeChat.class.php';
                                $wechat = new Wechat();
                                $token = $wechat->checkAuth($_W['account']['key'], $_W['account']['secret']);
                                $service = pdo_get('xc_beauty_service', array("id" => $order['pid']));
                                $postdata = array("keyword1" => array("value" => $order['out_trade_no']), "keyword2" => array("value" => $order['amount']), "keyword3" => array("value" => $service['name']), "keyword4" => array("value" => "人数不足"));
                                $post_data['touser'] = $order['openid'];
                                $post_data['template_id'] = $config['content']['group_fail'];
                                $post_data['page'] = 'xc_beauty/pages/base/base';
                                $post_data['form_id'] = $order['form_id'];
                                $post_data['data'] = $postdata;
                                $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $token;
                                $post_data = json_encode($post_data);
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                                $output = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                        $sql = 'UPDATE ' . tablename('xc_beauty_service') . ' SET group_stock=group_stock+:group_stock WHERE uniacid=:uniacid AND id=:id AND group_stock>=0';
                        pdo_query($sql, array(":group_stock" => $order['total'], ":uniacid" => $uniacid, ":id" => $order['pid']));
                    }
                    if (!empty($g['team'])) {
                        $g['team'] = json_decode($g['team'], true);
                        foreach ($g['team'] as $gg) {
                            $order = pdo_get('xc_beauty_order', array("status" => 1, "uniacid" => $uniacid, "group" => $g['id'], "openid" => $gg));
                            if ($order) {
                                if ($group_refund == 1) {
                                    pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                } else {
                                    if ($group_refund == 2) {
                                        $can_list = -1;
                                        if (floatval($order['wxpay']) != 0) {
                                            $tiangong = -1;
                                            $AppKey = '';
                                            $AppSecret = '';
                                            $agent_id = '';
                                            $user_id = '';
                                            $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                                            if ($config) {
                                                $config['content'] = json_decode($config['content'], true);
                                                if (!empty($config['content']['tiangong']) && !empty($config['content']['AppKey']) && !empty($config['content']['AppSecret'])) {
                                                    $tiangong = $config['content']['tiangong'];
                                                    $AppKey = $config['content']['AppKey'];
                                                    $AppSecret = $config['content']['AppSecret'];
                                                    $agent_id = $config['content']['agent_id'];
                                                    $user_id = $config['content']['user_id'];
                                                }
                                            }
                                            if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                                                if (!empty($order['charge_id'])) {
                                                    $url = 'https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=' . $AppKey . '&client_secret=' . $AppSecret;
                                                    $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                                                    $result = vpost($url, $data);
                                                    $result = json_decode($result, true);
                                                    if (!empty($result['result'])) {
                                                        $url = 'https://api.teegon.com/router?method=teegon.payment.charge.refund&app_key=' . $result['result']['key'] . '&client_secret=' . $result['result']['secret'];
                                                        $data = array("charge_id" => $order['charge_id'], "amount" => $order['wxpay']);
                                                        $result = vpost($url, $data);
                                                        $result = json_decode($result, true);
                                                        if (empty($result['result'])) {
                                                            pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                                            exit;
                                                        }
                                                    } else {
                                                        pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                                        exit;
                                                    }
                                                }
                                            } else {
                                                $config = pdo_get('uni_settings', array("uniacid" => $uniacid));
                                                $cert = pdo_get('xc_beauty_config', array("uniacid" => $uniacid, "xkey" => "refund"));
                                                if ($config && $cert) {
                                                    $cert['content'] = json_decode($cert['content'], true);
                                                    if (!empty($cert['content']['cert']) && !empty($cert['content']['key'])) {
                                                        $config['payment'] = unserialize($config['payment']);
                                                        $appid = $_W['account']['key'];
                                                        $transaction_id = $order['wx_out_trade_no'];
                                                        $total_fee = floatval($order['wxpay']) * 100;
                                                        $refund_fee = floatval($order['wxpay']) * 100;
                                                        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
                                                        $ref = strtoupper(md5('appid=' . $appid . '&mch_id=' . $config['payment']['wechat']['mchid'] . '&nonce_str=123456' . '&out_refund_no=' . $transaction_id . '&out_trade_no=' . $transaction_id . '&refund_fee=' . $refund_fee . '&total_fee=' . $total_fee . '&key=' . $config['payment']['wechat']['signkey']));
                                                        $refund = array("appid" => $appid, "mch_id" => $config['payment']['wechat']['mchid'], "nonce_str" => "123456", "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee, "sign" => $ref);
                                                        $xml = arrayToXml($refund);
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_URL, $url);
                                                        curl_setopt($ch, CURLOPT_HEADER, 0);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                                                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                                                        $cert_file = '../addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                                                        if (($TxtRes = fopen($cert_file, 'w+')) === FALSE) {
                                                            echo '创建可写文件：' . $cert_file . '失败';
                                                            exit;
                                                        }
                                                        $StrConents = $cert['content']['cert'];
                                                        if (!fwrite($TxtRes, $StrConents)) {
                                                            echo '尝试向文件' . $cert_file . '写入' . $StrConents . '失败！';
                                                            fclose($TxtRes);
                                                            exit;
                                                        }
                                                        fclose($TxtRes);
                                                        curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                                                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
                                                        $key_file = '../addons/' . $_GPC['m'] . '/resource/' . rand(100000, 999999) . '.pem';
                                                        if (($TxtRes = fopen($key_file, 'w+')) === FALSE) {
                                                            echo '创建可写文件：' . $key_file . '失败';
                                                            exit;
                                                        }
                                                        $StrConents = $cert['content']['key'];
                                                        if (!fwrite($TxtRes, $StrConents)) {
                                                            echo '尝试向文件' . $key_file . '写入' . $StrConents . '失败！';
                                                            fclose($TxtRes);
                                                            exit;
                                                        }
                                                        fclose($TxtRes);
                                                        curl_setopt($ch, CURLOPT_SSLKEY, $key_file);
                                                        curl_setopt($ch, CURLOPT_POST, 1);
                                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                                                        $data = curl_exec($ch);
                                                        unlink($cert_file);
                                                        unlink($key_file);
                                                        if ($data) {
                                                            curl_close($ch);
                                                            $data = xmlToArray($data);
                                                            if ($data['return_code'] == 'SUCCESS') {
                                                                if ($data['result_code'] == 'SUCCESS') {
                                                                    $can_list = 1;
                                                                }
                                                            } else {
                                                                pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                                                exit;
                                                            }
                                                        } else {
                                                            $error = curl_errno($ch);
                                                            curl_close($ch);
                                                            pdo_update('xc_beauty_order', array("refund_status" => -1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                                            exit;
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $can_list = 1;
                                        }
                                        if ($can_list == 1) {
                                            if (floatval($order['canpay']) != 0) {
                                                $userinfo = pdo_get('xc_beauty_userinfo', array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
                                                $money = round(floatval($userinfo['money']) + floatval($order['canpay']), 2);
                                                $request = pdo_update('xc_beauty_userinfo', array("money" => $money), array("status" => 1, "openid" => $order['openid'], "uniacid" => $uniacid));
                                            }
                                        }
                                        pdo_update('xc_beauty_order', array("refund_status" => 1, "status" => 2), array("id" => $order['id'], "uniacid" => $uniacid));
                                    }
                                }
                                $config = pdo_get('xc_beauty_config', array("xkey" => "web", "uniacid" => $uniacid));
                                if ($config) {
                                    $config['content'] = json_decode($config['content'], true);
                                    if (!empty($config['content']['group_fail'])) {
                                        require_once IA_ROOT . '/addons/xc_beauty/resource/WeChat.class.php';
                                        $wechat = new Wechat();
                                        $token = $wechat->checkAuth($_W['account']['key'], $_W['account']['secret']);
                                        $service = pdo_get('xc_beauty_service', array("id" => $order['pid']));
                                        $postdata = array("keyword1" => array("value" => $order['out_trade_no']), "keyword2" => array("value" => $order['amount']), "keyword3" => array("value" => $service['name']), "keyword4" => array("value" => "人数不足"));
                                        $post_data['touser'] = $order['openid'];
                                        $post_data['template_id'] = $config['content']['group_fail'];
                                        $post_data['page'] = 'xc_beauty/pages/base/base';
                                        $post_data['form_id'] = $order['form_id'];
                                        $post_data['data'] = $postdata;
                                        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $token;
                                        $post_data = json_encode($post_data);
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                        curl_setopt($ch, CURLOPT_POST, 1);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                                        $output = curl_exec($ch);
                                        curl_close($ch);
                                    }
                                }
                                $sql = 'UPDATE ' . tablename('xc_beauty_service') . ' SET group_stock=group_stock+:group_stock WHERE uniacid=:uniacid AND id=:id AND group_stock>=0';
                                pdo_query($sql, array(":group_stock" => $order['total'], ":uniacid" => $uniacid, ":id" => $order['pid']));
                            }
                        }
                    }
                    $request = pdo_update('xc_beauty_group', array("status" => 2), array("status" => -1, "uniacid" => $uniacid, "id" => $g['id']));
                }
            }
        }
        echo '退款完成';
    }
}
function arrayToXml($arr)
{
    $xml = '<root>';
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $xml .= '<' . $key . '>' . arrayToXml($val) . '</' . $key . '>';
        } else {
            $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
        }
    }
    $xml .= '</root>';
    return $xml;
}
function xmlToArray($xml)
{
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $values;
}
function juhecurl($url, $params = false, $ispost = 0)
{
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } elseif ($params) {
        curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
    } else {
        curl_setopt($ch, CURLOPT_URL, $url);
    }
    $response = curl_exec($ch);
    if ($response === FALSE) {
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
    curl_close($ch);
    return $response;
}
function push($requestInfo, $url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestInfo);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    return $tmpInfo;
}
function wp_print($printer_sn, $orderInfo, $times)
{
    $content = array("user" => USER, "stime" => STIME, "sig" => SIG, "apiname" => "Open_printMsg", "sn" => $printer_sn, "content" => $orderInfo, "times" => $times);
    $client = new HttpClient(IP, PORT);
    if (!$client->post(PATH, $content)) {
        echo 'error';
    } else {
        echo $client->getContent();
    }
}
function vpost($url, $data)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $tmpInfo = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    return $tmpInfo;
}
function array2url($params)
{
    $str = '';
    $ignore = array("coupon_refund_fee", "coupon_refund_count");
    foreach ($params as $key => $val) {
        if (!((empty($val) || is_array($val)) && !in_array($key, $ignore))) {
            $str .= "{$key}={$val}&";
        }
    }
    $str = trim($str, '&');
    return $str;
}