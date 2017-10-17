<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php 
//Query confirmation

function query_confirmation($result){
	if(!isset($result)){
		die("Database query failed: ".mysql_error());	
	}
}
function success_message(){
	if(isset($_GET['success_message'])){
		$message = $_GET['success_message'];
		return $message;
	}
	return false;
}
function even_strip($i){
	 if($i%2 == 0){
		 $class = "class='even'";
	}else {
		$class = "class='cell_value'" ;
	}	
	return $class;
}
function mysql_prep($value){
	$magic_quotes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists("mysql_real_escape_string"); // i.e. PHP >= v4.3.0
	if($new_enough_php){ // i.e. PHP v4.3.0 or higher
		//undo any magic quote effects so mysql_real_escape_string can do the work
		if($magic_quotes_active){
			$value = stripslashes($value);
		}
		$value = mysql_real_escape_string($value);
	} else{ // Before PHP >= v4.3.0 and magic quotes aren't on, add them manually
		if(!$magic_quotes_active){
			$value = addslashes($value);
		}
		//if magic quotes are active, then slashes already exist
	}
	return $value;
}
//Show errors
function show_errors(){
	global $errors;
	if(isset($errors)) {
		foreach ($errors as $key => $value) {
			echo "<p class='out_of_stock'>".$key;
			echo $value."</p>";
		}
	}
}


//Redirection to a locaion
function redirect_to($location = NULL){
	if($location != NULL){
		header("location: ".$location);
		exit;
	}
}
function redirect_success($location = NULL, $message = NULL){
	if($location != NULL && $message != NULL){
		header("location: ".$location."?success_message=".$message);
		exit;
	}
}
function redirect_success_subject($location = NULL, $message = NULL, $subj_id){
	if($location != NULL && $message != NULL){
		header("location: ".$location."?success_message=".$message."&subj=".$subj_id);
		exit;
	}
}
function redirect_success_page($location = NULL, $message = NULL, $page_id){
	if($location != NULL && $message != NULL){
		header("location: ".$location."?success_message=".$message."&page=".$page_id);
		exit;
	}
}

//User Functions
function select_users(){
	global $connection;
	$query = "SELECT * FROM users ORDER BY last_name, first_name ASC ";
	$result = mysql_query($query, $connection);
	return $result;
}
function select_user($user_id){
	global $connection;
	$query = "SELECT * FROM users WHERE id = ".$user_id;
	$result = mysql_query($query, $connection);
	return $result;
}
 
function select_categories(){
	global $connection;
	$query = "SELECT * FROM category ORDER BY name";
	$result = mysql_query($query, $connection);
	return $result;
}

function select_category($category_id){
	global $connection;
	$query = "SELECT * FROM category WHERE id = ".$category_id;
	$result = mysql_query($query, $connection);
	return $result;
}

function select_units(){
	global $connection;
	$query = "SELECT * FROM units";
	$result = mysql_query($query, $connection);
	return $result;
}

function select_unit($unit_id){
	global $connection;
	$query = "SELECT * FROM units WHERE id = ".$unit_id;
	$result = mysql_query($query, $connection);
	return $result;
}

function select_medicines(){
	global $connection;
	$query = "SELECT * FROM medicine ORDER BY name, trade_name ASC ";
	$result = mysql_query($query, $connection);
	return $result;
}

function select_medicine($medicine_id){
	global $connection;
	$query = "SELECT * FROM medicine WHERE id = ".$medicine_id;
	$result = mysql_query($query, $connection);
	return $result;
}

function select_customers(){
	global $connection;
	$query = "SELECT * FROM customer ORDER BY last_name, first_name ASC";
	$result = mysql_query($query, $connection);
	return $result;
}
function select_customer($customer_id){
	global $connection;
	$query = "SELECT * FROM customer WHERE id = ".$customer_id;
	$result = mysql_query($query, $connection);
	return $result;
}

function level($level){
	if($level == "admin"){
		return "Administrator";
	}
	if($level == "staff"){
		return "Staff";
	}
	if($level == "user"){
		return "User";
	}
}
function status($status){
	if($status == 1){
		return "Active";
	}
	else{
		return "Inactive";	
	}	
}

