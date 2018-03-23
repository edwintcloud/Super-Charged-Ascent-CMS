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
require_once BASEDIR."side_left.php";

if (file_exists(INFUSIONS."personnel_list/locale/".$settings['locale'].".php"))
{
    include INFUSIONS."personnel_list/locale/".$settings['locale'].".php";
} else {include INFUSIONS."personnel_list/locale/English.php";}

if (!isset($action)) $action = "";

if (iADMIN)
{

if ($action == "refresh")
{
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."personnel_list ORDER BY personnel_order");
	while ($data = dbarray($result))
    {
		$result2 = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order='$i' WHERE personnel_id='".$data['personnel_id']."'");
		$i++;
	}
	redirect(FUSION_SELF);
}elseif ($action == "moveup")
{
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."personnel_list WHERE personnel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order=personnel_order+1 WHERE personnel_id='".$data['personnel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order=personnel_order-1 WHERE personnel_id='$pers_id'");
	redirect(FUSION_SELF);
}elseif ($action == "movedown")
{
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."personnel_list WHERE personnel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order=personnel_order-1 WHERE personnel_id='".$data['personnel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order=personnel_order+1 WHERE personnel_id='$pers_id'");
	redirect(FUSION_SELF);
}elseif ($action == "delete")
{
  $data = dbarray(dbquery("SELECT * FROM ".$db_prefix."personnel_list WHERE personnel_id='$pers_id'"));
  $result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order=personnel_order-1 WHERE personnel_order>'".$data['personnel_order']."'");
  unlink(IMAGES."personnels/".$data['personnel_photo']);
  $result = dbquery("DELETE FROM ".$db_prefix."personnel_list WHERE personnel_id='$pers_id'");
  redirect(FUSION_SELF);
	
} elseif (isset($_POST['pers_submit']))
    {
	   if ($_POST['pers_name'] != ""  && $_POST['pers_branch'] != "")
       {
            $pers_order = trim(stripinput($_POST['pers_order']));
            $pers_name = trim(stripinput($_POST['pers_name']));
    		$pers_branch = trim(stripinput($_POST['pers_branch']));
    		$pers_email = trim(stripinput($_POST['pers_email']));
    		$pers_web = trim(stripinput($_POST['pers_web']));
            $pers_autobiography = trim(stripinput($_POST['pers_autobiography']));
            
            $result = dbquery("SELECT * FROM ".$db_prefix."personnel_list WHERE personnel_id='$pers_id'");
            if (dbrows($result)) {$data = dbarray($result);$photo = $data['personnel_photo'];}
            if ($pers_id=="")
            {
                  $result = dbquery("SELECT MAX(personnel_id) AS last_id FROM ".$db_prefix."personnel_list");
                  if (dbrows($result)) {$data = dbarray($result);$pers_id = $data['last_id']+1;}
            }
            
         if ($pers_name != ""  && $pers_branch != "")
         {
       		   $newphoto = $_FILES['pers_photo'];
           	   if ($photo=="" && !empty($newphoto['name']) && is_uploaded_file($newphoto['tmp_name']))
               {
   		          $photoext = strrchr($newphoto['name'],".");
		          $photoname = substr($newphoto['name'], 0, strrpos($newphoto['name'], "."));
		          if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $photoname) && preg_match("/(\.gif|\.GIF|\.jpg|\.JPG|\.png|\.PNG)$/", $photoext))
                  {
			         $photo = $photoname."[".$pers_id."]".$photoext;
			         move_uploaded_file($newphoto['tmp_name'], IMAGES."personnels/".$photo);
			         chmod(IMAGES."personnels/".$photo,0644);
                     if (!verify_image(IMAGES."personnels/".$photo))
                     {
					    unlink(IMAGES."personnels/".$photo);
					    $photo = "";
				     } /*verify*/
			      } /*preg_match*/ else {$photo="";}
		       } /*is_uploaded*/ 

		   if ($action == "edit")
           {
             if (isset($_POST['del_photo'])) {unlink(IMAGES."personnels/".$photo);$photo="";}
    	  	 $result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_name='$pers_name', personnel_branch='$pers_branch', personnel_email='$pers_email', personnel_web='$pers_web', personnel_photo='$photo', personnel_autobiography='$pers_autobiography' WHERE personnel_id='$pers_id'");
		   } else //Insert
                {
     	          if(!$pers_order) $pers_order=dbresult(dbquery("SELECT MAX(personnel_order) FROM ".$db_prefix."personnel_list"),0)+1;
		          $result = dbquery("UPDATE ".$db_prefix."personnel_list SET personnel_order=personnel_order+1 WHERE personnel_order>='$pers_order'");
          		  $result=dbquery("INSERT INTO ".$db_prefix."personnel_list VALUES('$pers_id', '$pers_order', '$pers_name', '$pers_branch','$pers_email', '$pers_web', '$photo', '$pers_autobiography')");
                }
         }
            redirect(FUSION_SELF);
	   } else
            {
                opentable($locale['pe200']);
                echo "<div align='center'><b>".$locale['pe400']."</b><br>\n<span class='small'>";
                echo $locale['pe401']."</span></div>\n";
                closetable();
                tablebreak();
            }
    } elseif ($action == "edit")
        {
	       $result = dbquery("SELECT * FROM ".$db_prefix."personnel_list WHERE personnel_id='$pers_id'");
	       if (dbrows($result))
           {
		      $data = dbarray($result);
		      $pers_order = $data['personnel_order'];
		      $pers_name = $data['personnel_name'];
		      $pers_branch = $data['personnel_branch'];
		      $pers_email = $data['personnel_email'];
		      $pers_web = $data['personnel_web'];
              $pers_autobiography = $data['personnel_autobiography'];
              $pers_photo = $data['personnel_photo'];
		      $formaction = FUSION_SELF."?action=edit&amp;pers_id=$pers_id";
	       } else
                {
		          $action = "";
		          $formaction = FUSION_SELF;
                }
        } else //Insert
            {
		       $pers_order = "";
		       $pers_name = "";
		       $pers_branch = "";
		       $pers_email = "";
		       $pers_web = "";
               $pers_autobiography = "";
               $pers_photo = "";
               $formaction = FUSION_SELF;
            }

