<?php

class Spend
{

	function __construct()
	{
		$Realm = addslashes($_POST['realm']);
		$Character = addslashes($_POST['character']);
		$Reward = addslashes($_POST['reward']);
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
		mysql_select_db(MYSQL_DB);
		
		//check availability of reward.
		$res = mysql_query("SELECT itemid,points FROM voterewards WHERE id='{$Reward}' AND realm='{$Realm}'");
		if(!$Rinfo = mysql_fetch_array($res))
			die("Nonexistant reward or realm.");
			
		
		// check points available
		if((int)$Rinfo['points'] > (int)$_SESSION['vcp']['points'])
			die("Insufficient points.");
			
		//get realm info
		$res = mysql_query("SELECT sqlhost,sqluser,sqlpass,chardb FROM realms WHERE id='{$Realm}'");
		$Realminfo = mysql_fetch_array($res);
		mysql_close($Con);
		
		// deduct points
		$Con = mysql_connect(LOGON_HOST,LOGON_USER,LOGON_PASS);
		mysql_select_db(LOGON_DB);
		mysql_query("UPDATE accounts SET reward_points = reward_points - {$Rinfo['points']} WHERE acct='{$_SESSION['vcp']['id']}'");
		(int)$_SESSION['vcp']['points'] -= (int)$Rinfo['points'];
		mysql_close($Con);
		
		//send item.
		$Con = mysql_connect($Realminfo['sqlhost'],$Realminfo['sqluser'],$Realminfo['sqlpass']);
		mysql_select_db($Realminfo['chardb']);
		mysql_query("INSERT INTO mailbox_insert_queue VALUES('{$Character}','{$Character}','".MAIL_SUBJECT."','".MAIL_BODY."','61','0','{$Rinfo['itemid']}','1')");
		mysql_close($Con);
		die("1");
	}
}


?>