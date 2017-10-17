<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['add_customer'])){
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
	
		$query = "INSERT INTO customer (first_name, last_name, gender, age, phone, address) VALUES('".$first_name."','".$last_name."','".$gender."','".$age."','".$phone."','".$address."')";
	$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The customer was sucessfully created";
			redirect_success("customers.php", $message);
		}else{
			$message = "The customer could not be created";
			$message .= "<br/>".mysql_error();	
			redirect_success("customers.php", $message);
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

?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Create A new customer</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_customer.php" method="post">
            <label>First Name:</label><input type="text" name="first_name" id="first_name" maxlength="30" value=""/>
            <label>Surname:</label><input type="text" name="last_name" id="last_name" maxlength="30" value=""/>
            <label>Gender</label>
            <input type="radio" name="gender" value="male" /> Male
            <input type="radio" name="gender" value="female" /> Female
            <label>Age:</label><input type="text" name="age" id="age" maxlength="30" value=""/>
            <label>Phone:</label>
            <input type="text" name="phone" id="phone" maxlength="30" value=""/>
            <label>Address:</label><input type="text" name="address" id="address" maxlength="30" value=""/>
            <input type="submit" name="add_customer" id="add_customer" value="Submit" />
        </form>
        <a href="customers.php">View Customers</a>
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