function check_stock(){
	global $connection;
	echo " <h2 class='notice'>Notice</h1>
	<table>
        <tr>
        <td class='column_head'>Medicine</td>
        <td class='column_head'>Message</td>
        </tr>";
	$sql = "SELECT * FROM medicine WHERE cummulative_amount > 0 ORDER BY name, trade_name ASC";
	$result_medicine = mysql_query($sql, $connection);
	while($medicine = mysql_fetch_assoc($result_medicine)){
		$medicine_id = $medicine['id'];
		$medicine_category = $medicine['category'];
		$medicine_name = $medicine['name'];
		$trade_name = $medicine['trade_name'];
		$weight = $medicine['weight'];
		$unit = $medicine['unit'];
		$unit_result = select_unit($unit);
		$units = mysql_fetch_assoc($unit_result);
		$unit_name = $units['value'];
		$name = $medicine_name."/".$trade_name."".$weight." ".$unit_name;
		$comment = $medicine['comment'];
		$result = select_category($medicine_category);
		$category = mysql_fetch_assoc($result);
		$category_name = $category['name'];
		$current_amount = $medicine['current_amount'];
		$initial_amount = $medicine['initial_amount'];
		$threshold_amount = $medicine['initial_amount']*0.3;
		$cummulative_amount = $medicine['cummulative_amount'];
		$initial_amount = $medicine['initial_amount']*0.3;
		$expiry_date = $medicine['expiry_date'];
		
		if($current_amount == 0){
			echo "<tr><td><a href=\"medicine.php?id=".$medicine_id."\">".$name."</a></td>";
			echo "<td class='out_of_stock'>Out of Stock - 0 units left</td></tr>";
			$query_out = "UPDATE medicine SET comment = 'out of stock' WHERE id = ".$medicine_id;
			$result_out = mysql_query($query_out, $connection);
		}
		elseif ($current_amount <= $threshold_amount){
			$side_stock_out_message = $name." is running out of stock at: ".$current_amount." out of ".$initial_amount." units";
			echo "<tr><td><a href=\"medicine.php?id=".$medicine_id."\">".$name."</a></td>";
			echo "<td class='almost_out_of_stock'>Less that ".$threshold_amount." Units left</td></tr>";
			$query_below = "UPDATE medicine SET comment = 'below threshold' WHERE id = ".$medicine_id;
			$result_below = mysql_query($query_below, $connection);
		}
		else{
			$today = date("Y-m-d");
			$todaysDate = strtotime($today);
			$almost_expiryDate = strtotime($today);
			$expiryDate = strtotime($expiry_date);
			$almost_expiryDate = strtotime(date("Y-m-d", strtotime($expiry_date)) . " -1 month");
			if ($expiryDate<=$todaysDate){
				$side_expiry_message = $name." expired on ".$expiry_date;
				echo "<tr><tr><td><a href=\"medicine.php?id=".$medicine_id."\">".$name."</a></td>";
				echo "<td class='expired'>Expired on ".$expiry_date."</td></tr>";
				$query_exp = "UPDATE medicine SET comment = 'expired' WHERE id = ".$medicine_id;
				$result_exp = mysql_query($query_exp, $connection);
			}else if($almost_expiryDate<=$todaysDate){
				$side_expiry_message = $name." expires on ".$expiry_date;
				echo "<tr><tr><td><a href=\"medicine.php?id=".$medicine_id."\">".$name."</a></td>";
				echo "<td class='almost_expired'>Expires on ".$expiry_date."</td></tr>";
			}
		}
	}
	echo "</table>";
}

