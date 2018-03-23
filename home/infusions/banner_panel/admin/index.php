<?PHP
error_reporting (0);
require_once "../../../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
if (!checkrights("IP")) fallback("../index.php");

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."banner_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."banner_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."banner_panel/locale/English.php";
}
global $op;


?>
<script type="text/JavaScript">
function smartOptionFinder(oSelect, oEvent) {
	var sKeyCode = oEvent.keyCode;
	var sToChar = String.fromCharCode(sKeyCode);
	if(sKeyCode >47 && sKeyCode<91){
		var sNow = new Date().getTime();
		if (oSelect.getAttribute("finder") == null) {
			oSelect.setAttribute("finder", sToChar.toUpperCase())
			oSelect.setAttribute("timer", sNow)
		} else if( sNow > parseInt(oSelect.getAttribute("timer"))+2000) { //Rest all;
			oSelect.setAttribute("finder", sToChar.toUpperCase())
			oSelect.setAttribute("timer", sNow) //reset timer;
		} else {
			oSelect.setAttribute("finder", oSelect.getAttribute("finder")+sToChar.toUpperCase())
			oSelect.setAttribute("timer", sNow); //update timer;
		}
		var sFinder =  oSelect.getAttribute("finder");
		var arrOpt = oSelect.options
		var iLen = arrOpt.length
		for (var i = 0; i < iLen ; i++) {
			sTest  = arrOpt[i].text;
			if (sTest.toUpperCase().indexOf(sFinder) == 0) {
				arrOpt[i].selected = true;
				break;
			}
		}
		event.returnValue = false;
	} else{
		//Not a digit;
	}
}
</script>
<?





