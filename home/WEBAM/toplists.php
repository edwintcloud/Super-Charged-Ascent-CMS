<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 02.02.2008 inital version (1.75)
 * Author: gmaze
 * Copyright: gmaze
 * Email: *****
 * License: GNU General Public License (GPL)
 */
 
require_once("header.php");
  valid_login(0);
  
function honor() {
 global $output, $dbhost, $dbuser, $dbpass, $char_db, $itemperpage;
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
    if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	   else $order_by = "killslifetime";
//get total number of items
      $query = "SELECT name,honorpoints,killslifetime FROM `characters` ORDER BY $order_by DESC LIMIT $itemperpage";
      $fetch = mysql_query($query) or die(error(mysql_error()));     
      $all_record = mysql_num_rows($fetch);
//top page navigation start
        $output .= "<center><fieldset><table cellpadding=\"3\" cellspacing=\"1\">";
        $output .= "<tr><td><legend>Top Honor List</legend>";
        $output .= "</td></tr></table>";
        $output .= "<table class=\"lined\">
        <tr> 
	       <td class=\"head\">Name</td>
	       <td class=\"head\"><a href=\"toplists.php?action=honor&amp;order_by=killslifetime\" class=\"head_link\">Lifetime Kills</a></td>
	       <td class=\"head\"><a href=\"toplists.php?action=honor&amp;order_by=honorpoints\" class=\"head_link\">Honor Points</a></td>
        </tr>";
    for ($i=1; $i<=$all_record; $i++)	{
      $data = mysql_fetch_row($fetch);   
	  		$output .= "<tr>
                      <td>$data[0]</td>
			                <td>$data[2]</td>
			                <td>$data[1]</td>
                    </tr>";
    }
        $output .= "</fieldset></table></center>";
  mysql_close();
}

function played() {
 global $output, $dbhost, $dbuser, $dbpass, $char_db, $itemperpage;
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
    if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	   else $order_by = "playedtime";
//get total number of players
      $query = "SELECT name,playedtime FROM `characters` ORDER BY $order_by DESC LIMIT $itemperpage";
      $fetch = mysql_query($query) or die(error(mysql_error()));     
      $all_record = mysql_num_rows($fetch);
//top page navigation start
        $output .= "<center><fieldset><table cellpadding=\"3\" cellspacing=\"1\">";
        $output .= "<tr><td><legend>Top Players List</legend>";
        $output .= "</td></tr></table>";
        $output .= "<table class=\"lined\">
        <tr> 
	       <td class=\"head\">Name</td>
	       <td class=\"head\"><a href=\"toplists.php?action=played&amp;order_by=playedtime\" class=\"head_link\">Time Played</a></td>
        </tr>";
    for ($i=1; $i<=$all_record; $i++)	{
      $data = mysql_fetch_row($fetch);
      $tot_time = $data[1];
        //total time played
	       $tot_days = (int)($data[1]/86400);
	       $tot_time = $tot_time - ($tot_days*86400);
	       $total_hours = (int)($tot_time/3600);
	       $tot_time = $tot_time - ($total_hours*3600);
	       $total_min = (int)($tot_time/60);
	       $tot_time = $tot_days." Days ".$total_hours." Hours ".$total_min." Min. "; 
	  		$output .= "<tr>
                      <td>$data[0]</td>
			                <td>$tot_time</td>
                    </tr>";
    }
        $output .= "</fieldset></table></center>";
  mysql_close();
}

//show top guilds
function guilds() {
  global $output, $dbhost, $dbuser, $dbpass, $char_db, $itemperpage;
    mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
    mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
  if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	 else $order_by = "guildid";
//get total number of guilds
      $query = "SELECT guildid,guildname,leaderguid FROM guilds ORDER BY $order_by" or die(error(mysql_error()));
      $res = mysql_query($query)or die(error(mysql_error()));
      $all_record = mysql_num_rows($res);
//top page navigation start
        $output .="<center><table class=\"top_hidden\"><tr><td>"; 
        $output .= "</td><td align=\"right\">";
        $output .= "</td></tr></table>";
        $output .= "<table class=\"lined\">
          <tr> 
	         <td class=\"head\"><a href=\"toplists.php?action=guilds&amp;order_by=guildid\" class=\"head_link\">ID</a></td>
	         <td class=\"head\"><a href=\"toplists.php?action=guilds&amp;order_by=guildname\" class=\"head_link\">Guild Name</a></td>
	         <td class=\"head\"><a href=\"toplists.php?action=guilds&amp;order_by=leaderguid\" class=\"head_link\">Guild Leader</a></td>
	         <td class=\"head\">Total Members</td>
          </tr>";
  for ($i=1; $i<=$all_record; $i++)	{
       $data = mysql_fetch_row($res) or die(error("No Guilds Found!"));
	 		 $sql = "SELECT name FROM `characters` WHERE guid ='$data[2]'";
		   $result = mysql_query($sql) or die(error(mysql_error()));
		   $guild_leader = mysql_result($result, 0, 'name');
		   $query_2 = "SELECT playerid FROM guild_data WHERE guildid = $data[0]";
       $data_2 = mysql_query($query_2)or die(error(mysql_error()));
       $all_plyrs = mysql_num_rows($data_2);
	       $output .= "<tr>";
   		   $output .= "<td>$data[0]</td>
			     <td><a href=\"guild.php?action=view_guild&amp;error=3&amp;id=$data[0]\">$data[1]</a></td>
			     <td><a href=\"char.php?id=$data[2]\">$guild_leader</a></td>
			     <td>$all_plyrs</td>
          </tr>";
  }
         $output .= "<tr>
           <td colspan=\"6\" class=\"hidden\" align=\"right\">Total Guilds : $all_record</td>
          </tr></table></center>";
  mysql_close();
    
}

//header display
  if(isset($_GET['error'])) $err = $_GET['error'];
	 else $err = NULL;
        $output .= "<div class=\"top\">";
  switch ($err) {
    case 1:
        $output .= "<h1></h1>";
        break;
    case 2:
        $output .= "<h1></h1>";
        break;
  default: //no error
        $output .= "<h1>$realm_name Top Lists</h1>";
  }
        $output .= "</div>";
//action definitions
  if(isset($_GET['action'])) $action = $_GET['action'];
	 else $action = NULL;
  switch ($action) {
    case "honor": 
      honor();
        break;
    case "played": 
      played();
        break;
    case "guilds": 
      guilds();
        break;
default:
    honor();
}

require_once("footer.php");
?>