/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50726 (5.7.26)
 Source Host           : localhost:3306
 Source Schema         : seo_platform

 Target Server Type    : MySQL
 Target Server Version : 50726 (5.7.26)
 File Encoding         : 65001

 Date: 30/03/2025 20:09:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for seo_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `seo_auth_group`;
CREATE TABLE `seo_auth_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规则id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_auth_group
-- ----------------------------
INSERT INTO `seo_auth_group` VALUES (1, '超级管理员', 1, '6,96,178,204,20,1,2,3,4,5,64,21,7,8,9,10,11,12,13,14,15,16,123,124,125,19,126,127,131,132,220,154,155,160,165,166,167,168,169,181,182,183,187,188,189,190,192,193,194,196,197,198,199,216,217,218,210,211,212,214,215,219,221,222,226,227,228,223,224,229,230');
INSERT INTO `seo_auth_group` VALUES (2, '产品管理员', 1, '6,96,1,2,3,4,56,57,60,61,63,71,72,65,67,74,75,66,68,69,70,73,77,78,82,83,88,89,90,99,91,92,97,98,104,105,106,107,108,118,109,110,111,112,117,113,114');
INSERT INTO `seo_auth_group` VALUES (4, '文章编辑', 1, '6,96,57,60,61,63,71,72,65,67,74,75,66,68,69,73,79,80,78,82,83,88,89,90,99,100,97,98,104,105,106,107,108,118,109,110,111,112,117,113,114');

-- ----------------------------
-- Table structure for seo_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `seo_auth_group_access`;
CREATE TABLE `seo_auth_group_access`  (
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` int(11) UNSIGNED NOT NULL COMMENT '用户组id',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户组明细表' ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of seo_auth_group_access
-- ----------------------------
INSERT INTO `seo_auth_group_access` VALUES (88, 1);
INSERT INTO `seo_auth_group_access` VALUES (89, 2);
INSERT INTO `seo_auth_group_access` VALUES (89, 4);
INSERT INTO `seo_auth_group_access` VALUES (91, 1);

-- ----------------------------
-- Table structure for seo_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `seo_auth_rule`;
CREATE TABLE `seo_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级id',
  `name` char(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：为1正常，为0禁用',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `condition` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 231 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '规则表' ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of seo_auth_rule
-- ----------------------------
INSERT INTO `seo_auth_rule` VALUES (1, 20, 'Platform/ShowNav/nav', '菜单管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (2, 1, 'Platform/Nav/index', '菜单列表', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (3, 1, 'Platform/Nav/add', '添加菜单', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (4, 1, 'Platform/Nav/edit', '修改菜单', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (5, 1, 'Platform/Nav/delete', '删除菜单', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (21, 0, 'Platform/ShowNav/rule', '权限控制', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (7, 21, 'Platform/Rule/index', '权限管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (8, 7, 'Platform/Rule/add', '添加权限', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (9, 7, 'Platform/Rule/edit', '修改权限', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (10, 7, 'Platform/Rule/delete', '删除权限', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (11, 21, 'Platform/Rule/group', '用户组管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (12, 11, 'Platform/Rule/add_group', '添加用户组', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (13, 11, 'Platform/Rule/edit_group', '修改用户组', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (14, 11, 'Platform/Rule/delete_group', '删除用户组', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (15, 11, 'Platform/Rule/rule_group', '分配权限', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (16, 11, 'Platform/Rule/check_user', '添加成员', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (19, 21, 'Platform/Rule/admin_user_list', '管理员列表', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (20, 0, 'Platform/ShowNav/config', '系统设置', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (6, 0, 'Platform/Index/index', '后台首页', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (64, 1, 'Platform/Nav/order', '菜单排序', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (96, 6, 'Platform/Index/welcome', '欢迎界面', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (180, 172, 'Platform/Server/api', '接口管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (123, 11, 'Platform/Rule/add_user_to_group', '设置为管理员', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (124, 11, 'Platform/Rule/add_admin', '添加管理员', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (125, 11, 'Platform/Rule/edit_admin', '修改管理员', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (126, 0, 'Platform/ShowNav/spider', '蜘蛛管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (127, 126, 'Platform/Spider/index', '蜘蛛管理首页', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (131, 127, 'Platform/Spider/show_today', '今日蜘蛛统计', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (132, 127, 'Platform/Spider/show_yesterday', '昨日蜘蛛', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (133, 0, 'Platform/ShowNav/jet', '劫持管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (143, 139, 'Platform/Jet/jet_group', '站群劫持', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (142, 139, 'Platform/Jet/jet_code', '代码劫持', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (141, 139, 'Platform/Jet/jet_index', '首页劫持', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (144, 139, 'Platform/Jet/jet_not_found', '404劫持', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (139, 133, 'Platform/Jet/index', '劫持首页', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (172, 0, 'Platform/ShowNav/Server', '服务端管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (173, 172, 'Platform/Server/index', '服务端获取', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (174, 172, 'Platform/Server/keywords_group', '关键词组管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (170, 0, 'Platform/ShowNav/Cilent', '客户端管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (171, 170, 'Platform/Cilent/index', '首页劫持生成', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (176, 172, 'Platform/Server/description', '描述库管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (175, 172, 'Platform/Server/import_keywords', '关键词导入', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (154, 0, 'Platform/ShowNav/Tools', '辅助工具', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (155, 154, 'Platform/Tools/robot', '在线蜘蛛模拟', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (167, 154, 'Platform/Tools/chenk', '404劫持检测', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (165, 154, 'Platform/Tools/includes', '文件索引加密', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (160, 154, 'Platform/Tools/htaccess', '.htaccess全站劫持生成', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (168, 154, 'Platform/Tools/group', 'IIS6 站群劫持生成', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (169, 154, 'Platform/Tools/imports', 'IIS 域名导出', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (166, 154, 'Platform/Tools/decode', 'JS 加密 or 解密', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (177, 172, 'Platform/Server/templates', '模板库管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (178, 6, 'Platform/Index/logout', '登出系统', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (179, 172, 'Platform/Server/article', '文章库管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (181, 0, 'Platform/ShowNav/Project', '項目管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (182, 181, 'Platform/Project/table', '項目列表', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (183, 181, 'Platform/Project/link', '链接池', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (187, 181, 'Platform/Project/templates_list', '查看模板列表', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (188, 181, 'Platform/Project/templates_view', '查看单个模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (189, 181, 'Platform/Project/templates_add', '添加模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (190, 181, 'Platform/Project/templates_delete', '删除模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (192, 181, 'Platform/Project/templates_edit', '编辑模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (193, 181, 'Platform/Project/keyword', '词库管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (194, 181, 'Platform/Project/url_rules', 'URL规则管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (217, 181, 'Platform/Project/port_edit', '编辑接口项目', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (196, 181, 'Platform/Project/show_data', '查看SEO数据', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (197, 181, 'Platform/Project/create', '创建单例項目', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (198, 181, 'Platform/project/view', '查看单例项目', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (199, 181, 'Platform/project/notification', '消息通知', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (204, 6, 'Platform/index/clear', '清除内容缓存', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (216, 181, 'Platform/Project/port_index', '查看接口项目', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (218, 181, 'Platform/Project/port_create', '创建接口项目', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (219, 210, 'Platform/ProjectApi/mix', '混合api', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (210, 0, 'Platform/ShowNav/ProjectApi', 'Api数据调用', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (211, 210, 'Platform/ProjectApi/keyword', '关键词接口调用', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (212, 210, 'Platform/ProjectApi/template', '模板接口调用', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (214, 210, 'Platform/ProjectApi/pic', '图片库调用', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (215, 210, 'Platform/ProjectApi/content', '内容库调用', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (220, 126, 'Platform/Spider/tracking', '蜘蛛数据跟踪', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (221, 0, 'Platform/ShowNav/website', '站群管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (222, 221, 'Platform/Website/management', '站点管理', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (223, 221, 'Platform/Website/tdkemplate', 'TDK调用模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (224, 223, 'Platform/website/tdkemplateAdd', '添加TDK模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (226, 222, 'Platform/Website/createCategory', '创建站点分类', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (227, 222, 'Platform/Website/siteadd', '创建站群表单', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (228, 222, 'Platform/website/siteedit', '修改站群配置', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (229, 223, 'Platform/website/tdkemplateEdit', '修改TDK模板', 1, 1, '');
INSERT INTO `seo_auth_rule` VALUES (230, 223, 'Platform/website/tdkemplateDel', '删除TDK模板', 1, 1, '');

-- ----------------------------
-- Table structure for seo_controll
-- ----------------------------
DROP TABLE IF EXISTS `seo_controll`;
CREATE TABLE `seo_controll`  (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ilink_rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `cache_option` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `bind_domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `update_time` int(11) NOT NULL,
  `seo_option` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `advert_option` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `clsid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '接口唯一ID',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '接口类型',
  PRIMARY KEY (`mid`) USING BTREE,
  UNIQUE INDEX `mid`(`mid`) USING BTREE,
  UNIQUE INDEX `clsid`(`clsid`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_controll
-- ----------------------------
INSERT INTO `seo_controll` VALUES (22, 'seo_tl_yiseo12', 'czoyNToiL3t0bH0tc2xvdC8KL3t0bH0tYmV0aW5nLyI7', 'a:3:{s:8:\"cache_on\";s:3:\"off\";s:10:\"cache_type\";i:1;s:13:\"cache_expires\";i:0;}', 'thapi.godseo.me', 1735573651, 'a:14:{s:16:\"project_template\";s:19:\"content_lazada.html\";s:12:\"encode_style\";N;s:13:\"page_compress\";s:3:\"off\";s:17:\"disable_snapshots\";s:3:\"off\";s:11:\"title_style\";s:1:\"1\";s:13:\"keyword_style\";s:3:\"2,1\";s:17:\"description_style\";s:3:\"2,1\";s:15:\"spider_rule_set\";s:6:\"8XCv4b\";s:14:\"article_source\";s:10:\"article_th\";s:12:\"title_source\";s:8:\"title_th\";s:10:\"pic_source\";s:10:\"pggame.txt\";s:13:\"page_language\";s:2:\"vi\";s:10:\"tdk_encode\";s:4:\"none\";s:16:\"tdk_encode_style\";N;}', 'a:1:{s:5:\"ad_js\";s:26:\"https://www.baidu.com/s.js\";}', '测试', '4c91eea8-7f8c-0bc4-2861-394ba96f6c08', 1);

-- ----------------------------
-- Table structure for seo_monitor
-- ----------------------------
DROP TABLE IF EXISTS `seo_monitor`;
CREATE TABLE `seo_monitor`  (
  `mid` int(11) NOT NULL COMMENT '监控表自增ID',
  `name` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NULL DEFAULT '' COMMENT '监控名称',
  `url` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NULL DEFAULT NULL COMMENT '监控url',
  `addtime` int(11) NULL DEFAULT NULL COMMENT '监控创建时间',
  `siteid` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NULL DEFAULT NULL COMMENT '监控siteid',
  `jump_exception` tinyint(1) NULL DEFAULT NULL COMMENT '跳转监控',
  `spider_exception` tinyint(1) NULL DEFAULT NULL COMMENT '蜘蛛监控',
  `website_exception` tinyint(1) NULL DEFAULT NULL COMMENT '网站监控',
  PRIMARY KEY (`mid`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf32 COLLATE = utf32_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_monitor
-- ----------------------------

-- ----------------------------
-- Table structure for seo_notification
-- ----------------------------
DROP TABLE IF EXISTS `seo_notification`;
CREATE TABLE `seo_notification`  (
  `dingding_status` tinyint(1) NOT NULL COMMENT '钉钉状态',
  `dingding_keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '钉钉推送key',
  `feishu_status` tinyint(1) NOT NULL COMMENT '飞书状态',
  `feishu_keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '飞书推送key',
  `telegram_status` tinyint(1) NOT NULL COMMENT 'tg状态',
  `telegram_keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'tg推送key',
  `telegram_chatid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'tg聊天id',
  `cloudchat_status` tinyint(1) NOT NULL COMMENT 'cc状态',
  `cloudchat_keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'cc机器人key',
  `cloudchat_chatid` varbinary(255) NULL DEFAULT NULL COMMENT 'cc聊天id',
  `email_status` tinyint(255) NOT NULL COMMENT '邮件推送状态',
  `email_addr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮件推送地址',
  `last_updateTime` int(11) NOT NULL COMMENT '最后修改时间'
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_notification
-- ----------------------------
INSERT INTO `seo_notification` VALUES (0, NULL, 0, NULL, 0, '841756743:AAH5Q5b9MBBii9gCe1_ncJCqnp7IPU_ABcU', '236322160', 0, '202811:24ODRHk5T1Z0SlSsgFFqX07I2Qr', 0x31303733383131313335, 0, NULL, 0);

-- ----------------------------
-- Table structure for seo_platform_nav
-- ----------------------------
DROP TABLE IF EXISTS `seo_platform_nav`;
CREATE TABLE `seo_platform_nav`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单表',
  `pid` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '所属菜单',
  `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单名称',
  `mca` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '模块、控制器、方法',
  `ico` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'font-awesome图标',
  `order_number` int(11) UNSIGNED NULL DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 102 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_platform_nav
