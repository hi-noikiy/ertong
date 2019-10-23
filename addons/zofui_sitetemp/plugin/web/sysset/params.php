<?php
global $_W, $_GPC;
$menu = array("index" => 5, "name" => "系统设置", "do" => "copyright", "p" => "sysset", "op" => "list", "leftbar" => array("copyright" => array("name" => "系统设置", "p" => "sysset", "icon" => "https://res.wx.qq.com/mpres/htmledition/images/icon/menu/icon_menu_setup.png", "list" => array(array("op" => "basic", "name" => "基本设置", "url" => self::pwUrl("sysset", "copyright", array("op" => "basic"))), array("op" => "list", "name" => "版权设置", "url" => self::pwUrl("sysset", "copyright", array("op" => "list"))), array("op" => "mail", "name" => "邮件设置", "url" => self::pwUrl("sysset", "copyright", array("op" => "mail"))), array("op" => "sms", "name" => "短信设置", "url" => self::pwUrl("sysset", "copyright", array("op" => "sms"))), array("op" => "auth", "name" => "权限设置", "url" => self::pwUrl("sysset", "copyright", array("op" => "auth"))), array("op" => "tempsort", "name" => "模板分类", "url" => self::pwUrl("sysset", "copyright", array("op" => "tempsort"))), array("op" => "ad", "name" => "广告设置", "url" => self::pwUrl("sysset", "copyright", array("op" => "ad")))), "toplist" => array("basic", "list", "mail", "sms", "auth", "tempsort", "ad"))));
$set = model_sysset::getSet();
if ($_W["role"] != "founder") {
	unset($menu["leftbar"]["copyright"]["list"][0], $menu["leftbar"]["copyright"]["list"][1], $menu["leftbar"]["copyright"]["list"][2], $menu["leftbar"]["copyright"]["list"][3], $menu["leftbar"]["copyright"]["list"][5]);
	$menu["op"] = "auth";
}
if (empty($set["isvicead"]) || $_W["role"] != "vice_founder") {
	unset($menu["leftbar"]["copyright"]["list"][6]);
	$menu["op"] = "auth";
}
if (!in_array($_W["role"], array("founder", "vice_founder")) || $_W["role"] == "vice_founder" && empty($set["viceisauth"])) {
	unset($menu["leftbar"]["copyright"]["list"][4]);
	$menu["op"] = "ad";
}
return $menu;