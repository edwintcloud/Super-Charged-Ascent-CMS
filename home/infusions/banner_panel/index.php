<?
error_reporting(0);
include ('header.php');
global $op;

function bannerstats() {
global $db_prefix, $locale, $userdata, $settings;
  
// ID der Custom Page wo sich die Information der Buchung befinden
$pageid = 3;  
  
$count = dbrows(dbquery("select bid, imptotal, impmade, clicks, date from ".$db_prefix."banner  WHERE status='1' AND cid='$userdata[user_id]'"));

    
    if($userdata['user_name']=="") 
	{
	echo $locale['BRS176'];
    } 
	elseif ($count==0)
		{
			echo '<div align="center"><p>&nbsp;</p><strong>'.$userdata['user_name'].', '.$locale['BRS173'].' '.$settings['sitename'].'.</strong><br><br>Bitte besuchen Sie folgende Informationsseite, um Banner auf '.$settings['sitename'].' zu buchen<br><br><img src="'.THEME.'images/bullet.gif"> <a href="'.BASEDIR.'viewpage.php?page_id='.$pageid.'">Zur Informationsseite</a> <img src="'.THEME.'images/bulletb.gif"><p>&nbsp;</p></div>';
		}
	else
	{
	if ($userdata['user_name']||$count!=0) 
	{
    
    echo"
      <center>
    <table class='tbl' border='0' width='100%'>
	<tr>
	<td>
    <table class='tbl' border='0' width='100%'>
	<tr>
	<td align='center'><b>".$locale['BRS132']." ".$userdata['user_name']."</b></center><br /><br>
    <table width='100%' class='tbl-border' cellspacing='1'>
	<tr>
    <td class='tbl2' align='center'><b>".$locale['BRS133']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS134']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS135']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS136']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS137']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS138']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS139']."</b></td>
	<tr class='tbl1' align='center'>";
    $aquery = dbquery("select * from ".$db_prefix."banner WHERE status='1' AND cid='$userdata[user_id]'");
    while($adata = dbarray($aquery)) {
        if($adata['impmade']==0) {
    	    $percent = 0;
        } else {
    	    $percent = substr(100 * $adata['clicks'] / $adata['impmade'], 0, 5);
        }
		

        if($adata['imptotal']==0) {
    	    $left = "Unlimited";
        } else {
    	    $left = $adata['imptotal']-$adata['impmade'];
        }
        echo '
        <td align="center" class="tbl1">'.$adata['bid'].'</td>
        <td align="center" class="tbl1">'.$adata['impmade'].'</td>
        <td align="center" class="tbl1">'.$adata['imptotal'].'</td>
		    <td align="center" class="tbl1">'.$left.'</td>
        <td align="center" class="tbl1">'.$adata['clicks'].'</td>
        <td align="center" class="tbl1">'.$percent.'%</td>
        <td align="center" class="tbl1"><a href="'.INFUSIONS.'banner_panel/index.php?op=EmailStats&bid='.$adata['bid'].'">'.$locale['BRS140'].'</a></td><tr>';
    }
    echo '</td>
	</tr></table><br><br>
    <center><table class="tbl-border" cellspacing="1" width="100%"><tr><td class="tbl2" align="center">
    <b>'.$locale['BRS101'].'</b>';

    $bquery = dbquery("select * from ".$db_prefix."banner where cid='$userdata[user_id]'");
    while($bdata = dbarray($bquery)) {

	$numrows = dbrows($bquery);
	if ($numrows>1) 
	{
echo "";
	}

	echo '</td></tr><tr><td class="tbl1" align="center"><b>'.$locale['BRS104'].': '.$bdata['bid'].'</b><br><img src="'.$bdata['imageurl'].'" border="1"><br /><br>
	
	<a href="'.INFUSIONS.'banner_panel/index.php?op=EmailStats&bid='.$bdata['bid'].'">'.$locale['BRS102'].'</a>
	<br /><br>
	'.$locale['BRS103'].' <a href="'.$bdata['clickurl'].'">'.$bdata['clickurl'].'</a>
	<br />
	<form action="'.INFUSIONS.'banner_panel/index.php" method="submit">
	URL : <input class="textbox" type="text" name="url" size="50" maxlength="200" value="'.$bdata['clickurl'].'">
	<input type="hidden" name="bid" value="'.$bdata['bid'].'">
	<input type="hidden" name="cid" value="'.$userdata['user_id'].'">
	<input type="hidden" name="op" value="Change">
	<input class="button" type="submit" name="submit" value="'.$locale['BRS105'].'">
	</form>';
    }
    
    echo "</td></tr></table>";
    echo "
    </td></tr></table></td></tr></table>
    </font>";


// Finnished Banners


    echo "
    <center>
    <table border='0' width='100%' class='tbl'><tr><td>
    <table border='0' width='100%' cellpadding='8' cellspacing='1'><tr>
	<td><center><b>".$locale['BRS106']." ".$userdata['user_name']."</b></center><br />
    <table width='100%' border='0' class='tbl-border' cellspacing='1'><tr>
    <td class='tbl2' align='center'><b>".$locale['BRS133']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS107']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS137']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS138']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS108']."</b></td>
    <td class='tbl2' align='center'><b>".$locale['BRS109']."</b></td><tr class='tbl1'>";
    $fquery = dbquery("select * from ".$db_prefix."banner WHERE status='0' AND cid='$userdata[user_id]'");
    while($fdata = dbarray($fquery)) {
        $percent = substr(100 * $fdata['clicks'] / $fdata['impmade'], 0, 5);
        
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
        <td align='center' class='tbl1'>$fdata[bid]</td>
        <td align='center' class='tbl1'>$fdata[impmade]</td>
        <td align='center' class='tbl1'>$fdata[clicks]</td>
	      <td align='center' class='tbl1'>$percent%</td>
        <td align='center' class='tbl1'>$neustartdate</td>
        <td align='center' class='tbl1'>$neuenddate</td><tr>";
    }
echo '
</td></tr></table></td></tr></table></td></tr></table>';

    } else {
    
echo $locale['BRS176'];
    }
    }

}