function BannersAdmin()
{
global $db_prefix,$locale;
	
	
	// Banners List
opentable($locale['BRS141']);
	//echo "";
	echo "
	<table width='100%' border='0' class='tbl-border' cellspacing='1'>
	<tr>
		<th class='tbl2'>".$locale['BRS133']."</th>
		<th class='tbl2'>".$locale['BRS107']."</th>
		<th class='tbl2'>".$locale['BRS136']."</th>
		<th class='tbl2'>".$locale['BRS137']."</th>
		<th class='tbl2'>".$locale['BRS138']."</th>
		<th class='tbl2'>".$locale['BRS116']."</th>
		<th class='tbl2'>".$locale['BRS142']."</th>
	</tr>";
	
	
	$result = dbquery("select * from ".$db_prefix."banner WHERE status='1' order by bid");
	
	while ($data = dbarray($result)) 
	{
			$result2 = dbquery("select user_id, user_name from ".$db_prefix."users where user_id=$data[cid]");
			$data2 = dbarray($result2);
			
		//percentages
		if($data['impmade']==0)
			{
				$percent = 0;
			}
		else
			{
				$percent = substr(100 * $data['clicks'] / $data['impmade'], 0, 5);
			}//if($data['impmade']==0)
			
		//remaining
		if($data['imptotal']==0)
			{
				$left = "Unlimited";
		    }
		else
			{
				$left = $data['imptotal']-$data['impmade'];
		    }//if($data['imptotal']==0)
			
			
		echo "
		<tr align='center'>
			<td class='tbl1'>$data[bid]</td>
			<td class='tbl1'>$data[impmade]</td>
			<td class='tbl1'>$left</td>
			<td class='tbl1'>$data[clicks]</td>
			<td class='tbl1'>$percent%</td>
			<td class='tbl1'>$data2[user_name]</td>
			<td class='tbl1'><a href='".$_SERVER['PHP_SELF']."?op=BannerEdit&amp;editbid=$data[bid]'>".$locale['BRS143']."</a>
				&nbsp;|&nbsp;
				<a href='".$_SERVER['PHP_SELF']."?op=BannerDelete&bid=$data[bid]&amp;ok=0'>".$locale['BRS144']."</a></td>
		</tr>";
	}//while ($data = dbarray($result))

	echo "
	</table>";
	closetable();
	

	tablebreak();
	
	// Finished Banners List
opentable($locale['BRS145']);
	
	echo "
	<table width='100%' border='0' class='tbl-border' cellspacing='1'>
	<tr>
		<th class='tbl2'>".$locale['BRS133']."</th>
		<th class='tbl2'>".$locale['BRS107']."</th>
		<th class='tbl2'>".$locale['BRS137']."</th>
		<th class='tbl2'>".$locale['BRS138']."</th>
		<th class='tbl2'>".$locale['BRS108']."</th>
		<th class='tbl2'>".$locale['BRS109']."</th>
		<th class='tbl2'>".$locale['BRS116']."</th>
		<th class='tbl2'>".$locale['BRS142']."</th>
	</tr>";

	$result = dbquery("select * from ".$db_prefix."banner WHERE status='0' order by bid");

	while ($fdata = dbarray($result)) 
	{
			$result2 = dbquery("select user_id, user_name from ".$db_prefix."users where user_id=".$fdata[cid]);
			$data2 = dbarray($result2);

		$percent = substr(100 * $fdata[clicks] / $fdata[impmade], 0, 5);

		$tag = substr("".$fdata[enddate]."", 8, 2);
		$monat = substr("".$fdata[enddate]."", 5, 2); 
		$jahr = substr("".$fdata[enddate]."", 0, 4); 
		
	  $stunde = substr("".$fdata[enddate]."", 11, 2);
		$minute = substr("".$fdata[enddate]."", 14, 2); 
		$sekunde = substr("".$fdata[enddate]."", 17, 2); 
		
		
		$neuenddate =" $tag.$monat.$jahr, $stunde:$minute:$sekunde Uhr, ";
		
		$tag = substr("".$fdata[date]."", 8, 2);
		$monat = substr("".$fdata[date]."", 5, 2); 
		$jahr = substr("".$fdata[date]."", 0, 4); 
		
	  $stunde = substr("".$fdata[date]."", 11, 2);
		$minute = substr("".$fdata[date]."", 14, 2); 
		$sekunde = substr("".$fdata[date]."", 17, 2); 
		
		$neustartdate =" $tag.$monat.$jahr, $stunde:$minute:$sekunde Uhr, ";
		
		echo "
		<tr align='center'>
			<td class='tbl1'>$fdata[bid]</td>
			<td class='tbl1'>$fdata[impmade]</td>
			<td class='tbl1'>$fdata[clicks]</td>
			<td class='tbl1'>$percent %</td>
			<td class='tbl1'>$neustartdate</td>
			<td class='tbl1'>$neuenddate</td>
			<td class='tbl1'>$data2[user_name]</td>
			<td class='tbl1'><a href='".$_SERVER['PHP_SELF']."?op=BannerEdit&amp;editbid=$fdata[bid]'>".$locale['BRS143']."</a>
				&nbsp;|&nbsp;<a href='".$_SERVER[PHP_SELF]."?op=BannerDelete&bid=$fdata[bid]&amp;ok=0'>".$locale['BRS144']."</a></td>
		</tr>";
	}//while ($data = dbarray($result))
	echo "</table>";
	closetable();
	
	
tablebreak();

	// Clients List
opentable($locale['BRS146']);
	
	echo "
	<table width='100%' class='tbl-border' cellspacing='1'>
	<tr>
		<th class='tbl2'>".$locale['BRS133']."</th>
		<th class='tbl2'>".$locale['BRS116']."</th>
		<th class='tbl2'>".$locale['BRS148']."</th>
		<th class='tbl2'>".$locale['BRS149']."</th>
		<th class='tbl2'>".$locale['BRS142']."</th>
	</tr>";

	$result = dbquery("select DISTINCT cid from ".$db_prefix."banner");
	$bcount=dbrows($result);
while ($data = dbarray($result))
	{
		$result2 = dbquery("select user_id , user_name, user_email from ".$db_prefix."users where user_id='$data[cid]'  order by user_id");
		$data2 = dbarray($result2);
		$result3 = dbquery("select cid from ".$db_prefix."banner where cid='$data[cid]' and status='1'");
		$numrows = dbrows($result3);
		
		echo "
		<tr align='center'>
			<td class='tbl1'>$data2[user_id]</td>
			<td class='tbl1'>$data2[user_name]</td>
			<td class='tbl1'>$numrows</td>
			<td class='tbl1'>$data2[user_email]</td>
			<td class='tbl1'>
				<a href='".$_SERVER['PHP_SELF']."?op=BannerClientDelete&amp;cid=$data2[user_id]'>".$locale['BRS144']." ".$locale['BRS172']." </a></td>
		</tr>";
	}

	echo "
	</table>";
	closetable();
	
	
tablebreak();

	// Add Banner

	$result = dbquery("select * from ".$db_prefix."users");
	$numrows = dbrows($result);

	if($numrows>0)
	{
	opentable($locale['BRS150']);
		
		echo '
		<form action="'.$_SERVER['PHP_SELF'].'?op=BannersAdd" method="post">
		<table class="tbl" align="center">
		<tr>
			<td align="right"><strong>'.$locale['BRS116'].':</strong></td>
			
			<td>
			<select class="textbox" name="cid" onkeydown="smartOptionFinder(this,event)">
				';

				$result = dbquery("select user_id, user_name from ".$db_prefix."users");
				while(list($cid, $name) = dbarraynum($result))
				{
					echo '<option value="'.$cid.'">'.$name.'</option>';
				}

				echo '
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><strong>'.$locale['BRS119'].':</strong></td>
			<td><input class="textbox" type="text" name="imptotal" size="12" maxlength="11" />'.$locale['BRS151'].'</td>
		</tr>
		<tr>
			<td align="right"><strong>'.$locale['BRS152'].':</strong></td>
			<td><input class="textbox" type="text" name="imageurl" size="60" maxlength="200" /></td>
		</tr>
		<tr>
			<td align="right"><strong>'.$locale['BRS153'].':</strong></td>
			<td><input class="textbox" type="text" name="clickurl" size="60" maxlength="200" /></td>
		</tr>
		<tr>
			<td align="right"><strong>Active:</strong></td>
			<td><input class="textbox" type="radio" name="active" value="1" checked>Active&nbsp;&nbsp;<input class="textbox" type="radio" name="active" value="0" checked>Inactive</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="hidden" name="op" value="BannersAdd" /><input class="button" type="submit" value="'.$locale['BRS154'].'" /></td></tr>
		</table>
		</form>';
closetable();
		
	}
	
tablebreak();

}






