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

// print cleanup options
function cleanup(){
 global $output;

 $output .= "<center>
 <fieldset style=\"width: 740px;\">
	<legend>CleanUp Options</legend><br/>
	<form action=\"cleanup.php\" method=\"GET\">
	   <input type=\"hidden\" name=\"action\" value=\"run_cleanup\" />
	   <select name=\"cleanup_by\">
		<optgroup label=\"Clean Accounts\">
			<option value=\"last_login\">Last Login (date)</option>
			<option value=\"banned\">Banned</option>
			<option value=\"num_of_char_in_acc\">Characters in Account</option>
		</optgroup>
		<optgroup label=\"Clean Characters\">
			<option value=\"char_lvl\">Character Level</option>
			<option value=\"totaltime\">Total Play Time (sec)</option>
		</optgroup>
		<optgroup label=\"Clean Guilds\">
			<option value=\"num_of_char_in_guild\">Characters in Guild</option>
		</optgroup>
	   </select>
	   <select name=\"cleanup_sign\">
		<option value=\"=\">=</option>
		<option value=\"<\"><</option>
		<option value=\"<=\"><=</option>
		<option value=\">\">></option>
		<option value=\">=\">>=</option>
		<option value=\"!=\">!=</option>
	   </select>
	   <input type=\"text\" size=\"45\" maxlength=\"50\" name=\"cleanup_value\" />
	   <input type=\"submit\" value=\"Run CleanUp\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form><br/>";
	    $output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Back", "javascript:window.history.back()","200");
		$output .= "</td>
          </tr>
        </table><br/></fieldset><br/><br/></center>";
}

// make and list all acc/chars
function run_cleanup(){
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db;

 if((empty($_GET['cleanup_by']))||(empty($_GET['cleanup_sign']))) {
	header("Location: cleanup.php?error=1");
	exit();
	}

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));

 $cleanup_by = quote_smart($_GET['cleanup_by']);
 $cleanup_sign = quote_smart($_GET['cleanup_sign']);
 $cleanup_value = quote_smart($_GET['cleanup_value']);

