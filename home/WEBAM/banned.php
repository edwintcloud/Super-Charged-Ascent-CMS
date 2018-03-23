<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 31.01.2008 revised version (1.71)
 * Author: SixSixNine
 * Copyright: SixSixNine
 * Email: *****
 * License: GNU General Public License (GPL)
 * Updated/Edited for Ascent: gmaze 
 */
 
require_once("header.php");
  valid_login(1);
require_once("defs/arrays.php");

//show banned list
function show_list() {
 global  $output,$dbhost, $dbuser, $dbpass, $acct_db;
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "ip";
	  $sql = "SELECT * FROM ipbans";
    $sql1 = "SELECT * FROM accounts WHERE banned='1'";
    $result = mysql_query($sql);
    $result1 = mysql_query($sql1);
    $result3 = mysql_query($sql);
    $total_banned_ips = mysql_num_rows($result);
    $tot_ban_accts = mysql_num_rows($result1);
      $output .= "<center><table class=\"top_hidden\"><tr><td colspan>";
   makebutton("Add IP Ban", "banned.php?action=add_ip","230");
	 makebutton("Add Account Ban", "banned.php?action=add_acct","260");
	 makebutton("Back", "javascript:window.history.back()","230");
      $output .= "</td></tr></table>";
      $output .= "<span style='color:white;'><table class=\"lined\"><font class=\"bold\">Banned IPs: $total_banned_ips </font></span>";
      $output .= "<tr> 
	                 <td width=\"14%\" class=\"head\"> Delete </td>
	                 <td width=\"43%\" class=\"head\"><a href=\"banned.php?order_by=login\" class=\"head_link\">Account</td>
	                 <td width=\"43%\" class=\"head\"><a href=\"banned.php?order_by=ip\" class=\"head_link\">IP Address</a></td>
                  </tr>";
 for($x = 1; $x <= $total_banned_ips; $x++)	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
    $iparray[$x] = $row['ip'];
 }
 for($y = 1; $y <= $tot_ban_accts; $y++)	{
		$raw = mysql_fetch_array($result1, MYSQL_ASSOC);
    $lastiparray[$y] = $raw['lastip'];
    $loginarray[$y] = $raw['login'];
 }
 if($iparray && $lastiparray) {
    $intrsect_array = array_intersect($iparray, $lastiparray);
 }
 for ($i=1; $i<=$total_banned_ips; $i++) {
    $ip_ban = $iparray[$i];
    $sql2 = "SELECT * FROM accounts WHERE lastip='$ip_ban'";
    $result2 = mysql_query($sql2);
    $acct_ban = mysql_fetch_row($result2);
      $output .= "<tr>";
      $output .= " <td><a href=\"banned.php?action=delete_ip&amp;ip=$ip_ban\">Delete</a></td>";
      $output .= " <td>".$acct_ban[1]."</td>
                   <td>".$ip_ban."</td>
			            </tr>";         
  }
      $output .= "</table></center><br/>";      
      $output .= "<center><table class=\"top_hidden\"><tr><td colspan>";
      $output .= "</td></tr></table>";
      $output .= "<span style='color:white;'><table class=\"lined\"><font class=\"bold\"> Banned Accounts: $tot_ban_accts</font></span>";
      $output .= "<tr> 
	                 <td width=\"14%\" class=\"head\"> Delete </td>
	                 <td width=\"43%\" class=\"head\"><a href=\"banned.php?order_by=login\" class=\"head_link\">Account</td>
	                 <td width=\"43%\" class=\"head\"><a href=\"banned.php?order_by=ip\" class=\"head_link\">IP Address</a></td>
                  </tr>";
 for ($i=1; $i<=$tot_ban_accts; $i++) {
    $lastip = $lastiparray[$i];
    $acctban = $loginarray[$i];
      $output .= "<tr>";
      $output .= "  <td><a href=\"banned.php?action=delete_acct&amp;acct=$acctban\">Delete</a></td>";
      $output .= "  <td>".$acctban."</td>";
  if($intrsect_array){
    if(in_array($lastip,$intrsect_array)) {
      $output .= "  <td><font color=\"darkred\">$lastip This account is also IP banned</font></td>"; 
    } else {
      $output .= "  <td><font color=\"darkgreen\">$lastip This account not IP banned</font></td>";     
    }
  }
  if(!$intrsect_array){
      $output .= "  <td><font color=\"darkgreen\">$lastip This account not IP banned</font></td>";     
  }
      $output .= "</tr>";
 }
      $output .= "</table></center><br/>";
   mysql_close();
}

