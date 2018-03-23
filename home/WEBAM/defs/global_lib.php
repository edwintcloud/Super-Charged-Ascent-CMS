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

//WeBAM version
$version = "version 1.84"; 

//global output string
$output = " ";

//site to reference for item information
$item_datasite = "http://www.wowhead.com/?item="; //site to use for item data

//to avoid Strict Standards notices in php 5.1
if (function_exists ('date_default_timezone_set') ) {
	date_default_timezone_set("$timezone");
}

//override PHP error reporting
if ($debug) {
	error_reporting (E_ALL ^ E_NOTICE);
	}
    
//validates session's vars and restricting access to given level
function valid_login($restrict_lvl){
 if (isset($_SESSION['user_lvl']) && isset($_SESSION['user_id'])) {
		$user_lvl = $_SESSION['user_lvl'];
		$user_id = $_SESSION['user_id'];	
	} else { 
		header("Location: login.php");
		exit();
		}

 if ($user_lvl < $restrict_lvl) {
	header("Location: login.php?error=5");
	exit();
	}
	
 if (isset($_SESSION['uname'])) $user_name = $_SESSION['uname'];
	else {
		header("Location: login.php");
		exit();
		}
}

//making buttons - just to make them all look the same
function makebutton($xtext, $xlink, $xwidth) {
 global $output;
 $output .="<div><a class=\"button\" style=\"width:".$xwidth."px;\" href=\"$xlink\">$xtext</a></div>";
}

// Generate paging navigation. Original from PHPBB with some modifications to make them more simple
function generate_pagination($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE) {
	if ( !$num_items ) return "";
	$total_pages = ceil($num_items/$per_page);
	if ( $total_pages == 1 ) {
		return "";
	}
	$on_page = floor($start_item / $per_page) + 1;
	$page_string = "";
	if ( $total_pages > 10 ) {
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		for($i = 1; $i < $init_page_max + 1; $i++) {
			$page_string .= ( $i == $on_page ) ? "<b>" . $i . "</b>" : "<a href=\"$base_url&amp;start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
			if ( $i <  $init_page_max ) {
				$page_string .= ", ";
			}
		}
		if ( $total_pages > 3 ) {
			if ( $on_page > 1  && $on_page < $total_pages ) {
				$page_string .= ( $on_page > 5 ) ? " ... " : ", ";
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;

				for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {
					$page_string .= ($i == $on_page) ? "<b>" . $i . "</b>" : "<a href=\"$base_url&amp;start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
					if ( $i <  $init_page_max + 1 ) {
						$page_string .= ", ";
					}
				}
				$page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : ", ";
			} 
			else {
				$page_string .= " ... ";
			}
			for($i = $total_pages - 2; $i < $total_pages + 1; $i++) {
				$page_string .= ( $i == $on_page ) ? "<b>" . $i . "</b>"  : "<a href=\"$base_url&amp;start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
				if( $i <  $total_pages ) {
					$page_string .= ", ";
				}
			}
		}
	}
	else {
		for($i = 1; $i < $total_pages + 1; $i++) {
			$page_string .= ( $i == $on_page ) ? "<b>" . $i . "</b>" : "<a href=\"$base_url&amp;start=" . ( ( $i - 1 ) * $per_page )  . "\">" . $i . "</a>";
			if ( $i <  $total_pages ) {
				$page_string .= ", ";
			}
		}
	}
	if ( $add_prevnext_text ) {
		if ( $on_page > 1 ) {
			$page_string = " <a href=\"$base_url&amp;start=" . ( ( $on_page - 2 ) * $per_page )  . "\">Prev</a>&nbsp;&nbsp;" . $page_string;
		}

		if ( $on_page < $total_pages ) {
			$page_string .= "&nbsp;&nbsp;<a href=\"$base_url&amp;start=" . ( $on_page * $per_page )  . "\">Next</a>";
		}
	}
	$page_string = "Page: " . $page_string;
	return $page_string;
}

//redirects to error page with error code
function error($err){
	$err = addslashes($err);
	header("Location: error.php?err=$err");
	exit();
}

//made to avoid mysql injections
function quote_smart($value){
   if( is_array($value) ) {
       return array_map("quote_smart", $value);
   } else {
       if( get_magic_quotes_gpc() ) $value = stripslashes($value);
       if( $value == '' ) $value = 'NULL';
       $value = mysql_real_escape_string($value);
       return $value;
   }
}

//testing for open port
function test_port($server,$port){
 $sock = @pfsockopen("$server", $port, $ERROR_NO, $ERROR_STR, (float)1.5);
 if($sock){
	 @fclose($sock);
	 return true;
	} else return false;
}

?>
