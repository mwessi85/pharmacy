<?php
require_once("includes/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select DISTINCT * FROM customer WHERE first_name LIKE '%$q%' OR last_name LIKE '%$q%'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$id = $rs['id'];
	$first_name = $rs['first_name'];
	$last_name = $rs['last_name'];
	$name = $first_name." ".$last_name;
	$address = $rs['address'];
	$phone = $rs['phone'];
	echo $name." - ".$address." ".$phone." | $id \n";
}
?>