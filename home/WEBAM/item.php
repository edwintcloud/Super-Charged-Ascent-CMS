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
valid_login(3);

//search page (empty display)
function search_item() {
 global $output, $dbhost, $dbuser, $dbpass, $world_db;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));

//get total number of items
 $query_1 = mysql_query("SELECT count(*) FROM items") or die(error(mysql_error()));
 $data_1 = mysql_fetch_row($query_1)or die(error(mysql_error()));
 $all_record = $data_1[0];
	
//top page navigation starts here
 $output .="<center><table class=\"top_hidden\">
          <tr><td align=\"left\">"; 
 
 makebutton("New Item", "item.php?action=add_new&error=10", "80");
 makebutton("Edit Item", "item_edit.php?action=edit_item", "80");
 makebutton("Back", "javascript:window.history.back()", "80");

 $output .= "</td>Total Items : $all_record<td align=\"right\">
    	  <form action=\"item.php\" method=\"GET\">
	   <input type=\"hidden\" name=\"action\" value=\"search\" />
	   <input type=\"hidden\" name=\"error\" value=\"3\" />
	   <input type=\"text\" size=\"18\" maxlength=\"50\" name=\"search_value\" />
	   <select name=\"search_by\">
		<option value=\"entry\">by Entry</option>
		<option value=\"name1\">by Name</option>
	   </select>
	   <input type=\"submit\" value=\"Search\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>
      </td></tr></table>
<form method=\"GET\" action=\"item.php\">
	 <input type=\"hidden\" name=\"action\" value=\"del_item\" />
	 <input type=\"hidden\" name=\"start\" value=\"$start\" />
  <table class=\"lined\">
  <tr>
	  <td width=\"7%\" class=\"head\">Delete</td>
	  <td width=\"7%\" class=\"head\"><a href=\"item.php?order_by=entry\" class=\"head_link\">Entry</a></td>
	  <td width=\"40%\" class=\"head\"><a href=\"item.php?order_by=name1\" class=\"head_link\">Name</a></td>
    <td width=\"10%\" class=\"head\"><a href=\"item.php?order_by=allowableclass\" class=\"head_link\">Class</a></td>
    <td width=\"10%\" class=\"head\"><a href=\"item.php?order_by=allowablerace\" class=\"head_link\">Race</a></td>	   
    <td width=\"7%\" class=\"head\"><a href=\"item.php?order_by=requiredlevel\" class=\"head_link\">Req.Lvl</a></td>
	  <td width=\"10%\" class=\"head\"><a href=\"item.php?order_by=itemset\" class=\"head_link\">Item Set</a></td>
	  <td width=\"9%\" class=\"head\"><a href=\"item.php?order_by=quality\" class=\"head_link\">Quality</a></td>
  </tr>
  </table></form><br/></center>";

mysql_close();

}

//search for item
function search() {
 global $output, $dbhost, $dbuser, $dbpass, $world_db;
	
 if(!isset($_GET['search_value'])) {
	header("Location: item.php?error=2");
	exit();
	}

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 $search_value = quote_smart($_GET['search_value']);
 
 if(isset($_GET['start'])) $start = quote_smart($_GET['start']);
	else $start = 0;

 if(isset($_GET['search_by']))  $search_by = quote_smart($_GET['search_by']);
	else $search_by = "name1";

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "entry";

 mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));

 $sql = "SELECT entry,name1,allowableclass,allowablerace,requiredlevel,itemset,quality FROM items WHERE $search_by LIKE '%$search_value%' ORDER BY $order_by ASC";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $total_found = mysql_num_rows($query);
 
 	$entryarray = array();
	$namearray = array();
	$classarray = array();
	$racearray = array();
	$reqlvlarray = array();
	$setarray = array();
	$qualarray = array();
  
	for($x = 1; $x <= $total_found; $x++)
	{
    $row = mysql_fetch_array($query, MYSQL_ASSOC);
		$entryarray[$x] = $row['entry'];
    $namearray[$x] = $row['name1'];
    $classarray[$x] = $row['allowableclass'];
    $racearray[$x] = $row['allowablerace'];
    $reqlvlarray[$x] = $row['requiredlevel'];
    $setarray[$x] = $row['itemset'];
    $qualarray[$x] = $row['quality'];
	}

//top page navigation starts here
 $output .="<center><table class=\"top_hidden\">
          <tr><td>"; 
 
 makebutton("Item List", "item.php", "120");
 makebutton("Back", "javascript:window.history.back()", "120");

 $output .= "</td><td align=\"right\">
  <form action=\"item.php\" method=\"GET\">
	   <input type=\"hidden\" name=\"action\" value=\"search\" />
	   <input type=\"text\" size=\"30\" maxlength=\"50\" name=\"search_value\" />
	   <select name=\"search_by\">
		<option value=\"entry\">by Entry</option>
		<option value=\"name1\">by Name</option>
		<option value=\"itemset\">by Item Set</option>
	   </select>
	   <input type=\"submit\" value=\"New Search\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>
	  </td>
	</tr>
	</table>";
//top page navigation ends here

  $output .= "<form method=\"GET\" action=\"item.php\">
			<input type=\"hidden\" name=\"action\" value=\"del_item\" />
 
 <table class=\"lined\">
   <tr> 
	<td width=\"7%\" class=\"head\">Delete</td>
	<td width=\"7%\" class=\"head\"><a href=\"item.php?action=search&error=3&search_value=$search_value&order_by=entry\" class=\"head_link\">Entry</a></td>
	<td width=\"40%\" class=\"head\"><a href=\"item.php?action=search&error=3&search_value=$search_value&order_by=name1\" class=\"head_link\">Name</a></td>
	<td width=\"10%\" class=\"head\"><a href=\"item.php?action=search&error=3&search_value=$search_value&order_by=allowableclass\" class=\"head_link\">Allow Class</a></td>
  <td width=\"10%\" class=\"head\"><a href=\"item.php?action=search&error=3&search_value=$search_value&order_by=allowablerace\" class=\"head_link\">Allow Race</a></td>
	<td width=\"7%\" class=\"head\"><a href=\"item.php?action=search&amp;error=3&search_value=$search_value&order_by=requiredlevel\" class=\"head_link\">Req Level</a></td>
	<td width=\"10%\" class=\"head\"><a href=\"item.php?action=search&amp;error=3&search_value=$search_value&order_by=itemset\" class=\"head_link\">Item Set</a></td>
	<td width=\"9%\" class=\"head\"><a href=\"item.php?action=search&amp;error=3&search_value=$search_value&order_by=quality\" class=\"head_link\">Quality</a></td>
   </tr>";

 for ($i=1; $i<=$total_found; $i++){
	  $entry = $entryarray[$i];
	  $name = $namearray[$i];
 	  $class = $classarray[$i];
	  $race = $racearray[$i];
	  $level = $reqlvlarray[$i];
	  $set = $setarray[$i];
	  $qual = $qualarray[$i];
	  
		$output .= "<tr>
	  <td><input type=\"checkbox\" name=\"check[]\" value=\"$entry\" /></td>
		<td>$entry</td>
    <td>$name</td>
    <td>$class</td>
    <td>$race</td>
    <td>$level</td>
    <td>$set</td>
		<td>$qual</td>
	 </tr>";
}

$output .= "<tr><td colspan=\"12\" class=\"hidden\"><br/></td></tr>
	<tr>
		<td colspan=\"6\" align=\"left\" class=\"hidden\">
        <input type=\"submit\" value=\"Delete Checked Item(s)\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	  </td>
      <td colspan=\"6\" align=\"right\" class=\"hidden\">Total Found : $total_found</td>
	 </tr>
 </table>
 </form><br/></center>";

