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
 
include_once("header.php");
valid_login(3);

//print backup options step one
function backup_step1() {
 global $output;
 
 $output .= "<center><br/>
 <fieldset style=\"width: 700px;\">
	<legend>Backup Options</legend>
		<table class=\"hidden\">
      <tr>
        <td colspan=\"2\">Select option:</td>
      </tr>
      <tr>
        <td><form action=\"backup.php\" method=\"GET\">
          <input type=\"hidden\" name=\"action\" value=\"backup_step2\" />
		      <input type=\"hidden\" name=\"error\" value=\"3\" />
	         <select name=\"backup_action\">
		        <option value=\"save\">Save</option>
		        <option value=\"load\">Load</option>
	         </select> - To/From -
	         <select name=\"backup_from_to\">
		        <option value=\"web\">Web Saved Backup</option>
		        <option value=\"file\">Local File</option>
	         </select>
	        <input type=\"submit\" value=\"-= Go =-\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" />
	       </td></tr>";
	       
	makebutton("Back", "javascript:window.history.back()","80");
	
  $output .= "<tr>
    <td colspan=\"2\" align=\"left\">
      <input type=\"checkbox\" name=\"struc_backup\" value=\"1\" /> Save table(s) structural backup in addition to data backup.</td></tr>
		</form></td></tr></table><br/></fieldset><br/><br/></center>";
		
}

