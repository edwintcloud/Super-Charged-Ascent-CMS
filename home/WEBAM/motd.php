<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 31.01.2008 revised version (1.72)
 * Author: SixSixNine
 * Copyright: SixSixNine
 * Email: *****
 * License: GNU General Public License (GPL)
 * Updated/Edited for Ascent: gmaze 
 */
 
require_once("header.php");
  valid_login(0);
error_reporting(E_ERROR);

//MOTD part
function show_motd() {
  global $output, $dbhost, $dbuser, $dbpass, $acct_db, $user_lvl, $messperpage;
  
if(isset($_GET['start'])) $start = quote_smart($_GET['start']);
	else $start = 0;
	
	mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
  
	$query_1 = mysql_query("SELECT count(*) FROM site_motd") or die(error(mysql_error()));
	$data_1 = mysql_fetch_row($query_1)or die(error(mysql_error()));
  $all_record = $data_1[0];
  $output .= "<center><table class=\"lined\"><tr>";
	$index_url = "motd.php?action=show_motd";
  $paging = generate_pagination($index_url, $all_record, $messperpage, $start);
  $output .= "<td class=\"head\" align=\"right\">";
if ($user_lvl > 0) {
  $output .= "<a href=\"motd.php?action=add_motd\" class=\"head_link\"> Add Message of the Day</a>";
}
  $output .= "</td></tr>";
  $sql = "SELECT id,poster,message FROM site_motd ORDER BY id DESC LIMIT $start, $messperpage";
  $result = mysql_query($sql) or die(error(mysql_error()));
  $this_page = mysql_num_rows($result);
if ($this_page < $messperpage) $looping = $this_page; 
    else $looping = $messperpage;
	for ($i=1; $i<=$looping; $i++){
		$post = mysql_fetch_row($result) or die(error("No MotD Posts found!"));
		$output .= "<tr>
					<td align=\"left\" class=\"large\"><blockquote>$post[2]</blockquote></td></tr>";
		$output .= "<tr>
					<td align=\"right\">$post[1] ";
		if ($user_lvl > 0) $output .= "<a href=\"motd.php?action=delete_motd&amp;id=$post[0]\">Delete</a>";			
		$output .= "</td></tr>";
		$output .= "<tr><td class=\"hidden\"></td></tr>";
	}
 $output .= "<tr><td align=\"right\" class=\"hidden\">$paging</td></tr></table><br/>";
}

// print add motd
function add_motd(){
 global $output, $user_lvl;
 if($user_lvl > 0) {
$output .= "<center><form action=\"motd.php?action=do_add_motd\" method=\"POST\">	
			<table class=\"top_hidden\">
				<tr>
				 <td align=\"left\"><input type=\"submit\" value=\"Post MotD\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
				 <td align=\"right\">Notice: the length is limited to 255 chars.<br/>HTML tags are usable.</td>
				</tr></table>
			<TEXTAREA NAME=\"msg\" ROWS=8 COLS=93></TEXTAREA><br/>
			</form><br/><br/></center>";
 }
}

// DO ADD MOTD
function do_add_motd(){
 global  $dbhost, $dbuser, $dbpass, $acct_db, $user_name, $user_lvl;
  if($user_lvl > 0) {
if (empty($_POST['msg'])) {
   header("Location: motd.php?error=1");
   exit();
 }

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 $msg = quote_smart($_POST['msg']);

 if (strlen($msg) > 254){
	mysql_close();
    header("Location: motd.php?error=2");
    exit();
   }

 $msg = str_replace("\n", "<br/>", $msg);
 $msg = str_replace("\r", " ", $msg);
 $msg = preg_replace( "/([^\/=\"\]])((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>",  $msg);
 $msg = preg_replace('/([^\/=\"\]])(www\.)(\S+)/', '\\1<a href="http://\\2\\3" target="_blank">\\2\\3</a>', $msg);

 $by = date("m/d/y H:i:s")." Posted by: $user_name";
 
mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 
 $query2 = sprintf("SELECT * FROM site_motd ORDER BY id DESC LIMIT 0,1");
 $result2 = mysql_query($query2);
 if ($result2) {
   ($row2 = mysql_fetch_array($result2));
}

 $amount = $row2['id'];
 $total = $amount+1;
 $sql = "INSERT INTO site_motd (id, poster, message) VALUES ('$total','$by','$msg')";
 $query = mysql_query($sql) or die(error(mysql_error()));
 
 mysql_close();

 header("Location: motd.php");
 exit();
 }
}

// DELETE MOTD
function delete_motd(){
 global  $dbhost, $dbuser, $dbpass, $acct_db, $user_lvl;
 if($user_lvl > 0) {
 if (empty($_GET['id'])) {
   header("Location: motd.php");
   exit();
 }
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 $id = quote_smart($_GET['id']);
 
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 
 $sql = "DELETE FROM site_motd WHERE id ='$id'";
 $query = mysql_query($sql) or die(error(mysql_error()));
 
 mysql_close();

 header("Location: motd.php");
 exit();
}
}

// MAIN
if(isset($_GET['error'])) $err = $_GET['error'];
	else $err = NULL;

$output .= "<div class=\"top\">";
switch ($err) {
case 1:
   $output .= "<h1><font class=\"error\">Some Fields Left Blank</font></h1>";
   break;
case 2:
   $output .= "<h1><font class=\"error\">Max. length limit Exceeded</font></h1>";
   break;
default: //no error
   $output .= "<h1>$realm_name Message of the Day.</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "show_motd":
	show_motd();
	break;
case "delete_motd":
	delete_motd();
	break;
case "add_motd":
	add_motd();
	break;
case "do_add_motd":
	do_add_motd();
	break;
default:
    show_motd();
}

require_once("footer.php");
?>