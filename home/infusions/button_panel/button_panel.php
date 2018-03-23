<?
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }
if (file_exists(INFUSIONS."button_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."button_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."button_panel/locale/English.php";
}

openside($locale['BLAN_001']);

if (dbrows(dbquery("SELECT * FROM ".$db_prefix."buttons")) > 0) {

	echo "<script>
	nereidFadeObjects = new Object();
	nereidFadeTimers = new Object();
	function nereidFade(object, destOp, rate, delta){
		if (!document.all)
	return
		if (object != '[object]'){  //do this so I can take a string too
	setTimeout('nereidFade('+object+','+destOp+','+rate+','+delta+')',0);
	return;
}
	clearTimeout(nereidFadeTimers[object.sourceIndex]);
	diff = destOp-object.filters.alpha.opacity;
	direction = 1;
		if (object.filters.alpha.opacity > destOp){
	direction = -1;
}
	delta=Math.min(direction*diff,delta);
	object.filters.alpha.opacity+=direction*delta;
		if (object.filters.alpha.opacity != destOp){
	nereidFadeObjects[object.sourceIndex]=object;
	nereidFadeTimers[object.sourceIndex]=setTimeout('nereidFade(nereidFadeObjects['+object.sourceIndex+'],'+destOp+','+rate+','+delta+')',rate);
	}
}
	</script>";
$szer=$theme_width_r-10;
	echo "<center><marquee behavior='scroll' align='center' valign='bottom' direction='up' width='120' height='120' scrollamount='1' scrolldelay='1' onmouseover='this.stop()' onmouseout='this.start()'>"; 
$result = dbquery("SELECT * FROM ".$db_prefix."buttons");
while ($data=dbarray($result)) {
	echo "
		<div align='center'>
		<table cellpadding='4' cellspacing='0' width='100%'>
		<tr><td><p align='center'><a href='".INFUSIONS."button_panel/button.php?button_id=".$data['button_id']."' target='_blank'>
		<img src='".$data['button_pic']."' border='0' style='filter:alpha(opacity=30)' onMouseOver='nereidFade(this,100,10,30)' onMouseOut='nereidFade(this,30,10,5)' title='".$data['button_name']."' alt='".$data['button_name']."'></a>
		</td></tr>"; 
}
echo "</table></div></p></center></marquee>";
} else {
	echo "<div style='text-align:center'>".$locale['BLAN_102']."</div>";
}
closeside();
?>
