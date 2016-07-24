# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.5.42)
# Database: tpadmin
# Generation Time: 2015-12-03 03:23:47 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table category
# ------------------------------------------------------------

-- drop database if exists aquariumDB;
-- create database aquariumDB;
#CHARACTER SET 'utf8';
#COLLATE 'utf8_general_ci';
-- use aquariumDB;
-- create database testone;
use onethink;


DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `create_at` varchar(20) DEFAULT '0',
  `update_at` varchar(20) DEFAULT '0',
  `login_ip` varchar(20) DEFAULT NULL,

  `nickname` varchar(20) DEFAULT NULL,
  `good_at` varchar(100) DEFAULT NULL,
  `gender` varchar(2) DEFAULT NULL,
  `feed_year` float DEFAULT NULL,
  `area_address` varchar(40) DEFAULT NULL,
  `district_address` varchar(40) DEFAULT NULL,
  `has_new_message` tinyint(1) DEFAULT '0' COMMENT '0:没有新消息 1:有新消息',

  `status` tinyint(1) DEFAULT '1' COMMENT '0:禁止登陆 1:正常 -1:已删除',
  `type` tinyint(1) DEFAULT '1' COMMENT '1:前台用户 2:管理员 ',
  PRIMARY KEY (`id`),
  KEY `username` (`username`) USING BTREE,
  KEY `password` (`password`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;

INSERT INTO `user` (`id`, `username`, `email`, `password`, `avatar`, `create_at`, `update_at`, `login_ip`, `status`, `type`,`nickname`,`good_at`,`gender`,`feed_year`,`area_address`,`district_address`)
VALUES
	(1,'18121380371','515343908@qq.com','96e79218965eb72c92a549dd5a330112','57610b640b1d5.jpg','1467211497','1467211497','0.0.0.0',1,2,'Tom','[1,3,5]','男',8,'杭州市西湖区','文三路33号');

UNLOCK TABLES;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


DROP TABLE IF EXISTS errcode;

CREATE TABLE errcode(
code varchar(10) NOT NULL PRIMARY KEY,
msg varchar(40) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO errcode (code, msg)
VALUES
	('LG0000','操作成功'),
	('LG0001','用户名或密码错误'),
	('LG0002','验证码检验失败'),
	('LG0003','手机号已注册'),
	('LG0004','手机号还未注册'),
	('LG0005','用户已被禁止登陆'),
	('CM0000', '操作成功'),
	('CM0001','HTTP请求方式错误'),
	('CM0002', '数据库操作失败'),
	('CM0003', '请登录后再操作'),
	('CM0004', '上传文件失败'),
	('CM0005', '读取文件失败'),
	('CM0006', '没有找到用户'),
	('FH0000', '操作成功'),
	('FH0001', '已存在此鱼种'),
	('FH0002', '没有找到鱼缸'),
	('FH0003', '没有找到此设备'),
	('FH0004', '设备添加失败'),
	('FH0005', '暂不支持此类设备'),
	('FH0006', '此鱼缸该种类设备已超出最大限定个数'),
	('SM0000', '短信发送成功'),
	('SM0010','验证信息失败'),
	('SM0011','用户接口被禁用'),
	('SM0020','短信余额不足'),
	('SM0030','短信内容为空'),
	('SM0031','短信内容存在敏感词'),
	('SM0032','短信内容缺少签名信息'),
	('SM0033','短信过长，超过300字（含签名）'),
	('SM0040','错误的手机号'),
	('SM0041','号码在黑名单中'),
	('SM0042','验证码类短信发送频率过快'),
	('SM0050','请求发送IP不在白名单内');



DROP TABLE IF EXISTS smsCode;

CREATE TABLE smsCode(
phone varchar(12) NOT NULL PRIMARY KEY,
code varchar(4) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS feedback;
CREATE TABLE feedback (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
userid int(11) NOT NULL,
content varchar(200),
answer varchar(200),
feedback_time varchar(20),
answer_time varchar(20),
answered tinyint(1) DEFAULT 0 COMMENT '0:新消息 6:已回复 3:已查看未回复',
isread tinyint(1) DEFAULT 0
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO feedback (userid, answer, answer_time,feedback_time, answered, content)
VALUES
('1','haha','1466418002','1466418001', '0', 'hhaaa'),
('1','haha','1466418004','1466418003', '0', 'hhahahahhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');

DROP TABLE IF EXISTS message;
CREATE TABLE message (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
userid int(11),
author varchar(20),
icon varchar(100),
picture varchar(100),
title varchar(40),
create_time varchar(20),
read_time varchar(20),
url varchar(500),
fetched tinyint(1) DEFAULT 0,
type tinyint(2) DEFAULT 1 COMMENT '1:新品推荐,2:灯光色温',
content varchar(400)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- INSERT INTO message (userid, fetched, content,icon)
-- VALUES
-- ('1','0','watch', '/Uploads/Push/aa2.jpg'),
-- ('2','0','fish','/Uploads/Push/aa2.jpg'),
-- ('1','0','bag','/Uploads/Push/aa2.jpg');

DROP TABLE IF EXISTS fishkind;
CREATE TABLE fishkind (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name varchar(20),
type tinyint(1) DEFAULT 0 COMMENT '0:public 1:private ',
userid int(11)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO fishkind (name)
VALUES
('锦鲤'),
('草金鱼'),
('清道夫'),
('血鹦鹉'),
('地图鱼'),
('招财鱼'),
('金龙鱼'),
('银龙鱼'),
('罗汉鱼'),
('慈鲷鱼'),
('红绿灯'),
('宝莲灯'),
('七彩鱼'),
('神仙鱼'),
('彩裙鱼'),
('斑马鱼'),
('金苔鼠'),
('玛丽鱼'),
('曼龙鱼'),
('异形鱼');

DROP TABLE IF EXISTS fishtank;
CREATE TABLE fishtank (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name varchar(20),
length float,
width float,
heigth float,
fishkinds varchar(100),
opendate varchar(20),
userid int(11) NOT NULL,
thermometer_list varchar(100) DEFAULT NULL,
light_list varchar(100) DEFAULT NULL,
socket int,
tank_status tinyint(1) DEFAULT 0 COMMENT '0:未连接,1:正常运转',
pre_set_temp float DEFAULT 26,
temp_mode_auto tinyint(1) DEFAULT 1
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO fishtank (name, length, width, heigth, fishkinds, opendate, userid,thermometer_list,light_list,socket)
VALUES
('大鱼缸','10.5','3', '1','[1,4,5]','1466418002', 1, '[1,2]','[1,2,3]',1),
('小鱼缸','5','3', '1','[1,4,5]','1466418002',1,'','',1);

DROP TABLE IF EXISTS thermometer;
CREATE TABLE thermometer (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name varchar(20),
max_temp float DEFAULT 30,
min_temp float DEFAULT 20,
cur_temp float DEFAULT NULL,
dis_order tinyint,
tank_id int(11)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO thermometer(name, max_temp, min_temp, cur_temp, tank_id, dis_order)
VALUES
('左温度计','30','20','24', '1', 1),
('右温度计', '33','22', '54', '1',2);


DROP TABLE IF EXISTS socket;
CREATE TABLE socket (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name varchar(20) DEFAULT '六孔插座',
usage_month float,
usage_total float,
status tinyint(1) DEFAULT 0 COMMENT '0:关闭,1:打开',
port_list VARCHAR(100) DEFAULT NULL,
tank_id int(11)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO socket(usage_month,usage_total,port_list, tank_id, status)
VALUES
(650,3210,'[1,2,3,4,5,6]', 1, 1);

DROP TABLE IF EXISTS socket_port;
CREATE TABLE socket_port (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR (20),
status tinyint(1) DEFAULT 0 COMMENT '0:关闭,1:打开',
deviceId int(11) DEFAULT NULL,
deviceType int(2) COMMENT '1:灯，2：温度计,3,六孔插座，4:水位计,5:遥控器',
#icon varchar(40),
dis_order tinyint COMMENT '1-6',
#socket_id int(11),
timer_list VARCHAR (100),
timer_list_name VARCHAR (20)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#INSERT INTO socket_port(name, status, deviceId, deviceType, icon, dis_order, timer_list,socket_id,timer_list_name)
#VALUES
#('灯1',1,1,1,'eaa.png',1, '[1,2]',1, '灯插座'),
#('灯2',1,2,1,'eaa.png',2,'[1,2]',1,'灯插座'),
#('灯3',1,1,1,'eaa.png',3, '[1,2]',1,'温度计插座'),
#('灯4',1,1,1,'eaa.png',4, '[1,2]',1,'灯插座'),
#('',0,1,1,'eaa.png',5, '',1,''),
#('',0,1,1,'eaa.png',6, '',1, '');

INSERT INTO socket_port(name, status, deviceId, deviceType, dis_order, timer_list,timer_list_name)
VALUES
('灯1',1,1,1,1, '[1,2]', '灯插座'),
('灯2',1,2,1,2,'[1,2]','灯插座'),
('灯3',1,1,1,3, '[1,2]','温度计插座'),
('灯4',1,1,1,4, '[1,2]','灯插座'),
('',0,1,1,5, '',''),
('',0,1,1,6, '','');


DROP TABLE IF EXISTS light;
CREATE TABLE light (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
cur_value int,
r_value tinyint,
g_value tinyint,
b_value tinyint,
w_value tinyint,
x_value tinyint,
name VARCHAR(20),
dis_order tinyint,
timer_list VARCHAR (100),
timer_list_name VARCHAR (20),
tank_id int(11)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO light(cur_value,r_value,g_value,b_value,w_value,x_value,name,dis_order,tank_id,timer_list,timer_list_name)
VALUES
(6500,50,60,70,80,90,'灯1',1,1, '[1,2]','灯光定时'),
(6600,30,65,70,80,90,'灯2',2,1,'',''),
(4600,30,65,70,80,50,'灯3',3,1,'','');


DROP TABLE IF EXISTS timer;
CREATE TABLE timer (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR (20),
status tinyint(1) DEFAULT 0 COMMENT '0:关闭,1:打开',
day_list VARCHAR (100) COMMENT '[1,2,3,4,5,6,7]',
start_time varchar(10),
end_time VARCHAR (10)
#只有插座可以定时?灯也可以
#type tinyint COMMENT '1:插座,2:灯光'
#socket_port int(11)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO timer(name,day_list,start_time,end_time,status)
VALUES
('灯1插座','[1,2,3]', '8:00','9:00',1),
('灯2插座','[1,2,3,4]', '8:00','9:00',1);


DROP TABLE IF EXISTS thermometer_his;
CREATE TABLE thermometer_his (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
thermometer_id int(11),
year int(4),
month tinyint,
day tinyint,
hour tinyint,
temperature float,
tank_id int(11)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

