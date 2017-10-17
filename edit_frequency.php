<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if($_GET['id']){
	$frq_id= $_GET['id'];
	$sql = "SELECT * FROM frequency WHERE id = ".$frq_id; 
	$frq_result = mysql_query($sql, $connection);
	$old_frequency = mysql_fetch_array($frq_result);
}
if(isset($_POST['edit_frequency'])){// Form has been submitted
	$error_count = array();
	$errors = array();
	$frequency = strtoupper(trim(mysql_prep($_POST['frequency'])));
	$times = $_POST['times'];
	$details = ucfirst(trim(mysql_prep($_POST['details'])));		
	if(empty($errors)){
		$query = "UPDATE frequency SET frequency = '".$frequency."', times = ".$times.", details = '".$details."' WHERE id = ".$frq_id;
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
        <h1>Edit Frequency</h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="edit_frequency.php?id=<?php echo $frq_id;?>" method="post">
            <label>Frequency:</label>
            <input type="text" name="frequency" id="frequency" maxlength="30" value="<?php echo $old_frequency['frequency'];?>" required autofocus placeholder="Enter frequency e.g OD, BD, TD, START"/>
            <label>Number of times a day:</label><input type="number" name="times" id="times" maxlength="33" value="<?php echo $old_frequency['times'];?>" min="1" max="12" placeholder="Enter number of times for the prescription" required/>
            <label>Details:</label>
            <input type="text" name="details" id="details" maxlength="250" value="<?php echo $old_frequency['details'];?>" required placeholder="Enter details e.g 'Once a day' for BD"/>
            <input type="submit" name="edit_frequency" id="edit_frequency" value="Edit" />
            
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

