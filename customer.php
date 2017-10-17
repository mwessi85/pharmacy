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
<?php 
if(isset($_GET['id'])){
	$customer_id = $_GET['id'];
}
$result = select_customer($customer_id);
$customer = mysql_fetch_array($result);
if(isset($customer)){
	$customer_id = $customer['id'];
	$first_name = $customer['first_name'];
	$last_name = $customer['last_name'];
	$phone = $customer['phone'];
	$address = $customer['address'];
}
?>
<?php include("includes/header.php");?>
<div id="main-copy"> 
        <h1>Customer: <?php echo $customer['first_name']." ".$customer['last_name'];?></h1>
        <table class='results'>
        <tr>
        <td class='even'>Name:</td><td class='even'><?php echo $first_name." ".$last_name;?></td>
        </tr>
        <tr>
        <td class='cell_value'>Phone</td><td class='cell_value'><?php echo $phone;?></td>
        </tr>
        <tr>
        <td class='even'>Adress</td><td class='even'><?php echo $address;?></td>
        </tr>
        <?php
        if(isset($customer)){
        echo "
        <tr>
        <td class='cell_value'><a href='edit_customer.php?id=".$customer_id."'>Edit</a></td>
        </tr>";
        }
        ?>
        </table>
        <table class='results'>
        <tr>
        <td class='column_head'>Customer</td>
        <td class='column_head'>Transaction No.</td>
        <td class='column_head'>Amount paid</td>
        <td class='column_head'>Balance</td>
        <td class='column_head'>Staff</td>
        <td class='column_head'></td>
        </tr>
        <?php 
		$query = "SELECT * FROM credit WHERE customer_id = ".$_GET['id'];
		//echo $query;
		$result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        $i = 0;
		$sum = 0;
		if(isset($result)){
			while($credit = mysql_fetch_array($result)){
				$credit_id = $credit['id'];
				$transaction_no = $credit['transaction_no'];
				$amount_paid = $credit['amount_paid'];
				$balance = $credit['balance'];
				$customer_id = $credit['customer_id'];
				$customer = $credit['customer'];
				$staff = $credit['staff'];
				echo "
				<tr>
				<td ".even_strip($i)."><a href='customer.php?id=".$customer_id."'>".$customer."</a></td>
				<td ".even_strip($i).">".$transaction_no."</td>
				<td ".even_strip($i).">".$amount_paid."</td>
				<td ".even_strip($i).">".$balance."</td>
				<td ".even_strip($i).">".$staff."</td>
				<td ".even_strip($i)."><a href='edit_credit.php?id=".$credit_id."'>Edit</a></td>
				</tr>";
				$i++;
				$sum = $sum+$balance;
			}
		$range = $result_list_offset." to ".($result_list_offset+$rows-1);
		}
        ?>
         <tr>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'><?php echo $sum;?></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        </table>
        <p><a href="customers.php">View Customers</a></p>
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
