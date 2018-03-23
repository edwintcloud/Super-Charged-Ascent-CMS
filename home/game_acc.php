<?php
include "mainconfig.php";
?>
<?php

//Recoding noriac


//includes
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
?>
<html>
<head><title><?php echo "$conf[browsertitle]"; $d = P;?></title><link rel="StyleSheet" type="text/css" MEDIA="screen" href="main.css"> </head>
<body>
<center>

<br></br>

<div class="container">


<div class="container_body">
<div class="container_top">
<h1><?php echo "$lang[header]"; $lampstn="- ";?></h1>
</div>
<div class="container_left">
</div>
<div class="container_right">
</div>


<?php
/* Recoded Page for ascent
  * This is just a simple registration page for Ascent
  */

// Settings -------------------------------------------------------------------------------
include_once "game_config.php";
// Database Connection -------------------------------------------------------------
// You should not need to change these two, unless you know what you are doing and know you need to.
	$Con 	= mysql_connect($Host,$Username,$Password) or die ("Error connecting to the mysql server!");
	$DB		= mysql_select_db($Database) or die ("Error selecting the database!");
// Start forming the page -------------------------------------------------------------
	$HTML = ""; // This will be used to store all the HTML and print it out in the end.
// HTML Header Var.
	$HTML .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" 
					\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
	$HTML .= "<html><head><title>$Title</title></head>";
	$HTML .= "<body>";
// Get the mode we are in.
	// Anti-Script Injection
		$search = array(
							'@<script[^>]*?>.*?</script>@si',  // Strip out javascript
			                '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			                '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			                '@<![\s\S]*?--[ \t\n\r]*>@'        // Strip multi-line comments including CDATA
						);

