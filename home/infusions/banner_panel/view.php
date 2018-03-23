<?php
//Be sure to alter this to suit your site
//if you uncomment this code the banners will be this size in all areas top,center,side

if (!isset($bannerheight)){$bannerheight='60';}
if (!isset($bannerwidth)){$bannerwidth='468';}

global $db_prefix, $userdata, $locale;
$bresult = dbquery("select * from ".$db_prefix."banner WHERE status='1'");
$gotbanners = dbrows($bresult);
	if($gotbanners > "0")
	{
	$numrows = dbrows($bresult);
	//Randomize
		if ($numrows > 1)
		{
			$numrows = $numrows-1;
			mt_srand((double)microtime()*1000000);
			$bannum = mt_rand(0, $numrows);
		}
		else 
		{
		$bannum = 0;
		}
	$bresult2 = dbquery("select * from ".$db_prefix."banner WHERE status='1' limit $bannum,1");
	$banner = dbarray($bresult2);
	if($numrows > 0)
	{
$banner_display = '<a target="_banner" href="'.INFUSIONS.'banner_panel/click.php?bid='.$banner['bid'].'"><img src="'.$banner['imageurl'].'" border="1" height="'.$bannerheight.'" width="'.$bannerwidth.'"></a>';


			//check ownership user to client id
			if($banner['cid']!=$userdata['user_id'])
			{
			 	//add the impression
				dbquery("update ".$db_prefix."banner set impmade=impmade+1 where bid=$banner[bid]");
			}//if($cid==$userdata['user_id'])

			//check if it should be ended
			//add a date and set status to 0
			
			if($banner['impmade'] >= $banner['imptotal'] && $banner['imptotal']!='0')
			{
				dbquery("UPDATE ".$db_prefix."banner SET enddate=NOW( ) , status = '0' WHERE bid =$banner[bid] LIMIT 1 ;");
			}//if($imptotal>$impmade)

	}//if($numrows > 0)
}//if($bresult != "")
?>
