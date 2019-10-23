<?php
$code = pdo_get("sqtg_sun_acode",array("id"=>2));
if (!$code || $code['code'] < 10313){
    $sql="
        DROP TABLE IF EXISTS `ims_sqtg_sun_pinclassify`;
        CREATE TABLE `ims_sqtg_sun_pinclassify`  (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类名称',
          `sort` int(5) NULL DEFAULT 0,
          `state` tinyint(1) NULL DEFAULT 1,
          `create_time` int(11) NULL DEFAULT NULL,
          `uniacid` int(11) NULL DEFAULT NULL,
          `is_del` int(1) NULL DEFAULT 0 COMMENT '1',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '拼团分类表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pingoods
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pingoods`;
        CREATE TABLE `ims_sqtg_sun_pingoods`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `uniacid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `store_id` int(11) NULL DEFAULT 0,
          `cid` int(11) NULL DEFAULT 0 COMMENT '商品分类id',
          `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名称',
          `unit` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '个' COMMENT '单位',
          `weight` double(10, 2) NULL DEFAULT 0.00 COMMENT '重量',
          `sort` int(11) NULL DEFAULT 0 COMMENT '排序 从大到小',
          `pic` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品缩略图(封面图)',
          `pics` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品轮播图',
          `price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '单购价',
          `pin_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '拼购价',
          `original_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '商品原价展示使用',
          `details` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品详细',
          `service` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '服务内容(正品保障)',
          `postagerules_id` int(11) NULL DEFAULT NULL COMMENT '运费模板id',
          `stock` int(11) NULL DEFAULT 0 COMMENT '库存',
          `sales_num` int(11) NULL DEFAULT 0 COMMENT '销量 销量支付完成',
          `sales_xnnum` int(11) NULL DEFAULT 0 COMMENT '虚拟销量',
          `use_attr` int(4) NULL DEFAULT 0 COMMENT '1使用规格',
          `attr` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规格库存和价格',
          `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
          `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
          `state` int(4) NULL DEFAULT 1 COMMENT '1启用状态',
          `is_hot` int(4) NULL DEFAULT 0 COMMENT '是否推荐',
          `check_state` int(4) NULL DEFAULT 1 COMMENT '1未审核 2审核成功 3审核失败',
          `fail_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `is_ladder` tinyint(1) NULL DEFAULT 0 COMMENT '1开启阶梯团',
          `ladder_info` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
          `limit_num` int(11) NULL DEFAULT 0 COMMENT '单次购买数量',
          `limit_times` int(11) NULL DEFAULT 0 COMMENT '购买次数限制',
          `group_num` int(11) NULL DEFAULT 0 COMMENT '实际成团数',
          `group_xnnum` int(11) NULL DEFAULT 0 COMMENT '虚拟成团数',
          `sendtype` tinyint(1) NULL DEFAULT 1 COMMENT '1.到店 2.物流',
          `need_num` int(1) NULL DEFAULT 2 COMMENT '开团人数',
          `is_group_coupon` tinyint(1) NULL DEFAULT 0 COMMENT '1开启团长优惠',
          `coupon_money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '团长优惠金额',
          `coupon_discount` int(11) NULL DEFAULT 0 COMMENT '团长优惠折扣',
          `group_time` int(11) NULL DEFAULT 0 COMMENT '组团限时',
          `pay_time` int(11) NULL DEFAULT 0 COMMENT '付款限时',
          `start_time` int(11) NULL DEFAULT NULL,
          `end_time` int(11) NULL DEFAULT NULL,
          `is_stock` tinyint(1) NULL DEFAULT 0 COMMENT '1.限时库存',
          `is_del` tinyint(1) NULL DEFAULT 0 COMMENT '1.删除',
          `is_alonepay` tinyint(1) NULL DEFAULT 1 COMMENT '0关闭单购',
          `mandatory` tinyint(1) NULL DEFAULT 0 COMMENT '1强制上架 0默认',
          `leader_draw_type` int(4) NULL DEFAULT 0,
          `leader_draw_rate` decimal(10, 2) NULL DEFAULT NULL,
          `leader_draw_money` decimal(10, 2) NULL DEFAULT NULL,
          `delivery_fee` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '配送费',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pingoodsattr
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pingoodsattr`;
        CREATE TABLE `ims_sqtg_sun_pingoodsattr`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `uniacid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规格名称',
          `goods_id` int(11) NULL DEFAULT NULL,
          `goodsattrgroup_id` int(11) NULL DEFAULT NULL,
          `create_time` int(11) NULL DEFAULT NULL,
          `update_time` int(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品规格表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pingoodsattrgroup
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pingoodsattrgroup`;
        CREATE TABLE `ims_sqtg_sun_pingoodsattrgroup`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `uniacid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规格分组名称',
          `goods_id` int(11) NULL DEFAULT NULL,
          `create_time` int(11) NULL DEFAULT NULL,
          `update_time` int(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品规格分组表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pingoodsattrsetting
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pingoodsattrsetting`;
        CREATE TABLE `ims_sqtg_sun_pingoodsattrsetting`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `uniacid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `key` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规格名称列表',
          `attr_ids` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规格ids',
          `goods_id` int(11) NULL DEFAULT NULL,
          `stock` int(11) NULL DEFAULT 0 COMMENT '库存',
          `price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '单购价',
          `weight` double(10, 2) NULL DEFAULT 0.00 COMMENT '重量',
          `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '编码',
          `pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '封面图',
          `create_time` int(11) NULL DEFAULT NULL,
          `update_time` int(11) NULL DEFAULT NULL,
          `pin_price` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '拼团价',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商品规格设置表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pinheads
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pinheads`;
        CREATE TABLE `ims_sqtg_sun_pinheads`  (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `groupnum` int(11) NULL DEFAULT 0 COMMENT '成团人数',
          `groupmoney` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '成团价钱',
          `user_id` int(11) NULL DEFAULT NULL,
          `create_time` int(11) NULL DEFAULT NULL,
          `uniacid` int(11) NULL DEFAULT NULL,
          `ladder_id` int(11) NULL DEFAULT 0 COMMENT '阶梯团id',
          `status` tinyint(1) NULL DEFAULT 0 COMMENT '1.开团成功 2.拼团成功 3.拼团失败',
          `oid` int(11) NULL DEFAULT NULL,
          `expire_time` int(11) NULL DEFAULT 0 COMMENT '到期时间',
          `update_time` int(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '团长表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pinladder
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pinladder`;
        CREATE TABLE `ims_sqtg_sun_pinladder`  (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `goods_id` int(11) NULL DEFAULT NULL,
          `groupnum` int(11) NULL DEFAULT 2 COMMENT '组团人数',
          `groupmoney` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '组团价格',
          `create_time` int(11) NULL DEFAULT NULL,
          `uniacid` int(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '阶梯团规则表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pinorder
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pinorder`;
        CREATE TABLE `ims_sqtg_sun_pinorder`  (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NULL DEFAULT NULL,
          `heads_id` int(11) NULL DEFAULT 0 COMMENT '团id',
          `leader_id` int(11) NULL DEFAULT NULL,  
          `is_head` tinyint(1) NULL DEFAULT 0 COMMENT '1.团长',
          `order_num` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
          `store_id` int(11) NULL DEFAULT NULL,
          `uniacid` int(11) NULL DEFAULT NULL,
          `out_trade_no` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `transaction_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `goods_id` int(11) NULL DEFAULT NULL,
          `attr_ids` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `attr_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `order_amount` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '订单金额（含运费）',
          `sincetype` tinyint(1) NULL DEFAULT 1 COMMENT '配送方式 1快递 2到店自提',
          `distribution` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '运费',
          `num` int(11) NULL DEFAULT 1 COMMENT '购买数量',
          `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '单价',
          `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `area` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `express_delivery` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递公司',
          `express_orderformid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号',
          `create_time` int(11) NULL DEFAULT NULL,
          `is_pay` tinyint(1) NULL DEFAULT 0 COMMENT '1.已付款',
          `pay_type` tinyint(1) NULL DEFAULT 1 COMMENT '1.微信支付 2.零钱支付',
          `pay_time` int(11) NULL DEFAULT NULL,
          `use_num` int(11) NULL DEFAULT 0 COMMENT '核销份数',
          `use_time` int(11) NULL DEFAULT NULL,
          `qrcode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '核销码',
          `order_status` tinyint(1) NULL DEFAULT 0 COMMENT '0.未付款 1.已付款 ',
          `prepay_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
          `expire_time` int(11) NULL DEFAULT 0 COMMENT '支付过期时间',
          `coupon_money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '优惠金额',
          `is_del` tinyint(1) NULL DEFAULT 0 COMMENT '1.已删除(过期未支付)',
          `is_show` tinyint(1) NULL DEFAULT 1 COMMENT '0.删除（订单列表不显示）',
          `shop_id` int(11) NULL DEFAULT 0 COMMENT '门店id',
          `is_refund` tinyint(1) NULL DEFAULT 0 COMMENT '1退款成功 2退款失败',
          `refund_time` int(11) NULL DEFAULT 0 COMMENT '退款时间',
          `refund_num` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '退款单号',
          `update_time` int(11) NULL DEFAULT NULL,
          `send_time` int(11) NULL DEFAULT 0 COMMENT '发货时间',
          `group_time` int(11) NULL DEFAULT 0 COMMENT '成团时间',
          `finish_time` int(11) NULL DEFAULT 0 COMMENT '完成时间',
          `is_comment`  tinyint(1) NULL DEFAULT 0 COMMENT '1.已经评论',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单表' ROW_FORMAT = Compact;
        
        -- ----------------------------
        -- Table structure for ims_sqtg_sun_pinrefund
        -- ----------------------------
        DROP TABLE IF EXISTS `ims_sqtg_sun_pinrefund`;
        CREATE TABLE `ims_sqtg_sun_pinrefund`  (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `uniacid` int(11) NULL DEFAULT NULL,
          `store_id` int(11) NOT NULL DEFAULT 0,
          `oid` int(11) NULL DEFAULT NULL COMMENT '订单id',
          `heads_id` int(11) NULL DEFAULT NULL,
          `refund_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '退款方式 1微信退款 2余额退款',
          `order_refund_no` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '退款单号',
          `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1仅退款',
          `refund_price` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '退款金额',
          `create_time` int(11) NULL DEFAULT NULL,
          `refund_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1退款成功 2退款失败',
          `refund_time` tinyint(11) NULL DEFAULT NULL COMMENT '退款时间',
          `err_code` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '退款失败错误码',
          `err_code_dec` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '错误信息',
          `xml` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '退款报文',
          `update_time` int(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '拼团退款记录表' ROW_FORMAT = Compact;
        
        SET FOREIGN_KEY_CHECKS = 1;
        
        #自己新加的
        DROP TABLE IF EXISTS `ims_sqtg_sun_leaderpingoods`;
        CREATE TABLE `ims_sqtg_sun_leaderpingoods`  (
          `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
          `create_time` int(11) NULL DEFAULT NULL,
          `leader_id` int(11) NULL DEFAULT NULL,
          `uniacid` int(11) NULL DEFAULT NULL,
          `goods_id` int(11) NULL DEFAULT NULL,
          `store_id` int(11) NULL DEFAULT NULL,
          `update_time` int(11) NULL DEFAULT NULL,
          PRIMARY KEY (`id`) USING BTREE,
          UNIQUE INDEX `id`(`id`) USING BTREE
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '社区团长拼团商品表' ROW_FORMAT = Compact;
        
        INSERT INTO ".tablename('sqtg_sun_menu')."(`name`, `create_time`, `update_time`, `memo`, `index`, `menugroup_id`, `control`, `action`, `icon`, `menu_id`, `state`, `store_show`) VALUES ('拼团管理', 1540797468, 1540797468, '', 6, 663, '', '', 'fa fa-cubes', 0, 1, 1);
        INSERT INTO ".tablename('sqtg_sun_menu')."( `name`, `create_time`, `update_time`, `memo`, `index`, `menugroup_id`, `control`, `action`, `icon`, `menu_id`, `state`, `store_show`) VALUES ( '分类列表', 1540797566, 1540797566, '', 0, 663, 'Cpinclassify', 'index', 'fa fa-external-link', 765, 1, 1);
        INSERT INTO ".tablename('sqtg_sun_menu')."( `name`, `create_time`, `update_time`, `memo`, `index`, `menugroup_id`, `control`, `action`, `icon`, `menu_id`, `state`, `store_show`) VALUES ( '商品列表', 1540801448, 1540801448, '', 1, 663, 'Cpingoods', 'index', 'fa fa-external-link', 765, 1, 1);
        INSERT INTO ".tablename('sqtg_sun_menu')."( `name`, `create_time`, `update_time`, `memo`, `index`, `menugroup_id`, `control`, `action`, `icon`, `menu_id`, `state`, `store_show`) VALUES ( '拼团设置', 1541139381, 1541139381, '', 3, 663, 'Cpingoods', 'pinset', 'fa fa-external-link', 765, 1, 0);
        INSERT INTO ".tablename('sqtg_sun_menu')."( `name`, `create_time`, `update_time`, `memo`, `index`, `menugroup_id`, `control`, `action`, `icon`, `menu_id`, `state`, `store_show`) VALUES ( '订单列表', 1541555004, 1541555004, '', 3, 663, 'Cpingoods', 'orderlist', 'fa fa-external-link', 765, 1, 1);
        INSERT INTO ".tablename('sqtg_sun_menu')."( `name`, `create_time`, `update_time`, `memo`, `index`, `menugroup_id`, `control`, `action`, `icon`, `menu_id`, `state`, `store_show`) VALUES ( '商品审核', 1541659321, 1541659321, '', 5, 664, 'Cpingoods', 'checks', 'fa fa-external-link', 765, 1, 0);
            ";
    pdo_query($sql);
}