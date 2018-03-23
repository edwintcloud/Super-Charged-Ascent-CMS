<?
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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/main.php";

$posts_per_page = 20;

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id) || !$thread_id || !isNum($thread_id)) fallback("index.php");

$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$forum_id."'");
if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) fallback("index.php");
} else {
	fallback("index.php");
}
$can_post = checkgroup($fdata['forum_posting']);

$forum_mods = explode(".", $fdata['forum_moderators']);
if (iMEMBER && in_array($userdata['user_id'], $forum_mods)) { define("iMOD", true); } else { define("iMOD", false); }

$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
if (dbrows($result)) { $tdata = dbarray($result); } else { fallback("index.php"); }

$fcdata = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$fdata['forum_cat']."'"));

$caption = $fcdata['forum_name']." | <a href='viewforum.php?forum_id=".$fdata['forum_id']."'>".$fdata['forum_name']."</a>";;
$result = dbquery("UPDATE ".$db_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

// Calculate random equation and answer
$var1 = rand(1,5);
$var2 = rand(1,5);
$equation = $var1 . " + " . $var2 . ":";
$validation_answer = $var1 + $var2;

if (iMEMBER && $can_post && isset($_POST['postquickreply'])) {

if ($_POST['validation'] != "" && $_POST['validation'] == $_POST['validation_answer'])
	{

	$message = stripinput(censorwords($message));
	if ($message != "") {
		$sig = ($userdata['user_sig'] ? '1' :'0');
		$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
		$subject = "RE: ".$tdata['thread_subject'];
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
		$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."' WHERE thread_id='$thread_id'");
		$result = dbquery("INSERT INTO ".$db_prefix."posts VALUES('$forum_id', '$thread_id', '', '$subject', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '0', '0')");
		$newpost_id = mysql_insert_id();
		$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
		$reply_count = dbcount("(thread_id)", "posts", "thread_id='".$thread_id."'");
		$rstart = ($reply_count > $posts_per_page ? "&rowstart=".((ceil($reply_count / $posts_per_page)-1)*$posts_per_page) : "");
		fallback(FUSION_SELF."?forum_id=$forum_id&thread_id=$thread_id".$rstart."#".$newpost_id);
	}
}
else {
	$invalid = true;
	$message2 = $message = stripinput(censorwords($message));
	echo "<div style='text-align:center'><br />You must answer the maths question correctly for your forum entry to be added. This is to prevent bots from spamming the forum. We apologise for the inconvenience.<br /><br /></div>";
}

}
opentable($locale['500']);
echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='smallalt'><a href='index.php'>".$settings['sitename']."</a> | $caption</td>\n";
if (iMEMBER && $can_post) {
	echo "<td align='right'>\n";
	if (!$tdata['thread_locked']) {
		echo "<a href='post.php?action=reply&forum_id=$forum_id&thread_id=$thread_id'><img src='".THEME."forum/reply.gif' border='0'></a>\n";
	}
	echo "<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' border='0'></a></td>\n";
}
echo "</tr>
</table>\n";

$rows = dbrows(dbquery("SELECT * FROM ".$db_prefix."posts WHERE thread_id='$thread_id'"));
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,$posts_per_page,$rows,3,FUSION_SELF."?forum_id=$forum_id&thread_id=$thread_id&")."
</div>\n";

echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>\n";

