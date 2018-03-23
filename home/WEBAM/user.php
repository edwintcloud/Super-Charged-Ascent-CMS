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
valid_login(1);
require_once("defs/id_tab.php");

//browse user accounts
function browse_users() {
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $itemperpage, $user_lvl, $user_name;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if(isset($_GET['start'])) $start = quote_smart($_GET['start']);
	else $start = 0;

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "acct";

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

//get total number of accounts
 $query_1 = mysql_query("SELECT count(*) FROM accounts") or die(error(mysql_error()));
 $data_1 = mysql_fetch_row($query_1)or die(error(mysql_error()));
 $all_record = $data_1[0];

 $sql = "SELECT acct,login,gm,email,banned,lastip,lastlogin FROM accounts ORDER BY $order_by ASC LIMIT $start, $itemperpage";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $this_page = mysql_num_rows($query) or die(error("No records found!"));

//top page navigation starts here
 $output .="<center><table class=\"top_hidden\">
          <tr><td>"; 
 $index_url = "user.php?action=browse_users&amp;order_by=$order_by";
 $paging = generate_pagination($index_url, $all_record, $itemperpage, $start);

 makebutton("Add New Account", "user.php?action=add_new", "150");
 makebutton("CleanUp", "cleanup.php", "120");
 makebutton("Backup", "backup.php", "120");
 makebutton("Back", "javascript:window.history.back()", "120");

 $output .= " </td><td align=\"right\">";

 if (!empty($paging)) $output .= " | ";
 $output .= $paging;
 $output .= "</td></tr>
	<tr align=\"left\"><td>
    	  <form action=\"user.php\" method=\"get\">
	   <input type=\"hidden\" name=\"action\" value=\"search\" />
	   <input type=\"hidden\" name=\"error\" value=\"3\" />
	   <input type=\"text\" size=\"47\" maxlength=\"50\" name=\"search_value\" />
	   <select name=\"search_by\">
		<option value=\"login\">by Name</option>
		<option value=\"gm\">by GM Level</option>
		<option value=\"email\">by Email</option>
		<option value=\"lastip\">by IP</option>
		<option value=\"lastlogin\">by Last Login</option>
		<option value=\"banned\">by Banned</option>
	   </select>
	   <input type=\"submit\" value=\"Search\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>";
 $output .= "</td></tr></table>";
//top page navigation ends here

 $output .= "<form method=\"get\" action=\"user.php\">
	 <input type=\"hidden\" name=\"action\" value=\"del_user\" />
	 <input type=\"hidden\" name=\"start\" value=\"$start\" />
 
 <table class=\"lined\">
   <tr>
	<td width=\"5%\" class=\"head\">Delete</td>
	<td width=\"5%\" class=\"head\"><a href=\"user.php?order_by=acct\" class=\"head_link\">ID</a></td>
	<td width=\"18%\" class=\"head\"><a href=\"user.php?order_by=login\" class=\"head_link\">Username</a></td>
	<td width=\"5%\" class=\"head\"><a href=\"user.php?order_by=gm\" class=\"head_link\">GMlvl</a></td>
  <td width=\"17%\" class=\"head\"><a href=\"user.php?order_by=email\" class=\"head_link\">Mail</a></td>
	<td width=\"5%\" class=\"head\"><a href=\"user.php?order_by=banned\" class=\"head_link\">Banned</a></td>
	<td width=\"10%\" class=\"head\"><a href=\"user.php?order_by=lastip\" class=\"head_link\">IP</a></td>
	<td width=\"10%\" class=\"head\"><a href=\"user.php?order_by=lastlogin\" class=\"head_link\">Last Login</a></td>
   </tr>";

if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

for ($i=1; $i<=$looping; $i++)	{
   $data = mysql_fetch_row($query) or die(error("No Users Found!"));
//disallow lower lvl gm's from viewing accounts of gm's with same or higher privs
//admin's can edit their accounts via edit my account in account header
	if (($user_lvl >= $data[2])||($user_name == $data[1])){
   		$output .= "<tr>";
		if ($user_lvl > $data[2]) $output .= "<td><input type=\"checkbox\" name=\"check[]\" value=\"$data[0]\" /></td>";
			else $output .= "<td></td>";
   		$output .= "<td>$data[0]</td>
        <td><a href=\"user.php?action=edit_user&error=11&acct=$data[0]\">$data[1]</a></td>
			  <td>$data[2]</td>
			  <td><a href=\"mailto:$data[3]\">".substr($data[3],0,15)."</a></td>
			  <td>$data[4]</td>
			  <td>$data[5]</td>";
		if (($user_lvl > $data[2])||($user_name == $data[1])) $output .= "<td>$data[6]</td>";
			else $output .= "<td>******</td>";
		$output .= "</tr>";
	}else{
		$output .= "<tr><td>*</td><td>***</td><td>You</td><td>Have</td><td>No</td>
			<td class=\"small\">Permission</td><td>to</td><td>View</td><td>this</td><td>Data</td><td>***</td><td>*</td></tr>";
	}
}
 $output .= "<tr><td colspan=\"12\" class=\"hidden\"><br/></td></tr>
	<tr>
		<td colspan=\"6\" align=\"left\" class=\"hidden\">
        <input type=\"submit\" value=\"Delete Checked User(s)\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	  </td>
      <td colspan=\"6\" align=\"right\" class=\"hidden\">Total Accounts : $all_record</td>
	 </tr>
 </table></form><br/></center>";

 mysql_close();
}

