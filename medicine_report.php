<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/config.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Medicine Sales Report</h1>
        <?php if(!empty($_GET['message'])){echo "<p class=\"notice\">".$_GET['message']."</p>";}?>
        <form autocomplete="off" action="report_medicine.php" method="post">
        <label>Medicine Name:</label><input type="search" name="medicine" id="medicine" value = ""/>
            <input type="hidden" name="medicine_val" id="medicine_val" value = ""/>
        <input type="submit" value="Submit" />
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