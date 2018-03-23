<?php

$rulesheet=file("sec_rules.txt");

function attack_detected($string,$header,$pattern) {
$date=date("m.d.y");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Forbidden</h1>
<p>You don't have permission to access this file that way.</p>
<hr>
</body></html>

<?php
$SecLog=@fopen("sec.log", "a");
fwrite($SecLog,"\n------------------------------\nAttack Detected\n\nDate: $date\nGET Header: $header\nPattern Match: $pattern \nIP address: $_SERVER[REMOTE_ADDR]\n\n\nString: $string");
fclose($SecLog);
die();
}


foreach($rulesheet as $k=>$v) {

// For $_GET (no pun intended)
if(preg_match("@SecFilter\[GET\]@i",$v)) {
$filterpiece=explode(":", $v);
$GET_filter_array[]=strtolower($filterpiece[1]);
}

// For $_POST
if(preg_match("@SecFilter\[POST\]@i",$v)) {
$filterpiece=explode(":", $v);
$POST_filter_array[]=strtolower($filterpiece[1]);
}


// For $_COOKIE
if(preg_match("@SecFilter\[COOKIE\]@i",$v)) {
$filterpiece=explode(":", $v);
$COOKIE_filter_array[]=strtolower($filterpiece[1]);
}


// For $_SERVER
if(preg_match("@SecFilter\[SERVER\]@i",$v)) {
$filterpiece=explode("]:", $v);
$filterpiece2=explode("][", $filterpiece[0]);

// What do we filter?
$SERVER_filter_array[]=strtolower($filterpiece[1]);

//The actual filters
$SERVER_filter_NAMES[]=strtoupper($filterpiece2[1]);

}



// For everything.

if(preg_match("@SecFilter\[ALL\]@i",$v)) {
$filterpiece=explode(":", $v);
$ALL_filter_array[]=strtolower($filterpiece[1]);
}

}

// Run through ONLY the GET data
if (is_array($_GET)) { 
foreach($_GET as $k=>$v) {

// Specific Array
foreach($GET_filter_array as $filterkey=>$filtered) {
$filtered=rtrim($filtered);
if(preg_match("@$filtered@i",$v)) {

attack_detected($v,$_SERVER['REQUEST_URI'],$filtered);

}
}

// All Array
foreach($ALL_filter_array as $filterkey=>$filtered) {
$filtered=rtrim($filtered);
if(preg_match("@$filtered@i",$v)) {

attack_detected($v,$_SERVER['REQUEST_URI'],$filtered);

}

}


$_GET[$k]=$v;
}
}

// Run through ONLY the POST data
if (is_array($_POST)) { 
foreach($_POST as $k=>$v) {

// Specific Array
foreach($POST_filter_array as $filterkey=>$filtered) {
$filtered=rtrim($filtered);
if(preg_match("@$filtered@i",$v)) {

attack_detected($v,$_SERVER['REQUEST_URI'],$filtered);

}
}

// All Array
foreach($ALL_filter_array as $filterkey=>$filtered) {
$filtered=rtrim($filtered);
if(preg_match("@$filtered@i",$v)) {


attack_detected($v,$_SERVER['REQUEST_URI'],$filtered);

}
}

$_POST[$k]=$v;
}
}


// Run through ONLY the COOKIE data
if (is_array($_COOKIE)) { 
foreach($_COOKIE as $k=>$v) {

// Specific Array
foreach($COOKIE_filter_array as $filterkey=>$filtered) {
$filtered=rtrim($filtered);
if(preg_match("@$filtered@i",$v)) {
attack_detected();
}
}


// All Array
foreach($ALL_filter_array as $filterkey=>$filtered) {
$filtered=rtrim($filtered);
if(preg_match("@$filtered@i",$v)) {

attack_detected($v,$_SERVER['REQUEST_URI'],$filtered);

}
}

$_COOKIE[$k]=$v;
}
}


for ($x=0; $x<count($SERVER_filter_array); $x++) 
{

$v=$SERVER_filter_NAMES[$x];
$filtered=rtrim($SERVER_filter_array[$x]);
if(preg_match("@$filtered@i",$_SERVER[$v])) {
attack_detected($v,$_SERVER['REQUEST_URI'],$filtered);
}

}

?>
