<?php
	/*
	 * 2007 (c) -={MikeP}=-
	 * Armory for Ascent WOW emu.
	*/
	ini_set("memory_limit","12M");

	require_once "maincore.php";
	require_once "subheader.php";
	require_once "side_left.php";
	require_once('include/dbLayer.php');
	require_once('include/readableData.php');
	require_once('include/CPlayer.php');
	
	if(empty($_GET['n'])){
		echo '<script type="text/javascript">window.close();</script>';
		exit();
	}
	$pname = $_GET['n'];
	
	try{
		// 1) load player information
		$player = new CPlayer($pname,true);
		
		// other actions?
		
	}catch(Exception $e){
		echo '<html><body>'.$e->getMessage().'<script type="text/javascript">setTimeout("window.close()",5000);</script></body></html>';
		exit;
	}
	
	
	function showItemImage($slot,$img){
		global $player;
		$ipics = hrData::getItemImages();
		if(isset($player->slots[$slot])){
			// we have something in the slot
			if(isset($ipics[$player->slots[$slot]->displayid])){
				// we know what icon to show
				$img = 'img/armory/itemicons/'.$ipics[$player->slots[$slot]->displayid];
			}else{
				// the icon is unknown
				$img = 'img/armory/unknown.gif';
			}
		}
		// add image
		echo '<a href="http://www.wowhead.com/?item='.$player->slots[$slot]->entry.'" target="blank"><img onmouseover="javascript: showDetails(\'slot'.$slot.'\');" onmouseout="javascript: hideDetails(\'slot'.$slot.'\')" width="40" height="40" border="0" src="'.$img.'" /></a>';
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Armory: <?php echo $pname; ?></title>
<link href="include/wow.css" rel="stylesheet" type="text/css" />
<link href="include/armory.css" rel="stylesheet" type="text/css" />
</head>

<script type="text/javascript">

var currentDiv = null;
var pos;


function getCoords(ev){
	var addX = 20;
	var addY = 0;
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX+addX, y:ev.pageY+addY};
	}
	return {x:ev.clientX+document.body.scrollLeft-document.body.clientLeft+addX, y:ev.clientY+document.body.scrollTop-document.body.clientTop+addY};
}

function moveToMouseLoc(ev){
	ev = ev || window.event;
	pos = getCoords(ev);

	if(currentDiv){
		currentDiv.style.left = pos.x + "px";
		currentDiv.style.top = pos.y + "px";
	}
}


function showDetails(name){
	var div = document.getElementById(name);
	if(div){
		// show required tooltip
		div.style.left = pos.x + "px";
		div.style.top = pos.y + "px";
		currentDiv = div;
		div.style.display = 'block';
	}
}

function hideDetails(name){
	var div = document.getElementById(name);
	if(div){
		// hide this tooltip
		div.style.display = 'none';
		currentDiv = null;
	}
}

// function to switch stats display
function swStats(sel, target){
	var td = document.getElementById(target);
	var dv = null;
	switch(sel.value){
		case '1':
			dv = document.getElementById('stat_base');
			break;
		case '2':
			dv = document.getElementById('stat_melee');
			break;
		case '3':
			dv = document.getElementById('stat_ranged');
			break;
		case '4':
			dv = document.getElementById('stat_spell');
			break;
		case '5':
			dv = document.getElementById('stat_defense');
			break;
	}
	if( dv ){
		td.innerHTML = dv.innerHTML;
	}
}

</script>