if (isset($new_personnel) || $action == "edit")
{
    opentable($locale['pe200']);
	echo "<form name='inputform' method='post' action=$formaction enctype='multipart/form-data'>
    <table align='center' cellpadding='0' cellspacing='0'>";
echo"<tr>
        <td align='right' class='tbl'>".$locale['pe202']."<span style='color:#ff0000'>*</span></td>
        <td class='tbl'><input type='text' name='pers_name' value='$pers_name' class='textbox' style='width:150px'></td>
    </tr>
    <tr>
        <td align='right' class='tbl'>".$locale['pe203']."<span style='color:#ff0000'>*</span></td>
        <td class='tbl'><input type='text' name='pers_branch' value='$pers_branch' class='textbox' style='width:300px'></td>
    </tr>
    <tr>
        <td align='right' class='tbl'>".$locale['pe204']."</td>
        <td class='tbl'><input type='text' name='pers_email' value='$pers_email' class='textbox' style='width:300px'></td>
    </tr>
    <tr>
        <td align='right' class='tbl'>".$locale['pe205']."</td>
        <td class='tbl'><input type='text' name='pers_web' value='$pers_web' class='textbox' style='width:300px'></td>
    </tr>
    <tr>
        <td align='right' valign='top' class='tbl'>".$locale['pe207']."</td>
        <td class='tbl'><textarea name='pers_autobiography' rows='5' class='textbox' style='width:300px'>$pers_autobiography</textarea></td>
      </tr>";
    if (!$pers_photo)
    {
    echo"
    <tr>
        <td align='right' class='tbl'>".$locale['pe206']."</td>
        <td class='tbl'><input type='file' name='pers_photo' class='textbox' style='width:150px'></td>
    </tr>";
    } else {
            echo "<tr>
                     <td class='tbl'>&nbsp;</td>
                     <td class='tbl'>
                          <img src='".IMAGES."personnels/$pers_photo' width='".$locale['pe306']."' height='".$locale['pe307']."'><br>
                          <input type='checkbox' name='del_photo' value='y'> ".$locale['pe302']."
                      </td>
                 </tr>";
           }
if ($action != "edit")
{
echo"<tr>
        <td align='right' class='tbl'>".$locale['pe201']."</td>
        <td class='tbl'><input type='text' name='pers_order' value='$pers_order' class='textbox' style='width:25px'></td>
    </tr>";
}
echo" <tr>
        <td class='tbl'>&nbsp;</td>
        <td align='center' class='tbl'><br/><input type='submit' name='pers_submit' value='".$locale['pe208']."' class='button'></td>
      </tr>
    </table>
    </form>\n";
	closetable();
	tablebreak();
} //Form
} //iADMIN

