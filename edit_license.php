<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
//START FORM PROCESSING
if(isset($_POST['edit_license'])){// Form has been submitted
	$errors = array();
	//echo $_POST['pharm'];
	if(isset($_POST['pharm'])){
		$query = "UPDATE pharm SET pharm = '".$_POST['pharm']."' WHERE id = 1";
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "License was registered: Login to check its validity";
			redirect_success("index.php", $message);
		}
	}
	else{
		$errors['No license was given '] = "";	
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Edit License</h1>
        <?php show_errors();?>
		<?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form action="edit_license.php" method="post">
        <p><label>License Code:</label>
        <input type="text" name="pharm" id="pharm" maxlength="30" value="" required autofocus/></p>
        <p><input type="submit" name="edit_license" id="edit_license" value="Enter" /></p>
        </form>
        <p><a href="settings.php">Back</a></p>
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