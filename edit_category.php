<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
//START FORM PROCESSING
if(isset($_GET['id'])){
	$category_id = $_GET['id'];
}
$query = "SELECT * FROM category WHERE id = ".$category_id;
$result = mysql_query($query, $connection);
query_confirmation($result);
$category = mysql_fetch_array($result);

if(isset($_POST['edit_category'])){// Form has been submitted
	$errors = array();
	$error_count = 1;
	if(!isset($_POST['name']) || $_POST['name'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Category Name";
	}
	else if(strlen(trim(mysql_prep($_POST['name'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Category Name";
		} else{
			$category_name = ucfirst(strtolower(trim(mysql_prep($_POST['name']))));
			echo "<p>Category Name: ".$category_name."</p>";		}
	if(isset($_POST['comment'])){
		$comment = $_POST['comment'];
		$comment = ucfirst(strtolower($_POST['comment']));
		$comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$_POST['comment']);
	}
	else{
		$comment = "";	
	}
	if(empty($errors)){
		$query = "UPDATE category SET name = '".$category_name."', comment = '".$comment."' WHERE id = ".$category_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The category was sucessfully Updated";
			redirect_success("categories.php", $message);
		}else{
			$message = "Update Failed";
			$message .= "<br/>".mysql_error();	
			redirect_success("categories.php", $message);
		}
	}
	else{
		if(count($errors) == 1){
			$message = "There was 1 error in the form";
		} else{
			$message = "There were ".count($errors)." errors in the form.";
		}	
	}
} 
else {// Form has not been submitted	

}
?>
<?php include("includes/header.php");?>
<div id="main-copy"> 
        <?php show_errors();?>
        <h1>Edit Category</h1>
		<?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form action="edit_category.php?id=<?php echo $category['id']?>" method="post">
        <p><label>Category Name:</label>
        <input type="text" name="name" id="name" maxlength="30" value="<?php echo htmlentities($category['name'])?>"/></p>
        <p><label>Comment:</label>
        <textarea name="comment"><?php echo htmlentities($category['comment'])?></textarea></p>
        <p><input type="submit" name="edit_category" id="edit_category" value="Edit Category" /></p>
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