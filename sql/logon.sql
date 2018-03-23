/*
MySQL Data Transfer
Source Host: localhost
Source Database: logon
Target Host: localhost
Target Database: logon
Date: 5/12/2009 3:14:18 PM
*/

SET FOREIGN_KEY_CHECKS=0;
ALTER TABLE `accounts` ADD `reward_points` int(255) DEFAULT '0';