//search accounts
function search() {
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_lvl, $user_name;
	
 if(!isset($_GET['search_value'])) {
	header("Location: user.php?error=2");
	exit();
	}

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 $search_value = quote_smart($_GET['search_value']);

 if(isset($_GET['search_by']))  $search_by = quote_smart($_GET['search_by']);
	else $search_by = "login";

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "acct";

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct,login,gm,email,banned,lastip,lastlogin FROM accounts WHERE $search_by LIKE '%$search_value%' ORDER BY $order_by ASC";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $total_found = mysql_num_rows($query);

//top page navigation starts here
 $output .= "<center><table class=\"top_hidden\">
       <td align=\"left\">";
		makebutton("User List", "user.php", "120");
		makebutton("Back", "javascript:window.history.back()", "120");
		
 $output .= "<form action=\"user.php\" method=\"get\">
	   <input type=\"hidden\" name=\"action\" value=\"search\" />
	   <input type=\"text\" size=\"30\" maxlength=\"50\" name=\"search_value\" />
	   <select name=\"search_by\">
		<option value=\"login\">by Name</option>
		<option value=\"gm\">by GM Level</option>
		<option value=\"email\">by Email</option>
		<option value=\"lastip\">by IP</option>
		<option value=\"banned\">by Banned</option>
	   </select>
	   <input type=\"submit\" value=\"New Search\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form>
	  </td>
	</tr>
	</table>";
//top page navigation ends here

 $output .= "<form method=\"get\" action=\"user.php\">
			<input type=\"hidden\" name=\"action\" value=\"del_user\" />
 
 <table class=\"lined\">
   <tr> 
	<td width=\"5%\" class=\"head\">Delete</td>
	<td width=\"5%\" class=\"head\"><a href=\"user.php?action=search&error=3&search_value=$search_value&search_by=$search_by&order_by=acct\" class=\"head_link\">ID</a></td>
	<td width=\"18%\" class=\"head\"><a href=\"user.php?action=search&error=3&search_value=$search_value&search_by=$search_by&order_by=login\" class=\"head_link\">Username</a></td>
	<td width=\"5%\" class=\"head\"><a href=\"user.php?action=search&error=3&search_value=$search_value&search_by=$search_by&order_by=gm\" class=\"head_link\">GMlvl</a></td>
    <td width=\"17%\" class=\"head\"><a href=\"user.php?action=search&error=3&search_value=$search_value&search_by=$search_by&order_by=email\" class=\"head_link\">Mail</a></td>
	<td width=\"5%\" class=\"head\"><a href=\"user.php?action=search&amp;error=3&search_value=$search_value&search_by=$search_by&order_by=banned\" class=\"head_link\">Banned</a></td>
	<td width=\"10%\" class=\"head\"><a href=\"user.php?action=search&amp;error=3&search_value=$search_value&search_by=$search_by&order_by=lastip\" class=\"head_link\">IP</a></td>
	<td width=\"10%\" class=\"head\"><a href=\"user.php?action=search&amp;error=3&search_value=$search_value&search_by=$search_by&order_by=lastlogin\" class=\"head_link\">Last Login</a></td>
   </tr>";

 for ($i=1; $i<=$total_found; $i++){
	$data = mysql_fetch_row($query) or die(error("No Users Found!"));
 
	if (($user_lvl >= $data[2])||($user_name == $data[1])){
		$output .= "<tr>";
		if ($user_lvl > $data[2]) $output .= "<td><input type=\"checkbox\" name=\"check[]\" value=\"$data[0]\" /></td>";
			else $output .= "<td></td>";
   		$output .= "<td>$data[0]</td>
         <td><a href=\"user.php?action=edit_user&error=11&acct=$data[0]\">$data[1]</a></td>
			   <td>$data[2]</td>
			   <td><a href=\"mailto:$data[3]\">".substr($data[3],0,15)."</a></td>
			   <td>$data[4]</td>
			   <td>$data[5]</td>";
		if (($user_lvl > $data[2])||($user_name == $data[1])) $output .= "<td>$data[6]</td>";
			else $output .= "<td>******</td>";
		$output .= "</tr>";
	}else{
		$output .= "<tr><td>*</td><td>***</td><td>You</td><td>Have</td><td>No</td>
			<td class=\"small\">Permission</td><td>to</td><td>View</td><td>this</td><td>Data</td><td>***</td><td>*</td></tr>";
	}
}
$output .= "<tr><td colspan=\"12\" class=\"hidden\"><br/></td></tr>
	<tr>
		<td colspan=\"6\" align=\"left\" class=\"hidden\">
        <input type=\"submit\" value=\"Delete Checked User(s)\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	  </td>
      <td colspan=\"6\" align=\"right\" class=\"hidden\">Total Found : $total_found : limit 100</td>
	 </tr>
 </table>
 </form><br/></center>";
 
 mysql_close();
}

