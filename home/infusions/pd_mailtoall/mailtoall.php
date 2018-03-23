<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
require_once INCLUDES."sendmail_include.php";


if (!checkrights("IP")) fallback("../index.php");


if (isset($_POST['send']))
{
	$subject = stripslash($_POST['subject']);
	if ($_POST['format'] == "plain") {
		$content = stripslash($_POST['content']);
	} else if ($_POST['format'] == "html") {
		$content = "<html>
								<head>
								<style type=\"text/css\">
								<!--
								a { color: #0000ff; text-decoration:none; }
								a:hover { color: #0000ff; text-decoration: underline; }
								body { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:10px; }
								p { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:10px; }
								.td { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:10px; }
								-->
								</style>
								</head>
								<body>
								".stripslashes($_POST['content'])."
								</body>
								</html>";
	}
	//Send to	
	$msg_to_group = $_POST['msg_to_group'];
	if ($msg_to_group == "101" || $msg_to_group == "102" || $msg_to_group == "103")
	{
		$result = dbquery("SELECT user_name, user_email FROM ".$db_prefix."users WHERE user_level>='".$msg_to_group."'");
		while ($data = dbarray($result))
		{
			sendemail($data['user_name'], 
								$data['user_email'],
								$settings['siteusername'],
								$settings['siteemail'],
								$subject,
								$content,
								$_POST['format'],
								$cc="",
								$bcc="");
		}
	} else {
		$result = dbquery("SELECT user_name, user_email FROM ".$db_prefix."users WHERE user_groups REGEXP('^\\\.{$msg_to_group}$|\\\.{$msg_to_group}\\\.|\\\.{$msg_to_group}$')");
		while ($data = dbarray($result))
		{
			sendemail($data['user_name'], 
								$data['user_email'],
								$settings['siteusername'],
								$settings['siteemail'],
								$subject,
								$content,
								$_POST['format'],
								$cc="",
								$bcc="");
		}
	}
	

	opentable("E-Mail Versand");
	echo "<center><br>\n";
	if (!$error) {
		echo "E-Mail Sent!<br><br>\n";
	} else {
		echo "Fehler!<br><br>\n".$error."<br><br>\n";
	}
	echo "<a href='mailtoall.php'>Back to Mailltoall</a><br><br>
<a href='".ADMIN."index.php'>Admin Index</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['preview'])) {
		$subject = phpentities(stripslash($_POST['subject']));
		$content = phpentities(stripslash($_POST['content']));
		$plain = ($_POST['format'] == "plain" ? " checked" : "");
		$html = ($_POST['format'] == "html" ? " checked" : "");
		if ($_POST['format'] == "plain") {
			$contentpreview = nl2br(stripslash($_POST['content']));
		} else {
			$contentpreview = stripslash($_POST['content']);
		}
		opentable($subject);
		echo "$contentpreview\n";
		closetable();
		tablebreak();
		
	} else {
		if (!isset($_POST['preview'])) {
			$subject = "";
			$content = "";
			$html = "";
			$plain = " checked";
		}
	}
	$action = FUSION_SELF;
	//Usergroups & Levels
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		if ($user_group['0'] != "0") {
			$sel = ($msg_to_group == $user_group['0'] ? " selected" : "");
			$user_types .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
		}
	}

	opentable("MailToAll");
	echo "<form name='inputform' method='post' action='$action' onSubmit='return ValidateForm(this)'>
        <table align='center' cellspacing='0' cellpadding='0' class='tbl'>
          <tr>
            <td width='100'>Message to Group:</td>
            <td><select name='msg_to_group' class='textbox'>\n".$user_types."</select></td>
          </tr>
          <tr>
            <td width='100'><br/>Subject:</td>
            <td><br/><input type='text' name='subject' value='$subject' class='textbox' style='width:250px;'></td>
          </tr>
          <tr>
            <td valign='top' width='100'><br/>Text</td>
            <td><br/><textarea name='content' cols='95' rows='15' class='textbox'>$content</textarea></td>
          </tr>
          <tr>
            <td>HTML-Code:</td>
            <td>
              <input type='button' value='p' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('content', '<p>', '</p>');\">
              <input type='button' value='br' class='button' style='font-weight:bold;width:25px;' onClick=\"insertText('content', '<br>');\">
              <input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('content', '<b>', '</b>');\">
              <input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('content', '<i>', '</i>');\">
              <input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('content', '<u>', '</u>');\">
              <input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('content', '<a href=\'http://\' target=\'_blank\'>', '</a>');\">
              <input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('content', '<img src=\'".$settings['siteurl']."fusion_images/\' style=\'margin:5px;\' align=\'left\'>');\">
              <input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('content', '<center>', '</center>');\">
              <input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('content', '<span class=\'small\'>', '</span>');\">
              <input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('content', '<span class=\'small2\'>', '</span>');\">
              <input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('content', '<span class=\'alt\'>', '</span>');\">
            </td>
          </tr>
          <tr>
            <td><br>Format:</td>
            <td><br><input type='radio' name='format' value='plain'$plain>Text <input type='radio' name='format' value='html'$html>HTML</td>
          </tr>
          <tr>
            <td align='center' colspan='2'><br>
              <input type='submit' name='preview' value='Preview' class='button'>
              <input type='submit' name='send' value='Send' class='button'>
            </td>
          </tr>
        </table>
        </form>\n";
	closetable();
	echo "<script language=\"JavaScript\">
function ValidateForm(frm) {
	if(frm.subject.value=='') {
		alert('You must fill out the fields first!');
		return false;
	}
}
</script>\n";
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>
