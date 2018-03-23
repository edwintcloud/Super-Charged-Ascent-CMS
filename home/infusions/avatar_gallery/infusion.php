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
if (!defined("IN_FUSION") || !checkRights("C")) { header("Location:../../index.php"); exit; }

if (!file_exists(INFUSIONS."avatar_gallery/locale/".$settings['locale'].".php")) {
	include INFUSIONS."avatar_gallery/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."avatar_gallery/locale/English.php";
}

// Infusion Information
$inf_title = $locale['AVA_title'];
$inf_description = $locale['AVA_description'];
$inf_version = "1.20";
$inf_developer = "Daniel Zschintzsch , Carsten Pukaß";
$inf_email = "webmaster@carstenpukass.de";
$inf_weburl = "http://www.carstenpukass.de";

$inf_folder = "avatar_gallery";
$inf_admin_image = "";
$inf_admin_panel = "";
$inf_link_name = $locale['AVA_link'];
$inf_link_url = "avatar_gallery.php";
$inf_link_visibility = "101";

$inf_newtables = 0;
$inf_altertables = 0;
$inf_deldbrows = 0;

$inf_newtable_[1] = "";
$inf_droptable_[1] = "";
$inf_altertable_[1] = "";
$inf_deldbrow_[1] = "";
?>