opentable($locale['pe300']);
$result = dbquery("SELECT * FROM ".$db_prefix."personnel_list");
$rows = dbrows($result);
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
if ($rows != 0)
{
	if (iADMIN) {echo "<center>[<a href='".FUSION_SELF."?new_personnel=1'>".$locale['pe200']."</a>] |
                               [<a href='".FUSION_SELF."?action=refresh'>".$locale['pe305']."</a>]</center><br>";}
	tablebreak();
	$i = 1; 
	$result = dbquery("SELECT * FROM ".$db_prefix."personnel_list ORDER BY personnel_order LIMIT $rowstart,10");
	$numrows = dbrows($result);
	while ($data = dbarray($result))
    {
		echo "<table align='center' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>
              <tr>
                  <td class='tbl2' colspan='2'>
                    <table cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                        <td class='small'><b><span class='comment-name'>".$data['personnel_name']."</span> | ".$data['personnel_branch']."</b></td>
                        <td class='small' align='right'>";
                          if ($data['personnel_email']<>"")
                          {echo "<a href='mailto:".$data['personnel_email']."'><img src='".THEME."forum/email.gif' style='border:0px;'></a>";}
                          if ($data['personnel_web']<>"")
                          {echo "&nbsp;<a href='http://".$data['personnel_web']."'><img src='".THEME."forum/web.gif' style='border:0px;'></a>";}
           echo "       </td>
		            </tr>
                    </table>
                  </td>
              </tr>
              <tr>
                  <td class='tbl1' valign='top' width='".$locale['pe306']."' height='".$locale['pe307']."'>";
                    if ($data['personnel_photo']<>"")
                    {
                      echo "<img width='".$locale['pe306']."' height='".$locale['pe307']."' src='".IMAGES."personnels/".$data['personnel_photo']."'/>";
                    }
           echo " </td>
                  <td class='tbl1' valign='top'>
                    <table cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                       <td class='small'>".nl2br($data['personnel_autobiography'])."</td>
                    </tr>
                    </table>
                  </td>
              </tr>";
   if (iADMIN)
          {
        echo "<tr>
                  <td class='tbl2' colspan='2'>
                    <table cellpadding='0' cellspacing='0' width='100%'>
                    <tr>
                       <td align='right' class='small'>".$locale['pe201']."&nbsp;".$data['personnel_order']."&nbsp;";
                       //UP-DOWN
                        if ($rows != 1)
                        {
 				          $up = $data['personnel_order'] - 1;
				          $down = $data['personnel_order'] + 1;
				          if ($data['personnel_order'] == 1) {
					      echo "<a href='".FUSION_SELF."?action=movedown&amp;order=$down&amp;pers_id=".$data['personnel_id']."'><img src='".THEME."images/down.gif' alt='".$locale['pe303']."' title='".$locale['pe303']."' style='border:0px;'></a>&nbsp;";
				          } elseif ($data['personnel_order'] < $rows) {
					      echo "<a href='".FUSION_SELF."?action=moveup&amp;order=$up&amp;pers_id=".$data['personnel_id']."'><img src='".THEME."images/up.gif' alt='".$locale['pe304']."' title='".$locale['pe304']."' style='border:0px;'></a>&nbsp;";
					      echo "<a href='".FUSION_SELF."?action=movedown&amp;order=$down&amp;pers_id=".$data['personnel_id']."'><img src='".THEME."images/down.gif' alt='".$locale['pe303']."' title='".$locale['pe303']."' style='border:0px;'></a>&nbsp;";
				          } else {
					      echo "<a href='".FUSION_SELF."?action=moveup&amp;order=$up&amp;pers_id=".$data['personnel_id']."'><img src='".THEME."images/up.gif' alt='".$locale['pe304']."' title='".$locale['pe304']."' style='border:0px;'></a>&nbsp;";
				          }
                        } //IF
                       //UP-DOWN
                  echo"  [<a href='".FUSION_SELF."?action=edit&amp;pers_id=".$data['personnel_id']."'>".$locale['pe301']."</a>] |
                         [<a href='".FUSION_SELF."?action=delete&amp;pers_id=".$data['personnel_id']."' onClick='return DeleteMessage();'>".$locale['pe302']."</a>]
                       </td>
                    </tr>
                    </table>
                  </td>
              </tr>";
           } //iADMIN
      echo "</table>\n";
              if ($i != $numrows) echo "<br>\n";
		      $i++;
	}
} else {
	       echo "<center><br>\n".$locale['pe308']."<br><br>\n";
                 if (iADMIN)
                 {
                  echo "[ <a href='".FUSION_SELF."?new_personnel=1'>".$locale['pe200']."</a> ] <br><br>\n";
                 }
                echo "</center>\n";
       }
closetable();

if ($rows != 0) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?")."\n</div>\n";

echo "<script>
function DeleteMessage()
{
	return confirm(\"".$locale['pe309']."\");
}
</script>\n";

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>
