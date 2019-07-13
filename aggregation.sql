/*
 Navicat Premium Data Transfer

 Source Server         : 3357
 Source Server Type    : MySQL
 Source Server Version : 50725
 Source Host           : localhost
 Source Database       : aggregation

 Target Server Type    : MySQL
 Target Server Version : 50725
 File Encoding         : utf-8

 Date: 07/13/2019 10:57:31 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `administrator`
-- ----------------------------
DROP TABLE IF EXISTS `administrator`;
CREATE TABLE `administrator` (
  `uid` int(8) NOT NULL AUTO_INCREMENT COMMENT '用户ID,主键',
  `uuid` char(36) CHARACTER SET utf8 NOT NULL COMMENT 'UUID',
  `mobile` char(11) CHARACTER SET utf8 NOT NULL COMMENT '手机号码(可用作登录)',
  `username` char(60) COLLATE utf8mb4_bin NOT NULL COMMENT '登录用户名',
  `email` char(60) CHARACTER SET utf8 DEFAULT '' COMMENT '邮箱(可用作登录)',
  `password` char(32) CHARACTER SET utf8 DEFAULT '' COMMENT '登录密码',
  `encrypt` char(10) CHARACTER SET utf8 DEFAULT '' COMMENT '密码加密因子',
  `name` varchar(60) CHARACTER SET utf8 DEFAULT '' COMMENT '用户名字',
  `avatar` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '用户头像',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:可用,2:禁用(不可登录)',
  `token` char(36) CHARACTER SET utf8 DEFAULT '' COMMENT '登录token',
  `expiration_date_token` int(11) NOT NULL DEFAULT '0' COMMENT 'token过期时间',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  `delete_at` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uid` (`uid`,`uuid`,`mobile`,`token`,`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- ----------------------------
--  Records of `administrator`
-- ----------------------------
BEGIN;
INSERT INTO `administrator` VALUES ('1', '9688625C-B422-B283-9F8D-0487C507B2AF', '13123144888', '', 'yangchenpeng@qq.com', '16c91ca76866363e6e8ec73bf82cd86f', 'uZb6ZG', '杨陈鹏', '', '1', '5b010861-bcf7-5566-10a7-d8416b0f0c4f', '1562382820', '0', '1561518820', '0');
COMMIT;

-- ----------------------------
--  Table structure for `authorization`
-- ----------------------------
DROP TABLE IF EXISTS `authorization`;
CREATE TABLE `authorization` (
  `uid` int(8) NOT NULL COMMENT '用户组ID,主键',
  `groups` char(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户组ID，可多个,使用半角","分割',
  `rules` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户组拥有的规则id， 多个规则","隔开',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  `delete_at` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='用户授权';

-- ----------------------------
--  Records of `authorization`
-- ----------------------------
BEGIN;
INSERT INTO `authorization` VALUES ('1', '1', '', '1511333224', '1511670028', '0'), ('2', '9', '', '1511336316', '1511764790', '0'), ('3', '', '', '1511336784', '1511670070', '0'), ('4', '3', '', '1511336789', '1530629934', '0'), ('5', '2', '', '1530630002', '1530630002', '0'), ('6', '4', '', '1533019017', '1533019017', '0'), ('7', '3', '', '1511336795', '1533258065', '0'), ('8', '3', '', '1533258041', '1533258059', '0'), ('9', '3', '', '1533258033', '1533258053', '0');
COMMIT;

-- ----------------------------
--  Table structure for `group`
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'ID,主键',
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '管理组名称',
  `rules` text CHARACTER SET utf8 COMMENT '用户组拥有的规则id， 多个规则","隔开',
  `descriptions` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '描述',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '修改时间',
  `delete_at` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='管理组/角色';

-- ----------------------------
--  Records of `group`
-- ----------------------------
BEGIN;
INSERT INTO `group` VALUES ('1', '超级管理员', '1,3,12,13,15,16,17,5,14,18,19,7,20,21,22,23,24,26,25,27,28,29,112', '超级管理员', '0', '1561465054', '0');
COMMIT;

-- ----------------------------
--  Table structure for `rule`
-- ----------------------------
DROP TABLE IF EXISTS `rule`;
CREATE TABLE `rule` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'ID, 主键',
  `pid` int(8) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `name` char(30) CHARACTER SET utf8 NOT NULL COMMENT '规则名称',
  `identification` char(255) CHARACTER SET utf8 NOT NULL COMMENT '规则唯一标识',
  `icon_class` char(60) CHARACTER SET utf8 DEFAULT '' COMMENT '规则字体图标',
  `icon_family` char(60) CHARACTER SET utf8 DEFAULT 'an-mall-icon' COMMENT '字体图标font-family class',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型(1:菜单,2:路由类的功能性按钮,3:非路由类的功能性按钮,4: 展示类权限)',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序字段,主要用于菜单排序',
  `address` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '路由地址,因为前段使用name字段进行路由,所以该字段暂时不会被使用',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  `delete_at` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`identification`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPACT COMMENT='权限规则表';

-- ----------------------------
--  Records of `rule`
-- ----------------------------
BEGIN;
INSERT INTO `rule` VALUES ('1', '0', '权限管理', 'permission', 'icon-quanxianguanli1', 'an-mall-icon', '1', '1', '', '1510823097', '1511581910', '0'), ('3', '1', '管理员', 'permission.administrator.list', 'icon-guanliyuan', 'an-mall-icon', '1', '1', '', '1510900497', '1511670357', '0'), ('5', '1', '权限', 'permission.rule.list', 'icon-permission', 'an-mall-icon', '1', '1', '', '1510900597', '1511670388', '0'), ('7', '1', '管理组', 'permission.group.list', 'icon-guanlifenzu', 'an-mall-icon', '1', '1', '', '1510906482', '1511670405', '0'), ('12', '3', '添加', 'permission.administrator.create', '', 'an-mall-icon', '2', '1', '', '1511672044', '1511672044', '0'), ('13', '3', '编辑', 'permission.administrator.update', '', 'an-mall-icon', '2', '1', '', '1511672093', '1511672093', '0'), ('14', '5', '添加', 'permission.rule.create', '', 'an-mall-icon', '2', '1', '', '1511679168', '1511679168', '0'), ('15', '3', '授权', 'permission.administrator.authorization', '', 'an-mall-icon', '3', '1', '', '1511679269', '1511679324', '0'), ('16', '3', '删除', 'permission.administrator.delete', '', 'an-mall-icon', '3', '1', '', '1511679312', '1511679312', '0'), ('17', '3', '禁封/解禁', 'permission.administrator.disable_or_enable', '', 'an-mall-icon', '3', '1', '', '1511679401', '1511679401', '0'), ('18', '5', '编辑', 'permission.rule.update', '', 'an-mall-icon', '2', '1', '', '1511679446', '1511679446', '0'), ('19', '5', '删除', 'permission.rule.delete', '', 'an-mall-icon', '3', '1', '', '1511679472', '1511679472', '0'), ('20', '7', '添加', 'permission.group.create', '', 'an-mall-icon', '2', '1', '', '1511679509', '1511679509', '0'), ('21', '7', '编辑', 'permission.group.update', '', 'an-mall-icon', '2', '1', '', '1511679561', '1511679561', '0'), ('22', '7', '删除', 'permission.group.delete', '', 'an-mall-icon', '3', '1', '', '1511679592', '1511679592', '0'), ('23', '7', '分配权限', 'permission.group.authorization', '', 'an-mall-icon', '3', '1', '', '1511679643', '1511679643', '0'), ('24', '0', '系统设置', 'setting', 'icon-setting', 'an-mall-icon', '1', '1', '', '1511924145', '1511924402', '0'), ('25', '26', '白名单', 'setting.rules.white_list', '', 'an-mall-icon', '1', '1', '', '1511925793', '1511926639', '0'), ('26', '24', '权限相关', 'setting.rules', 'icon-permission', 'an-mall-icon', '1', '1', '', '1511926097', '1511926595', '0'), ('27', '25', '添加', 'setting.rules.white_list.create', '', 'an-mall-icon', '3', '1', '', '1511938031', '1511938031', '0'), ('28', '25', '编辑', 'setting.rules.white_list.update', '', 'an-mall-icon', '3', '1', '', '1511938054', '1511938054', '0'), ('29', '25', '删除', 'setting.rules.white_list.delete', '', 'an-mall-icon', '3', '1', '', '1511938088', '1511938088', '0'), ('34', '0', '基础数据', 'basic', 'icon-basic-data', 'v-font', '1', '1', '', '1527909562', '1561457721', '1561457721'), ('35', '34', '分类管理', 'basic.category', 'icon-category', 'v-font', '1', '1', '', '1527909650', '1561457718', '1561457718'), ('36', '35', '分类列表', 'basic.category.items', '', '', '1', '1', '', '1527909705', '1561457703', '1561457703'), ('37', '35', '修改分类', 'basic.category.edit', '', '', '2', '1', '', '1527909781', '1561457705', '1561457705'), ('38', '35', '删除分类', 'basic.category.delete', '', '', '3', '1', '', '1527909814', '1561457706', '1561457706'), ('39', '35', '创建分类', 'basic.category.create', '', '', '2', '1', '', '1527910389', '1561457708', '1561457708'), ('108', '0', '视频管理', 'video', 'icon-video', 'v-font', '1', '1', '', '1550833595', '1561457631', '1561457631'), ('109', '108', '视频管理', 'video.management', 'icon-video', 'v-font', '1', '1', '', '1550833817', '1561457627', '1561457627'), ('110', '109', '视频上传', 'video.management.upload', 'icon-shangchuan', 'v-font', '1', '1', '', '1550833854', '1561457624', '1561457624'), ('111', '109', '视频转码', 'video.management.transcoding', 'icon-meitizhuanma', 'v-font', '1', '1', '', '1550833907', '1561457620', '1561457620'), ('112', '0', '控制台', 'dashboard', 'icon-yibiao', 'an-mall-icon', '1', '1', '', '1561465034', '1561465321', '0');
COMMIT;

-- ----------------------------
--  Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `key` char(255) CHARACTER SET utf8mb4 NOT NULL,
  `value` text CHARACTER SET utf8mb4 NOT NULL,
  `description` char(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '配置描述',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '修改时间',
  `delete_at` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC COMMENT='系统设置表';

-- ----------------------------
--  Records of `setting`
-- ----------------------------
BEGIN;
INSERT INTO `setting` VALUES ('agent_upgrade', '{\"invitation\":\"50\",\"pay\":\"399\"}', '代理商升级配置,invitation单位为人,pay单位为元', '1536139762', '1536482520', '0'), ('app_block_item', '[{\"sort\":0,\"image\":\"\\/thumb\\/20180801\\/2f8a0e2156bbfd87e28661e3188b498e.jpg\",\"name\":\"\\u978b\\u5b50\",\"jump_type\":1,\"jump\":\"1\"},{\"sort\":1,\"image\":\"\\/thumb\\/20180801\\/500a95c21e10945d4d1483045cdc3d41.jpg\",\"name\":\"\\u5305\\u5305\",\"jump_type\":1,\"jump\":\"4\"},{\"sort\":2,\"image\":\"\\/thumb\\/20180801\\/c38bd771dad462f20b4759188400131c.jpg\",\"name\":\"\\u88e4\\u5b50\",\"jump_type\":1,\"jump\":\"31\"},{\"sort\":3,\"image\":\"\\/thumb\\/20180801\\/389d9fa0b85b7d87fbdc54d74293005c.jpg\",\"name\":\"\\u7537\\u88c5\",\"jump_type\":1,\"jump\":\"40\"},{\"sort\":4,\"image\":\"\\/thumb\\/20180801\\/e372339c244007b11593573b3e8ae09a.jpg\",\"name\":\"\\u5973\\u88c5\",\"jump_type\":1,\"jump\":\"37\"},{\"sort\":5,\"image\":\"\\/thumb\\/20180801\\/3521f2568719c153992620476df32ff5.jpg\",\"name\":\"\\u76ae\\u5e26\",\"jump_type\":1,\"jump\":\"45\"},{\"sort\":6,\"image\":\"\\/thumb\\/20180801\\/8f7a2d38d8f5b6cba86c3babbf418e28.jpg\",\"name\":\"\\u9970\\u54c1\",\"jump_type\":1,\"jump\":\"46\"},{\"sort\":7,\"image\":\"\\/thumb\\/20180801\\/ab9a0e6eb6de004f5f9b808ab0d2b71d.jpg\",\"name\":\"\\u624b\\u8868\",\"jump_type\":1,\"jump\":\"47\"}]', '应用首页的分类展示块', '1532784425', '1533103729', '0'), ('app_brand', '[{\"sort\":0,\"image\":\"\\/thumb\\/20180810\\/d0564fd692dbf4bd473a4873443f9582.jpg\",\"name\":\"GUCCI\",\"id\":\"1\"},{\"sort\":1,\"image\":\"\\/thumb\\/20180810\\/239bb0649499ccca8ccd7e5524046d6e.jpg\",\"name\":\"LV\",\"id\":\"2\"},{\"sort\":2,\"image\":\"\\/thumb\\/20180810\\/4f25f9e2e1a0e4e3c3f0720740f70cda.jpg\",\"name\":\"\\u9999\\u5948\\u513f\",\"id\":\"8\"},{\"sort\":3,\"image\":\"\\/thumb\\/20180810\\/c2e87cf85273cdddbf5b393b03af770e.jpg\",\"name\":\"\\u5b9d\\u683c\\u4e3d\",\"id\":\"3\"},{\"sort\":4,\"image\":\"\\/thumb\\/20180810\\/65e87b2f0063e11fafbb9a44d8fd8306.jpg\",\"name\":\"\\u5df4\\u9ece\\u4e16\\u5bb6\",\"id\":\"4\"},{\"sort\":5,\"image\":\"\\/thumb\\/20180810\\/a77b4b19e22ca18afecd97266af3be1f.jpg\",\"name\":\"\\u7eaa\\u68b5\\u5e0c\",\"id\":\"6\"},{\"sort\":6,\"image\":\"\\/thumb\\/20180814\\/62c988dbd08041fcba6114afc3d943be.jpg\",\"name\":\"\\u5361\\u5730\\u4e9a\",\"id\":\"9\"},{\"sort\":7,\"image\":\"\\/thumb\\/20180814\\/614d9b7dcdf4893042f1d6c84f80e5e3.jpg\",\"name\":\"\\u8482\\u8299\\u5c3c\",\"id\":\"10\"}]', '首页要展示的品牌', '1532875019', '1534225560', '0'), ('app_swiper', '[{\"sort\":0,\"image\":\"\\/thumb\\/20180801\\/c809a871fddbf10d67c676211315ddca.jpg\",\"descriptions\":\"\\u53e4\\u9a70\",\"jump_type\":2,\"jump\":\"1\"},{\"sort\":1,\"image\":\"\\/thumb\\/20180801\\/cce54c2b506b57f0da398bfe6e2ddc58.jpg\",\"descriptions\":\"\\u8def\\u6613\\u5a01\\u767b\",\"jump_type\":2,\"jump\":\"2\"}]', '引用首页轮播图', '1532948884', '1533091928', '0'), ('customer_wechat', '{\"wechat\":\"ly6229638\"}', '客服微信', '1530629721', '1536899073', '0'), ('distribution_ratio', '{\"switch\":true,\"member\":{\"first\":\"15\",\"second\":\"5\"},\"agent\":{\"first\":\"30\",\"second\":\"10\"},\"purchase_agent_member\":{\"first\":\"25\",\"second\":\"12.5\"},\"purchase_agent_agent\":{\"first\":\"50\",\"second\":\"20\"}}', '分销比例', '1534390835', '1538022262', '0'), ('rule_white_list', '[{\"name\":\"\\u63a7\\u5236\\u53f0\",\"identification\":\"dashboard\"}]', '总后台路由白名单', '1511942531', '1512006533', '0');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
