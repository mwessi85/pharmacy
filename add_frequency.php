<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if(isset($_POST['add_frequency'])){// Form has been submitted
	$error_count = array();
	$errors = array();
	$frequency = strtoupper(trim(mysql_prep($_POST['frequency'])));
	$times = $_POST['times'];
	$details = ucfirst(trim(mysql_prep($_POST['details'])));		
	if(empty($errors)){
		$query = "INSERT INTO frequency (frequency, times, details) VALUES('".$frequency."', ".$times.", '".$details."');";
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The frequency was sucessfully created";
			redirect_success("frequencies.php", $message);
		}
	}
}  
?> 
<?php include("includes/header.php");?>
<div id="main-copy"> 
		<?php show_errors();?>
        <h1>Add Frequency</h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_frequency.php" method="post">
            <label>Frequency:</label>
            <input type="text" name="frequency" id="frequency" maxlength="30" value="" required autofocus placeholder="Enter frequency e.g OD, BD, TD, START"/>
            <label>Number of times a day:</label><input type="number" name="times" id="times" maxlength="33" value="" min="1" max="12" placeholder="Enter number of times a day" required/>
            <label>Details:</label>
            <input type="text" name="details" id="details" maxlength="250" value="" required placeholder="Enter details e.g 'Once a day' for BD"/>
            <input type="submit" name="add_frequency" id="add_frequency" value="Add" />
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