function show_receipt(){
	global $connection;
	$query_sale_no = "SELECT * FROM medicine_sold ORDER BY sale_no DESC LIMIT 1";
	$result_sale_no = mysql_query($query_sale_no, $connection);
	$sale_no_array = mysql_fetch_array($result_sale_no);
	$sale_no = $sale_no_array['sale_no'];
	$query = "SELECT * FROM medicine_sold WHERE sale_no = ".$sale_no;
	$result = mysql_query($query, $connection);
	$sold_customer = mysql_fetch_array(mysql_query($query, $connection));
	echo "<h1>Previous Sale</h1>";
	echo "<h3>Customer: ".$sold_customer['customer_name']."</h3>";
	echo "<h3>Date: ".$sold_customer['date_sold']."</h3>";
	echo " <table class='results'>
        <tr>
        <td class='column_head'></td>
		<td class='column_head'>Item</td>
        <td class='column_head'>Amount</td>
        <td class='column_head'>Unit Cost</td>
        <td class='column_head'>Total</td>
        </tr>";
	$i = 0;
	$total_sold = 0;
	while($sold = mysql_fetch_array($result)){
		$medicine_name = $sold['medicine_name'];
		$sold_amount = $sold['sold_amount'];
		$selling_price = $sold['selling_price'];
		$sold = $sold['selling_price'] * $sold_amount;
		$staff = $sold['staff'];
		$total_sold = $total_sold + $sold;
		echo "
		<tr>
			<td class='cell_value'>".++$i."</td>
			<td class='cell_value'>".$medicine_name."</td>
			<td class='cell_value'>".$sold_amount."</td>
			<td class='cell_value'>".$selling_price."</td>
			<td class='cell_value'>".$sold."</td>";
			echo "</tr>";
		}
		
	echo " <tr>
            <td></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'>".$total_sold."</td>
            </tr>
        </table>";
}


function get_logged_user(){
	if(isset($_SESSION['user_id'])){
		global $connection;
		$user_id = $_SESSION['user_id'];
		$query = "SELECT * FROM users WHERE id=".$user_id;
		$result = mysql_query($query, $connection);
		if(mysql_num_rows($result) == 1){
			$logged_user = mysql_fetch_array($result);
			$logged_user_name = $logged_user['last_name']." ".$logged_user['first_name'];
			return $logged_user_name;
		}
	}	
}
function get_users_name($user_id){
	global $connection;
	$query = "SELECT * FROM users WHERE id=".$user_id;
	$result = mysql_query($query, $connection);
	if(mysql_num_rows($result) == 1){
		$users = mysql_fetch_array($result);
		$users_name = $users['last_name']." ".$users['first_name'];
		return $users_name;
	}	
}

function drug_expired($expiry){
		// Find todays date.
		$today = date("Y-m-d");
		$todaysDate = strtotime($today);
		$expiryDate = strtotime($expiry);
		if ($expiryDate<=$todaysDate){
			return 'Expired';
		}else{
			return 'Not Expired';
		}
}
function isValidEmail($email) {
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}else{
		return false;	
	}
}
function isValidPhone($phone) {
	if (strlen($phone)==10) {
		return true;
	}else{
		return false;	
	}
}
function isValidIp($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP)) {
		return true;
	}else{
		return false;	
	}
}
function isValidInt($int) {
	if (filter_var($int, FILTER_VALIDATE_INT)) {
		return true;
	}else{
		return false;	
	}
}

