<?php
	/*
	 * 2007 (c) -={MikeP}=-
	 * Guild information for Ascent WOW emu.
	*/
	require_once "maincore.php";
	require_once "subheader.php";
	require_once "side_left.php";
	require_once('include/dbLayer.php');
	require_once('include/readableData.php');
	
	if(empty($_GET['n'])){
		echo '<script type="text/javascript">window.close();</script>';
		exit();
	}
	$gname = $_GET['n'];
	
	try{
		// 1) Get guild description
		$pdb = dbLayer::getCharDB();
		$gname = mysql_real_escape_string($gname); // SQL injection prevention
		$pdb->Execute("SELECT * FROM guilds WHERE guildName='$gname'");
		
		if( !($guild = $pdb->Fetch()) ) throw new Exception('There is no guild "'.$gname.'" in database.');
		
		// 2) get ranks for guild members
		$granks = array();
		$gid = $guild->guildId;
		$pdb->Execute("SELECT * FROM guild_ranks WHERE guildId = $gid ORDER BY rankId ASC");
		while($gr = $pdb->Fetch()) $granks[$gr->rankId] = $gr;
		
		// 3) getGuild members
		$gmembers = array();
		$pdb->Execute("SELECT * FROM characters, guild_data WHERE guild_data.guildid = $gid AND guild_data.playerid = characters.guid ORDER BY guildRank ASC");
		while($gm = $pdb->Fetch()) $gmembers[$gm->guid] = $gm;
		
		// 4) fetch any other required information
		
	}catch(Exception $e){
		echo '<html><body>'.$e->getMessage().'<script type="text/javascript">setTimeout("window.close()",5000);</script></body></html>';
		exit;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Guild details: <?php echo $gname; ?></title>
<link href="include/wow.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="740" border="0" align="center" cellpadding="0" cellspacing="10">
	<tr>
		<td width="120" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" class="onlinePlrs">
			<tr>
				<th><center>
					Guild ranks
				</center></th>
			</tr>
			<?php
				foreach($granks as $gr){
					echo '<tr><td>'.$gr->rankName.'</td></tr>';
				}
			?>
		</table></td>
		<td valign="top"><table width="600" border="0" cellpadding="1" cellspacing="1" class="onlinePlrs">
			<tr>
				<th width="360"><center>
					Guild &quot;<?php echo $guild->guildName; ?>&quot;<br />
					<span class="mapname">created <?php echo $guild->createdate; ?></span>
				</center>				</th>
			</tr>
			<tr>
				<td height="15" colspan="2"></td>
				</tr>
			<tr>
				<td colspan="2"><table width="100%" border="0" cellpadding="1" cellspacing="1" class="onlinePlrs">
						<tr>
							<th>Member</th>
							<th><center>
								Race
							</center></th>
							<th><center>
								Class
							</center></th>
							<th><center>
								Level
							</center></th>
							<th><center>
								Rank
								</center></th>
						</tr>
						<?php
							foreach($gmembers as $gm){
								// name
								echo '<tr><td><a href="armory.php?n='.rawurlencode($gm->name).'" target="_blank">'.$gm->name.'</a></td>';
								// race
								echo '<td valign="middle"><center><img src="img/icon/race/'.$gm->race.'-'.$gm->gender.'.gif" alt="'.hrData::$arrRace[$gm->race].'" width="18" height="18" /></center></td>';
								// class
								echo '<td valign="middle"><center><img src="img/icon/class/'.$gm->class.'.gif" alt="'.hrData::$arrClass[$gm->class].'" width="18" height="18" /></center></td>';
								// level
								echo '<td><center>'.$gm->level.'</center></td>';
								// rank
								echo '<td><center>'.$granks[$gm->guildRank]->rankName.'</center></td></tr>';
							}
						?>
					</table></td>
				</tr>
		</table></td>
        <?php
		
		echo '<tr></tr><td colspan="2"><table width="100%" border="0" cellpadding="1" cellspacing="1" class="onlinePlrs">
			<tr>
				<th><center>Guild information</center></th>
			</tr>
			<tr>';
		?>
				<td><?php echo $guild->guildInfo; ?></td>
			</tr>
			<tr>
				<td height="20" width="100">&nbsp;</td>
			</tr>
			<tr>
				<th><center>Guild message of the day</center></th>
			</tr>
			<tr>
				<td><?php echo $guild->motd; ?></td>
			</tr>
		</table></td>
		
      
	</tr>
</table>
</body>
</html>
