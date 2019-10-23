<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("order", "discuss", "discuss_on", "mall_order", "mall_order_status", "mall_order_detail", "mall_tui");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "order";
switch ($op) {
    case "order":
        $request = pdo_getall("xc_train_order", array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid, "order_type" => $_GPC["order_type"]), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $service = pdo_getall("xc_train_service_team", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request as &$x) {
                $x["start_time"] = $datalist[$x["pid"]]["start_time"];
                $x["use_time"] = json_decode($x["use_time"], true);
            }
            $request = pdo_getall("xc_train_service", array("status" => 1, "id" => $_GPC["id"]));
            if ($request) {
                foreach ($request as &$x) {
                    $x["price"] = floatval($x["price"]);
                    $x["amount"] = floatval($x["amount"]);
                    if ($x["type"] == 1 && !empty($x["service"])) {
                        $service = pdo_get("xc_train_service", array("uniacid" => $uniacid, "id" => $_GPC["id"]));
                        if ($service) {
                            $x["service_name"] = $service["name"];
                            $x["price"] = $service["price"];
                        }
                    }
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "discuss":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        $condition["pid"] = $_GPC["id"];
        $condition["type"] = 1;
        if (!empty($_GPC["type"])) {
            $condition["type"] = $_GPC["type"];
        }
        $request = pdo_getall("xc_train_discuss", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $userinfo = pdo_getall("xc_train_userinfo", array("uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$x) {
                $x["simg"] = $datalist[$x["openid"]]["avatar"];
                $x["nick"] = base64_decode($datalist[$x["openid"]]["nick"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "discuss_on":
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["pid"] = $_GPC["id"];
        $condition["content"] = $_GPC["content"];
        $condition["type"] = 1;
        if (!empty($_GPC["type"])) {
            $condition["type"] = $_GPC["type"];
        }
        $request = pdo_insert("xc_train_discuss", $condition);
        if ($request) {
            if ($condition["type"] == 1) {
                $service = pdo_get("xc_train_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
                pdo_update("xc_train_service", array("discuss" => $service["discuss"] + 1), array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
            }
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "mall_order":
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["order_type"] = 4;
        if ($_GPC["curr"] == 1) {
            $condition["status"] = 1;
            $condition["order_status"] = -1;
        } else {
            if ($_GPC["curr"] == 2) {
                $condition["status"] = 1;
                $condition["order_status"] = 1;
            } else {
                if ($_GPC["curr"] == 3) {
                    $condition["status"] = 2;
                } else {
                    $condition["status IN"] = array(1, 2);
                }
            }
        }
        $request = pdo_getall("xc_train_order", $condition, array(), '', "id DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $service = pdo_getall("xc_train_mall", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request as &$x) {
                $x["simg"] = tomedia($datalist[$x["pid"]]["simg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "mall_order_status":
        $request = pdo_update("xc_train_order", array("order_status" => $_GPC["status"]), array("uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "mall_order_detail":
        $request = pdo_get("xc_train_order", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $service = pdo_get("xc_train_mall", array("uniacid" => $uniacid, "id" => $request["pid"]));
            if ($service) {
                $request["simg"] = tomedia($service["simg"]);
            }
            $request["userinfo"] = json_decode($request["userinfo"], true);
            if (!empty($request["store"])) {
                $store = pdo_get("xc_train_school", array("uniacid" => $uniacid, "id" => $request["store"]));
                if ($store) {
                    $request["store_name"] = $store["name"];
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "mall_tui":
        $request = pdo_update("xc_train_order", array("status" => 2, "content" => $_GPC["content"]), array("uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "操作失败");
        }
        break;
}