mysql_close();

}

//delete items
function del_item() {
global $output;
	
 if(isset($_GET['check'])) $check = $_GET['check'];
	else {
			header("Location: item.php?error=1");
			exit();
			}

 $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
 $output .= "<font class=\"bold\">Item id(s): ";

 $pass_array = "";

 for ($i=0; $i<count($check); $i++){
	$output .= $check[$i].", ";
	$pass_array .= "&amp;check%5B%5D=$check[$i]";
	}

 $output .= "Will be unrecoverble if erased from DB!</font><br/><br/>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("YES", "item.php?action=dodel_item$pass_array","120");
 $output .= "</td>
            <td>";
		makebutton("NO", "item.php","120");
 $output .= "</td>
          </tr>
        </table></center><br/>";
}

//do delete item
function dodel_item() {
 global $output,$dbhost, $dbuser, $dbpass, $world_db;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if(isset($_GET['check'])) $check = quote_smart($_GET['check']);
	else {
			header("Location: item.php?error=1");
			exit();
			}
 
 $del_items = 0;

 mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));
 
 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
    
	 require_once("defs/del_tab.php");
		
	 mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));
	 $sql = "SELECT * FROM items WHERE entry='$check[$i]'";
	 $result = mysql_query($sql) or die(error(mysql_error()));
	 while ($row = mysql_fetch_array($result)) {
	 
		$sql = "SELECT * FROM items WHERE entry ='$row[0]'";
		$temp = mysql_query($sql) or die(error(mysql_error()));
		for ($k=1; $k<=mysql_num_rows($temp); $k++){
			$item_ins = mysql_fetch_row($temp) or die(error(mysql_error()));
			$sql = "DELETE FROM items WHERE entry = '$item_ins[0]'";
			$query = @mysql_query($sql);
		}
	}
  }
 }
 mysql_close();
 $output .= "<center>";
 if ($query == 0) $output .= "<h1><font class=\"error\">No Items Deleted!</font></h1>"; 
   else {
	$output .= "<h1><font class=\"error\">Total <font color=blue>$query</font> Items deleted!</font><br/></h1>";
	}
 $output .= "<br/><br/>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
 makebutton("Back Browsing Items", "item.php", "200");
 $output .= "</td>
          </tr>
        </table><br/></center>";
}

