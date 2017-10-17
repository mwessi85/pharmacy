<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/config.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
unset($_SESSION['customer']);
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Medicine Sales Report By Date Range</h1>
        
        <form autocomplete="off" action="report_from_to_sales.php" method="post">
        <label>Date from:</label><input type="date" name="fromdate" id="fromdate" maxlength="30" value=""/>
            <label>Date to:</label><input type="date" name="todate" id="todate" maxlength="30" value=""/>
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