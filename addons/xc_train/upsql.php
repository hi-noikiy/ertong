 <?php
 function upsql(){
     if(!pdo_fieldexists('xc_train_video', 'link')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_video')." ADD `link` varchar(255) DEFAULT NULL COMMENT '链接'");
     }
     if(!pdo_fieldexists('xc_train_video', 'vid')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_video')." ADD `vid` varchar(50) DEFAULT NULL");
     }
     if(!pdo_fieldexists('xc_train_video', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_video')." ADD `type` int(11) DEFAULT '1' COMMENT '类型'");
     }
     if(!pdo_fieldexists('xc_train_school', 'sms')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_school')." ADD `sms` varchar(50) DEFAULT NULL COMMENT '接收短信'");
     }
     if(!pdo_fieldexists('xc_train_userinfo', 'shop_id')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_userinfo')." ADD `shop_id` int(11) DEFAULT NULL COMMENT '分校id'");
     }
     if(!pdo_tableexists('xc_train_nav')){
         $sql = <<<EOT
CREATE TABLE `ims_xc_train_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `simg` varchar(255) DEFAULT NULL COMMENT '图片',
  `link` varchar(255) DEFAULT NULL COMMENT '链接',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自定义导航';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_train_prize', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_prize')." ADD `type` int(11) DEFAULT '1'");
     }
     if(!pdo_fieldexists('xc_train_prize', 'pid')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_prize')." ADD `pid` int(11) DEFAULT NULL COMMENT '奖品id'");
     }
     if(!pdo_tableexists('xc_train_gua')){
         $sql = <<<EOT
CREATE TABLE `ims_xc_train_gua` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `bimg` varchar(255) DEFAULT NULL COMMENT '图片',
  `times` int(11) DEFAULT NULL COMMENT '概率',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='奖品';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_train_active', 'gua_img')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_active')." ADD `gua_img` varchar(255) DEFAULT NULL COMMENT '刮刮卡图片'");
     }
     if(!pdo_fieldexists('xc_train_active', 'list')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_active')." ADD `list` longtext COMMENT '奖品'");
     }
     if(!pdo_fieldexists('xc_train_active', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_active')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1集卡2刮刮卡）'");
     }
     if(!pdo_fieldexists('xc_train_service_team', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service_team')." ADD `type` int(11) DEFAULT '1' COMMENT '类型'");
     }
     if(!pdo_fieldexists('xc_train_order', 'can_use')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `can_use` int(11) DEFAULT '1' COMMENT '核销次数'");
     }
     if(!pdo_fieldexists('xc_train_order', 'is_use')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `is_use` int(11) DEFAULT '0' COMMENT '已核销次数'");
     }
     if(!pdo_fieldexists('xc_train_order', 'use_time')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `use_time` longtext COMMENT '核销时间'");
     }
     if(!pdo_fieldexists('xc_train_article', 'link_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_article')." ADD `link_type` int(11) DEFAULT '1' COMMENT '模式'");
     }
     if(!pdo_fieldexists('xc_train_teacher', 'content2')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service')." ADD `content2` longtext COMMENT '内容2'");
     }
     if(!pdo_fieldexists('xc_train_teacher', 'content_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service')." ADD `content_type` int(11) DEFAULT '1' COMMENT '课程模式'");
     }
     if(!pdo_fieldexists('xc_train_service', 'can_use')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service')." ADD `can_use` int(11) DEFAULT '1' COMMENT '核销次数'");
     }
     if(!pdo_fieldexists('xc_train_service', 'content2')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service')." ADD `content2` longtext COMMENT '内容2'");
     }
     if(!pdo_fieldexists('xc_train_service', 'content_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service')." ADD `content_type` int(11) DEFAULT '1' COMMENT '课程模式'");
     }

     if(!pdo_fieldexists('xc_train_discuss', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_discuss')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1课程2视频）'");
     }
     if(!pdo_fieldexists('xc_train_active', 'share_type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_active')." ADD `share_type` int(11) DEFAULT '1' COMMENT '分享类型（1分享2分享点击）'");
     }
     if(!pdo_tableexists('xc_train_video')){
         $sql = <<<EOT
CREATE TABLE `ims_xc_train_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `video` varchar(255) DEFAULT NULL COMMENT '视频',
  `bimg` varchar(255) DEFAULT NULL COMMENT '封面',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `pid` int(11) DEFAULT NULL COMMENT '课程id',
  `cid` int(11) DEFAULT NULL COMMENT '分类',
  `teacher_id` int(11) DEFAULT NULL COMMENT '主讲教师',
  `teacher_name` varchar(50) DEFAULT NULL COMMENT '教师姓名',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_train_video_class')){
         $sql = <<<EOT
CREATE TABLE `ims_xc_train_video_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频分来';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_train_order', 'store')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `store` int(11) DEFAULT NULL COMMENT '校区'");
     }
     if(!pdo_fieldexists('xc_train_order', 'content')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `content` longtext COMMENT '备注'");
     }
     if(!pdo_fieldexists('xc_train_school', 'longitude')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_school')." ADD `longitude` decimal(10,7) DEFAULT NULL COMMENT '经度'");
     }
     if(!pdo_fieldexists('xc_train_school', 'latitude')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_school')." ADD `latitude` decimal(10,7) DEFAULT NULL COMMENT '纬度'");
     }
     if(!pdo_tableexists('xc_train_login_log')){
         $sql = <<<EOT
CREATE TABLE `ims_xc_train_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL COMMENT '用户id',
  `plan_date` varchar(50) DEFAULT NULL COMMENT '日期',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='登录日志';
EOT;
         pdo_run($sql);
     }
     //2018-08-30
     if(!pdo_tableexists('xc_train_cut')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_train_cut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL COMMENT '课程id',
  `mark` varchar(255) DEFAULT NULL COMMENT '标记',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `is_member` int(11) DEFAULT '0' COMMENT '已有人数',
  `member` int(11) DEFAULT NULL COMMENT '人数',
  `join_member` int(11) DEFAULT '0' COMMENT '参与人数',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `cut_price` decimal(10,2) DEFAULT NULL COMMENT '最低价',
  `max_price` decimal(10,2) DEFAULT NULL COMMENT '砍价区间',
  `min_price` decimal(10,2) DEFAULT NULL COMMENT '砍价区间',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`pid`,`sort`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_train_cut_log')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_train_cut_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `cid` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL COMMENT '砍去的价格',
  `cut_openid` varchar(50) DEFAULT NULL COMMENT '帮砍的用户id',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`cid`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价记录';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_train_cut_order')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_train_cut_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `cid` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `is_min` int(11) DEFAULT '-1' COMMENT '最低价',
  `status` int(11) DEFAULT '-1' COMMENT '购买状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`is_min`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价订单';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_train_order', 'cut_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `cut_status` int(11) DEFAULT '-1' COMMENT '砍价'");
     }
     //2018-09-06
     if(!pdo_fieldexists('xc_train_service_class', 'type')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service_class')." ADD `type` int(11) DEFAULT '1' COMMENT '类型（1课程2名师）'");
     }
     if(!pdo_fieldexists('xc_train_teacher', 'cid')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_teacher')." ADD `cid` int(11) DEFAULT NULL COMMENT '分类'");
     }
     if(!pdo_tableexists('xc_train_address')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_train_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(50) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL COMMENT '用户id',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `sex` int(11) DEFAULT '1' COMMENT '性别',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `latitude` decimal(10,7) DEFAULT NULL COMMENT '经度',
  `longitude` decimal(10,7) DEFAULT NULL COMMENT '纬度',
  `content` longtext COMMENT '详情',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`openid`,`status`,`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地址';
EOT;
         pdo_run($sql);
     }
     if(!pdo_tableexists('xc_train_mall')){
         $sql = <<<EOT
CREATE TABLE IF NOT EXISTS `ims_xc_train_mall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `title` varchar(255) DEFAULT NULL COMMENT '副标题',
  `cid` int(11) DEFAULT NULL COMMENT '分类',
  `simg` varchar(255) DEFAULT NULL COMMENT '封面',
  `bimg` longtext,
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `format` longtext COMMENT '多规格',
  `sold` int(11) DEFAULT '0',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` int(11) DEFAULT '1' COMMENT '状态',
  `content` longtext COMMENT '详情',
  `createtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商城';
EOT;
         pdo_run($sql);
     }
     if(!pdo_fieldexists('xc_train_order', 'userinfo')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `userinfo` longtext COMMENT '用户信息'");
     }
     if(!pdo_fieldexists('xc_train_order', 'format')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `format` varchar(255) DEFAULT NULL COMMENT '规格'");
     }
     if(!pdo_fieldexists('xc_train_order', 'order_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `order_status` int(11) DEFAULT '-1' COMMENT '-1未发货1未收货2完成'");
     }
     if(!pdo_fieldexists('xc_train_service', 'code')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_service')." ADD `code` varchar(255) DEFAULT NULL COMMENT '二维码'");
     }
     //2018-09-07
     if(!pdo_fieldexists('xc_train_order', 'tui_status')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `tui_status` int(11) DEFAULT '-1' COMMENT '退款状态（-1未退款1退款）'");
     }
     if(!pdo_fieldexists('xc_train_order', 'tui_content')) {
         pdo_query("ALTER TABLE ".tablename('xc_train_order')." ADD `tui_content` longtext COMMENT '退款原因'");
     }

     $json = array('status' => 1, 'msg' => '更新成功');
     echo json_encode($json);
 }