function BannersAdd()
{
global $db_prefix,$locale;

dbquery("INSERT INTO ".$db_prefix."banner ( bid , cid , imptotal , impmade , clicks , imageurl , clickurl , date, enddate, status)VALUES ('', '".$_POST[cid]."', '".$_POST[imptotal]."', '0', '0', '".$_POST[imageurl]."', '".$_POST[clickurl]."', NOW( ),NOW( ),'".$_POST[active]."');");

	Header("Location: ".$_SERVER[PHP_SELF]."?op=BannersAdmin");
}









//function no longer used
/*
function BannerFinishDelete($dfbid)
{
global $db_prefix,$locale,$dfbid;
		dbquery("DELETE FROM `".$db_prefix."bannerfinish` WHERE `bid` = ".$dfbid." LIMIT 1;");
	Header("Location: ".$_SERVER[PHP_SELF]."?op=BannersAdmin");

}
*/










function BannerDelete($bid, $ok=0)
{
global $db_prefix,$locale;
	if ($ok==1)
	{
		dbquery("delete from ".$db_prefix."banner where bid='$bid'");
		Header("Location: ".$_SERVER[PHP_SELF]."?op=BannersAdmin");
	}
	else
	{
		

		$result=dbquery("select cid, imptotal, impmade, clicks, imageurl, clickurl from ".$db_prefix."banner where bid=$bid");
		$data = dbarray($result);
		echo "
 		<table width='100%' class='tbl-border' cellspacing='1' align='center'>
		<tr><td colspan='6' align='center' class='tbl1'><a href='$data[clickurl]'><img src='$data[imageurl]' border='1' /></a></td></tr>
		<tr><td colspan='6' align='center' class='tbl1'><a href='$data[clickurl]'>$data[clickurl]</a></td></tr>
		<tr>
		<td class='tbl1'>
		<table width='100%' class='tbl-border' cellspacing='1' align='center'>
		<tr>
			<td align='center' class='tbl2'><strong>".$locale['BRS133']."</strong></td>
			<td align='center'class='tbl2'><strong>".$locale['BRS107']."</strong></td>
			<td align='center'class='tbl2'><strong>".$locale['BRS136']."</strong></td>
			<td align='center'class='tbl2'><strong>".$locale['BRS137']."</strong></td>
			<td align='center'class='tbl2'><strong>".$locale['BRS138']."</strong></td>
			<td align='center'class='tbl2'><strong>".$locale['BRS116']."</strong></td>
		</tr>";

		$result5 = dbquery("select user_id, user_name from ".$db_prefix."users where user_id=$data[cid]");
			$data5 = dbarray($result5);
		$percent = substr(100 * $clicks / $data[impmade], 0, 5);

		if($data[imptotal]==0)
		{
			$left = unlimited;
		}
		else
		{
			$left = $data[imptotal]-$data[impmade];
		}

		echo "

		<tr align='center'>
			<td class='tbl1'>$bid</td>
			<td class='tbl1'>$data[impmade]</td>
			<td class='tbl1'>$left</td>
			<td class='tbl1'>$data[clicks]</td>
			<td class='tbl1'>$percent%</td>
			<td class='tbl1'>$data5[user_name]</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
			<td colspan='6' align='center' class='tbl1'>
				".$locale['BRS159']."<br />
				<a href='".$_SERVER[PHP_SELF]."?op=BannerDelete&amp;bid=$bid&amp;ok=1'><b>".$locale['BRS160']."</b></a>
				&nbsp;|&nbsp;
				<a href='".$_SERVER[PHP_SELF]."?op=BannersAdmin'><b>".$locale['BRS161']."</b></a>
			</td>
		</tr>
		</table>";

		
	}
}













