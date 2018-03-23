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
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
if (file_exists(INFUSIONS."button_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."button_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."button_panel/locale/English.php";
}

switch($axn) {
	default:

opentable($locale['BLAN_003']);
echo"<form action='".INFUSIONS."button_panel/button_client.php?axn=show' method='post'>
<table width='200px' cellpadding='2' cellspacing='0' border='0' class='tbl' align='center'>
<tr>
	<td colspan='2' style='text-align:center; font-weight:bold; padding:1em;'>".$locale['BLAN_003']."</td>
</tr>
<tr>
	<td width='80px'>".$locale['BLAN_004'].": </td>
	<td><input type='text' name='blogin' size='20' maxlength='50' class='textbox'></td>
</tr>
<tr>
	<td width='80px'>".$locale['BLAN_005'].": </td>
	<td><input type='password' name='bpass' size='20' maxlength='50' class='textbox'></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type='submit' value='".$locale['BLAN_006']."' class='button'></td>
</tr>
</table>
</form>";
closetable();

	break;
	case "show":

	$result = dbquery("SELECT * FROM ".$db_prefix."buttons WHERE button_user='".$_POST['blogin']."' AND button_pass='".$_POST['bpass']."'");
	$rows = dbrows($result);
	if ($rows>0) {
		$data=dbarray($result);
	opentable($locale['BLAN_003']." - ".$data['button_name']);
		echo "<div style='text-align:center'>
			<p style='font-weight:bold; font-size:1.5em;'>".$locale['BLAN_003']."</p>
			<p><span style='font-weight:bold'>".$locale['BLAN_007'].":</span> ".$data['button_name']."</p>
			<p style='font-weight:bold'>".$locale['BLAN_008']."</p>
			<p><img src='".$data['button_pic']."' title='".$data['buton_name']."' alt='".$data['buton_name']."'></p>
			<p><span style='font-weight:bold'>".$locale['BLAN_010'].": </span>".$data['button_link']."</p>
			<p><span style='font-weight:bold'>".$locale['BLAN_009'].": </span>".$data['button_count']."</p>
		</div>";
	} else {
		opentable($locale['BLAN_003']);
		echo "<div style='text-align:center'><p style='color:#cc0000; margin:2em;'>".$locale['BLAN_002']."</p></div>";
	}
	
	closetable();
	break;

}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>