function diff_months($date1, $date2){
	
	$ts1 = strtotime($date1);
	$ts2 = strtotime($date2);
	
	$year1 = date('Y', $ts1);
	$year2 = date('Y', $ts2);
	
	$month1 = date('m', $ts1);
	$month2 = date('m', $ts2);
	
	$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
	return $diff;
}
function admin_nav(){
$admin_nav = "
<p>&nbsp;</p>
<ul>
<li><a href='main.php'><strong>Home</strong></a></li>
<li><a href='settings.php'><strong>Settings</strong></a></li>
<li><a href='medicines.php'><strong>Medicines</strong></a></li>
<li><a href='services.php'><strong>Services</strong></a></li>
<li><a href='tests.php'><strong>Lab Tests</strong></a></li>
<li><a href='stock_levels.php'><strong>Stock</strong></a></li>
</ul>
<ul>
<li><a href='customers.php'><strong>Customers</strong></a></li>
<li><a href='sale.php?new_sale=true'><a onClick=\"return toggleMenu('menu1')\"><strong>Sales Point >></strong></a><span class='menu' id='menu1'>
<a href='sale.php?sale=only&new_sale=true'>Medicine</a >
<a href='sale_services.php?sale=only&new_sale=true'>Services</a>
<a href='sale_tests.php?sale=only&new_sale=true'>Tests</a>
</li>
</ul>
<ul>
<li><a href='reports.php'><strong>Reports</strong></a></li>
<li><a href='credit.php'><strong>Credit Sales</strong></a></li>
</ul>
<p></p>
";
return $admin_nav;
}
function clerk_nav(){
$clerk_nav = "
<h1>Users</h1>
<ul>
<li><a href='main.php'>Home</a></li>
</ul>
<h1>Sales</h1>
<ul>
<li>
<a href='sale.php?new_sale=true'><a onClick=\"return toggleMenu('menu1')\"><strong>Sales Point >></strong></a><span class='menu' id='menu1'>
<a href='sale.php?sale=only&new_sale=true'>Medicine</a >
<a href='sale_services.php?sale=only&new_sale=true'>Services</a>
<a href='sale_tests.php?sale=only&new_sale=true'>Tests</a>
</ul>
";
return $clerk_nav;
}
function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
}
function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
}
function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
}
function xlsWriteLabel($Row, $Col, $Value) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
}
function export_medicines(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Medicine ID");
xlsWriteLabel(0, 1,"Generic Name");
xlsWriteLabel(0, 2,"Trade Name");
xlsWriteLabel(0, 3,"Weight");
xlsWriteLabel(0, 4,"Units");
xlsWriteLabel(0, 5,"Category");
xlsWriteLabel(0, 6,"Form");
xlsWriteLabel(0, 7,"Status");
// second row 
$query = "SELECT distinct m.id id, m.status status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, f.form form, u.value units, c.name category 
		FROM medicine m JOIN category c JOIN units u JOIN form f WHERE m.category = c.id AND m.unit = u.id AND m.form = f.id 
		ORDER BY generic_name, trade_name, weight";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteNumber($xlsRow, 0, $row['id']);
		xlsWriteLabel($xlsRow, 1, $row['generic_name']);
		xlsWriteLabel($xlsRow, 2, $row['trade_name']);
		xlsWriteNumber($xlsRow, 3, $row['weight']);
		xlsWriteLabel($xlsRow, 4, $row['units']);
		xlsWriteLabel($xlsRow, 5, $row['category']);
		xlsWriteLabel($xlsRow, 6, ucfirst($row['form']));
		xlsWriteLabel($xlsRow, 7, ucfirst($row['status']));
		$xlsRow++;
	}
xlsEOF();
}
function export_tests(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Test ID");
xlsWriteLabel(0, 1,"Test Name");
xlsWriteLabel(0, 2,"Status");
xlsWriteLabel(0, 3,"Unit Cost");
xlsWriteLabel(0, 4,"Description");
// second row 
$query = "SELECT * FROM tests ORDER BY test, description";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteNumber($xlsRow, 0, $row['id']);
		xlsWriteLabel($xlsRow, 1, $row['test']);
		xlsWriteLabel($xlsRow, 2, $row['status']);
		xlsWriteNumber($xlsRow, 3, $row['unit_cost']);
		xlsWriteLabel($xlsRow, 4, $row['description']);
		$xlsRow++;
	}
xlsEOF();
}
function export_services(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Service ID");
xlsWriteLabel(0, 1,"Service Name");
xlsWriteLabel(0, 2,"Status");
xlsWriteLabel(0, 3,"Unit Cost");
xlsWriteLabel(0, 4,"Description");
// second row 
$query = "SELECT * FROM services ORDER BY service, description";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteNumber($xlsRow, 0, $row['id']);
		xlsWriteLabel($xlsRow, 1, $row['service']);
		xlsWriteLabel($xlsRow, 2, $row['status']);
		xlsWriteNumber($xlsRow, 3, $row['unit_cost']);
		xlsWriteLabel($xlsRow, 4, $row['description']);
		$xlsRow++;
	}
