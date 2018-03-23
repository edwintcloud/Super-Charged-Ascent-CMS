<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 03.11.2007 inital version (1.0)
 * Author: SixSixNine
 * Copyright: SixSixNine
 * Email: *****
 * License: GNU General Public License (GPL)
 * Updated/Edited for Ascent: gmaze 
 */
 
require_once("header.php");
  valid_login(0);
require_once("defs/id_tab.php");
require_once("defs/get_lib.php");
require_once("defs/arrays.php");

if (empty($_GET['id'])) {
   header("Location: index.php");
   exit();
} 

mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

$id = quote_smart($_GET['id']);

$sq = "SELECT acct FROM `characters` WHERE guid ='$id'";
$result = mysql_query($sq) or die(error(mysql_error()));

//if we have 0 results == no user
if (mysql_num_rows($result) == 1){
//resrict by owner's gmlvl
	$owner_acc_id = mysql_result($result, 0, 'acct');
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	$sql = "SELECT gm,login FROM accounts WHERE acct ='$owner_acc_id'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$owner_gmlvl = mysql_result($query, 0, 'gm');
	$owner_name = mysql_result($query, 0, 'login');

if ($user_lvl >= $owner_gmlvl){
	mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

	$sql1 = "SELECT * FROM `characters` WHERE guid ='$id'";
	$result = mysql_query($sql1) or die(error(mysql_error()));
	$char = mysql_fetch_row($result) or die(error(mysql_error()));
	$char_data = explode(' ',$char[2]);
	 
//online status	
	if($char[35]) $online = "Online";
		else $online = "Offline";
//total time played
	$tot_time = $char[44];
	
//guild stuff
    $sql2 = "SELECT guildid FROM `guild_data` WHERE playerid ='$id'";
	  $result1 = mysql_query($sql2) or die(error(mysql_error()));
	  $gldinfo = mysql_num_rows($result1);
  if ($gldinfo >= 1) {
    $guildid = mysql_result($result1, 0, 'guildid');
		$sql3 = "SELECT guildname FROM `guilds` WHERE guildid ='$guildid'";
		$query1 = mysql_query($sql3) or die(error(mysql_error()));
		$guild_name = mysql_result($query1, 0, 'guildname');
	if ($user_lvl > 0 ) $guild_name = "<a href=\"guild.php?action=view_guild&amp;error=3&amp;guildid=$guildid\" >$guild_name</a>";
	  $sql = "SELECT guildrank FROM `guild_data` WHERE guildid ='$guildid'";
	  $result = mysql_query($sql) or die(error(mysql_error()));
    $guild_rank = mysql_result($result, 0, 'guildrank');
	if ($guild_rank){
			$sql = "SELECT rankname FROM guild_ranks WHERE guildid ='$guildid' AND rankid='$guild_rank'";
			$guild_rank_query = mysql_query($sql) or die(error(mysql_error()));
			$guild_rank = mysql_fetch_row($guild_rank_query);
			} else $guild_rank = "Guild Leader";
	} else {
		$guild_name = "none";
		$guild_rank = "none";
		}   
//end guild stuff

//total time played
	$tot_days = (int)($tot_time/86400);
	$tot_time = $tot_time - ($tot_days*86400);
	$total_hours = (int)($tot_time/3600);
	$tot_time = $tot_time - ($total_hours*3600);
	$total_min = (int)($tot_time/60);
	$tot_time = $tot_days." Days ".$total_hours." Hours ".$total_min." Min. ";
	
//print extra info only if higher gm level or is the owner
if (($user_lvl > $owner_gmlvl)||($owner_name == $user_name)){
//gold
	$total_money = $char[14];
	$money_gold = (int)($total_money/10000);
	$total_money = $total_money - ($money_gold*10000);
	$money_silver = (int)($total_money/100);
	$money_cooper = $total_money - ($money_silver*100);
	$money = $money_gold."G ".$money_silver."S ".$money_cooper."C ";
//items stuff	
  mysql_select_db($char_db) or die(error("Error - Can't open the database ! ($char_db)"));
	$sql = "SELECT slot,entry FROM playeritems WHERE ownerguid = $id ORDER BY slot";
	$query2 = mysql_query($sql) or die(error(mysql_error()));
	$total_items = mysql_num_rows($query2);
	$bag_id = 0;
	$j = 0;
	for ($i=1; $i<=$total_items; $i++){
		$slot = mysql_fetch_row($query2) or die(error(mysql_error()));
		if ($slot[0] == 0) {
			if($slot[1] >= 19 && $slot[1] <= 22) array_push($equiped_bag_id, $slot[2]);
			if($slot[1] >= 23 && $slot[1] <= 38) array_push($bag[0], $slot[3]);
			} else {
					if ($bag_id != $slot[0]) {
						foreach ($equiped_bag_id as $equiped_id){
							if ($equiped_id == $slot[0]){
								$j++;
								$bag_id = $slot[0];
								array_push($bag[$j], $slot[3]);
							}
						}
					} else array_push($bag[$j], $slot[3]);
				}
		}
//end items stuff
	
$output .= "<center>
	<table class=\"lined\">
  <tr>
    <td colspan=\"6\"><font class=\"bold\">$char[2] - ".get_player_race($char[3])." ".get_player_class($char[4])." lvl $char[7]</font><br/>$online</td>
</tr>
<tr>
 <td colspan=\"3\">".get_map_name($char[29])."</td><td colspan=\"3\">".get_zone_name($char[29], $char[25], $char[26])."</td>
</tr>
<tr>
 <td colspan=\"3\">Guild: $guild_name || Rank: $guild_rank</td><td colspan=\"3\">Honor Points: $char[77] || Kills: $char[74]</td>
</tr>
  <tr>
    <td width=\"20%\">Head<br/><a href=\"$item_datasite$char_data[368]\" target=\"_blank\">".get_item_name($char_data[368])."</a></td>
    <td colspan=\"2\">Health: $char[18]</td>
    <td colspan=\"2\">Res. Holy:</td>
    <td width=\"20%\">Gloves<br/><a href=\"$item_datasite$char_data[368]\" target=\"_blank\">".get_item_name($char_data[368])."</a></td>
  </tr>
  <tr>
    <td>Neck<br/><a href=\"$item_datasite$char_data[272]\" target=\"_blank\">".get_item_name($char_data[272])."</a></td>
    <td colspan=\"2\">Mana: $char[19]</td>
    <td colspan=\"2\">Res. Arcane:  $res_arcane</td>
    <td>Belt<br/><a href=\"$item_datasite$char_data[320]\" target=\"_blank\">".get_item_name($char_data[320])."</a></td>
  </tr>
  <tr>
    <td>Shoulder<br/><a href=\"$item_datasite$char_data[284]\" target=\"_blank\">".get_item_name($char_data[284])."</a></td>
    <td colspan=\"2\">Strength: $str</td>
    <td colspan=\"2\">Res. Fire: $res_fire</td>
    <td>Legs<br/><a href=\"$item_datasite$char_data[332]\" target=\"_blank\">".get_item_name($char_data[332])."</a></td>
  </tr>
  <tr>
    <td>Back<br/><a href=\"$item_datasite$char_data[428]\" target=\"_blank\">".get_item_name($char_data[428])."</a></td>
    <td colspan=\"2\">Agility: $agi</td>
    <td colspan=\"2\">Res. Nature: $res_nature</td>
    <td>Feet<br/><a href=\"$item_datasite$char_data[344]\" target=\"_blank\">".get_item_name($char_data[344])."</a></td>
  </tr>
  <tr>
    <td>Chest<br/><a href=\"$item_datasite$char_data[308]\" target=\"_blank\">".get_item_name($char_data[308])."</a></td>
    <td colspan=\"2\">Stamina: $sta</td>
    <td colspan=\"2\">Res. Frost: $res_frost</td>
    <td>Finger 1<br/><a href=\"$item_datasite$char_data[380]\" target=\"_blank\">".get_item_name($char_data[380])."</a></td>
  </tr>
  <tr>
    <td>Shirt<br/><a href=\"$item_datasite$char_data[296]\" target=\"_blank\">".get_item_name($char_data[296])."</a></td>
    <td colspan=\"2\">Intellect: $int</td>
    <td colspan=\"2\">Res. Shadow: $res_shadow</td>
    <td>Finger 2<br/><a href=\"$item_datasite$char_data[392]\" target=\"_blank\">".get_item_name($char_data[392])."</a></td>
  </tr>
  <tr>
    <td>Tabard<br/><a href=\"$item_datasite$char_data[476]\" target=\"_blank\">".get_item_name($char_data[476])."</a></td>
    <td colspan=\"2\">Spirit: $spi</td>
    <td colspan=\"2\">EXP: $char[8]</td>
    <td>Trinket 1<br/><a href=\"$item_datasite$char_data[404]\" target=\"_blank\">".get_item_name($char_data[404])."</a></td>
  </tr>
  <tr>
    <td>Wrist<br/><a href=\"$item_datasite$char_data[356]\" target=\"_blank\">".get_item_name($char_data[256])."</a></td>
    <td colspan=\"2\">Armour: $armour</td>
    <td colspan=\"2\">Melee Power: $attack_power<br/>Ranged Power: $range_attack_power</td>
    <td>Trinket 2<br/><a href=\"$item_datasite$char_data[416]\" target=\"_blank\">".get_item_name($char_data[416])."</a></td>
  </tr>
  <tr>
    <td></td>
    <td width=\"15%\">Main hand<br/><a href=\"$item_datasite$char_data[440]\" target=\"_blank\">".get_item_name($char_data[440])."</a></td>
    <td width=\"15%\">Off hand<br/><a href=\"$item_datasite$char_data[452]\" target=\"_blank\">".get_item_name($char_data[452])."</a></td>
    <td width=\"15%\">Ranged<br/><a href=\"$item_datasite$char_data[464]\" target=\"_blank\">".get_item_name($char_data[464])."</a></td>
    <td width=\"15%\">Ammo</td>
    <td></td>
<tr>
<td colspan=\"6\">Block : $block% | Dodge: $dodge% | Parry: $parry% | Crit: $crit% | Range Crit: $range_crit%</td>
</tr>
</table><br/>";
$output .= "
 <table class=\"lined\">
  <tr>
    <td width=\"19%\">Backpack</td>
    <td width=\"2%\" class=\"hidden\"></td>
    <td width=\"18%\">Bag 1</td>
    <td width=\"2%\" class=\"hidden\"></td>
    <td width=\"18%\">Bag 2</td>
    <td width=\"2%\" class=\"hidden\"></td>
    <td width=\"18%\">Bag 3</td>
    <td width=\"2%\" class=\"hidden\"></td>
    <td width=\"19%\">Bag 4</td>
  </tr>
  <tr>";
  
  for($t = 0; $t <= 4; $t++){
   $output .= " <td height=\"100\">";
    $count = 0;
    foreach ($bag[$t] as $item)  {
     $count++;
     $output .= "<a href=\"$item_datasite$item\" target=\"_blank\"> $item </a>";
     if (3 == $count) {
		$count = 0;
		$output .= "<br/>";
      }
     }
    $output .= "</td>";
    if ($t!=4) $output .= "<td class=\"hidden\"></td>";
	}

 $output .= "</tr>
  <tr><td colspan=\"10\" class=\"hidden\" align=\"right\">Gold: $money</td></tr>
  <tr><td colspan=\"10\">Total Time Played: $tot_time</td></tr>
</table><br/>";

$output .= "
 <table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Back", "javascript:window.history.back()","200");
 $output .= "</td>";
		if ($user_lvl > $owner_gmlvl){
			$output .= "<td>";
			makebutton("Character's Account", "user.php?action=edit_user&amp;acct=$owner_acc_id","200");
			$output .= "</td>";
			}
		if (($user_lvl > 0)&&(($user_lvl > $owner_gmlvl)||($owner_name == $user_name))){
			$output .= "<td>";
			makebutton("Delete Character", "char_list.php?action=del_char&amp;check%5B%5D=$id","200");
			$output .= "</td>";
			}
 $output .= "</tr>
        </table><br/></center>";
        
	//end of admin options
	} else {
			$output .= "<table class=\"hidden\">
				<tr><td>";
				makebutton("Back", "javascript:window.history.back()","200");
			$output .= "</td></tr>
				</table><br/></center>";
			}

 //case of non auth request
 } else {
		mysql_close();
		header("Location: index.php");
		exit();
		}

} else error("No Character Found!");
mysql_close();

require_once("footer.php");
?>