<?php
defined("IN_IA") or exit("Access Denied");
$ops = array("userinfo", "map", "set_coupon", "sign", "active", "active_detail", "prize", "active_status", "address", "address_edit", "address_del", "address_status", "getCode");
global $_GPC, $_W;
$uniacid = $_W["uniacid"];
$op = in_array($_GPC["op"], $ops) ? $_GPC["op"] : "service";
switch ($op) {
    case "userinfo":
        $request = pdo_get("xc_train_userinfo", array("status" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
        if ($request) {
            $request["nick"] = base64_decode($request["nick"]);
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "map":
        $request = pdo_get("xc_train_config", array("xkey" => "map", "uniacid" => $uniacid));
        if ($request) {
            $request["content"] = json_decode($request["content"], true);
            if (!empty($request["content"]["bimg"])) {
                $request["content"]["bimg"] = tomedia($request["content"]["bimg"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "set_coupon":
        $coupon = pdo_get("xc_train_coupon", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($coupon) {
            if ($coupon["total"] == 0) {
                return $this->result(1, "优惠券被领光了！");
            }
            $request = pdo_insert("xc_train_user_coupon", array("uniacid" => $uniacid, "openid" => $_W["openid"], "cid" => $_GPC["id"], "status" => -1));
            if ($request) {
                if ($coupon["total"] != -1) {
                    pdo_update("xc_train_coupon", array("total" => intval($coupon["total"]) - 1), array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
                    return $this->result(0, "操作成功", array("status" => 1));
                }
            } else {
                return $this->result(1, "操作失败");
            }
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "sign":
        if (!empty($_GPC["cut"])) {
            $request = pdo_get("xc_train_cut", array("id" => $_GPC["cut"], "uniacid" => $uniacid));
            if ($request) {
                $service = pdo_get("xc_train_service", array("id" => $request["pid"], "uniacid" => $uniacid));
                if ($service) {
                    $request["title"] = $service["name"] . "【" . $request["mark"] . "】";
                }
                $order = pdo_get("xc_train_cut_order", array("openid" => $_W["openid"], "cid" => $request["id"], "uniacid" => $uniacid));
                if ($order) {
                    $request["amount"] = $order["price"];
                } else {
                    $request["amount"] = $request["price"];
                }
                return $this->result(0, "操作成功", $request);
            } else {
                return $this->result(0, "操作失败");
            }
        } else {
            $pid = '';
            if (empty($_GPC["pid"])) {
                $order = pdo_getall("xc_train_order", array("order_type" => 1, "uniacid" => $uniacid, "openid" => $_W["openid"]));
                if ($order) {
                    $pid = $order[0]["pid"];
                }
            } else {
                $pid = $_GPC["pid"];
            }
            if (!empty($pid)) {
                $sql = "SELECT * FROM " . tablename("xc_train_service_team") . " WHERE status=1 AND uniacid=:uniacid AND UNIX_TIMESTAMP(end_time)>:times AND more_member>member AND id=:id";
                $request = pdo_fetch($sql, array(":uniacid" => $uniacid, ":times" => time(), "id" => $pid));
                if ($request) {
                    $service = pdo_get("xc_train_service", array("status" => 1, "id" => $request["pid"], "uniacid" => $uniacid));
                    if ($service) {
                        $request["title"] = $service["name"] . "【" . $request["mark"] . "】";
                        if (!empty($service["price"])) {
                            $request["amount"] = $service["price"];
                        } else {
                            $request["amount"] = 0;
                        }
                        $request["list"] = array();
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
                            $condition["status"] = -1;
                            $condition["uniacid"] = $uniacid;
                            $condition["cid IN"] = $coupon_id;
                            $condition["openid"] = $_W["openid"];
                            $list = pdo_getall("xc_train_user_coupon", $condition, array(), '', "createtime DESC");
                            if ($list) {
                                foreach ($list as $key => $x) {
                                    $x["times"] = $datalist[$x["cid"]]["times"];
                                    $x["times"] = json_decode($x["times"], true);
                                    if (strtotime($x["times"]["start"]) < time() && time() < strtotime($x["times"]["end"])) {
                                        $list[$key]["fail"] = date("Y/m/d", strtotime($x["times"]["end"]));
                                        $list[$key]["name"] = $datalist[$x["cid"]]["name"];
                                        $list[$key]["condition"] = $datalist[$x["cid"]]["condition"];
                                        if (floatval($list[$key]["condition"]) > floatval($request["amount"])) {
                                            unset($list[$key]);
                                        }
                                    } else {
                                        unset($list[$key]);
                                    }
                                }
                            }
                            $request["list"] = $list;
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
        }
        break;
    case "active":
        $page = (intval($_GPC["page"]) - 1) * intval($_GPC["pagesize"]);
        $pagesize = $_GPC["pagesize"];
        $request = pdo_fetchall("SELECT * FROM " . tablename("xc_train_active") . " WHERE status=:status AND uniacid=:uniacid AND UNIX_TIMESTAMP(start_time)<:plan_date AND UNIX_TIMESTAMP(end_time)>:plan_date ORDER BY sort DESC,createtime DESC LIMIT {$page},{$pagesize}", array(":status" => 1, ":uniacid" => $uniacid, ":plan_date" => time()));
        if ($request) {
            $userinfo = pdo_getall("xc_train_userinfo", array("uniacid" => $uniacid));
            $datalist = array();
            if ($userinfo) {
                foreach ($userinfo as $u) {
                    $datalist[$u["openid"]] = $u;
                }
            }
            foreach ($request as &$x) {
                $x["simg"] = tomedia($x["simg"]);
                $x["bimg"] = tomedia($x["bimg"]);
                $x["start_time"] = date("Y-m-d", strtotime($x["start_time"]));
                $x["end_time"] = date("Y-m-d", strtotime($x["end_time"]));
                $x["share_img"] = tomedia($x["share_img"]);
                $x["list"] = pdo_getall("xc_train_prize", array("status" => 1, "uniacid" => $uniacid, "cid" => $x["id"], "type" => $x["type"]), array(), '', "prizetime DESC,createtime DESC,id DESC", array(1, 10));
                if ($x["list"]) {
                    foreach ($x["list"] as &$y) {
                        $y["simg"] = $datalist[$y["openid"]]["avatar"];
                        $y["nick"] = base64_decode($datalist[$y["openid"]]["nick"]);
                    }
                }
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "active_detail":
        $request = pdo_get("xc_train_active", array("status" => 1, "id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            $request["simg"] = tomedia($request["simg"]);
            $request["bimg"] = tomedia($request["bimg"]);
            $request["gua_img"] = tomedia($request["gua_img"]);
            if ($request["type"] == 1) {
                $request["prize_name"] = $request["prize"];
                $request["share_img"] = tomedia($request["share_img"]);
            }
            $request["share_type"] = 2;
            $request["list"] = pdo_getall("xc_train_prize", array("status" => 1, "uniacid" => $uniacid, "cid" => $request["id"], "type" => $request["type"]), array(), '', "prizetime DESC", array(1, 10));
            if ($request["list"]) {
                $userinfo = pdo_getall("xc_train_userinfo", array("uniacid" => $uniacid));
                $datalist = array();
                if ($userinfo) {
                    foreach ($userinfo as $u) {
                        $datalist[$u["openid"]] = $u;
                    }
                }
                foreach ($request["list"] as &$y) {
                    $y["simg"] = $datalist[$y["openid"]]["avatar"];
                    $y["nick"] = base64_decode($datalist[$y["openid"]]["nick"]);
                }
            }
            $request["opengid"] = array();
            $request["prize"] = pdo_get("xc_train_prize", array("openid" => $_W["openid"], "cid" => $request["id"], "uniacid" => $uniacid, "type" => $request["type"]));
            $request["you_xiao"] = 0;
            if ($request["prize"]) {
                $request["prize"]["opengid"] = json_decode($request["prize"]["opengid"], true);
                if (!empty($request["prize"]["opengid"])) {
                    foreach ($request["prize"]["opengid"] as $p) {
                        if (!empty($p)) {
                            $request["you_xiao"] = $request["you_xiao"] + 1;
                        }
                    }
                }
                if ($request["prize"]["type"] == 2 && !empty($request["prize"]["pid"])) {
                    $gua_list = pdo_get("xc_train_gua", array("id" => $request["prize"]["pid"], "uniacid" => $uniacid));
                    if ($gua_list) {
                        $request["prize_bimg"] = tomedia($gua_list["bimg"]);
                    }
                }
                $request["opengid"] = $request["prize"]["opengid"];
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "prize":
        $request = pdo_getall("xc_train_prize", array("status" => 1, "openid" => $_W["openid"], "uniacid" => $uniacid), array(), '', "prizetime DESC", array($_GPC["page"], $_GPC["pagesize"]));
        if ($request) {
            $prize = pdo_getall("xc_train_active", array("uniacid" => $uniacid));
            $datalist = array();
            if ($prize) {
                foreach ($prize as $p) {
                    $datalist[$p["id"]] = $p;
                }
            }
            $gua = pdo_getall("xc_train_gua", array("uniacid" => $uniacid));
            $gua_list = array();
            if ($gua) {
                foreach ($gua as $g) {
                    $gua_list[$g["id"]] = $g;
                }
            }
            foreach ($request as &$x) {
                if ($x["type"] == 1) {
                    $x["simg"] = tomedia($datalist[$x["cid"]]["bimg"]);
                } else {
                    if ($x["type"] == 2) {
                        $x["simg"] = tomedia($gua_list[$x["pid"]]["bimg"]);
                    }
                }
                $x["code"] = base64_encode($x["id"]);
            }
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "active_status":
        $prize = pdo_get("xc_train_prize", array("cid" => $_GPC["id"], "uniacid" => $uniacid, "type" => 2, "openid" => $_W["openid"]));
        $request = pdo_update("xc_train_prize", array("status" => 1, "prizetime" => date("Y-m-d H:i:s")), array("openid" => $_W["openid"], "cid" => $_GPC["id"], "uniacid" => $uniacid, "type" => 2));
        if ($request) {
            return $this->result(0, "操作成功", array("name" => $prize["prize"]));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "address":
        $request = pdo_getall("xc_train_address", array("openid" => $_W["openid"], "uniacid" => $uniacid), array(), '', "id DESC");
        if ($request) {
            return $this->result(0, "操作成功", $request);
        } else {
            return $this->result(0, "操作失败");
        }
        break;
    case "address_edit":
        $condition = array("uniacid" => $uniacid, "openid" => $_W["openid"], "name" => $_GPC["name"], "mobile" => $_GPC["mobile"], "sex" => $_GPC["sex"], "latitude" => $_GPC["latitude"], "longitude" => $_GPC["longitude"]);
        if (!empty($_GPC["address"])) {
            $condition["address"] = $_GPC["address"];
        }
        if (!empty($_GPC["latitude"])) {
            $condition["latitude"] = $_GPC["latitude"];
        }
        if (!empty($_GPC["longitude"])) {
            $condition["longitude"] = $_GPC["longitude"];
        }
        if (!empty($_GPC["content"])) {
            $condition["content"] = $_GPC["content"];
        }
        if (!empty($_GPC["id"])) {
            $request = pdo_update("xc_train_address", $condition, array("id" => $_GPC["id"], "uniacid" => $uniacid));
        } else {
            pdo_update("xc_train_address", array("status" => -1), array("openid" => $_W["openid"], "uniacid" => $uniacid));
            $request = pdo_insert("xc_train_address", $condition);
        }
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "address_del":
        $request = pdo_delete("xc_train_address", array("id" => $_GPC["id"], "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "address_status":
        pdo_update("xc_train_address", array("status" => -1), array("openid" => $_W["openid"], "uniacid" => $uniacid));
        $request = pdo_update("xc_train_address", array("status" => 1), array("id" => $_GPC["id"], "openid" => $_W["openid"], "uniacid" => $uniacid));
        if ($request) {
            return $this->result(0, "操作成功", array("status" => 1));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
    case "getCode":
        $service = pdo_get("xc_train_service", array("uniacid" => $uniacid, "status" => 1, "id" => $_GPC["id"]));
        if ($service) {
            $code = '';
            if (!empty($service["code"])) {
                $code = $service["code"];
            } else {
                $filename = "service_" . $service["id"] . ".jpg";
                $fileurl = IA_ROOT . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y");
                if (!is_dir($fileurl)) {
                    mkdir($fileurl, 0700, true);
                }
                $fileurl = $fileurl . "/" . date("m") . "/";
                if (!is_dir($fileurl)) {
                    mkdir($fileurl, 0700, true);
                }
                $account_api = WeAccount::create();
                $token = $account_api->getAccessToken();
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $token;
                $post_data = array("path" => "xc_train/pages/base/base", "scene" => "service_" . $service["id"]);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
                $output = curl_exec($ch);
                curl_close($ch);
                $request = json_decode($output, true);
                if (is_array($request) && !empty($request)) {
                    return $this->result(1, "图片生成失败", $request);
                } else {
                    header("Content-Type: text/plain; charset=utf-8");
                    header("Content-type:image/jpeg");
                    $jpg = $output;
                    $file = fopen($fileurl . $filename, "w");
                    $reee = fwrite($file, $jpg);
                    fclose($file);
                    $code = "https://" . $_SERVER["HTTP_HOST"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y") . "/" . date("m") . "/" . $filename;
                    pdo_update("xc_train_service", array("code" => $code), array("id" => $_GPC["id"], "uniacid" => $uniacid));
                }
            }
            $url = usercode($code, $service, $_W);
            return $this->result(0, "操作成功", array("code" => $url));
        } else {
            return $this->result(1, "操作失败");
        }
        break;
}