xlsEOF();
}
function export_medicine($medicine_id){
global $connection;
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
$query = "SELECT * FROM medicine m JOIN category c JOIN units u WHERE m.id=".$_GET['id']." AND m.category = c.id AND m.unit=u.id";
$result = mysql_query($query, $connection);
$medicine = mysql_fetch_assoc($result);
$form_result= mysql_query("SELECT * FROM form WHERE id = ".$medicine['form']);
$select_form = mysql_fetch_assoc($form_result);
$form = ucfirst($select_form["form"]);
$sales_unit = ucfirst($select_form["sales_unit"]);
xlsWriteLabel(0, 0, "Medicine");
xlsWriteLabel(0, 1, $medicine['generic_name']."/".$medicine['trade_name']." ".$medicine['weight']."".$medicine['value']);
xlsWriteLabel(1, 0, "Form");
xlsWriteLabel(1, 1, $form);
xlsWriteLabel(2, 0, "Status");
xlsWriteLabel(2, 1, ucfirst($medicine['status']));
xlsWriteLabel(3, 0, "Category");
xlsWriteLabel(3, 1, ucfirst(strtolower($medicine['name'])));

// second row 
xlsWriteLabel(4, 0,"Stock ID");
xlsWriteLabel(4, 1,"Stock Amount");
xlsWriteLabel(4, 2,"Current Amount");
xlsWriteLabel(4, 3,"Percentage Balance");
xlsWriteLabel(4, 4,"Expiry Date");
xlsWriteLabel(4, 5,"Buying");
xlsWriteLabel(4, 6,"Selling");
xlsWriteLabel(4, 7,"Stock Date");
xlsWriteLabel(4, 8,"Staff");
$query = "SELECT id, stock_amount, current_amount, percentage_balance, expiry_date, buying, selling, stock_date, staff staff  FROM stock WHERE medicine=".$medicine_id." ORDER BY expiry_date";
$result=mysql_query($query, $connection);
$xlsRow = 5;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteNumber($xlsRow, 0, $row['id']);
		xlsWriteNumber($xlsRow, 1, $row['stock_amount']);
		xlsWriteNumber($xlsRow, 2, $row['current_amount']);
		xlsWriteNumber($xlsRow, 3, $row['percentage_balance']);
		xlsWriteLabel($xlsRow, 4, $row['expiry_date']);
		xlsWriteNumber($xlsRow, 5, $row['buying']);
		xlsWriteNumber($xlsRow, 6, $row['selling']);
		xlsWriteLabel($xlsRow, 7, $row['stock_date']);
		$staff_name = get_users_name($row['staff']);
		xlsWriteLabel($xlsRow, 8, ucfirst($staff_name));
		$xlsRow++;
	}
xlsEOF();
}
function export_stock(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Stock ID");
xlsWriteLabel(0, 1,"Medicine");
xlsWriteLabel(0, 2,"Sales Unit");
xlsWriteLabel(0, 3,"Stock");
xlsWriteLabel(0, 4,"Balance");
xlsWriteLabel(0, 5,"Balance(%)");
xlsWriteLabel(0, 6,"Expiry Date");
xlsWriteLabel(0, 7,"Buying Price");
xlsWriteLabel(0, 8,"Selling Price");
xlsWriteLabel(0, 9,"Stock Date");
xlsWriteLabel(0, 10,"Staff");
// second row 
$query = "SELECT m.id medicine_id, m.status medicine_status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.form form, m.unit unit_id, u.value unit, m.category category_id, c.name category, m.form form_id, f.form medicine_form, f.sales_unit sales_unit, s.id stock_id, s.stock_amount stock_amount, s.current_amount current_amount, s.percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff
FROM medicine m
JOIN category c
JOIN units u
JOIN form f
JOIN stock s
WHERE m.id = s.medicine
AND m.category = c.id
AND m.unit = u.id
AND m.form = f.id
ORDER BY generic_name, expiry_date";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteNumber($xlsRow, 0, $row['stock_id']);
		xlsWriteLabel($xlsRow, 1, $row['generic_name']."/".$row['trade_name']." ".$row['weight']."".$row['unit']);
		xlsWriteLabel($xlsRow, 2, ucfirst($row['sales_unit']));
		xlsWriteNumber($xlsRow, 3, $row['stock_amount']);
		xlsWriteNumber($xlsRow, 4, $row['current_amount']);
		xlsWriteNumber($xlsRow, 5, $row['percentage_balance']);
		xlsWriteLabel($xlsRow, 6, $row['expiry_date']);
		xlsWriteNumber($xlsRow, 7, $row['buying']);
		xlsWriteNumber($xlsRow, 8, $row['selling']);
		xlsWriteLabel($xlsRow, 9, $row['stock_date']);
		$staff_name = get_users_name($row['staff']);
		xlsWriteLabel($xlsRow, 10, ucfirst($staff_name));
		$xlsRow++;
	}
