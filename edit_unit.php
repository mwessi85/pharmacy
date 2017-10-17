<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
//START FORM PROCESSING
if(isset($_GET['id'])){
	$unit_id = $_GET['id'];
}
$query = "SELECT * FROM units WHERE id = ".$unit_id;
$result = mysql_query($query, $connection);
query_confirmation($result);
$unit = mysql_fetch_array($result);

if(isset($_POST['edit_unit'])){// Form has been submitted
	if(!isset($_POST['value']) || $_POST['value'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Unit";
	}
	else if(strlen(trim(mysql_prep($_POST['value'])))>10){
			$errors['Maximum length exceeded for '] = "Unit";
		} else{
			$value = strtolower(trim(mysql_prep($_POST['value'])));		
		}
	if(empty($errors)){
		$query = "UPDATE units SET value = '".$value."' WHERE id = ".$unit_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The unit was sucessfully Updated";
			redirect_success("units.php", $message);
		}else{
			$message = "Update Failed";
			$message .= "<br/>".mysql_error();	
			redirect_success("units.php", $message);
		}
	}
	else{

	}
} 
else {// Form has not been submitted	

}

?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <?php show_errors();?>
        <h1>Edit unit</h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form action="edit_unit.php?id=<?php echo $unit['id']?>" method="post">
        <p>
        <label>Unit Name:</label>
        <input type="text" name="value" id="value" maxlength="30" value="<?php echo htmlentities($unit['value'])?>"/></p>
        <p><input type="submit" name="edit_unit" id="edit_unit" value="Edit unit" /></p>
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