function BannerEdit($editbid)
{
global $db_prefix,$locale,$editbid;
	
	$editdata = dbarray(dbquery("SELECT * FROM ".$db_prefix."banner WHERE bid=$editbid"));

	echo '
	<form action="'.$_SERVER['PHP_SELF'].'?op=BannerChange" method="post">
	<table align="center">
	<tr><td colspan="2" align="center"><img src="'.$editdata['imageurl'].'" border="1" /><br><br></td>
	</tr>
	<tr>
		<td align="right"><strong>'.$locale['BRS116'].':</strong></td>
		<td><select class="textbox" name="cid">';
			//make a members list pulldown
$result2 = dbquery("select * from ".$db_prefix."users where user_id=$editdata[cid]");
	$data2 = dbarray($result2);
			//insert current client first
			echo '
				<option value="'.$data2['user_id'].'" selected="selected">'.$data2['user_name'].'</option>';
			//now collect the rest without current client
		$result3 = dbquery("select user_id, user_name from ".$db_prefix."users");
		while ($data3 = dbarray($result3))
			{
				if($data2[user_id]!=$data3[user_id])
				{
					echo '
						<option value="'.$data3['user_id'].'">'.$data3['user_name'].'</option>';
				}
			}

			echo '</select>
		</td>
	</tr>';

	if($editdata['imptotal']==0)
	{
		$impressions = "Unlimited";
	}
	else
	{
		$impressions = $editdata['imptotal'];
	}

	echo '
	<tr>
	<td align="right"><strong>'.$locale['BRS119'].':</strong></td>
	<td>'.$impressions.'</td>
	</tr>
	<tr>
	<td align="right"><strong>'.$locale['BRS120'].':</strong></td>
	<td>'.$editdata['impmade'].'</td>
</tr>
<tr>
	<td align="right"><strong>'.$locale['BRS162'].':</strong></td>
	<td>'.$impressions.' 
	<select class="textbox" name="change" size="1">
	<option value="+" SELECTED>'.$locale['BRS178'].'</option>
	<option value="-">'.$locale['BRS179'].'</option>
</select>
	<input class="textbox" type="text" name="impadded" size="5" value=""/></td>
</tr>
<tr>
	<td align="right"><strong>'.$locale['BRS152'].':</strong></td>
	<td><input class="textbox" type="text" name="imageurl" size="60" maxlength="200" value="'.$editdata['imageurl'].'" /></td>
</tr>
<tr>
	<td align="right"><strong>'.$locale['BRS153'].':</strong></td>
	<td><input class="textbox" type="text" name="clickurl" size="60" maxlength="200" value="'.$editdata['clickurl'].'" /></td>
</tr>
		<tr>
			<td align="right"><strong>Banner Status:</strong></td>
			<td><select class="textbox" name="status" size="1">
				<option value="'.$editdata['status'].'" SELECTED>Current Status</option>
				<option value="" disabled></option>
				<option value="0">Inactive</option>
				<option value="1">Active</option>
				</select></td>
		</tr>
<tr>
		<td align="center" colspan="2">
			<input type="hidden" name="changebid" value="'.$editdata['bid'].'" />
			<input type="hidden" name="imptotal" value="'.$editdata['imptotal'].'" />
			<input class="button" type="submit" value="'.$locale['BRS163'].'['.$editdata['bid'].']" />
		</td>
</tr>
	</table>
	</form>';

	
}











