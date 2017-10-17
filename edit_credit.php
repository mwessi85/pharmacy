<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
//START FORM PROCESSING
if(isset($_GET['id'])){
	$credit_id = $_GET['id'];
	$result = mysql_query("SELECT * FROM credit WHERE id=".$credit_id, $connection);
	$credit = mysql_fetch_array($result);
}
if($credit){
if(isset($_POST['edit_credit'])){// Form has been submitted
	$errors = array();
	$amount_paid = $_POST['amount_paid'];
	if($amount_paid>0){
		$query = "UPDATE credit SET amount_paid = (amount_paid + '".$amount_paid."'), balance = (balance + '".$amount_paid."') WHERE id = ".$credit_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The credit was sucessfully Updated";
			redirect_success("credit.php", $message);
		}
	}
}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Edit Credit:</h1>
        <?php show_errors();?>
		<?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="edit_credit.php?id=<?php echo $credit['id']?>" method="post" >
        <p><label>Transaction no.:</label>
        <input type="text" name="transaction_no" id="transaction_no" maxlength="30" value="" placeholder="<?php echo htmlentities($credit['transaction_no'])?>" disabled /></p>
        <p><label>Customer:</label>
        <input type="text" name="customer" id="customer" maxlength="30" value="" placeholder="<?php echo htmlentities($credit['customer'])?>" disabled/></p>
        <p><label>Payment made:</label>
        <input type="text" name="total" id="total" maxlength="30" value="" placeholder="<?php echo htmlentities($credit['amount_paid'] + ($credit['balance']*(-1)))?>" disabled/></p>
        <p><label>Payment made:</label>
        <input type="text" name="payment" id="payment" maxlength="30" value="" placeholder="<?php echo htmlentities($credit['amount_paid'])?>" disabled/></p>
        <p><label>Balance:</label>
        <input type="text" name="balance" id="balance" maxlength="30" value="" placeholder="<?php echo htmlentities($credit['balance'])*(-1)?>" disabled/></p>
        <p><label>New payment:</label>
        <input type="number" name="amount_paid" id="amount_paid" maxlength="30" value="" min="0" autofocus/></p>
        <p><input type="submit" name="edit_credit" id="edit_credit" value="Submit" /></p>
        </form>
        <p><a href="credit.php">View Credit</a></p>
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