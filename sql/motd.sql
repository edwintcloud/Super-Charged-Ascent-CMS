SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for site_motd
-- ----------------------------
DROP TABLE IF EXISTS `site_motd`;
CREATE TABLE `site_motd` (
  `id` tinyint(4) NOT NULL default 0,
  `poster` text,
  `message` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=myisam DEFAULT CHARSET=utf8;

-- ----------------------------
