<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php
if(!isset($success_message)){
	$success_message = success_message();
}else{
	$success_message = "";	
}
//START FORM PROCESSING
if(isset($_POST['login'])){// Form has been submitted
	$errors = array();
	$error_count = 1;
	if(isset($_POST['username']) && $_POST['username'] != ""){
		$username = trim(mysql_prep($_POST['username']));
	}else{
		$errors['Error '.$error_count++.'! No Value entered for '] = "Username";	
	}
	if(isset($_POST['password']) && $_POST['password'] != ""){
		$password = trim(mysql_prep($_POST['password']));
		$encrypted_password = sha1($password);
	}else{
		$errors['Error '.$error_count++.'! No Value entered for '] = "Password";	
	}
	if($_POST['username'] == "" && $_POST['password'] == ""){
		unset($errors);
	}
	
	if(!empty($username) && !empty($encrypted_password)){	
		$query = "SELECT id, username, level FROM users WHERE username = '".$username."' AND password = '".$encrypted_password."'";
		$result = mysql_query($query, $connection);
		query_confirmation($result);
		if(mysql_num_rows($result) == 1){
			$found_user = mysql_fetch_array($result);
			$_SESSION['user_id'] = $found_user['id'];
			$_SESSION['username'] = $found_user['username'];
			$_SESSION['level'] = $found_user['level'];
			$message = "User was sucessfully logged in";
			redirect_success("main.php", "User logged in Successfuly!");
		}else{
			$errors[''] = "Username and password combination did not match an entry";
				
		}
	} 
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" title="Sinorca (screen)" />
<title>Pharmacy Inventory System</title>	
</head>
<body>
    <div id="header">
      <div class="superHeader">  
      </div>

      <div class="midHeader">
        <h1 class="headerTitle">PHARMACY MANAGEMENT</h1>
      </div>
      <div class="subHeader">
       <?php //show_logout();?>
      </div>
    </div>
<div id="main-copy" style="height:536px">
        <h1>Login</h1>
        <?php 
        $sucess_message = success_message();
        if(isset($sucess_message)){
        echo "<p class='notice'>".$sucess_message."</p>";
        }
        ?>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <p>Enter Credentials to login</p>
        <form action="index.php" method="post">
        <p><label>Username:</label>
        <input type="text" name="username" id="username" /></p>
        <p><label>Password:</label>
        <input type="password" name="password" id="password" /></p>
        <p><input type="submit" name="login" id="login" value="Login" /></p>
        </form>
   
    	    </div>
        <div id="footer">
         <?php 
		if(isset($connection)){
			mysql_close($connection);
		}
		?>
        <p><!--Developed by Mutebi Michael--></p>
        </div>
    </body>
</html>