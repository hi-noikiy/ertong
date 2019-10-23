<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("list", "edit", "savemodel", "delete", "statuschange");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "list";
$tablename = "xc_beauty_order";
pdo_update($tablename, array("status" => 2, "refund_status" => -1), array("uniacid" => $uniacid, "out_trade_no" => "1420180418000012"));
switch ($op) {
    case "list":
        $condition = array();
        $condition["status"] = 2;
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
        include $this->template("Refund/list");
        break;
    case "edit":
        if (!empty($_GPC["id"])) {
            $list = pdo_get($tablename, array("id" => $_GPC["id"]));
        } else {
            $list["status"] = 1;
        }
        include $this->template("Refund/edit");
        break;
    case "savemodel":
        $condition = array();
        $condition["uniacid"] = $uniacid;
        $condition["name"] = $_GPC["name"];
        $condition["bimg"] = $_GPC["bimg"];
        if (empty($_GPC["status"])) {
            $condition["status"] = -1;
        } else {
            $condition["status"] = $_GPC["status"];
        }
        if (empty($_GPC["sort"])) {
            $condition["sort"] = 0;
        } else {
            $condition["sort"] = $_GPC["sort"];
        }
        if (empty($_GPC["id"])) {
            $request = pdo_insert($tablename, $condition);
        } else {
            $request = pdo_update($tablename, $condition, array("id" => $_GPC["id"], "uniacid" => $uniacid));
        }
        if (!empty($request)) {
            message("操作成功", $this->createWebUrl("refund"));
        } else {
            message("操作失败", '', "warning");
        }
        break;
    case "statuschange":
        $request = pdo_update($tablename, array("menu" => 2), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => -1, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
    case "delete":
        $request = pdo_update($tablename, array("status" => 1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $json = array("status" => 1, "msg" => "操作成功");
            echo json_encode($json);
        } else {
            $json = array("status" => 0, "msg" => "操作失败");
            echo json_encode($json);
        }
        break;
}