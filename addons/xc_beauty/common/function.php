<?php
function xc_message($status, $obj = null, $message = '', $title = '', $type = "JSON", $json_option = 0)
{
    $data = array();
    if (empty($message)) {
        if ($status == 1) {
            $data["type"] = "success";
            $message = "操作成功";
        } else {
            $data["type"] = "fail";
            $message = "操作失败";
        }
    }
    if (empty($title)) {
        if ($status == 1) {
            $title = "操作成功";
        } else {
            $data["type"] = "fail";
            $title = "操作失败";
        }
    }
    $data["status"] = $status;
    $data["message"] = $message;
    $data["obj"] = $obj;
    switch (strtoupper($type)) {
        case "JSON":
        case "XML":
        case "JSONP":
            header("Content-Type:application/json; charset=utf-8");
            $handler = isset($_GET[C("VAR_JSONP_HANDLER")]) ? $_GET[C("VAR_JSONP_HANDLER")] : C("DEFAULT_JSONP_HANDLER");
            exit($handler . "(" . json_encode($data, $json_option) . ");");
            break;
        case "EVAL":
        default:
            exit("error");
    }
}
function momessv2($accessurl, $templateParam, $openids, $paradata)
{
    $contents = array();
    $contents["data"] = $templateParam["data"];
    $contents["url"] = $templateParam["url"];
    if (strlen($templateParam["ismin"]) > 0 && $templateParam["ismin"] != "-1" && strlen($templateParam["miniprogramappid"])) {
        $miniprogram = array();
        $miniprogram["appid"] = $templateParam["miniprogramappid"];
        $miniprogram["pagepath"] = $templateParam["miniprogrampagepath"];
        $contents["miniprogram"] = $miniprogram;
    }
    $jsoncontents = json_encode($contents, true);
    foreach ($paradata as $parakey => $paraval) {
        $jsoncontents = str_replace("{{" . $parakey . "}}", $paraval, $jsoncontents);
    }
    $contents = json_decode($jsoncontents, true);
    $contents["template_id"] = $templateParam["template_id"];
    $contents["topcolor"] = $templateParam["topcolor"];
    $postdatda = array();
    $postdatda["contents"] = $contents;
    $postdatda["openids"] = $openids;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $accessurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdatda, true));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function emoji($str, $m)
{
    $emoji = array("/::)" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/01.gif", "/::~" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/02.gif", "/::B" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/03.gif", "/::|" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/04.gif", "/:8-)" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/05.gif", "/::<" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/06.gif", "/::\$" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/07.gif", "/::X" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/08.gif", "/::Z" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/09.gif", "/::'(" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/10.gif", "/::-|" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/11.gif", "/::@" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/12.gif", "/::P" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/13.gif", "/::D" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/14.gif", "/::O" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/15.gif", "/::(" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/16.gif", "/::([囧]" => "[囧]", "/::Q" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/17.gif", "/::T" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/18.gif", "/:,@P" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/19.gif", "/:,@-D" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/21.gif", "/::d" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/22.gif", "/:,@o" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/23.gif", "/:|-)" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/24.gif", "/::!" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/25.gif", "/::L" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/26.gif", "/::>" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/27.gif", "/::,@" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/28.gif", "/:,@f" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/29.gif", "/::-S" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/30.gif", "/:?" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/31.gif", "/:,@x" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/32.gif", "/:,@@" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/33.gif", "/:,@!" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/34.gif", "/:!!!" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/35.gif", "/:xx" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/36.gif", "/:bye" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/37.gif", "/:wipe" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/38.gif", "/:dig" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/39.gif", "/:handclap" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/40.gif", "/:B-)" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/41.gif", "/:<@" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/42.gif", "/:@>" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/43.gif", "/::-O" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/44.gif", "/:>-|" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/45.gif", "/:P-(" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/46.gif", "/::'|" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/47.gif", "/:X-)" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/48.gif", "/::*" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/49.gif", "/:8*" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/50.gif", "/:pd" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/51.gif", "/:<W>" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/52.gif", "/:beer" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/53.gif", "/:coffee" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/54.gif", "/:pig" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/55.gif", "/:rose" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/56.gif", "/:fade" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/57.gif", "/:showlove" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/58.gif", "/:heart" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/59.gif", "/:break" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/60.gif", "/:cake" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/61.gif", "/:bome" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/62.gif", "/:shit" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/63.gif", "/:moon" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/64.gif", "/:sun" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/65.gif", "/:hug" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/66.gif", "/:strong" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/67.gif", "/:weak" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/68.gif", "/:share" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/69.gif", "/:v" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/70.gif", "/:@)" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/71.gif", "/:jj" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/72.gif", "/:@@" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/73.gif", "/:ok" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/74.gif", "/:jump" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/75.gif", "/:shake" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/76.gif", "/:<O>" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/77.gif", "/:circle" => "https://" . $_SERVER["HTTP_HOST"] . "/addons/" . $m . "/resource/emoji/78.gif", "[Hey]" => "[嘿哈]", "[Facepalm]" => "[捂脸]", "[Smirk]" => "[奸笑]", "[Smart]" => "[机智]", "[Concerned]" => "[皱眉]", "[Yeah!]" => "[耶]", "[Packet]" => "[红包]", "[發]" => "[發]", "[小狗]" => "[小狗]");
    $data = array();
    foreach ($emoji as $key => $x) {
        $str = str_replace($key, $m . "_cut" . $x . $m . "_cut", $str);
    }
    $str = explode($m . "_cut", $str);
    foreach ($str as $s) {
        if (strpos($s, "/addons/" . $m . "/resource/emoji/") !== false) {
            $data[] = array("type" => 2, "content" => $s);
        } else {
            $data[] = array("type" => 1, "content" => $s);
        }
    }
    return $data;
}
function sub_pay($order, $_GPC, $_W)
{
    load()->model("payment");
    load()->model("account");
    $moduels = uni_modules();
    if (empty($order) || !array_key_exists($_GPC["m"], $moduels)) {
        return error(1, "模块不存在");
    }
    $moduleid = empty($_GPC["i"]) ? "000000" : sprintf("%06d", $_GPC["i"]);
    $uniontid = date("YmdHis") . $moduleid . random(8, 1);
    $wxapp_uniacid = intval($_W["account"]["uniacid"]);
    $paylog = pdo_get("core_paylog", array("uniacid" => $_W["uniacid"], "module" => $_GPC["m"], "tid" => $order["tid"]));
    if (empty($paylog)) {
        $paylog = array("uniacid" => $_W["uniacid"], "acid" => $_W["acid"], "openid" => $_W["openid"], "module" => $_GPC["m"], "tid" => $order["tid"], "uniontid" => $uniontid, "fee" => floatval($order["fee"]), "card_fee" => floatval($order["fee"]), "status" => "0", "is_usecard" => "0", "tag" => iserializer(array("acid" => $_W["acid"], "uid" => $_W["member"]["uid"])), "type" => "wxapp");
        pdo_insert("core_paylog", $paylog);
        $paylog["plid"] = pdo_insertid();
    }
    if (!empty($paylog) && $paylog["status"] != "0") {
        return error(1, "这个订单已经支付成功, 不需要重复支付.");
    }
    if (!empty($paylog) && empty($paylog["uniontid"])) {
        pdo_update("core_paylog", array("uniontid" => $uniontid), array("plid" => $paylog["plid"]));
        $paylog["uniontid"] = $uniontid;
    }
    $_W["openid"] = $paylog["openid"];
    $params = array("tid" => $paylog["tid"], "fee" => $paylog["card_fee"], "user" => $paylog["openid"], "uniontid" => $paylog["uniontid"], "title" => $order["title"]);
    $setting = uni_setting($wxapp_uniacid, array("payment"));
    $wechat_payment = array("appid" => $_W["account"]["key"], "signkey" => $setting["payment"]["wechat"]["signkey"], "mchid" => $setting["payment"]["wechat"]["mchid"], "version" => 2);
    load()->func("communication");
    $wOpt = array();
    if (!empty($params["user"]) && is_numeric($params["user"])) {
        $params["user"] = mc_uid2openid($params["user"]);
    }
    $package = array();
    $package["appid"] = $order["sub_appid"];
    $package["mch_id"] = $order["sub_mch_id"];
    $package["sub_appid"] = $wechat_payment["appid"];
    $package["sub_mch_id"] = $wechat_payment["mchid"];
    $package["nonce_str"] = random(8);
    $package["body"] = cutstr($params["title"], 26);
    $package["attach"] = $_W["uniacid"];
    $package["out_trade_no"] = $params["uniontid"];
    $package["total_fee"] = $params["fee"] * 100;
    $package["spbill_create_ip"] = CLIENT_IP;
    $package["time_start"] = date("YmdHis", TIMESTAMP);
    $package["time_expire"] = date("YmdHis", TIMESTAMP + 600);
    $package["notify_url"] = $_W["siteroot"] . "addons/" . $_GPC["m"] . "/common/notify.php";
    $package["trade_type"] = "JSAPI";
    $package["sub_openid"] = empty($params["user"]) ? $_W["fans"]["from_user"] : $params["user"];
    ksort($package, SORT_STRING);
    $string1 = '';
    foreach ($package as $key => $v) {
        if (!empty($v)) {
            $string1 .= "{$key}={$v}&";
        }
    }
    $string1 .= "key={$order["sub_key"]}";
    $package["sign"] = strtoupper(md5($string1));
    $dat = array2xml($package);
    $response = ihttp_request("https://api.mch.weixin.qq.com/pay/unifiedorder", $dat);
    if (is_error($response)) {
        return $response;
    }
    $xml = @isimplexml_load_string($response["content"], "SimpleXMLElement", LIBXML_NOCDATA);
    if (strval($xml->return_code) == "FAIL") {
        return error(-1, strval($xml->return_msg));
    }
    if (strval($xml->result_code) == "FAIL") {
        return error(-1, strval($xml->err_code) . ": " . strval($xml->err_code_des));
    }
    $prepayid = $xml->prepay_id;
    $wOpt["appId"] = $wechat_payment["appid"];
    $wOpt["timeStamp"] = strval(TIMESTAMP);
    $wOpt["nonceStr"] = random(8);
    $wOpt["package"] = "prepay_id=" . $prepayid;
    $wOpt["signType"] = "MD5";
    if ($xml->trade_type == "NATIVE") {
        $code_url = $xml->code_url;
        $wOpt["code_url"] = strval($code_url);
    }
    ksort($wOpt, SORT_STRING);
    $string = '';
    foreach ($wOpt as $key => $v) {
        $string .= "{$key}={$v}&";
    }
    $string .= "key={$order["sub_key"]}";
    $wOpt["paySign"] = strtoupper(md5($string));
    return $wOpt;
}