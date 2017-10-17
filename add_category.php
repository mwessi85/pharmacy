<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['add_category'])){
	$errors = array();
	$error_count = 1;
	if(!isset($_POST['name']) || $_POST['name'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Category Name";
	}
	else if(strlen(trim(mysql_prep($_POST['name'])))>TEXTBOX_MAXLENGTH){
		$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Category Name";
		} else{
		$category_name = ucfirst(strtolower(trim(mysql_prep($_POST['name']))));
	}
	if(isset($_POST['comment'])){
		$comment = $_POST['comment'];
		$comment = ucfirst(strtolower($_POST['comment']));
		$comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$_POST['comment']);
	}
	else{
		$comment = "";	
	}
	if(empty($errors)){
		$query = "INSERT INTO category (name, comment) 
		VALUES('".$category_name."','".$comment."');";
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The category was sucessfully created";
			redirect_success("categories.php", $message);
		}else{
			$message = "The category could not be created";
			$message .= "<br/>".mysql_error();	
			redirect_success("categories.php", $message);
		}
	}
} 

?> 
<?php include("includes/header.php");?>
<div id="main-copy">
<h1>Create A Category</h1>	
            <?php show_errors();?>
            <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
            <form autocomplete="off" action="add_category.php" method="post">
            <label>Category Name:</label><input type="text" name="name" id="name" maxlength="30" value=""/>
            <label>Comment:</label><textarea name="comment"></textarea>
            <input type="submit" name="add_category" id="add_category" value="Add Category" />
            </form>
            <a href="categories.php">View Categories</a>
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
