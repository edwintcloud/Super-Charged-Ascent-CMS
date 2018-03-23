<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
|	Enhanced Members List - Shedrock 2007 (c)
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."e_members.php";

opentable($locale['em000']);
if (iMEMBER) {
	if (!isset($sortby) || !preg_match("/^[A-Z]$/", $sortby)) $sortby = "all";
	$orderby = ($sortby == "all" ? "" : " WHERE user_name LIKE '".stripinput($sortby)."%'");
	$result = dbquery("SELECT * FROM ".$db_prefix."users".$orderby."");
	$rows = dbrows($result);
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$i = 0;
		echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
<tr>
<td align='center' colspan='8' width='16%' class='forum-caption'><b>".$locale['em001']."</b></td>

<td align='center' width='12%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em002']."</b></td>
<td align='center' width='16%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em003']."</b></td>
<td align='center' width='12%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em004']."</b></td>
<td align='center' width='19%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em005']."</b></td>
<td align='center' width='12%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em006']."</b></td>
<td align='center' width='6%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em007']."</b></td>
<td align='center' width='7%' class='forum-caption' style='white-space:nowrap'><b>".$locale['em008']."</b></td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$db_prefix."users".$orderby." ORDER BY user_level DESC, user_name LIMIT $rowstart,20");
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
		echo "<tr>\n

			<td colspan='8' class='$cell_color'>\n<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n
			
			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>";
	if ($data['user_id'] != $userdata['user_id']) {
		echo " <a href='messages.php?msg_send=".$data['user_id']."' title='".$locale['em009']."'><img alt='' src='".THEME."forum/pm.gif' style='border:0px;'></a>\n";
	}
			echo "</td>
			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>

			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>";
	if ($data['user_hide_email'] != "1" || iADMIN) {

$filename = "".THEME."forum/email.gif";

	if (file_exists($filename)) {
		echo "<a href='mailto:".str_replace("@","&#64;",$data['user_email'])."' title='".str_replace("@","&#64;",$data['user_email'])."'><img src=".$filename." alt='".$data['user_email']."' border='0'></a> ";
	}else{
		echo "<a href='mailto:".str_replace("@","&#64;",$data['user_email'])."' title='".str_replace("@","&#64;",$data['user_email'])."'>".$locale['em004']."</a>\n";
	}
}
		echo "</td>
			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap' title='".$data['user_location']."'>".trimlink($data['user_location'] ? $data['user_location'] : $locale['em010'], 20)."</td>
			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'> ".showdate("%b %d %Y", $data['user_joined'])."</td>
			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".number_format(dbcount("(post_id)", "posts", "post_author='".$data['user_id']."'"))."</td>
			<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>";
	if ($data['user_web']) {
		if ($data['user_web']) {
			if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; } else { $urlprefix = ""; }
		echo "<a href='".$urlprefix."".$data['user_web']."' target='_blank'><img src='".THEME."forum/web.gif' alt='".$data['user_web']."' style='border:0px;'></a>";
	}else{
		$urlprefix = !strstr($data['user_web'], "http://") ? "http://" : "";
		echo "[<a href='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' target='_blank'>".$locale['em008']."</a>]\n";
	}
}
		echo "</td>\n</tr>";
		}
		echo "</table>\n"; 
	} else {
		echo "<center><br>\n".$locale['em013']."$sortby<br><br>\n</center>\n";
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr>\n<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".$locale['em011']."</a></td>";
	for ($i=0;$i < 36!="";$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".FUSION_SELF."?sortby=".$search[$i]."'>".$search[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".$locale['em011']."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
		echo "</tr>\n</table>\n";
} else {
		echo "<center><br>\n".$locale['em012']."<br><br>\n</center>\n";
}

$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order ASC");
while ($data = dbarray($result)) {
	$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE ".groupaccess('forum_access')." AND forum_cat='".$data['forum_id']."' ORDER BY forum_order ASC");
	if (dbrows($result2)) {
		$forum_list .= "<optgroup label='".$data['forum_name']."'>\n";
		while ($data2 = dbarray($result2)) {
			$sel = ($data2['forum_id'] == $fdata['forum_id'] ? " selected" : "");
			$forum_list .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
		}
		$forum_list .= "</optgroup>\n";
	}
}
echo "<table width='100%' cellpadding='0' cellspacing='0' style='margin-top:5px;'><tr>
<td align='left' class='tbl'>
<form name='search' method='post' action='".BASEDIR."search.php?stype=m'><span class='small'>".$locale['em015']." </span>
<input type='textbox' name='stext' class='textbox' style='width:100px;'>
<input type='submit' name='search' value='".$locale['em016']."' class='button'>
</form></td></tr></table>\n";

closetable();

if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?sortby=$sortby&amp;")."\n</div>\n";

//require_once "side_right.php";
require_once "footer.php";
?>