switch ($cleanup_by) {
 // clean by lvl
 case "char_lvl":
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
 $sql = "SELECT * FROM `characters`";
 $result = mysql_query($sql) or die(error(mysql_error()));
 $total_chars = mysql_num_rows($result);

 $char_output_array = array();

 for ($i=1; $i<=$total_chars; $i++){
	$char = mysql_fetch_row($result) or die(error(mysql_error()));

	switch ($cleanup_sign){
		case "=":
		if($char[8] == $cleanup_value) array_push($char_output_array, $char[0]);
		break;
		case "<":
		if($char[8] < $cleanup_value) array_push($char_output_array, $char[0]);
		break;
		case "<=":
		if($char[8] <= $cleanup_value) array_push($char_output_array, $char[0]);
		break;
		case ">":
		if($char[8] > $cleanup_value) array_push($char_output_array, $char[0]);
		break;
		case ">=":
		if($char[8] >= $cleanup_value) array_push($char_output_array, $char[0]);
		break;
		case "!=":
		if($char[8] <> $cleanup_value) array_push($char_output_array, $char[0]);
		break;
		default:
  		header("Location: cleanup.php?error=1");
  		exit();
		}
 }

 $output .= "<center>";
 if ($char_output_array){
	$output .= "<h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
	$output .= "<font class=\"bold\">Charecter(s) id(s): ";

	//this array needed to pass multiple values from check boxes down to delete by post method
	//look at the loop and the value passed on "YES"
	$pass_array = "";

	for ($i=0; $i<count($char_output_array); $i++){
		$output .= $char_output_array[$i].", ";
		$pass_array .= "&amp;check%5B%5D=$char_output_array[$i]";
		}

	$output .= "<br/>Total of ".count($char_output_array)." Will be unrecoverable if erased from DB!</font><br/><br/>";

	$output .= " <table class=\"hidden\">
 	         <tr>
   	         <td>";
			makebutton("YES", "cleanup.php?action=docleanup&amp;type=char$pass_array","120");
	$output .= "     </td>
   	         <td>";
			makebutton("NO", "cleanup.php","120");
	$output .= "     </td>
     	     </tr>
     	   </table>";
 } else {
	$output .= "<h1><font class=\"error\">None Found!</font></h1><br/>";
	 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Go Back", "cleanup.php","120");
	 $output .= "</td>
          </tr>
        </table>";
 }
 $output .= "</center><br/>";
break;


//last login
 case "last_login":
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

  $sql = "SELECT acct FROM accounts WHERE lastlogin $cleanup_sign '$cleanup_value'";
  $result = mysql_query($sql) or die(error(mysql_error()));
  $total_accounts = @mysql_num_rows($result);

 $acc_output_array = array();

 for ($i=1; $i<=$total_accounts; $i++){
	$acc = mysql_fetch_row($result) or die(error(mysql_error()));
	array_push($acc_output_array, $acc[0]);
  }

$output .= "<center>";
if ($acc_output_array){
	$output .= "<h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
	$output .= "<font class=\"bold\">Account(s) id(s): ";

	$pass_array = "";

	for ($i=0; $i<count($acc_output_array); $i++){
		$output .= $acc_output_array[$i].", ";
		$pass_array .= "&amp;check%5B%5D=$acc_output_array[$i]";
		}

	$output .= "<br/>Will be unrecoverable if erased from DB!</font><br/><br/>";

	$output .= " <table class=\"hidden\">
 	         <tr>
   	         <td>";
			makebutton("YES", "cleanup.php?action=docleanup&amp;type=acc$pass_array","120");
	$output .= "     </td>
  	          <td>";
			makebutton("NO", "cleanup.php","120");
	$output .= "     </td>
      		    </tr>
        	</table>";
 } else {
	$output .= "<h1><font class=\"error\">None Found!</font></h1><br/>";
	$output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Go Back", "cleanup.php","120");
	 $output .= "</td>
          </tr>
        </table>";
 }
 $output .= "</center><br/>";
break;

//clean banned accounts
case "banned":
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct FROM accounts WHERE banned $cleanup_sign '$cleanup_value'";
 $result = mysql_query($sql) or die(error(mysql_error()));
 $total_accounts = mysql_num_rows($result);

 $acc_output_array = array();

 for ($i=1; $i<=$total_accounts; $i++){
	$acc = mysql_fetch_row($result) or die(error(mysql_error()));
	array_push($acc_output_array, $acc[0]);
 }
 $output .= "<center>";
 if ($acc_output_array){
	$output .= "<h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
	$output .= "<font class=\"bold\">Account(s) id(s): ";

	$pass_array = "";

	for ($i=0; $i<count($acc_output_array); $i++){
		$output .= $acc_output_array[$i].", ";
		$pass_array .= "&amp;check%5B%5D=$acc_output_array[$i]";
		}

	$output .= "<br/>Will be unrecoverble erased from DB!</font><br/><br/>";
	$output .= " <table class=\"hidden\">
 	         <tr>
   	         <td>";
			makebutton("YES", "cleanup.php?action=docleanup&amp;type=acc$pass_array","120");
	$output .= "     </td>
  	          <td>";
			makebutton("NO", "cleanup.php","120");
	$output .= "     </td>
      		    </tr>
        	</table>";
 } else {
	$output .= "<h1><font class=\"error\">Non Found!</font></h1><br/>";
	$output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Go Back", "cleanup.php","120");
	$output .= "</td>
          </tr>
        </table>";
 }
  $output .= "</center><br/>";
break;

//clean chars with given total time played
case "totaltime":
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $sql = "SELECT guid FROM `characters` WHERE playedtime $cleanup_sign '$cleanup_value'";
 $result = mysql_query($sql) or die(error(mysql_error()));
 $total_chars = mysql_num_rows($result);

 $char_output_array = array();

 for ($i=1; $i<=$total_chars; $i++){
	$char = mysql_fetch_row($result) or die(error(mysql_error()));
	array_push($char_output_array, $char[0]);
 }
 $output .= "<center>";
 if ($char_output_array){
	$output .= "<h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
	$output .= "<font class=\"bold\">Characters(s) id(s): ";

	$pass_array = "";

	for ($i=0; $i<count($char_output_array); $i++){
		$output .= $char_output_array[$i].", ";
		$pass_array .= "&amp;check%5B%5D=$char_output_array[$i]";
		}

	$output .= "<br/>Will be unrecoverble erased from DB!</font><br/><br/>";
	$output .= " <table class=\"hidden\">
 	         <tr>
   	         <td>";
			makebutton("YES", "cleanup.php?action=docleanup&amp;type=char$pass_array","120");
	$output .= "     </td>
  	          <td>";
			makebutton("NO", "cleanup.php","120");
	$output .= "     </td>
      		    </tr>
        	</table>";
 } else {
	$output .= "<h1><font class=\"error\">Non Found!</font></h1><br/>";
	$output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Go Back", "cleanup.php","120");
	 $output .= "</td>
          </tr>
        </table>";
 }
  $output .= "</center><br/>";
break;

//accounts without chars or specified number of chars
case "num_of_char_in_acc":
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct FROM accounts";
 $result = mysql_query($sql) or die(error(mysql_error()));

 $acc_output_array = array();
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 while($acc = mysql_fetch_row($result)){
	$sql = "SELECT guid FROM `characters` WHERE acct='$acc[0]'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$total_chars_in_acc = mysql_num_rows($query);

	switch ($cleanup_sign){
		case "=":
		if($total_chars_in_acc == $cleanup_value) array_push($acc_output_array, $acc[0]);
		break;
		case "<":
		if($total_chars_in_acc < $cleanup_value) array_push($acc_output_array, $acc[0]);
		break;
		case "<=":
		if($total_chars_in_acc <= $cleanup_value) array_push($acc_output_array, $acc[0]);
		break;
		case ">":
		if($total_chars_in_acc > $cleanup_value) array_push($acc_output_array, $acc[0]);
		break;
		case ">=":
		if($total_chars_in_acc >= $cleanup_value) array_push($acc_output_array, $acc[0]);
		break;
		case "!=":
		if($total_chars_in_acc <> $cleanup_value) array_push($acc_output_array, $acc[0]);
		break;
		default:
  		header("Location: cleanup.php?error=1");
  		exit();
		}
 }
 
 $output .= "<center>";
 if ($acc_output_array){
	$output .= "<h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
	$output .= "<font class=\"bold\">Account(s) id(s): ";

	$pass_array = "";

	for ($i=0; $i<count($acc_output_array); $i++){
		$output .= $acc_output_array[$i].", ";
		$pass_array .= "&amp;check%5B%5D=$acc_output_array[$i]";
		}

	$output .= "<br/>Will be unrecoverble erased from DB!</font><br/><br/>";

	$output .= " <table class=\"hidden\">
 	         <tr>
   	         <td>";
			makebutton("YES", "cleanup.php?action=docleanup&amp;type=acc$pass_array","120");
	$output .= "     </td>
  	          <td>";
			makebutton("NO", "cleanup.php","120");
	$output .= "     </td>
      		    </tr>
        	</table>";
 } else {
	$output .= "<h1><font class=\"error\">Non Found!</font></h1><br/>";
	$output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Go Back", "cleanup.php","120");
	 $output .= "</td>
          </tr>
        </table>";
 }
  $output .= "</center><br/>";
break;

//guild  without chars or specified number of chars
case "num_of_char_in_guild":
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $sql = "SELECT guildid FROM guilds";
 $result = mysql_query($sql) or die(error(mysql_error()));

 $guild_output_array = array();

 while($guild = mysql_fetch_row($result)){
	$sql = "SELECT playerid FROM guild_data WHERE guildid='$guild[0]'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$total_chars_in_guild = mysql_num_rows($query);

	switch ($cleanup_sign){
		case "=":
		if($total_chars_in_guild == $cleanup_value) array_push($guild_output_array, $guild[0]);
		break;
		case "<":
		if($total_chars_in_guild < $cleanup_value) array_push($guild_output_array, $guild[0]);
		break;
		case "<=":
		if($total_chars_in_guild <= $cleanup_value) array_push($guild_output_array, $guild[0]);
		break;
		case ">":
		if($total_chars_in_guild > $cleanup_value) array_push($guild_output_array, $guild[0]);
		break;
		case ">=":
		if($total_chars_in_guild >= $cleanup_value) array_push($guild_output_array, $guild[0]);
		break;
		case "!=":
		if($total_chars_in_guild <> $cleanup_value) array_push($guild_output_array, $guild[0]);
		break;
		default:
  		header("Location: cleanup.php?error=1");
  		exit();
		}
 }
 
 $output .= "<center>";
 if ($guild_output_array){
	$output .= "<h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
	$output .= "<font class=\"bold\">Guild(s) id(s): ";

	$pass_array = "";

	for ($i=0; $i<count($guild_output_array); $i++){
		$output .= $guild_output_array[$i].", ";
		$pass_array .= "&amp;check%5B%5D=$guild_output_array[$i]";
		}

	$output .= "<br/>Will be unrecoverble erased from DB!</font><br/><br/>";
	$output .= " <table class=\"hidden\">
 	         <tr>
   	         <td>";
			makebutton("YES", "cleanup.php?action=docleanup&amp;type=guild$pass_array","120");
	$output .= "     </td>
  	          <td>";
			makebutton("NO", "cleanup.php","120");
	$output .= "     </td>
      		    </tr>
        	</table>";
 } else {
	$output .= "<h1><font class=\"error\">Non Found!</font></h1><br/>";
	$output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Go Back", "cleanup.php","120");
	 $output .= "</td>
          </tr>
        </table>";
 }
  $output .= "</center><br/>";
break;


default:
 header("Location: cleanup.php?error=1");
 exit();
}

 mysql_close();
}

