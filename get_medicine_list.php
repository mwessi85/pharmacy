<?php require_once("includes/functions.php");?>
<?php
require_once("includes/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "SELECT m.id id, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, u.value units, c.name category
FROM medicine m JOIN category c JOIN units u 
WHERE m.category = c.id AND m.unit = u.id AND (m.generic_name LIKE '%$q%' OR m.trade_name LIKE '%$q%') AND m.status = 'active' 
ORDER BY generic_name, trade_name, weight";
$rsd = mysql_query($sql);
while($medicine = mysql_fetch_array($rsd)) {
	$medicine_id = $medicine['id'];
	$generic_name = $medicine['generic_name'];
	$trade_name = $medicine['trade_name'];
	$category = $medicine['category'];
	$weight = $medicine['weight'];
	$units = $medicine['units'];
	echo $generic_name."/".$trade_name." ".$weight."".$units." | ".$medicine_id." \n";
}
?>