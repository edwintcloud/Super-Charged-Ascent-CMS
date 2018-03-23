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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

if (file_exists(INFUSIONS."newsletter_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."newsletter_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."newsletter_panel/locale/English.php";
}

openside($locale['nl500']);
if (iMEMBER) {
	$result = dbquery("SELECT * FROM ".$db_prefix."newsletter_subs WHERE newsletter_sub_user='".$userdata['user_id']."'");
	if (dbrows($result) == 0) {
		if (isset($_POST['subscribe'])) {
			$result = dbquery("INSERT INTO ".$db_prefix."newsletter_subs VALUES('', '".$userdata['user_id']."')");
			redirect(FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
		}
		echo "<div align='center'>
".$locale['nl501']."<br><br>
<form name='newsletter' method='post' action='".FUSION_SELF."'>
<input type='submit' name='subscribe' value='".$locale['nl503']."' class='button'>
</form>
</div>\n";
	} else {
		if (isset($_POST['unsubscribe'])) {
			$result = dbquery("DELETE FROM ".$db_prefix."newsletter_subs WHERE newsletter_sub_user='".$userdata['user_id']."'");
			redirect(FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
		}
		echo "<div align='center'>
".$locale['nl502']."<br><br>
<form name='newsletter' method='post' action='".FUSION_SELF."'>
<input type='submit' name='unsubscribe' value='".$locale['nl504']."' class='button'>
</form>
</div>\n";
$sql_n = dbquery("SELECT COUNT(newsletter_sub_user) as antal FROM ".$db_prefix."newsletter_subs");
$data = dbarray($sql_n);
echo "<br><center>".$locale['nl505']."$data[antal]<br>".$locale['nl506']."</center>";
	}
} else {
	echo $locale['003'];
}
closeside();
?>