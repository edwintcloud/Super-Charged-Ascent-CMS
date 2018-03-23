<?
//Be sure to alter this to suit your site
$bannerheight = '240';
$bannerwidth = '120';


// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."banner_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include_once INFUSIONS."banner_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include_once INFUSIONS."banner_panel/locale/English.php";
}

global $fusion_prefix, $userdata, $locale;
$bresult = dbquery("select * from ".$db_prefix."banner WHERE status='1'");
$gotbanners = dbrows($bresult);
	if($gotbanners > "0")
	{
	opentable($locale['BRS171']);
echo '<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td align="center">';
	include_once INFUSIONS.'banner_panel/view.php';
	echo $banner_display;
echo '</td>
</tr>
</table>';
closetable();
}//if($bresult != "")
?>