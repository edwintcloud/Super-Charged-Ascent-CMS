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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

openside($locale['120']);
if (isset($_POST['post_shout'])) {
	if (iMEMBER) {
		$shout_name = $userdata['user_id'];
	} elseif ($settings['guestposts'] == "1") {
		$shout_name = trim(stripinput($_POST['shout_name']));
		$shout_name = preg_replace("(^[0-9]*)", "", $shout_name);
		if (isNum($shout_name)) $shout_name="";
	}
	$shout_message = str_replace("\n", " ", $_POST['shout_message']);
	$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
	$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
	$shout_message = trim(stripinput(censorwords($shout_message)));
	$shout_message = str_replace("\n", "<br>", $shout_message);
	
	if ($_POST['validation'] != "" && $_POST['validation'] == $_POST['validation_answer'])
	{

	if ($shout_name != "" && $shout_message != "") {
		if (dbcount("(*)", "shoutbox", "shout_message='$shout_message' AND shout_datestamp+84600>".time())) {
			header("Location: ".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."shoutbox VALUES('', '$shout_name', '$shout_message', '".time()."', '".USER_IP."')");
		}
	}
	header("Location: ".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
	}
	else {
		echo "<div style='text-align:center'>You must answer the maths question correctly for your shoutbox entry to be added. This is to prevent bots from spamming the shoutbox. We apologise for the inconvenience.<br /><br /></div>";
	}
}

// Calculate random equation and answer
$var1 = rand(1,5);
$var2 = rand(1,5);
$equation = $var1 . " + " . $var2 . ":";
$validation_answer = $var1 + $var2;

if (iMEMBER || $settings['guestposts'] == "1") {
	echo "<form name='chatform' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td colspan='2'>\n";
	if (iGUEST) {
		echo $locale['121']."<br>
<input type='text' name='shout_name' value='".$userdata['user_name']."' class='textbox' maxlength='32' style='width:140px;'><br>
".$locale['122']."<br>\n";
	}
	echo "<textarea name='shout_message' rows='4' class='textbox' style='width:140px;'></textarea>
</td>
</tr>
<tr>
<td align='right' class='tbl'>".$equation."</td>
<td class='tbl'>
<input type='text' name='validation' value='' class='textbox' style='width:50px'>
<input type='hidden' name='validation_answer' value='$validation_answer' class='textbox' style='width:50px'>
</td>
</tr>
<td><input type='submit' name='post_shout' value='".$locale['123']."' class='button'></td>
<td align='right' class='small'><a href='".INFUSIONS."shoutbox_panel/shoutboxhelp.php'>".$locale['124']."</a></td>
</tr>
</table>
</form>
<br>\n";
} else {
	echo "<center>".$locale['125']."</center><br>\n";
}
$result = dbquery("SELECT count(shout_id) FROM ".$db_prefix."shoutbox");
$numrows = dbresult($result, 0);
$result = dbquery(
	"SELECT * FROM ".$db_prefix."shoutbox LEFT JOIN ".$db_prefix."users
	ON ".$db_prefix."shoutbox.shout_name=".$db_prefix."users.user_id
	ORDER BY shout_datestamp DESC LIMIT 0,".$settings['numofshouts']
);
if (dbrows($result) != 0) {
	$i = 0;
	while ($data = dbarray($result)) {
		echo "<span class='shoutboxname'>";
		if ($data['user_name']) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='side'>".$data['user_name']."</a>\n";
		} else {
			echo "".$data['shout_name']."\n";
		}
		echo "</span><br>
<span class='shoutboxdate'>".showdate("shortdate", $data['shout_datestamp'])."</span><br>
<span class='shoutbox'>".parsesmileys($data['shout_message'])."</span><br>\n";
		if ($i != $numrows) echo "<br>\n";
	}
	if ($numrows > $settings['numofshouts']) {
		echo "<center>\n<img src='".THEME."images/bullet.gif'>
<a href='".INFUSIONS."shoutbox_panel/shoutbox_archive.php' class='side'>".$locale['126']."</a> <img src='".THEME."images/bulletb.gif'></center>\n";
	}
} else {
	echo "<div align='left'>".$locale['127']."</div>\n";
}
closeside();
?>