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
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";

if (file_exists(INFUSIONS."custom_contact/locale/".$settings['locale'].".php")) {
	include INFUSIONS."custom_contact/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."custom_contact/locale/English.php";
}

$csettings = dbarray(dbquery("SELECT * FROM ".$db_prefix."contact_settings"));

if (checkgroup($csettings['contact_access'])) {
	if (isset($_POST['sendmessage'])) {
		$error = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."contact_fields WHERE cf_must=1 AND cf_readonly=0 ORDER BY cf_order");
		while($data = dbarray($result)) {
			if (checkgroup($data['cf_access'])) {
				$id = $data['cf_id'];
				if ($data['cf_type'] == 1 && $_POST["cf_text_".$id] == "") {
					$error .= "<li>".$data['cf_name']."</li>\n";
				} elseif ($data['cf_type'] == 2 && $_POST["cf_textarea_".$id] == "") {
					$error .= "<li>".$data['cf_name']."</li>\n";
				} elseif ($data['cf_type'] == 4 && !$_POST["cf_radio_".$id]) {
					$error .= "<li>".$data['cf_name']."</li>\n";
				} elseif ($data['cf_type'] == 5) {
					$ok = 0;				
					$cf_options_list = explode("\r\n", $data['cf_options']);
					for ($i=0;$i < count($cf_options_list);$i++) {
						$k = $i+1;
						if ($_POST["cf_checkbox_".$id."_".$k]) $ok = 1;
					}
					if ($ok == 0) {
						$error .= "<li>".$data['cf_name']."</li>\n";
					}
				}
			}
		}
		if ($error != "") $error = $locale['ccf_178']."<ul type='disc'>\n".$error."\n</ul>\n<br>\n";
		$error1 = "";
		$error2 = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."contact_fields WHERE cf_type=1 AND cf_typecheck!=0 ORDER BY cf_order");
		while($data = dbarray($result)) {
			if (checkgroup($data['cf_access'])) {
				$id = $data['cf_id'];
				if ($data['cf_typecheck'] == "1") {
					if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST["cf_text_".$id])) {
						$error1 .= "<li>".$data['cf_name']."</li>\n";
					}
				} elseif ($data['cf_typecheck'] == "2") {
					if (!isNum($_POST["cf_text_".$id])) {
						$error2 .= "<li>".$data['cf_name']."</li>\n";
					}
				}
			}
		}
		if ($error1 != "") {
			$error .= $locale['ccf_149am']."<ul type='disc'>\n".$error1."\n</ul>\n<br>\n";
		} elseif ($error1 != "") {
			$error .= $locale['ccf_149bm']."<ul type='disc'>\n".$error2."\n</ul>\n<br>\n";
		}
		if ($error == "") {
			if ($csettings['contact_captcha_type'] > 0 && !check_captcha($_POST['captcha_encode'], $_POST['captcha_code'])) {
				$error = "<center>\n<br>\n".$locale['ccf_183']."\n</center>\n<br>\n";
			}
		}
		if ($error == "") {
			opentable($csettings['contact_title']);
			$message = "";
			$result = dbquery("SELECT * FROM ".$db_prefix."contact_fields ORDER BY cf_order");
			while($data = dbarray($result)) {
				if (checkgroup($data['cf_access'])) {
					$id = $data['cf_id'];
					$message .= $data['cf_name'].": ";
					if ($data['cf_type'] == 1) {
						if ($data['cf_readonly'] == "1") {
							$message .= $data['cf_default']."\n\n";
						} else {
							$message .= stripinput(trim($_POST["cf_text_".$id]))."\n\n";
						}
					} elseif ($data['cf_type'] == 2) {
						if ($data['cf_readonly'] == "1") {
							$message .= "\n".$data['cf_default']."\n\n";
						} else {
							$message .= "\n".descript(stripslash(trim($_POST["cf_textarea_".$id])))."\n\n";
						}
					} else {
						$cf_options_list = explode("\r\n", $data['cf_options']);
						if ($data['cf_type'] == 3) {
							if ($data['cf_readonly'] == "1") {
								$i = $data['cf_default'];
								$k = $i-1;
								$message .= $cf_options_list[$k]."\n\n";
							} else {
								$i = (isnum($_POST["cf_select_".$id]) ? $_POST["cf_select_".$id] : "");
								$k = $i-1;
								$message .= $cf_options_list[$k]."\n\n";
							}
						} elseif ($data['cf_type'] == 4) {
							if ($data['cf_readonly'] == "1") {
								$i = $data['cf_default'];
								$k = $i-1;
								$message .= $cf_options_list[$k]."\n\n";
							} else {
								$i = (isnum($_POST["cf_radio_".$id]) ? $_POST["cf_radio_".$id] : "");
								$k = $i-1;
								$message .= $cf_options_list[$k]."\n\n";
							}
						} elseif ($data['cf_type'] == 5) {
							$message .= "\n";
							if ($data['cf_readonly'] == "1") {
								for ($i=0;$i < count($cf_options_list);$i++) {
									$k = $i+1;
									if (in_array($k, explode(".", $data['cf_default']))) $message .= " - ".$cf_options_list[$i]."\n";
								}
							} else {
								for ($i=0;$i < count($cf_options_list);$i++) {
									$k = $i+1;
									if ($_POST["cf_checkbox_".$id."_".$k] == "1") $message .= " - ".$cf_options_list[$i]."\n";
								}
							}
							$message .= "\n";
						}
					}
				}
			}
			if ($csettings['contact_bad_words_enabled'] == "1" && $csettings['contact_bad_words'] != "") {
				$word_list = explode("\r\n", $csettings['contact_bad_words']);
				for ($i=0;$i < count($word_list);$i++) {
					if ($word_list[$i] != "") {
						if (eregi($word_list[$i],$message)) {
							$error = "<li>".$word_list[$i]."</li>\n";
						}
					}
				}
			}
			if ($error != "") {
				echo "<center>".$locale['ccf_186']."</center><br><br>".$locale['ccf_187']."<ul type='disc'>\n".$error."</ul>\n";
			} else {
				if ($csettings['contact_show_ip'] == 1) $message = $locale['ccf_184'].": ".USER_IP."\n\n".$message;
				if ($csettings['contact_show_username'] == 1) $message = $locale['ccf_185'].": ".(iMEMBER ? $userdata['user_name'] : $locale['ccf_185g'])."\n\n".$message;
				require_once INCLUDES."sendmail_include.php";
				sendemail($csettings['contact_email_name'],$csettings['contact_email'],$settings['siteusername'],$settings['siteemail'],$csettings['contact_email_title'],$message);
				echo "<center>\n<br>\n".$locale['ccf_179']."\n</center>\n<br>\n";
			}
			closetable();
		} else {
			opentable($locale['ccf_176']);
			echo $error;
			closetable();
		}
	} else {
		$result = dbquery("SELECT * FROM ".$db_prefix."contact_fields ORDER BY cf_order");
		if (dbrows($result) != 0) {
			opentable($csettings['contact_title']);
			echo stripslashes($csettings['contact_message'])."<br>\n";
			echo "<form name='contactform' method='post' action='".FUSION_SELF."'>\n";
			echo "<table align='center' cellpadding='0' cellspacing='0'>\n";
			while($data = dbarray($result)) {
				if (checkgroup($data['cf_access'])) {
					echo "<tr>\n<td class='tbl' valign='top'>".$data['cf_name'].":";
					if ($data['cf_must'] == "1") echo " <span style='color:#ff0000'>*</span> ";
					echo "</td>\n<td class='tbl'>";
					$readonly = "";
					$default = "";
					if ($data['cf_type'] == "1") {
						if ($data['cf_readonly'] == "1") $readonly = " readonly";
						if ($data['cf_default'] != "") $default = " value='".$data['cf_default']."'";
						echo "<input type='text' name='cf_text_".$data['cf_id']."'$default class='textbox' style='width:200px'$readonly>";
					} elseif ($data['cf_type'] == "2") {
						if ($data['cf_readonly'] == "1") $readonly = " readonly";
						echo "\n<textarea name='cf_textarea_".$data['cf_id']."' rows='5' cols='50' class='textbox'$readonly>".stripslashes($data['cf_default'])."</textarea>\n";
					} else {
						$cf_options_list = explode("\r\n", $data['cf_options']);
						if ($data['cf_type'] == "3") {
							if ($data['cf_readonly'] == "1") $readonly = " disabled";
							echo "<select name='cf_select_".$data['cf_id']."' class='textbox'$readonly>\n";
							for ($i=0;$i < count($cf_options_list);$i++) {
								$k = $i+1;
								$default = "";
								if ($data['cf_default'] == $k) $default = " selected";
								echo "<option value='$k'$default>".$cf_options_list[$i]."</option>\n";
							}
							echo "</select>\n";
						} elseif ($data['cf_type'] == "4") {
							if ($data['cf_readonly'] == "1") $readonly = " disabled";
							for ($i=0;$i < count($cf_options_list);$i++) {
								$k = $i+1;
								$default = "";
								if ($data['cf_default'] == $k) $default = " checked";
								echo "<input type='radio' name='cf_radio_".$data['cf_id']."' value='$k'$default$readonly> ".$cf_options_list[$i]."<br>\n";
							}
						} elseif ($data['cf_type'] == "5") {
							if ($data['cf_readonly'] == "1") $readonly = " disabled";
							for ($i=0;$i < count($cf_options_list);$i++) {
								$default = "";
								$k = $i+1;
								if (in_array($k, explode(".", $data['cf_default']))) $default = " checked";
								echo "<input type='checkbox' name='cf_checkbox_".$data['cf_id']."_$k' value='1'$default$readonly> ".$cf_options_list[$i]."<br>\n";
							}
						}
					}
					echo "</td>\n</tr>\n";
				}
			}
			if ($csettings['contact_captcha_type'] > 0) {
				if ($csettings['contact_captcha_type'] == "1") $settings['validation_method'] = "image";
				if ($csettings['contact_captcha_type'] == "2") $settings['validation_method'] = "text";
				echo "<tr>\n<td class='tbl'>".$locale['ccf_181'].":</td>\n<td class='tbl'>";
				echo make_captcha();
				echo "</td>\n</tr>\n<tr>\n";
				echo "<td class='tbl'>".$locale['ccf_182'].": <span style='color:#ff0000'>*</span> </td>\n";
				echo "<td class='tbl'><input type='text' name='captcha_code' class='textbox' style='width:100px'></td>\n</tr>\n";
			}
			echo "<tr>\n<td class='tbl' colspan='2' align='center'>\n<br>\n";
			echo "<input type='submit' name='sendmessage' value='".$locale['ccf_180']."' class='button'>\n";
			echo "</td>\n</tr>\n";
			echo "</table>\n</form>\n";
			closetable();
		} else {
			opentable($locale['ccf_176']);
			echo $locale['ccf_170'];
			closetable();
		}
	}
} else {
	opentable($locale['ccf_176']);
	echo $locale['ccf_177'];
	closetable();
}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>