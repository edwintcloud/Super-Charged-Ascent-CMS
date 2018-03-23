<?php
include "../mainconfig.php";
//Configuration

//Logon Database information.
define("LOGON_HOST",$db_host);
define("LOGON_USER",$db_user);
define("LOGON_PASS",$db_pass);
define("LOGON_DB",$logon_db_name);
define("ENCRYPT_PASSWORDS",false); // !!! - Not yet supported

//Local webserver's MySQL information.
define("MYSQL_HOST",$db_host);
define("MYSQL_USER",$db_user);
define("MYSQL_PASS",$db_pass);
define("MYSQL_DB",$vote_db_name);

//In game mail settings
define("MAIL_SUBJECT","Thank You For Voting!");
define("MAIL_BODY","Thank you for voting for our server. Here is your reward!");

// Misc
define("RPPV",$vote_reward_points); // Reward points per vote. Must be a whole number.

session_start();
// force login to view.
if(!$_SESSION['vcp']['authenticated'] && $_GET['act'] != "vote")
{
	include("pages/login.php");
	new Login();
	die;
}
switch($_GET['act'])
{
default:
	include("pages/overview.php");
	new Overview();
	break;
case "rewards":
	include("pages/rewards.php");
	new Rewards();
	break;
case "spend":
	include("pages/spend.php");
	new Spend();
	break;
case "vote":
	include("pages/vote.php");
	new Vote();
	break;
}
?>