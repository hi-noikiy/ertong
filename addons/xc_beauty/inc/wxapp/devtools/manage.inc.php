<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("bind", "login", "index", "count", "income", "order_change", "order", "order_search", "online", "store", "online_status", "prize", "prize_status", "prize_detail", "index2", "amount", "store_detail", "member_search", "record", "pclass", "service", "shop_num", "shop_status", "shop", "pick_order", "pick_order_detail", "pick_order_del", "pick_order_status", "pick_order_change");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "manage";
switch ($op) {
    case "bind":
        $request = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            if ($request["shop"] == 1) {
                return $this->result(0, "操作成功", array("status" => 1));
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "login":
        $request = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "web"));
        if ($request) {
            $request["content"] = json_decode($request["content"], true);
            if (!empty($request["content"]["password"])) {
                if ($request["content"]["password"] == md5($_GPC["password"])) {
                    if ($_GPC["status"] == 1) {
                        pdo_update("xc_beauty_userinfo", array("shop" => 1), array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
                    }
                    return $this->result(0, "操作成功", array("status" => 1));
                } else {
                    return $this->result(1, "密码错误");
                }
            } else {
                return $this->result(1, "密码错误");
            }
        } else {
            return $this->result(1, "密码错误");
        }
        break;
    case "index":
        $request = array();
        $count = pdo_get("xc_beauty_count", array("uniacid" => $uniacid, "plan_date" => date("Y-m"), "type" => 1));
        if ($count) {
            $request["count"] = $count;
        } else {
            $request["count"] = array("order" => 0, "amount" => "0.00");
        }
        $order = pdo_getall("xc_beauty_order", array("uniacid" => $uniacid, "status IN" => array(1, 2)), array(), '', "createtime DESC", array(1, 2));
        if ($order) {
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x) {
                    $datalist[$x["id"]] = $x;
                }
            }
            foreach ($order as &$y) {
                $y["pname"] = $datalist[$y["pid"]]["name"];
                $y["userinfo"] = json_decode($y["userinfo"], true);
            }
            $request["order"] = $order;
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "count":
        $condition["uniacid"] = $uniacid;
        if ($_GPC["today"] == 1) {
            $condition["plan_date"] = date("Y-m");
        } else {
            if ($_GPC["today"] == -1) {
                $condition["plan_date"] = date("Y-m", strtotime("last month"));
            }
        }
        $condition["type"] = 1;
        $request = pdo_get("xc_beauty_count", $condition);
        if (!$request) {
            $request = array("order" => 0, "amount" => "0.00");
        }
        return $this->result(0, "操作成功", $request);
        break;
    case "income":
        $condition["uniacid"] = $uniacid;
        $condition["type"] = 1;
        if (!empty($_GPC["store"])) {
            $condition["store"] = $_GPC["store"];
        }
        $request = pdo_getall("xc_beauty_count", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "order_change":
        $order = pdo_get("xc_beauty_order", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        $userinfo = pdo_get("xc_beauty_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
        if ($userinfo["shop"] != 1 && $userinfo["shop_id"] != $order["store"]) {
            return $this->result(1, "没有权限");
        }
        $condition["is_use"] = intval($order["is_use"]) + 1;
        if ($condition["is_use"] == intval($order["can_use"])) {
            $condition["use"] = 1;
        }
        if (!empty($order["he_log"])) {
            $order["he_log"] = json_decode($order["he_log"], true);
            if (!is_array($order["he_log"])) {
                $order["he_log"] = array();
            }
            $order["he_log"][] = array("name" => $_W["openid"], "time" => date("Y-m-d H:i:s"));
            $condition["he_log"] = json_encode($order["he_log"]);
        } else {
            $order["he_log"] = array();
            $order["he_log"][] = array("name" => $_W["openid"], "time" => date("Y-m-d H:i:s"));
            $condition["he_log"] = json_encode($order["he_log"]);
        }
        $request = pdo_update("xc_beauty_order", $condition, array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            if (!empty($condition["use"])) {
                $share = pdo_getall("xc_beauty_share", array("status" => -1, "uniacid" => $uniacid, "out_trade_no" => $order["out_trade_no"]));
                if ($share) {
                    foreach ($share as $x) {
                        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $x["openid"]));
                        if ($userinfo) {
                            $share_amount = round(floatval($userinfo["share_amount"]) + floatval($x["amount"]), 2);
                            $share_o_amount = round(floatval($userinfo["share_o_amount"]) + floatval($x["amount"]), 2);
                            pdo_update("xc_beauty_userinfo", array("share_amount" => $share_amount, "share_o_amount" => $share_o_amount), array("status" => 1, "uniacid" => $uniacid, "openid" => $x["openid"]));
                        }
                        pdo_update("xc_beauty_share", array("status" => 1), array("uniacid" => $uniacid, "id" => $x["id"]));
                    }
                }
            }
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "order":
        $page = ($_GPC["page"] - 1) * $_GPC["pagesize"];
        $pagesize = $_GPC["pagesize"];
        $condition = '';
        if ($_GPC["curr"] == 1) {
            $condition = $condition . " AND status=1";
            $condition = $condition . "  AND `use`=-1";
        } else {
            if ($_GPC["curr"] == 2) {
                $condition = $condition . " AND status=1";
                $condition = $condition . " AND `use`=1";
            } else {
                if ($_GPC["curr"] == 3) {
                    $condition = $condition . " AND status=2";
                }
            }
        }
        if (!empty($_GPC["store"])) {
            $condition .= " AND store=" . $_GPC["store"];
        }
        if (!empty($_GPC["search"])) {
            $condition .= " AND (out_trade_no LIKE '%" . $_GPC["search"] . "%' OR userinfo LIKE '%" . $_GPC["search"] . "%')";
        }
        $request = pdo_fetchall("SELECT * FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND (order_type=1 OR order_type=3) " . $condition . " ORDER BY createtime DESC,id DESC LIMIT {$page},{$pagesize}", array(":uniacid" => $uniacid));
        if ($request) {
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x) {
                    $datalist[$x["id"]] = $x;
                }
            }
            foreach ($request as &$y) {
                $y["pname"] = $datalist[$y["pid"]]["name"];
                $y["userinfo"] = json_decode($y["userinfo"], true);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "order_search":
        $condition["uniacid"] = $uniacid;
        $condition["status IN"] = array(1, 2);
        if (!empty($_GPC["id"])) {
            $condition["id"] = $_GPC["id"];
        }
        if (!empty($_GPC["out_trade_no"])) {
            $condition["out_trade_no"] = $_GPC["out_trade_no"];
        }
        $request = pdo_get("xc_beauty_order", $condition);
        if ($request) {
            $request["userinfo"] = json_decode($request["userinfo"], true);
            if ($request["order_type"] == 4) {
                $service = pdo_get("xc_beauty_store_service", array("id" => $request["pid"], "uniacid" => $uniacid));
            } else {
                $service = pdo_get("xc_beauty_service", array("id" => $request["pid"], "uniacid" => $uniacid));
            }
            if ($service) {
                $request["pname"] = $service["name"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "订单不存在");
        }
        break;
    case "online":
        $online_time = -1;
        $config = pdo_get("xc_beauty_config", array("xkey" => "web", "uniacid" => $uniacid));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["online_time"])) {
                $online_time = $config["content"]["online_time"];
            }
        }
        $request = array();
        if (!empty($_GPC["id"])) {
            $condition["store"] = $_GPC["id"];
            if ($_GPC["id"] != -1) {
                $store = pdo_get("xc_beauty_store", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
                if ($store) {
                    $request["store"] = $store;
                }
            }
        } else {
            $store = pdo_getall("xc_beauty_store", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
            if ($store) {
                $request["store"] = $store[0];
                $condition["store"] = $request["store"]["id"];
            } else {
                return $this->result(0, "操作失败");
            }
        }
        $request["online"] = -1;
        $online = pdo_getall("xc_beauty_online", array("uniacid" => $uniacid, "status" => 1, "store" => $condition["store"], "plan_date" => $_GPC["plan_date"], "createtime >" => date("Y") . "-01-01 00:00:00"));
        if ($online) {
            $request["online"] = 1;
        }
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if ($online_time == 1) {
            $condition["plan_date LIKE"] = "%" . $_GPC["plan_date"] . "%";
            $condition["createtime >"] = date("Y") . "-01-01 00:00:00";
        } else {
            $date = $_GPC["plan_date"];
            $date = str_replace("月", "-", $date);
            $date = str_replace("日", '', $date);
            $date = date("Y") . "-" . $date;
            $date = date("Y-m-d", strtotime($date));
            $condition["createtime LIKE"] = "%" . $date . "%";
        }
        $condition["order_type"] = 4;
        $list = pdo_getall("xc_beauty_order", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($list) {
            $userinfo = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
            }
            $service = pdo_getall("xc_beauty_store_service", array("uniacid" => $uniacid));
            $service_list = array();
            if ($service) {
                foreach ($service as $s) {
                    $service_list[$s["id"]] = $s;
                }
            }
            $member = pdo_getall("xc_beauty_store_member", array("uniacid" => $uniacid));
            $member_list = array();
            if ($member) {
                foreach ($member as $m) {
                    $member_list[$m["id"]] = $m;
                }
            }
            foreach ($list as &$x) {
                $x["userinfo"] = json_decode($x["userinfo"], true);
                $x["simg"] = $datalist[$x["openid"]]["avatar"];
                $x["service_name"] = $service_list[$x["pid"]]["name"];
                $x["member_name"] = $member_list[$x["member"]]["name"];
            }
            $request["list"] = $list;
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "store":
        $request = pdo_getall("xc_beauty_store", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
                $x["map"] = json_decode($x["map"], true);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "online_status":
        $online = pdo_get("xc_beauty_online", array("uniacid" => $uniacid, "status" => -$_GPC["status"], "store" => $_GPC["store"], "plan_date" => $_GPC["plan_date"], "createtime >" => date("Y") . "-01-01 00:00:00"));
        if ($online) {
            $request = pdo_update("xc_beauty_online", array("status" => $_GPC["status"]), array("uniacid" => $uniacid, "status" => -$_GPC["status"], "store" => $_GPC["store"], "plan_date" => $_GPC["plan_date"], "createtime >" => date("Y") . "-01-01 00:00:00"));
        } else {
            $request = pdo_insert("xc_beauty_online", array("uniacid" => $uniacid, "status" => $_GPC["status"], "store" => $_GPC["store"], "plan_date" => $_GPC["plan_date"]));
        }
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "prize":
        $request = pdo_getall("xc_beauty_rotate_log", array("type" => 2, "uniacid" => $uniacid, "status" => $_GPC["curr"]), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $prize = pdo_getall("xc_beauty_prize", array("type" => 2, "uniacid" => $uniacid));
            $datalist = array();
            if ($prize) {
                foreach ($prize as $p) {
                    $datalist[$p["id"]] = $p;
                }
            }
            $userinfo = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $user_list = array();
            if ($userinfo) {
                foreach ($userinfo as &$u) {
                    $user_list[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$x) {
                $x["simg"] = tomedia($datalist[$x["cid"]]["simg"]);
                $x["nick"] = base64_decode($user_list[$x["openid"]]["nick"]);
                $x["avatar"] = $user_list[$x["openid"]]["avatar"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "prize_status":
        $request = pdo_update("xc_beauty_rotate_log", array("status" => 1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "prize_detail":
        $request = pdo_get("xc_beauty_rotate_log", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $prize = pdo_get("xc_beauty_prize", array("type" => 2, "uniacid" => $uniacid, "id" => $request["cid"]));
            if ($prize) {
                $request["simg"] = tomedia($prize["simg"]);
            }
            $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $request["openid"]));
            if ($userinfo) {
                $request["nick"] = base64_decode($userinfo["nick"]);
                $request["avatar"] = $userinfo["avatar"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "记录不存在");
        }
        break;
    case "index2":
        $request = array();
        $request["all_amount"] = 0;
        $count = pdo_getall("xc_beauty_count", array("uniacid" => $uniacid, "type" => 1));
        $datalist = array();
        if ($count) {
            foreach ($count as $c) {
                if ($c["store"] == -1) {
                    $request["all_amount"] = round(floatval($request["all_amount"]) + floatval($c["amount"]), 2);
                } else {
                    if (empty($datalist[$c["store"]])) {
                        $datalist[$c["store"]] = $c["amount"];
                    } else {
                        $datalist[$c["store"]] = round(floatval($datalist[$c["store"]]) + floatval($c["amount"]), 2);
                    }
                }
            }
        }
        $request["member"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_userinfo") . " WHERE uniacid=:uniacid", array(":uniacid" => $uniacid));
        $request["card_on"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_userinfo") . " WHERE card=1 AND uniacid=:uniacid", array(":uniacid" => $uniacid));
        $request["card"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_userinfo") . " WHERE card=-1 AND uniacid=:uniacid", array(":uniacid" => $uniacid));
        $request["over"] = pdo_fetchcolumn("SELECT SUM(money) FROM " . tablename("xc_beauty_userinfo") . " WHERE uniacid=:uniacid", array(":uniacid" => $uniacid));
        $request["over"] = round(floatval($request["over"]), 2);
        $request["refund"] = pdo_fetchcolumn("SELECT SUM(o_amount) FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND status=2 AND refund_status=1", array(":uniacid" => $uniacid));
        $request["refund"] = round(floatval($request["refund"]), 2);
        $request["pick_order"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_pick_order") . " WHERE uniacid=:uniacid", array(":uniacid" => $uniacid));
        $store = pdo_getall("xc_beauty_store", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($store) {
            foreach ($store as &$s) {
                $s["simg"] = tomedia($s["simg"]);
            }
            $request["store"] = $store;
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "记录不存在");
        }
        break;
    case "amount":
        $request = "0";
        $condition["uniacid"] = $uniacid;
        $condition["plan_date"] = $_GPC["year"] . "-" . $_GPC["month"];
        if (!empty($_GPC["store"])) {
            $condition["store"] = $_GPC["store"];
        } else {
            $condition["store"] = -1;
        }
        $condition["type"] = 1;
        $count = pdo_get("xc_beauty_count", $condition);
        if ($count) {
            $request = $count["amount"];
        }
        return $this->result(0, "操作成功", $request);
        break;
    case "store_detail":
        $request = array();
        $store = pdo_get("xc_beauty_store", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($store) {
            $request["store"] = $store;
        }
        $count = pdo_getall("xc_beauty_count", array("store" => $_GPC["id"], "uniacid" => $uniacid, "type" => 1));
        $request["all_amount"] = "0";
        if ($count) {
            foreach ($count as $x) {
                $request["all_amount"] = floatval($request["all_amount"]) + floatval($x["amount"]);
            }
        }
        $list = pdo_getall("xc_beauty_count", array("store" => $_GPC["id"], "uniacid" => $uniacid, "type" => 1), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($list) {
            $request["list"] = $list;
        }
        $request["member"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_userinfo") . " WHERE uniacid=:uniacid", array(":uniacid" => $uniacid));
        $request["card_on"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_userinfo") . " WHERE card=1 AND uniacid=:uniacid", array(":uniacid" => $uniacid));
        $request["store_card"] = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("xc_beauty_userinfo") . " WHERE uniacid=:uniacid AND store=:store", array(":uniacid" => $uniacid, ":store" => $_GPC["id"]));
        $request["wxpay"] = pdo_fetchcolumn("SELECT SUM(wxpay) FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND status=1 AND order_type IN (1,3,4,5) AND store=:store", array(":uniacid" => $uniacid, ":store" => $_GPC["id"]));
        $request["wxpay"] = round(floatval($request["wxpay"]), 2);
        $request["recharge"] = pdo_fetchcolumn("SELECT SUM(amount) FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND status=1 AND order_type=2 AND store=:store", array(":uniacid" => $uniacid, ":store" => $_GPC["id"]));
        $request["recharge"] = round(floatval($request["recharge"]), 2);
        $request["canpay"] = pdo_fetchcolumn("SELECT SUM(canpay) FROM " . tablename("xc_beauty_order") . " WHERE uniacid=:uniacid AND status=1 AND order_type IN (1,3,4,5) AND store=:store", array(":uniacid" => $uniacid, ":store" => $_GPC["id"]));
        $request["canpay"] = round(floatval($request["canpay"]), 2);
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "记录不存在");
        }
        break;
    case "member_search":
        $condition["uniacid"] = $uniacid;
        if ($_GPC["curr"] == 1) {
            $condition["mobile LIKE"] = "%" . $_GPC["search"] . "%";
        } else {
            if ($_GPC["curr"] == 2) {
                $condition["store"] = $_GPC["id"];
            }
        }
        $request = pdo_getall("xc_beauty_userinfo", $condition, array(), '', "createtime DESC,id DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["nick"] = base64_decode($x["nick"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "记录不存在");
        }
        break;
    case "record":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        $condition["store"] = $_GPC["store"];
        if ($_GPC["curr"] == 1) {
            $condition["order_type"] = 5;
        } else {
            if ($_GPC["curr"] == 2) {
                $condition["order_type"] = 2;
            }
        }
        $request = pdo_getall("xc_beauty_order", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $userinfo = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$x) {
                $x["name"] = $datalist[$x["openid"]]["name"];
                $x["mobile"] = $datalist[$x["openid"]]["mobile"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "记录不存在");
        }
        break;
    case "pclass":
        $request = pdo_getall("xc_beauty_pick_class", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "service":
        $request = pdo_getall("xc_beauty_pick_service", array("uniacid" => $uniacid, "status" => 1, "cid" => $_GPC["cid"]), array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $shop = pdo_getall("xc_beauty_shop", array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
            $datalist = array();
            if ($shop) {
                foreach ($shop as $s) {
                    $datalist[$s["pid"]] = $s;
                }
            }
            foreach ($request as &$x) {
                if (!empty($datalist[$x["id"]])) {
                    $x["total"] = $datalist[$x["id"]]["total"];
                } else {
                    $x["total"] = 0;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "shop_num":
        $request = array("total" => 0, "amount" => 0.0);
        $shop = pdo_getall("xc_beauty_shop", array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid));
        if ($shop) {
            $service = pdo_getall("xc_beauty_pick_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($shop as &$x) {
                if (!empty($datalist[$x["pid"]])) {
                    $request["total"] = $request["total"] + $x["total"];
                    $request["amount"] = round(floatval($request["amount"]) + $x["total"] * floatval($datalist[$x["pid"]]["price"]), 2);
                }
            }
        }
        return $this->result(0, "操作成功", $request);
        break;
    case "shop_status":
        $request = array();
        $condition["uniacid"] = $uniacid;
        $condition["status"] = $_GPC["status"];
        $condition["pid"] = $_GPC["pid"];
        $shop = pdo_get("xc_beauty_shop", array("uniacid" => $uniacid, "openid" => $_W["openid"], "pid" => $_GPC["pid"]));
        if ($shop) {
            if ($_GPC["status"] == 1) {
                $condition["total"] = $shop["total"] + $_GPC["total"];
                if ($condition["total"] < 0) {
                    $condition["total"] = 0;
                }
            } else {
                if ($_GPC["status"] == -1) {
                    $condition["total"] = 0;
                }
            }
            if ($condition["total"] == 0) {
                $condition["status"] = -1;
            }
            $request = pdo_update("xc_beauty_shop", $condition, array("uniacid" => $uniacid, "openid" => $_W["openid"], "pid" => $_GPC["pid"]));
        } else {
            if (!empty($_GPC["total"])) {
                $condition["total"] = $_GPC["total"];
            }
            $condition["openid"] = $_W["openid"];
            $request = pdo_insert("xc_beauty_shop", $condition);
        }
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "shop":
        $request = pdo_getall("xc_beauty_shop", array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid), array(), '', "createtime DESC");
        if ($request) {
            $service = pdo_getall("xc_beauty_pick_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request as &$x) {
                $x["service"] = $datalist[$x["pid"]]["name"];
                $x["price"] = $datalist[$x["pid"]]["price"];
                $x["unit"] = $datalist[$x["pid"]]["unit"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "pick_order":
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["store"])) {
            $condition["store"] = $_GPC["store"];
        }
        if (!empty($_GPC["content"])) {
            $condition["out_trade_no LIKE"] = "%" . $_GPC["content"] . "%";
        }
        $request = pdo_getall("xc_beauty_pick_order", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid));
            $datalist = array();
            if ($store) {
                foreach ($store as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request as &$x) {
                $x["store_list"] = $datalist[$x["store"]];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "pick_order_detail":
        $request = pdo_get("xc_beauty_pick_order", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $service = pdo_getall("xc_beauty_pick_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            $request["pid"] = json_decode($request["pid"], true);
            foreach ($request["pid"] as &$x) {
                $x["name"] = $datalist[$x["id"]]["name"];
                $x["status"] = -1;
            }
            $request["store_list"] = pdo_get("xc_beauty_store", array("id" => $request["store"], "uniacid" => $uniacid));
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "pick_order_del":
        $request = pdo_delete("xc_beauty_pick_order", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "pick_order_status":
        $request = pdo_update("xc_beauty_pick_order", array("status" => $_GPC["status"]), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "pick_order_change":
        $pid = $_GPC["pid"];
        $pid = json_decode(htmlspecialchars_decode($pid), true);
        if (!empty($pid) && is_array($pid)) {
            $total = 0;
            $amount = 0.0;
            foreach ($pid as $key => $p) {
                if ($p["total"] == 0) {
                    unset($pid[$key]);
                } else {
                    $total = $total + $p["total"];
                    $amount = round(floatval($amount) + floatval($p["price"]) * $p["total"], 2);
                }
            }
            $request = pdo_update("xc_beauty_pick_order", array("pid" => json_encode($pid), "total" => $total, "amount" => $amount), array("id" => $_GPC["id"], "uniacid" => $uniacid));
            if ($request) {
                $order = pdo_get("xc_beauty_pick_order", array("id" => $_GPC["id"], "uniacid" => $uniacid));
                $service = pdo_getall("xc_beauty_pick_service", array("uniacid" => $uniacid));
                $datalist = array();
                if ($service) {
                    foreach ($service as $s) {
                        $datalist[$s["id"]] = $s;
                    }
                }
                $order["pid"] = json_decode($order["pid"], true);
                foreach ($order["pid"] as &$x) {
                    $x["name"] = $datalist[$x["id"]]["name"];
                    $x["status"] = -1;
                }
                $order["store_list"] = pdo_get("xc_beauty_store", array("id" => $order["store"], "uniacid" => $uniacid));
                return $this->result(0, "操作成功", $order);
            } else {
                return $this->result(1, "操作失败");
            }
        } else {
            return $this->result(1, "操作失败");
        }
        break;
}