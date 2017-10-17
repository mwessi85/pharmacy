<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/config.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Medicine Sales Report</h1>
        
        <form autocomplete="off" action="report_date_sales.php" method="post">
        <label>Date:</label><input type="date" name="date" id="date" maxlength="30" value=""/>
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