<?PHP 
include_once "settings3.php";
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright &#169; 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| PHP-Fusion Ascent Realm Status Panel by Thurgood
+----------------------------------------------------+
| Tested with Ascent's v1777+ Character DB Structure
+----------------------------------------------------+
| Place this code into a folder under Infusionss
| Make sure the folder and the filename are identical
| then add your panel. 
+----------------------------------------------------+
| http://emu.game-server-cc/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/

if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }
openside($panel_name);
$conn = mysql_connect($host,$user,$pass) or die(mysql_error());
mysql_select_db($db) or die(mysql_error());
$numonline=@mysql_num_rows(mysql_query("SELECT NULL FROM `characters` WHERE `online`='1'"));
$accdb=@mysql_num_rows(mysql_query("SELECT * FROM accounts"));
$chardb=@mysql_num_rows(mysql_query("SELECT * FROM characters"));
$guilddb=@mysql_num_rows(mysql_query("SELECT * FROM guilds"));
$arenadb=@mysql_num_rows(mysql_query("SELECT * FROM arenateams"));


$abfrage = "SELECT * FROM `characters` WHERE `online`='1'";
$result = mysql_query($abfrage);
$rows0 = mysql_num_rows($result);

$abfrage1 = "SELECT * FROM `characters` WHERE `race`='1' AND `online`='1'";
$result1 = mysql_query($abfrage1);
$rows1 = mysql_num_rows($result1);

$abfrage2 = "SELECT * FROM `characters` WHERE `race`='2' AND `online`='1'";
$result2 = mysql_query($abfrage2);
$rows2 = mysql_num_rows($result2);

$abfrage3 = "SELECT * FROM `characters` WHERE `race`='3' AND `online`='1'";
$result3 = mysql_query($abfrage3);
$rows3 = mysql_num_rows($result3);

$abfrage4 = "SELECT * FROM `characters` WHERE `race`='4' AND `online`='1'";
$result4 = mysql_query($abfrage4);
$rows4 = mysql_num_rows($result4);

$abfrage5 = "SELECT * FROM `characters` WHERE `race`='5' AND `online`='1'";
$result5 = mysql_query($abfrage5);
$rows5 = mysql_num_rows($result5);

$abfrage6 = "SELECT * FROM `characters` WHERE `race`='6' AND `online`='1'";
$result6 = mysql_query($abfrage6);
$rows6 = mysql_num_rows($result6);

$abfrage7 = "SELECT * FROM `characters` WHERE `race`='7' AND `online`='1'";
$result7 = mysql_query($abfrage7);
$rows7 = mysql_num_rows($result7);

$abfrage8 = "SELECT * FROM `characters` WHERE `race`='8' AND `online`='1'";
$result8 = mysql_query($abfrage8);
$rows8 = mysql_num_rows($result8);

$abfrage9 = "SELECT * FROM `characters` WHERE `race`='10' AND `online`='1'";
$result9 = mysql_query($abfrage9);
$rows9 = mysql_num_rows($result9);

$abfrage10 = "SELECT * FROM `characters` WHERE `race`='11' AND `online`='1'";
$result10 = mysql_query($abfrage10);
$rows10 = mysql_num_rows($result10);

$horde = $rows2+$rows5+$rows6+$rows8+$rows9;
$ally = $rows1+$rows3+$rows4+$rows7+$rows10;

//Database Connection information
$ip = $host; //IP or DNS of your LOGONSERVER
$port1 = $port; //WORLD LISTENER PORT - REALM 1
$port2 ='8128'; //WORLD LISTENER PORT - REALM 2
$port3 ='8127'; //WORLD LISTENER PORT - REALM 2

// Lets see if Realm 1 is alive
$fp = @fsockopen ($ip,$port1,$errno,$errstr, 0.5);
if ($fp)
{ print "<center></font><p><center><img src='".BASEDIR."images/realm_status/up.png'></center>"; }
else {print "<center><img src='".BASEDIR."images/realm_status/down.png'></center>"; }
@fclose($fp);

$conn = mysql_connect($host,$user,$pass) or die(mysql_error());
mysql_select_db($logondb) or die(mysql_error());
$accdb=@mysql_num_rows(mysql_query("SELECT * FROM accounts"));
echo "<b>Online Players: $numonline  <br> Alliance : <font color='blue'>$ally</font> Horde : <font color='red'>$horde </font></b><p><b>Name:</b> $realmname<br><b>Realm:</b> $realmadress  <br> <b>Patch:</b> $patch<br><b>Rates:</b> $rates <br> <b><br><u>Database Statistics:</u></b><br><b>Accounts: $accdb <br>Characters: $chardb <br>Guilds: $guilddb <br>Arena Teams: $arenadb</b>
";
?>


<?php mysql_close; ?>
<?php
mysql_connect("$db_host",
"$db_user","$db_pass");
mysql_select_db("$db_name");
?>
<?php
// Leave this line in
mysql_close;
closeside();
?>