//delete an account
function del_user() {
global $output;
	
 if(isset($_GET['check'])) $check = $_GET['check'];
	else {
			header("Location: user.php?error=1");
			exit();
			}

 $output .= "<center><h1><font class=\"error\">ARE YOU SURE?</font></h1><br/>";
 $output .= "<font class=\"bold\">Account(s) id(s): ";

 $pass_array = "";

 for ($i=0; $i<count($check); $i++){
	$output .= $check[$i].", ";
	$pass_array .= "&amp;check%5B%5D=$check[$i]";
	}

 $output .= "Will be unrecoverble if erased from DB!</font><br/><br/>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("YES", "user.php?action=dodel_user$pass_array","120");
 $output .= "</td>
            <td>";
		makebutton("NO", "user.php","120");
 $output .= "</td>
          </tr>
        </table></center><br/>";
}

//perform the delete
function dodel_user() {
 global $output,$dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_lvl;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if(isset($_GET['check'])) $check = quote_smart($_GET['check']);
	else {
			header("Location: user.php?error=1");
			exit();
			}
 
 $deleted_acc = 0;
 $deleted_chars = 0;

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
 
 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
	//checking if current user have gm power to delete the accounts.
	$sql = "SELECT gm FROM accounts WHERE acct ='$check[$i]'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$owner_gmlevel = mysql_result($query, 0, 'gm');

	if ($user_lvl > $owner_gmlevel){
	 require_once("defs/del_tab.php");
		
	 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	 $sql = "SELECT guid FROM characters WHERE acct='$check[$i]'";
	 $result = mysql_query($sql) or die(error(mysql_error()));
	 
	 while ($row = mysql_fetch_array($result)) {
			$sql = "DELETE FROM playeritems WHERE ownerguid = '$row[0]'";
			$query = mysql_query($sql);
		}
		
		foreach ($tab_del_user_char as $value) {
			$sql = "DELETE FROM $value[0] WHERE $value[1] = '$row[0]'";
			$query = mysql_query($sql) or die(error(mysql_error()));
			}
		$deleted_chars++;
	}
	mysql_free_result($result);

	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
	foreach ($tab_del_user_acct as $value){
			$sql = "DELETE FROM accounts WHERE acct = '$check[$i]'";
			$query = mysql_query($sql) or die(error(mysql_error()));
			}
	if (mysql_affected_rows() <> 0) $deleted_acc++;
	}
  }
 mysql_close();
 $output .= "<center>";
 if ($deleted_acc == 0) $output .= "<h1><font class=\"error\">No Accounts deleted!<br/>Permission Err?</font></h1>"; 
   else {
	$output .= "<h1><font class=\"error\">Total <font color=blue>$deleted_acc</font> Accounts deleted!</font><br/></h1>";
	$output .= "<h1><font class=\"error\">Total <font color=blue>$deleted_chars</font> Charecters deleted!</font></h1>";
	}
 $output .= "<br/><br/>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
 makebutton("Back Browsing Users", "user.php", "200");
 $output .= "</td>
          </tr>
        </table><br/></center>";
}