function BannerChange()
{
global $db_prefix,$locale;
//we need to decide if the total needs updateing
		if ($_POST[impadded]==''||!$_POST[impadded]||$_POST[impadded]=='0')
		{//make the database entry
		dbquery("UPDATE ".$db_prefix."banner SET cid = '$_POST[cid]', imageurl = '$_POST[imageurl]',clickurl = '$_POST[clickurl]',status = '$_POST[status]', enddate=NOW( ) WHERE bid =$_POST[changebid] LIMIT 1 ;");
		
		echo '<p align="center"><br><strong>Banner '.$_POST[changebid].' geändert</strong><br><br>
				Klicks : '.$_POST[imptotal].'<br><br>
				URL :'.$_POST[clickurl].'<br><br>
				Banner URL : '.$_POST[imageurl].'<br><br>
				<a href="'.$_SERVER[PHP_SELF].'">Zurück zur Administration</a></p><br>';
		
		}
		else
		{
		//work it out for the database entry
		$total = $_POST[imptotal].$_POST[change].$_POST[impadded];
		//make the database entry
				
		dbquery("UPDATE ".$db_prefix."banner SET cid = '$_POST[cid]', imptotal=$total, imageurl = '$_POST[imageurl]',clickurl = '$_POST[clickurl]',status = '$_POST[status]', enddate=NOW( ) WHERE bid =$_POST[changebid] LIMIT 1 ;");
		
		echo '<p align="center"><strong>Banner '.$_POST[changebid].' Updated</strong><br>
				Impressions Purchased: '.$total.'<br>
				Click Url :'.$_POST[clickurl].'<br>
				Banner Location: '.$_POST[imageurl].'<br>
				<a href="'.$_SERVER[PHP_SELF].'">Return to banner administration</a></p>';
		}//if total changed


}//BannerChange()














function BannerClientDelete($cid, $ok=0)
{
global $db_prefix, $locale, $settings;
	if ($cid AND $ok==1)
	{
		$result = dbquery("delete from ".$db_prefix."banner where cid='$cid'");
				
		Header("Location: ".$_SERVER[PHP_SELF]."");
	}
	else
	{
		$result = dbquery("select * from ".$db_prefix."users where user_id=$cid");
		$data = dbarray($result);

		echo "	<table width='100%'>
		<tr><td>".$locale['BRS164'].": <b>$data[user_name]</b>, ".$locale['BRS165']."</td></tr>
		<tr><td>";
		$result3 = dbquery("select imageurl, clickurl from ".$db_prefix."banner where cid=$cid");
		
		if (dbrows($result3) < '1')
		{
			echo $locale['BRS166']."<br /><br />";
		}
		else
		{
			echo "<span><font color='red'><strong>".$locale['BRS167']."</strong></font></span><br />
			".$locale['BRS168']." $settings[sitename]<br /><br />
			";
		}//if (dbrows($result3) < '1')

		while ($data3 = dbarray($result3))
		{
			echo "
			<a href='$data3[clickurl]' target='_blank'><img src='$data3[imageurl]' border='1' /></a><br /><br />
			<a href='$data3[clickurl]' target='_blank'>$data3[clickurl]</a><br /><br />
			";
		}//while ($data3 = dbarray($result3))
	
		echo "
		</td></tr>
		<tr>
			<td>
				".$locale['BRS169']."<br /><br />
				<a href='".$_SERVER[PHP_SELF]."'>No</a>
				&nbsp;|&nbsp;
				<a href='".$_SERVER[PHP_SELF]."?op=BannerClientDelete&amp;cid=$cid&amp;ok=1'>".$locale['BRS160']."</a>
			</td>
		</tr>
		</table>";
	}
}

switch($op) {

    case "BannersAdd":
	opentable('BannersAdd');
	BannersAdd();
	closetable();
	break;

	case "BannerEdit":
	opentable('Banner Edit');
	BannerEdit($bid);
	closetable();
	break;
	    
    case "BannerClientDelete":
	opentable('Client Delete');
	BannerClientDelete($cid, $ok);
	closetable();
	break;

    case "BannerChange":
	opentable('Banner Change');
	BannerChange();
	closetable();
	break;
	
	case "BannerFinishDelete":
	opentable('Banner Delete');
	BannerFinishDelete($dfbid);
	closetable();
	break;
	
	case "BannerEdit":
	opentable('Banner Edit');
	BannerEdit($editbid);
	closetable();
	break;
	
	case "BannerDelete":
	opentable('Banner Delete');
	BannerDelete($bid, $ok);
	closetable();
	break;
	
	default:
  BannersAdmin();
	break;
}


$version='2.0.4';
	//Start Copywrite Link removal is NOT ALLOWED.
	echo '<table align="right" frame="box" rules="none" style="cursor:pointer;" border="1" bordercolor="#0099cc" width="80" height="15" cellspacing="0" cellpadding="0" onClick="self.location=\'http://www.ausimods.net\'" title="Banner System '.$version.' By AusiMods http://www.ausimods.net"><tr><td bgcolor="#cccccc">&nbsp;<font color="#660000" size="1">Banners</font>&nbsp;</td><td bgcolor="#999999">&nbsp;<font color="#ffffcc" size="1">'.$version.'</font>&nbsp;</td></tr></table>';
	//END Copywrite Link removal is NOT ALLOWED.
echo "</td>\n";
require_once BASEDIR."footer.php";
?>
