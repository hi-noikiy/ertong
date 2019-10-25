<?php
function usercode($code, $service, $_W)
{
    $back = IA_ROOT . "/addons/xc_train/resource/images/back.jpg";
    $num = time();
    $savepath = IA_ROOT . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y");
    if (!is_dir($savepath)) {
        mkdir($savepath, 0700, true);
    }
    $savepath = $savepath . "/" . date("m") . "/";
    if (!is_dir($savepath)) {
        mkdir($savepath, 0700, true);
    }
    $savefile = $num;
    $saveokpath = $savepath . $savefile;
    $back_img = imagecreatefromstring(file_get_contents($back));
    list($dst_w, $dst_h, $dst_type) = getimagesize($back);
    $font = IA_ROOT . "/addons/xc_train/resource/WRYH.ttf";
    $poster = pdo_get("xc_train_config", array("xkey" => "service_poster", "uniacid" => $_W["uniacid"]));
    if ($poster) {
        $poster["content"] = json_decode($poster["content"], true);
        if (!empty($poster["content"]) && !empty($poster["content"]["list"])) {
            $poster["content"]["list"] = my_array_multisort($poster["content"]["list"], "z-index");
            foreach ($poster["content"]["list"] as $x) {
                switch ($x["type"]) {
                    case "bimg":
                        $service_bimg = $savepath . "service_" . $num . ".jpg";
                        $service["bimg"] = tomedia($service["bimg"]);
                        $re = mkThumbnail($service["bimg"], intval($x["width"]) * 2, intval($x["height"]) * 2, $service_bimg);
                        $bimg = imagecreatefromstring(file_get_contents($service_bimg));
                        list($bimg_w, $bimg_h, $bimg_type) = getimagesize($service_bimg);
                        $bimg_x = imagesx($bimg);
                        $bimg_y = imagesy($bimg);
                        imagecopy($back_img, $bimg, intval($x["left"]) * 2, intval($x["top"]) * 2, 0, 0, $bimg_x, $bimg_y);
                        unlink($service_bimg);
                        break;
                    case "title":
                        $name = $service["name"];
                        $width = intval(intval($x["width"]) / intval($x["px"]));
                        $height = intval(intval($x["height"]) / intval($x["px"]));
                        $line = intval(mb_strlen($name, "UTF-8") / $width) + 1;
                        $fontBox = imagettfbbox((intval($x["px"]) - (intval(intval($x["px"]) / 7) + 3)) * 2, 0, $font, $name);
                        $color = hex2rgb($x["color"]);
                        $black = imagecolorallocate($back_img, $color["r"], $color["g"], $color["b"]);
                        $i = 0;
                        while ($i < $line) {
                            $text = mb_substr($name, $i * $width, $width, "UTF-8");
                            if ($i + 1 <= $height && !empty($text)) {
                                imagefttext($back_img, (intval($x["px"]) - (intval(intval($x["px"]) / 7) + 3)) * 2, 0, intval($x["left"]) * 2, (intval($x["top"]) + ($fontBox[1] - $fontBox[7]) * ($i + 1)) * 2, $black, $font, mb_substr($name, $i * $width, $width, "UTF-8"));
                            }
                            $i++;
                        }
                        break;
                    case "price":
                        if (!empty($service["price"])) {
                            $name = "￥" . $service["price"];
                        } else {
                            $name = "免费";
                        }
                        $fontBox = imagettfbbox((intval($x["px"]) - (intval(intval($x["px"]) / 7) + 3)) * 2, 0, $font, $name);
                        $color = hex2rgb($x["color"]);
                        $black = imagecolorallocate($back_img, $color["r"], $color["g"], $color["b"]);
                        imagefttext($back_img, (intval($x["px"]) - (intval(intval($x["px"]) / 7) + 3)) * 2, 0, intval($x["left"]) * 2, (intval($x["top"]) + $fontBox[1] - $fontBox[7]) * 2, $black, $font, $name);
                        break;
                    case "code":
                        $service_bimg = $savepath . "code_" . $num . ".jpg";
                        $re = mkThumbnail($code, intval($x["width"]) * 2, intval($x["height"]) * 2, $service_bimg);
                        $bimg = imagecreatefromstring(file_get_contents($service_bimg));
                        list($bimg_w, $bimg_h, $bimg_type) = getimagesize($service_bimg);
                        $bimg_x = imagesx($bimg);
                        $bimg_y = imagesy($bimg);
                        imagecopy($back_img, $bimg, intval($x["left"]) * 2, intval($x["top"]) * 2, 0, 0, $bimg_x, $bimg_y);
                        unlink($service_bimg);
                        break;
                    case "text":
                        $name = $x["content"];
                        $width = intval(intval($x["width"]) / intval($x["px"]));
                        $height = intval(intval($x["height"]) / intval($x["px"]));
                        $line = intval(mb_strlen($name, "UTF-8") / $width) + 1;
                        $fontBox = imagettfbbox((intval($x["px"]) - (intval(intval($x["px"]) / 7) + 3)) * 2, 0, $font, $name);
                        $color = hex2rgb($x["color"]);
                        $black = imagecolorallocate($back_img, $color["r"], $color["g"], $color["b"]);
                        $i = 0;
                        while ($i < $line) {
                            $text = mb_substr($name, $i * $width, $width, "UTF-8");
                            if ($i + 1 <= $height && !empty($text)) {
                                imagefttext($back_img, (intval($x["px"]) - (intval(intval($x["px"]) / 7) + 3)) * 2, 0, intval($x["left"]) * 2, (intval($x["top"]) + ($fontBox[1] - $fontBox[7]) * ($i + 1)) * 2, $black, $font, mb_substr($name, $i * $width, $width, "UTF-8"));
                            }
                            $i++;
                        }
                        break;
                    case "img":
                        if (!empty($x["content"])) {
                            $service_bimg = $savepath . "service_" . $num . ".jpg";
                            $service["simg"] = tomedia($service["simg"]);
                            $re = mkThumbnail($x["content"], intval($x["width"]) * 2, intval($x["height"]) * 2, $service_bimg);
                            $bimg = imagecreatefromstring(file_get_contents($service_bimg));
                            list($bimg_w, $bimg_h, $bimg_type) = getimagesize($service_bimg);
                            $bimg_x = imagesx($bimg);
                            $bimg_y = imagesy($bimg);
                            imagecopy($back_img, $bimg, intval($x["left"]) * 2, intval($x["top"]) * 2, 0, 0, $bimg_x, $bimg_y);
                            unlink($service_bimg);
                        }
                        break;
                }
            }
        }
    }
    $exname = '';
    switch ($dst_type) {
        case 1:
            $exname = ".gif";
            imagegif($back_img, $saveokpath . ".gif");
            break;
        case 2:
            $exname = ".jpg";
            imagejpeg($back_img, $saveokpath . ".jpg");
            break;
        case 3:
            $exname = ".png";
            imagepng($back_img, $saveokpath . ".png");
            break;
        default:
            break;
    }
    $url = "https://" . $_SERVER["HTTP_HOST"] . "/attachment/images/" . $_W["uniacid"] . "/" . date("Y") . "/" . date("m") . "/" . $savefile . '' . $exname;
    return $url;
}
function mkThumbnail($src, $width = null, $height = null, $filename = null)
{
    if (!isset($width) && !isset($height)) {
        return false;
    }
    if (isset($width) && $width <= 0) {
        return false;
    }
    if (isset($height) && $height <= 0) {
        return false;
    }
    $size = getimagesize($src);
    if (!$size) {
        return false;
    }
    list($src_w, $src_h, $src_type) = $size;
    $src_mime = $size["mime"];
    switch ($src_type) {
        case 1:
            $img_type = "gif";
            break;
        case 2:
            $img_type = "jpeg";
            break;
        case 3:
            $img_type = "png";
            break;
        case 15:
            $img_type = "wbmp";
            break;
        default:
            return false;
    }
    if (!isset($width)) {
        $width = $src_w * ($height / $src_h);
    }
    if (!isset($height)) {
        $height = $src_h * ($width / $src_w);
    }
    $imagecreatefunc = "imagecreatefrom" . $img_type;
    $src_img = $imagecreatefunc($src);
    $dest_img = imagecreatetruecolor($width, $height);
    imagealphablending($dest_img, false);
    imagesavealpha($dest_img, true);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
    $imagefunc = "image" . $img_type;
    if ($filename) {
        $imagefunc($dest_img, $filename);
    } else {
        header("Content-Type: " . $src_mime);
        $imagefunc($dest_img);
    }
    imagedestroy($src_img);
    imagedestroy($dest_img);
    return true;
}
function my_array_multisort($data, $sort_order_field, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
{
    foreach ($data as $val) {
        $key_arrays[] = $val[$sort_order_field];
    }
    array_multisort($key_arrays, SORT_ASC, SORT_NUMERIC, $data);
    return $data;
}
function hex2rgb($hexColor)
{
    $color = str_replace("#", '', $hexColor);
    if (strlen($color) > 3) {
        $rgb = array("r" => hexdec(substr($color, 0, 2)), "g" => hexdec(substr($color, 2, 2)), "b" => hexdec(substr($color, 4, 2)));
    } else {
        $color = $hexColor;
        $r = substr($color, 0, 1) . substr($color, 0, 1);
        $g = substr($color, 1, 1) . substr($color, 1, 1);
        $b = substr($color, 2, 1) . substr($color, 2, 1);
        $rgb = array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
    }
    return $rgb;
}
function test($url, $path = "./", $w, $h)
{
    $original_path = $url;
    $dest_path = $path . ".png";
    $src = imagecreatefromstring(file_get_contents($original_path));
    $newpic = imagecreatetruecolor($w, $h);
    imagealphablending($newpic, false);
    $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
    $r = $w / 2;
    $x = 0;
    while ($x < $w) {
        $y = 0;
        while ($y < $h) {
            $c = imagecolorat($src, $x, $y);
            $_x = $x - $w / 2;
            $_y = $y - $h / 2;
            if ($_x * $_x + $_y * $_y < $r * $r) {
                imagesetpixel($newpic, $x, $y, $c);
            } else {
                imagesetpixel($newpic, $x, $y, $transparent);
            }
            $y++;
        }
        $x++;
    }
    imagesavealpha($newpic, true);
    imagepng($newpic, $dest_path);
    imagedestroy($newpic);
    imagedestroy($src);
    unlink($url);
    return $dest_path;
}