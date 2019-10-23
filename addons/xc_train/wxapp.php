<?php
defined("IN_IA") or exit("Access Denied");
include_once IA_ROOT . "/addons/xc_train/common/function.php";
class Xc_trainModuleWxapp extends WeModuleWxapp
{
    public function doPagePrize()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        if (!empty($_GPC["openid"])) {
            if ($_GPC["openid"] == $_W["openid"]) {
                return $this->result(1, "自己不能助力");
            }
        }
        $active = pdo_get("xc_train_active", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($active) {
            if (intval($active["total"]) == intval($active["is_total"])) {
                return $this->result(1, "活动已经结束了！");
            } else {
                require_once "../addons/" . $_GPC["m"] . "/resource/share/wxBizDataCrypt.php";
                $appid = $_W["account"]["key"];
                $sessionKey = $_SESSION["session_key"];
                $encryptedData = $_GPC["encryptedData"];
                $iv = $_GPC["iv"];
                $pc = new WXBizDataCrypt($appid, $sessionKey);
                $errCode = $pc->decryptData($encryptedData, $iv, $data);
                if ($errCode == 0) {
                    $data = json_decode($data, true);
                    $opengid = $data["openGId"];
                    if (!empty($_GPC["openid"])) {
                        $prize = pdo_get("xc_train_prize", array("status" => -1, "type" => $active["type"], "cid" => $_GPC["id"], "openid" => $_GPC["openid"], "uniacid" => $uniacid));
                    } else {
                        $prize = pdo_get("xc_train_prize", array("status" => -1, "type" => $active["type"], "cid" => $_GPC["id"], "openid" => $_W["openid"], "uniacid" => $uniacid));
                    }
                    if ($prize) {
                        $prize["opengid"] = json_decode($prize["opengid"], true);
                        $common = -1;
                        $is_total = 0;
                        $i = 0;
                        while ($i < count($prize["opengid"])) {
                            if ($prize["opengid"][$i] == $opengid) {
                                $common = 1;
                            }
                            if (!empty($prize["opengid"][$i])) {
                                $is_total = $is_total + 1;
                            }
                            $i++;
                        }
                        if ($common == 1) {
                            if (!empty($_GPC["openid"])) {
                                return $this->result(1, "该群已助力");
                            } else {
                                return $this->result(1, "该群已分享");
                            }
                        } else {
                            $prize_bimg = '';
                            $condition["opengid"] = $prize["opengid"];
                            $condition["opengid"][intval($_GPC["share"]) - 1] = $opengid;
                            $condition["opengid"] = json_encode($condition["opengid"]);
                            if ($is_total + 1 == intval($active["share"])) {
                                if ($active["type"] == 1) {
                                    $condition["status"] = 1;
                                    $condition["prizetime"] = date("Y-m-d H:i:s");
                                    $condition["prize"] = $active["prize"];
                                } else {
                                    if ($active["type"] == 2) {
                                        if (!empty($active["list"])) {
                                            $id = array();
                                            $list = json_decode($active["list"], true);
                                            foreach ($list as $l) {
                                                $id[] = $l["id"];
                                            }
                                            $gua = pdo_getall("xc_train_gua", array("status" => 1, "uniacid" => $uniacid, "id IN" => $id));
                                            if ($gua) {
                                                $total_times = 0;
                                                foreach ($gua as &$g) {
                                                    $g["min"] = $total_times;
                                                    $total_times = intval($g["times"]) + $total_times;
                                                    $g["max"] = $total_times;
                                                }
                                                $num = rand(0, $total_times * 100);
                                                $num = $num / 100;
                                                foreach ($gua as $gg) {
                                                    if (floatval($gg["min"]) < floatval($num) && floatval($num) < floatval($gg["max"])) {
                                                        $condition["prize"] = $gg["name"];
                                                        $condition["pid"] = $gg["id"];
                                                        $prize_bimg = tomedia($gg["bimg"]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (!empty($_GPC["openid"])) {
                                $request = pdo_update("xc_train_prize", $condition, array("status" => -1, "cid" => $_GPC["id"], "openid" => $_GPC["openid"], "uniacid" => $uniacid, "type" => $active["type"]));
                            } else {
                                $request = pdo_update("xc_train_prize", $condition, array("status" => -1, "cid" => $_GPC["id"], "openid" => $_W["openid"], "uniacid" => $uniacid, "type" => $active["type"]));
                            }
                            if ($request) {
                                if (!empty($condition["status"])) {
                                    pdo_update("xc_train_active", array("is_total" => intval($active["is_total"]) + 1), array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
                                    return $this->result(0, "分享成功", array("status" => 2, "opengid" => json_decode($condition["opengid"], true)));
                                } else {
                                    $dddddd = array("status" => 1, "opengid" => json_decode($condition["opengid"], true));
                                    if (!empty($prize_bimg)) {
                                        $dddddd["bimg"] = $prize_bimg;
                                        pdo_update("xc_train_active", array("is_total" => intval($active["is_total"]) + 1), array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
                                    }
                                    return $this->result(0, "分享成功", $dddddd);
                                }
                            } else {
                                if (!empty($_GPC["openid"])) {
                                    return $this->result(1, "助力失败");
                                } else {
                                    return $this->result(1, "分享失败");
                                }
                            }
                        }
                    } else {
                        $condition["uniacid"] = $uniacid;
                        if (!empty($_GPC["openid"])) {
                            $condition["openid"] = $_GPC["openid"];
                        } else {
                            $condition["openid"] = $_W["openid"];
                        }
                        $condition["title"] = $active["name"];
                        $condition["cid"] = $_GPC["id"];
                        $condition["opengid"] = array();
                        $i = 0;
                        while ($i < intval($active["share"])) {
                            $condition["opengid"][] = '';
                            $i++;
                        }
                        $condition["opengid"][intval($_GPC["share"]) - 1] = $opengid;
                        $condition["opengid"] = json_encode($condition["opengid"]);
                        $condition["type"] = $active["type"];
                        $request = pdo_insert("xc_train_prize", $condition);
                        if ($request) {
                            return $this->result(0, "分享成功", array("status" => 1, "opengid" => json_decode($condition["opengid"], true)));
                        } else {
                            if (!empty($_GPC["openid"])) {
                                return $this->result(1, "助力失败2");
                            } else {
                                return $this->result(1, "分享失败");
                            }
                        }
                    }
                } else {
                    if (!empty($_GPC["openid"])) {
                        return $this->result(1, "助力失败1");
                    } else {
                        return $this->result(1, "分享失败");
                    }
                }
            }
        } else {
            return $this->result(1, "活动不存在");
        }
    }
    public function doPageSetOrder()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        if (!empty($_GPC["pid"])) {
            $service = pdo_get("xc_train_service_team", array("status" => 1, "id" => $_GPC["pid"], "uniacid" => $uniacid));
            if ($service) {
                if (time() > strtotime($service["end_time"])) {
                    return $this->result(1, "课程报名/预约已截止");
                }
                if (intval($service["member"]) + $_GPC["total"] > intval($service["more_member"])) {
                    return $this->result(1, "人数超过最大人数");
                }
                $class = pdo_get("xc_train_service", array("status" => 1, "id" => $service["pid"], "uniacid" => $uniacid));
                if ($class) {
                    $condition["amount"] = $class["price"];
                    $condition["title"] = $class["name"] . "【" . $service["mark"] . "】";
                    $condition["can_use"] = $class["can_use"];
                }
            } else {
                return $this->result(1, "课程不存在");
            }
        } else {
            if (!empty($_GPC["cut"])) {
                $sql = "SELECT * FROM " . tablename("xc_train_cut") . " WHERE status=1 AND uniacid=:uniacid AND id=:id AND is_member<member AND unix_timestamp(end_time)>:times";
                $service = pdo_fetch($sql, array(":uniacid" => $uniacid, ":id" => $_GPC["cut"], ":times" => time()));
                if ($service) {
                    $class = pdo_get("xc_train_service", array("status" => 1, "id" => $service["pid"], "uniacid" => $uniacid));
                    if ($class) {
                        $condition["amount"] = $class["price"];
                        $condition["title"] = $class["name"] . "【" . $service["mark"] . "】";
                        $condition["can_use"] = $class["can_use"];
                    }
                    $order = pdo_get("xc_train_cut_order", array("openid" => $_W["openid"], "cid" => $service["id"], "status" => -1));
                    if ($order) {
                        $condition["amount"] = $order["price"];
                    } else {
                        $condition["amount"] = $service["price"];
                    }
                } else {
                    return $this->result(1, "已结束");
                }
            }
        }
        $config = pdo_get("xc_train_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["online_limit"])) {
                if (intval($_GPC["total"]) > intval($config["content"]["online_limit"])) {
                    return $this->result(1, "单次预约最多" . $config["content"]["online_limit"] . "人");
                }
            }
        }
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["out_trade_no"] = setcode();
        if (!empty($_GPC["pid"])) {
            $condition["pid"] = $_GPC["pid"];
            $condition["cut_status"] = -1;
        } else {
            if ($_GPC["cut"]) {
                $condition["pid"] = $_GPC["cut"];
                $condition["cut_status"] = 1;
            }
        }
        $condition["order_type"] = $_GPC["order_type"];
        $condition["total"] = $_GPC["total"];
        $condition["name"] = $_GPC["name"];
        $condition["mobile"] = $_GPC["mobile"];
        if (!empty($_GPC["mobile2"])) {
            $condition["mobile2"] = $_GPC["mobile2"];
        }
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        if (!empty($_GPC["store"])) {
            $condition["store"] = $_GPC["store"];
        }
        $condition["form_id"] = $_GPC["form_id"];
        $condition["o_amount"] = $condition["amount"];
        if (!empty($_GPC["coupon_id"])) {
            $coupon = pdo_get("xc_train_coupon", array("id" => $_GPC["coupon_id"], "uniacid" => $uniacid));
            if ($coupon) {
                if (floatval($condition["amount"]) >= floatval($coupon["condition"]) && !empty($condition["amount"])) {
                    $condition["coupon_id"] = $_GPC["coupon_id"];
                    $condition["coupon_price"] = $coupon["name"];
                    $condition["o_amount"] = round(floatval($condition["amount"]) - floatval($coupon["name"]), 2);
                    if (floatval($condition["o_amount"]) < 0) {
                        $condition["o_amount"] = 0;
                    }
                }
            }
        }
        $request = pdo_insert("xc_train_order", $condition);
        if ($request) {
            if ($condition["order_type"] == 2 || $condition["o_amount"] == 0) {
                pdo_update("xc_train_order", array("status" => 1), array("uniacid" => $uniacid, "openid" => $_W["openid"], "out_trade_no" => $condition["out_trade_no"]));
                if ($condition["cut_status"] == -1) {
                    $sql = "UPDATE " . tablename("xc_train_service_team") . " SET member=member+:member WHERE status=1 AND id=:id AND uniacid=:uniacid";
                    pdo_query($sql, array(":member" => $condition["total"], ":id" => $condition["pid"], ":uniacid" => $uniacid));
                } else {
                    if ($condition["cut_status"] == 1) {
                        $sql = "UPDATE " . tablename("xc_train_cut") . " SET is_member=is_member+:member WHERE status=1 AND id=:id AND uniacid=:uniacid";
                        pdo_query($sql, array(":member" => $condition["total"], ":id" => $condition["pid"], ":uniacid" => $uniacid));
                        pdo_update("xc_train_cut_order", array("status" => 1), array("openid" => $_W["openid"], "uniacid" => $uniacid, "cid" => $condition["pid"]));
                    }
                }
                if (!empty($condition["coupon_id"])) {
                    pdo_update("xc_train_user_coupon", array("status" => 1), array("cid" => $condition["coupon_id"], "openid" => $_W["openid"], "uniacid" => $uniacid, "status" => -1));
                }
                $config = pdo_get("xc_train_config", array("xkey" => "web", "uniacid" => $uniacid));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                    if (!empty($config["content"]["template_id"])) {
                        $account_api = WeAccount::create();
                        $token = $account_api->getAccessToken();
                        $postdata = array("keyword1" => array("value" => $_GPC["name"]), "keyword2" => array("value" => $_GPC["mobile"]), "keyword3" => array("value" => $condition["title"]), "keyword4" => array("value" => date("Y-m-d")));
                        $post_data["touser"] = $_W["openid"];
                        $post_data["template_id"] = $config["content"]["template_id"];
                        $post_data["page"] = "xc_train/pages/base/base";
                        $post_data["form_id"] = $_GPC["form_id"];
                        $post_data["data"] = $postdata;
                        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                $sms = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                if ($sms) {
                    $sms["content"] = json_decode($sms["content"], true);
                    $send_mobile = $sms["content"]["mobile"];
                    if (!empty($_GPC["store"])) {
                        $store = pdo_get("xc_train_school", array("id" => $_GPC["store"], "uniacid" => $uniacid));
                        if ($store && !empty($store["sms"])) {
                            $send_mobile = $store["sms"];
                        }
                    }
                    if ($sms["content"]["status"] == 1) {
                        require_once IA_ROOT . "/addons/xc_train/resource/sms/sendSms.php";
                        if ($sms["content"]["type"] == 1) {
                            set_time_limit(0);
                            header("Content-Type: text/plain; charset=utf-8");
                            $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $condition["out_trade_no"], "amount" => "免费", "namex" => $condition["name"], "phonex" => $condition["mobile"], "service" => $condition["title"]);
                            $send = new sms();
                            $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $send_mobile, $templateParam);
                        } else {
                            if ($sms["content"]["type"] == 2) {
                                header("content-type:text/html;charset=utf-8");
                                $sendUrl = "http://v.juhe.cn/sms/send";
                                $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $condition["out_trade_no"] . "&#amount#=免费&#namex#=" . $condition["name"] . "&#phonex#=" . $condition["mobile"] . "&#service#=" . $condition["title"];
                                $smsConf = array("key" => $sms["content"]["key"], "mobile" => $send_mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                $content = juhecurl($sendUrl, $smsConf, 1);
                                if ($content) {
                                    $result = json_decode($content, true);
                                    $error_code = $result["error_code"];
                                }
                            } else {
                                if ($sms["content"]["type"] == 3) {
                                    if (!empty($sms["content"]["url"])) {
                                        $customize = $sms["content"]["customize"];
                                        $post = $sms["content"]["post"];
                                        if (is_array($post) && !empty($post)) {
                                            $post = json_encode($post);
                                            if (is_array($customize)) {
                                                foreach ($customize as $x) {
                                                    $post = str_replace("{{" . $x["attr"] . "}}", $x["value"], $post);
                                                }
                                            }
                                            $post = str_replace("{{webnamex}}", $config["content"]["title"], $post);
                                            $post = str_replace("{{trade}}", $condition["out_trade_no"], $post);
                                            $post = str_replace("{{amount}}", "免费", $post);
                                            $post = str_replace("{{namex}}", $condition["name"], $post);
                                            $post = str_replace("{{phonex}}", $condition["mobile"], $post);
                                            $post = str_replace("{{service}}", $condition["title"], $post);
                                            $post = json_decode($post, true);
                                            $data = array();
                                            foreach ($post as $x2) {
                                                $data[$x2["attr"]] = $x2["value"];
                                            }
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_POST, 1);
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                            $output = curl_exec($ch);
                                            curl_close($ch);
                                        }
                                        $get = $sms["content"]["get"];
                                        if (is_array($get) && !empty($get)) {
                                            $get = json_encode($get);
                                            if (is_array($customize)) {
                                                foreach ($customize as $x) {
                                                    $get = str_replace("{{" . $x["attr"] . "}}", $x["value"], $get);
                                                }
                                            }
                                            $get = str_replace("{{webnamex}}", $config["content"]["title"], $get);
                                            $get = str_replace("{{trade}}", $condition["out_trade_no"], $get);
                                            $get = str_replace("{{amount}}", "免费", $get);
                                            $get = str_replace("{{namex}}", $condition["name"], $get);
                                            $get = str_replace("{{phonex}}", $condition["mobile"], $get);
                                            $get = str_replace("{{service}}", $condition["title"], $get);
                                            $get = json_decode($get, true);
                                            $url_data = '';
                                            foreach ($get as $x3) {
                                                if (empty($url_data)) {
                                                    $url_data = urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                } else {
                                                    $url_data = $url_data . "&" . urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                }
                                            }
                                            if (strpos($sms["content"]["url"], "?") !== false) {
                                                $sms["content"]["url"] = $sms["content"]["url"] . $url_data;
                                            } else {
                                                $sms["content"]["url"] = $sms["content"]["url"] . "?" . $url_data;
                                            }
                                            $ch = curl_init();
                                            curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($ch, CURLOPT_HEADER, 0);
                                            $output = curl_exec($ch);
                                            curl_close($ch);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $print = pdo_get("xc_train_config", array("xkey" => "print", "uniacid" => $uniacid));
                if ($print) {
                    $print["content"] = json_decode($print["content"], true);
                    if ($print["content"]["status"] == 1) {
                        if ($print["content"]["type"] == 1) {
                            $service_name = $service["name"];
                            $time = time();
                            $content = '';
                            $content .= "订单号：" . $condition["out_trade_no"] . "\r\n";
                            $content .= "小程序名：" . $config["content"]["title"] . "\r\n";
                            $content .= "人数：" . $condition["total"] . "\r\n";
                            $content .= "课程：" . $condition["title"] . "\r\n";
                            $content .= "价格：免费\r\n";
                            $content .= "姓名：" . $condition["name"] . "\r\n";
                            $content .= "手机：" . $condition["mobile"] . "\r\n";
                            $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $print["content"]["machine_code"] . "partner" . $print["content"]["partner"] . "time" . $time . $print["content"]["msign"]));
                            $requestUrl = "http://open.10ss.net:8888";
                            $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $print["content"]["machine_code"], "time" => $time, "content" => $content, "sign" => $sign];
                            $requestInfo = http_build_query($requestAll);
                            $request = push($requestInfo, $requestUrl);
                        } else {
                            if ($print["content"]["type"] == 2) {
                                include dirname(__FILE__) . "/resource/HttpClient.class.php";
                                define("USER", $print["content"]["user"]);
                                define("UKEY", $print["content"]["ukey"]);
                                define("SN", $print["content"]["sn"]);
                                define("IP", "api.feieyun.cn");
                                define("PORT", 80);
                                define("PATH", "/Api/Open/");
                                define("STIME", time());
                                define("SIG", sha1(USER . UKEY . STIME));
                                $orderInfo = "<CB>订单</CB><BR>";
                                $orderInfo .= "订单号：" . $condition["out_trade_no"] . "<BR>";
                                $orderInfo .= "小程序名：" . $config["content"]["title"] . "<BR>";
                                $orderInfo .= "--------------------------------<BR>";
                                $orderInfo .= "课程：" . $condition["title"] . "<BR>";
                                $orderInfo .= "--------------------------------<BR>";
                                $orderInfo .= "人数：" . $condition["total"] . "<BR>";
                                $orderInfo .= "价格：免费<BR>";
                                $orderInfo .= "姓名：" . $condition["name"] . "<BR>";
                                $orderInfo .= "手机：" . $condition["mobile"] . "<BR>";
                                $request = wp_print(SN, $orderInfo, 1);
                            }
                        }
                    }
                }
                return $this->result(0, "操作成功", array("status" => 1));
            } else {
                if ($condition["order_type"] == 1 && $condition["o_amount"] > 0) {
                    $data["title"] = $condition["title"];
                    $data["tid"] = $condition["out_trade_no"];
                    $data["fee"] = $condition["o_amount"];
                    $postdata = $this->pay($data);
                    $postdata["status"] = 2;
                    return $this->result(0, "操作成功", $postdata);
                }
            }
        } else {
            return $this->result(1, "生成订单失败");
        }
    }
    public function doPageBuyVideo()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $video = pdo_get("xc_train_video", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($video) {
            $condition["uniacid"] = $uniacid;
            $condition["openid"] = $_W["openid"];
            $condition["out_trade_no"] = setcode();
            $condition["pid"] = $_GPC["id"];
            $condition["order_type"] = 3;
            $condition["amount"] = $video["price"];
            $condition["title"] = $video["name"];
            $request = pdo_insert("xc_train_order", $condition);
            if ($request) {
                $data["title"] = $video["name"];
                $data["tid"] = $condition["out_trade_no"];
                $data["fee"] = $condition["amount"];
                $postdata = $this->pay($data);
                $postdata["status"] = 1;
                return $this->result(0, "操作成功", $postdata);
            } else {
                return $this->result(1, "生成订单失败");
            }
        } else {
            return $this->result(1, "该视频不存在");
        }
    }
    public function payResult($params)
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        load()->func("logging");
        logging_run("记录字符串日志数据");
        logging_run($params);
        if ($params["result"] == "success" && $params["from"] == "notify") {
            $order = pdo_get("xc_train_order", array("uniacid" => $uniacid, "out_trade_no" => $params["tid"]));
            if ($order) {
                if ($order["order_type"] == 3) {
                    if (floatval($order["amount"]) == $params["fee"]) {
                        $request = pdo_update("xc_train_order", array("status" => 1, "wx_out_trade_no" => $params["uniontid"]), array("id" => $order["id"], "uniacid" => $uniacid));
                    }
                } else {
                    if ($order["order_type"] == 4) {
                        if (floatval($order["o_amount"]) == $params["fee"]) {
                            if (!empty($order["coupon_id"])) {
                                pdo_update("xc_train_user_coupon", array("status" => 1), array("cid" => $order["coupon_id"], "openid" => $order["openid"], "uniacid" => $uniacid, "status" => -1));
                            }
                            $request = pdo_update("xc_train_order", array("status" => 1, "wx_out_trade_no" => $params["uniontid"]), array("id" => $order["id"], "uniacid" => $uniacid));
                            if ($request) {
                                $address = json_decode($order["userinfo"], true);
                                $config = pdo_get("xc_train_config", array("xkey" => "web", "uniacid" => $uniacid));
                                if ($config) {
                                    $config["content"] = json_decode($config["content"], true);
                                    if (!empty($config["content"]["template_id"])) {
                                        require_once IA_ROOT . "/addons/xc_train/resource/WeChat.class.php";
                                        $wechat = new Wechat();
                                        $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                        $postdata = array("keyword1" => array("value" => $address["name"]), "keyword2" => array("value" => $address["mobile"]), "keyword3" => array("value" => $order["title"]), "keyword4" => array("value" => date("Y-m-d")));
                                        $post_data["touser"] = $order["openid"];
                                        $post_data["template_id"] = $config["content"]["template_id"];
                                        $post_data["page"] = "xc_train/pages/base/base";
                                        $post_data["form_id"] = $order["form_id"];
                                        $post_data["data"] = $postdata;
                                        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                $sms = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                if ($sms) {
                                    $sms["content"] = json_decode($sms["content"], true);
                                    if ($sms["content"]["status"] == 1) {
                                        $send_mobile = $sms["content"]["mobile"];
                                        if (!empty($order["store"])) {
                                            $store = pdo_get("xc_train_school", array("id" => $order["store"], "uniacid" => $uniacid));
                                            if ($store && !empty($store["sms"])) {
                                                $send_mobile = $store["sms"];
                                            }
                                        }
                                        require_once IA_ROOT . "/addons/xc_train/resource/sms/sendSms.php";
                                        if ($sms["content"]["type"] == 1) {
                                            set_time_limit(0);
                                            header("Content-Type: text/plain; charset=utf-8");
                                            $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => $address["name"], "phonex" => $address["mobile"], "service" => $order["title"]);
                                            $send = new sms();
                                            $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $send_mobile, $templateParam);
                                        } else {
                                            if ($sms["content"]["type"] == 2) {
                                                header("content-type:text/html;charset=utf-8");
                                                $sendUrl = "http://v.juhe.cn/sms/send";
                                                $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . $address["name"] . "&#phonex#=" . $address["mobile"] . "&#service#=" . $order["title"];
                                                $smsConf = array("key" => $sms["content"]["key"], "mobile" => $send_mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                $content = juhecurl($sendUrl, $smsConf, 1);
                                                if ($content) {
                                                    $result = json_decode($content, true);
                                                    $error_code = $result["error_code"];
                                                }
                                            } else {
                                                if ($sms["content"]["type"] == 3) {
                                                    if (!empty($sms["content"]["url"])) {
                                                        $customize = $sms["content"]["customize"];
                                                        $post = $sms["content"]["post"];
                                                        if (is_array($post) && !empty($post)) {
                                                            $post = json_encode($post);
                                                            if (is_array($customize)) {
                                                                foreach ($customize as $x) {
                                                                    $post = str_replace("{{" . $x["attr"] . "}}", $x["value"], $post);
                                                                }
                                                            }
                                                            $post = str_replace("{{webnamex}}", $config["content"]["title"], $post);
                                                            $post = str_replace("{{trade}}", $order["out_trade_no"], $post);
                                                            $post = str_replace("{{amount}}", $order["o_amount"], $post);
                                                            $post = str_replace("{{namex}}", $address["name"], $post);
                                                            $post = str_replace("{{phonex}}", $address["mobile"], $post);
                                                            $post = str_replace("{{service}}", $order["title"], $post);
                                                            $post = json_decode($post, true);
                                                            $data = array();
                                                            foreach ($post as $x2) {
                                                                $data[$x2["attr"]] = $x2["value"];
                                                            }
                                                            $ch = curl_init();
                                                            curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            curl_setopt($ch, CURLOPT_POST, 1);
                                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                                            $output = curl_exec($ch);
                                                            curl_close($ch);
                                                        }
                                                        $get = $sms["content"]["get"];
                                                        if (is_array($get) && !empty($get)) {
                                                            $get = json_encode($get);
                                                            if (is_array($customize)) {
                                                                foreach ($customize as $x) {
                                                                    $get = str_replace("{{" . $x["attr"] . "}}", $x["value"], $get);
                                                                }
                                                            }
                                                            $get = str_replace("{{webnamex}}", $config["content"]["title"], $get);
                                                            $get = str_replace("{{trade}}", $order["out_trade_no"], $get);
                                                            $get = str_replace("{{amount}}", $order["o_amount"], $get);
                                                            $get = str_replace("{{namex}}", $address["name"], $get);
                                                            $get = str_replace("{{phonex}}", $address["mobile"], $get);
                                                            $get = str_replace("{{service}}", $order["title"], $get);
                                                            $get = json_decode($get, true);
                                                            $url_data = '';
                                                            foreach ($get as $x3) {
                                                                if (empty($url_data)) {
                                                                    $url_data = urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                                } else {
                                                                    $url_data = $url_data . "&" . urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                                }
                                                            }
                                                            if (strpos($sms["content"]["url"], "?") !== false) {
                                                                $sms["content"]["url"] = $sms["content"]["url"] . $url_data;
                                                            } else {
                                                                $sms["content"]["url"] = $sms["content"]["url"] . "?" . $url_data;
                                                            }
                                                            $ch = curl_init();
                                                            curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            curl_setopt($ch, CURLOPT_HEADER, 0);
                                                            $output = curl_exec($ch);
                                                            curl_close($ch);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $print = pdo_get("xc_train_config", array("xkey" => "print", "uniacid" => $uniacid));
                                if ($print) {
                                    $print["content"] = json_decode($print["content"], true);
                                    if ($print["content"]["status"] == 1) {
                                        if ($print["content"]["type"] == 1) {
                                            $time = time();
                                            $content = '';
                                            $content .= "订单号：" . $order["out_trade_no"] . "\r\n";
                                            $content .= "小程序名：" . $config["content"]["title"] . "\r\n";
                                            $content .= "人数：" . $order["total"] . "\r\n";
                                            $content .= "商品：" . $order["title"] . "\r\n";
                                            $content .= "价格：" . $order["o_amount"] . "\r\n";
                                            $content .= "姓名：" . $address["name"] . "\r\n";
                                            $content .= "手机：" . $address["mobile"] . "\r\n";
                                            $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $print["content"]["machine_code"] . "partner" . $print["content"]["partner"] . "time" . $time . $print["content"]["msign"]));
                                            $requestUrl = "http://open.10ss.net:8888";
                                            $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $print["content"]["machine_code"], "time" => $time, "content" => $content, "sign" => $sign];
                                            $requestInfo = http_build_query($requestAll);
                                            $request = push($requestInfo, $requestUrl);
                                        } else {
                                            if (!($print["content"]["type"] == 2)) {
                                            } else {
                                                include IA_ROOT . "/addons/xc_train/resource/HttpClient.class.php";
                                                define("USER", $print["content"]["user"]);
                                                define("UKEY", $print["content"]["ukey"]);
                                                define("SN", $print["content"]["sn"]);
                                                define("IP", "api.feieyun.cn");
                                                define("PORT", 80);
                                                define("PATH", "/Api/Open/");
                                                define("STIME", time());
                                                define("SIG", sha1(USER . UKEY . STIME));
                                                $orderInfo = "<CB>订单</CB><BR>";
                                                $orderInfo .= "订单号：" . $order["out_trade_no"] . "<BR>";
                                                $orderInfo .= "小程序名：" . $config["content"]["title"] . "<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "商品：" . $order["title"] . "<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "人数：" . $order["total"] . "<BR>";
                                                $orderInfo .= "价格：" . $order["o_amount"] . "<BR>";
                                                $orderInfo .= "姓名：" . $address["name"] . "<BR>";
                                                $orderInfo .= "手机：" . $address["mobile"] . "<BR>";
                                                $request = wp_print(SN, $orderInfo, 1);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if (floatval($order["o_amount"]) == $params["fee"]) {
                            if (!empty($order["coupon_id"])) {
                                pdo_update("xc_train_user_coupon", array("status" => 1), array("cid" => $order["coupon_id"], "openid" => $order["openid"], "uniacid" => $uniacid, "status" => -1));
                            }
                            $request = pdo_update("xc_train_order", array("status" => 1, "wx_out_trade_no" => $params["uniontid"]), array("id" => $order["id"], "uniacid" => $uniacid));
                            if ($request) {
                                if ($order["cut_status"] == -1) {
                                    $sql = "UPDATE " . tablename("xc_train_service_team") . " SET member=member+:member WHERE status=1 AND id=:id AND uniacid=:uniacid";
                                    pdo_query($sql, array(":member" => $order["total"], ":id" => $order["pid"], ":uniacid" => $uniacid));
                                } else {
                                    if ($order["cut_status"] == 1) {
                                        $sql = "UPDATE " . tablename("xc_train_cut") . " SET is_member=is_member+:member WHERE status=1 AND id=:id AND uniacid=:uniacid";
                                        pdo_query($sql, array(":member" => $order["total"], ":id" => $order["pid"], ":uniacid" => $uniacid));
                                        pdo_update("xc_train_cut_order", array("status" => 1), array("openid" => $_W["openid"], "uniacid" => $uniacid, "cid" => $order["pid"]));
                                    }
                                }
                                $config = pdo_get("xc_train_config", array("xkey" => "web", "uniacid" => $uniacid));
                                if ($config) {
                                    $config["content"] = json_decode($config["content"], true);
                                    if (!empty($config["content"]["template_id"])) {
                                        $account_api = WeAccount::create();
                                        $token = $account_api->getAccessToken();
                                        $postdata = array("keyword1" => array("value" => $order["name"]), "keyword2" => array("value" => $order["mobile"]), "keyword3" => array("value" => $order["title"]), "keyword4" => array("value" => date("Y-m-d")));
                                        $post_data["touser"] = $order["openid"];
                                        $post_data["template_id"] = $config["content"]["template_id"];
                                        $post_data["page"] = "xc_train/pages/base/base";
                                        $post_data["form_id"] = $order["form_id"];
                                        $post_data["data"] = $postdata;
                                        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                $sms = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                if ($sms) {
                                    $sms["content"] = json_decode($sms["content"], true);
                                    if ($sms["content"]["status"] == 1) {
                                        $send_mobile = $sms["content"]["mobile"];
                                        if (!empty($order["store"])) {
                                            $store = pdo_get("xc_train_school", array("id" => $order["store"], "uniacid" => $uniacid));
                                            if ($store && !empty($store["sms"])) {
                                                $send_mobile = $store["sms"];
                                            }
                                        }
                                        require_once IA_ROOT . "/addons/xc_train/resource/sms/sendSms.php";
                                        if ($sms["content"]["type"] == 1) {
                                            set_time_limit(0);
                                            header("Content-Type: text/plain; charset=utf-8");
                                            $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => $order["name"], "phonex" => $order["mobile"], "service" => $order["title"]);
                                            $send = new sms();
                                            $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $send_mobile, $templateParam);
                                        } else {
                                            if ($sms["content"]["type"] == 2) {
                                                header("content-type:text/html;charset=utf-8");
                                                $sendUrl = "http://v.juhe.cn/sms/send";
                                                $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . $order["name"] . "&#phonex#=" . $order["mobile"] . "&#service#=" . $order["title"];
                                                $smsConf = array("key" => $sms["content"]["key"], "mobile" => $send_mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                $content = juhecurl($sendUrl, $smsConf, 1);
                                                if ($content) {
                                                    $result = json_decode($content, true);
                                                    $error_code = $result["error_code"];
                                                }
                                            } else {
                                                if ($sms["content"]["type"] == 3) {
                                                    if (!empty($sms["content"]["url"])) {
                                                        $customize = $sms["content"]["customize"];
                                                        $post = $sms["content"]["post"];
                                                        if (is_array($post) && !empty($post)) {
                                                            $post = json_encode($post);
                                                            if (is_array($customize)) {
                                                                foreach ($customize as $x) {
                                                                    $post = str_replace("{{" . $x["attr"] . "}}", $x["value"], $post);
                                                                }
                                                            }
                                                            $post = str_replace("{{webnamex}}", $config["content"]["title"], $post);
                                                            $post = str_replace("{{trade}}", $order["out_trade_no"], $post);
                                                            $post = str_replace("{{amount}}", $order["o_amount"], $post);
                                                            $post = str_replace("{{namex}}", $order["name"], $post);
                                                            $post = str_replace("{{phonex}}", $order["mobile"], $post);
                                                            $post = str_replace("{{service}}", $order["title"], $post);
                                                            $post = json_decode($post, true);
                                                            $data = array();
                                                            foreach ($post as $x2) {
                                                                $data[$x2["attr"]] = $x2["value"];
                                                            }
                                                            $ch = curl_init();
                                                            curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            curl_setopt($ch, CURLOPT_POST, 1);
                                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                                            $output = curl_exec($ch);
                                                            curl_close($ch);
                                                        }
                                                        $get = $sms["content"]["get"];
                                                        if (is_array($get) && !empty($get)) {
                                                            $get = json_encode($get);
                                                            if (is_array($customize)) {
                                                                foreach ($customize as $x) {
                                                                    $get = str_replace("{{" . $x["attr"] . "}}", $x["value"], $get);
                                                                }
                                                            }
                                                            $get = str_replace("{{webnamex}}", $config["content"]["title"], $get);
                                                            $get = str_replace("{{trade}}", $order["out_trade_no"], $get);
                                                            $get = str_replace("{{amount}}", $order["o_amount"], $get);
                                                            $get = str_replace("{{namex}}", $order["name"], $get);
                                                            $get = str_replace("{{phonex}}", $order["mobile"], $get);
                                                            $get = str_replace("{{service}}", $order["title"], $get);
                                                            $get = json_decode($get, true);
                                                            $url_data = '';
                                                            foreach ($get as $x3) {
                                                                if (empty($url_data)) {
                                                                    $url_data = urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                                } else {
                                                                    $url_data = $url_data . "&" . urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                                }
                                                            }
                                                            if (strpos($sms["content"]["url"], "?") !== false) {
                                                                $sms["content"]["url"] = $sms["content"]["url"] . $url_data;
                                                            } else {
                                                                $sms["content"]["url"] = $sms["content"]["url"] . "?" . $url_data;
                                                            }
                                                            $ch = curl_init();
                                                            curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                            curl_setopt($ch, CURLOPT_HEADER, 0);
                                                            $output = curl_exec($ch);
                                                            curl_close($ch);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $print = pdo_get("xc_train_config", array("xkey" => "print", "uniacid" => $uniacid));
                                if ($print) {
                                    $print["content"] = json_decode($print["content"], true);
                                    if ($print["content"]["status"] == 1) {
                                        if ($print["content"]["type"] == 1) {
                                            $time = time();
                                            $content = '';
                                            $content .= "订单号：" . $order["out_trade_no"] . "\r\n";
                                            $content .= "小程序名：" . $config["content"]["title"] . "\r\n";
                                            $content .= "人数：" . $order["total"] . "\r\n";
                                            $content .= "课程：" . $order["title"] . "\r\n";
                                            $content .= "价格：" . $order["o_amount"] . "\r\n";
                                            $content .= "姓名：" . $order["name"] . "\r\n";
                                            $content .= "手机：" . $order["mobile"] . "\r\n";
                                            $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $print["content"]["machine_code"] . "partner" . $print["content"]["partner"] . "time" . $time . $print["content"]["msign"]));
                                            $requestUrl = "http://open.10ss.net:8888";
                                            $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $print["content"]["machine_code"], "time" => $time, "content" => $content, "sign" => $sign];
                                            $requestInfo = http_build_query($requestAll);
                                            $request = push($requestInfo, $requestUrl);
                                        } else {
                                            if ($print["content"]["type"] == 2) {
                                                include dirname(__FILE__) . "/resource/HttpClient.class.php";
                                                define("USER", $print["content"]["user"]);
                                                define("UKEY", $print["content"]["ukey"]);
                                                define("SN", $print["content"]["sn"]);
                                                define("IP", "api.feieyun.cn");
                                                define("PORT", 80);
                                                define("PATH", "/Api/Open/");
                                                define("STIME", time());
                                                define("SIG", sha1(USER . UKEY . STIME));
                                                $orderInfo = "<CB>订单</CB><BR>";
                                                $orderInfo .= "订单号：" . $order["out_trade_no"] . "<BR>";
                                                $orderInfo .= "小程序名：" . $config["content"]["title"] . "<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "课程：" . $order["title"] . "<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "人数：" . $order["total"] . "<BR>";
                                                $orderInfo .= "价格：" . $order["o_amount"] . "<BR>";
                                                $orderInfo .= "姓名：" . $order["name"] . "<BR>";
                                                $orderInfo .= "手机：" . $order["mobile"] . "<BR>";
                                                $request = wp_print(SN, $orderInfo, 1);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function doPageMallOrder()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $service = pdo_get("xc_train_mall", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        $price = 0;
        $title = '';
        if ($service) {
            $service["format"] = json_decode($service["format"], true);
            $title = $service["name"];
            if ($_GPC["format"] == -1) {
                $price = $service["price"];
            } else {
                $price = $service["format"][$_GPC["format"]]["price"];
                $title = $title . " " . $service["format"][$_GPC["format"]]["name"];
                $condition["format"] = $service["format"][$_GPC["format"]]["name"];
            }
        }
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["pid"] = $_GPC["id"];
        $condition["order_type"] = 4;
        $condition["total"] = $_GPC["member"];
        $condition["amount"] = round(intval($condition["total"]) * floatval($price), 2);
        $condition["o_amount"] = $condition["amount"];
        $condition["form_id"] = $_GPC["form_id"];
        $condition["title"] = $title;
        if (!empty($_GPC["coupon"])) {
            $coupon = pdo_get("xc_train_coupon", array("uniacid" => $uniacid, "id" => $_GPC["coupon"]));
            if ($coupon) {
                $condition["coupon_id"] = $_GPC["coupon"];
                $condition["coupon_price"] = $coupon["name"];
                $condition["o_amount"] = round(floatval($condition["amount"]) - floatval($condition["coupon_price"]), 2);
            }
        }
        if (floatval($condition["o_amount"]) < 0) {
            $condition["o_amount"] = 0;
        }
        if (!empty($_GPC["content"])) {
            $condition["content"];
        }
        $condition["can_use"] = $condition["total"];
        $condition["userinfo"] = htmlspecialchars_decode($_GPC["address"]);
        if (!empty($_GPC["store"])) {
            $condition["store"] = $_GPC["store"];
        }
        $condition["out_trade_no"] = setcode();
        $request = pdo_insert("xc_train_order", $condition);
        if ($request) {
            $sql = "UPDATE " . tablename("xc_train_mall") . " SET sold=sold+:sold WHERE id=:id AND uniacid=:uniacid";
            pdo_query($sql, array(":sold" => $condition["total"], ":id" => $condition["pid"], ":uniacid" => $uniacid));
            if (floatval($condition["o_amount"]) == 0) {
                $request = pdo_update("xc_train_order", array("status" => 1), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                if ($request) {
                    if (!empty($condition["coupon_id"])) {
                        pdo_update("xc_train_user_coupon", array("status" => 1), array("cid" => $condition["coupon_id"], "openid" => $_W["openid"], "uniacid" => $uniacid, "status" => -1));
                    }
                    $address = json_decode($condition["userinfo"], true);
                    $config = pdo_get("xc_train_config", array("xkey" => "web", "uniacid" => $uniacid));
                    if ($config) {
                        $config["content"] = json_decode($config["content"], true);
                        if (!empty($config["content"]["template_id"])) {
                            require_once IA_ROOT . "/addons/xc_train/resource/WeChat.class.php";
                            $wechat = new Wechat();
                            $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                            $postdata = array("keyword1" => array("value" => $address["name"]), "keyword2" => array("value" => $address["mobile"]), "keyword3" => array("value" => $condition["title"]), "keyword4" => array("value" => date("Y-m-d")));
                            $post_data["touser"] = $_W["openid"];
                            $post_data["template_id"] = $config["content"]["template_id"];
                            $post_data["page"] = "xc_train/pages/base/base";
                            $post_data["form_id"] = $condition["form_id"];
                            $post_data["data"] = $postdata;
                            $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                    $sms = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                    if ($sms) {
                        $sms["content"] = json_decode($sms["content"], true);
                        $send_mobile = $sms["content"]["mobile"];
                        if (!empty($condition["store"])) {
                            $store = pdo_get("xc_train_school", array("id" => $condition["store"], "uniacid" => $uniacid));
                            if ($store && !empty($store["sms"])) {
                                $send_mobile = $store["sms"];
                            }
                        }
                        if ($sms["content"]["status"] == 1) {
                            require_once IA_ROOT . "/addons/xc_train/resource/sms/sendSms.php";
                            if ($sms["content"]["type"] == 1) {
                                set_time_limit(0);
                                header("Content-Type: text/plain; charset=utf-8");
                                $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $condition["out_trade_no"], "amount" => $condition["o_amount"], "namex" => $address["name"], "phonex" => $address["mobile"], "service" => $condition["title"]);
                                $send = new sms();
                                $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $send_mobile, $templateParam);
                            } else {
                                if ($sms["content"]["type"] == 2) {
                                    header("content-type:text/html;charset=utf-8");
                                    $sendUrl = "http://v.juhe.cn/sms/send";
                                    $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $condition["out_trade_no"] . "&#amount#=免费&#namex#=" . $address["name"] . "&#phonex#=" . $address["mobile"] . "&#service#=" . $condition["title"];
                                    $smsConf = array("key" => $sms["content"]["key"], "mobile" => $send_mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                    $content = juhecurl($sendUrl, $smsConf, 1);
                                    if ($content) {
                                        $result = json_decode($content, true);
                                        $error_code = $result["error_code"];
                                    }
                                } else {
                                    if ($sms["content"]["type"] == 3) {
                                        if (!empty($sms["content"]["url"])) {
                                            $customize = $sms["content"]["customize"];
                                            $post = $sms["content"]["post"];
                                            if (is_array($post) && !empty($post)) {
                                                $post = json_encode($post);
                                                if (is_array($customize)) {
                                                    foreach ($customize as $x) {
                                                        $post = str_replace("{{" . $x["attr"] . "}}", $x["value"], $post);
                                                    }
                                                }
                                                $post = str_replace("{{webnamex}}", $config["content"]["title"], $post);
                                                $post = str_replace("{{trade}}", $condition["out_trade_no"], $post);
                                                $post = str_replace("{{amount}}", "免费", $post);
                                                $post = str_replace("{{namex}}", $address["name"], $post);
                                                $post = str_replace("{{phonex}}", $address["mobile"], $post);
                                                $post = str_replace("{{service}}", $condition["title"], $post);
                                                $post = json_decode($post, true);
                                                $data = array();
                                                foreach ($post as $x2) {
                                                    $data[$x2["attr"]] = $x2["value"];
                                                }
                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($ch, CURLOPT_POST, 1);
                                                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                                $output = curl_exec($ch);
                                                curl_close($ch);
                                            }
                                            $get = $sms["content"]["get"];
                                            if (is_array($get) && !empty($get)) {
                                                $get = json_encode($get);
                                                if (is_array($customize)) {
                                                    foreach ($customize as $x) {
                                                        $get = str_replace("{{" . $x["attr"] . "}}", $x["value"], $get);
                                                    }
                                                }
                                                $get = str_replace("{{webnamex}}", $config["content"]["title"], $get);
                                                $get = str_replace("{{trade}}", $condition["out_trade_no"], $get);
                                                $get = str_replace("{{amount}}", "免费", $get);
                                                $get = str_replace("{{namex}}", $address["name"], $get);
                                                $get = str_replace("{{phonex}}", $address["mobile"], $get);
                                                $get = str_replace("{{service}}", $condition["title"], $get);
                                                $get = json_decode($get, true);
                                                $url_data = '';
                                                foreach ($get as $x3) {
                                                    if (empty($url_data)) {
                                                        $url_data = urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                    } else {
                                                        $url_data = $url_data . "&" . urlencode($x3["attr"]) . "=" . urlencode($x3["value"]);
                                                    }
                                                }
                                                if (strpos($sms["content"]["url"], "?") !== false) {
                                                    $sms["content"]["url"] = $sms["content"]["url"] . $url_data;
                                                } else {
                                                    $sms["content"]["url"] = $sms["content"]["url"] . "?" . $url_data;
                                                }
                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_URL, $sms["content"]["url"]);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                                $output = curl_exec($ch);
                                                curl_close($ch);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $print = pdo_get("xc_train_config", array("xkey" => "print", "uniacid" => $uniacid));
                    if ($print) {
                        $print["content"] = json_decode($print["content"], true);
                        if ($print["content"]["status"] == 1) {
                            if ($print["content"]["type"] == 1) {
                                $service_name = $service["name"];
                                $time = time();
                                $content = '';
                                $content .= "订单号：" . $condition["out_trade_no"] . "\r\n";
                                $content .= "小程序名：" . $config["content"]["title"] . "\r\n";
                                $content .= "人数：" . $condition["total"] . "\r\n";
                                $content .= "商品：" . $condition["title"] . "\r\n";
                                $content .= "价格：免费\r\n";
                                $content .= "姓名：" . $address["name"] . "\r\n";
                                $content .= "手机：" . $address["mobile"] . "\r\n";
                                $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $print["content"]["machine_code"] . "partner" . $print["content"]["partner"] . "time" . $time . $print["content"]["msign"]));
                                $requestUrl = "http://open.10ss.net:8888";
                                $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $print["content"]["machine_code"], "time" => $time, "content" => $content, "sign" => $sign];
                                $requestInfo = http_build_query($requestAll);
                                $request = push($requestInfo, $requestUrl);
                            } else {
                                if ($print["content"]["type"] == 2) {
                                    include IA_ROOT . "/addons/xc_train/resource/HttpClient.class.php";
                                    define("USER", $print["content"]["user"]);
                                    define("UKEY", $print["content"]["ukey"]);
                                    define("SN", $print["content"]["sn"]);
                                    define("IP", "api.feieyun.cn");
                                    define("PORT", 80);
                                    define("PATH", "/Api/Open/");
                                    define("STIME", time());
                                    define("SIG", sha1(USER . UKEY . STIME));
                                    $orderInfo = "<CB>订单</CB><BR>";
                                    $orderInfo .= "订单号：" . $condition["out_trade_no"] . "<BR>";
                                    $orderInfo .= "小程序名：" . $config["content"]["title"] . "<BR>";
                                    $orderInfo .= "--------------------------------<BR>";
                                    $orderInfo .= "商品：" . $condition["title"] . "<BR>";
                                    $orderInfo .= "--------------------------------<BR>";
                                    $orderInfo .= "人数：" . $condition["total"] . "<BR>";
                                    $orderInfo .= "价格：免费<BR>";
                                    $orderInfo .= "姓名：" . $address["name"] . "<BR>";
                                    $orderInfo .= "手机：" . $address["mobile"] . "<BR>";
                                    $request = wp_print(SN, $orderInfo, 1);
                                }
                            }
                        }
                    }
                }
                $postdata["status"] = 2;
            } else {
                $data["title"] = $condition["title"];
                $data["tid"] = $condition["out_trade_no"];
                $data["fee"] = $condition["o_amount"];
                $postdata = $this->pay($data);
                $postdata["status"] = 1;
            }
            return $this->result(0, "操作成功", $postdata);
        } else {
            return $this->result(1, "生成订单失败");
        }
    }
}
function setcode()
{
    global $_GPC, $_W;
    $uniacid = $_W["uniacid"];
    $request = pdo_get("xc_train_config", array("xkey" => "code", "uniacid" => $uniacid));
    if (!$request) {
        $request["content"] = "000000";
        pdo_insert("xc_train_config", array("uniacid" => $uniacid, "xkey" => "code", "content" => $request["content"]));
    }
    $code = intval($request["content"]) + 1;
    $code3 = '';
    $i = 0;
    while ($i < 6 - strlen($code)) {
        $code3 = $code3 . "0";
        $i++;
    }
    $code3 = $code3 . $code;
    pdo_update("xc_train_config", array("content" => $code3), array("xkey" => "code", "uniacid" => $uniacid));
    $vipcode = $uniacid . date("Ymd") . $code3;
    return $vipcode;
}
function juhecurl($url, $params = false, $ispost = 0)
{
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if ($params) {
            curl_setopt($ch, CURLOPT_URL, $url . "?" . $params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
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
        echo "Errno" . curl_error($curl);
    }
    curl_close($curl);
    return $tmpInfo;
}
function wp_print($printer_sn, $orderInfo, $times)
{
    $content = array("user" => USER, "stime" => STIME, "sig" => SIG, "apiname" => "Open_printMsg", "sn" => $printer_sn, "content" => $orderInfo, "times" => $times);
    $client = new HttpClient(IP, PORT);
    if (!$client->post(PATH, $content)) {
        return "error";
    } else {
        return $client->getContent();
    }
}