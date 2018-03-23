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

//  BROWSE  TICKETS
function browse_tickets() {
 global  $output,$dbhost, $dbuser, $dbpass, $char_db, $itemperpage;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if(isset($_GET['start'])) $start = quote_smart($_GET['start']);
	else $start = 0;

 if(isset($_GET['order_by'])) $order_by = quote_smart($_GET['order_by']);
	else $order_by = "guid";

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 //get total number of items
 $query_1 = mysql_query("SELECT count(*) FROM gm_tickets") or die(error(mysql_error()));
 $data_1 = mysql_fetch_row($query_1)or die(error(mysql_error()));
 $all_record = $data_1[0];

 $sql = "SELECT guid,name,message,timestamp FROM gm_tickets ORDER BY '$order_by' DESC LIMIT $start, $itemperpage";
 $query = mysql_query($sql) or die(error(mysql_error()));
 $this_page = mysql_num_rows($query);

 $output .="<center><table class=\"top_hidden\">
          <tr><td>";

 $index_url = "ticket.php?action=browse_tickets&amp;order_by=$order_by";
 $paging = generate_pagination($index_url, $all_record, $itemperpage, $start);

 if (!empty($paging))  $output .= " | ";
 $output .= $paging;
 $output .= "</td></tr></table>";

 $output .= "
 <form method=\"get\" action=\"ticket.php\">
	<input type=\"hidden\" name=\"action\" value=\"delete_tickets\">
	<input type=\"hidden\" name=\"start\" value=\"$start\">
	
 <table class=\"lined\">
   <tr> 
	<td width=\"5%\" class=\"head\">Delete</td>
	<td width=\"5%\" class=\"head\">Edit</td>
	<td width=\"8%\" class=\"head\"><a href=\"ticket.php?order_by=guid\" class=\"head_link\">ID</a></td>
	<td width=\"15%\" class=\"head\"><a href=\"ticket.php?order_by=name\" class=\"head_link\">Sender</a></td>
	<td width=\"60%\" class=\"head\"><a href=\"ticket.php?order_by=message\" class=\"head_link\">Ticket Text</a></td>
    <td width=\"7%\" class=\"head\"><a href=\"ticket.php?order_by=timestamp\" class=\"head_link\">Time Made</a></td>
  </tr>";

 if ($this_page < $itemperpage) $looping = $this_page; else $looping = $itemperpage;

 for ($i=1; $i<=$looping; $i++)	{
    $ticket = mysql_fetch_row($query) or die(error("No Tickets Found!"));

	$owner_name = @mysql_result($query, 0, 'name');

		 $output .= "<tr>
   		    <td><input type=\"checkbox\" name=\"check[]\" value=\"$ticket[0]\" /></td>
   		    <td><a href=\"ticket.php?action=edit_ticket&amp;id=$ticket[0]\">Edit</a></td>
   		    <td>$ticket[0]</td>
   		    <td><a href=\"char.php?id=$ticket[1]\">$owner_name</a></td>
			<td>$ticket[2] ...</td>
			<td>$ticket[3]</td>
            </tr>";
}

$output .= "<tr><td colspan=\"12\" class=\"hidden\"><br/></td></tr>
	<tr>
		<td colspan=\"4\" align=\"left\" class=\"hidden\">
        <input type=\"submit\" value=\"Delete Checked Ticket(s)\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	  </td>
      <td colspan=\"2\" align=\"right\" class=\"hidden\">Total Tickets : $all_record</td>
	 </tr>
 </table>
 </form><br/></center>";
 
mysql_close();

}

//  DELETE TICKETS
function delete_tickets() {
global $dbhost, $dbuser, $dbpass, $char_db;

mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));

 if(isset($_GET['check'])) $check = quote_smart($_GET['check']);
	else {
			header("Location: ticket.php?error=1&amp;style=$style");
			exit();
			}

 $deleted_tickets = 0;

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 for ($i=0; $i<count($check); $i++) {
    if ($check[$i] <> "" ) {
		$sql = "DELETE FROM gm_tickets WHERE guid = '$check[$i]'";
		$query = mysql_query($sql) or die(error(mysql_error()));
		$deleted_tickets++;
		}
	}

 mysql_close();

 if ($deleted_tickets == 0){
	header("Location: ticket.php?error=3");
    exit();
   }else {
		header("Location: ticket.php?error=2");
		exit();
		}
}

