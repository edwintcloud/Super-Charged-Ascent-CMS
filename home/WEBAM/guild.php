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
valid_login(1);

//browse guilds
function browse_guilds() {
 global $output, $dbhost, $dbuser, $dbpass, $char_db, $itemperpage;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if(isset($_GET['start'])) $start = quote_smart($_GET['start']);
	else $start = 0;

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "guildid";

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

//get total number of items
 $query_1 = mysql_query("SELECT count(*) FROM guilds") or die(error(mysql_error()));
 $data_1 = mysql_fetch_row($query_1)or die(error(mysql_error()));
 $all_record = $data_1[0];

 $sql = "SELECT guildid, guildname, leaderguid, MOTD FROM guilds ORDER BY $order_by ASC LIMIT $start, $itemperpage";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $this_page = mysql_num_rows($query);

//top tage navigaion starts here
 $output .="<center><table class=\"top_hidden\">
          <tr><td>"; 

 $index_url = "guild.php?action=brows_guilds&amp;order_by=$order_by";
 $paging = generate_pagination($index_url, $all_record, $itemperpage, $start);

  $output .="<form action=\"guild.php\" method=\"get\">
	   <input type=\"hidden\" name=\"action\" value=\"search\" />
	   <input type=\"hidden\" name=\"error\" value=\"4\" />
	   <input type=\"text\" size=\"45\" name=\"search_value\" />
	   <select name=\"search_by\">
		<option value=\"guildname\">by Name</option>
		<option value=\"leaderguid\">by Guid Leader</option>
		<option value=\"guildid\">by Id</option>
	   </select>
	   <input type=\"submit\" value=\"Search\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>";

 $output .= "</td><td align=\"right\">";

 if (!empty($paging)) $output .= " | ";
 $output .= $paging;
 
 $output .= "</td></tr></table>";
//top tage navigaion ENDS here

 $output .= "<table class=\"lined\">
   <tr> 
	<td width=\"5%\" class=\"head\"><a href=\"guild.php?order_by=guildid&amp;start=$start\" class=\"head_link\">ID</a></td>
	<td width=\"25%\" class=\"head\"><a href=\"guild.php?order_by=guildname&amp;start=$start\" class=\"head_link\">Guild Name</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"guild.php?order_by=leaderguid&amp;start=$start\" class=\"head_link\">Guild Leader</a></td>
	<td width=\"40%\" class=\"head\"><a href=\"guild.php?order_by=MOTD&amp;start=$start\" class=\"head_link\">Guild MOTD</a></td>
	<td width=\"15%\" class=\"head\">Total Members</td>
   </tr>";

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

for ($i=1; $i<=$looping; $i++)	{
   $data = mysql_fetch_row($query) or die(error("No Guilds Found!"));
	
		$sql = "SELECT name FROM `characters` WHERE guid ='$data[2]'";
		$result = mysql_query($sql) or die(error(mysql_error()));
		$guild_leader = mysql_result($result, 0, 'name');
		$query_2 = "SELECT playerid FROM guild_data WHERE guildid = $data[0]" or die(error(mysql_error()));
    $data_2 = mysql_query($query_2)or die(error(mysql_error()));
    $all_plyrs = mysql_num_rows($data_2);
	
   		$output .= "<tr>";
   		$output .= "<td>$data[0]</td>
			<td><a href=\"guild.php?action=view_guild&amp;error=3&amp;id=$data[0]\">$data[1]</a></td>
			<td><a href=\"char.php?id=$data[2]\">$guild_leader</a></td>
			<td>$data[3] ...</td>
			<td>$all_plyrs</td>
            </tr>";
}

 $output .= "
     <tr>
      <td colspan=\"6\" class=\"hidden\" align=\"right\">Total Guilds : $all_record</td>
    </tr>
   </table></center>";

 mysql_close();
}

