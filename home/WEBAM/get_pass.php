<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 22.12.2007 inital version (1.24)
 * Author: gmaze
 * Copyright: gmaze
 * Email: *****
 * License: GNU General Public License (GPL)
 */
 
require_once("header.php");

//perform the password retreival
function doget_pass() {
  global $output,$dbhost,$dbuser,$dbpass,$acct_db;
  
//make sure posts not empty
 if ((empty($_POST['login']))||(empty($_POST['email']))) {
   header("Location: get_pass.php?error=1");
   exit();
 }
 
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 
 $user_name =  quote_smart($_POST['login']);
 $sql = "SELECT email FROM accounts WHERE login = '$user_name'";
 $result = mysql_query($sql) or die(error(mysql_error()));
 $mail = mysql_result ($result, 0, 'email');
 $numrows = mysql_num_rows($result);
 
//if no rows, error in fetching account data
	if ($numrows == 0) {
	  mysql_close();
	  header("Location: get_pass.php?error=2");
			exit();
	} 

	$email = quote_smart($_POST['email']);
	
	if ($email != $mail) {
      mysql_close();
      header("Location: get_pass.php?error=4");
        exit();
  } else {
    $query = "SELECT password FROM accounts WHERE login = '$user_name'";
    $res = mysql_query($query) or die(error(mysql_error()));
    $pass = mysql_result ($res, 0, 'password');
      mysql_close();
 $output .= "
<center>
<fieldset style=\"width: 550px;\">
	<legend>Password Display</legend>
   <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
    <tr align=\"center\">
      <td><font size=\"4\"><a href=\"login.php\">Sign-in</a></font></td></tr><br />
    <tr>
	   <td><font size=\"2\">Your password is:<font color=\"darkred\"> $pass</td></font></font>
	  </tr>
   </table><br/></fieldset><br/><br/>
</center>";
  }
}
	
// Print form
function get_pass() {
global $output;
 $output .= "
<center>
<fieldset style=\"width: 550px;\">
	<legend>Input Your Information</legend>
   <form method=\"POST\" action=\"get_pass.php?action=doget_pass\">
   <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
      <tr align=\"left\">
	   <td>
        <div class=\"large\">Account Name:</div>
          <input type=\"text\" name=\"login\" size=\"29\" maxlength=\"25\" />
        <div class=\"large\">Email Address:</div>
          <input type=\"text\" name=\"email\" size=\"29\" maxlength=\"25\" />
          <input type=\"submit\" value=\"Get Password\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /><br/>
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
   $output .=  "<h1><font class=\"error\">That account does not exist!</font></h1>";
   break;
case 2:
   $output .=  "<h1><font class=\"error\">Error in selecting an account!</font></h1>";
   break;
case 4:
   $output .=  "<h1><font class=\"error\">That email does not match that account!</font></h1>";
   break;
default: //no error
    $output .=  "<h1>Password Retreival:</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action){
case "doget_pass":
   doget_pass();
   break;
default:
    get_pass();
}

require_once("footer.php");
?>