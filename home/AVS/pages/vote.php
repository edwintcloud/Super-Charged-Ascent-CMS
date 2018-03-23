<?php

class Vote
{
	function Title()
	{
	include "../mainconfig.php";
	echo "$site_name - Vote";
		?>
		<?php
	}
	
	function GetVoteForms()
	{
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
		mysql_select_db(MYSQL_DB);
		$res = mysql_query("SELECT id,name,image,url FROM votemodules");
		while($Row = mysql_fetch_array($res))
		{
			?>				
				<form action="?act=vote&do=vote" target="<?php echo $Row['name']; ?>" method="post">
					<input name="module" type="hidden" value="<?php echo $Row['id']; ?>" />
					<input name="to" type="hidden" value="<?php echo $Row['url']; ?>" />
					<tr>
						<td valign="top"><input type="image" src="<?php echo $Row['image']; ?>" alt="Submit" /></td>
						<td valign="bottom"><input type="text" name="account" size="20" maxlength="16" /></td>
					</tr>
					<tr>
						<td valign="top"><?php echo $Row['name']; ?></td>	
						<td valign="bottom"><input type="submit" value="Vote Now!" /></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
				</form>
			<?php
		}
		mysql_close($Con);
	}
	
	function Form()
	{
		?>
			<table style="margin-left:10px;">
				<tr>
					<td colspan="2">
						Enter your account name and click &quot;vote&quot; in the forms below.<br />
						Your account will receive vote points for every vote.
					</td>
				</tr>
				<?php $this->GetVoteForms(); ?>
			</table>
		<?php
	}
	
	function TallyVote()
	{
		$Module = addslashes($_POST['module']);
		$Account = addslashes($_POST['account']);
		$To = addslashes($_POST['to']);
		$Ip = $_SERVER['REMOTE_ADDR'];
		
		$Con = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
		mysql_select_db(MYSQL_DB);
		
		//redirect
		header("Location: {$To}\r\n");
		
		//check that the module exists
		if(mysql_result(mysql_query("SELECT COUNT(*) FROM votemodules WHERE id='{$Module}'"),0) != 1)
			return;
			
		//check if the user or account has been accredited for a vote within the last 12 hrs.
		if(mysql_result(mysql_query("SELECT COUNT(*) FROM votes WHERE module='{$Module}' AND (ip = '{$Ip}' OR account = '{$Account}')"),0) != 0)
			return;
		
		//set cookie
		setcookie("vote_time","1",time()+12*60*60,"/");
		
		//add vote to timeout
		mysql_query("INSERT INTO votes VALUES ('{$Ip}','{$Account}','{$Module}','".time()."')");
		
		mysql_close($Con);
		$Con = mysql_connect(LOGON_HOST,LOGON_USER,LOGON_PASS);
		mysql_select_db(LOGON_DB);
		
		// +1 vote point
		if($_SESSION['vcp']['account'] == $Account)
		{
			(int)$_SESSION['vcp']['points']+= RPPV;
		}
		mysql_query("UPDATE accounts SET reward_points = reward_points + ".RPPV." WHERE login='{$Account}'");
		mysql_close($Con);
	}
	
	function Content()
	{
		$this->Form();
	}

	function __construct()
	{
		if($_GET['do'] == "vote")
		{
			$this->TallyVote();
			return;
		}
		include("html/main.php");
	}

}

?>