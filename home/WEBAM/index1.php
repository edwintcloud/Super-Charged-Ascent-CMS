<?php
/*
 * Project Name: WeBAM (web ascent manager)
 * Date: 23.01.2008 inital version (1.49)
 * Author: gmaze
 * Copyright: gmaze
 * Email: *****
 * License: GNU General Public License (GPL)
 */
 
function stats() {
//create the XSL transform process
 $xp = new XsltProcessor();
//identify the XSL stylesheet
  $xsl = new DomDocument;
  $xsl->load('http://localhost/Aspire-WEBAM/substats/server_stats.xsl');
// import the XSL styelsheet into the XSLT process
  $xp->importStylesheet($xsl);
//identify the XML source
  $xml_doc = new DomDocument;
  $xml_doc->load('http://localhost/stats/conquest_stats.xml');
// transform the XML into HTML using the XSL file
  if ($html = $xp->transformToXML($xml_doc)) {
      echo $html;
  } else {
      trigger_error('XSL transformation failed.', E_USER_ERROR);
  }
}

function webam() {
//redirect browser
header("Location: site/motd.php");
}

//action defines
if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = NULL;

switch ($action) {
case "stats": 
   stats();
   break;
case "webam": 
   webam();
   break;
default:
    stats();
}
?>