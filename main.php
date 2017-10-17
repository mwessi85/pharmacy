<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Welcome</h1>
		<?php echo "<p class='notice'>".success_message()."</p>"; ?>
        <p>Welcome to the Pharmacy Management System.</p>
        </div>
        <div id="footer">
         <?php 
		if(isset($connection)){
			check_sense();
			mysql_close($connection);
		}
		?>
        <div style="text-align:right"><!--Developed by Mutebi Michael--></div>
        </div>
    </body>
</html>