//remove acct from banned list
function delete_acct() {
  global  $output;
 if(isset($_GET['acct'])) $acct = ($_GET['acct']);
	else {
	   header("Location: banned.php?error=1");
	   exit();
 }
	    $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
      $output .= "<font class=\"bold\">Account : '$acct' Will be removed from the ban list</font><br/><br/>";
      $output .= "<table class=\"hidden\"><tr><td>";
	 makebutton("YES", "banned.php?action=dodelete_acct&amp;acct=$acct","115");
      $output .= "</td><td>";
	 makebutton("NO", "banned.php","115");
      $output .= "</td></tr></table><br/></center>";
}

//actually perform the removal
function dodelete_acct() {
 global $dbhost, $dbuser, $dbpass, $acct_db;
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 if(isset($_GET['acct'])) $acct = ($_GET['acct']);
	else {
	 header("Location: banned.php?error=1");
		exit();
 }
    $sql2 = "UPDATE accounts SET banned = '0' WHERE login = '$acct'";
    $query2 = mysql_query($sql2) or die(error(mysql_error()));
 if (mysql_affected_rows() <> 0) {
	mysql_close();
	 header("Location: banned.php?error=3");
    exit();
 } else {
 	mysql_close();
	 header("Location: banned.php?error=2");
    exit();
 }
}

//remove ip from banned list
function delete_ip() {
  global  $output;
 if(isset($_GET['ip'])) $ip = addslashes($_GET['ip']);
	else {
	 header("Location: banned.php?error=1");
		exit();
 }
	    $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
      $output .= "<font class=\"bold\">IP : '$ip' Will be removed from the ban list</font><br/><br/>";
      $output .= "<table class=\"hidden\"><tr><td>";
	 makebutton("YES", "banned.php?action=dodelete_ip&amp;ip=$ip","115");
      $output .= "</td><td>";
	 makebutton("NO", "banned.php","115");
      $output .= "</td></tr></table><br/></center>";
}

//actually perform the removal
function dodelete_ip() {
 global $dbhost, $dbuser, $dbpass, $acct_db;
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 if(isset($_GET['ip'])) $ip = quote_smart($_GET['ip']);
	else {
	 header("Location: banned.php?error=1");
	  exit();
 }
    $sql1 = "DELETE FROM ipbans WHERE ip = '$ip'";
    $sql2 = "UPDATE accounts SET banned = '0' WHERE lastip = '$ip'";
    $query1 = mysql_query($sql1) or die(error(mysql_error()));
    $query2 = mysql_query($sql2) or die(error(mysql_error()));
 if(mysql_affected_rows() <> 0) {
	mysql_close();
	 header("Location: banned.php?error=3");
    exit();
 } else {
 	mysql_close();
	 header("Location: banned.php?error=2");
    exit();
 }
}

//add an account to banned list
function add_acct() {
  global  $output, $dbhost, $dbuser, $dbpass, $acct_db;
 if (empty($_GET['new_ban_acct'])) {
   $_GET['new_ban_acct'] = "NULL";
 } 
     $new_ban_acct = ($_GET['new_ban_acct']); 
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
      $output .= "<center><fieldset style=\"width: 550px;\">
	     <legend>Ban Account</legend>
        <form method=\"GET\" action=\"banned.php\"><input type=\"hidden\" name=\"action\" value=\"doadd_acct\" /><table class=\"flat\">
          <tr> 
            <td>Account Name</td>
            <td><input type=\"text\" name=\"new_ban_acct\" size=\"40\" maxlength=\"16\" value=\"$new_ban_acct\" /></td>
          </tr>
          <tr>
            <td colspan></td>
          </tr>
          <tr> 
            <td><input type=\"submit\" value=\"Commit Ban\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
            <td>";
	makebutton("Back", "banned.php","294");
      $output .= "</td></tr></table></form></fieldset><br/><br/></center>";
}

