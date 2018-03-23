<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright  2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION")) header("Location:../index.php");

if (isset($_POST['previewpost'])) {
	$sticky_check = isset($_POST['sticky']) ? " checked" : "";
	$sig_checked = isset($_POST['show_sig']) ? " checked" : "";
	$disable_smileys_check = isset($_POST['disable_smileys']) ? " checked" : "";
	opentable($locale['400']);
	$subject = trim(stripinput(censorwords($_POST['subject'])));
	$message = trim(stripinput(censorwords($_POST['message'])));
	if ($subject == "") $subject = $locale['420'];
	if ($message == "") {
		$previewmessage = $locale['421'];
	} else {
		$previewmessage = $message;
		if ($sig_checked) { $previewmessage = $previewmessage."\n\n".$userdata['user_sig']; }
		if (!$disable_smileys_check) { $previewmessage = parsesmileys($previewmessage);	}
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$is_mod = in_array($data['user_id'], $forum_mods) && iUSER < "102" ? true : false;
	echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td width='145' class='tbl2'>".$locale['422']."</td>
<td class='tbl2'>$subject</td>
</tr>
<tr>
<td valign='top' rowspan='2' width='145' class='tbl1'>".$userdata['user_name']."<br>
<span class='alt'>".($is_mod ? $locale['userf1'] : getuserlevel($userdata['user_level']))."</span><br><br>\n";
if ($userdata['user_avatar']) {
	echo "<img src='".IMAGES."avatars/".$userdata['user_avatar']."'><br><br>\n";
	$height = "200";
} else {
	$height = "60";
}
echo "<span class='alt'>".$locale['423']."</span> ".$userdata['user_posts']."<br>
<span class='alt'>".$locale['424']."</span> ".$userdata['user_location']."<br>
<span class='alt'>".$locale['425']."</span> ".showdate("%d.%m.%y", $userdata['user_joined'])."</td>
<td class='tbl1'><span class='small'>".$locale['426'].showdate("forumdate", time())."</span></td>
</tr>
<tr>
<td height='$height' valign='top' class='tbl1'>$previewmessage</td>
</tr>
</table>
</tr>
</td>
</table>\n";
	closetable();
	tablebreak();
}
// Calculate random equation and answer
$var1 = rand(1,5);
$var2 = rand(1,5);
$equation = $var1 . " + " . $var2 . ":";
$validation_answer = $var1 + $var2;

if ($_POST['validation'] != "" && $_POST['validation'] == $_POST['validation_answer'])
	{

	if (isset($_POST['postnewthread'])) {
	

	$error = "0";
	$sticky = isset($_POST['sticky']) ? "1" : "0";
	$sig = isset($_POST['show_sig']) ? "1" : "0";
	$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
	$subject = trim(stripinput(censorwords($_POST['subject'])));
	$message = trim(stripinput(censorwords($_POST['message'])));
	if (iMEMBER) {
		if ($subject != "" && $message != "") {
			if (dbcount("(*)", "posts", "forum_id='$forum_id' AND post_message='$message' AND post_datestamp+84600>".time())) {
				redirect("index.php");
			} else {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
				$result = dbquery("INSERT INTO ".$db_prefix."threads VALUES('$forum_id', '', '$subject', '".$userdata['user_id']."', '0', '".time()."', '".$userdata['user_id']."', '$sticky', '0')");
				$thread_id = mysql_insert_id();
				$result = dbquery("INSERT INTO ".$db_prefix."posts VALUES('$forum_id', '$thread_id', '', '$subject', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '', '0')");
				$post_id = mysql_insert_id();
				$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
				
				$attach = $_FILES['attach'];
				if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name']) && $attach['size'] <= $settings['attachmax']) {
					$attachext = strtolower(strrchr($attach['name'],"."));
					$attachtypes = explode(",", $settings['attachtypes']);
					if (in_array($attachext, $attachtypes)) {
						$attachname = substr($attach['name'], 0, strrpos($attach['name'], "."));
						$result = dbquery("INSERT INTO ".$db_prefix."forum_attachments VALUES('', '$thread_id', '$post_id', '$attachname', '$attachext', '".$attach['size']."')");
						$file_id = mysql_insert_id();
						$attachname = $attachname."[".$file_id."]".$attachext;
						move_uploaded_file($attach['tmp_name'], FORUM."attachments/".$attachname);
						chmod(FORUM."attachments/".$attachname,0644);
					} else {
						$error = "1";
					}
				}
			}
		} else {
			$error = "2";
		}
	} else {
		$error = "3";
	}
	redirect("postify.php?post=new&error=$error&forum_id=$forum_id&thread_id=$thread_id");
	}
	else {
		
		}
	
} else {

	$invalid = true;
	$subject2 = trim(stripinput(censorwords($_POST['subject'])));
	$message2 = trim(stripinput(censorwords($_POST['message'])));
	if (isset($_POST['postnewthread']) && $invalid) echo "<div style='text-align:center'><br />You must answer the maths question correctly for your forum entry to be added. This is to prevent bots from spamming the forum. We apologise for the inconvenience.<br /><br /></div>";

	if (!isset($_POST['previewpost']) || $invalid) {
		$subject = "";
		$message = "";
		$sticky_check = "";
		$disable_smileys_check = "";
		$sig_checked = " checked";
	}
	opentable($locale['401']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?action=newthread&forum_id=$forum_id' enctype='multipart/form-data'>
<table border='0' cellspacing='0' cellpadding='0' width='100%' class='tbl-border'>
<tr>
<td>
<table width='100%' border='0' cellspacing='1' cellpadding='0'>
<tr>
<td width='145' class='tbl2'>".$locale['460']."</td>
<td class='tbl2'><input type='text' name='subject' value='"; if($invalid) { echo $subject2; } else { $subject; } echo"' class='textbox' maxlength='255' style='width: 250px'></td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".$locale['461']."</td>
<td class='tbl1'><textarea name='message' cols='80' rows='15' class='textbox'>"; if($invalid) { echo $message2; } else { $message; } echo"</textarea></td>
</tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\">
</td>
</tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl1'>
".$locale['462']."<select name='bbcolor' class='textbox' style='width:90px;' onChange=\"addText('message', '[color=' + this.options[this.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\">
<option value=''>Default</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
</td>
</tr>
<tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
".displaysmileys("message")."
</td>
</tr>
<tr>
<td width='145' class='tbl2'>".$equation."</td>
<td class='tbl2'><input type='text' name='validation' value='' class='textbox' style='width:250px'>
<input type='hidden' name='validation_answer' value='$validation_answer' class='textbox' style='width:250px'>
</td>
<tr>
<td valign='top' width='145' class='tbl2'>".$locale['463']."</td>
<td class='tbl1'>\n";
	if (iMOD) {
		echo "<input type='checkbox' name='sticky' value='1'$sticky_check>".$locale['480']."<br>\n";
	}
	echo "<input type='checkbox' name='disable_smileys' value='1'$disable_smileys_check>".$locale['483'];
	if ($userdata['user_sig']) {
		echo "<br>\n<input type='checkbox' name='show_sig' value='1'$sig_checked>".$locale['481'];
	}
	echo "</td>
</tr>\n";
	if ($settings['attachments'] == "1") {
		echo "<tr>
<td width='145' class='tbl2'>".$locale['464']."</td>
<td class='tbl1'><input type='file' name='attach' enctype='multipart/form-data' class='textbox' style='width:200px;'></td>
</tr>\n";
	}
	echo "</table>
</td>
</tr>
</table>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='previewpost' value='".$locale['400']."' class='button'>
<input type='submit' name='postnewthread' value='".$locale['401']."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
?>