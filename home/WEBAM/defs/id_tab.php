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

require_once("arrays.php"); 

//get map name by its id -- updated to 2.2.x
function get_map_name($map_id){
  switch ($map_id) {
    case -1:
      return("");
        break;
    case 0:
      return("Eastern Kingdoms");
        break;
    case 1:
      return("Kalimdor");
        break;
    case 30:
      return("Alterac Valley");
        break;
    case 33:
      return("Shadowfang Keep");
        break;
    case 34:
      return("Stormwind Stockade");
        break;
    case 35:
      return("Stormwind Vault");
        break;
    case 36:
      return("Deadmines");
        break;
    case 43:
      return("Wailing Caverns");
        break;
    case 47:
      return("Razorfen Kraul");
        break;
    case 48:
      return("Blackfathom Deeps");
        break;
    case 70:
      return("Uldaman");
        break;
    case 90:
      return("Gnomeregan");
        break;
    case 109:
      return("Sunken Temple");
        break;
    case 129:
      return("Razorfen Downs");
        break;
    case 169:
      return("Emerald Dream");
        break;
    case 189:
      return("Scarlet Monastery");
        break;
    case 209:
      return("Zul'Farrak");
        break;
    case 229:
      return("Blackrock Spire");
        break;
    case 230:
      return("Blackrock Depths");
        break;
    case 249:
      return("Onyxia's Lair");
        break;
    case 269:
      return("Black Morass");
        break;
    case 289:
      return("Scholomance");
        break;
    case 309:
      return("Zul'Gurub");
        break;
    case 329:
      return("Stratholme");
        break;
    case 349:
      return("Mauradon");
        break;
    case 369:
      return("Deeprun Tram");
        break;
    case 389:
      return("Ragefire Chasm");
        break;
    case 409:
      return("Molten Core");
        break;
    case 429:
      return("Dire Maul");
        break;
    case 449:
      return("Alliance Military Barracks");
        break;
    case 450:
      return("Horde Military Barracks");
        break;
    case 469:
      return("Blackwing Lair");
        break;
    case 509:
      return("Ahn'Qiraj Ruins");
        break;
    case 530:
      return("Outlands");
        break;
    case 531:
      return("Ahn'Qiraj Temple");
        break;
    case 532:
      return("Karazhan");
        break;
    case 533:
      return("Naxxramas");
        break;
    case 534:
      return("Hyjal Summit");
        break;
    case 540:
      return("Shattered Halls");
        break;
    case 542:
      return("Blood Furnace");
        break;
    case 543:
      return("Hellfire Ramparts");
        break;
    case 544:
      return("Magtheridon's Lair");
        break;
    case 545:
      return("Steamvault");
        break;
    case 546:
      return("Underbog");
        break;
    case 547:
      return("Slave Pens");
        break;
    case 548:
      return("Serpentshrine Cavern");
        break;
    case 550:
      return("Tempest Keep");
        break;
    case 552:
      return("Arcatraz");
        break;
    case 553:
      return("Botanica");
        break;
    case 554:
      return("Mechanar");
        break;
    case 555:
      return("Shadow Labyrinth");
        break;
    case 556:
      return("Sethekk Halls");
        break;
    case 557:
      return("Mana-Tombs");
        break;
    case 558:
      return("Auchenai Crypts");
        break;
    case 560:
      return("Old Hillsbrad Foothills");
        break;
    case 564:
      return("Black Temple");
        break;
    case 565:
      return("Gruul's Lair");
        break;
    case 568:
      return("Zul'Aman");
        break;
    case 572:
      return("Ruins of Lordaeron");
        break;
    default:
      return("Unknown Map");
  }
}

//get zone name by mapid and players x,y 
function get_zone_name($map_id,$player_x,$player_y){
  global $zone_0,$zone_1,$zone_530;
    switch ($map_id) {
      case 0:
        for ($i=0; $i<=count($zone_0); $i++)
	       if (($zone_0[$i][2] < $player_x) && ($zone_0[$i][3] > $player_x) && ($zone_0[$i][1] < $player_y) && ($zone_0[$i][0] > $player_y)) return ($zone_0[$i][4]);
          break;
      case 1:
        for ($i=0; $i<=count($zone_1); $i++)
	       if (($zone_1[$i][2] < $player_x) && ($zone_1[$i][3] > $player_x) && ($zone_1[$i][1] < $player_y) && ($zone_1[$i][0] > $player_y)) return ($zone_1[$i][4]);
          break;
      case 530:
        for ($i=0; $i<=count($zone_530); $i++)
	       if (($zone_530[$i][2] < $player_x) && ($zone_530[$i][3] > $player_x) && ($zone_530[$i][1] < $player_y) && ($zone_530[$i][0] > $player_y)) return ($zone_530[$i][4]);
          break; 
      default:
        return("Unknown Zone");
    }
}

//get player class by its id
function get_player_class($class_id){
  switch ($class_id) {
    case 1:
      return("Warrior");
        break;
    case 2:
      return("Paladin");
        break;
    case 3:
      return("Hunter");
        break;
    case 4:
      return("Rogue");
        break;
    case 5:
      return("Priest");
        break;
    case 7:
      return("Shaman");
        break;
    case 8:
      return("Mage");
        break;
    case 9:
      return("Warlock");
        break;
    case 11:
      return("Druid");
        break;
    default:
      return(" ");
  }
}

//get player race by its id
function get_player_race($race_id){
  switch ($race_id) {
    case 1:
      return("Human");
        break;
    case 2:
      return("Orc");
        break;
    case 3:
      return("Dwarf");
        break;
    case 4:
      return("NightElf");
        break;
    case 5:
      return("Undead");
        break;
    case 6:
      return("Tauren");
        break;
    case 7:
      return("Gnome");
        break;
    case 8:
      return("Troll");
        break;
    case 10:
      return("Blood Elf");
        break;
    case 11:
      return("Draenei");
        break;
    default:
      return(" ");
  }
}    

//get item name from items entry
function get_item_name($item_id){
  global $lang_global, $world_db;
    if($item_id){
      mysql_connect($dbhost,$dbuser,$dbpass) or die(error("Error - Connection to database is not established !"));
      mysql_select_db($world_db) or die(error("Error - Can't open the database ! ('$world_db')"));
        $sql = "SELECT name1 FROM `items` WHERE `entry`='$item_id'";
	      $result = mysql_query($sql) or die(error(mysql_error()));
	      $total_items = mysql_num_rows($result);
	       if ($total_items == 1) $item_name = $total_items($result, 0,"name1");
		      else $item_name = "ItemID: $item_id Not Found" ;
      mysql_close();
	     return($item_name);
    } else 
       return(NULL);
}
?>