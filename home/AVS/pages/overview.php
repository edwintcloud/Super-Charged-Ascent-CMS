<?php


class Overview
{
	function Title()
	{
	include "../mainconfig.php";
	echo "$site_name - Vote Reward Control Panel";
		?>
        <?php
	}
	
	function ListVoteModules()
	{
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
		mysql_select_db(MYSQL_DB);
		
		//clear expired vote timeouts
		mysql_query("DELETE FROM votes WHERE time < ".(time()-12*60*60));
		$res = mysql_query("SELECT id,name FROM votemodules");
		while($Row = mysql_fetch_array($res))
		{
			$r = mysql_query("SELECT time FROM votes WHERE module='{$Row['id']}' AND ip='{$_SERVER['REMOTE_ADDR']}'");
			if(!$R = mysql_fetch_array($r))
				$time = "now";
			else
			{
				$Expiretime = (int)$R['time'] + (12*60*60);
				$Until = $Expiretime - time();
				$time = ceil($Until/60/60);
			}
			if($time == 1)
				$time = $time." hour";
			elseif($time > 1)
				$time = $time." hours";
			?>				
				<tr>
					<td width="100px"><?php echo $Row['name']; ?></td>
					<td><b><?php echo $time; ?></b>.</td>
				</tr>
			<?php
		}
		mysql_close($Con);
	}
	
	function Main()
	{
		
		?><div style="margin-left:10px;"><h3> &bull; Welcome</h3>
            <p>Welcome, <b><?php echo $_SESSION['vcp']['account']; ?></b>.</p>
            <table>
                <tr>
					<td colspan="2"><b>Your Stats</b></td>
				</tr>
				<tr>
					<td width="125px">&bull; Reward Points:</td>
					<td><b><?php echo $_SESSION['vcp']['points']; ?></b></td>
				</tr>
				<tr>
					<td valign="top">&bull; Time to vote:</td>
					<td valign="top">
							
					</td>
				</tr>
				<?php $this->ListVoteModules(); ?>
            </table>
			
			</div>
        <?php
	
	}
	
	function Content()
	{
		$this->Main();
	}

	function __construct()
	{
		include("html/main.php");
	}
}




?>