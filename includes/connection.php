<?php require_once("constants.php");?>
<?php 
$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
if(!isset($connection)){
	die("Database Connection Failed: ".mysql_error());
}

$db_select = mysql_select_db(DB_NAME, $connection);
if(!isset($db_select)){
	die("Database Connection Failed: ".mysql_error());
}
?>