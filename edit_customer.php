<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
//START FORM PROCESSING
if(isset($_GET['id'])){
	$customer_id = $_GET['id'];
	$result = select_customer($customer_id);
	$customer = mysql_fetch_array($result);
}
if(isset($_POST['edit_customer'])){// Form has been submitted
	$errors = array();
	$error_count = 1;
	if(!isset($_POST['first_name']) || $_POST['first_name'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "First Name";
	}
	else if(strlen(trim(mysql_prep($_POST['first_name'])))>TEXTBOX_MAXLENGTH){
		$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "First Name";
	} else{
		$first_name = ucfirst(strtolower(trim(mysql_prep($_POST['first_name']))));
	}
	if(!isset($_POST['last_name']) || $_POST['last_name'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Surname";
	}
	else if(strlen(trim(mysql_prep($_POST['first_name'])))>TEXTBOX_MAXLENGTH){
		$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Surname";
	} else{
		$last_name = ucfirst(strtolower(trim(mysql_prep($_POST['last_name']))));
	}
	if(isset($_POST['gender']) || $_POST['gender'] != ""){
		$gender = $_POST['gender'];
	}
	if(isset($_POST['age']) || $_POST['age'] != ""){
		$age = $_POST['age'];
	}
	if(isset($_POST['phone'])){
		$phone = $_POST['phone'];
	}
	if(isset($_POST['address'])){
		$address = trim(mysql_prep($_POST['address']));
	}
	if(empty($errors)){
		$query = "UPDATE customer SET first_name = '".$first_name."', last_name = '".$last_name."', gender = '".$gender."', age = '".$age."', phone = '".$phone."', address = '".$address."' WHERE id = ".$customer_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The customer was sucessfully Updated";
			redirect_success("customers.php", $message);
		}else{
			$message = "Update Failed";
			$message .= "<br/>".mysql_error();	
			redirect_success("users.php", $message);
		}
	}
	else{
		if(count($errors) == 1){
			$message = "There was 1 error in the form";
		} else{
			$message = "There were ".count($errors)." errors in the form.";
		}	
	}
} 
else {// Form has not been submitted	

}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Edit Customer: <?php echo $customer['first_name']." ".$customer['last_name'];?></h1>
        <?php show_errors();?>
		<?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form action="edit_customer.php?id=<?php echo $customer['id']?>" method="post">
        <p><label>First Name:</label>
        <input type="text" name="first_name" id="first_name" maxlength="30" value="<?php echo htmlentities($customer['first_name'])?>"/></p>
        <p><label>Surname:</label>
        <input type="text" name="last_name" id="last_name" maxlength="30" value="<?php echo htmlentities($customer['last_name'])?>"/></p>
        
        <p><label>Phone:</label>
        <input type="text" name="phone" id="phone" maxlength="30" value="<?php echo htmlentities($customer['phone'])?>"/></p>
        <p><label>Address:</label>
        <input type="text" name="address" id="address" maxlength="30" value="<?php echo htmlentities($customer['address'])?>"/></p>
        <p><input type="submit" name="edit_customer" id="edit_customer" value="Submit" /></p>
        </form>
        <p><a href="customers.php">View Customers</a></p>
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