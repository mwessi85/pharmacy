<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
	<div id="main-copy">
    	<h1>Reports</h1>
		<?php echo "<p class='notice'>".success_message()."</p>"; ?>
        <table>
        <tr>
        <td><strong>Medicine</strong></td>
        <td><strong>Services</strong></td>
        <td><strong>Tests</strong></td>
        </tr>
        <tr>
        <td><p><a href='report_sales.php?report_name=General Sales Report'>General Medicine Sales Report</a></p></td>
        <td><p><a href='report_service_sales.php?report_name=General Sales Report'>General Service Sales Report</a></p></td>
        <td><p><a href='report_test_sales.php?report_name=General Sales Report'>General Test Sales Report</a></p></td>
        </tr>
        <tr>
        <td><p><a href='medicine_report.php'>Medicine Sales Report</a></p></td>
        <td><p><a href='service_report.php'>Service Sales Report</a></p></td>
        <td><p><a href='test_report.php'>Test Sales Report</a></p></td>
        </tr>
        <tr>
        <td><p><a href='date_report.php'>Medicine Sales Report By Date</a></p></td>
        <td><p><a href='date_sevice_report.php'>Service Sales Report By Date</a></p></td>
        <td><p><a href='date_test_report.php'>Test Report By Date</a></p></td>
        </tr>
        <tr>
        <td><p><a href='report_daily_sales.php'>Daily Medicine Sales Report</a></p></td>
        <td><p><a href='report_service_daily_sales.php'>Daily service Sales Report</a></p></td>
        <td><p><a href='report_test_daily_sales.php'>Daily Test Sales Report</a></p></td>
        </tr>
        <tr>
        <td><p><a href='from_to_report_sales.php'>Medicine Sales by Date Range</a></p></td>
        <td><p><a href='service_from_to_report_sales.php'>Service Sales by Date Range</a></p></td>
        <td><p><a href='test_from_to_report_sales.php'>Test Sales by Date Range</a></p></td>
        </tr>
        </table>
   </div>
        <div id="footer">
         <?php 
		if(isset($connection)){
			mysql_close($connection);
		}
		?>
        <div style="text-apgn:right"><!--Developed by Mutebi Michael--></div>
        </div>
    </body>
</html>