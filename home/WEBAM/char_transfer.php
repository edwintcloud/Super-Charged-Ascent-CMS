<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 25.12.2007 inital version (1.30)
 * Author: gmaze
 * Copyright: gmaze
 * Email: *****
 * License: GNU General Public License (GPL)
 */
 
require_once("header.php");
valid_login(0);

//perform the unstuck operation
function do_trans(){
global $dbhost,$dbuser,$dbpass,$acct_db,$char_db,$user_name;

//make sure posts not empty
 if ((empty($_POST['char1']))||(empty($_POST['char1_pass']))||(empty($_POST['char_name']))||(empty($_POST['char2']))||(empty($_POST['char2_pass']))) {
   header("Location: char_transfer.php?error=1");
   exit();
 }
 
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 
 $acct1 = quote_smart($_POST['char1']);
 $pass1 = quote_smart($_POST['char1_pass']);
 $char = quote_smart($_POST['char_name']);
 $acct2 = quote_smart($_POST['char2']);
 $pass2 = quote_smart($_POST['char2_pass']);
 
 $sql1 = "SELECT acct,banned FROM accounts WHERE login = '$acct1' AND password = '$pass1'";
 $result1 = mysql_query($sql1) or die(error(mysql_error()));
 $numrows1 = mysql_num_rows($result1);
 
//if no rows, error in fetching account data
	if ($numrows1 == 0) {
	  mysql_close();
	  header("Location: char_transfer.php?error=2");
			exit();
	}
 
 $ban1 = mysql_result ($result1, 0, 'banned');
 
//check for ban status
	if ($ban1 != 0) {
	  mysql_close();
	  header("Location: char_transfer.php?error=3");
			exit();
	}
	
 $id = mysql_result ($result1, 0, 'acct');

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $query = "SELECT acct,guid FROM characters WHERE name = '$char'";
 $res = mysql_query($query) or die(error(mysql_error()));
 $numrow = mysql_num_rows($res);
 
 if ($numrow != 1) {
    mysql_close();
	  header("Location: char_transfer.php?error=4");
			exit();
	}
	
 $acct = mysql_result ($res, 0, 'acct');
 $guid = mysql_result ($res, 0, 'guid');

//lets ensure the character belongs to the owner
 if ($id != $acct){
    mysql_close();
    header("Location: char_transfer.php?error=5");
			exit();
  }
  
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
  
  $sql2 = "SELECT acct,banned FROM accounts WHERE login = '$acct2' AND password = '$pass2'";
  $result2 = mysql_query($sql2) or die(error(mysql_error()));
  $numrows2 = mysql_num_rows($result2);
 
//if no rows, error in fetching account data
	if ($numrows2 == 0) {
	  mysql_close();
	  header("Location: char_transfer.php?error=6");
			exit();
	}
	
	$ban2 = mysql_result ($result2, 0, 'banned');
 
//check for ban status
	if ($ban2 != 0) {
	  mysql_close();
	  header("Location: char_transfer.php?error=7");
			exit();
	}

  $acct_new = mysql_result ($result2, 0, 'acct');
  
  mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

  $sql3 = "SELECT * FROM characters WHERE acct = '$acct_new'";
  $result3 = mysql_query($sql3) or die(error(mysql_error()));
  $numrows3 = mysql_num_rows($result3);
  
//if more than 9 characters, can not be transferred to that account
	if ($numrows3 > 9) {
	  mysql_close();
	  header("Location: char_transfer.php?error=8");
			exit();
	}

//actually transfer the character and items 
  $query = "UPDATE characters SET acct = '$acct_new' WHERE acct = '$id' AND name = '$char'";
  $query1 = "UPDATE playeritems SET ownerguid = '$acct_new' WHERE guid = '$guid'";
  $res = mysql_query($query) or die(error(mysql_error()));
  $res1 = mysql_query($query1) or die(error(mysql_error()));
  
//output a positive result
	if ($res && $res1) {
	  mysql_close();
		header("Location: char_transfer.php?error=9");
			exit();
	}
}

// Print form
function trans(){
global $output;
 $output .= "
<center>
<fieldset style=\"width: 550px;\">
  <legend>Character Transfer</legend>
  <form method=\"POST\" action=\"char_transfer.php?action=do_trans\">
   <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
    <tr align=\"left\">
	   <td><center><div class=\"large\">Original Account:</div>
      <input type=\"text\" name=\"char1\" size=\"29\" maxlength=\"25\" /></center><br />
      <center><div class=\"large\">Original Account Password:</div>
      <input type=\"text\" name=\"char1_pass\" size=\"29\" maxlength=\"25\" /></center><br /><br />
     </td>
     <td><center><div class=\"large\">New Account:</div>
      <input type=\"text\" name=\"char2\" size=\"29\" maxlength=\"25\" /></center><br />
      <center><div class=\"large\">New Account Password:</div>
      <input type=\"text\" name=\"char2_pass\" size=\"29\" maxlength=\"25\" /></center><br /><br />
	   </td>
    </tr>
    <tr>
     <td align=\"center\"><center><div class=\"large\">Character Name:</div>
      <input type=\"text\" name=\"char_name\" size=\"29\" maxlength=\"25\" /></center>
     </td>
     <td align=\"center\"><center>
      <input type=\"submit\" value=\"Execute\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></center>
	   </td>
    </tr>
   </table>
  </form><br/>
</fieldset><br/><br/>
</center>";
}

// MAIN
if(isset($_GET['error'])) $err = $_GET['error'];
	else $err = NULL;

$output .= "<div class=\"top\">";
switch ($err) {
case 1:
   $output .=  "<h1><font class=\"error\">Some Fields Left Blank!</font></h1>";
   break;
case 2:
   $output .=  "<h1><font class=\"error\">Error! Original Account or Password Incorrect!</font></h1>";
   break;
case 3:
   $output .=  "<h1><font class=\"error\">The Original Account is Banned!</font></h1>";
   break;
case 4:
   $output .=  "<h1><font class=\"error\">That Character Does Not Exist!</font></h1>";
   break;
case 5:
   $output .=  "<h1><font class=\"error\">That Character Does Not Belong To That Account!</font></h1>";
   break;
case 6:
   $output .=  "<h1><font class=\"error\">Error! Target Account or Password Incorrect!</font></h1>";
   break;
case 7:
   $output .=  "<h1><font class=\"error\">The Target Account is Banned!</font></h1>";
   break;
case 8:
   $output .=  "<h1><font class=\"error\">The Target Account Has The Maximum Number of Characters!<br />Transfer can not be completed.</font></h1>";
   break;
case 9:
   $output .=  "<h1><font class=\"error\">Character Successfully Transferred!</font></h1>";
   break;
default: //no error
    $output .=  "<h1>Enter the required information:</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action){
case "do_trans":
   do_trans();
   break;
default:
    trans();
}

require_once("footer.php");
?>