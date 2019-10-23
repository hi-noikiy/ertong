<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("base", "index", "userinfo", "coupon", "school", "school_detail", "login_log", "article");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "base";
switch ($op) {
    case "userinfo":
        if (!empty($_W["openid"])) {
            $userinfo = pdo_get("xc_train_userinfo", array("openid" => $_W["openid"], "uniacid" => $uniacid));
            if ($userinfo) {
                $condition = array();
                if (!empty($_GPC["avatarUrl"])) {
                    $condition["avatar"] = $_GPC["avatarUrl"];
                }
                if (!empty($_GPC["nickName"])) {
                    $condition["nick"] = base64_encode($_GPC["nickName"]);
                }
                pdo_update("xc_train_userinfo", $condition, array("openid" => $_W["openid"], "uniacid" => $uniacid));
            } else {
                if (!empty($_GPC["avatarUrl"])) {
                    $condition["avatar"] = $_GPC["avatarUrl"];
                }
                if (!empty($_GPC["nickName"])) {
                    $condition["nick"] = base64_encode($_GPC["nickName"]);
                }
                $condition["uniacid"] = $uniacid;
                $condition["openid"] = $_W["openid"];
                pdo_insert("xc_train_userinfo", $condition);
            }
            $request = pdo_get("xc_train_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
            if ($request) {
                $request["nick"] = base64_decode($request["nick"]);
                $request["login"] = -1;
                $config = pdo_get("xc_train_config", array("xkey" => "open_ad", "uniacid" => $uniacid));
                if ($config) {
                    $config["content"] = json_decode($config["content"], true);
                    if ($config["content"]["status"] == 1) {
                        if ($config["content"]["type"] == 1) {
                            $log = pdo_get("xc_train_login_log", array("openid" => $_W["openid"]));
                            if ($log) {
                                $request["login"] = -1;
                            } else {
                                $request["login"] = 1;
                            }
                        } else {
                            if ($config["content"]["type"] == 2) {
                                $log = pdo_get("xc_train_login_log", array("openid" => $_W["openid"], "plan_date" => date("Y-m-d")));
                                if ($log) {
                                    $request["login"] = -1;
                                } else {
                                    $request["login"] = 1;
                                }
                            }
                        }
                    }
                }
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(1, "请先授权");
            }
        }
        break;
    case "base":
        $request = array();
        $config = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "web"));
        if ($config) {
            $config["content"] = json_decode($config["content"], true);
            if (!empty($config["content"]["footer"])) {
                foreach ($config["content"]["footer"] as &$x) {
                    $x["icon"] = tomedia($x["icon"]);
                    $x["select"] = tomedia($x["select"]);
                }
            }
            if (!empty($config["content"]["password"])) {
                $config["content"]["password"] = md5($config["content"]["password"]);
            }
            if (!empty($config["content"]["online_simg"])) {
                $config["content"]["online_simg"] = tomedia($config["content"]["online_simg"]);
            }
            if (!empty($config["content"]["sign_bimg"])) {
                $config["content"]["sign_bimg"] = tomedia($config["content"]["sign_bimg"]);
            }
            if (!empty($config["content"]["g_class"])) {
                $config["content"]["g_class"] = tomedia($config["content"]["g_class"]);
            }
            if (!empty($config["content"]["x_class"])) {
                $config["content"]["x_class"] = tomedia($config["content"]["x_class"]);
            }
            if (!empty($config["content"]["cut_bimg"])) {
                $config["content"]["cut_bimg"] = explode(",", $config["content"]["cut_bimg"]);
                foreach ($config["content"]["cut_bimg"] as &$cb) {
                    $cb = tomedia($cb);
                }
            }
            $request["config"] = $config;
        }
        $theme = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "theme"));
        if ($theme) {
            $theme["content"] = json_decode($theme["content"], true);
            if (!empty($theme["content"]["icon"])) {
                foreach ($theme["content"]["icon"] as &$x) {
                    $x = tomedia($x);
                }
            }
            $request["theme"] = $theme;
        }
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "index":
        $request = array();
        $banner = pdo_getall("xc_train_banner", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,createtime DESC");
        if ($banner) {
            foreach ($banner as &$x) {
                $x["bimg"] = tomedia($x["bimg"]);
            }
            $request["banner"] = $banner;
        }
        $ad = pdo_get("xc_train_config", array("xkey" => "ad", "uniacid" => $uniacid));
        if ($ad) {
            $ad["content"] = json_decode($ad["content"], true);
            if ($ad["content"]["status"] == 1 && !empty($ad["content"]["list"])) {
                $request["ad"] = $ad["content"]["list"];
            }
        }
        $nav = pdo_getall("xc_train_nav", array("status" => 1, "uniacid" => $uniacid), array(), '', "sort DESC,id DESC");
        if ($nav) {
            foreach ($nav as &$n) {
                $n["simg"] = tomedia($n["simg"]);
            }
            $request["nav"] = $nav;
        }
        $list = pdo_getall("xc_train_service", array("uniacid" => $uniacid, "status" => 1, "index" => 1), array(), '', "sort DESC,createtime DESC");
        if ($list) {
            foreach ($list as &$l) {
                $l["bimg"] = tomedia($l["bimg"]);
            }
            $request["list"] = $list;
        }
        $open_ad = pdo_get("xc_train_config", array("uniacid" => $uniacid, "xkey" => "open_ad"));
        if ($open_ad) {
            $open_ad["content"] = json_decode($open_ad["content"], true);
            if (!empty($open_ad["content"]["bimg"])) {
                $open_ad["content"]["bimg"] = tomedia($open_ad["content"]["bimg"]);
            }
            $request["open_ad"] = $open_ad;
        }
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "coupon":
        $coupon = pdo_getall("xc_train_coupon", array("status" => 1, "uniacid" => $uniacid));
        if ($coupon) {
            $coupon_id = array();
            $datalist = array();
            foreach ($coupon as $c) {
                $datalist[$c["id"]] = $c;
                $coupon_id[] = $c["id"];
            }
            $user_coupon = pdo_getall("xc_train_user_coupon", array("status" => -1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
            $user_id = array();
            if ($user_coupon) {
                foreach ($user_coupon as $u) {
                    $user_id[] = $u["cid"];
                }
            }
            if ($_GPC["curr"] == 1) {
                $condition["status"] = 1;
                $condition["uniacid"] = $uniacid;
                if (!empty($user_id)) {
                    $condition["id !="] = $user_id;
                }
                $request = pdo_getall("xc_train_coupon", $condition, array(), '', "sort DESC,createtime DESC");
                foreach ($request as $key => $x) {
                    $x["times"] = json_decode($x["times"], true);
                    if (strtotime($x["times"]["start"]) < time() && time() < strtotime($x["times"]["end"])) {
                        $request[$key]["fail"] = date("Y/m/d", strtotime($x["times"]["end"]));
                    } else {
                        unset($request[$key]);
                    }
                }
            } else {
                if ($_GPC["curr"] == 2) {
                    $condition["status"] = -1;
                    $condition["uniacid"] = $uniacid;
                    $condition["cid IN"] = $coupon_id;
                    $condition["openid"] = $_W["openid"];
                    $request = pdo_getall("xc_train_user_coupon", $condition, array(), '', "createtime DESC");
                    if ($request) {
                        foreach ($request as $key => $x) {
                            $x["times"] = $datalist[$x["cid"]]["times"];
                            $x["times"] = json_decode($x["times"], true);
                            if (strtotime($x["times"]["start"]) < time() && time() < strtotime($x["times"]["end"])) {
                                $request[$key]["fail"] = date("Y/m/d", strtotime($x["times"]["end"]));
                                $request[$key]["name"] = $datalist[$x["cid"]]["name"];
                                $request[$key]["condition"] = $datalist[$x["cid"]]["condition"];
                            } else {
                                unset($request[$key]);
                            }
                        }
                    }
                } else {
                    if ($_GPC["curr"] == 3) {
                        $condition["status"] = 1;
                        $condition["uniacid"] = $uniacid;
                        $condition["cid IN"] = $coupon_id;
                        $condition["openid"] = $_W["openid"];
                        $request = pdo_getall("xc_train_user_coupon", $condition, array(), '', "createtime DESC");
                        if ($request) {
                            foreach ($request as $key => $x) {
                                $x["times"] = $datalist[$x["cid"]]["times"];
                                $x["times"] = json_decode($x["times"], true);
                                $request[$key]["fail"] = date("Y/m/d", strtotime($x["times"]["end"]));
                                $request[$key]["name"] = $datalist[$x["cid"]]["name"];
                                $request[$key]["condition"] = $datalist[$x["cid"]]["condition"];
                            }
                        }
                    } else {
                        if ($_GPC["curr"] == 4) {
                            $condition["status"] = -1;
                            $condition["uniacid"] = $uniacid;
                            $condition["cid IN"] = $coupon_id;
                            $condition["openid"] = $_W["openid"];
                            $request = pdo_getall("xc_train_user_coupon", $condition, array(), '', "createtime DESC");
                            if ($request) {
                                foreach ($request as $key => $x) {
                                    $x["times"] = $datalist[$x["cid"]]["times"];
                                    $x["times"] = json_decode($x["times"], true);
                                    if (strtotime($x["times"]["start"]) < time() && time() < strtotime($x["times"]["end"])) {
                                        unset($request[$key]);
                                    } else {
                                        unset($request[$key]);
                                        $request[$key]["fail"] = date("Y/m/d", strtotime($x["times"]["end"]));
                                        $request[$key]["name"] = $datalist[$x["cid"]]["name"];
                                        $request[$key]["condition"] = $datalist[$x["cid"]]["condition"];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($request)) {
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "school":
        if (!empty($_GPC["longitude"]) && !empty($_GPC["latitude"])) {
            $page = intval($_GPC["page"] - 1) * intval($_GPC["pagesize"]);
            $pageisze = $_GPC["pagesize"];
            $sql = "SELECT *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((:xlatitude*PI()/180-latitude*PI()/180)/2),2)+COS(:xlatitude*PI()/180)*COS(latitude*PI()/180)*POW(SIN((:xlongitude*PI()/180-longitude*PI()/180)/2),2))),2) AS juli FROM " . tablename("xc_train_school") . " WHERE status=1 AND uniacid=:uniacid ORDER BY juli,sort DESC,createtime DESC LIMIT {$page},{$pageisze}";
            $request = pdo_fetchall($sql, array(":xlatitude" => $_GPC["latitude"], ":xlongitude" => $_GPC["longitude"], ":uniacid" => $uniacid));
        } else {
            $request = pdo_getall("xc_train_school", array("uniacid" => $uniacid, "status" => 1), array(), '', "sort DESC,createtime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        }
        if ($request) {
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "school_detail":
        $request = pdo_get("xc_train_school", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["simg"] = tomedia($request["simg"]);
            $request["content"] = json_decode($request["content"], true);
            $request["total"] = 0;
            if (!empty($request["teacher"])) {
                $request["teacher"] = json_decode($request["teacher"], true);
                $request["total"] = count($request["teacher"]);
                $teacher = pdo_getall("xc_train_teacher", array("status" => 1, "uniacid" => $uniacid));
                $datalist = array();
                if ($teacher) {
                    foreach ($teacher as $t) {
                        $datalist[$t["id"]] = $t;
                    }
                }
                foreach ($request["teacher"] as &$x) {
                    $x["simg"] = tomedia($datalist[$x["id"]]["simg"]);
                    $x["name"] = $datalist[$x["id"]]["name"];
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "login_log":
        $request = pdo_insert("xc_train_login_log", array("openid" => $_W["openid"], "uniacid" => $uniacid, "plan_date" => date("Y-m-d")));
        break;
    case "article":
        $request = pdo_get("xc_train_article", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["content"] = htmlspecialchars_decode($request["content"]);
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