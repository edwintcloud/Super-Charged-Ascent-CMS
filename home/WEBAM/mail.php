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
 
include("header.php");
valid_login(1);

// print mail form
function print_mail_form(){
 global $output;

if(isset($_GET['to'])) $to = $_GET['to'];
	else $to = NULL;

$output .= "<center>
			<form name=\"main\" action=\"mail.php?action=send_mail\" method=\"POST\">
				 <fieldset style=\"width: 770px;\">
					<legend>Mail Options / Type</legend>
					<br/>
					<table class=\"top_hidden\" style=\"width: 720px;\">
						<tr>
						<td align=\"left\">
						<select name=\"type\">
							<option value=\"ingame_mail\">InGame Mail</option>
						</select>
						</td>
						<td align=\"left\">Group Send:<select name=\"grup_send\">
						    <optgroup label=\"Both\">
							   <option value=\"gm_level\">GM Level</option>
						    </optgroup>
						    <optgroup label=\"inGame Mail\">
							   <option value=\"char_level\">Character Level</option>
							   <option value=\"online\">Online</option>
						    </optgroup>
						  </select>
						  <select name=\"grup_sign\">
							 <option value=\"=\">=</option>
							 <option value=\"<\"><</option>
							 <option value=\">\">></option>
							 <option value=\"!=\">!=</option>
						  </select>
						  <input type=\"text\" name=\"grup_value\" size=\"20\" maxlength=\"40\" />
						  <td align=\"right\">&nbsp;<input type=\"submit\" value=\" Send \" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
						</td>
						</tr>
					</table>
					<hr />
					<table class=\"top_hidden\" style=\"width: 720px;\">
					 <tr>
					   <td align=\"left\">
						Sender: <input type=\"text\" name=\"from\" size=\"22\" value=\"$from\" maxlength=\"225\" />
						Recipient: <input type=\"text\" name=\"to\" size=\"22\" value=\"$to\" maxlength=\"225\" />
						Subject: <input type=\"text\" name=\"subject\" size=\"22\" maxlength=\"50\" />
						</td>
					 </tr>
					</table><hr />
					<table class=\"top_hidden\" style=\"width: 720px;\">
						<tr>
            <td><b>In Game Mail Attachments:</b>
						</td>
						<td>Item ID:<input type=\"text\" name=\"item\" size=\"10\" maxlength=\"11\" />
						</td>
						<td>How Many:<input type=\"text\" name=\"quant\" size=\"5\" maxlength=\"3\" />
						</td>
						<td align=\"right\">Gold:<input type=\"text\" name=\"money\" size=\"10\" maxlength=\"7\" />
						</td>
					 </tr>
					</table><hr />
					<b>Notes:</b> *If you are using 'Group Send', leave 'Recipient' field blank. <br/>*Only designate 'Sender' for in-game mail. Use your characters name.<br/>
			</fieldset>
			<p><fieldset style=\"width: 770px;\">
					<legend>Mail Body</legend>
					<p>
					<TEXTAREA NAME=\"body\" ROWS=14 COLS=92></TEXTAREA>
					</p>
			</fieldset></p>
			</form>
			</center><br/>";
}