//search guilds
function search() {
 global $output, $dbhost, $dbuser, $dbpass, $char_db;

 if(!isset($_GET['search_value'])) {
	header("Location: guild.php?error=2");
	exit();
	}

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
 
 $search_value = quote_smart($_GET['search_value']);

 if(isset($_GET['search_by']))  $search_by = quote_smart($_GET['search_by']);
	else $search_by = "guildid";

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "guildid";

if ($search_by == "leaderguid") {
	$sql = "SELECT guid FROM `characters` WHERE name ='$search_value'";
	$temp = mysql_query($sql) or die(error(mysql_error()));
	$search_value = mysql_result($temp, 0, 'guid');
}

 $sql = "SELECT guildid, guildname, leaderguid, MOTD, createdate FROM guilds WHERE $search_by LIKE '%$search_value%' ORDER BY '$order_by' LIMIT 100";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $total_found = mysql_num_rows($query);

//top tage navigaion starts here
 $output .="<center><table class=\"top_hidden\">
			<tr><td>";
		makebutton("Guilds", "guild.php", "120");
		makebutton("Back", "javascript:window.history.back()", "120");
		
  $output .= "<form action=\"guild.php\" method=\"get\">
			<input type=\"hidden\" name=\"action\" value=\"search\" />
			<input type=\"hidden\" name=\"error\" value=\"4\" />
			<input type=\"text\" size=\"40\" name=\"search_value\" />
			<select name=\"search_by\">
				<option value=\"guildname\">by Name</option>
				<option value=\"leaderguid\">by Guild Leader</option>
				<option value=\"createdate\">by Create Date</option>
				<option value=\"guildid\">by Id</option>
			</select>
			<input type=\"submit\" value=\"Search\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
		</form>
	</td></tr></table>";
//top tage navigaion ENDS here

 $output .= "<table class=\"lined\">
   <tr> 
	<td width=\"5%\" class=\"head\"><a href=\"guild.php?action=search&amp;error=4&amp;order_by=guildid&amp;search_by=$search_by&amp;search_value=$search_value\" class=\"head_link\">ID</a></td>
	<td width=\"25%\" class=\"head\"><a href=\"guild.php?action=search&amp;error=4&amp;order_by=guildname&amp;search_by=$search_by&amp;search_value=$search_value\" class=\"head_link\">Guild Name</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"guild.php?action=search&amp;error=4&amp;order_by=leaderguid&amp;search_by=$search_by&amp;search_value=$search_value\" class=\"head_link\">Guild Leader</a></td>
	<td width=\"40%\" class=\"head\"><a href=\"guild.php?action=search&amp;error=4&amp;order_by=MOTD&amp;search_by=$search_by&amp;search_value=$search_value\" class=\"head_link\">Guild MOTD</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"guild.php?action=search&amp;error=4&amp;order_by=createdate&amp;search_by=$search_by&amp;search_value=$search_value\" class=\"head_link\">Create Date</a></td>
   </tr>";

for ($i=1; $i<=$total_found; $i++)	{
   $data = mysql_fetch_row($query) or die(error("No Guilds Found!"));
	
		$sql = "SELECT name FROM `characters` WHERE guid ='$data[2]'";
		$result = mysql_query($sql) or die(error(mysql_error()));
		$guild_leader = mysql_result($result, 0, 'name');
	
   		$output .= "<tr>";
   		$output .= "<td>$data[0]</td>
			<td><a href=\"guild.php?action=view_guild&amp;error=3&amp;id=$data[0]\">$data[1]</a></td>
			<td><a href=\"char.php?id=$data[2]\">$guild_leader</a></td>
			<td>$data[3] ...</td>
			<td class=\"small\">$data[4]</td>
            </tr>";
}

 $output .= "<tr>
      <td colspan=\"6\" class=\"hidden\" align=\"right\">Total Found : $total_found Limit : 100</td>
    </tr>
   </table></center>";

 mysql_close();
}

