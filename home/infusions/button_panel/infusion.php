<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright &#352; 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION") || !checkrights("I")) { header("Location:../../index.php"); exit; }

if (file_exists(INFUSIONS."button_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."button_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."button_panel/locale/English.php";
}

// Infusion Information
$inf_title = "Button Panel";
$inf_description = "Button panel with click-counting and login system for clients.";
$inf_version = "1.00";
$inf_developer = "cmike";
$inf_email = "mike@wnet.net.pl";
$inf_weburl = "http://freeware.wnet.net.pl";

// Infusion Paths
$inf_folder = "button_panel";
$inf_admin_image = "infusions.gif";
$inf_admin_panel = "button_admin.php";
$inf_link_name = "";
$inf_link_url = "";
$inf_link_visibility = "";

$inf_newtables = 1;
$inf_insertdbrows = 0;
$inf_altertables = 0;
$inf_deldbrows = 0;

// Database Table information : Create database tables.
$inf_newtable_[1] = "buttons (
  `button_id` int(11) NOT NULL auto_increment,
  `button_name` varchar(250) NOT NULL default '',
  `button_pic` varchar(200) NOT NULL default '',
  `button_link` varchar(200) NOT NULL default '',
  `button_count` int(11) NOT NULL default '0',
  `button_user` varchar(100) NOT NULL default '',
  `button_pass` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`button_id`)
) TYPE=MyISAM";
	
// Database Table Drop Command : Drop tables if infusion is uninstalled.
$inf_droptable_[1] = "buttons";
?>