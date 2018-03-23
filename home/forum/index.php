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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/main.php";

if (empty($lastvisited)) { $lastvisited = time(); }


opentable($locale['413']);
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td style='padding: 4px 4px 8px 4px; text-align:center; font-size: 10px; font-weight:bold;' colspan='2' class='tbl6'>";

//View or Edit User Profile
if (iMEMBER) {

echo "<a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."'>".$locale['414']."</a>&nbsp;&nbsp;";
echo "<img border='0' src='".THEME."images/bullet.gif'>&nbsp;&nbsp;<a href='".BASEDIR."edit_profile.php'>".$locale['415']."</a>&nbsp;&nbsp;";

//Register New User
} else {
	echo "<img border='0' src='".THEME."images/bullet.gif'>&nbsp;&nbsp;<a href='".BASEDIR."register.php'>".$locale['416']."</a>&nbsp;&nbsp;";
}
//

//View Private Messages + Members
if (iMEMBER) {

	echo "<img border='0' src='".THEME."images/bullet.gif'>&nbsp;&nbsp;<a href='".BASEDIR."messages.php'>".$locale['417']."</a>&nbsp;&nbsp;";
	echo "<img border='0' src='".THEME."images/bullet.gif'>&nbsp;&nbsp;<a href='".BASEDIR."members.php'>".$locale['418']."</a>&nbsp;&nbsp;";
} else {
	echo "&nbsp;&nbsp;";
}
//



if (iMEMBER) {
	echo "<img border='0' src='".THEME."images/bullet.gif'>&nbsp;&nbsp;<a href='".BASEDIR."setuser.php?logout=yes'>".$locale['420']."</a>";
}
//
echo "</td></tr></table></td></tr></table>\n";
closetable();
tablebreak();

//Original Digi code begins here.
opentable($locale['400']);
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td colspan='2' class='tbl4'>".$locale['401']."</td>
<td align='center' width='50' class='tbl4'>".$locale['402']."</td>
<td align='center' width='50' class='tbl4'>".$locale['403']."</td>
<td width='120' class='tbl4'>".$locale['404']."</td>
</tr>\n";

$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$forums = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if (checkgroup($data2['forum_access'])) {
					$moderators = "";
					$forum_mods = ($data2['forum_moderators'] ? explode(".", $data2['forum_moderators']) : "");
					if (is_array($forum_mods)) {
						sort($forum_mods);
						for ($i=0;$i < count($forum_mods);$i++) {
							$data3 = dbarray(dbquery("SELECT user_id,user_name FROM ".$db_prefix."users WHERE user_id='".$forum_mods[$i]."'"));
							$moderators .= "<a href='".BASEDIR."profile.php?lookup=".$data3['user_id']."'>".$data3['user_name']."</a>".($i != (count($forum_mods)-1) ? ", " : "");
						}
					}
					$new_posts = dbcount("(*)", "posts", "forum_id='".$data2['forum_id']."' AND post_datestamp>'$lastvisited'");
					$thread_count = dbcount("(*)", "threads", "forum_id='".$data2['forum_id']."'");
					$posts_count = dbcount("(*)", "posts", "forum_id='".$data2['forum_id']."'");
					if ($new_posts > 0) {
						$fim = "<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."'>";
					} else {
						$fim = "<img src='".THEME."forum/folder.gif' alt='".$locale['561']."'>";
					}
	        			$forums .= "<tr>
<td align='center' class='tbl2'>$fim</td>
<td class='tbl1'><a href='viewforum.php?forum_id=".$data2['forum_id']."'>".$data2['forum_name']."</a><br>
<span class='small'>".$data2['forum_description'].($moderators ? "<br>\n".$locale['411'].$moderators."</span></td>\n" : "</span></td>\n")."
<td align='center' class='tbl2'>".$thread_count."</td>
<td align='center' class='tbl1'>".$posts_count."</td>
<td class='tbl2'>";
					if ($data2['forum_lastpost'] == 0) {
						$forums .=  $locale['405']."</td>\n</tr>\n";
					} else {
						$data3 = dbarray(dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id='".$data2['forum_lastuser']."'"));
						$forums .= showdate("forumdate", $data2['forum_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='".BASEDIR."profile.php?lookup=".$data2['forum_lastuser']."'>".$data3['user_name']."</a></span></td>
</tr>\n";
					}
				}
			}
			if ($forums != "") {
				echo "<tr>\n<td colspan='5' class='forum-caption'>".$data['forum_name']."</td>\n</tr>\n".$forums;
				unset($forums);
			}
		}
	}
}

