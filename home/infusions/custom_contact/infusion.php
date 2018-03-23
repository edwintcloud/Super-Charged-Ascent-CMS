<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2007 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------+
| Custom Contact for PHP-Fusion 6
+----------------------------------------------------+
| Author: Korcsii
| E-mail: korcsii.adm@php-fusion.co.hu
| Web: http://www.php-fusion.co.hu
+----------------------------------------------------*/
if (!defined("IN_FUSION") || !checkrights("I")) { header("Location: ../../index.php"); exit; }

if (file_exists(INFUSIONS."custom_contact/locale/".$settings['locale'].".php")) {
	include INFUSIONS."custom_contact/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."custom_contact/locale/English.php";
}

$inf_title = $locale['ccf_100'];
$inf_description = $locale['ccf_101'];
$inf_version = "1.00";
$inf_developer = "Korcsii";
$inf_email = "korcsii.adm@php-fusion.co.hu";
$inf_weburl = "http://www.php-fusion.co.hu";

$inf_folder = "custom_contact";
$inf_admin_image = "";
$inf_admin_panel = "admin.php";
$inf_link_name = $locale['ccf_102'];
$inf_link_url = "contact.php";
$inf_link_visibility = "0";

$inf_newtables = 2;
$inf_insertdbrows = 5;
$inf_altertables = 0;
$inf_deldbrows = 2;

$inf_newtable_[1] = "contact_settings (
contact_access SMALLINT(5) NOT NULL,
contact_title VARCHAR(200) NOT NULL,
contact_message TEXT NOT NULL,
contact_captcha_type SMALLINT(2) NOT NULL,
contact_bad_words_enabled TINYINT(1) NOT NULL,
contact_bad_words TEXT NOT NULL,
contact_email VARCHAR(200) NOT NULL,
contact_email_name VARCHAR(200) NOT NULL,
contact_email_title VARCHAR(200) NOT NULL,
contact_show_username TINYINT(1) NOT NULL,
contact_show_ip TINYINT(1) NOT NULL
) TYPE=MyISAM;";

$inf_newtable_[2] = "contact_fields (
cf_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
cf_order SMALLINT(5) NOT NULL,
cf_access SMALLINT(5) NOT NULL,
cf_name VARCHAR(200) NOT NULL,
cf_must TINYINT(1) NOT NULL,
cf_type SMALLINT(2) NOT NULL,
cf_options TEXT NOT NULL,
cf_default TEXT NOT NULL,
cf_readonly TINYINT(1) NOT NULL,
cf_typecheck SMALLINT(2) NOT NULL,
PRIMARY KEY (cf_id)
) TYPE=MyISAM;";

$inf_insertdbrow_[1] = "contact_settings (contact_access, contact_title, contact_message, contact_captcha_type, contact_bad_words_enabled, contact_bad_words, contact_email, contact_email_name, contact_email_title, contact_show_username, contact_show_ip) VALUES ('0', '".$locale['ccf_103']."', '".$locale['ccf_104']."', '0', '0', '".$locale['ccf_188']."', '".$settings['siteemail']."', '".$settings['siteusername']."', '".$locale['ccf_105']."', '0', '0')";
$inf_insertdbrow_[2] = "contact_fields (cf_id, cf_order, cf_access, cf_name, cf_must, cf_type, cf_options, cf_default, cf_readonly, cf_typecheck) VALUES('1', '1', '0', '".$locale['ccf_106']."', '1', '1', '', '', '0', '0')";
$inf_insertdbrow_[3] = "contact_fields (cf_id, cf_order, cf_access, cf_name, cf_must, cf_type, cf_options, cf_default, cf_readonly, cf_typecheck) VALUES('2', '2', '0', '".$locale['ccf_107']."', '1', '1', '', '', '0', '1')";
$inf_insertdbrow_[4] = "contact_fields (cf_id, cf_order, cf_access, cf_name, cf_must, cf_type, cf_options, cf_default, cf_readonly, cf_typecheck) VALUES('3', '3', '0', '".$locale['ccf_108']."', '1', '1', '', '', '0', '0')";
$inf_insertdbrow_[5] = "contact_fields (cf_id, cf_order, cf_access, cf_name, cf_must, cf_type, cf_options, cf_default, cf_readonly, cf_typecheck) VALUES('4', '4', '0', '".$locale['ccf_109']."', '1', '2', '', '', '0', '0')";

$inf_droptable_[1] = "contact_settings";
$inf_droptable_[2] = "contact_fields";
?>