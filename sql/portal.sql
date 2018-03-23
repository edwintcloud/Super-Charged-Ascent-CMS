/*
SQLyog Community Edition- MySQL GUI v8.05 
MySQL - 5.1.33-community : Database - portal
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`portal` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `portal`;

/*Table structure for table `fusion_admin` */

DROP TABLE IF EXISTS `fusion_admin`;

CREATE TABLE `fusion_admin` (
  `admin_id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `admin_rights` char(2) NOT NULL DEFAULT '',
  `admin_image` varchar(50) NOT NULL DEFAULT '',
  `admin_title` varchar(50) NOT NULL DEFAULT '',
  `admin_link` varchar(100) NOT NULL DEFAULT 'reserved',
  `admin_page` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_admin` */

LOCK TABLES `fusion_admin` WRITE;

insert  into `fusion_admin`(`admin_id`,`admin_rights`,`admin_image`,`admin_title`,`admin_link`,`admin_page`) values (1,'AD','admins.gif','Administrators','administrators.php',2),(2,'AC','article_cats.gif','Article Categories','article_cats.php',1),(3,'A','articles.gif','Articles','articles.php',1),(4,'B','blacklist.gif','Blacklist','blacklist.php',2),(5,'C','','Comments','reserved',2),(6,'CP','c-pages.gif','Custom Pages','custom_pages.php',1),(7,'DB','db_backup.gif','Database Backup','db_backup.php',3),(8,'DC','dl_cats.gif','Download Categories','download_cats.php',1),(9,'D','dl.gif','Downloads','downloads.php',1),(10,'FQ','faq.gif','FAQs','faq.php',1),(11,'F','forums.gif','Forums','forums.php',1),(12,'IM','images.gif','Images','images.php',1),(13,'I','infusions.gif','Infusions','infusions.php',3),(14,'IP','','Infusion Panels','reserved',3),(15,'M','members.gif','Members','members.php',2),(16,'N','news.gif','News','news.php',1),(17,'NC','news_cats.gif','News Categories','news_cats.php',1),(18,'P','panels.gif','Panels','panels.php',3),(19,'PH','photoalbums.gif','Photo Albums','photoalbums.php',1),(20,'PI','phpinfo.gif','PHP Info','phpinfo.php',3),(21,'PO','polls.gif','Polls','polls.php',1),(22,'S','shout.gif','Shoutbox','shoutbox.php',2),(23,'SL','site_links.gif','Site Links','site_links.php',3),(24,'SU','submissions.gif','Submissions','submissions.php',2),(25,'U','upgrade.gif','Upgrade','upgrade.php',3),(26,'UG','user_groups.gif','User Groups','user_groups.php',2),(27,'WC','wl_cats.gif','Web Link Categories','weblink_cats.php',1),(28,'W','wl.gif','Web Links','weblinks.php',1),(29,'S1','settings.gif','Main Settings','settings_main.php',3),(30,'S2','settings_time.gif','Time and Date Settings','settings_time.php',3),(31,'S3','settings_forum.gif','Forum Settings','settings_forum.php',3),(32,'S4','registration.gif','Registration Settings','settings_registration.php',3),(33,'S5','photoalbums.gif','Photo Gallery Settings','settings_photo.php',3),(34,'S6','settings_misc.gif','Miscellaneous Settings','settings_misc.php',3),(35,'S7','settings_pm.gif','Private Message Options','settings_messages.php',3),(38,'IP','infusion_panel.gif','Banner System','../infusions/banner_panel/admin/index.php',4),(39,'IP','infusions.gif','Button Panel','../infusions/button_panel/button_admin.php',4),(42,'IP','infusion_panel.gif','pd MailToAll','../infusions/pd_mailtoall/mailtoall.php',4);

UNLOCK TABLES;

/*Table structure for table `fusion_article_cats` */

DROP TABLE IF EXISTS `fusion_article_cats`;

CREATE TABLE `fusion_article_cats` (
  `article_cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `article_cat_name` varchar(100) NOT NULL DEFAULT '',
  `article_cat_description` varchar(200) NOT NULL DEFAULT '',
  `article_cat_sorting` varchar(50) NOT NULL DEFAULT 'article_subject ASC',
  `article_cat_access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_article_cats` */

LOCK TABLES `fusion_article_cats` WRITE;

insert  into `fusion_article_cats`(`article_cat_id`,`article_cat_name`,`article_cat_description`,`article_cat_sorting`,`article_cat_access`) values (1,'Battlegrounds Events','Battlegrounds Events Will be Posted Here','article_subject ASC',0);

UNLOCK TABLES;

/*Table structure for table `fusion_articles` */

DROP TABLE IF EXISTS `fusion_articles`;

CREATE TABLE `fusion_articles` (
  `article_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `article_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `article_subject` varchar(200) NOT NULL DEFAULT '',
  `article_snippet` text NOT NULL,
  `article_article` text NOT NULL,
  `article_breaks` char(1) NOT NULL DEFAULT '',
  `article_name` smallint(5) unsigned NOT NULL DEFAULT '1',
  `article_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `article_reads` smallint(5) unsigned NOT NULL DEFAULT '0',
  `article_allow_comments` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `article_allow_ratings` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_articles` */

LOCK TABLES `fusion_articles` WRITE;

insert  into `fusion_articles`(`article_id`,`article_cat`,`article_subject`,`article_snippet`,`article_article`,`article_breaks`,`article_name`,`article_datestamp`,`article_reads`,`article_allow_comments`,`article_allow_ratings`) values (1,1,'Warsong','<font size=\\\"4\\\" color=\\\"#ffffff\\\">fhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfh</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf<br /></font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf<br /></font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font><font size=\\\"4\\\" color=\\\"#ffffff\\\">hgfhgfhgf</font>','','n',1,1177416952,3,0,0);

UNLOCK TABLES;

/*Table structure for table `fusion_banner` */

DROP TABLE IF EXISTS `fusion_banner`;

CREATE TABLE `fusion_banner` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `imageurl` varchar(100) NOT NULL DEFAULT '',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `date` datetime DEFAULT NULL,
  `enddate` datetime NOT NULL,
  `status` enum('0','1') NOT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_banner` */

LOCK TABLES `fusion_banner` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_blacklist` */

DROP TABLE IF EXISTS `fusion_blacklist`;

CREATE TABLE `fusion_blacklist` (
  `blacklist_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `blacklist_ip` varchar(20) NOT NULL DEFAULT '',
  `blacklist_email` varchar(100) NOT NULL DEFAULT '',
  `blacklist_reason` text NOT NULL,
  PRIMARY KEY (`blacklist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_blacklist` */

LOCK TABLES `fusion_blacklist` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_buttons` */

DROP TABLE IF EXISTS `fusion_buttons`;

CREATE TABLE `fusion_buttons` (
  `button_id` int(11) NOT NULL AUTO_INCREMENT,
  `button_name` varchar(250) NOT NULL DEFAULT '',
  `button_pic` varchar(200) NOT NULL DEFAULT '',
  `button_link` varchar(200) NOT NULL DEFAULT '',
  `button_count` int(11) NOT NULL DEFAULT '0',
  `button_user` varchar(100) NOT NULL DEFAULT '',
  `button_pass` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`button_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_buttons` */

LOCK TABLES `fusion_buttons` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_captcha` */

DROP TABLE IF EXISTS `fusion_captcha`;

CREATE TABLE `fusion_captcha` (
  `captcha_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `captcha_ip` varchar(20) NOT NULL DEFAULT '',
  `captcha_encode` varchar(32) NOT NULL DEFAULT '',
  `captcha_string` varchar(15) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_captcha` */

LOCK TABLES `fusion_captcha` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_comments` */

DROP TABLE IF EXISTS `fusion_comments`;

CREATE TABLE `fusion_comments` (
  `comment_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `comment_item_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comment_type` char(2) NOT NULL DEFAULT '',
  `comment_name` varchar(50) NOT NULL DEFAULT '',
  `comment_message` text NOT NULL,
  `comment_smileys` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `comment_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_comments` */

LOCK TABLES `fusion_comments` WRITE;

insert  into `fusion_comments`(`comment_id`,`comment_item_id`,`comment_type`,`comment_name`,`comment_message`,`comment_smileys`,`comment_datestamp`,`comment_ip`) values (7,2,'P','fd','fd',1,1176234165,'127.0.0.1'),(9,2,'P','1','jhgjhg',1,1184141971,'91.148.143.68');

UNLOCK TABLES;

/*Table structure for table `fusion_custom_pages` */

DROP TABLE IF EXISTS `fusion_custom_pages`;

CREATE TABLE `fusion_custom_pages` (
  `page_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(200) NOT NULL DEFAULT '',
  `page_access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `page_content` text NOT NULL,
  `page_allow_comments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_allow_ratings` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_custom_pages` */

LOCK TABLES `fusion_custom_pages` WRITE;

insert  into `fusion_custom_pages`(`page_id`,`page_title`,`page_access`,`page_content`,`page_allow_comments`,`page_allow_ratings`) values (34,'Armory Search',0,'<?php\r\n	/*\r\n	 * 2007 (c) -={ MikeP }=-\r\n	 * Simple stats and reg page for Ascent\r\n	 */\r\n	\r\n	require_once(\\\'include/dbLayer.php\\\');\r\n	require_once(\\\'include/readableData.php\\\');\r\n	require_once(\\\'include/settings.php\\\');\r\n	\r\n	/*\r\n	 * Load and parse players\r\n	 */\r\n	$alliancePlrs = array();\r\n	$hordePlrs = array();\r\n	$gmPlrs = array();\r\n\r\n	$s = gSettings::get();\r\n	\r\n	$sDoc = new DOMDocument();\r\n	$sDoc->load($s->statfile);\r\n	\r\n	/* parse GMs */\r\n	$gmList = $sDoc->getElementsByTagName(\\\'gmplr\\\');\r\n	for($i = 0; $i < $gmList->length; $i++){\r\n		$gm = $gmList->item($i);\r\n		\r\n		$ar = array( \\\'name\\\' => \\\'Unknown\\\', \\\'level\\\' => 0, \\\'gender\\\' => 0, \\\'race\\\' => 0, \\\'class\\\' => 0, \\\'map\\\' => -1, \\\'areaid\\\' => -1);\r\n		$n = $gm->firstChild;\r\n		while($n){\r\n			$ar[$n->nodeName] = $n->nodeValue;\r\n			$n = $n->nextSibling;\r\n		}\r\n		$gmPlrs[] = $ar;\r\n	}\r\n	\r\n	/* parse player stats */\r\n	$plrList = $sDoc->getElementsByTagName(\\\'plr\\\');\r\n	for($i = 0; $i < $plrList->length; $i++){\r\n		$plr = $plrList->item($i);\r\n		\r\n		$ar = array( \\\'name\\\' => \\\'Unknown\\\', \\\'level\\\' => 0, \\\'gender\\\' => 0, \\\'race\\\' => 0, \\\'class\\\' => 0, \\\'map\\\' => -1, \\\'areaid\\\' => -1);\r\n		$n = $plr->firstChild;\r\n		while($n){\r\n			$ar[$n->nodeName] = $n->nodeValue;\r\n			$n = $n->nextSibling;\r\n		}\r\n		\r\n		switch($ar[\\\'race\\\']){\r\n			case 1: // human\r\n			case 3: // dwarf\r\n			case 4: // night elf\r\n			case 7: // gnome\r\n			case 11: // draenei\r\n				$alliancePlrs[] = $ar;\r\n				break;\r\n			case 2:\r\n			case 5:\r\n			case 6:\r\n			case 8:\r\n			case 10:\r\n				$hordePlrs[] = $ar;\r\n				break;\r\n			default:\r\n				// do not know where to add players\r\n				break;\r\n		}\r\n		\r\n	}\r\n?>\r\n\r\n\r\n\r\n                <center><h2>Character Search</h2>\r\n                  <form id=\\\"searchPlayer\\\" name=\\\"searchPlayer\\\" method=\\\"get\\\" action=\\\"armory.php\\\">\r\n                    <input name=\\\"n\\\" type=\\\"text\\\" class=\\\"editbox\\\" id=\\\"n\\\" />\r\n					<input name=\\\"search\\\" type=\\\"submit\\\" class=\\\"editbox\\\" id=\\\"search\\\" value=\\\"Search\\\" />\r\n                  </form>\r\n                  </center>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\n                <center><a href=\\\"/home/armory/index.php?act=charsearch\\\">Goto the advanced Armory</a>\r\n<br>\r\n<br>\r\n<br>',0,0),(33,'Guild Search',0,'<?php\r\n	/*\r\n	 * 2007 (c) -={ MikeP }=-\r\n	 * Simple stats and reg page for Ascent\r\n	 */\r\n	\r\n	require_once(\\\'include/dbLayer.php\\\');\r\n	require_once(\\\'include/readableData.php\\\');\r\n	require_once(\\\'include/settings.php\\\');\r\n	\r\n	/*\r\n	 * Load and parse players\r\n	 */\r\n	$alliancePlrs = array();\r\n	$hordePlrs = array();\r\n	$gmPlrs = array();\r\n\r\n	$s = gSettings::get();\r\n	\r\n	$sDoc = new DOMDocument();\r\n	$sDoc->load($s->statfile);\r\n	\r\n	/* parse GMs */\r\n	$gmList = $sDoc->getElementsByTagName(\\\'gmplr\\\');\r\n	for($i = 0; $i < $gmList->length; $i++){\r\n		$gm = $gmList->item($i);\r\n		\r\n		$ar = array( \\\'name\\\' => \\\'Unknown\\\', \\\'level\\\' => 0, \\\'gender\\\' => 0, \\\'race\\\' => 0, \\\'class\\\' => 0, \\\'map\\\' => -1, \\\'areaid\\\' => -1);\r\n		$n = $gm->firstChild;\r\n		while($n){\r\n			$ar[$n->nodeName] = $n->nodeValue;\r\n			$n = $n->nextSibling;\r\n		}\r\n		$gmPlrs[] = $ar;\r\n	}\r\n	\r\n	/* parse player stats */\r\n	$plrList = $sDoc->getElementsByTagName(\\\'plr\\\');\r\n	for($i = 0; $i < $plrList->length; $i++){\r\n		$plr = $plrList->item($i);\r\n		\r\n		$ar = array( \\\'name\\\' => \\\'Unknown\\\', \\\'level\\\' => 0, \\\'gender\\\' => 0, \\\'race\\\' => 0, \\\'class\\\' => 0, \\\'map\\\' => -1, \\\'areaid\\\' => -1);\r\n		$n = $plr->firstChild;\r\n		while($n){\r\n			$ar[$n->nodeName] = $n->nodeValue;\r\n			$n = $n->nextSibling;\r\n		}\r\n		\r\n		switch($ar[\\\'race\\\']){\r\n			case 1: // human\r\n			case 3: // dwarf\r\n			case 4: // night elf\r\n			case 7: // gnome\r\n			case 11: // draenei\r\n				$alliancePlrs[] = $ar;\r\n				break;\r\n			case 2:\r\n			case 5:\r\n			case 6:\r\n			case 8:\r\n			case 10:\r\n				$hordePlrs[] = $ar;\r\n				break;\r\n			default:\r\n				// do not know where to add players\r\n				break;\r\n		}\r\n		\r\n	}\r\n?>\r\n\r\n\r\n\r\n<center><h2>Guild Search</h2>\r\n                  <form id=\\\"searchGuild\\\" name=\\\"searchGuild\\\" method=\\\"get\\\" action=\\\"guild.php\\\">\r\n                    <input name=\\\"n\\\" type=\\\"text\\\" class=\\\"editbox\\\" id=\\\"n\\\" />\r\n					<input name=\\\"search\\\" type=\\\"submit\\\" class=\\\"editbox\\\" id=\\\"search\\\" value=\\\"Search\\\" />\r\n                  </form>\r\n                  </center>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\n<br>\r\n                <center><a href=\\\"/home/armory/index.php?act=guildlist\\\">Goto the advanced Armory</a>\r\n<br>\r\n<br>\r\n<br>',0,0);

UNLOCK TABLES;

/*Table structure for table `fusion_download_cats` */

DROP TABLE IF EXISTS `fusion_download_cats`;

CREATE TABLE `fusion_download_cats` (
  `download_cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `download_cat_name` varchar(100) NOT NULL DEFAULT '',
  `download_cat_description` text NOT NULL,
  `download_cat_sorting` varchar(50) NOT NULL DEFAULT 'download_title ASC',
  `download_cat_access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`download_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_download_cats` */

LOCK TABLES `fusion_download_cats` WRITE;

insert  into `fusion_download_cats`(`download_cat_id`,`download_cat_name`,`download_cat_description`,`download_cat_sorting`,`download_cat_access`) values (1,'Patches','World of Warcraft Patches needed to play on the server','download_datestamp ASC',0),(3,'World of Warcraft Installers','Installers to install the World of Warcraft Game.','download_id ASC',0),(4,'GM/Developer Tools','Tools for Gm&#39;s and Dev&#39;s only.','download_id ASC',102);

UNLOCK TABLES;

/*Table structure for table `fusion_downloads` */

DROP TABLE IF EXISTS `fusion_downloads`;

CREATE TABLE `fusion_downloads` (
  `download_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `download_title` varchar(100) NOT NULL DEFAULT '',
  `download_description` text NOT NULL,
  `download_url` varchar(200) NOT NULL DEFAULT '',
  `download_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `download_license` varchar(50) NOT NULL DEFAULT '',
  `download_os` varchar(50) NOT NULL DEFAULT '',
  `download_version` varchar(20) NOT NULL DEFAULT '',
  `download_filesize` varchar(20) NOT NULL DEFAULT '',
  `download_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `download_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_downloads` */

LOCK TABLES `fusion_downloads` WRITE;

insert  into `fusion_downloads`(`download_id`,`download_title`,`download_description`,`download_url`,`download_cat`,`download_license`,`download_os`,`download_version`,`download_filesize`,`download_datestamp`,`download_count`) values (13,'Multi-Installer for Wow','Installs whichever expansion you choose from the internet for free.','/home/files/InstallWoW.exe',3,'','','','1.1 MB',1241128366,3),(14,'GMH','GMH addon made by Maven.','/home/files/GMH.zip',4,'','','','87 KB',1241128431,14);

UNLOCK TABLES;

/*Table structure for table `fusion_faq_cats` */

DROP TABLE IF EXISTS `fusion_faq_cats`;

CREATE TABLE `fusion_faq_cats` (
  `faq_cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `faq_cat_name` varchar(200) NOT NULL DEFAULT '',
  `faq_cat_description` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`faq_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_faq_cats` */

LOCK TABLES `fusion_faq_cats` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_faqs` */

DROP TABLE IF EXISTS `fusion_faqs`;

CREATE TABLE `fusion_faqs` (
  `faq_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `faq_cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `faq_question` varchar(200) NOT NULL DEFAULT '',
  `faq_answer` text NOT NULL,
  PRIMARY KEY (`faq_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_faqs` */

LOCK TABLES `fusion_faqs` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_flood_control` */

DROP TABLE IF EXISTS `fusion_flood_control`;

CREATE TABLE `fusion_flood_control` (
  `flood_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
  `flood_timestamp` int(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_flood_control` */

LOCK TABLES `fusion_flood_control` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_forum_attachments` */

DROP TABLE IF EXISTS `fusion_forum_attachments`;

CREATE TABLE `fusion_forum_attachments` (
  `attach_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `attach_name` varchar(100) NOT NULL DEFAULT '',
  `attach_ext` varchar(5) NOT NULL DEFAULT '',
  `attach_size` int(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`attach_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_forum_attachments` */

LOCK TABLES `fusion_forum_attachments` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_forums` */

DROP TABLE IF EXISTS `fusion_forums`;

CREATE TABLE `fusion_forums` (
  `forum_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `forum_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `forum_name` varchar(100) NOT NULL DEFAULT '',
  `forum_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `forum_description` text NOT NULL,
  `forum_moderators` text NOT NULL,
  `forum_access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forum_posting` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forum_lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `forum_lastuser` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`forum_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_forums` */

LOCK TABLES `fusion_forums` WRITE;

insert  into `fusion_forums`(`forum_id`,`forum_cat`,`forum_name`,`forum_order`,`forum_description`,`forum_moderators`,`forum_access`,`forum_posting`,`forum_lastpost`,`forum_lastuser`) values (1,0,'Red&#39;s Custom Server News',1,'','',0,0,0,0),(2,1,'Server News',1,'Everything that happen to the server will be post here like updates and etc.','1',0,103,1241135602,18),(16,0,'Development',5,'','',0,0,0,0),(17,0,'Suggestions',4,'','',0,0,0,0),(18,15,'Free Chat',1,'','',101,101,0,0),(19,16,'Script Submissions',2,'','',102,102,0,0),(20,16,'Addon/Program Submissions',3,'','',102,102,0,0),(21,16,'Core Development',4,'','',102,102,0,0),(22,16,'Current Projects',1,'Stay up to date with what the other Dev&#39;s are doing','',102,102,0,0),(23,16,'Database Development',5,'','',102,102,0,0),(24,17,'General Suggestions',1,'','',101,101,0,0),(25,15,'Introductions',2,'Introduce yourself!','',101,101,0,0),(26,0,'Support',6,'','',0,0,0,0),(27,26,'Help me!',1,'Need help with something? Say so..','',101,101,0,0),(28,0,'Staff Applications',2,'','',0,0,0,0),(15,0,'Free Chat',3,'','',0,0,0,0),(29,28,'GM Applications',1,'','',101,101,0,0),(30,28,'Developer Applications',2,'','',101,101,0,0),(31,28,'Special Area Application',3,'Good at scripting, web development, core editing.. etc? Make an app and convince us!','',101,101,0,0);

UNLOCK TABLES;

/*Table structure for table `fusion_infusions` */

DROP TABLE IF EXISTS `fusion_infusions`;

CREATE TABLE `fusion_infusions` (
  `inf_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `inf_title` varchar(100) NOT NULL DEFAULT '',
  `inf_folder` varchar(100) NOT NULL DEFAULT '',
  `inf_version` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`inf_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_infusions` */

LOCK TABLES `fusion_infusions` WRITE;

insert  into `fusion_infusions`(`inf_id`,`inf_title`,`inf_folder`,`inf_version`) values (3,'Avatar Gallery','avatar_gallery','1.20'),(4,'Banner System','banner_panel','2.0.4'),(5,'Button Panel','button_panel','1.00'),(8,'pd MailToAll','pd_mailtoall','1.1');

UNLOCK TABLES;

/*Table structure for table `fusion_messages` */

DROP TABLE IF EXISTS `fusion_messages`;

CREATE TABLE `fusion_messages` (
  `message_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `message_to` smallint(5) unsigned NOT NULL DEFAULT '0',
  `message_from` smallint(5) unsigned NOT NULL DEFAULT '0',
  `message_subject` varchar(100) NOT NULL DEFAULT '',
  `message_message` text NOT NULL,
  `message_smileys` char(1) NOT NULL DEFAULT '',
  `message_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `message_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `message_folder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_messages` */

LOCK TABLES `fusion_messages` WRITE;

insert  into `fusion_messages`(`message_id`,`message_to`,`message_from`,`message_subject`,`message_message`,`message_smileys`,`message_read`,`message_datestamp`,`message_folder`) values (8,1,1,'Hello all','Sf','y',1,1174818520,1);

UNLOCK TABLES;

/*Table structure for table `fusion_messages_options` */

DROP TABLE IF EXISTS `fusion_messages_options`;

CREATE TABLE `fusion_messages_options` (
  `user_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pm_email_notify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pm_save_sent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pm_inbox` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pm_savebox` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pm_sentbox` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_messages_options` */

LOCK TABLES `fusion_messages_options` WRITE;

insert  into `fusion_messages_options`(`user_id`,`pm_email_notify`,`pm_save_sent`,`pm_inbox`,`pm_savebox`,`pm_sentbox`) values (0,1,1,20,20,20);

UNLOCK TABLES;

/*Table structure for table `fusion_new_users` */

DROP TABLE IF EXISTS `fusion_new_users`;

CREATE TABLE `fusion_new_users` (
  `user_code` varchar(32) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `user_info` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_new_users` */

LOCK TABLES `fusion_new_users` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_news` */

DROP TABLE IF EXISTS `fusion_news`;

CREATE TABLE `fusion_news` (
  `news_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `news_subject` varchar(200) NOT NULL DEFAULT '',
  `news_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `news_news` text NOT NULL,
  `news_extended` text NOT NULL,
  `news_breaks` char(1) NOT NULL DEFAULT '',
  `news_name` smallint(5) unsigned NOT NULL DEFAULT '1',
  `news_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `news_start` int(10) unsigned NOT NULL DEFAULT '0',
  `news_end` int(10) unsigned NOT NULL DEFAULT '0',
  `news_visibility` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `news_reads` smallint(5) unsigned NOT NULL DEFAULT '0',
  `news_sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `news_allow_comments` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `news_allow_ratings` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_news` */

LOCK TABLES `fusion_news` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_news_cats` */

DROP TABLE IF EXISTS `fusion_news_cats`;

CREATE TABLE `fusion_news_cats` (
  `news_cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `news_cat_name` varchar(100) NOT NULL DEFAULT '',
  `news_cat_image` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`news_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_news_cats` */

LOCK TABLES `fusion_news_cats` WRITE;

insert  into `fusion_news_cats`(`news_cat_id`,`news_cat_name`,`news_cat_image`) values (1,'Bugs','bugs.gif'),(2,'Downloads','downloads.gif'),(3,'Games','games.gif'),(4,'Graphics','graphics.gif'),(5,'Hardware','hardware.gif'),(6,'Journal','journal.gif'),(7,'Members','members.gif'),(8,'Mods','mods.gif'),(9,'Movies','movies.gif'),(10,'Network','network.gif'),(11,'News','news.gif'),(12,'PHP-Fusion','php-fusion.gif'),(13,'Security','security.gif'),(14,'Software','software.gif'),(15,'Themes','themes.gif'),(16,'Windows','windows.gif'),(17,'BC News','WOW_TBC_Logo.png'),(18,'Server News','freemigration.jpg'),(19,'Events','arena.jpg');

UNLOCK TABLES;

/*Table structure for table `fusion_online` */

DROP TABLE IF EXISTS `fusion_online`;

CREATE TABLE `fusion_online` (
  `online_user` varchar(50) NOT NULL DEFAULT '',
  `online_ip` varchar(20) NOT NULL DEFAULT '',
  `online_lastactive` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_online` */

LOCK TABLES `fusion_online` WRITE;

insert  into `fusion_online`(`online_user`,`online_ip`,`online_lastactive`) values ('34','127.0.0.1',1242583034);

UNLOCK TABLES;

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_panels` */

LOCK TABLES `fusion_panels` WRITE;

insert  into `fusion_panels`(`panel_id`,`panel_name`,`panel_filename`,`panel_content`,`panel_side`,`panel_order`,`panel_type`,`panel_access`,`panel_display`,`panel_status`) values (1,'Menu','navigation_panel','',1,1,'file',0,0,1),(2,'Online Users','online_users_panel','',1,4,'file',0,0,1),(3,'Forum Threads','forum_threads_panel','',1,3,'file',0,0,1),(23,'Shoutbox','shoutbox_panel','',1,2,'file',0,0,1),(5,'Welcome Message','welcome_message_panel','',2,1,'file',0,0,1),(6,'Forum Threads List','forum_threads_list_panel','',2,2,'file',0,0,1),(7,'User Info','user_info_panel','',4,1,'file',0,0,1),(8,'Members Poll','member_poll_panel','',4,2,'file',0,0,1),(20,'Realm Status','realms_status_panel','',4,5,'file',0,0,1),(21,'DONATE','paypal_donate_panel','',4,4,'file',0,0,0),(24,'Realm Status2','','$relmop .= \\\'<a href=\\\"http://redscustom.servegame.org/home\\\">\\\';\r\nopenside(\\\"Realm Status\\\");\r\necho \\\"For Actual Realm Statistics (slow) click: \\\";\r\necho \\\"$relmop\\\";\r\necho \\\"<br /><br />\\\";\r\necho \\\" <u>Realmlist:</u><br />\\\";\r\necho \\\"set realmlist redscustom.servegame.org \\\";\r\necho \\\"<br /><br /> \\\";\r\necho \\\"<u>Accepted Patch:</u><br />\\\";\r\necho \\\"3.0.9 WOTLK\\\";\r\necho \\\"<br /><br />\\\";\r\necho \\\"<u>Rates:</u><br />\\\";\r\necho \\\"x100 XP<br />\\\";\r\necho \\\"MAX LVL 200<br />\\\";\r\necho \\\"DK Start LVL 1\\\";\r\ncloseside();',4,6,'php',0,0,0),(25,'Donate 2','','openside(\\\"Donate\\\");\r\necho \\\"\r\n<center>\r\n<form action=\\\'/home/Donations\\\' method=\\\'post\\\'>\r\n<input type=\\\'image\\\' src=\\\'\\\".INFUSIONS.\\\"paypal_donate_panel/paypal.gif\\\' border:0px\\\'>\r\n</form>\r\n</center>\\\";\r\ncloseside();',4,3,'php',0,0,1);

UNLOCK TABLES;

/*Table structure for table `fusion_photo_albums` */

DROP TABLE IF EXISTS `fusion_photo_albums`;

CREATE TABLE `fusion_photo_albums` (
  `album_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `album_title` varchar(100) NOT NULL DEFAULT '',
  `album_description` text NOT NULL,
  `album_thumb` varchar(100) NOT NULL DEFAULT '',
  `album_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `album_access` smallint(5) unsigned NOT NULL DEFAULT '0',
  `album_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `album_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_photo_albums` */

LOCK TABLES `fusion_photo_albums` WRITE;

insert  into `fusion_photo_albums`(`album_id`,`album_title`,`album_description`,`album_thumb`,`album_user`,`album_access`,`album_order`,`album_datestamp`) values (1,'Game Screenshot','Take a screenshot in the game and upload it here','outland1_t1.jpg',1,0,1,1172682450);

UNLOCK TABLES;

/*Table structure for table `fusion_photos` */

DROP TABLE IF EXISTS `fusion_photos`;

CREATE TABLE `fusion_photos` (
  `photo_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `photo_title` varchar(100) NOT NULL DEFAULT '',
  `photo_description` text NOT NULL,
  `photo_filename` varchar(100) NOT NULL DEFAULT '',
  `photo_thumb1` varchar(100) NOT NULL DEFAULT '',
  `photo_thumb2` varchar(100) NOT NULL DEFAULT '',
  `photo_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `photo_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `photo_views` smallint(5) unsigned NOT NULL DEFAULT '0',
  `photo_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `photo_allow_comments` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `photo_allow_ratings` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_photos` */

LOCK TABLES `fusion_photos` WRITE;

insert  into `fusion_photos`(`photo_id`,`album_id`,`photo_title`,`photo_description`,`photo_filename`,`photo_thumb1`,`photo_thumb2`,`photo_datestamp`,`photo_user`,`photo_views`,`photo_order`,`photo_allow_comments`,`photo_allow_ratings`) values (2,1,'Outland','Outland','outland1.jpg','outland1_t1.jpg','outland1_t2.jpg',1174913587,1,41,1,1,1),(4,1,'Me in SW','Me in Stormwind','wowscrnshot_021407_182102.jpg','wowscrnshot_021407_182102_t1.jpg','wowscrnshot_021407_182102_t2.jpg',1175187928,7,30,2,1,1);

UNLOCK TABLES;

/*Table structure for table `fusion_poll_votes` */

DROP TABLE IF EXISTS `fusion_poll_votes`;

CREATE TABLE `fusion_poll_votes` (
  `vote_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `vote_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `vote_opt` smallint(2) unsigned NOT NULL DEFAULT '0',
  `poll_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_poll_votes` */

LOCK TABLES `fusion_poll_votes` WRITE;

insert  into `fusion_poll_votes`(`vote_id`,`vote_user`,`vote_opt`,`poll_id`) values (1,1,0,1);

UNLOCK TABLES;

/*Table structure for table `fusion_polls` */

DROP TABLE IF EXISTS `fusion_polls`;

CREATE TABLE `fusion_polls` (
  `poll_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `poll_title` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_0` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_1` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_2` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_3` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_4` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_5` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_6` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_7` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_8` varchar(200) NOT NULL DEFAULT '',
  `poll_opt_9` varchar(200) NOT NULL DEFAULT '',
  `poll_started` int(10) unsigned NOT NULL DEFAULT '0',
  `poll_ended` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`poll_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_polls` */

LOCK TABLES `fusion_polls` WRITE;

insert  into `fusion_polls`(`poll_id`,`poll_title`,`poll_opt_0`,`poll_opt_1`,`poll_opt_2`,`poll_opt_3`,`poll_opt_4`,`poll_opt_5`,`poll_opt_6`,`poll_opt_7`,`poll_opt_8`,`poll_opt_9`,`poll_started`,`poll_ended`) values (2,'How do you like the updated site and server?','Freakin Awesome!','Not Bad..','WTF...','','','','','','','',1241134628,0);

UNLOCK TABLES;

/*Table structure for table `fusion_posts` */

DROP TABLE IF EXISTS `fusion_posts`;

CREATE TABLE `fusion_posts` (
  `forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thread_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `post_subject` varchar(100) NOT NULL DEFAULT '',
  `post_message` text NOT NULL,
  `post_showsig` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `post_smileys` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `post_author` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `post_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
  `post_edituser` smallint(5) unsigned NOT NULL DEFAULT '0',
  `post_edittime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `thread_id` (`thread_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_posts` */

LOCK TABLES `fusion_posts` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_ratings` */

DROP TABLE IF EXISTS `fusion_ratings`;

CREATE TABLE `fusion_ratings` (
  `rating_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `rating_item_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rating_type` char(1) NOT NULL DEFAULT '',
  `rating_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rating_vote` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rating_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`rating_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_ratings` */

LOCK TABLES `fusion_ratings` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_settings` */

DROP TABLE IF EXISTS `fusion_settings`;

CREATE TABLE `fusion_settings` (
  `sitename` varchar(200) NOT NULL DEFAULT '',
  `siteurl` varchar(200) NOT NULL DEFAULT '',
  `sitebanner` varchar(200) NOT NULL DEFAULT '',
  `siteemail` varchar(100) NOT NULL DEFAULT '',
  `siteusername` varchar(30) NOT NULL DEFAULT '',
  `siteintro` text NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `footer` text NOT NULL,
  `opening_page` varchar(100) NOT NULL DEFAULT '',
  `news_style` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locale` varchar(20) NOT NULL DEFAULT 'English',
  `theme` varchar(100) NOT NULL DEFAULT '',
  `shortdate` varchar(50) NOT NULL DEFAULT '',
  `longdate` varchar(50) NOT NULL DEFAULT '',
  `forumdate` varchar(50) NOT NULL DEFAULT '',
  `subheaderdate` varchar(50) NOT NULL DEFAULT '',
  `timeoffset` char(3) NOT NULL DEFAULT '0',
  `numofthreads` smallint(2) unsigned NOT NULL DEFAULT '5',
  `attachments` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attachmax` int(12) unsigned NOT NULL DEFAULT '150000',
  `attachtypes` varchar(150) NOT NULL DEFAULT '.gif,.jpg,.png,.zip,.rar,.tar',
  `thread_notify` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_registration` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `email_verification` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `admin_activation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `display_validation` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `validation_method` varchar(5) NOT NULL DEFAULT 'image',
  `thumb_w` smallint(3) unsigned NOT NULL DEFAULT '100',
  `thumb_h` smallint(3) unsigned NOT NULL DEFAULT '100',
  `photo_w` smallint(4) unsigned NOT NULL DEFAULT '400',
  `photo_h` smallint(4) unsigned NOT NULL DEFAULT '300',
  `photo_max_w` smallint(4) unsigned NOT NULL DEFAULT '1800',
  `photo_max_h` smallint(4) unsigned NOT NULL DEFAULT '1600',
  `photo_max_b` int(10) unsigned NOT NULL DEFAULT '150000',
  `thumb_compression` char(3) NOT NULL DEFAULT 'gd2',
  `thumbs_per_row` smallint(2) unsigned NOT NULL DEFAULT '4',
  `thumbs_per_page` smallint(2) unsigned NOT NULL DEFAULT '12',
  `tinymce_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `smtp_host` varchar(200) NOT NULL DEFAULT '',
  `smtp_username` varchar(100) NOT NULL DEFAULT '',
  `smtp_password` varchar(100) NOT NULL DEFAULT '',
  `bad_words_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bad_words` text NOT NULL,
  `bad_word_replace` varchar(20) NOT NULL DEFAULT '[censored]',
  `guestposts` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `numofshouts` tinyint(2) unsigned NOT NULL DEFAULT '10',
  `flood_interval` tinyint(2) unsigned NOT NULL DEFAULT '15',
  `counter` bigint(20) unsigned NOT NULL DEFAULT '0',
  `version` varchar(10) NOT NULL DEFAULT '6.00.400',
  `maintenance` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `maintenance_message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_settings` */

LOCK TABLES `fusion_settings` WRITE;

insert  into `fusion_settings`(`sitename`,`siteurl`,`sitebanner`,`siteemail`,`siteusername`,`siteintro`,`description`,`keywords`,`footer`,`opening_page`,`news_style`,`locale`,`theme`,`shortdate`,`longdate`,`forumdate`,`subheaderdate`,`timeoffset`,`numofthreads`,`attachments`,`attachmax`,`attachtypes`,`thread_notify`,`enable_registration`,`email_verification`,`admin_activation`,`display_validation`,`validation_method`,`thumb_w`,`thumb_h`,`photo_w`,`photo_h`,`photo_max_w`,`photo_max_h`,`photo_max_b`,`thumb_compression`,`thumbs_per_row`,`thumbs_per_page`,`tinymce_enabled`,`smtp_host`,`smtp_username`,`smtp_password`,`bad_words_enabled`,`bad_words`,`bad_word_replace`,`guestposts`,`numofshouts`,`flood_interval`,`counter`,`version`,`maintenance`,`maintenance_message`) values ('site name','/','rotate.php','siteemail@email.com','yourname','<center>\r\nWelcome to our server!   We try to give each player a unique experience of the World of Warcraft game. We are currently looking to recruit help for the development stages of this server and wish to say that anyone is welcome. Once the server is done with the setup, custom leveling roads and custom quests, we will open the server to everyone who just wants to have fun. If anyone has any ideas please do not hesitate to write a response in the forums. All ideas are apreciated. Thank you and enjoy your stay.\r\n</center>','','','<center>Copyright © 2009 servername. All Rights Reserved.\r\n</center>','news.php',0,'English','WoW','%d/%m/%Y %H:%M','%B %d %Y %H:%M:%S','%d-%m-%Y %H:%M','%B %d %Y %H:%M:%S','0',5,0,150000,'.gif,.jpg,.png,.zip,.rar,.tar',1,1,0,0,1,'text',150,120,400,300,1800,1600,400000,'gd2',4,12,1,'','','',0,'viewpage.php?page_id=7','****',0,5,6,123,'6.01.11',0,'Weekly Maintance of Server and Site it will take about 5-10 min \r\nThank you for your patiance\r\n\r\nBest Regards <b>Server Owner</b>');

UNLOCK TABLES;

/*Table structure for table `fusion_shoutbox` */

DROP TABLE IF EXISTS `fusion_shoutbox`;

CREATE TABLE `fusion_shoutbox` (
  `shout_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `shout_name` varchar(50) NOT NULL DEFAULT '',
  `shout_message` varchar(200) NOT NULL DEFAULT '',
  `shout_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `shout_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`shout_id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_shoutbox` */

LOCK TABLES `fusion_shoutbox` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_site_links` */

DROP TABLE IF EXISTS `fusion_site_links`;

CREATE TABLE `fusion_site_links` (
  `link_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(100) NOT NULL DEFAULT '',
  `link_url` varchar(200) NOT NULL DEFAULT '',
  `link_visibility` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `link_position` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `link_window` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link_order` smallint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_site_links` */

LOCK TABLES `fusion_site_links` WRITE;

insert  into `fusion_site_links`(`link_id`,`link_name`,`link_url`,`link_visibility`,`link_position`,`link_window`,`link_order`) values (1,'Home','index.php',0,1,0,4),(61,'Account Tools','---',101,1,0,20),(3,'Downloads','downloads.php',0,1,0,6),(4,'FAQ','faq.php',0,1,0,7),(5,'Forum','forum/',0,1,0,5),(81,'Server Status','./stats',0,1,0,30),(10,'Search','search.php',0,1,0,10),(11,'---','---',0,1,0,1),(60,'---','---',0,1,0,14),(62,'Extras','---',0,1,0,28),(63,'Register an Account','---',0,1,0,15),(64,'Website/Forum Account','./register.php',0,1,0,18),(20,'Game Account','game_acc.php',0,1,0,17),(69,'Main','---',0,1,0,2),(70,'---','---',0,1,0,3),(55,'Avatar Gallery','infusions/avatar_gallery/avatar_gallery.php',101,1,0,26),(65,'Unstuck Tool','unstuck.php',101,1,0,22),(66,'Teleporter','./AccountManager/teleporter.php',101,1,0,23),(67,'---','---',0,1,0,16),(71,'---','---',0,1,0,19),(72,'---','---',101,1,0,21),(73,'---','---',0,1,0,29),(74,'---','---',101,1,0,27),(75,'---','---',0,1,0,32),(77,'Vote','./AVS',0,2,0,12),(78,'Donate','./Donations',0,2,0,13),(79,'Password Changer','changegamepass.php',101,1,0,24),(80,'World Map','worldmap.php',101,1,0,31),(82,'WEBAM','./WEBAM',101,1,0,25);

UNLOCK TABLES;

/*Table structure for table `fusion_submissions` */

DROP TABLE IF EXISTS `fusion_submissions`;

CREATE TABLE `fusion_submissions` (
  `submit_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `submit_type` char(1) NOT NULL DEFAULT '',
  `submit_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `submit_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `submit_criteria` text NOT NULL,
  PRIMARY KEY (`submit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_submissions` */

LOCK TABLES `fusion_submissions` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_thread_notify` */

DROP TABLE IF EXISTS `fusion_thread_notify`;

CREATE TABLE `fusion_thread_notify` (
  `thread_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `notify_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `notify_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `notify_status` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_thread_notify` */

LOCK TABLES `fusion_thread_notify` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_threads` */

DROP TABLE IF EXISTS `fusion_threads`;

CREATE TABLE `fusion_threads` (
  `forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thread_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `thread_subject` varchar(100) NOT NULL DEFAULT '',
  `thread_author` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thread_views` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thread_lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `thread_lastuser` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thread_sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `thread_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_threads` */

LOCK TABLES `fusion_threads` WRITE;

UNLOCK TABLES;

/*Table structure for table `fusion_user_groups` */

DROP TABLE IF EXISTS `fusion_user_groups`;

CREATE TABLE `fusion_user_groups` (
  `group_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL DEFAULT '',
  `group_description` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_user_groups` */

LOCK TABLES `fusion_user_groups` WRITE;

insert  into `fusion_user_groups`(`group_id`,`group_name`,`group_description`) values (1,'VIP',''),(2,'GM',''),(3,'Moderators','People that manage diffrent cont of the site');

UNLOCK TABLES;

/*Table structure for table `fusion_users` */

DROP TABLE IF EXISTS `fusion_users`;

CREATE TABLE `fusion_users` (
  `user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL DEFAULT '',
  `user_password` varchar(32) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_hide_email` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `user_location` varchar(50) NOT NULL DEFAULT '',
  `user_birthdate` date NOT NULL DEFAULT '0000-00-00',
  `user_aim` varchar(16) NOT NULL DEFAULT '',
  `user_icq` varchar(15) NOT NULL DEFAULT '',
  `user_msn` varchar(100) NOT NULL DEFAULT '',
  `user_yahoo` varchar(100) NOT NULL DEFAULT '',
  `user_web` varchar(200) NOT NULL DEFAULT '',
  `user_theme` varchar(100) NOT NULL DEFAULT 'Default',
  `user_offset` char(3) NOT NULL DEFAULT '0',
  `user_avatar` varchar(100) NOT NULL DEFAULT '',
  `user_sig` text NOT NULL,
  `user_posts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_joined` int(10) unsigned NOT NULL DEFAULT '0',
  `user_lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(20) NOT NULL DEFAULT '0.0.0.0',
  `user_rights` text NOT NULL,
  `user_groups` text NOT NULL,
  `user_level` tinyint(3) unsigned NOT NULL DEFAULT '101',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_users` */

LOCK TABLES `fusion_users` WRITE;

insert  into `fusion_users`(`user_id`,`user_name`,`user_password`,`user_email`,`user_hide_email`,`user_location`,`user_birthdate`,`user_aim`,`user_icq`,`user_msn`,`user_yahoo`,`user_web`,`user_theme`,`user_offset`,`user_avatar`,`user_sig`,`user_posts`,`user_joined`,`user_lastvisit`,`user_ip`,`user_rights`,`user_groups`,`user_level`,`user_status`) values (34,'admin','0c909a141f1f2c0a1cb602b0b2d7d050','admin@server.com',0,'','0000-00-00','','','','','','Default','0','avatar[34].gif','',0,1242501715,1242583034,'127.0.0.1','A.AC.AD.B.C.CP.DB.DC.D.FQ.F.IM.I.IP.M.N.NC.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SU.UG.U.W.WC','.1.2.3',103,0);

UNLOCK TABLES;

/*Table structure for table `fusion_vcode` */

DROP TABLE IF EXISTS `fusion_vcode`;

CREATE TABLE `fusion_vcode` (
  `vcode_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `vcode_1` varchar(5) NOT NULL DEFAULT '',
  `vcode_2` varchar(32) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `fusion_vcode` */

LOCK TABLES `fusion_vcode` WRITE;

insert  into `fusion_vcode`(`vcode_datestamp`,`vcode_1`,`vcode_2`) values (1241034849,'bfe47','cbf9cc27d0a2926b563facf54effc4bc'),(1241034849,'ce1c2','7e6b262bcb1da9351ad9c0b37c3d8187'),(1241034869,'1c22d','a300b95de316c256fb32f44c8fd3ba92'),(1241130366,'63ff4','a23fbd5312194dc586499ec927b3acfc'),(1241130367,'be4e7','0409c0a1eb8c5c54cb58d4707238008c'),(1241130436,'f2217','4228e52b4b240dd369cec5a6f22c7db6'),(1241130886,'4758f','94c828730c5b663e1e5ea2dc9ab3e160'),(1241379892,'10ca0','5b2ef81e25a65916e43d541d9ce38ffb'),(1241379919,'6ab63','e5d41f4b1dba5d4aaea8610caf23c37a'),(1241395971,'089f9','0d3c72364f61616690d2a1fa40203d90'),(1241395979,'12031','1f539471127c904e22309f6fa599e98f'),(1241396081,'9aa64','261863027bd5ed4d7a930ae2d8958e0a'),(1241396271,'1818a','96c7236b3343b7acb7752bdc1ec6eecb'),(1241412279,'0f1e8','960f85747087ffd45b04394f87fcd9af'),(1241412393,'03f0e','a75f28ca9395cd281fd006baf8eda539'),(1241412301,'9e943','3639d26e2745a9e146c1cb8296ff2b68'),(1241456319,'e337b','e44b3d2adb83eda5c2401b63e0f23b0d'),(1241464303,'1c206','8679f59154a23ca60f34c9ea6e432756'),(1241478677,'0fe34','a173d7db8f704f32b0595ffb948a21f5'),(1241479553,'76fc0','0001855fa56fd060f8dbd39009d29a0f'),(1241479563,'536fb','5c99d106377b0b94578fb35d6586a610'),(1241513222,'e03c1','153d9aecfdad16f0f2ae719c65417aa4'),(1241634037,'61dbc','34343f80ae723f64b114b152d2d1141d'),(1241713789,'fe647','b040252654df19193e50d70c544e93a8'),(1241719945,'5ace6','e7ce70c650018cac745611c795eed5d0'),(1241724662,'cb118','c6f4eb219ca6a3fb951bead46326d215'),(1241818872,'c96b0','84f771efc4a5f286ea9d6bb291e36408'),(1242502066,'ac2e3','d1fb0af739bbd407530f278484e73156'),(1242502257,'eb3f9','f14ff79aa83c72e6592ff4425e967a98'),(1242503918,'254e7','0df3e375eacd6b8b61e4fd1d97ade0f0');

UNLOCK TABLES;

/*Table structure for table `fusion_weblink_cats` */

DROP TABLE IF EXISTS `fusion_weblink_cats`;

CREATE TABLE `fusion_weblink_cats` (
  `weblink_cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `weblink_cat_name` varchar(100) NOT NULL DEFAULT '',
  `weblink_cat_description` text NOT NULL,
  `weblink_cat_sorting` varchar(50) NOT NULL DEFAULT 'weblink_name ASC',
  `weblink_cat_access` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`weblink_cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_weblink_cats` */

LOCK TABLES `fusion_weblink_cats` WRITE;

insert  into `fusion_weblink_cats`(`weblink_cat_id`,`weblink_cat_name`,`weblink_cat_description`,`weblink_cat_sorting`,`weblink_cat_access`) values (1,'Banners','Friends','weblink_id ASC',0);

UNLOCK TABLES;

/*Table structure for table `fusion_weblinks` */

DROP TABLE IF EXISTS `fusion_weblinks`;

CREATE TABLE `fusion_weblinks` (
  `weblink_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `weblink_name` varchar(100) NOT NULL DEFAULT '',
  `weblink_description` text NOT NULL,
  `weblink_url` varchar(200) NOT NULL DEFAULT '',
  `weblink_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
  `weblink_datestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `weblink_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`weblink_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `fusion_weblinks` */

LOCK TABLES `fusion_weblinks` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
