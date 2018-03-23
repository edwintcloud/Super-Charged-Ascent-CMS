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
if (!defined("IN_FUSION") || !checkrights("I")) { header("Location:../../index.php"); exit; }

if (file_exists(INFUSIONS."newsletter_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."newsletter_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."newsletter_panel/locale/English.php";
}

// Infusion Information
$inf_title = $locale['nl100'];
$inf_description = $locale['nl101'];
$inf_version = "1.40";
$inf_developer = "Digitanium";
$inf_email = "digitanium@php-fusion.co.uk";
$inf_weburl = "http://www.php-fusion.co.uk";

$inf_folder = "newsletter_panel";
$inf_admin_image = "infusion_panel.gif";
$inf_admin_panel = "newsletter_admin.php";

$inf_link_name = "";
$inf_link_url = "";
$inf_link_visibility = "";

$inf_newtables = 2;
$inf_altertables = 0;
$inf_deldbrows = 0;

$inf_newtable_[1] = "newsletters (
newsletter_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
newsletter_subject VARCHAR(200) NOT NULL DEFAULT '',
newsletter_content text NOT NULL,
newsletter_format VARCHAR(5) NOT NULL DEFAULT 'plain',
newsletter_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (newsletter_id)
) TYPE=MyISAM;";
$inf_newtable_[2] = "newsletter_subs (
newsletter_sub_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
newsletter_sub_user SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (newsletter_sub_id)
) TYPE=MyISAM;";

$inf_droptable_[1] = "newsletters";

$inf_droptable_[2] = "newsletter_subs";
?>