/*
SQLyog Community Edition- MySQL GUI v8.05 
MySQL - 5.1.33-community : Database - avs
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`avs` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `avs`;

/*Table structure for table `realms` */

DROP TABLE IF EXISTS `realms`;

CREATE TABLE `realms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `sqlhost` varchar(32) DEFAULT NULL,
  `sqluser` varchar(32) DEFAULT NULL,
  `sqlpass` varchar(32) DEFAULT NULL,
  `chardb` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `realms` */

LOCK TABLES `realms` WRITE;

insert  into `realms`(`id`,`name`,`sqlhost`,`sqluser`,`sqlpass`,`chardb`) values (1,'Eternal Empire','localhost','root','ascent','logon');

UNLOCK TABLES;

/*Table structure for table `votemodules` */

DROP TABLE IF EXISTS `votemodules`;

CREATE TABLE `votemodules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `votemodules` */

LOCK TABLES `votemodules` WRITE;

insert  into `votemodules`(`id`,`name`,`image`,`url`) values (1,'XtremeTop100','http://www.xtremeTop100.com/votenew.jpg','http://www.xtremetop100.com/in.php?site=1132282318'),(2,'Top100Arena','http://www.top100arena.com/hit.asp?id=34246&c=WoW&t=2','http://wow.top100arena.com/'),(3,'Gtop100','http://www.gtop100.com/images/votebutton.jpg','http://www.gtop100.com/in.php?site=34524'),(4,'Gamesitestop100','http://www.gamesitestop100.com/images/votebutton.jpg','http://www.gamesitestop100.com/in.php?site=13271'),(5,'TopG ','http://topg.org/topg.gif','http://topg.org/index.php?siteid=342708');

UNLOCK TABLES;

/*Table structure for table `voterewards` */

DROP TABLE IF EXISTS `voterewards`;

CREATE TABLE `voterewards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realm` tinyint(3) unsigned DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `description` text,
  `itemid` int(10) unsigned DEFAULT NULL,
  `points` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `voterewards` */

LOCK TABLES `voterewards` WRITE;

UNLOCK TABLES;

/*Table structure for table `votes` */

DROP TABLE IF EXISTS `votes`;

CREATE TABLE `votes` (
  `ip` varchar(16) DEFAULT NULL,
  `account` varchar(16) DEFAULT NULL,
  `module` tinyint(3) unsigned DEFAULT NULL,
  `time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `votes` */

LOCK TABLES `votes` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
