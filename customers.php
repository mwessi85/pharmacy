<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
// Prev... Next...
if(isset($_GET['result_list_offset'])){
	$result_list_offset = $_GET['result_list_offset'];
}
else{
	$result_list_offset = 1;
}
$results_per_page = 13;
if(isset($_GET['increment'])){
	$result_list_offset += $_GET['increment']*$results_per_page;
}
if($result_list_offset<1){
		$result_list_offset = 1;
}
if(isset($_GET['generate_xls'])){
	export_customers();
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Customers</h1>
        <?php echo "<p class='notice'>".success_message()."</p>"; ?>
		<?php show_errors();?>
        <div>
        <form autocomplete="off" action="customers.php" method="post">
        <label>Name:</label><input type="text" name="customer_search" id="customer_search" maxlength="30" value=""/>
        <input type="submit" name="search" id="search" value="Search" />
        </form>
        </div>
        <table class='results'>
        <tr>
        <td class='column_head'>Name</td>
        <td class='column_head'>Phone</td>
        <td class='column_head'>Address</td>
        <td class='column_head'></td>
        </tr>
        <?php 
        if(isset($_POST['search'])){
			$query = "SELECT * FROM customer WHERE first_name LIKE '%".$_POST['customer_search']."%' OR last_name LIKE '%".$_POST['customer_search']."%' ORDER BY first_name, last_name LIMIT ".($result_list_offset-1).", ".$results_per_page;	
		}else{
        	$query = "SELECT * FROM customer LIMIT ".($result_list_offset-1).", ".$results_per_page;	
		}
		//echo $query;
		$result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        $i = 0;
		if(isset($result)){
			while($customer = mysql_fetch_array($result)){
				$customer_id = $customer['id'];
				$first_name = $customer['first_name'];
				$last_name = $customer['last_name'];
				$phone = $customer['phone'];
				$address = $customer['address'];
				echo "
				<tr>
				<td ".even_strip($i)."><a href=\"customer.php?id=".$customer_id."\">".$last_name." ".$first_name."</a></td>
				<td ".even_strip($i).">".$phone."</td>
				<td ".even_strip($i).">".ucfirst($address)."</td>
				<td ".even_strip($i)."><a href='edit_customer.php?id=".$customer_id."'>Edit</a></td>
				</tr>";
				$i++;
			}
		$range = $result_list_offset." to ".($result_list_offset+$rows-1);
		}
        ?>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='customers.php?id=".$customer_id."&increment=-1&result_list_offset=$result_list_offset'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='customers.php?increment=1&result_list_offset=$result_list_offset'>>></a>" : "")." ";?></p>
        <p><a href="customers.php?generate_xls=true">Export to Excel</a></p>
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