//actually perform the addition
function doadd_acct() {
  global $dbhost, $dbuser, $dbpass, $acct_db;
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
      $new_ban_acct = quote_smart($_GET['new_ban_acct']);
 if ($new_ban_acct == "NULL") {
  mysql_close();
	 header("Location: banned.php?error=4");
	 exit();
 }
      $sql1 = "UPDATE accounts SET banned='1' WHERE login= '$new_ban_acct'";
      $result1 = mysql_query($sql1) or die(error(mysql_error()));
 if ($result1) {
	mysql_close();
	 header("Location: banned.php?error=3");
	 exit();
 }
}

//add an ip to banned list
function add_ip() {
  global  $output, $dbhost, $dbuser, $dbpass, $acct_db;
 if (empty($_GET['new_ban_ip'])) {
   $_GET['new_ban_ip'] = "0.0.0.0";
 } 
  mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
  mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
      $new_ban_ip = quote_smart($_GET['new_ban_ip']);
        $output .= "<center><fieldset style=\"width: 550px;\">
	       <legend>Ban IP</legend>
          <form method=\"GET\" action=\"banned.php\"><input type=\"hidden\" name=\"action\" value=\"doadd_ip\" /><table class=\"flat\">
            <tr> 
              <td>IP Address</td>
              <td><input type=\"text\" name=\"new_ban_ip\" size=\"40\" maxlength=\"16\" value=\"$new_ban_ip\" /></td>
            </tr>
            <tr>
              <td colspan></td>
            </tr>
            <tr>
              <td colspan><b>Set Ban Duration:</b></td>
	            <td colspan>Days:
                <select name=\"d\">
		              <option value=\"0\">0</option>
		              <option value=\"1\">1</option>
		              <option value=\"2\">2</option>
		              <option value=\"3\">3</option>
		              <option value=\"4\">4</option>
		              <option value=\"5\">5</option>
		              <option value=\"6\">6</option>
		              <option value=\"7\">7</option>
		              <option value=\"8\">8</option>
		              <option value=\"9\">9</option>
		              <option value=\"10\">10</option>
		              <option value=\"11\">11</option>
		              <option value=\"12\">12</option>
		              <option value=\"13\">13</option>
		              <option value=\"14\">14</option>
		              <option value=\"15\">15</option>
		              <option value=\"16\">16</option>
		              <option value=\"17\">17</option>
		              <option value=\"18\">18</option>
		              <option value=\"19\">19</option>
		              <option value=\"20\">20</option>
		              <option value=\"21\">21</option>
		              <option value=\"22\">22</option>
		              <option value=\"23\">23</option>
		              <option value=\"24\">24</option>
		              <option value=\"25\">25</option>
		              <option value=\"26\">26</option>
		              <option value=\"27\">27</option>
		              <option value=\"28\">28</option>
	              </select>
              Months:
                <select name=\"m\">
		              <option value=\"0\">0</option>
		              <option value=\"1\">1</option>
		              <option value=\"2\">2</option>
		              <option value=\"3\">3</option>
		              <option value=\"4\">4</option>
		              <option value=\"5\">5</option>
		              <option value=\"6\">6</option>
		              <option value=\"7\">7</option>
		              <option value=\"8\">8</option>
		              <option value=\"9\">9</option>
		              <option value=\"10\">10</option>
		              <option value=\"11\">11</option>
	              </select>
              Years:
                <select name=\"Y\">
		              <option value=\"0\">0</option>
		              <option value=\"1\">1</option>
		              <option value=\"2\">2</option>
		              <option value=\"3\">3</option>
		              <option value=\"4\">4</option>
		              <option value=\"5\">5</option>
		              <option value=\"6\">6</option>
		              <option value=\"7\">7</option>
		              <option value=\"8\">8</option>
		              <option value=\"9\">9</option>
		              <option value=\"10\">10</option>
		              <option value=\"20\">20</option>
		              <option value=\"30\">30</option>
		              <option value=\"50\">50</option>
	              </select>
              </td>
            </tr>
            <tr>
              <td colspan></td>
            </tr>
            <tr> 
              <td><input type=\"submit\" value=\"Commit Ban\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
              <td>";
	makebutton("Back", "banned.php","294");
      $output .= "</td></tr></table></form></fieldset><br/><br/></center>";
}

