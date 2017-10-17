<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
//START FORM PROCESSING
if(isset($_GET['id'])){
	$form_id = $_GET['id'];
}
$query = "SELECT * FROM form WHERE id = ".$form_id;
$result = mysql_query($query, $connection);
query_confirmation($result);
$form = mysql_fetch_array($result);

if(isset($_POST['edit_form'])){// Form has been submitted
	$form = strtolower(trim(mysql_prep($_POST['form'])));
	$sales_unit = strtolower(trim(mysql_prep($_POST['sales_unit'])));
	if(empty($errors)){
		$query = "UPDATE form SET form = '".$form."', sales_unit = '".$sales_unit."' WHERE id = ".$form_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The medicine form was sucessfully Updated";
			redirect_success("forms.php", $message);
		}
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <?php show_errors();?>
        <h1>Edit Form</h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form action="edit_form.php?id=<?php echo $form['id']?>" method="post">
        <label>Form:</label><input type="text" name="form" id="form" maxlength="30" value="<?php echo $form['form']?>" required/>
            <label>Sales Unit:</label><input type="text" name="sales_unit" id="sales_unit" maxlength="30" value="<?php echo $form['sales_unit']?>" required/>
            <input type="submit" name="edit_form" id="edit_form" value="Edit Form" />
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