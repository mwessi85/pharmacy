<?php require_once("includes/functions.php");?>
<?php
require_once("includes/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "SELECT DISTINCT * FROM medicine WHERE name LIKE '%$q%' AND balance_after_sale >= 1 AND comment != 'expired'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$id = $rs['id'];
	$name = $rs['name'];
	$trade_name = $rs['trade_name'];
	$weight = $rs['weight'];
	$unit = $rs['unit'];
	$category = $rs['category'];
	$current_amount = $rs['current_amount'];
	$unit_result = select_unit($unit);
	$units = mysql_fetch_assoc($unit_result);
	$unit_name = $units['value'];
	$category_result = select_category($category);
	$category = mysql_fetch_assoc($category_result);
	$category_name = $category['name'];
	if($current_amount>0){
		echo $name." ".$trade_name." ".$weight." ".$unit_name." - ".$current_amount."Units left| $id \n";
	}
}
?>