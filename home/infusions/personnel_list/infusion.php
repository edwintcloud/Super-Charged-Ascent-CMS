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

if (file_exists(INFUSIONS."personnel_list/locale/".$settings['locale'].".php")) {
	include INFUSIONS."personnel_list/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."personnel_list/locale/English.php";
}

// Infusion Information

$inf_title = $locale['pe100'];
$inf_description = $locale['pe101'];
$inf_version = "1.0";
$inf_developer = "Erkan DURAN (eduran)";
$inf_email = "eduran@balikesir.edu.tr";
$inf_weburl = "http://w3.balikesir.edu.tr/~eduran";

$inf_folder = "personnel_list";
$inf_admin_image = "";
$inf_admin_panel = "personnel_list.php";

$inf_link_name = $locale['pe100'];
$inf_link_url = "personnel_list.php";


$inf_newtables = 1;
$inf_altertables = 0;
$inf_deldbrows = 0;

$inf_newtable_[1] = "personnel_list (
personnel_id SMALLINT(3) NOT NULL,
personnel_order SMALLINT(2) NOT NULL,
personnel_name VARCHAR(50) NOT NULL DEFAULT '',
personnel_branch VARCHAR(50) NOT NULL DEFAULT '',
personnel_email VARCHAR(100) DEFAULT '',
personnel_web VARCHAR(100) DEFAULT '',
personnel_photo VARCHAR(20) DEFAULT '',
personnel_autobiography TEXT DEFAULT '',
PRIMARY KEY (personnel_id)
) TYPE=MyISAM;";

$inf_droptable_[1] = "personnel_list";
?>
