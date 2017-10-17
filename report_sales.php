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
$results_per_page = 20;
if(isset($_GET['increment'])){
	$result_list_offset += $_GET['increment']*$results_per_page;
}
if($result_list_offset<1){
		$result_list_offset = 1;
}
if(isset($_GET['generate_xls'])){
	excel_general_sales();
}
?>
<?php 
if(isset($_GET['order_by'])){
	$order_by = $_GET['order_by']; 
}
else{
	$order_by = "transaction_no DESC";	
}
if($order_by){
	$query = "SELECT * FROM sales ORDER BY ".$order_by." LIMIT ".($result_list_offset-1).", ".$results_per_page;
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>General Medicine Sales Report ordered by 
		<?php 
		 if(isset($order_by)){
			if($order_by == 'medicine_name')	echo "ordered by Medicine";
			if($order_by == 'transaction_no DESC')	echo "ordered by Transaction ID";
			if($order_by == 'datetime DESC') echo "ordered by Date and time of sale";
			if($order_by == 'client_name')	echo "ordered by Client name";
			if($order_by == 'staff_name') echo "ordered by staff Name";
		}
		?></h1>
        <table class='results'>
        <tr>
        <td class='column_head'><?php echo "<a href='report_sales.php?order_by=transaction_no DESC'>Transaction ID</a>";?></td>
        <td class='column_head'><?php echo "<a href='report_sales.php?order_by=medicine_name'>Medicine</a>";?></td>
        <td class='column_head'>Dispensed</td>
        <td class='column_head'>Buying</td>
        <td class='column_head'>Selling</td>
        <td class='column_head'>Bought</td>
        <td class='column_head'>Sold</td>
        <td class='column_head'>Profit</td>
        <td class='column_head'><?php echo "<a href='report_sales.php?order_by=datetime DESC'>Date-time</a>";?></td>
        <td class='column_head'><?php echo "<a href='report_sales.php?order_by=client_name'>Client</a>";?></td>
        <td class='column_head'><?php echo "<a href='report_sales.php?order_by=staff_name'>Staff</a>";?></td>
        </tr>
        
        <?php
        if(isset($query)){	
			$i=0;
			$total_dispensed = 0;
			$total_buying = 0;
			$total_selling = 0;
			$total_bought = 0;
			$total_sold = 0;
			$total_profit = 0;
			$result = mysql_query($query, $connection);
			$rows = mysql_num_rows($result);
			$i = 0;
			while($sold = mysql_fetch_array($result)){  
                $sale_date = strtotime($sold['datetime']);
				$sale_date = date("j-M-Y h:i:s A", $sale_date);
				$buying = $sold['buying']*$sold['dispensed'];
				$selling = $sold['selling']*$sold['dispensed'];
				$profit = $selling-$buying;
				echo "
                <tr>
					<td ".even_strip($i)."><a href='reciept.php?transaction_no=".$sold['transaction_no']."'>".$sold['transaction_no']."</a></td>
                    <td ".even_strip($i).">".$sold['medicine_name']."</td>
                    <td ".even_strip($i).">".$sold['dispensed']."</td>
                    <td ".even_strip($i).">".$sold['buying']."</td>
					<td ".even_strip($i).">".$sold['selling']."</td>
                    <td ".even_strip($i).">".$buying."</td>
					<td ".even_strip($i).">".$selling."</td>
                    <td ".even_strip($i).">".$profit."</td>
					<td ".even_strip($i).">".$sale_date."</td>
                    <td ".even_strip($i).">".$sold['client_name']."</td>
					<td ".even_strip($i).">".$sold['staff_name']."</td>";
					$total_dispensed = $total_dispensed + $sold['dispensed'];
					$total_buying = $total_buying + $sold['buying'];
					$total_selling = $total_selling + $sold['selling'];
					$total_bought= $total_bought + $buying;
					$total_sold = $total_sold + $selling;
					$total_profit = $total_profit + $profit;
					$i++;
            }
        
		 $range = $result_list_offset." to ".($result_list_offset+$rows-1);
		}
        ?>
        <tr>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'><?php echo $total_buying;?></td>
        <td class='column_head'><?php echo $total_selling;?></td>
        <td class='column_head'><?php echo $total_bought;?></td>
        <td class='column_head'><?php echo $total_sold;?></td>
        <td class='column_head'><?php echo $total_profit;?></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='report_sales.php?increment=-1&result_list_offset=$result_list_offset&order_by=".$order_by."'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='report_sales.php?increment=1&result_list_offset=$result_list_offset&order_by=".$order_by."'>>></a>" : "")." ";?></p>   
        <p><a href="report_sales.php?generate_xls=true">Export to Excel</a> | <a href="credit.php">Credit</a></p>
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