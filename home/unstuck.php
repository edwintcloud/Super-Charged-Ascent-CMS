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
		$HTML .= "<legend> Unstuck Tool </legend>";
		if ($ShowWarning)
		{
			$HTML .= "<fieldset><legend> Warning: </legend>";
					$HTML .= "<br /> You must be logged off the server in order for this tool to work.";
					$HTML .= "<br /";
					$HTML .= "<br />Please wait 1-2 minutes before logging back on.</b> ";
			$HTML .= "</fieldset>";
		}
		$HTML .= "<br />";
		$HTML .= "<form method='post' action='unstuck.php?m=submit'>";
		$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<tr><td>Username:</td><td> <input type='text' name='Username' id='Username' /></td></tr>";
			$HTML .= "<tr><td>Password:</td><td> <input type='password' name='password' id='password' /></td></tr>";
			$HTML .= "<tr><td>Character:</td><td> <input type='text' name='character' id='character' /></td></tr>";
						$HTML .= "<tr><td></td><td><input type='submit' name=submit value=Unstuck /></td></tr>";
		$HTML .= "</table>";
		$HTML .= "</form>";
		$HTML .= "</fieldset>";
	} else if ($MODE == "submit")
	{
		// Being system of checks.
		$Username = htmlspecialchars(preg_replace($search, '', $_POST['Username']), ENT_QUOTES);
		$Password = htmlspecialchars(preg_replace($search, '', $_POST['password']), ENT_QUOTES);
		$character = htmlspecialchars(preg_replace($search, '', $_POST['character']), ENT_QUOTES);
		$ip = $_SERVER['REMOTE_ADDR'];
		$Username = mysql_real_escape_string($Username);
		$Password = mysql_real_escape_string($Password);
		$character = mysql_real_escape_string($character);
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
		if ($character == null || $character == "")
		{
			$HTML .="<br /><b> You must enter a character name!</b>";
			$Continue = false;
		}
		
		$query4 = "SELECT acct FROM accounts WHERE login = '".$Username."' AND password = '".$Password."'";

	$result4 = mysql_query($query4) or die(mysql_error());
	$numrows4 = mysql_num_rows($result4);
	$row4 = mysql_fetch_array($result4);
	$acct = $row4[0];
	$query3 = "SELECT name, acct FROM characters WHERE acct = ".$acct." AND name = '".$character."'";

	$result3 = mysql_query($query3);
	$numrows3 = mysql_num_rows($result3);

	if ($numrows3 == 0)
	{
		die("That Character does not exist on that Account!");
	}

		$HTML .= "</p>";
		$query = "update characters SET positionX = bindpositionX, positionY = bindpositionY, positionZ = bindpositionZ, mapId = bindmapId, zoneId = bindzoneId, deathstate = 0 WHERE name = '".$character."'";
		if ($Continue)
		{
			mysql_query($query)
						or die("Error completing the unstuck function!");
						
			$Check = mysql_query("SELECT login, password FROM accounts WHERE login = '$Username' AND password = '$Password'");
			$HTML .= "<p>";
			if (mysql_num_rows($Check) == 1)
			{
				$HTML .= "<br />Your character was unstuck!<br><b>It may take up to 1 to 2 min before it takes effect</b>";
			} else
			{
				$HTML .= "<br />Your Username and Password did not match!";
			}
			$HTML .= "</p>";
		} else
		{
			$HTML .= "<p>";
			$HTML .= "<fieldset>";
			$HTML .= "<legend> Unstuck Tool </legend>";
			$HTML .= "<table cellspacing = '0' cellpadding = '0' border = '0'>";
			$HTML .= "<br /> Go back and try again!";
			$HTML .= "</table>";
			$HTML .= "</form>";
			$HTML .= "</fieldset>";
			$HTML .= "</body></html>";
				}
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