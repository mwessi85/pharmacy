<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['add_stock'])){
	$errors = array();
	$medicine_id = $_POST['medicine_val'];
	$result_medicine = select_medicine($medicine_id);
	$stock_amount = $_POST['stock_amount'];
	$current_amount = $stock_amount;
	$today = date("Y-m-d");
	$todaysDate = strtotime($today);
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
	$stock_date = date("Y-m-d h:i:s A");
	if(empty($errors)){
		$query = "INSERT INTO stock(medicine, stock_amount, current_amount, expiry_date, buying, selling, stock_date, staff) 
		VALUES(".$medicine_id.", ".$stock_amount.", ".$current_amount.", '".$expiry_date."', ".$buying.", ".$selling.", NOW(), ".$_SESSION['user_id'].")";
		echo $query;
		//exit;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "Stock sucessfully added";
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
        <form autocomplete="off" action="add_stock.php" method="post">
            <label>Medicine Name:</label><input type="text" name="medicine" id="medicine" value = "" autofocus placeholder="Slowly key in the medicine name" required/>
            <input type="hidden" name="medicine_val" id="medicine_val" value = ""/>
            <label>Amount:</label><input type="number" name="stock_amount" id="stock_amount" maxlength="30" value="" min="0" max="999999" placeholder="Enter the stock amount" required/>
            <label>Expiry:</label><input type="date" name="expiry_date" id="expiry_date" maxlength="30" value="" required/>
            <label>Buying Price:</label>
            <input type="number" name="buying" id="buying" maxlength="30" value="" min="0" max="999999" placeholder="Enter the unit stock buying price" required/>
            <label>Selling Price:</label>
            <input type="number" name="selling" id="selling" maxlength="30" value="" min="0" max="999999" placeholder="Enter the unit selling price" required/>
            <input type="submit" name="add_stock" id="add_stock" value="Submit" />
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
