<?
require_once "../../maincore.php";
global $bid,$link, $locale;

 $result = dbquery("select * from ".$db_prefix."banner where bid = $bid");
 $numrows = dbrows($result);
 if ($numrows > 0)
		{
 $row = dbarray($result);
 $link = $row['clickurl'];
 //check that the url has http:
 if(strtolower(substr($link,0,5)) != "http:")
 {
 //if not add it
  $link = "http://" . $link;
  //exit;
 } 
 //update the hit
dbquery("update ".$db_prefix."banner set clicks=clicks+1 where bid=$bid");
//go there
 header("Location: ".$link);
 exit;
	}//if count
	else
	{
	header("Location:../../index.php"); 
	exit;
	}


?>