//prints the page for new item creation
function add_new() {
 global $output;

  $output .= "<center>
		<form method=\"POST\" action=\"doadd_new\" name=\"form1\">
		<input type=\"hidden\"name=\"type\" value=\"POST\"/>
    <div id=\"pane1\"><br />
<table class=\"lined\" style=\"width: 720px;\">
<tr><td colspan=\"8\" class=\"large_bold\" align=\"left\">General:</td></tr>
<tr>
 <td>entry:</td>
  <td><input type=\"text\" name=\"entry\" size=\"8\" maxlength=\"11\" value=\"0\" /></td>
<td>displayid:</td>
  <td><input type=\"text\" name=\"displayid\" size=\"8\" maxlength=\"11\" value=\"0\" /></td>
 <td>requiredlevel:</td>
  <td><input type=\"text\" name=\"requiredlevel\" size=\"8\" maxlength=\"4\" value=\"0\" /></td>
 <td>itemlevel:</td>
  <td><input type=\"text\" name=\"itemlevel\" size=\"8\" maxlength=\"4\" value=\"0\" /></td>
</tr>
<tr><td colspan=\"8\" class=\"large_bold\" align=\"left\">Names:</td></tr>
<tr>
 <td>Name1:</td>
  <td colspan=\"3\"><input type=\"text\" name=\"name1\" size=\"30\" maxlength=\"225\" value=\"\" /></td>
 <td>Name2:</td>
  <td colspan=\"3\"><input type=\"text\" name=\"name2\" size=\"30\" maxlength=\"225\" value=\"\" /></td>
</tr>
<tr>
 <td>Name3:</td>
  <td colspan=\"3\"><input type=\"text\" name=\"name3\" size=\"30\" maxlength=\"225\" value=\"\" /></td>
 <td>Name4:</td>
  <td colspan=\"3\"><input type=\"text\" name=\"name4\" size=\"30\" maxlength=\"225\" value=\"\" /></td>
</tr>
<tr>
  <td>description:</td>
    <td colspan=\"8\"><input type=\"text\" name=\"description\" size=\"82\" maxlength=\"225\" value=\"\" /></td>
</tr>
<tr><td colspan=\"8\" class=\"large_bold\" align=\"left\">Item Types:</td></tr>
<tr>
  <td>item classes:</td>
	 <td colspan=\"3\"><select name=\"class\">
		<option value=\"0\">0 - consumables</option>
		<option value=\"1\">1 - containers</option>
		<option value=\"2\">2 - weapons</option>
		<option value=\"3\">3 - jewels</option>
		<option value=\"4\">4 - armor</option>
		<option value=\"5\">5 - reagants</option>
		<option value=\"6\">6 - projectiles</option>
		<option value=\"7\">7 - trade goods</option>
		<option value=\"9\">9 - recipes</option>
		<option value=\"11\">11 - quivers</option>
		<option value=\"12\">12 - quests</option>
		<option value=\"13\">13 - keys</option>
		<option value=\"14\">14 - permanents?</option>
		<option value=\"15\">15 - miscellaneous</option>
	</select></td>
 <td>subclasses:</td>
   <td colspan=\"3\"><select name=\"subclass\">
  <optgroup label=\"Class 0: consumables\">
		<option value=\"0\">0 - consumable</option>
	<optgroup label=\"Class 1: containers\">
		<option value=\"0\">0 - bag</option>
		<option value=\"1\">1 - soul bag</option>
		<option value=\"2\">2 - herb bag</option>
		<option value=\"3\">3 - enchanter bag</option>
		<option value=\"4\">4 - engineer bag</option>
		<option value=\"5\">5 - jewel bag</option>
		<option value=\"6\">6 - miner bag</option>
	<optgroup label=\"Class 2: weapons\">
		<option value=\"0\">0 - axe_1hand</option>
		<option value=\"1\">1 - axe_2hand</option>
		<option value=\"2\">2 - bow</option>
		<option value=\"3\">3 - rifle</option>
		<option value=\"4\">4 - mace_1hand</option>
		<option value=\"5\">5 - mace_2hand</option>
		<option value=\"6\">6 - polearm</option>
		<option value=\"7\">7 - sword_1hand</option>
		<option value=\"8\">8 - sword_2hand</option>
		<option value=\"10\">10 - staff</option>
		<option value=\"13\">13 - fist</option>
		<option value=\"14\">14 - other</option>
		<option value=\"15\">15 - dagger</option>
		<option value=\"16\">16 - thrown</option>
		<option value=\"18\">18 - crossbow</option>
		<option value=\"19\">19 - wand</option>
		<option value=\"20\">20 - fishing pole</option>
	<optgroup label=\"Class 3: jewels\">
		<option value=\"0\">0 - red socket</option>
		<option value=\"1\">1 - blue socket</option>
		<option value=\"2\">2 - yellow socket</option>
		<option value=\"3\">3 - red or blue socket</option>
		<option value=\"4\">4 - blue or yellow socket</option>
		<option value=\"5\">5 - red or yellow socket</option>
		<option value=\"6\">6 - meta socket</option>
	<optgroup label=\"Class 4: armor\">
		<option value=\"0\">0 - miscellaneous</option>
		<option value=\"1\">1 - cloth</option>
		<option value=\"2\">2 - leather</option>
		<option value=\"3\">3 - mail</option>
		<option value=\"4\">4 - plate</option>
		<option value=\"6\">6 - shield</option>
		<option value=\"7\">7 - libram</option>
		<option value=\"8\">8 - idol</option>
		<option value=\"9\">9 - totem</option>
	<optgroup label=\"Class 5: reagents\">
		<option value=\"0\">0 - reagent</option>
	<optgroup label=\"Class 6: projectiles\">
		<option value=\"2\">2 - arrows</option>
		<option value=\"3\">3 - bullets</option>
	<optgroup label=\"Class 7: trade goods\">
		<option value=\"0\">0 - trade goods</option>
		<option value=\"1\">1 - parts</option>
		<option value=\"2\">2 - explosives</option>
		<option value=\"3\">3 - devices</option>
	<optgroup label=\"Class 9: recipes\">
		<option value=\"0\">0 - book</option>
		<option value=\"1\">1 - leatherworking</option>
		<option value=\"2\">2 - {tailoring</option>
		<option value=\"3\">3 - engineering</option>
		<option value=\"4\">4 - blacksmithing</option>
		<option value=\"5\">5 - cooking</option>
		<option value=\"6\">6 - alchemy</option>
		<option value=\"7\">7 - first_aid</option>
		<option value=\"8\">8 - enchanting</option>
		<option value=\"9\">9 - fishing</option>
		<option value=\"10\">10 - jewelcrafting</option>
	</optgroup>
	<optgroup label=\"Class 11: quivers\">
		<option value=\"2\">2 - quiver</option>
		<option value=\"3\">3 - ammo_pouch</option>
	</optgroup>
	<optgroup label=\"Class 12: quests\">
		<option value=\"0\">0 - quest</option>
	</optgroup>
	<optgroup label=\"Class 13: keys\">
		<option value=\"0\">0 - key</option>
	</optgroup>
	<optgroup label=\"Class 14: permanents\">
	 <option value=\"0\">0 - permanent</option>
  </optgroup>
	<optgroup label=\"Class 15: miscellaneous\">
	<option value=\"0\">0 - miscellaneous</option>
	</optgroup>
  </select></td>
</tr>
<tr>
  <td>item quality:</td>
	 <td colspan=\"2\"><select name=\"quality\">
		<option value=\"0\">0 - poor</option>
		<option value=\"1\">1 - common</option>
		<option value=\"2\">2 - uncommon</option>
		<option value=\"3\">3 - rare</option>
		<option value=\"4\">4 - epic</option>
		<option value=\"5\">5 - legendary</option>
		<option value=\"6\">6 - artifact</option>
	</select></td>
  <td>inventory type:</td>
	 <td colspan=\"2\"><select name=\"inventorytype\">
	  <option value=\"0\">0 - other</option>
		<option value=\"1\">1 - head</option>
		<option value=\"2\">2 - neck</option>
		<option value=\"3\">3 - shoulder</option>
		<option value=\"4\">4 - shirt</option>
		<option value=\"5\">5 - chest</option>
		<option value=\"6\">6 - belt</option>
		<option value=\"7\">7 - legs</option>
		<option value=\"8\">8 - feet</option>
		<option value=\"9\">9 - wrist</option>
		<option value=\"10\">10 - gloves</option>
		<option value=\"11\">11 - finger</option>
		<option value=\"12\">12 - trinket</option>
		<option value=\"13\">13 - one_hand</option>
		<option value=\"14\">14 - shield</option>
		<option value=\"15\">15 - bow</option>
		<option value=\"16\">16 - back</option>
		<option value=\"17\">17 - two_hand</option>
		<option value=\"18\">18 - bag</option>
		<option value=\"19\">19 - tabard</option>
		<option value=\"20\">20 - robe</option>
		<option value=\"21\">21 - main_hand</option>
		<option value=\"22\">22 - fist_weapon</option>
		<option value=\"23\">23 - off_hand</option>
		<option value=\"24\">24 - projectile</option>
		<option value=\"25\">25 - thrown</option>
		<option value=\"26\">26 - ranged</option>
		<option value=\"28\">28 - relic</option>
	</select></td>
  <td>bonding:</td>
	 <td colspan=\"3\"><select name=\"bonding\">
		<option value=\"0\">0 - none</option>
		<option value=\"1\">1 - on pick up</option>
		<option value=\"2\">2 - on equip</option>
		<option value=\"3\">3 - on use</option>
		<option value=\"4\">4 - quest item 1</option>
		<option value=\"5\">5 - quest item 2</option>
	</select></td>
</tr>
<tr>
  <td><span>itemset:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"itemset\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
  <td>flags:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"Flags\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
</tr>
</table>
</div>";

$output .= "<div id=\"pane2\">
    <table class=\"lined\" style=\"width: 720px;\">
	<tr><td colspan=\"10\" class=\"large_bold\" align=\"left\">Vendors:</td></tr>
	<tr>
	 <td>buy price:</td>
	 <td colspan=\"2\"><input type=\"text\" name=\"BuyPrice\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	 <td>sell price:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"SellPrice\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	 <td><span>extended cost:</td>
	 <td colspan=\"2\"><input type=\"text\" name=\"ItemExtendedCost\" size=\"8\" maxlength=\"30\" value=\"NULL\" /></td>
	</tr>
	<tr><td colspan=\"10\" class=\"large_bold\" align=\"left\">Containers:</td></tr>
	<tr>
	<td>bag family:</td>
	 <td colspan=\"4\"><select name=\"BagFamily\">
		<option value=\"0\">0 - none</option>
		<option value=\"1\">1 - arrows</option>
		<option value=\"2\">2 - bullets</option>
		<option value=\"3\">3 - soul shards</option>
		<option value=\"6\">6 - herbs</option>
		<option value=\"7\">7 - enchanting</option>
		<option value=\"8\">8 - engineering</option>
		<option value=\"9\">9 - keys</option>
		<option value=\"10\">10 - jewels</option>
		<option value=\"11\">11 - mining</option>
	   </select></td>
	<td>bag slots:</td>
	<td colspan=\"4\"><input type=\"text\" name=\"ContainerSlots\" size=\"10\" maxlength=\"3\" value=\"0\" /></td>
	</tr>
	<tr>
		<tr><td colspan=\"10\" class=\"large_bold\" align=\"left\">Materials:</td></tr>
	<tr>
	<td>lock:</td>
	 <td colspan=\"3\"><select name=\"lock_material\">
		<option value=\"-1\">-1 - consumable</option>
		<option value=\"-1\">0 - none</option>
		<option value=\"1\">1 - metal</option>
		<option value=\"2\">2 - wood</option>
		<option value=\"3\">3 - liquid</option>
		<option value=\"4\">4 - jewelry</option>
		<option value=\"5\">5 - chain</option>
		<option value=\"6\">6 - plate</option>
		<option value=\"7\">7 - cloth</option>
		<option value=\"8\">8 - leather</option>
	   </select></td>
	<td>page:</td>
	 <td colspan=\"3\"><select name=\"page_material\">
		<option value=\"0\">0 - none</option>
		<option value=\"1\">1 - parchment</option>
		<option value=\"2\">2 - stone</option>
		<option value=\"3\">3 - marble</option>
		<option value=\"4\">4 - silver</option>
		<option value=\"5\">5 - bronze</option>
		<option value=\"6\">6 - valentine</option>
		<option value=\"7\">7 - illidari</option>
	   </select></td>
   <td>durability:</td>
    <td><input type=\"text\" name=\"MaxDurability\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>	
<tr>
  <td>language id:</td>
	 <td colspan=\"3\"><select name=\"page_language\">
		<option value=\"0\">0 - other</option>
		<option value=\"1\">1 - unknown</option>
		<option value=\"2\">2 - unknown</option>
		<option value=\"7\">7 - common</option>
		<option value=\"8\">8 - demonic</option>
		<option value=\"11\">11 - draconic</option>
		<option value=\"35\">35 - draenei</option>
	   </select></td>	
  <td>sheath id:</td>
	 <td colspan=\"3\"><select name=\"sheathID\">
		<option value=\"0\">0 - other</option>
		<option value=\"1\">1 - 2hand weapon</option>
		<option value=\"2\">2 - staff</option>
		<option value=\"3\">3 - 1hand weapon</option>
		<option value=\"4\">4 - shield</option>
		<option value=\"5\">5 - rod</option>
		<option value=\"7\">7 - off hand</option>
	   </select></td> 
	 <td>}quest id:</td>
	 <td><input type=\"text\" name=\"quest_id\" size=\"6\" maxlength=\"5\" value=\"0\" /></td>
