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

// Login
function dologin(){
global $dbhost,$dbuser,$dbpass,$acct_db;

//make sure both username and pass not empty
 if ((empty($_POST['user']))||(empty($_POST['pass']))) {
   header("Location: login.php?error=2");
   exit();
 }
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 $user_name  = quote_smart($_POST['user']);
 $pass  = quote_smart($_POST['pass']);

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct,gm,banned FROM accounts WHERE login ='$user_name' AND password = '$pass'";
 $result = mysql_query($sql) or die(error(mysql_error()));

//if we have 0 results == no user
 if (mysql_num_rows($result) == 1) {
	 if (mysql_result($result, 0, 'banned')) {
			mysql_close();
			header("Location: login.php?error=3");
			exit();
	  } else if (mysql_result($result, 0, 'banned')) {
						mysql_close();
						header("Location: login.php?error=4");
						exit();
 		        } else {
   			//login succes
			include "../mainconfig.php";
			      $_SESSION['user_id'] = mysql_result($result, 0, 'acct');
			      $_SESSION['uname'] = $user_name;
                              if (mysql_result($result, 0, 'gm') == $user_level_0)
                                  {
			           $_SESSION['user_lvl'] = 0;
			                            }
			                        if (mysql_result($result, 0, 'gm') == $user_level_1)
                                  {
			           $_SESSION['user_lvl'] = 1;
			                            }
			                        if (mysql_result($result, 0, 'gm') == $user_level_2)
                                  {
			           $_SESSION['user_lvl'] = 2;
                                  }
                              if (mysql_result($result, 0, 'gm') == $user_level_3)
                                  {
			           $_SESSION['user_lvl'] = 3;
                                  }
                              if (mysql_result($result, 0, 'gm') == $user_level_3)
                                  {
			           $_SESSION['user_lvl'] = 3;
                                  }

			      mysql_close();
   			      header("Location: motd.php");
   				  exit();
			     }
   } else {

   mysql_close();

   //failed to login
   header("Location: login.php?error=1");
   exit();
 }
}


// Print login form
function login(){
global $output;
 $output .= "
<center>
<fieldset style=\"width: 380px;\">
	<legend>Login</legend>
   <form method=\"POST\" action=\"login.php?action=dologin\">
   <table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
      <tr align=\"left\">
	   <td>
          <div class=\"large\">Username:</div>
          <input type=\"text\" name=\"user\" size=\"37\" maxlength=\"15\" /><br/>
           <div class=\"large\">Password:</div>
          <input type=\"password\" name=\"pass\" size=\"29\" maxlength=\"25\" />
          <input type=\"submit\" value=\"Login\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /><br/><br/>
	  <a style='line-height: 1px' href=\"form.php\">Not Registered?</a><br/><br/>
	  <a style='line-height: 1px' href=\"get_pass.php\">Forgot Your Password?</a>
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
   $output .=  "<h1><font class=\"error\">Invalid Username and/or Password!</font></h1>";
   break;
case 2:
   $output .=  "<h1><font class=\"error\">This account does not exist!</font></h1>";
   break;
case 3:
   $output .=  "<h1><font class=\"error\">Your Account Has Been Banned. Please Contact An Administrator</font></h1>";
   break;
case 4:
   $output .=  "<h1><font class=\"error\">Your Account Has Been Suspended. Please Contact An Administrator</font></h1>";
   break;
case 5:
   $output .=  "<h1><font class=\"error\">You Do Not Have The Correct Permissions To View This Page!</font></h1>";
   break;
case 6:
   $output .=  "<h1><font class=\"error\">Your Account Has Successfully Been Created.</font></h1>";
   break;
default: //no error
    $output .=  "<h1>Enter Your Username and Password:</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action){
case "dologin":
   dologin();
   break;
default:
    login();
}

require_once("footer.php");
?>