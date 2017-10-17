<?php require_once("includes/functions.php");?>
<?php
require_once("includes/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "SELECT * FROM services WHERE service LIKE '%$q%' OR description LIKE '%$q%' AND m.status = 'active' 
ORDER BY service";
$rsd = mysql_query($sql);
while($services= mysql_fetch_assoc($rsd)) {
	$service_id = $services['id'];
	$service = $services['service'];
	echo $service."| ".$service_id." \n";
}
?>