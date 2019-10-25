<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("list", "statuschange", "add_orders", "index", "group_success", "timeschange", "index2");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "index";
$tablename = "xc_beauty_order";
switch ($op) {
    case "index":
        $tablename = "xc_beauty_group";
        $condition = array();
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["group"])) {
            $group = $_GPC["group"];
            $condition["id"] = $_GPC["group"];
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
        $list = pdo_getall($tablename, $condition, array(), '', "createtime DESC,id DESC", array($pageindex, $pagesize));
        if ($list) {
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2["id"]] = $x2;
                }
            }
            foreach ($list as &$x) {
                $x["service"] = $datalist[$x["pid"]]["name"];
                $x["fail"] = date("Y-m-d H:i:s", strtotime($x["createtime"]) + intval($x["failtime"]) * 60 * 60);
            }
        }
        include $this->template("Group/index");
        break;
    case "list":
        $condition = array();
        $condition["status IN"] = array(1, 2);
        $condition["order_type"] = 3;
        $condition["uniacid"] = $uniacid;
        $condition["group"] = $_GPC["id"];
        $group = pdo_get("xc_beauty_group", array("id" => $_GPC["id"], "uniacid" => $uniacid));
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
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2["id"]] = $x2;
                }
            }
            $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid));
            $store_list = array();
            if ($store) {
                foreach ($store as $s) {
                    $store_list[$s["id"]] = $s;
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
        include $this->template("Group/list");
        break;
    case "index2":
        $condition = array();
        $condition["status IN"] = array(1, 2);
        $condition["order_type"] = 3;
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
            $service = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $x2) {
                    $datalist[$x2["id"]] = $x2;
                }
            }
            $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid));
            $store_list = array();
            if ($store) {
                foreach ($store as $s) {
                    $store_list[$s["id"]] = $s;
                }
            }
            $member = pdo_getall("xc_beauty_store_member", array("uniacid" => $uniacid));
            $member_list = array();
            if ($member) {
                foreach ($member as $m) {
                    $member_list[$m["id"]] = $m;
                }
            }
            $group = pdo_getall("xc_beauty_group", array("uniacid" => $uniacid));
            $group_list = array();
            if ($group) {
                foreach ($group as $gg) {
                    $group_list[$gg["id"]] = $gg;
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
                $x["group_order"] = $group_list[$x["group"]];
                $x["he_log"] = json_decode($x["he_log"], true);
            }
        }
        include $this->template("Group/index2");
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
    case "group_success":
        $group = pdo_get("xc_beauty_group", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        $request = pdo_update("xc_beauty_group", array("status" => 1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $list = pdo_getall("xc_beauty_order", array("status" => 1, "order_type" => 3, "group" => $group["id"]));
            if ($list) {
                foreach ($list as $l) {
                    if (!empty($l["score"])) {
                        $userinfo = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $l["openid"]));
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
                            require_once IA_ROOT . "/addons/xc_beauty/resource/WeChat.class.php";
                            $wechat = new Wechat();
                            $token = $wechat->checkAuth($_W["account"]["key"], $_W["account"]["secret"]);
                            $service = pdo_get("xc_beauty_service", array("id" => $l["pid"]));
                            $postdata = array("keyword1" => array("value" => $l["out_trade_no"]), "keyword2" => array("value" => $l["amount"]), "keyword3" => array("value" => $service["name"]), "keyword4" => array("value" => date("Y-m-d")));
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
}