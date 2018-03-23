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

if (!checkRights("IP")) fallback("../index.php");

if (!isset($step)) $step = "";

if ($step == "suball") {
	$result = dbquery("SELECT * FROM ".$db_prefix."users");
		while ($data = dbarray($result)) {
			$result2 = dbquery("SELECT * FROM ".$db_prefix."newsletter_subs WHERE newsletter_sub_user='".$data['user_id']."'");
			$rows = dbrows($result2);
			if ($rows == 0) { $sql = dbquery("INSERT INTO ".$db_prefix."newsletter_subs VALUES('', '".$data['user_id']."')"); }
		}
	opentable($locale['nl600']);
		echo "<center><br>
	".$locale['nl607']."<br><br>
	<a href='newsletter_subs.php'>".$locale['nl472']."</a><br><br>
	<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
	</center>\n";
	closetable();

} elseif ($step == "unsuball") {
	$result = dbquery("DELETE FROM ".$db_prefix."newsletter_subs");
		opentable($locale['nl600']);
		echo "<center><br>
	".$locale['nl608']."<br><br>
	<a href='newsletter_subs.php'>".$locale['nl472']."</a><br><br>
	<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
	</center>\n";
	closetable();
} elseif ($step == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."newsletter_subs WHERE newsletter_sub_user='$user_id'");
	opentable($locale['nl470']);
	echo "<center><br>
".$locale['nl471']."<br><br>
<a href='newsletter_subs.php'>".$locale['nl472']."</a><br><br>
<a href='".ADMIN."index.php'>".$locale['nl422']."</a><br><br>
</center>\n";
	closetable();
} else {
	opentable($locale['nl403']);
	if (!isset($sortby)) $sortby = "all";
	$orderby = ($sortby == "all" ? "" : " WHERE user_name LIKE '$sortby%'");
	$result = dbquery(
		"SELECT tns.*, tu.user_id,user_name,user_email FROM ".$db_prefix."newsletter_subs tns
		LEFT JOIN ".$db_prefix."users tu ON tns.newsletter_sub_user=tu.user_id".$orderby
	);
	$rows = dbrows($result);
	if (!isset($rowstart)) $rowstart = 0; 
	$result = dbquery(
		"SELECT tns.*, tu.user_id,user_name,user_email FROM ".$db_prefix."newsletter_subs tns
		LEFT JOIN ".$db_prefix."users tu ON tns.newsletter_sub_user=tu.user_id
		".$orderby."ORDER BY user_name LIMIT $rowstart,20"
	);
	if ($rows != 0) {
		echo "<table width='430' align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td class='tbl2'>".$locale['nl480']."</td>
<td align='right' class='tbl2'>".$locale['nl481']."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			if ($data['user_id'] != "") {
				echo "<tr>\n<td><a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>
<td align='right'><a href='".FUSION_SELF."?step=delete&rowstart=$rowstart&user_id=".$data['user_id']."' onclick='return DeleteMember()'>".$locale['nl402']."</a></td>
</tr>\n";
			} else {
				dbquery("DELETE FROM ".$db_prefix."newsletter_subs WHERE newsletter_sub_user='".$data['newsletter_sub_user']."'");
			}
		}
		echo "</table>\n";
	} else {
		if (!dbcount("(*)", "newsletter_subs")) {
			echo "<center><br>".$locale['nl482']."<br><br>\n</center>\n";
		} else {
			echo "<center><br>\n".$locale['nl483']."$sortby<br><br>\n</center>\n";
		}
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr><table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".$locale['nl484']."</a></td>";
	for ($i=0;$i < 36;$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".FUSION_SELF."?sortby=".$search[$i]."'>".$search[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".$locale['nl484']."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
	echo "</tr>\n</table>\n";
	closetable();
	echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?sortby=$sortby&")."\n</div>\n";
	echo "<script language='JavaScript'>

	function DeleteMember() {
		return confirm('".$locale['nl485']."');
	}
	</script>\n";

	tablebreak();
	opentable($locale['nl600']);

	$result = dbquery("SELECT user_id FROM ".$db_prefix."users");
	$total = dbrows($result);

	$result = dbquery("SELECT newsletter_sub_id FROM ".$db_prefix."newsletter_subs");
	$subscribed_users = dbrows($result);
	$unsubscribed_users = $total - $subscribed_users;

	// Determine which options to display
	if ($subscribed_users == "0" || $subscribed_users != $total) { 
		$options .= "<a href='$PHP_SELF?step=suball'>".$locale['nl605']."</a>";
	}

	if ($unsubscribed_users == "0" || $unsubscribed_users != $total) {
		$options .= "<a href='$PHP_SELF?step=unsuball'>".$locale['nl606']."</a>";
	}

	echo "<br /><table width='430' align='center' cellpadding='0' cellspacing='0' class='tbl'>
		<tr>
		<td align='center' class='tbl2' width='20'>".$locale['nl601']."</td>
		<td align='center' class='tbl2' width='20'>".$locale['nl602']."</td>
		<td align='center' class='tbl2' width='20'>".$locale['nl604']."</td>
		<td class='tbl2' align='right'>".$locale['nl603']."</td>
		</tr>\n

		<tr>
		<td align='center' class='tbl1'>".$unsubscribed_users."</td>
		<td align='center' class='tbl1'>".$subscribed_users."</td>
		<td align='center' class='tbl1'>".$total."</td>
		<td class='tbl1' align='right'>".$options."</td>
		</tr>\n";

		echo "</table><br />\n";

	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>