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
		$HTML .= "<legend> Ban Checker </legend>";
		if ($ShowWarning)
		{
			$HTML .= "<fieldset><legend> Warning: </legend>";
					$HTML .= "<br /> This will check and see if you are banned!";
					$HTML .= "<br /";
					$HTML .= "<br />It will tell you for how long and what reason.</b> ";
			$HTML .= "</fieldset>";
		}
		$HTML .= "<br />";
		$HTML .= "<form method='post' action='bancheck.php?m=submit'>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<tr><td>Username:</td><td> <input type='text' name='Username' id='Username' /></td></tr>";
			$HTML .= "<tr><td>Password:</td><td> <input type='password' name='password' id='password' /></td></tr>";
			$HTML .= "<tr><td></td><td><input type='submit' name=submit value=Check /></td></tr>";
		$HTML .= "</table>";
		$HTML .= "</form>";
		$HTML .= "</fieldset>";
	} else if ($MODE == "submit")
	{
		// Being system of checks.
		$Username = htmlspecialchars(preg_replace($search, '', $_POST['Username']), ENT_QUOTES);
		$Password = htmlspecialchars(preg_replace($search, '', $_POST['password']), ENT_QUOTES);
		$ip = $_SERVER['REMOTE_ADDR'];
		$Username = mysql_real_escape_string($Username);
		$Password = mysql_real_escape_string($Password);
		$HTML .= "<p>";

$ban == false;
$ipban == false;
$mute == false;

		if ($Username == null || $Username == "")
		{
				$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .="<br /><b> You must enter a username!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";

		}
		if ($Password == null || $Password == "")
		{
				$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .="<br /><b> You must enter a password!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";

		}
		
		// check if account exist
		$UserCheck = mysql_query("SELECT acct FROM accounts WHERE login = '$Username' AND password = '$Password'");
		if (mysql_num_rows($UserCheck) == 0 && $Username != "" && $Password != "")
		{
				$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> There is no such account. Please try again!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";

		}

		$BanCheck = mysql_query("SELECT banned FROM accounts WHERE login = '$Username' AND password = '$Password'");
		$banrow = mysql_fetch_array($BanCheck);
		
		if ($banrow['banned'] == 0 && $Username != "" && $Password != "")
		{
				$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> You are not currently banned!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";
}else{
$ban == true;
}


if($ban == true && $Username != "" && $Password != ""){
$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> You are currently banned!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";
		}
	
		
		$MuteCheck = mysql_query("SELECT muted FROM accounts WHERE login = '$Username' AND password = '$Password'");
		$muterow = mysql_fetch_array($MuteCheck);
		
		if ($muterow['muted'] == 0 && $Username != "" && $Password != "")
		{
				$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> You are not currently muted!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";
}else{
$mute == true;
}
if($mute == true && $Username != "" && $Password != ""){
$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> You are currently muted!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";
		}
	
		$ipbanCheck = mysql_query("SELECT ip FROM ipbans WHERE ip LIKE '%".$ip."%'");
		$ipbanrow = mysql_fetch_array($ipbanCheck);
		
		if($row2['ip'] == $ip && $Username != "" && $Password != "")
		{
				$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> Your Ip is not currently banned!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";
}else{
$ipban == true;
}

if($ipban == true && $Username != "" && $Password != ""){
$HTML .= "<fieldset>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /><b> Your Ip is currently banned!</b>";
						$HTML .= "</table>";
								$HTML .= "</fieldset>";
		}
	
		
		$HTML .= "</p>";
		
	$HTML .= "</body></html>";
	}
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