//actually perform the addition
function doadd_ip() {
  global $dbhost, $dbuser, $dbpass, $acct_db;
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
    $new_ban_ip = quote_smart($_GET['new_ban_ip']);
    $yr = quote_smart($_GET['Y']);
    $mn = quote_smart($_GET['m']);
    $dy = quote_smart($_GET['d']);
 if ($new_ban_ip == "0.0.0.0") {
  mysql_close();
	 header("Location: banned.php?error=5");
	   exit();
 }
 //set time/advance time	    
 class mkTime {
    var $day = 0;
    var $month = 0;
    var $year = 0;
    var $hour = 0;
    var $minute = 0;
    var $sec = 0;
    var $iniTime;
    var $returnFormat = "Y-m-d H:i:s";
  function addTime() {
        $newTime = mktime(
        date("H",$this->iniTime)+$this->hour,
        date("i",$this->iniTime)+$this->minute,
        date("s",$this->iniTime)+$this->sec,
        date("m",$this->iniTime)+$this->month,
        date("d",$this->iniTime)+$this->day,
        date("Y",$this->iniTime)+$this->year
        );
        $rtnTime = date($this->returnFormat,$newTime);
        return $rtnTime;
  }
}

$t = new mkTime; // call the class
$t->iniTime = time(); // set initial time
$t->year = $yr; // add year
$t->month = $mn; // add month
$t->day = $dy; // add month
$newTime = $t->addTime(); // call the add time function
    $sql1 = "UPDATE accounts SET banned='1' WHERE lastip= '$new_ban_ip'";
    $sql2 = "INSERT INTO ipbans VALUES('$new_ban_ip','$newTime')";
    $result1 = mysql_query($sql1) or die(error(mysql_error()));
    $result2 = mysql_query($sql2) or die(error(mysql_error()));
 if ($result1 && $result2) {
	mysql_close();
	 header("Location: banned.php?error=3");
	   exit();
 }
  mysql_close();
}

//error reports
if(isset($_GET['error'])) $err = $_GET['error'];
	else $err = NULL;
	
$output .= "<div class=\"top\">";
switch ($err) {
case 1:
    $output .= "<h1><font class=\"error\">Some Fields Left Blank</font></h1>";
   break;
case 2:
    $output .= "<h1><font class=\"error\">Error removing Ban</font></h1>";
   break;
case 3:
    $output .= "<h1><font class=\"error\">Update action success!</font></h1>";
   break;
case 4:
    $output .= "<h1><font class=\"error\">That is the default account setting!</font></h1>";
   break;
case 5:
    $output .= "<h1><font class=\"error\">That is the default ip setting!</font></h1>";
   break;
default: //no error
     $output .= "<h1>$realm_name Bans</h1>";
}
$output .= "</div>";

//action defines
if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "delete_acct":
	delete_acct(); 
	break;
case "dodelete_acct":
 	dodelete_acct();
    break;
case "delete_ip":
	delete_ip(); 
	break;
case "dodelete_ip":
 	dodelete_ip();
    break;
case "add_ip":
 	add_ip();
 	break;
case "doadd_ip":
	doadd_ip();
	break;
case "add_acct":
 	add_acct();
 	break;
case "doadd_acct":
	doadd_acct();
	break;
default:
    show_list();
}

require_once("footer.php");
?>