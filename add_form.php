<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if(isset($_POST['add_form'])){// Form has been submitted
	$error_count = array();
	$errors = array();
	$form = strtolower(trim(mysql_prep($_POST['form'])));
	$sales_unit = strtolower(trim(mysql_prep($_POST['sales_unit'])));		
	if(empty($errors)){
		$query = "INSERT INTO form(form, sales_unit) VALUES('".$form."', '".$sales_unit."')";
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The form was sucessfully created";
			redirect_success("forms.php", $message);
		}
	}
}  
?> 
<?php include("includes/header.php");?>
<div id="main-copy"> 
		<?php show_errors();?>
        <h1>Create a Form</h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_form.php" method="post">
            <label>Form:</label><input type="text" name="form" id="form" maxlength="30" value="" placeholder="e.g. tablet or capsule or syrup" required/>
            <label>Sales Unit:</label><input type="text" name="sales_unit" id="sales_unit" maxlength="30" value="" placeholder="e.g. tablet or capsule or bottle" required/>
            <input type="submit" name="add_form" id="add_form" value="Add Form" />
        </form>
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

