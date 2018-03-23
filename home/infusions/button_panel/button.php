<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright &#352; 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../../maincore.php";
if (isset($button_id)) {
$result=dbquery("SELECT * FROM ".$db_prefix."buttons WHERE button_id='$button_id'");
$data=dbarray($result);
$licz=$data['button_count']+1;
dbquery("UPDATE ".$db_prefix."buttons SET button_count=".$licz." WHERE button_id='$button_id'");
	redirect($data['button_link'], "header");
} else {
	redirect(BASEDIR."index.php", "header");
}
?>
