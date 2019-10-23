<?php
defined("IN_IA") or exit("Access Denied");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = strlen($_GPC["op"]) > 1 ? $_GPC["op"] : "service";
switch ($op) {
    case "service_class":
        $request = pdo_getall("xc_train_service_class", array("status" => 1, "uniacid" => $uniacid, "type" => $_GPC["type"]), array(), '', "sort DESC,createtime DESC");
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "service":
        $condition["uniacid"] = $uniacid;
        $condition["status"] = 1;
        if (!empty($_GPC["cid"])) {
            $condition["cid"] = $_GPC["cid"];
        }
        if (!empty($_GPC["tui"])) {
            $condition["tui"] = 1;
        }
        $request = pdo_getall("xc_train_service", $condition, array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["bimg"] = tomedia($x["bimg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "detail":
        $request = pdo_get("xc_train_service", array("uniacid" => $uniacid, "status" => 1, "id" => $_GPC["id"]));
        if ($request) {
            pdo_update("xc_train_service", array("click" => intval($request["click"]) + 1), array("uniacid" => $uniacid, "status" => 1, "id" => $_GPC["id"]));
            $request["bimg"] = tomedia($request["bimg"]);
            $request["click"] = intval($request["click"]) + 1;
            $request["content2"] = htmlspecialchars_decode(str_replace("src=&quot; ", "src=&quot;", $request["content2"]));
            if (!empty($request["teacher"])) {
                $request["teacher"] = json_decode($request["teacher"], true);
                $teacher = pdo_getall("xc_train_teacher", array("status" => 1, "uniacid" => $uniacid));
                $datalist = array();
                if ($teacher) {
                    foreach ($teacher as $t) {
                        $datalist[$t["id"]] = $t;
                    }
                }
                foreach ($request["teacher"] as &$x) {
                    $x["name"] = $datalist[$x["id"]]["name"];
                    $x["simg"] = tomedia($datalist[$x["id"]]["simg"]);
                }
                $request["team"] = array();
                $team = pdo_getall("xc_train_service_team", array("status" => 1, "pid" => $request["id"], "uniacid" => $uniacid), array(), '', "createtime DESC");
                if ($team) {
                    foreach ($team as $key => $t) {
                        if (time() > strtotime($t["end_time"])) {
                            unset($team[$key]);
                        }
                        if (intval($t["member"]) >= intval($t["more_member"])) {
                            unset($team[$key]);
                        }
                    }
                    if (!empty($team)) {
                        $request["team"] = $team;
                    }
                }
            }
            $request["is_zan"] = -1;
            if (!empty($_W["openid"])) {
                $zan = pdo_get("xc_train_zan", array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid, "cid" => $request["id"]));
                if ($zan) {
                    $request["is_zan"] = 1;
                }
            }
            $request["share"] = -1;
            $config = pdo_get("xc_train_config", array("xkey" => "service_poster", "uniacid" => $uniacid));
            if ($config) {
                $config["content"] = json_decode($config["content"], true);
                if (!empty($config["content"]) && !empty($config["content"]["status"])) {
                    $request["share"] = $config["content"]["status"];
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "news":
        $request = pdo_getall("xc_train_news", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "teacher":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["cid"])) {
            $condition["cid"] = $_GPC["cid"];
        }
        $request = pdo_getall("xc_train_teacher", $condition, array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "teacher_detail":
        $request = pdo_get("xc_train_teacher", array("uniacid" => $uniacid, "id" => $_GPC["id"]));
        if ($request) {
            $request["simg"] = tomedia($request["simg"]);
            $request["is_student"] = -1;
            $request["is_zan"] = -1;
            $request["content2"] = htmlspecialchars_decode(str_replace("src=&quot; ", "src=&quot;", $request["content2"]));
            $request["member"] = array();
            $log = pdo_getall("xc_train_teacher_log", array("uniacid" => $uniacid, "tid" => $request["id"], "status" => 1), array(), '', "createtime DESC", array(1, 6));
            if ($log) {
                $userinfo = pdo_getall("xc_train_userinfo", array("status" => 1));
                $datalist = array();
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
                foreach ($log as &$l) {
                    $l["avatar"] = $datalist[$l["openid"]]["avatar"];
                    $l["nick"] = $datalist[$l["openid"]]["nick"];
                }
                $request["member"] = $log;
            }
            if (!empty($_W["openid"])) {
                $student = pdo_get("xc_train_teacher_log", array("uniacid" => $uniacid, "openid" => $_W["openid"], "tid" => $request["id"], "status" => 1));
                if ($student) {
                    $request["is_student"] = 1;
                }
                $zan = pdo_get("xc_train_teacher_log", array("uniacid" => $uniacid, "openid" => $_W["openid"], "tid" => $request["id"], "status" => 2));
                if ($zan) {
                    $request["is_zan"] = 1;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "zan":
        $log = pdo_get("xc_train_teacher_log", array("uniacid" => $uniacid, "openid" => $_W["openid"], "tid" => $_GPC["id"], "status" => $_GPC["status"]));
        if (!$log) {
            $request = pdo_insert("xc_train_teacher_log", array("uniacid" => $uniacid, "openid" => $_W["openid"], "tid" => $_GPC["id"], "status" => $_GPC["status"]));
            if ($request) {
                $teacher = pdo_get("xc_train_teacher", array("uniacid" => $uniacid, "id" => $_GPC["id"]));
                if ($teacher) {
                    if ($_GPC["status"] == 1) {
                        pdo_update("xc_train_teacher", array("students" => intval($teacher["students"]) + 1), array("uniacid" => $uniacid, "id" => $_GPC["id"]));
                    } else {
                        if ($_GPC["status"] == 2) {
                            pdo_update("xc_train_teacher", array("zan" => intval($teacher["zan"]) + 1), array("uniacid" => $uniacid, "id" => $_GPC["id"]));
                        }
                    }
                }
                return $this->result(0, "操作成功", array("status" => 1));
            } else {
                return $this->result(1, "操作失败");
            }
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "list":
        $date = time();
        $page = ($_GPC["page"] - 1) * $_GPC["pagesize"];
        $pagesize = $_GPC["pagesize"];
        $sql = "SELECT * FROM " . tablename("xc_train_service_team") . " WHERE status=1 AND uniacid=:uniacid AND type=:type AND UNIX_TIMESTAMP(end_time)>:times AND more_member>member ORDER BY createtime DESC,id DESC LIMIT {$page},{$pagesize}";
        $request = pdo_fetchall($sql, array(":uniacid" => $uniacid, ":type" => $_GPC["curr"], ":times" => $date));
        if ($request) {
            $service = pdo_getall("xc_train_service", array("status" => 1, "uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request as &$x) {
                $x["name"] = $datalist[$x["pid"]]["name"];
                $x["keshi"] = $datalist[$x["pid"]]["keshi"];
                $x["bimg"] = tomedia($datalist[$x["pid"]]["bimg"]);
                $x["price"] = $datalist[$x["pid"]]["price"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "detail_zan":
        $zan = pdo_get("xc_train_zan", array("status" => 1, "openid" => $_W["openid"], "cid" => $_GPC["id"], "uniacid" => $uniacid));
        if ($zan) {
            return $this->result(0, "已点赞");
        } else {
            $request = pdo_insert("xc_train_zan", array("openid" => $_W["openid"], "uniacid" => $uniacid, "cid" => $_GPC["id"], "status" => 1));
            if ($request) {
                $service = pdo_get("xc_train_service", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
                pdo_update("xc_train_service", array("zan" => $service["zan"] + 1), array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
                return $this->result(0, "操作成功", array("status" => 1));
            } else {
                return $this->result(0, "操作失败");
            }
        }
        break;
    case "video_class":
        $request = pdo_getall("xc_train_video_class", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "video":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["cid"])) {
            $condition["cid"] = $_GPC["cid"];
        }
        if (!empty($_GPC["id"])) {
            $condition["id !="] = $_GPC["id"];
        }
        if (!empty($_GPC["service"])) {
            $condition["pid"] = $_GPC["service"];
        }
        $request = pdo_getall("xc_train_video", $condition, array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $teacher = pdo_getall("xc_train_teacher", array("uniacid" => $uniacid));
            $datalist = array();
            if ($teacher) {
                foreach ($teacher as $t) {
                    $datalist[$t["id"]] = $t;
                }
            }
            foreach ($request as &$x) {
                $x["video"] = tomedia($x["video"]);
                $x["bimg"] = tomedia($x["bimg"]);
                $x["zan"] = $datalist[$x["teacher_id"]]["zan"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "video_detail":
        $request = pdo_get("xc_train_video", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            if ($request["type"] == 3) {
                $request["link"] = xc_txvideoUrl($request["link"], 1);
            } else {
                if ($request["type"] == 4) {
                    $request["link"] = xc_txvideoUrl($request["link"], 2);
                }
            }
            $request["is_buy"] = -1;
            $request["video"] = tomedia($request["video"]);
            $request["bimg"] = tomedia($request["bimg"]);
            $teacher = pdo_get("xc_train_teacher", array("id" => $request["teacher_id"], "uniacid" => $uniacid));
            if ($teacher) {
                $request["zan"] = $teacher["zan"];
            }
            $service = pdo_get("xc_train_service", array("id" => $request["pid"], "uniacid" => $uniacid));
            if ($service) {
                $request["service_name"] = $service["name"];
                $request["content"] = $service["content"];
                $request["content_type"] = $service["content_type"];
                $request["content2"] = htmlspecialchars_decode(str_replace("src=&quot; ", "src=&quot;", $service["content2"]));
            }
            $service_team = pdo_getall("xc_train_service_team", array("pid" => $request["pid"], "uniacid" => $uniacid));
            if ($service_team) {
                $pid = array();
                foreach ($service_team as $s) {
                    $pid[] = $s["id"];
                    $service_order = pdo_getall("xc_train_order", array("status" => 1, "uniacid" => $uniacid, "pid IN" => $pid, "openid" => $_W["openid"], "order_type IN" => array(1, 2)));
                    if ($service_order) {
                        $request["is_buy"] = 1;
                    }
                }
            }
            if ($request["is_buy"] == -1) {
                if ($request["price"] == 0) {
                    $request["is_buy"] = 1;
                } else {
                    $order = pdo_get("xc_train_order", array("status" => 1, "uniacid" => $uniacid, "pid" => $request["id"], "openid" => $_W["openid"], "order_type" => 3));
                    if ($order) {
                        $request["is_buy"] = 1;
                    }
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "sign":
        $service = pdo_getall("xc_train_service", array("status" => 1, "price !=" => '', "uniacid" => $uniacid));
        if ($service) {
            $pid = array();
            $datalist = array();
            foreach ($service as $s) {
                $pid[] = $s["id"];
                $datalist[$s["id"]] = $s;
            }
            $date = time();
            $sql = "SELECT * FROM " . tablename("xc_train_service_team") . " WHERE status=1 AND uniacid=:uniacid AND UNIX_TIMESTAMP(end_time)>:times AND more_member>member ORDER BY createtime DESC,id DESC";
            $team = pdo_fetchall($sql, array(":uniacid" => $uniacid, ":times" => $date));
            if ($team) {
                $id = array();
                foreach ($team as $t) {
                    $id[] = $t["id"];
                }
                $request = pdo_getall("xc_train_service_team", array("status" => 1, "uniacid" => $uniacid, "id IN" => $id, "pid IN" => $pid), array(), '', "createtime DESC,id DESC", array($_GPC["page"], $_GPC["pagesize"]));
                if ($request) {
                    foreach ($request as &$x) {
                        $x["name"] = $datalist[$x["pid"]]["name"];
                    }
                    return $this->result(0, "操作成功", $request);
                } else {
                    return $this->result(0, "操作失败");
                }
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "cut":
        $service = pdo_getall("xc_train_service", array("status" => 1, "uniacid" => $uniacid));
        if ($service) {
            $service_list = array();
            $ids = array();
            foreach ($service as $s) {
                $service_list[$s["id"]] = $s;
                $ids[] = $s["id"];
            }
            $request = pdo_getall("xc_train_cut", array("status" => 1, "pid IN" => $ids, "uniacid" => $uniacid), array(), '', "sort DESC,id DESC", array($_GPC["page"], $_GPC["pagesize"]));
            if ($request) {
                foreach ($request as &$x) {
                    $x["bimg"] = tomedia($service_list[$x["pid"]]["bimg"]);
                    $x["name"] = $service_list[$x["pid"]]["name"];
                    $x["end"] = -1;
                    if (time() > strtotime($x["end_time"])) {
                        $x["end"] = 1;
                    }
                    if ($x["is_member"] >= $x["member"]) {
                        $x["end"] = 1;
                    }
                }
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "cut_detail":
        $request = pdo_get("xc_train_cut", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["is_member"] = intval($request["is_member"]);
            $request["member"] = intval($request["member"]);
            $request["end"] = -1;
            $request["fail"] = 0;
            if (time() < strtotime($request["end_time"])) {
                $request["fail"] = strtotime($request["end_time"]) - time();
            } else {
                $request["end"] = 1;
            }
            if ($request["is_member"] >= $request["member"]) {
                $request["end"] = 1;
            }
            $service = pdo_get("xc_train_service", array("id" => $request["pid"], "uniacid" => $uniacid));
            if ($service) {
                $request["bimg"] = tomedia($service["bimg"]);
                $request["name"] = $service["name"];
                $request["content_type"] = $service["content_type"];
                $request["content"] = $service["content"];
                $request["content2"] = htmlspecialchars_decode(str_replace("src=&quot; ", "src=&quot;", $service["content2"]));
                if (!empty($service["teacher"])) {
                    $service["teacher"] = json_decode($service["teacher"], true);
                    $teacher = pdo_getall("xc_train_teacher", array("status" => 1, "uniacid" => $uniacid));
                    $datalist = array();
                    if ($teacher) {
                        foreach ($teacher as $t) {
                            $datalist[$t["id"]] = $t;
                        }
                    }
                    foreach ($service["teacher"] as &$x) {
                        $x["name"] = $datalist[$x["id"]]["name"];
                        $x["simg"] = tomedia($datalist[$x["id"]]["simg"]);
                    }
                    $request["teacher"] = $service["teacher"];
                }
            }
            $userinfo = pdo_get("xc_train_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
            if ($userinfo) {
                $request["userinfo"] = $userinfo;
            }
            $order = pdo_get("xc_train_cut_order", array("openid" => $_W["openid"], "cid" => $_GPC["id"], "uniacid" => $uniacid));
            if ($order) {
                $order["pro"] = 0;
                if (floatval($request["price"]) != 0) {
                    $order["pro"] = ($request["price"] - $order["price"]) / ($request["price"] - $request["cut_price"]) * 100;
                }
                $request["order"] = $order;
            }
            $request["bang"] = array();
            $bang = pdo_getall("xc_train_cut_order", array("cid" => $_GPC["id"], "uniacid" => $uniacid), array(), '', "id DESC", array(1, 20));
            if ($bang) {
                $user = pdo_getall("xc_train_userinfo", array("uniacid" => $uniacid));
                $user_list = array();
                if ($user) {
                    foreach ($user as $u) {
                        $u["nick"] = base64_decode($u["nick"]);
                        $user_list[$u["openid"]] = $u;
                    }
                }
                $sql = "SELECT count(*) as dao,openid FROM " . tablename("xc_train_cut_log") . " WHERE cid=:cid AND uniacid=:uniacid GROUP BY openid";
                $dao = pdo_fetchall($sql, array(":cid" => $_GPC["id"], ":uniacid" => $uniacid));
                $dao_list = array();
                if ($dao) {
                    foreach ($dao as $d) {
                        $dao_list[$d["openid"]] = $d;
                    }
                }
                foreach ($bang as &$b) {
                    $b["nick"] = $user_list[$b["openid"]]["nick"];
                    $b["avatar"] = $user_list[$b["openid"]]["avatar"];
                    $b["dao"] = $dao_list[$b["openid"]]["dao"];
                }
                $request["bang"] = $bang;
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "cut_price":
        if (!empty($_W["openid"])) {
            $sql = "SELECT * FROM " . tablename("xc_train_cut") . " WHERE status=1 AND uniacid=:uniacid AND id=:id AND is_member<member AND unix_timestamp(end_time)>:times";
            $service = pdo_fetch($sql, array(":uniacid" => $uniacid, ":id" => $_GPC["id"], ":times" => time()));
            if ($service) {
                $price = $service["price"];
                $num = rand(floatval($service["min_price"]) * 100, floatval($service["max_price"]) * 100);
                $cut_price = $num / 100;
                $price = floatval($price) - $cut_price;
                if (!$price > floatval($service["cut_price"])) {
                    $cut_price = floatval($service["price"]) - floatval($service["cut_price"]);
                    $price = $service["cut_price"];
                    $condition["is_min"] = 1;
                }
                $condition["uniacid"] = $uniacid;
                $condition["openid"] = $_W["openid"];
                $condition["cid"] = $service["id"];
                $condition["price"] = $price;
                $request = pdo_insert("xc_train_cut_order", $condition);
                if ($request) {
                    pdo_insert("xc_train_cut_log", array("uniacid" => $uniacid, "openid" => $_W["openid"], "cid" => $service["id"], "price" => $cut_price, "cut_openid" => $_W["openid"]));
                    $sql = "UPDATE " . tablename("xc_train_cut") . " SET join_member=join_member+:member WHERE status=1 AND id=:id AND uniacid=:uniacid";
                    pdo_query($sql, array(":member" => 1, ":id" => $_GPC["id"], ":uniacid" => $uniacid));
                    return $this->result(0, "操作成功", array("cut_price" => $cut_price));
                } else {
                    return $this->result(1, "失败");
                }
            } else {
                return $this->result(1, "已结束");
            }
        } else {
            return $this->result(1, "请先登录");
        }
        break;
    case "cut_log":
        $request = pdo_getall("xc_train_cut_log", array("openid" => $_W["openid"], "cid" => $_GPC["id"], "uniacid" => $uniacid), array(), '', "id DESC");
        if ($request) {
            $user = pdo_getall("xc_train_userinfo", array("uniacid" => $uniacid));
            $user_list = array();
            if ($user) {
                foreach ($user as $u) {
                    $u["nick"] = base64_decode($u["nick"]);
                    $user_list[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$x) {
                $x["nick"] = $user_list[$x["cut_openid"]]["nick"];
                $x["avatar"] = $user_list[$x["cut_openid"]]["avatar"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "cut_price2":
        if (!empty($_W["openid"])) {
            $sql = "SELECT * FROM " . tablename("xc_train_cut") . " WHERE status=1 AND uniacid=:uniacid AND id=:id AND is_member<member AND unix_timestamp(end_time)>:times";
            $service = pdo_fetch($sql, array(":uniacid" => $uniacid, ":id" => $_GPC["id"], ":times" => time()));
            if ($service) {
                $order = pdo_get("xc_train_cut_order", array("openid" => $_GPC["openid"], "cid" => $_GPC["id"], "status" => -1, "is_min" => -1, "uniacid" => $uniacid));
                if ($order) {
                    $cut_log = pdo_get("xc_train_cut_log", array("openid" => $_GPC["openid"], "cid" => $_GPC["id"], "uniacid" => $uniacid, "cut_openid" => $_W["openid"]));
                    if ($cut_log) {
                        return $this->result(1, "已助力");
                    } else {
                        $price = $order["price"];
                        $num = rand(floatval($service["min_price"]) * 100, floatval($service["max_price"]) * 100);
                        $cut_price = $num / 100;
                        $price = floatval($price) - $cut_price;
                        if (!$price > floatval($service["cut_price"])) {
                            $cut_price = floatval($order["price"]) - floatval($service["cut_price"]);
                            $price = $service["cut_price"];
                            $condition["is_min"] = 1;
                        }
                        $condition["price"] = $price;
                        $request = pdo_update("xc_train_cut_order", $condition, array("openid" => $_GPC["openid"], "cid" => $_GPC["id"], "uniacid" => $uniacid));
                        if ($request) {
                            pdo_insert("xc_train_cut_log", array("uniacid" => $uniacid, "openid" => $_GPC["openid"], "cid" => $service["id"], "price" => $cut_price, "cut_openid" => $_W["openid"]));
                            $user = pdo_get("xc_train_userinfo", array("openid" => $_GPC["openid"], "uniacid" => $uniacid));
                            if ($user) {
                                $user["nick"] = base64_decode($user["nick"]);
                            }
                            return $this->result(0, "操作成功", array("cut_price" => $cut_price, "cut_user" => $user["nick"]));
                        } else {
                            return $this->result(1, "失败");
                        }
                    }
                } else {
                    return $this->result(0, "失败");
                }
            } else {
                return $this->result(1, "已结束");
            }
        } else {
            return $this->result(1, "请先登录");
        }
        break;
    case "cut_user":
        $service = pdo_getall("xc_train_service", array("status" => 1, "uniacid" => $uniacid));
        if ($service) {
            $service_list = array();
            $ids = array();
            foreach ($service as $s) {
                $service_list[$s["id"]] = $s;
                $ids[] = $s["id"];
            }
            $order = pdo_getall("xc_train_cut_order", array("openid" => $_W["openid"], "uniacid" => $uniacid));
            if ($order) {
                $order_list = array();
                $idt = array();
                foreach ($order as &$o) {
                    $order_list[$o["cid"]] = $o;
                    $idt[] = $o["cid"];
                }
                $request = pdo_getall("xc_train_cut", array("status" => 1, "pid IN" => $ids, "uniacid" => $uniacid, "id IN" => $idt), array(), '', "sort DESC,id DESC", array($_GPC["page"], $_GPC["pagesize"]));
                if ($request) {
                    foreach ($request as &$x) {
                        $x["bimg"] = tomedia($service_list[$x["pid"]]["bimg"]);
                        $x["name"] = $service_list[$x["pid"]]["name"];
                        $x["end"] = -1;
                        $x["hour"] = 0;
                        $x["min"] = 0;
                        $x["second"] = 0;
                        $x["fail"] = 0;
                        if (time() > strtotime($x["end_time"])) {
                            $x["end"] = 1;
                        } else {
                            $x["fail"] = strtotime($x["end_time"]) - time();
                        }
                        if ($x["is_member"] >= $x["member"]) {
                            $x["end"] = 1;
                        }
                        $x["order"] = $order_list[$x["id"]];
                    }
                    return $this->result(0, "操作成功", $request);
                } else {
                    return $this->result(0, "操作失败");
                }
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "mall":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["cid"])) {
            $condition["cid"] = $_GPC["cid"];
        }
        $request = pdo_getall("xc_train_mall", $condition, array(), '', "sort DESC,id DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "mall_detail":
        $request = pdo_get("xc_train_mall", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["simg"] = tomedia($request["simg"]);
            $request["bimg"] = explode(",", $request["bimg"]);
            if (!empty($request["bimg"]) && is_array($request["bimg"])) {
                foreach ($request["bimg"] as &$bb) {
                    $bb = tomedia($bb);
                }
            }
            $request["format"] = json_decode($request["format"], true);
            if (!empty($_GPC["member"])) {
                $amount = 0;
                if ($_GPC["format"] == -1) {
                    $amount = floatval($request["price"]) * intval($_GPC["member"]);
                } else {
                    $amount = floatval($request["format"][$_GPC["format"]]["price"]) * intval($_GPC["member"]);
                }
                $request["coupon"] = array();
                $coupon = pdo_getall("xc_train_user_coupon", array("status" => -1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
                if ($coupon) {
                    $coupon_id = array();
                    foreach ($coupon as $c) {
                        $coupon_id[] = $c["cid"];
                    }
                    $user_coupon = pdo_getall("xc_train_coupon", array("status" => 1, "condition <=" => $amount, "id IN" => $coupon_id, "uniacid" => $uniacid));
                    if ($user_coupon) {
                        foreach ($user_coupon as $key => $uc) {
                            $uc["times"] = json_decode($uc["times"], true);
                            if (time() > strtotime($uc["times"]["start"]) && time() < strtotime($uc["times"]["end"])) {
                                $user_coupon[$key]["times"] = $uc["times"];
                            } else {
                                unset($user_coupon[$key]);
                            }
                        }
                    }
                    $request["coupon"] = $user_coupon;
                }
            }
            $store = pdo_getall("xc_train_school", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,id DESC");
            if ($store) {
                foreach ($store as &$x) {
                    $x["simg"] = tomedia($x["simg"]);
                }
                $request["store"] = $store;
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "address":
        $request = pdo_get("xc_train_address", array("uniacid" => $uniacid, "status" => 1, "openid" => $_W["openid"]));
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
}
function xc_txvideoUrl($url, $type = 1)
{
    if (strpos($url, "http") !== false) {
        $vid = substr($url, 24, 11);
    } else {
        $vid = $url;
    }
    load()->func("communication");
    $response = ihttp_request("http://vv.video.qq.com/getinfo?vids=" . $vid . "&platform=101001&charge=0&otype=json");
    $response = strstr($response["content"], "{");
    $response = substr($response, 0, strlen($response) - 1);
    $response = json_decode($response, true);
    $url = $response["vl"]["vi"][0]["ul"]["ui"][0]["url"];
    if ($type == 1) {
        $fn = $response["vl"]["vi"][0]["fn"];
        $vkey = $response["vl"]["vi"][0]["fvkey"];
        $trueurl = $url . $fn . "?vkey=" . $vkey;
        return $trueurl;
    } else {
        if ($type == 2) {
            $response_2 = ihttp_request("http://vv.video.qq.com/getkey?format=2&otype=json&vt=150&vid=" . $vid . "&ran=0%2E9477521511726081&charge=0&filename=" . $vid . ".mp4&platform=11");
            $response_2 = strstr($response_2["content"], "{");
            $response_2 = substr($response_2, 0, strlen($response_2) - 1);
            $response_2 = json_decode($response_2, true);
            $vkey_2 = $response_2["key"];
            $fn_2 = $response_2["filename"];
            $trueurl = $url . $fn_2 . "?vkey=" . $vkey_2;
            return $trueurl;
        } else {
            return $url;
        }
    }
}