if ($rows != 0) {
	$result = dbquery(
		"SELECT * FROM ".$db_prefix."posts
		LEFT JOIN ".$db_prefix."users ON ".$db_prefix."posts.post_author=".$db_prefix."users.user_id
		WHERE thread_id='$thread_id' ORDER BY post_datestamp LIMIT $rowstart,$posts_per_page"
	);
	$numrows = dbrows($result);
	$i = 0;
	while ($data = dbarray($result)) {
		$i++;
		$message = $data['post_message'];
		if ($data['post_showsig']) { $message = $message."\n\n<hr>".$data['user_sig']; }
		if ($data['post_smileys']) { $message = parsesmileys($message); }
		$message = parseubb($message);
		$message = nl2br($message);
		if ($data['post_edittime'] != "0") {
			if ($data['post_author'] != $data['post_edituser']) {
				$data2 = dbarray(dbquery("SELECT user_id,user_name FROM ".$db_prefix."users WHERE user_id='".$data['post_edituser']."'"));
				$edituser = "<a href='../profile.php?lookup=".$data2['user_id']."'>".$data2['user_name']."</a>";
			} else {
				$edituser = "<a href='../profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>";
			}
			$edittime = showdate("forumdate", $data['post_edittime']);
		}
		$is_mod = in_array($data['user_id'], $forum_mods) && $data['user_level'] < "102" ? true : false;
		echo "<tr>
<td width='145' class='tbl2'>".$locale['501']."</td>
<td class='tbl2'><a name='".$data['post_id']."'></a>".$data['post_subject']."</td>
</tr>
<tr>
<td valign='top' rowspan='3' width='145' class='tbl1'>
<a href='../profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>
<span class='alt'>".($is_mod ? $locale['userf1'] : getuserlevel($data['user_level']))."</span><br><br>\n";
		if ($data['user_avatar'] != "") {
			echo "<img src='".IMAGES."avatars/".$data['user_avatar']."'><br><br>\n";
			$height = "185";
		} else {
			$height = "70";
		}
		echo "<span class='alt'>".$locale['502']."</span> ".$data['user_posts']."<br>
<span class='alt'>".$locale['503']."</span> ".$data['user_location']."<br>
<span class='alt'>".$locale['504']."</span> ".showdate("%d.%m.%y", $data['user_joined'])."</td>
<td class='tbl1'>
<table cellspacing='0' cellpadding='0' width='100%'>
<tr>
<td>".$locale['505'].showdate("forumdate", $data['post_datestamp'])."</td>
<td align='right'>\n";
		if (iMEMBER && $can_post) {
			if (!$tdata['thread_locked']) {
				if ($userdata['user_id'] == $data['post_author'] || iMOD || iSUPERADMIN) {
					echo "<a href='post.php?action=edit&forum_id=$forum_id&thread_id=".$data['thread_id']."&post_id=".$data['post_id']."'><img src='".THEME."forum/edit.gif' border='0'></a>\n";
				}
				echo "<a href='post.php?action=reply&forum_id=$forum_id&thread_id=".$data['thread_id']."&post_id=".$data['post_id']."&quote=".$data['post_id']."'><img src='".THEME."forum/quote.gif' alt='quote' border='0'></a>\n";
				if (iMOD || iSUPERADMIN && $data['post_ip'] != "0.0.0.0" && file_exists(THEME."forum/ip.gif")) echo "<img src='".THEME."forum/ip.gif' alt='".$data['post_ip']."' title='".$data['post_ip']."' border='0'>\n";
			} else {
				if (iMOD || iSUPERADMIN) {
					echo "<a href='post.php?action=edit&forum_id=$forum_id&thread_id=".$data['thread_id']."&post_id=".$data['post_id']."'><img src='".THEME."forum/edit.gif' border='0'></a>\n";
					if ($data['post_ip'] != "0.0.0.0" && file_exists(THEME."forum/ip.gif")) echo "<img src='".THEME."forum/ip.gif' alt='".$data['post_ip']."' title='".$data['post_ip']."' border='0'>\n";
				}
			}
		}
		echo "</td>
</tr>
</table>
</td>
</tr>
<tr>
<td height='$height' valign='top' class='tbl1'>
$message";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		if (dbrows($result2) != 0) {
			$attach = dbarray($result2);
			if ($attach['attach_ext'] == ".gif" || $attach['attach_ext'] == ".jpg" || $attach['attach_ext'] == ".png") {
				echo "<br><br>
<span class='small'>".$data['user_name'].$locale['506']."</span><br><br>
<img src='".FORUM."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']."'>";
			} else {
				echo "<br><br>
<span class='small'>".$data['user_name'].$locale['507']."</span><br>
<a href='".FORUM."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']."'>".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']."</a>";
			}
		}
		if ($data['post_edittime'] != "0") {
			echo "<br>
<br>
<span class='small'>".$locale['508']."$edituser".$locale['509']."$edittime</span>";
		}