$downlink .= '<a target="_blank" href="/home/files/InstallWow.exe">WOTLK</a>';

	$MODE = htmlspecialchars(preg_replace($search, '', $_GET['m']), ENT_QUOTES);
	if (($MODE == null || !isset($MODE)) && $AllowReg)
	{
		$HTML .= "<fieldset>";
		$HTML .= "<legend> Create Account </legend>";
		if ($ShowWarning)
		{
			$HTML .= "<fieldset><legend> Warning: </legend>";
					$HTML .= "<br /> You must have $downlink to play on this server.";
					$HTML .= "<br /";
					$HTML .= "<br />Set your realmlist to: <b>set realmlist $realm_address</b> ";
			$HTML .= "</fieldset>";
		}
		$HTML .= "<br />";
		$HTML .= "<form method='post' action='game_acc.php?m=submit'>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<tr><td>Username:</td><td> <input type='text' name='Username' id='Username' /></td></tr>";
			$HTML .= "<tr><td>Password:</td><td> <input type='password' name='password' id='password' /></td></tr>";
			$HTML .= "<tr><td>Retype Password:</td><td> <input type='password' name='passconf' id='passconf' /></td></tr>";
			$HTML .= "<tr><td>E-Mail:</td><td> <input type='text' name='email' id='email' />";
 $HTML .= "<br></br><tr><td>Security Image: </td><td><img src=\"CaptchaSecurityImages.php\" />";
 $HTML .= "<br>TIP: press F5 untill you get an easy image";
 $HTML .= "</td>";
 $HTML .= "</tr>";
 $HTML .= "<tr><td>Security Code: </td><td><input name=\"security_code\" type=\"text\" class=\"button\" id=\"security_code\" />";
 $HTML .= "</td>";
 $HTML .= "</tr>";
			// Get the TBC settings.
			if ($AllowTBC && !$ForceTBC)
			{
				$HTML.= "<tr><td>Check if WOTLK:</td><td><input type='checkbox' name='tbc' id='tbc' /></td></tr>";
			}
			$HTML .= "<tr><td></td><td><input type='submit' /></td></tr>";
		$HTML .= "</table>";
		$HTML .= "</form>";
		$HTML .= "</fieldset>";
	} else if ($MODE == "submit" && $AllowReg)
	{
		// Being system of checks.
		$Username = htmlspecialchars(preg_replace($search, '', $_POST['Username']), ENT_QUOTES);
		$Password = htmlspecialchars(preg_replace($search, '', $_POST['password']), ENT_QUOTES);
		$PassConf = htmlspecialchars(preg_replace($search, '', $_POST['passconf']), ENT_QUOTES);
		$Email	  = htmlspecialchars(preg_replace($search, '', $_POST['email']), ENT_QUOTES);
		$ip = $_SERVER['REMOTE_ADDR'];
		$Username = mysql_real_escape_string($Username);
		$Password = mysql_real_escape_string($Password);
		$PassConf = mysql_real_escape_string($PassConf);
		$Email	  = mysql_real_escape_string($Email);
		$HTML .= "<p>";
		if ($AllowTBC && !$ForceTBC)
		{
			$TBC = htmlspecialchars(preg_replace($search, '', $_POST['tbc']), ENT_QUOTES);
		}
		$Continue = true;
		if ($Username == null || $Username == "")
		{
			$HTML .="<br /><b> You must enter a username!</b>";
			$Continue = false;
		}
		if ($Password == null || $Password == "")
		{
			$HTML .="<br /><b> You must enter a password!</b>";
			$Continue = false;
		}
		if ($PassConf == null || $PassConf == "")
		{
			$HTML .="<br /><b> You must enter a confirmation password!</b>";
			$Continue = false;
		}
		if ($PassConf != $Password)
		{
			$HTML .="<br /><b> Your passwords did not match!</b>";
			$Continue = false;
		}
		if ($Email == null || $Email == "")
		{
			$HTML .="<br /><b> You must enter an e-mail!</b>";
			$Continue = false;
		}
		// check if already registered.
		$UserCheck = mysql_query("SELECT login FROM accounts WHERE login = '$Username'");
		if (mysql_num_rows($UserCheck) > 0)
		{
			$HTML .= "<br /><b> This username is already registered, please select another</b>";
			$Continue = false;
		}
		// Check if this IP has more than the accounts per IP limit
		$UserCheck = mysql_query("SELECT login FROM accounts WHERE lastip = '$ip'");
		if (mysql_num_rows($UserCheck) > $AccsPerIP)
		{
			$HTML .= "<br /><b> You have reached your maximum amount of accounts, which is currently: $AccsPerIP </b>";
			$Continue = false;
		}
		// Check to make sure the e-mail is valid
		if (!ereg("^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$",$Email))
		{
			$HTML .= "<br /><b> You have entered an invalid e-mail! E-Mails should be in the form user@domain.com</b>";
			$Continue = false;
		}
		if(!session_id())
    session_start();
    if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
        // Insert you code for processing the form here, e.g emailing the submission, entering it into a database. <br>
        unset($_SESSION['security_code']);
    } else {
        // Insert your code for showing an error message here<br>
$HTML .= "<br /><b> You have entered an invalid Security Code. Go back and try again!</b>";
			$Continue = false;
    }
		$HTML .= "</p>";
		if ($Continue)
		{
			// Register the account.
			if ($ForceTBC)
			{
				$Flag = 24;
			} else if ($AllowTBC)
			{
				if ($TBC)
				{
					$Flag = 24;
				} else
				{
					$Flag = 0;
				}
			}
			mysql_query("INSERT INTO `accounts` (`login`,`password`,`email`,`lastip`,`gm`,`banned`,`flags`) VALUES ('$Username','$Password','$Email','$ip','0','0','24')")
						or die("Error creating your character!");
			// Check to make sure it was actually registered
			$Check = mysql_query("SELECT login FROM accounts WHERE login = '$Username'");
			$HTML .= "<p>";
			if (mysql_num_rows($Check) == 1)
			{
				$HTML .= "<br />Your account was registered!<br><b>It may take up to 5 to 10 min before to become active</b>";
			} else
			{
				$HTML .= "<br />There was an error registering your account!";
			}
			$HTML .= "</p>";
		} else
		{
			$HTML .= "<br></br>";
			$HTML .= "<form method='post' action='game_acc.php?m=submit'>";

				// Get the TBC settings.
				if ($AllowTBC && !$ForceTBC)
				{
					$HTML.= "";
				}
				$HTML .= "<tr><td></td><td><input type='submit' /></td></tr>";
			$HTML .= "</table>";
			$HTML .= "</form>";
			$HTML .= "</fieldset>";
		}
	} else if (!$AllowReg)
	{
		$HTML .= "<p><b> Sorry, but registration is currently closed, please try again later </b></p>";
	}
	$HTML .= "</body></html>";
	
	echo $HTML;
	mysql_close($Con);
	
?>
</div>
<div class="container_bottom">
</div>
</body>
</html>
<?php mysql_close; ?>

</center>

<?php

mysql_connect("$db_host",

"$db_user","$db_pass");

mysql_select_db("$db_name");

?> 


<?php
require_once "footer.php";
?>