</tr>
<tr>
	<td>max count:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"maxcount\" size=\"6\" maxlength=\"5\" value=\"0\" /></td>
	<td>unique:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"Unique\" size=\"6\" maxlength=\"5\" value=\"0\" /></td>
	<td>lock id:</td>
	 <td><input type=\"text\" name=\"lock_id\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>randomprop:</td>
   <td colspan=\"3\"><input type=\"text\" name=\"randomprop\" size=\"6\" maxlength=\"30\" value=\"0\" /></td>
	<td>reqdisenchantskill:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"RequiredDisenchantSkill\" size=\"6\" maxlength=\"10\" value=\"-1\" /></td>
	<td>page id:</td>
   <td colspan=\"2\"><input type=\"text\" name=\"page_id\" size=\"6\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>zoneid:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"ZoneNameID\" size=\"6\" maxlength=\"10\" value=\"0\" /></td>
	<td>mapid:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"mapid\" size=\"6\" maxlength=\"10\" value=\"NULL\" /></td>
	<td>totemcategory:</td>
	 <td colspan=\"2\"><input type=\"text\" name=\"TotemCategory\" size=\"6\" maxlength=\"10\" value=\"NULL\" /></td>
</tr>
  </table>
</div>";

$output .= "<div id=\"pane3\">
<table class=\"lined\" style=\"width: 720px;\">
	<tr><td colspan=\"8\" class=\"large_bold\" align=\"left\">Stats:</td></tr>
	<tr>
	 <td>Slot1:</td>
	 <td><select name=\"stat_type1\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	 </select></td>
  <td><input type=\"text\" name=\"stat_value1\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
   <td>Slot2:</td>
	 <td><select name=\"stat_type2\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value2\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
</tr>
<tr>
	<td>Slot3:</td>
	 <td><select name=\"stat_type3\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
  <td><input type=\"text\" name=\"stat_value3\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
  <td>Slot4:</td>
	 <td><select name=\"stat_type4\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value4\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
</tr>
<tr>
	 <td>Slot5:</td>
	 <td><select name=\"stat_type5\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value5\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
	 <td>Slot6:</td>
	 <td><select name=\"stat_type6\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value6\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
</tr>
<tr>
	 <td>Slot7:</td>
	 <td><select name=\"stat_type7\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value7\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
	 <td>Slot8:</td>
	 <td><select name=\"stat_type8\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value8\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
</tr>
<tr>
	 <td>Slot9:</td>
	 <td><select name=\"stat_type9\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value9\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
	 <td>Slot10:</td>
	 <td><select name=\"stat_type10\">
	  <option value=\"0\">0: none</option>
	  <option value=\"1\">1: mana</option>
	  <option value=\"2\">2: health</option>
		<option value=\"3\">3: agility</option>
		<option value=\"4\">4: strength</option>
		<option value=\"5\">5: intellect</option>
		<option value=\"6\">6: spirit</option>
		<option value=\"7\">7: stamina</option>
		<option value=\"12\">12: defense</option>
		<option value=\"13\">13: dodge</option>
		<option value=\"14\">14: parry</option>
		<option value=\"15\">15: shield-block</option>
		<option value=\"16\">16: melee-hit</option>
		<option value=\"17\">17: range-hit</option>
		<option value=\"18\">18: spell-hit</option>
		<option value=\"19\">19: melee-crit</option>
		<option value=\"20\">20: ranged-crit</option>
		<option value=\"21\">21: spell-crit</option>
		<option value=\"28\">28: melee-haste</option>
		<option value=\"29\">29: ranged-haste</option>
		<option value=\"30\">30: spell-haste</option>
		<option value=\"31\">31: hit-rating</option>
		<option value=\"32\">32: crit-rating</option>
		<option value=\"35\">35: resist-rating</option>
		<option value=\"36\">36: haste-rating</option>
	  </select></td>
	 <td><input type=\"text\" name=\"stat_value10\" size=\"10\" maxlength=\"6\" value=\"0\" /></td>
</tr>
<tr>
  <td colspan=\"8\" class=\"large_bold\" align=\"left\">Resistances:</td>
</tr>
<tr>
	<td colspan=\"2\">armor:</td>
	 <td><input type=\"text\" name=\"armor\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
	<td colspan=\"2\">block:</td>
	 <td><input type=\"text\" name=\"block\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td colspan=\"2\">holy resistance:</td>
	 <td><input type=\"text\" name=\"holy_res\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
	<td colspan=\"2\">fire resistance:</td>
	 <td><input type=\"text\" name=\"fire_res\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td colspan=\"2\">nature resistance:</td>
	 <td><input type=\"text\" name=\"nature_res\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
	<td colspan=\"2\">frost resistance:</td>
	 <td><input type=\"text\" name=\"frost_res\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td colspan=\"2\">shadow resistance:</td>
	 <td><input type=\"text\" name=\"shadow_res\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
  <td colspan=\"2\">arcane resistance:</td>
	 <td><input type=\"text\" name=\"arcane_res\" size=\"10\" maxlength=\"30\" value=\"0\" /></td>
</tr>
   </table>
</div>";

$output .= "<div id=\"pane4\">
<table class=\"lined\" style=\"width: 720px;\">
<tr>
  <td colspan=\"8\" class=\"large_bold\" align=\"left\">Weapon properties:</td>
</tr>
<tr>
  <td>delay:</td>
    <td colspan=\"2\"><input type=\"text\" name=\"delay\" size=\"10\" maxlength=\"11\" value=\"0\" /></td>	   
  <td>range:</td>
    <td><input type=\"text\" name=\"range\" size=\"10\" maxlength=\"40\" value=\"0\" /></td>
  <td>ammo type:</td>
	 <td colspan=\"2\"><select name=\"ammo_type\">
		<option value=\"0\">0 - none</option>
		<option value=\"2\">2 - arrows</option>
		<option value=\"3\">3 - bullets</option>
	   </select></td>
</tr>
<tr>
  <td colspan=\"8\" class=\"large_bold\" align=\"left\">Weapon damage:</td>
