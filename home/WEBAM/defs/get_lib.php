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

//generate item tooltip from items entry
function get_item_tooltip($item_id){
 global $lang_global, $lang_item, $lang_id_tab, $world_db;
 if($item_id){
	mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
	mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));

	$sql = "SELECT * FROM items WHERE entry = '$item_id' LIMIT 1";
	$result_1 = mysql_query($sql) or die(error(mysql_error()));
	if ($item = mysql_fetch_row($result_1)) {

//item quality
		$tooltip = "";
		switch ($item[9]) {
			case 0: //Grey Poor
			$tooltip .= "<font color=\"#b2c2b9\" class=\"large\">$item[32]</font><br/>";
			break;
			case 1: //White Common
			$tooltip .= "<font color=\"white\" class=\"large\">$item[32]</font><br/>";
			break;
			case 2: //Green Uncommon
			$tooltip .= "<font color=\"#1eff00\" class=\"large\">$item[32]</font><br/>";
			break;
			case 3: //Blue Rare
			$tooltip .= "<font color=\"#0070dd\" class=\"large\">$item[32]</font><br/>";
			break;
			case 4: //Purple Epic
			$tooltip .= "<font color=\"#a335ee\" class=\"large\">$item[32]</font><br/>";
			break;
			case 5: //Orange Legendary
			$tooltip .= "<font color=\"orange\" class=\"large\">$item[32]</font><br/>";
			break;
			case 6: //Red Artifact
			$tooltip .= "<font color=\"red\" class=\"large\">$item[32]</font><br/>";
			break;
			default:
			}
						
 $tooltip .= "<font color=\"white\">";

//item bonding		
	switch ($item[103]) {
			case 1: //Binds when Picked Up
			$tooltip .= "{$lang_item['bop']}<br/>";
			break;
			case 2: //Binds when Equipped
			$tooltip .= "{$lang_item['boe']}<br/>";
			break;
			case 3: //Binds when Used
			$tooltip .= "{$lang_item['bou']}<br/>";
			break;
			case 4: //Quest Item
			$tooltip .= "{$lang_item['quest_item']}<br/>";
			break;
			case 5: //Quest Item 2
			$tooltip .= "{$lang_item['quest_item']}<br/>";
			break;
			default:
			}

//item is unique
 if ($item[25]) $tooltip .= "{$lang_item['unique']}<br/>";

//item inventory slot
 $tooltip .= "<br/>";
	switch ($item[13]) {
			case 0:
			$tooltip .= "{$lang_item['head']} - ";
			break;
			case 1:
			$tooltip .= "{$lang_item['neck']} - ";
			break;
			case 2:
			$tooltip .= "{$lang_item['shoulder']} - ";
			break;
			case 3:
			$tooltip .= "{$lang_item['shirt']} - ";
			break;
			case 4:
			$tooltip .= "{$lang_item['chest']} - ";
			break;
			case 5:
			$tooltip .= "{$lang_item['belt']} - ";
			break;
			case 6:
			$tooltip .= "{$lang_item['legs']} - ";
			break;
			case 7:
			$tooltip .= "{$lang_item['feet']} - ";
			break;
			case 8:
			$tooltip .= "{$lang_item['wrist']} - ";
			break;
			case 9:
			$tooltip .= "{$lang_item['gloves']} - ";
			break;
			case 10:
			$tooltip .= "{$lang_item['finger']} - ";
			break;
			case 11:
			$tooltip .= "{$lang_item['trinket']} - ";
			break;
			case 12:
			$tooltip .= "{$lang_item['one_hand']} - ";
			break;
			case 13:
			$tooltip .= "{$lang_item['shield']} - ";
			break;
			case 14:
			$tooltip .= "{$lang_item['bow']} - ";
			break;
			case 15:
			$tooltip .= "{$lang_item['back']} - ";
			break;
			case 16:
			$tooltip .= "{$lang_item['two_hand']} - ";
			break;
			case 17:
			$tooltip .= "{$lang_item['bag']}";
			break;
			case 18:
			$tooltip .= "{$lang_item['tabard']} - ";
			break;
			case 19:
			$tooltip .= "{$lang_item['robe']} - ";
			break;
			case 20:
			$tooltip .= "{$lang_item['main_hand']} - ";
			break;
			case 21:
			$tooltip .= "{$lang_item['fist']} - ";
			break;
			case 22:
			$tooltip .= "{$lang_item['off_hand']} - ";
			break;
			case 23:
			$tooltip .= "{$lang_item['projectile']} - ";
			break;
			case 24:
			$tooltip .= "{$lang_item['thrown']} - ";
			break;
			case 25:
			$tooltip .= "{$lang_item['ranged']} - ";
			break;
			case 26:
			$tooltip .= "{$lang_item['relic']} - ";
			break;
			default:
			}

//item class					
	switch ($item[1]) {
			case 0: //Consumable
			$tooltip .= "{$lang_item['consumable']}<br/>";	
   			break;
			case 1: //Containers
				switch ($item[2]) {
					case 0:
					$tooltip .= "{$lang_item['bag']}<br/>";
					break;
					case 1:
					$tooltip .= "{$lang_item['soul_shards']}<br/>";
					break;
					case 2:
					$tooltip .= "{$lang_item['herbs']}<br/>";
					break;
					case 3:
					$tooltip .= "{$lang_item['enchanting']}<br/>";
					break;
					case 4:
					$tooltip .= "{$lang_item['engineering']}<br/>";
					break;
					case 5:
					$tooltip .= "{$lang_item['jewels']}<br/>";
					break;
					case 6:
					$tooltip .= "{$lang_item['mining']}<br/>";
					break;
					default:
					}
			case 2: //Weapons
				switch ($item[2]) {
					case 0:
					$tooltip .= "{$lang_item['axe_1h']}<br/>";
					break;
					case 1:
					$tooltip .= "{$lang_item['axe_2h']}<br/>";
					break;
					case 2:
					$tooltip .= "{$lang_item['bow']}<br/>";
					break;
					case 3:
					$tooltip .= "{$lang_item['rifle']}<br/>";
					break;
					case 4:
					$tooltip .= "{$lang_item['mace_1h']}<br/>";
					break;
					case 5:
					$tooltip .= "{$lang_item['mace_2h']}<br/>";
					break;
					case 6:
					$tooltip .= "{$lang_item['polearm']}<br/>";
					break;
					case 7:
					$tooltip .= "{$lang_item['sword_1h']}<br/>";
					break;
					case 8:
					$tooltip .= "{$lang_item['sword_2h']}<br/>";
					break;
					case 10:
					$tooltip .= "{$lang_item['staff']}<br/>";
					break;
					case 13:
					$tooltip .= "{$lang_item['fist_weapon']}<br/>";
					break;
					case 14:
					$tooltip .= "{$lang_item['misc_weapon']}<br/>";
					break;
					case 15:
					$tooltip .= "{$lang_item['dagger']}<br/>";
					break;
					case 16:
					$tooltip .= "{$lang_item['thrown']}<br/>";
					break;
					case 18:
					$tooltip .= "{$lang_item['crossbow']}<br/>";
					break;
					case 19:
					$tooltip .= "{$lang_item['wand']}<br/>";
					break;
					case 20:
					$tooltip .= "{$lang_item['fishing_pole']}<br/>";
					break;
					default:
					}
   			break;
			case 4: //Armor
				switch ($item[2]) {
					case 0:
					$tooltip .= "{$lang_item['misc']}<br/>";
					break;
					case 1:
					$tooltip .= "{$lang_item['cloth']}<br/>";
					break;
					case 2:
					$tooltip .= "{$lang_item['leather']}<br/>";
					break;
					case 3:
					$tooltip .= "{$lang_item['mail']}<br/>";
					break;
					case 4:
					$tooltip .= "{$lang_item['plate']}<br/>";
					break;
					case 6:
					$tooltip .= "{$lang_item['shield']}<br/>";
					break;
					default:
					}
   			break;
			case 6: //Projectile
				switch ($item[2]) {
					case 2:
					$tooltip .= "{$lang_item['arrows']}<br/>";
					break;
					case 3:
					$tooltip .= "{$lang_item['bullets']}<br/>";
					break;
					default:
					}
   			break;
			case 7: //Trade Goods
				switch ($item[2]) {
					case 0:
					$tooltip .= "{$lang_item['trade_goods']}<br/>";
					break;
					case 1:
					$tooltip .= "{$lang_item['parts']}<br/>";
					break;
					case 2:
					$tooltip .= "{$lang_item['explosives']}<br/>";
					break;
					case 3:
					$tooltip .= "{$lang_item['devices']}<br/>";
					break;
					default:
					}
   			break;
			case 9: //Recipe
				switch ($item[2]) {
					case 0:
					$tooltip .= "{$lang_item['book']}<br/>";
					break;
					case 1:
					$tooltip .= "{$lang_item['LW_pattern']}<br/>";
					break;
					case 2:
					$tooltip .= "{$lang_item['tailoring_pattern']}<br/>";
					break;
					case 3:
					$tooltip .= "{$lang_item['ENG_Schematic']}<br/>";
					break;
					case 4:
					$tooltip .= "{$lang_item['BS_plans']}<br/>";
					break;
					case 5:
					$tooltip .= "{$lang_item['cooking_recipe']}<br/>";
					break;
					case 6:
					$tooltip .= "{$lang_item['alchemy_recipe']}<br/>";
					break;
					case 7:
					$tooltip .= "{$lang_item['FA_manual']}<br/>";
					break;
					case 8:
					$tooltip .= "{$lang_item['ench_formula']}<br/>";
					break;
					case 9:
					$tooltip .= "{$lang_item['JC_formula']}<br/>";
					break;
					default:
					}
   			break;
			case 11: //Quiver
				switch ($item[2]) {
					case 2:
					$tooltip .= " {$lang_item['quiver']}<br/>";
					break;
					case 3:
					$tooltip .= " {$lang_item['ammo_pouch']}<br/>";
					break;
					default:
					}
   			break;
			case 12: //Quest
				if ($item[53] != 4) $tooltip .= "{$lang_item['quest_item']}<br/>";
   			break;
			
			case 13: //key
				switch ($item[2]) {
					case 0:
					$tooltip .= "{$lang_item['key']}<br/>";
					break;
					case 1:
					$tooltip .= "{$lang_item['lockpick']}<br/>";
					break;
					default:
					}
   			break;
			default:
		}
		
	if ($item[20]) $tooltip .= "$item[20] {$lang_item['armor']}<br/>";

	for($f=37;$f<=51;$f+=3){
		$dmg_type = $item[$f+2];
		$min_dmg_value = $item[$f];
		$max_dmg_value = $item[$f+1];
	
		if ($min_dmg_value && $max_dmg_value){
			switch ($dmg_type) {
			case 0: // Physical
					$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['damage']}<br/>
					(".round(((($min_dmg_value+$max_dmg_value)/2)/($item[52]/1000)),2)." DPS)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					{$lang_item['speed']} : ".(($item[52])/1000)."<br/>";
   			break;
			case 1: // Holy
				$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['holy_dmg']}<br/>";
   			break;
			case 2: // Fire
				$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['fire_dmg']}<br/>";
   			break;
			case 3: // Nature
				$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['nature_dmg']}<br/>";
   			break;
			case 4: // Frost
				$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['frost_dmg']}<br/>";
   			break;
			case 5: // Shadow
				$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['shadow_dmg']}<br/>";
   			break;
			case 6: // Arcane
				$tooltip .= "$min_dmg_value - $max_dmg_value {$lang_item['arcane_dmg']}<br/>";
   			break;
			default:
			}
		}
	}

