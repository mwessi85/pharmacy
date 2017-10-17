<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['add_service'])){
	$errors = array();
	$service = ucfirst(strtolower(trim(mysql_prep($_POST['service']))));
	$unit_cost = $_POST['unit_cost'];
	$description = $_POST['description'];
	$query = "SELECT * FROM services 
	WHERE service = '".$service."'";
	$result = mysql_query($query, $connection);
	if(mysql_num_rows($result) == 1){
		$errors['The service '.$service] = " already exists";
	}
	if(empty($errors)){
		$query = "INSERT INTO services(service, unit_cost, description) 
		VALUES('".$service."', '".$unit_cost."', '".$description."')";
		//echo $query;
		//exit;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The service was sucessfully added";
			redirect_success("services.php", $message);
		}
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Add Service</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_service.php" method="post">
            <label>Service</label><input type="text" name="service" id="service" maxlength="25" value="" required autofocus placeholder="Enter service e.g Dressing"/>
            <label>Unit Cost</label><input type="number" name="unit_cost" id="unit_cost" maxlength="11" value="" required placeholder="Enter cost e.g 1000"/> Ush
            <label>Description</label><input type="text" name="description" id="description" maxlength="100" value="" placeholder="Enter keywords relevant to service to help with searching e.g Wound dressing, plastering" style="width:600px;"/> 
            <input type="submit" name="add_service" id="add_service" value="Submit" />
        </form>
        <p><a href="services.php">Show Services</a></p>
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