</tr>
<tr>
	<td>Type1:</td>
	 <td colspan=\"3\"><select name=\"dmg_type1\">
		<option value=\"0\">0: physical</option>
		<option value=\"1\">1: holy</option>
		<option value=\"2\">2: fire</option>
		<option value=\"3\">3: nature</option>
		<option value=\"4\">4: frost</option>
		<option value=\"5\">5: shadow</option>
		<option value=\"6\">6: arcane</option>
	  </select></td>
	<td>min - max:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"dmg_min1\" size=\"8\" maxlength=\"45\" value=\"0\" /> - <input type=\"text\" name=\"dmg_max1\" size=\"8\" maxlength=\"45\" value=\"0\" /></td>
</tr>
<tr>
	 <td>Type2:</td>
	 <td colspan=\"3\"><select name=\"dmg_type2\">
		<option value=\"0\">0: physical</option>
		<option value=\"1\">1: holy</option>
		<option value=\"2\">2: fire</option>
		<option value=\"3\">3: nature</option>
		<option value=\"4\">4: frost</option>
		<option value=\"5\">5: shadow</option>
		<option value=\"6\">6: arcane</option>
	  </select></td>
	<td>min - max:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"dmg_min2\" size=\"8\" maxlength=\"45\" value=\"0\" /> - <input type=\"text\" name=\"dmg_max2\" size=\"8\" maxlength=\"45\" value=\"0\" /></td>
</tr>
<tr>
	<td>Type3:</td>
	 <td colspan=\"3\"><select name=\"dmg_type3\">
		<option value=\"0\">0: physical</option>
		<option value=\"1\">1: holy</option>
		<option value=\"2\">2: fire</option>
		<option value=\"3\">3: nature</option>
		<option value=\"4\">4: frost</option>
		<option value=\"5\">5: shadow</option>
		<option value=\"6\">6: arcane</option>
	  </select></td>
	<td>min - max:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"dmg_min3\" size=\"8\" maxlength=\"45\" value=\"0\" /> - <input type=\"text\" name=\"dmg_max3\" size=\"8\" maxlength=\"45\" value=\"0\" /></td>
</tr>
<tr>
	<td>Type4:</td>
	 <td colspan=\"3\"><select name=\"dmg_type4\">
		<option value=\"0\">0: physical</option>
		<option value=\"1\">1: holy</option>
		<option value=\"2\">2: fire</option>
		<option value=\"3\">3: nature</option>
		<option value=\"4\">4: frost</option>
		<option value=\"5\">5: shadow</option>
		<option value=\"6\">6: arcane</option>
	  </select></td>
	<td>min - max:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"dmg_min4\" size=\"8\" maxlength=\"45\" value=\"0\" /> - <input type=\"text\" name=\"dmg_max4\" size=\"8\" maxlength=\"45\" value=\"0\" /></td>
</tr>
<tr>
	<td>Type5:</td>
	 <td colspan=\"3\"><select name=\"dmg_type5\">
		<option value=\"0\">0: physical</option>
		<option value=\"1\">1: holy</option>
		<option value=\"2\">2: fire</option>
		<option value=\"3\">3: nature</option>
		<option value=\"4\">4: frost</option>
		<option value=\"5\">5: shadow</option>
		<option value=\"6\">6: arcane</option>
	  </select></td>
	<td>min - max:</td>
	 <td colspan=\"3\"><input type=\"text\" name=\"dmg_min5\" size=\"8\" maxlength=\"45\" value=\"0\" /> - <input type=\"text\" name=\"dmg_max5\" size=\"8\" maxlength=\"45\" value=\"0\" /></td>
</tr>
  </table>
</div>"; 

$output .= "<div id=\"pane5\">
<table class=\"lined\" style=\"width: 720px;\">
<tr><td colspan=\"8\" class=\"large_bold\" align=\"left\">Spell properties:</td></tr>
<tr>
	<td>ID1:</td>
	 <td><input type=\"text\" name=\"spellid_1\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Trigger1:</td>
	 <td><select name=\"spelltrigger_1\">
		<option value=\"0\">0: on use</option>
		<option value=\"1\">1: on equip</option>
		<option value=\"2\">2: chance on hit</option>
	</select></td>
	<td>Charges1:</td>
	 <td><input type=\"text\" name=\"spellcharges_1\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>Cooldown1:</td>
	 <td><input type=\"text\" name=\"spellcooldown_1\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category1:</td>
	 <td><input type=\"text\" name=\"spellcategory_1\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category cooldown1:</td>
	 <td><input type=\"text\" name=\"spellcategorycooldown_1\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>ID2:</td>
	 <td><input type=\"text\" name=\"spellid_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Trigger2:</td>
	 <td><select name=\"spelltrigger_2\">
		<option value=\"0\">0: on use</option>
		<option value=\"1\">1: on equip</option>
		<option value=\"2\">2: chance on hit</option>
	  </select></td>
	<td>Charges2:</td>
	 <td><input type=\"text\" name=\"spellcharges_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>Cooldown2:</td>
	 <td><input type=\"text\" name=\"spellcooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category2:</td>
	 <td><input type=\"text\" name=\"spellcategory_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category cooldown2:</td>
	 <td><input type=\"text\" name=\"spellcategorycooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	 <td>ID3:</td>
	 <td><input type=\"text\" name=\"spellid_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Trigger3:</td>
	 <td><select name=\"spelltrigger_2\">
		<option value=\"0\">0: on use</option>
		<option value=\"1\">1: on equip</option>
		<option value=\"2\">2: chance on hit</option>
	  </select></td>
	<td>Charges3:</td>
	 <td><input type=\"text\" name=\"spellcharges_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>Cooldown3:</td>
	 <td><input type=\"text\" name=\"spellcooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category3:</td>
	 <td><input type=\"text\" name=\"spellcategory_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category cooldown3:</td>
	 <td><input type=\"text\" name=\"spellcategorycooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	 <td>ID4:</td>
	 <td><input type=\"text\" name=\"spellid_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Trigger4:</td>
	 <td><select name=\"spelltrigger_2\">
		<option value=\"0\">0: on use</option>
		<option value=\"1\">1: on equip</option>
		<option value=\"2\">2: chance on hit</option>
	  </select></td>
	<td>Charges4:</td>
	 <td><input type=\"text\" name=\"spellcharges_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>Cooldown4:</td>
	 <td><input type=\"text\" name=\"spellcooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category4:</td>
	 <td><input type=\"text\" name=\"spellcategory_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category cooldown4:</td>
	 <td><input type=\"text\" name=\"spellcategorycooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	<td>ID5:</td>
	 <td><input type=\"text\" name=\"spellid_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Trigger5:</td>
	 <td><select name=\"spelltrigger_2\">
		<option value=\"0\">0: on use</option>
		<option value=\"1\">1: on equip</option>
		<option value=\"2\">2: chance on hit</option>
	  </select></td>
	<td>Charges5:</td>
	 <td><input type=\"text\" name=\"spellcharges_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	 <td>Cooldown5:</td>
	 <td><input type=\"text\" name=\"spellcooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category5:</td>
	 <td><input type=\"text\" name=\"spellcategory_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
	<td>Category cooldown5:</td>
	 <td><input type=\"text\" name=\"spellcategorycooldown_2\" size=\"8\" maxlength=\"30\" value=\"0\" /></td>
</tr>
 </table>
</div>";

$output .= "<div id=\"pane6\">
<table class=\"lined\" style=\"width: 720px;\">
<tr>
  <td colspan=\"8\" class=\"large_bold\" align=\"left\">Requirements:</td>