//basic status
	for($s=0;$s<=18;$s+=2){
		$stat_type = $item[$s];
		$stat_value = $item[$s+1];
		if ($stat_type && $stat_value){
			switch ($stat_type) {
			case 1:
			$tooltip .= "+$stat_value {$lang_item['health']}<br/>";
   			break;
			case 2:
			$tooltip .= "+$stat_value {$lang_item['mana']}<br/>";
   			break;
			case 3:
			$tooltip .= "+$stat_value {$lang_item['agility']}<br/>";
   			break;
			case 4:
			$tooltip .= "+$stat_value {$lang_item['strength']}<br/>";
   			break;
			case 5:
			$tooltip .= "+$stat_value {$lang_item['intellect']}<br/>";
   			break;
			case 6:
			$tooltip .= "+$stat_value {$lang_item['spirit']}<br/>";
   			break;
			case 7:
			$tooltip .= "+$stat_value {$lang_item['stamina']}<br/>";
   			break;
			default:
			 $flag_rating = 1;
			}
		}
	}

	if ($item[21]) $tooltip .= "$item[21] {$lang_item['res_holy']}<br/>";
	if ($item[25]) $tooltip .= "$item[25] {$lang_item['res_arcane']}<br/>";
	if ($item[22]) $tooltip .= "$item[22] {$lang_item['res_fire']}<br/>";
	if ($item[23]) $tooltip .= "$item[23] {$lang_item['res_nature']}<br/>";
	if ($item[24]) $tooltip .= "$item[24] {$lang_item['res_frost']}<br/>";
	if ($item[26]) $tooltip .= "$item[26] {$lang_item['res_shadow']}<br/>";
	
	//sockets
	for($p=72;$p<=74;$p++){
		if($item[$p]){
			switch ($item[$p]) {
				case 1:
				$tooltip .= "<font color=\"gray\">{$lang_item['socket_meta']}</font><br/>";
				break;
				case 2:
				$tooltip .= "<font color=\"red\">{$lang_item['socket_red']}</font><br/>";
				break;
				case 4:
				$tooltip .= "<font color=\"yellow\">{$lang_item['socket_yellow']}</font><br/>";
				break;
				case 8:
				$tooltip .= "<font color=\"blue\">{$lang_item['socket_blue']}</font><br/>";
				break;
			default:
			}
		}
	}