//print backup options step two
function backup_step2(){
 global $output, $backup_dir;

 if ((empty($_GET['backup_action']))||(empty($_GET['backup_from_to']))) {
   header("Location: backup.php?error=1");
   exit();
 }else {
		$backup_action = addslashes($_GET['backup_action']);
		$backup_from_to = addslashes($_GET['backup_from_to']);
		$struc_backup = addslashes($_GET['struc_backup']);
	}

  $upload_max_filesize=ini_get("upload_max_filesize");
  if (eregi("([0-9]+)K",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024;
  if (eregi("([0-9]+)M",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024;

switch ($backup_action){
case "load":
	$output .= "<center><fieldset style=\"width: 700px;\">
				<legend>Select File</legend>
				<br/><table class=\"hidden\">";
	if ($backup_from_to == "file"){
		$output .= "<tr>
        <td colspan=\"2\">Max. Upload Filesize is : $upload_max_filesize bytes (".round ($upload_max_filesize/1024/1024)." Mbytes)<br/></td>
    </tr>
    <tr>";
		$output .= "<td><form enctype=\"multipart/form-data\" action=\"backup.php?action=dobackup&backup_action=$backup_action&backup_from_to=$backup_from_to\" method=\"POST\">
					<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$upload_max_filesize\" />
					<input type=\"file\" name=\"uploaded_file\" accept=\"*/*\" />
					<input type=\"submit\" value=\"Upload\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></td>";
		}else {
			$output .= "<td><form action=\"backup.php?action=dobackup&backup_action=$backup_action&backup_from_to=$backup_from_to\" method=\"POST\">";	
			$output .= "<select name=\"selected_file_name\">";
			
			if (is_dir($backup_dir)) {
				if ($dh = opendir($backup_dir)) {
				while (($file = readdir($dh)) != false) {
					if (($file != '.')&&($file != '..')&&($file != '.htaccess'))
						$output .= "<option value=\"$file\">$file</option>";
				}
				closedir($dh);
				}
			}
			$output .= "</select><input type=\"submit\" value=\"-= Go =-\" onmouseover=\"this.className='mouseover'\" onmouseout=\"this.className=''\" /></form></td>";
	   }
		$output .= "<td>";
	makebutton("Back", "javascript:window.history.back()","80");
	$output .= "</td>
          </tr>
        </table><br/><br/></fieldset><br/><br/></center>";	
	break;
case "save":
	header("Location: backup.php?action=dobackup&backup_action=$backup_action&backup_from_to=$backup_from_to&struc_backup=$struc_backup");
  	exit();
	break;
default:
    header("Location: backup.php?error=1");
    exit();
 }
}

//do backup
function dobackup(){
 global $backup_dir, $tables_backup_acct, $tables_backup_char,
		$output, $dbhost, $dbuser, $dbpass, $acct_db, $char_db;

 if ((empty($_GET['backup_action']))||(empty($_GET['backup_from_to']))) {
   header("Location: backup.php?error=1");
   exit();
 } else {
		$backup_action = addslashes($_GET['backup_action']);
		$backup_from_to = addslashes($_GET['backup_from_to']);
		$struc_backup = addslashes($_GET['struc_backup']);
	}

 if (("load" == $backup_action)&&("file" == $backup_from_to)) {
	if (!eregi("(\.(sql|qbquery))$",$_FILES["uploaded_file"]["name"])) error("You may only upload .sql or .qbquery files.");
	
	$uploaded_filename=str_replace(" ","_",$_FILES["uploaded_file"]["name"]);
  $uploaded_filename=preg_replace("/[^_A-Za-z0-9-\.]/i",'',$uploaded_filename);
	$file_name_new =$uploaded_filename."_".date("m.d.y_H.i.s").".sql";
	
	move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], "$backup_dir/$file_name_new") or die (error("Couldn't Upload File<br/>Check the directory permissions for $backup_dir"));
	if (file_exists("$backup_dir/$file_name_new")) {
		require_once("defs/mysql_lib.php");
		$queries = mysql_run_sql_script("$backup_dir/$file_name_new",true);
		header("Location: backup.php?error=4&tot=$queries");
   		exit();
	} else error("File Not Found!");
	
  } else if (("load" == $backup_action)&&("web" == $backup_from_to)) {
 
 		if (empty($_POST['selected_file_name'])) {
   			header("Location: backup.php?error=1");
   			exit();
 			} else $file_name = addslashes($_POST['selected_file_name']);
		
		if (file_exists("$backup_dir/$file_name")) {
			require_once("defs/mysql_lib.php");
			$queries = mysql_run_sql_script("$backup_dir/$file_name",false);
			header("Location: backup.php?error=4&tot=$queries");
   			exit();
		} else error("File Not Found!");

 } else if (("save" == $backup_action)&&("file" == $backup_from_to)) {
			//save and send to user
			$file_name_new = "backup_".date("m.d.y_H.i.s").".sql";
			
			require_once("defs/mysql_lib.php");
			

			$output_file .= "CREATE DATABASE /*!32312 IF NOT EXISTS*/ $char_db;\n";
			$output_file .= "USE $char_db;\n\n";
  		 foreach ($tables_backup_char as $value) {
				$table_dump = mysql_table_dump ("$char_db",$value,$struc_backup);
				$output_file .= $table_dump;
				} 
			
			Header("Content-type: application/octet-stream");
			Header("Content-Disposition: attachment; filename=$file_name_new");
			echo $output_file;
			exit();
	
 } else if (("save" == $backup_action)&&("web" == $backup_from_to)) {
			//save backup to web/backup folder
			 $file_name_new = "backup_".date("m.d.y_H.i.s").".sql";
			 
			$fp = fopen("$backup_dir/$file_name_new", 'w') or die (error("Couldn't Create File!"));

			fwrite($fp, "CREATE DATABASE /*!32312 IF NOT EXISTS*/ $acct_db;\n")or die (error("Couldn't Write to a File!"));
			fwrite($fp, "USE $acct_db;\n\n")or die (error("Couldn't Write to a File!"));
			
			require_once("defs/mysql_lib.php");
			
			
			fwrite($fp, "CREATE DATABASE /*!32312 IF NOT EXISTS*/ $char_db;\n")or die (error("Couldn't Write to a File!"));
			fwrite($fp, "USE $char_db;\n\n")or die (error("Couldn't Write to a File!"));
			
			foreach ($tables_backup_char as $value) {
				$table_dump = mysql_table_dump ("$char_db",$value,$struc_backup);
				fwrite($fp, $table_dump)or die (error("Couldn't Write to a File!"));
				} 
			
			fclose($fp);
			header("Location: backup.php?error=2");
   			exit();
 } else {
	//non of the options = error
	header("Location: backup.php?error=1");
   exit();
 }

}

//error reports
if(isset($_GET['error'])) $err = $_GET['error'];
	else $err = NULL;
	
$output .= "<div class=\"top\">";
switch ($err) {
case 1:
   $output .= "<h1><font class=\"error\">Some Fields Left Blank</font></h1></div>";
   break;
case 2:
   $output .= "<h1><font class=\"error\">Backup Finished Successfully</font></h1></div>";
   break;
case 3:
	$output .= "<h1>Select Backup File</h1></div>";
   break;
case 4:
	if(isset($_GET['tot'])) $total_queries = $_GET['tot'];
		else $total_queries = NULL;
	$output .= "<h1><font class=\"error\">File Loaded and $total_queries Queries Executed Successfully.</font></h1></div>";
	break;
default: //no error
   $output .= "<h1>Back-Up Logon Database</h1></div>";
   $output .= "<span style='color:white;'><center><font size=\"3\">The following tables will be saved:</font><br/><br/></span>";
 	$output .= "<br/><font size=\"3\">$acct_db<br/></font>";
 	foreach ($tables_backup_char as $value) $output .= ".$value<br/>";
	$output .= "</center>";
}

//action defines
if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "backup_step2":
	backup_step2();
	break;
case "dobackup":
	dobackup();
	break;
default:
    backup_step1();
}

include_once("footer.php");
?>