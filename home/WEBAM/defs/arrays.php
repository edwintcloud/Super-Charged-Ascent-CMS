<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 30.01.2008 inital version (1.63)
 * Author: gmaze
 * Copyright: gmaze
 * Email: *****
 * License: GNU General Public License (GPL)
 */
 
//define list of arrays used
 			$acctarray = array();
      $acntarray = array();
      $char_array = array();   
	    $classarray = array();
	    $equiped_bag_id = array();
	    $equiped_items = array();
	    $gmarray = array();
      $guidarray = array();
      $intrsect_array = array();
      $iparray = array();
      $lastiparray = array();
	    $levelarray = array();
	    $loginarray = array();
	    $maparray = array();
	    $namearray = array();
	    $positionxarray = array();
      $positionyarray = array();
	    $racearray = array();
      $result_array = array();
      $sect_array = array();    
	    $zoneidarray = array();
//bag array
      $bag = array(
		    0=>array(),
		    1=>array(),
		    2=>array(),
		    3=>array(),
		    4=>array()
		  );
//zone arrays
$zone_0 = Array(
		Array(700,10,1244,1873,'Undercity',1497),
		Array(-840,-1330,-5050,-4560,'Ironforge',1537),
		Array(1190,200,-9074,-8280,'Stormwind City',1519),
		Array(-2170,-4400,-7348,-6006,'Badlands',3),
		Array(-500,-4400,-4485,-2367,'Wetlands',11),
		Array(2220,-2250,-15422,-11299,'Stranglethorn Vale',33),
		Array(-1724,-3540,-9918,-8667,'Redridge Mountains',44),
		Array(-2480,-4400,-6006,-4485,'Loch Modan',38),
		Array(662,-1638,-11299,-9990,'Duskwood',10),
		Array(-1638,-2344,-11299,-9918,'Deadwind Pass',41),
		Array(834,-1724,-9990,-8526,'Elwynn Forest',12),
		Array(-500,-3100,-8667,-7348,'Burning Steppes',46),
		Array(-608,-2170,-7348,-6285,'Searing Gorge',51),
		Array(2000,-2480,-6612,-4485,'Dun Morogh',1),
		Array(-1575,-5425,-432,805,'The Hinterlands',47),
		Array(3016,662,-11299,-9400,'Westfall',40),
		Array(600,-1575,-1874,220,'Hillsbrad Foothills',267),
		Array(-2725,-6056,805,3800,'Eastern Plaguelands',139),
		Array(-850,-2725,805,3400,'Western Plaguelands',28),
		Array(2200,600,-900,1525,'Silverpine Forest',130),
		Array(2200,-850,1525,3400,'Tirisfal Glades',85),
		Array(-2250,-3520,-12800,-10666,'Blasted Lands',4),
		Array(-2344,-4516,-11070,-9600,'Swamp of Sorrows',8),
		Array(-1575,-3900,-2367,-432,'Arathi Highlands',45),
		Array(600,-1575,220,1525,'Alterac Mountains',36)
 		);

$zone_1 = Array(
		Array(2698,2030,9575,10267,'Darnassus',1657),
		Array(326,-360,-1490,-910,'Thunder Bluff',1638),
		Array(-3849,-4809,1387,2222,'Orgrimmar',1637),
		Array(-1300,-3250,7142,8500,'Moonglade',493),
		Array(2021,-400,-9000,-6016,'Silithus',1377),
		Array(-2259,-7000,4150,8500,'Winterspring',618),
		Array(-400,-2094,-8221,-6016,'Un\'Goro Crater',490),
		Array(-590,-2259,3580,7142,'Felwood',361),
		Array(-3787,-8000,1370,6000,'Azshara',16),
		Array(-1900,-5500,-10475,-6825,'Tanaris',440),
		Array(-2478,-5500,-5135,-2330,'Dustwallow Marsh',15),
		Array(360,-1536,-3474,-412,'Mulgore',215),
		Array(4000,-804,-6828,-2477,'Feralas',357),
		Array(3500,360,-2477,372,'Desolace',405),
		Array(-804,-5500,-6828,-4566,'Thousand Needles',400),
		Array(-3758,-5500,-1300,1370,'Durotar',14),
		Array(1000,-3787,1370,4150,'Ashenvale',331),
		Array(2500,-1300,4150,8500,'Darkshore',148),
		Array(3814,-1100,8600,11831,'Teldrassil',141),
		Array(3500,-804,-412,3580,'Stonetalon Mountains',406),
		Array(-804,-4200,-4566,1370,'The Barrens',17)
		);

$zone_530 = Array(
		Array(6135.25,4829,-2344.78,-1473.95,'Shattrath City',3703),
		Array(-6400.75,-7612.20,9346.93,10153.70,'Silvermoon City',3487),
		Array(5483.33,-91.66,1739.58,5456.25,'Netherstorm',3523),
		Array(7083.33,1683.33,-4600,-999.99,'Terokkar Forest',3519),
		Array(10295.83,4770.83,-3641.66,41.66,'Nagrand',3518),
		Array(-10075,-13337.49,-2933.33,-758.33,'Bloodmyst Isle',3525),
		Array(8845.83,3420.83,791.66,4408.33,'Blades Edge Mountains',3522),
		Array(4225,-1275,-5614.58,-1947.91,'Shadowmoon Valley',3520),
		Array(-11066.36,-12123.13,-4314.37,-3609.68,'The Exodar',3557),
		Array(9475,4447.91,-1416.66,1935.41,'Zangarmarsh',3521),
		Array(5539.58,375,-1962.49,1481.25,'Hellfire Peninsula',3483),
		Array(-10500,-14570.83,-5508.33,-2793.75,'Azuremyst Isle',3524),
		Array(-5283.33,-8583.33,6066.66,8266.66,'Ghostlands',3433),
		Array(-4487,-9412,7758,11041,'Eversong Woods',3430)
    );

?>