<body>
<form action="#">
<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" class="onlinePlrs">
	<tr>
		<th height="20" colspan="10"><center><?php echo $player->name; ?></center></th>
	</tr>
	<tr>
		<td height="40" colspan="10" align="center" valign="top"><center>
		<?php
			$desc = 'Level '.$player->level.' ';
			$desc .= hrData::$arrRace[$player->race].' ';
			$desc .= hrData::$arrClass[$player->class].' ';
			switch($player->gender){
				case 0:
					$desc .= '(male)';
					break;
				case 1:
					$desc .= '(female)';
					break;
				default:
					$desc .= '(gender unknown)';
					break;
			}
			echo $desc;
		?></center></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(0,'img/armory/dfltHead.gif'); ?></td>
		<td colspan="8" rowspan="4" align="center" valign="middle"><center><?php	echo '<img src="img/armory/crest'.$player->race.'.gif" width="160" height="160" />'; ?></center></td>
		<td width="40" height="40"><?php	showItemImage(9, 'img/armory/dfltHands.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(1, 'img/armory/dfltNeck.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(5, 'img/armory/dfltWaist.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(2, 'img/armory/dfltShoulder.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(6, 'img/armory/dfltLegs.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(14, 'img/armory/dfltChest.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(7, 'img/armory/dfltFeet.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(4, 'img/armory/dfltChest.gif'); ?></td>
		<td colspan="4" rowspan="4" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><center><select name="ss1" id="ss1" style="width:130px" onchange="javascript: swStats(this,'dtd1');"><option value="1">Base Stats</option><option value="2">Melee</option><option value="3">Ranged</option><option value="4">Spell</option><option value="5">Defenses</option></select></center></td>
				</tr>
				<tr>
					<td height="20" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td id="dtd1">Choose one of the categories above</td>
				</tr>
			</table>
		</td>
		<td colspan="4" rowspan="4" valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><center><select name="ss2" id="ss2" style="width:130px" onchange="javascript: swStats(this,'dtd2');"><option value="1">Base Stats</option><option value="2">Melee</option><option value="3">Ranged</option><option value="4">Spell</option><option value="5">Defenses</option></select></center></td>
				</tr>
				<tr>
					<td height="20" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td id="dtd2">Choose one of the items above</td>
				</tr>
			</table>
		</td>
		<td width="40" height="40"><?php	showItemImage(10, 'img/armory/dfltFinger.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(3, 'img/armory/dfltShirt.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(11, 'img/armory/dfltFinger.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(18, 'img/armory/dfltTabard.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(12, 'img/armory/dfltEar.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"><?php	showItemImage(8, 'img/armory/dfltWrists.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(13, 'img/armory/dfltEar.gif'); ?></td>
	</tr>
	<tr>
		<td width="40" height="40"></td>
		<td width="40" height="40"></td>
		<td width="40" height="40"></td>
		<td width="40" height="40"><?php	showItemImage(15, 'img/armory/dfltMainHand.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(16, 'img/armory/dfltOffHand.gif'); ?></td>
		<td width="40" height="40"><?php	showItemImage(17, 'img/armory/dfltRanged.gif'); ?></td>
		<td width="40" height="40"><img src="img/armory/dfltAmmo.gif" width="40" height="40" /></td>
		<td width="40" height="40"></td>
		<td width="40" height="40"></td>
		<td width="40" height="40"></td>
	</tr>
</table>
</form>

<?php
	$pst = $player->stats;
	$bpst = $player->bstats;
	
	//
	// print base stats
	//
	echo '<div id="stat_base" style="display:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
	echo '<tr><td width="10">&nbsp;</td><td>Strength</td><td>'.($bpst->strength != 0 ?  '<div align="right" class="q2">'.($pst->strength + $bpst->strength).'</div>' : '<div align="right">'.$pst->strength.'</div>' ).'</td><td width="10">&nbsp;</td></tr>';
	echo '<tr><td width="10">&nbsp;</td><td>Agility</td><td>'.($bpst->agility != 0 ?  '<div align="right" class="q2">'.($pst->agility + $bpst->agility).'</div>' : '<div align="right">'.$pst->agility.'</div>' ).'</td><td width="10">&nbsp;</td></tr>';
	echo '<tr><td width="10">&nbsp;</td><td>Stamina</td><td>'.($bpst->stamina != 0 ?  '<div align="right" class="q2">'.($pst->stamina + $bpst->stamina).'</div>' : '<div align="right">'.$pst->stamina.'</div>' ).'</td><td width="10">&nbsp;</td></tr>';
	echo '<tr><td width="10">&nbsp;</td><td>Intellect</td><td>'.($bpst->intellect != 0 ?  '<div align="right" class="q2">'.($pst->intellect + $bpst->intellect).'</div>' : '<div align="right">'.$pst->intellect.'</div>' ).'</td><td width="10">&nbsp;</td></tr>';
	echo '<tr><td width="10">&nbsp;</td><td>Spirit</td><td>'.($bpst->spirit != 0 ?  '<div align="right" class="q2">'.($pst->spirit + $bpst->spirit).'</div>' : '<div align="right">'.$pst->spirit.'</div>' ).'</td><td width="10">&nbsp;</td></tr>';
	$armor = 2 * ($pst->agility + $bpst->agility) + $pst->armor + $bpst->armor;
	echo '<tr><td width="10">&nbsp;</td><td>Armor</td><td><div align="right">'.$armor.'</div></td><td width="10">&nbsp;</td></tr>';
	echo '</table></div>';
	
	//
	// melee
	//
	echo '<div id="stat_melee" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
	$mskill = 0;
	if(!isset($player->slots[15])){
		// unarmed
		$mskill = (isset($player->skills[SKILL_UNARMED]) ? $player->skills[SKILL_UNARMED][0] : 0);
	}else{
		switch($player->slots[15]->subclass){
			case 0: $mskill = (isset($player->skills[SKILL_AXES]) ? $player->skills[SKILL_AXES][0] : 0); break;
			case 1: $mskill = (isset($player->skills[SKILL_2H_AXES]) ? $player->skills[SKILL_2H_AXES][0] : 0); break;
			case 4: $mskill = (isset($player->skills[SKILL_MACES]) ? $player->skills[SKILL_MACES][0] : 0); break;
			case 5: $mskill = (isset($player->skills[SKILL_2H_MACES]) ? $player->skills[SKILL_2H_MACES][0] : 0); break;
			case 6: $mskill = (isset($player->skills[SKILL_POLEARMS]) ? $player->skills[SKILL_POLEARMS][0] : 0); break;
			case 7: $mskill = (isset($player->skills[SKILL_SWORDS]) ? $player->skills[SKILL_SWORDS][0] : 0); break;
			case 8: $mskill = (isset($player->skills[SKILL_2H_SWORDS]) ? $player->skills[SKILL_2H_SWORDS][0] : 0); break;
			case 10: $mskill = (isset($player->skills[SKILL_STAVES]) ? $player->skills[SKILL_STAVES][0] : 0); break;
			case 13: $mskill = (isset($player->skills[SKILL_ARMS]) ? $player->skills[SKILL_ARMS][0] : 0); break;
			case 15: $mskill = (isset($player->skills[SKILL_DAGGERS]) ? $player->skills[SKILL_DAGGERS][0] : 0); break;
			case 17: $mskill = (isset($player->skills[SKILL_SPEARS]) ? $player->skills[SKILL_SPEARS][0] : 0); break;
		}
	}
	echo '<tr><td width="10">&nbsp;</td><td>Weapon Skill</td><td><div align="right">'.$mskill.'</div></td><td width="10">&nbsp;</td></tr>';
	// attack power
	$AP = statSystem::getAP($player->class,$player->level, ($pst->strength + $bpst->strength), ($pst->agility + $bpst->agility));
	$bdps = $AP/14;
	$d_min = 0; $d_max = 0;
	if(isset($player->slots[15])){
		$d_min = intval($player->slots[15]->delay*$bdps/1000 + $player->slots[15]->dmg_min);
		$d_max = intval($player->slots[15]->delay*$bdps/1000 + $player->slots[15]->dmg_max);
	}
	echo '<tr><td width="10">&nbsp;</td><td>Damage</td><td><div align="right">'.$d_min.' - '.$d_max.'</div></td><td width="10">&nbsp;</td></tr>';
	$mspeed = (isset($player->slots[15]) ? $player->slots[15]->delay/1000 : 1.00);
	echo '<tr><td width="10">&nbsp;</td><td>Speed</td><td><div align="right">'.number_format($mspeed,2).'</div></td><td width="10">&nbsp;</td></tr>';
	echo '<tr><td width="10">&nbsp;</td><td>Power</td><td><div align="right">'.$AP.'</div></td><td width="10">&nbsp;</td></tr>';
	
	echo '<tr><td width="10">&nbsp;</td><td>Hit Rating</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>';
	
	echo '<tr><td width="10">&nbsp;</td><td>Crit Chance</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>';
	echo '</table></div>';
	
	//
	// ranged
	//
	
	//
	// spell
	//
	
	//
	// defense
	//
	echo '<div id="stat_defense" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
	echo '<tr><td width="10">&nbsp;</td><td>Armor</td><td><div align="right">'.$armor.'</div></td><td width="10">&nbsp;</td></tr>';
	$defense = ( isset($player->skills[SKILL_DEFENSE]) ? $player->skills[SKILL_DEFENSE][0] : 0 );
	echo '<tr><td width="10">&nbsp;</td><td>Defense</td><td><div align="right">'.$defense.'</div></td><td width="10">&nbsp;</td></tr>';
	// Dodge% = Base dodge + (AGI / AGI:Dodge ratio) + (Dodge Rating / DR:Dodge ratio) + Talent and Race contributions + ((Defense skill - Attacker's attack skill) * 0.04)
	// (A mob's weapon skill is assumed to be its level * 5)
	$dodge = statSystem::baseDodge($player->class,$player->level) + ($pst->agility + $bpst->agility)/statSystem::ADratio($player->class,$player->level) + 0.0 + 0.04*($defense - 5*$player->level);
	echo '<tr><td width="10">&nbsp;</td><td>Dodge</td><td><div align="right">'.number_format($dodge,2).'%</div></td><td width="10">&nbsp;</td></tr>';
	// Parry% = 5% base chance + contribution from Parry Rating + contribution from talents + ((Defense skill - attacker's weapon skill) * 0.04) 
	// (A mob's weapon skill is assumed to be its level * 5)
	$parry = 5.0 + 0.0 + 0.0 + 0.04*($defense - 5*$player->level);
	echo '<tr><td width="10">&nbsp;</td><td>Parry</td><td><div align="right">'.number_format($parry,2).'%</div></td><td width="10">&nbsp;</td></tr>';
	// Block% = 5% base chance + contribution from Block Rating + contribution from talents + ((Defense skill - attacker's weapon skill) * 0.04)
	// is there a 5% base for block in Ascent?
	$block = 5.0 + 0.0 + 0.0 + 0.04*($defense - 5*$player->level);
	echo '<tr><td width="10">&nbsp;</td><td>Block</td><td><div align="right">'.number_format($block,2).'%</div></td><td width="10">&nbsp;</td></tr>';
	$resil = 0.0;
	echo '<tr><td width="10">&nbsp;</td><td>Resilience</td><td><div align="right">'.$resil.'</div></td><td width="10">&nbsp;</td></tr>';
	echo '</table></div>';
	
?>

<div id="stat_ranged" style="display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td width="10">&nbsp;</td><td>Weapon Skill</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Damage</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Speed</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Power</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Hit Rating</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Crit Chance</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
</table>
</div>

<div id="stat_spell" style="display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td width="10">&nbsp;</td><td>Bonus Damage</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Bonus Healing</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Hit Rating</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Crit Chance</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Penetration</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
	<tr><td width="10">&nbsp;</td><td>Mana Regen</td><td><div align="right">N/A</div></td><td width="10">&nbsp;</td></tr>
</table>
</div>

<?php
	// descriptins of equipped items go here
	$descr = array('Helmet','Neck','Shoulder','Shirt','Chest','Waist','Legs','Boots','Wrists','Gloves','Finger','Finger','Trinket','Trinket','Back','Main Hand','Off-Hand','Ranged/Wand/Idol/Libram','Tabard');
	for($i = 0; $i < 19; $i++){
		echo '<div id="slot'.$i.'" style="position:absolute; display:none;">';
		if(isset($player->slots[$i])){
			echo $player->slots[$i]->dumpInfo();
		}else{
			echo '<table class="ItemInfo"><tr><td>'.$descr[$i].'</td></tr></table>';
		}
		echo '</div>';
	}
?><?php
require_once "side_right.php";
require_once "footer.php";
?>

<script type="text/javascript">
document.onmousemove = moveToMouseLoc;
// now show base stats in both selection boxes
swStats(document.getElementById('ss1'),'dtd1');
swStats(document.getElementById('ss2'),'dtd2');
</script>

</body>
</html>
