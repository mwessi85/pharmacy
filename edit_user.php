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
if(isset($_POST['edit_user'])){// Form has been submitted
	$errors = array();
	$error_count = 1;
	if(!isset($_POST['first_name']) || $_POST['first_name'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "First Name";
	}
	else if(strlen(trim(mysql_prep($_POST['first_name'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "First Name";
		} else{
			$first_name = trim(mysql_prep($_POST['first_name']));
			//unset($errors);
		}
	if(!isset($_POST['last_name']) || $_POST['last_name'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Surname";
	}
	else if(strlen(trim(mysql_prep($_POST['first_name'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Surname";
		} else{
			$last_name = trim(mysql_prep($_POST['last_name']));
			//unset($errors);
		}
	if(!isset($_POST['username']) || $_POST['username'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Username";
	}
	else if(strlen(trim(mysql_prep($_POST['username'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Username";
		} else{
			$username = trim(mysql_prep($_POST['username']));
			//unset($errors);
		}
	/*if(!isset($_POST['password']) || $_POST['password'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Password";
	}
	else if(strlen(trim(mysql_prep($_POST['password'])))>TEXTBOX_MAXLENGTH){
			$errors['Error '.$error_count++.'! Maximum length exceeded for '] = "Password";
		} else{
			$password = trim(mysql_prep($_POST['password']));
			$password = sha1($password);
			//unset($errors);
		}*/
	if(!isset($_POST['status']) || $_POST['status'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Active";
	}
	else{
		$status = trim(mysql_prep($_POST['status']));	
	}
	if(!isset($_POST['level']) || $_POST['level'] == ""){
		$errors['Error '.$error_count++.'! No Value entered for '] = "Level";
	}
	else{
		$level = trim(mysql_prep($_POST['level']));	
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
		$query = "UPDATE users SET status = '".$status."', username = '".$username."', first_name = '".$first_name."', last_name = '".$last_name."', level = '".$level."', comment = '".$comment."' WHERE id = ".$user_id;
		$result = mysql_query($query, $connection);
		if(isset($result)){
			unset($result);
			$message = "The user was sucessfully Updated";
			redirect_success("users.php", $message);
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
	$username = "";
	$password = "";
}

?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Edit User: <?php echo $user['first_name']." ".$user['last_name'];?></h1>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <?php if(!empty($errors)){show_errors($errors);}?>
        <form action="edit_user.php?id=<?php echo $user['id']?>" method="post">
        <p><label>First Name:</label>
        <input type="text" name="first_name" id="first_name" maxlength="30" value="<?php echo htmlentities($user['first_name'])?>"/></p>
        <p><label>Surname:</label>
        <input type="text" name="last_name" id="last_name" maxlength="30" value="<?php echo htmlentities($user['last_name'])?>"/></p>
        <p><label>Username:</label>
        <input type="text" name="username" id="username" maxlength="30" value="<?php echo htmlentities($user['username'])?>"/></p>
        <!--<p><label>Password:</label>
        <input type="password" name="password" id="password" maxlength="30" value="<?php //echo htmlentities($password)?>"/></p>-->
        <p><label>Status:</label>
        <input type="radio" name="status" value="1" 
        <?php 
        if($user['status'] == 1){
                echo " checked ";
        }
        ?>/>
        Active        
        <input type="radio" name="status" value="0" 
        <?php 
        if($user['status'] == 0){
                echo " checked ";
        }
        ?>/> 
        Inactive</p>
        <p><label>Level:</label>
        <select name="level" id="level">
        <option value="admin" 
        <?php if($user['username'] == "admin"){
                echo " selected=\"selected\"";
            }?>
        >Administrator</option>
        <option value="staff" 
        <?php if($user['username'] == "staff"){
                echo " selected=\"selected\"";
            }?>
        >Staff</option>
        <option value="user" 
        <?php if($user['username'] == "user"){
                echo " selected=\"selected\"";
            }?>
        >User</option>
        </select></p>
        <p><label>Comment:</label>
        <textarea name="comment"></textarea></p>
        <p><input type="submit" name="edit_user" id="edit_user" value="Submit" /></p>
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