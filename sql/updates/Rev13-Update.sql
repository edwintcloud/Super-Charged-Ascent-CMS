/*
SQLyog Community Edition- MySQL GUI v8.05 
MySQL - 5.1.33-community : Database - portal
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

USE `portal`;

/*Table structure for table `fusion_panels` */

DROP TABLE IF EXISTS `fusion_panels`;

CREATE TABLE `fusion_panels` (
  `panel_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `panel_name` varchar(100) NOT NULL DEFAULT '',
  `panel_filename` varchar(100) NOT NULL DEFAULT '',
  `panel_content` text NOT NULL,
  `panel_side` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `panel_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `panel_type` varchar(20) NOT NULL DEFAULT '',
  `panel_access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `panel_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `panel_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`panel_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_panels` */

LOCK TABLES `fusion_panels` WRITE;

insert  into `fusion_panels`(`panel_id`,`panel_name`,`panel_filename`,`panel_content`,`panel_side`,`panel_order`,`panel_type`,`panel_access`,`panel_display`,`panel_status`) values (1,'Menu','navigation_panel','',1,1,'file',0,0,1),(2,'Online Users','online_users_panel','',1,4,'file',0,0,1),(3,'Forum Threads','forum_threads_panel','',1,3,'file',0,0,1),(23,'Shoutbox','shoutbox_panel','',1,2,'file',0,0,1),(5,'Welcome Message','welcome_message_panel','',2,1,'file',0,0,1),(6,'Forum Threads List','forum_threads_list_panel','',2,2,'file',0,0,1),(7,'User Info','user_info_panel','',4,1,'file',0,0,1),(8,'Members Poll','member_poll_panel','',4,2,'file',0,0,1),(20,'Realm Status','realms_status_panel','',4,4,'file',0,0,1),(33,'custom banner','banner_panel','',1,5,'file',0,0,0),(27,'Realm Status2','realms_status_panel2','',4,5,'file',0,0,0),(28,'Realm Status3','realms_status_panel3','',4,6,'file',0,0,0),(29,'Realm Status4','realms_status_panel4','',4,7,'file',0,0,0),(30,'Realm Status5','realms_status_panel5','',4,8,'file',0,0,0),(31,'Realm Status6','realms_status_panel6','',4,9,'file',0,0,0),(25,'Donate 2','','openside(\\\"Donate\\\");\r\necho \\\"\r\n<center>\r\n<form action=\\\'/home/Donations\\\' method=\\\'post\\\'>\r\n<input type=\\\'image\\\' src=\\\'\\\".INFUSIONS.\\\"paypal_donate_panel/paypal.gif\\\' border:0px\\\'>\r\n</form>\r\n</center>\\\";\r\ncloseside();',4,3,'php',0,0,1);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