xlsEOF();
}
function export_expired_stock(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Stock ID");
xlsWriteLabel(0, 1,"Medicine");
xlsWriteLabel(0, 2,"Sales Unit");
xlsWriteLabel(0, 3,"Stock");
xlsWriteLabel(0, 4,"Balance");
xlsWriteLabel(0, 5,"Balance(%)");
xlsWriteLabel(0, 6,"Expiry Date");
xlsWriteLabel(0, 7,"Buying Price");
xlsWriteLabel(0, 8,"Selling Price");
xlsWriteLabel(0, 9,"Stock Date");
xlsWriteLabel(0, 10,"Staff");
// second row 
$query = "SELECT m.id medicine_id, m.status medicine_status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.form form, m.unit unit_id, u.value unit, m.category category_id, c.name category, m.form form_id, f.form medicine_form, f.sales_unit sales_unit, s.id stock_id, s.stock_amount stock_amount, s.current_amount current_amount, s.percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff
FROM medicine m
JOIN category c
JOIN units u
JOIN form f
JOIN stock s
WHERE m.id = s.medicine
AND m.category = c.id
AND m.unit = u.id
AND m.form = f.id
AND s.expiry_date <= NOW()
ORDER BY generic_name, expiry_date";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteNumber($xlsRow, 0, $row['stock_id']);
		xlsWriteLabel($xlsRow, 1, $row['generic_name']."/".$row['trade_name']." ".$row['weight']."".$row['unit']);
		xlsWriteLabel($xlsRow, 2, ucfirst($row['sales_unit']));
		xlsWriteNumber($xlsRow, 3, $row['stock_amount']);
		xlsWriteNumber($xlsRow, 4, $row['current_amount']);
		xlsWriteNumber($xlsRow, 5, $row['percentage_balance']);
		xlsWriteLabel($xlsRow, 6, $row['expiry_date']);
		xlsWriteNumber($xlsRow, 7, $row['buying']);
		xlsWriteNumber($xlsRow, 8, $row['selling']);
		xlsWriteLabel($xlsRow, 9, $row['stock_date']);
		$staff_name = get_users_name($row['staff']);
		xlsWriteLabel($xlsRow, 10, ucfirst($staff_name));
		$xlsRow++;
	}