//add a new account----------->why do we need this? May be removed in future releases:gmaze
function add_new() {
 global $output;
	
 $output .= "<center>
	<fieldset style=\"width: 550px;\">
	<legend>Create New Account</legend>
     <form method=\"GET\" action=\"user.php\">
     <input type=\"hidden\" name=\"action\" value=\"doadd_new\" />
     <table class=\"flat\">
     <tr>
        <td>Username</td>
        <td><input type=\"text\" name=\"new_user\" size=\"42\" maxlength=\"15\" value=\"New_Account\" /></td>
      </tr>
     <tr>
        <td>Password</td>
        <td><input type=\"text\" name=\"new_pass1\" size=\"42\" maxlength=\"25\" value=\"123456\" /></td>
     </tr>
     <tr>
        <td>Confirm</td>
        <td><input type=\"text\" name=\"new_pass2\" size=\"42\" maxlength=\"25\" value=\"123456\" /></td>
     </tr>
     <tr>
        <td>Mail</td>
        <td><input type=\"text\" name=\"new_mail\" size=\"42\" maxlength=\"225\" value=\"none@mail.com\" /></td>
     </tr>
     <tr>
        <td>Banned</td>
        <td><input type=\"text\" name=\"new_banned\" size=\"42\" maxlength=\"1\" value=\"0\" /></td>
     </tr>
     <tr>
        <td><input type=\"submit\" value=\"Create Account\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
     <td>";
 $output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("Back", "user.php","306");
 $output .= "</td>
          </tr>
        </table>";
 $output .= "</td></tr>
		</table>
    </form>
	</fieldset><br/><br/></center>";
}

//perform adding new account-->see add_new:gmaze
function doadd_new() {
 global $dbhost, $dbuser, $dbpass, $acct_db;

 if ((empty($_GET['new_user']))||(empty($_GET['new_mail']))||(empty($_GET['new_pass1']))||(empty($_GET['new_pass2']))) {
   header("Location: user.php?action=add_new&error=4");
   exit();
}

 if ($_GET['new_pass1'] == $_GET['new_pass2']){
    mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
	
	$new_user = quote_smart(trim($_GET['new_user']));
	$pass = quote_smart($_GET['new_pass1']);

	//make sure username/pass at least 4 chars long and less than max
	if ((strlen($new_user) < 4) || (strlen($new_user) > 15) || (strlen($pass) < 4) || (strlen($pass) > 27)){
			mysql_close();
     		header("Location: user.php?action=add_new&error=8");
     		exit();
   		}

	require_once("defs/valid_lib.php");
	//make sure it doesnt contain non english chars.
	if (!alphabetic($new_user)) {
			mysql_close();
     		header("Location: user.php?action=add_new&error=9");
     		exit();
   		}

	mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

	$sql = "SELECT login FROM accounts WHERE login='$new_user'";
	$result = mysql_query($sql) or die(error(mysql_error()));

	//there is already someone with same username
	if (mysql_num_rows($result)){
			mysql_close();
    	 	header("Location: user.php?action=add_new&error=7");
    	 	exit();
	} else {
    	$last_ip = "0.0.0.0";
		$new_mail = quote_smart(trim($_GET['new_mail']));

		if(isset($_GET['new_banned'])) $banned = quote_smart($_GET['new_banned']);
			else $banned = 0;

 		$sql = "INSERT INTO accounts (login,password,email,banned,lastip)
 				VALUES ('$new_user','$pass','$new_mail','$banned' ,'$last_ip')";
		$result = mysql_query($sql) or die(error(mysql_error()));

		mysql_close();

		if ($result) {
			header("Location: user.php?error=5");
			exit();
			}
 		}
 } else { 
 	header("Location: user.php?action=add_new&error=6");
 	exit();
    }
}

