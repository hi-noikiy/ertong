<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("list", "withdraw", "statuschange", "share", "count", "buy", "count_detail");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "list";
$tablename = "xc_beauty_order";
switch ($op) {
    case "list":
        $condition = array();
        $condition["status"] = 1;
        $condition["order_type"] = 2;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["out_trade_no"])) {
            $out_trade_no = $_GPC["out_trade_no"];
            $condition["out_trade_no LIKE"] = "%" . $_GPC["out_trade_no"] . "%";
        }
        if (!empty($_GPC["openid"])) {
            $openid = $_GPC["openid"];
            $condition["openid LIKE"] = "%" . $_GPC["openid"] . "%";
        }
        if (!empty($_GPC["mobile"])) {
            $mobile = $_GPC["mobile"];
            $openid_mobile = array();
            $use_mobile = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid, "mobile LIKE" => "%" . $_GPC["mobile"] . "%"));
            if ($use_mobile) {
                foreach ($use_mobile as $ff) {
                    $openid_mobile[] = $ff["openid"];
                }
            }
            $condition["openid IN"] = $openid_mobile;
        }
        $request = pdo_getall($tablename, $condition);
        $total = count($request);
        if (!isset($_GPC["page"])) {
            $pageindex = 1;
        } else {
            $pageindex = intval($_GPC["page"]);
        }
        $pagesize = 15;
        $pager = pagination($total, $pageindex, $pagesize);
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
        if ($list) {
            $userinfo = pdo_getall("xc_beauty_userinfo", array("card" => 1, "uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($list as &$x) {
                $x["title"] = "会员充值";
                $x["mobile"] = $datalist[$x["openid"]]["mobile"];
            }
        }
        include $this->template("Record/list");
        break;
    case "withdraw":
        $tablename = "xc_beauty_withdraw";
        $condition = array();
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["openid"])) {
            $openid = $_GPC["openid"];
            $condition["openid LIKE"] = "%" . $_GPC["openid"] . "%";
        }
        if (!empty($_GPC["status"])) {
            $status = $_GPC["status"];
            $condition["status LIKE"] = $_GPC["status"];
        }
        $request = pdo_getall($tablename, $condition);
        $total = count($request);
        if (!isset($_GPC["page"])) {
            $pageindex = 1;
        } else {
            $pageindex = intval($_GPC["page"]);
        }
        $pagesize = 15;
        $pager = pagination($total, $pageindex, $pagesize);
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
        if ($list) {
            foreach ($list as &$x) {
                if ($x["pay_type"] == 1) {
                    $x["pay_name"] = "微信";
                } else {
                    if ($x["pay_type"] == 2) {
                        $x["pay_name"] = "支付宝";
                    }
                }
                if ($x["order_type"] == 1) {
                    $x["order_name"] = "余额提现";
                } else {
                    if ($x["order_type"] == 2) {
                        $x["order_name"] = "佣金提现";
                    }
                }
            }
        }
        include $this->template("Record/withdraw");
        break;
    case "statuschange":
        $tablename = "xc_beauty_withdraw";
        $request = pdo_update($tablename, array("status" => $_GPC["status"]), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $order = pdo_get($tablename, array("id" => $_GPC["id"], "uniacid" => $uniacid));
            $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
            if ($_GPC["status"] == 1) {
                if ($order["order_type"] == 2) {
                    $money = round(floatval($userinfo["share_t_amount"]) + floatval($order["amount"]), 2);
                    pdo_update("xc_beauty_userinfo", array("share_t_amount" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                }
            } else {
                if ($_GPC["status"] == 2) {
                    if ($order["order_type"] == 1) {
                        $money = round(floatval($userinfo["money"]) + floatval($order["amount"]), 2);
                        pdo_update("xc_beauty_userinfo", array("money" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                    } else {
                        if ($order["order_type"] == 2) {
                            $money = round(floatval($userinfo["share_o_amount"]) + floatval($order["amount"]), 2);
                            pdo_update("xc_beauty_userinfo", array("share_o_amount" => $money), array("status" => 1, "openid" => $order["openid"], "uniacid" => $uniacid));
                        }
                    }
                }
            }
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => -1, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
    case "share":
        $tablename = "xc_beauty_share";
        $condition = array();
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["out_trade_no"])) {
            $out_trade_no = $_GPC["out_trade_no"];
            $condition["out_trade_no LIKE"] = "%" . $_GPC["out_trade_no"] . "%";
        }
        if (!empty($_GPC["openid"])) {
            $openid = $_GPC["openid"];
            $condition["openid LIKE"] = "%" . $_GPC["openid"] . "%";
        }
        $request = pdo_getall($tablename, $condition);
        $total = count($request);
        if (!isset($_GPC["page"])) {
            $pageindex = 1;
        } else {
            $pageindex = intval($_GPC["page"]);
        }
        $pagesize = 15;
        $pager = pagination($total, $pageindex, $pagesize);
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
        include $this->template("Record/share");
        break;
    case "count":
        $tablename = "xc_beauty_count";
        $condition = array();
        $condition["store !="] = -1;
        $condition["type"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["times"]) && !empty($_GPC["times"]["start"]) && !empty($_GPC["times"]["end"])) {
            $times = $_GPC["times"];
            $condition["plan_date >="] = $_GPC["times"]["start"];
            $condition["plan_date <="] = $_GPC["times"]["end"];
        } else {
            $times = array("start" => date("Y-m") . "-01", "end" => date("Y-m-d"));
        }
        if (!empty($_GPC["store_id"])) {
            $store_id = $_GPC["store_id"];
            $condition["store"] = $_GPC["store_id"];
        }
        $request = pdo_getall($tablename, $condition);
        $total = count($request);
        if (!isset($_GPC["page"])) {
            $pageindex = 1;
        } else {
            $pageindex = intval($_GPC["page"]);
        }
        $pagesize = 15;
        $pager = pagination($total, $pageindex, $pagesize);
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
        $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid), array(), '', "sort DESC,id DESC");
        $datalist = array();
        if ($store) {
            foreach ($store as $s) {
                $datalist[$s["id"]] = $s;
            }
        }
        $gg = array();
        if ($list) {
            foreach ($list as $key => $x) {
                $list[$key]["store_name"] = $datalist[$list[$key]["store"]]["name"];
                $list[$key]["dd"] = -1;
                if ($key > 0 && $list[$key - 1]["plan_date"] == $x["plan_date"]) {
                    $list[$key]["dd"] = 1;
                }
                if (!empty($gg[$x["plan_date"]])) {
                    $gg[$x["plan_date"]] = $gg[$x["plan_date"]] + 1;
                } else {
                    $gg[$x["plan_date"]] = 1;
                }
            }
        }
        include $this->template("Record/count");
        break;
    case "count_detail":
        $tablename = "xc_beauty_count";
        $condition = array();
        if (!empty($_GPC["id"])) {
            $plan_date = $_GPC["id"];
            $condition["plan_date LIKE"] = "%" . $_GPC["id"] . "%";
        }
        $condition["store !="] = -1;
        $condition["type"] = 2;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["times"])) {
            $times = $_GPC["times"];
            $condition["plan_date >="] = $_GPC["times"]["start"];
            $condition["plan_date <="] = $_GPC["times"]["end"];
        } else {
            $times = array("start" => date("Y-m") . "-01", "end" => date("Y-m-d"));
        }
        if (!empty($_GPC["store_id"])) {
            $store_id = $_GPC["store_id"];
            $condition["store"] = $_GPC["store_id"];
        }
        $request = pdo_getall($tablename, $condition);
        $total = count($request);
        if (!isset($_GPC["page"])) {
            $pageindex = 1;
        } else {
            $pageindex = intval($_GPC["page"]);
        }
        $pagesize = 15;
        $pager = pagination($total, $pageindex, $pagesize);
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
        $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid), array(), '', "sort DESC,id DESC");
        $datalist = array();
        if ($store) {
            foreach ($store as $s) {
                $datalist[$s["id"]] = $s;
            }
        }
        if ($list) {
            foreach ($list as $key => $x) {
                $list[$key]["store_name"] = $datalist[$list[$key]["store"]]["name"];
            }
        }
        include $this->template("Record/count_detail");
        break;
    case "buy":
        $condition = array();
        $condition["status"] = 1;
        $condition["order_type"] = 5;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["out_trade_no"])) {
            $out_trade_no = $_GPC["out_trade_no"];
            $condition["out_trade_no LIKE"] = "%" . $_GPC["out_trade_no"] . "%";
        }
        if (!empty($_GPC["openid"])) {
            $openid = $_GPC["openid"];
            $condition["openid LIKE"] = "%" . $_GPC["openid"] . "%";
        }
        $request = pdo_getall($tablename, $condition);
        $total = count($request);
        if (!isset($_GPC["page"])) {
            $pageindex = 1;
        } else {
            $pageindex = intval($_GPC["page"]);
        }
        $pagesize = 15;
        $pager = pagination($total, $pageindex, $pagesize);
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC", array($pageindex, $pagesize));
        $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid));
        $datalist = array();
        if ($store) {
            foreach ($store as $s) {
                $datalist[$s["id"]] = $s["name"];
            }
        }
        if ($list) {
            foreach ($list as &$x) {
                $x["store_name"] = $datalist[$x["store"]];
            }
        }
        include $this->template("Record/buy");
        break;
}