<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright &#352; 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/

require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";

if (file_exists(INFUSIONS."avatar_gallery/locale/".$settings['locale'].".php")) {
        include INFUSIONS."avatar_gallery/locale/".$settings['locale'].".php";
} else {
        include INFUSIONS."avatar_gallery/locale/English.php";
}

if (iMEMBER) {

        if ($speichern) {
          $ext = strrchr($speichern, '.');
          copy(IMAGES."avatars/".$speichern, IMAGES."avatars/avatar[".$userdata[user_id]."]".$ext);
          $saveresult = dbquery("UPDATE `".$db_prefix."users` SET `user_avatar` = 'avatar[".$userdata[user_id]."]".$ext."' WHERE `user_id` = '".$userdata['user_id']."' LIMIT 1");
          opentable($locale['AVA_001']);
           echo '<br /><center>'.$locale['AVA_016'].'<br /><br /><a href="'.BASEDIR.'edit_profile.php">'.$locale['AVA_017'].'</a><br /><br /></center>';
          closetable();
        } else {
                $avatarfolder=IMAGES."avatars/avatar_gallery/";
                $handle = opendir($avatarfolder);
                while ($folder = readdir($handle)) {
                        if (!in_array($folder, array(".", "..", "/", "index.php"))) {
                                $avatar_list[] = $folder;
                        }
                }
                closedir($handle);
                sort($avatar_list);

                opentable($locale['AVA_001']);
                  echo '<table width="100%" cellpadding="0" cellspacing="1" class="tbl-border">
                                  <tr><td class="tbl2" colspan="2"><b>'.$locale['AVA_002'].'</b></td></tr>
                                  <tr><td class="tbl2">'.$locale['AVA_003'].'</td><td class="tbl1">'.$locale['AVA_004'].'</td></tr>
                                  <tr><td class="tbl2">'.$locale['AVA_005'].'<br /><small>'.$locale['AVA_006'].'</small></td><td class="tbl1">';
                  echo "  <form name='inputform1' method='post' action='".FUSION_SELF."'>
                                        <input type='hidden' name='av_folder' value='".$avatarfolder."'>";
                  if(!$_POST['avatar_box']) { $avatar_box=$avatar_list[0]; }
                  echo "    <input type='hidden' name='av_kat' value='".$avatar_box."'>
                                        <select onChange=\"javascript:document.inputform1.submit();\" name='avatar_box' size='1' class='textbox' style='width:250px;'>\n";
                                        for ($count = 0; $avatar_list[$count] != ""; $count++) {
                                                if($_POST['avatar_box'] == $avatar_list[$count]) {
                                                        echo "<option selected value='".$avatar_list[$count]."'>$avatar_list[$count]</option>\n";
                                                } else {
                                                        echo "<option value='".$avatar_list[$count]."'>$avatar_list[$count]</option>\n";
                                                }
                                        }
                  echo "  </select></form>";
                  echo '  </td></tr>
                                  <tr><td class="tbl2">'.$locale['AVA_007'].'<br /><small>'.$locale['AVA_008'].'</small></td><td class="tbl1">';
                $avatarfolder2=$avatarfolder.$avatar_box."/";
                $handle = opendir($avatarfolder2);
                while ($folder = readdir($handle)) {
                        if (!in_array($folder, array(".", "..", "/", "index.php"))) {
                                $avatar_file_list[] = $folder;
                        }
                }
                closedir($handle);
                sort($avatar_file_list);

                echo "<form name='inputform2'>
                          <select onChange='showimage();' name='avatar_file_box' size='10' class='textbox' style='width:250px;'>\n";
                for ($count=0;$avatar_file_list[$count]!="";$count++) {
                        echo "<option value='".$avatar_file_list[$count]."'>$avatar_file_list[$count]</option>\n";
                }
                  echo "</select></form>";
                  echo '  </td></tr>
                                  <tr><td class="tbl2">'.$locale['AVA_009'].'</td><td class="tbl1" align="center"><img src="'.INFUSIONS.'avatar_gallery/img/noav.gif" name="pictures"></td></tr>
                                  <tr><td class="tbl2">'.$locale['AVA_010'].'<br /><small>'.$locale['AVA_011'].'</small></td><td align="center" class="tbl1"><input type="Submit" onClick="saveavatar();" value="'.$locale['AVA_018'].'" class="button"></td></tr>
                                </table>';

                closetable();

                echo "<script language='JavaScript'>

                function saveavatar() {
                 savepfad = 'avatar_gallery/' + document.inputform1.av_kat.value + '/' + document.inputform2.avatar_file_box.options[document.inputform2.avatar_file_box.selectedIndex].value
                  newwin = window.open('avatar_gallery.php?speichern='+savepfad, '_self')
                }

                function funn(category) {
                  newwin = window.open('avatar_gallery.php?cat='+category, '_self')
                }

                function showimage() {
                  document.images.pictures.src= document.inputform1.av_folder.value + document.inputform1.av_kat.value + '/' + document.inputform2.avatar_file_box.options[document.inputform2.avatar_file_box.selectedIndex].value
                }

                </script>";
        }

} else {
        opentable($locale['AVA_012']);
        echo "<br /><center><img src='".INFUSIONS."avatar_gallery/img/restricted.gif' alt='Restricted Access'><br /><br />".$locale['AVA_013']."
        <br /><br /><img src='".THEME."images/bullet.gif'><a href='".BASEDIR."register.php'> ".$locale['AVA_014']." </a><img src='".THEME."images/bulletb.gif'><br /><img src='".THEME."images/bullet.gif'><a href='".BASEDIR."lostpassword.php'> ".$locale['AVA_015']." </a><img src='".THEME."images/bulletb.gif'><br /><br /></center>";
        closetable();
}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>
