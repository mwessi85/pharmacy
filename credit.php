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
	export_credit();
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Credit</h1>
        <?php echo "<p class='notice'>".success_message()."</p>"; ?>
		<?php show_errors();?>
        <div>
        <form autocomplete="off" action="credit.php" method="post">
        <label>Transaction No.:</label><input type="text" name="transaction_no" id="transaction_no" maxlength="30" value=""/>
        <label>Customer Name:</label><input type="text" name="credit_search" id="credit_search" maxlength="30" value=""/>
        <input type="submit" name="search" id="search" value="Search" />
        </form>
        </div>
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
        $result_delete = mysql_query("DELETE * FROM credit WHERE balance >= 0");
		if(isset($_POST['search'])){
			if(!empty($_POST['transaction_no'])){
				$query = "SELECT * FROM credit WHERE transaction_no = '".$_POST['transaction_no']."'";
			}else if(!empty($_POST['credit_search'])){
				$query = "SELECT * FROM credit WHERE customer LIKE '%".$_POST['credit_search']."%' ORDER BY customer LIMIT ".($result_list_offset-1).", ".$results_per_page;	
			}else{
				$query = "SELECT * FROM credit LIMIT ".($result_list_offset-1).", ".$results_per_page;	
			}
		}else{
				$query = "SELECT * FROM credit LIMIT ".($result_list_offset-1).", ".$results_per_page;	
		}
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
				<td ".even_strip($i)."><a href='reciept.php?transaction_no=".$transaction_no."'>".$transaction_no."</a></td>
				<td ".even_strip($i).">".$amount_paid."</td>
				<td ".even_strip($i).">".$balance."</td>
				<td ".even_strip($i).">".$staff."</td>";
				if($balance<0){
					echo "<td ".even_strip($i)."><a href='edit_credit.php?id=".$credit_id."'>Clear debt</a></td>";
				}else{
					echo "<td ".even_strip($i).">Clear debted</td>";
				}
				echo "</tr>";
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
        <td class='column_head'><?php echo $sum*(-1);?></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='credit.php?increment=-1&result_list_offset=$result_list_offset'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='credit.php?increment=1&result_list_offset=$result_list_offset'>>></a>" : "")." ";?></p>
        <p><a href="credit.php?generate_xls=true">Export to Excel</a></p>
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