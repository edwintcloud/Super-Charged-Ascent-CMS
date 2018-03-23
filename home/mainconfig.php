<?php

//Basic Settings
$db_host = "localhost";
$db_user = "root";
$db_pass = "ascent";
$world_db_name = "world";
$logon_db_name = "logon";
$website_db_name = "portal";
$vote_db_name = "avs";
$donations_db_name = "donations";
$armory_db_name = "armory";
$site_title = "My Server_name";
$logonserver_port = "3306";
$site_name = "My_site";
$site_url = "my_url";


//Registration Settings
$def_gm_lvl	= 0;	// Default starting user access level, default = 0 (Player), 1 - 3 are GM levels (And i think az is full rights, or was, not sure about later revs).
$acc_per_ip	= 9999; // Sets the ammount of accounts per IP a user can make. default = 1

//tools setup
$teleporter_title = "$site_name: Teleporter";
$teleporter_cost = 0;

//stats setup
$stats_loc = "stats.xml"; //location of server stats.xml file

//donations setup
$donate_acp_user = "admin";
$donate_acp_pass = "godzilla";
$paypal_email = "payup@gmail.com";
$paypal_url = "paypal.com";
$currency = "USD";
$currency_character = "$"; 

//vote setup
$vote_reward_points = 1;     //vote_popup_style and vote_popup_btn_style configs are for experienced scripters ONLY!
$vote_popup_style = "<div id='container' style='-moz-user-select: none; -khtml-user-select: none; user-select: none; cursor:default; padding-bottom:5px; width:320px; border: 4px double red; position: absolute; top:70px; left:60px; background-color:black; color:white;'>";
$vote_popup_btn_style = "<input type='submit' style='font-size: 12px; position:relative; top:1px; color:white; height: 17px; border:1px solid red; background-color:black;' value='Vote!' />";

//to set up realm status panels see realmconfig.php

//DO NOT TOUCH
$db_prefix = "fusion_";

//WEBAM Config
$url_webam = "my_url/home/WEBAM";
$title_webam = "$site_name: WEBAM";
$link1_webam = "<a href=\"../\">Back</a>"; //format for links:<a target=\"_blank\" href=\"http://your.site.com/\">your site name</a>
$link2_webam = "<a href=\"../forum\">Forum</a>"; 
$link3_webam = "<a href=\"../downloads.php\">Downloads</a>"; 
$timezone = "EST";
$user_level_0 = '0';   //regular player command level
$user_level_1 = 't';   //moderator/developer command level
$user_level_2 = 'a';   //administrator command level
$user_level_3 = 'az';  //owner/super admin command level
$tables_backup_logon = Array(
     "account_data", "account_forced_permissions", "accounts", "arenateams", "auctions", "banned_names", 
	 "character_achievement", "character_achievement_progress", "characters", "charters", "command_overrides", 
	 "corpses", "gm_tickets", "groups", "guild_bankitems", "guild_banklogs", "guild_banktabs", "guild_data", 
	 "guild_logs", "guild_ranks", "guilds", "instanceids", "instances", "ipbans", "mailbox", "playercooldowns", 
	 "playeritems", "playerpets", "playerpetspells", "playersummonspells", "questlog", "server_settings", "site_motd", "social_friends", "social_ignores", "tutorials"
    );

?>