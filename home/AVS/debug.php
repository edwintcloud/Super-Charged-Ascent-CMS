<?php
ob_start();
include("index.php");
ob_end_clean();
?>
	Debug tool started:
	<ul>
<?php
$Con = @mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
if(!$Con || !@mysql_select_db(MYSQL_DB))
{
	?>
		<li>Local MySQL database: <b>fail</b>: <?php echo mysql_error(); ?>.</li>
	<?php
}
else
{
	?>
		<li>Local MySQL database: pass.</li>
	<?php
}
@mysql_close($Con);

$Con = @mysql_connect(LOGON_HOST,LOGON_USER,LOGON_PASS);
if(!$Con || !@mysql_select_db(LOGON_DB))
{
	?>
		<li>Logon MySQL database: <b>fail</b>: <?php echo mysql_error(); ?>.</li>
	<?php
}
else
{
	?>
		<li>Logon MySQL database: pass.</li>
	<?php
}
@mysql_close($Con);

?>