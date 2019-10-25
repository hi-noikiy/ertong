<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("list", "statuschange", "add_orders", "mall", "orderchange", "tui");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "list";
$tablename = "xc_train_order";
switch ($op) {
    case "list":
        $condition = array();
        $condition["status"] = 1;
        $condition["order_type IN"] = array(1, 2);
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
            $condition["mobile LIKE"] = "%" . $_GPC["mobile"] . "%";
        }
        if (!empty($_GPC["use"])) {
            $use = $_GPC["use"];
            $condition["use"] = $_GPC["use"];
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
            $store = pdo_getall("xc_train_school", array("uniacid" => $uniacid));
            $store_list = array();
            if ($store) {
                foreach ($store as $s) {
                    $store_list[$s["id"]] = $s;
                }
            }
            foreach ($list as &$x) {
                if (!empty($x["store"])) {
                    $x["store_name"] = $store_list[$x["store"]]["name"];
                }
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
        if (!empty($order["use_time"])) {
            $order["use_time"] = json_decode($order["use_time"], true);
        } else {
            $order["use_time"] = array();
        }
        $condition["use_time"] = $order["use_time"];
        $condition["use_time"][] = date("Y-m-d H:i:s");
        $condition["use_time"] = json_encode($condition["use_time"]);
        $request = pdo_update($tablename, $condition, array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
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
    case "mall":
        $condition = array();
        $condition["status IN"] = array(1, 2);
        $condition["order_type"] = 4;
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
        $store = pdo_getall("xc_train_school", array("uniacid" => $uniacid));
        if ($list) {
            $store_list = array();
            if ($store) {
                foreach ($store as $s) {
                    $store_list[$s["id"]] = $s;
                }
            }
            foreach ($list as &$x) {
                $x["userinfo"] = json_decode($x["userinfo"], true);
                if (!empty($x["store"])) {
                    $x["store_name"] = $store_list[$x["store"]]["name"];
                }
            }
        }
        include $this->template("Order/mall");
        break;
    case "orderchange":
        $request = pdo_update($tablename, array("order_status" => 1), array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => 0, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
    case "tui":
        $order = pdo_get($tablename, array("uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($order) {
            $setting = uni_setting_load("payment", $_W["uniacid"]);
            $refund_setting = $setting["payment"]["wechat_refund"];
            if ($refund_setting["switch"] != 1) {
                $json = array("status" => 0, "msg" => "未开启微信退款功能");
                echo json_encode($json);
                exit;
            }
            if (empty($refund_setting["key"]) || empty($refund_setting["cert"])) {
                $json = array("status" => 0, "msg" => "缺少微信证书");
                echo json_encode($json);
                exit;
            }
            $cert = authcode($refund_setting["cert"], "DECODE");
            $key = authcode($refund_setting["key"], "DECODE");
            file_put_contents(ATTACHMENT_ROOT . $_W["uniacid"] . "_wechat_refund_all.pem", $cert . $key);
            $status = 1;
            $message = '';
            if (floatval($order["o_amount"]) != 0) {
                $transaction_id = $order["wx_out_trade_no"];
                $total_fee = floatval($order["o_amount"]) * 100;
                $refund_fee = floatval($order["o_amount"]) * 100;
                $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
                $refund = array("appid" => $_W["account"]["key"], "mch_id" => $setting["payment"]["wechat"]["mchid"], "nonce_str" => random(8), "out_refund_no" => $transaction_id, "out_trade_no" => $transaction_id, "refund_fee" => $refund_fee, "total_fee" => $total_fee);
                $ref = strtoupper(md5("appid=" . $refund["appid"] . "&mch_id=" . $refund["mch_id"] . "&nonce_str=" . $refund["nonce_str"] . "&out_refund_no=" . $refund["out_refund_no"] . "&out_trade_no=" . $refund["out_trade_no"] . "&refund_fee=" . $refund["refund_fee"] . "&total_fee=" . $refund["total_fee"] . "&key=" . $setting["payment"]["wechat"]["signkey"]));
                $refund["sign"] = $ref;
                load()->func("communication");
                $xml = array2xml($refund);
                $response = ihttp_request("https://api.mch.weixin.qq.com/secapi/pay/refund", $xml, array(CURLOPT_SSLCERT => ATTACHMENT_ROOT . $_W["uniacid"] . "_wechat_refund_all.pem"));
                if ($response) {
                    $data = xml2array($response["content"]);
                    if ($data["return_code"] == "SUCCESS") {
                        if ($data["result_code"] == "SUCCESS") {
                            pdo_update($tablename, array("tui_status" => 1), array("id" => $order["id"], "uniacid" => $_W["uniacid"]));
                        } else {
                            $message = $data["err_code_des"];
                            $status = -1;
                        }
                    } else {
                        $message = $data["return_msg"];
                        $status = -1;
                    }
                } else {
                    $error = curl_errno($ch);
                    $message = $error;
                    $status = -1;
                }
            } else {
                pdo_update($tablename, array("tui_status" => 1), array("id" => $order["id"], "uniacid" => $_W["uniacid"]));
                $status = 1;
            }
            unlink(ATTACHMENT_ROOT . $_W["uniacid"] . "_wechat_refund_all.pem");
            if ($status == 1) {
                $json = array("status" => 1, "msg" => "操作成功");
                echo json_encode($json);
                exit;
            } else {
                $json = array("status" => 0, "msg" => $message);
                echo json_encode($json);
                exit;
            }
        } else {
            $json = array("status" => 0, "msg" => "操作失败");
            echo json_encode($json);
            exit;
        }
        break;
}