//  EDIT   TICKET
function edit_ticket() {
 global  $output,$dbhost, $dbuser, $dbpass, $char_db;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 if(isset($_GET['id'])) $id = quote_smart($_GET['id']);
	else header("Location: ticket.php?error=1");

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $sql = "SELECT name,guid,message FROM gm_tickets WHERE guid = '$id'";
 $query = mysql_query($sql) or die(error(mysql_error()));

 if (mysql_num_rows($query) == 1) {
	$ticket = mysql_fetch_row($query) or die(error(mysql_error()));

	$owner_name = mysql_result($query, 0, 'name');
	
	$output .= "<center>
	<fieldset style=\"width: 550px;\">
	<legend>Edit / Reply</legend>
    <form method=\"POST\" action=\"ticket.php?action=do_edit_ticket\">
	<input type=\"hidden\" name=\"id\" value=\"$id\" />
	<table class=\"flat\">
      <tr>
        <td>Ticket ID</td>
        <td>$ticket[0]</td>
      </tr>
      <tr>
        <td>Submitted by:</td>
        <td><a href=\"char.php?id=$ticket[1]\">$owner_name</a></td>
      </tr>
	  <tr>
        <td valign=\"top\">Text</td>
        <td><TEXTAREA NAME=\"new_text\" ROWS=5 COLS=40>$ticket[2]</TEXTAREA></td>
      </tr>

      <tr>
        <td><input type=\"submit\" value=\"Update Ticket\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>
        <td>";
$output .= " <table class=\"hidden\">
          <tr>
            <td>";
	makebutton("Back", "ticket.php","330");
$output .= "     </td>
          </tr>
        </table>";
	$output .= "</td></tr>
     </table>
     </form></fieldset><br/><br/></center>";
  } else error("Unexpected Error!<br/>Wrong Value Passed.");
  
 mysql_close();
}

//  DO EDIT  TICKET
function do_edit_ticket() {
 global $dbhost, $dbuser, $dbpass, $char_db;

 if((!isset($_POST['new_category']))||(empty($_POST['new_text']))||(empty($_POST['id']))) {
   header("Location: ticket.php?error=1");
   exit();
 } 

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 
 $new_category = quote_smart($_POST['new_category']);
 $new_text = quote_smart($_POST['new_text']);
 $id = quote_smart($_POST['id']);

 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));

 $sql = "UPDATE gm_tickets SET message='$new_text', WHERE guid = '$id'";
 $query = mysql_query($sql) or die(error(mysql_error()));

 if (mysql_affected_rows() <> 0) {
	mysql_close();
	header("Location: ticket.php?error=5");
    exit();
    } else {
		mysql_close();
		header("Location: ticket.php?error=6");
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
   $output .= "<h1><font class=\"error\">Tickets Deleted Successfully.</font></h1>";
   break;
case 3:
   $output .= "<h1><font class=\"error\">No Tickets Deleted!</font></h1>";
   break;
case 4:
   $output .= "<h1>Edit Ticket</h1>";
   break;
case 5:
   $output .= "<h1><font class=\"error\">Ticket Updated</font></h1>";
   break;
case 6:
   $output .= "<h1><font class=\"error\">Error Updating Ticket</font></h1>";
   break;
default: //no error
    $output .= "<h1>Browse Tickets</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "browse_tickets": 
   browse_tickets();
   break;
case "delete_tickets": 
   delete_tickets();
   break;
case "edit_ticket": 
   edit_ticket();
   break;
case "do_edit_ticket": 
   do_edit_ticket();
   break;
default:
    browse_tickets();
}

require_once("footer.php");
?>