//level requierment
	if($item[36]) $tooltip .= "{$lang_item['lvl_req']} $item[36]<br/>";
//allowable classes
	if (($item[71])&&($item[71] != -1)&&($item[71] != 1503)){
		$tooltip .= "{$lang_item['class']}:";
		if ($item[71] & 1) $tooltip .= " {$lang_id_tab['warrior']} ";
		if ($item[71] & 2) $tooltip .= " {$lang_id_tab['paladin']} ";
		if ($item[71] & 4) $tooltip .= " {$lang_id_tab['hunter']} ";
		if ($item[71] & 8) $tooltip .= " {$lang_id_tab['rogue']} ";
		if ($item[71] & 16) $tooltip .= " {$lang_id_tab['priest']} ";
		if ($item[71] & 64) $tooltip .= " {$lang_id_tab['shaman']} ";
		if ($item[71] & 128) $tooltip .= " {$lang_id_tab['mage']} ";
		if ($item[71] & 256) $tooltip .= " {$lang_id_tab['warlock']} ";
		if ($item[71] & 1024) $tooltip .= " {$lang_id_tab['druid']} ";
		$tooltip .= "<br/>";
		}

	//number of bag slots
	if ($item[66]) $tooltip .= " $item[66] {$lang_item['slots']}<br/>";
	
	$tooltip .= "</font><br/><font color=\"#1eff00\">";