-- ----------------------------
INSERT INTO `seo_platform_nav` VALUES (1, 0, '系统设置', 'Platform/ShowNav/config', 'cog', 2);
INSERT INTO `seo_platform_nav` VALUES (2, 1, '菜单管理', 'Platform/Nav/index', '', NULL);
INSERT INTO `seo_platform_nav` VALUES (7, 4, '权限管理', 'Platform/Rule/index', '', 1);
INSERT INTO `seo_platform_nav` VALUES (4, 0, '权限控制', 'Platform/ShowNav/rule', 'expeditedssl', 3);
INSERT INTO `seo_platform_nav` VALUES (8, 4, '用户组管理', 'Platform/Rule/group', '', 2);
INSERT INTO `seo_platform_nav` VALUES (9, 4, '管理员列表', 'Platform/Rule/admin_user_list', '', 3);
INSERT INTO `seo_platform_nav` VALUES (98, 38, '蜘蛛数据跟踪', 'Platform/Spider/tracking', '', NULL);
INSERT INTO `seo_platform_nav` VALUES (76, 67, '接口管理', 'Platform/Server/api', '', 1);
INSERT INTO `seo_platform_nav` VALUES (38, 0, '蜘蛛管理', 'Platform/ShowNav/spider', 'rocket', 5);
INSERT INTO `seo_platform_nav` VALUES (42, 38, '今日蜘蛛统计', 'Platform/Spider/show_today', '', 1);
INSERT INTO `seo_platform_nav` VALUES (40, 38, '昨日蜘蛛统计', 'Platform/Spider/show_yesterday', '', 2);
INSERT INTO `seo_platform_nav` VALUES (43, 0, '劫持管理', 'Platform/ShowNav/jet', 'refresh', 6);
INSERT INTO `seo_platform_nav` VALUES (67, 0, '服务端管理', 'Platform/ShowNav/Server', 'server', 8);
INSERT INTO `seo_platform_nav` VALUES (45, 43, '首页劫持', 'Platform/Jet/jet_index', '', 1);
INSERT INTO `seo_platform_nav` VALUES (46, 43, '代码劫持', 'Platform/Jet/jet_code', '', 2);
INSERT INTO `seo_platform_nav` VALUES (47, 43, '站群劫持', 'Platform/Jet/jet_group', '', 3);
INSERT INTO `seo_platform_nav` VALUES (48, 43, '404劫持', 'Platform/Jet/jet_not_found', '', 4);
INSERT INTO `seo_platform_nav` VALUES (66, 64, '首页劫持生成', 'Platform/Cilent/index', '', NULL);
INSERT INTO `seo_platform_nav` VALUES (64, 0, '客户端管理', 'Platform/ShowNav/Cilent', 'television', 9);
INSERT INTO `seo_platform_nav` VALUES (57, 54, '.htaccess全站劫持生成', 'Platform/Tools/htaccess', '', 3);
INSERT INTO `seo_platform_nav` VALUES (56, 54, '404劫持检测', 'Platform/Tools/chenk', '', 2);
INSERT INTO `seo_platform_nav` VALUES (68, 67, '服务端获取', 'Platform/Server/index', '', 2);
INSERT INTO `seo_platform_nav` VALUES (54, 0, '辅助工具', 'Platform/ShowNav/Tools', 'cog', 10);
INSERT INTO `seo_platform_nav` VALUES (55, 54, '在线蜘蛛模拟', 'Platform/Tools/robot', '', 1);
INSERT INTO `seo_platform_nav` VALUES (58, 54, 'IIS 6 站群劫持生成', 'Platform/Tools/group', '', 4);
INSERT INTO `seo_platform_nav` VALUES (59, 54, 'IIS 域名导出', 'Platform/Tools/imports', '', 5);
INSERT INTO `seo_platform_nav` VALUES (60, 54, '文件索引加密', 'Platform/Tools/includes', '', 6);
INSERT INTO `seo_platform_nav` VALUES (61, 54, 'JS 加密 or 解密', 'Platform/Tools/decode', '', 7);
INSERT INTO `seo_platform_nav` VALUES (70, 67, '关键词导入', 'Platform/Server/import_keywords', '', 4);
INSERT INTO `seo_platform_nav` VALUES (69, 67, '关键词组管理', 'Platform/Server/keywords_group', '', 3);
INSERT INTO `seo_platform_nav` VALUES (71, 67, '描述库管理', 'Platform/Server/description', '', 5);
INSERT INTO `seo_platform_nav` VALUES (72, 67, '模板库管理', 'Platform/Server/templates', '', 6);
INSERT INTO `seo_platform_nav` VALUES (75, 67, '文章库管理', 'Platform/Server/article', '', 7);
INSERT INTO `seo_platform_nav` VALUES (77, 0, '项目管理', 'Platform/ShowNav/project', 'user', 11);
INSERT INTO `seo_platform_nav` VALUES (78, 77, '项目列表(单例模式)', 'Platform/Project/table', '', 1);
INSERT INTO `seo_platform_nav` VALUES (79, 77, '外链管理', 'Platform/Project/link', '', 6);
INSERT INTO `seo_platform_nav` VALUES (80, 0, '站群管理', 'Platform/ShowNav/Zqmanager', 'folder-o', 7);
INSERT INTO `seo_platform_nav` VALUES (81, 80, '蜘蛛日志', 'Platform/Zqmanager/log', '', 1);
INSERT INTO `seo_platform_nav` VALUES (83, 80, '数据总汇', 'Platform/Zqmanager/panel', '', 2);
INSERT INTO `seo_platform_nav` VALUES (84, 77, '模板管理', 'Platform/Project/templates_list', '', 4);
INSERT INTO `seo_platform_nav` VALUES (85, 77, '词库管理', 'Platform/Project/keyword', '', 3);
INSERT INTO `seo_platform_nav` VALUES (86, 77, 'URL 规则管理', 'Platform/Project/url_rules', '', 5);
INSERT INTO `seo_platform_nav` VALUES (87, 0, 'Dashboard', 'Platform/index/index', 'tachometer', 1);
INSERT INTO `seo_platform_nav` VALUES (88, 77, '消息通知', 'Platform/Project/notification', '', 9);
INSERT INTO `seo_platform_nav` VALUES (99, 0, '站群管理', 'Platform/showNav/Website', 'sitemap', NULL);
INSERT INTO `seo_platform_nav` VALUES (100, 99, '站点管理', 'Platform/Website/management', '', NULL);
INSERT INTO `seo_platform_nav` VALUES (97, 77, '项目列表(接口模式)', 'Platform/Project/port_index', '', 2);
INSERT INTO `seo_platform_nav` VALUES (101, 99, 'TDK调用模板', 'Platform/Website/tdkemplate', '', NULL);

