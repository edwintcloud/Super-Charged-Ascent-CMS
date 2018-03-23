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
if (file_exists(INFUSIONS."slideshows_random_photo_panel/locale/".LANGUAGE.".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."slideshows_random_photo_panel/locale/".LANGUAGE.".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."slideshows_random_photo_panel/locale/English.php";
}

// Infusion general information
$inf_title = "Slideshows Random Photo Panel";
$inf_description = "random photo panel which uses different configurable DHTML slideshows to display random images from PHP-Fusion photogallery";
$inf_version = "1.10";
$inf_developer = "Wooya";
$inf_email = "wooya@2loud.net.pl";
$inf_weburl = "http://www.2loud.net.pl";

$inf_folder = "slideshows_random_photo_panel"; // The folder in which the infusion resides.
$inf_admin_image = ""; // Leave blank to use the default image.
$inf_admin_panel = "slideshows_admin.php"; // The admin panel filename if required.

$inf_link_name = ""; // if not required replace $locale['xxx103']; with "";
$inf_link_url = ""; // The filename you wish to link to.
$inf_link_visibility = "0"; // 0 - Guest / 101 - Member / 102 - Admin / 103 - Super Admin.

$inf_newtables = 1; // Number of new db tables to create or drop.
$inf_altertables = 0; // Number of db tables to alter (upgrade).
$inf_insertdbrows = 1; // Number of db tables to delete data from.
$inf_deldbrows = 0; // Number of db tables to delete data from.

// Delete any items not required here.
$inf_newtable_[1] = "srpp_settings (
   slideshow_id INT(1) NOT NULL default '1',
	slideshow_type VARCHAR(20) NOT NULL default 'slide-left',
	slideshow_time INT(4) NOT NULL default '3000',
	slideshow_items INT(5) NOT NULL default '5'
) TYPE=MyISAM;";

$inf_droptable_[1] = "srpp_settings";

$inf_insertdbrow_[1] = "srpp_settings VALUES ('1', 'slide-left', '3000', '5');";

$inf_deldbrow_[1] = "srpp_settings";
?>