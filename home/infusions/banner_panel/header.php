<?
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include_once INCLUDES."sendmail_include.php";
global $bid, $locale;
// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."banner_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."banner_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."banner_panel/locale/English.php";
}

if (!iMEMBER) { fallback(BASEDIR."index.php"); }
echo '<!-- START Banner System by AusiMods @ http://www.ausimods.com -->';
?>
