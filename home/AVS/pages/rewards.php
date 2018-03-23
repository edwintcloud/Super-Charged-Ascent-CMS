

<?php

class Rewards
{
	function Title()
	{
	include "../mainconfig.php";
	echo "$site_name - Voter Rewards";
		?>
        <?php
	}

	function GetRealmData()
	{
		//Get data to use in the form.
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,true);
		mysql_select_db(MYSQL_DB,$Con);
		
		$RealmInfo = "{";
		//get each realm
		$res = mysql_query("SELECT id,name,sqlhost,sqluser,sqlpass,chardb FROM realms",$Con);
		echo mysql_error();
		while($Row = mysql_fetch_array($res))
		{
			$RealmInfo .= $Row['id'].":{name:\"".$Row['name']."\"},";
		}
		if(strlen($RealmInfo) > 1)
			$RealmInfo = substr($RealmInfo,0,strlen($RealmInfo)-1)."}";
		else
			$RealmInfo .='}';
		mysql_close($Con);
		return $RealmInfo;
	}
	
	function GetCharData()
	{
		//get data that will be used to select the character.
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,true);
		mysql_select_db(MYSQL_DB,$Con);
		
		$CharInfo = "{";
		$Index = 0;
		$res = mysql_query("SELECT id,sqlhost,sqluser,sqlpass,chardb FROM realms");
		while($Row = mysql_fetch_array($res))
		{
			$Con2 = mysql_connect($Row['sqlhost'],$Row['sqluser'],$Row['sqlpass'],true);
			mysql_select_db($Row['chardb'],$Con2);
			$res2 = mysql_query("SELECT guid,name FROM characters WHERE acct = '{$_SESSION['vcp']['id']}'",$Con2);
			while($Row2 = mysql_fetch_array($res2))
			{
				$CharInfo .= $Index.":{guid:".$Row2['guid'].",realm:".$Row['id'].",name:\"".$Row2['name']."\"},";
				$Index++;
			}
			mysql_close($Con2);
		}
		if(strlen($CharInfo) > 1)
			$CharInfo = substr($CharInfo,0,strlen($CharInfo)-1)."}";
		else
			$CharInfo .='}';
		mysql_close($Con);
		return $CharInfo;
	}
	
	function GetRewardData()
	{
		//rewards.. etc.
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,true);
		mysql_select_db(MYSQL_DB,$Con);
		
		$RewardInfo = "{";
		
		$res = mysql_query("SELECT id,realm,name,description,points FROM voterewards");
		while($Row = mysql_fetch_array($res))
		{
			$RewardInfo .= $Row['id'].":{realm:".$Row['realm'].",name:\"".$Row['name']."\",description:\"".$Row['description']."\",cost:".$Row['points']."},";
		}
		if(strlen($RewardInfo) > 1)
			$RewardInfo = substr($RewardInfo,0,strlen($RewardInfo)-1)."}";
		else
			$RewardInfo .='}';
		$RewardInfo = str_replace("\r\n","<br />",$RewardInfo);
		mysql_close($Con);
		return $RewardInfo;
	}

	function Form()
	{
		?>
			<table>
				<tr>
					<td valign="top">
						<table cellspacing="2px">
							<tr valign="top">
								<td colspan="2">Select A Reward</td>
							</tr>
							<tr valign="top">
								<td>Points:</td>
								<td><span id="points"><?php echo $_SESSION['vcp']['points']; ?></span></td>
							</tr>
							<tr valign="top">
								<td width="75px">Realm:</td>
								<td><select name="realm" id="realm" size="1" style="width:150px;" onchange="getCharacters(); getRewards();"><option value="0">Your browser is outdated.</option></select></td>
							</tr>
							<tr valign="top">
								<td>Character:</td>
								<td><select name="character" id="character" size="1" style="width:150px;"><option value="0">Your browser is outdated.</option></select></td>
							</tr>
							<tr valign="top">
								<td>Reward:</td>
								<td><select name="reward" size="1" id="reward" style="width:150px;" onchange="getInfo();"><option value="0">Your browser is outdated.</option></select></td>
							</tr>
							<tr valign="top">
								<td>Cost:</td>
								<td><span id="cost"></span> rp</td>
							</tr>
							<tr valign="top">
								<td colspan="2" align="right"><input id="purchase" type="button" value="Purchase" onclick="onPurchase();" /></td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<div id="description" style="width:200px; height:200px; border:#000000 solid 1px; padding:2px;"></div>
					</td>
				</tr>
			</table>
			<script type="text/javascript">
				var Realm = document.getElementById("realm");
				var Character = document.getElementById("character");
				var Reward = document.getElementById("reward");
				var Description = document.getElementById("description");
				var Cost = document.getElementById("cost");
				var Points = document.getElementById("points");
				var Purchase = document.getElementById("purchase");
				
				var Realms = <?php echo $this->GetRealmData(); ?>;
				var Characters = <?php echo $this->GetCharData(); ?>;
				var Rewards = <?php echo $this->GetRewardData(); ?>;
				
				var PointCount = <?php echo $_SESSION['vcp']['points']; ?>;
				
				function getCharacters()
				{
					var i=0;
					Character.options.length = 0;
					for(var r in Characters)
					{
						if(Characters[r].realm == parseInt(Realm.value))
						{
							Character.options[i] = new Option(Characters[r].name,Characters[r].guid);
							i++;
						}
					}
				}
				
				function getRewards()
				{
					var i=0;
					Reward.options.length = 0;
					for(var r in Rewards)
					{
						if(Rewards[r].realm == parseInt(Realm.value))
						{
							Reward.options[i] = new Option(Rewards[r].name,r);
							i++;
						}
					}
					getInfo();
				}
				
				function getInfo()
				{
					Description.innerHTML = Rewards[Reward.value].description;
					Cost.innerHTML = Rewards[Reward.value].cost;
				}
				
				function onPurchase()
				{
					if(Character.options.length == 0)
					{
						alert("You don't have a character on that realm!");
						return false;
					}
					if(Rewards[Reward.value].cost > PointCount)
					{
						alert("You don't have enough points!");
						return false;
					}
					if(!confirm("Are you sure you wish to spend\r\n"+Rewards[Reward.value].cost+" reward points?"))
						return false;
					Purchase.disabled = true;
					
					var R;
					var Sub = Rewards[Reward.value].cost;
					if(window.XMLHttpRequest)
					{
						R = new XMLHttpRequest();
					}
					else if(window.ActiveX)
					{
						R = new ActiveXObject("Microsoft.XMLHTTP");
					}
					R.onreadystatechange = function()
					{
						if(R.readyState == 4)
						{
							Purchase.disabled = false;
							if(R.responseText != "1")
							{
								alert("Transaction failed:\r\n"+R.responseText);
							}
							else
							{
								PointCount -= Sub;
								Points.innerHTML = PointCount;
							}
						}
					}
					R.open("POST","?act=spend",true);
					var params = "realm="+Realm.value+"&reward="+Reward.value+"&character="+Character.value;
					R.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					R.setRequestHeader("Content-length",params.length);
					R.setRequestHeader("Connection","close");
					R.send(params);
				}
				
				function Initialize()
				{
					// Setup realm list, char list, etc.
					var i = 0;
					for(var r in Realms)
					{
						Realm.options[i] = new Option(Realms[r].name,r);
						i++;
					}
					
					i=0;
					for(var r in Characters)
					{
						if(Characters[r].realm == parseInt(Realm.value))
						{
							Character.options[i] = new Option(Characters[r].name,Characters[r].guid);
							i++;
						}
					}
					
					i=0;
					for(var r in Rewards)
					{
						if(Rewards[r].realm == parseInt(Realm.value))
						{
							Reward.options[i] = new Option(Rewards[r].name,r);
							i++;
						}
					}
					getCharacters();
					getRewards();
					getInfo();
				}
				Initialize();
				
			</script>
		<?php

	}

	function Content()
	{
		$this->Form();
	}

	function __construct()
	{
		include("html/main.php");
	}

}

?>