echo "</table>
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr><td>&nbsp;</td><br>

<img src='".THEME."forum/foldernew.gif' style='vertical-align:middle;'> - ".$locale['409']."<br>
<img src='".THEME."forum/folder.gif' style='vertical-align:middle;'> - ".$locale['410']."
<table border='0' cellpadding='0' cellspacing='1' width='100%'class='tbl-border'><tr><td class='forum-caption'>&nbsp;
	<td class='forum-caption' align='left'><b>Who is Online</b>
	<tr><td class='tbl2' align='middle'>";

$filename = "".THEME."forum/whosonline.gif";
if (file_exists($filename)) {
	echo "<img src=".$filename." align='left'>";
} else {
	echo "<img src='".THEME."forum/whosonline.png' width='30'>";
}     
	echo "</td><td width='100%' align='left' class='tbl1'>";

// Online Users Panel Code
if ($settings['maintenance'] != "1") {
	$cond = ($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".FUSION_IP."'");
	$result = dbquery("SELECT * FROM ".$db_prefix."online WHERE online_user=".$cond."");
	if (dbrows($result) != 0) {
		$result = dbquery("UPDATE ".$db_prefix."online SET online_lastactive='".time()."' WHERE online_user=".$cond."");
	} else {
		$name = ($userdata['user_level'] != 0 ? $userdata['user_id'] : "0");
		$result = dbquery("INSERT INTO ".$db_prefix."online VALUES('$name', '".FUSION_IP."', '".time()."')");
	}
	if (isset($_POST['login'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".FUSION_IP."'");
	} else if (isset($logout)) {
		$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".FUSION_IP."'");
	}
	$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_lastactive<".(time()-60)."");
	$result = dbquery("SELECT * FROM ".$db_prefix."online WHERE online_user='0'");
	echo "<span class='small'>Today is: ".ucwords(showdate($settings['subheaderdate'], time()))."</span><br>";
	echo "<span class='small'>There are currently <b>".dbrows($result)."</b> ".$locale['011']."</span><br>\n";
	$result = dbquery(
		"SELECT ton.*, user_id,user_name FROM ".$db_prefix."online ton
		LEFT JOIN ".$db_prefix."users tu ON ton.online_user=tu.user_id
		WHERE online_user!='0'"
	);

// This code reads the amount of Forum Post from the Database
	$facount = dbquery("SELECT count(post_id) FROM ".$db_prefix."posts");
	echo "<span class='small'>Our users have posted a total of: </span><span class='small'><b>".dbresult($facount, 0)."</b> Articles<br>";
// Ends Forum Post code

	$members = dbrows($result);
	if ($members != 0) {
		$i = 1;
		$locale['012'];
		while($data = dbarray($result)) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a>";
			if ($i != $members) echo ", ";
			$i++;
		}
		echo "<br>\n";
	} else {
		echo $locale['013']."<br>\n";
	}
	$result = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users ORDER BY user_joined DESC");
	$total = dbrows($result);
	$data = dbarray($result);
	echo "<br><span class='small'>Registered Users: <b>".$total."</b><br>
	".$locale['016']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a></span>\n";
}
// End Online Users Panel Code

	echo "<table class='tbl' border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr><td><hr>";
if (iMEMBER) {
	echo "<div align='left'><img border='0' src='".THEME."images/bullet.gif'> <strong><a href='".BASEDIR."infusions/forum_threads_list_panel/allthreads.php'>View all Posts</a></strong></div>";
}
	echo "<div align='right'><form name='search' method='post' action='".BASEDIR."search.php?stype=f'><span class='small'>Forum Search: </span>
	<input type='textbox' name='stext' class='textbox' style='width:150px'>
	<input type='submit' name='search' value='".$locale['550']."' class='button'>
	</form></td></tr></table></div></td></tr><br>
	</td></tr></td></tr></table><br>\n";

closetable();

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>
