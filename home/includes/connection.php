<?php
include "mainconfig.php";
?>

<?php
DEFINE ('DB_USER', $db_user);
DEFINE ('DB_PASSWORD', $db_pass);
DEFINE ('DB_HOST', $db_host);
DEFINE ('DB_NAME', $db_name);

$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not
       connect to MySQL: '.mysql_error());
       
@mysql_select_db (DB_NAME) OR die ('Could not connect to database: '
.mysql_error());


?>