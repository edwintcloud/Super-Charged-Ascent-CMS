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

//edit your account info
function edit_user() {
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_name;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct,login,password,email,gm FROM accounts WHERE login ='$user_name'";
 $result = mysql_query($sql) or die(error(mysql_error()));

 //make sure we return only 1 acct
 if (mysql_num_rows($result) == 1) {
 $acc = mysql_fetch_row($result) or die(error(mysql_error()));
 $output .= "<center>
 <fieldset style=\"width: 550px;\">
	<legend>Edit Your Account</legend>
    <form method=\"GET\" action=\"edit.php\">
	<input type=\"hidden\" name=\"action\" value=\"doedit_user\" />
    <table width=\"400\" class=\"flat\">
      <tr>
        <td>ID</td>
        <td>$acc[0]</td>
      </tr>
      <tr>
        <td>Username</td>
        <td>$acc[1]</td>
      </tr>
	  <tr>
        <td>Password</td>
        <td><input type=\"text\" name=\"new_pass\" size=\"43\" maxlength=\"25\" value=\"$acc[2]\" /></td>
      </tr>
      <tr>
        <td>Mail</td>
        <td><input type=\"text\" name=\"new_mail\" size=\"43\" maxlength=\"225\" value=\"$acc[3]\" /></td>
      </tr>
	  <tr>
        <td>GMlevel</td>
        <td>$acc[4]</td>
      </tr>
	  <tr>
        <td>Characters</td>
        <td>";

	//get characters for this acc.
	$acc_id = mysql_result($result, 0, 'acct');

	mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

	$sql = "SELECT guid,name FROM `characters` WHERE acct='$acc_id'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	while ($char = mysql_fetch_array($query))
		$output .= "<a href=\"char.php?id=$char[0]\">$char[1], </a>";

 $output .= "</td>
      </tr>
      <tr>
        <td><input type=\"submit\" value=\"Update Data\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
       <td>";
		makebutton("Delete Account", "edit.php?action=delete_user","150");
		makebutton("Back", "index.php","150");
 $output .= "</td></tr>
    	</table>
    </form></fieldset><br/><br/></center>";
 } else error("Unexpected Error!<br/>Wrong Value Passed.");
 
 mysql_close();
}

//perform the edit
function doedit_user() {
 global $dbhost, $dbuser, $dbpass, $acct_db, $user_name;

 if ((empty($_GET['new_pass']))||(empty($_GET['new_mail']))) {
   header("Location: edit.php?error=1");
   exit();
 } 
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 $new_pass = quote_smart($_GET['new_pass']);
 $new_mail = quote_smart(trim($_GET['new_mail']));
	//make sure the mail is valid mail format
 require_once("defs/valid_lib.php");
 if ((!is_email($new_mail))||(strlen($new_mail)  > 224)) {
     	header("Location: edit.php?error=2");
     	exit();
   	}

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "UPDATE accounts SET email='$new_mail', password ='$new_pass' WHERE login = '$user_name'";
 $query = mysql_query($sql) or die(error(mysql_error()));

 if (mysql_affected_rows() <> 0) {
	mysql_close();
	header("Location: edit.php?error=3");
    exit();
    } else {
		mysql_close();
		header("Location: edit.php?error=4");
     	exit();
	}
}

//delete account
function delete_user() {
 global $output, $user_name;

 $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
 $output .= "<font class=\"bold\">Username : '$user_name' and all it's Characters Will be erased from DB!</font><br/><br/>";

 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("YES", "edit.php?action=dodelete_user","120");
 $output .= "     </td>
            <td>";
		makebutton("NO", "edit.php","120");
 $output .= "     </td>
          </tr>
        </table></center><br/>";
}

//perform the delete
function dodelete_user() {
 global $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_name;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct FROM accounts WHERE login = '$user_name'";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $acc_id = mysql_result($query, 0, 'acct');

 require_once("defs/del_tab.php");

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
 $sql = "SELECT guid FROM `characters` WHERE acct='$acc_id'";
 $result = mysql_query($sql) or die(error(mysql_error()));
 while ($row = mysql_fetch_array($result)) {

		$sql = "SELECT entry FROM playeritems WHERE ownerguid ='$row[0]'";
		$temp = mysql_query($sql) or die(error(mysql_error()));
		for ($k=1; $k<=mysql_num_rows($temp); $k++){
			$item_ins = mysql_fetch_row($temp) or die(error(mysql_error()));
			$sql = "DELETE FROM playeritems WHERE ownerguid = '$item_ins[0]'";
			$query = @mysql_query($sql);
		}
		
	foreach ($tab_del_user_mangos as $value){
			$sql = "DELETE FROM $value[0] WHERE $value[1] = '$row[0]'";
			$query = mysql_query($sql) or die(error(mysql_error()));
			}
 }
 mysql_free_result($result);

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 foreach ($tab_del_user_realmd as $value){
		$sql = "DELETE FROM $value[0] WHERE $value[1] = '$acc_id'";
		$query = mysql_query($sql) or die(error(mysql_error()));
		}
 if (mysql_affected_rows() <> 0){
	mysql_close();
	include("logout.php");
    exit();
    } else {
		mysql_close();
		header("Location: edit.php?error=5");
     	exit();
	}
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
   $output .= "<h1><font class=\"error\">Please Provide a Valid Email Address!</font></h1>";
   break;
case 3:
   $output .= "<h1><color=#228B22><font class=\"error\">Update action success!</font></color></h1>";
   break;
case 4:
   $output .= "<h1><font class=\"error\">Update action Error! (maybe none of the fields changed?)</font></h1>";
   break;
case 5:
   $output .= "<h1><font class=\"error\">Unexpected Delete Error.</font></h1>";
   break;
default: //no error
   $output .= "<h1>Edit User</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "doedit_user": 
	doedit_user();
	break;
case "delete_user":
	delete_user(); 
	break;
case "dodelete_user":
 	dodelete_user();
	break;
default:
    edit_user();
}

require_once("footer.php");
?>