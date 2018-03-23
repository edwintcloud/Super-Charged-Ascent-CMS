<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 22.12.2007 inital version (1.20)
 * Author: gmaze
 * Copyright: gmaze
 * Email: *****
 * License: GNU General Public License (GPL)
 */
 
require_once("header.php");
valid_login(0);

//perform the unstuck operation
function do_unstuck(){
global $dbhost,$dbuser,$dbpass,$acct_db,$char_db,$user_name;

//make sure posts not empty
 if (empty($_POST['char'])) {
   header("Location: char_unstuck.php?error=1");
   exit();
 }
 
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 
 $sql = "SELECT acct FROM accounts WHERE login = '$user_name'";
 $result = mysql_query($sql) or die(error(mysql_error()));
 $id = mysql_result ($result, 0, 'acct');
 $numrows = mysql_num_rows($result);
 
//if no rows, error in fetching account data
	if ($numrows == 0) {
	  mysql_close();
	  header("Location: char_unstuck.php?error=5");
			exit();
	}
 
 $char = quote_smart($_POST['char']);

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $query = "SELECT acct FROM characters WHERE name = '$char'";
 $res = mysql_query($query) or die(error(mysql_error()));
 $numrow = mysql_num_rows($res);
 
 if ($numrow != 1) {
    mysql_close();
	  header("Location: char_unstuck.php?error=1");
			exit();
	}
	
 $acct = mysql_result ($res, 0, 'acct');

//lets ensure the character belongs to the owner
 if ($id != $acct){
    mysql_close();
    header("Location: char_unstuck.php?error=4");
			exit();
  } else {
    $sql = "UPDATE characters SET positionX = bindpositionX, positionY = bindpositionY, positionZ = bindpositionZ, mapId = bindmapId, zoneId = bindzoneId WHERE name = '$char'";
    $result = mysql_query($sql) or die(error(mysql_error()));
  }
  
//output result
	if ($result) {
	  mysql_close();
		header("Location: char_unstuck.php?error=2");
			exit();
	}
}

// Print form
function unstuck(){
global $output;
 $output .= "
<center>
<fieldset style=\"width: 550px;\">
	<legend>Unstuck</legend>
   <form method=\"POST\" action=\"char_unstuck.php?action=do_unstuck\">
   <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
      <tr align=\"left\">
	   <td>
            <div class=\"large\">Character:</div>
          <input type=\"character\" name=\"char\" size=\"29\" maxlength=\"25\" />
          <input type=\"submit\" value=\"Execute\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /><br/>
      </td>
	  </tr>
   </table>
   </form><br/></fieldset><br/><br/>
</center>";
}

// MAIN
if(isset($_GET['error'])) $err = $_GET['error'];
	else $err = NULL;

$output .= "<div class=\"top\">";
switch ($err) {
case 1:
   $output .=  "<h1><font class=\"error\">You did not input a valid character name!</font></h1>";
   break;
case 2:
   $output .=  "<h1><font class=\"error\">Character successfully unstuck!</font></h1>";
   break;
case 4:
   $output .=  "<h1><font class=\"error\">That character does not belong to your account!</font></h1>";
   break;
case 5:
   $output .=  "<h1><font class=\"error\">Error in selecting an account!</font></h1>";
   break;
default: //no error
    $output .=  "<h1>Enter your character name:</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action){
case "do_unstuck":
   do_unstuck();
   break;
default:
    unstuck();
}

require_once("footer.php");
?>