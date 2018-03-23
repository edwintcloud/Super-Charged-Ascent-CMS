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

$time_start = microtime(true);
session_start();

//enable switching styles
if (isset($_GET['style'])) {
      $_COOKIE['style'] = $_GET['style'];
    }
if (!isset($_COOKIE['style'])) {
      $_COOKIE['style'] = "default";
    }
$style = $_COOKIE['style'];

//set cookie for our chosen style
setcookie("style", $style, time()+36000000000, "/","", 0); 

//define our includes
require_once("defs/config.php");
require_once("defs/global_lib.php");

//define our header
$output .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
  <head>
    <title>$title</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"templates/$style.css\" />
    <script type=\"text/javascript\" src=\"menu.js\"></script>";
$output .= "<!--[if lte IE 7]>
		<style>
		  #menuwrapper, #menubar ul a {height: 1%;}
		  a:active {width: auto;}
		</style>
	<![endif]-->";
$output .= "
  </head>
	<body onLoad=\"Menu()\">
		<center>";

//get our user name/level
if ((isset($_SESSION['user_lvl'])) && (isset($_SESSION['uname']))){
  $user_lvl = $_SESSION['user_lvl'];
	$user_name = $_SESSION['uname'];
	
//define site options by user level
	switch ($user_lvl){
	case 1: // level 1 moderator status
	 $output .= "
		<div id=\"menuwrapper\">
			<ul id=\"menubar\">
			<li><a class=\"trigger\" href=\"index.php\">Stats/Info</a>
      <ul>
       <li><a href=\"../stats\">Server Stats</a></li> 
				<li><a href=\"motd.php\">Server Messages</a></li>
				<li><a href=\"toplists.php\">Honor List</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Player Tools</a>
			<ul>
			 	<li><a href=\"char_unstuck.php\">Character Unstuck</a></li>
				<li><a href=\"char_transfer.php\">Character Transfer</a></li>				

			  <li><a href=\"edit.php\">Edit My Account</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Mod Tools</a>
			<ul> 
				<li><a href=\"banned.php\">Banned List</a></li>
				<li><a href=\"ticket.php\">Tickets</a></li>
				<li><a href=\"mail.php\">Mail</a></li>
				<li><a href=\"char_list.php\">Characters</a></li>
				<li><a href=\"guild.php\">Guilds</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Styles</a>
			<ul>
				<li><a href=\"motd.php?style=default\">Default Style</a></li>
				<li><a href=\"motd.php?style=grave\">Graveyard Style</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">$custom</a>
			<ul>
				<li>$link1</li>
				<li>$link2</li>
				<li>$link3</li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"logout.php\">Logout</a>
      <ul>
        <li><a href=\"logout.php\">Logout</a></li>
			</ul>
			</li>
			</ul>
		<br class=\"clearit\"><div id=\"username\">Mod::$user_name</div>
	</div>";
	break;
	case 2: // level 2 game master status
	 $output .= "
		<div id=\"menuwrapper\">
			<ul id=\"menubar\">
			<li><a class=\"trigger\" href=\"index.php\">Stats/Info</a>
      <ul>
        <li><a href=\"../stats\">Server Stats</a></li>
				<li><a href=\"motd.php\">Server Messages</a></li>
				<li><a href=\"toplists.php\">Honor List</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Player Tools</a>
			<ul>
			 	<li><a href=\"char_unstuck.php\">Character Unstuck</a></li>
				<li><a href=\"char_transfer.php\">Character Transfer</a></li>				

			  <li><a href=\"edit.php\">Edit My Account</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">GM Tools</a>
			<ul> 
				<li><a href=\"banned.php\">Banned List</a></li>
				<li><a href=\"ticket.php\">Tickets</a></li>
				<li><a href=\"mail.php\">Mail</a></li>
        <li><a href=\"char_list.php\">Characters</a></li>
				<li><a href=\"guild.php\">Guilds</a></li>				
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Styles</a>
			<ul>
				<li><a href=\"motd.php?style=default\">Default Style</a></li>
				<li><a href=\"motd.php?style=grave\">Bloodelf Style</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">$custom</a>
			<ul>
				<li>$link1</li>
				<li>$link2</li>
				<li>$link3</li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"logout.php\">Logout</a>
      <ul>
        <li><a href=\"logout.php\">Logout</a></li>
			</ul>
			</li>
			</ul>
		<br class=\"clearit\"><div id=\"username\">GM::$user_name</div>
	</div>";
	break;
	case 3: // level 3 administrator status
   $output .= "
		<div id=\"menuwrapper\">
			<ul id=\"menubar\">
			<li><a class=\"trigger\" href=\"index.php\">Stats/Info</a>
      <ul>
        <li><a href=\"../stats\">Server Stats</a></li>
				<li><a href=\"motd.php\">Server Messages</a></li>
				<li><a href=\"toplists.php\">Top Honor List</a></li>
				<li><a href=\"toplists.php?action=played\">Top Player List</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Player Tools</a>
			<ul>
			 	<li><a href=\"char_unstuck.php\">Character Unstuck</a></li>
				<li><a href=\"char_transfer.php\">Character Transfer</a></li>				
			  <li><a href=\"edit.php\">Edit My Account</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">GM Tools</a>
			<ul> 			
        <li><a href=\"user.php\">Accounts</a></li>
				<li><a href=\"banned.php\">Banned List</a></li>
				<li><a href=\"ticket.php\">Tickets</a></li>
				<li><a href=\"mail.php\">Mail</a></li>
				<li><a href=\"backup.php\">Backup</a></li>
				<li><a href=\"cleanup.php\">Cleanup</a></li>
			  <li><a href=\"run_patch.php\">Run SQL Patch</a></li>
			  <li><a href=\"char_list.php\">Characters</a></li>
				<li><a href=\"guild.php\">Guilds</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Styles</a>
			<ul>
				<li><a href=\"motd.php?style=default\">Default Style</a></li>
				<li><a href=\"motd.php?style=grave\">Bloodelf Style</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">$custom</a>
			<ul>
				<li>$link1</li>
				<li>$link2</li>
				<li>$link3</li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"logout.php\">Logout</a>
      <ul>
        <li><a href=\"logout.php\">Logout</a></li>
			</ul>
			</li>
			</ul>
		<br class=\"clearit\"><div id=\"username\">Admin::$user_name</div>
	</div>";
   break;
	default: // level 0 standard player
   $output .= "
		<div id=\"menuwrapper\">
			<ul id=\"menubar\">
			<li><a class=\"trigger\" href=\"index.php\">Stats/Info</a>
      <ul>
        <li><a href=\"../stats\">Server Stats</a></li>
				<li><a href=\"motd.php\">Server Messages</a></li>
				<li><a href=\"toplists.php\">Honor List</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Player Tools</a>
			<ul>
			 	<li><a href=\"char_unstuck.php\">Character Unstuck</a></li>
				<li><a href=\"char_transfer.php\">Character Transfer</a></li>				
			  <li><a href=\"edit.php\">Edit My Account</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">Styles</a>
			<ul>
				<li><a href=\"motd.php?style=default\">Default Style</a></li>
				<li><a href=\"motd.php?style=grave\">Graveyard Style</a></li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"\">$custom</a>
			<ul>
				<li>$link1</li>
				<li>$link2</li>
				<li>$link3</li>
			</ul>
			</li>
			<li><a class=\"trigger\" href=\"logout.php\">Logout</a>
      <ul>
        <li><a href=\"logout.php\">Logout</a></li>
			</ul>
			</li>
			</ul>
		<br class=\"clearit\"><div id=\"username\">Plyr::$user_name</div>
	</div>";
  }
 }
$output .= "<div id=\"body_main\">
			<div class=\"bubble\">
			<i class=\"tr\"></i><i class=\"tl\"></i>"

?>