</tr>
<tr>
  <td>allowable class:</td>
   <td><select multiple=\"multiple\" name=\"AllowableClass[]\" size=\"4\">
		<option value=\"-1\">-1 - all</option>
		<option value=\"1\">1 - warrior</option>
		<option value=\"2\">2 - paladin</option>
		<option value=\"3\">3 - warrior,paladin</option>
		<option value=\"4\">4 - hunter</option>
		<option value=\"8\">8 - rogue</option>
		<option value=\"9\">9 - warrior,rogue</option>
		<option value=\"15\">15 - warrior,paladin,hunter,rogue</option>
		<option value=\"16\">16 - priest</option>
		<option value=\"21\">21 - warrior,hunter,priest</option>
		<option value=\"28\">28 - hunter,rogue,priest</option>
		<option value=\"29\">29 - warrior,hunter,rogue,priest</option>
		<option value=\"64\">64 - shaman</option>
		<option value=\"66\">66 - paladin,shaman</option>
		<option value=\"68\">68 - hunter,shaman</option>
		<option value=\"69\">69 - warrior,hunter,shaman</option>
		<option value=\"73\">73 - warrior,rogue,shaman</option>
		<option value=\"74\">74 - paladin,rogue,shaman</option>
		<option value=\"79\">79 - warrior,paladin,hunter,rogue,shaman</option>
		<option value=\"128\">128 - mage</option>
		<option value=\"134\">134 - paladin,hunter,mage</option>
		<option value=\"140\">140 - hunter,rogue,mage</option>
		<option value=\"141\">141 - warrior,hunter,rogue,mage</option>
		<option value=\"153\">153 - warrior,rogue,priest,mage</option>
		<option value=\"210\">210 - paladin,priest,shaman,mage</option>
		<option value=\"256\">256 - warlock</option>
		<option value=\"265\">265 - warrior,rogue,warlock</option>
		<option value=\"274\">274 - paladin,priest,warlock</option>
		<option value=\"284\">284 - hunter,rogue,priest,warlock</option>
		<option value=\"326\">326 - paladin,hunter,shaman,warlock</option>
	   </select></td>
  <td>allowable race:</td>
   <td><select multiple=\"multiple\" name=\"AllowableRace[]\" size=\"5\">
		<option value=\"-1\">-1 - all</option>
		<option value=\"0\">0 - </option>
		<option value=\"32\">32 - tauren</option>
		<option value=\"68\">68 - dwarf,gnome</option>
		<option value=\"415\">415 - alliance</option>
		<option value=\"658\">658 - gnome</option>
		<option value=\"690\">690 - troll</option>
		<option value=\"1101\">1101 - draenei</option>
		<option value=\"2047\">2047 - bloodelf</option>
		<option value=\"31232\">31232 - </option>
		<option value=\"32767\">32767 - </option>
		<option value=\"2147483647\">2147483647 - </option>
	   </select></td>
</tr>
<tr>
	 <td>required skill:</td>
	   <td><input type=\"text\" name=\"RequiredSkill\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
	 <td>required skill rank:</td>
	   <td><input type=\"text\" name=\"RequiredSkillRank\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	 <td><span>required skill subrank:</span></td>
	   <td><input type=\"text\" name=\"requiredSkillSubRank\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
	 <td><span>required player rank1:</span></td>
	   <td><input type=\"text\" name=\"requiredplayerrank1\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
	</tr>
<tr>
	 <td><span>required faction:</span></td>
	   <td><input type=\"text\" name=\"RequiredFaction\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
   <td><span>required player rank2:</span></td>
	   <td><input type=\"text\" name=\"requiredplayerrank2\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
</tr>
<tr>
	 <td><span>required faction standing:</span></td>
	   <td><input type=\"text\" name=\"RequiredFactionStanding\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
	 <td><span>arena rank requirement:</span></td>
	   <td><input type=\"text\" name=\"ArenaRankRequirement\" size=\"15\" maxlength=\"30\" value=\"0\" /></td>
</tr>
  </table>
</div>";

$output .= "<div id=\"pane7\">
    <table class=\"lined\" style=\"width: 720px;\">
<tr><td colspan=\"8\" class=\"large_bold\" align=\"left\">Sockets:</td></tr>
<tr>
	<td>color1:</td>
	 <td><select name=\"socketColor_1\">
		<option value=\"0\">0: none</option>
		<option value=\"1\">1: meta</option>
		<option value=\"2\">2: red</option>
		<option value=\"4\">4: yellow</option>
		<option value=\"8\">8: blue</option>
	  </select></td>
	<td>content1:</td>
	 <td><input type=\"text\" name=\"unk201_3\" size=\"15\" maxlength=\"10\" value=\"0\" /></td>
</tr>
<tr>
	<td>color2:</td>
	 <td><select name=\"socketColor_2\">
		<option value=\"0\">0: none</option>
		<option value=\"1\">1: meta</option>
		<option value=\"2\">2: red</option>
		<option value=\"4\">4: yellow</option>
		<option value=\"8\">8: blue</option>
	  </select></td>
	<td>content2:</td>
	 <td><input type=\"text\" name=\"unk201_5\" size=\"15\" maxlength=\"10\" value=\"0\" /></td>
</tr>
<tr>
	 <td>color3:</td>
	 <td><select name=\"socketColor_3\">
		<option value=\"0\">0: none</option>
		<option value=\"1\">1: meta</option>
		<option value=\"2\">2: red</option>
		<option value=\"4\">4: yellow</option>
		<option value=\"8\">8: blue</option>
	  </select></td>
	<td>content3:</td>
	 <td><input type=\"text\" name=\"unk201_7\" size=\"15\" maxlength=\"10\" value=\"0\" /></td>
</tr>
<tr>
	<td>bonus:</td>
	 <td><input type=\"text\" name=\"socketBonus\" size=\"15\" maxlength=\"10\" value=\"NULL\" /></td>
	<td>gem properties:</td>
	 <td><input type=\"text\" name=\"GemProperties\" size=\"15\" maxlength=\"10\" value=\"NULL\" /></td>
</tr>
  </table>
</div>
  </form>
<script>setupPanes(\"container\", \"tab1\")</script>";

 $output .= "<table class=\"hidden\">
<tr><td>";
    makebutton("save to db", "item.php?action=doadd_new",170);
		makebutton("save as sql", "javascript:do_submit('form1',1)",170);
		makebutton("browse items", "item.php",170);
 $output .= "</td></tr>

</table></center>";

}

//add a new item to database
function doadd_new () {
  global $dbhost, $dbuser, $dbpass, $world_db;
  
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));
 
 if (empty($_POST['entry'])) {
    header("Location: item.php?error=1");
			exit();
		} else {
			$entry = quote_smart($_POST['entry']);
    }
