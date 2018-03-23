<?php
$host = "localhost";
$user = "root";
$pass = "pass";
$data = "database";

$conn = mysql_connect($host, $user, $pass) or die(mysql_error());
mysql_select_db($data, $conn) or die(mysql_error());


function countOnline(){
  global $conn;
  $q = "SELECT * from characters where online = '1'";
  $result = mysql_query($q,$conn);
  return (mysql_numrows($result) > 0);
}
echo countOnline();

mysql_close($conn);

?>