<?php

class Login
{
	function Title()
	{
	include "../mainconfig.php";
	echo "$site_name - Vote Panel Log in";
		?>
		<?php
	}

	function Form()
	{
		?>
        	<form action="" method="post">
            	<input name="login" type="hidden" />
                <table style="margin-left:10px;">
                    <tr>
                        <td colspan="2">
                        	Please log in to your game account.
                        </td>
                    </tr>
                    <tr>
                    	<td width="100px;">
                        	Account Name:                        </td>
                  <td>
               	<input name="account" type="text" size="24" maxlength="16" />
                        </td>
                  </tr>
                    <tr>
                    	<td>
                        	Password:
                        </td>
                    	<td>
                        	<input name="password" type="password" size="24" maxlength="16" />
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" align="right">
                        	<input type="submit" value="Log In" />
                        </td>
                    </tr>
                </table>
            </form>
        <?php
	}

	function Fail()
	{
		?>
        	Login failed.
        <?php
	}

	function Success()
	{
		?>
        	Successfully logged in. Please wait while we redirect you.
            <script type="text/javascript">window.setTimeout("window.location=''",1000);</script>
        <?php
	}

	function Content()
	{
		if(isset($_POST['login']))
		{
			//verify
			$Account = addslashes($_POST['account']);
			$Password = addslashes($_POST['password']);
			$Con = mysql_connect(LOGON_HOST,LOGON_USER,LOGON_PASS);
			mysql_select_db(LOGON_DB);
			if(ENCRYPT_PASSWORDS)
			{
				//TODO
			}
			else
			{
				$res = mysql_query("SELECT acct,reward_points FROM accounts WHERE login = '{$Account}' AND password = '{$Password}'");
			}
			
			// login failed.
			if(!$Row = mysql_fetch_array($res))
				return $this->Fail();
			
			//set authenticated
			$_SESSION['vcp']['authenticated'] = true;
			$_SESSION['vcp']['id'] = (int)$Row['acct'];
			$_SESSION['vcp']['account'] = $Account;
			$_SESSION['vcp']['points'] = (int)$Row['reward_points'];
			//success
			$this->Success();
			return;
		}
		$this->Form();
	}
	
	function __construct()
	{
		include("html/message.php");
	}
}

?>