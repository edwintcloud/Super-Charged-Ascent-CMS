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
valid_login(3);

// DO UPLOAD/SUBMIT PATCH
function print_upload(){
 global $backup_dir, $output, $acct_db, $char_db, $world_db;

if (isset($_FILES["uploaded_file"]["name"])){
 if (file_exists($_FILES["uploaded_file"]["tmp_name"])){	
	$buffer = implode('', file($_FILES["uploaded_file"]["tmp_name"]));
	} else error("File Not Found!");
 } else $buffer = "";
 
	$upload_max_filesize=ini_get("upload_max_filesize");
	if (eregi("([0-9]+)K",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024;
	if (eregi("([0-9]+)M",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024;
 
	$output .= "<span style='color:white;'><center>Select SQL File to Open :<br/>Max. Filesize $upload_max_filesize bytes (".round ($upload_max_filesize/1024/1024)." Mbytes)<br/><br/></span>";
	$output .= "<form enctype=\"multipart/form-data\" action=\"run_patch.php?action=print_upload\" method=\"POST\">
				<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$upload_max_filesize\" />
				<input type=\"file\" name=\"uploaded_file\" accept=\"*/*\" />
				<input type=\"submit\" value=\"Open\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form><hr />";
			
	$output .= "<form action=\"run_patch.php?action=do_run_patch\" method=\"POST\">	
				<table class=\"top_hidden\">
				<tr>
				 <td align=\"left\">The following query(s) will be executed.<br/>Make sure each query line ends with \"&#059\" sign.</td>
				 <td align=\"right\">Select DB to be used by Default: 
				 <select name=\"use_db\">
				 <option value=\"$world_db\">$world_db</option>
					<option value=\"$acct_db\">$acct_db</option>
				 </select>
				</td>
				</tr></table>
				<TEXTAREA NAME=\"query\" ROWS=14 COLS=93>$buffer</TEXTAREA><br/>
				<input type=\"submit\" value=\"+++++ Run SQL +++++\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form></center><br/>";	
}	

// DO Run the Query line by line
function do_run_patch(){
 global  $output, $dbhost, $dbuser, $dbpass;

 if ((empty($_POST['query']))||(empty($_POST['use_db']))) {
   header("Location: run_patch.php?error=1");
   exit();
 }
 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 $use_db = quote_smart($_POST['use_db']);
 $query = $_POST['query'];
 
 mysql_select_db($use_db) or die(error("Error - Can't open the database ! ('$use_db')"));
	
 $new_queries = array();
 $good = 0;
 $bad = 0;
 $line = 0;
 
 $queries = explode("\n",$query);
 for($i=0; $i<count($queries); $i++) {
    $queries[$i] = trim($queries[$i]);
	
    if(strpos ($queries[$i], '#') === 0 || strpos ($queries[$i], '--') === 0)  $line++;
		else array_push($new_queries, $queries[$i]);
   }
 $qr=split(";\n",implode("\n",$new_queries));
 
 foreach($qr as $qry) {
	$line++;
	if(trim($qry)) (mysql_query(trim($qry))?$good++:$bad++);
		if ($bad) {
					$err = ereg_replace ("\n","",mysql_error());
					$err = ereg_replace ("\r\n$","",$err);
					$err = ereg_replace ("\r$","",$err);
					error("MySQL syntax error in line: $line <br/>$err");
					exit();
					}
  }

 mysql_close();
 
 if ($queries) header("Location: run_patch.php?error=2&tot=$good");
	else {
		header("Location: run_patch.php?error=3");
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
	if(isset($_GET['tot'])) $tot = $_GET['tot'];
		else $tot = NULL;
   $output .= "<h1><font class=\"error\">Total of $tot SQL Query(s) Executed Successfully.</font></h1>";
   break;
case 3:
   $output .= "<h1><font class=\"error\">Zero Result Returned / Non queries Found.</font></h1>";
   break;
default:
   $output .= "<h1>Run SQL Patch</h1>";
}
$output .= "</div>";

if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "do_run_patch":
	do_run_patch();
	break;
default:
    print_upload();
}

require_once("footer.php");
?>