//Function to let the client E-mail his banner Stats

function EmailStats($bid) {
global $db_prefix, $locale, $userdata, $bid, $settings;

    if ($userdata['user_email']=="") {
	echo "
	<center><br>
	<b>".$locale['BRS110']. $bid .$locale['BRS111']. $userdata['user_name']."<br />
	".$locale['BRS112']."<br /><br /></b>
	<a href=\"javascript:history.go(-1)\">".$locale['BRS113']."</a><br></td></tr></table>
	";
    } 
	else 
	{
	$result = dbquery("select bid, imptotal, impmade, clicks, imageurl, clickurl, date from ".$db_prefix."banner where bid='$bid' and cid='$userdata[user_id]'");
	list($bid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = dbarraynum($result);
        if($impmade==0) {
    	    $percent = 0;
        } else {
    	    $percent = substr(100 * $clicks / $impmade, 0, 5);
        }

        if($imptotal==0) {
    	    $left = "Unlimited";
	    $imptotal = "Unlimited";
        } else {
    	    $left = $imptotal-$impmade;
        }
	$fecha = date("F jS Y, h:iA.");
	$subject = $locale['BRS114'] . $settings['sitename'];
	$message = $locale['BRS115'] . $settings['sitename']." \n<br><br></b>\n\n <b>".$locale['BRS116']." : </b>".$userdata['user_name']."<br>\n<b>".$locale['BRS104']." : </b>".$bid."\n<br><b>".$locale['BRS117']." : </b>".$imageurl."\n<br><b>".$locale['BRS118']." : </b>".$clickurl."\n\n<br><b>".$locale['BRS119']." : </b>".$imptotal."\n<br><b>".$locale['BRS120']." : </b>".$impmade."\n<br><b>".$locale['BRS121']." : </b>".$left."\n<br><b>".$locale['BRS122']." : </b>".$clicks."\n<br><b>".$locale['BRS123']." : </b>".$percent."%\n<br>\n<b>".$locale['BRS124']." : </b>".$fecha;
	$from = $settings['sitename'];
	
	sendemail($userdata['user_name'],$userdata['user_email'],$settings['siteusername'],$settings['siteemail'],$subject,$message,'','','');

	echo "<center><br><b>".$locale['BRS110']. $bid .$locale['BRS125']." ".$userdata['user_name']." (".$userdata['user_email'].")<br /><br /></b>
	<a href=\"".INFUSIONS."banner_panel/index.php\">".$locale['BRS113']."</a><br><br>
	";
    }
}




//Function to let the client to change the url for his banner

function change_banner_url_by_client($bid, $url) {
global $db_prefix, $locale, $userdata;

    dbquery("update ".$db_prefix."banner SET clickurl='$url' WHERE bid='$bid'");
    echo "<center><br />".$locale['BRS126']."<br /><br /><a href=\"".INFUSIONS."banner_panel/index.php\">".$locale['BRS113']."</a></center>";
}
switch($op) {

   case "Ok":
	opentable($locale['BRS100']);
	bannerstats();
	closetable();
	break;

    case "Change":
	opentable($locale['BRS100']." ".$locale['BRS163']);
	change_banner_url_by_client($bid, $url);
	closetable();
	break;

    case "EmailStats":
	opentable($locale['BRS100']." ".$locale['BRS139']);
	EmailStats($bid);
	closetable();
	break;
	
    default:
	opentable($locale['BRS100']);
	bannerstats();
	closetable();
	break;
}



include ('footer.php');
?>
