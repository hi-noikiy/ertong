<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("service", "service_load", "detail", "porder", "discuss_post", "discuss", "address_default", "rotate", "sign", "sign_log", "group_order", "store_order", "pclass", "list", "member_discuss");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "service";
switch ($op) {
    case "service":
        $request = array();
        $class = pdo_getall("xc_beauty_service_class", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
        if ($class) {
            foreach ($class as &$c) {
                $c["bimg"] = tomedia($c["bimg"]);
            }
            $request["class"] = $class;
            $condition["uniacid"] = $uniacid;
            $condition["status"] = 1;
            if (!empty($_GPC["cid"])) {
                $condition["cid"] = $_GPC["cid"];
            } else {
                $condition["cid"] = $class[0]["id"];
            }
            $list = pdo_getall("xc_beauty_service", $condition, array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
            if ($list) {
                foreach ($list as &$x) {
                    $x["simg"] = tomedia($x["simg"]);
                    if (!empty($x["bimg"])) {
                        $x["bimg"] = json_decode($x["bimg"], true);
                        if (is_array($x["bimg"])) {
                            $i = 0;
                            while ($i < count($x["bimg"])) {
                                $x["bimg"][$i] = tomedia($x["bimg"][$i]);
                                $i++;
                            }
                        }
                    }
                    $x["is_flash"] = -1;
                    if ($x["flash_status"] == 1 && $x["flash_member"] > 0 && !empty($x["flash_member"]) && strtotime($x["flash_start"]) < time() && strtotime($x["flash_end"]) > time()) {
                        $x["is_flash"] = 1;
                        $x["fail"] = strtotime($x["flash_end"]) - time();
                    }
                }
                $request["list"] = $list;
            }
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "service_load":
        $request = pdo_getall("xc_beauty_service", array("uniacid" => $uniacid, "status" => 1, "cid" => $_GPC["cid"]), array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
                $x["is_flash"] = -1;
                if ($x["flash_status"] == 1 && $x["flash_member"] > 0 && !empty($x["flash_member"]) && strtotime($x["flash_start"]) < time() && strtotime($x["flash_end"]) > time()) {
                    $x["is_flash"] = 1;
                    $x["fail"] = strtotime($x["flash_end"]) - time();
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "detail":
        $request = pdo_get("xc_beauty_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            if (!empty($request["bimg"])) {
                $request["bimg"] = json_decode($request["bimg"], true);
                foreach ($request["bimg"] as &$x) {
                    $x = tomedia($x);
                }
            }
            $request["kind"] = json_decode($request["kind"], true);
            $request["parameter"] = json_decode($request["parameter"], true);
            $request["content"] = json_decode($request["content"], true);
            $request["content2"] = htmlspecialchars_decode($request["content2"]);
            $request["store"] = json_decode($request["store"], true);
            if (!empty($request["discuss"]) && $request["discuss"] != 0) {
                $log = pdo_getall("xc_beauty_discuss_log", array("uniacid" => $uniacid, "pid" => $request["id"]), array(), '', "createtime DESC", array(1, 5));
                if ($log) {
                    $userinfo = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid));
                    $datalist = array();
                    if ($userinfo) {
                        foreach ($userinfo as $u) {
                            $datalist[$u["openid"]] = $u;
                        }
                    }
                    foreach ($log as &$x) {
                        $x["nick"] = $datalist[$x["openid"]]["nick"];
                        $x["avatar"] = $datalist[$x["openid"]]["avatar"];
                    }
                    $request["log"] = $log;
                }
            }
            $request["is_flash"] = -1;
            if ($request["flash_status"] == 1 && $request["flash_member"] > 0 && !empty($request["flash_member"]) && strtotime($request["flash_start"]) < time() && strtotime($request["flash_end"]) > time()) {
                $request["is_flash"] = 1;
                $request["fail"] = strtotime($request["flash_end"]) - time();
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "porder":
        $request = array();
        $condition = array();
        $service = pdo_get("xc_beauty_service", array("status" => 1, "uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($service) {
            $service["simg"] = tomedia($service["simg"]);
            $service["parameter"] = json_decode($service["parameter"], true);
            $request["service"] = $service;
            if ($service["store_status"] == -1 && !empty($service["store"])) {
                $service["store"] = json_decode($service["store"], true);
                $id = array();
                foreach ($service["store"] as $y) {
                    $id[] = $y["id"];
                }
                if (count($id) > 0) {
                    $condition["id"] = $id;
                }
            }
        }
        if (!empty($condition["id"])) {
            $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"], "store IN" => $condition));
        } else {
            $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        }
        if ($userinfo && !empty($userinfo["store"])) {
            $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $userinfo["store"]));
            if ($store) {
                $request["list"] = $store;
            }
        } else {
            if (!empty($condition["id"])) {
                $log = pdo_getall("xc_beauty_order", array("uniacid" => $uniacid, "openid" => $_W["openid"], "order_type IN" => array(1, 3), "store IN" => $condition["id"]), array(), '', "createtime DESC");
            } else {
                $log = pdo_getall("xc_beauty_order", array("uniacid" => $uniacid, "openid" => $_W["openid"], "order_type IN" => array(1, 3), "store !=" => -1), array(), '', "createtime DESC");
            }
            if ($log && !empty($log[0]["store"])) {
                $id = $log[0]["store"];
                $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $id));
                if ($store) {
                    $request["list"] = $store;
                }
            } else {
                if (!empty($condition["id"])) {
                    $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid, "status" => 1, "id IN" => $condition["id"]), array(), '', "sort DESC,createtime DESC");
                } else {
                    $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
                }
                if ($store) {
                    $id = $store[0]["id"];
                    $store = pdo_get("xc_beauty_store", array("uniacid" => $uniacid, "id" => $id));
                    if ($store) {
                        $request["list"] = $store;
                    }
                }
            }
        }
        $times = pdo_getall("xc_beauty_times", array("status" => 1, "uniacid" => $uniacid));
        if ($times) {
            foreach ($times as &$t) {
                $t["content"] = json_decode($t["content"], true);
            }
            $request["times"] = $times;
        }
        $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
        $request["more_store"] = -1;
        if ($store) {
            if (count($store) > 1) {
                $request["more_store"] = 1;
            }
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "discuss_post":
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["pid"] = $_GPC["pid"];
        $condition["score"] = $_GPC["score"];
        $condition["content"] = $_GPC["content"];
        $condition["tip"] = $_GPC["tip"];
        if (!empty($_GPC["imgs"])) {
            $condition["imgs"] = htmlspecialchars_decode($_GPC["imgs"]);
        }
        $condition["type"] = 1;
        $request = pdo_insert("xc_beauty_discuss", $condition);
        if (!empty($request)) {
            pdo_update("xc_beauty_order", array("discuss" => 1), array("uniacid" => $uniacid, "out_trade_no" => $_GPC["out_trade_no"]));
            $service = pdo_get("xc_beauty_service", array("status" => 1, "id" => $_GPC["pid"], "uniacid" => $uniacid));
            if ($service) {
                $data["discuss_total"] = $service["discuss_total"] + 1;
                if ($_GPC["score"] == 1) {
                    $data["good_total"] = $service["good_total"] + 1;
                } else {
                    if ($_GPC["score"] == 2) {
                        $data["middle_total"] = $service["middle_total"] + 1;
                    } else {
                        if ($_GPC["score"] == 3) {
                            $data["bad_total"] = $service["bad_total"] + 1;
                        }
                    }
                }
                $log = pdo_get("xc_beauty_discuss_log", array("openid" => $_W["openid"], "pid" => $_GPC["pid"], "uniacid" => $uniacid));
                if (!$log) {
                    pdo_insert("xc_beauty_discuss_log", array("openid" => $_W["openid"], "pid" => $_GPC["pid"], "uniacid" => $uniacid));
                    $data["discuss"] = $service["discuss"] + 1;
                }
                pdo_update("xc_beauty_service", $data, array("status" => 1, "id" => $_GPC["pid"], "uniacid" => $uniacid));
            }
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "discuss":
        $condition["uniacid"] = $uniacid;
        $condition["pid"] = $_GPC["id"];
        $condition["status"] = 1;
        if (!empty($_GPC["curr"])) {
            $condition["score"] = $_GPC["curr"];
        }
        $condition["type"] = 1;
        $request = pdo_getall("xc_beauty_discuss", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $userinfo = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $x) {
                    $x["nick"] = base64_decode($x["nick"]);
                    $datalist[$x["openid"]] = $x;
                }
            }
            foreach ($request as &$y) {
                $y["nick"] = $datalist[$y["openid"]]["nick"];
                $y["avatar"] = $datalist[$y["openid"]]["avatar"];
                $y["imgs"] = json_decode($y["imgs"], true);
                if ($y["tip"] == 1 && !empty($y["nick"])) {
                    $y["nick"] = mb_substr($y["nick"], 0, 1, "utf-8") . "**";
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "address_default":
        $request = pdo_get("xc_beauty_address", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            $request["map"] = json_decode($request["map"], true);
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "rotate":
        $request = array();
        $tian = 0;
        $rotate = pdo_get("xc_beauty_config", array("xkey" => "rotate", "uniacid" => $uniacid));
        if ($rotate) {
            $rotate["content"] = json_decode($rotate["content"], true);
            $tian = $rotate["content"]["sign"];
            $request["signList"] = array();
            if (!empty($rotate["content"]["sign"])) {
                $i = 0;
                while ($i < $rotate["content"]["sign"]) {
                    $request["signList"][] = $i + 1;
                    $i++;
                }
            }
            $request["rotate"] = $rotate;
        }
        if (!empty($_W["openid"])) {
            $rotate_user = pdo_get("xc_beauty_rotate", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
            if ($rotate_user) {
                $rotate_user["todaySign"] = false;
                if (intval($tian) == intval($rotate_user["times"]) && $rotate_user["plan_date"] == date("Y-m-d", strtotime("-1 day"))) {
                    $rotate_user["times"] = 0;
                    $rotate_user["status"] = -1;
                    pdo_update("xc_beauty_rotate", array("times" => 0, "status" => -1), array("uniacid" => $uniacid, "openid" => $_W["openid"]));
                } else {
                    if ($rotate_user["plan_date"] == date("Y-m-d")) {
                        $rotate_user["todaySign"] = true;
                    } else {
                        if ($rotate_user["plan_date"] == date("Y-m-d", strtotime("-1 day"))) {
                            $rotate_user["status"] = -1;
                        } else {
                            $rotate_user["times"] = 0;
                            $rotate_user["status"] = -1;
                            pdo_update("xc_beauty_rotate", array("times" => 0, "status" => -1), array("uniacid" => $uniacid, "openid" => $_W["openid"]));
                        }
                    }
                }
                $request["rotate_user"] = $rotate_user;
            }
        }
        $coupon = pdo_getall("xc_beauty_coupon", array("status" => 1, "type" => 4, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($coupon) {
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
            }
            if (!empty($coupon)) {
                $request["coupon"] = $coupon;
            }
        }
        $prize = pdo_getall("xc_beauty_prize", array("status" => 1, "uniacid" => $uniacid, "member !=" => 0), array(), '', "sort DESC,createtime DESC");
        if ($prize) {
            $request["total_time"] = 0;
            foreach ($prize as &$p) {
                if ($p["times"] != 0 && !empty($p["times"])) {
                    $p["min"] = $request["total_time"];
                    $request["total_time"] = intval($request["total_time"]) + intval($p["times"]);
                    $p["max"] = $request["total_time"];
                }
            }
            $request["prize"] = $prize;
        }
        $list = pdo_getall("xc_beauty_rotate_log", array("uniacid" => $uniacid, "type IN" => array(1, 2)), array(), '', "createtime DESC", array(1, 20));
        if ($list) {
            $userinfo = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($list as &$l) {
                $l["nick"] = base64_decode($datalist[$l["openid"]]["nick"]);
                $l["zi_shu"] = mb_strlen($l["nick"], "UTF8") + mb_strlen($l["title"], "UTF8");
                if ($l["zi_shu"] > 11) {
                    $l["line"] = 30;
                } else {
                    $l["line"] = 60;
                }
            }
            $request["list"] = $list;
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "sign":
        $list = pdo_get("xc_beauty_rotate", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($list) {
            $condition["times"] = $list["times"] + 1;
            $condition["plan_date"] = date("Y-m-d");
            $rotate = pdo_get("xc_beauty_config", array("xkey" => "rotate", "uniacid" => $uniacid));
            if ($rotate) {
                $rotate["content"] = json_decode($rotate["content"], true);
                if (intval($condition["times"]) == intval($rotate["content"]["sign"])) {
                    $condition["rotated"] = intval($list["rotated"]) + 1;
                }
            }
            $request = pdo_update("xc_beauty_rotate", $condition, array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        } else {
            $condition["uniacid"] = $uniacid;
            $condition["openid"] = $_W["openid"];
            $condition["times"] = 1;
            $condition["plan_date"] = date("Y-m-d");
            $condition["status"] = -1;
            $rotate = pdo_get("xc_beauty_config", array("xkey" => "rotate", "uniacid" => $uniacid));
            if ($rotate) {
                $rotate["content"] = json_decode($rotate["content"], true);
                if (intval($condition["times"]) == intval($rotate["content"]["sign"])) {
                    $condition["rotated"] = 1;
                }
            }
            $request = pdo_insert("xc_beauty_rotate", $condition);
        }
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "签到失败");
        }
        break;
    case "sign_log":
        $rotate = pdo_get("xc_beauty_rotate", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
        if (intval($rotate["rotated"]) > 0) {
            $prize = pdo_get("xc_beauty_prize", array("id" => $_GPC["id"], "uniacid" => $uniacid));
            if ($prize && ($prize["member"] == -1 || $prize["member"] > 0)) {
                $condition["uniacid"] = $uniacid;
                $condition["openid"] = $_W["openid"];
                $condition["type"] = $prize["type"];
                $condition["title"] = $prize["name"];
                $condition["cid"] = $_GPC["id"];
                if ($condition["type"] == 1) {
                    $condition["pid"] = $prize["cid"];
                } else {
                    if ($condition["type"] == 2) {
                        $condition["status"] = -1;
                    }
                }
                $request = pdo_insert("xc_beauty_rotate_log", $condition);
                if ($request) {
                    if ($condition["type"] == 1) {
                        $coupon = pdo_get("xc_beauty_coupon", array("id" => $prize["cid"], "uniacid" => $uniacid));
                        if ($coupon["total"] != -1) {
                            pdo_update("xc_beauty_coupon", array("total" => $coupon["total"] - 1), array("id" => $_GPC["pid"], "uniacid" => $uniacid));
                        }
                        pdo_insert("xc_beauty_user_coupon", array("uniacid" => $uniacid, "openid" => $_W["openid"], "cid" => $prize["cid"]));
                    }
                    if ($prize["member"] != -1) {
                        pdo_update("xc_beauty_prize", array("member" => $prize["member"] - 1), array("id" => $_GPC["id"], "uniacid" => $uniacid));
                    }
                    pdo_update("xc_beauty_rotate", array("rotated" => intval($rotate["rotated"]) - 1), array("uniacid" => $uniacid, "openid" => $_W["openid"]));
                    return $this->result(0, "操作成功", array("status" => 1));
                } else {
                    return $this->result(1, "操作失败");
                }
            } else {
                return $this->result(1, "操作失败");
            }
        } else {
            return $this->result(1, "抽奖次数不够");
        }
        break;
    case "group_order":
        $condition["uniacid"] = $uniacid;
        $condition["status"] = -1;
        if (!empty($_GPC["pid"])) {
            $condition["pid"] = $_GPC["pid"];
        }
        $request = pdo_getall("xc_beauty_group", $condition, array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
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
                $y["is_group"] = -1;
                if ($y["openid"] == $_W["openid"]) {
                    $y["is_group"] = 1;
                }
                $y["team"] = json_decode($y["team"], true);
                if (is_array($y["team"]) && !empty($y["team"])) {
                    foreach ($y["team"] as $t) {
                        if ($t == $_W["openid"]) {
                            $y["is_group"] = 1;
                        }
                    }
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "store_order":
        $request = array();
        $id = '';
        if (!empty($_GPC["id"])) {
            $id = $_GPC["id"];
        } else {
            $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
            if ($userinfo && !empty($userinfo["store"])) {
                $id = $userinfo["store"];
            } else {
                $log = pdo_getall("xc_beauty_order", array("uniacid" => $uniacid, "openid" => $_W["openid"], "order_type" => 4, "store !=" => -1), array(), '', "createtime DESC");
                if ($log && !empty($log[0]["store"])) {
                    $id = $log[0]["store"];
                } else {
                    $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
                    if ($store) {
                        $id = $store[0]["id"];
                    }
                }
            }
        }
        if (!empty($id)) {
            $list = pdo_get("xc_beauty_store", array("status" => 1, "id" => $id, "uniacid" => $uniacid));
            if ($list) {
                $list["simg"] = tomedia($list["simg"]);
                $list["map"] = json_decode($list["map"], true);
                $request["list"] = $list;
            }
        }
        $times = pdo_getall("xc_beauty_times", array("status" => 1, "uniacid" => $uniacid));
        if ($times) {
            foreach ($times as &$t) {
                $t["content"] = json_decode($t["content"], true);
            }
            $request["times"] = $times;
        }
        $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
        $request["more_store"] = -1;
        if ($store) {
            if (count($store) > 1) {
                $request["more_store"] = 1;
            }
        }
        if (!empty($request)) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "pclass":
        $request = pdo_getall("xc_beauty_service_class", array("status" => 1, "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "list":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["cid"])) {
            $condition["cid"] = $_GPC["cid"];
        }
        if (!empty($_GPC["home"])) {
            $condition["home"] = $_GPC["home"];
        }
        if (!empty($_GPC["shop"])) {
            $condition["shop"] = $_GPC["shop"];
        }
        $request = pdo_getall("xc_beauty_service", $condition, array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
                if (!empty($x["bimg"])) {
                    $x["bimg"] = json_decode($x["bimg"], true);
                    if (is_array($x["bimg"])) {
                        $i = 0;
                        while ($i < count($x["bimg"])) {
                            $x["bimg"][$i] = tomedia($x["bimg"][$i]);
                            $i++;
                        }
                    }
                }
                $x["is_flash"] = -1;
                if ($x["flash_status"] == 1 && $x["flash_member"] > 0 && !empty($x["flash_member"]) && strtotime($x["flash_start"]) < time() && strtotime($x["flash_end"]) > time()) {
                    $x["is_flash"] = 1;
                    $x["fail"] = strtotime($x["flash_end"]) - time();
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "member_discuss":
        $condition["uniacid"] = $uniacid;
        $condition["openid"] = $_W["openid"];
        $condition["pid"] = $_GPC["id"];
        $condition["content"] = $_GPC["content"];
        $condition["status"] = 1;
        $condition["type"] = 2;
        $request = pdo_insert("xc_beauty_discuss", $condition);
        if ($request) {
            pdo_update("xc_beauty_order", array("member_discuss" => 1), array("uniacid" => $uniacid, "out_trade_no" => $_GPC["out_trade_no"]));
            $sql = "UPDATE " . tablename("xc_beauty_store_member") . " SET discuss=discuss+1 WHERE id=:id AND uniacid=:uniacid";
            pdo_query($sql, array(":id" => $_GPC["id"], ":uniacid" => $uniacid));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
}