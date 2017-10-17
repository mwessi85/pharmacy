<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
    	<h1>Settings</h1>
		<?php echo "<p class='notice'>".success_message()."</p>"; ?>
        <p>Welcome to the Pharmacy Management System.</p>
        <p><a href='users.php'>System Users</a></p>
        <p><a href="categories.php">Categories</a></p>
        <p><a href="units.php">Units</a></p>
        <p><a href="forms.php">Forms</a></p>
        <p><a href="frequencies.php">Frequencies</a></p>
        <p><a href="edit_license.php">Edit License</a></p>
   
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