-- ----------------------------
-- Table structure for seo_project
-- ----------------------------
DROP TABLE IF EXISTS `seo_project`;
CREATE TABLE `seo_project`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目ID（自增）',
  `project_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '项目名称',
  `project_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '项目地址',
  `siteid` varchar(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '自动生成的SITE_ID',
  `site_type` tinyint(1) NOT NULL COMMENT '项目类型',
  `rankname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '备注',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `ilink_rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '链接规则',
  `project_status` tinyint(1) NOT NULL COMMENT '项目状态',
  `monitor_status` tinyint(1) NOT NULL COMMENT '监控状态',
  `keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '词库类型',
  `seo_option` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'seo优化有关配置',
  `cache_option` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '缓存有关设置',
  `elink_option` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '外聯設置',
  `push_option` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '推送设置',
  `other_option` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '其他设置',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `project_charset` tinyint(2) NOT NULL COMMENT '项目编码',
  `advert_option` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '广告设置',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `siteid`(`siteid`) USING BTREE,
  INDEX `url`(`project_url`) USING BTREE,
  INDEX `site_type`(`site_type`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 162 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_project
-- ----------------------------

-- ----------------------------
-- Table structure for seo_slink
-- ----------------------------
DROP TABLE IF EXISTS `seo_slink`;
CREATE TABLE `seo_slink`  (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `sdomain` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `srules` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `addtime` int(11) NULL DEFAULT NULL,
  `slevel` tinyint(255) NULL DEFAULT NULL,
  PRIMARY KEY (`sid`) USING BTREE,
  UNIQUE INDEX `sid`(`sid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_slink
-- ----------------------------

-- ----------------------------
-- Table structure for seo_url_rules
-- ----------------------------
DROP TABLE IF EXISTS `seo_url_rules`;
CREATE TABLE `seo_url_rules`  (
  `ur_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'url规则自增ID',
  `ur_name` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NULL DEFAULT '' COMMENT 'url规则名称',
  `ur_content` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT 'url规则内容',
  `ur_addtime` int(11) NULL DEFAULT NULL COMMENT 'url规则创建时间',
  `ur_alias` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NULL DEFAULT NULL COMMENT 'url别名供外部url调用',
  `ur_flink_number` tinyint(3) NOT NULL DEFAULT 0 COMMENT 'url外链条数',
  `ur_filnk_enable` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否加入外链',
  `ur_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否为默认内链',
  `ur_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '规则类型',
  `ur_from_table` varchar(255) CHARACTER SET utf32 COLLATE utf32_bin NULL DEFAULT NULL COMMENT '为0时 数据表的来源',
  `ur_hreflang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'hreflang',
  PRIMARY KEY (`ur_id`) USING BTREE,
  UNIQUE INDEX `ur_id`(`ur_id`) USING BTREE,
  INDEX `ur_alias`(`ur_alias`(250)) USING BTREE,
  INDEX `ur_default`(`ur_default`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 35 CHARACTER SET = utf32 COLLATE = utf32_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_url_rules
-- ----------------------------
INSERT INTO `seo_url_rules` VALUES (21, '动态默认规则', 'a:571:{i:0;s:28:\"?{数字}/{随机字符}.xls\";i:1;s:30:\"?{字母6}/{随机字符}.xlsx\";i:2;s:29:\"?{字母5}/{随机字符}.doc\";i:3;s:38:\"?{字母5}/{日期}/{随机字符}.txt\";i:4;s:38:\"?{字母5}/{日期}/{随机字符}.pdf\";i:5;s:44:\"/?tid={字母5}/{数字日期}/{数字7}.pdf\";i:6;s:44:\"/?mid={字母5}/{数字日期}/{数字7}.pdf\";i:7;s:50:\"/?cid={数字日期}/{时}/{分}/{数字1-5}.shtml\";i:8;s:48:\"/?fid={数字日期}/{时}/{分}/{数字1-5}.csv\";i:9;s:24:\"?down={随机字符}.ppt\";i:10;s:25:\"?down={随机字符}.pptx\";i:11;s:24:\"?down={随机字符}.xml\";i:12;s:38:\"?seo={数字日期}{随机字符}.docx\";i:13;s:38:\"?seo={数字日期}{随机字符}.docx\";i:14;s:37:\"?search={年}{日}/{随机字符}.xls\";i:15;s:36:\"?search={年}{日}/{数字1-5}.shtml\";i:16;s:33:\"?search={数字5}/{字母1-5}.ppt\";i:17;s:30:\"?search={时}/{字母1-5}.pptx\";i:18;s:29:\"?search={时}/{字母1-5}.ppt\";i:19;s:29:\"?search={时}/{字母1-5}.doc\";i:20;s:40:\"?search={年}{时}{秒}/{字母1-5}.docx\";i:21;s:39:\"?search={年}{时}{秒}/{字母1-5}.xml\";i:22;s:40:\"?action={年}{时}{秒}/{字母1-5}.pptx\";i:23;s:39:\"?action={随机字符}/{字母1-5}.pptx\";i:24;s:38:\"?down={数字6}-{年}/{字母1-5}.pptx\";i:25;s:30:\"?action={时}/{字母1-5}.pptx\";i:26;s:29:\"?action={时}/{字母1-5}.txt\";i:27;s:29:\"?action={时}/{字母1-5}.csv\";i:28;s:38:\"/?search={年}{日}/{随机字符}.xls\";i:29;s:37:\"/?search={年}{日}/{数字1-5}.shtml\";i:30;s:34:\"/?search={数字5}/{字母1-5}.ppt\";i:31;s:31:\"/?search={时}/{字母1-5}.pptx\";i:32;s:30:\"/?search={时}/{字母1-5}.ppt\";i:33;s:30:\"/?search={时}/{字母1-5}.doc\";i:34;s:41:\"/?search={年}{时}{秒}/{字母1-5}.docx\";i:35;s:40:\"/?search={年}{时}{秒}/{字母1-5}.xml\";i:36;s:41:\"/?action={年}{时}{秒}/{字母1-5}.pptx\";i:37;s:40:\"/?action={随机字符}/{字母1-5}.pptx\";i:38;s:39:\"/?down={数字6}-{年}/{字母1-5}.pptx\";i:39;s:31:\"/?action={时}/{字母1-5}.pptx\";i:40;s:30:\"/?action={时}/{字母1-5}.txt\";i:41;s:30:\"/?action={时}/{字母1-5}.csv\";i:42;s:38:\"/?hot={年}{时}{秒}/{字母1-5}.pptx\";i:43;s:37:\"/?hot={随机字符}/{字母1-5}.pptx\";i:44;s:38:\"/?hot={数字6}-{年}/{字母1-5}.pptx\";i:45;s:28:\"/?hot={时}/{字母1-5}.pptx\";i:46;s:27:\"/?hot={时}/{字母1-5}.txt\";i:47;s:27:\"/?hot={时}/{字母1-5}.csv\";i:48;s:30:\"/?ArticleID={字母1-10}.shtml\";i:49;s:52:\"/?hot={随机字符}/{大写字母数字}{秒}.shtml\";i:50;s:54:\"/?video={随机字符}/{大写字母数字}{秒}.shtml\";i:51;s:28:\"/?video={随机字符}.shtml\";i:52;s:26:\"/?video={随机字符}.txt\";i:53;s:26:\"/?video={随机字符}.pdf\";i:54;s:26:\"/?video={随机字符}.ppt\";i:55;s:20:\"/?{随机字符}.xml\";i:56;s:20:\"/?{随机字符}.doc\";i:57;s:20:\"/?{随机字符}.txt\";i:58;s:20:\"/?{随机字符}.ppt\";i:59;s:20:\"/?{随机字符}.xls\";i:60;s:20:\"/?{随机字符}.csv\";i:61;s:22:\"/?{随机字符}.shtml\";i:62;s:20:\"/?{随机字符}.pdf\";i:63;s:21:\"/?{随机字符}.docx\";i:64;s:21:\"/?{随机字符}.xlsx\";i:65;s:29:\"/?{大小写字母数字}.xml\";i:66;s:29:\"/?{大小写字母数字}.doc\";i:67;s:29:\"/?{大小写字母数字}.txt\";i:68;s:29:\"/?{大小写字母数字}.ppt\";i:69;s:29:\"/?{大小写字母数字}.xls\";i:70;s:29:\"/?{大小写字母数字}.csv\";i:71;s:31:\"/?{大小写字母数字}.shtml\";i:72;s:29:\"/?{大小写字母数字}.pdf\";i:73;s:30:\"/?{大小写字母数字}.docx\";i:74;s:30:\"/?{大小写字母数字}.xlsx\";i:75;s:20:\"/?{数字字母}.xml\";i:76;s:20:\"/?{数字字母}.doc\";i:77;s:20:\"/?{数字字母}.txt\";i:78;s:20:\"/?{数字字母}.ppt\";i:79;s:20:\"/?{数字字母}.xls\";i:80;s:20:\"/?{数字字母}.csv\";i:81;s:22:\"/?{数字字母}.shtml\";i:82;s:20:\"/?{数字字母}.pdf\";i:83;s:21:\"/?{数字字母}.docx\";i:84;s:21:\"/?{数字字母}.xlsx\";i:85;s:28:\"/?{数字1-5}{字母1-5}.xml\";i:86;s:28:\"/?{数字1-5}{字母1-5}.doc\";i:87;s:28:\"/?{数字1-5}{字母1-5}.txt\";i:88;s:28:\"/?{数字1-5}{字母1-5}.ppt\";i:89;s:28:\"/?{数字1-5}{字母1-5}.xls\";i:90;s:28:\"/?{数字1-5}{字母1-5}.csv\";i:91;s:30:\"/?{数字1-5}{字母1-5}.shtml\";i:92;s:28:\"/?{数字1-5}{字母1-5}.pdf\";i:93;s:29:\"/?{数字1-5}{字母1-5}.docx\";i:94;s:29:\"/?{数字1-5}{字母1-5}.xlsx\";i:95;s:28:\"/?{字母1-5}{数字1-5}.xml\";i:96;s:28:\"/?{字母1-5}{数字1-5}.doc\";i:97;s:28:\"/?{字母1-5}{数字1-5}.txt\";i:98;s:28:\"/?{字母1-5}{数字1-5}.ppt\";i:99;s:28:\"/?{字母1-5}{数字1-5}.xls\";i:100;s:28:\"/?{字母1-5}{数字1-5}.csv\";i:101;s:30:\"/?{字母1-5}{数字1-5}.shtml\";i:102;s:28:\"/?{字母1-5}{数字1-5}.pdf\";i:103;s:29:\"/?{字母1-5}{数字1-5}.docx\";i:104;s:29:\"/?{字母1-5}{数字1-5}.xlsx\";i:105;s:35:\"/?{年}{月}{日}{随机字符}.xml\";i:106;s:35:\"/?{年}{月}{日}{随机字符}.doc\";i:107;s:35:\"/?{年}{月}{日}{随机字符}.txt\";i:108;s:38:\"?seo={数字日期}{随机字符}.docx\";i:109;s:38:\"?seo={数字日期}{随机字符}.docx\";i:110;s:36:\"?games={年}{日}/{随机字符}.xls\";i:111;s:35:\"?games={年}{日}/{数字1-5}.shtml\";i:112;s:32:\"?games={数字5}/{字母1-5}.ppt\";i:113;s:29:\"?games={时}/{字母1-5}.pptx\";i:114;s:28:\"?games={时}/{字母1-5}.ppt\";i:115;s:28:\"?games={时}/{字母1-5}.doc\";i:116;s:39:\"?games={年}{时}{秒}/{字母1-5}.docx\";i:117;s:38:\"?games={年}{时}{秒}/{字母1-5}.xml\";i:118;s:40:\"?action={年}{时}{秒}/{字母1-5}.pptx\";i:119;s:39:\"?action={随机字符}/{字母1-5}.pptx\";i:120;s:38:\"?down={数字6}-{年}/{字母1-5}.pptx\";i:121;s:30:\"?action={时}/{字母1-5}.pptx\";i:122;s:29:\"?action={时}/{字母1-5}.txt\";i:123;s:29:\"?action={时}/{字母1-5}.csv\";i:124;s:37:\"/?games={年}{日}/{随机字符}.xls\";i:125;s:36:\"/?games={年}{日}/{数字1-5}.shtml\";i:126;s:33:\"/?games={数字5}/{字母1-5}.ppt\";i:127;s:30:\"/?games={时}/{字母1-5}.pptx\";i:128;s:29:\"/?games={时}/{字母1-5}.ppt\";i:129;s:29:\"/?games={时}/{字母1-5}.doc\";i:130;s:40:\"/?games={年}{时}{秒}/{字母1-5}.docx\";i:131;s:39:\"/?games={年}{时}{秒}/{字母1-5}.xml\";i:132;s:41:\"/?action={年}{时}{秒}/{字母1-5}.pptx\";i:133;s:40:\"/?action={随机字符}/{字母1-5}.pptx\";i:134;s:39:\"/?down={数字6}-{年}/{字母1-5}.pptx\";i:135;s:31:\"/?action={时}/{字母1-5}.pptx\";i:136;s:30:\"/?action={时}/{字母1-5}.txt\";i:137;s:30:\"/?action={时}/{字母1-5}.csv\";i:138;s:38:\"/?hot={年}{时}{秒}/{字母1-5}.pptx\";i:139;s:37:\"/?hot={随机字符}/{字母1-5}.pptx\";i:140;s:38:\"/?hot={数字6}-{年}/{字母1-5}.pptx\";i:141;s:28:\"/?hot={时}/{字母1-5}.pptx\";i:142;s:27:\"/?hot={时}/{字母1-5}.txt\";i:143;s:27:\"/?hot={时}/{字母1-5}.csv\";i:144;s:35:\"/?{年}{月}{日}{随机字符}.ppt\";i:145;s:35:\"/?{年}{月}{日}{随机字符}.xls\";i:146;s:35:\"/?{年}{月}{日}{随机字符}.csv\";i:147;s:37:\"/?{年}{月}{日}{随机字符}.shtml\";i:148;s:35:\"/?{年}{月}{日}{随机字符}.pdf\";i:149;s:36:\"/?{年}{月}{日}{随机字符}.docx\";i:150;s:36:\"/?{年}{月}{日}{随机字符}.xlsx\";i:151;s:32:\"/?{随机字符}/{数字1-5}.xml\";i:152;s:32:\"/?{随机字符}/{数字1-5}.doc\";i:153;s:32:\"/?{随机字符}/{数字1-5}.txt\";i:154;s:32:\"/?{随机字符}/{数字1-5}.ppt\";i:155;s:32:\"/?{随机字符}/{数字1-5}.xls\";i:156;s:32:\"/?{随机字符}/{数字1-5}.csv\";i:157;s:34:\"/?{随机字符}/{数字1-5}.shtml\";i:158;s:32:\"/?{随机字符}/{数字1-5}.pdf\";i:159;s:33:\"/?{随机字符}/{数字1-5}.docx\";i:160;s:33:\"/?{随机字符}/{数字1-5}.xlsx\";i:161;s:32:\"/?{随机字符}/{字母1-5}.xml\";i:162;s:32:\"/?{随机字符}/{字母1-5}.doc\";i:163;s:32:\"/?{随机字符}/{字母1-5}.txt\";i:164;s:32:\"/?{随机字符}/{字母1-5}.ppt\";i:165;s:32:\"/?{随机字符}/{字母1-5}.xls\";i:166;s:32:\"/?{随机字符}/{字母1-5}.csv\";i:167;s:34:\"/?{随机字符}/{字母1-5}.shtml\";i:168;s:32:\"/?{随机字符}/{字母1-5}.pdf\";i:169;s:33:\"/?{随机字符}/{字母1-5}.docx\";i:170;s:33:\"/?{随机字符}/{字母1-5}.xlsx\";i:171;s:41:\"/?{大小写字母数字}/{数字1-5}.xml\";i:172;s:41:\"/?{大小写字母数字}/{数字1-5}.doc\";i:173;s:41:\"/?{大小写字母数字}/{数字1-5}.txt\";i:174;s:41:\"/?{大小写字母数字}/{数字1-5}.ppt\";i:175;s:41:\"/?{大小写字母数字}/{数字1-5}.xls\";i:176;s:41:\"/?{大小写字母数字}/{数字1-5}.csv\";i:177;s:43:\"/?{大小写字母数字}/{数字1-5}.shtml\";i:178;s:41:\"/?{大小写字母数字}/{数字1-5}.pdf\";i:179;s:42:\"/?{大小写字母数字}/{数字1-5}.docx\";i:180;s:42:\"/?{大小写字母数字}/{数字1-5}.xlsx\";i:181;s:41:\"/?{大小写字母数字}/{字母1-5}.xml\";i:182;s:41:\"/?{大小写字母数字}/{字母1-5}.doc\";i:183;s:41:\"/?{大小写字母数字}/{字母1-5}.txt\";i:184;s:41:\"/?{大小写字母数字}/{字母1-5}.ppt\";i:185;s:41:\"/?{大小写字母数字}/{字母1-5}.xls\";i:186;s:41:\"/?{大小写字母数字}/{字母1-5}.csv\";i:187;s:43:\"/?{大小写字母数字}/{字母1-5}.shtml\";i:188;s:41:\"/?{大小写字母数字}/{字母1-5}.pdf\";i:189;s:42:\"/?{大小写字母数字}/{字母1-5}.docx\";i:190;s:42:\"/?{大小写字母数字}/{字母1-5}.xlsx\";i:191;s:36:\"/?{年}{月}{日}/{随机字符}.xml\";i:192;s:36:\"/?{年}{月}{日}/{随机字符}.doc\";i:193;s:36:\"/?{年}{月}{日}/{随机字符}.txt\";i:194;s:36:\"/?{年}{月}{日}/{随机字符}.ppt\";i:195;s:36:\"/?{年}{月}{日}/{随机字符}.xls\";i:196;s:36:\"/?{年}{月}{日}/{随机字符}.csv\";i:197;s:38:\"/?{年}{月}{日}/{随机字符}.shtml\";i:198;s:36:\"/?{年}{月}{日}/{随机字符}.pdf\";i:199;s:37:\"/?{年}{月}{日}/{随机字符}.docx\";i:200;s:37:\"/?{年}{月}{日}/{随机字符}.xlsx\";i:201;s:45:\"/?{年}{月}{日}/{大小写字母数字}.xml\";i:202;s:45:\"/?{年}{月}{日}/{大小写字母数字}.doc\";i:203;s:45:\"/?{年}{月}{日}/{大小写字母数字}.txt\";i:204;s:45:\"/?{年}{月}{日}/{大小写字母数字}.ppt\";i:205;s:45:\"/?{年}{月}{日}/{大小写字母数字}.xls\";i:206;s:45:\"/?{年}{月}{日}/{大小写字母数字}.csv\";i:207;s:47:\"/?{年}{月}{日}/{大小写字母数字}.shtml\";i:208;s:45:\"/?{年}{月}{日}/{大小写字母数字}.pdf\";i:209;s:46:\"/?{年}{月}{日}/{大小写字母数字}.docx\";i:210;s:46:\"/?{年}{月}{日}/{大小写字母数字}.xlsx\";i:211;s:23:\"/?id={随机字符}.xml\";i:212;s:23:\"/?id={随机字符}.doc\";i:213;s:23:\"/?id={随机字符}.txt\";i:214;s:23:\"/?id={随机字符}.ppt\";i:215;s:23:\"/?id={随机字符}.xls\";i:216;s:23:\"/?id={随机字符}.csv\";i:217;s:25:\"/?id={随机字符}.shtml\";i:218;s:23:\"/?id={随机字符}.pdf\";i:219;s:24:\"/?id={随机字符}.docx\";i:220;s:24:\"/?id={随机字符}.xlsx\";i:221;s:32:\"/?id={大小写字母数字}.xml\";i:222;s:32:\"/?id={大小写字母数字}.doc\";i:223;s:32:\"/?id={大小写字母数字}.txt\";i:224;s:32:\"/?id={大小写字母数字}.ppt\";i:225;s:32:\"/?id={大小写字母数字}.xls\";i:226;s:32:\"/?id={大小写字母数字}.csv\";i:227;s:34:\"/?id={大小写字母数字}.shtml\";i:228;s:32:\"/?id={大小写字母数字}.pdf\";i:229;s:33:\"/?id={大小写字母数字}.docx\";i:230;s:33:\"/?id={大小写字母数字}.xlsx\";i:231;s:23:\"/?id={数字字母}.xml\";i:232;s:23:\"/?id={数字字母}.doc\";i:233;s:23:\"/?id={数字字母}.txt\";i:234;s:23:\"/?id={数字字母}.ppt\";i:235;s:23:\"/?id={数字字母}.xls\";i:236;s:23:\"/?id={数字字母}.csv\";i:237;s:25:\"/?id={数字字母}.shtml\";i:238;s:23:\"/?id={数字字母}.pdf\";i:239;s:24:\"/?id={数字字母}.docx\";i:240;s:24:\"/?id={数字字母}.xlsx\";i:241;s:31:\"/?id={数字1-5}{字母1-5}.xml\";i:242;s:31:\"/?id={数字1-5}{字母1-5}.doc\";i:243;s:31:\"/?id={数字1-5}{字母1-5}.txt\";i:244;s:31:\"/?id={数字1-5}{字母1-5}.ppt\";i:245;s:31:\"/?id={数字1-5}{字母1-5}.xls\";i:246;s:31:\"/?id={数字1-5}{字母1-5}.csv\";i:247;s:33:\"/?id={数字1-5}{字母1-5}.shtml\";i:248;s:31:\"/?id={数字1-5}{字母1-5}.pdf\";i:249;s:32:\"/?id={数字1-5}{字母1-5}.docx\";i:250;s:32:\"/?id={数字1-5}{字母1-5}.xlsx\";i:251;s:31:\"/?id={字母1-5}{数字1-5}.xml\";i:252;s:31:\"/?id={字母1-5}{数字1-5}.doc\";i:253;s:31:\"/?id={字母1-5}{数字1-5}.txt\";i:254;s:31:\"/?id={字母1-5}{数字1-5}.ppt\";i:255;s:31:\"/?id={字母1-5}{数字1-5}.xls\";i:256;s:31:\"/?id={字母1-5}{数字1-5}.csv\";i:257;s:33:\"/?id={字母1-5}{数字1-5}.shtml\";i:258;s:31:\"/?id={字母1-5}{数字1-5}.pdf\";i:259;s:32:\"/?id={字母1-5}{数字1-5}.docx\";i:260;s:32:\"/?id={字母1-5}{数字1-5}.xlsx\";i:261;s:38:\"/?id={年}{月}{日}{随机字符}.xml\";i:262;s:38:\"/?id={年}{月}{日}{随机字符}.doc\";i:263;s:38:\"/?id={年}{月}{日}{随机字符}.txt\";i:264;s:38:\"/?id={年}{月}{日}{随机字符}.ppt\";i:265;s:38:\"/?id={年}{月}{日}{随机字符}.xls\";i:266;s:38:\"/?id={年}{月}{日}{随机字符}.csv\";i:267;s:40:\"/?id={年}{月}{日}{随机字符}.shtml\";i:268;s:38:\"/?id={年}{月}{日}{随机字符}.pdf\";i:269;s:39:\"/?id={年}{月}{日}{随机字符}.docx\";i:270;s:39:\"/?id={年}{月}{日}{随机字符}.xlsx\";i:271;s:35:\"/?id={随机字符}/{数字1-5}.xml\";i:272;s:35:\"/?id={随机字符}/{数字1-5}.doc\";i:273;s:35:\"/?id={随机字符}/{数字1-5}.txt\";i:274;s:35:\"/?id={随机字符}/{数字1-5}.ppt\";i:275;s:35:\"/?id={随机字符}/{数字1-5}.xls\";i:276;s:35:\"/?id={随机字符}/{数字1-5}.csv\";i:277;s:37:\"/?id={随机字符}/{数字1-5}.shtml\";i:278;s:35:\"/?id={随机字符}/{数字1-5}.pdf\";i:279;s:36:\"/?id={随机字符}/{数字1-5}.docx\";i:280;s:36:\"/?id={随机字符}/{数字1-5}.xlsx\";i:281;s:35:\"/?id={随机字符}/{字母1-5}.xml\";i:282;s:35:\"/?id={随机字符}/{字母1-5}.doc\";i:283;s:35:\"/?id={随机字符}/{字母1-5}.txt\";i:284;s:35:\"/?id={随机字符}/{字母1-5}.ppt\";i:285;s:35:\"/?id={随机字符}/{字母1-5}.xls\";i:286;s:35:\"/?id={随机字符}/{字母1-5}.csv\";i:287;s:37:\"/?id={随机字符}/{字母1-5}.shtml\";i:288;s:35:\"/?id={随机字符}/{字母1-5}.pdf\";i:289;s:36:\"/?id={随机字符}/{字母1-5}.docx\";i:290;s:36:\"/?id={随机字符}/{字母1-5}.xlsx\";i:291;s:44:\"/?id={大小写字母数字}/{数字1-5}.xml\";i:292;s:44:\"/?id={大小写字母数字}/{数字1-5}.doc\";i:293;s:44:\"/?id={大小写字母数字}/{数字1-5}.txt\";i:294;s:44:\"/?id={大小写字母数字}/{数字1-5}.ppt\";i:295;s:44:\"/?id={大小写字母数字}/{数字1-5}.xls\";i:296;s:44:\"/?id={大小写字母数字}/{数字1-5}.csv\";i:297;s:46:\"/?id={大小写字母数字}/{数字1-5}.shtml\";i:298;s:44:\"/?id={大小写字母数字}/{数字1-5}.pdf\";i:299;s:45:\"/?id={大小写字母数字}/{数字1-5}.docx\";i:300;s:45:\"/?id={大小写字母数字}/{数字1-5}.xlsx\";i:301;s:44:\"/?id={大小写字母数字}/{字母1-5}.xml\";i:302;s:44:\"/?id={大小写字母数字}/{字母1-5}.doc\";i:303;s:44:\"/?id={大小写字母数字}/{字母1-5}.txt\";i:304;s:44:\"/?id={大小写字母数字}/{字母1-5}.ppt\";i:305;s:44:\"/?id={大小写字母数字}/{字母1-5}.xls\";i:306;s:44:\"/?id={大小写字母数字}/{字母1-5}.csv\";i:307;s:46:\"/?id={大小写字母数字}/{字母1-5}.shtml\";i:308;s:44:\"/?id={大小写字母数字}/{字母1-5}.pdf\";i:309;s:45:\"/?id={大小写字母数字}/{字母1-5}.docx\";i:310;s:45:\"/?id={大小写字母数字}/{字母1-5}.xlsx\";i:311;s:39:\"/?id={年}{月}{日}/{随机字符}.xml\";i:312;s:39:\"/?id={年}{月}{日}/{随机字符}.doc\";i:313;s:39:\"/?id={年}{月}{日}/{随机字符}.txt\";i:314;s:39:\"/?id={年}{月}{日}/{随机字符}.ppt\";i:315;s:39:\"/?id={年}{月}{日}/{随机字符}.xls\";i:316;s:39:\"/?id={年}{月}{日}/{随机字符}.csv\";i:317;s:41:\"/?id={年}{月}{日}/{随机字符}.shtml\";i:318;s:39:\"/?id={年}{月}{日}/{随机字符}.pdf\";i:319;s:40:\"/?id={年}{月}{日}/{随机字符}.docx\";i:320;s:40:\"/?id={年}{月}{日}/{随机字符}.xlsx\";i:321;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.xml\";i:322;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.doc\";i:323;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.txt\";i:324;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.ppt\";i:325;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.xls\";i:326;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.csv\";i:327;s:50:\"/?id={年}{月}{日}/{大小写字母数字}.shtml\";i:328;s:48:\"/?id={年}{月}{日}/{大小写字母数字}.pdf\";i:329;s:49:\"/?id={年}{月}{日}/{大小写字母数字}.docx\";i:330;s:49:\"/?id={年}{月}{日}/{大小写字母数字}.xlsx\";i:331;s:24:\"/?sex={随机字符}.xml\";i:332;s:24:\"/?sex={随机字符}.doc\";i:333;s:24:\"/?sex={随机字符}.txt\";i:334;s:24:\"/?sex={随机字符}.ppt\";i:335;s:24:\"/?sex={随机字符}.xls\";i:336;s:24:\"/?sex={随机字符}.csv\";i:337;s:26:\"/?sex={随机字符}.shtml\";i:338;s:24:\"/?sex={随机字符}.pdf\";i:339;s:25:\"/?sex={随机字符}.docx\";i:340;s:25:\"/?sex={随机字符}.xlsx\";i:341;s:33:\"/?sex={大小写字母数字}.xml\";i:342;s:33:\"/?sex={大小写字母数字}.doc\";i:343;s:33:\"/?sex={大小写字母数字}.txt\";i:344;s:33:\"/?sex={大小写字母数字}.ppt\";i:345;s:33:\"/?sex={大小写字母数字}.xls\";i:346;s:33:\"/?sex={大小写字母数字}.csv\";i:347;s:35:\"/?sex={大小写字母数字}.shtml\";i:348;s:33:\"/?sex={大小写字母数字}.pdf\";i:349;s:34:\"/?sex={大小写字母数字}.docx\";i:350;s:34:\"/?sex={大小写字母数字}.xlsx\";i:351;s:24:\"/?sex={数字字母}.xml\";i:352;s:24:\"/?sex={数字字母}.doc\";i:353;s:24:\"/?sex={数字字母}.txt\";i:354;s:24:\"/?sex={数字字母}.ppt\";i:355;s:24:\"/?sex={数字字母}.xls\";i:356;s:24:\"/?sex={数字字母}.csv\";i:357;s:26:\"/?sex={数字字母}.shtml\";i:358;s:24:\"/?sex={数字字母}.pdf\";i:359;s:25:\"/?sex={数字字母}.docx\";i:360;s:25:\"/?sex={数字字母}.xlsx\";i:361;s:32:\"/?sex={数字1-5}{字母1-5}.xml\";i:362;s:32:\"/?sex={数字1-5}{字母1-5}.doc\";i:363;s:32:\"/?sex={数字1-5}{字母1-5}.txt\";i:364;s:32:\"/?sex={数字1-5}{字母1-5}.ppt\";i:365;s:32:\"/?sex={数字1-5}{字母1-5}.xls\";i:366;s:32:\"/?sex={数字1-5}{字母1-5}.csv\";i:367;s:34:\"/?sex={数字1-5}{字母1-5}.shtml\";i:368;s:32:\"/?sex={数字1-5}{字母1-5}.pdf\";i:369;s:33:\"/?sex={数字1-5}{字母1-5}.docx\";i:370;s:33:\"/?sex={数字1-5}{字母1-5}.xlsx\";i:371;s:32:\"/?sex={字母1-5}{数字1-5}.xml\";i:372;s:32:\"/?sex={字母1-5}{数字1-5}.doc\";i:373;s:32:\"/?sex={字母1-5}{数字1-5}.txt\";i:374;s:32:\"/?sex={字母1-5}{数字1-5}.ppt\";i:375;s:32:\"/?sex={字母1-5}{数字1-5}.xls\";i:376;s:32:\"/?sex={字母1-5}{数字1-5}.csv\";i:377;s:34:\"/?sex={字母1-5}{数字1-5}.shtml\";i:378;s:32:\"/?sex={字母1-5}{数字1-5}.pdf\";i:379;s:33:\"/?sex={字母1-5}{数字1-5}.docx\";i:380;s:33:\"/?sex={字母1-5}{数字1-5}.xlsx\";i:381;s:39:\"/?sex={年}{月}{日}{随机字符}.xml\";i:382;s:39:\"/?sex={年}{月}{日}{随机字符}.doc\";i:383;s:39:\"/?sex={年}{月}{日}{随机字符}.txt\";i:384;s:39:\"/?sex={年}{月}{日}{随机字符}.ppt\";i:385;s:39:\"/?sex={年}{月}{日}{随机字符}.xls\";i:386;s:39:\"/?sex={年}{月}{日}{随机字符}.csv\";i:387;s:41:\"/?sex={年}{月}{日}{随机字符}.shtml\";i:388;s:39:\"/?sex={年}{月}{日}{随机字符}.pdf\";i:389;s:40:\"/?sex={年}{月}{日}{随机字符}.docx\";i:390;s:40:\"/?sex={年}{月}{日}{随机字符}.xlsx\";i:391;s:36:\"/?sex={随机字符}/{数字1-5}.xml\";i:392;s:36:\"/?sex={随机字符}/{数字1-5}.doc\";i:393;s:36:\"/?sex={随机字符}/{数字1-5}.txt\";i:394;s:36:\"/?sex={随机字符}/{数字1-5}.ppt\";i:395;s:36:\"/?sex={随机字符}/{数字1-5}.xls\";i:396;s:36:\"/?sex={随机字符}/{数字1-5}.csv\";i:397;s:38:\"/?sex={随机字符}/{数字1-5}.shtml\";i:398;s:36:\"/?sex={随机字符}/{数字1-5}.pdf\";i:399;s:37:\"/?sex={随机字符}/{数字1-5}.docx\";i:400;s:37:\"/?sex={随机字符}/{数字1-5}.xlsx\";i:401;s:36:\"/?sex={随机字符}/{字母1-5}.xml\";i:402;s:36:\"/?sex={随机字符}/{字母1-5}.doc\";i:403;s:36:\"/?sex={随机字符}/{字母1-5}.txt\";i:404;s:36:\"/?sex={随机字符}/{字母1-5}.ppt\";i:405;s:36:\"/?sex={随机字符}/{字母1-5}.xls\";i:406;s:36:\"/?sex={随机字符}/{字母1-5}.csv\";i:407;s:38:\"/?sex={随机字符}/{字母1-5}.shtml\";i:408;s:36:\"/?sex={随机字符}/{字母1-5}.pdf\";i:409;s:37:\"/?sex={随机字符}/{字母1-5}.docx\";i:410;s:37:\"/?sex={随机字符}/{字母1-5}.xlsx\";i:411;s:45:\"/?sex={大小写字母数字}/{数字1-5}.xml\";i:412;s:45:\"/?sex={大小写字母数字}/{数字1-5}.doc\";i:413;s:45:\"/?sex={大小写字母数字}/{数字1-5}.txt\";i:414;s:45:\"/?sex={大小写字母数字}/{数字1-5}.ppt\";i:415;s:45:\"/?sex={大小写字母数字}/{数字1-5}.xls\";i:416;s:45:\"/?sex={大小写字母数字}/{数字1-5}.csv\";i:417;s:47:\"/?sex={大小写字母数字}/{数字1-5}.shtml\";i:418;s:45:\"/?sex={大小写字母数字}/{数字1-5}.pdf\";i:419;s:46:\"/?sex={大小写字母数字}/{数字1-5}.docx\";i:420;s:46:\"/?sex={大小写字母数字}/{数字1-5}.xlsx\";i:421;s:45:\"/?sex={大小写字母数字}/{字母1-5}.xml\";i:422;s:45:\"/?sex={大小写字母数字}/{字母1-5}.doc\";i:423;s:45:\"/?sex={大小写字母数字}/{字母1-5}.txt\";i:424;s:45:\"/?sex={大小写字母数字}/{字母1-5}.ppt\";i:425;s:45:\"/?sex={大小写字母数字}/{字母1-5}.xls\";i:426;s:45:\"/?sex={大小写字母数字}/{字母1-5}.csv\";i:427;s:47:\"/?sex={大小写字母数字}/{字母1-5}.shtml\";i:428;s:45:\"/?sex={大小写字母数字}/{字母1-5}.pdf\";i:429;s:46:\"/?sex={大小写字母数字}/{字母1-5}.docx\";i:430;s:46:\"/?sex={大小写字母数字}/{字母1-5}.xlsx\";i:431;s:40:\"/?sex={年}{月}{日}/{随机字符}.xml\";i:432;s:40:\"/?sex={年}{月}{日}/{随机字符}.doc\";i:433;s:40:\"/?sex={年}{月}{日}/{随机字符}.txt\";i:434;s:40:\"/?sex={年}{月}{日}/{随机字符}.ppt\";i:435;s:40:\"/?sex={年}{月}{日}/{随机字符}.xls\";i:436;s:40:\"/?sex={年}{月}{日}/{随机字符}.csv\";i:437;s:42:\"/?sex={年}{月}{日}/{随机字符}.shtml\";i:438;s:40:\"/?sex={年}{月}{日}/{随机字符}.pdf\";i:439;s:41:\"/?sex={年}{月}{日}/{随机字符}.docx\";i:440;s:41:\"/?sex={年}{月}{日}/{随机字符}.xlsx\";i:441;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.xml\";i:442;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.doc\";i:443;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.txt\";i:444;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.ppt\";i:445;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.xls\";i:446;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.csv\";i:447;s:51:\"/?sex={年}{月}{日}/{大小写字母数字}.shtml\";i:448;s:49:\"/?sex={年}{月}{日}/{大小写字母数字}.pdf\";i:449;s:50:\"/?sex={年}{月}{日}/{大小写字母数字}.docx\";i:450;s:50:\"/?sex={年}{月}{日}/{大小写字母数字}.xlsx\";i:451;s:35:\"/?{随机字符}={随机字符}.xml\";i:452;s:35:\"/?{随机字符}={随机字符}.doc\";i:453;s:35:\"/?{随机字符}={随机字符}.txt\";i:454;s:35:\"/?{随机字符}={随机字符}.ppt\";i:455;s:35:\"/?{随机字符}={随机字符}.xls\";i:456;s:35:\"/?{随机字符}={随机字符}.csv\";i:457;s:37:\"/?{随机字符}={随机字符}.shtml\";i:458;s:35:\"/?{随机字符}={随机字符}.pdf\";i:459;s:36:\"/?{随机字符}={随机字符}.docx\";i:460;s:36:\"/?{随机字符}={随机字符}.xlsx\";i:461;s:44:\"/?{随机字符}={大小写字母数字}.xml\";i:462;s:44:\"/?{随机字符}={大小写字母数字}.doc\";i:463;s:44:\"/?{随机字符}={大小写字母数字}.txt\";i:464;s:44:\"/?{随机字符}={大小写字母数字}.ppt\";i:465;s:44:\"/?{随机字符}={大小写字母数字}.xls\";i:466;s:44:\"/?{随机字符}={大小写字母数字}.csv\";i:467;s:46:\"/?{随机字符}={大小写字母数字}.shtml\";i:468;s:44:\"/?{随机字符}={大小写字母数字}.pdf\";i:469;s:45:\"/?{随机字符}={大小写字母数字}.docx\";i:470;s:45:\"/?{随机字符}={大小写字母数字}.xlsx\";i:471;s:35:\"/?{随机字符}={数字字母}.xml\";i:472;s:35:\"/?{随机字符}={数字字母}.doc\";i:473;s:35:\"/?{随机字符}={数字字母}.txt\";i:474;s:35:\"/?{随机字符}={数字字母}.ppt\";i:475;s:35:\"/?{随机字符}={数字字母}.xls\";i:476;s:35:\"/?{随机字符}={数字字母}.csv\";i:477;s:37:\"/?{随机字符}={数字字母}.shtml\";i:478;s:35:\"/?{随机字符}={数字字母}.pdf\";i:479;s:36:\"/?{随机字符}={数字字母}.docx\";i:480;s:36:\"/?{随机字符}={数字字母}.xlsx\";i:481;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.xml\";i:482;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.doc\";i:483;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.txt\";i:484;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.ppt\";i:485;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.xls\";i:486;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.csv\";i:487;s:45:\"/?{随机字符}={数字1-5}{字母1-5}.shtml\";i:488;s:43:\"/?{随机字符}={数字1-5}{字母1-5}.pdf\";i:489;s:44:\"/?{随机字符}={数字1-5}{字母1-5}.docx\";i:490;s:44:\"/?{随机字符}={数字1-5}{字母1-5}.xlsx\";i:491;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.xml\";i:492;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.doc\";i:493;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.txt\";i:494;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.ppt\";i:495;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.xls\";i:496;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.csv\";i:497;s:45:\"/?{随机字符}={字母1-5}{数字1-5}.shtml\";i:498;s:43:\"/?{随机字符}={字母1-5}{数字1-5}.pdf\";i:499;s:44:\"/?{随机字符}={字母1-5}{数字1-5}.docx\";i:500;s:44:\"/?{随机字符}={字母1-5}{数字1-5}.xlsx\";i:501;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.xml\";i:502;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.doc\";i:503;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.txt\";i:504;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.ppt\";i:505;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.xls\";i:506;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.csv\";i:507;s:52:\"/?{随机字符}={年}{月}{日}{随机字符}.shtml\";i:508;s:50:\"/?{随机字符}={年}{月}{日}{随机字符}.pdf\";i:509;s:51:\"/?{随机字符}={年}{月}{日}{随机字符}.docx\";i:510;s:51:\"/?{随机字符}={年}{月}{日}{随机字符}.xlsx\";i:511;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.xml\";i:512;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.doc\";i:513;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.txt\";i:514;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.ppt\";i:515;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.xls\";i:516;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.csv\";i:517;s:49:\"/?{随机字符}={随机字符}/{数字1-5}.shtml\";i:518;s:47:\"/?{随机字符}={随机字符}/{数字1-5}.pdf\";i:519;s:48:\"/?{随机字符}={随机字符}/{数字1-5}.docx\";i:520;s:48:\"/?{随机字符}={随机字符}/{数字1-5}.xlsx\";i:521;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.xml\";i:522;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.doc\";i:523;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.txt\";i:524;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.ppt\";i:525;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.xls\";i:526;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.csv\";i:527;s:49:\"/?{随机字符}={随机字符}/{字母1-5}.shtml\";i:528;s:47:\"/?{随机字符}={随机字符}/{字母1-5}.pdf\";i:529;s:48:\"/?{随机字符}={随机字符}/{字母1-5}.docx\";i:530;s:48:\"/?{随机字符}={随机字符}/{字母1-5}.xlsx\";i:531;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.xml\";i:532;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.doc\";i:533;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.txt\";i:534;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.ppt\";i:535;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.xls\";i:536;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.csv\";i:537;s:58:\"/?{随机字符}={大小写字母数字}/{数字1-5}.shtml\";i:538;s:56:\"/?{随机字符}={大小写字母数字}/{数字1-5}.pdf\";i:539;s:57:\"/?{随机字符}={大小写字母数字}/{数字1-5}.docx\";i:540;s:57:\"/?{随机字符}={大小写字母数字}/{数字1-5}.xlsx\";i:541;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.xml\";i:542;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.doc\";i:543;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.txt\";i:544;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.ppt\";i:545;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.xls\";i:546;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.csv\";i:547;s:58:\"/?{随机字符}={大小写字母数字}/{字母1-5}.shtml\";i:548;s:56:\"/?{随机字符}={大小写字母数字}/{字母1-5}.pdf\";i:549;s:57:\"/?{随机字符}={大小写字母数字}/{字母1-5}.docx\";i:550;s:57:\"/?{随机字符}={大小写字母数字}/{字母1-5}.xlsx\";i:551;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.xml\";i:552;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.doc\";i:553;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.txt\";i:554;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.ppt\";i:555;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.xls\";i:556;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.csv\";i:557;s:53:\"/?{随机字符}={年}{月}{日}/{随机字符}.shtml\";i:558;s:51:\"/?{随机字符}={年}{月}{日}/{随机字符}.pdf\";i:559;s:52:\"/?{随机字符}={年}{月}{日}/{随机字符}.docx\";i:560;s:52:\"/?{随机字符}={年}{月}{日}/{随机字符}.xlsx\";i:561;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.xml\";i:562;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.doc\";i:563;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.txt\";i:564;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.ppt\";i:565;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.xls\";i:566;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.csv\";i:567;s:62:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.shtml\";i:568;s:60:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.pdf\";i:569;s:61:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.docx\";i:570;s:61:\"/?{随机字符}={年}{月}{日}/{大小写字母数字}.xlsx\";}', 1667505466, '6AvOh7', 5, 0, 1, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (22, '动态引外链专用', 'a:34:{i:0;s:37:\"?search={年}{日}/{随机字符}.xls\";i:1;s:36:\"?search={年}{日}/{数字1-5}.shtml\";i:2;s:33:\"?search={数字5}/{字母1-5}.ppt\";i:3;s:30:\"?search={时}/{字母1-5}.pptx\";i:4;s:29:\"?search={时}/{字母1-5}.ppt\";i:5;s:29:\"?search={时}/{字母1-5}.doc\";i:6;s:40:\"?search={年}{时}{秒}/{字母1-5}.docx\";i:7;s:39:\"?search={年}{时}{秒}/{字母1-5}.xml\";i:8;s:40:\"?action={年}{时}{秒}/{字母1-5}.pptx\";i:9;s:39:\"?action={随机字符}/{字母1-5}.pptx\";i:10;s:38:\"?down={数字6}-{年}/{字母1-5}.pptx\";i:11;s:30:\"?action={时}/{字母1-5}.pptx\";i:12;s:29:\"?action={时}/{字母1-5}.txt\";i:13;s:29:\"?action={时}/{字母1-5}.cvs\";i:14;s:38:\"/?search={年}{日}/{随机字符}.xls\";i:15;s:37:\"/?search={年}{日}/{数字1-5}.shtml\";i:16;s:34:\"/?search={数字5}/{字母1-5}.ppt\";i:17;s:31:\"/?search={时}/{字母1-5}.pptx\";i:18;s:30:\"/?search={时}/{字母1-5}.ppt\";i:19;s:30:\"/?search={时}/{字母1-5}.doc\";i:20;s:41:\"/?search={年}{时}{秒}/{字母1-5}.docx\";i:21;s:40:\"/?search={年}{时}{秒}/{字母1-5}.xml\";i:22;s:41:\"/?action={年}{时}{秒}/{字母1-5}.pptx\";i:23;s:40:\"/?action={随机字符}/{字母1-5}.pptx\";i:24;s:39:\"/?down={数字6}-{年}/{字母1-5}.pptx\";i:25;s:31:\"/?action={时}/{字母1-5}.pptx\";i:26;s:30:\"/?action={时}/{字母1-5}.txt\";i:27;s:30:\"/?action={时}/{字母1-5}.cvs\";i:28;s:38:\"/?hot={年}{时}{秒}/{字母1-5}.pptx\";i:29;s:37:\"/?hot={随机字符}/{字母1-5}.pptx\";i:30;s:38:\"/?hot={数字6}-{年}/{字母1-5}.pptx\";i:31;s:28:\"/?hot={时}/{字母1-5}.pptx\";i:32;s:27:\"/?hot={时}/{字母1-5}.txt\";i:33;s:27:\"/?hot={时}/{字母1-5}.cvs\";}', 1647417139, 'VJL0KT', 0, 0, 0, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (23, '论坛URL', 'a:18:{i:0;s:36:\"?forum-{数字3}-fid-{数字6}.shtml\";i:1;s:36:\"?forum-{数字3}-fid-{数字6}.shtml\";i:2;s:36:\"?forum-{数字3}-fid-{数字6}.shtml\";i:3;s:37:\"?thread-{数字3}-tid-{数字6}.shtml\";i:4;s:37:\"?thread-{数字3}-tid-{数字6}.shtml\";i:5;s:37:\"?thread-{数字3}-tid-{数字6}.shtml\";i:6;s:43:\"?/bbs/thread-{数字3}-tid-{数字6}.shtml.\";i:7;s:42:\"?/bbs/thread-{数字3}-tid-{数字6}.shtml\";i:8;s:42:\"?/bbs/thread-{数字3}-tid-{数字6}.shtml\";i:9;s:42:\"?/bbs/thread-{数字3}-tid-{数字6}.shtml\";i:10;s:41:\"?/bbs/forum-{数字3}-fid-{数字6}.shtml\";i:11;s:41:\"?/bbs/forum-{数字3}-fid-{数字6}.shtml\";i:12;s:41:\"?/bbs/forum-{数字3}-fid-{数字6}.shtml\";i:13;s:39:\"?/news/{年}{月}{日}/{数字1-5}.pptx\";i:14;s:38:\"?/news/{年}{月}{日}/{数字1-5}.xls\";i:15;s:38:\"?/news/{年}{月}{日}/{数字1-5}.xml\";i:16;s:38:\"?/news/{年}{月}{日}/{数字1-5}.xls\";i:17;s:38:\"?/news/{年}{月}{日}/{数字1-5}.cvs\";}', 1647417483, 'hFzsUT', 0, 0, 0, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (24, 'indexphp首页', 'a:242:{i:0;s:40:\"/index.php?id={字母1-5}{数字1-5}.doc\";i:1;s:40:\"/index.php?id={字母1-5}{数字1-5}.txt\";i:2;s:40:\"/index.php?id={字母1-5}{数字1-5}.ppt\";i:3;s:40:\"/index.php?id={字母1-5}{数字1-5}.xls\";i:4;s:40:\"/index.php?id={字母1-5}{数字1-5}.csv\";i:5;s:42:\"/index.php?id={字母1-5}{数字1-5}.shtml\";i:6;s:40:\"/index.php?id={字母1-5}{数字1-5}.pdf\";i:7;s:41:\"/index.php?id={字母1-5}{数字1-5}.docx\";i:8;s:41:\"/index.php?id={字母1-5}{数字1-5}.xlsx\";i:9;s:47:\"/index.php?id={年}{月}{日}{随机字符}.xml\";i:10;s:47:\"/index.php?id={年}{月}{日}{随机字符}.doc\";i:11;s:47:\"/index.php?id={年}{月}{日}{随机字符}.txt\";i:12;s:47:\"/index.php?id={年}{月}{日}{随机字符}.ppt\";i:13;s:47:\"/index.php?id={年}{月}{日}{随机字符}.xls\";i:14;s:47:\"/index.php?id={年}{月}{日}{随机字符}.csv\";i:15;s:49:\"/index.php?id={年}{月}{日}{随机字符}.shtml\";i:16;s:47:\"/index.php?id={年}{月}{日}{随机字符}.pdf\";i:17;s:48:\"/index.php?id={年}{月}{日}{随机字符}.docx\";i:18;s:48:\"/index.php?id={年}{月}{日}{随机字符}.xlsx\";i:19;s:44:\"/index.php?id={随机字符}/{数字1-5}.xml\";i:20;s:44:\"/index.php?id={随机字符}/{数字1-5}.doc\";i:21;s:44:\"/index.php?id={随机字符}/{数字1-5}.txt\";i:22;s:44:\"/index.php?id={随机字符}/{数字1-5}.ppt\";i:23;s:44:\"/index.php?id={随机字符}/{数字1-5}.xls\";i:24;s:44:\"/index.php?id={随机字符}/{数字1-5}.csv\";i:25;s:46:\"/index.php?id={随机字符}/{数字1-5}.shtml\";i:26;s:44:\"/index.php?id={随机字符}/{数字1-5}.pdf\";i:27;s:45:\"/index.php?id={随机字符}/{数字1-5}.docx\";i:28;s:45:\"/index.php?id={随机字符}/{数字1-5}.xlsx\";i:29;s:44:\"/index.php?id={随机字符}/{字母1-5}.xml\";i:30;s:44:\"/index.php?id={随机字符}/{字母1-5}.doc\";i:31;s:44:\"/index.php?id={随机字符}/{字母1-5}.txt\";i:32;s:44:\"/index.php?id={随机字符}/{字母1-5}.ppt\";i:33;s:44:\"/index.php?id={随机字符}/{字母1-5}.xls\";i:34;s:44:\"/index.php?id={随机字符}/{字母1-5}.csv\";i:35;s:46:\"/index.php?id={随机字符}/{字母1-5}.shtml\";i:36;s:44:\"/index.php?id={随机字符}/{字母1-5}.pdf\";i:37;s:45:\"/index.php?id={随机字符}/{字母1-5}.docx\";i:38;s:45:\"/index.php?id={随机字符}/{字母1-5}.xlsx\";i:39;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.xml\";i:40;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.doc\";i:41;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.txt\";i:42;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.ppt\";i:43;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.xls\";i:44;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.csv\";i:45;s:55:\"/index.php?id={大小写字母数字}/{数字1-5}.shtml\";i:46;s:53:\"/index.php?id={大小写字母数字}/{数字1-5}.pdf\";i:47;s:54:\"/index.php?id={大小写字母数字}/{数字1-5}.docx\";i:48;s:54:\"/index.php?id={大小写字母数字}/{数字1-5}.xlsx\";i:49;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.xml\";i:50;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.doc\";i:51;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.txt\";i:52;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.ppt\";i:53;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.xls\";i:54;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.csv\";i:55;s:55:\"/index.php?id={大小写字母数字}/{字母1-5}.shtml\";i:56;s:53:\"/index.php?id={大小写字母数字}/{字母1-5}.pdf\";i:57;s:54:\"/index.php?id={大小写字母数字}/{字母1-5}.docx\";i:58;s:54:\"/index.php?id={大小写字母数字}/{字母1-5}.xlsx\";i:59;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.xml\";i:60;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.doc\";i:61;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.txt\";i:62;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.ppt\";i:63;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.xls\";i:64;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.csv\";i:65;s:50:\"/index.php?id={年}{月}{日}/{随机字符}.shtml\";i:66;s:48:\"/index.php?id={年}{月}{日}/{随机字符}.pdf\";i:67;s:49:\"/index.php?id={年}{月}{日}/{随机字符}.docx\";i:68;s:49:\"/index.php?id={年}{月}{日}/{随机字符}.xlsx\";i:69;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.xml\";i:70;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.doc\";i:71;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.txt\";i:72;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.ppt\";i:73;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.xls\";i:74;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.csv\";i:75;s:59:\"/index.php?id={年}{月}{日}/{大小写字母数字}.shtml\";i:76;s:57:\"/index.php?id={年}{月}{日}/{大小写字母数字}.pdf\";i:77;s:58:\"/index.php?id={年}{月}{日}/{大小写字母数字}.docx\";i:78;s:58:\"/index.php?id={年}{月}{日}/{大小写字母数字}.xlsx\";i:79;s:33:\"/index.php?sex={随机字符}.xml\";i:80;s:33:\"/index.php?sex={随机字符}.doc\";i:81;s:33:\"/index.php?sex={随机字符}.txt\";i:82;s:33:\"/index.php?sex={随机字符}.ppt\";i:83;s:33:\"/index.php?sex={随机字符}.xls\";i:84;s:33:\"/index.php?sex={随机字符}.csv\";i:85;s:35:\"/index.php?sex={随机字符}.shtml\";i:86;s:33:\"/index.php?sex={随机字符}.pdf\";i:87;s:34:\"/index.php?sex={随机字符}.docx\";i:88;s:34:\"/index.php?sex={随机字符}.xlsx\";i:89;s:42:\"/index.php?sex={大小写字母数字}.xml\";i:90;s:42:\"/index.php?sex={大小写字母数字}.doc\";i:91;s:42:\"/index.php?sex={大小写字母数字}.txt\";i:92;s:42:\"/index.php?sex={大小写字母数字}.ppt\";i:93;s:42:\"/index.php?sex={大小写字母数字}.xls\";i:94;s:42:\"/index.php?sex={大小写字母数字}.csv\";i:95;s:44:\"/index.php?sex={大小写字母数字}.shtml\";i:96;s:42:\"/index.php?sex={大小写字母数字}.pdf\";i:97;s:43:\"/index.php?sex={大小写字母数字}.docx\";i:98;s:43:\"/index.php?sex={大小写字母数字}.xlsx\";i:99;s:33:\"/index.php?sex={数字字母}.xml\";i:100;s:33:\"/index.php?sex={数字字母}.doc\";i:101;s:33:\"/index.php?sex={数字字母}.txt\";i:102;s:33:\"/index.php?sex={数字字母}.ppt\";i:103;s:33:\"/index.php?sex={数字字母}.xls\";i:104;s:33:\"/index.php?sex={数字字母}.csv\";i:105;s:35:\"/index.php?sex={数字字母}.shtml\";i:106;s:33:\"/index.php?sex={数字字母}.pdf\";i:107;s:34:\"/index.php?sex={数字字母}.docx\";i:108;s:34:\"/index.php?sex={数字字母}.xlsx\";i:109;s:41:\"/index.php?sex={数字1-5}{字母1-5}.xml\";i:110;s:41:\"/index.php?sex={数字1-5}{字母1-5}.doc\";i:111;s:41:\"/index.php?sex={数字1-5}{字母1-5}.txt\";i:112;s:41:\"/index.php?sex={数字1-5}{字母1-5}.ppt\";i:113;s:41:\"/index.php?sex={数字1-5}{字母1-5}.xls\";i:114;s:41:\"/index.php?sex={数字1-5}{字母1-5}.csv\";i:115;s:43:\"/index.php?sex={数字1-5}{字母1-5}.shtml\";i:116;s:41:\"/index.php?sex={数字1-5}{字母1-5}.pdf\";i:117;s:42:\"/index.php?sex={数字1-5}{字母1-5}.docx\";i:118;s:42:\"/index.php?sex={数字1-5}{字母1-5}.xlsx\";i:119;s:41:\"/index.php?sex={字母1-5}{数字1-5}.xml\";i:120;s:41:\"/index.php?sex={字母1-5}{数字1-5}.doc\";i:121;s:41:\"/index.php?sex={字母1-5}{数字1-5}.txt\";i:122;s:41:\"/index.php?sex={字母1-5}{数字1-5}.ppt\";i:123;s:41:\"/index.php?sex={字母1-5}{数字1-5}.xls\";i:124;s:41:\"/index.php?sex={字母1-5}{数字1-5}.csv\";i:125;s:43:\"/index.php?sex={字母1-5}{数字1-5}.shtml\";i:126;s:41:\"/index.php?sex={字母1-5}{数字1-5}.pdf\";i:127;s:42:\"/index.php?sex={字母1-5}{数字1-5}.docx\";i:128;s:42:\"/index.php?sex={字母1-5}{数字1-5}.xlsx\";i:129;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.xml\";i:130;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.doc\";i:131;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.txt\";i:132;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.ppt\";i:133;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.xls\";i:134;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.csv\";i:135;s:50:\"/index.php?sex={年}{月}{日}{随机字符}.shtml\";i:136;s:48:\"/index.php?sex={年}{月}{日}{随机字符}.pdf\";i:137;s:49:\"/index.php?sex={年}{月}{日}{随机字符}.docx\";i:138;s:49:\"/index.php?sex={年}{月}{日}{随机字符}.xlsx\";i:139;s:45:\"/index.php?sex={随机字符}/{数字1-5}.xml\";i:140;s:45:\"/index.php?sex={随机字符}/{数字1-5}.doc\";i:141;s:45:\"/index.php?sex={随机字符}/{数字1-5}.txt\";i:142;s:45:\"/index.php?sex={随机字符}/{数字1-5}.ppt\";i:143;s:45:\"/index.php?sex={随机字符}/{数字1-5}.xls\";i:144;s:45:\"/index.php?sex={随机字符}/{数字1-5}.csv\";i:145;s:47:\"/index.php?sex={随机字符}/{数字1-5}.shtml\";i:146;s:45:\"/index.php?sex={随机字符}/{数字1-5}.pdf\";i:147;s:46:\"/index.php?sex={随机字符}/{数字1-5}.docx\";i:148;s:46:\"/index.php?sex={随机字符}/{数字1-5}.xlsx\";i:149;s:45:\"/index.php?sex={随机字符}/{字母1-5}.xml\";i:150;s:45:\"/index.php?sex={随机字符}/{字母1-5}.doc\";i:151;s:45:\"/index.php?sex={随机字符}/{字母1-5}.txt\";i:152;s:45:\"/index.php?sex={随机字符}/{字母1-5}.ppt\";i:153;s:45:\"/index.php?sex={随机字符}/{字母1-5}.xls\";i:154;s:45:\"/index.php?sex={随机字符}/{字母1-5}.csv\";i:155;s:47:\"/index.php?sex={随机字符}/{字母1-5}.shtml\";i:156;s:45:\"/index.php?sex={随机字符}/{字母1-5}.pdf\";i:157;s:46:\"/index.php?sex={随机字符}/{字母1-5}.docx\";i:158;s:46:\"/index.php?sex={随机字符}/{字母1-5}.xlsx\";i:159;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.xml\";i:160;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.doc\";i:161;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.txt\";i:162;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.ppt\";i:163;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.xls\";i:164;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.csv\";i:165;s:56:\"/index.php?sex={大小写字母数字}/{数字1-5}.shtml\";i:166;s:54:\"/index.php?sex={大小写字母数字}/{数字1-5}.pdf\";i:167;s:55:\"/index.php?sex={大小写字母数字}/{数字1-5}.docx\";i:168;s:55:\"/index.php?sex={大小写字母数字}/{数字1-5}.xlsx\";i:169;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.xml\";i:170;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.doc\";i:171;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.txt\";i:172;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.ppt\";i:173;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.xls\";i:174;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.csv\";i:175;s:56:\"/index.php?sex={大小写字母数字}/{字母1-5}.shtml\";i:176;s:54:\"/index.php?sex={大小写字母数字}/{字母1-5}.pdf\";i:177;s:55:\"/index.php?sex={大小写字母数字}/{字母1-5}.docx\";i:178;s:55:\"/index.php?sex={大小写字母数字}/{字母1-5}.xlsx\";i:179;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.xml\";i:180;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.doc\";i:181;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.txt\";i:182;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.ppt\";i:183;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.xls\";i:184;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.csv\";i:185;s:51:\"/index.php?sex={年}{月}{日}/{随机字符}.shtml\";i:186;s:49:\"/index.php?sex={年}{月}{日}/{随机字符}.pdf\";i:187;s:50:\"/index.php?sex={年}{月}{日}/{随机字符}.docx\";i:188;s:50:\"/index.php?sex={年}{月}{日}/{随机字符}.xlsx\";i:189;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.xml\";i:190;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.doc\";i:191;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.txt\";i:192;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.ppt\";i:193;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.xls\";i:194;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.csv\";i:195;s:60:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.shtml\";i:196;s:58:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.pdf\";i:197;s:59:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.docx\";i:198;s:59:\"/index.php?sex={年}{月}{日}/{大小写字母数字}.xlsx\";i:199;s:44:\"/index.php?{随机字符}={随机字符}.xml\";i:200;s:44:\"/index.php?{随机字符}={随机字符}.doc\";i:201;s:44:\"/index.php?{随机字符}={随机字符}.txt\";i:202;s:44:\"/index.php?{随机字符}={随机字符}.ppt\";i:203;s:44:\"/index.php?{随机字符}={随机字符}.xls\";i:204;s:44:\"/index.php?{随机字符}={随机字符}.csv\";i:205;s:46:\"/index.php?{随机字符}={随机字符}.shtml\";i:206;s:44:\"/index.php?{随机字符}={随机字符}.pdf\";i:207;s:45:\"/index.php?{随机字符}={随机字符}.docx\";i:208;s:45:\"/index.php?{随机字符}={随机字符}.xlsx\";i:209;s:53:\"/index.php?{随机字符}={大小写字母数字}.xml\";i:210;s:53:\"/index.php?{随机字符}={大小写字母数字}.doc\";i:211;s:53:\"/index.php?{随机字符}={大小写字母数字}.txt\";i:212;s:53:\"/index.php?{随机字符}={大小写字母数字}.ppt\";i:213;s:53:\"/index.php?{随机字符}={大小写字母数字}.xls\";i:214;s:53:\"/index.php?{随机字符}={大小写字母数字}.csv\";i:215;s:55:\"/index.php?{随机字符}={大小写字母数字}.shtml\";i:216;s:53:\"/index.php?{随机字符}={大小写字母数字}.pdf\";i:217;s:54:\"/index.php?{随机字符}={大小写字母数字}.docx\";i:218;s:54:\"/index.php?{随机字符}={大小写字母数字}.xlsx\";i:219;s:44:\"/index.php?{随机字符}={数字字母}.xml\";i:220;s:44:\"/index.php?{随机字符}={数字字母}.doc\";i:221;s:44:\"/index.php?{随机字符}={数字字母}.txt\";i:222;s:44:\"/index.php?{随机字符}={数字字母}.ppt\";i:223;s:44:\"/index.php?{随机字符}={数字字母}.xls\";i:224;s:44:\"/index.php?{随机字符}={数字字母}.csv\";i:225;s:46:\"/index.php?{随机字符}={数字字母}.shtml\";i:226;s:44:\"/index.php?{随机字符}={数字字母}.pdf\";i:227;s:45:\"/index.php?{随机字符}={数字字母}.docx\";i:228;s:45:\"/index.php?{随机字符}={数字字母}.xlsx\";i:229;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.xml\";i:230;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.doc\";i:231;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.txt\";i:232;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.ppt\";i:233;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.xls\";i:234;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.csv\";i:235;s:54:\"/index.php?{随机字符}={数字1-5}{字母1-5}.shtml\";i:236;s:52:\"/index.php?{随机字符}={数字1-5}{字母1-5}.pdf\";i:237;s:53:\"/index.php?{随机字符}={数字1-5}{字母1-5}.docx\";i:238;s:53:\"/index.php?{随机字符}={数字1-5}{字母1-5}.xlsx\";i:239;s:52:\"/index.php?{随机字符}={字母1-5}{数字1-5}.xml\";i:240;s:52:\"/index.php?{随机字符}={字母1-5}{数字1-5}.doc\";i:241;s:52:\"/index.php?{随机字符}={字母1-5}{数字1-5}.txt\";}', 1651762341, 'AO61UF', 0, 0, 0, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (27, 'aspx规则', 'a:60:{i:0;s:26:\"/{随机字符}/index.aspx\";i:1;s:28:\"/{随机字符}/default.aspx\";i:2;s:28:\"/{随机字符}/Default.aspx\";i:3;s:29:\"/{年}{月}{日}/Default.aspx\";i:4;s:27:\"/{年}{月}{日}/index.aspx\";i:5;s:24:\"/soft{随机字符}.aspx\";i:6;s:24:\"/soft{随机字符}.aspx\";i:7;s:24:\"/soft{随机字符}.aspx\";i:8;s:24:\"/soft{随机字符}.aspx\";i:9;s:24:\"/soft{随机字符}.aspx\";i:10;s:33:\"/soft{大小写字母数字}.aspx\";i:11;s:33:\"/soft{大小写字母数字}.aspx\";i:12;s:33:\"/soft{大小写字母数字}.aspx\";i:13;s:31:\"/20{大小写字母数字}.aspx\";i:14;s:29:\"/{大小写字母数字}.aspx\";i:15;s:29:\"/{大小写字母数字}.aspx\";i:16;s:29:\"/{大小写字母数字}.aspx\";i:17;s:29:\"/{大小写字母数字}.aspx\";i:18;s:29:\"/{大小写字母数字}.aspx\";i:19;s:29:\"/{大小写字母数字}.aspx\";i:20;s:20:\"/{数字字母}.aspx\";i:21;s:20:\"/{数字字母}.aspx\";i:22;s:20:\"/{数字字母}.aspx\";i:23;s:20:\"/{数字字母}.aspx\";i:24;s:20:\"/{数字字母}.aspx\";i:25;s:20:\"/{数字字母}.aspx\";i:26;s:20:\"/{数字字母}.aspx\";i:27;s:20:\"/{数字字母}.aspx\";i:28;s:20:\"/{数字字母}.aspx\";i:29;s:20:\"/{数字字母}.aspx\";i:30;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:31;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:32;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:33;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:34;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:35;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:36;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:37;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:38;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:39;s:28:\"/{数字1-5}{字母1-5}.aspx\";i:40;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:41;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:42;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:43;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:44;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:45;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:46;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:47;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:48;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:49;s:28:\"/{字母1-5}{数字1-5}.aspx\";i:50;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:51;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:52;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:53;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:54;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:55;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:56;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:57;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:58;s:35:\"/{年}{月}{日}{随机字符}.aspx\";i:59;s:35:\"/{年}{月}{日}{随机字符}.aspx\";}', 1662450767, 'OPcahx', 5, 0, 0, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (29, 'IIS泛目录规则', 'a:17:{i:0;s:21:\"/doms/{随机字符}/\";i:1;s:21:\"/soft/{随机字符}/\";i:2;s:21:\"/20mx/{随机字符}/\";i:3;s:21:\"/zips/{随机字符}/\";i:4;s:21:\"/rars/{随机字符}/\";i:5;s:20:\"/doms{随机字符}/\";i:6;s:20:\"/soft{随机字符}/\";i:7;s:20:\"/20mx{随机字符}/\";i:8;s:20:\"/zips{随机字符}/\";i:9;s:20:\"/rars{随机字符}/\";i:10;s:59:\"/20mx/{随机字符}/{年}-{月}-{日}/{数字字母}.shtml\";i:11;s:59:\"/zips/{随机字符}/{年}-{月}-{日}/{数字字母}.shtml\";i:12;s:59:\"/rars/{随机字符}/{年}-{月}-{日}/{数字字母}.shtml\";i:13;s:59:\"/doms/{随机字符}/{年}-{月}-{日}/{数字字母}.shtml\";i:14;s:24:\"/soft{随机字符}.html\";i:15;s:24:\"/zips{随机字符}.shtm\";i:16;s:24:\"/rars{随机字符}.html\";}', 1667770653, 'wo26z7', 3, 1, 0, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (30, 'IIS_DIR', 'a:12:{i:0;s:41:\"/thnews-{年}-{月}-{日}-{数字6}.shtml\";i:1;s:40:\"/thnews-{年}-{月}-{日}-{数字5}.html\";i:2;s:39:\"/slot-{数字6}-{月}-{日}-{年}.shtml\";i:3;s:38:\"/slot-{数字6}-{月}-{日}-{年}.html\";i:4;s:47:\"/bet-{随机字符}-{月}-{日}-{数字6}.shtml\";i:5;s:46:\"/bet-{随机字符}-{月}-{日}-{数字5}.html\";i:6;s:42:\"/android-{年}-{月}-{日}-{数字6}.shtml\";i:7;s:41:\"/android-{年}-{月}-{日}-{数字5}.html\";i:8;s:47:\"/ios-{随机字符}-{月}-{日}-{数字6}.shtml\";i:9;s:46:\"/ios-{随机字符}-{月}-{日}-{数字5}.html\";i:10;s:48:\"/book-{随机字符}-{月}-{日}-{数字6}.shtml\";i:11;s:47:\"/book-{随机字符}-{月}-{日}-{数字5}.html\";}', 1706599469, 'kHZNlw', 0, 0, 0, 0, NULL, '');
INSERT INTO `seo_url_rules` VALUES (31, '测试TL对应', 'a:8:{i:0;s:19:\"/android/{tl}.shtml\";i:1;s:15:\"/ios/{tl}.shtml\";i:2;s:18:\"/thnews/{tl}.shtml\";i:3;s:11:\"/slot/{tl}/\";i:4;s:14:\"/article/{tl}/\";i:5;s:13:\"/thnews/{tl}/\";i:6;s:14:\"/android/{tl}/\";i:7;s:10:\"/ios/{tl}/\";}', 1733404235, '8WiuEX', 0, 0, 0, 1, 'seo_tl_thaiall', 'th-TH');
INSERT INTO `seo_url_rules` VALUES (32, 'app二弟', 'a:8:{i:0;s:19:\"/android/{tl}.shtml\";i:1;s:15:\"/ios/{tl}.shtml\";i:2;s:18:\"/thnews/{tl}.shtml\";i:3;s:11:\"/slot/{tl}/\";i:4;s:14:\"/article/{tl}/\";i:5;s:13:\"/thnews/{tl}/\";i:6;s:14:\"/android/{tl}/\";i:7;s:10:\"/ios/{tl}/\";}', 1733131943, 'dAiorV', 1, 1, 0, 1, 'seo_tl_thall24', 'th-TH');
INSERT INTO `seo_url_rules` VALUES (33, 'TH测试', 'a:3:{i:0;s:14:\"/betting-{tl}/\";i:1;s:11:\"/slot/{tl}/\";i:2;s:18:\"/gameing-{tl}.html\";}', 1733499645, 'PD6rtU', 0, 0, 0, 1, 'seo_tl_yiseo12', 'th-TH');
INSERT INTO `seo_url_rules` VALUES (34, '测试vn', 'a:2:{i:0;s:11:\"/{tl}-slot/\";i:1;s:13:\"/{tl}-beting/\";}', 1735223491, '8XCv4b', 0, 0, 0, 1, 'seo_tl_vnshop1226', 'vi');

-- ----------------------------
-- Table structure for seo_users
-- ----------------------------
DROP TABLE IF EXISTS `seo_users`;
CREATE TABLE `seo_users`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `username` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录密码；mb_password加密',
  `salt` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '加盐',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像，相对于upload/avatar目录',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `email_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '激活码',
  `phone` bigint(11) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT '手机号',
  `status` tinyint(1) NOT NULL DEFAULT 2 COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `register_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `last_login_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '最后登录时间',
  `google_keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'google验证码',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_login_key`(`username`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 92 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_users
-- ----------------------------
INSERT INTO `seo_users` VALUES (88, 'admin', '4a316a11f3f07b040819c4bb1f08ba52', '1257ed', '/Upload/avatar/user1.jpg', '', '', NULL, 1, 1449199996, '', 0, 'YE5NKRPCABHLBGVZ');

-- ----------------------------
-- Table structure for seo_website_category
-- ----------------------------
DROP TABLE IF EXISTS `seo_website_category`;
CREATE TABLE `seo_website_category`  (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '类别ID',
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of seo_website_category
-- ----------------------------
INSERT INTO `seo_website_category` VALUES (9, '本地测试');

-- ----------------------------
-- Table structure for seo_website_lists
-- ----------------------------
DROP TABLE IF EXISTS `seo_website_lists`;
CREATE TABLE `seo_website_lists`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT '站点类别',
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '显示名称',
  `bind_domains` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '绑定的子域名',
  `profile_id` int(11) NOT NULL COMMENT '站点的配置文件',
  `lastedite_time` int(11) NOT NULL COMMENT '上次编辑的时间',
  `seo_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'seo配置',
  `clsid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'clsid',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of seo_website_lists
-- ----------------------------
INSERT INTO `seo_website_lists` VALUES (6, 9, '123123', 'a:2:{i:0;s:12:\"go.godseo.me\";i:1;s:18:\"fun.webgroup.ac.th\";}', 10, 1736413564, NULL, '2b23ddf4-2f3a-3f6b-ff20-4f7396447556');
INSERT INTO `seo_website_lists` VALUES (7, 9, 'cc21', 'a:1:{i:0;s:14:\"webgroup.go.th\";}', 11, 1736414524, NULL, 'd61d3d8f-71b7-906e-fda3-92919bd6f069');

-- ----------------------------
-- Table structure for seo_website_profile
-- ----------------------------
DROP TABLE IF EXISTS `seo_website_profile`;
CREATE TABLE `seo_website_profile`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `category` int(11) NOT NULL DEFAULT 1 COMMENT '类别：1：根据关键词库生成子域名(DKT)\r\n2：随机字符串生成子域名',
  `category_keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '关键词的词表',
  `category_content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '文章内容',
  `category_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '站点模板',
  `ad_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL,
  `analytics_custom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT '插入统计代码 或其他跟踪代码',
  `last_edit_time` int(11) NOT NULL COMMENT '上次编辑时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of seo_website_profile
-- ----------------------------
INSERT INTO `seo_website_profile` VALUES (10, '本地站群', 1, 'seo_tl_thaiall', 'article_th', 'shopng.html', 'http://platform.godseo.me/', '', 1736358134);
INSERT INTO `seo_website_profile` VALUES (11, 'ccc', 1, 'seo_tl_thall24', 'article_th', 'content_lazada.html', 'https://www.google.com', '', 1736414394);

-- ----------------------------
-- Table structure for seo_zqmanager
-- ----------------------------
DROP TABLE IF EXISTS `seo_zqmanager`;
CREATE TABLE `seo_zqmanager`  (
  `zq_id` int(11) NOT NULL AUTO_INCREMENT,
  `zq_day_count` int(11) NULL DEFAULT NULL,
  `zq_page_count` int(11) NULL DEFAULT NULL,
  `zq_baiduspider_count` int(11) NULL DEFAULT NULL,
  `zq_sogouspider_count` int(11) NULL DEFAULT NULL,
  `zq_360spider_count` bigint(11) NULL DEFAULT NULL,
  `zq_yisouspider_count` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`zq_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 37 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of seo_zqmanager
-- ----------------------------

-- ----------------------------
-- Table structure for seo_zqmanager_log
-- ----------------------------
DROP TABLE IF EXISTS `seo_zqmanager_log`;
CREATE TABLE `seo_zqmanager_log`  (
  `zq_id` int(11) NOT NULL AUTO_INCREMENT,
  `zq_spider_types` tinyint(255) NULL DEFAULT NULL,
  `zq_request_uri` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `zq_full_ua` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `zq_domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `zq_real_ip` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `zq_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL,
  `zq_request_times` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`zq_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 298705 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo_zqmanager_log
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
