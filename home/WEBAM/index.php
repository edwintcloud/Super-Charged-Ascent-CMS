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
require_once("defs/config.php");
require_once("defs/id_tab.php");
require_once("defs/arrays.php");

//check for use of webam stats or stats.xml
 if ($stats){
	header("Location: index1.php");
 	exit();
 }

//print online/offline status 
$output .=  "<div class=\"top\">";
if (test_port($server,$port)) {
	$output .= "<h1><font color=\"green\">$realm_name Realm Online</font></h1>";
	$online = true;
	}else{ 
		$output .= "<h1><font class=\"error\">$realm_name Realm Offline</font></h1>";
		$online = false;
		}
$output .= "Emulator: $ascent_rev <<>> Database: $db_rev" ;
$output .=  "</div>";

//print online gm's and player's
mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
//print online gm's
  $output .= "<center><table class=\"lined\"><tr><td class=\"head\" align=\"right\">";
  $output .= "</td></tr>";
  $output .= "<tr><td align=\"right\" class=\"hidden\"></td></tr></table>";
//only run if the server is online
if ($online){
//set the order by function(note:as of adding online gm's, this needs to stay acct till I fix sorting)
	if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
		else $order_by = "acct";
//lets get the info we need from the database
		  $query = "SELECT * FROM accounts WHERE gm != ''";
	    $res = mysql_query($query) or die(error(mysql_error()));
	    $totgm = mysql_num_rows($res);
mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	  $sql = "SELECT acct FROM `characters` WHERE `online`='1'";
	  $result = mysql_query($sql) or die(error(mysql_error()));
	  $total_online = mysql_num_rows($result);
//loop through the accounts with a gm flag
  for ($x = 1; $x <= $totgm; $x++) { 
  	$raw = mysql_fetch_array($res, MYSQL_ASSOC);
  	$acctarray[$x] = $raw['acct'];
  }
//loop through accounts where character is online
	for ($y = 1; $y <= $total_online; $y++) {
		$rew = mysql_fetch_array($result, MYSQL_ASSOC);
		$acntarray[$y] = $rew['acct'];
  }
//find the intersection of the previous 2 arrays
    $sect_array = array_intersect($acctarray, $acntarray);
//now count the results in the intertsection
    $gms_online = count($sect_array);
//ouput our table header information
  if(!$gms_online) {
$output .= "<table class=\"lined\"><font class=\"bold\">GMs Online: <font color=\"red\">$gms_online</font></font>";
  } else {
$output .= "<table class=\"lined\"><font class=\"bold\">GMs Online: <font color=\"green\">$gms_online</font></font>";
  }
//select data needed to display character info
      $sql1 = "SELECT * FROM `characters` WHERE `online`='1' ORDER BY $order_by ASC";
	    $result1 = mysql_query($sql1) or die(error(mysql_error()));
//loop through it for all gms online
	for($x = 1; $x <= $gms_online; $x++)	{
		$riw = mysql_fetch_array($result1, MYSQL_ASSOC);
		$acarray[$x] = $riw['acct'];
		$guidarray[$x] = $riw['guid'];
    $namearray[$x] = $riw['name'];
    $racearray[$x] = $riw['race'];
    $classarray[$x] = $riw['class'];
    $levelarray[$x] = $riw['level'];
    $maparray[$x] = $riw['mapId'];
	}
//this is the table column header
  $output .= "<table class=\"lined\">
    <tr> 
	   <td width=\"30%\" class=\"head\">Name</td>
	   <td width=\"20%\" class=\"head\">Race</td>
	   <td width=\"20%\" class=\"head\">Class</td>
	   <td width=\"10%\" class=\"head\">Level</td>
	   <td width=\"20%\" class=\"head\">Map</td>
    </tr>";
//loop for getting data for each online gm into a form we can display
	for ($i=1; $i<=$gms_online; $i++) {
	  $acry = $acarray[$i];
		$guid = $guidarray[$i];
		$name = $namearray[$i];
		$level = $levelarray[$i];
		$race = $racearray[$i];
		$class = $classarray[$i];
		$map = $maparray[$i];
//only output data if online account is also a gm
	 if(in_array($acry,$sect_array)) {
//output actual data
  $output .= "<tr>";
  $output .= "<td><a href=\"char.php?id=$guid\">$name</a></td>
         		    <td>".get_player_race($race)."</td>
		            <td>".get_player_class($class)."</td>
		            <td>$level</td>
		            <td>".get_map_name($map)."</td>
         </tr>";
   }
  }
  $output .= "</table></center>";
  
//print online character's
  $output .= "<center><table class=\"lined\"><tr><td class=\"head\" align=\"right\">";
  $output .= "</td></tr>";
  $output .= "<tr><td align=\"right\" class=\"hidden\"></td></tr></table>";
//select data needed to display character info
      $sql1 = "SELECT guid,name,race,class,level,mapId,zoneId,honorpoints,positionx,positiony FROM `characters` WHERE `online`='1' ORDER BY $order_by ASC";
	    $result1 = mysql_query($sql1) or die(error(mysql_error()));
	    $total_online = mysql_num_rows($result1);
//loop through it for all players online        
	for($x = 1; $x <= $total_online; $x++)
	{
		$row = mysql_fetch_array($result1, MYSQL_ASSOC);
		$guidarray[$x] = $row['guid'];
    $namearray[$x] = $row['name'];
    $racearray[$x] = $row['race'];
    $classarray[$x] = $row['class'];
    $levelarray[$x] = $row['level'];
    $maparray[$x] = $row['mapId'];
    $zoneidarray[$x] = $row['zoneId'];
    $honorarray[$x] = $row['honorpoints'];
    $positionxarray[$x] = $row['positionx'];
    $positionyarray = $row['positiony'];
	}
//ouput our table header information
  if(!$total_online) {
$output .= "<font class=\"bold\">Players Online: <font color=\"red\">$total_online</font></font>";
  } else {
$output .= "<font class=\"bold\">Players Online: <font color=\"green\">$total_online</font></font>";
  }    
//this is the table column header   
  $output .= "<table class=\"lined\">
    <tr> 
	   <td width=\"20%\" class=\"head\">Name</td>
	   <td width=\"10%\" class=\"head\">Race</td>
	   <td width=\"10%\" class=\"head\">Class</td>
	   <td width=\"5%\" class=\"head\">Level</td>
	   <td width=\"20%\" class=\"head\">Map</td>
	   <td width=\"20%\" class=\"head\">Zone</td>
      <td width=\"15%\" class=\"head\">Honor</td>
    </tr>";
//loop for getting data for each online player into a form we can display
	for ($i=1; $i<=$total_online; $i++){
		$guid = $guidarray[$i];
		$name = $namearray[$i];
		$level = $levelarray[$i];
		$race = $racearray[$i];
		$class = $classarray[$i];
		$map = $maparray[$i];
		$honor = $honorarray[$i];
    $zoneid = $zoneidarray[$i];
    $positx = $positionxarray[$i];
    $posity = $positionyarray[$i];
    $zone = get_zone_name($map, $positx, $posity);
		$output .= "<tr>";
		$output .= "<td><a href=\"char.php?id=$guid\">$name</a></td>
         		    <td>".get_player_race($race)."</td>
		            <td>".get_player_class($class)."</td>
		            <td>$level</td>
		            <td>".get_map_name($map)."</td>
		            <td>$zone</td>
		            <td>$honor</td>
         </tr>";
  }
   $output .= "</table><br/></center>";
}
mysql_close();

require_once("footer.php");

?>