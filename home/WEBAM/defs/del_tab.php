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

//list of tables in account db to delete on deletion
$tab_del_user_acct = Array(
    Array("accounts","acct")
);

//list of tables in character db to delete on deletion
$tab_del_user_char = Array(
	Array("auctions","owner"),
	Array("characters","acct"),
	Array("charters","leaderguid"),
	Array("corpses","guid"),
	Array("gm_tickets","guid"),
	Array("guild_data","playerid"),
	Array("mailbox","player_guid"),
	Array("playercooldownitems","OwnerGuid"),
	Array("playercooldownsecurity","OwnerGuid"),
	Array("playeritems","ownerguid"),
	Array("playerpets","ownerguid"),
	Array("playerpetspells","ownerguid"),
	Array("playersummonspells","ownerguid"),
	Array("questlog","player_guid"),
	Array("social","guid"),
	Array("tutorials","playerid"),
);

//list of tables in world db to delete on deletion
$tab_del_user_world = Array(
	Array("items","entry"),
);
?>