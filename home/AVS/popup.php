<?php
if(!isset($_COOKIE['vote_time']))
{
	?>
		<!-- Start Vote Pop-up -->
		
		<div id="votepopup" style="padding:2px; border:#000000 solid 1px; height:150px; width:200px; top:100px; left:100px; position:absolute; background:#FFFFFF;">
			This is your customizeable vote reminder box,
			letting you know that you are able to vote again.<br><br>
			<a href="index.php?act=vote">Vote!</a>
		</div>
		
		<!-- End Vote Pop-up-->
	<?php
}
?>