<?php

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

	$MODE = htmlspecialchars(preg_replace($search, '', $_GET['m']), ENT_QUOTES);
	if (($MODE == null || !isset($MODE)))
	{
		$HTML .= "<fieldset>";
		$HTML .= "<legend> Change Password </legend>";
		if ($ShowWarning)
		{
			$HTML .= "<fieldset><legend> Warning: </legend>";
					$HTML .= "<br /> This will change the password for your in-game account!";
					$HTML .= "<br /";
					$HTML .= "<br />Enter your information to change your password below.</b> ";
			$HTML .= "</fieldset>";
		}
		$HTML .= "<br />";
		$HTML .= "<form method='post' action='changegamepass.php?m=submit'>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<tr><td>Username:</td><td> <input type='text' name='Username' id='Username' /></td></tr>";
			$HTML .= "<tr><td>Password:</td><td> <input type='password' name='password' id='password' /></td></tr>";
			$HTML .= "<tr><td>Retype Password:</td><td> <input type='password' name='passconf' id='passconf' /></td></tr>";
			$HTML .= "<tr><td>New Password:</td><td> <input type='password' name='npass' id='npass' /></td></tr>";
			$HTML .= "<tr><td></td><td><input type='submit' /></td></tr>";
		$HTML .= "</table>";
		$HTML .= "</form>";
		$HTML .= "</fieldset>";
	} else if ($MODE == "submit")
	{
		// Being system of checks.
		$Username = htmlspecialchars(preg_replace($search, '', $_POST['Username']), ENT_QUOTES);
		$Password = htmlspecialchars(preg_replace($search, '', $_POST['password']), ENT_QUOTES);
		$PassConf = htmlspecialchars(preg_replace($search, '', $_POST['passconf']), ENT_QUOTES);
		$npass	  = htmlspecialchars(preg_replace($search, '', $_POST['npass']), ENT_QUOTES);
		$ip = $_SERVER['REMOTE_ADDR'];
		$Username = mysql_real_escape_string($Username);
		$Password = mysql_real_escape_string($Password);
		$PassConf = mysql_real_escape_string($PassConf);
		$npass	  = mysql_real_escape_string($npass);
		$HTML .= "<p>";
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
		if ($npass == null || $npass == "")
		{
			$HTML .="<br /><b> You must enter an new password!</b>";
			$Continue = false;
		}
		// check if aaccount exist
		$UserCheck = mysql_query("SELECT acct FROM accounts WHERE login = '$Username' AND password = '$Password'");
		if (mysql_num_rows($UserCheck) == 0)
		{
			$HTML .= "<br /><b> There is no such account. Please try again!</b>";
			$Continue = false;
		}
		$HTML .= "</p>";
		if ($Continue)
		{
			mysql_query("UPDATE accounts SET password = '$npass' WHERE login = '$Username'")
						or die("Error changing your password!");
			// Check to make sure password was changed
			$Check = mysql_query("SELECT password FROM accounts WHERE password = '$npass'");
			$HTML .= "<p>";
			if (mysql_num_rows($Check) > 0)
			{
				$HTML .= "<br />Your password was succesfully changed!";
			} else
			{
				$HTML .= "<br />There was an error changing your password! Please report this on the forums.";
			}
			$HTML .= "</p>";
		} else
		{
			$HTML .= "<fieldset>";
			$HTML .= "<legend> Change Password </legend>";
			if ($ShowWarning)
			{
				$HTML .= "<fieldset><legend> Warning: </legend>";
					$HTML .= "<br /> Go back and try again!";
				$HTML .= "</fieldset>";
			}
			$HTML .= "<form method='post' action='game_acc.php?m=submit'>";
			$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
					$HTML .= "<br /> This will change the password for your in-game account!";
					$HTML .= "<br /";
					$HTML .= "<br />Enter your information to change your password below.</b> ";
			$HTML .= "</table>";
			$HTML .= "</form>";
			$HTML .= "</fieldset>";
		}
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
