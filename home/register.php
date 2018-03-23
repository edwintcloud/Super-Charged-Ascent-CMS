<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright � 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."register.php";
include LOCALE.LOCALESET."user_fields.php";
include INCLUDES."sendmail_include.php";

if (iMEMBER) fallback("index.php");

if ($settings['enable_registration']) {

if (isset($activate)) {
	$result = dbquery("SELECT * FROM ".$db_prefix."new_users WHERE user_code='$activate'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$user_info = unserialize($data['user_info']);
		$activation = $settings['admin_activation'] == "1" ? "2" : "0";
		$result = dbquery("INSERT INTO ".$db_prefix."users VALUES('', '".$user_info['user_name']."', '".md5($user_info['user_password'])."', '".$user_info['user_email']."', '".$user_info['user_hide_email']."', '', '0000-00-00', '', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$activation')");
		// Subscribe user to Newsletter when user registers //
		$user_id = mysql_insert_id();
		$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_code='$activate'");	
		opentable($locale['401']);
		if ($settings['admin_activation'] == "1") {
			echo "<center><br>\n".$locale['455']."<br><br>\n".$locale['453']."<br><br>\n</center>\n";
		} else {
			echo "<center><br>\n".$locale['455']."<br><br>\n".$locale['452']."<br><br>\n</center>\n";
		}
		closetable();
	} else {
		redirect("index.php");
	}
} else if (isset($_POST['register'])) {
	$username = stripinput(trim(eregi_replace(" +", " ", $_POST['username'])));
	$email = stripinput(trim(eregi_replace(" +", "", $_POST['email'])));
	$password1 = stripinput(trim(eregi_replace(" +", "", $_POST['password1'])));
	
	if ($username == "" || $password1 == "" || $email == "") $error .= $locale['402']."<br>\n";
	
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['403']."<br>\n";
	
	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
		if ($password1 != $_POST['password2']) $error .= $locale['404']."<br>\n";
	} else {
		$error .= $locale['405']."<br>\n";
	}
 
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= $locale['406']."<br>\n";
	}
	
	$email_domain = substr(strrchr($email, "@"), 1);
	$result = dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_email='".$email."' OR blacklist_email='$email_domain'");
	if (dbrows($result) != 0) $error = $locale['411']."<br>\n";
	
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$username'");
	if (dbrows($result) != 0) $error = $locale['407']."<br>\n";
	
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='".$email."'");
	if (dbrows($result) != 0) $error = $locale['408']."<br>\n";
	
	if ($settings['email_verification'] == "1") {
		$result = dbquery("SELECT * FROM ".$db_prefix."new_users");
		while ($new_users = dbarray($result)) {
			$user_info = unserialize($new_users['user_info']); 
			if ($new_users['user_email'] == $email) { $error = $locale['409']."<br>\n"; }
			if ($user_info['user_name'] == $username) { $error = $locale['407']."<br>\n"; break; }
		}
	}
	
	if ($settings['display_validation'] == "1") {
		$user_code = stripinput($_POST['user_code']);
		$result = dbquery("SELECT * FROM ".$db_prefix."vcode WHERE vcode_1='$user_code'");
		if (dbrows($result) == 0) {
			$error .= $locale['410']."<br>\n";
		} else {
			$result = dbquery("DELETE FROM ".$db_prefix."vcode WHERE vcode_1='$user_code'");
		}
	}
	
	$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
	
	if ($settings['email_verification'] == "0") {
		$user_location = isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "";
		if ($_POST['user_month'] != 0 && $_POST['user_day'] != 0 && $_POST['user_year'] != 0) {
			$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "0000")
			."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "00")
			."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "00");
		} else {
			$user_birthdate = "0000-00-00";
		}
		$user_aim = isset($_POST['user_aim']) ? stripinput(trim($_POST['user_aim'])) : "";
		$user_icq = isset($_POST['user_icq']) ? stripinput(trim($_POST['user_icq'])) : "";
		$user_msn = isset($_POST['user_msn']) ? stripinput(trim($_POST['user_msn'])) : "";
		$user_yahoo = isset($_POST['user_yahoo']) ? stripinput(trim($_POST['user_yahoo'])) : "";
		$user_web = isset($_POST['user_web']) ? stripinput(trim($_POST['user_web'])) : "";
		$user_theme = stripinput($_POST['user_theme']);
		$user_offset = is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0";
		$user_sig = isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "";
	}
	if ($error == "") {
		if ($settings['email_verification'] == "1") {
			mt_srand((double)microtime()*1000000);
			for ($i=0;$i<=7;$i++) { $salt .= chr(rand(97, 122)); }
			$user_code = md5($email.$salt);
			$activation_url = $settings['siteurl']."register.php?activate=".$user_code;
			if (sendemail($username,$email,$settings['siteusername'],$settings['siteemail'],"Welcome to ".$settings['sitename'], $locale['450'].$activation_url)) {
				$user_info = serialize(array(
					"user_name" => $username,
					"user_password" => $password1,
					"user_email" => $email,
					"user_hide_email" => isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1"
				));
				$result = dbquery("INSERT INTO ".$db_prefix."new_users VALUES('$user_code', '".$email."', '".time()."', '$user_info')");
				opentable($locale['400']);
				echo "<center><br>\n".$locale['454']."<br><br>\n</center>\n";
				closetable();
			} else {
				opentable($locale['400']);
				echo "<center><br>\n".$locale['456']."<br><br>\n</center>\n";
				closetable();
			}
		} else {
			$activation = $settings['admin_activation'] == "1" ? "2" : "0";
			$result = dbquery("INSERT INTO ".$db_prefix."users VALUES('', '$username', md5('".$password1."'), '".$email."', '$user_hide_email', '$user_location', '$user_birthdate', '$user_aim', '$user_icq', '$user_msn', '$user_yahoo', '$user_web', '$user_theme', '$user_offset', '', '$user_sig', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$activation')");
			// Subscribe user to Newsletter when user registers //
			$user_id = mysql_insert_id();
			opentable($locale['400']);
			if ($settings['admin_activation'] == "1") {
				echo "<center><br>\n".$locale['451']."<br><br>\n".$locale['453']."<br><br>\n</center>\n";
			} else {
				echo "<center><br>\n".$locale['451']."<br><br>\n".$locale['452']."<br><br>\n</center>\n";
			}
			closetable();
		}
	} else {
		opentable($locale['400']);
		echo "<center><br>\n".$locale['456']."<br><br>\n$error<br>\n<a href='".FUSION_SELF."'>".$locale['459']."</a></div></br>\n";
		closetable();
	}
} else {
	if ($settings['email_verification'] == "0") {
		$theme_files = makefilelist(THEMES, ".|..", true, "folders");
		array_unshift($theme_files, "Default");
		$offset_list = "";
		for ($i=-13;$i<17;$i++) {
			if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
			$offset_list .= "<option".($offset == "0" ? " selected" : "").">$offset</option>\n";
		}
	}
	if ($settings['display_validation'] == "1") {
		srand((double)microtime()*1000000); 
		$temp_num = md5(rand(0,9999)); 
		$vcode_1 = substr($temp_num, 17, 5); 
		$vcode_2 = md5($vcode_1);
		unset($temp_num);
		$result = dbquery("INSERT INTO ".$db_prefix."vcode VALUES('".time()."', '$vcode_1', '$vcode_2')");
	}
	opentable($locale['400']);
	echo "<center>".$locale['500']."\n";
	if ($settings['email_verification'] == "1") echo $locale['501']."\n";
	echo $locale['502'];
	if ($settings['email_verification'] == "1") echo "\n".$locale['503'];
	echo "</center><br>
<table align='center' cellspacing='0' cellpadding='0'>
<form name='inputform' method='post' action='".FUSION_SELF."' onSubmit='return ValidateForm(this)'>
<tr>
<td class='tbl'>".$locale['u001']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u002']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u004']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u005']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u006']."</td>
<td class='tbl'><input type='radio' name='user_hide_email' value='1'>".$locale['u007']."
<input type='radio' name='user_hide_email' value='0' checked>".$locale['u008']."</td>
</tr>\n";
	if ($settings['display_validation'] == "1") {
		echo "<tr>\n<td class='tbl'>".$locale['504']."</td>\n<td class='tbl'>";
		if ($settings['validation_method'] == "image") {
			echo "<img src='?vimage=$vcode_2'>\n";
		} else {
			echo "<b>$vcode_1</b>\n";
		}
		unset($vcode_1,$vcode_2);
		echo "</td>\n</tr>\n";
		echo "<tr>
<td class='tbl'>".$locale['505']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='user_code' class='textbox' style='width:100px'></td>
</tr>\n";
	}
	if ($settings['email_verification'] == "0") {
		echo "<tr>
<td class='tbl'>".$locale['u009']."</td>
<td class='tbl'><input type='text' name='user_location' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u010']." <span class='small2'>(mm/dd/yyyy)</span></td>
<td class='tbl'><select name='user_month' class='textbox'>\n<option> </option>\n";
		for ($i=1;$i<=12;$i++) echo "<option".($user_month == $i ? " selected" : "").">$i</option>\n";
		echo "</select>\n<select name='user_day' class='textbox'>\n<option> </option>\n";
		for ($i=1;$i<=31;$i++) echo "<option".($user_day == $i ? " selected" : "").">$i</option>\n";
		echo "</select>\n<select name='user_year' class='textbox'>\n<option> </option>\n";
		for ($i=1900;$i<=2004;$i++) echo "<option".($user_year == $i ? " selected" : "").">$i</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td class='tbl'>".$locale['u021']."</td>
<td class='tbl'><input type='text' name='user_aim' maxlength='16' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u011']."</td>
<td class='tbl'><input type='text' name='user_icq' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u012']."</td>
<td class='tbl'><input type='text' name='user_msn' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u013']."</td>
<td class='tbl'>
<input type='text' name='user_yahoo' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u014']."</td>
<td class='tbl'><input type='text' name='user_web' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u015']."</td>
<td class='tbl'><select name='user_theme' class='textbox' style='width:200px;'>
".makefileopts($theme_files)."
</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u016']."</td>
<td class='tbl'><select name='user_offset' class='textbox'>
$offset_list</select></td>
</tr>
<tr>
<td valign='top'>".$locale['u020']."</td>
<td class='tbl'>
<textarea name='user_sig' rows='5' class='textbox' style='width:295px'>".$userdata['user_sig']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('user_sig', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('user_sig', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('user_sig', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('user_sig', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('user_sig', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('user_sig', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('user_sig', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('user_sig', '[small]', '[/small]');\">
</td>
</tr>\n";
	}
	echo "<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='register' value='".$locale['506']."' class='button'>
</td>
</tr>
</form>
</table>";
	closetable();
	echo "<script language='JavaScript'>
function ValidateForm(frm) {
	if (frm.username.value==\"\") {
		alert(\"".$locale['550']."\");
		return false;
	}
	if (frm.password1.value==\"\") {
		alert(\"".$locale['551']."\");
		return false;
	}
	if (frm.email.value==\"\") {
		alert(\"".$locale['552']."\");
		return false;
	}
}
</script>\n";
}

} else {
	opentable($locale['400']);
	echo "<center><br>\n".$locale['507']."<br><br>\n</center>\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>