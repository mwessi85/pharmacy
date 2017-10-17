<?php require_once("includes/functions.php");?>
<?php
require_once("includes/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "SELECT m.id medicine_id, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.unit unit_id, u.value units, m.category category_id, c.name category, s.stock_amount stock_amount, s.current_amount current_amount, s. percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff  
FROM medicine m JOIN category c JOIN units u JOIN stock s 
WHERE m.id = s.medicine AND m.category = c.id AND m.unit = u.id AND (generic_name LIKE '%$q%' OR trade_name LIKE '%$q%') AND expiry_date>=NOW() AND m.status = 'active' AND s.current_amount >= 1 
ORDER BY generic_name, trade_name, weight, s.expiry_date, percentage_balance";
$rsd = mysql_query($sql);
while($medicine= mysql_fetch_assoc($rsd)) {
	$medicine_id = $medicine['medicine_id'];
	$generic_name = $medicine['generic_name'];
	$trade_name = $medicine['trade_name'];
	$category = $medicine['category'];
	$weight = $medicine['weight'];
	$units = $medicine['units'];
	$percentage_balance = $medicine['percentage_balance'];
	$current_amount = $medicine['current_amount'];
	$expiry_date = $medicine['expiry_date'];
	$formated_expiry_date = strtotime($expiry_date);
	$formated_expiry_date = date("j-M-Y", $formated_expiry_date);
	echo $generic_name."/".$trade_name." ".$weight."".$medicine['units']." - ".$percentage_balance."% left - Exp: ".$formated_expiry_date."| ".$medicine_id." \n";
	/*if($medicine['percentage_balance']>50){
		echo $generic_name."/".$trade_name." ".$weight."".$medicine['units']."| ".$medicine_id." \n";
	}	
	if($medicine['percentage_balance']<=50 && $medicine['percentage_balance']>25){
		echo $generic_name."/".$trade_name." ".$weight."".$medicine['units']."| ".$medicine_id." \n";
	}
	if($medicine['percentage_balance']<=25){
		echo $generic_name."/".$trade_name." ".$weight."".$medicine['units']."| ".$medicine_id." \n";	
	}
*/}
?>