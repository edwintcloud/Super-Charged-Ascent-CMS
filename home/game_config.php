<?php
include "mainconfig.php";
?>

<?php
//Game Account Config Settings
	$Host		= $db_host; 	// This is by default localhost or 127.0.0.1, if your MySQL server is not hosted
									// on the same machine you are putting this one, you will need to put the IP of that server here.
									// Default = localhost or 127.0.0.1
	$Username	= $db_user; 		// Database username, default = root
	$Password	= $db_pass; 		// Database password
	$Database	= $logon_db_name; 	// If your Antrix database is not called Antrix, please put its name here in place of Antrix. default = antrix

	$UserLevel	= $def_gm_lvl;	// Default starting user access level, default = 0 (Player), 1 - 3 are GM levels (And i think az is full rights, or was, not sure about later revs).
	$AllowTBC	= true; // If you do not want them to have access to TBC Characters set this to false. default = true
	$AllowReg	= true; // If you want to turn the registration off, change this to false, default = true
	$ForceTBC	= true; // If this is set to true, it will override $AllowTBC and make any character TBC without displaying an option to make or not to make.
	$AccsPerIP	= $acc_per_ip; // Sets the ammount of accounts per IP a user can make. default = 1
	$Title		= $site_title; // Title of the site.
	$ShowWarning = true; // Turn this to false if you wish to not display the warning on the registration page. default = true
							// For now I would recommend showing it, unless you feel it will scare your members off, however Antrix does not encrypt
							// Account passwords, therefore any vulnerable site you may be using (Vulnerable to SQL injection) may be used by
							// a hacker to gain your users account information.
	$TELEPORT_COST = $teleporter_cost;
?>