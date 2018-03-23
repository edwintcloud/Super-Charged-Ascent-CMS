<?php

//includes
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
?>

<style type="text/css">

		BODY	  {  BACKGROUND-COLOR: #000000; BACKGROUND-IMAGE: url(../../themes/ElonBC/images/Background.jpg); BACKGROUND-REPEAT: repeat-x; }
.style1 {
	color: #FFFFFF;
	font-style: italic;
}
.style2 {color: #FFFFFF}
</style>

<html>
<head><title><?php echo "$conf[browsertitle]"; $d = P;?></title> </head>
<body>
<center>
 

<div class="container">


<div class="container_body">
<div class="container_top">
<h1><?php echo "$lang[header]"; $lampstn="- ";?></h1>
</div>
<div class="container_left">
</div>
<div class="container_right">
</div>

	<table cellspacing =" 2" cellpadding =" 3" border =" 0" width =" 100%" height="">
			<td class="blogbody">
				<center>
				<h2 align="center" class="main-title"><span class="style2">World Map</span> <span class="style1"></span></h2>
<!--<object width="740" height="600">
				<param name="worldmap" value="00_world.swf">
				<embed src="00_world.swf" width="800" height="600"></embed>
				</object>--->
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="800" height="533">
                                        <param name="movie" value="00_world.swf">
                                        <param name="wmode" value="transparent" >
                                        <param name=quality value=high>
                                        <embed src="00_world.swf" wmode="transparent" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="800" height="533"></embed></center>
                                </object>
			</td>
		<tr>
	</table>
	
<?php
require_once "footer.php";
?>
