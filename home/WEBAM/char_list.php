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
require_once("defs/id_tab.php");

//  BROWSE  CHARS
function browse_chars() {
 global $output,$dbhost, $dbuser, $dbpass, $acct_db, $char_db, $itemperpage, $user_lvl,$user_name;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
 
 if(isset($_GET['start'])) $start = quote_smart($_GET['start']);
	else $start = 0;

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "guid";
 
 //get total number of items
 $query_1 = mysql_query("SELECT count(*) FROM `characters`") or die(error(mysql_error()));
 $data_1 = mysql_fetch_row($query_1)or die(error(mysql_error()));
 $all_record = $data_1[0];

 $sql = "SELECT guid,name,acct,positionx,positiony,mapId,online,level,race,class,honorpoints FROM `characters` ORDER BY $order_by ASC LIMIT $start, $itemperpage";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $this_page = mysql_num_rows($query) or die(error("No records found!"));

//top page navigaion starts here
  $output .="<center><table class=\"top_hidden\">
          <tr><td>"; 

 $index_url = "char_list.php?action=browse_chars&amp;order_by=$order_by";
 $paging = generate_pagination($index_url, $all_record, $itemperpage, $start);

		makebutton("CleanUp", "cleanup.php", "70");
		makebutton("Back", "javascript:window.history.back()", "60");
		
  $output .= "<form action=\"char_list.php\" method=\"GET\">
	   <input type=\"hidden\" name=\"action\" value=\"search\" />
	   <input type=\"hidden\" name=\"error\" value=\"3\" />
	   <input type=\"text\" size=\"12\" maxlength=\"50\" name=\"search_value\" />
	   <select name=\"search_by\">
		<option value=\"name\">by Name</option>
		<option value=\"acct\">by Account</option>
		<option value=\"level\">by Level</option>
		<option value=\"guild\">by Guild</option>
		<option value=\"race\">by Race id</option>
		<option value=\"class\">by Class id</option>
		<option value=\"mapid\">by Map id</option>
		<option value=\"honorpoints\">by H.Standing</option>
		<option value=\"online\">by Online</option>
	   </select>
	   <input type=\"submit\" value=\"Search\"/ onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>
	</td>";	
  $output .= "<td align=\"right\">";

 if (!empty($paging))  $output .= " | ";
  $output .= $paging;
  $output .= "</td></tr></table>";
//top page navigaion ENDS here

 $output .= "<form method=\"GET\" action=\"char_list.php\">
	<input type=\"hidden\" name=\"action\" value=\"del_char\" />
	<input type=\"hidden\" name=\"start\" value=\"$start\" />
 
  <table class=\"lined\">
   <tr> 
	<td width=\"5%\" class=\"head\">Delete</td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=guid\" class=\"head_link\">GUID</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=acct\" class=\"head_link\">Acct ID</a></td>
	<td width=\"15%\" class=\"head\">Account</td>
	<td width=\"15%\" class=\"head\"><a href=\"char_list.php?order_by=name\" class=\"head_link\">Character Name</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=race\" class=\"head_link\">Race</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=class\" class=\"head_link\">Class</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=level\" class=\"head_link\">Level</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"char_list.php?order_by=mapid\" class=\"head_link\">Map</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=honorpoints\" class=\"head_link\">Honor</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?order_by=online\" class=\"head_link\">Online</a></td>
  </tr>";

 if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

 for ($i=1; $i<=$looping; $i++)	{
    $char = mysql_fetch_row($query) or die(error("No Users Found!"));
 //restrict gm access viewing accounts of other gms...admin can edit his account via edit in main panel
	$owner_acc_id = $char[2];
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	$sql = "SELECT gm,login FROM accounts WHERE acct ='$owner_acc_id'";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$owner_gmlvl = mysql_result($result, 0, 'gm');
	$owner_acc_name = mysql_result($result, 0, 'login');

	if (($user_lvl >= $owner_gmlvl)||($owner_acc_name == $user_name)){
		 $output .= "<tr>";
		 if (($user_lvl > $owner_gmlvl)||($owner_acc_name == $user_name))$output .= "
   		    <td><input type=\"checkbox\" name=\"check[]\" value=\"$char[0]\" /></td>";
			else $output .= "<td></td>";
			$output .= "
   		<td>$char[0]</td>
   		<td>$char[2]</td>
			<td><a href=\"user.php?action=search&amp;error=3&amp;search_value=$owner_acc_name&amp;search_by=login\">$owner_acc_name</a></td>
			<td><a href=\"char.php?id=$char[0]\">$char[1]</a></td>
			<td>".get_player_race($char[8])."</td>
			<td>".get_player_class($char[9])."</td>
			<td>$char[7]</td>
			<td>".get_map_name($char[5])."</td>     
			<td>$char[10]</td>
			<td>$char[6]</td>
             </tr>";			//<td>".get_zone_name($char[3]posx, $char[4]posy, $char[5]map)."</td>
             
	}else{
		 $output .= "<tr><td>*</td><td>***</td><td>You</td><td>Have</td><td>No</td>
			<td class=\"small\">Permission</td><td>to</td><td>View</td><td>this</td><td>Data</td><td>***</td></tr>";
		}
}

$output .= "<tr><td colspan=\"12\" class=\"hidden\"><br/></td></tr>
	<tr>
		<td colspan=\"5\" align=\"left\" class=\"hidden\">
        <input type=\"submit\" value=\"Delete Checked Character(s)\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	  </td>
      <td colspan=\"6\" align=\"right\" class=\"hidden\">Total Characters : $all_record</td>
	 </tr>
 </table>
 </form><br/></center>";
 
 mysql_close();
}

