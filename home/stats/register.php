<?php

#################
#               #
#               #
#     Enjoy!    #
#               #
#               #
#################

include("config.php");

error_reporting(E_ALL ^ E_NOTICE);

session_start();

$msg = Array();
$error = Array();

function addUser(){
    if (empty($_POST)) return false;
    global $config, $msg, $error;
    if (empty($_POST['login'])) $error[] = 'Error, You forgot to enter a account name!';
    if (empty($_POST['password'][0]) || empty($_POST['password'][1])) $error[] = 'Error, You forgot to enter a password!';
    if ($_POST['password'][0] !== $_POST['password'][1]) $error[] = 'Password does not match!';
    if (empty($_POST['email'])) $error[] = 'Please fill in a valid email adress!';
    if (!empty($error)) return false;
    $db = @mysql_connect($config['mysql_host'], $config['mysql_user'], $config['mysql_pass']);
    if (!$db) return $error[] = 'Database: '.mysql_error();
    if (!@mysql_select_db($config['mysql_dbname'], $db)) return $error[] = 'Database: '.mysql_error();
    $query = "SELECT `acct` FROM `accounts` WHERE `login` = '".mysql_real_escape_string($_POST['login'])."'";
    $res = mysql_query($query, $db);
    if (!$res) return $error[] = 'Database: '.mysql_error();
    if (mysql_num_rows($res) > 0) return $error[] = 'Username already in use.';
    $query = "INSERT INTO `accounts` (`login`,`password`,`lastip`, `email`, `flags`) VALUES ('".mysql_real_escape_string($_POST['login'])."', '".mysql_real_escape_string($_POST['password'][0])."', '".$_SERVER['REMOTE_ADDR']."', '".mysql_real_escape_string($_POST['email'])."', '8')";
    $res = mysql_query($query, $db);
    if (!$res) return $error[] = 'Database: '.mysql_error();
    $msg[] = 'The Account <span style="color:#00FF00"><strong>'.htmlentities($_POST['login']).'</strong></span> has been created!';
    mysql_close($db);
    return true;
}
{
addUser();
}

?>


<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Account Creation Page</title>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <style type="text/css" media="screen">@import url(server_stats.css);</style>
    <!--[if lt IE 7.]>
    <script defer type="text/javascript" src="pngfix.js"></script>
    <![endif]-->
</head>
<body>
    <center>
    <div class="logo"></div>
    <div style="width:300px">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr class="head"><th colspan="2">Account Creation</th></tr>
            <tr>
                <th>Username: </th><td align="center"><input class="button" type="text" name="login" size="30" maxlength="16"/></td>
            </tr>
            <tr>
                <th>Password: </th><td align="center"><input class="button" type="password" name="password[]" size="30" maxlength="16"/></td>
            </tr>
            <tr>
                <th>Retype Password: </th><td align="center"><input class="button" type="password" name="password[]" size="30" maxlength="16"/></td>
            </tr>
            <tr>
                <th>E-mail: </th><td align="center"><input class="button" type="text" name="email" size="30" maxlength="30"/></td>
            </tr>
        </table>
        <input type="button" class="button" value="Back" onClick="history.go(-1)" />
        <input type="submit" value="Create" class="button"/>
        </form>

		<?php
        if (!empty($error)){
            echo '<table width="100%" border="0" cellspacing="1" cellpadding="3"><tr><td class="error" align="center">';
            foreach($error as $text)
                echo $text.'</br>';
            echo '</td></tr></table>';
        };
        if (!empty($msg)){
            echo '<table width="100%" border="0" cellspacing="1" cellpadding="3"><tr><td align="center">';
            foreach($msg as $text)
                echo $text.'</br>';
            echo '</td></tr></table>';
            exit();
        };
        ?>

    </div>
    <div class="footer">
        Original design by mmorpg4free.com, styling by NoriaC
    </div>
    </center>
</body>
</html>