//edit account-->admins can edit any account
function edit_user() {
 global $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_lvl,$user_name;
 
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if (empty($_GET['acct'])) {
   header("Location: user.php?error=10");
   exit();
 }
 
 $id = quote_smart($_GET['acct']);
 
 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "SELECT acct,login,password,gm,email,banned,lastip,lastlogin FROM accounts WHERE acct ='$id'";
 $result = mysql_query($sql) or die(error(mysql_error()));
 $data = mysql_fetch_row($result) or die(error("No Users Found!"));
 
//if we have 0 results == no user
 if (mysql_num_rows($result) == 1){
//restricting accsess to lower gmlvl
	if (($user_lvl < '3')&&($user_name != $data[1])){
		mysql_close();
		header("Location: user.php?error=14");
		exit();
		}

 $output .= "<center>
 <fieldset style=\"width: 550px;\">
	<legend>Edit Account</legend>
   <form method=\"POST\" action=\"user.php\">
   <input type=\"hidden\" name=\"action\" value=\"doedit_user\" />
   
   <table class=\"flat\">
      <tr>
        <td>Acct</td>
        <td>$data[0]<a href=\"banned.php?action=add_acct&new_ban_acct=$data[1]\"> <--- Ban This Account</a></td>
      </tr>
      <tr>
        <td>Username</td>
	      <td><input type=\"text\" name=\"new_user\" size=\"43\" maxlength=\"15\" value=\"$data[1]\" /></td>
      </tr>
      <tr>
        <td>Password</td>
        <td><input type=\"text\" name=\"new_pass\" size=\"43\" maxlength=\"25\" value=\"$data[2]\" /></td>
      </tr>
      <tr>
        <td>GMlevel</td>
        <td><input type=\"text\" name=\"new_gm\" size=\"43\" maxlength=\"225\"value=\"$data[3]\" /></td>
      </tr>
      <tr>
        <td>Mail</td>
        <td><input type=\"text\" name=\"new_mail\" size=\"43\" maxlength=\"225\"value=\"$data[4]\" /></td>
      </tr>
      <tr>
        <td>Banned</td>
	    <td>$data[5]</td>
      </tr>
      <tr>
        <td>Last IP</td>
        <td>$data[6]<a href=\"banned.php?action=add_ip&new_ban_ip=$data[6]\"> <--- Ban This IP</a></td>
      </tr>
      <tr>
        <td>Last Login</td>
        <td>$data[7]</td>
      </tr>";
  mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
	$sql = "SELECT * FROM characters WHERE acct = '$id'";
	$query = mysql_query($sql) or die(error(mysql_error()));
	$tot_chars = mysql_num_rows($query);


	$output .= "<tr>
        <td>Total Characters</td>
        <td>$tot_chars</td>
      </tr>";

	//if there is any chars to display
	if ($tot_chars){
		mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
		$sql = "SELECT guid,name,race,class,level FROM `characters` WHERE acct='$id'";
		$char_array = mysql_query($sql) or die(error(mysql_error()));
		while ($char = mysql_fetch_array($char_array)){
			$output .= "<tr> 
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----></td>
			<td><a href=\"char.php?id=$char[0]\">$char[1]  - ".get_player_race($char[2])." ".get_player_class($char[3])." | lvl $char[4]</a></td>
      </tr>";
	}
 }
  $output .=  "<tr>
        <td><input type=\"submit\" value=\"Update Data\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
        <td>"; 
		makebutton("Delete Account", "user.php?action=del_user&amp;check%5B%5D=$id","150");
		makebutton("Back", "user.php","150");
 $output .= "</td></tr>
		</table>
    </form></fieldset><br/><br/></center>";
	
  } else error("Unexpected Error!<br/>Wrong Value Passed.");
 mysql_close();
}

