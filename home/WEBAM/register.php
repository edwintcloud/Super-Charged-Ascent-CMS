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

// PRINT FORM
function register(){
 global $output;
 $output .= "<center>
	<fieldset style=\"width: 550px;\">
	<legend>Create Account</legend>
	<form method=\"POST\" action=\"register.php?action=doregister\">
    <table class=\"flat\">
	<tr>
  	 <td valign=\"top\">Username:</td>
   	<td><input type=\"text\" name=\"username\" size=\"45\" maxlength=\"14\" /><br/>
		Use English characters and numbers Only.<br/>
		Min. Length 4 | Max. Length 14.<br/></td>
	</tr>
	<tr>
  	 <td valign=\"top\">Password:</td>
   	<td><input type=\"password\" name=\"pass1\" size=\"45\" maxlength=\"25\" onChange=\"same(pass1.value, pass2.value);\" /></td>
	</tr>
	<tr>
  	 <td valign=\"top\">Confirm Password:</td>
   	<td><input type=\"password\" name=\"pass2\" size=\"45\"  maxlength=\"25\" onChange=\"same(pass1.value, pass2.value);\" /><br/>
	Min. Length 4 | Max. Length 25.<br/>
	</td>
	</tr>
	<tr>
  	 <td valign=\"top\">E-mail:</td>
  	 <td><input type=\"text\" name=\"email\" size=\"45\" maxlength=\"225\" /><br/>
	 Please make sure to use valid e-mail address.</td>
	</tr>
	<tr>
   	 <td><input type=\"submit\" value=\"Create Account\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
	 <td>";
	  $output .= " <table class=\"hidden\">
          <tr>
            <td>";
		makebutton("Back", "javascript:window.history.back()", "328");
		$output .= "</td>
          </tr>
        </table>";
 $output .= "</td>
    </table>
	</form></fieldset>
	<br/><br/></center>";
}

//register
function doregister(){
 global $dbhost,$dbuser,$dbpass,$acct_db,$disable_acc_creation,$limit_acc_per_ip;
	
//make sure all fields have post information
 if ((empty($_POST['pass1']))||(empty($_POST['pass2']))||(empty($_POST['email']))||(empty($_POST['username']))) {
   header("Location: register.php?err=1");
   exit();
 }
 
//check global to enable/disable acct creation
 if ($disable_acc_creation){
	header("Location: register.php?err=4");
 	exit();
 }
 
//get ip
 if (getenv('HTTP_X_FORWARDED_FOR')) $last_ip = getenv('HTTP_X_FORWARDED_FOR');	
    	else $last_ip = getenv('REMOTE_ADDR');
    	
//make sure passwords are identical
 if ($_POST['pass1'] == $_POST['pass2']){
	mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
	
	$user_name = quote_smart(trim($_POST['username']));
	$pass = quote_smart($_POST['pass1']);

//make sure username/pass at least 4 chars long and less than max
	if ((strlen($user_name) < 4) || (strlen($user_name) > 15) || (strlen($pass) < 4) || (strlen($pass) > 27)){
			mysql_close();
     		header("Location: register.php?err=5");
     		exit();
   		}

	require_once("defs/valid_lib.php");
	
//make sure it doesnt contain non english chars.
	if (!alphabetic($user_name)) {
			mysql_close();
     		header("Location: register.php?err=6");
     		exit();
   		}

//make sure the email is valid format
	$mail = quote_smart(trim($_POST['email']));
	if ((!is_email($mail))||(strlen($mail)  > 224)) {
			mysql_close();
     		header("Location: register.php?err=7");
     		exit();
   		}

//check ip for limit acct creation 	
  if($limit_acc_per_ip) $per_ip = "OR lastip=$last_ip";
		    else $per_ip = "";
		  
	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

//check for ip ban		
	$sql = "SELECT ip FROM ipbans WHERE ip='$last_ip'";
	$result = mysql_query($sql) or die(error(mysql_error()));
//ip is in ban list
	if (mysql_num_rows($result)){
			mysql_close();
    	 	header("Location: register.php?err=8&usr=$last_ip");
    	 	exit();
	}
	

//check for duplicate user/mail  	
	$sql = "SELECT login,email FROM accounts WHERE login='$user_name' OR email='$mail' '$per_ip'";
	$result = mysql_query($sql) or die(error(mysql_error()));

//user/mail already exists
	if (mysql_num_rows($result)){
			mysql_close();
    	 	header("Location: register.php?err=3&usr=$user_name");
    	 	exit();
	} else {

//place acct data into database	
 		$sql = "INSERT INTO accounts (login,password,gm,email,banned,lastip,flags) 
 				VALUES ('$user_name','$pass','0','$mail',0,'$last_ip','24')";
		$result = mysql_query($sql) or die(error(mysql_error()));
		mysql_close();
		
//let them know acct successfully created
		if ($result) {
			header("Location: login.php?error=6");
			exit();
			}
 		}
} else { 
 	header("Location: register.php?err=2");
 	exit();
    }
}

//main
$output .= "
    <script type=\"text/javascript\">
      //testing pass fields wherever they are same
      <!-- Hide the script
function same(value1, value2)
{
  if (((value1 != null) ||
       (value1 != \"\")) &&
       value2 != \"\" &&
       value1 != value2)      
  {
       alert(\"The Passwords must be identical.\");
       return false;
  }
  return true;
}
// end hiding -->
    </script>";

//error processing
if (isset($_GET['err'])) $err = $_GET['err'];
    else $err = NULL;

if (isset($_GET['usr'])) $usr = $_GET['usr'];
    else $usr = NULL;
	
$output .=  "<div class=\"top\">";
switch ($err) {
case 2: 
   $output .= "<h1><font class=\"error\">The passwords provided do not match.</font></h1>";
   break;
case 3: 
   $output .= "<h1><font class=\"error\">The user: ".$usr." Already Exists<br/>Or another user registered with same email address.</font></h1>";
   break;
case 4: 
   $output .= "<h1><font class=\"error\">Sorry, Account registration is currently Closed.</font></h1>";
   break;
case 5: 
   $output .= "<h1><font class=\"error\">Username/Password must be 4-to-15 characters long!</font></h1>";
   break;
case 6: 
   $output .= "<h1><font class=\"error\">Username must contain uppercase [A-Z] lowercase [a-z] and digits [0-9] only!</font></h1>";
   break;
case 7: 
   $output .= "<h1><font class=\"error\">Please provide a valid email address!</font></h1>";
   break;
case 8: 
   $output .= "<h1><font class=\"error\">IP Address you use is Banned ($usr)<br/>Contact Server Administrator.</font></h1>";
   break;
default:
   $output .= "<h1><font class=\"error\">Please fill out all fields.</font></h1>";
}
$output .=  "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action){
case "doregister":
   doregister();
   break;
default:
    register();
}

require_once("footer.php");
?>