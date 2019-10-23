<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("list", "statuschange", "add_orders", "timeschange", "yan");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "list";
$tablename = "xc_beauty_order";
switch ($op) {
    case "list":
        $condition = array();
        $condition["status IN"] = array(-1, 1, 2);
        $condition["order_type"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["out_trade_no"])) {
            $out_trade_no = $_GPC["out_trade_no"];
            $condition["out_trade_no LIKE"] = "%" . $_GPC["out_trade_no"] . "%";
        }
        if (!empty($_GPC["openid"])) {
            $openid = $_GPC["openid"];
            $condition["openid LIKE"] = "%" . $_GPC["openid"] . "%";
        }
        if (!empty($_GPC["use"])) {
            $use = $_GPC["use"];
            $condition["use"] = $_GPC["use"];
        }
        if (!empty($_GPC["content"])) {
            $content = $_GPC["content"];
            $condition["userinfo LIKE"] = "%" . $_GPC["content"] . "%";
        }
        if (!empty($_GPC["pay_type"])) {
            $pay_type = $_GPC["pay_type"];
            $condition["pay_type"] = $_GPC["pay_type"];
        }
        if (!empty($_GPC["store_id"])) {
            $store_id = $_GPC["store_id"];
            $condition["store"] = $_GPC["store_id"];
        }
        if (!empty($_GPC["times"])) {
            $times = $_GPC["times"];
            $condition["createtime >="] = $_GPC["times"]["start"];
            $condition["createtime <="] = $_GPC["times"]["end"];
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
        $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        $store_list = array();
        if ($store) {
            foreach ($store as $s) {
                $store_list[$s["id"]] = $s;
            }
        }
        if ($list) {
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2["id"]] = $x2;
                }
            }
            $userinfo = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $user_list = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $user_list[$u["openid"]] = $u;
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
                if ($x["pay_type"] == 1) {
                    $x["pay_name"] = "微信支付";
                } else {
                    if ($x["pay_type"] == 2) {
                        $x["pay_name"] = "余额支付";
                    }
                }
                $x["service"] = $datalist[$x["pid"]]["name"];
                $x["nick"] = base64_decode($user_list[$x["openid"]]["nick"]);
                if (!empty($x["store"])) {
                    $x["store_name"] = $store_list[$x["store"]]["name"];
                }
                if (!empty($x["member"])) {
                    if ($x["member"] == -2) {
                        $x["member_name"] = "店内安排";
                    } else {
                        $x["member_name"] = $member_list[$x["member"]]["name"];
                    }
                }
                $x["he_log"] = json_decode($x["he_log"], true);
            }
        }
        include $this->template("Order/list");
        break;
    case "statuschange":
        $order = pdo_get($tablename, array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        $condition["is_use"] = intval($order["is_use"]) + 1;
        if (intval($condition["is_use"]) == intval($order["can_use"])) {
            $condition["use"] = 1;
        }
        if (!empty($order["he_log"])) {
            $order["he_log"] = json_decode($order["he_log"], true);
            if (!is_array($order["he_log"])) {
                $order["he_log"] = array();
            }
            $order["he_log"][] = array("name" => "后台", "time" => date("Y-m-d H:i:s"));
            $condition["he_log"] = json_encode($order["he_log"]);
        } else {
            $order["he_log"] = array();
            $order["he_log"][] = array("name" => "后台", "time" => date("Y-m-d H:i:s"));
            $condition["he_log"] = json_encode($order["he_log"]);
        }
        $request = pdo_update($tablename, $condition, array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
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
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => 0, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
    case "delete":
        $request = pdo_delete($tablename, array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => 0, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
    case "timeschange":
        $request = pdo_update($tablename, array("is_use" => $_GPC["time"], "use" => -1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => 0, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
    case "yan":
        $order = pdo_get($tablename, array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($order) {
            if (!empty($order["wq_out_trade_no"])) {
                $pay = pdo_get("core_paylog", array("uniacid" => $uniacid, "openid" => $order["openid"], "tid" => $order["wq_out_trade_no"]));
            } else {
                $pay = pdo_get("core_paylog", array("uniacid" => $uniacid, "openid" => $order["openid"], "tid" => $order["out_trade_no"]));
            }
            if ($pay) {
                if ($pay["status"] == 1) {
                    pdo_update($tablename, array("status" => 1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
                    $json = array("status" => 1, "msg" => "已支付");
                    echo json_encode($json);
                } else {
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
                        $json = array("status" => 1, "msg" => $result["return_msg"]);
                        echo json_encode($json);
                        exit;
                    }
                    if ($result["result_code"] != "SUCCESS") {
                        $json = array("status" => 1, "msg" => $result["err_code_des"]);
                        echo json_encode($json);
                        exit;
                    }
                    if ($result["trade_state"] == "SUCCESS") {
                        $total_fee = $result["total_fee"] / 100;
                        if (floatval($total_fee) == floatval($order["wxpay"])) {
                            pdo_update($tablename, array("status" => 1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
                            $json = array("status" => 1, "msg" => $result["trade_state_desc"]);
                            echo json_encode($json);
                        } else {
                            $json = array("status" => 1, "msg" => "金额不同");
                            echo json_encode($json);
                        }
                    } else {
                        $json = array("status" => 1, "msg" => $result["trade_state_desc"]);
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