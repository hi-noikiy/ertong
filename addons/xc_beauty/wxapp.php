<?php

include_once IA_ROOT . "/addons/xc_beauty/common/function.php";
defined("IN_IA") or exit("Access Denied");
class Xc_beautyModuleWxapp extends WeModuleWxapp
{
    public function doPageGetCode()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
        if ($card) {
            $card["content"] = json_decode($card["content"], true);
            $code = rand(100000, 999999);
            if ($card["content"]["type"] == 1) {
                if (empty($card["content"]["AccessKeyId"]) || empty($card["content"]["AccessKeySecret"]) || empty($card["content"]["sign"]) || empty($card["content"]["code"])) {
                    return $this->result(1, "验证码短信接口未配置");
                } else {
                    require_once dirname(__FILE__) . "/resource/sms/sendSms.php";
                    $templateParam = array("code" => $code);
                    $send = new sms();
                    $result = $send->sendSms($card["content"]["AccessKeyId"], $card["content"]["AccessKeySecret"], $card["content"]["sign"], $card["content"]["code"], $_GPC["mobile"], $templateParam);
                    cache_write($uniacid . $_W["openid"] . "code", $code, 5 * 60);
                }
            } elseif ($card["content"]["type"] == 2) {
                if (empty($card["content"]["key"]) || empty($card["content"]["tpl_id"])) {
                    return $this->result(1, "验证码短信接口未配置");
                } else {
                    header("content-type:text/html;charset=utf-8");
                    $sendUrl = "http://v.juhe.cn/sms/send";
                    $tpl_value = "#code#=" . $code;
                    $smsConf = array("key" => $card["content"]["key"], "mobile" => $_GPC["mobile"], "tpl_id" => $card["content"]["tpl_id"], "tpl_value" => $tpl_value);
                    $content = juhecurl($sendUrl, $smsConf, 1);
                    if ($content) {
                        cache_write($uniacid . $_W["openid"] . "code", $code, 5 * 60);
                        $result = json_decode($content, true);
                        $error_code = $result["error_code"];
                    }
                }
            } else {
                return $this->result(1, "发送失败");
            }
        } else {
            return $this->result(1, "发送失败");
        }
    }
    public function doPageUpload()
    {
        $http_type = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" || isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https" ? "https://" : "http://";
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $imgname = $_FILES["file"]["name"];
        $tmp = $_FILES["file"]["tmp_name"];
        $filepath = "../attachment/images/" . $uniacid . "/" . date("Y") . "/" . date("m") . "/";
        if (!file_exists("../attachment/images/" . $uniacid . "/" . date("Y") . "/")) {
            mkdir("../attachment/images/" . $uniacid . "/" . date("Y") . "/");
        }
        if (!file_exists($filepath)) {
            mkdir($filepath);
        }
        if (move_uploaded_file($tmp, $filepath . $imgname)) {
            $url = "images/" . $uniacid . "/" . date("Y") . "/" . date("m") . "/" . $imgname;
            load()->func("file");
            file_remote_upload($url);
            $url = tomedia($url);
            echo $url;
        } else {
            echo "上传失败";
        }
    }
    public function doPageOnlineUpload()
    {
        $http_type = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" || isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https" ? "https://" : "http://";
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $imgname = $_FILES["file"]["name"];
        $tmp = $_FILES["file"]["tmp_name"];
        $filepath = "../attachment/images/" . $uniacid . "/" . date("Y") . "/" . date("m") . "/";
        if (!file_exists("../attachment/images/" . $uniacid . "/" . date("Y") . "/")) {
            mkdir("../attachment/images/" . $uniacid . "/" . date("Y") . "/");
        }
        if (!file_exists($filepath)) {
            mkdir($filepath);
        }
        if (move_uploaded_file($tmp, $filepath . $imgname)) {
            $url = "https://" . $_SERVER["HTTP_HOST"] . "/attachment/images/" . $uniacid . "/" . date("Y") . "/" . date("m") . "/" . $imgname;
            $upload = IA_ROOT . "/attachment/images/" . $uniacid . "/" . date("Y") . "/" . date("m") . "/" . $imgname;
            echo json_encode(array("url" => $url, "upload" => $upload));
        } else {
            echo "上传失败";
        }
    }
    public function doPageCardOrder()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["out_trade_no"] = setcode();
        $condition["order_type"] = 2;
        $condition["amount"] = $_GPC["amount"];
        if (!empty($_GPC["gift"])) {
            $condition["gift"] = $_GPC["gift"];
        }
        $userinfo = pdo_get("xc_beauty_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
        if ($userinfo) {
            if (!empty($userinfo["store"])) {
                $condition["store"] = $userinfo["store"];
            }
        }
        $request = pdo_insert("xc_beauty_order", $condition);
        if ($request) {
            $tiangong = -1;
            $AppKey = '';
            $AppSecret = '';
            $agent_id = '';
            $user_id = '';
            $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
            if ($config) {
                $config["content"] = json_decode($config["content"], true);
                if (!empty($config["content"]["tiangong"]) && !empty($config["content"]["AppKey"]) && !empty($config["content"]["AppSecret"])) {
                    $tiangong = $config["content"]["tiangong"];
                    $AppKey = $config["content"]["AppKey"];
                    $AppSecret = $config["content"]["AppSecret"];
                    $agent_id = $config["content"]["agent_id"];
                    $user_id = $config["content"]["user_id"];
                }
            }
            if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                $url = "https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=" . $AppKey . "&client_secret=" . $AppSecret;
                $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                $result = vpost($url, $data);
                $result = json_decode($result, true);
                if (!empty($result["result"])) {
                    $url = "https://api.teegon.com/router?method=teegon.payment.charge.mppay&app_key=" . $result["result"]["key"] . "&client_secret=" . $result["result"]["secret"];
                    $notify_url = $_W["siteroot"] . "app/index.php?i=" . $uniacid . "&c=entry&do=TGong&m=" . $_GPC["m"];
                    $data = array("out_order_no" => $condition["out_trade_no"], "notify_url" => $notify_url, "return_url" => '', "amount" => $condition["amount"], "subject" => "充值", "wx_openid" => $_W["openid"], "mini_appid" => $_W["account"]["key"]);
                    $result = vpost($url, $data);
                    $result = json_decode($result, true);
                    if (!empty($result["result"])) {
                        pdo_update("xc_beauty_order", array("charge_id" => $result["result"]["charge_id"]), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                        $postdata = $result["result"]["action"]["params"];
                        $postdata["status"] = 1;
                        return $this->result(0, "操作成功", $postdata);
                    } else {
                        return $this->result(1, "操作失败");
                    }
                } else {
                    return $this->result(1, "操作失败");
                }
            } else {
                $sub_status = -1;
                $sub_appid = '';
                $sub_mch_id = '';
                $sub_key = '';
                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $_W["uniacid"]));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                    if (!empty($config["content"]) && $config["content"]["sub_status"] && !empty($config["content"]["sub_appid"]) && !empty($config["content"]["sub_mch_id"]) && !empty($config["content"]["sub_key"])) {
                        $sub_status = $config["content"]["sub_status"];
                        $sub_appid = $config["content"]["sub_appid"];
                        $sub_mch_id = $config["content"]["sub_mch_id"];
                        $sub_key = $config["content"]["sub_key"];
                    }
                }
                if ($sub_status == 1 && !empty($sub_appid) && !empty($sub_mch_id) && !empty($sub_key)) {
                    $data["title"] = "充值";
                    $data["tid"] = $condition["out_trade_no"];
                    $data["fee"] = $condition["amount"];
                    $data["sub_appid"] = $sub_appid;
                    $data["sub_mch_id"] = $sub_mch_id;
                    $data["sub_key"] = $sub_key;
                    $postdata = sub_pay($data, $_GPC, $_W);
                    $postdata["status"] = 1;
                } else {
                    $data["title"] = "充值";
                    $data["tid"] = $condition["out_trade_no"];
                    $data["fee"] = $condition["amount"];
                    $postdata = $this->pay($data);
                }
                return $this->result(0, "操作成功", $postdata);
            }
        } else {
            return $this->result(1, "生成订单失败");
        }
    }
    public function doPageAdminCard()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $admin = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($admin) {
            if ($admin["shop"] == -1) {
                return $this->result(1, "没有权限");
            }
        } else {
            return $this->result(1, "没有权限");
        }
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_GPC["openid"];
        $condition["store"] = $_GPC["store"];
        $condition["out_trade_no"] = setcode();
        $condition["order_type"] = 2;
        $condition["amount"] = $_GPC["amount"];
        if (!empty($_GPC["gift"])) {
            $condition["gift"] = $_GPC["gift"];
        }
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        $condition["recharge_type"] = 2;
        $condition["recharge_openid"] = $_W["openid"];
        $condition["status"] = 1;
        $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_GPC["openid"]));
        if ($userinfo) {
            $money = round(floatval($userinfo["money"]) + floatval($condition["amount"]), 2);
            if (!empty($_GPC["gift"])) {
                $money = round(floatval($money) + floatval($_GPC["gift"]), 2);
            }
            $condition["money"] = $money;
        }
        $request = pdo_insert("xc_beauty_order", $condition);
        if ($request) {
            if (!empty($condition["money"])) {
                pdo_update("xc_beauty_userinfo", array("money" => $condition["money"]), array("uniacid" => $uniacid, "openid" => $_GPC["openid"]));
                $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                if ($card) {
                    $card["content"] = json_decode($card["content"], true);
                    if ($card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                        $level_data = array();
                        $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($_GPC["amount"]), 2);
                        if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                            foreach ($card["content"]["level"] as $card_l) {
                                if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                    $level_data["card_name"] = $card_l["name"];
                                    $level_data["card_price"] = $card_l["price"];
                                }
                            }
                        }
                        pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $_GPC["openid"], "uniacid" => $uniacid));
                    }
                }
            }
            $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
            if ($count) {
                $orders = $count["order"] + 1;
                $amount = round(floatval($count["amount"]) + floatval($condition["amount"]), 2);
                pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
            } else {
                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $condition["amount"], "store" => -1));
            }
            if (!empty($condition["store"]) && $condition["store"] != -1) {
                $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $condition["store"], "type" => 1));
                if ($store_count) {
                    $orders = $store_count["order"] + 1;
                    $amount = round(floatval($store_count["amount"]) + floatval($condition["amount"]), 2);
                    pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $condition["store"], "type" => 1));
                } else {
                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $condition["amount"], "store" => $condition["store"], "type" => 1));
                }
                $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $condition["store"], "type" => 2));
                if ($day_count) {
                    $day_orders = $day_count["order"] + 1;
                    $day_amount = round(floatval($day_count["amount"]) + floatval($condition["amount"]), 2);
                    pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $condition["store"], "type" => 2));
                } else {
                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $condition["amount"], "store" => $condition["store"], "type" => 2));
                }
            }
            return $this->result(0, "充值成功", array("status" => 1));
        } else {
            return $this->result(1, "充值失败");
        }
    }
    public function doPageSetOrder()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $order_fail = '';
        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["order_fail"])) {
                $order_fail = $config["content"]["order_fail"];
            }
            if (!empty($config["content"]["total_limit"])) {
                if (intval($_GPC["total"]) > intval($config["content"]["total_limit"])) {
                    return $this->result(1, "最多预约" . $config["content"]["total_limit"] . "个");
                }
            }
        }
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["pid"] = $_GPC["id"];
        if (!empty($_GPC["kind"])) {
            $condition["kind"] = $_GPC["kind"];
        }
        if (!empty($_GPC["plan_date"])) {
            $condition["plan_date"] = $_GPC["plan_date"];
        }
        if (!empty($_GPC["order_type"])) {
            $condition["order_type"] = $_GPC["order_type"];
            $condition["store"] = $_GPC["store"];
            $condition["member"] = $_GPC["member"];
            if ($_GPC["order_type"] == 1) {
                $service = pdo_get("xc_beauty_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
            } elseif ($_GPC["order_type"] == 4) {
                if (!empty($_GPC["plan_date"])) {
                    $online = pdo_get("xc_beauty_online", array("uniacid" => $uniacid, "status" => 1, "store" => $_GPC["store"], "plan_date" => $_GPC["date"], "createtime >" => date("Y") . "-01-01 00:00:00"));
                    if ($online) {
                        return $this->result(1, "预约已满");
                    }
                }
                $service = pdo_get("xc_beauty_store_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
            }
        } else {
            if (!empty($_GPC["store"])) {
                if (!empty($_GPC["plan_date"])) {
                    $online = pdo_get("xc_beauty_online", array("uniacid" => $uniacid, "status" => 1, "store" => $_GPC["store"], "plan_date" => $_GPC["date"], "createtime >" => date("Y") . "-01-01 00:00:00"));
                    if ($online) {
                        return $this->result(1, "预约已满");
                    }
                }
                $service = pdo_get("xc_beauty_store_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
                $condition["store"] = $_GPC["store"];
                $condition["member"] = $_GPC["member"];
                $condition["order_type"] = 4;
            } else {
                $service = pdo_get("xc_beauty_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
            }
        }
        $can_member = 1;
        if (!empty($_GPC["service_type"]) && !empty($_GPC["plan_date"])) {
            $condition["service_type"] = $_GPC["service_type"];
            $times_log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "plan_date" => $condition["plan_date"], "member" => $condition["member"], "createtime >=" => date("Y") . "-01-01 00:00:00"));
            if ($times_log) {
                $can_member = $times_log["total"] + 1;
                $condition["can_member"] = 1;
                if (!empty($service["member"]) && $service["member"] != 1) {
                    $can_member = intval($can_member) + intval($service["member"]) - 1;
                    $condition["can_member"] = $service["member"];
                }
                if ($condition["service_type"] == 1 && !empty($_GPC["home_member"])) {
                    if ($can_member > intval($_GPC["home_member"])) {
                        return $this->result(1, "该时间段预约人数不足");
                    }
                } elseif ($condition["service_type"] == 2 && !empty($_GPC["shop_member"])) {
                    if ($can_member > intval($_GPC["shop_member"])) {
                        return $this->result(1, "该时间段预约人数不足");
                    }
                }
            } else {
                $condition["can_member"] = 1;
                $can_member = 1;
                if (!empty($service["member"]) && $service["member"] != 1) {
                    $can_member = intval($can_member) + intval($service["member"]) - 1;
                    $condition["can_member"] = $service["member"];
                }
                if ($condition["service_type"] == 1 && !empty($_GPC["home_member"])) {
                    if ($can_member > intval($_GPC["home_member"])) {
                        return $this->result(1, "该时间段预约人数不足");
                    }
                } elseif ($condition["service_type"] == 2 && !empty($_GPC["shop_member"])) {
                    if ($can_member > intval($_GPC["shop_member"])) {
                        return $this->result(1, "该时间段预约人数不足");
                    }
                }
            }
        }
        $condition["total"] = $_GPC["total"];
        $condition["userinfo"] = array("name" => $_GPC["name"], "mobile" => $_GPC["mobile"]);
        if (!empty($_GPC["address"])) {
            $condition["userinfo"]["address"] = $_GPC["address"];
        }
        if (!empty($_GPC["map"])) {
            $condition["userinfo"]["map"] = json_decode(htmlspecialchars_decode($_GPC["map"]), true);
        }
        $condition["userinfo"] = json_encode($condition["userinfo"]);
        if (!empty($_GPC["group"])) {
            $condition["price"] = $service["group_price"];
            if (empty($_GPC["group_id"]) && $service["group_head_status"] == 1 && !empty($service["group_head_price"])) {
                $condition["price"] = $service["group_head_price"];
            }
            $condition["amount"] = round(floatval($condition["price"]) * intval($_GPC["total"]), 2);
            $condition["order_type"] = 3;
        } else {
            $condition["price"] = $service["price"];
            $condition["amount"] = round(floatval($condition["price"]) * intval($_GPC["total"]), 2);
            if (!empty($_GPC["flash"])) {
                $condition["price"] = $service["flash_price"];
                $condition["amount"] = round(floatval($condition["price"]) * intval($_GPC["total"]), 2);
            } elseif (!empty($condition["kind"]) && !empty($service["parameter"])) {
                $service["parameter"] = json_decode($service["parameter"], true);
                foreach ($service["parameter"] as $pp) {
                    if ($pp["name"] == $condition["kind"] && !empty($service["parameter"])) {
                        $condition["amount"] = round(floatval($pp["price"]) * intval($_GPC["total"]), 2);
                        $condition["price"] = $pp["price"];
                    }
                }
            }
        }
        $condition["can_use"] = 1 * intval($condition["total"]);
        if (!empty($service["can_use"])) {
            $condition["can_use"] = intval($service["can_use"]) * intval($condition["total"]);
        }
        if (!empty($_GPC["group_id"])) {
            $condition["group"] = $_GPC["group_id"];
            $group = pdo_get("xc_beauty_group", array("status" => -1, "uniacid" => $uniacid, "id" => $_GPC["group_id"]));
            if ($group) {
                if ($group["total"] == $group["team_total"]) {
                    return $this->result(1, "已满员");
                }
                if ($group["openid"] == $_W["openid"]) {
                    return $this->result(1, "已在团内");
                }
                if (!empty($group["team"])) {
                    $group["team"] = json_decode($group["team"], true);
                    foreach ($group["team"] as $g) {
                        if ($g == $_W["openid"]) {
                            return $this->result(1, "已在团内");
                        }
                    }
                }
            } else {
                return $this->result(1, "团不存在");
            }
        }
        if (empty($condition["store"])) {
            $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
            if ($userinfo && !empty($userinfo["store"])) {
                $condition["store"] = $userinfo["store"];
            }
        }
        if (!empty($_GPC["flash"])) {
            $condition["flash"] = $_GPC["flash"];
            $service = pdo_get("xc_beauty_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
            if ($_GPC["total"] > $service["flash_member"]) {
                return $this->result(1, "库存余" . $service["flash_member"]);
            }
            if (!empty($service["flash_shu"]) && $service["flash_shu"] != 0) {
                if ($_GPC["total"] > $service["flash_shu"]) {
                    return $this->result(1, "每单限购" . $service["flash_shu"]);
                }
            }
            if (!empty($service["flash_order"]) && $service["flash_order"] != 0) {
                $flash_order = pdo_getall("xc_beauty_order", array("openid" => $_W["openid"], "pid" => $_GPC["id"], "flash" => 1));
                if ($flash_order) {
                    if (count($flash_order) >= $service["flash_order"]) {
                        return $this->result(1, "每人限购" . $service["flash_shu"] . "单");
                    }
                }
            }
            $condition["flash_price"] = $service["flash_price"];
        }
        if ($condition["order_type"] == 1 || $condition["order_type"] == 4) {
            if (!empty($order_fail)) {
                $condition["failtime"] = date("Y-m-d H:i:s", time() + intval($order_fail));
                $condition["failstatus"] = -1;
            }
        }
        if (!empty($_GPC["form_id"])) {
            $condition["form_id2"] = $_GPC["form_id"];
        }
        if ($condition["order_type"] == 3) {
            if ($service["group_stock"] != -1 && intval($condition["total"]) > intval($service["group_stock"])) {
                return $this->result(1, "团购库存余" . $service["group_stock"]);
            }
        }
        $condition["out_trade_no"] = setcode();
        $request = pdo_insert("xc_beauty_order", $condition);
        if ($request) {
            if ($condition["order_type"] == 1 || $condition["order_type"] == 3) {
                if ($condition["order_type"] == 1) {
                    $datataatata["sold"] = $service["sold"] + $condition["total"];
                } else {
                    if ($condition["order_type"] == 3) {
                        $datataatata["group_total"] = $service["group_total"] + $condition["total"];
                    }
                }
                if (!empty($_GPC["flash"])) {
                    $datataatata["flash_member"] = $service["flash_member"] - $condition["total"];
                }
                pdo_update("xc_beauty_service", $datataatata, array("id" => $service["id"], "uniacid" => $uniacid));
            }
            if ($condition["order_type"] != 3 && !empty($condition["member"]) && !empty($_GPC["plan_date"])) {
                $times_log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "plan_date" => $condition["plan_date"], "member" => $condition["member"], "createtime >=" => date("Y") . "-01-01 00:00:00"));
                if ($times_log) {
                    pdo_update("xc_beauty_times_log", array("total" => $can_member), array("uniacid" => $uniacid, "id" => $times_log["id"]));
                } else {
                    pdo_insert("xc_beauty_times_log", array("uniacid" => $uniacid, "member" => $condition["member"], "plan_date" => $condition["plan_date"], "total" => $can_member));
                }
            }
            return $this->result(0, "操作成功", $condition);
        } else {
            return $this->result(1, "生成订单失败");
        }
    }
    public function doPageOrderPay()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $order = pdo_get("xc_beauty_order", array("status" => -1, "out_trade_no" => $_GPC["out_trade_no"], "uniacid" => $uniacid));
        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"]));
        if (!empty($_GPC["password"])) {
            if (md5($_GPC["password"]) != $userinfo["password"]) {
                return $this->result(1, "密码错误");
            }
        }
        if (!empty($order["group"])) {
            $group = pdo_get("xc_beauty_group", array("id" => $order["group"], "uniacid" => $uniacid, "status" => 1));
            if ($group) {
                return $this->result(1, "已满员");
            }
        }
        $condition["form_id"] = $_GPC["form_id"];
        $condition["o_amount"] = $order["amount"];
        $condition["discount"] = null;
        $sale_status = -1;
        if ($order["order_type"] == 4) {
            $sale_service = pdo_get("xc_beauty_store_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
        } else {
            $sale_service = pdo_get("xc_beauty_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
        }
        if ($sale_service) {
            $sale_status = $sale_service["sale_status"];
        }
        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
        if ($card && $sale_status == 1) {
            $card["content"] = json_decode($card["content"], true);
            if ($card["content"]["level_status"] == 1 && $userinfo["card"] == 1 && !empty($userinfo["card_price"])) {
                $condition["o_amount"] = round(floatval($condition["o_amount"]) * floatval($userinfo["card_price"]) / 10, 2);
                $condition["discount"] = $userinfo["card_price"];
            } elseif ($card["content"]["discount_status"] == 1 && !empty($card["content"]["discount"]) && $userinfo["card"] == 1) {
                $condition["o_amount"] = round(floatval($condition["o_amount"]) * floatval($card["content"]["discount"]) / 10, 2);
                $condition["discount"] = $card["content"]["discount"];
            }
        }
        if (!empty($_GPC["coupon_id"])) {
            $coupon = pdo_get("xc_beauty_coupon", array("status" => 1, "id" => $_GPC["coupon_id"]));
            $condition["coupon_id"] = $_GPC["coupon_id"];
            $condition["coupon_price"] = $coupon["name"];
            $condition["o_amount"] = round(floatval($condition["o_amount"]) - floatval($condition["coupon_price"]), 2);
        } else {
            $condition["coupon_id"] = null;
            $condition["coupon_price"] = null;
        }
        $condition["pay_type"] = $_GPC["pay_type"];
        if ($_GPC["pay_type"] == 1) {
            $condition["canpay"] = 0;
            $condition["wxpay"] = $condition["o_amount"];
        } elseif ($_GPC["pay_type"] == 2) {
            if (floatval($condition["o_amount"]) > floatval($userinfo["money"])) {
                return $this->result(1, "余额不足");
            } else {
                $condition["canpay"] = $condition["o_amount"];
                $condition["wxpay"] = 0;
            }
        }
        $condition["createtime"] = date("Y-m-d H:i:s");
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        if (floatval($condition["canpay"]) < 0) {
            $condition["canpay"] = 0;
        }
        $request = pdo_update("xc_beauty_order", $condition, array("id" => $order["id"], "uniacid" => $uniacid));
        if ($request) {
            if ($order["order_type"] == 4) {
                $service = pdo_get("xc_beauty_store_service", array("id" => $order["pid"], "uniacid" => $uniacid));
            } else {
                $service = pdo_get("xc_beauty_service", array("id" => $order["pid"], "uniacid" => $uniacid));
            }
            if (floatval($condition["wxpay"]) != 0) {
                $tiangong = -1;
                $AppKey = '';
                $AppSecret = '';
                $agent_id = '';
                $user_id = '';
                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                    if (!empty($config["content"]["tiangong"]) && !empty($config["content"]["AppKey"]) && !empty($config["content"]["AppSecret"])) {
                        $tiangong = $config["content"]["tiangong"];
                        $AppKey = $config["content"]["AppKey"];
                        $AppSecret = $config["content"]["AppSecret"];
                        $agent_id = $config["content"]["agent_id"];
                        $user_id = $config["content"]["user_id"];
                    }
                }
                if ($tiangong == 1) {
                    if (!empty($AppKey) && !empty($AppSecret)) {
                        $url = "https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=" . $AppKey . "&client_secret=" . $AppSecret;
                        $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                        $result = vpost($url, $data);
                        $result = json_decode($result, true);
                        if (!empty($result["result"])) {
                            $url = "https://api.teegon.com/router?method=teegon.payment.charge.mppay&app_key=" . $result["result"]["key"] . "&client_secret=" . $result["result"]["secret"];
                            $notify_url = $_W["siteroot"] . "app/index.php?i=" . $uniacid . "&c=entry&do=TGong&m=" . $_GPC["m"];
                            $data = array("out_order_no" => $order["out_trade_no"], "notify_url" => $notify_url, "return_url" => '', "amount" => $condition["wxpay"], "subject" => $service["name"], "wx_openid" => $_W["openid"], "mini_appid" => $_W["account"]["key"]);
                            $result = vpost($url, $data);
                            $result = json_decode($result, true);
                            if (!empty($result["result"])) {
                                pdo_update("xc_beauty_order", array("charge_id" => $result["result"]["charge_id"]), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $uniacid));
                                $postdata = $result["result"]["action"]["params"];
                                $postdata["status"] = 1;
                                return $this->result(0, "操作成功", $postdata);
                            } else {
                                return $this->result(1, "操作失败");
                            }
                        } else {
                            return $this->result(1, "操作失败");
                        }
                    } else {
                        return $this->result(1, "操作失败");
                    }
                } else {
                    $sub_status = -1;
                    $sub_appid = '';
                    $sub_mch_id = '';
                    $sub_key = '';
                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $_W["uniacid"]));
                    if ($config) {
                        $config["content"] = json_decode($config["content"], true);
                        if (!empty($config["content"]) && $config["content"]["sub_status"] && !empty($config["content"]["sub_appid"]) && !empty($config["content"]["sub_mch_id"]) && !empty($config["content"]["sub_key"])) {
                            $sub_status = $config["content"]["sub_status"];
                            $sub_appid = $config["content"]["sub_appid"];
                            $sub_mch_id = $config["content"]["sub_mch_id"];
                            $sub_key = $config["content"]["sub_key"];
                        }
                    }
                    if ($sub_status == 1 && !empty($sub_appid) && !empty($sub_mch_id) && !empty($sub_key)) {
                        $data["title"] = $service["name"];
                        if (!empty($order["wq_out_trade_no"])) {
                            if (!empty($order["wxpay"]) && floatval($order["wxpay"]) == floatval($condition["wxpay"])) {
                                $data["tid"] = $order["wq_out_trade_no"];
                            } else {
                                $data["tid"] = $order["out_trade_no"] . rand(1000, 9999);
                            }
                        } else {
                            $data["tid"] = $order["out_trade_no"];
                        }
                        pdo_update("xc_beauty_order", array("wq_out_trade_no" => $data["tid"]), array("id" => $order["id"], "uniacid" => $uniacid));
                        $data["fee"] = $condition["wxpay"];
                        $data["sub_appid"] = $sub_appid;
                        $data["sub_mch_id"] = $sub_mch_id;
                        $data["sub_key"] = $sub_key;
                        $postdata = sub_pay($data, $_GPC, $_W);
                        $postdata["status"] = 1;
                    } else {
                        $data["title"] = $service["name"];
                        if (!empty($order["wq_out_trade_no"])) {
                            if (!empty($order["wxpay"]) && floatval($order["wxpay"]) == floatval($condition["wxpay"])) {
                                $data["tid"] = $order["wq_out_trade_no"];
                            } else {
                                $data["tid"] = $order["out_trade_no"] . rand(1000, 9999);
                            }
                        } else {
                            $data["tid"] = $order["out_trade_no"];
                        }
                        pdo_update("xc_beauty_order", array("wq_out_trade_no" => $data["tid"]), array("id" => $order["id"], "uniacid" => $uniacid));
                        $data["fee"] = $condition["wxpay"];
                        $postdata = $this->pay($data);
                        $postdata["status"] = 1;
                    }
                    return $this->result(0, "操作成功", $postdata);
                }
            } else {
                if (!empty($_GPC["coupon_id"])) {
                    $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $order["openid"], "uniacid" => $uniacid, "cid" => $_GPC["coupon_id"]));
                    pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
                }
                $money = round(floatval($userinfo["money"]) - floatval($condition["canpay"]), 2);
                pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                $score = null;
                $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                if ($card) {
                    $card["content"] = json_decode($card["content"], true);
                    if ($card["content"]["score_status"] == 1 && !empty($card["content"]["score_attr"]) && !empty($card["content"]["score_value"]) && $userinfo["card"] == 1) {
                        $score = intval(floatval($condition["o_amount"]) / floatval($card["content"]["score_attr"])) * intval($card["content"]["score_value"]);
                    }
                }
                $share_config = array("level" => 3, "type" => '', "one" => '', "two" => '', "three" => '');
                $share = pdo_get("xc_beauty_config", array("xkey" => "share", "uniacid" => $uniacid));
                $share_status = 1;
                if ($share) {
                    $share["content"] = json_decode($share["content"], true);
                    if (!empty($share["content"]["status"])) {
                        $share_status = $share["content"]["status"];
                    }
                    if (!empty($share["content"]["level"])) {
                        $share_config["level"] = $share["content"]["level"];
                    }
                    if (!empty($share["content"]["type"])) {
                        $share_config["type"] = $share["content"]["type"];
                        $share_config["one"] = $share["content"]["level_one"];
                        $share_config["two"] = $share["content"]["level_two"];
                        $share_config["three"] = $share["content"]["level_three"];
                    }
                }
                if (!empty($service["type"])) {
                    $share_config["type"] = $service["type"];
                    $share_config["one"] = $service["level_one"];
                    $share_config["two"] = $service["level_two"];
                    $share_config["three"] = $service["level_three"];
                }
                $share_condition["status"] = 1;
                $share_condition["score"] = $score;
                $share_condition["one_openid"] = null;
                $share_condition["one_amount"] = null;
                $share_condition["two_openid"] = null;
                $share_condition["two_amount"] = null;
                $share_condition["three_openid"] = null;
                $share_condition["three_amount"] = null;
                if (!empty($share_config["type"]) && $share_status == 1) {
                    if ($share_config["level"] >= 1 && !empty($share_config["one"]) && !empty($userinfo["share"])) {
                        $share_condition["one_openid"] = $userinfo["share"];
                        if ($share_config["type"] == 1) {
                            $share_condition["one_amount"] = round(floatval($condition["o_amount"]) * floatval($share_config["one"]) * 0.01, 2);
                        } elseif ($share_config["type"] == 2) {
                            $share_condition["one_amount"] = $share_config["one"];
                        }
                        $one = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $userinfo["share"], "uniacid" => $uniacid));
                        if ($share_config["level"] >= 2 && !empty($share_config["two"]) && !empty($one["share"])) {
                            $share_condition["two_openid"] = $one["share"];
                            if ($share_config["type"] == 1) {
                                $share_condition["two_amount"] = round(floatval($condition["o_amount"]) * floatval($share_config["two"]) * 0.01, 2);
                            } elseif ($share_config["type"] == 2) {
                                $share_condition["two_amount"] = $share_config["two"];
                            }
                            $two = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $one["share"], "uniacid" => $uniacid));
                            if ($share_config["level"] >= 3 && !empty($share_config["three"]) && !empty($two["share"])) {
                                $share_condition["three_openid"] = $two["share"];
                                if ($share_config["type"] == 1) {
                                    $share_condition["three_amount"] = round(floatval($condition["o_amount"]) * floatval($share_config["three"]) * 0.01, 2);
                                } elseif ($share_config["type"] == 2) {
                                    $share_condition["three_amount"] = $share_config["three"];
                                }
                            }
                        }
                    }
                }
                $request = pdo_update("xc_beauty_order", $share_condition, array("id" => $order["id"], "uniacid" => $uniacid));
                if ($request) {
                    if ($order["order_type"] == 1 || $order["order_type"] == 4) {
                        if ($share_status == 1) {
                            if (!empty($share_condition["one_openid"])) {
                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["one_openid"], "title" => "一级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["one_amount"], "level" => 1, "status" => -1));
                            }
                            if (!empty($share_condition["two_openid"])) {
                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["two_openid"], "title" => "二级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["two_amount"], "level" => 2, "status" => -1));
                            }
                            if (!empty($share_condition["three_openid"])) {
                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["three_openid"], "title" => "三级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["three_amount"], "level" => 3, "status" => -1));
                            }
                        }
                        if (!empty($score)) {
                            $user_score = $userinfo["score"] + $score;
                            pdo_update("xc_beauty_userinfo", array("score" => $user_score), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                            pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $order["openid"], "status" => 1, "score" => $score, "over" => $user_score, "title" => "消费"));
                        }
                        $service_name = '';
                        if ($order["order_type"] == 4) {
                            $service = pdo_get("xc_beauty_store_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                            $service_name = $service["name"];
                        } else {
                            $service = pdo_get("xc_beauty_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                            $service_name = $service["name"];
                        }
                        $order["userinfo"] = json_decode($order["userinfo"], true);
                        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                        if ($config) {
                            $config["content"] = json_decode($config["content"], true);
                            if (!empty($config["content"]["template_id"])) {
                                $account_api = WeAccount::create();
                                $token = $account_api->getAccessToken();
                                if (!empty($order["plan_date"])) {
                                    $plan_date = $order["plan_date"];
                                } else {
                                    $plan_date = date("Y-m-d");
                                }
                                $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["userinfo"]["name"]), "keyword3" => array("value" => $order["userinfo"]["mobile"]), "keyword4" => array("value" => $service["name"]), "keyword5" => array("value" => $plan_date));
                                $post_data["touser"] = $order["openid"];
                                $post_data["template_id"] = $config["content"]["template_id"];
                                $post_data["page"] = "xc_beauty/pages/base/base";
                                $post_data["form_id"] = $_GPC["form_id"];
                                $post_data["data"] = $postdata;
                                $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                        $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                        if ($sms) {
                            $sms["content"] = json_decode($sms["content"], true);
                            if ($sms["content"]["status"] == 1) {
                                $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                $store_name = '';
                                $mobile = $sms["content"]["mobile"];
                                if ($store) {
                                    $store_name = $store["name"];
                                    if (!empty($store["sms"])) {
                                        $mobile = $store["sms"];
                                    }
                                } else {
                                    $store_name = "/";
                                }
                                if ($sms["content"]["type"] == 1) {
                                    require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                    set_time_limit(0);
                                    header("Content-Type: text/plain; charset=utf-8");
                                    $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $condition["o_amount"], "namex" => $order["userinfo"]["name"], "phonex" => $order["userinfo"]["mobile"], "datex" => $order["plan_date"], "store" => $store_name, "service" => $service_name);
                                    if (!empty($order["userinfo"]["address"])) {
                                        $templateParam["addrx"] = $order["userinfo"]["address"];
                                    } else {
                                        $templateParam["addrx"] = "无";
                                    }
                                    $send = new sms();
                                    $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                    pdo_update("xc_beauty_order", array("callback1" => json_encode($result)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                } elseif ($sms["content"]["type"] == 2) {
                                    header("content-type:text/html;charset=utf-8");
                                    $sendUrl = "http://v.juhe.cn/sms/send";
                                    $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $condition["o_amount"] . "&#namex#=" . $order["userinfo"]["name"] . "&#phonex#=" . $order["userinfo"]["mobile"] . "&#datex#=" . $order["plan_date"] . "&#store#=" . $store_name . "&#service#=" . $service_name;
                                    if (!empty($order["userinfo"]["address"])) {
                                        $tpl_value = $tpl_value . "&#addrx#=" . $order["userinfo"]["address"];
                                    } else {
                                        $tpl_value = $tpl_value . "&#addrx#=无";
                                    }
                                    $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                    $content = juhecurl($sendUrl, $smsConf, 1);
                                    if ($content) {
                                        $result = json_decode($content, true);
                                        $error_code = $result["error_code"];
                                    }
                                    pdo_update("xc_beauty_order", array("callback1" => $content), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                } elseif ($sms["content"]["type"] == 3) {
                                    if (!empty($sms["content"]["url"])) {
                                        $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                        if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                            if (is_array($header["Location"])) {
                                                $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                            } else {
                                                $sms["content"]["url"] = $header["Location"];
                                            }
                                        }
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
                                            $post = str_replace("{{amount}}", $condition["o_amount"] . "元", $post);
                                            $post = str_replace("{{namex}}", $order["userinfo"]["name"], $post);
                                            $post = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $post);
                                            if (!empty($order["userinfo"]["address"])) {
                                                $post = str_replace("{{addrx}}", $order["userinfo"]["address"], $post);
                                            } else {
                                                $post = str_replace("{{addrx}}", "无", $post);
                                            }
                                            $post = str_replace("{{datex}}", $order["plan_date"], $post);
                                            $post = str_replace("{{store}}", $store_name, $post);
                                            $post = str_replace("{{service}}", $service_name, $post);
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
                                            pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
                                            $get = str_replace("{{amount}}", $condition["o_amount"] . "元", $get);
                                            $get = str_replace("{{namex}}", $order["userinfo"]["name"], $get);
                                            $get = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $get);
                                            if (!empty($order["userinfo"]["address"])) {
                                                $get = str_replace("{{addrx}}", $order["userinfo"]["address"], $get);
                                            } else {
                                                $get = str_replace("{{addrx}}", "无", $get);
                                            }
                                            $get = str_replace("{{datex}}", $order["plan_date"], $get);
                                            $get = str_replace("{{store}}", $store_name, $get);
                                            $get = str_replace("{{service}}", $service_name, $get);
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
                                            pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                        }
                                    }
                                }
                            }
                        }
                        $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                        if ($print) {
                            $print["content"] = json_decode($print["content"], true);
                            if ($print["content"]["status"] == 1) {
                                $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                $store_name = '';
                                $machine_code = $print["content"]["machine_code"];
                                $msign = $print["content"]["msign"];
                                $sn = $print["content"]["sn"];
                                if ($store && $store["print_status"] == 1) {
                                    $machine_code = $store["machine_code"];
                                    $msign = $store["msign"];
                                    $sn = $store["sn"];
                                    $store_name = $store["name"];
                                }
                                $member = '';
                                if (!empty($order["member"])) {
                                    $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                    if ($store_member) {
                                        $member = $store_member["name"];
                                    }
                                }
                                if ($print["content"]["type"] == 1) {
                                    $service_name = $service["name"];
                                    $time = time();
                                    $content = '';
                                    $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                    $content .= "门店：" . $store_name . "\r\n";
                                    if ($order["order_type"] == 4) {
                                        $content .= "类型：" . "预约" . "\r\n";
                                    } else {
                                        $content .= "类型：" . "项目" . "\r\n";
                                    }
                                    $content .= "--------------------------------\r\n";
                                    $content .= "服务项目：" . $service_name . "\r\n";
                                    $content .= "服务人员：" . $member . "\r\n";
                                    $content .= "预约时间：" . $order["plan_date"] . "\r\n";
                                    $content .= "客户姓名：" . $order["userinfo"]["name"] . "\r\n";
                                    $content .= "联系电话：" . $order["userinfo"]["mobile"] . "\r\n";
                                    $content .= "--------------------------------\r\n";
                                    $content .= "订单金额：" . $order["amount"] . "\r\n";
                                    $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                    $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                    $requestUrl = "http://open.10ss.net:8888";
                                    $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                    $requestInfo = http_build_query($requestAll);
                                    $request = push($requestInfo, $requestUrl);
                                    pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                } elseif ($print["content"]["type"] == 2) {
                                    include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                    define("USER", $print["content"]["user"]);
                                    define("UKEY", $print["content"]["ukey"]);
                                    define("SN", $sn);
                                    define("IP", "api.feieyun.cn");
                                    define("PORT", 80);
                                    define("PATH", "/Api/Open/");
                                    define("STIME", time());
                                    define("SIG", sha1(USER . UKEY . STIME));
                                    $service_list = pdo_getall("xc_cake_service", array("uniacid" => $uniacid));
                                    $service_data = array();
                                    if ($service_list) {
                                        foreach ($service_list as $sl) {
                                            $service_data[$sl["id"]] = $sl;
                                        }
                                    }
                                    $orderInfo = '';
                                    $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                    $orderInfo .= "门店：" . $store_name . "<BR>";
                                    if ($order["order_type"] == 4) {
                                        $orderInfo .= "类型：" . "预约" . "<BR>";
                                    } else {
                                        $orderInfo .= "类型：" . "项目" . "<BR>";
                                    }
                                    $orderInfo .= "--------------------------------<BR>";
                                    $orderInfo .= "服务项目：" . $service_name . "<BR>";
                                    $orderInfo .= "服务人员：" . $member . "<BR>";
                                    $orderInfo .= "预约时间：" . $order["plan_date"] . "<BR>";
                                    $orderInfo .= "客户姓名：" . $order["userinfo"]["name"] . "<BR>";
                                    $orderInfo .= "联系电话：" . $order["userinfo"]["mobile"] . "<BR>";
                                    $orderInfo .= "--------------------------------<BR>";
                                    $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                    $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                    $request = wp_print(SN, $orderInfo, 1);
                                    pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                }
                            }
                        }
                    } else {
                        if ($order["order_type"] == 3) {
                            $service = pdo_get("xc_beauty_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                            if ($service) {
                                if ($service["group_stock"] != -1 && intval($service["group_stock"]) > 0) {
                                    $sql = "UPDATE " . tablename("xc_beauty_service") . " SET group_stock=group_stock-:group_stock WHERE uniacid=:uniacid AND id=:id";
                                    pdo_query($sql, array(":group_stock" => $order["total"], ":uniacid" => $uniacid, ":id" => $order["pid"]));
                                }
                            }
                            $service_name = $service["name"];
                            $order["userinfo"] = json_decode($order["userinfo"], true);
                            $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                            if ($config) {
                                $config["content"] = json_decode($config["content"], true);
                                if (!empty($config["content"]["template_id"])) {
                                    $account_api = WeAccount::create();
                                    $token = $account_api->getAccessToken();
                                    if (!empty($order["plan_date"])) {
                                        $plan_date = $order["plan_date"];
                                    } else {
                                        $plan_date = date("Y-m-d");
                                    }
                                    $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["userinfo"]["name"]), "keyword3" => array("value" => $order["userinfo"]["mobile"]), "keyword4" => array("value" => $service["name"]), "keyword5" => array("value" => $plan_date));
                                    $post_data = array();
                                    $post_data["touser"] = $order["openid"];
                                    $post_data["template_id"] = $config["content"]["template_id"];
                                    $post_data["page"] = "xc_beauty/pages/base/base";
                                    $post_data["form_id"] = $order["form_id2"];
                                    $post_data["data"] = $postdata;
                                    $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                            if (!empty($order["group"])) {
                                $group = pdo_get("xc_beauty_group", array("id" => $order["group"], "uniacid" => $uniacid));
                                if ($group) {
                                    $group_data = array();
                                    if (!empty($group["team"])) {
                                        $group_data["team"] = json_decode($group["team"], true);
                                        $group_data["team"][] = $order["openid"];
                                    } else {
                                        $group_data["team"] = array($order["openid"]);
                                    }
                                    $group_data["team"] = json_encode($group_data["team"]);
                                    $group_data["status"] = -1;
                                    $group_data["team_total"] = $group["team_total"] + 1;
                                    if ($group_data["team_total"] == $group["total"]) {
                                        $group_data["status"] = 1;
                                    }
                                    $request = pdo_update("xc_beauty_group", $group_data, array("id" => $order["group"], "uniacid" => $uniacid));
                                    if ($request) {
                                        if ($group_data["status"] == 1) {
                                            $list = pdo_getall("xc_beauty_order", array("status" => 1, "order_type" => 3, "group" => $order["group"], "uniacid" => $uniacid));
                                            if ($list) {
                                                foreach ($list as $l) {
                                                    $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $l["openid"], "uniacid" => $uniacid));
                                                    if (!empty($l["score"])) {
                                                        $user_score = $userinfo["score"] + $l["score"];
                                                        pdo_update("xc_beauty_userinfo", array("score" => $user_score), array("status" => 1, "openid" => $l["openid"], "uniacid" => $uniacid));
                                                        pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $l["openid"], "status" => 1, "score" => $l["score"], "over" => $user_score, "title" => "消费"));
                                                    }
                                                    if (!empty($l["one_openid"])) {
                                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["one_openid"], "title" => "一级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["one_amount"], "level" => 1, "status" => -1));
                                                    }
                                                    if (!empty($l["two_openid"])) {
                                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["two_openid"], "title" => "二级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["two_amount"], "level" => 2, "status" => -1));
                                                    }
                                                    if (!empty($l["three_openid"])) {
                                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["three_openid"], "title" => "三级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["three_amount"], "level" => 3, "status" => -1));
                                                    }
                                                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                                    if ($config) {
                                                        $config["content"] = json_decode($config["content"], true);
                                                        if (!empty($config["content"]["group_success"])) {
                                                            $account_api = WeAccount::create();
                                                            $token = $account_api->getAccessToken();
                                                            $service = pdo_get("xc_beauty_service", array("id" => $l["pid"]));
                                                            $postdata = array("keyword1" => array("value" => $l["out_trade_no"]), "keyword2" => array("value" => $l["amount"]), "keyword3" => array("value" => $service["name"]), "keyword4" => array("value" => date("Y-m-d")));
                                                            $post_data = array();
                                                            $post_data["touser"] = $l["openid"];
                                                            $post_data["template_id"] = $config["content"]["group_success"];
                                                            $post_data["page"] = "xc_beauty/pages/base/base";
                                                            $post_data["form_id"] = $l["form_id"];
                                                            $post_data["data"] = $postdata;
                                                            $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $request = pdo_insert("xc_beauty_group", array("uniacid" => $uniacid, "openid" => $order["openid"], "pid" => $order["pid"], "failtime" => $service["group_limit"], "total" => $service["group_number"], "team_total" => 1));
                                if ($request) {
                                    $group = pdo_getall("xc_beauty_group", array("uniacid" => $uniacid, "openid" => $order["openid"]), array(), '', "id DESC");
                                    pdo_update("xc_beauty_order", array("group" => $group[0]["id"]), array("id" => $order["id"], "uniacid" => $uniacid));
                                }
                            }
                            if (!empty($order["member"])) {
                                $times_log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "plan_date" => $order["plan_date"], "member" => $order["member"], "createtime >=" => date("Y") . "-01-01 00:00:00"));
                                if ($times_log) {
                                    $times_log_total = intval($times_log["total"]) + 1;
                                    pdo_update("xc_beauty_times_log", array("total" => $times_log_total), array("uniacid" => $uniacid, "id" => $times_log["id"]));
                                } else {
                                    pdo_insert("xc_beauty_times_log", array("uniacid" => $uniacid, "member" => $order["member"], "plan_date" => $order["plan_date"], "total" => 1));
                                }
                            }
                            $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                            if ($sms) {
                                $sms["content"] = json_decode($sms["content"], true);
                                if ($sms["content"]["status"] == 1) {
                                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                    $store_name = '';
                                    $mobile = $sms["content"]["mobile"];
                                    if ($store) {
                                        $store_name = $store["name"];
                                        if (!empty($store["sms"])) {
                                            $mobile = $store["sms"];
                                        }
                                    } else {
                                        $store_name = "/";
                                    }
                                    if ($sms["content"]["type"] == 1) {
                                        require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                        set_time_limit(0);
                                        header("Content-Type: text/plain; charset=utf-8");
                                        $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $condition["o_amount"], "namex" => $order["userinfo"]["name"], "phonex" => $order["userinfo"]["mobile"], "datex" => $order["plan_date"], "store" => $store_name, "service" => $service_name);
                                        if (!empty($order["userinfo"]["address"])) {
                                            $templateParam["addrx"] = $order["userinfo"]["address"];
                                        } else {
                                            $templateParam["addrx"] = "无";
                                        }
                                        $send = new sms();
                                        $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                        pdo_update("xc_beauty_order", array("callback1" => json_encode($result)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                    } elseif ($sms["content"]["type"] == 2) {
                                        header("content-type:text/html;charset=utf-8");
                                        $sendUrl = "http://v.juhe.cn/sms/send";
                                        $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $condition["o_amount"] . "&#namex#=" . $order["userinfo"]["name"] . "&#phonex#=" . $order["userinfo"]["mobile"] . "&#datex#=" . $order["plan_date"] . "&#store#=" . $store_name . "&#service#=" . $service_name;
                                        if (!empty($order["userinfo"]["address"])) {
                                            $tpl_value = $tpl_value . "&#addrx#=" . $order["userinfo"]["address"];
                                        } else {
                                            $tpl_value = $tpl_value . "&#addrx#=无";
                                        }
                                        $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                        $content = juhecurl($sendUrl, $smsConf, 1);
                                        if ($content) {
                                            $result = json_decode($content, true);
                                            $error_code = $result["error_code"];
                                        }
                                        pdo_update("xc_beauty_order", array("callback1" => json_encode($content)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                    } elseif ($sms["content"]["type"] == 3) {
                                        if (!empty($sms["content"]["url"])) {
                                            $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                            if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                if (is_array($header["Location"])) {
                                                    $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                } else {
                                                    $sms["content"]["url"] = $header["Location"];
                                                }
                                            }
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
                                                $post = str_replace("{{amount}}", $condition["o_amount"] . "元", $post);
                                                $post = str_replace("{{namex}}", $order["userinfo"]["name"], $post);
                                                $post = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $post);
                                                if (!empty($order["userinfo"]["address"])) {
                                                    $post = str_replace("{{addrx}}", $order["userinfo"]["address"], $post);
                                                } else {
                                                    $post = str_replace("{{addrx}}", "无", $post);
                                                }
                                                $post = str_replace("{{datex}}", $order["plan_date"], $post);
                                                $post = str_replace("{{store}}", $store_name, $post);
                                                $post = str_replace("{{service}}", $service_name, $post);
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
                                                pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
                                                $get = str_replace("{{amount}}", $condition["o_amount"] . "元", $get);
                                                $get = str_replace("{{namex}}", $order["userinfo"]["name"], $get);
                                                $get = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $get);
                                                if (!empty($order["userinfo"]["address"])) {
                                                    $get = str_replace("{{addrx}}", $order["userinfo"]["address"], $get);
                                                } else {
                                                    $get = str_replace("{{addrx}}", "无", $get);
                                                }
                                                $get = str_replace("{{datex}}", $order["plan_date"], $get);
                                                $get = str_replace("{{store}}", $store_name, $get);
                                                $get = str_replace("{{service}}", $service_name, $get);
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
                                                pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            }
                                        }
                                    }
                                }
                            }
                            $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                            if ($print) {
                                $print["content"] = json_decode($print["content"], true);
                                if ($print["content"]["status"] == 1) {
                                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                    $store_name = '';
                                    $machine_code = $print["content"]["machine_code"];
                                    $msign = $print["content"]["msign"];
                                    $sn = $print["content"]["sn"];
                                    if ($store && $store["print_status"] == 1) {
                                        $machine_code = $store["machine_code"];
                                        $msign = $store["msign"];
                                        $sn = $store["sn"];
                                        $store_name = $store["name"];
                                    }
                                    $member = '';
                                    if (!empty($order["member"])) {
                                        $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                        if ($store_member) {
                                            $member = $store_member["name"];
                                        }
                                    }
                                    if ($print["content"]["type"] == 1) {
                                        $service_name = $service["name"];
                                        $time = time();
                                        $content = '';
                                        $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                        $content .= "门店：" . $store_name . "\r\n";
                                        if ($order["order_type"] == 4) {
                                            $content .= "类型：" . "预约" . "\r\n";
                                        } else {
                                            $content .= "类型：" . "项目" . "\r\n";
                                        }
                                        $content .= "--------------------------------\r\n";
                                        $content .= "服务项目：" . $service_name . "\r\n";
                                        $content .= "服务人员：" . $member . "\r\n";
                                        $content .= "预约时间：" . $order["plan_date"] . "\r\n";
                                        $content .= "客户姓名：" . $order["userinfo"]["name"] . "\r\n";
                                        $content .= "联系电话：" . $order["userinfo"]["mobile"] . "\r\n";
                                        $content .= "--------------------------------\r\n";
                                        $content .= "订单金额：" . $order["amount"] . "\r\n";
                                        $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                        $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                        $requestUrl = "http://open.10ss.net:8888";
                                        $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                        $requestInfo = http_build_query($requestAll);
                                        $request = push($requestInfo, $requestUrl);
                                        pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                    } elseif ($print["content"]["type"] == 2) {
                                        include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                        define("USER", $print["content"]["user"]);
                                        define("UKEY", $print["content"]["ukey"]);
                                        define("SN", $sn);
                                        define("IP", "api.feieyun.cn");
                                        define("PORT", 80);
                                        define("PATH", "/Api/Open/");
                                        define("STIME", time());
                                        define("SIG", sha1(USER . UKEY . STIME));
                                        $service_list = pdo_getall("xc_cake_service", array("uniacid" => $uniacid));
                                        $service_data = array();
                                        if ($service_list) {
                                            foreach ($service_list as $sl) {
                                                $service_data[$sl["id"]] = $sl;
                                            }
                                        }
                                        $orderInfo = '';
                                        $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                        $orderInfo .= "门店：" . $store_name . "<BR>";
                                        if ($order["order_type"] == 4) {
                                            $orderInfo .= "类型：" . "预约" . "<BR>";
                                        } else {
                                            $orderInfo .= "类型：" . "项目" . "<BR>";
                                        }
                                        $orderInfo .= "--------------------------------<BR>";
                                        $orderInfo .= "服务项目：" . $service_name . "<BR>";
                                        $orderInfo .= "服务人员：" . $member . "<BR>";
                                        $orderInfo .= "预约时间：" . $order["plan_date"] . "<BR>";
                                        $orderInfo .= "客户姓名：" . $order["userinfo"]["name"] . "<BR>";
                                        $orderInfo .= "联系电话：" . $order["userinfo"]["mobile"] . "<BR>";
                                        $orderInfo .= "--------------------------------<BR>";
                                        $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                        $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                        $request = wp_print(SN, $orderInfo, 1);
                                        pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                    }
                                }
                            }
                        }
                    }
                    return $this->result(0, "操作成功", array("status" => 2));
                }
            }
        } else {
            return $this->result(1, "支付失败");
        }
    }
    public function doPageOrderBuy()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if (!empty($_GPC["password"])) {
            if (md5($_GPC["password"]) != $userinfo["password"]) {
                return $this->result(1, "密码错误");
            }
        }
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["out_trade_no"] = setcode();
        $condition["pid"] = -1;
        $condition["store"] = $_GPC["store"];
        $condition["order_type"] = 5;
        $condition["amount"] = $_GPC["amount"];
        $condition["o_amount"] = $_GPC["amount"];
        $condition["discount"] = null;
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        $buy_sale_status = -1;
        $buy_sale = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($buy_sale) {
            $buy_sale["content"] = json_decode($buy_sale["content"], true);
            if (!empty($buy_sale["content"]) && !empty($buy_sale["content"]["buy_sale_status"])) {
                $buy_sale_status = $buy_sale["content"]["buy_sale_status"];
            }
        }
        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
        if ($card && $buy_sale_status == 1) {
            $card["content"] = json_decode($card["content"], true);
            if ($card["content"]["level_status"] == 1 && $userinfo["card"] == 1 && !empty($userinfo["card_price"])) {
                $condition["o_amount"] = round(floatval($condition["o_amount"]) * floatval($userinfo["card_price"]) / 10, 2);
                $condition["discount"] = $userinfo["card_price"];
            } elseif ($card["content"]["discount_status"] == 1 && !empty($card["content"]["discount"]) && $userinfo["card"] == 1) {
                $condition["o_amount"] = round(floatval($condition["o_amount"]) * floatval($card["content"]["discount"]) / 10, 2);
                $condition["discount"] = $card["content"]["discount"];
            }
        }
        if (!empty($_GPC["coupon_id"])) {
            $coupon = pdo_get("xc_beauty_coupon", array("status" => 1, "id" => $_GPC["coupon_id"]));
            $condition["coupon_id"] = $_GPC["coupon_id"];
            $condition["coupon_price"] = $coupon["name"];
            $condition["o_amount"] = round(floatval($condition["o_amount"]) - floatval($condition["coupon_price"]), 2);
            if (floatval($condition["o_amount"]) < 0) {
                $condition["o_amount"] = 0;
            }
        } else {
            $condition["coupon_id"] = null;
            $condition["coupon_price"] = null;
        }
        $condition["pay_type"] = $_GPC["pay_type"];
        if ($_GPC["pay_type"] == 1) {
            $condition["canpay"] = 0;
            $condition["wxpay"] = $condition["o_amount"];
        } elseif ($_GPC["pay_type"] == 2) {
            if (floatval($condition["o_amount"]) > floatval($userinfo["money"])) {
                return $this->result(1, "余额不足");
            } else {
                $condition["canpay"] = $condition["o_amount"];
                $condition["wxpay"] = 0;
            }
        }
        if (floatval($condition["canpay"]) < 0) {
            $condition["canpay"] = 0;
        }
        $request = pdo_insert("xc_beauty_order", $condition);
        if ($request) {
            if (!empty($condition["wxpay"])) {
                $tiangong = -1;
                $AppKey = '';
                $AppSecret = '';
                $agent_id = '';
                $user_id = '';
                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                    if (!empty($config["content"]["tiangong"]) && !empty($config["content"]["AppKey"]) && !empty($config["content"]["AppSecret"])) {
                        $tiangong = $config["content"]["tiangong"];
                        $AppKey = $config["content"]["AppKey"];
                        $AppSecret = $config["content"]["AppSecret"];
                        $agent_id = $config["content"]["agent_id"];
                        $user_id = $config["content"]["user_id"];
                    }
                }
                if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                    $url = "https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=" . $AppKey . "&client_secret=" . $AppSecret;
                    $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                    $result = vpost($url, $data);
                    $result = json_decode($result, true);
                    if (!empty($result["result"])) {
                        $url = "https://api.teegon.com/router?method=teegon.payment.charge.mppay&app_key=" . $result["result"]["key"] . "&client_secret=" . $result["result"]["secret"];
                        $notify_url = $_W["siteroot"] . "app/index.php?i=" . $uniacid . "&c=entry&do=TGong&m=" . $_GPC["m"];
                        $data = array("out_order_no" => $condition["out_trade_no"], "notify_url" => $notify_url, "return_url" => '', "amount" => $condition["wxpay"], "subject" => "买单", "wx_openid" => $_W["openid"], "mini_appid" => $_W["account"]["key"]);
                        $result = vpost($url, $data);
                        $result = json_decode($result, true);
                        if (!empty($result["result"])) {
                            pdo_update("xc_beauty_order", array("charge_id" => $result["result"]["charge_id"]), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                            $postdata = $result["result"]["action"]["params"];
                            $postdata["status"] = 1;
                            return $this->result(0, "操作成功", $postdata);
                        } else {
                            return $this->result(1, "操作失败");
                        }
                    } else {
                        return $this->result(1, "操作失败");
                    }
                } else {
                    $sub_status = -1;
                    $sub_appid = '';
                    $sub_mch_id = '';
                    $sub_key = '';
                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $_W["uniacid"]));
                    if ($config) {
                        $config["content"] = json_decode($config["content"], true);
                        if (!empty($config["content"]) && $config["content"]["sub_status"] && !empty($config["content"]["sub_appid"]) && !empty($config["content"]["sub_mch_id"]) && !empty($config["content"]["sub_key"])) {
                            $sub_status = $config["content"]["sub_status"];
                            $sub_appid = $config["content"]["sub_appid"];
                            $sub_mch_id = $config["content"]["sub_mch_id"];
                            $sub_key = $config["content"]["sub_key"];
                        }
                    }
                    if ($sub_status == 1 && !empty($sub_appid) && !empty($sub_mch_id) && !empty($sub_key)) {
                        $data["title"] = "买单";
                        $data["tid"] = $condition["out_trade_no"];
                        $data["fee"] = $condition["wxpay"];
                        $data["sub_appid"] = $sub_appid;
                        $data["sub_mch_id"] = $sub_mch_id;
                        $data["sub_key"] = $sub_key;
                        $postdata = sub_pay($data, $_GPC, $_W);
                        $postdata["status"] = 1;
                    } else {
                        $data["title"] = "买单";
                        $data["tid"] = $condition["out_trade_no"];
                        $data["fee"] = $condition["wxpay"];
                        $postdata = $this->pay($data);
                        $postdata["status"] = 1;
                    }
                    return $this->result(0, "操作成功", $postdata);
                }
            } else {
                if (!empty($_GPC["coupon_id"])) {
                    $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $_W["openid"], "uniacid" => $uniacid, "cid" => $_GPC["coupon_id"]));
                    pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
                }
                $money = round(floatval($userinfo["money"]) - floatval($condition["canpay"]), 2);
                pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid));
                pdo_update("xc_beauty_order", array("status" => 1), array("out_trade_no" => $condition["out_trade_no"], "openid" => $_W["openid"], "uniacid" => $uniacid));
                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                }
                $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                if ($sms) {
                    $sms["content"] = json_decode($sms["content"], true);
                    if ($sms["content"]["status"] == 1) {
                        $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $_GPC["store"]));
                        $store_name = '';
                        $mobile = $sms["content"]["mobile"];
                        if ($store) {
                            $store_name = $store["name"];
                            if (!empty($store["sms"])) {
                                $mobile = $store["sms"];
                            }
                        } else {
                            $store_name = "/";
                        }
                        if ($sms["content"]["type"] == 1) {
                            require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                            set_time_limit(0);
                            header("Content-Type: text/plain; charset=utf-8");
                            $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $condition["out_trade_no"], "amount" => $condition["o_amount"], "namex" => base64_decode($userinfo["nick"]), "phonex" => "/", "datex" => date("Y-m-d H:i:s"), "store" => $store_name, "service" => "买单");
                            $templateParam["addrx"] = "无";
                            $send = new sms();
                            $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                            pdo_update("xc_beauty_order", array("callback1" => json_encode($result)), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $_W["uniacid"]));
                        } elseif ($sms["content"]["type"] == 2) {
                            header("content-type:text/html;charset=utf-8");
                            $sendUrl = "http://v.juhe.cn/sms/send";
                            $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $condition["out_trade_no"] . "&#amount#=" . $condition["o_amount"] . "&#namex#=" . base64_decode($userinfo["nick"]) . "&#phonex#=/&#datex#=" . date("Y-m-d H:i:s") . "&#store#=" . $store_name . "&#service#=买单";
                            $tpl_value = $tpl_value . "&#addrx#=无";
                            $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                            $content = juhecurl($sendUrl, $smsConf, 1);
                            if ($content) {
                                $result = json_decode($content, true);
                                $error_code = $result["error_code"];
                            }
                            pdo_update("xc_beauty_order", array("callback1" => $content), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $_W["uniacid"]));
                        } elseif ($sms["content"]["type"] == 3) {
                            if (!empty($sms["content"]["url"])) {
                                $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                    if (is_array($header["Location"])) {
                                        $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                    } else {
                                        $sms["content"]["url"] = $header["Location"];
                                    }
                                }
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
                                    $post = str_replace("{{amount}}", $condition["o_amount"] . "元", $post);
                                    $post = str_replace("{{namex}}", base64_decode($userinfo["nick"]), $post);
                                    $post = str_replace("{{phonex}}", "/", $post);
                                    $post = str_replace("{{addrx}}", "无", $post);
                                    $post = str_replace("{{datex}}", date("Y-m-d H:i:s"), $post);
                                    $post = str_replace("{{store}}", $store_name, $post);
                                    $post = str_replace("{{service}}", "买单", $post);
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
                                    pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
                                    $get = str_replace("{{amount}}", $condition["o_amount"] . "元", $get);
                                    $get = str_replace("{{namex}}", base64_decode($userinfo["nick"]), $get);
                                    $get = str_replace("{{phonex}}", "/", $get);
                                    $get = str_replace("{{addrx}}", "无", $get);
                                    $get = str_replace("{{datex}}", date("Y-m-d H:i:s"), $get);
                                    $get = str_replace("{{store}}", $store_name, $get);
                                    $get = str_replace("{{service}}", "买单", $get);
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
                                    pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                }
                            }
                        }
                    }
                }
                $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                if ($print) {
                    $print["content"] = json_decode($print["content"], true);
                    if ($print["content"]["status"] == 1) {
                        $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $_GPC["store"]));
                        $store_name = '';
                        $machine_code = $print["content"]["machine_code"];
                        $msign = $print["content"]["msign"];
                        $sn = $print["content"]["sn"];
                        if ($store && $store["print_status"] == 1) {
                            $machine_code = $store["machine_code"];
                            $msign = $store["msign"];
                            $sn = $store["sn"];
                            $store_name = $store["name"];
                        }
                        if ($print["content"]["type"] == 1) {
                            $service_name = "买单";
                            $time = time();
                            $content = '';
                            $content .= "单号：" . $condition["out_trade_no"] . "\r\n";
                            $content .= "门店：" . $store_name . "\r\n";
                            $content .= "--------------------------------\r\n";
                            $content .= "服务项目：" . $service_name . "\r\n";
                            $content .= "--------------------------------\r\n";
                            $content .= "订单金额：" . $condition["amount"] . "\r\n";
                            $content .= "实付金额：" . $condition["o_amount"] . "\r\n";
                            $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                            $requestUrl = "http://open.10ss.net:8888";
                            $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                            $requestInfo = http_build_query($requestAll);
                            $request = push($requestInfo, $requestUrl);
                            pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $_W["uniacid"]));
                        } elseif ($print["content"]["type"] == 2) {
                            include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                            define("USER", $print["content"]["user"]);
                            define("UKEY", $print["content"]["ukey"]);
                            define("SN", $sn);
                            define("IP", "api.feieyun.cn");
                            define("PORT", 80);
                            define("PATH", "/Api/Open/");
                            define("STIME", time());
                            define("SIG", sha1(USER . UKEY . STIME));
                            $orderInfo = '';
                            $orderInfo .= "单号：" . $condition["out_trade_no"] . "<BR>";
                            $orderInfo .= "门店：" . $store_name . "<BR>";
                            $orderInfo .= "--------------------------------<BR>";
                            $orderInfo .= "服务项目：" . "买单" . "<BR>";
                            $orderInfo .= "--------------------------------<BR>";
                            $orderInfo .= "订单金额：" . $condition["amount"] . "<BR>";
                            $orderInfo .= "实付金额：" . $condition["o_amount"] . "<BR>";
                            $request = wp_print(SN, $orderInfo, 1);
                            pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $_W["uniacid"]));
                        }
                    }
                }
                return $this->result(0, "操作成功", array("status" => 2));
            }
        } else {
            return $this->result(1, "生成订单失败");
        }
    }
    public function doPageAdminBuy()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $admin = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($admin) {
            if ($admin["shop"] == -1) {
                return $this->result(1, "没有权限");
            }
        } else {
            return $this->result(1, "没有权限");
        }
        $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_GPC["openid"]));
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_GPC["openid"];
        $condition["pid"] = -1;
        $condition["store"] = $_GPC["store"];
        $condition["order_type"] = 5;
        $condition["amount"] = $_GPC["amount"];
        $condition["o_amount"] = $_GPC["amount"];
        $condition["discount"] = null;
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        $buy_sale_status = -1;
        $buy_sale = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($buy_sale) {
            $buy_sale["content"] = json_decode($buy_sale["content"], true);
            if (!empty($buy_sale["content"]) && !empty($buy_sale["content"]["buy_sale_status"])) {
                $buy_sale_status = $buy_sale["content"]["buy_sale_status"];
            }
        }
        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
        if ($card && $buy_sale_status == 1) {
            $card["content"] = json_decode($card["content"], true);
            if ($card["content"]["level_status"] == 1 && $userinfo["card"] == 1 && !empty($userinfo["card_price"])) {
                $condition["o_amount"] = round(floatval($condition["o_amount"]) * floatval($userinfo["card_price"]) / 10, 2);
                $condition["discount"] = $userinfo["card_price"];
            } elseif ($card["content"]["discount_status"] == 1 && !empty($card["content"]["discount"]) && $userinfo["card"] == 1) {
                $condition["o_amount"] = round(floatval($condition["o_amount"]) * floatval($card["content"]["discount"]) / 10, 2);
                $condition["discount"] = $card["content"]["discount"];
            }
        }
        if (!empty($_GPC["coupon_id"])) {
            $coupon = pdo_get("xc_beauty_coupon", array("status" => 1, "id" => $_GPC["coupon_id"]));
            $condition["coupon_id"] = $_GPC["coupon_id"];
            $condition["coupon_price"] = $coupon["name"];
            $condition["o_amount"] = round(floatval($condition["o_amount"]) - floatval($condition["coupon_price"]), 2);
            if (floatval($condition["o_amount"]) < 0) {
                $condition["o_amount"] = 0;
            }
        } else {
            $condition["coupon_id"] = null;
            $condition["coupon_price"] = null;
        }
        $condition["pay_type"] = $_GPC["pay_type"];
        $condition["canpay"] = $condition["o_amount"];
        $condition["wxpay"] = 0;
        if ($userinfo) {
            if (floatval($condition["o_amount"]) > floatval($userinfo["money"])) {
                return $this->result(1, "余额不足");
            }
        }
        $condition["out_trade_no"] = setcode();
        $condition["buy_type"] = 2;
        $request = pdo_insert("xc_beauty_order", $condition);
        if ($request) {
            if (!empty($_GPC["coupon_id"])) {
                $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $_GPC["openid"], "uniacid" => $uniacid, "cid" => $_GPC["coupon_id"]));
                pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
            }
            $money = round(floatval($userinfo["money"]) - floatval($condition["canpay"]), 2);
            pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $_GPC["openid"], "uniacid" => $uniacid));
            pdo_update("xc_beauty_order", array("status" => 1), array("out_trade_no" => $condition["out_trade_no"], "openid" => $_GPC["openid"], "uniacid" => $uniacid));
            return $this->result(0, "操作成功", array("status" => 2));
        } else {
            return $this->result(1, "生成订单失败");
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
            $sql = "SELECT * FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND status=-1 AND (out_trade_no=:out_trade_no OR wq_out_trade_no=:out_trade_no)";
            $order = pdo_fetch($sql, array(":uniacid" => $uniacid, ":out_trade_no" => $params["tid"]));
            logging_run($order);
            if ($order) {
                if ($order["order_type"] == 1 || $order["order_type"] == 3 || $order["order_type"] == 4) {
                    if ($order["order_type"] == 4) {
                        $service = pdo_get("xc_beauty_store_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                    } else {
                        $service = pdo_get("xc_beauty_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                    }
                    if (floatval($order["wxpay"]) == $params["fee"]) {
                        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"]));
                        if (floatval($order["canpay"]) != 0) {
                            $moeny = round(floatval($userinfo["money"]) - floatval($order["canpay"]), 2);
                            pdo_update("xc_beauty_userinfo", array("money" => $moeny), array("status" => 1, "openid" => $order["openid"]));
                        }
                        if (!empty($order["coupon_id"])) {
                            $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $order["openid"], "uniacid" => $uniacid, "cid" => $order["coupon_id"]));
                            pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
                        }
                        $score = null;
                        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                        if ($card) {
                            $card["content"] = json_decode($card["content"], true);
                            if ($card["content"]["score_status"] == 1 && !empty($card["content"]["score_attr"]) && !empty($card["content"]["score_value"]) && $userinfo["card"] == 1) {
                                $score = intval(floatval($order["o_amount"]) / floatval($card["content"]["score_attr"])) * $card["content"]["score_value"];
                            }
                        }
                        $share_config = array("level" => 3, "type" => '', "one" => '', "two" => '', "three" => '');
                        $share_status = 1;
                        $share = pdo_get("xc_beauty_config", array("xkey" => "share", "uniacid" => $uniacid));
                        if ($share) {
                            $share["content"] = json_decode($share["content"], true);
                            if (!empty($share["content"]["status"])) {
                                $share_status = $share["content"]["status"];
                            }
                            if (!empty($share["content"]["level"])) {
                                $share_config["level"] = $share["content"]["level"];
                            }
                            if (!empty($share["content"]["type"])) {
                                $share_config["type"] = $share["content"]["type"];
                                $share_config["one"] = $share["content"]["level_one"];
                                $share_config["two"] = $share["content"]["level_two"];
                                $share_config["three"] = $share["content"]["level_three"];
                            }
                        }
                        if (!empty($service["type"])) {
                            $share_config["type"] = $service["type"];
                            $share_config["one"] = $service["level_one"];
                            $share_config["two"] = $service["level_two"];
                            $share_config["three"] = $service["level_three"];
                        }
                        $share_condition["status"] = 1;
                        $share_condition["score"] = $score;
                        $share_condition["wx_out_trade_no"] = $params["uniontid"];
                        $share_condition["one_openid"] = null;
                        $share_condition["one_amount"] = null;
                        $share_condition["two_openid"] = null;
                        $share_condition["two_amount"] = null;
                        $share_condition["three_openid"] = null;
                        $share_condition["three_amount"] = null;
                        if (!empty($share_config["type"]) && $share_status == 1) {
                            if ($share_config["level"] >= 1 && !empty($share_config["one"]) && !empty($userinfo["share"])) {
                                $share_condition["one_openid"] = $userinfo["share"];
                                if ($share_config["type"] == 1) {
                                    $share_condition["one_amount"] = round(floatval($order["o_amount"]) * floatval($share_config["one"]) * 0.01, 2);
                                } elseif ($share_config["type"] == 2) {
                                    $share_condition["one_amount"] = $share_config["one"];
                                }
                                $one = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $userinfo["share"], "uniacid" => $uniacid));
                                if ($share_config["level"] >= 2 && !empty($share_config["two"]) && !empty($one["share"])) {
                                    $share_condition["two_openid"] = $one["share"];
                                    if ($share_config["type"] == 1) {
                                        $share_condition["two_amount"] = round(floatval($order["o_amount"]) * floatval($share_config["two"]) * 0.01, 2);
                                    } elseif ($share_config["type"] == 2) {
                                        $share_condition["two_amount"] = $share_config["two"];
                                    }
                                    $two = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $one["share"], "uniacid" => $uniacid));
                                    if ($share_config["level"] >= 3 && !empty($share_config["three"]) && !empty($two["share"])) {
                                        $share_condition["three_openid"] = $two["share"];
                                        if ($share_config["type"] == 1) {
                                            $share_condition["three_amount"] = round(floatval($order["o_amount"]) * floatval($share_config["three"]) * 0.01, 2);
                                        } elseif ($share_config["type"] == 2) {
                                            $share_condition["three_amount"] = $share_config["three"];
                                        }
                                    }
                                }
                            }
                        }
                        $request = pdo_update("xc_beauty_order", $share_condition, array("id" => $order["id"], "uniacid" => $uniacid));
                        logging_run($request);
                        if ($request) {
                            if ($order["order_type"] == 1 || $order["order_type"] == 4) {
                                $order["userinfo"] = json_decode($order["userinfo"], true);
                                if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                    $level_data = array();
                                    $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($order["wxpay"]), 2);
                                    if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                        foreach ($card["content"]["level"] as $card_l) {
                                            if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                $level_data["card_name"] = $card_l["name"];
                                                $level_data["card_price"] = $card_l["price"];
                                            }
                                        }
                                    }
                                    pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $order["openid"], "uniacid" => $uniacid));
                                }
                                $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                if ($count) {
                                    $orders = $count["order"] + 1;
                                    $amount = round(floatval($count["amount"]) + floatval($order["wxpay"]), 2);
                                    pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                } else {
                                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => -1));
                                }
                                if (!empty($order["store"]) && $order["store"] != -1) {
                                    $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                    if ($store_count) {
                                        $orders = $store_count["order"] + 1;
                                        $amount = round(floatval($store_count["amount"]) + floatval($order["wxpay"]), 2);
                                        pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                    } else {
                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 1));
                                    }
                                    $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                    if ($day_count) {
                                        $day_orders = $day_count["order"] + 1;
                                        $day_amount = round(floatval($day_count["amount"]) + floatval($order["wxpay"]), 2);
                                        pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                    } else {
                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 2));
                                    }
                                }
                                if ($share_status == 1) {
                                    if (!empty($share_condition["one_openid"])) {
                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["one_openid"], "title" => "一级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["one_amount"], "level" => 1, "status" => -1));
                                    }
                                    if (!empty($share_condition["two_openid"])) {
                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["two_openid"], "title" => "二级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["two_amount"], "level" => 2, "status" => -1));
                                    }
                                    if (!empty($share_condition["three_openid"])) {
                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["three_openid"], "title" => "三级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["three_amount"], "level" => 3, "status" => -1));
                                    }
                                }
                                if (!empty($score)) {
                                    $user_score = $userinfo["score"] + $score;
                                    pdo_update("xc_beauty_userinfo", array("score" => $user_score), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                                    pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $order["openid"], "status" => 1, "score" => $score, "over" => $user_score, "title" => "消费"));
                                }
                                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                if ($config) {
                                    $config["content"] = json_decode($config["content"], true);
                                    if (!empty($config["content"]["template_id"])) {
                                        require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                        $wechat = new Wechat();
                                        $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                        $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["userinfo"]["name"]), "keyword3" => array("value" => $order["userinfo"]["mobile"]), "keyword4" => array("value" => $service["name"]), "keyword5" => array("value" => $order["plan_date"]));
                                        $post_data["touser"] = $order["openid"];
                                        $post_data["template_id"] = $config["content"]["template_id"];
                                        $post_data["page"] = "xc_beauty/pages/base/base";
                                        $post_data["form_id"] = $order["form_id"];
                                        $post_data["data"] = $postdata;
                                        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                        logging_run($output);
                                    }
                                }
                                $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                                if ($print) {
                                    $print["content"] = json_decode($print["content"], true);
                                    if ($print["content"]["status"] == 1) {
                                        $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                        $store_name = '';
                                        $machine_code = $print["content"]["machine_code"];
                                        $msign = $print["content"]["msign"];
                                        $sn = $print["content"]["sn"];
                                        if ($store && $store["print_status"] == 1) {
                                            $machine_code = $store["machine_code"];
                                            $msign = $store["msign"];
                                            $sn = $store["sn"];
                                            $store_name = $store["name"];
                                        }
                                        $member = '';
                                        if (!empty($order["member"])) {
                                            $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                            if ($store_member) {
                                                $member = $store_member["name"];
                                            }
                                        }
                                        $service_name = $service["name"];
                                        if ($print["content"]["type"] == 1) {
                                            $time = time();
                                            $content = '';
                                            $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                            $content .= "门店：" . $store_name . "\r\n";
                                            if ($order["order_type"] == 4) {
                                                $content .= "类型：" . "预约" . "\r\n";
                                            } else {
                                                $content .= "类型：" . "项目" . "\r\n";
                                            }
                                            $content .= "--------------------------------\r\n";
                                            $content .= "服务项目：" . $service_name . "\r\n";
                                            $content .= "服务人员：" . $member . "\r\n";
                                            $content .= "预约时间：" . $order["plan_date"] . "\r\n";
                                            $content .= "客户姓名：" . $order["userinfo"]["name"] . "\r\n";
                                            $content .= "联系电话：" . $order["userinfo"]["mobile"] . "\r\n";
                                            $content .= "--------------------------------\r\n";
                                            $content .= "订单金额：" . $order["amount"] . "\r\n";
                                            $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                            $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                            $requestUrl = "http://open.10ss.net:8888";
                                            $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                            $requestInfo = http_build_query($requestAll);
                                            $request = push($requestInfo, $requestUrl);
                                            pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                        } elseif ($print["content"]["type"] == 2) {
                                            include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                            define("USER", $print["content"]["user"]);
                                            define("UKEY", $print["content"]["ukey"]);
                                            define("SN", $sn);
                                            define("IP", "api.feieyun.cn");
                                            define("PORT", 80);
                                            define("PATH", "/Api/Open/");
                                            define("STIME", time());
                                            define("SIG", sha1(USER . UKEY . STIME));
                                            $service_list = pdo_getall("xc_cake_service", array("uniacid" => $uniacid));
                                            $service_data = array();
                                            if ($service_list) {
                                                foreach ($service_list as $sl) {
                                                    $service_data[$sl["id"]] = $sl;
                                                }
                                            }
                                            $orderInfo = '';
                                            $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                            $orderInfo .= "门店：" . $store_name . "<BR>";
                                            if ($order["order_type"] == 4) {
                                                $orderInfo .= "类型：" . "预约" . "<BR>";
                                            } else {
                                                $orderInfo .= "类型：" . "项目" . "<BR>";
                                            }
                                            $orderInfo .= "--------------------------------<BR>";
                                            $orderInfo .= "服务项目：" . $service_name . "<BR>";
                                            $orderInfo .= "服务人员：" . $member . "<BR>";
                                            $orderInfo .= "预约时间：" . $order["plan_date"] . "<BR>";
                                            $orderInfo .= "客户姓名：" . $order["userinfo"]["name"] . "<BR>";
                                            $orderInfo .= "联系电话：" . $order["userinfo"]["mobile"] . "<BR>";
                                            $orderInfo .= "--------------------------------<BR>";
                                            $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                            $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                            $request = wp_print(SN, $orderInfo, 1);
                                            pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                        }
                                    }
                                }
                                $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                if ($sms) {
                                    $sms["content"] = json_decode($sms["content"], true);
                                    if ($sms["content"]["status"] == 1) {
                                        $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                        $mobile = $sms["content"]["mobile"];
                                        $store_name = '';
                                        if ($store) {
                                            $store_name = $store["name"];
                                            if (!empty($store["sms"])) {
                                                $mobile = $store["sms"];
                                            }
                                        } else {
                                            $store_name = "/";
                                        }
                                        if ($sms["content"]["type"] == 1) {
                                            require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                            $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => $order["userinfo"]["name"], "phonex" => $order["userinfo"]["mobile"], "datex" => $order["plan_date"], "store" => $store_name, "service" => $service["name"]);
                                            if (!empty($order["userinfo"]["address"])) {
                                                $templateParam["addrx"] = $order["userinfo"]["address"];
                                            } else {
                                                $templateParam["addrx"] = "无";
                                            }
                                            $send = new sms();
                                            $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                            pdo_update("xc_beauty_order", array("callback1" => json_encode($result)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                        } elseif ($sms["content"]["type"] == 2) {
                                            header("content-type:text/html;charset=utf-8");
                                            $sendUrl = "http://v.juhe.cn/sms/send";
                                            $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . $order["userinfo"]["name"] . "&#phonex#=" . $order["userinfo"]["mobile"] . "&#datex#=" . $order["plan_date"] . "&#store#=" . $store_name . "&#service#=" . $service["name"];
                                            if (!empty($order["userinfo"]["address"])) {
                                                $tpl_value = $tpl_value . "&#addrx#=" . $order["userinfo"]["address"];
                                            } else {
                                                $tpl_value = $tpl_value . "&#addrx#=无";
                                            }
                                            $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                            $content = juhecurl($sendUrl, $smsConf, 1);
                                            if ($content) {
                                                $result = json_decode($content, true);
                                                $error_code = $result["error_code"];
                                            }
                                            pdo_update("xc_beauty_order", array("callback1" => $content), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                        } elseif ($sms["content"]["type"] == 3) {
                                            if (!empty($sms["content"]["url"])) {
                                                $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                                if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                    if (is_array($header["Location"])) {
                                                        $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                    } else {
                                                        $sms["content"]["url"] = $header["Location"];
                                                    }
                                                }
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
                                                    $post = str_replace("{{amount}}", $order["o_amount"] . "元", $post);
                                                    $post = str_replace("{{namex}}", $order["userinfo"]["name"], $post);
                                                    $post = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $post);
                                                    if (!empty($order["userinfo"]["address"])) {
                                                        $post = str_replace("{{addrx}}", $order["userinfo"]["address"], $post);
                                                    } else {
                                                        $post = str_replace("{{addrx}}", "无", $post);
                                                    }
                                                    $post = str_replace("{{datex}}", $order["plan_date"], $post);
                                                    $post = str_replace("{{store}}", $store_name, $post);
                                                    $post = str_replace("{{service}}", $service["name"], $post);
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
                                                    pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
                                                    $get = str_replace("{{amount}}", $order["o_amount"] . "元", $get);
                                                    $get = str_replace("{{namex}}", $order["userinfo"]["name"], $get);
                                                    $get = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $get);
                                                    if (!empty($order["userinfo"]["address"])) {
                                                        $get = str_replace("{{addrx}}", $order["userinfo"]["address"], $get);
                                                    } else {
                                                        $get = str_replace("{{addrx}}", "无", $get);
                                                    }
                                                    $get = str_replace("{{datex}}", $order["plan_date"], $get);
                                                    $get = str_replace("{{store}}", $store_name, $get);
                                                    $get = str_replace("{{service}}", $service["name"], $get);
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
                                                    pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($order["order_type"] == 3) {
                                    if ($service) {
                                        if ($service["group_stock"] != -1 && intval($service["group_stock"]) > 0) {
                                            $sql = "UPDATE " . tablename("xc_beauty_service") . " SET group_stock=group_stock-:group_stock WHERE uniacid=:uniacid AND id=:id";
                                            pdo_query($sql, array(":group_stock" => $order["total"], ":uniacid" => $uniacid, ":id" => $order["pid"]));
                                        }
                                    }
                                    $order["userinfo"] = json_decode($order["userinfo"], true);
                                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                    if ($config) {
                                        $config["content"] = json_decode($config["content"], true);
                                        if (!empty($config["content"]["template_id"])) {
                                            require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                            $wechat = new Wechat();
                                            $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                            if (!empty($order["plan_date"])) {
                                                $plan_date = $order["plan_date"];
                                            } else {
                                                $plan_date = date("Y-m-d");
                                            }
                                            $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["userinfo"]["name"]), "keyword3" => array("value" => $order["userinfo"]["mobile"]), "keyword4" => array("value" => $service["name"]), "keyword5" => array("value" => $plan_date));
                                            $post_data = array();
                                            $post_data["touser"] = $order["openid"];
                                            $post_data["template_id"] = $config["content"]["template_id"];
                                            $post_data["page"] = "xc_beauty/pages/base/base";
                                            $post_data["form_id"] = $order["form_id2"];
                                            $post_data["data"] = $postdata;
                                            $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                    if (!empty($order["group"])) {
                                        $group = pdo_get("xc_beauty_group", array("id" => $order["group"], "uniacid" => $uniacid));
                                        logging_run($group);
                                        if ($group) {
                                            $group_data = array();
                                            if (!empty($group["team"])) {
                                                $group_data["team"] = json_decode($group["team"], true);
                                                $group_data["team"][] = $order["openid"];
                                            } else {
                                                $group_data["team"] = array($order["openid"]);
                                            }
                                            $group_data["team"] = json_encode($group_data["team"]);
                                            $group_data["status"] = -1;
                                            $group_data["team_total"] = $group["team_total"] + 1;
                                            if ($group_data["team_total"] == $group["total"]) {
                                                $group_data["status"] = 1;
                                            }
                                            $request = pdo_update("xc_beauty_group", $group_data, array("id" => $order["group"], "uniacid" => $uniacid));
                                            if ($request) {
                                                if ($group_data["status"] == 1) {
                                                    $list = pdo_getall("xc_beauty_order", array("status" => 1, "order_type" => 3, "group" => $order["group"]));
                                                    if ($list) {
                                                        foreach ($list as $l) {
                                                            $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $l["openid"], "uniacid" => $uniacid));
                                                            if (!empty($l["score"])) {
                                                                $user_score = $userinfo["score"] + $l["score"];
                                                                pdo_update("xc_beauty_userinfo", array("score" => $user_score), array("status" => 1, "openid" => $l["openid"], "uniacid" => $uniacid));
                                                                pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $l["openid"], "status" => 1, "score" => $l["score"], "over" => $user_score, "title" => "消费"));
                                                            }
                                                            if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                                                $level_data = array();
                                                                $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($l["wxpay"]), 2);
                                                                if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                                                    foreach ($card["content"]["level"] as $card_l) {
                                                                        if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                                            $level_data["card_name"] = $card_l["name"];
                                                                            $level_data["card_price"] = $card_l["price"];
                                                                        }
                                                                    }
                                                                }
                                                                pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $l["openid"], "uniacid" => $uniacid));
                                                            }
                                                            if (!empty($l["one_openid"])) {
                                                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["one_openid"], "title" => "一级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["one_amount"], "level" => 1, "status" => -1));
                                                            }
                                                            if (!empty($l["two_openid"])) {
                                                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["two_openid"], "title" => "二级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["two_amount"], "level" => 2, "status" => -1));
                                                            }
                                                            if (!empty($l["three_openid"])) {
                                                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["three_openid"], "title" => "三级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["three_amount"], "level" => 3, "status" => -1));
                                                            }
                                                            if (!empty($l["wxpay"])) {
                                                                $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                                                if ($count) {
                                                                    $orders = $count["order"] + 1;
                                                                    $amount = round(floatval($count["amount"]) + floatval($l["wxpay"]), 2);
                                                                    pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                                                } else {
                                                                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $l["wxpay"], "store" => -1));
                                                                }
                                                                if (!empty($l["store"]) && $l["store"] != -1) {
                                                                    $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $l["store"], "type" => 1));
                                                                    if ($store_count) {
                                                                        $orders = $store_count["order"] + 1;
                                                                        $amount = round(floatval($store_count["amount"]) + floatval($l["wxpay"]), 2);
                                                                        pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $l["store"], "type" => 1));
                                                                    } else {
                                                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $l["wxpay"], "store" => $l["store"], "type" => 1));
                                                                    }
                                                                    $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $l["store"], "type" => 2));
                                                                    if ($day_count) {
                                                                        $day_orders = $day_count["order"] + 1;
                                                                        $day_amount = round(floatval($day_count["amount"]) + floatval($l["wxpay"]), 2);
                                                                        pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $l["store"], "type" => 2));
                                                                    } else {
                                                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $l["wxpay"], "store" => $l["store"], "type" => 2));
                                                                    }
                                                                }
                                                            }
                                                            $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                                            if ($config) {
                                                                $config["content"] = json_decode($config["content"], true);
                                                                if (!empty($config["content"]["group_success"])) {
                                                                    require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                                                    $wechat = new Wechat();
                                                                    $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                                                    $service = pdo_get("xc_beauty_service", array("id" => $l["pid"]));
                                                                    $postdata = array("keyword1" => array("value" => $l["out_trade_no"]), "keyword2" => array("value" => $l["amount"]), "keyword3" => array("value" => $service["name"]), "keyword4" => array("value" => date("Y-m-d")));
                                                                    $post_data = array();
                                                                    $post_data["touser"] = $l["openid"];
                                                                    $post_data["template_id"] = $config["content"]["group_success"];
                                                                    $post_data["page"] = "xc_beauty/pages/base/base";
                                                                    $post_data["form_id"] = $l["form_id"];
                                                                    $post_data["data"] = $postdata;
                                                                    $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $request = pdo_insert("xc_beauty_group", array("uniacid" => $uniacid, "openid" => $order["openid"], "pid" => $order["pid"], "failtime" => $service["group_limit"], "total" => $service["group_number"], "team_total" => 1));
                                        if ($request) {
                                            $group = pdo_getall("xc_beauty_group", array("uniacid" => $uniacid, "openid" => $order["openid"]), array(), '', "id DESC");
                                            pdo_update("xc_beauty_order", array("group" => $group[0]["id"]), array("id" => $order["id"], "uniacid" => $uniacid));
                                        }
                                    }
                                    if (!empty($order["member"])) {
                                        $times_log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "plan_date" => $order["plan_date"], "member" => $order["member"], "createtime >=" => date("Y") . "-01-01 00:00:00"));
                                        if ($times_log) {
                                            $times_log_total = intval($times_log["total"]) + 1;
                                            pdo_update("xc_beauty_times_log", array("total" => $times_log_total), array("uniacid" => $uniacid, "id" => $times_log["id"]));
                                        } else {
                                            pdo_insert("xc_beauty_times_log", array("uniacid" => $uniacid, "member" => $order["member"], "plan_date" => $order["plan_date"], "total" => 1));
                                        }
                                    }
                                    $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                                    if ($print) {
                                        $print["content"] = json_decode($print["content"], true);
                                        if ($print["content"]["status"] == 1) {
                                            $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                            $store_name = '';
                                            $machine_code = $print["content"]["machine_code"];
                                            $msign = $print["content"]["msign"];
                                            $sn = $print["content"]["sn"];
                                            if ($store && $store["print_status"] == 1) {
                                                $machine_code = $store["machine_code"];
                                                $msign = $store["msign"];
                                                $sn = $store["sn"];
                                                $store_name = $store["name"];
                                            }
                                            $member = '';
                                            if (!empty($order["member"])) {
                                                $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                                if ($store_member) {
                                                    $member = $store_member["name"];
                                                }
                                            }
                                            $service_name = $service["name"];
                                            if ($print["content"]["type"] == 1) {
                                                $time = time();
                                                $content = '';
                                                $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                                $content .= "门店：" . $store_name . "\r\n";
                                                if ($order["order_type"] == 4) {
                                                    $content .= "类型：" . "预约" . "\r\n";
                                                } else {
                                                    $content .= "类型：" . "项目" . "\r\n";
                                                }
                                                $content .= "--------------------------------\r\n";
                                                $content .= "服务项目：" . $service_name . "\r\n";
                                                $content .= "服务人员：" . $member . "\r\n";
                                                $content .= "预约时间：" . $order["plan_date"] . "\r\n";
                                                $content .= "客户姓名：" . $order["userinfo"]["name"] . "\r\n";
                                                $content .= "联系电话：" . $order["userinfo"]["mobile"] . "\r\n";
                                                $content .= "--------------------------------\r\n";
                                                $content .= "订单金额：" . $order["amount"] . "\r\n";
                                                $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                                $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                                $requestUrl = "http://open.10ss.net:8888";
                                                $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                                $requestInfo = http_build_query($requestAll);
                                                $request = push($requestInfo, $requestUrl);
                                                pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            } elseif ($print["content"]["type"] == 2) {
                                                include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                                define("USER", $print["content"]["user"]);
                                                define("UKEY", $print["content"]["ukey"]);
                                                define("SN", $sn);
                                                define("IP", "api.feieyun.cn");
                                                define("PORT", 80);
                                                define("PATH", "/Api/Open/");
                                                define("STIME", time());
                                                define("SIG", sha1(USER . UKEY . STIME));
                                                $service_list = pdo_getall("xc_cake_service", array("uniacid" => $uniacid));
                                                $service_data = array();
                                                if ($service_list) {
                                                    foreach ($service_list as $sl) {
                                                        $service_data[$sl["id"]] = $sl;
                                                    }
                                                }
                                                $orderInfo = '';
                                                $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                                $orderInfo .= "门店：" . $store_name . "<BR>";
                                                if ($order["order_type"] == 4) {
                                                    $orderInfo .= "类型：" . "预约" . "<BR>";
                                                } else {
                                                    $orderInfo .= "类型：" . "项目" . "<BR>";
                                                }
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "服务项目：" . $service_name . "<BR>";
                                                $orderInfo .= "服务人员：" . $member . "<BR>";
                                                $orderInfo .= "预约时间：" . $order["plan_date"] . "<BR>";
                                                $orderInfo .= "客户姓名：" . $order["userinfo"]["name"] . "<BR>";
                                                $orderInfo .= "联系电话：" . $order["userinfo"]["mobile"] . "<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                                $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                                $request = wp_print(SN, $orderInfo, 1);
                                                pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            }
                                        }
                                    }
                                    $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                    if ($sms) {
                                        $sms["content"] = json_decode($sms["content"], true);
                                        if ($sms["content"]["status"] == 1) {
                                            $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                            $mobile = $sms["content"]["mobile"];
                                            $store_name = '';
                                            if ($store) {
                                                $store_name = $store["name"];
                                                if (!empty($store["sms"])) {
                                                    $mobile = $store["sms"];
                                                }
                                            } else {
                                                $store_name = "/";
                                            }
                                            if ($sms["content"]["type"] == 1) {
                                                require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                                $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => $order["userinfo"]["name"], "phonex" => $order["userinfo"]["mobile"], "datex" => $order["plan_date"], "store" => $store_name, "service" => $service["name"]);
                                                if (!empty($order["userinfo"]["address"])) {
                                                    $templateParam["addrx"] = $order["userinfo"]["address"];
                                                } else {
                                                    $templateParam["addrx"] = "无";
                                                }
                                                $send = new sms();
                                                $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                                pdo_update("xc_beauty_order", array("callback1" => json_encode($result)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            } elseif ($sms["content"]["type"] == 2) {
                                                header("content-type:text/html;charset=utf-8");
                                                $sendUrl = "http://v.juhe.cn/sms/send";
                                                $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . $order["userinfo"]["name"] . "&#phonex#=" . $order["userinfo"]["mobile"] . "&#datex#=" . $order["plan_date"] . "&#store#=" . $store_name . "&#service#=" . $service["name"];
                                                if (!empty($order["userinfo"]["address"])) {
                                                    $tpl_value = $tpl_value . "&#addrx#=" . $order["userinfo"]["address"];
                                                } else {
                                                    $tpl_value = $tpl_value . "&#addrx#=无";
                                                }
                                                $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                $content = juhecurl($sendUrl, $smsConf, 1);
                                                if ($content) {
                                                    $result = json_decode($content, true);
                                                    $error_code = $result["error_code"];
                                                }
                                                pdo_update("xc_beauty_order", array("callback1" => $content), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            } elseif ($sms["content"]["type"] == 3) {
                                                if (!empty($sms["content"]["url"])) {
                                                    $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                                    if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                        if (is_array($header["Location"])) {
                                                            $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                        } else {
                                                            $sms["content"]["url"] = $header["Location"];
                                                        }
                                                    }
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
                                                        $post = str_replace("{{amount}}", $order["o_amount"] . "元", $post);
                                                        $post = str_replace("{{namex}}", $order["userinfo"]["name"], $post);
                                                        $post = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $post);
                                                        if (!empty($order["userinfo"]["address"])) {
                                                            $post = str_replace("{{addrx}}", $order["userinfo"]["address"], $post);
                                                        } else {
                                                            $post = str_replace("{{addrx}}", "无", $post);
                                                        }
                                                        $post = str_replace("{{datex}}", $order["plan_date"], $post);
                                                        $post = str_replace("{{store}}", $store_name, $post);
                                                        $post = str_replace("{{service}}", $service["name"], $post);
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
                                                        pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
                                                        $get = str_replace("{{amount}}", $order["o_amount"] . "元", $get);
                                                        $get = str_replace("{{namex}}", $order["userinfo"]["name"], $get);
                                                        $get = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $get);
                                                        if (!empty($order["userinfo"]["address"])) {
                                                            $get = str_replace("{{addrx}}", $order["userinfo"]["address"], $get);
                                                        } else {
                                                            $get = str_replace("{{addrx}}", "无", $get);
                                                        }
                                                        $get = str_replace("{{datex}}", $order["plan_date"], $get);
                                                        $get = str_replace("{{store}}", $store_name, $get);
                                                        $get = str_replace("{{service}}", $service["name"], $get);
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
                                                        pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($order["order_type"] == 2) {
                        if (floatval($order["amount"]) == $params["fee"]) {
                            $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"]));
                            $money = floatval($userinfo["money"]) + floatval($order["amount"]);
                            if (!empty($order["gift"])) {
                                $money = round(floatval($money) + floatval($order["gift"]), 2);
                            }
                            $request = pdo_update("xc_beauty_order", array("status" => 1, "money" => $money, "wx_out_trade_no" => $params["uniontid"]), array("id" => $order["id"], "uniacid" => $uniacid));
                            if ($request) {
                                $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                                if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                    $level_data = array();
                                    $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($order["amount"]), 2);
                                    if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                        foreach ($card["content"]["level"] as $card_l) {
                                            if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                $level_data["card_name"] = $card_l["name"];
                                                $level_data["card_price"] = $card_l["price"];
                                            }
                                        }
                                    }
                                    pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $order["openid"], "uniacid" => $uniacid));
                                }
                                pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"]));
                                $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                if ($count) {
                                    $orders = $count["order"] + 1;
                                    $amount = round(floatval($count["amount"]) + floatval($order["amount"]), 2);
                                    pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                } else {
                                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["amount"], "store" => -1));
                                }
                                if (!empty($order["store"]) && $order["store"] != -1) {
                                    $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                    if ($store_count) {
                                        $orders = $store_count["order"] + 1;
                                        $amount = round(floatval($store_count["amount"]) + floatval($order["amount"]), 2);
                                        pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                    } else {
                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["amount"], "store" => $order["store"], "type" => 1));
                                    }
                                    $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                    if ($day_count) {
                                        $day_orders = $day_count["order"] + 1;
                                        $day_amount = round(floatval($day_count["amount"]) + floatval($order["amount"]), 2);
                                        pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                    } else {
                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $order["amount"], "store" => $order["store"], "type" => 2));
                                    }
                                }
                            }
                        }
                    } else {
                        if ($order["order_type"] == 5) {
                            if (floatval($order["wxpay"]) == $params["fee"]) {
                                $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"]));
                                if (floatval($order["canpay"]) != 0) {
                                    $moeny = round(floatval($userinfo["money"]) - floatval($order["canpay"]), 2);
                                    pdo_update("xc_beauty_userinfo", array("money" => $moeny), array("status" => 1, "openid" => $order["openid"]));
                                }
                                if (!empty($order["coupon_id"])) {
                                    $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $order["openid"], "uniacid" => $uniacid, "cid" => $order["coupon_id"]));
                                    pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
                                }
                                $request = pdo_update("xc_beauty_order", array("status" => 1), array("id" => $order["id"], "uniacid" => $uniacid));
                                if ($request) {
                                    $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                                    if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                        $level_data = array();
                                        $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($order["wxpay"]), 2);
                                        if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                            foreach ($card["content"]["level"] as $card_l) {
                                                if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                    $level_data["card_name"] = $card_l["name"];
                                                    $level_data["card_price"] = $card_l["price"];
                                                }
                                            }
                                        }
                                        pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $order["openid"], "uniacid" => $uniacid));
                                    }
                                    $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                    if ($count) {
                                        $orders = $count["order"] + 1;
                                        $amount = round(floatval($count["amount"]) + floatval($order["wxpay"]), 2);
                                        pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                    } else {
                                        pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => -1));
                                    }
                                    if (!empty($order["store"]) && $order["store"] != -1) {
                                        $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                        if ($store_count) {
                                            $orders = $store_count["order"] + 1;
                                            $amount = round(floatval($store_count["amount"]) + floatval($order["wxpay"]), 2);
                                            pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                        } else {
                                            pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 1));
                                        }
                                        $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                        if ($day_count) {
                                            $day_orders = $day_count["order"] + 1;
                                            $day_amount = round(floatval($day_count["amount"]) + floatval($order["wxpay"]), 2);
                                            pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                        } else {
                                            pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 2));
                                        }
                                    }
                                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                    if ($config) {
                                        $config["content"] = json_decode($config["content"], true);
                                    }
                                    $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                    if ($sms) {
                                        $sms["content"] = json_decode($sms["content"], true);
                                        if ($sms["content"]["status"] == 1) {
                                            $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                            $mobile = $sms["content"]["mobile"];
                                            $store_name = '';
                                            if ($store) {
                                                $store_name = $store["name"];
                                                if (!empty($store["sms"])) {
                                                    $mobile = $store["sms"];
                                                }
                                            } else {
                                                $store_name = "/";
                                            }
                                            if ($sms["content"]["type"] == 1) {
                                                require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                                $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => base64_decode($userinfo["nick"]), "phonex" => "/", "datex" => date("Y-m-d H:i:s"), "store" => $store_name, "service" => "买单");
                                                $templateParam["addrx"] = "无";
                                                $send = new sms();
                                                $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                                pdo_update("xc_beauty_order", array("callback1" => json_encode($result)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            } elseif ($sms["content"]["type"] == 2) {
                                                header("content-type:text/html;charset=utf-8");
                                                $sendUrl = "http://v.juhe.cn/sms/send";
                                                $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . base64_decode($userinfo["nick"]) . "&#phonex#=/&#datex#=" . date("Y-m-d H:i:s") . "&#store#=" . $store_name . "&#service#=买单";
                                                $tpl_value = $tpl_value . "&#addrx#=无";
                                                $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                $content = juhecurl($sendUrl, $smsConf, 1);
                                                if ($content) {
                                                    $result = json_decode($content, true);
                                                    $error_code = $result["error_code"];
                                                }
                                                pdo_update("xc_beauty_order", array("callback1" => $content), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            } elseif ($sms["content"]["type"] == 3) {
                                                if (!empty($sms["content"]["url"])) {
                                                    $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                                    if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                        if (is_array($header["Location"])) {
                                                            $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                        } else {
                                                            $sms["content"]["url"] = $header["Location"];
                                                        }
                                                    }
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
                                                        $post = str_replace("{{amount}}", $order["o_amount"] . "元", $post);
                                                        $post = str_replace("{{namex}}", base64_decode($userinfo["nick"]), $post);
                                                        $post = str_replace("{{phonex}}", "/", $post);
                                                        $post = str_replace("{{addrx}}", "无", $post);
                                                        $post = str_replace("{{datex}}", date("Y-m-d H:i:s"), $post);
                                                        $post = str_replace("{{store}}", $store_name, $post);
                                                        $post = str_replace("{{service}}", "买单", $post);
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
                                                        pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
                                                        $get = str_replace("{{amount}}", $order["o_amount"] . "元", $get);
                                                        $get = str_replace("{{namex}}", base64_decode($userinfo["nick"]), $get);
                                                        $get = str_replace("{{phonex}}", "/", $get);
                                                        $get = str_replace("{{addrx}}", "无", $get);
                                                        $get = str_replace("{{datex}}", date("Y-m-d H:i:s"), $get);
                                                        $get = str_replace("{{store}}", $store_name, $get);
                                                        $get = str_replace("{{service}}", "买单", $get);
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
                                                        pdo_update("xc_beauty_order", array("callback1" => $output), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                                    if ($print) {
                                        $print["content"] = json_decode($print["content"], true);
                                        if ($print["content"]["status"] == 1) {
                                            $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                            $store_name = '';
                                            $machine_code = $print["content"]["machine_code"];
                                            $msign = $print["content"]["msign"];
                                            $sn = $print["content"]["sn"];
                                            if ($store && $store["print_status"] == 1) {
                                                $machine_code = $store["machine_code"];
                                                $msign = $store["msign"];
                                                $sn = $store["sn"];
                                                $store_name = $store["name"];
                                            }
                                            $member = '';
                                            if (!empty($order["member"])) {
                                                $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                                if ($store_member) {
                                                    $member = $store_member["name"];
                                                }
                                            }
                                            $service_name = "买单";
                                            if ($print["content"]["type"] == 1) {
                                                $time = time();
                                                $content = '';
                                                $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                                $content .= "门店：" . $store_name . "\r\n";
                                                $content .= "--------------------------------\r\n";
                                                $content .= "服务项目：" . $service_name . "\r\n";
                                                $content .= "--------------------------------\r\n";
                                                $content .= "订单金额：" . $order["amount"] . "\r\n";
                                                $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                                $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                                $requestUrl = "http://open.10ss.net:8888";
                                                $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                                $requestInfo = http_build_query($requestAll);
                                                $request = push($requestInfo, $requestUrl);
                                                pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
                                            } elseif ($print["content"]["type"] == 2) {
                                                include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                                define("USER", $print["content"]["user"]);
                                                define("UKEY", $print["content"]["ukey"]);
                                                define("SN", $sn);
                                                define("IP", "api.feieyun.cn");
                                                define("PORT", 80);
                                                define("PATH", "/Api/Open/");
                                                define("STIME", time());
                                                define("SIG", sha1(USER . UKEY . STIME));
                                                $orderInfo = '';
                                                $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                                $orderInfo .= "门店：" . $store_name . "<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "服务项目：买单<BR>";
                                                $orderInfo .= "--------------------------------<BR>";
                                                $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                                $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                                $request = wp_print(SN, $orderInfo, 1);
                                                pdo_update("xc_beauty_order", array("callback2" => json_encode($request)), array("out_trade_no" => $order["out_trade_no"], "uniacid" => $_W["uniacid"]));
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
    public function doPageShare()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $share = pdo_get("xc_beauty_config", array("xkey" => "share", "uniacid" => $uniacid));
        if ($share) {
            $share["content"] = json_decode($share["content"], true);
            if (!empty($share["content"]["back"])) {
                $filename = "code_" . $_W["openid"] . ".jpg";
                $fileurl = $_SERVER["DOCUMENT_ROOT"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y");
                if (!is_dir($fileurl)) {
                    mkdir($fileurl, 0700, true);
                }
                $fileurl = $fileurl . "/" . date("m") . "/";
                if (!is_dir($fileurl)) {
                    mkdir($fileurl, 0700, true);
                }
                require_once dirname(__FILE__) . "/resource/WeChat.class.php";
                $wechat = new Wechat();
                $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $token;
                $post_data = array("page" => "xc_beauty/pages/base/base", "scene" => $_W["openid"]);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                $output = curl_exec($ch);
                curl_close($ch);
                $request = json_decode($output, true);
                if (is_array($request)) {
                    return $this->result(1, "图片生成失败");
                } else {
                    header("Content-Type: text/plain; charset=utf-8");
                    header("Content-type:image/jpeg");
                    $jpg = $output;
                    $file = fopen($fileurl . $filename, "w");
                    $reee = fwrite($file, $jpg);
                    fclose($file);
                    $share["content"]["code"] = $fileurl . $filename;
                    $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $_W["openid"]));
                    $url = usercode(tomedia($share["content"]["back"]), $userinfo, $share["content"], $_W["openid"], $_W);
                    return $this->result(0, "操作成功", array("share" => $url));
                }
            } else {
                return $this->result(1, "图片生成失败");
            }
        } else {
            return $this->result(1, "图片生成失败");
        }
    }
    public function doPageAdminCode()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $store = pdo_get("xc_beauty_store", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($store) {
            $share = '';
            if (!empty($store["code"])) {
                $share = $store["code"];
            } else {
                $filename = "store_" . $store["id"] . ".jpg";
                $fileurl = $_SERVER["DOCUMENT_ROOT"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y");
                if (!is_dir($fileurl)) {
                    mkdir($fileurl, 0700, true);
                }
                $fileurl = $fileurl . "/" . date("m") . "/";
                if (!is_dir($fileurl)) {
                    mkdir($fileurl, 0700, true);
                }
                require_once dirname(__FILE__) . "/resource/WeChat.class.php";
                $wechat = new Wechat();
                $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $token;
                $post_data = array("page" => "xc_beauty/pages/base/base", "scene" => "xc_beauty/pages/order/buy?&id=" . $store["id"]);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                $output = curl_exec($ch);
                curl_close($ch);
                $request = json_decode($output, true);
                if (is_array($request)) {
                    return $this->result(1, "图片生成失败", $request);
                } else {
                    header("Content-Type: text/plain; charset=utf-8");
                    header("Content-type:image/jpeg");
                    $jpg = $output;
                    $file = fopen($fileurl . $filename, "w");
                    $reee = fwrite($file, $jpg);
                    fclose($file);
                    $share = "https://" . $_SERVER["HTTP_HOST"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y") . "/" . date("m") . "/" . $filename;
                    pdo_update("xc_beauty_store", array("code" => $share), array("id" => $_GPC["id"], "uniacid" => $uniacid));
                }
            }
            return $this->result(0, "操作成功", array("share" => $share));
        } else {
            return $this->result(1, "门店不存在");
        }
    }
    public function doPageGroupRefund()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        set_time_limit(0);
        ignore_user_abort(true);
        $config = pdo_get("xc_beauty_config", array("xkey" => "group", "uniacid" => $uniacid));
        if ($config) {
            if ($config["content"] == date("Y-m-d")) {
                return $this->result(0, "图片生成失败");
            } else {
                pdo_update("xc_beauty_config", array("content" => date("Y-m-d")), array("xkey" => "group", "uniacid" => $uniacid));
            }
        } else {
            pdo_insert("xc_beauty_config", array("xkey" => "group", "uniacid" => $uniacid, "content" => date("Y-m-d")));
        }
        $group = pdo_getall("xc_beauty_group", array("status" => -1, "uniacid" => $uniacid));
        if ($group) {
            $group_refund = 1;
            $group_config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
            if ($group_config) {
                $group_config["content"] = json_decode($group_config["content"], true);
                if (!empty($group_config["content"]["group_refund"])) {
                    $group_refund = $group_config["content"]["group_refund"];
                }
            }
            foreach ($group as &$g) {
                if (strtotime($g["createtime"]) + intval($g["failtime"]) * 60 * 60 < time()) {
                    $order = pdo_get("xc_beauty_order", array("status" => 1, "uniacid" => $uniacid, "group" => $g["id"], "openid" => $g["openid"]));
                    if ($order) {
                        if ($group_refund == 1) {
                            pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                        } else {
                            if ($group_refund == 2) {
                                $can_list = -1;
                                if (floatval($order["wxpay"]) != 0) {
                                    $tiangong = -1;
                                    $AppKey = '';
                                    $AppSecret = '';
                                    $agent_id = '';
                                    $user_id = '';
                                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                    if ($config) {
                                        $config["content"] = json_decode($config["content"], true);
                                        if (!empty($config["content"]["tiangong"]) && !empty($config["content"]["AppKey"]) && !empty($config["content"]["AppSecret"])) {
                                            $tiangong = $config["content"]["tiangong"];
                                            $AppKey = $config["content"]["AppKey"];
                                            $AppSecret = $config["content"]["AppSecret"];
                                            $agent_id = $config["content"]["agent_id"];
                                            $user_id = $config["content"]["user_id"];
                                        }
                                    }
                                    if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                                        if (!empty($order["charge_id"])) {
                                            $url = "https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=" . $AppKey . "&client_secret=" . $AppSecret;
                                            $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                                            $result = vpost($url, $data);
                                            $result = json_decode($result, true);
                                            if (!empty($result["result"])) {
                                                $url = "https://api.teegon.com/router?method=teegon.payment.charge.refund&app_key=" . $result["result"]["key"] . "&client_secret=" . $result["result"]["secret"];
                                                $data = array("charge_id" => $order["charge_id"], "amount" => $order["wxpay"]);
                                                $result = vpost($url, $data);
                                                $result = json_decode($result, true);
                                                if (empty($result["result"])) {
                                                    $can_list = 1;
                                                }
                                            }
                                        }
                                    } else {
                                        $config = pdo_get("uni_settings", array("uniacid" => $uniacid));
                                        $cert = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "refund"));
                                        if ($config && $cert) {
                                            $cert["content"] = json_decode($cert["content"], true);
                                            if (!empty($cert["content"]["cert"]) && !empty($cert["content"]["key"])) {
                                                $config["payment"] = unserialize($config["payment"]);
                                                $appid = $_W["account"]["key"];
                                                $transaction_id = $order["wx_out_trade_no"];
                                                $total_fee = floatval($order["wxpay"]) * 100;
                                                $refund_fee = floatval($order["wxpay"]) * 100;
                                                $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
                                                $ref = strtoupper(md5("appid=" . $appid . "&mch_id=" . $config["payment"]["wechat"]["mchid"] . "&nonce_str=123456" . "&out_refund_no=" . $transaction_id . "&out_trade_no=" . $transaction_id . "&refund_fee=" . $refund_fee . "&total_fee=" . $total_fee . "&key=" . $config["payment"]["wechat"]["signkey"]));
                                                $refund = array("appid" => $appid, "mch_id" => $config["payment"]["wechat"]["mchid"], "nonce_str" => "123456", "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee, "sign" => $ref);
                                                $xml = arrayToXml($refund);
                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_URL, $url);
                                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                                                curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                                                $cert_file = "../addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                                                if (($TxtRes = fopen($cert_file, "w+")) === false) {
                                                    echo "创建可写文件：" . $cert_file . "失败";
                                                    exit;
                                                }
                                                $StrConents = $cert["content"]["cert"];
                                                if (!fwrite($TxtRes, $StrConents)) {
                                                    echo "尝试向文件" . $cert_file . "写入" . $StrConents . "失败！";
                                                    fclose($TxtRes);
                                                    exit;
                                                }
                                                fclose($TxtRes);
                                                curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                                                curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                                                $key_file = "../addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                                                if (($TxtRes = fopen($key_file, "w+")) === false) {
                                                    echo "创建可写文件：" . $key_file . "失败";
                                                    exit;
                                                }
                                                $StrConents = $cert["content"]["key"];
                                                if (!fwrite($TxtRes, $StrConents)) {
                                                    echo "尝试向文件" . $key_file . "写入" . $StrConents . "失败！";
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
                                                    if ($data["return_code"] == "SUCCESS") {
                                                        if ($data["result_code"] == "SUCCESS") {
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
                                    if (floatval($order["canpay"]) != 0) {
                                        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                                        $money = round(floatval($userinfo["money"]) + floatval($order["canpay"]), 2);
                                        $request = pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                                    }
                                    pdo_update("xc_beauty_order", array("refund_status" => 1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                } else {
                                    pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                }
                            }
                        }
                        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                        if ($config) {
                            $config["content"] = json_decode($config["content"], true);
                            if (!empty($config["content"]["group_fail"])) {
                                require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                $wechat = new Wechat();
                                $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                $service = pdo_get("xc_beauty_service", array("id" => $order["pid"]));
                                $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["amount"]), "keyword3" => array("value" => $service["name"]), "keyword4" => array("value" => "人数不足"));
                                $post_data["touser"] = $order["openid"];
                                $post_data["template_id"] = $config["content"]["group_fail"];
                                $post_data["page"] = "xc_beauty/pages/base/base";
                                $post_data["form_id"] = $order["form_id"];
                                $post_data["data"] = $postdata;
                                $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                        $sql = "UPDATE " . tablename("xc_beauty_service") . " SET group_stock=group_stock+:group_stock WHERE uniacid=:uniacid AND id=:id AND group_stock>=0";
                        pdo_query($sql, array(":group_stock" => $order["total"], ":uniacid" => $uniacid, ":id" => $order["pid"]));
                    }
                    if (!empty($g["team"])) {
                        $g["team"] = json_decode($g["team"], true);
                        foreach ($g["team"] as $gg) {
                            $order = pdo_get("xc_beauty_order", array("status" => 1, "uniacid" => $uniacid, "group" => $g["id"], "openid" => $gg));
                            if ($order) {
                                if ($group_refund == 1) {
                                    pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                } else {
                                    if ($group_refund == 2) {
                                        $can_list = -1;
                                        if (floatval($order["wxpay"]) != 0) {
                                            $tiangong = -1;
                                            $AppKey = '';
                                            $AppSecret = '';
                                            $agent_id = '';
                                            $user_id = '';
                                            $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                            if ($config) {
                                                $config["content"] = json_decode($config["content"], true);
                                                if (!empty($config["content"]["tiangong"]) && !empty($config["content"]["AppKey"]) && !empty($config["content"]["AppSecret"])) {
                                                    $tiangong = $config["content"]["tiangong"];
                                                    $AppKey = $config["content"]["AppKey"];
                                                    $AppSecret = $config["content"]["AppSecret"];
                                                    $agent_id = $config["content"]["agent_id"];
                                                    $user_id = $config["content"]["user_id"];
                                                }
                                            }
                                            if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                                                if (!empty($order["charge_id"])) {
                                                    $url = "https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=" . $AppKey . "&client_secret=" . $AppSecret;
                                                    $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                                                    $result = vpost($url, $data);
                                                    $result = json_decode($result, true);
                                                    if (!empty($result["result"])) {
                                                        $url = "https://api.teegon.com/router?method=teegon.payment.charge.refund&app_key=" . $result["result"]["key"] . "&client_secret=" . $result["result"]["secret"];
                                                        $data = array("charge_id" => $order["charge_id"], "amount" => $order["wxpay"]);
                                                        $result = vpost($url, $data);
                                                        $result = json_decode($result, true);
                                                        if (empty($result["result"])) {
                                                            pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                                            exit;
                                                        }
                                                    } else {
                                                        pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                                        exit;
                                                    }
                                                }
                                            } else {
                                                $config = pdo_get("uni_settings", array("uniacid" => $uniacid));
                                                $cert = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "refund"));
                                                if ($config && $cert) {
                                                    $cert["content"] = json_decode($cert["content"], true);
                                                    if (!empty($cert["content"]["cert"]) && !empty($cert["content"]["key"])) {
                                                        $config["payment"] = unserialize($config["payment"]);
                                                        $appid = $_W["account"]["key"];
                                                        $transaction_id = $order["wx_out_trade_no"];
                                                        $total_fee = floatval($order["wxpay"]) * 100;
                                                        $refund_fee = floatval($order["wxpay"]) * 100;
                                                        $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
                                                        $ref = strtoupper(md5("appid=" . $appid . "&mch_id=" . $config["payment"]["wechat"]["mchid"] . "&nonce_str=123456" . "&out_refund_no=" . $transaction_id . "&out_trade_no=" . $transaction_id . "&refund_fee=" . $refund_fee . "&total_fee=" . $total_fee . "&key=" . $config["payment"]["wechat"]["signkey"]));
                                                        $refund = array("appid" => $appid, "mch_id" => $config["payment"]["wechat"]["mchid"], "nonce_str" => "123456", "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee, "sign" => $ref);
                                                        $xml = arrayToXml($refund);
                                                        $ch = curl_init();
                                                        curl_setopt($ch, CURLOPT_URL, $url);
                                                        curl_setopt($ch, CURLOPT_HEADER, 0);
                                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                                                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                                                        $cert_file = "../addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                                                        if (($TxtRes = fopen($cert_file, "w+")) === false) {
                                                            echo "创建可写文件：" . $cert_file . "失败";
                                                            exit;
                                                        }
                                                        $StrConents = $cert["content"]["cert"];
                                                        if (!fwrite($TxtRes, $StrConents)) {
                                                            echo "尝试向文件" . $cert_file . "写入" . $StrConents . "失败！";
                                                            fclose($TxtRes);
                                                            exit;
                                                        }
                                                        fclose($TxtRes);
                                                        curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                                                        curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                                                        $key_file = "../addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                                                        if (($TxtRes = fopen($key_file, "w+")) === false) {
                                                            echo "创建可写文件：" . $key_file . "失败";
                                                            exit;
                                                        }
                                                        $StrConents = $cert["content"]["key"];
                                                        if (!fwrite($TxtRes, $StrConents)) {
                                                            echo "尝试向文件" . $key_file . "写入" . $StrConents . "失败！";
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
                                                            if ($data["return_code"] == "SUCCESS") {
                                                                if ($data["result_code"] == "SUCCESS") {
                                                                    $can_list = 1;
                                                                }
                                                            } else {
                                                                pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                                                exit;
                                                            }
                                                        } else {
                                                            $error = curl_errno($ch);
                                                            curl_close($ch);
                                                            pdo_update("xc_beauty_order", array("refund_status" => -1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                                            exit;
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $can_list = 1;
                                        }
                                        if ($can_list == 1) {
                                            if (floatval($order["canpay"]) != 0) {
                                                $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                                                $money = round(floatval($userinfo["money"]) + floatval($order["canpay"]), 2);
                                                $request = pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                                            }
                                        }
                                        pdo_update("xc_beauty_order", array("refund_status" => 1, "status" => 2), array("id" => $order["id"], "uniacid" => $uniacid));
                                    }
                                }
                                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                if ($config) {
                                    $config["content"] = json_decode($config["content"], true);
                                    if (!empty($config["content"]["group_fail"])) {
                                        require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                        $wechat = new Wechat();
                                        $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                        $service = pdo_get("xc_beauty_service", array("id" => $order["pid"]));
                                        $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["amount"]), "keyword3" => array("value" => $service["name"]), "keyword4" => array("value" => "人数不足"));
                                        $post_data["touser"] = $order["openid"];
                                        $post_data["template_id"] = $config["content"]["group_fail"];
                                        $post_data["page"] = "xc_beauty/pages/base/base";
                                        $post_data["form_id"] = $order["form_id"];
                                        $post_data["data"] = $postdata;
                                        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                $sql = "UPDATE " . tablename("xc_beauty_service") . " SET group_stock=group_stock+:group_stock WHERE uniacid=:uniacid AND id=:id AND group_stock>=0";
                                pdo_query($sql, array(":group_stock" => $order["total"], ":uniacid" => $uniacid, ":id" => $order["pid"]));
                            }
                        }
                    }
                    $request = pdo_update("xc_beauty_group", array("status" => 2), array("status" => -1, "uniacid" => $uniacid, "id" => $g["id"]));
                }
            }
        }
    }
    public function doPageOrderRefund()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $order = pdo_get("xc_beauty_order", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($order) {
            $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
            if (floatval($order["wxpay"]) != 0) {
                $tiangong = -1;
                $AppKey = '';
                $AppSecret = '';
                $agent_id = '';
                $user_id = '';
                $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                    if (!empty($config["content"]["tiangong"]) && !empty($config["content"]["AppKey"]) && !empty($config["content"]["AppSecret"])) {
                        $tiangong = $config["content"]["tiangong"];
                        $AppKey = $config["content"]["AppKey"];
                        $AppSecret = $config["content"]["AppSecret"];
                        $agent_id = $config["content"]["agent_id"];
                        $user_id = $config["content"]["user_id"];
                    }
                }
                if ($tiangong == 1 && !empty($AppKey) && !empty($AppSecret)) {
                    if (!empty($order["charge_id"])) {
                        $url = "https://api.teegon.com/router?method=teegon.agent.mfuser.get.pay.secret&app_key=" . $AppKey . "&client_secret=" . $AppSecret;
                        $data = array("agent_id" => $agent_id, "user_id" => $user_id, "secret_type" => 1);
                        $result = vpost($url, $data);
                        $result = json_decode($result, true);
                        if (!empty($result["result"])) {
                            $url = "https://api.teegon.com/router?method=teegon.payment.charge.refund&app_key=" . $result["result"]["key"] . "&client_secret=" . $result["result"]["secret"];
                            $data = array("charge_id" => $order["charge_id"], "amount" => $order["wxpay"]);
                            if (!empty($_GPC["tui_amount"])) {
                                $data["amount"] = $_GPC["tui_amount"];
                            }
                            $result = vpost($url, $data);
                            $result = json_decode($result, true);
                            if (empty($result["result"])) {
                                return $this->result(1, $result["emsg"]);
                                exit;
                            }
                        } else {
                            return $this->result(1, "失败");
                            exit;
                        }
                    }
                } else {
                    $config = pdo_get("uni_settings", array("uniacid" => $uniacid));
                    $cert = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "refund"));
                    if ($config && $cert) {
                        $cert["content"] = json_decode($cert["content"], true);
                        if (!empty($cert["content"]["cert"]) && !empty($cert["content"]["key"])) {
                            $config["payment"] = unserialize($config["payment"]);
                            $appid = $_W["account"]["key"];
                            $transaction_id = $order["wx_out_trade_no"];
                            $total_fee = floatval($order["wxpay"]) * 100;
                            $refund_fee = floatval($order["wxpay"]) * 100;
                            if (!empty($_GPC["tui_amount"])) {
                                $refund_fee = floatval($_GPC["tui_amount"]) * 100;
                            }
                            $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
                            $ref = strtoupper(md5("appid=" . $appid . "&mch_id=" . $config["payment"]["wechat"]["mchid"] . "&nonce_str=123456" . "&out_refund_no=" . $transaction_id . "&out_trade_no=" . $transaction_id . "&refund_fee=" . $refund_fee . "&total_fee=" . $total_fee . "&key=" . $config["payment"]["wechat"]["signkey"]));
                            $refund = array("appid" => $appid, "mch_id" => $config["payment"]["wechat"]["mchid"], "nonce_str" => "123456", "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee, "sign" => $ref);
                            $xml = arrayToXml($refund);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                            curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                            $cert_file = "../addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                            if (($TxtRes = fopen($cert_file, "w+")) === false) {
                                echo "创建可写文件：" . $cert_file . "失败";
                                exit;
                            }
                            $StrConents = $cert["content"]["cert"];
                            if (!fwrite($TxtRes, $StrConents)) {
                                echo "尝试向文件" . $cert_file . "写入" . $StrConents . "失败！";
                                fclose($TxtRes);
                                exit;
                            }
                            fclose($TxtRes);
                            curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                            curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                            $key_file = "../addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                            if (($TxtRes = fopen($key_file, "w+")) === false) {
                                echo "创建可写文件：" . $key_file . "失败";
                                exit;
                            }
                            $StrConents = $cert["content"]["key"];
                            if (!fwrite($TxtRes, $StrConents)) {
                                echo "尝试向文件" . $key_file . "写入" . $StrConents . "失败！";
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
                                if ($data["return_code"] == "SUCCESS") {
                                    if (!$data["result_code"] == "SUCCESS") {
                                        return $this->result(1, $data["err_code_des"]);
                                        exit;
                                    }
                                } else {
                                    return $this->result(1, "操作失败");
                                    exit;
                                }
                            } else {
                                $error = curl_errno($ch);
                                curl_close($ch);
                                return $this->result(1, "操作失败");
                                exit;
                            }
                        } else {
                            return $this->result(1, "操作失败");
                            exit;
                        }
                    }
                }
            }
            if (floatval($order["canpay"]) != 0) {
                $money = round(floatval($userinfo["money"]) + floatval($order["canpay"]), 2);
                $request = pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
            }
            if (!empty($order["score"]) && $order["order_type"] != 3) {
                $score = $userinfo["score"] - $order["score"];
                pdo_update("xc_beauty_userinfo", array("score" => $score), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
            }
            $share = pdo_get("xc_beauty_share", array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"], "out_trade_no" => $order["out_trade_no"]));
            if ($share && $order["order_type"] != 3) {
                $share_o_amount = round(floatval($userinfo["share_o_amount"]) - floatval($share["amount"]), 2);
                $share_empty = round(floatval($userinfo["share_empty"]) + floatval($share["amount"]));
                pdo_update("xc_beauty_userinfo", array("share_o_amount" => $share_o_amount, "share_empty" => $share_empty), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                pdo_update("xc_beauty_share", array("status" => 2), array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"], "out_trade_no" => $order["out_trade_no"]));
            }
            $request = pdo_update("xc_beauty_order", array("refund_status" => 1, "status" => 2), array("id" => $_GPC["id"], "uniacid" => $uniacid));
            if ($request) {
                return $this->result(0, "操作成功", array("status" => 1));
            } else {
                return $this->result(1, "操作失败");
            }
        } else {
            return $this->result(1, "操作失败");
        }
    }
    public function doPagePickOrder()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        if (empty($_W["openid"])) {
            return $this->result(1, "请先授权");
        }
        $userinfo = pdo_get("xc_beauty_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
        if (!($userinfo["shop"] == 1 || $userinfo["shop"] == 2 && $userinfo["shop_id"] == $_GPC["store"])) {
            return $this->result(1, "没有权限");
        }
        $total = 0;
        $amount = 0;
        $pid = array();
        $shop = pdo_getall("xc_beauty_shop", array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid), array(), '', "createtime DESC");
        if ($shop) {
            $service = pdo_getall("xc_beauty_pick_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($shop as &$x) {
                $x["service"] = $datalist[$x["pid"]]["name"];
                $x["price"] = $datalist[$x["pid"]]["price"];
                $x["unit"] = $datalist[$x["pid"]]["unit"];
                $total = $total + $x["total"];
                $amount = $amount + $x["price"] * $x["total"];
                $pid[] = array("id" => $x["pid"], "total" => $x["total"], "price" => $x["price"], "unit" => $x["unit"]);
            }
            $condition["uniacid"] = $uniacid;
            $condition["store"] = $_GPC["store"];
            $condition["openid"] = $_W["openid"];
            $condition["out_trade_no"] = setcode();
            $condition["pid"] = json_encode($pid);
            $condition["total"] = $total;
            $condition["amount"] = $amount;
            $request = pdo_insert("xc_beauty_pick_order", $condition);
            if ($request) {
                if (empty($_GPC["id"])) {
                    pdo_update("xc_beauty_shop", array("status" => -1, "total" => 0), array("uniacid" => $uniacid, "openid" => $_W["openid"]));
                }
                return $this->result(0, "操作成功", array("status" => 1));
            } else {
                return $this->result(1, "操作失败");
            }
        } else {
            return $this->result(1, "操作失败");
        }
    }
    public function doPageOrderDo()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        set_time_limit(0);
        ignore_user_abort(true);
        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if (!$config) {
            return $this->result(0, "网站未配置");
        }
        $config["content"] = json_decode($config["content"], true);
        if (!empty($config["content"]["order_fail"]) && !empty($config["content"]["order_do"])) {
            $order = pdo_get("xc_beauty_config", array("xkey" => "order", "uniacid" => $uniacid));
            if ($order) {
                if (strtotime($order["content"]) + $config["content"]["order_do"] >= time()) {
                    return $this->result(0, "订单已处理");
                    exit;
                } else {
                    pdo_update("xc_beauty_config", array("content" => date("Y-m-d H:i:s")), array("xkey" => "order", "uniacid" => $uniacid));
                }
            } else {
                pdo_insert("xc_beauty_config", array("xkey" => "order", "uniacid" => $uniacid, "content" => date("Y-m-d H:i:s")));
            }
            $fail_order = pdo_getall("xc_beauty_order", array("status" => -1, "uniacid" => $uniacid, "failtime <=" => date("Y-m-d H:i:s"), "failstatus" => -1, "order_type IN" => array(1, 4)));
            if ($fail_order) {
                foreach ($fail_order as $x) {
                    if (!empty($x["member"]) && !empty($x["plan_date"])) {
                        $time_log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "member" => $x["member"], "plan_date" => $x["plan_date"], "createtime >=" => date("Y") . "-01-01"));
                        if ($time_log) {
                            pdo_update("xc_beauty_times_log", array("total" => $time_log["total"] - intval($x["can_member"])), array("uniacid" => $uniacid, "member" => $x["member"], "plan_date" => $x["plan_date"], "createtime >=" => date("Y") . "-01-01"));
                        }
                        if ($x["flash"] == 1) {
                            pdo_query("UPDATE " . tablename("xc_beauty_service") . " SET flash_member=flash_member+:member WHERE uniacid=:uniacid AND id=:id", array(":member" => $x["total"], ":uniacid" => $uniacid, ":id" => $x["pid"]));
                        }
                    }
                    pdo_update("xc_beauty_order", array("failstatus" => 1), array("id" => $x["id"], "uniacid" => $uniacid));
                }
            }
            return $this->result(0, "处理成功");
        } else {
            return $this->result(0, "订单失效时间或订单处理时间未配置");
        }
    }
    public function doPageWithdraw()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["pay_type"] = $_GPC["pay_type"];
        $condition["username"] = $_GPC["username"];
        if ($_GPC["pay_type"] == 1) {
            $condition["mobile"] = $_GPC["mobile"];
        } else {
            if ($_GPC["pay_type"] == 2) {
                $condition["name"] = $_GPC["name"];
            }
        }
        $condition["amount"] = $_GPC["amount"];
        $condition["order_type"] = $_GPC["order_type"];
        if ($condition["order_type"] == 1) {
            $card = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "card"));
            if ($card) {
                $card["content"] = json_decode($card["content"], true);
                if (!empty($card["content"]["withdraw_amount"])) {
                    if (floatval($_GPC["amount"] < floatval($card["content"]["withdraw_amount"]))) {
                        return $this->result(1, $card["content"]["withdraw_amount"] . "元起提");
                    }
                }
            }
        } elseif ($condition["order_type"] == 2) {
            $share = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "share"));
            if ($share) {
                $share["content"] = json_decode($share["content"], true);
                if (!empty($share["content"]["withdraw_amount"])) {
                    if (floatval($_GPC["amount"] < floatval($share["content"]["withdraw_amount"]))) {
                        return $this->result(1, $share["content"]["withdraw_amount"] . "元起提");
                    }
                }
            }
        }
        $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
        if ($userinfo) {
            if ($condition["order_type"] == 1) {
                if (floatval($userinfo["money"]) < floatval($_GPC["amount"])) {
                    return $this->result(1, "余额不足");
                } else {
                    $money = round(floatval($userinfo["money"]) - floatval($_GPC["amount"]), 2);
                    pdo_update("xc_beauty_userinfo", array("money" => $money), array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
                    $condition["money"] = $money;
                }
            } elseif ($condition["order_type"] == 2) {
                if (floatval($userinfo["share_o_amount"]) < floatval($_GPC["amount"])) {
                    return $this->result(1, "余额不足");
                } else {
                    $money = round(floatval($userinfo["share_o_amount"]) - floatval($_GPC["amount"]), 2);
                    pdo_update("xc_beauty_userinfo", array("share_o_amount" => $money), array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
                    $condition["money"] = $money;
                }
            }
        }
        $condition["out_trade_no"] = setcode();
        $request = pdo_insert("xc_beauty_withdraw", $condition);
        if (!$request) {
            return $this->result(1, "操作失败");
        }
        $ti_status = -1;
        $ti_config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($ti_config) {
            $ti_config["content"] = json_decode($ti_config["content"], true);
            if (!empty($ti_config["content"]) && !empty($ti_config["content"]["ti_status"])) {
                $ti_status = $ti_config["content"]["ti_status"];
            }
        }
        if ($ti_status == 1 && $condition["pay_type"] == 1) {
            $config = pdo_get("uni_settings", array("uniacid" => $uniacid));
            $cert = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "refund"));
            if ($config && $cert) {
                $cert["content"] = json_decode($cert["content"], true);
                if (!empty($cert["content"]["cert"]) && !empty($cert["content"]["key"])) {
                    $config["payment"] = unserialize($config["payment"]);
                    $appid = $_W["account"]["key"];
                    $mch_id = $config["payment"]["wechat"]["mchid"];
                    $arr = array();
                    $arr["mch_appid"] = $appid;
                    $arr["mchid"] = $mch_id;
                    $arr["nonce_str"] = "123456";
                    $arr["partner_trade_no"] = $condition["out_trade_no"];
                    $arr["openid"] = $condition["openid"];
                    $arr["check_name"] = "NO_CHECK";
                    $arr["amount"] = floatval($condition["amount"]) * 100;
                    $desc = "提现";
                    $arr["desc"] = $desc;
                    $user_IP = $_SERVER["HTTP_VIA"] ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
                    $user_IP = $user_IP ? $user_IP : $_SERVER["REMOTE_ADDR"];
                    $arr["spbill_create_ip"] = $user_IP;
                    $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
                    ksort($arr);
                    $str = '';
                    foreach ($arr as $k => $v) {
                        $str .= $k . "=" . $v . "&";
                    }
                    $str .= "key=" . $config["payment"]["wechat"]["signkey"];
                    $arr["sign"] = md5($str);
                    $xml = arrayToXml($arr);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                    curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                    $cert_file = IA_ROOT . "/addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                    if (($TxtRes = fopen($cert_file, "w+")) === false) {
                        echo "创建可写文件：" . $cert_file . "失败";
                        exit;
                    }
                    $StrConents = $cert["content"]["cert"];
                    if (!fwrite($TxtRes, $StrConents)) {
                        echo "尝试向文件" . $cert_file . "写入" . $StrConents . "失败！";
                        fclose($TxtRes);
                        exit;
                    }
                    fclose($TxtRes);
                    curl_setopt($ch, CURLOPT_SSLCERT, $cert_file);
                    curl_setopt($ch, CURLOPT_SSLCERTTYPE, "pem");
                    $key_file = IA_ROOT . "/addons/" . $_GPC["m"] . "/resource/" . rand(100000, 999999) . ".pem";
                    if (($TxtRes = fopen($key_file, "w+")) === false) {
                        echo "创建可写文件：" . $key_file . "失败";
                        exit;
                    }
                    $StrConents = $cert["content"]["key"];
                    if (!fwrite($TxtRes, $StrConents)) {
                        echo "尝试向文件" . $key_file . "写入" . $StrConents . "失败！";
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
                        if ($data["return_code"] == "SUCCESS") {
                            if ($data["result_code"] == "SUCCESS") {
                                pdo_update("xc_beauty_withdraw", array("status" => 1, "wx_out_trade_no" => $data["payment_no"]), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                            } else {
                                pdo_update("xc_beauty_withdraw", array("content" => $data["err_code"] . "：" . $data["err_code_des"]), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                            }
                        } else {
                            pdo_update("xc_beauty_withdraw", array("content" => $data["return_msg"]), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                        }
                    } else {
                        $error = curl_errno($ch);
                        curl_close($ch);
                        pdo_update("xc_beauty_withdraw", array("content" => $error), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                    }
                } else {
                    pdo_update("xc_beauty_withdraw", array("content" => "证书未上传"), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
                }
            } else {
                pdo_update("xc_beauty_withdraw", array("content" => "证书未配置"), array("out_trade_no" => $condition["out_trade_no"], "uniacid" => $uniacid));
            }
        }
        return $this->result(0, "操作成功", array("status" => 1));
    }
    public function doPageCheck()
    {
        global $_GPC, $_W;
        $uniacid = $_W["uniacid"];
        set_time_limit(0);
        ignore_user_abort(true);
        $order = pdo_get("xc_beauty_order", array("out_trade_no" => $_GPC["out_trade_no"], "uniacid" => $uniacid));
        if ($order) {
            if ($order["status"] == 1) {
                return $this->result(0, "操作成功666", array("status" => 1));
            } else {
                if (!empty($order["wq_out_trade_no"])) {
                    $pay = pdo_get("core_paylog", array("uniacid" => $uniacid, "openid" => $order["openid"], "tid" => $order["wq_out_trade_no"]));
                } else {
                    $pay = pdo_get("core_paylog", array("uniacid" => $uniacid, "openid" => $order["openid"], "tid" => $order["out_trade_no"]));
                }
                if ($pay) {
                    $setting = uni_setting_load("payment", $_W["uniacid"]);
                    $params = array("appid" => $_W["account"]["key"], "mch_id" => $setting["payment"]["wechat"]["mchid"], "nonce_str" => random(32));
                    $params["out_trade_no"] = $pay["uniontid"];
                    ksort($params);
                    $string = array2url($params);
                    $string = $string . "&key={$setting["payment"]["wechat"]["signkey"]}";
                    $string = md5($string);
                    $params["sign"] = strtoupper($string);
                    load()->func("communication");
                    $xml = array2xml($params);
                    $result = ihttp_request("https://api.mch.weixin.qq.com/pay/orderquery", $xml, $params);
                    $result = xml2array($result["content"]);
                    if (is_error($result)) {
                        return $this->result(0, "操作失败", array("status" => -1));
                    }
                    if ($result["result_code"] != "SUCCESS") {
                        return $this->result(0, "操作失败", array("status" => -1));
                    }
                    if ($result["trade_state"] == "SUCCESS") {
                        $total_fee = $result["total_fee"] / 100;
                        if (floatval($total_fee) == floatval($order["wxpay"])) {
                            if ($order["order_type"] == 1 || $order["order_type"] == 3 || $order["order_type"] == 4) {
                                if ($order["order_type"] == 4) {
                                    $service = pdo_get("xc_beauty_store_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                                } else {
                                    $service = pdo_get("xc_beauty_service", array("uniacid" => $uniacid, "id" => $order["pid"]));
                                }
                                $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"]));
                                if (floatval($order["canpay"]) != 0) {
                                    $moeny = round(floatval($userinfo["money"]) - floatval($order["canpay"]), 2);
                                    pdo_update("xc_beauty_userinfo", array("money" => $moeny), array("status" => 1, "openid" => $order["openid"]));
                                }
                                if (!empty($order["coupon_id"])) {
                                    $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $order["openid"], "uniacid" => $uniacid, "cid" => $order["coupon_id"]));
                                    pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
                                }
                                $score = null;
                                $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                                if ($card) {
                                    $card["content"] = json_decode($card["content"], true);
                                    if ($card["content"]["score_status"] == 1 && !empty($card["content"]["score_attr"]) && !empty($card["content"]["score_value"]) && $userinfo["card"] == 1) {
                                        $score = intval(floatval($order["o_amount"]) / floatval($card["content"]["score_attr"])) * $card["content"]["score_value"];
                                    }
                                }
                                $share_config = array("level" => 3, "type" => '', "one" => '', "two" => '', "three" => '');
                                $share_status = 1;
                                $share = pdo_get("xc_beauty_config", array("xkey" => "share", "uniacid" => $uniacid));
                                if ($share) {
                                    $share["content"] = json_decode($share["content"], true);
                                    if (!empty($share["content"]["status"])) {
                                        $share_status = $share["content"]["status"];
                                    }
                                    if (!empty($share["content"]["level"])) {
                                        $share_config["level"] = $share["content"]["level"];
                                    }
                                    if (!empty($share["content"]["type"])) {
                                        $share_config["type"] = $share["content"]["type"];
                                        $share_config["one"] = $share["content"]["level_one"];
                                        $share_config["two"] = $share["content"]["level_two"];
                                        $share_config["three"] = $share["content"]["level_three"];
                                    }
                                }
                                if (!empty($service["type"])) {
                                    $share_config["type"] = $service["type"];
                                    $share_config["one"] = $service["level_one"];
                                    $share_config["two"] = $service["level_two"];
                                    $share_config["three"] = $service["level_three"];
                                }
                                $share_condition["status"] = 1;
                                $share_condition["score"] = $score;
                                $share_condition["wx_out_trade_no"] = $result["transaction_id"];
                                $share_condition["one_openid"] = null;
                                $share_condition["one_amount"] = null;
                                $share_condition["two_openid"] = null;
                                $share_condition["two_amount"] = null;
                                $share_condition["three_openid"] = null;
                                $share_condition["three_amount"] = null;
                                if (!empty($share_config["type"]) && $share_status == 1) {
                                    if ($share_config["level"] >= 1 && !empty($share_config["one"]) && !empty($userinfo["share"])) {
                                        $share_condition["one_openid"] = $userinfo["share"];
                                        if ($share_config["type"] == 1) {
                                            $share_condition["one_amount"] = round(floatval($order["o_amount"]) * floatval($share_config["one"]) * 0.01, 2);
                                        } elseif ($share_config["type"] == 2) {
                                            $share_condition["one_amount"] = $share_config["one"];
                                        }
                                        $one = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $userinfo["share"], "uniacid" => $uniacid));
                                        if ($share_config["level"] >= 2 && !empty($share_config["two"]) && !empty($one["share"])) {
                                            $share_condition["two_openid"] = $one["share"];
                                            if ($share_config["type"] == 1) {
                                                $share_condition["two_amount"] = round(floatval($order["o_amount"]) * floatval($share_config["two"]) * 0.01, 2);
                                            } elseif ($share_config["type"] == 2) {
                                                $share_condition["two_amount"] = $share_config["two"];
                                            }
                                            $two = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $one["share"], "uniacid" => $uniacid));
                                            if ($share_config["level"] >= 3 && !empty($share_config["three"]) && !empty($two["share"])) {
                                                $share_condition["three_openid"] = $two["share"];
                                                if ($share_config["type"] == 1) {
                                                    $share_condition["three_amount"] = round(floatval($order["o_amount"]) * floatval($share_config["three"]) * 0.01, 2);
                                                } elseif ($share_config["type"] == 2) {
                                                    $share_condition["three_amount"] = $share_config["three"];
                                                }
                                            }
                                        }
                                    }
                                }
                                $request = pdo_update("xc_beauty_order", $share_condition, array("id" => $order["id"], "uniacid" => $uniacid));
                                if ($request) {
                                    if ($order["order_type"] == 1 || $order["order_type"] == 4) {
                                        $order["userinfo"] = json_decode($order["userinfo"], true);
                                        if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                            $level_data = array();
                                            $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($order["wxpay"]), 2);
                                            if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                                foreach ($card["content"]["level"] as $card_l) {
                                                    if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                        $level_data["card_name"] = $card_l["name"];
                                                        $level_data["card_price"] = $card_l["price"];
                                                    }
                                                }
                                            }
                                            pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $order["openid"], "uniacid" => $uniacid));
                                        }
                                        $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                        if ($count) {
                                            $orders = $count["order"] + 1;
                                            $amount = round(floatval($count["amount"]) + floatval($order["wxpay"]), 2);
                                            pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                        } else {
                                            pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => -1));
                                        }
                                        if (!empty($order["store"]) && $order["store"] != -1) {
                                            $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                            if ($store_count) {
                                                $orders = $store_count["order"] + 1;
                                                $amount = round(floatval($store_count["amount"]) + floatval($order["wxpay"]), 2);
                                                pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                            } else {
                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 1));
                                            }
                                            $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                            if ($day_count) {
                                                $day_orders = $day_count["order"] + 1;
                                                $day_amount = round(floatval($day_count["amount"]) + floatval($order["wxpay"]), 2);
                                                pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                            } else {
                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 2));
                                            }
                                        }
                                        if ($share_status == 1) {
                                            if (!empty($share_condition["one_openid"])) {
                                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["one_openid"], "title" => "一级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["one_amount"], "level" => 1, "status" => -1));
                                            }
                                            if (!empty($share_condition["two_openid"])) {
                                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["two_openid"], "title" => "二级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["two_amount"], "level" => 2, "status" => -1));
                                            }
                                            if (!empty($share_condition["three_openid"])) {
                                                pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $share_condition["three_openid"], "title" => "三级订单结算奖励", "out_trade_no" => $order["out_trade_no"], "amount" => $share_condition["three_amount"], "level" => 3, "status" => -1));
                                            }
                                        }
                                        if (!empty($score)) {
                                            $user_score = $userinfo["score"] + $score;
                                            pdo_update("xc_beauty_userinfo", array("score" => $user_score), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                                            pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $order["openid"], "status" => 1, "score" => $score, "over" => $user_score, "title" => "消费"));
                                        }
                                        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                        if ($config) {
                                            $config["content"] = json_decode($config["content"], true);
                                            if (!empty($config["content"]["template_id"])) {
                                                require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                                $wechat = new Wechat();
                                                $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                                $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["userinfo"]["name"]), "keyword3" => array("value" => $order["userinfo"]["mobile"]), "keyword4" => array("value" => $service["name"]), "keyword5" => array("value" => $order["plan_date"]));
                                                $post_data["touser"] = $order["openid"];
                                                $post_data["template_id"] = $config["content"]["template_id"];
                                                $post_data["page"] = "xc_beauty/pages/base/base";
                                                $post_data["form_id"] = $order["form_id"];
                                                $post_data["data"] = $postdata;
                                                $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                        $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                                        if ($print) {
                                            $print["content"] = json_decode($print["content"], true);
                                            if ($print["content"]["status"] == 1) {
                                                $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                                $store_name = '';
                                                $machine_code = $print["content"]["machine_code"];
                                                $msign = $print["content"]["msign"];
                                                $sn = $print["content"]["sn"];
                                                if ($store && $store["print_status"] == 1) {
                                                    $machine_code = $store["machine_code"];
                                                    $msign = $store["msign"];
                                                    $sn = $store["sn"];
                                                    $store_name = $store["name"];
                                                }
                                                $member = '';
                                                if (!empty($order["member"])) {
                                                    $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                                    if ($store_member) {
                                                        $member = $store_member["name"];
                                                    }
                                                }
                                                $service_name = $service["name"];
                                                if ($print["content"]["type"] == 1) {
                                                    $time = time();
                                                    $content = '';
                                                    $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                                    $content .= "门店：" . $store_name . "\r\n";
                                                    if ($order["order_type"] == 4) {
                                                        $content .= "类型：" . "预约" . "\r\n";
                                                    } else {
                                                        $content .= "类型：" . "项目" . "\r\n";
                                                    }
                                                    $content .= "--------------------------------\r\n";
                                                    $content .= "服务项目：" . $service_name . "\r\n";
                                                    $content .= "服务人员：" . $member . "\r\n";
                                                    $content .= "预约时间：" . $order["plan_date"] . "\r\n";
                                                    $content .= "客户姓名：" . $order["userinfo"]["name"] . "\r\n";
                                                    $content .= "联系电话：" . $order["userinfo"]["mobile"] . "\r\n";
                                                    $content .= "--------------------------------\r\n";
                                                    $content .= "订单金额：" . $order["amount"] . "\r\n";
                                                    $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                                    $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                                    $requestUrl = "http://open.10ss.net:8888";
                                                    $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                                    $requestInfo = http_build_query($requestAll);
                                                    $request = push($requestInfo, $requestUrl);
                                                } elseif ($print["content"]["type"] == 2) {
                                                    include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                                    define("USER", $print["content"]["user"]);
                                                    define("UKEY", $print["content"]["ukey"]);
                                                    define("SN", $sn);
                                                    define("IP", "api.feieyun.cn");
                                                    define("PORT", 80);
                                                    define("PATH", "/Api/Open/");
                                                    define("STIME", time());
                                                    define("SIG", sha1(USER . UKEY . STIME));
                                                    $service_list = pdo_getall("xc_cake_service", array("uniacid" => $uniacid));
                                                    $service_data = array();
                                                    if ($service_list) {
                                                        foreach ($service_list as $sl) {
                                                            $service_data[$sl["id"]] = $sl;
                                                        }
                                                    }
                                                    $orderInfo = '';
                                                    $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                                    $orderInfo .= "门店：" . $store_name . "<BR>";
                                                    if ($order["order_type"] == 4) {
                                                        $orderInfo .= "类型：" . "预约" . "<BR>";
                                                    } else {
                                                        $orderInfo .= "类型：" . "项目" . "<BR>";
                                                    }
                                                    $orderInfo .= "--------------------------------<BR>";
                                                    $orderInfo .= "服务项目：" . $service_name . "<BR>";
                                                    $orderInfo .= "服务人员：" . $member . "<BR>";
                                                    $orderInfo .= "预约时间：" . $order["plan_date"] . "<BR>";
                                                    $orderInfo .= "客户姓名：" . $order["userinfo"]["name"] . "<BR>";
                                                    $orderInfo .= "联系电话：" . $order["userinfo"]["mobile"] . "<BR>";
                                                    $orderInfo .= "--------------------------------<BR>";
                                                    $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                                    $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                                    $request = wp_print(SN, $orderInfo, 1);
                                                }
                                            }
                                        }
                                        $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                        if ($sms) {
                                            $sms["content"] = json_decode($sms["content"], true);
                                            if ($sms["content"]["status"] == 1) {
                                                $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                                $mobile = $sms["content"]["mobile"];
                                                $store_name = '';
                                                if ($store) {
                                                    $store_name = $store["name"];
                                                    if (!empty($store["sms"])) {
                                                        $mobile = $store["sms"];
                                                    }
                                                } else {
                                                    $store_name = "/";
                                                }
                                                if ($sms["content"]["type"] == 1) {
                                                    require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                                    $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => $order["userinfo"]["name"], "phonex" => $order["userinfo"]["mobile"], "datex" => $order["plan_date"], "store" => $store_name, "service" => $service["name"]);
                                                    if (!empty($order["userinfo"]["address"])) {
                                                        $templateParam["addrx"] = $order["userinfo"]["address"];
                                                    } else {
                                                        $templateParam["addrx"] = "无";
                                                    }
                                                    $send = new sms();
                                                    $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                                } else {
                                                    if ($sms["content"]["type"] == 2) {
                                                        header("content-type:text/html;charset=utf-8");
                                                        $sendUrl = "http://v.juhe.cn/sms/send";
                                                        $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . $order["userinfo"]["name"] . "&#phonex#=" . $order["userinfo"]["mobile"] . "&#datex#=" . $order["plan_date"] . "&#store#=" . $store_name . "&#service#=" . $service["name"];
                                                        if (!empty($order["userinfo"]["address"])) {
                                                            $tpl_value = $tpl_value . "&#addrx#=" . $order["userinfo"]["address"];
                                                        } else {
                                                            $tpl_value = $tpl_value . "&#addrx#=无";
                                                        }
                                                        $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                        $content = juhecurl($sendUrl, $smsConf, 1);
                                                        if ($content) {
                                                            $result = json_decode($content, true);
                                                            $error_code = $result["error_code"];
                                                        }
                                                    } else {
                                                        if ($sms["content"]["type"] == 3) {
                                                            if (!empty($sms["content"]["url"])) {
                                                                $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                                                if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                                    if (is_array($header["Location"])) {
                                                                        $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                                    } else {
                                                                        $sms["content"]["url"] = $header["Location"];
                                                                    }
                                                                }
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
                                                                    $post = str_replace("{{amount}}", $order["o_amount"] . "元", $post);
                                                                    $post = str_replace("{{namex}}", $order["userinfo"]["name"], $post);
                                                                    $post = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $post);
                                                                    if (!empty($order["userinfo"]["address"])) {
                                                                        $post = str_replace("{{addrx}}", $order["userinfo"]["address"], $post);
                                                                    } else {
                                                                        $post = str_replace("{{addrx}}", "无", $post);
                                                                    }
                                                                    $post = str_replace("{{datex}}", $order["plan_date"], $post);
                                                                    $post = str_replace("{{store}}", $store_name, $post);
                                                                    $post = str_replace("{{service}}", $service["name"], $post);
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
                                                                    $get = str_replace("{{amount}}", $order["o_amount"] . "元", $get);
                                                                    $get = str_replace("{{namex}}", $order["userinfo"]["name"], $get);
                                                                    $get = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $get);
                                                                    if (!empty($order["userinfo"]["address"])) {
                                                                        $get = str_replace("{{addrx}}", $order["userinfo"]["address"], $get);
                                                                    } else {
                                                                        $get = str_replace("{{addrx}}", "无", $get);
                                                                    }
                                                                    $get = str_replace("{{datex}}", $order["plan_date"], $get);
                                                                    $get = str_replace("{{store}}", $store_name, $get);
                                                                    $get = str_replace("{{service}}", $service["name"], $get);
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
                                    } else {
                                        if ($order["order_type"] == 3) {
                                            $order["userinfo"] = json_decode($order["userinfo"], true);
                                            $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                            if ($config) {
                                                $config["content"] = json_decode($config["content"], true);
                                                if (!empty($config["content"]["template_id"])) {
                                                    require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                                    $wechat = new Wechat();
                                                    $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                                    if (!empty($order["plan_date"])) {
                                                        $plan_date = $order["plan_date"];
                                                    } else {
                                                        $plan_date = date("Y-m-d");
                                                    }
                                                    $postdata = array("keyword1" => array("value" => $order["out_trade_no"]), "keyword2" => array("value" => $order["userinfo"]["name"]), "keyword3" => array("value" => $order["userinfo"]["mobile"]), "keyword4" => array("value" => $service["name"]), "keyword5" => array("value" => $plan_date));
                                                    $post_data = array();
                                                    $post_data["touser"] = $order["openid"];
                                                    $post_data["template_id"] = $config["content"]["template_id"];
                                                    $post_data["page"] = "xc_beauty/pages/base/base";
                                                    $post_data["form_id"] = $order["form_id2"];
                                                    $post_data["data"] = $postdata;
                                                    $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                            if (!empty($order["group"])) {
                                                $group = pdo_get("xc_beauty_group", array("id" => $order["group"], "uniacid" => $uniacid));
                                                if ($group) {
                                                    $group_data = array();
                                                    if (!empty($group["team"])) {
                                                        $group_data["team"] = json_decode($group["team"], true);
                                                        $group_data["team"][] = $order["openid"];
                                                    } else {
                                                        $group_data["team"] = array($order["openid"]);
                                                    }
                                                    $group_data["team"] = json_encode($group_data["team"]);
                                                    $group_data["status"] = -1;
                                                    $group_data["team_total"] = $group["team_total"] + 1;
                                                    if ($group_data["team_total"] == $group["total"]) {
                                                        $group_data["status"] = 1;
                                                    }
                                                    $request = pdo_update("xc_beauty_group", $group_data, array("id" => $order["group"], "uniacid" => $uniacid));
                                                    if ($request) {
                                                        if ($group_data["status"] == 1) {
                                                            $list = pdo_getall("xc_beauty_order", array("status" => 1, "order_type" => 3, "group" => $order["group"]));
                                                            if ($list) {
                                                                foreach ($list as $l) {
                                                                    $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $l["openid"], "uniacid" => $uniacid));
                                                                    if (!empty($l["score"])) {
                                                                        $user_score = $userinfo["score"] + $l["score"];
                                                                        pdo_update("xc_beauty_userinfo", array("score" => $user_score), array("status" => 1, "openid" => $l["openid"], "uniacid" => $uniacid));
                                                                        pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $l["openid"], "status" => 1, "score" => $l["score"], "over" => $user_score, "title" => "消费"));
                                                                    }
                                                                    if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                                                        $level_data = array();
                                                                        $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($l["wxpay"]), 2);
                                                                        if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                                                            foreach ($card["content"]["level"] as $card_l) {
                                                                                if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                                                    $level_data["card_name"] = $card_l["name"];
                                                                                    $level_data["card_price"] = $card_l["price"];
                                                                                }
                                                                            }
                                                                        }
                                                                        pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $l["openid"], "uniacid" => $uniacid));
                                                                    }
                                                                    if (!empty($l["one_openid"])) {
                                                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["one_openid"], "title" => "一级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["one_amount"], "level" => 1, "status" => -1));
                                                                    }
                                                                    if (!empty($l["two_openid"])) {
                                                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["two_openid"], "title" => "二级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["two_amount"], "level" => 2, "status" => -1));
                                                                    }
                                                                    if (!empty($l["three_openid"])) {
                                                                        pdo_insert("xc_beauty_share", array("uniacid" => $uniacid, "openid" => $l["three_openid"], "title" => "三级订单结算奖励", "out_trade_no" => $l["out_trade_no"], "amount" => $l["three_amount"], "level" => 3, "status" => -1));
                                                                    }
                                                                    if (!empty($l["wxpay"])) {
                                                                        $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                                                        if ($count) {
                                                                            $orders = $count["order"] + 1;
                                                                            $amount = round(floatval($count["amount"]) + floatval($l["wxpay"]), 2);
                                                                            pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                                                        } else {
                                                                            pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $l["wxpay"], "store" => -1));
                                                                        }
                                                                        if (!empty($l["store"]) && $l["store"] != -1) {
                                                                            $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $l["store"], "type" => 1));
                                                                            if ($store_count) {
                                                                                $orders = $store_count["order"] + 1;
                                                                                $amount = round(floatval($store_count["amount"]) + floatval($l["wxpay"]), 2);
                                                                                pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $l["store"], "type" => 1));
                                                                            } else {
                                                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $l["wxpay"], "store" => $l["store"], "type" => 1));
                                                                            }
                                                                            $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $l["store"], "type" => 2));
                                                                            if ($day_count) {
                                                                                $day_orders = $day_count["order"] + 1;
                                                                                $day_amount = round(floatval($day_count["amount"]) + floatval($l["wxpay"]), 2);
                                                                                pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $l["store"], "type" => 2));
                                                                            } else {
                                                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $l["wxpay"], "store" => $l["store"], "type" => 2));
                                                                            }
                                                                        }
                                                                    }
                                                                    $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                                                    if ($config) {
                                                                        $config["content"] = json_decode($config["content"], true);
                                                                        if (!empty($config["content"]["group_success"])) {
                                                                            require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                                                                            $wechat = new Wechat();
                                                                            $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                                                                            $service = pdo_get("xc_beauty_service", array("id" => $l["pid"]));
                                                                            $postdata = array("keyword1" => array("value" => $l["out_trade_no"]), "keyword2" => array("value" => $l["amount"]), "keyword3" => array("value" => $service["name"]), "keyword4" => array("value" => date("Y-m-d")));
                                                                            $post_data = array();
                                                                            $post_data["touser"] = $l["openid"];
                                                                            $post_data["template_id"] = $config["content"]["group_success"];
                                                                            $post_data["page"] = "xc_beauty/pages/base/base";
                                                                            $post_data["form_id"] = $l["form_id"];
                                                                            $post_data["data"] = $postdata;
                                                                            $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=" . $token;
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
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                $request = pdo_insert("xc_beauty_group", array("uniacid" => $uniacid, "openid" => $order["openid"], "pid" => $order["pid"], "failtime" => $service["group_limit"], "total" => $service["group_number"], "team_total" => 1));
                                                if ($request) {
                                                    $group = pdo_getall("xc_beauty_group", array("uniacid" => $uniacid, "openid" => $order["openid"]), array(), '', "id DESC");
                                                    pdo_update("xc_beauty_order", array("group" => $group[0]["id"]), array("id" => $order["id"], "uniacid" => $uniacid));
                                                }
                                            }
                                            if (!empty($order["member"])) {
                                                $times_log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "plan_date" => $order["plan_date"], "member" => $order["member"], "createtime >=" => date("Y") . "-01-01 00:00:00"));
                                                if ($times_log) {
                                                    $times_log_total = intval($times_log["total"]) + 1;
                                                    pdo_update("xc_beauty_times_log", array("total" => $times_log_total), array("uniacid" => $uniacid, "id" => $times_log["id"]));
                                                } else {
                                                    pdo_insert("xc_beauty_times_log", array("uniacid" => $uniacid, "member" => $order["member"], "plan_date" => $order["plan_date"], "total" => 1));
                                                }
                                            }
                                            $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                                            if ($print) {
                                                $print["content"] = json_decode($print["content"], true);
                                                if ($print["content"]["status"] == 1) {
                                                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                                    $store_name = '';
                                                    $machine_code = $print["content"]["machine_code"];
                                                    $msign = $print["content"]["msign"];
                                                    $sn = $print["content"]["sn"];
                                                    if ($store && $store["print_status"] == 1) {
                                                        $machine_code = $store["machine_code"];
                                                        $msign = $store["msign"];
                                                        $sn = $store["sn"];
                                                        $store_name = $store["name"];
                                                    }
                                                    $member = '';
                                                    if (!empty($order["member"])) {
                                                        $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                                        if ($store_member) {
                                                            $member = $store_member["name"];
                                                        }
                                                    }
                                                    $service_name = $service["name"];
                                                    if ($print["content"]["type"] == 1) {
                                                        $time = time();
                                                        $content = '';
                                                        $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                                        $content .= "门店：" . $store_name . "\r\n";
                                                        if ($order["order_type"] == 4) {
                                                            $content .= "类型：" . "预约" . "\r\n";
                                                        } else {
                                                            $content .= "类型：" . "项目" . "\r\n";
                                                        }
                                                        $content .= "--------------------------------\r\n";
                                                        $content .= "服务项目：" . $service_name . "\r\n";
                                                        $content .= "服务人员：" . $member . "\r\n";
                                                        $content .= "预约时间：" . $order["plan_date"] . "\r\n";
                                                        $content .= "客户姓名：" . $order["userinfo"]["name"] . "\r\n";
                                                        $content .= "联系电话：" . $order["userinfo"]["mobile"] . "\r\n";
                                                        $content .= "--------------------------------\r\n";
                                                        $content .= "订单金额：" . $order["amount"] . "\r\n";
                                                        $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                                        $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                                        $requestUrl = "http://open.10ss.net:8888";
                                                        $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                                        $requestInfo = http_build_query($requestAll);
                                                        $request = push($requestInfo, $requestUrl);
                                                    } elseif ($print["content"]["type"] == 2) {
                                                        include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                                        define("USER", $print["content"]["user"]);
                                                        define("UKEY", $print["content"]["ukey"]);
                                                        define("SN", $sn);
                                                        define("IP", "api.feieyun.cn");
                                                        define("PORT", 80);
                                                        define("PATH", "/Api/Open/");
                                                        define("STIME", time());
                                                        define("SIG", sha1(USER . UKEY . STIME));
                                                        $service_list = pdo_getall("xc_cake_service", array("uniacid" => $uniacid));
                                                        $service_data = array();
                                                        if ($service_list) {
                                                            foreach ($service_list as $sl) {
                                                                $service_data[$sl["id"]] = $sl;
                                                            }
                                                        }
                                                        $orderInfo = '';
                                                        $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                                        $orderInfo .= "门店：" . $store_name . "<BR>";
                                                        if ($order["order_type"] == 4) {
                                                            $orderInfo .= "类型：" . "预约" . "<BR>";
                                                        } else {
                                                            $orderInfo .= "类型：" . "项目" . "<BR>";
                                                        }
                                                        $orderInfo .= "--------------------------------<BR>";
                                                        $orderInfo .= "服务项目：" . $service_name . "<BR>";
                                                        $orderInfo .= "服务人员：" . $member . "<BR>";
                                                        $orderInfo .= "预约时间：" . $order["plan_date"] . "<BR>";
                                                        $orderInfo .= "客户姓名：" . $order["userinfo"]["name"] . "<BR>";
                                                        $orderInfo .= "联系电话：" . $order["userinfo"]["mobile"] . "<BR>";
                                                        $orderInfo .= "--------------------------------<BR>";
                                                        $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                                        $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                                        $request = wp_print(SN, $orderInfo, 1);
                                                    }
                                                }
                                            }
                                            $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                            if ($sms) {
                                                $sms["content"] = json_decode($sms["content"], true);
                                                if ($sms["content"]["status"] == 1) {
                                                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                                    $mobile = $sms["content"]["mobile"];
                                                    $store_name = '';
                                                    if ($store) {
                                                        $store_name = $store["name"];
                                                        if (!empty($store["sms"])) {
                                                            $mobile = $store["sms"];
                                                        }
                                                    } else {
                                                        $store_name = "/";
                                                    }
                                                    if ($sms["content"]["type"] == 1) {
                                                        require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                                        $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => $order["userinfo"]["name"], "phonex" => $order["userinfo"]["mobile"], "datex" => $order["plan_date"], "store" => $store_name, "service" => $service["name"]);
                                                        if (!empty($order["userinfo"]["address"])) {
                                                            $templateParam["addrx"] = $order["userinfo"]["address"];
                                                        } else {
                                                            $templateParam["addrx"] = "无";
                                                        }
                                                        $send = new sms();
                                                        $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                                    } else {
                                                        if ($sms["content"]["type"] == 2) {
                                                            header("content-type:text/html;charset=utf-8");
                                                            $sendUrl = "http://v.juhe.cn/sms/send";
                                                            $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . $order["userinfo"]["name"] . "&#phonex#=" . $order["userinfo"]["mobile"] . "&#datex#=" . $order["plan_date"] . "&#store#=" . $store_name . "&#service#=" . $service["name"];
                                                            if (!empty($order["userinfo"]["address"])) {
                                                                $tpl_value = $tpl_value . "&#addrx#=" . $order["userinfo"]["address"];
                                                            } else {
                                                                $tpl_value = $tpl_value . "&#addrx#=无";
                                                            }
                                                            $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                            $content = juhecurl($sendUrl, $smsConf, 1);
                                                            if ($content) {
                                                                $result = json_decode($content, true);
                                                                $error_code = $result["error_code"];
                                                            }
                                                        } else {
                                                            if ($sms["content"]["type"] == 3) {
                                                                if (!empty($sms["content"]["url"])) {
                                                                    $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                                                    if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                                        if (is_array($header["Location"])) {
                                                                            $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                                        } else {
                                                                            $sms["content"]["url"] = $header["Location"];
                                                                        }
                                                                    }
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
                                                                        $post = str_replace("{{amount}}", $order["o_amount"] . "元", $post);
                                                                        $post = str_replace("{{namex}}", $order["userinfo"]["name"], $post);
                                                                        $post = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $post);
                                                                        if (!empty($order["userinfo"]["address"])) {
                                                                            $post = str_replace("{{addrx}}", $order["userinfo"]["address"], $post);
                                                                        } else {
                                                                            $post = str_replace("{{addrx}}", "无", $post);
                                                                        }
                                                                        $post = str_replace("{{datex}}", $order["plan_date"], $post);
                                                                        $post = str_replace("{{store}}", $store_name, $post);
                                                                        $post = str_replace("{{service}}", $service["name"], $post);
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
                                                                        $get = str_replace("{{amount}}", $order["o_amount"] . "元", $get);
                                                                        $get = str_replace("{{namex}}", $order["userinfo"]["name"], $get);
                                                                        $get = str_replace("{{phonex}}", $order["userinfo"]["mobile"], $get);
                                                                        if (!empty($order["userinfo"]["address"])) {
                                                                            $get = str_replace("{{addrx}}", $order["userinfo"]["address"], $get);
                                                                        } else {
                                                                            $get = str_replace("{{addrx}}", "无", $get);
                                                                        }
                                                                        $get = str_replace("{{datex}}", $order["plan_date"], $get);
                                                                        $get = str_replace("{{store}}", $store_name, $get);
                                                                        $get = str_replace("{{service}}", $service["name"], $get);
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
                                        }
                                    }
                                }
                            } else {
                                if ($order["order_type"] == 2) {
                                    $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"]));
                                    $money = floatval($userinfo["money"]) + floatval($order["amount"]);
                                    if (!empty($order["gift"])) {
                                        $money = round(floatval($money) + floatval($order["gift"]), 2);
                                    }
                                    $request = pdo_update("xc_beauty_order", array("status" => 1, "money" => $money, "wx_out_trade_no" => $result["transaction_id"]), array("id" => $order["id"], "uniacid" => $uniacid));
                                    if ($request) {
                                        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                                        if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                            $level_data = array();
                                            $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($order["amount"]), 2);
                                            if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                                foreach ($card["content"]["level"] as $card_l) {
                                                    if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                        $level_data["card_name"] = $card_l["name"];
                                                        $level_data["card_price"] = $card_l["price"];
                                                    }
                                                }
                                            }
                                            pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $order["openid"], "uniacid" => $uniacid));
                                        }
                                        pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "uniacid" => $uniacid, "openid" => $order["openid"]));
                                        $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                        if ($count) {
                                            $orders = $count["order"] + 1;
                                            $amount = round(floatval($count["amount"]) + floatval($order["amount"]), 2);
                                            pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                        } else {
                                            pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["amount"], "store" => -1));
                                        }
                                        if (!empty($order["store"]) && $order["store"] != -1) {
                                            $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                            if ($store_count) {
                                                $orders = $store_count["order"] + 1;
                                                $amount = round(floatval($store_count["amount"]) + floatval($order["amount"]), 2);
                                                pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                            } else {
                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["amount"], "store" => $order["store"], "type" => 1));
                                            }
                                            $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                            if ($day_count) {
                                                $day_orders = $day_count["order"] + 1;
                                                $day_amount = round(floatval($day_count["amount"]) + floatval($order["amount"]), 2);
                                                pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                            } else {
                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $order["amount"], "store" => $order["store"], "type" => 2));
                                            }
                                        }
                                    }
                                } else {
                                    if ($order["order_type"] == 5) {
                                        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"]));
                                        if (floatval($order["canpay"]) != 0) {
                                            $moeny = round(floatval($userinfo["money"]) - floatval($order["canpay"]), 2);
                                            pdo_update("xc_beauty_userinfo", array("money" => $moeny), array("status" => 1, "openid" => $order["openid"]));
                                        }
                                        if (!empty($order["coupon_id"])) {
                                            $use_coupon = pdo_get("xc_beauty_user_coupon", array("status" => -1, "openid" => $order["openid"], "uniacid" => $uniacid, "cid" => $order["coupon_id"]));
                                            pdo_update("xc_beauty_user_coupon", array("status" => 1), array("id" => $use_coupon["id"], "uniacid" => $uniacid));
                                        }
                                        $request = pdo_update("xc_beauty_order", array("status" => 1), array("id" => $order["id"], "uniacid" => $uniacid));
                                        if ($request) {
                                            $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
                                            if ($card && $card["content"]["level_status"] == 1 && $userinfo["card"] == 1) {
                                                $level_data = array();
                                                $level_data["card_amount"] = round(floatval($userinfo["card_amount"]) + floatval($order["wxpay"]), 2);
                                                if (!empty($card["content"]["level"]) && is_array($card["content"]["level"])) {
                                                    foreach ($card["content"]["level"] as $card_l) {
                                                        if (floatval($level_data["card_amount"]) >= floatval($card_l["amount"])) {
                                                            $level_data["card_name"] = $card_l["name"];
                                                            $level_data["card_price"] = $card_l["price"];
                                                        }
                                                    }
                                                }
                                                pdo_update("xc_beauty_userinfo", $level_data, array("openid" => $order["openid"], "uniacid" => $uniacid));
                                            }
                                            $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                            if ($count) {
                                                $orders = $count["order"] + 1;
                                                $amount = round(floatval($count["amount"]) + floatval($order["wxpay"]), 2);
                                                pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => -1));
                                            } else {
                                                pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => -1));
                                            }
                                            if (!empty($order["store"]) && $order["store"] != -1) {
                                                $store_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                                if ($store_count) {
                                                    $orders = $store_count["order"] + 1;
                                                    $amount = round(floatval($store_count["amount"]) + floatval($order["wxpay"]), 2);
                                                    pdo_update("xc_beauty_count", array("order" => $orders, "amount" => $amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "store" => $order["store"], "type" => 1));
                                                } else {
                                                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 1));
                                                }
                                                $day_count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                                if ($day_count) {
                                                    $day_orders = $day_count["order"] + 1;
                                                    $day_amount = round(floatval($day_count["amount"]) + floatval($order["wxpay"]), 2);
                                                    pdo_update("xc_beauty_count", array("order" => $day_orders, "amount" => $day_amount), array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "store" => $order["store"], "type" => 2));
                                                } else {
                                                    pdo_insert("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m-d"), "order" => 1, "amount" => $order["wxpay"], "store" => $order["store"], "type" => 2));
                                                }
                                            }
                                            $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
                                            if ($config) {
                                                $config["content"] = json_decode($config["content"], true);
                                            }
                                            $sms = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sms"));
                                            if ($sms) {
                                                $sms["content"] = json_decode($sms["content"], true);
                                                if ($sms["content"]["status"] == 1) {
                                                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                                    $mobile = $sms["content"]["mobile"];
                                                    $store_name = '';
                                                    if ($store) {
                                                        $store_name = $store["name"];
                                                        if (!empty($store["sms"])) {
                                                            $mobile = $store["sms"];
                                                        }
                                                    } else {
                                                        $store_name = "/";
                                                    }
                                                    if ($sms["content"]["type"] == 1) {
                                                        require_once IA_ROOT . "/addons/xc_beauty/resource/sms/sendSms.php";
                                                        $templateParam = array("webnamex" => $config["content"]["title"], "trade" => $order["out_trade_no"], "amount" => $order["o_amount"], "namex" => base64_decode($userinfo["nick"]), "phonex" => "/", "datex" => date("Y-m-d H:i:s"), "store" => $store_name, "service" => "买单");
                                                        $templateParam["addrx"] = "无";
                                                        $send = new sms();
                                                        $result = $send->sendSms($sms["content"]["AccessKeyId"], $sms["content"]["AccessKeySecret"], $sms["content"]["sign"], $sms["content"]["code"], $mobile, $templateParam);
                                                    } elseif ($sms["content"]["type"] == 2) {
                                                        header("content-type:text/html;charset=utf-8");
                                                        $sendUrl = "http://v.juhe.cn/sms/send";
                                                        $tpl_value = "#webnamex#=" . $config["content"]["title"] . "&#trade#=" . $order["out_trade_no"] . "&#amount#=" . $order["o_amount"] . "&#namex#=" . base64_decode($userinfo["nick"]) . "&#phonex#=/&#datex#=" . date("Y-m-d H:i:s") . "&#store#=" . $store_name . "&#service#=买单";
                                                        $tpl_value = $tpl_value . "&#addrx#=无";
                                                        $smsConf = array("key" => $sms["content"]["key"], "mobile" => $mobile, "tpl_id" => $sms["content"]["tpl_id"], "tpl_value" => $tpl_value);
                                                        $content = juhecurl($sendUrl, $smsConf, 1);
                                                        if ($content) {
                                                            $result = json_decode($content, true);
                                                            $error_code = $result["error_code"];
                                                        }
                                                    } elseif ($sms["content"]["type"] == 3) {
                                                        if (!empty($sms["content"]["url"])) {
                                                            $header = get_headers($sms["content"]["url"] . "&_xcdebug=1", 1);
                                                            if (strpos($header[0], "301") || strpos($header[0], "302")) {
                                                                if (is_array($header["Location"])) {
                                                                    $sms["content"]["url"] = $header["Location"][count($header["Location"]) - 1];
                                                                } else {
                                                                    $sms["content"]["url"] = $header["Location"];
                                                                }
                                                            }
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
                                                                $post = str_replace("{{amount}}", $order["o_amount"] . "元", $post);
                                                                $post = str_replace("{{namex}}", base64_decode($userinfo["nick"]), $post);
                                                                $post = str_replace("{{phonex}}", "/", $post);
                                                                $post = str_replace("{{addrx}}", "无", $post);
                                                                $post = str_replace("{{datex}}", date("Y-m-d H:i:s"), $post);
                                                                $post = str_replace("{{store}}", $store_name, $post);
                                                                $post = str_replace("{{service}}", "买单", $post);
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
                                                                logging_run($output);
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
                                                                $get = str_replace("{{amount}}", $order["o_amount"] . "元", $get);
                                                                $get = str_replace("{{namex}}", base64_decode($userinfo["nick"]), $get);
                                                                $get = str_replace("{{phonex}}", "/", $get);
                                                                $get = str_replace("{{addrx}}", "无", $get);
                                                                $get = str_replace("{{datex}}", date("Y-m-d H:i:s"), $get);
                                                                $get = str_replace("{{store}}", $store_name, $get);
                                                                $get = str_replace("{{service}}", "买单", $get);
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
                                            $print = pdo_get("xc_beauty_config", array("xkey" => "print", "uniacid" => $uniacid));
                                            if ($print) {
                                                $print["content"] = json_decode($print["content"], true);
                                                if ($print["content"]["status"] == 1) {
                                                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $order["store"]));
                                                    $store_name = '';
                                                    $machine_code = $print["content"]["machine_code"];
                                                    $msign = $print["content"]["msign"];
                                                    $sn = $print["content"]["sn"];
                                                    if ($store && $store["print_status"] == 1) {
                                                        $machine_code = $store["machine_code"];
                                                        $msign = $store["msign"];
                                                        $sn = $store["sn"];
                                                        $store_name = $store["name"];
                                                    }
                                                    $member = '';
                                                    if (!empty($order["member"])) {
                                                        $store_member = pdo_get("xc_beauty_store_member", array("id" => $order["member"], "uniacid" => $uniacid));
                                                        if ($store_member) {
                                                            $member = $store_member["name"];
                                                        }
                                                    }
                                                    $service_name = "买单";
                                                    if ($print["content"]["type"] == 1) {
                                                        $time = time();
                                                        $content = '';
                                                        $content .= "单号：" . $order["out_trade_no"] . "\r\n";
                                                        $content .= "门店：" . $store_name . "\r\n";
                                                        $content .= "--------------------------------\r\n";
                                                        $content .= "服务项目：" . $service_name . "\r\n";
                                                        $content .= "--------------------------------\r\n";
                                                        $content .= "订单金额：" . $order["amount"] . "\r\n";
                                                        $content .= "实付金额：" . $order["o_amount"] . "\r\n";
                                                        $sign = strtoupper(md5($print["content"]["apikey"] . "machine_code" . $machine_code . "partner" . $print["content"]["partner"] . "time" . $time . $msign));
                                                        $requestUrl = "http://open.10ss.net:8888";
                                                        $requestAll = ["partner" => $print["content"]["partner"], "machine_code" => $machine_code, "time" => $time, "content" => $content, "sign" => $sign];
                                                        $requestInfo = http_build_query($requestAll);
                                                        $request = push($requestInfo, $requestUrl);
                                                        logging_run($request);
                                                    } elseif ($print["content"]["type"] == 2) {
                                                        include IA_ROOT . "/addons/xc_beauty/resource/HttpClient.class.php";
                                                        define("USER", $print["content"]["user"]);
                                                        define("UKEY", $print["content"]["ukey"]);
                                                        define("SN", $sn);
                                                        define("IP", "api.feieyun.cn");
                                                        define("PORT", 80);
                                                        define("PATH", "/Api/Open/");
                                                        define("STIME", time());
                                                        define("SIG", sha1(USER . UKEY . STIME));
                                                        $orderInfo = '';
                                                        $orderInfo .= "单号：" . $order["out_trade_no"] . "<BR>";
                                                        $orderInfo .= "门店：" . $store_name . "<BR>";
                                                        $orderInfo .= "--------------------------------<BR>";
                                                        $orderInfo .= "服务项目：买单<BR>";
                                                        $orderInfo .= "--------------------------------<BR>";
                                                        $orderInfo .= "订单金额：" . $order["amount"] . "<BR>";
                                                        $orderInfo .= "实付金额：" . $order["o_amount"] . "<BR>";
                                                        $request = wp_print(SN, $orderInfo, 1);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            return $this->result(0, "操作成功", array("status" => 1));
                        } else {
                            return $this->result(0, "操作失败", array("status" => -1));
                        }
                    } else {
                        return $this->result(0, "操作失败", array("status" => -1));
                    }
                } else {
                    return $this->result(0, "操作失败", array("status" => -1));
                }
            }
        } else {
            return $this->result(0, "操作失败", array("status" => -1));
        }
    }
}
function setcode()
{
    global $_GPC, $_W;
    $uniacid = $_W["uniacid"];
    $request = pdo_get("xc_beauty_config", array("xkey" => "code", "uniacid" => $uniacid));
    if (!$request) {
        $request["content"] = "000000";
        pdo_insert("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "code", "content" => $request["content"]));
    }
    $code = intval($request["content"]) + 1;
    $code3 = '';
    for ($i = 0; $i < 6 - strlen($code); $i++) {
        $code3 = $code3 . "0";
    }
    $code3 = $code3 . $code;
    pdo_update("xc_beauty_config", array("content" => $code3), array("xkey" => "code", "uniacid" => $uniacid));
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
    } elseif ($params) {
        curl_setopt($ch, CURLOPT_URL, $url . "?" . $params);
    } else {
        curl_setopt($ch, CURLOPT_URL, $url);
    }
    $response = curl_exec($ch);
    if ($response === false) {
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
function usercode($back, $userinfo, $share, $openid, $_W)
{
    $num = time();
    $savepath = $_SERVER["DOCUMENT_ROOT"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y");
    if (!is_dir($savepath)) {
        mkdir($savepath, 0700, true);
    }
    $savepath = $savepath . "/" . date("m") . "/";
    if (!is_dir($savepath)) {
        mkdir($savepath, 0700, true);
    }
    $savefile = $num;
    $saveokpath = $savepath . $savefile;
    $back_img2 = $savepath . "back_" . $num . ".jpg";
    mkThumbnail($back, 630, null, $back_img2);
    $back_img = imagecreatefromstring(file_get_contents($back_img2));
    list($dst_w, $dst_h, $dst_type) = getimagesize($back_img2);
    if (!empty($userinfo["avatar"])) {
        $header = array("User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0", "Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3", "Accept-Encoding: gzip, deflate");
        $url = $userinfo["avatar"];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code == 200) {
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
        }
        $img_content = $imgBase64Code;
        if (preg_match("/^(data:\\s*image\\/(\\w+);base64,)/", $img_content, $result)) {
            $type = $result[2];
            $avatar = $savepath . "avatar_" . $num . ".{$type}";
            if (file_put_contents($avatar, base64_decode(str_replace($result[1], '', $img_content)))) {
                mkThumbnail($avatar, 100, null, $avatar);
                $avatar = test($avatar, $savepath . "avatar2_" . $num);
                $avatar_img = imagecreatefromstring(file_get_contents($avatar));
                list($avatar_w, $avatar_h, $avatar_type) = getimagesize($avatar);
                $avatar_x = imagesx($avatar_img);
                $avatar_y = imagesy($avatar_img);
                imagecopy($back_img, $avatar_img, 40, 70, 0, 0, $avatar_x, $avatar_y);
                unlink($avatar);
            }
        }
    }
    $font = $_SERVER["DOCUMENT_ROOT"] . "/addons/xc_beauty/resource/WRYH.ttf";
    if (!empty($userinfo["nick"])) {
        $userinfo["nick"] = base64_decode($userinfo["nick"]);
        $fontBox1 = imagettfbbox(10, 0, $font, $userinfo["nick"]);
        if (!empty($share["share_nick_color"])) {
            $rgb = hex2rgb($share["share_nick_color"]);
            $black = imagecolorallocate($back_img, $rgb["r"], $rgb["g"], $rgb["b"]);
        } else {
            $black = imagecolorallocate($back_img, 51, 51, 51);
        }
        imagefttext($back_img, 20, 0, 160, 110, $black, $font, $userinfo["nick"]);
    }
    if (!empty($share["share_text"])) {
        $fontBox2 = imagettfbbox(10, 0, $font, $share["share_text"]);
        if (!empty($share["share_text_color"])) {
            $rgb = hex2rgb($share["share_text_color"]);
            $black = imagecolorallocate($back_img, $rgb["r"], $rgb["g"], $rgb["b"]);
        } else {
            $black = imagecolorallocate($back_img, 153, 153, 153);
        }
        imagefttext($back_img, 20, 0, 160, 150, $black, $font, $share["share_text"]);
    }
    if (!empty($share["code"])) {
        mkThumbnail($share["code"], $dst_w / 2, null, $share["code"]);
        $code_img = imagecreatefromstring(file_get_contents($share["code"]));
        list($code_w, $code_h, $code_type) = getimagesize($share["code"]);
        $code_x = imagesx($code_img);
        $code_y = imagesy($code_img);
        imagecopy($back_img, $code_img, ($dst_w - $code_w) / 2, ($dst_h - $code_h) / 2, 0, 0, $code_x, $code_y);
        unlink($share["code"]);
    }
    unlink($back_img2);
    $exname = '';
    switch ($dst_type) {
        case 1:
            $exname = ".gif";
            imagegif($back_img, $saveokpath . ".gif");
            break;
        case 2:
            $exname = ".jpg";
            imagejpeg($back_img, $saveokpath . ".jpg");
            break;
        case 3:
            $exname = ".png";
            imagepng($back_img, $saveokpath . ".png");
            break;
        default:
            break;
    }
    $url = "https://" . $_SERVER["HTTP_HOST"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y") . "/" . date("m") . "/" . $savefile . '' . $exname;
    return $url;
}
function mkThumbnail($src, $width = null, $height = null, $filename = null)
{
    if (!isset($width) && !isset($height)) {
        return false;
    }
    if (isset($width) && $width <= 0) {
        return false;
    }
    if (isset($height) && $height <= 0) {
        return false;
    }
    $size = getimagesize($src);
    if (!$size) {
        return false;
    }
    list($src_w, $src_h, $src_type) = $size;
    $src_mime = $size["mime"];
    switch ($src_type) {
        case 1:
            $img_type = "gif";
            break;
        case 2:
            $img_type = "jpeg";
            break;
        case 3:
            $img_type = "png";
            break;
        case 15:
            $img_type = "wbmp";
            break;
        default:
            return false;
            break;
    }
    if (!isset($width)) {
        $width = $src_w * ($height / $src_h);
    }
    if (!isset($height)) {
        $height = $src_h * ($width / $src_w);
    }
    $imagecreatefunc = "imagecreatefrom" . $img_type;
    $src_img = $imagecreatefunc($src);
    $dest_img = imagecreatetruecolor($width, $height);
    imagealphablending($dest_img, false);
    imagesavealpha($dest_img, true);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
    $imagefunc = "image" . $img_type;
    if ($filename) {
        $imagefunc($dest_img, $filename);
    } else {
        header("Content-Type: " . $src_mime);
        $imagefunc($dest_img);
    }
    imagedestroy($src_img);
    imagedestroy($dest_img);
    return true;
}
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
    $opacity = $pct;
    $w = imagesx($src_im);
    $h = imagesy($src_im);
    $cut = imagecreatetruecolor($src_w, $src_h);
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
    $opacity = 100 - $opacity;
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
}
function getBytes($string)
{
    $bytes = array();
    $i = 0;
    while ($i < strlen($string)) {
        $bytes[] = ord($string[$i]);
        $i++;
    }
    return $bytes;
}
function http_post_json($url, $jsonStr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Content-Length: " . strlen($jsonStr)));
    $response = curl_exec($ch);
    $response = trim($response, chr(239) . chr(187) . chr(191));
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($httpCode, $response);
}
function test($url, $path = "./")
{
    $w = 100;
    $h = 100;
    $original_path = $url;
    $dest_path = $path . ".png";
    $src = imagecreatefromstring(file_get_contents($original_path));
    $newpic = imagecreatetruecolor($w, $h);
    imagealphablending($newpic, false);
    $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
    $r = $w / 2;
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $c = imagecolorat($src, $x, $y);
            $_x = $x - $w / 2;
            $_y = $y - $h / 2;
            if ($_x * $_x + $_y * $_y < $r * $r) {
                imagesetpixel($newpic, $x, $y, $c);
            } else {
                imagesetpixel($newpic, $x, $y, $transparent);
            }
        }
    }
    imagesavealpha($newpic, true);
    imagepng($newpic, $dest_path);
    imagedestroy($newpic);
    imagedestroy($src);
    unlink($url);
    return $dest_path;
}
function vpost($url, $data)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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
function hex2rgb($hexColor)
{
    $color = str_replace("#", '', $hexColor);
    if (strlen($color) > 3) {
        $rgb = array("r" => hexdec(substr($color, 0, 2)), "g" => hexdec(substr($color, 2, 2)), "b" => hexdec(substr($color, 4, 2)));
    } else {
        $color = $hexColor;
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
    }
    return $rgb;
}
function arrayToXml($arr)
{
    $xml = "<root>";
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
    }
    $xml .= "</root>";
    return $xml;
}
function xmlToArray($xml)
{
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA)), true);
    return $values;
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
    $str = trim($str, "&");
    return $str;
}