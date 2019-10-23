<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("base", "index", "userinfo", "set_coupon", "coupon", "card", "score_coupon", "team", "share", "store", "store_detail", "store_member", "store_service", "article", "member_detail", "zan", "discuss");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "base";
switch ($op) {
    case "userinfo":
        if (!empty($_W["openid"])) {
            $userinfo = pdo_get("xc_beauty_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
            if ($userinfo) {
                $condition = array();
                if (!empty($_GPC["avatarUrl"])) {
                    $condition["avatar"] = $_GPC["avatarUrl"];
                }
                if (!empty($_GPC["nickName"])) {
                    $condition["nick"] = base64_encode($_GPC["nickName"]);
                }
                if (!empty($condition)) {
                    pdo_update("xc_beauty_userinfo", $condition, array("openid" => $_W["openid"], "uniacid" => $uniacid));
                }
            } else {
                if (!empty($_GPC["avatarUrl"])) {
                    $condition["avatar"] = $_GPC["avatarUrl"];
                }
                if (!empty($_GPC["nickName"])) {
                    $condition["nick"] = base64_encode($_GPC["nickName"]);
                }
                $condition["uniacid"] = $uniacid;
                $condition["openid"] = $_W["openid"];
                if (!empty($_GPC["scene"])) {
                    $one = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $_GPC["scene"], "uniacid" => $uniacid));
                    if ($one) {
                        $one["share"] = -1;
                        $share_config = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "share"));
                        if ($share_config) {
                            $share_config["content"] = json_decode($share_config["content"], true);
                            if (!empty($share_config["content"]) && !empty($share_config["content"]["amount"])) {
                                $sql = "SELECT sum(o_amount) FROM " . tablename("xc_beauty_order") . " WHERE openid=:openid AND uniacid=:uniacid AND status=1 AND order_type!=2";
                                $one["share_amount"] = 0;
                                $share_amount = pdo_fetchcolumn($sql, array(":openid" => $one["openid"], ":uniacid" => $uniacid));
                                if ($share_amount) {
                                    $one["share_amount"] = $share_amount;
                                }
                                if (floatval($one["share_amount"]) >= floatval($share_config["content"]["amount"])) {
                                    $one["share"] = 1;
                                }
                            } else {
                                $one["share"] = 1;
                            }
                        }
                        if ($one["share"] == 1) {
                            $condition["share"] = $_GPC["scene"];
                        }
                    }
                }
                $store = pdo_getall("xc_beauty_store", array("status" => 1, "uniacid" => $uniacid));
                if ($store) {
                    if (count($store) == 1) {
                        $condition["store"] = $store[0]["id"];
                    }
                }
                $add = pdo_insert("xc_beauty_userinfo", $condition);
                if ($add && !empty($condition["share"])) {
                    $one = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $_GPC["scene"], "uniacid" => $uniacid));
                    if ($one) {
                        pdo_update("xc_beauty_userinfo", array("level_one" => $one["level_one"] + 1), array("status" => 1, "openid" => $_GPC["scene"], "uniacid" => $uniacid));
                        if (!empty($one["share"])) {
                            $two = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $one["share"], "uniacid" => $uniacid));
                            if ($two) {
                                pdo_update("xc_beauty_userinfo", array("level_two" => $two["level_two"] + 1), array("status" => 1, "openid" => $one["share"], "uniacid" => $uniacid));
                                if (!empty($two["share"])) {
                                    $three = pdo_get("xc_beauty_userinfo", array("status" => 1, "openid" => $two["share"], "uniacid" => $uniacid));
                                    if ($three) {
                                        pdo_update("xc_beauty_userinfo", array("level_three" => $three["level_three"] + 1), array("status" => 1, "openid" => $two["share"], "uniacid" => $uniacid));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $request = pdo_get("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
            if ($request) {
                $request["nick"] = base64_decode($request["nick"]);
                $request["card_id"] = strtotime($request["createtime"]);
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "请先授权");
            }
        } else {
            return $this->result(0, "操作成功");
        }
        break;
    case "base":
        $request = array();
        $config = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "web"));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["footer"])) {
                foreach ($config["content"]["footer"] as &$x) {
                    $x["icon"] = tomedia($x["icon"]);
                    $x["select"] = tomedia($x["select"]);
                }
            }
            if (!empty($config["content"]["online_simg"])) {
                $config["content"]["online_simg"] = tomedia($config["content"]["online_simg"]);
            }
            if (!empty($config["content"]["mobile_simg"])) {
                $config["content"]["mobile_simg"] = tomedia($config["content"]["mobile_simg"]);
            }
            if (!empty($config["content"]["coupon_bg"])) {
                $config["content"]["coupon_bg"] = tomedia($config["content"]["coupon_bg"]);
            }
            if (!empty($config["content"]["rotate_bg"])) {
                $config["content"]["rotate_bg"] = tomedia($config["content"]["rotate_bg"]);
            }
            if (!empty($config["content"]["share_index_img"])) {
                $config["content"]["share_index_img"] = tomedia($config["content"]["share_index_img"]);
            }
            if (!empty($config["content"]["share_service_img"])) {
                $config["content"]["share_service_img"] = tomedia($config["content"]["share_service_img"]);
            }
            if (!empty($config["content"]["share_group_img"])) {
                $config["content"]["share_group_img"] = tomedia($config["content"]["share_group_img"]);
            }
            $request["config"] = $config;
        }
        $theme = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "theme"));
        if ($theme) {
            $theme["content"] = json_decode($theme["content"], true);
            if (!empty($theme["content"]["icon"])) {
                foreach ($theme["content"]["icon"] as &$x) {
                    $x = tomedia($x);
                }
            }
            $request["theme"] = $theme;
        }
        $map = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "map"));
        if ($map) {
            $map["content"] = json_decode($map["content"], true);
            $request["map"] = $map;
        }
        $share = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "share"));
        if ($share) {
            $share["content"] = json_decode($share["content"], true);
            if (empty($share["content"]["status"])) {
                $share["content"]["status"] = 1;
            }
            $request["share"] = $share;
        }
        $audit = pdo_get("xc_beauty_config", array("xkey" => "audit", "uniacid" => $uniacid));
        if ($audit) {
            $audit["content"] = json_decode($audit["content"], true);
            if (!empty($audit["content"])) {
                $request["audit"] = $audit["content"];
            }
        }
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "index":
        $request = array();
        $sort = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "sort"));
        if ($sort) {
            $sort["content"] = json_decode($sort["content"], true);
            if (!empty($sort["content"]["sort"])) {
                $service = pdo_getall("xc_beauty_service", array("status" => 1, "uniacid" => $uniacid));
                $service_list = array();
                foreach ($service as $s) {
                    $service_list[$s["id"]] = $s;
                }
                foreach ($sort["content"]["sort"] as &$so) {
                    if ($so["name"] == "display") {
                        foreach ($so["value"] as &$sos) {
                            $sos["price"] = $service_list[$sos["id"]]["price"];
                            $sos["simg"] = tomedia($service_list[$sos["id"]]["simg"]);
                            $sos["discuss_total"] = $service_list[$sos["id"]]["discuss_total"];
                            $sos["group_status"] = $service_list[$sos["id"]]["group_status"];
                            $sos["home"] = $service_list[$sos["id"]]["home"];
                            $sos["shop"] = $service_list[$sos["id"]]["shop"];
                            $sos["flash_status"] = $service_list[$sos["id"]]["flash_status"];
                        }
                    }
                }
                $request["sort"] = $sort["content"]["sort"];
            }
        }
        $banner = pdo_getall("xc_beauty_banner", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($banner) {
            foreach ($banner as &$x) {
                $x["bimg"] = tomedia($x["bimg"]);
            }
            $request["banner"] = $banner;
        }
        $map = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "map"));
        if ($map) {
            $map["content"] = json_decode($map["content"], true);
            $userinfo = pdo_get("xc_beauty_userinfo", array("uniacid" => $uniacid, "openid" => $_W["openid"]));
            if ($userinfo && !empty($userinfo["store"])) {
                $store = pdo_get("xc_beauty_store", array("status" => 1, "id" => $userinfo["store"], "uniacid" => $uniacid));
                if ($store) {
                    $map["content"]["store_list"] = $store;
                }
            }
            if (empty($map["content"]["store_list"])) {
                $store = pdo_getall("xc_beauty_store", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
                if ($store) {
                    $map["content"]["store_list"] = $store[0];
                }
            }
            $request["map"] = $map;
        }
        $nav = pdo_getall("xc_beauty_nav", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
        if ($nav) {
            foreach ($nav as &$n) {
                $n["simg"] = tomedia($n["simg"]);
            }
            $request["nav"] = $nav;
        }
        $coupon = pdo_getall("xc_beauty_coupon", array("status" => 1, "type" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($coupon) {
            $datalist = array();
            if (!empty($_W["openid"])) {
                $user_coupon = pdo_getall("xc_beauty_user_coupon", array("status" => -1, "openid" => $_W["openid"], "uniacid" => $uniacid));
                if ($user_coupon) {
                    foreach ($user_coupon as $up) {
                        $datalist[$up["cid"]] = $up;
                    }
                }
            }
            foreach ($coupon as $key => $cc) {
                $coupon[$key]["user"] = -1;
                if (!empty($datalist[$cc["id"]])) {
                    $coupon[$key]["user"] = 1;
                }
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
        $group = pdo_getall("xc_beauty_service", array("status" => 1, "group_status" => 1, "group_index" => 1, "uniacid" => $uniacid, "flash_status" => -1), array(), '', "sort DESC,createtime DESC");
        if ($group) {
            foreach ($group as &$g) {
                $g["simg"] = tomedia($g["simg"]);
            }
            $request["group"] = $group;
        }
        $time = time();
        $sql = "SELECT * FROM " . tablename("xc_beauty_service") . " WHERE status=1 AND uniacid=:uniacid AND flash_status=1 AND UNIX_TIMESTAMP(flash_start)<:times AND UNIX_TIMESTAMP(flash_end)>:times AND flash_index=1 AND flash_member!=0 ORDER BY sort DESC,createtime DESC";
        $flash = pdo_fetchall($sql, array(":uniacid" => $uniacid, ":service" => $_GPC["service"], ":times" => $time));
        if ($flash) {
            foreach ($flash as &$f) {
                $f["simg"] = tomedia($f["simg"]);
                $f["fail"] = strtotime($f["flash_end"]) - time();
            }
            $request["flash"] = $flash;
        }
        $pclass = pdo_getall("xc_beauty_service_class", array("status" => 1, "index" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($pclass) {
            foreach ($pclass as &$pp) {
                $pp["bimg"] = tomedia($pp["bimg"]);
            }
            $request["pclass"] = $pclass;
        }
        $ad = pdo_get("xc_beauty_config", array("xkey" => "ad", "uniacid" => $uniacid));
        if ($ad) {
            $ad["content"] = json_decode($ad["content"], true);
            if ($ad["content"]["status"] == 1 && !empty($ad["content"]["list"])) {
                $request["ad"] = $ad["content"]["list"];
            }
        }
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "set_coupon":
        $coupon = pdo_get("xc_beauty_coupon", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($coupon) {
            if (!empty($coupon["times"])) {
                $coupon["times"] = json_decode($coupon["times"], true);
                $coupon["failtime"] = date("Y.m.d", strtotime($coupon["times"]["end"]));
            }
            if ($coupon["total"] != 0) {
                $user = pdo_get("xc_beauty_user_coupon", array("status" => -1, "cid" => $_GPC["id"], "openid" => $_W["openid"], "uniacid" => $uniacid));
                if ($user) {
                    $coupon["user"] = 1;
                    return $this->result(0, "操作成功", $coupon);
                } else {
                    $request = pdo_insert("xc_beauty_user_coupon", array("uniacid" => $uniacid, "cid" => $_GPC["id"], "openid" => $_W["openid"]));
                    if ($request) {
                        if ($coupon["total"] != -1) {
                            pdo_update("xc_beauty_coupon", array("total" => intval($coupon["total"]) - 1), array("uniacid" => $uniacid, "id" => $coupon["id"]));
                        }
                        $coupon["user"] = -1;
                        return $this->result(0, "操作成功", $coupon);
                    } else {
                        return $this->result(1, "领取失败");
                    }
                }
            } else {
                return $this->result(1, "领取失败");
            }
        } else {
            return $this->result(1, "领取失败");
        }
        break;
    case "coupon":
        $coupon = pdo_getall("xc_beauty_coupon", array("status" => 1, "uniacid" => $uniacid));
        if ($coupon) {
            $coupon_id = array();
            $datalist = array();
            foreach ($coupon as $ccc) {
                if ($ccc["total"] != 0 && !empty($ccc["times"])) {
                    $ccc["times"] = json_decode($ccc["times"], true);
                    $ccc["failtime"] = date("Y.m.d", strtotime($ccc["times"]["end"]));
                    if (strtotime($ccc["times"]["start"]) < time() && time() < strtotime($ccc["times"]["end"])) {
                        $coupon_id[] = $ccc["id"];
                        $datalist[$ccc["id"]] = $ccc;
                    }
                }
            }
            if (!empty($coupon_id)) {
                $condition["status"] = $_GPC["curr"];
                $condition["openid"] = $_W["openid"];
                $condition["cid IN"] = $coupon_id;
                $request = pdo_getall("xc_beauty_user_coupon", $condition, array(), '', "createtime DESC");
                if ($request) {
                    foreach ($request as &$x) {
                        $x["coupon"] = $datalist[$x["cid"]];
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
    case "card":
        $request = pdo_get("xc_beauty_config", array("uniacid" => $uniacid, "xkey" => "card"));
        if ($request) {
            $request["content"] = json_decode($request["content"], true);
            if (!empty($request["content"]["score_icon"])) {
                $request["content"]["score_icon"] = tomedia($request["content"]["score_icon"]);
            }
            if (!empty($request["content"]["discount_icon"])) {
                $request["content"]["discount_icon"] = tomedia($request["content"]["discount_icon"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "score_coupon":
        $request = pdo_getall("xc_beauty_coupon", array("status" => 1, "type" => 3, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($request) {
            $datalist = array();
            if (!empty($_W["openid"])) {
                $user_coupon = pdo_getall("xc_beauty_user_coupon", array("status" => -1, "openid" => $_W["openid"], "uniacid" => $uniacid));
                if ($user_coupon) {
                    foreach ($user_coupon as $up) {
                        $datalist[$up["cid"]] = $up;
                    }
                }
            }
            foreach ($request as $key => $cc) {
                if ($cc["total"] == 0) {
                    unset($request[$key]);
                }
                if (!empty($cc["times"])) {
                    $cc["times"] = json_decode($cc["times"], true);
                    if (!(strtotime($cc["times"]["start"]) < time() && strtotime($cc["times"]["end"]) > time())) {
                        unset($request[$key]);
                    }
                }
                $request[$key]["user"] = -1;
                if (!empty($datalist[$cc["id"]])) {
                    $request[$key]["user"] = 1;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "share":
        $request = pdo_get("xc_beauty_config", array("xkey" => "share", "uniacid" => $uniacid));
        if ($request) {
            $request["content"] = json_decode($request["content"], true);
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "team":
        $userinfo = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid));
        $datalist = array();
        if ($userinfo) {
            foreach ($userinfo as $u) {
                $datalist[$u["openid"]] = $u;
            }
        }
        $one = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "share" => $_W["openid"]));
        $one_data = array();
        if ($one) {
            foreach ($one as $o) {
                $one_data[] = $o["openid"];
            }
        }
        $two = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "share IN" => $one_data));
        $two_data = array();
        if ($two) {
            foreach ($two as $t) {
                $two_data[] = $t["openid"];
            }
        }
        if ($_GPC["curr"] == 1) {
            $request = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "share" => $_W["openid"]), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        } else {
            if ($_GPC["curr"] == 2) {
                if (!empty($one_data)) {
                    $request = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "share" => $one_data), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
                }
            } else {
                if ($_GPC["curr"] == 3) {
                    if (!empty($two_data)) {
                        $request = pdo_getall("xc_beauty_userinfo", array("status" => 1, "uniacid" => $uniacid, "share" => $two_data), array(), '', "createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
                    }
                }
            }
        }
        if (!empty($request)) {
            foreach ($request as &$x) {
                $x["nick"] = base64_decode($x["nick"]);
                $x["share_nick"] = base64_decode($datalist[$x["share"]]["nick"]);
            }
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
    case "store_detail":
        $request = pdo_get("xc_beauty_store", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["simg"] = tomedia($request["simg"]);
            $request["content"] = json_decode($request["content"], true);
            $request["total"] = 0;
            $total = pdo_getall("xc_beauty_store_member", array("status" => 1, "uniacid" => $uniacid, "cid" => $request["id"]));
            if ($total) {
                $request["total"] = count($total);
            }
            $member = pdo_getall("xc_beauty_store_member", array("status" => 1, "uniacid" => $uniacid, "cid" => $request["id"]), array(), '', "sort DESC,createtime DESC", array(1, 4));
            if ($member) {
                foreach ($member as &$x) {
                    $x["simg"] = tomedia($x["simg"]);
                }
                $request["member"] = $member;
            }
            $request["map"] = json_decode($request["map"], true);
            $store = pdo_getall("xc_beauty_store", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC");
            $request["more_store"] = -1;
            if ($store) {
                if (count($store) > 1) {
                    $request["more_store"] = 1;
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "store_member":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if (!empty($_GPC["id"])) {
            if ($_GPC["id"] == -1) {
                $condition["cid"] = 0;
            } else {
                $condition["cid"] = $_GPC["id"];
            }
            if (!empty($_GPC["service"])) {
                $condition["service LIKE"] = "%\"id\":\"" . $_GPC["service"] . "\"%";
            }
        }
        $request = pdo_getall("xc_beauty_store_member", $condition, array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
                if (!empty($x["service"])) {
                    $x["service"] = json_decode($x["service"], true);
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "store_service":
        $condition["status"] = 1;
        $condition["uniacid"] = $uniacid;
        if ($_GPC["id"] == -1) {
            $condition["cid"] = 0;
        } else {
            $condition["cid"] = $_GPC["id"];
        }
        if (!empty($_GPC["member"]) && $_GPC["member"] != -2) {
            $condition["id"] = $_GPC["member"];
        }
        $member = pdo_getall("xc_beauty_store_member", $condition);
        if ($member) {
            $service = array();
            foreach ($member as $x) {
                $x["service"] = json_decode($x["service"], true);
                if (!empty($x["service"]) && is_array($x["service"])) {
                    foreach ($x["service"] as $r) {
                        $service[] = $r["id"];
                    }
                }
            }
            if (!empty($service)) {
                $data["status"] = 1;
                $data["uniacid"] = $uniacid;
                $data["id IN"] = $service;
                if (!empty($_GPC["service_type"])) {
                    if ($_GPC["service_type"] == 1) {
                        $data["home"] = 1;
                    } else {
                        if ($_GPC["service_type"] == 2) {
                            $data["shop"] = 1;
                        }
                    }
                }
                $request = pdo_getall("xc_beauty_store_service", $data, array(), '', "sort DESC,createtime DESC");
                if ($request) {
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
    case "article":
        $request = pdo_get("xc_beauty_article", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["content"] = htmlspecialchars_decode($request["content"]);
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "member_detail":
        $request = pdo_get("xc_beauty_store_member", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["simg"] = tomedia($request["simg"]);
            $store = pdo_get("xc_beauty_store", array("id" => $request["cid"], "uniacid" => $uniacid));
            if ($store) {
                $store["simg"] = tomedia($store["simg"]);
                $request["store"] = $store;
            }
            $request["service"] = json_decode($request["service"], true);
            $service = pdo_getall("xc_beauty_store_service", array("uniacid" => $uniacid));
            $datalist = array();
            if ($service) {
                foreach ($service as $s) {
                    $datalist[$s["id"]] = $s;
                }
            }
            foreach ($request["service"] as &$x) {
                $x["price"] = $datalist[$x["id"]]["price"];
            }
            $request["is_zan"] = -1;
            $zan = pdo_get("xc_beauty_zan", array("openid" => $_W["openid"], "status" => 1, "uniacid" => $uniacid, "pid" => $_GPC["id"]));
            if ($zan) {
                $request["is_zan"] = 1;
            }
            $discuss = pdo_getall("xc_beauty_discuss", array("uniacid" => $uniacid, "pid" => $request["id"], "status" => 1, "type" => 2), array(), '', "id DESC", array(1, 10));
            if ($discuss) {
                $user = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
                $user_list = array();
                if ($user) {
                    foreach ($user as $u) {
                        $u["nick"] = base64_decode($u["nick"]);
                        $user_list[$u["openid"]] = $u;
                    }
                }
                foreach ($discuss as &$d) {
                    $d["nick"] = $user_list[$d["openid"]]["nick"];
                    $d["avatar"] = $user_list[$d["openid"]]["avatar"];
                }
                $request["discuss_list"] = $discuss;
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "zan":
        $request = pdo_insert("xc_beauty_zan", array("uniacid" => $uniacid, "openid" => $_W["openid"], "pid" => $_GPC["id"], "status" => 1));
        if ($request) {
            $sql = "UPDATE " . tablename("xc_beauty_store_member") . " SET zan=zan+1 WHERE id=:id AND uniacid=:uniacid";
            pdo_query($sql, array(":id" => $_GPC["id"], ":uniacid" => $uniacid));
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "discuss":
        $request = pdo_getall("xc_beauty_discuss", array("uniacid" => $uniacid, "pid" => $_GPC["id"], "status" => 1, "type" => 2), array(), '', "id DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $user = pdo_getall("xc_beauty_userinfo", array("uniacid" => $uniacid));
            $user_list = array();
            if ($user) {
                foreach ($user as $u) {
                    $u["nick"] = base64_decode($u["nick"]);
                    $user_list[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$x) {
                $x["nick"] = $user_list[$x["openid"]]["nick"];
                $x["avatar"] = $user_list[$x["openid"]]["avatar"];
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