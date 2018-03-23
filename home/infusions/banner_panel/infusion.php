<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION") || !checkrights("I")) { header("Location: ../../index.php"); exit; }

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."banner_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."banner_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."banner_panel/locale/English.php";
}

// Infusion general information
$inf_title = $locale['BRS000'];
$inf_description = $locale['BRS001'];
$inf_version = "2.0.4";
$inf_developer = "AusiMods";
$inf_email = "support@ausimods.net";
$inf_weburl = "http://www.ausimods.net";

$inf_folder = "banner_panel"; // The folder in which the infusion resides.
$inf_admin_image = ""; // Leave blank to use the default image.
$inf_admin_panel = "admin/index.php"; // The admin panel filename if required.

$inf_link_name = $locale['BRS002']; // if not required replace $locale['xxx102']; with "";
$inf_link_url = "index.php"; // The filename you wish to link to.
$inf_link_visibility = "101"; // 0 - Guest / 101 - Member / 102 - Admin / 103 - Super Admin.

$inf_newtables = 1; // Number of new db tables to create or drop.
$inf_insertdbrows = 0; // Numbers rows added into created db tables.
$inf_altertables = 2; // Number of db tables to alter (upgrade).
$inf_deldbrows = 0; // Number of db tables to delete data from.

// Delete any items not required here.
$inf_newtable_[1] = "banner(
bid int( 11 ) NOT NULL AUTO_INCREMENT ,
cid int( 11 ) NOT NULL default '0',
imptotal int( 11 ) NOT NULL default '0',
impmade int( 11 ) NOT NULL default '0',
clicks int( 11 ) NOT NULL default '0',
imageurl varchar( 100 ) NOT NULL default '',
clickurl varchar( 200 ) NOT NULL default '',
date datetime default NULL ,
enddate DATETIME NOT NULL ,
status ENUM( '0', '1' ) NOT NULL,
PRIMARY KEY ( bid )
) TYPE=MyISAM;";

//early update
//dbquery("DROP TABLE IF EXISTS ".$db_prefix."bannerfinish;");



$inf_droptable_[1] = "banner";

//early update
//$inf_altertable_[1] = "banner ADD enddate DATETIME NOT NULL;";
//$inf_altertable_[2] = "banner ADD status ENUM( '0', '1' ) NOT NULL ;";

//$inf_deldbrow_[1] = "other_table";

?>
