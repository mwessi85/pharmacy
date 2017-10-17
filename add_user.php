<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

//Submit form
if(isset($_POST['add_user'])){
	$errors = array();
	$error_count = 1;
	//Firstname
	if(isValidInt($_POST['first_name'])){
		$errors["'Firstname' should not be a number"] = "";
	}
	if(strlen(trim(mysql_prep($_POST['first_name'])))>20){
		$errors["Maximum length of 20 characters exceeded for "] = "'Firstname'";
	}
	$first_name = strtolower(trim(mysql_prep($_POST['first_name'])));
	//Surname
	if(isValidInt($_POST['last_name'])){
		$errors["'Surname' should not be a number"] = "";
	}
	if(strlen(trim(mysql_prep($_POST['last_name'])))>20){
		$errors["Maximum length of 20 characters exceeded for "] = "'Surname'";
	}
	$last_name = strtolower(trim(mysql_prep($_POST['last_name'])));
	
	if(!isset($_POST['username']) || $_POST['username'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Username";
	}
	$query = "SELECT * FROM users WHERE username = ".$_POST['username'];
	$result = mysql_query($query);
	if(mysql_num_rows($query) == 0){
		if(strlen(trim(mysql_prep($_POST['username'])))>20){
				$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Username";
		}
		$username = trim(mysql_prep($_POST['username']));
	}
	else{
		$errors["Username ".$_POST['username']." already exists"] = "";
	}
	if(!isset($_POST['password']) || $_POST['password'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Password";
	}
	else if(strlen(trim(mysql_prep($_POST['password'])))>20){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Password";
		}
		else{
		$password = trim(mysql_prep($_POST['password']));
		$password = sha1($password);
	}
	if(isset($_POST['status'])){
		$status = 1;
	}
	if(isset($_POST['level'])){
		$level = trim(mysql_prep($_POST['level']));
	}
	else if(isset($_POST['comment'])){
			$comment = $_POST['comment'];
			$comment = ucfirst(strtolower($_POST['comment']));
			$comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$_POST['comment']);
		}
		else{
			$comment = "";	
		}
	if(empty($errors)){
		$query = "INSERT INTO users 
		(status, username, password, first_name, last_name, level, comment)
		VALUES('".$status."','".$username."','".$password."','".$first_name."','".$last_name."','".$level."','".$comment."');";
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The user was sucessfully created";
			redirect_success("users.php", $message);
		}else{
			$message = "The user could not be created";
			$message .= "<br/>".mysql_error();	
			redirect_success("users.php", $message);
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
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Add a staff Member</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_user.php" method="post">
        <label>First Name:</label><input type="text" name="first_name" id="first_name" maxlength="30" value=""/>
        <label>Surname:</label><input type="text" name="last_name" id="last_name" maxlength="30" value=""/>
        <label>Username:</label><input type="text" name="username" id="username" maxlength="30" value=""/>
        <label>Password:</label><input type="password" name="password" id="password" maxlength="30" value=""/>
        <label>Status</label>
        <input type="radio" name="status" value="1" checked /> Active        
        <input type="radio" name="status" value="0" /> Inactive
        <label>Level</label>
        <select name="level" id="level">
            <option selected="selected">Select</option>
            <option value="admin">Administrator</option>
            <option value="staff">Staff</option>
        </select></p>
        <label>Comment</label>
        <textarea name="comment"></textarea>
        <input type="submit" name="add_user" id="add_user" value="Submit" />
        </form>
        <a href="users.php">View System Users</a>
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