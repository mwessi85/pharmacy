<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['add_test'])){
	$errors = array();
	$test = ucfirst(strtolower(trim(mysql_prep($_POST['test']))));
	$unit_cost = $_POST['unit_cost'];
	$description = $_POST['description'];
	$query = "SELECT * FROM tests 
	WHERE test = '".$test."'";
	$result = mysql_query($query, $connection);
	if(mysql_num_rows($result) == 1){
		$errors['The test '.$test] = " already exists";
	}
	if(empty($errors)){
		$query = "INSERT INTO tests(test, unit_cost, description) 
		VALUES('".$test."', '".$unit_cost."', '".$description."')";
		//echo $query;
		//exit;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The test was sucessfully added";
			redirect_success("tests.php", $message);
		}
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Add Test</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_test.php" method="post">
            <label>Test</label><input type="text" name="test" id="test" maxlength="25" value="" required autofocus placeholder="Enter test e.g Dressing"/>
            <label>Unit Cost</label><input type="number" name="unit_cost" id="unit_cost" maxlength="11" value="" required placeholder="Enter cost e.g 1000"/> Ush
            <label>Description</label><input type="text" name="description" id="description" maxlength="100" value="" placeholder="Enter keywords relevant to test to help with searching e.g Wound dressing, plastering" style="width:600px;"/> 
            <input type="submit" name="add_test" id="add_test" value="Submit" />
        </form>
        <p><a href="tests.php">Show Tests</a></p>
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