// if (isset($_POST['class'])) $class = quote_smart($_POST['class']);
// if (isset($_POST['subclass'])) $subclass = quote_smart($_POST['subclass']);
// if (isset($_POST['name1'])) $name1 = quote_smart($_POST['name1']);
 /*if (isset($_POST['name2'])) $name2 = quote_smart($_POST['name2']);
 if (isset($_POST['name3'])) $name3 = quote_smart($_POST['name3']);
 if (isset($_POST['name4'])) $name4 = quote_smart($_POST['name4']);
 if (isset($_POST['displayid'])) $displayid = quote_smart($_POST['displayid']);
 if (isset($_POST['quality'])) $quality = quote_smart($_POST['quality']);
 if (isset($_POST['flags'])) $flags = quote_smart($_POST['flags']);
 if (isset($_POST['buyprice'])) $buyprice = quote_smart($_POST['buyprice']);
 if (isset($_POST['sellprice'])) $sellprice = quote_smart($_POST['sellprice']);
 if (isset($_POST['inventorytype'])) $inventorytype = quote_smart($_POST['inventorytype']);
 if (isset($_POST['allowableclass'])) $allowableclass = quote_smart($_POST['allowableclass']);
 if (isset($_POST['allowablerace'])) $allowablerace = $mysql->quote_smart($_POST['allowablerace']);
 if (isset($_POST['itemlevel'])) $itemlevel = quote_smart($_POST['itemlevel']); 
 if (isset($_POST['requiredlevel'])) $requiredlevel = quote_smart($_POST['requiredlevel']);
 if (isset($_POST['RequiredSkill'])) $RequiredSkill = quote_smart($_POST['RequiredSkill']);
 if (isset($_POST['RequiredSkillRank'])) $RequiredSkillRank = quote_smart($_POST['RequiredSkillRank']);
 if (isset($_POST['RequiredSkillSubRank'])) $RequiredSkillSubRank = quote_smart($_POST['RequiredSkillSubRank']);
 if (isset($_POST['RequiredPlayerRank1'])) $RequiredPlayerRank1 = quote_smart($_POST['RequiredPlayerRank1']);
 if (isset($_POST['RequiredPlayerRank2'])) $rRequiredPlayerRank2 = quote_smart($_POST['RequiredPlayerRank2']);
 if (isset($_POST['RequiredFaction'])) $RequiredFaction = quote_smart($_POST['RequiredFaction']);
 if (isset($_POST['RequiredFactionStanding'])) $RequiredFactionStanding = quote_smart($_POST['RequiredFactionStanding']);
 if (isset($_POST['Unique'])) $Unique = quote_smart($_POST['Unique']);
 if (isset($_POST['maxcount'])) $maxcount = quote_smart($_POST['maxcount']);
 if (isset($_POST['ContainerSlots'])) $ContainerSlots = quote_smart($_POST['ContainerSlots']);
 if (isset($_POST['stat_type1'])) $stat_type1 = quote_smart($_POST['stat_type1']);
 if (isset($_POST['stat_value1'])) $stat_value1 = quote_smart($_POST['stat_value1']);
 if (isset($_POST['stat_type2'])) $stat_type2 = quote_smart($_POST['stat_type2']);
 if (isset($_POST['stat_value2'])) $stat_value2 = quote_smart($_POST['stat_value2']);
 if (isset($_POST['stat_type3'])) $stat_type3 = quote_smart($_POST['stat_type3']);
 if (isset($_POST['stat_value3'])) $stat_value3 = quote_smart($_POST['stat_value3']);
 if (isset($_POST['stat_type4'])) $stat_type4 = quote_smart($_POST['stat_type4']);
 if (isset($_POST['stat_value4'])) $stat_value4 = quote_smart($_POST['stat_value4']);
 if (isset($_POST['stat_type5'])) $stat_type5 = quote_smart($_POST['stat_type5']);
 if (isset($_POST['stat_value5'])) $stat_value5 = quote_smart($_POST['stat_value5']);
 if (isset($_POST['stat_type6'])) $stat_type6 = quote_smart($_POST['stat_type6']);
 if (isset($_POST['stat_value6'])) $stat_value6 = quote_smart($_POST['stat_value6']);
 if (isset($_POST['stat_type7'])) $stat_type7 = quote_smart($_POST['stat_type7']);
 if (isset($_POST['stat_value7'])) $stat_value7 = quote_smart($_POST['stat_value7']);
 if (isset($_POST['stat_type8'])) $stat_type8 = quote_smart($_POST['stat_type8']);
 if (isset($_POST['stat_value8'])) $stat_value8 = quote_smart($_POST['stat_value8']);
 if (isset($_POST['stat_type9'])) $stat_type9 = quote_smart($_POST['stat_type9']);
 if (isset($_POST['stat_value9'])) $stat_value9 = quote_smart($_POST['stat_value9']);
 if (isset($_POST['stat_type10'])) $stat_type10 = quote_smart($_POST['stat_type10']);
 if (isset($_POST['stat_value10'])) $stat_value10 = quote_smart($_POST['stat_value10']);
 if (isset($_POST['dmg_min1'])) $dmg_min1 = quote_smart($_POST['dmg_min1']);
 if (isset($_POST['dmg_max1'])) $dmg_max1 = quote_smart($_POST['dmg_max1']);
 if (isset($_POST['dmg_type1'])) $dmg_type1 = quote_smart($_POST['dmg_type1']);
 if (isset($_POST['dmg_min2'])) $dmg_min2 = quote_smart($_POST['dmg_min2']);
 if (isset($_POST['dmg_max2'])) $dmg_max2 = quote_smart($_POST['dmg_max2']);
 if (isset($_POST['dmg_type2'])) $dmg_type2 = quote_smart($_POST['dmg_type2']);
 if (isset($_POST['dmg_min3'])) $dmg_min3 = quote_smart($_POST['dmg_min3']);
 if (isset($_POST['dmg_max3'])) $dmg_max3 = quote_smart($_POST['dmg_max3']);
 if (isset($_POST['dmg_type3'])) $dmg_type3 = quote_smart($_POST['dmg_type3']);
 if (isset($_POST['dmg_min4'])) $dmg_min4 = quote_smart($_POST['dmg_min4']);
 if (isset($_POST['dmg_max4'])) $dmg_max4 = quote_smart($_POST['dmg_max4']);
 if (isset($_POST['dmg_type4'])) $dmg_type4 = quote_smart($_POST['dmg_type4']);
 if (isset($_POST['dmg_min5'])) $dmg_min5 = quote_smart($_POST['dmg_min5']);
 if (isset($_POST['dmg_max5'])) $dmg_max5 = quote_smart($_POST['dmg_max5']);
 if (isset($_POST['dmg_type5'])) $dmg_type5 = quote_smart($_POST['dmg_type5']);
 if (isset($_POST['armor'])) $armor = quote_smart($_POST['armor']);
 if (isset($_POST['holy_res'])) $holy_res = quote_smart($_POST['holy_res']);
 if (isset($_POST['fire_res'])) $fire_res = quote_smart($_POST['fire_res']);
 if (isset($_POST['nature_res'])) $nature_res = quote_smart($_POST['nature_res']);
 if (isset($_POST['frost_res'])) $frost_res = quote_smart($_POST['frost_res']);
 if (isset($_POST['shadow_res'])) $shadow_res = quote_smart($_POST['shadow_res']);
 if (isset($_POST['arcane_res'])) $arcane_res = quote_smart($_POST['arcane_res']);
 if (isset($_POST['delay'])) $delay = quote_smart($_POST['delay']);
 if (isset($_POST['ammo_type'])) $ammo_type = quote_smart($_POST['ammo_type']);
 if (isset($_POST['range'])) $range = quote_smart($_POST['range']);
 if (isset($_POST['spellid_1'])) $spellid_1 = quote_smart($_POST['spellid_1']);
 if (isset($_POST['spelltrigger_1'])) $spelltrigger_1 = quote_smart($_POST['spelltrigger_1']);
 if (isset($_POST['spellcharges_1'])) $spellcharges_1 = quote_smart($_POST['spellcharges_1']);
 if (isset($_POST['spellcooldown_1'])) $spellcooldown_1 = quote_smart($_POST['spellcooldown_1']);
 if (isset($_POST['spellcategory_1'])) $spellcategory_1 = quote_smart($_POST['spellcategory_1']);
 if (isset($_POST['spellcategorycooldown_1'])) $spellcategorycooldown_1 = quote_smart($_POST['spellcategorycooldown_1']);
 if (isset($_POST['spellid_2'])) $spellid_2 = quote_smart($_POST['spellid_2']);
 if (isset($_POST['spelltrigger_2'])) $spelltrigger_2 = quote_smart($_POST['spelltrigger_2']);
 if (isset($_POST['spellcharges_2'])) $spellcharges_2 = quote_smart($_POST['spellcharges_2']);
 if (isset($_POST['spellcooldown_2'])) $spellcooldown_2 = quote_smart($_POST['spellcooldown_2']);
 if (isset($_POST['spellcategory_2'])) $spellcategory_2 = quote_smart($_POST['spellcategory_2']);
 if (isset($_POST['spellcategorycooldown_2'])) $spellcategorycooldown_2 = quote_smart($_POST['spellcategorycooldown_2']);
 if (isset($_POST['spellid_3'])) $spellid_3 = quote_smart($_POST['spellid_3']);
 if (isset($_POST['spelltrigger_3'])) $spelltrigger_3 = quote_smart($_POST['spelltrigger_3']);
 if (isset($_POST['spellcharges_3'])) $spellcharges_3 = quote_smart($_POST['spellcharges_3']);
 if (isset($_POST['spellcooldown_3'])) $spellcooldown_3 = quote_smart($_POST['spellcooldown_3']);
 if (isset($_POST['spellcategory_3'])) $spellcategory_3 = quote_smart($_POST['spellcategory_3']);
 if (isset($_POST['spellcategorycooldown_3'])) $spellcategorycooldown_3 = quote_smart($_POST['spellcategorycooldown_3']);
 if (isset($_POST['spellid_4'])) $spellid_4 = quote_smart($_POST['spellid_4']);
 if (isset($_POST['spelltrigger_4'])) $spelltrigger_4 = quote_smart($_POST['spelltrigger_4']);
 if (isset($_POST['spellcharges_4'])) $spellcharges_4 = quote_smart($_POST['spellcharges_4']);
 if (isset($_POST['spellcooldown_4'])) $spellcooldown_4 = quote_smart($_POST['spellcooldown_4']);
 if (isset($_POST['spellcategory_4'])) $spellcategory_4 = quote_smart($_POST['spellcategory_4']);
 if (isset($_POST['spellcategorycooldown_4'])) $spellcategorycooldown_4 = quote_smart($_POST['spellcategorycooldown_4']);
 if (isset($_POST['spellid_5'])) $spellid_5 = quote_smart($_POST['spellid_5']);
 if (isset($_POST['spelltrigger_5'])) $spelltrigger_5 = quote_smart($_POST['spelltrigger_5']);
 if (isset($_POST['spellcharges_5'])) $spellcharges_5 = quote_smart($_POST['spellcharges_5']);
 if (isset($_POST['spellcooldown_5'])) $spellcooldown_5 = quote_smart($_POST['spellcooldown_5']);
 if (isset($_POST['spellcategory_5'])) $spellcategory_5 = quote_smart($_POST['spellcategory_5']);
 if (isset($_POST['spellcategorycooldown_5'])) $spellcategorycooldown_5 = quote_smart($_POST['spellcategorycooldown_5']);
 if (isset($_POST['bonding'])) $bonding = quote_smart($_POST['bonding']);
 if (isset($_POST['description'])) $description = quote_smart($_POST['description']);
 if (isset($_POST['page_id'])) $page_id = quote_smart($_POST['page_id']);
 if (isset($_POST['page_language'])) $page_language = quote_smart($_POST['page_language']);
 if (isset($_POST['page_material'])) $page_material = quote_smart($_POST['page_material']);
 if (isset($_POST['quest_id'])) $quest_id = quote_smart($_POST['quest_id']);
 if (isset($_POST['lock_id'])) $lock_id = quote_smart($_POST['lock_id']);
 if (isset($_POST['lock_material'])) $lock_material = quote_smart($_POST['lock_material']);
 if (isset($_POST['sheathID'])) $sheathID = quote_smart($_POST['sheathID']);
 if (isset($_POST['randomprop'])) $randomprop = quote_smart($_POST['randomprop']);
 if (isset($_POST['block '])) $block = quote_smart($_POST['block']);
 if (isset($_POST['itemset'])) $itemset = quote_smart($_POST['itemset']);
 if (isset($_POST['MaxDurability'])) $MaxDurability = quote_smart($_POST['MaxDurability']);
 if (isset($_POST['ZoneNameID'])) $ZoneNameID = quote_smart($_POST['ZoneNameID']);
 if (isset($_POST['mapid'])) $mapid = quote_smart($_POST['mapid']);
 if (isset($_POST['bagfamily'])) $bagfamily = quote_smart($_POST['bagfamily']);
 if (isset($_POST['TotemCategory'])) $TotemCategory = quote_smart($_POST['TotemCategory']);
 if (isset($_POST['socket_color_1'])) $socket_color_1 = quote_smart($_POST['socket_color_1']);
 if (isset($_POST['unk201_3'])) $unk201_3 = quote_smart($_POST['unk201_3']);
 if (isset($_POST['socket_color_2'])) $socket_color_2 = quote_smart($_POST['socket_color_2']);
 if (isset($_POST['unk201_5'])) $unk201_5 = quote_smart($_POST['unk201_5']);
 if (isset($_POST['socket_color_3'])) $socket_color_3 = quote_smart($_POST['socket_color_3']);
 if (isset($_POST['unk201_7'])) $unk201_7 = quote_smart($_POST['unk201_7']);
 if (isset($_POST['socket_bonus'])) $socket_bonus = quote_smart($_POST['socket_bonus']);
 if (isset($_POST['GemProperties'])) $GemProperties = quote_smart($_POST['GemProperties']);
 if (isset($_POST['ItemExtendedCost'])) $ItemExtendedCost = quote_smart($_POST['ItemExtendedCost']);
 if (isset($_POST['ArenaRankRequirement'])) $ArenaRankRequirement = quote_smart($_POST['ArenaRankRequirement']);
 if (isset($_POST['ReqDisenchantSkill'])) $ReqDisenchantSkill = quote_smart($_POST['ReqDisenchantSkill']);*/

	$sql = "INSERT INTO items VALUES ('$entry', '0', '0','-1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0',
	  '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 
    '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0','0', '0', '0', '0', '0', 
    '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0',	'0', '0', '0', '0', '0', '0', '0', '0', 
    '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 
    '0', '0', '0', '0',	'0', '0', '0', '0', '0', '0', '0', '0', '0', 
    '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 
    '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0',	'0', '0', '0', '0', '0', '0', 
    '0', '0', '0')";
  $result = mysql_query($sql) or die(error(mysql_error()));

 if ($result) {
			header("Location: item.php?err=5");
			exit();
  } else { 
 	header("Location: item.php?err=4");
 	exit();
	}
	mysql_close();
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
case 4:
   $output .= "<h1><font class=\"error\">New item creation failed!</font></h1>";
   break;
case 5:
   $output .= "<h1>New item created</h1>";
   break;
case 10:
   $output .=  "<h1>Item Creation:</h1>";
   break;
default: //no error
    $output .= "<h1>Search Items</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "search_item": 
   search_item();
   break;
case "search": 
   search();
   break;
case "del_item": 
   del_item();
   break;
case "dodel_item": 
   dodel_item();
   break;
case "add_new": 
   add_new();
   break;
case "doadd_new": 
   doadd_new();
   break;
default:
    search_item();
}

require_once("footer.php");
?>