// DO CLEANUP
function docleanup(){
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_lvl;

 if (empty($_GET['type'])) {
   header("Location: cleanup.php?error=1");
   exit();
 } 
 
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  
 $type = quote_smart($_GET['type']);
 if(isset($_GET['check'])) $check = quote_smart($_GET['check']);
	else {
			header("Location: cleanup.php?error=1");
			exit();
			}
	
 $deleted_acc = 0;
 $deleted_chars = 0;
 $del_guilds = 0;

switch ($type){
//deleting account array
 case "acc":
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
	//checking if current user have gm power to delete the accounts.
	$sql = "SELECT gm FROM accounts WHERE acct ='$check[$i]'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$gmlevel = mysql_result($query, 0, 'gm');

   if ($user_lvl > $gmlevel){
	require_once("defs/del_tab.php");
		
	mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	$sql = "SELECT guid FROM `characters` WHERE acct='$check[$i]'";
	$result = mysql_query($sql) or die(error(mysql_error()));
	while ($row = mysql_fetch_array($result)) {
	
		$sql = "SELECT entry FROM playeritems WHERE ownerguid ='$row[0]'";
		$temp = mysql_query($sql) or die(error(mysql_error()));
		for ($k=1; $k<=mysql_num_rows($temp); $k++){
			$item_ins = mysql_fetch_row($temp) or die(error(mysql_error()));
			$sql = "DELETE FROM playeritems WHERE guid = '$item_ins[0]'";
			$query = @mysql_query($sql);
		}
		
		foreach ($tab_del_user_char as $value){
			$sql = "DELETE FROM $value[0] WHERE $value[1] = '$row[0]'";
			$query = mysql_query($sql) or die(error(mysql_error()));
			}
		$deleted_chars++;
	}
	mysql_free_result($result);

	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));


	if (mysql_affected_rows() <> 0) $deleted_acc++;
	}
  }
}
break;