//Ratings additions.
	if (isset($flag_rating)){
		for($s=0;$s<=18;$s+=2){
		$stat_type = $item[$s];
		$stat_value = $item[$s+1];
		if ($stat_type && $stat_value){
			switch ($stat_type) {
			case 12:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['DEFENCE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 13:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['DODGE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 14:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['PARRY_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 15:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['SHIELD_BLOCK_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 16:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['MELEE_HIT_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 17:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['RANGED_HIT_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 18:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['SPELL_HIT_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 19:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['MELEE_CS_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 20:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['RANGED_CS_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 21:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['SPELL_CS_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 22:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['MELEE_HA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 23:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['RANGED_HA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 24:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['SPELL_HA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 25:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['MELEE_CA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 26:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['RANGED_CA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 27:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['SPELL_CA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 28:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['MELEE_HASTE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 29:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['RANGED_HASTE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 30:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['SPELL_HASTE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 31:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['HIT_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 32:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['CS_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 33:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['HA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 34:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['CA_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 35:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['RESILIENCE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			case 36:
			$tooltip .= "{$lang_item['spell_equip']}: {$lang_item['improves']} {$lang_item['HASTE_RATING']} {$lang_item['rating_by']} $stat_value.<br/>";
   			break;
			default:
			}
		}
	}	
 }
	
//add equip spellid to status
	for($s1=27;$s1<=31;$s1++){
		if ($item[$s1]) {
				switch ($item[$s1+34]) {
					case 0:
					$tooltip .= "{$lang_item['spell_use']}: ";
					break;
					case 1:
					$tooltip .= "{$lang_item['spell_equip']}: ";
					break;
					case 2:
					$tooltip .= "{$lang_item['spell_coh']}: ";
					break;
					default:
				}
				$tooltip .= " $item[$s1]<br/>";
			if ($item[$s1]) {
				if ($item[$s1+40] != 0) 
					if  ($item[$s1+40] < 0) $tooltip.= ($item[$s1+40]*-1)." {$lang_item['charges']}.<br/>";
						else $tooltip.= $item[$s1+40]." {$lang_item['charges']}.<br/>";
			}		
		
		}			
	}

	$tooltip .= "</font>";
		
	if ($item[55]) $tooltip .= "<br/><font color=\"orange\">{$lang_item['item_set']} : $item[55]</font>";
	if ($item[54]) $tooltip .= "<br/><font color=\"orange\">\"$item[54]\"</font>";

	} else $tooltip = "Item ID: $item_id Not Found" ;

	$mysql_1->close();
	return $tooltip;
 } else return(NULL);
}

//get item icon - if icon not exists in INV folder D/L it from web.
function get_icon($itemid) {
 if (file_exists("img/INV/$itemid.png")) return ("img/INV/$itemid.png");
 //only customitems have this ids - and there is no icones on alla for them.
 if ($itemid>35000) return "img/INV/INV_blank_32.gif";
 
	$xmlfilepath="http://wow.allakhazam.com/dev/wow/item-xml.pl?witem=";
	$proxy = '';
	$port = 8080;
	$proxy_user = '';
	$proxy_pass = '';
	
	if (empty($proxy)) {
		$proxy = "http://wow.allakhazam.com/";
		$xmlfilepath = "dev/wow/item-xml.pl?witem=";
		$port = 80;
		}

//get the icon path
	$fp = @fsockopen($proxy, $port, $errno, $errstr, 5);
	if (!$fp) return "img/INV/INV_blank_32.gif";
	$out = "GET $xmlfilepath$itemid HTTP/1.0\r\nHost: $proxy";
	if (!empty($proxy_user)) $out .= "\r\nProxy-Authorization: Basic ". base64_encode ("$proxy_user:$proxy_pass");
	$out .="\r\n\r\n";
			
    $temp ="";
	fwrite($fp, $out);
	while (!feof($fp)) $temp .= fgets($fp, 128);
	fclose($fp);
   
    preg_match('~(/images/icons/\s*(.*?)\s*png)~i', $temp, $m);
	
	if (!$m) return "img/INV/INV_blank_32.gif";
	
	//get the icon itself
	$fp = @fsockopen($proxy, $port, $errno, $errstr, 5);
	if (!$fp) return "img/INV/INV_blank_32.gif";
	if ($port == 80) $file = $m[0];
		else $file = 'http://wow.allakhazam.com'.$m[0];
	$out = "GET $file HTTP/1.0\r\nHost: $proxy";
	if (!empty($proxy_user)) $out .= "\r\nProxy-Authorization: Basic ". base64_encode ("$proxy_user:$proxy_pass");
	$out .="\r\n\r\n";
	
	$test ="";
	fwrite($fp, $out);
	while (!feof($fp)) $test .= fgets($fp, 128);
	fclose($fp);
	
	$image = stristr($test, '‰PNG');
	if ($image) { 
		//write the icon to  hdd
		$img_file = fopen("img/INV/$itemid.png", 'wb');
		fwrite($img_file,$image);
		fclose($img_file);

		return "img/INV/$itemid.png";
		} else return "img/INV/INV_blank_32.gif";
}
?>