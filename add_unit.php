<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if(isset($_POST['add_unit'])){// Form has been submitted
	$error_count = array();
	$errors = array();
	if(!isset($_POST['value']) || $_POST['value'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Unit";
	}
	else if(strlen(trim(mysql_prep($_POST['value'])))>10){
		$errors['Maximum length exceeded for '] = "Unit";
	} else{
		$value = strtolower(trim(mysql_prep($_POST['value'])));		
	}
	if(empty($errors)){
		$query = "INSERT INTO units (value) VALUES('".$value."');";
		$result = mysql_query($query, $connection);
	if(isset($result)){
		unset($result);
		$message = "The unit was sucessfully created";
		redirect_success("units.php", $message);
	}else{
		$message = "The unit could not be created";
		$message .= "<br/>".mysql_error();	
		redirect_success("units.php", $message);
		}
	}
}  
?> 
<?php include("includes/header.php");?>
<div id="main-copy"> 
		<?php show_errors();?>
        <h1>Create a unit</h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_unit.php" method="post">
            <label>Unit Name:</label><input type="text" name="value" id="value" maxlength="30" value=""/>
            <input type="submit" name="add_unit" id="add_unit" value="Add unit" />
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