//deleting character array
case "char":
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
	//checking if current user has gm power to delete the char.
	$sql = "SELECT acct FROM `characters` WHERE guid ='$check[$i]'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$owner_acc_id = mysql_result($query, 0, 'account');
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	$sql = "SELECT gm FROM accounts WHERE acct ='$owner_acc_id'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$owner_gmlvl = mysql_result($query, 0, 'gmlevel');

	mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	if ($user_lvl > $owner_gmlvl){
		require_once("defs/del_tab.php");
		
		$sql = "SELECT entry FROM playeritems WHERE ownerguid ='$check[$i]'";
		$temp = mysql_query($sql) or die(error(mysql_error()));
		for ($k=1; $k<=mysql_num_rows($temp); $k++){
			$item_ins = mysql_fetch_row($temp) or die(error(mysql_error()));
			$sql = "DELETE FROM playeritems WHERE guid = '$item_ins[0]'";
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
 break;

 //cleaning guilds
 case "guild":
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
		$sql = "DELETE FROM guilds WHERE guildid = '$check[$i]'";
		$query = mysql_query($sql) or die(error(mysql_error()));
		
		$sql = "DELETE FROM guild_ranks WHERE guildid = '$check[$i]'";
		$query = mysql_query($sql) or die(error(mysql_error()));
		
		$sql = "DELETE FROM guild_data WHERE guildid = '$check[$i]'";
		$query = mysql_query($sql) or die(error(mysql_error()));
		
		$sql = "DELETE FROM guild_logs WHERE guildid = '$check[$i]'";
		$query = mysql_query($sql) or die(error(mysql_error()));
		}
	$del_guilds++;
	}
 break;
 
default:
 header("Location: cleanup.php?error=1");
 exit();
}

 mysql_close();

 $output .= "<center>";
 if ($type == "guild") {
	if (!$del_guilds) $output .= "<h1><font class=\"error\">No Guilds deleted!</font></h1>";
		else $output .= "<h1><font class=\"error\">Total <font color=blue>$del_guilds</font> Guild(s) deleted!</font></h1>";
} else {
 if (($deleted_acc+$deleted_chars) == 0) $output .= "<h1><font class=\"error\">No Accounts/Characters deleted!<br/>Permission Err?</font></h1>"; 
   else {
	$output .= "<h1><font class=\"error\">Total <font color=blue>$deleted_acc</font> Account(s) deleted!</font></h1><br/>";
	$output .= "<h1><font class=\"error\">Total <font color=blue>$deleted_chars</font> Character(s) deleted!</font></h1>";
	}
}
 $output .= "<br/><br/>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
			makebutton("Back Cleanning", "cleanup.php", "200");
	 $output .= "</td>
          </tr>
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
default: //no error
   $output .= "<h1>Clean Database</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "run_cleanup":
	run_cleanup();
	break;
case "docleanup":
	docleanup();
	break;
default:
    cleanup();
}

require_once("footer.php");
?>