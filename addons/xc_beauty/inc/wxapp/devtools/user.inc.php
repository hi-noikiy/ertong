<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("address", "address_add", "address_choose", "address_del", "userinfo", "card", "card_on", "card_edit", "plan_date", "prize", "times_log", "store_bind", "member_search", "online", "online_detail", "online_on", "online_refresh", "online_refresh2");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "service";
switch ($op) {
    case "userinfo":
        $request = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            $request["nick"] = base64_decode($request["nick"]);
            if (!empty($request["share"])) {
                $share = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $request["share"]));
                if ($share) {
                    $request["share_nick"] = base64_decode($share["nick"]);
                }
            }
            $request["withdraw"] = 0;
            $withdraw = pdo_getall("xc_beauty_withdraw", array("status" => -1, "openid" => $_W["openid"], "order_type" => 2, "uniacid" => $uniacid));
            if ($withdraw) {
                foreach ($withdraw as $w) {
                    $request["withdraw"] = round(floatval($request["withdraw"]) + floatval($w["amount"]), 2);
                }
            }
            if (!empty($request["store"])) {
                $store = pdo_get("xc_beauty_store", array("id" => $request["store"], "status" => 1, "uniacid" => $uniacid));
                if ($store) {
                    $request["store_name"] = $store["name"];
                } else {
                    $request["store_name"] = "门店不存在";
                }
            }
            $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
            if ($card) {
                $card["content"] = json_decode($card["content"], true);
                if (empty($card["content"]["level_status"]) || $card["content"]["level_status"] != 1) {
                    $request["card_name"] = null;
                    $request["card_price"] = null;
                    $request["card_amount"] = null;
                }
            } else {
                $request["card_name"] = null;
                $request["card_price"] = null;
                $request["card_amount"] = null;
            }
            $request["share"] = -1;
            $share_config = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "share"));
            if ($share_config) {
                $share_config["content"] = json_decode($share_config["content"], true);
                if (!empty($share_config["content"]) && !empty($share_config["content"]["amount"])) {
                    $sql = "SELECT sum(o_amount) FROM " . tablename("xc_beauty_order") . " WHERE openid=:openid AND uniacid=:uniacid AND status=1 AND order_type!=2";
                    $request["share_amount"] = 0;
                    $share_amount = pdo_fetchcolumn($sql, array(":openid" => $_W["openid"], ":uniacid" => $uniacid));
                    if ($share_amount) {
                        $request["share_amount"] = $share_amount;
                    }
                    if (floatval($request["share_amount"]) >= floatval($share_config["content"]["amount"])) {
                        $request["share"] = 1;
                    }
                } else {
                    $request["share"] = 1;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "address":
        if (empty($_GPC["id"])) {
            $request = pdo_getall("xc_beauty_address", array("uniacid" => $uniacid, "openid" => $_W["openid"]), array(), '', "createtime DESC");
        } else {
            $request = pdo_get("xc_beauty_address", array("uniacid" => $uniacid, "openid" => $_W["openid"], "id" => $_GPC["id"]));
        }
        if ($request) {
            if (!empty($_GPC["id"])) {
                $request["map"] = json_decode($request["map"], true);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "address_add":
        $condition["name"] = $_GPC["name"];
        $condition["mobile"] = $_GPC["mobile"];
        if (!empty($_GPC["address"])) {
            $condition["address"] = $_GPC["address"];
        }
        if (!empty($_GPC["map"])) {
            $condition["map"] = htmlspecialchars_decode($_GPC["map"]);
        }
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        $condition["status"] = 1;
        pdo_update("xc_beauty_address", array("status" => -1), array("uniacid" => $uniacid, "openid" => $_W["openid"], "status" => 1));
        if (empty($_GPC["id"])) {
            $condition["uniacid"] = $uniacid;
            $condition["openid"] = $_W["openid"];
            $request = pdo_insert("xc_beauty_address", $condition);
        } else {
            $request = pdo_update("xc_beauty_address", $condition, array("uniacid" => $uniacid, "openid" => $_W["openid"], "id" => $_GPC["id"]));
        }
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "address_choose":
        pdo_update("xc_beauty_address", array("status" => -1), array("uniacid" => $uniacid, "openid" => $_W["openid"], "status" => 1));
        $request = pdo_update("xc_beauty_address", array("status" => 1), array("uniacid" => $uniacid, "openid" => $_W["openid"], "id" => $_GPC["id"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "address_del":
        $request = pdo_delete("xc_beauty_address", array("uniacid" => $uniacid, "status" => -1, "id" => $_GPC["id"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "card":
        $request = array();
        $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
        if ($card) {
            $card["content"] = json_decode($card["content"], true);
            if (!empty($card["content"]["card"])) {
                $card["content"]["card"] = tomedia($card["content"]["card"]);
            }
            if (!empty($card["content"]["card_on"])) {
                $card["content"]["card_on"] = tomedia($card["content"]["card_on"]);
            }
            if (!empty($card["content"]["score_icon"])) {
                $card["content"]["score_icon"] = tomedia($card["content"]["score_icon"]);
            }
            if (!empty($card["content"]["discount_icon"])) {
                $card["content"]["discount_icon"] = tomedia($card["content"]["discount_icon"]);
            }
            $request["card"] = $card;
        }
        $coupon = pdo_getall("xc_beauty_coupon", array("status" => 1, "type" => 2, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($coupon) {
            if (!empty($_W["openid"])) {
                $user_coupon = pdo_getall("xc_beauty_user_coupon", array("status" => -1, "openid" => $_W["openid"], "uniacid" => $uniacid));
                if ($user_coupon) {
                    foreach ($user_coupon as $up) {
                        $datalist[$up["cid"]] = $up;
                    }
                }
            }
            foreach ($coupon as $key => $cc) {
                if ($cc["total"] == 0) {
                    unset($coupon[$key]);
                }
                if (!empty($cc["times"])) {
                    $cc["times"] = json_decode($cc["times"], true);
                    if (!(strtotime($cc["times"]["start"]) < time() && strtotime($cc["times"]["end"]) > time())) {
                        unset($coupon[$key]);
                    }
                }
                $coupon[$key]["user"] = -1;
                if (!empty($datalist[$cc["id"]])) {
                    $coupon[$key]["user"] = 1;
                }
            }
            if (!empty($coupon)) {
                $request["coupon"] = $coupon;
            }
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "card_on":
        $code = cache_load($uniacid . $_W["openid"] . "code");
        if (!empty($_GPC["code"])) {
            if (empty($code) || $code != $_GPC["code"]) {
                return $this->result(1, "验证码错误");
            }
        }
        $userinfo = pdo_get("xc_beauty_userinfo", array("card" => 1, "mobile" => $_GPC["mobile"], "uniacid" => $uniacid));
        if ($userinfo) {
            return $this->result(1, "该手机号已绑定会员");
        }
        $request = pdo_update("xc_beauty_userinfo", array("card" => 1, "mobile" => $_GPC["mobile"], "password" => md5($_GPC["password"]), "name" => $_GPC["name"]), array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "card_edit":
        $code = cache_load($uniacid . $_W["openid"] . "code");
        if (!empty($_GPC["code"])) {
            if (empty($code) || $code != $_GPC["code"]) {
                return $this->result(1, "验证码错误");
            }
        }
        $userinfo = pdo_get("xc_beauty_userinfo", array("mobile" => $_GPC["mobile"], "uniacid" => $uniacid));
        if ($userinfo) {
            return $this->result(1, "该手机号已被绑定");
        }
        $request = pdo_update("xc_beauty_userinfo", array("password" => md5($_GPC["password"]), "mobile" => $_GPC["mobile"]), array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "plan_date":
        if (!empty($_GPC["id"])) {
            $request = pdo_get("xc_beauty_online", array("uniacid" => $uniacid, "status" => 1, "store" => $_GPC["id"], "plan_date" => $_GPC["plan_date"], "createtime >" => date("Y") . "-01-01 00:00:00"));
            if ($request) {
                return $this->result(0, "操作成功", array("status" => 2));
            } else {
                return $this->result(0, "操作成功", array("status" => 1));
            }
        } else {
            return $this->result(0, "操作成功", array("status" => 1));
        }
        break;
    case "prize":
        $request = pdo_getall("xc_beauty_rotate_log", array("openid" => $_W["openid"], "type" => 2, "uniacid" => $uniacid));
        if ($request) {
            $prize = pdo_getall("xc_beauty_prize", array("type" => 2, "uniacid" => $uniacid));
            $datalist = array();
            if ($prize) {
                foreach ($prize as $p) {
                    $datalist[$p["id"]] = $p;
                }
            }
            foreach ($request as &$x) {
                $x["simg"] = tomedia($datalist[$x["cid"]]["simg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "times_log":
        $request = json_decode(htmlspecialchars_decode($_GPC["list"]), true);
        foreach ($request as &$x) {
            $x["status"] = -1;
            $x["shop_total"] = 0;
            $x["home_total"] = 0;
            if (!empty($x["p_time"])) {
                $date = date("Y-m-d", strtotime("+" . $_GPC["index"] . " day")) . " " . $x["start"];
                if (time() >= strtotime($date) - floatval($x["p_time"]) * 60 * 60) {
                    $x["status"] = 2;
                }
            } else {
                $date = date("Y-m-d", strtotime("+" . $_GPC["index"] . " day")) . " " . $x["end"];
                if (time() >= strtotime($date)) {
                    $x["status"] = 2;
                }
            }
            if ($x["status"] != 2) {
                $week = $_GPC["week"];
                if ($week == 0) {
                    $week = 7;
                }
                $member = pdo_get("xc_beauty_store_member", array("uniacid" => $uniacid, "id" => $_GPC["member"]));
                if ($member) {
                    $pai_id = '';
                    if ($member["pai_status"] == -1 && !empty($member["pai1"])) {
                        $pai_id = $member["pai1"];
                    } else {
                        if ($member["pai_status"] == 1) {
                            $weekth = date("W", strtotime("+" . $_GPC["index"] . " day"));
                            if (intval($weekth) % 2 == 0 && !empty($member["pai1"])) {
                                $pai_id = $member["pai1"];
                            } else {
                                if (intval($weekth) % 2 != 0 && !empty($member["pai2"])) {
                                    $pai_id = $member["pai2"];
                                }
                            }
                        }
                    }
                    if (!empty($pai_id)) {
                        $pai = pdo_get("xc_beauty_pai", array("uniacid" => $uniacid, "id" => $pai_id, "status" => 1));
                        if ($pai) {
                            $x["status"] = 2;
                            $pai_detail = pdo_get("xc_beauty_pai_detail", array("pid" => $pai["id"], "uniacid" => $uniacid, "weeknum" => $week, "status" => 1));
                            if ($pai_detail) {
                                if (intval($x["start"]) >= intval($pai_detail["time1start"]) && intval($x["end"]) <= intval($pai_detail["time1end"])) {
                                    $x["status"] = -1;
                                } else {
                                    if (intval($x["start"]) >= intval($pai_detail["time2start"]) && intval($x["end"]) <= intval($pai_detail["time2end"])) {
                                        $x["status"] = -1;
                                    } else {
                                        if (intval($x["start"]) >= intval($pai_detail["time3start"]) && intval($x["end"]) <= intval($pai_detail["time3end"])) {
                                            $x["status"] = -1;
                                        } else {
                                            if (!(intval($x["start"]) >= intval($pai_detail["time4start"]) && intval($x["end"]) <= intval($pai_detail["time4end"]))) {
                                            } else {
                                                $x["status"] = -1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($x["status"] != 2) {
                if (!empty($_GPC["member"]) && $_GPC["member"] != -1) {
                    $log = pdo_get("xc_beauty_times_log", array("uniacid" => $uniacid, "plan_date" => $_GPC["plan_date"] . " " . $x["start"] . "-" . $x["end"], "member" => $_GPC["member"], "createtime >=" => date("Y") . "-01-01 00:00:00"));
                    if ($_GPC["member"] == -2 && !empty($x["home_member"])) {
                        $x["total"] = $x["home_member"];
                        if ($log) {
                            $x["home_total"] = $log["total"];
                            if (intval($x["home_member"]) <= intval($x["home_total"])) {
                                $x["status"] = 1;
                            } else {
                                $x["total"] = intval($x["home_member"]) - intval($x["home_total"]);
                            }
                        }
                    } else {
                        if ($_GPC["member"] != -2 && !empty($x["shop_member"])) {
                            if ($x["shop_member"] == 0) {
                                $x["status"] = 1;
                            }
                            $x["total"] = $x["shop_member"];
                            if ($log) {
                                $x["shop_total"] = $log["total"];
                                if (intval($x["shop_member"]) <= intval($x["shop_total"])) {
                                    $x["status"] = 1;
                                } else {
                                    $x["total"] = intval($x["shop_member"]) - intval($x["shop_total"]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->result(0, "操作成功", $request);
        break;
    case "store_bind":
        $request = pdo_update("xc_beauty_userinfo", array("store" => $_GPC["id"]), array("openid" => $_W["openid"], "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "绑定失败");
        }
        break;
    case "member_search":
        $request = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "store !=" => '', "card" => 1, "mobile" => $_GPC["search"]));
        if ($request) {
            $card = pdo_get("xc_beauty_config", array("xkey" => "card", "uniacid" => $uniacid));
            if ($card) {
                $card["content"] = json_decode($card["content"], true);
                if (empty($card["content"]["level_status"]) || $card["content"]["level_status"] != 1) {
                    $request["card_name"] = null;
                    $request["card_price"] = null;
                    $request["card_amount"] = null;
                }
            } else {
                $request["card_name"] = null;
                $request["card_price"] = null;
                $request["card_amount"] = null;
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "没有此会员");
        }
        break;
    case "online":
        if (!empty($_W["openid"])) {
            $request = array("admin" => -1);
            $userinfo = pdo_get("xc_beauty_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
            if ($userinfo && $userinfo["shop"] == 1) {
                $request["admin"] = 1;
            }
            $user_condition["uniacid"] = $uniacid;
            if (!empty($_GPC["search"])) {
                $user_condition["nick LIKE"] = "%" . base64_encode($_GPC["search"]) . "%";
            }
            $user = pdo_getall("xc_beauty_userinfo", $user_condition);
            $datalist = array();
            $openid = [];
            if ($user) {
                foreach ($user as $u) {
                    $u["nick"] = base64_decode($u["nick"]);
                    $datalist[$u["openid"]] = $u;
                    if (!empty($_GPC["search"])) {
                        $openid[] = $u["openid"];
                    }
                }
            }
            $condition["uniacid"] = $uniacid;
            if (!empty($openid)) {
                $condition["openid IN"] = $openid;
            }
            $list = pdo_getall("xc_beauty_onlines", $condition, array(), '', "updatetime DESC,id DESC", array($_GPC["page"], $_GPC["pagesize"]));
            if ($list) {
                foreach ($list as &$x) {
                    if ($x["type"] == 1) {
                        $x["content"] = base64_decode($x["content"]);
                        $x["content"] = emoji($x["content"], $_GPC["m"]);
                    }
                    $x["nick"] = $datalist[$x["openid"]]["nick"];
                    $x["avatar"] = $datalist[$x["openid"]]["avatar"];
                }
                $request["list"] = $list;
            }
            if ($request) {
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "online_detail":
        $request = array("refresh" => 0);
        $userinfo = pdo_get("xc_beauty_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
        if ($userinfo) {
            $request["user"] = $userinfo;
        }
        $user = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
        $datalist = array();
        if ($user) {
            foreach ($user as $u) {
                $u["nick"] = base64_decode($u["nick"]);
                $datalist[$u["openid"]] = $u;
            }
        }
        pdo_update("xc_beauty_onlines", array("member" => 0), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        $list = pdo_getall("xc_beauty_onlines", array("uniacid" => $uniacid, "id" => $_GPC["id"]), array(), '', "updatetime DESC,id DESC");
        if ($list) {
            foreach ($list as &$x) {
                $x["nick"] = $datalist[$x["openid"]]["nick"];
                $x["avatar"] = $datalist[$x["openid"]]["avatar"];
            }
            $request["list"] = $list;
        }
        $condition["pid"] = $_GPC["id"];
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["prev_id"])) {
            $condition["id <"] = $_GPC["prev_id"];
        }
        $detail = pdo_getall("xc_beauty_online_log", $condition, array(), '', "id DESC", array(1, $_GPC["pagesize"]));
        if ($detail) {
            foreach ($detail as &$d) {
                if ($d["type"] == 1) {
                    $d["content"] = base64_decode($d["content"]);
                    if ($d["duty"] == 1 || $d["duty"] == 2 && $d["openid"] != $_W["openid"]) {
                        $d["content"] = emoji($d["content"], $_GPC["m"]);
                    }
                }
                $d["nick"] = $datalist[$d["openid"]]["nick"];
                $d["avatar"] = $datalist[$d["openid"]]["avatar"];
                $d["on"] = -1;
                if ($d["duty"] == 2 && $d["openid"] == $_W["openid"]) {
                    $d["on"] = 1;
                }
            }
            $request["detail"] = array_reverse($detail);
        }
        $config = pdo_get("xc_beauty_config", array("xkey" => "online", "uniacid" => $uniacid));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]) && !empty($config["content"]["refresh"])) {
                $request["refresh"] = intval($config["content"]["refresh"]) * 1000;
            }
        }
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "online_on":
        $online = pdo_get("xc_beauty_onlines", array("id" => $_GPC["pid"], "uniacid" => $uniacid));
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        $data = array();
        if ($_GPC["type"] == 1) {
            $data = array("touser" => $online["openid"], "msgtype" => "text", "text" => array("content" => $_GPC["content"]));
        } else {
            if ($_GPC["type"] == 2) {
                $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $token . "&type=image";
                $res = wxUpload($url, array("media" => "@" . $_GPC["upload"]));
                $res = json_decode($res, true);
                if (!empty($res["media_id"])) {
                    $data = array("touser" => $online["openid"], "msgtype" => "image", "image" => array("media_id" => $res["media_id"]));
                } else {
                    return $this->result(1, $res["errmsg"]);
                }
            }
        }
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $token;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($json)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            return $this->result(1, "Errno" . curl_error($curl));
        }
        curl_close($curl);
        if ($output == 0) {
            $condition["uniacid"] = $uniacid;
            $condition["pid"] = $_GPC["pid"];
            $condition["openid"] = $_W["openid"];
            $condition["type"] = $_GPC["type"];
            if ($_GPC["type"] == 1) {
                $condition["content"] = base64_encode($_GPC["content"]);
            } else {
                $condition["content"] = $_GPC["content"];
            }
            $condition["duty"] = 2;
            $request = pdo_insert("xc_beauty_online_log", $condition);
            if ($request) {
                $id = pdo_insertid();
                return $this->result(0, "操作成功", array("id" => $id, "createtime" => date("Y-m-d H:i:s")));
            } else {
                return $this->result(1, "发送失败");
            }
        } else {
            return $this->result(1, "Errno" . $output);
        }
        break;
    case "online_refresh":
        pdo_update("xc_beauty_onlines", array("member" => 0), array("id" => $_GPC["pid"], "uniacid" => $uniacid));
        $request = pdo_getall("xc_beauty_online_log", array("id >" => $_GPC["id"], "pid" => $_GPC["pid"], "duty" => 1), array(), '', "id");
        if ($request) {
            $user = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $datalist = array();
            if ($user) {
                foreach ($user as $u) {
                    $u["nick"] = base64_decode($u["nick"]);
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$d) {
                if ($d["type"] == 1) {
                    $d["content"] = base64_decode($d["content"]);
                    $d["content"] = emoji($d["content"], $_GPC["m"]);
                }
                $d["nick"] = $datalist[$d["openid"]]["nick"];
                $d["avatar"] = $datalist[$d["openid"]]["avatar"];
                $d["on"] = -1;
                if ($d["duty"] == 2 && $d["openid"] == $_W["openid"]) {
                    $d["on"] = 1;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "online_refresh2":
        $request = pdo_getall("xc_beauty_onlines", array("id !=" => $_GPC["id"], "member >" => 0), array(), '', "updatetime DESC,id DESC");
        if ($request) {
            $user = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $datalist = array();
            if ($user) {
                foreach ($user as $u) {
                    $u["nick"] = base64_decode($u["nick"]);
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$d) {
                if ($d["type"] == 1) {
                    $d["content"] = base64_decode($d["content"]);
                    $d["content"] = emoji($d["content"], $_GPC["m"]);
                }
                $d["nick"] = $datalist[$d["openid"]]["nick"];
                $d["avatar"] = $datalist[$d["openid"]]["avatar"];
                $d["on"] = -1;
                if ($d["duty"] == 2 && $d["openid"] == $_W["openid"]) {
                    $d["on"] = 1;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
}