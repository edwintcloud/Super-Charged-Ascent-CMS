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
require_once ADMIN."navigation.php";

if (!checkrights("IP") || !defined("iAUTH") || $aid != iAUTH) fallback("../../index.php");

if (file_exists(INFUSIONS."custom_contact/locale/".$settings['locale'].".php")) {
	include INFUSIONS."custom_contact/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."custom_contact/locale/English.php";
}

if (!isset($section) || ($section != "fields" && $section != "current")) $section = "settings";
if (isset($cf_id) && !isNum($cf_id)) fallback(FUSION_SELF.$aidlink."&".$section);
if (isset($cf_order) && !isNum($cf_order) && $cf_order !=0 && $cf_order != "") fallback(FUSION_SELF.$aidlink."&".$section);
if (isset($cf_type) && (!isNum($cf_type) || $cf_type < 1 || $cf_type > 5)) fallback(FUSION_SELF.$aidlink."&".$section);

if (isset($status)) {
	if ($status == "del") {
		$title = $locale['ccf_118'];
		$message = "<b>".$locale['ccf_173']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($action))  {
	if ($action == "refresh" && $section == "current") {
		$i = 1;
		$result = dbquery("SELECT * FROM ".$db_prefix."contact_fields ORDER BY cf_order");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order='$i' WHERE cf_id='".$data['cf_id']."'");
			$i++;
		}
		redirect(FUSION_SELF.$aidlink."&section=current");
	} elseif ($action == "moveup" && $section == "current") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."contact_fields WHERE cf_order='$cf_order'"));
		$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order=cf_order+1 WHERE cf_id='".$data['cf_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order=cf_order-1 WHERE cf_id='$cf_id'");
		redirect(FUSION_SELF.$aidlink."&section=current");
	} elseif ($action == "movedown" && $section == "current") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."contact_fields WHERE cf_order='$cf_order'"));
		$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order=cf_order-1 WHERE cf_id='".$data['cf_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order=cf_order+1 WHERE cf_id='$cf_id'");
		redirect(FUSION_SELF.$aidlink."&section=current");
	} elseif ($action == "delete" && $section == "current") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."contact_fields WHERE cf_id='$cf_id'"));
		$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order=cf_order-1 WHERE cf_order>'".$data['cf_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."contact_fields WHERE cf_id='$cf_id'");
		redirect(FUSION_SELF.$aidlink."&section=current&status=del");
	} elseif ($action == "savefield" && $section == "fields") {
		$cf_access = (isNum($_POST['cf_access']) ? $_POST['cf_access'] : "0");
		$cf_name = stripinput($_POST['cf_name']);
		$cf_must = (isNum($_POST['cf_must']) ? $_POST['cf_must'] : "0");
		$cf_readonly = (isNum($_POST['cf_readonly']) ? $_POST['cf_readonly'] : "0");
		$cf_type = (isNum($_POST['cf_type']) ? $_POST['cf_type'] : "0");
		$cf_typecheck = (isNum($_POST['cf_typecheck']) ? $_POST['cf_typecheck'] : "0");
		if ($cf_type > 2) {
			$cf_options = addslash($_POST['cf_options']);
			$cf_options_list = explode("\r\n", $cf_options);
			if ($cf_type == 3) $cf_default = (isNum($_POST['cf_default']) ? $_POST['cf_default']+1 : "");
			if ($cf_type == 4) $cf_default = (isNum($_POST['cf_default']) ? $_POST['cf_default']+1 : "");
			if ($cf_type == 5) {
				$cf_default = "";
				for ($i=0;$i < count($cf_options_list);$i++) {
					$k = $i+1;
					if ($_POST["cf_default_".$i] == 1) $cf_default .= ".".$k;
				}
			}
		} else {
			$cf_options = "";
			if ($cf_type == 1) $cf_default = stripinput($_POST['cf_default']);
			if ($cf_type == 2) $cf_default = addslash($_POST['cf_default']);
		}		
		if (isset($cf_id)) {
			$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_access='$cf_access', cf_name='$cf_name', cf_must='$cf_must', cf_type='$cf_type', cf_default='$cf_default', cf_options='$cf_options', cf_readonly='$cf_readonly', cf_typecheck='$cf_typecheck' WHERE cf_id='$cf_id'");
		} else {
			if(!$cf_order) $cf_order = dbresult(dbquery("SELECT MAX(cf_order) FROM ".$db_prefix."contact_fields"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."contact_fields SET cf_order=cf_order+1 WHERE cf_order>='$cf_order'");
			$result = dbquery("INSERT INTO ".$db_prefix."contact_fields (cf_order, cf_access, cf_name, cf_must, cf_type, cf_options, cf_default, cf_readonly, cf_typecheck) VALUES('$cf_order', '$cf_access', '$cf_name', '$cf_must', '$cf_type', '$cf_options', '$cf_default', '$cf_readonly', '$cf_typecheck')");
		}
		redirect(FUSION_SELF.$aidlink."&section=current");
	} elseif (isset($_POST['savesettings']) && $section == "settings") {
		$result = dbquery("UPDATE ".$db_prefix."contact_settings SET
		contact_access='".(isNum($_POST['contact_access']) ? $_POST['contact_access'] : "0")."',
		contact_title='".stripinput($_POST['contact_title'])."',
		contact_message='".addslash(descript($_POST['contact_message']))."',
		contact_captcha_type='".stripinput($_POST['contact_captcha_type'])."',
		contact_email='".stripinput($_POST['contact_email'])."',
		contact_email_name='".stripinput($_POST['contact_email_name'])."',
		contact_email_name='".stripinput($_POST['contact_email_name'])."',
		contact_email_title='".stripinput($_POST['contact_email_title'])."',
		contact_show_username='".(isNum($_POST['contact_show_username']) ? $_POST['contact_show_username'] : "0")."',
		contact_show_ip='".(isNum($_POST['contact_show_ip']) ? $_POST['contact_show_ip'] : "0")."',
		contact_bad_words_enabled='".(isNum($_POST['contact_bad_words_enabled']) ? $_POST['contact_bad_words_enabled'] : "0")."',
		contact_bad_words='".addslash(descript($_POST['contact_bad_words']))."'
		");
		redirect(FUSION_SELF.$aidlink."&section=settings");
	}
} else {
	opentable($locale['ccf_110']);
	echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td width='33%' class=".($section == "settings" ? "tbl1" : "tbl2")." align='center'><span class='small'>".($section == "settings" ? "<b>".$locale['ccf_111']."</b>" : "<a class='small' href='".FUSION_SELF.$aidlink."&amp;section=settings'><b>".$locale['ccf_111']."</b></a>")."</span></td>\n";
	echo "<td width='34%' class=".($section == "fields" ? "tbl1" : "tbl2")." align='center'><span class='small'>".($section == "fields" ? "<b>".$locale['ccf_112']."</b>" : "<a class='small' href='".FUSION_SELF.$aidlink."&amp;section=fields'><b>".$locale['ccf_112']."</b></a>")."</span></td>\n";
	echo "<td width='33%' class=".($section == "current" ? "tbl1" : "tbl2")." align='center'><span class='small'>".($section == "current" ? "<b>".$locale['ccf_112']."</b>" : "<a class='small' href='".FUSION_SELF.$aidlink."&amp;section=current'><b>".$locale['ccf_113']."</b></a>")."</span></td>\n";
	echo "</tr>\n</table>\n";
	closetable();
	tablebreak();	
	if ($section == "current") {
		opentable($locale['ccf_119']);
		echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>\n<tr>\n";
		echo "<td class='tbl2'><b>".$locale['ccf_156']."</b></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['ccf_157']."</b></td>\n";
		echo "<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['ccf_158']."</b></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['ccf_159']."</b></td>\n</tr>\n";
		$result = dbquery("SELECT cf_id,cf_order,cf_access,cf_name,cf_must FROM ".$db_prefix."contact_fields ORDER BY cf_order");
		if (dbrows($result) != 0) {
			$i = 0; $k = 1;
			while($data = dbarray($result)) {
				echo "<tr>\n<td class='tbl1'>".$data['cf_name'];
				if ($data['cf_must'] == 1) echo " <span style='color:#ff0000'>*</span>";
				echo "</td>\n";
				echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>";
				if ($data['cf_access'] == 0) { echo $locale['ccf_146']; } else { echo getgroupname($data['cf_access']); }
				echo "</td>\n";
				echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data['cf_order']."</td>\n";
				echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>\n";
				$up = $data['cf_order'] - 1;
				$down = $data['cf_order'] + 1;
				if ($k == 1) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;section=current&amp;action=movedown&amp;cf_order=$down&amp;cf_id=".$data['cf_id']."'><img src='".THEME."images/down.gif' alt='".$locale['ccf_158b']."' title='".$locale['ccf_158b']."' style='border:0px;'></a>\n";
				} elseif ($k < dbrows($result)) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;section=current&amp;action=moveup&amp;cf_order=$up&amp;cf_id=".$data['cf_id']."'><img src='".THEME."images/up.gif' alt='".$locale['ccf_158a']."' title='".$locale['ccf_158a']."' style='border:0px;'></a>\n";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;section=current&amp;action=movedown&amp;cf_order=$down&amp;cf_id=".$data['cf_id']."'><img src='".THEME."images/down.gif' alt='".$locale['ccf_158b']."' title='".$locale['ccf_158b']."' style='border:0px;'></a>\n";
				} else {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;section=current&amp;action=moveup&amp;cf_order=$up&amp;cf_id=".$data['cf_id']."'><img src='".THEME."images/up.gif' alt='".$locale['ccf_158a']."' title='".$locale['ccf_158a']."' style='border:0px;'></a>\n";
				}
				$k++;
				echo "</td>\n";
				echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>\n";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;section=fields&amp;cf_id=".$data['cf_id']."'>".$locale['ccf_161']."</a> - \n";
				echo "<a href='".FUSION_SELF.$aidlink."&amp;section=current&amp;action=delete&amp;cf_id=".$data['cf_id']."' onclick='return DeleteField();'>".$locale['ccf_162']."</a>\n</td>\n</tr>\n";
			}
		} else {
			echo "<tr>\n<td align='center' colspan='5' class='tbl1'>".$locale['ccf_170']."</td>\n</tr>\n";
		}
		if (dbrows($result)) echo "<tr>\n<td align='center' colspan='5' class='tbl1'>[ <a href='".FUSION_SELF.$aidlink."&amp;section=current&amp;action=refresh'>".$locale['ccf_164']."</a> ]</td>\n</tr>\n";
		echo "</table>\n";
		closetable();
		echo "<script type='text/javascript'>\n"."function DeleteField() {\n";
		echo "return confirm('".$locale['ccf_174']."');\n}\n</script>\n";
	} elseif ($section == "fields" && isset($setdefault)) {
		if (isset($cf_id)) {
			$formaction = FUSION_SELF.$aidlink."&amp;section=fields&amp;action=savefield&amp;cf_id=".$cf_id;
			opentable($locale['ccf_117']);
		} else {
			$formaction = FUSION_SELF.$aidlink."&amp;section=fields&amp;action=savefield";
			opentable($locale['ccf_116']);
		}
		echo "<form name='fieldform' method='post' action='$formaction'>\n";
		echo "<table align='center' cellpadding='0' cellspacing='0'>\n<tr>\n";
		echo "<td class='tbl' valign='top'>".$locale['ccf_147'].":</td>\n<td class='tbl'>\n";
		if (isset($cf_id)) {
			$result = dbquery("SELECT cf_default,cf_readonly FROM ".$db_prefix."contact_fields WHERE cf_id='$cf_id'");
			$data = dbarray($result);
			$cf_readonly = ($data['cf_readonly']=="1" ? " checked" : "");
		}
		$cf_name = stripinput($_POST['cf_name']);
		$cf_type = (isNum($_POST['cf_type']) ? $_POST['cf_type'] : "0");
		$cf_must = (isNum($_POST['cf_must']) ? $_POST['cf_must'] : "0");
		$cf_order = (isNum($_POST['cf_order']) ? $_POST['cf_order'] : "0");
		$cf_access = (isNum($_POST['cf_access']) ? $_POST['cf_access'] : "0");
		$cf_options = addslash($_POST['cf_options']);
		$cf_options_list = explode("\r\n", $cf_options);
		if ($cf_type == 5) {
			for ($i=0;$i < count($cf_options_list);$i++) {
				echo "<input type='checkbox' name='cf_default_$i' value='1'> ".$cf_options_list[$i]."<br>\n";
			}
		} elseif ($cf_type == 4) {
			for ($i=0;$i < count($cf_options_list);$i++) {
				echo "<input type='radio' name='cf_default' value='$i'> ".$cf_options_list[$i]."<br>\n";
			}
		} else {
			echo "<select name='cf_default' class='textbox'>\n";
			for ($i=0;$i < count($cf_options_list);$i++) {
				echo "<option value='$i'>".$cf_options_list[$i]."</option>\n";
			}
			echo "</select>\n";
		}
		echo "</td>\n</tr>\n<tr>\n";
		echo "<td align='center' colspan='2' class='tbl'>\n";
		echo "<input type='checkbox' name='cf_readonly' value='1'$cf_readonly> ".$locale['ccf_148']."\n<br><br>\n";
		echo "<input type='hidden' name='cf_name' value='$cf_name'>\n";
		echo "<input type='hidden' name='cf_type' value='$cf_type'>\n";
		echo "<input type='hidden' name='cf_must' value='$cf_must'>\n";
		echo "<input type='hidden' name='cf_access' value='$cf_access'>\n";
		echo "<input type='hidden' name='cf_options' value='$cf_options'>\n";
		if ($cf_order > 0) echo "<input type='hidden' name='cf_order' value='$cf_order'>\n";
		echo "<input type='submit' name='savefield' value='".$locale['ccf_160']."' class='button'>\n";
		echo "</td>\n</tr>\n</table>\n</form>\n";
		closetable();
	} elseif ($section == "fields" && (isset($cf_id) || isset($cf_type))) {
		if (isset($cf_id)) {
			$result = dbquery("SELECT * FROM ".$db_prefix."contact_fields WHERE cf_id='$cf_id'");
			$data = dbarray($result);
			$cf_name = $data['cf_name'];
			$cf_must = ($data['cf_must']=="1" ? " checked" : "");;
			$cf_access = $data['cf_access'];
			$cf_type = $data['cf_type'];
			$cf_readonly = ($data['cf_readonly']=="1" ? " checked" : "");
			$cf_default = $data['cf_default'];
			$cf_options = $data['cf_options'];
			$cf_typecheck = $data['cf_typecheck'];
			if ($cf_type > 2) {
				$formaction = FUSION_SELF.$aidlink."&amp;section=fields&amp;cf_id=".$data['cf_id'];
			} else {
				$formaction = FUSION_SELF.$aidlink."&amp;section=fields&amp;action=savefield&amp;cf_id=".$data['cf_id'];
			}
			opentable($locale['ccf_117']);
		} else {
			$cf_name = "";
			$cf_must = "";
			$cf_access = "0";
			$cf_order = "";
			$cf_readonly = "";
			$cf_default = "";
			$cf_options = "";
			$cf_typecheck = "";
			if ($cf_type > 2) {
				$formaction = FUSION_SELF.$aidlink."&amp;section=fields";
			} else {
				$formaction = FUSION_SELF.$aidlink."&amp;section=fields&amp;action=savefield";
			}
			opentable($locale['ccf_116']);
		}
		$access_opts = ""; $sel = "";
		$user_groups = getusergroups();
		while(list($key, $user_group) = each($user_groups)){
			$sel = ($cf_access == $user_group['0'] ? " selected" : "");
			if ($user_group['0'] == 0) $user_group['1'] = $locale['ccf_146'];
			$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
		}
		echo "<form name='fieldform' method='post' action='$formaction'>\n";
		echo "<table align='center' cellpadding='0' cellspacing='0'>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_140'].":</td>\n";
		echo "<td class='tbl'><input type='text' name='cf_name' value='$cf_name' maxlength='200' class='textbox' style='width:250px;'></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_145'].":</td>\n";
		echo "<td class='tbl'>\n<select name='cf_access' class='textbox'>\n$access_opts</select>\n";
		if (!isset($cf_id)) echo $locale['ccf_144'].": <input type='text' name='cf_order'  value='$cf_order' maxlength='2' class='textbox' style='width:40px;'>";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl'></td>\n<td class='tbl'>";
		echo "<input type='checkbox' name='cf_must' value='1'$cf_must> ".$locale['ccf_141']."</td>\n";
		if ($cf_type > 2) {
			echo "</tr>\n<tr>\n<td class='tbl' valign='top'>".$locale['ccf_143'].":<br>\n";
			echo "<span class='small2'>".$locale['ccf_175']."</span></td>\n<td class='tbl'>";
			echo "<textarea name='cf_options' rows='4' cols='35' class='textbox'>".stripslashes($cf_options)."</textarea>";
			echo "<input type='hidden' name='cf_readonly' value='1'$cf_readonly>\n";
		} else {
			echo "</tr>\n<tr>\n<td class='tbl' valign='top'>".$locale['ccf_147'].":</td>\n<td class='tbl'>";
			if ($cf_type == 1) echo "<input type='text' name='cf_default' value='$cf_default' maxlength='200' class='textbox' style='width:250px;'>";
			if ($cf_type == 2) echo "<textarea name='cf_default' rows='4' cols='35' class='textbox'>".stripslashes($cf_default)."</textarea>";
			echo "<br>\n<input type='checkbox' name='cf_readonly' value='1'$cf_readonly> ".$locale['ccf_148']."\n";
		}
		echo "</td>\n</tr>\n<tr>\n";
		if ($cf_type == "1") {
			if ($cf_typecheck == "1") $sela = " selected";
			if ($cf_typecheck == "2") $selb = " selected";
			echo "<td class='tbl'>\n".$locale['ccf_149'].":\n</td>\n<td class='tbl'>\n";
			echo "<select name='cf_typecheck' class='textbox'>\n";
			echo "<option value='0'>---</option>\n";
			echo "<option value='1'$sela>".$locale['ccf_149a']."</option>\n";
			echo "<option value='2'$selb>".$locale['ccf_149b']."</option>\n";
			echo "</select>\n</td>\n</tr>\n<tr>\n";
		}
		echo "<td align='center' colspan='2' class='tbl'>\n<br>\n";
		echo "<input type='hidden' name='cf_type' value='$cf_type'>\n";
		if ($cf_type > 2) {
			echo "<input type='submit' name='setdefault' value='".$locale['ccf_165']."' class='button'>\n";
		} else {
			echo "<input type='submit' name='savefield' value='".$locale['ccf_160']."' class='button'>\n";
		}
		echo "</td>\n</tr>\n</table>\n</form>\n";
		closetable();
	} elseif ($section == "fields") {
		opentable($locale['ccf_116']);
		echo "<form name='typeform' method='post' action='".FUSION_SELF.$aidlink."&amp;section=fields'>\n";
		echo "<center>\n".$locale['ccf_142'].":\n <select name='cf_type' class='textbox'>\n";
		echo "<option value='1'>".$locale['ccf_150']."</option>\n";
		echo "<option value='2'>".$locale['ccf_151']."</option>\n";
		echo "<option value='3'>".$locale['ccf_152']."</option>\n";
		echo "<option value='4'>".$locale['ccf_153']."</option>\n";
		echo "<option value='5'>".$locale['ccf_154']."</option>\n";
		echo "</select>\n<br><br>\n<input type='submit' name='fieldtype' value='".$locale['ccf_165']."' class='button'>\n</center>\n</form>\n";
		closetable();
	} else {
		$csettings = dbarray(dbquery("SELECT * FROM ".$db_prefix."contact_settings"));
		$contact_access = $csettings['contact_access'];
		$user_groups = getusergroups(); $access_opts = ""; $sel = "";
		while(list($key, $user_group) = each($user_groups)){
			$sel = ($contact_access == $user_group['0'] ? " selected" : "");
			$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
		}
		$show_contact_bad_words = $csettings['contact_bad_words_enabled'] == "1" ? " style='display:block'" : " style='display:none'";
		opentable($locale['ccf_115']);
		echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."&amp;section=settings&amp;action=savesettings'>\n";
		echo "<table align='center' cellpadding='0' cellspacing='0' width='500'>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_120'].":</td>\n";
		echo "<td class='tbl'>\n<select name='contact_access' class='textbox'>\n".$access_opts."</select>\n</td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_121'].":</td>\n";
		echo "<td class='tbl'><input type='text' name='contact_title' value='".$csettings['contact_title']."' maxlength='200' class='textbox' style='width:200px;'></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl' valign='top'>".$locale['ccf_122'].":</td>\n";
		echo "<td class='tbl' width='1%'><textarea name='contact_message' rows='5' cols='44' class='textbox'>".stripslashes($csettings['contact_message'])."</textarea></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_123'].":</td>\n";
		echo "<td class='tbl'>\n<select name='contact_captcha_type' class='textbox'>\n";
		echo "<option value='0'".($csettings['contact_captcha_type'] == "0" ? " selected" : "").">".$locale['ccf_130']."</option>\n";
		echo "<option value='1'".($csettings['contact_captcha_type'] == "1" ? " selected" : "").">".$locale['ccf_131']."</option>\n";
		echo "<option value='2'".($csettings['contact_captcha_type'] == "2" ? " selected" : "").">".$locale['ccf_132']."</option>\n";
		echo "</select>\n</td>\n</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_133'].":</td>\n";
		echo "<td class='tbl'><input type='checkbox' name='contact_bad_words_enabled' value='1'".($csettings['contact_bad_words_enabled'] == "1" ? " checked" : "")." onchange=\"show_contact_bad_words()\"></td>\n";
		echo "</tr>\n<tr>\n<td align='center' colspan='2'>\n";
		echo "<table cellpadding='0' cellspacing='0' width='100%' border='0' id='contact_bad_words'$show_contact_bad_words>\n<tr>";
		echo "<td class='tbl' valign='top'>".$locale['ccf_134'].":<br><span class='small2'>".$locale['ccf_135']."</span></td>\n";
		echo "<td class='tbl' width='1%'><textarea name='contact_bad_words' rows='5' cols='44' class='textbox'>".stripslashes($csettings['contact_bad_words'])."</textarea></td>\n";
		echo "</tr>\n</table>\n</td>\n";
		echo "</tr>\n<tr>\n<td colspan='2' class='tb1'><br><br><b>".$locale['ccf_124']."</b><br><br></td>\n</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_125'].":</td>\n";
		echo "<td class='tbl'><input type='text' name='contact_email' value='".$csettings['contact_email']."' maxlength='200' class='textbox' style='width:200px;'></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_126'].":</td>\n";
		echo "<td class='tbl'><input type='text' name='contact_email_name' value='".$csettings['contact_email_name']."' maxlength='200' class='textbox' style='width:200px;'></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['ccf_127'].":</td>\n";
		echo "<td class='tbl'><input type='text' name='contact_email_title' value='".$csettings['contact_email_title']."' maxlength='200' class='textbox' style='width:200px;'></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl' nowrap>".$locale['ccf_128'].":</td>\n";
		echo "<td class='tbl'><input type='checkbox' name='contact_show_username' value='1'".($csettings['contact_show_username'] == "1" ? " checked" : "")."></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl' nowrap>".$locale['ccf_129'].":</td>\n";
		echo "<td class='tbl'><input type='checkbox' name='contact_show_ip' value='1'".($csettings['contact_show_ip'] == "1" ? " checked" : "")."></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td align='center' colspan='2' class='tbl'><br><input type='submit' name='savesettings' value='".$locale['ccf_160']."' class='button'></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
		echo "<script type='text/javascript'>\n";
		echo "function show_contact_bad_words() {\n";
		echo "if (!document.settingsform.contact_bad_words_enabled.checked) {\n";
		echo "contact_bad_words.style.display = 'none';\n";
		echo "} else {\n";
		echo "contact_bad_words.style.display = 'block';\n";
		echo "}\n}\n</script>\n";
	}
}
echo "</td>\n";
require_once BASEDIR."footer.php";
?>