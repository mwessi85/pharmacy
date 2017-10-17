<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
       if(isset($_GET['excel_export'])){
			$filename = date("Y-m-d_H-i",time());
			header("Content-type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename='.$filename); 
			header("Pragma: no-cache"); 
		}
	   if(isset($_GET['order_by'])){
			$order_by = $_GET['order_by']; 
		}
		else{
			$order_by = "id";	
		}
	   if($order_by){
			$query = "SELECT * FROM medicine WHERE comment = 'stocked' ORDER BY ".$order_by." ASC";
	   }
	   ?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>In-Stock Report  
		<?php 
		 if(isset($order_by)){
			if($order_by == 'name')	echo "ordered by Medicine Name";
			if($order_by == 'trade_name')	echo "ordered by Trade Name";
			if($order_by == 'category')	echo "ordered by Category";
			if($order_by == 'expiry_date') echo "ordered by Expiry Date";
			if($order_by == 'stock_date')	echo "ordered by Stock Date";
			if($order_by == 'date_sold') echo "ordered by date of sale";
			if($order_by == 'staff') echo "ordered by Dispenser";
			if($order_by == 'id') echo "order of sale";
		}
		?></h1>
        <table class='results'>
        <tr>
        <td></td>
        <td class='column_head'><?php echo "<a href='report_stock.php?order_by=name'>Medicine</a>";?>
        <td class='column_head'>Initial Stock</td>
        <td class='column_head'>Current Stock</td>
        <td class='column_head'>Threshold Level</td>
        <td class='column_head'>Cummulative Stock</td>
        <td class='column_head'><?php echo "<a href='report_stock.php?order_by=expiry_date'>Expiry Date</a>";?>
        <td class='column_head'><?php echo "<a href='report_stock.php?order_by=stock_date'>Stock Date</a>";?>
        <td class='column_head'>Buying Price</td>
        <td class='column_head'>Selling Price</td>
        </tr>
        
        <?php
	    if(isset($query)){
			$i=0;
			$result = mysql_query($query, $connection);
			while($medicine = mysql_fetch_array($result)){
                $medicine_id = $medicine['id'];
                $medicine_name = $medicine['name'];
                $trade_name = $medicine['trade_name'];
                $name = $medicine_name."/".$trade_name;
                $medicine_category = $medicine['category'];
                $weight = $medicine['weight'];
                $unit = $medicine['unit'];
                $buying_price = $medicine['buying_price'];
                $selling_price = $medicine['selling_price'];
                $initial_amount = $medicine['initial_amount'];
                $current_amount = $medicine['current_amount'];
                $cummulative_amount = $medicine['cummulative_amount'];
                $expiry_date = $medicine['expiry_date'];
                $stock_date = $medicine['stock_date'];
                $comment = $medicine['comment'];
                $unit_result = select_unit($unit);
                $units = mysql_fetch_assoc($unit_result);
                $unit_name = $units['value'];
                $category_result = select_category($medicine_category);
                $category = mysql_fetch_assoc($category_result);
                $category_name = $category['name'];
                echo "
                <tr>
                    <td class='cell_value'>".++$i."</td>
					<td class='cell_value'>".$name."-".$weight."".$unit_name."</td>
                    <td class='cell_value'>".$initial_amount."</td>
					<td class='cell_value'>".$current_amount."</td>
                    <td class='cell_value'>".$initial_amount*0.3."</td>
					<td class='cell_value'>".$cummulative_amount."</td>
                    <td class='cell_value'>".$expiry_date ."</td>
                    <td class='cell_value'>".$stock_date ."</td>
                    <td class='cell_value'>".$buying_price."</td>
                    <td class='cell_value'>".$selling_price."</td>";
            }
        }
		
        ?>
            <tr>
            <td></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            <td class='column_head'></td>
            </tr>
        </table>
        <p><a href="report_stock.php?excel_export=true">Export to Excel</a></p>
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