//  SEARCH
function search() {
 global  $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $itemperpage, $user_lvl,$user_name;

 if(!isset($_GET['search_value'])) {
	header("Location: char_list.php?error=2");
	exit();
	}

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 $search_value = quote_smart($_GET['search_value']);

 if(isset($_GET['search_by']))  $search_by = quote_smart($_GET['search_by']);
	else $search_by = "name";

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "guid";
 
 switch ($search_by){
 //need to get the acc id from other table since input comes as name
 case "account":
 {
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	$sql = "SELECT acct FROM accounts WHERE login LIKE '%$search_value%' LIMIT 100";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$tot_found = mysql_num_rows($result);

	//acc = 0 added just in case there will be NO result
	$where_out = "account = 0 OR ";
	for ($i=1; $i<=$tot_found; $i++){
		 $acc = mysql_fetch_row($result) or die(error(mysql_error()));
		 $where_out .= "account = $acc[0] OR ";
	}
	//todo find abetter way dealing with " OR " in end of string
	$where_out[strlen($where_out)-1] = " ";
	$where_out[strlen($where_out)-2] = " ";
	$where_out[strlen($where_out)-3] = " ";

	$sql = "SELECT guid,name,acct,positionx,positiony,mapId,online FROM `characters` WHERE acct = '$where_out' ORDER BY '$order_by' LIMIT 100";
 }
 break;

 //in case of lvl we need to actualy extract the char's lvl from its data
 case "level":
 {
	$sql = "SELECT guid,name,acct,positionx,positiony,mapId,online FROM `characters` WHERE level = '$search_value' ORDER BY '$order_by' LIMIT 100";
 }
 break;

 case "guild":
 {
	mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	$sql = "SELECT guildid FROM guilds WHERE guildname LIKE '%$search_value%'";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$guildid = mysql_result($result, 0, 'guildid');

	$sql = "SELECT playerid FROM guild_data WHERE guildid = $guildid";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$total_chars = mysql_num_rows($result);
	
	//guid = 0 added just in case there will be NO result
	$where_out = "guid = 0 OR ";
	for ($i=1; $i<=$total_chars; $i++){
		$char = mysql_fetch_row($result) or die(error(mysql_error()));
		$where_out .= "guid = $char[0] OR ";
	}

	//todo find abetter way dealing with " OR " in end of string
	$where_out[strlen($where_out)-1] = " ";
	$where_out[strlen($where_out)-2] = " ";
	$where_out[strlen($where_out)-3] = " ";

	$sql = "SELECT guid,name,acct,positionx,positiony,mapId,online FROM `characters` WHERE guildid = '$where_out' ORDER BY '$order_by' LIMIT 100";
 }
 break;
 
 default:
    $sql = "SELECT guid,name,acct,positionx,positiony,mapId,online FROM `characters` WHERE $search_by LIKE '%$search_value%' ORDER BY '$order_by' LIMIT 100";
 }

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $query = mysql_query($sql) or die(error(mysql_error()));
 $total_found = mysql_num_rows($query);

//top tage navigaion starts here
  $output .="<center><table class=\"top_hidden\">
          <tr><td>";
		makebutton("Characters", "char_list.php", "130");
		makebutton("CleanUp", "cleanup.php", "120");
		makebutton("Back", "javascript:window.history.back()", "120");
  $output .= "
	   	<form action=\"char_list.php\" method=\"GET\">
	   	<input type=\"hidden\" name=\"action\" value=\"search\" />
	   	<input type=\"hidden\" name=\"error\" value=\"3\" />
	   	<input type=\"text\" size=\"18\" maxlength=\"50\" name=\"search_value\" />
	   	<select name=\"search_by\">
			<option value=\"name\">by Name</option>
			<option value=\"level\">by Level</option>
			<option value=\"guild\">by Guild</option>
			<option value=\"acct\">by Account</option>
			<option value=\"race\">by Race id</option>
			<option value=\"class\">by Class id</option>
			<option value=\"mapid\">by Map id</option>
			<option value=\"honorpoints\">by H.Standing</option>
			<option value=\"online\">by Online</option>
	   	</select>
	   	<input type=\"submit\" value=\"Search\"/ onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>
	   </td>
	</tr></table>";
//top tage navigaion ENDS here

  $output .= "
     <form method=\"GET\" action=\"char_list.php\">
	 <input type=\"hidden\" name=\"action\" value=\"del_char\" />
   <table class=\"lined\">
   <tr> 
	<td width=\"5%\" class=\"head\">Delete</td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=guid\" class=\"head_link\">ID</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=name\" class=\"head_link\">Character Name</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=acct\" class=\"head_link\">Account</a></td>
  <td width=\"8%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=race\" class=\"head_link\">Race</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=class\" class=\"head_link\">Class</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=level\" class=\"head_link\">Level</a></td>
	<td width=\"17%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=mapid\" class=\"head_link\">Map</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=honorpoints\" class=\"head_link\">H.P</a></td>
	<td width=\"8%\" class=\"head\"><a href=\"char_list.php?action=search&amp;error=3&amp;search_value=$search_value&amp;search_by=$search_by&amp;order_by=online\" class=\"head_link\">Online</a></td>
  </tr>";

 for ($i=1; $i<=$total_found; $i++){
   $char = mysql_fetch_row($query) or die(error("No Users Found!"));
 //to disalow lower lvl gm to  view accounts of other gms same or bigger lvl
 //admin can edit his account via edit in main pannel
	$owner_acc_id = $char[2];
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	$sql = "SELECT gm,login FROM accounts WHERE acct ='$owner_acc_id'";
	$result = mysql_query($sql) or die(error(mysql_error()));
	$owner_gmlvl = mysql_result($result, 0, 'gm');
	$owner_acc_name = mysql_result($result, 0, 'login');
	
 if (($user_lvl >= $owner_gmlvl)||($owner_acc_name == $user_name)){
		 $output .= "<tr>";
		 if (($user_lvl > $owner_gmlvl)||($owner_acc_name == $user_name))$output .= "
   		    <td><input type=\"checkbox\" name=\"check[]\" value=\"$char[0]\" /></td>";
			else $output .= "<td></td>";
		$output .= "
   	    <td>$char[0]</td>
        <td><a href=\"char.php?id=$char[0]\">$char[1]</a></td>
		<td><a href=\"user.php?action=search&amp;error=3&amp;search_value=$owner_acc_name&amp;search_by=username\">$owner_acc_name</a></td>
		<td>".get_player_race($char[3])."</td>
		<td>".get_player_class($char[4])."</td>
		<td>$char[10]</td>
		<td>".get_map_name($char[7])."</td>
		<td>$char[8]</td>
		<td>$char[9]</td>
       </tr>";
	}else{
		 $output .= "<tr><td>*</td><td>***</td><td>You</td><td>Have</td><td>No</td>
			<td class=\"small\">Permission</td><td>to</td><td>View</td><td>this</td><td>Data</td><td>***</td></tr>";
	}
}

$output .= "<tr><td colspan=\"12\" class=\"hidden\"><br/></td></tr>
	<tr>
		<td colspan=\"5\" align=\"left\" class=\"hidden\">
        <input type=\"submit\" value=\"Delete Checked Character(s)\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	  </td>
      <td colspan=\"6\" align=\"right\" class=\"hidden\">Total Found : $total_found : limit 100</td>
	 </tr>
 </table>
 </form><br/></center>";

 mysql_close();
}

