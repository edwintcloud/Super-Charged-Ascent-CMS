<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../../config.php";
require_once "../../maincore.php";

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
		echo "<span class='shoutboxname'><img src='".THEME."images/bullet.gif' alt=''> ";
		if ($data['user_name']) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='side'>".$data['user_name']."</a>\n";
		} else {
			echo $data['shout_name']."\n";
		}
		echo "</span><br>\n<span class='shoutboxdate'>".showdate("shortdate", $data['shout_datestamp'])."</span>";
		if (iADMIN && checkrights("S")) {
			echo "\n[<a href='".ADMIN."shoutbox.php".$aidlink."&amp;action=edit&amp;shout_id=".$data['shout_id']."' class='side'>".$locale['048']."</a>]";
		}
		echo "<br>\n<span class='shoutbox'>".parsesmileys($data['shout_message'])."</span><br>\n";
		if ($i != $numrows) echo "<br>\n";
	}
	if ($numrows > $settings['numofshouts']) {
		echo "<center>\n<img src='".THEME."images/bullet.gif' alt=''>
<a href='".INFUSIONS."shoutbox_panel/shoutbox_archive.php' class='side'>".$locale['126']."</a>
<img src='".THEME."images/bulletb.gif' alt=''></center>\n";
	}
} else {
	echo "<div align='left'>".$locale['127']."</div>\n";
}
?>