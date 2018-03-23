/*
SQLyog Community Edition- MySQL GUI v8.05 
MySQL - 5.1.33-community : Database - donations
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`donations` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `donations`;

/*Table structure for table `log` */

DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `entry` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(32) DEFAULT NULL,
  `txn_id` varchar(32) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` varchar(16) DEFAULT NULL,
  `amount` float unsigned DEFAULT NULL,
  `info` blob,
  PRIMARY KEY (`entry`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `log` */

LOCK TABLES `log` WRITE;

UNLOCK TABLES;

/*Table structure for table `realms` */

DROP TABLE IF EXISTS `realms`;

CREATE TABLE `realms` (
  `entry` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `sqlhost` varchar(32) DEFAULT NULL,
  `sqluser` varchar(32) DEFAULT NULL,
  `sqlpass` varchar(32) DEFAULT NULL,
  `chardb` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`entry`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `realms` */

LOCK TABLES `realms` WRITE;

insert  into `realms`(`entry`,`name`,`sqlhost`,`sqluser`,`sqlpass`,`chardb`) values (1,'Eternal Empire','eternalempire.servegame.org','root','ascent','logon');

UNLOCK TABLES;

/*Table structure for table `rewards` */

DROP TABLE IF EXISTS `rewards`;

CREATE TABLE `rewards` (
  `entry` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `realm` int(10) unsigned DEFAULT NULL,
  `description` text,
  `item1` int(10) unsigned DEFAULT NULL,
  `quantity1` tinyint(3) unsigned DEFAULT NULL,
  `item2` int(10) unsigned DEFAULT NULL,
  `quantity2` tinyint(3) unsigned DEFAULT NULL,
  `item3` int(10) unsigned DEFAULT NULL,
  `quantity3` tinyint(3) unsigned DEFAULT NULL,
  `gold` int(10) unsigned DEFAULT NULL,
  `price` float unsigned DEFAULT NULL,
  PRIMARY KEY (`entry`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `rewards` */

LOCK TABLES `rewards` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
