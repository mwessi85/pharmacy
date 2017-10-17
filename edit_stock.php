<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if($_GET['id']){
	$stock_id = $_GET['id'];
	 $query = "SELECT s.id stock_id, m.id medicine_id, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.unit unit_id, u.value unit, m.category category_id, c.name category, s.stock_amount stock_amount, s.current_amount current_amount, s. percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff  FROM medicine m JOIN category c JOIN units u JOIN stock s WHERE m.id = s.medicine
AND m.category = c.id
AND m.unit = u.id AND s.id = ".$stock_id;
        $result = mysql_query($query, $connection);
        if(isset($result)){	
            $medicine = mysql_fetch_array($result);
			$medicine_id = $medicine['medicine_id'];
			$generic_name = $medicine['generic_name'];
			$trade_name = $medicine['trade_name'];
			$weight = $medicine['weight'];
			$unit = $medicine['unit'];
			$category = $medicine['category'];
			$stock_amount = $medicine['stock_amount'];
			$current_amount = $medicine['current_amount'];
			$percentage_balance = $medicine['percentage_balance'];
			$expiry_date = $medicine['expiry_date'];
			$formated_expiry_date = strtotime($expiry_date);
			$formated_expiry_date = date("Y-m-d", $formated_expiry_date);
			$buying = $medicine['buying'];
			$selling = $medicine['selling'];
			$stock_date = $medicine['stock_date'];
			$formated_stock_date = strtotime($stock_date);
			$formated_stock_date = date("jS-M-Y h:i:s A", $formated_stock_date);
			$staff = $medicine['staff'];
			$staff_name = get_users_name($staff);
			$medicine_name = $generic_name."/".$trade_name." ".$weight."".$unit;
		}
}

if(isset($_POST['edit_stock'])){
	$errors = array();
	$medicine_id = $_POST['all_medicine_val'];
	$result_medicine = select_medicine($medicine_id);
	$stock_amount = $_POST['stock_amount'];
	$current_amount = $_POST['current_amount'];
	$percentage_balance = $current_amount/$stock_amount*100;
	$todaysDate = strtotime(date("Y-m-d"));
	$_POST['expiry_date'];
	$expiryDate = strtotime($_POST['expiry_date']);
	if ($expiryDate<=$todaysDate){
		$expiry_date = date("d-M-Y", $expiryDate);
		$errors['Expiry date must be after today so '.$expiry_date.' is an invalid date! '] = "";	
	}else{
		$expiry_date = date("Y-m-d", $expiryDate);
	}
	
	$buying = $_POST['buying'];
	$selling = $_POST['selling'];
	if($buying >= $selling){
		$errors['The selling price ('.$selling.') should be greater than the Buying price ('.$buying.')'] = "";
	}
	//$stock_date = date("Y-m-d h:i:s A");
	
	if(empty($errors)){
		$query = "UPDATE stock set medicine=".$medicine_id.", stock_amount=".$stock_amount.", current_amount=".$current_amount.", percentage_balance = ".$percentage_balance.", expiry_date='".$expiry_date."', buying=".$buying.", selling=".$selling.", stock_date=NOW(), staff=".$_SESSION['user_id']." WHERE id=".$stock_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The stock was sucessfully updated";
			redirect_success("stock_levels.php", $message);
		}
	}
} 
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Add new stock</h1> 
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="edit_stock.php?id=<?php echo $stock_id;?>" method="post">
            <label>Medicine Name:</label><input type="text" name="all_medicine" id="all_medicine" value = "<?php echo $medicine_name;?>" autofocus placeholder="<?php echo $medicine_name;?>" required/>
            <input type="hidden" name="all_medicine_val" id="all_medicine_val" value ="<?php echo $medicine_id?>"/>
            <label>Amount:</label><input type="number" name="stock_amount" id="stock_amount" maxlength="30" value="<?php echo $stock_amount;?>" min="0" max="999999" placeholder="Enter the stock amount" required/>
            <label>Balance:</label><input type="number" name="current_amount" id="current_amount" maxlength="30" value="<?php echo $current_amount;?>" min="0" max="999999" placeholder="Enter the stock amount" required/>
            <label>Expiry:</label><input type="date" name="expiry_date" id="expiry_date" maxlength="30" value="<?php echo $expiry_date;?>" required/>
            <label>Buying Price:</label>
            <input type="number" name="buying" id="buying" maxlength="30" value="<?php echo $buying;?>" min="0" max="999999" placeholder="Enter the unit stock buying price" required/>
            <label>Selling Price:</label>
            <input type="number" name="selling" id="selling" maxlength="30" value="<?php echo $selling;?>" min="0" max="999999" placeholder="Enter the unit selling price" required/>
            <input type="submit" name="edit_stock" id="edit_stock" value="Submit" />
        </form>
        <a href="stock_levels.php">Show Stock</a>
            </div>
        <div id="footer">
         <?php 
		if(isset($connection)){
			mysql_close($connection);
		}
		?>
        <div style="text-align:right"><!--Developed by Mutebi Michael--></div>
        </div>
    </body>
</html>