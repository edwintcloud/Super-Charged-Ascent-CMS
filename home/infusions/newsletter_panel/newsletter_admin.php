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
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
require_once INCLUDES."sendmail_include.php";

if (file_exists(INFUSIONS."newsletter_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."newsletter_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."newsletter_panel/locale/English.php";
}

if (!checkrights("IP")) fallback("../index.php");

if (isset($_POST['save'])) {
	$subject = addslash($_POST['subject']);
	$content = addslash($_POST['content']);
	if (isset($newsletter_id)) {
		$result = dbquery("UPDATE ".$db_prefix."newsletters SET newsletter_subject='$subject', newsletter_content='$content', newsletter_format='".$_POST['format']."' WHERE newsletter_id='$newsletter_id'");
		opentable($locale['nl411']);
		echo "<center><br>
".$locale['nl415']."<br><br>
<a href='newsletter_admin.php'>".$locale['nl421']."</a><br><br>
<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
</center>\n";
		closetable();
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."newsletters VALUES('', '$subject', '$content', '".$_POST['format']."', '".time()."')");
		opentable($locale['nl410']);
		echo "<center><br>
".$locale['nl414']."<br><br>
<a href='newsletter_admin.php'>".$locale['nl421']."</a><br><br>
<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
</center>\n";
		closetable();
	}
} else if (isset($_POST['send'])) {
	$subject = stripslash($_POST['subject']);
	if ($_POST['format'] == "plain") {
		$content = stripslash($_POST['content']);
	} else if ($_POST['format'] == "html") {
		$content = "<html>
<head>
<style type=\"text/css\">
<!--
a { color: #0000ff; text-decoration:none; }
a:hover { color: #0000ff; text-decoration: underline; }
body { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:10px; }
p { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:10px; }
.td { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:10px; }
-->
</style>
</head>
<body>
".stripslashes($_POST['content'])."
</body>
</html>";
	}
	$result = dbquery(
		"SELECT tns.*, tu.user_id,user_name,user_email FROM ".$db_prefix."newsletter_subs tns
		LEFT JOIN ".$db_prefix."users tu ON tns.newsletter_sub_user=tu.user_id"
	);
	if (dbrows($result)) {
		$i = 1; $rows = dbrows($result); $bcc_list = "";
		while ($data = dbarray($result)) {
			$bcc_list .= ($i != 1 ? ", " : "").$data['user_email'];
			if ($rows == 1 || $i == 99) {
				if (!sendemail($settings['siteusername'],$settings['siteemail'],$settings['siteusername'],$settings['siteemail'],$subject,$content,$_POST['format'],"", $bcc_list)) {
					$error = $locale['nl418'];
				}
				$bcc_list = "";
			}
			if ($i != 99) { $i++; } else { $i = 1; }
			$rows--;
		}
	} else {
		$error = $locale['nl419'];
	}
	opentable($locale['nl412']);
	echo "<center><br>\n";
	if (!$error) {
		echo $locale['nl416']."<br><br>\n";
	} else {
		echo $locale['nl417']."<br><br>\n".$error."<br><br>\n";
	}
	echo "<a href='newsletter_admin.php'>".$locale['nl421']."</a><br><br>
<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
</center>\n";
	closetable();
} else if (isset($_POST['delete'])) {
	$result = dbquery("DELETE FROM ".$db_prefix."newsletters WHERE newsletter_id='$newsletter_id'");
	opentable($locale['nl413']);
	echo "<center><br>
".$locale['nl420']."<br><br>
<a href='newsletter_admin.php'>".$locale['nl421']."</a><br><br>
<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['preview'])) {
		$subject = phpentities(stripslash($_POST['subject']));
		$content = phpentities(stripslash($_POST['content']));
		$plain = ($_POST['format'] == "plain" ? " checked" : "");
		$html = ($_POST['format'] == "html" ? " checked" : "");
		if ($_POST['format'] == "plain") {
			$contentpreview = nl2br(stripslash($_POST['content']));
		} else {
			$contentpreview = stripslash($_POST['content']);
		}
		opentable($subject);
		echo "$contentpreview\n";
		closetable();
		tablebreak();
	}
	opentable($locale['nl400']);
	$editlist = ""; $sel = "";
	$result = dbquery("SELECT * FROM ".$db_prefix."newsletters ORDER BY newsletter_datestamp DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($newsletter_id)) $sel = ($newsletter_id == $data['newsletter_id'] ? " selected" : "");
			$editlist .= "<option value='".$data['newsletter_id']."'$sel>".$data['newsletter_subject']."</option>\n";
		}
	}
	echo "<form name='selectform' method='post' action='".FUSION_SELF."'>
<center>
<select name='newsletter_id' class='textbox' style='width:250px;'>
$editlist</select>
<input type='submit' name='edit' value='".$locale['nl401']."' class='button'>
<input type='submit' name='delete' value='".$locale['nl402']."' onclick='return DeleteNewsletter();' class='button'><br><br>
<img src='".THEME."images/bullet.gif'>
<a href='newsletter_subs.php'>".$locale['nl403']."</a>
<img src='".THEME."images/bulletb.gif'>
</center>
</form>\n";
	closetable();
	tablebreak();
	if (isset($_POST['edit'])) {
		$result = dbquery("SELECT * FROM ".$db_prefix."newsletters WHERE newsletter_id='$newsletter_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$subject = phpentities(stripslashes($data['newsletter_subject']));
			$content = phpentities(stripslashes($data['newsletter_content']));
			$plain = ($data['newsletter_format'] == "plain" ? " checked" : "");
			$html = ($data['newsletter_format'] == "html" ? " checked" : "");
		}
	}
	if (isset($newsletter_id)) {
		$action = FUSION_SELF."?newsletter_id=$newsletter_id";
		opentable($locale['nl411']);
	} else {
		if (!isset($_POST['preview'])) {
			$subject = "";
			$content = "";
			$html = "";
			$plain = " checked";
		}
		$action = FUSION_SELF;
		opentable($locale['nl410']);
	}
	echo "<form name='inputform' method='post' action='$action' onSubmit='return ValidateForm(this)'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='100'>".$locale['nl430']."</td>
<td><input type='text' name='subject' value='$subject' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td valign='top' width='100'>".$locale['nl431']."</td>
<td><textarea name='content' cols='95' rows='15' class='textbox'>$content</textarea></td>
</tr>
<tr>
<td>".$locale['nl432']."</td>
<td>
<input type='button' value='p' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('content', '<p>', '</p>');\">
<input type='button' value='br' class='button' style='font-weight:bold;width:25px;' onClick=\"insertText('content', '<br>');\">
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('content', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('content', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('content', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('content', '<a href=\'http://\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('content', '<img src=\'".$settings['siteurl']."fusion_images/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('content', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('content', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('content', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('content', '<span class=\'alt\'>', '</span>');\">
</td>
</tr>
<tr>
<td><br>".$locale['nl433']."</td>
<td><br><input type='radio' name='format' value='plain'$plain>".$locale['nl434']." <input type='radio' name='format' value='html'$html>".$locale['nl435']."</td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='preview' value='".$locale['nl436']."' class='button'>
<input type='submit' name='save' value='".$locale['nl437']."' class='button'>
<input type='submit' name='send' value='".$locale['nl438']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	echo "<script language=\"JavaScript\">
function DeleteNewsletter() {
	return confirm('".$locale['nl451']."');
}
function ValidateForm(frm) {
	if(frm.subject.value=='') {
		alert('".$locale['nl450']."');
		return false;
	}
}
</script>\n";
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>