//view guilds
function view_guild() {
 global $output, $dbhost, $dbuser, $dbpass, $char_db, $user_lvl;

 if(!isset($_GET['id'])) {
	header("Location: guild.php?error=1");
	exit();
	}
	
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $guild_id = quote_smart($_GET['id']);
 
 $sql = "SELECT guildid, guildname, guildinfo, MOTD, createdate FROM guilds WHERE guildid = '$guild_id'";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $guild_data = mysql_fetch_row($query) or die(error("No Guild Found!"));

 $sql = "SELECT playerid, guildrank FROM guild_data WHERE guildid = '$guild_id' ORDER BY guildrank";
 $members = mysql_query($sql) or die(error(mysql_error()));
 $total_members = mysql_num_rows($members);

 if (!$guild_data[2]) $guild_data[2] = "none";

 $output .= "<center>
 <fieldset style=\"width: 600px;\">
	<legend>Guild</legend>
 <table class=\"lined\" style=\"width: 560px;\">
  <tr class=\"bold\">
    <td colspan=\"2\">$guild_data[1]</td>
  </tr>
  <tr>
    <td colspan=\"2\">Foundation Date: $guild_data[4]</td>
  </tr>
  <tr>
    <td colspan=\"2\">Info: $guild_data[2]</td>
  </tr>
  <tr>
    <td colspan=\"2\">MOTD: $guild_data[3]</td>
  </tr>
  <tr>
    <td colspan=\"2\">Total Members: $total_members</td>
  </tr>";
  
 for ($i=1; $i<=$total_members; $i++)	{
   $member = mysql_fetch_row($members) or die(error("No Member Found!"));
	
	$sql = "SELECT name FROM `characters` WHERE guid ='$member[0]'";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$member_name = mysql_result($result, 0, 'name');
	
	if ($member[1]){
	$sql = "SELECT rankname FROM guild_ranks WHERE guildid ='$guild_data[0]' AND rankid='$member[1]'";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$member_rank = mysql_result($result, 0, 'rankname');
	} else $member_rank = "Guild Leader";
	
   	$output .= " 
	<tr>
	<td width=\"50%\"><a href=\"char.php?id=$member[0]\">$member_name</a></td>
	<td width=\"50%\">$member_rank ($member[1])</td>
	</tr>";
}

 $output .= "</table><br/>";
  mysql_close();
  
 $output .= "
 <table class=\"hidden\">
          <tr>
            <td>";
				makebutton("Guilds", "guild.php", "272");
 $output .= "</td>
			<td>";
				makebutton("Delete Guild", "guild.php?action=del_guild&amp;id=$guild_id", "272");
 $output .= "</td></tr>
        </table>";
if ($user_lvl == 3)	{
		$output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("Back", "javascript:window.history.back()","556");
		$output .= "</td>
          </tr>
        </table>";
		}
$output .= "</fieldset></center><br/>";
}

//delete a guild
function del_guild() {
 global $output;
 if(isset($_GET['id'])) $id = $_GET['id'];
	else {
			header("Location: guild.php?error=1");
			exit();
			}

 $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
 $output .= "<font class=\"bold\">Guild id: $id ";
 $output .= "Will be unrecoverble erased from DB!</font><br/><br/>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("YES", "cleanup.php?action=docleanup&amp;type=guild&amp;check%5B%5D=$id","120");
 $output .= "</td>
            <td>";
		makebutton("NO", "guild.php?action=view_guild&amp;id=$id","120");
 $output .= "</td>
          </tr>
        </table></center><br/>";
}

//error reports
if(isset($_GET['error'])) $err = $_GET['error'];
	else $err = NULL;
	
$output .= "<div class=\"top\">";
switch ($err) {
case 1:
   $output .= "<h1><font class=\"error\">Some Fields Left Blank</font></h1>";
   break;
case 2:
   $output .= "<h1><font class=\"error\">No Search Value Passed.</font></h1>";
   break;
case 3: //keep blank
   break;
case 4:
   $output .= "<h1>Guilds Search Results:</h1>";
   break;
default: //no error
    $output .= "<h1>Browse Guilds</h1>";
}
$output .= "</div>";

//action definitions
if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "browse_guilds": 
   browse_guilds();
   break;
case "search": 
   search();
   break;
case "view_guild": 
   view_guild();
   break;
case "del_guild": 
   del_guild();
   break;
default:
    browse_guilds();
}

require_once("footer.php");
?>