<?php
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";

if (file_exists(INFUSIONS."button_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."button_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."button_panel/locale/English.php";
}
if (!checkrights("A")) fallback("../index.php");

opentable($locale['BLAN_101']);
switch ($axn) {
	default:
$result = dbquery("SELECT * FROM ".$db_prefix."buttons");
$rows = dbrows($result);
if ($rows>0) {
	echo "
<table width='100%' cellpadding='3' cellspacing='1' border='0' align='center' style='margin-top:1em;margin-bottom:1em;'>
	<tr>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_104']."</td>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_105']."</td>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_106']."</td>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_107']."</td>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_108']."</td>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_109']."</td>
	   <td align='center' class='tbl2' style='font-weight:bold'>".$locale['BLAN_112']."</td>
	</tr>";
while ($data = dbarray($result)) {
echo "
	<tr>
	   <td align='center' class='tbl1'>".$data['button_id']."</td>
	   <td align='center' class='tbl1'>".$data['button_name']."</td>
	   <td align='center' class='tbl1'><img src='".$data['button_pic']."'></td>
	   <td align='center' class='tbl1'>".$data['button_link']."</td>
	   <td align='center' class='tbl1'>".$data['button_user']."</td>
	   <td align='center' class='tbl1'>".$data['button_pass']."</td>
	   <td align='center' class='tbl1'><a href='".FUSION_SELF."?axn=edit&but_id=".$data['button_id']."'>".$locale['BLAN_110']."</a> | 
	   <a href='".FUSION_SELF."?axn=del&but_id=".$data['button_id']."'>".$locale['BLAN_111']."</a></td>
	</tr>";
}
echo "
	<tr>
		<td align='right' class='tbl2' colspan='8'><p style='padding:0.4em;'><a href='".FUSION_SELF."?axn=add'>".$locale['BLAN_103']."</a></p></td>
	</tr>
</table>
	";
} else {
	echo "<div style='text-align:center'><p style='margin-top:2em'><span style='color:#cc0000; weight:bold;font-size:1.5em;'>".$locale['BLAN_102']."</span></p>
	<p style='margin-bottom:2em'><a href='".FUSION_SELF."?axn=add'>".$locale['BLAN_103']."</a></p></div>";
}
	break;
	case 'add':
if (isset($_POST['add_button'])) {
	dbquery("INSERT INTO ".$db_prefix."buttons VALUES('', '".$_POST['butname']."', '".$_POST['butpic']."', '".$_POST['butlink']."', '0', '".$_POST['butuser']."', '".$_POST['butpass']."')");
	redirect (FUSION_SELF, "script");
} else {
	echo "
	<form action='".FUSION_SELF."?axn=add' method='post' style='margin:2em;'>
	<table width='60%' cellpadding='3' cellspacing='1' border='0' align='center'>
	<tr height='40'>
		<td align='center' class='tbl2' colspan='2'><span style='font-weight:bold;font-size:1.5em;'>".$locale['BLAN_103']."</span></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' width='35%' style='font-weight:bold;'>".$locale['BLAN_105']."</td>
	   <td class='tbl1' align='left' width='65%'><input type='text' name='butname' size='40' class='textbox'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_106']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butpic' size='40' class='textbox'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_107']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butlink' size='40' class='textbox'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_108']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butuser' size='40' class='textbox'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_109']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butpass' size='40' class='textbox'></td>
	</tr>
	<tr height='40'>
		<td align='center' class='tbl1' colspan='2'><input type='submit' name='add_button' value='".$locale['BLAN_113']."' class='button'></td>
	</tr>
</table>
</form>";
}
	break;
	case 'edit':
if (isset($_POST['edit_button'])) {
	dbquery("UPDATE ".$db_prefix."buttons SET button_name='".$_POST['butname']."', button_pic='".$_POST['butpic']."', button_link='".$_POST['butlink']."', button_user='".$_POST['butuser']."', button_pass='".$_POST['butpass']."' WHERE button_id='".$_POST['but_id']."'");
	redirect (FUSION_SELF, "script");
} else {
$result = dbquery("SELECT * FROM ".$db_prefix."buttons WHERE button_id=".$but_id."");
$data = dbarray($result);
	echo "<form action='".FUSION_SELF."?axn=edit' method='post' style='margin:2em;'>
	<p>
<table width='60%' cellpadding='3' cellspacing='1' border='0' align='center'>
	<tr height='40'>
		<td align='center' class='tbl2' colspan='2'><span style='font-weight:bold;font-size:1.5em;'>".$locale['BLAN_119']."</span></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' width='35%' style='font-weight:bold;'>".$locale['BLAN_105']."</td>
	   <td class='tbl1' align='left' width='65%'><input type='text' name='butname' size='40' class='textbox' value='".$data['button_name']."'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_106']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butpic' size='40' class='textbox' value='".$data['button_pic']."'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_107']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butlink' size='40' class='textbox' value='".$data['button_link']."'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_108']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butuser' size='40' class='textbox' value='".$data['button_user']."'></td>
	</tr>
	<tr>
	   <td class='tbl1' align='right' style='font-weight:bold;'>".$locale['BLAN_109']."</td>
	   <td class='tbl1' align='left'><input type='text' name='butpass' size='40' class='textbox' value='".$data['button_pass']."'></td>
	</tr>
	<tr height='40'>
		<td align='center' class='tbl1' colspan='2'><input type='hidden' name='but_id' value='$but_id'><input type='submit' name='edit_button' value='".$locale['BLAN_114']."' class='button'></td>
	</tr>
</table>
</p>
</form>
   ";
}
	break;
	case 'del':
if (isset($_POST['del_button'])) {
	dbquery("DELETE FROM ".$db_prefix."buttons WHERE button_id=".$_POST['but_id']."");
	redirect (FUSION_SELF, "script");
} else {
$result = dbquery("SELECT * FROM ".$db_prefix."buttons WHERE button_id=".$but_id."");
$data = dbarray($result);
echo "<form action='".FUSION_SELF."?axn=del' method='post' style='margin:2em;'>
<table width='50%' cellpadding='3' cellspacing='1' border='0' align='center'>
	<tr height='40'>
		<td align='center' class='tbl2'><span style='font-weight:bold;font-size:1.5em;'>".$locale['BLAN_120']."</span></td>
	</tr>
	<tr>
	   <td class='tbl1' align='center' width='35%'><p style='padding:2em;'>".$locale['BLAN_118']."<span style='font-weight:bold;'>".$data['button_name']."</span>?</td>
	</tr>
	<tr>
		<td align='center' class='tbl1'><input type='hidden' name='but_id' value='$but_id'><input type='submit' name='del_button' value='".$locale['BLAN_116']."' class='button'>
		<input type='button' onclick=\"history.go(-1);\" value='".$locale['BLAN_117']."' class='button'></td>
	</tr>
</table>
</form>";
}
	break;
}
closetable();
require_once BASEDIR."footer.php";
?>