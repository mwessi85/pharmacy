<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if(isset($_GET['id'])){
	$sql = "SELECT * FROM tests WHERE id=".$_GET['id'];
	$prepop_result= mysql_query($sql, $connection);
    $prepop = mysql_fetch_assoc($prepop_result);
}
if(isset($_POST['edit_test'])){
	$errors = array();
	$test = ucfirst(strtolower(trim(mysql_prep($_POST['test']))));
	$unit_cost = $_POST['unit_cost'];
	$description = $_POST['description'];
	$query = "SELECT * FROM test 
	WHERE test = '".$test."'";
	$result = mysql_query($query, $connection);
	if(empty($errors)){
		$query = "UPDATE tests SET 
		test = '".$test."', unit_cost ='".$unit_cost."', description = '".$description."' WHERE id=".$_GET['id'];
		//echo $query;
		//exit;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The test was sucessfully uupdated";
			redirect_success("tests.php", $message);
		}
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Edit Test</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="edit_test.php?id=<?php echo $_GET['id'];?>" method="post">
            <label>Test</label>
            <input type="text" name="test" id="test" maxlength="25" value="<?php echo $prepop['test']?>" required autofocus/>
             <label>Unit Cost</label><input type="number" name="unit_cost" id="unit_cost" maxlength="11" value="<?php echo $prepop['unit_cost']?>" required/> Ush
            <label>Description</label><input type="text" name="description" id="description" maxlength="100" value="<?php echo $prepop['description']?>"/> 
            <input type="submit" name="edit_test" id="edit_test" value="Submit" />
        </form>
        <p><a href="medicines.php">Show Tests</a></p>
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