// Send the actual mail(s)
function send_mail(){
 global  $output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db, $user_name,
		$mailer_type, $from_mail, $smtp_host, $smtp_port, $smtp_username, $smtp_password;
 include("defs/arrays.php");
 if ((empty($_POST['body']))||(empty($_POST['subject']))||(empty($_POST['type']))||(empty($_POST['grup_sign']))||(empty($_POST['grup_send']))) {
   header("Location: mail.php?error=1");
   exit();
 }
 
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 $body = quote_smart($_POST['body']);
 $subject = quote_smart($_POST['subject']);
 $type = $_POST['type'];
 $attach = quote_smart($_POST['money']);
 $item = quote_smart($_POST['item']);
 $stack = quote_smart($_POST['quant']);
 $from = quote_smart($_POST['from']);
 if(!empty($_POST['to'])){ 
 $to = quote_smart($_POST['to']);
 }else {
			$to = NULL;
			if(!isset($_POST['grup_value'])){
				header("Location: mail.php?error=1");
				exit();
				} else {
						$grup_value = quote_smart($_POST['grup_value']);
						$grup_sign = quote_smart($_POST['grup_sign']);
						$grup_send = quote_smart($_POST['grup_send']);
						}
			}
 mysql_close();

//use the specified mail action
switch ($type) {
case "email":

 require("defs/class.phpmailer.php");
 $mail = new PHPMailer();
		$mail->Mailer = $mailer_type;
		if ($mailer_type == "smtp"){
			$mail->Host = $smtp_host;
			$mail->Port = $smtp_port;
			if($smtp_username != '') {
				$mail->SMTPAuth  = true;
				$mail->Username  = $smtp_username;
				$mail->Password  =  $smtp_password;
				}
		}

		$mail->From = $from_mail;
		$mail->FromName = $user_name;
		$mail->Subject = $subject;
		$mail->IsHTML(true);

		$body = str_replace("\n", "<br/>", $body);
		$body = str_replace("\r", " ", $body);
		$body = preg_replace( "/([^\/=\"\]])((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>",  $body);
		$body = preg_replace('/([^\/=\"\]])(www\.)(\S+)/', '\\1<a href="http://\\2\\3" target="_blank">\\2\\3</a>', $body);
 
		$mail->Body = $body;
		$mail->WordWrap = 50;
		
 if($to){ //single Recipient
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$mail->ClearAddresses(); 
		header("Location: mail.php?error=3&mail_err=".$mail->ErrorInfo);
		exit();
	} else {
		$mail->ClearAddresses(); 
		header("Location: mail.php?error=2");
		exit();
		}
 
 } elseif (isset($grup_value)){ //grup send
			mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
			mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
			
			$email_array = array();
			switch ($grup_send) {
			case "gm_level":
				$sql = "SELECT email FROM accounts WHERE gmlevel $grup_sign '$grup_value'";
				$result = mysql_query($sql) or die(error(mysql_error()));
				while($user = mysql_fetch_row($result)){
					if($user[0] != "") array_push($email_array, $user[0]);
					}
			break;
			case "banned":
				$sql = "SELECT email FROM accounts WHERE banned $grup_sign '$grup_value'";
				$result = mysql_query($sql) or die(error(mysql_error()));
				while($user = mysql_fetch_row($result)){
					if($user[0] != "") array_push($email_array, $user[0]);
					}
			break;
			default:
			mysql_close();
			header("Location: mail.php?error=5");
			exit();
			}
			mysql_close();

			foreach ($email_array as $mail_addr){
				$mail->AddAddress($mail_addr);
				if(!$mail->Send()) {
					$mail->ClearAddresses(); 
					header("Location: mail.php?error=3&mail_err=".$mail->ErrorInfo);
					exit();
				} else {
					$mail->ClearAddresses();
					}
			}
			header("Location: mail.php?error=2");
			exit();

	} else {
			header("Location: mail.php?error=1");
			exit();	
			}
  break;


case "ingame_mail":
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
 
 if($to){ //single Recipient
  $sql1 = "SELECT guid FROM `characters` WHERE name ='$from'";
	$sql2 = "SELECT guid FROM `characters` WHERE name ='$to'";
	$res = mysql_query($sql1) or die(error(mysql_error()));
	$result = mysql_query($sql2) or die(error(mysql_error()));
	
	if (mysql_num_rows($res) == 1) {
		$sender = mysql_result($res, 0, 'guid');

	if (mysql_num_rows($result) == 1) 
		$receiver = mysql_result($result, 0, 'guid');

					$sql = "INSERT INTO mailbox_insert_queue VALUES ('$sender','$receiver','$subject','$body','41','$attach','$item','$stack')";
					$result = mysql_query($sql) or die(error(mysql_error()));

	} else {
			mysql_close();
			header("Location: mail.php?error=4");
			exit();
	}
	mysql_close();

	header("Location: mail.php?error=2");
	exit();
	break;
	
} elseif(isset($grup_value)){ //grup send
		mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
	  mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));		
		  $sq1 = "SELECT guid FROM `characters` WHERE name ='$from'";
	    $res = mysql_query($sq1) or die(error(mysql_error()));
	
	if (mysql_num_rows($res) == 1) {
		$sender = mysql_result($res, 0, 'guid');
	}
