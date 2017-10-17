<?php require_once("includes/functions.php");?>
<?php
require_once("includes/config.php");
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "SELECT * FROM tests WHERE test LIKE '%$q%' OR description LIKE '%$q%' AND status = 'active' 
ORDER BY test";
$rsd = mysql_query($sql);
while($tests= mysql_fetch_assoc($rsd)) {
	$test_id = $tests['id'];
	$test = $tests['test'];
	echo $test."| ".$test_id." \n";
}
?>