echo "</td>
</tr>
<td class='tbl1'>\n";
		if ($data['user_aim'] && file_exists(THEME."forum/aim.gif")) {
			echo "<a href='aim:goim?screenname=".str_replace(" ", "+", $data['user_aim'])."' target='_blank'><img src='".THEME."forum/aim.gif' alt='".$data['user_aim']."' border='0'></a> ";
		}
		if ($data['user_icq']) {
			echo "<a href='http://web.icq.com/wwp?Uin=".$data['user_icq']."' target='_blank'><img src='".THEME."forum/icq.gif' alt='".$data['user_icq']."' border='0'></a> ";
		}
		if ($data['user_msn']) {
			echo "<a href='mailto:$data[user_msn]'><img src='".THEME."forum/msn.gif' alt='".$data['user_msn']."' border='0'></a> ";
		}
		if ($data['user_yahoo']) {
			echo "<a href='http://uk.profiles.yahoo.com/$data[user_yahoo]' target='_blank'><img src='".THEME."forum/yahoo.gif' alt='".$data['user_yahoo']."' border='0'></a> ";
		}
		if ($data['user_web']) {
			if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; } else { $urlprefix = ""; }
			echo "<a href='".$urlprefix."".$data['user_web']."' target='_blank'><img src='".THEME."forum/web.gif' alt='".$data['user_web']."' border='0'></a> ";
		}
		echo "<a href='".BASEDIR."messages.php?msg_send=".$data['user_id']."'><img src='".THEME."forum/pm.gif' border='0'></a>
</td>
</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";

echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$posts_per_page,$rows,3,FUSION_SELF."?forum_id=$forum_id&thread_id=$thread_id&")."
</div>\n";

if (iMEMBER && $can_post) {
	echo "<div style='margin-top:5px;'>
<form name='modopts' method='post' action='options.php?forum_id=$forum_id&thread_id=$thread_id'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>\n";
	if (iMOD || iSUPERADMIN) {
		echo "<td style='text-align:left;'>
".$locale['520']."<br>
<select name='step' class='textbox'>
<option value='none'></option>
<option value='renew'>".$locale['527']."</option>
<option value='delete'>".$locale['521']."</option>\n";
		if (!$tdata['thread_locked']) { 
			echo "<option value='lock'>".$locale['522']."</option>\n";
		} else {
			echo "<option value='unlock'>".$locale['523']."</option>\n";
		}
		if (!$tdata['thread_sticky']) {
			echo "<option value='sticky'>".$locale['524']."</option>\n";
		} else {
			echo "<option value='nonsticky'>".$locale['525']."</option>\n";
		}
		echo "<option value='move'>".$locale['526']."</option>\n";
		echo "</select>
<input type='submit' name='go' value='".$locale['528']."' class='button'>
</td>\n";
	}
	echo "<td style='text-align:right;vertical-align:bottom;'>\n";
	if (!$tdata['thread_locked']) {
		echo "<a href='post.php?action=reply&forum_id=$forum_id&thread_id=$thread_id'><img src='".THEME."forum/reply.gif' border='0'></a>";
	}
	echo "&nbsp;<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' border='0'></a>
</td>
</tr>
</table>
</form>
</div>\n";
}

if (iMEMBER && $can_post && !$tdata['thread_locked']) {
	tablebreak();
	echo "<br><form name='inputform' method='post' action='".FUSION_SELF."?forum_id=$forum_id&thread_id=$thread_id'>
<table border='0' cellspacing='1' cellpadding='0' align='center' class='tbl-border'>
<tr>
<td class='tbl2'>".$locale['512']."</td>
</tr>
<tr>
<td align='center' class='tbl1'><textarea name='message' cols='80' rows='7' class='textbox'>"; if($invalid) { echo $message2; } else { $message; } echo"</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\"></td>
</tr>
<tr>
<td align='center' class='tbl2'>".$equation." <input type='text' name='validation' value='' class='textbox' style='width:250px'>
<input type='hidden' name='validation_answer' value='$validation_answer' class='textbox' style='width:250px'>
</td>
<tr>
<td align='center' class='tbl2'>".displaysmileys("message")."<br>
<input type='checkbox' name='disable_smileys' value='1'>".$locale['513']."</td>
</tr>
<tr>
<td align='center' class='tbl1'><input type='submit' name='postquickreply' value='".$locale['514']."' class='button'></td>
</tr>
</table>
</form>\n";
}
closetable();

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>