<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
if(isset($_GET['id'])){
	$user_id = $_GET['id'];
}
$result = select_user($user_id);
$user = mysql_fetch_array($result);
if(isset($user)){
	$user_id = $user['id'];
	$first_name = $user['first_name'];
	$last_name = $user['last_name'];
	$username = $user['username'];
	$name = $first_name." ".$last_name;
	$level = $user['level'];
	$level = level($level);
	$status = $user['status'];
	$status = status($status);
	$comment = $user['comment'];
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>User: <?php echo $user['first_name']." ".$user['last_name'];?></h1>
        <table class='results'>
        <tr>
        <td class='even'>Name</td><td class='even'><?php echo $name;?></td>
        <tr>
        <tr>
        <td class='cell_value'>Username</td><td class='cell_value'><?php echo $username;?></td>
        <tr>
        <tr>
        <td class='even'>Level</td><td class='even'><?php echo $level;?></td>
        <tr>
        <tr>
        <td class='cell_value'>Status</td><td class='cell_value'><?php echo $status;?></td>
        <tr>
        <tr>
        <td class='even'>Comment</td><td class='even'><?php echo $comment;?></td>
        <tr>
        
         <?php
        if(isset($user)){
            if($_SESSION['level'] == "admin"){
			echo "
            <tr>
                <td class='cell_value'><a href='edit_user.php?id=".$user_id."'>Edit</a></td>
            </tr>";
			}
            echo "<tr>
                <td class='cell_value'><a href='edit_pass.php?id=".$user_id."'>Change Password</a></td>
            </tr>";
        }
        ?>
        </table>
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