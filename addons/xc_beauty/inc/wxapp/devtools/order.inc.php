<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("order", "detail", "order_del", "coupon", "refund", "over", "withdraw", "score_order", "to_coupon", "withdraw_order", "group_order", "group_detail", "share_order", "store_order", "store", "buy_order", "buy_detail");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "order";
switch ($op) {
    case "order":
        $time = time();
        $condition = '';
        $fail_status = -1;
        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["order_fail"])) {
                $fail_status = 1;
            }
        }
        if ($_GPC["curr"] == 2) {
            $condition .= " AND status=-1";
            if ($fail_status == 1) {
                $condition .= " AND UNIX_TIMESTAMP(failtime)>{$time}";
            }
        } else {
            if ($_GPC["curr"] == 3) {
                $condition .= " AND status=1 AND `use`=-1 ";
            } else {
                if ($_GPC["curr"] == 4) {
                    $condition .= " AND status=1 AND `use`=1 ";
                } else {
                    if ($_GPC["curr"] == 5) {
                        $condition .= " AND status=2 ";
                    } else {
                        if ($_GPC["curr"] == 1) {
                            if ($fail_status == 1) {
                                $condition .= " AND ((status=-1 AND UNIX_TIMESTAMP(failtime)>{$time}) OR status!=-1)";
                            }
                        }
                    }
                }
            }
        }
        $page = (intval($_GPC["page"]) - 1) * intval($_GPC["pagesize"]);
        $pagesize = $_GPC["pagesize"];
        $request = pdo_fetchall("SELECT * FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND openid=:openid AND order_type=1 " . $condition . " ORDER BY createtime DESC,id DESC LIMIT {$page},{$pagesize}", array(":uniacid" => $uniacid, ":openid" => $_W["openid"]));
        if ($request) {
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x) {
                    $datalist[$x["id"]] = $x;
                }
            }
            foreach ($request as &$y) {
                $y["service_name"] = $datalist[$y["pid"]]["name"];
                $y["service_simg"] = tomedia($datalist[$y["pid"]]["simg"]);
                if (empty($y["price"])) {
                    $y["price"] = $datalist[$y["pid"]]["price"];
                }
                if ($y["flash"] == 1) {
                    $y["o_price"] = $datalist[$y["pid"]]["price"];
                } else {
                    $y["o_price"] = $datalist[$y["pid"]]["o_price"];
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "detail":
        $request = pdo_get("xc_beauty_order", array("uniacid" => $uniacid, "out_trade_no" => $_GPC["out_trade_no"]));
        if ($request) {
            if ($request["order_type"] == 4) {
                $service = pdo_get("xc_beauty_store_service", array("id" => $request["pid"], "uniacid" => $uniacid));
                $request["service_list"] = $service;
            } else {
                $service = pdo_get("xc_beauty_service", array("id" => $request["pid"], "uniacid" => $uniacid));
                $request["service_list"] = $service;
            }
            $request["service_name"] = $service["name"];
            $request["service_simg"] = tomedia($service["simg"]);
            if ($request["order_type"] == 1) {
                if (empty($request["price"])) {
                    $request["price"] = $service["price"];
                }
                $request["o_price"] = $service["o_price"];
                if ($request["flash"] == 1) {
                    $request["o_price"] = $service["price"];
                }
            } else {
                if ($request["order_type"] == 3) {
                    $request["price"] = $service["group_price"];
                    $request["o_price"] = $service["price"];
                } else {
                    if ($request["order_type"] == 4) {
                        $request["price"] = $service["price"];
                        $request["o_price"] = $service["price"];
                    }
                }
            }
            if (!empty($request["store"])) {
                $request["store_list"] = pdo_get("xc_beauty_store", array("id" => $request["store"], "uniacid" => $uniacid));
            }
            if (!empty($request["member"]) && $request["member"] != -2) {
                $request["member_list"] = pdo_get("xc_beauty_store_member", array("uniacid" => $uniacid, "id" => $request["member"]));
                $request["member_name"] = $request["member_list"]["name"];
            } else {
                if ($request["member"] == -2) {
                    $request["member_name"] = "店内安排";
                }
            }
            $request["he_log"] = json_decode($request["he_log"], true);
            $request["userinfo"] = json_decode($request["userinfo"], true);
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "order_del":
        $request = pdo_delete("xc_beauty_order", array("uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "coupon":
        $request = pdo_getall("xc_beauty_user_coupon", array("status" => -1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            $coupon = pdo_getall("xc_beauty_coupon", array("status" => 1, "uniacid" => $uniacid));
            $datalist = array();
            if ($coupon) {
                foreach ($coupon as $x) {
                    if (!empty($x["condition"])) {
                        if (floatval($_GPC["amount"]) >= floatval($x["condition"])) {
                            if (!empty($x["times"])) {
                                $x["times"] = json_decode($x["times"], true);
                                if (time() > strtotime($x["times"]["start"]) && time() < strtotime($x["times"]["end"])) {
                                    $datalist[$x["id"]] = $x;
                                }
                            } else {
                                $datalist[$x["id"]] = $x;
                            }
                        }
                    } else {
                        $datalist[$x["id"]] = $x;
                    }
                }
            }
            foreach ($request as $key => $y) {
                if (empty($datalist[$y["cid"]])) {
                    unset($request[$key]);
                } else {
                    $request[$key]["coupon"] = $datalist[$y["cid"]];
                }
            }
            if (!empty($request)) {
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "refund":
        $request = pdo_update("xc_beauty_order", array("status" => 2, "refund_content" => $_GPC["content"]), array("uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "over":
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["status"] = 1;
        if ($_GPC["curr"] == 1) {
            $condition["order_type"] = 2;
        } else {
            if ($_GPC["curr"] == 2) {
                $condition["order_type IN"] = array(1, 3, 4, 5);
                $condition["canpay !="] = 0;
            }
        }
        $request = pdo_getall("xc_beauty_order", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "withdraw":
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
        } else {
            if ($condition["order_type"] == 2) {
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
            } else {
                if ($condition["order_type"] == 2) {
                    if (floatval($userinfo["share_o_amount"]) < floatval($_GPC["amount"])) {
                        return $this->result(1, "余额不足");
                    } else {
                        $money = round(floatval($userinfo["share_o_amount"]) - floatval($_GPC["amount"]), 2);
                        pdo_update("xc_beauty_userinfo", array("share_o_amount" => $money), array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
                        $condition["money"] = $money;
                    }
                }
            }
        }
        $request = pdo_insert("xc_beauty_withdraw", $condition);
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "score_order":
        $request = pdo_getall("xc_beauty_score", array("status" => $_GPC["curr"], "openid" => $_W["openid"], "uniacid" => $uniacid), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "to_coupon":
        $coupon = pdo_get("xc_beauty_coupon", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($userinfo["score"] < $coupon["score"]) {
            return $this->result(1, "积分不足");
        }
        if ($coupon["total"] != 0) {
            $user = pdo_get("xc_beauty_user_coupon", array("status" => -1, "cid" => $_GPC["id"], "openid" => $_W["openid"], "uniacid" => $uniacid));
            if ($user) {
                $coupon["user"] = 1;
                return $this->result(0, "操作成功", $coupon);
            } else {
                $score = $userinfo["score"] - $coupon["score"];
                pdo_update("xc_beauty_userinfo", array("score" => $score), array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
                $request = pdo_insert("xc_beauty_user_coupon", array("uniacid" => $uniacid, "openid" => $_W["openid"], "cid" => $_GPC["id"]));
                if ($request) {
                    if ($coupon["total"] != -1) {
                        pdo_update("xc_beauty_coupon", array("total" => intval($coupon["total"]) - 1), array("uniacid" => $uniacid, "id" => $coupon["id"]));
                    }
                    $coupon["user"] = -1;
                    pdo_insert("xc_beauty_score", array("uniacid" => $uniacid, "openid" => $_W["openid"], "title" => "兑换优惠券", "status" => 2, "score" => $coupon["score"], "over" => $score));
                    return $this->result(0, "操作成功", $request);
                } else {
                    return $this->result(0, "操作失败");
                }
            }
        } else {
            return $this->result(1, "领取失败");
        }
        break;
    case "withdraw_order":
        $request = pdo_getall("xc_beauty_withdraw", array("openid" => $_W["openid"], "uniacid" => $uniacid, "order_type" => $_GPC["order_type"]), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "group_order":
        $order = pdo_getall("xc_beauty_order", array("status IN" => array(1, 2), "openid" => $_W["openid"], "uniacid" => $uniacid, "order_type" => 3));
        if ($order) {
            $group = array();
            $order_list = array();
            foreach ($order as $x) {
                $group[] = $x["group"];
                $order_list[$x["group"]] = $x;
            }
            $request = pdo_getall("xc_beauty_group", array("id IN" => $group, "uniacid" => $uniacid), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
            if ($request) {
                $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
                $datalist = array();
                if ($service) {
                    foreach ($service as $x2) {
                        $datalist[$x2["id"]] = $x2;
                    }
                }
                $userinfo = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid));
                $user_list = array();
                if ($userinfo) {
                    foreach ($userinfo as $u) {
                        $user_list[$u["openid"]] = $u;
                    }
                }
                foreach ($request as &$y) {
                    $y["name"] = $datalist[$y["pid"]]["name"];
                    $y["simg"] = tomedia($datalist[$y["pid"]]["simg"]);
                    $y["price"] = $datalist[$y["pid"]]["group_price"];
                    if ($y["status"] == -1) {
                        $y["fail"] = $y["failtime"] * 60 * 60 + strtotime($y["createtime"]) - time();
                    }
                    $y["avatar"] = $user_list[$y["openid"]]["avatar"];
                    $y["nick"] = base64_decode($user_list[$y["openid"]]["nick"]);
                    if (!empty($y["team"])) {
                        $y["team"] = json_decode($y["team"], true);
                        $i = 0;
                        while ($i < count($y["team"])) {
                            $y["team"][$i] = $user_list[$y["team"][$i]]["avatar"];
                            $i++;
                        }
                    }
                    $y["order"] = $order_list[$y["id"]];
                    $y["out_trade_no"] = $order_list[$y["id"]]["out_trade_no"];
                }
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "group_detail":
        $request = pdo_get("xc_beauty_group", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $service = pdo_get("xc_beauty_service", array("id" => $request["pid"], "uniacid" => $uniacid));
            $service["simg"] = tomedia($service["simg"]);
            $request["service"] = $service;
            $request["sale"] = round(floatval($service["price"]) - floatval($service["group_price"]), 2);
            if ($request["status"] == -1) {
                $request["fail"] = $request["failtime"] * 60 * 60 + strtotime($request["createtime"]) - time();
            }
            $userinfo = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid));
            $user_list = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $user_list[$u["openid"]] = $u;
                }
            }
            $request["avatar"] = $user_list[$request["openid"]]["avatar"];
            if (!empty($request["team"])) {
                $request["team"] = json_decode($request["team"], true);
                foreach ($request["team"] as &$x) {
                    $x = $user_list[$x]["avatar"];
                }
            }
            $request["empty"] = array();
            if ($request["total"] != $request["team_total"]) {
                $i = 0;
                while ($i < $request["total"] - $request["team_total"]) {
                    $request["empty"][] = '';
                    $i++;
                }
            }
            $order = pdo_get("xc_beauty_order", array("status" => 1, "openid" => $_W["openid"], "group" => $request["id"], "order_type" => 3, "uniacid" => $uniacid));
            if ($order) {
                $request["out_trade_no"] = $order["out_trade_no"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "share_order":
        $request = pdo_getall("xc_beauty_share", array("status" => $_GPC["curr"], "uniacid" => $uniacid, "openid" => $_W["openid"]), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "store_order":
        $condition["uniacid"] = $uniacid;
        $condition["order_type"] = 4;
        $condition["openid"] = $_W["openid"];
        $fail_status = -1;
        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["order_fail"])) {
                $fail_status = 1;
            }
        }
        $request = array();
        if ($_GPC["curr"] == 1) {
            $condition["status"] = -1;
            if ($fail_status == 1) {
                $condition["failtime >"] = date("Y-m-d H:i:s");
            }
            $request = pdo_getall("xc_beauty_order", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        } else {
            if ($_GPC["curr"] == 2) {
                $condition["status !="] = -1;
                $request = pdo_getall("xc_beauty_order", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
            } else {
                if ($_GPC["curr"] == -1) {
                    $page = (intval($_GPC["page"]) - 1) * intval($_GPC["pagesize"]);
                    $pagesize = intval($_GPC["pagesize"]);
                    $data = array(":uniacid" => $uniacid, ":openid" => $_W["openid"]);
                    $where = '';
                    if ($fail_status == 1) {
                        $data[":failtime"] = date("Y-m-d H:i:s");
                        $where = " AND (status=1 OR status=-1 AND failtime>:failtime) ";
                    }
                    $sql = "SELECT * FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND order_type=4 AND openid=:openid " . $where . " ORDER BY id DESC LIMIT {$page},{$pagesize}";
                    $request = pdo_fetchall($sql, $data);
                }
            }
        }
        if ($request) {
            $service = pdo_getall("xc_beauty_store_service", array("uniacid" => $uniacid));
            $service_data = array();
            if ($service) {
                foreach ($service as $s) {
                    $service_data[$s["id"]] = $s;
                }
            }
            $member = pdo_getall("xc_beauty_store_member", array("uniacid" => $uniacid));
            $member_data = array();
            if ($member) {
                foreach ($member as $m) {
                    $member_data[$m["id"]] = $m;
                }
            }
            $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid));
            $store_data = array();
            if ($store) {
                foreach ($store as $ss) {
                    $store_data[$ss["id"]] = $ss;
                }
            }
            foreach ($request as &$x) {
                if ($x["store"] != -1) {
                    $x["name"] = $store_data[$x["store"]]["name"];
                    $x["simg"] = tomedia($store_data[$x["store"]]["simg"]);
                }
                if ($x["member"] == -2) {
                    $x["member_name"] = "店内安排";
                } else {
                    $x["member_name"] = $member_data[$x["member"]]["name"];
                }
                $x["service_name"] = $service_data[$x["pid"]]["name"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "store":
        if (!empty($_GPC["id"])) {
            $service = pdo_get("xc_beauty_service", array("id" => $_GPC["id"], "uniacid" => $uniacid));
            if ($service) {
                if ($service["store_status"] == -1 && !empty($service["store"])) {
                    $service["store"] = json_decode($service["store"], true);
                    $id = array();
                    foreach ($service["store"] as $y) {
                        $id[] = $y["id"];
                    }
                    if (count($id) > 0) {
                        $condition["id IN"] = $id;
                    }
                }
            }
        }
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        $request = pdo_getall("xc_beauty_store", $condition, array(), '', "sort DESC,createtime DESC");
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
                $x["map"] = json_decode($x["map"], true);
                if (!empty($x["map"]["longitude"]) && !empty($x["map"]["latitude"]) && !empty($_GPC["longitude"]) && !empty($_GPC["latitude"])) {
                    $x["distance"] = getDistance($x["map"]["longitude"], $x["map"]["latitude"], $_GPC["longitude"], $_GPC["latitude"]);
                    $x["distance"] = round($x["distance"] / 1000);
                }
            }
            if (!empty($_GPC["longitude"]) && !empty($_GPC["latitude"])) {
                $request = my_array_multisort($request, "distance", SORT_ASC);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "buy_order":
        $request = pdo_getall("xc_beauty_order", array("status" => 1, "order_type" => 5, "openid" => $_W["openid"], "uniacid" => $uniacid), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid));
            $datalist = array();
            if ($store) {
                foreach ($store as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request as &$x) {
                if (!empty($datalist[$x["store"]])) {
                    $x["store_name"] = $datalist[$x["store"]]["name"];
                } else {
                    $x["store_name"] = "门店不存在";
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "buy_detail":
        $request = pdo_get("xc_beauty_order", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $request["store"]));
            if ($store) {
                $request["store_list"] = $store;
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
}
function getDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6367000;
    $lat1 = $lat1 * pi() / 180;
    $lng1 = $lng1 * pi() / 180;
    $lat2 = $lat2 * pi() / 180;
    $lng2 = $lng2 * pi() / 180;
    $calcLongitude = $lng2 - $lng1;
    $calcLatitude = $lat2 - $lat1;
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;
    return round($calculatedDistance);
}
function my_array_multisort($data, $sort_order_field, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
{
    foreach ($data as $val) {
        $key_arrays[] = $val[$sort_order_field];
    }
    array_multisort($key_arrays, $sort_order, $sort_type, $data);
    return $data;
}