//  DELETE CHAR
function del_char() {
global  $output;
 if(isset($_GET['check'])) $check = $_GET['check'];
	else {
			header("Location: char_list.php?error=1");
			exit();
			}

  $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
  $output .= "<font class=\"bold\">Character(s) id(s): ";

 //this array needed to pass multiple values from check boxes down to delete by post method
 $pass_array = "";

  for ($i=0; $i<count($check); $i++){
	$output .= $check[$i].", ";
	$pass_array .= "&amp;check%5B%5D=$check[$i]";
	}

 $output .= "<br/>Will be unrecoverble erased from DB!</font><br/><br/>";

 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("YES", "char_list.php?action=dodel_char$pass_array","120");
 $output .= "     </td>
            <td>";
		makebutton("NO", "char_list.php","120");
 $output .= "</td>
          </tr>
        </table></center>";
}

//  DO DELETE CHARS
function dodel_char() {
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_lvl, $user_name;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
 
 if(isset($_GET['check'])) $check = quote_smart($_GET['check']);
	else {
			header("Location: char_list.php?error=1");
			exit();
			}
 
 $deleted_chars = 0;

 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
//checking if current user have gm power to delete the char.
	$sql = "SELECT acct FROM `characters` WHERE guid ='$check[$i]'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$owner_acc_id = mysql_result($query, 0, 'acct');
	
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	$sql = "SELECT gm,login FROM accounts WHERE acct ='$owner_acc_id'";
	
	$query = mysql_query($sql) or die(error(mysql_error()));
	$owner_gmlvl = mysql_result($query, 0, 'gm');
	$owner_acc_name = mysql_result($query, 0, 'login');
	
	mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	
	if (($user_lvl > $owner_gmlvl)||($owner_acc_name == $user_name)){
		include("defs/del_tab.php");
		
		$sql = "SELECT entry FROM playeritems WHERE ownerguid ='$check[$i]'";
		$temp = mysql_query($sql) or die(error(mysql_error()));
		for ($k=1; $k<=mysql_num_rows($temp); $k++){
			$item_ins = mysql_fetch_row($temp) or die(error(mysql_error()));
			$sql = "DELETE FROM playeritems WHERE ownerguid = '$item_ins[0]'";
			$query = @mysql_query($sql);
		}
		
		foreach ($tab_del_user_char as $value){
			$sql = "DELETE FROM $value[0] WHERE $value[1] = '$check[$i]'";
			$query = mysql_query($sql) or die(error(mysql_error()));
			}

		$deleted_chars++;
		}
	}
}

 mysql_close();
 $output .= "<center>";
 if ($deleted_chars == 0) $output .= "<h1><font class=\"error\">No Characters deleted!</br>Permission Error?</font></h1>"; 
   else $output .= "<h1><font class=\"error\">Total <font color=blue>$deleted_chars</font> Characters deleted!</font></h1>";
 $output .= "<br/><br/>";
 $output .= "<table class=\"hidden\">
				<tr><td>";
  makebutton("Back Browsing Characters", "char_list.php", "250");
  $output .= "</td></tr>
				</table><br/></center>";
}

// MAIN
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
case 3:
   $output .= "<h1>Search Results</h1>";
   break;

default: //no error
    $output .= "<h1>Browse Characters</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "browse_chars": 
   browse_chars();
   break;
case "search": 
   search();
   break;
case "del_char": 
   del_char();
   break;
case "dodel_char": 
   dodel_char();
   break;
default:
    browse_chars();
}

require_once("footer.php");
?>