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

//saves data from selected DB.table - Thanks to Assure for partial structurbackup code
function mysql_table_dump ($database,$table,$construct) {
 global $dbhost, $dbuser, $dbpass;

 mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
 mysql_select_db($database) or die(error("Error - Can't open the database ! ('$database')"));

 $result = "--\n";
 $result .= "-- Dump of $database.$table\n";
 $result .= "-- Dump DATE : " . date("m.d.y H:i:s") ."\n--\n\n";
 
 if($construct){
	$result .= "-- Table structure for table $database.$table\n";
				
	if(!($fi = @mysql_query("DESC ".$table))){
				error(mysql_error());
			}
			
	$result.= "DROP TABLE IF EXISTS ".$table.";\n";

	$pri = "";
	$creatinfo = array();
	while($tmp = @mysql_fetch_row($fi)){
		$con = "`".$tmp[0]."` ";
		$con .= trim($tmp[1]." ");
		if($tmp[2] != "YES") { $con .= " NOT NULL"; }
		if($tmp[4]) {
			if ($tmp[4] == 'CURRENT_TIMESTAMP' || $tmp[4] == 'timestamp') $con .= " default ".$tmp[4]; 
				else $con .= " default '".$tmp[4]."'"; 
			} else if($tmp[4] === '' && $tmp[3] != "PRI") { 
					$con .= " default ''"; 
					} else if(strlen($tmp[4])!=0) { 
							$con .= " default '0'"; 
							}
		if(strtolower($tmp[5]) == "auto_increment") { $con .= " auto_increment"; }
				
		$creatinfo[] = $con;
	}

	$fieldscon = implode(",\n\t", $creatinfo);
	$result.= "CREATE TABLE ".$table." (";
	$result.= "\n\t".$fieldscon;

	$qkey = @mysql_query("SHOW INDEX FROM ".$table);

	if($rkey = @mysql_fetch_array($qkey)){
		$knames = array();
		$keys = array();
		do {
			$keys[$rkey["Key_name"]]["nonunique"] = $rkey["Non_unique"];
			if(!$rkey["Sub_part"]){
				$keys[$rkey["Key_name"]]["order"][$rkey["Seq_in_index"]-1] = $rkey["Column_name"];
			} else {
				$keys[$rkey["Key_name"]]["order"][$rkey["Seq_in_index"]-1] = $rkey["Column_name"]."(".$rkey["Sub_part"].")";
			}

			$flag = false;
			for($l=0; $l<sizeof($knames); $l++){
				if($knames[$l] == $rkey["Key_name"]) $flag = true;
				}

			if(!$flag) { $knames[] = $rkey["Key_name"]; }
		} while($rkey = @mysql_fetch_array($qkey));

		for($kl=0; $kl<sizeof($knames); $kl++){
			if($knames[$kl] == "PRIMARY") {
				$result.= ",\n\tPRIMARY KEY";
			} else {
				if($keys[$knames[$kl]]["nonunique"] == "0") {
					$result.= ",\n\tUNIQUE `". $knames[$kl]."`";
				} else {
					$result.= ",\n\tKEY `". $knames[$kl]."`";
				}
			}
         $a=@implode("`,`", $keys[$knames[$kl]]["order"]);

			$result.= " (`".$a."`)";
		}
	}

	$query_res = mysql_query("SHOW TABLE STATUS FROM $database WHERE Name = '$table'");
	$tmp = @mysql_fetch_row($query_res);
	
	$query_charset = mysql_query("SHOW VARIABLES WHERE Variable_name = 'character_set_database'");
	
	$info = " ";
	if($tmp[1]) $info .= "ENGINE=$tmp[1] ";
	$info .= "DEFAULT CHARSET=".mysql_result($query_charset, 0, 'Value')." ";
	if($tmp[16]) $info .= strtoupper($tmp[16])." ";
	if($tmp[10]) $info .= "AUTO_INCREMENT=$tmp[10] ";
	if($tmp[17]) $info .= "COMMENT='$tmp[17]'";

	$result.= "\n)$info;\n\n";
 }

 $query = mysql_query("SELECT * FROM $table") or die(error(mysql_error()));
 $num_fields = mysql_num_fields($query);
 $numrow = mysql_num_rows($query);

 if ($numrow){
	$result .= "-- Dumping data for table $database.$table\n";
	$result .= "LOCK TABLES $table WRITE;\n";
	$result .= "DELETE FROM $table;\n";

	$result .= "INSERT INTO ".$table." (";
	for($count = 0; $count < $num_fields; $count++) {
		$result .= "`".mysql_field_name($query,$count)."`";
		if ($count < ($num_fields-1)) $result .= ",";
		} 
	$result .= ") VALUES \n";

	for ($i =0; $i<$numrow; $i++) {
		$result .= "\t(";
		$row = mysql_fetch_row($query);
		for($j=0; $j<$num_fields; $j++) {
			$row[$j] = addslashes($row[$j]);
			$row[$j] = ereg_replace("\n","\\n",$row[$j]);
			if (isset($row[$j])) {
				if (mysql_field_type($query,$j) == "int") $result .= "$row[$j]";
					else $result .= "'$row[$j]'" ;
				}else $result .= "''";
			if ($j<($num_fields-1)) $result .= ",";
			}   
		if ($i < ($numrow-1)) $result .= "),\n";
		}
	$result .= ");\n";
	$result .= "UNLOCK TABLES;\n";
 } else $result .= "-- EMPTY\n";
 
 mysql_close();
 return $result."\n";
}

//executes given file into sql
function mysql_run_sql_script ($path,$unlink) {
	global $dbhost, $dbuser, $dbpass;

	$fp = fopen($path, 'r') or die (error("Couldn't Open File!"));
	mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));

	$query="";
	$queries=0;
	$linenumber=0;
	$inparents=false;

	while (!feof($fp)){
		$dumpline = "";
		while (!feof($fp) && substr ($dumpline, -1) != "\n"){  
			$dumpline .= fgets($fp, 16384);
			}

		$dumpline=ereg_replace("\r\n$", "\n", $dumpline);
		$dumpline=ereg_replace("\r$", "\n", $dumpline);

		if (!$inparents){ 
			$skipline=false;
			if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, '#') === 0 || strpos ($dumpline, '-- ') === 0)){ 
				$skipline=true;
				}

			if ($skipline){
				$linenumber++;
				continue;
				}
		}

		$dumpline_deslashed = str_replace ("\\\\","",$dumpline);

		$parents=substr_count ($dumpline_deslashed, "'")-substr_count ($dumpline_deslashed, "\\'");
		if ($parents % 2 != 0)
			$inparents=!$inparents;

		$query .= $dumpline;

		if (ereg(";$",trim($dumpline)) && !$inparents){ 
			if (!mysql_query(trim($query))){
				fclose($fp);
				if($unlink) unlink($path);
				$err = ereg_replace ("\n","",mysql_error());
				$err = ereg_replace ("\r\n$","",$err);
				$err = ereg_replace ("\r$","",$err);
				error("SQL Error at the line: $linenumber in $path <br/> $err");
				break;
			}
			$queries++;
			$query="";
		}
		$linenumber++;
	}
	mysql_close();
	fclose($fp);
	return $queries;
}
?>