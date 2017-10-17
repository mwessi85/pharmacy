<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
//START FORM PROCESSING
if(isset($_GET['id'])){
	$user_id = $_GET['id'];
}
$result = select_user($user_id);
$user = mysql_fetch_array($result);
if(isset($_POST['edit_pass'])){// Form has been submitted
	$errors = array();
	$error_count = 1;
	
	if(!isset($_POST['password1']) || $_POST['password1'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "the first password";
	}
	else if(strlen(trim(mysql_prep($_POST['password1'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Password1";
		} else{
			$password1 = trim(mysql_prep($_POST['password1']));
			$password1 = sha1($password1);
			//unset($errors);
		}
	
	if(!isset($_POST['password2']) || $_POST['password2'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "the second password";
	}
	else if(strlen(trim(mysql_prep($_POST['password2'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Password2";
		} else{
			$password2 = trim(mysql_prep($_POST['password2']));
			$password2 = sha1($password2);
			//unset($errors);
		}
	if(isset($password1) && isset($password2) && isset($_POST['password_old'])){
		//echo "<p>Old Password:".$_POST['password_old']."</p>";
		//echo "<p>Password1:".$password1."</p>";
		//echo "<p>Password2:".$password2."</p>";
		$query = "SELECT password FROM users WHERE id = ".$user_id;
		$result = mysql_query($query, $connection);
		query_confirmation($result);
		if(mysql_num_rows($result) == 1){
			$found_user_pass = mysql_fetch_array($result);
			if ($password1 == $password2 && sha1($_POST['password_old']) == $found_user_pass['password']){
					$new_password = $password1;
			}
		}
	}
	if(empty($errors) && isset($new_password)){
		$query = "UPDATE users SET password = '".$new_password."' WHERE id = ".$user_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The password was sucessfully Updated";
			redirect_success("index.php", $message);
		}else{
			$message = "Update Failed";
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
else {// Form has not been submitted	
	$password1 = "";
	$password2 = "";
	$password_new = "";
}

?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <?php show_errors();?>
        <h1>Edit Password for: <?php echo $user['first_name']." ".$user['last_name'];?></h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form action="edit_pass.php?id=<?php echo $user['id']?>" method="post">
        <p><label>Enter your current password:</label>
        <input type="password_old" name="password_old" id="password_old" maxlength="30" value=""/></p>
        <p><label>Enter new password:</label>
        <input type="password1" name="password1" id="password1" maxlength="30" value=""/></p>
        <p><label>Enter new password again:</label>
        <input type="password2" name="password2" id="password2" maxlength="30" value=""/></p>
        <p><input type="submit" name="edit_pass" id="edit_pass" value="Submit" /></p>
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