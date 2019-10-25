<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `categoryId` int(10) NOT NULL DEFAULT '0' COMMENT '分类id',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `mobile` varchar(18) DEFAULT '' COMMENT '手机号',
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `weixin` varchar(100) DEFAULT NULL COMMENT '微信号',
  `company` varchar(100) DEFAULT NULL COMMENT '公司',
  `job` varchar(100) DEFAULT NULL,
  `fromId` varchar(100) DEFAULT '',
  `qq` varchar(100) DEFAULT '',
  `industry` varchar(100) DEFAULT '',
  `department` varchar(100) DEFAULT '',
  `desc` varchar(255) DEFAULT NULL,
  `imgs` text,
  `vip` tinyint(1) DEFAULT '0' COMMENT '0:非vip，1vip',
  `zan` int(10) DEFAULT '0',
  `clazz` varchar(10) DEFAULT 'default' COMMENT '模板',
  `view` int(10) DEFAULT '0',
  `lng` decimal(10,6) DEFAULT '0.000000',
  `lat` decimal(10,6) DEFAULT '0.000000',
  `status` tinyint(1) DEFAULT '0' COMMENT '0表示未审核，1表示已审核，2表示禁用',
  `audit_status` tinyint(1) DEFAULT '0' COMMENT '0表示未审核，1表示已审核，2表示禁用',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `collect` int(10) DEFAULT '0',
  `avater` varchar(255) DEFAULT '',
  `weixinImg` varchar(200) DEFAULT '',
  `listorder` int(10) DEFAULT '0' COMMENT '排序',
  `createtime` int(11) DEFAULT NULL,
  `qrcode` varchar(255) DEFAULT '',
  `qrcode2` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_card_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `cardid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL COMMENT '自己',
  `zan_mid` int(10) NOT NULL,
  `zan_cid` int(10) NOT NULL,
  `to_user` varchar(255) NOT NULL COMMENT '好友',
  `sms_type` tinyint(2) NOT NULL COMMENT '0:看，1:赞 2:收藏',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_card_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `click` tinyint(2) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `appid` varchar(255) NOT NULL DEFAULT '',
  `qrcode` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `endtime` int(10) unsigned NOT NULL COMMENT '广告结束时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_creditshop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` text,
  `price` decimal(10,2) DEFAULT '0.00',
  `type` tinyint(3) DEFAULT '0',
  `credit` int(11) DEFAULT '0',
  `stock` int(11) DEFAULT '0',
  `credit2` int(11) DEFAULT '0',
  `money` decimal(10,2) DEFAULT '0.00',
  `total` int(11) DEFAULT '0',
  `totalday` int(11) DEFAULT '0',
  `detail` text,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '0',
  `vip` tinyint(3) DEFAULT '0',
  `istop` tinyint(3) DEFAULT '0',
  `isrecommand` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_endtime` (`endtime`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_status` (`status`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_istop` (`istop`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_creditshop_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `address_phone` varchar(255) DEFAULT '',
  `address_name` varchar(255) DEFAULT '' COMMENT '收货人',
  `address` varchar(255) DEFAULT '' COMMENT '收货地址',
  `openid` varchar(255) DEFAULT '',
  `goodsid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0' COMMENT '0-未发货 1-已发货 3 取消',
  `usetime` int(11) DEFAULT '0',
  `express` varchar(255) DEFAULT '',
  `expresscom` varchar(255) DEFAULT '',
  `expresssn` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `vip` tinyint(1) DEFAULT '0' COMMENT '0vip，1非vip',
  `sex` tinyint(1) DEFAULT '0' COMMENT '1男，2女',
  `myattention` varchar(255) DEFAULT NULL,
  `myfocus` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `companyAddress` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0表示已审核，1表示未审核，2表示禁用',
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_navs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL,
  `title` varchar(1000) DEFAULT '' COMMENT '导航名称',
  `followurl` varchar(1000) DEFAULT '' COMMENT '网页链接',
  `thumb` varchar(1000) DEFAULT '' COMMENT '底部图片',
  `click` tinyint(1) DEFAULT '0' COMMENT '小程序点击类型 0 直接进入小程序 1:弹出小程序二维码',
  `status` tinyint(1) DEFAULT '0' COMMENT '跳转类型 0:小程序 1:web网页',
  `recommend` tinyint(1) DEFAULT '1' COMMENT '是否推荐',
  `bgcolor` varchar(1000) DEFAULT '' COMMENT '背景',
  `qrcode` varchar(1000) DEFAULT '' COMMENT '小程序二维码',
  `info` varchar(1000) DEFAULT '' COMMENT '介绍',
  `appid` varchar(1000) DEFAULT '' COMMENT 'appid',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `ordersn` varchar(255) NOT NULL COMMENT '订单号',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `paytype` tinyint(2) NOT NULL COMMENT '1：置顶',
  `house_id` int(10) NOT NULL,
  `transid` varchar(20) NOT NULL,
  `formid` varchar(50) NOT NULL COMMENT '推送码',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为成功',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '入驻金额',
  `top_day` int(10) NOT NULL DEFAULT '0' COMMENT '置顶天数',
  `createtime` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `prepay_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `code` varchar(255) DEFAULT '' COMMENT '验证码code',
  `mobile` varchar(50) NOT NULL DEFAULT '手机号',
  `status` tinyint(2) NOT NULL COMMENT '0未使用，1使用',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `mobile_verify_status` tinyint(1) DEFAULT '1' COMMENT '短信验证码',
  `logo` varchar(255) DEFAULT NULL,
  `adv_day` int(10) DEFAULT '7' COMMENT '广告到期天数',
  `adv_fee` decimal(10,2) DEFAULT '0.00' COMMENT '广告费用',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `systel` varchar(255) DEFAULT '' COMMENT '联系电话',
  `enable` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否官方客服',
  `isshare` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否开启分享',
  `iscreate` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否开启生成图片',
  `sms_user` varchar(50) NOT NULL DEFAULT '',
  `enter_price` int(10) NOT NULL DEFAULT '0' COMMENT '入驻金额',
  `sms_secret` varchar(80) NOT NULL,
  `sms_type` tinyint(2) NOT NULL COMMENT '0阿里大于老接口，1新接口',
  `sms_template_code` text NOT NULL COMMENT '短信模板Code',
  `sms_free_sign_name` text NOT NULL COMMENT '阿里大鱼短信签名',
  `reg_sms_code` varchar(50) NOT NULL,
  `qqmap_ak` varchar(500) NOT NULL DEFAULT '',
  `mp` varchar(500) NOT NULL COMMENT '公众号图片',
  `share_title` varchar(500) NOT NULL DEFAULT '',
  `share_desc` varchar(500) NOT NULL DEFAULT '',
  `share_thumb` varchar(500) NOT NULL DEFAULT '',
  `public_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否开启分享',
  `baidu_ak` varchar(500) NOT NULL DEFAULT '' COMMENT '是否官方客服',
  `public_price` int(10) NOT NULL DEFAULT '0' COMMENT '入驻金额',
  `top_price` int(10) NOT NULL DEFAULT '0' COMMENT '入驻金额',
  `collect_tpl` varchar(255) DEFAULT NULL COMMENT '名片新增收藏模板通知',
  `zan_tpl` varchar(255) DEFAULT NULL COMMENT '名片新增点赞模板通知',
  `save_tpl` varchar(255) DEFAULT NULL COMMENT '名片保存模板通知',
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `exchange` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启分销',
  `wxapp_name1` varchar(255) NOT NULL DEFAULT '' COMMENT '分享',
  `wxapp_url1` varchar(255) NOT NULL DEFAULT '' COMMENT '分享',
  `wxapp_name2` varchar(255) NOT NULL DEFAULT '' COMMENT '分享',
  `wxapp_url2` varchar(255) NOT NULL DEFAULT '' COMMENT '分享',
  `is_style` tinyint(2) NOT NULL DEFAULT '0' COMMENT '风格',
  `is_close_shop` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否关闭门店',
  `rule` longtext NOT NULL,
  `sets` longtext NOT NULL COMMENT '后增字段',
  `show_top` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启',
  `is_pay_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付成功免审核',
  `is_shield` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否屏蔽电话号码',
  `check` tinyint(2) NOT NULL DEFAULT '0' COMMENT '分享',
  `aduit_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '审核模板',
  `is_custom` tinyint(2) NOT NULL DEFAULT '0' COMMENT '客户定制页面',
  `public_credit` int(10) NOT NULL DEFAULT '0' COMMENT '发布积分',
  `share_credit` int(10) NOT NULL COMMENT '分享积分',
  `pay_credit` int(10) NOT NULL DEFAULT '0' COMMENT '支付积分',
  `isrent` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否开启求购求租',
  `appname` varchar(50) NOT NULL COMMENT '置顶信息',
  `appid` varchar(50) NOT NULL COMMENT '置顶信息',
  `limit_credit` int(10) NOT NULL DEFAULT '0' COMMENT '每日限制积分',
  `bgcolor` varchar(25) NOT NULL COMMENT '置顶信息',
  `indexname` varchar(25) NOT NULL COMMENT '置顶信息',
  `notice_sms_code` varchar(100) NOT NULL COMMENT '短信通知模板code',
  `isenable` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否官方客服',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_tplcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `code` varchar(255) DEFAULT '' COMMENT '推送码',
  `openid` varchar(50) NOT NULL DEFAULT 'openid',
  `status` tinyint(2) NOT NULL COMMENT '0未使用，1使用',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_zhandui` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '战队名称',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `desc` varchar(255) DEFAULT NULL,
  `avater` varchar(1024) NOT NULL DEFAULT '' COMMENT '头像',
  `qrcode` varchar(1024) NOT NULL DEFAULT '' COMMENT '头像',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_wxapp_zhandui_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `zhandui_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '战队Id',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_laotouzi_ctdr_morewxapp` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `uniacid` bigint(11) DEFAULT NULL,
  `wxappname` varchar(50) DEFAULT NULL,
  `synopsis` varchar(100) DEFAULT NULL,
  `appid` varchar(64) DEFAULT NULL,
  `sort_rank` bigint(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `defaultpage` varchar(255) DEFAULT NULL,
  `qrcodepath` varchar(255) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxapp_card_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `bg` varchar(255) DEFAULT '',
  `data` text,
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxapp_general_analysis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `session_cnt` int(10) NOT NULL,
  `visit_pv` int(10) NOT NULL,
  `visit_uv` int(10) NOT NULL,
  `visit_uv_new` int(10) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `stay_time_uv` varchar(10) NOT NULL,
  `stay_time_session` varchar(10) NOT NULL,
  `visit_depth` varchar(10) NOT NULL,
  `ref_date` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `ref_date` (`ref_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxapp_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `version` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `modules` varchar(1000) NOT NULL,
  `design_method` tinyint(1) NOT NULL,
  `template` int(10) NOT NULL,
  `quickmenu` varchar(2500) NOT NULL,
  `createtime` int(10) NOT NULL,
  `type` int(2) NOT NULL,
  `entry_id` int(11) NOT NULL,
  `appjson` text NOT NULL,
  `default_appjson` text NOT NULL,
  `use_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `version` (`version`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxapp_wxapp_agent_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `org_price` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `now_price` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `direct_ratio` int(11) DEFAULT '5' COMMENT '直接比例',
  `indirect_ratio` int(11) DEFAULT '5' COMMENT '间隔比例',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('amouse_wxapp_card',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'categoryId')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `categoryId` int(10) NOT NULL DEFAULT '0' COMMENT '分类id';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `mobile` varchar(18) DEFAULT '' COMMENT '手机号';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'username')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `username` varchar(100) DEFAULT NULL COMMENT '用户名';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'email')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `email` varchar(100) DEFAULT NULL COMMENT '邮箱';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `weixin` varchar(100) DEFAULT NULL COMMENT '微信号';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'company')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `company` varchar(100) DEFAULT NULL COMMENT '公司';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'job')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `job` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'fromId')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `fromId` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `qq` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'industry')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `industry` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'department')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `department` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `desc` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'imgs')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `imgs` text;");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'vip')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `vip` tinyint(1) DEFAULT '0' COMMENT '0:非vip，1vip';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'zan')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `zan` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'clazz')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `clazz` varchar(10) DEFAULT 'default' COMMENT '模板';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'view')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `view` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `lng` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `lat` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '0表示未审核，1表示已审核，2表示禁用';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'audit_status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `audit_status` tinyint(1) DEFAULT '0' COMMENT '0表示未审核，1表示已审核，2表示禁用';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'address')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `address` varchar(255) DEFAULT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'collect')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `collect` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'avater')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `avater` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'weixinImg')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `weixinImg` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `listorder` int(10) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `qrcode` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card',  'qrcode2')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD `qrcode2` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('amouse_wxapp_card',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `cardid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `from_user` varchar(255) NOT NULL COMMENT '自己';");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'zan_mid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `zan_mid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'zan_cid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `zan_cid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'to_user')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `to_user` varchar(255) NOT NULL COMMENT '好友';");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'sms_type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `sms_type` tinyint(2) NOT NULL COMMENT '0:看，1:赞 2:收藏';");
}
if(!pdo_fieldexists('amouse_wxapp_card_history',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_indexexists('amouse_wxapp_card_history',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_history')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'name')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `name` varchar(50) NOT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'click')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `click` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `status` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `appid` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `qrcode` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'url')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `url` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_card_slide',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD `endtime` int(10) unsigned NOT NULL COMMENT '广告结束时间';");
}
if(!pdo_indexexists('amouse_wxapp_card_slide',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_card_slide')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_category')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('amouse_wxapp_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('amouse_wxapp_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('amouse_wxapp_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_category')." ADD `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists('amouse_wxapp_category',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_category')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `thumb` text;");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `type` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `credit` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `stock` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `credit2` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'money')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `total` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'totalday')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `totalday` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'detail')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `detail` text;");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `description` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'vip')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `vip` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'istop')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `istop` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `isrecommand` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_goods',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD `endtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('amouse_wxapp_creditshop_goods',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_wxapp_creditshop_goods',  'idx_endtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD KEY `idx_endtime` (`endtime`);");
}
if(!pdo_indexexists('amouse_wxapp_creditshop_goods',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('amouse_wxapp_creditshop_goods',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('amouse_wxapp_creditshop_goods',  'idx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD KEY `idx_displayorder` (`displayorder`);");
}
if(!pdo_indexexists('amouse_wxapp_creditshop_goods',  'idx_istop')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_goods')." ADD KEY `idx_istop` (`istop`);");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'address_phone')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `address_phone` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'address_name')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `address_name` varchar(255) DEFAULT '' COMMENT '收货人';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'address')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `address` varchar(255) DEFAULT '' COMMENT '收货地址';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `goodsid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `status` tinyint(3) DEFAULT '0' COMMENT '0-未发货 1-已发货 3 取消';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'usetime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `usetime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'express')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `express` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'expresscom')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `expresscom` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_creditshop_log',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_creditshop_log')." ADD `expresssn` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `realname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `mobile` varchar(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'address')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `address` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'vip')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `vip` tinyint(1) DEFAULT '0' COMMENT '0vip，1非vip';");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `sex` tinyint(1) DEFAULT '0' COMMENT '1男，2女';");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'myattention')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `myattention` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'myfocus')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `myfocus` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'companyAddress')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `companyAddress` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '0表示已审核，1表示未审核，2表示禁用';");
}
if(!pdo_fieldexists('amouse_wxapp_member',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD `desc` varchar(255) DEFAULT NULL;");
}
if(!pdo_indexexists('amouse_wxapp_member',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_member')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `title` varchar(1000) DEFAULT '' COMMENT '导航名称';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'followurl')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `followurl` varchar(1000) DEFAULT '' COMMENT '网页链接';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `thumb` varchar(1000) DEFAULT '' COMMENT '底部图片';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'click')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `click` tinyint(1) DEFAULT '0' COMMENT '小程序点击类型 0 直接进入小程序 1:弹出小程序二维码';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '跳转类型 0:小程序 1:web网页';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `recommend` tinyint(1) DEFAULT '1' COMMENT '是否推荐';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `bgcolor` varchar(1000) DEFAULT '' COMMENT '背景';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `qrcode` varchar(1000) DEFAULT '' COMMENT '小程序二维码';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'info')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `info` varchar(1000) DEFAULT '' COMMENT '介绍';");
}
if(!pdo_fieldexists('amouse_wxapp_navs',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD `appid` varchar(1000) DEFAULT '' COMMENT 'appid';");
}
if(!pdo_indexexists('amouse_wxapp_navs',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_navs')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `ordersn` varchar(255) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `paytype` tinyint(2) NOT NULL COMMENT '1：置顶';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'house_id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `house_id` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `transid` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'formid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `formid` varchar(50) NOT NULL COMMENT '推送码';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为成功';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '入驻金额';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'top_day')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `top_day` int(10) NOT NULL DEFAULT '0' COMMENT '置顶天数';");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'module')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `module` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_order',  'prepay_id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD `prepay_id` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('amouse_wxapp_order',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_order')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'total')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `total` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'code')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `code` varchar(255) DEFAULT '' COMMENT '验证码code';");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `mobile` varchar(50) NOT NULL DEFAULT '手机号';");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `status` tinyint(2) NOT NULL COMMENT '0未使用，1使用';");
}
if(!pdo_fieldexists('amouse_wxapp_sms',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_indexexists('amouse_wxapp_sms',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sms')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'mobile_verify_status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `mobile_verify_status` tinyint(1) DEFAULT '1' COMMENT '短信验证码';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `logo` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'adv_day')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `adv_day` int(10) DEFAULT '7' COMMENT '广告到期天数';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'adv_fee')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `adv_fee` decimal(10,2) DEFAULT '0.00' COMMENT '广告费用';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `copyright` varchar(255) DEFAULT '' COMMENT '版权';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'systel')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `systel` varchar(255) DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `enable` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否官方客服';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'isshare')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `isshare` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否开启分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'iscreate')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `iscreate` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否开启生成图片';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'sms_user')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `sms_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'enter_price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `enter_price` int(10) NOT NULL DEFAULT '0' COMMENT '入驻金额';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'sms_secret')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `sms_secret` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'sms_type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `sms_type` tinyint(2) NOT NULL COMMENT '0阿里大于老接口，1新接口';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'sms_template_code')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `sms_template_code` text NOT NULL COMMENT '短信模板Code';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'sms_free_sign_name')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `sms_free_sign_name` text NOT NULL COMMENT '阿里大鱼短信签名';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'reg_sms_code')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `reg_sms_code` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'qqmap_ak')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `qqmap_ak` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'mp')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `mp` varchar(500) NOT NULL COMMENT '公众号图片';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `share_title` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `share_desc` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'share_thumb')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `share_thumb` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'public_status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `public_status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否开启分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'baidu_ak')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `baidu_ak` varchar(500) NOT NULL DEFAULT '' COMMENT '是否官方客服';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'public_price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `public_price` int(10) NOT NULL DEFAULT '0' COMMENT '入驻金额';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'top_price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `top_price` int(10) NOT NULL DEFAULT '0' COMMENT '入驻金额';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'collect_tpl')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `collect_tpl` varchar(255) DEFAULT NULL COMMENT '名片新增收藏模板通知';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'zan_tpl')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `zan_tpl` varchar(255) DEFAULT NULL COMMENT '名片新增点赞模板通知';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'save_tpl')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `save_tpl` varchar(255) DEFAULT NULL COMMENT '名片保存模板通知';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'is_public')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'exchange')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `exchange` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启分销';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'wxapp_name1')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `wxapp_name1` varchar(255) NOT NULL DEFAULT '' COMMENT '分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'wxapp_url1')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `wxapp_url1` varchar(255) NOT NULL DEFAULT '' COMMENT '分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'wxapp_name2')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `wxapp_name2` varchar(255) NOT NULL DEFAULT '' COMMENT '分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'wxapp_url2')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `wxapp_url2` varchar(255) NOT NULL DEFAULT '' COMMENT '分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'is_style')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `is_style` tinyint(2) NOT NULL DEFAULT '0' COMMENT '风格';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'is_close_shop')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `is_close_shop` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否关闭门店';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `rule` longtext NOT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'sets')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `sets` longtext NOT NULL COMMENT '后增字段';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'show_top')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `show_top` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开启';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'is_pay_public')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `is_pay_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付成功免审核';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'is_shield')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `is_shield` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否屏蔽电话号码';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'check')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `check` tinyint(2) NOT NULL DEFAULT '0' COMMENT '分享';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'aduit_tpl')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `aduit_tpl` varchar(255) NOT NULL DEFAULT '' COMMENT '审核模板';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'is_custom')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `is_custom` tinyint(2) NOT NULL DEFAULT '0' COMMENT '客户定制页面';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'public_credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `public_credit` int(10) NOT NULL DEFAULT '0' COMMENT '发布积分';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'share_credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `share_credit` int(10) NOT NULL COMMENT '分享积分';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'pay_credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `pay_credit` int(10) NOT NULL DEFAULT '0' COMMENT '支付积分';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'isrent')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `isrent` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否开启求购求租';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'appname')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `appname` varchar(50) NOT NULL COMMENT '置顶信息';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `appid` varchar(50) NOT NULL COMMENT '置顶信息';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'limit_credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `limit_credit` int(10) NOT NULL DEFAULT '0' COMMENT '每日限制积分';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `bgcolor` varchar(25) NOT NULL COMMENT '置顶信息';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'indexname')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `indexname` varchar(25) NOT NULL COMMENT '置顶信息';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'notice_sms_code')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `notice_sms_code` varchar(100) NOT NULL COMMENT '短信通知模板code';");
}
if(!pdo_fieldexists('amouse_wxapp_sysset',  'isenable')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD `isenable` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否官方客服';");
}
if(!pdo_indexexists('amouse_wxapp_sysset',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_sysset')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_tplcode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_tplcode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_tplcode',  'code')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD `code` varchar(255) DEFAULT '' COMMENT '推送码';");
}
if(!pdo_fieldexists('amouse_wxapp_tplcode',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD `openid` varchar(50) NOT NULL DEFAULT 'openid';");
}
if(!pdo_fieldexists('amouse_wxapp_tplcode',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD `status` tinyint(2) NOT NULL COMMENT '0未使用，1使用';");
}
if(!pdo_fieldexists('amouse_wxapp_tplcode',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_indexexists('amouse_wxapp_tplcode',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_tplcode')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'name')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `name` varchar(50) NOT NULL COMMENT '战队名称';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `desc` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'avater')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `avater` varchar(1024) NOT NULL DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `qrcode` varchar(1024) NOT NULL DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui_log')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui_log',  'zhandui_id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui_log')." ADD `zhandui_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '战队Id';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui_log')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('amouse_wxapp_zhandui_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_wxapp_zhandui_log')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'id')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `id` bigint(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `uniacid` bigint(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'wxappname')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `wxappname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'synopsis')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `synopsis` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `appid` varchar(64) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'sort_rank')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `sort_rank` bigint(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `logo` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'defaultpage')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `defaultpage` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'qrcodepath')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `qrcodepath` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('laotouzi_ctdr_morewxapp',  'type')) {
	pdo_query("ALTER TABLE ".tablename('laotouzi_ctdr_morewxapp')." ADD `type` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('wxapp_card_poster',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wxapp_card_poster',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wxapp_card_poster',  'bg')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD `bg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('wxapp_card_poster',  'data')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD `data` text;");
}
if(!pdo_fieldexists('wxapp_card_poster',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('wxapp_card_poster',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('wxapp_card_poster',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_card_poster')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'session_cnt')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `session_cnt` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'visit_pv')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `visit_pv` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'visit_uv')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `visit_uv` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'visit_uv_new')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `visit_uv_new` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `type` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'stay_time_uv')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `stay_time_uv` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'stay_time_session')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `stay_time_session` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'visit_depth')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `visit_depth` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_general_analysis',  'ref_date')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD `ref_date` varchar(8) NOT NULL;");
}
if(!pdo_indexexists('wxapp_general_analysis',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('wxapp_general_analysis',  'ref_date')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_general_analysis')." ADD KEY `ref_date` (`ref_date`);");
}
if(!pdo_fieldexists('wxapp_versions',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wxapp_versions',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'multiid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `multiid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'version')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `version` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'description')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'modules')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `modules` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'design_method')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `design_method` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'template')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `template` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'quickmenu')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `quickmenu` varchar(2500) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `type` int(2) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'entry_id')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `entry_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'appjson')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `appjson` text NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'default_appjson')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `default_appjson` text NOT NULL;");
}
if(!pdo_fieldexists('wxapp_versions',  'use_default')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD `use_default` tinyint(1) NOT NULL;");
}
if(!pdo_indexexists('wxapp_versions',  'version')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD KEY `version` (`version`);");
}
if(!pdo_indexexists('wxapp_versions',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_versions')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'org_price')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `org_price` decimal(10,2) DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'now_price')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `now_price` decimal(10,2) DEFAULT '0.00' COMMENT '现价';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'direct_ratio')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `direct_ratio` int(11) DEFAULT '5' COMMENT '直接比例';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'indirect_ratio')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `indirect_ratio` int(11) DEFAULT '5' COMMENT '间隔比例';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `remark` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wxapp_wxapp_agent_set',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('wxapp_wxapp_agent_set',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('wxapp_wxapp_agent_set',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('wxapp_wxapp_agent_set')." ADD KEY `idx_createtime` (`createtime`);");
}

?>