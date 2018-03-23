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
 
require_once("defs/config.php");
print $output;
$output = "";
?>  

<i class="bl"></i><i class="br"></i>
</div>
<br />
<div id="body_buttom">
<table style="width:800px; height:100px; position:absolute;">
	<tr>
	<td>
		<input type=button value="Back" onClick="history.go(-1)" onmouseover="this.className='mouseover'" onmouseout="this.className=''"  /><input type=button value="Foward" onClick="history.go(1)" onmouseover="this.className='mouseover'" onmouseout="this.className=''"  /><br/><br/>
		Got a problem? Post it in the <a target="_blank" href="../forum">Forums</a><br />
		<p>
		<a href="http://www.php.net/" target="_blank"><img src="templates/logos/logo-php.png" alt="PHP Powered"></a>
		<a href="http://www.mysql.com/" target="_blank"><img src="templates/logos/logo-mysql.png" alt="MySql Powered"></a>
		<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank"><img src="templates/logos/logo-css.png" alt="Valid CSS"></a>
		</p>
		WeBAM <?php echo $version; ?> (by gmaze): Rescripted by Red<br />
		<?php
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		printf('Execute time: %.5f', $time);
		$output .= "<div id=\"version\">$version</div>";
		?>
	</td>
	</tr>
	</table>
  </div>
 </div>
</center>
</body></html>