//perform the account edit
function doedit_user() {
 global $dbhost, $dbuser, $dbpass, $acct_db, $user_lvl,$user_name;
 
 if ((empty($_GET['acct']))||(empty($_POST['new_user']))||(empty($_POST['new_pass']))||(empty($_POST['new_mail']))) {
   header("Location: user.php?error=1");
   exit();
 }

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if (isset($_POST['new_gm'])) {
    $gm = quote_smart($_POST['new_gm']);
  } else {
    $gm = "";
  }
  
 $id = quote_smart($_GET['acct']);
 $username = quote_smart($_POST['new_user']);
 $pass = quote_smart($_POST['new_pass']);
 $mail = quote_smart(trim($_POST['new_mail']));

//make sure username/pass at least 4 chars long and less than max
 if ((strlen($username) < 4) || (strlen($username) > 15) || (strlen($pass) < 4) || (strlen($pass) > 27)){
	mysql_close();
    header("Location: user.php?action=edit_user&error=8&acct='$id'");
    exit();
   }

require_once("defs/valid_lib.php");
//make sure it doesnt contain non english chars.
 if (!alphabetic($username)) {
	mysql_close();
    header("Location: user.php?action=edit_user&error=9&acct='$id'");
    exit();
   }
  
require_once("defs/valid_lib.php");
//make sure the mail is valid mail format
 if ((!is_email($mail))||(strlen($mail)  > 224)) {
     	header("Location: user.php?action=edit_user&error=15&acct='$id'");
     	exit();
   	}

 mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));

 $sql = "UPDATE accounts SET login = '$username', password = '$pass', email = '$mail', gm = '$gm' WHERE acct = '$id'";
 $query = mysql_query($sql) or die(error(mysql_error()));

if (mysql_affected_rows() <> 0) {
	mysql_close();
	header("Location: user.php?action=edit_user&error=13&acct='$id'");
	exit();
    } else {
		mysql_close();
		header("Location: user.php?action=edit_user&error=12&acct='$id'");
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
   $output .= "<h1><font class=\"error\">No Search Value Passed.</font></h1>";
   break;
case 3:
   $output .= "<h1>Search Results</h1>";
   break;
case 4:
   $output .= "<h1><font class=\"error\">New Account Creation Failed! (blank fields)</font></h1>";
   break;
case 5:
   $output .= "<h1>New Account Created</h1>";
   break;
case 6: 
   $output .= "<h1><font class=\"error\">You have Entered nonidentical Passwords.</font></h1>";
   break;
case 7:
   $output .= "<h1><font class=\"error\">Username Already Exist.</font></h1>";
   break;
case 8:
   $output .= "<h1><font class=\"error\">Username/Password size must be 4-to-15 characters long!</font></h1>";
   break;
case 9:
   $output .= "<h1><font class=\"error\">Username must contain [A-Z][a-z][0-9] only!</font></h1>";
   break;
case 10:
   $output .= "<h1><font class=\"error\">No Value Passed</font></h1>";
   break;
case 11:
   $output .= "<h1>Edit Account</h1>";
   break;
case 12:
   $output .= "<h1><font class=\"error\">Update Failed - Maybe None of the Fields Changed?</font></h1>";
   break;
case 13:
   $output .= "<h1>Updated ...</h1>";
   break;
case 14:
   $output .= "<h1><font class=\"error\">You have No Permission to Edit this Data</font></h1>";
   break;
case 15:
   $output .= "<h1><font class=\"error\">Please Provide a Valid Email Address!</font></h1>";
   break;
default: //no error
    $output .= "<h1>Browse Accounts</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "browse_users": 
   browse_users();
   break;
case "search": 
   search();
   break;
case "add_new": 
   add_new();
   break;
case "doadd_new": 
   doadd_new();
   break;
case "edit_user": 
   edit_user();
   break;
case "doedit_user": 
   doedit_user();
   break;
case "del_user": 
   del_user();
   break;
case "dodel_user": 
   dodel_user();
   break;
default:
    browse_users();
}

require_once("footer.php");
?>