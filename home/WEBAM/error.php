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

if(isset($_GET['err'])) $err = $_GET['err'];
	else $err = "How did you get here?...";

$output .= "<center><br/><table width=\"300\" class=\"flat\">
          <tr>
            <td align=\"center\"><h1><font class=\"error\">ERR!</font></h1>
            <br/>$err<br/><br/>
            </td>
          </tr>
        </table><br/>";
$output .= "<table class=\"hidden\">
          <tr>
            <td>";
		makebutton("Back", "javascript:window.history.back()", "120");
$output .= "</td>
            <td>";
		makebutton("Home", "index.php", "120");
$output .= "</td>
          </tr>
        </table><br/></center>";

require_once("footer.php");
?>