xlsEOF();
}
function excel_general_sales(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Medicine");
xlsWriteLabel(0, 2,"Frequency");
xlsWriteLabel(0, 3,"Duration");
xlsWriteLabel(0, 4,"Dispensed");
xlsWriteLabel(0, 5,"Buying");
xlsWriteLabel(0, 6,"Selling");
xlsWriteLabel(0, 7,"Bought");
xlsWriteLabel(0, 8,"Sold");
xlsWriteLabel(0, 9,"Profit");
xlsWriteLabel(0, 10,"Sale Date and Time");
xlsWriteLabel(0, 11,"Client");
xlsWriteLabel(0, 12,"Staff");
// second row 
$query = "SELECT * FROM sales";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		$bought = $row['buying']*$row['dispensed'];
		$sold = $row['selling']*$row['dispensed'];
		$profit = $sold-$bought;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['medicine_name']);
		xlsWriteLabel($xlsRow, 2, $row['frequency']);
		xlsWriteNumber($xlsRow, 3, $row['duration']);
		xlsWriteNumber($xlsRow, 4, $row['dispensed']);
		xlsWriteNumber($xlsRow, 5, $row['buying']);
		xlsWriteNumber($xlsRow, 6, $row['selling']);
		xlsWriteNumber($xlsRow, 7, $bought);
		xlsWriteNumber($xlsRow, 8, $sold);
		xlsWriteNumber($xlsRow, 9, $profit);
		xlsWriteLabel($xlsRow, 10, $row['datetime']);
		xlsWriteLabel($xlsRow, 11, $row['client_name']);
		xlsWriteLabel($xlsRow, 12, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_service_general_sales(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Service");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM service_sales";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['service']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_test_general_sales(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Test");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM test_sales";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['test']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function export_credit(){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Customer");
xlsWriteLabel(0, 1,"Transaction No.");
xlsWriteLabel(0, 2,"Amount Paid");
xlsWriteLabel(0, 3,"Balance");
xlsWriteLabel(0, 4,"Staff");
// second row 
$query = "SELECT * FROM credit";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		xlsWriteLabel($xlsRow, 0, $row['customer']);
		xlsWriteLabel($xlsRow, 1, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 2, $row['amount_paid']);
		xlsWriteNumber($xlsRow, 3, $row['balance']);
		xlsWriteNumber($xlsRow, 3, $row['staff']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_medicine_sales($medicine_id){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Medicine");
xlsWriteLabel(0, 2,"Frequency");
xlsWriteLabel(0, 3,"Duration");
xlsWriteLabel(0, 4,"Dispensed");
xlsWriteLabel(0, 5,"Buying");
xlsWriteLabel(0, 6,"Selling");
xlsWriteLabel(0, 7,"Bought");
xlsWriteLabel(0, 8,"Sold");
xlsWriteLabel(0, 9,"Profit");
xlsWriteLabel(0, 10,"Sale Date and Time");
xlsWriteLabel(0, 11,"Client");
xlsWriteLabel(0, 12,"Staff");
// second row 
$query = "SELECT * FROM sales WHERE medicine_id = ".$medicine_id;
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		$bought = $row['buying']*$row['dispensed'];
		$sold = $row['selling']*$row['dispensed'];
		$profit = $sold-$bought;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['medicine_name']);
		xlsWriteLabel($xlsRow, 2, $row['frequency']);
		xlsWriteNumber($xlsRow, 3, $row['duration']);
		xlsWriteNumber($xlsRow, 4, $row['dispensed']);
		xlsWriteNumber($xlsRow, 5, $row['buying']);
		xlsWriteNumber($xlsRow, 6, $row['selling']);
		xlsWriteNumber($xlsRow, 7, $bought);
		xlsWriteNumber($xlsRow, 8, $sold);
		xlsWriteNumber($xlsRow, 9, $profit);
		xlsWriteLabel($xlsRow, 10, $row['datetime']);
		xlsWriteLabel($xlsRow, 11, $row['client_name']);
		xlsWriteLabel($xlsRow, 12, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_date_sales($date){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Medicine");
xlsWriteLabel(0, 2,"Frequency");
xlsWriteLabel(0, 3,"Duration");
xlsWriteLabel(0, 4,"Dispensed");
xlsWriteLabel(0, 5,"Buying");
xlsWriteLabel(0, 6,"Selling");
xlsWriteLabel(0, 7,"Bought");
xlsWriteLabel(0, 8,"Sold");
xlsWriteLabel(0, 9,"Profit");
xlsWriteLabel(0, 10,"Sale Date and Time");
xlsWriteLabel(0, 11,"Client");
xlsWriteLabel(0, 12,"Staff");
// second row 
$query = "SELECT * FROM sales WHERE datetime >= '".$date." 00:00:00' AND datetime <= '".$date." 23:49:59'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	while($row=mysql_fetch_assoc($result))
	{
		$bought = $row['buying']*$row['dispensed'];
		$sold = $row['selling']*$row['dispensed'];
		$profit = $sold-$bought;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['medicine_name']);
		xlsWriteLabel($xlsRow, 2, $row['frequency']);
		xlsWriteNumber($xlsRow, 3, $row['duration']);
		xlsWriteNumber($xlsRow, 4, $row['dispensed']);
		xlsWriteNumber($xlsRow, 5, $row['buying']);
		xlsWriteNumber($xlsRow, 6, $row['selling']);
		xlsWriteNumber($xlsRow, 7, $bought);
		xlsWriteNumber($xlsRow, 8, $sold);
		xlsWriteNumber($xlsRow, 9, $profit);
		xlsWriteLabel($xlsRow, 10, $row['datetime']);
		xlsWriteLabel($xlsRow, 11, $row['client_name']);
		xlsWriteLabel($xlsRow, 12, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_service_date_sales($date){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Service");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM service_sales WHERE datetime >= '".$date." 00:00:00' AND datetime <= '".$date." 23:49:59'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['service']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_service_sales($service){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Service");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM service_sales WHERE service = '".$service."'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['service']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_test_sales($test){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Test");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM test_sales WHERE test = '".$test."'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['test']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_test_date_sales($date){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Test");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM test_sales WHERE datetime >= '".$date." 00:00:00' AND datetime <= '".$date." 23:49:59'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['test']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}

function check_sense(){
	global $connection;
	//global $str;
	$str = strtotime(date("Y-m-d"));
	$query = "SELECT * FROM pharm WHERE id = 1";
	$result = mysql_query($query, $connection);
	$pharm = mysql_fetch_array($result);
	if($str>=$pharm['pharm']){
         unset($_SESSION['user_id']);
	}	
	
}
function excel_service_from_to_sales($fromdate, $todate){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Service");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM service_sales WHERE datetime >= '".$fromdate." 00:00:00' AND datetime <= '".$todate." 23:49:60'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['service']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_test_from_to_sales($fromdate, $todate){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Test");
xlsWriteLabel(0, 2,"Unit Cost");
xlsWriteLabel(0, 3,"Quantity");
xlsWriteLabel(0, 4,"Total Cost");
xlsWriteLabel(0, 5,"Sale Date and Time");
xlsWriteLabel(0, 6,"Client");
xlsWriteLabel(0, 7,"Staff");
// second row 
$query = "SELECT * FROM test_sales WHERE datetime >= '".$fromdate." 00:00:00' AND datetime <= '".$todate." 23:49:60'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
$total_cost = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$cost = $row['unit_cost']*$row['quantity'];
		$total_cost = $total_cost-$cost;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['test']);
		xlsWriteLabel($xlsRow, 2, $row['unit_cost']);
		xlsWriteNumber($xlsRow, 3, $row['quantity']);
		xlsWriteNumber($xlsRow, 4, $total_cost);
		xlsWriteLabel($xlsRow, 5, $row['datetime']);
		xlsWriteLabel($xlsRow, 6, $row['client_name']);
		xlsWriteLabel($xlsRow, 7, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function excel_from_to_sales($fromdate, $todate){
global $connection; 
// prepare headers information
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"export_".date("Y-m-d").".xls\"");
header("Content-Transfer-Encoding: binary");
header("Pragma: no-cache");
header("Expires: 0");
// start exporting
xlsBOF();
// first row 
xlsWriteLabel(0, 0,"Transaction ID");
xlsWriteLabel(0, 1,"Medicine");
xlsWriteLabel(0, 2,"Frequency");
xlsWriteLabel(0, 3,"Duration");
xlsWriteLabel(0, 4,"Dispensed");
xlsWriteLabel(0, 5,"Buying");
xlsWriteLabel(0, 6,"Selling");
xlsWriteLabel(0, 7,"Bought");
xlsWriteLabel(0, 8,"Sold");
xlsWriteLabel(0, 9,"Profit");
xlsWriteLabel(0, 10,"Sale Date and Time");
xlsWriteLabel(0, 11,"Client");
xlsWriteLabel(0, 12,"Staff");
// second row 
$query = "SELECT * FROM sales WHERE datetime >= '".$fromdate." 00:00:00' AND datetime <= '".$todate." 23:49:60'";
$result=mysql_query($query, $connection);
$xlsRow = 1;
	$i=0;
	$total_dispensed = 0;
	$total_buying = 0;
	$total_selling = 0;
	while($row=mysql_fetch_assoc($result))
	{
		$bought = $row['buying']*$row['dispensed'];
		$sold = $row['selling']*$row['dispensed'];
		$profit = $sold-$bought;
		xlsWriteLabel($xlsRow, 0, $row['transaction_no']);
		xlsWriteLabel($xlsRow, 1, $row['medicine_name']);
		xlsWriteLabel($xlsRow, 2, $row['frequency']);
		xlsWriteNumber($xlsRow, 3, $row['duration']);
		xlsWriteNumber($xlsRow, 4, $row['dispensed']);
		xlsWriteNumber($xlsRow, 5, $row['buying']);
		xlsWriteNumber($xlsRow, 6, $row['selling']);
		xlsWriteNumber($xlsRow, 7, $bought);
		xlsWriteNumber($xlsRow, 8, $sold);
		xlsWriteNumber($xlsRow, 9, $profit);
		xlsWriteLabel($xlsRow, 10, $row['datetime']);
		xlsWriteLabel($xlsRow, 11, $row['client_name']);
		xlsWriteLabel($xlsRow, 12, $row['staff_name']);
		$xlsRow++;
	}
xlsEOF();
}
function absolute($value){
	$value*(-1);
	return $value;
}
?>