switch ($grup_send) {
	case "gm_level":
				mysql_select_db($acct_db) or die(error("Error - Can't open the database ! ('$acct_db')"));
				$sql = "SELECT acct FROM accounts WHERE gm $grup_sign '$grup_value'";
				$result = mysql_query($sql) or die(error(mysql_error()));
				mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
				while($acc = mysql_fetch_row($result)){
					$sql1 = "SELECT guid FROM `characters` WHERE acct = '$acc[0]'";
					$result_1 = mysql_query($sql1) or die(error(mysql_error()));
					while($char = mysql_fetch_row($result_1)){
						array_push($char_array, $char[0]);
						}
					}
			break;
  case "online":
				mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
				$sql2 = "SELECT guid FROM `characters` WHERE online $grup_sign '$grup_value'";
				$result2 = mysql_query($sql2) or die(error(mysql_error()));
				while($user = mysql_fetch_row($result2)){
					array_push($char_array, $user[0]);
					}
			break;
  case "char_level":
				mysql_select_db($char_db) or die(error("Error - Can't open the database ! ('$char_db')"));
				$sql3 = "SELECT guid,SUBSTRING_INDEX(SUBSTRING_INDEX(`data`, ' ', 35), ' ', -1) FROM `characters`";
				$result3 = mysql_query($sql3) or die(error(mysql_error()));
				while($user1 = mysql_fetch_row($result3)){
					switch ($grup_sign){
						case "=":
						if($user[1] == $grup_value) array_push($char_array, $user1[0]);
						break;
						case "<":
						if($user[1] < $grup_value) array_push($char_array, $user1[0]);
						break;
						case ">":
						if($user[1] > $grup_value) array_push($char_array, $user1[0]);
						break;
						case "!=":
						if($user[1] <> $grup_value) array_push($char_array, $user1[0]);
						break;
						default:
						header("Location: mail.php?error=1");
						exit();
						}
					}
			break;
  default:
			mysql_close();
			header("Location: mail.php?error=5");
			exit();
			}
	foreach ($char_array as $receiver){
					$sql = "INSERT INTO mailbox_insert_queue VALUES ('$sender','$receiver','$subject','$body','41','$attach','$item','$stack')";
					$result = mysql_query($sql) or die(error(mysql_error()));
			}
			header("Location: mail.php?error=2");
			exit();
 }
 
default:
	header("Location: mail.php?error=1");
	exit();
}
  mysql_close();
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
   $output .= "<h1><font class=\"error\">Mail Sent Successfully.</font></h1>";
   break;
case 3:
	if(isset($_GET['mail_err'])) $mail_err = $_GET['mail_err'];
	else $mail_err = NULL;
   $output .= "<h1><font class=\"error\">Mail Error: $mail_err</font></h1>";
   break;
case 4:
   $output .= "<h1><font class=\"error\">No Mail Recipient Found.</font></h1>";
   $output .= "Use Character name in case of inGame Mail - Email address in case of email.";
   break;
case 5:
   $output .= "<h1><font class=\"error\">You cannot use this option at current configurations.</font></h1>";
   $output .= "Some of the 'Group Send' options can be used only with 'inGame mail' or 'Email' but not with both.";
   break;
default: //no error
   $output .= "<h1>Send Mail</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "send_mail":
	send_mail();
	break;
default:
    print_mail_form();
}

include("footer.php");
?>