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
	excel_service_from_to_sales($_GET['fromdate'], $_GET['todate']);
}
?>
<?php 
if(isset($_POST['fromdate']) && isset($_POST['todate']) || (isset($_GET['fromdate']) && isset($_GET['todate']))){
	if(isset($_POST['fromdate'])){
		$fromdate = date("Y-m-d", strtotime($_POST['fromdate']));
	}
	if(isset($_POST['todate'])){
		$todate = date("Y-m-d", strtotime($_POST['todate']));
	}
	if(isset($_GET['fromdate'])){
		$fromdate = date("Y-m-d", strtotime($_GET['fromdate']));
	}
	if(isset($_GET['todate'])){
		$todate = date("Y-m-d", strtotime($_GET['todate']));
	}
	if(isset($_GET['date'])){
		$date = $_GET['date'];   
	}
    if(isset($_GET['order_by'])){
		$order_by = $_GET['order_by']; 
	}
	else{
		$order_by = "transaction_no DESC";	
	}
   if($order_by){
		$query = "SELECT * FROM service_sales WHERE datetime >= '".$fromdate." 00:00:00' AND datetime <= '".$todate." 23:49:60' ORDER BY ".$order_by." LIMIT ".($result_list_offset-1).", ".$results_per_page;
   }
}else{
	$message = "Enter a date range";	
	redirect_to("service_from_to_report_sales.php?message=".$message);	
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Service date range Sales Report  
		<?php 
		 if(isset($order_by)){
			if($order_by == 'service')	echo "ordered by Service";
			if($order_by == 'transaction_no DESC')	echo "ordered by Transaction ID";
			if($order_by == 'datetime DESC') echo "ordered by Date and time of sale";
			if($order_by == 'client_name')	echo "ordered by Client name";
			if($order_by == 'staff_name') echo "ordered by staff Name";
		}
		?></h1>
        <table class='results'>
        <tr>
        <td class='column_head'>
        <?php if(isset($date)){
            echo "<a href='report_service_from_to_sales.php?fromdate=".$fromdate."&todate=".$todate."&order_by=transaction_no DESC'>Transaction ID</a>";
        }?>
        </td>
        <td class='column_head'>
        <?php if(isset($date)){
            echo "<a href='report_service_from_to_sales.php?fromdate=".$fromdate."&todate=".$todate."&order_by=service ASC'>Service</a>";
        }?>
        </td>
		<td class='column_head'>Unit Cost</td>
        <td class='column_head'>Quantity</td>
        <td class='column_head'>Cost</td>
        <td class='column_head'>
        <?php if(isset($date)){
            echo "<a href='report_service_from_to_sales.php?fromdate=".$fromdate."&todate=".$todate."&order_by=datetime DESC'>Date-time</td>";
        }?>
        </td>
        <td class='column_head'>
        <?php if(isset($date)){
            echo "<a href='report_service_from_to_sales.php?fromdate=".$fromdate."&todate=".$todate."&order_by=client_name ASC'>Client</a>";
        }?>
        </td>
        <td class='column_head'>
        <?php if(isset($date)){
            echo "<a href='report_service_from_to_sales.php?fromdate=".$fromdate."&todate=".$todate."&order_by=staff_name ASC'>Staff</a>";
        }?>
        </td>
        </tr>
       <?php
        if(isset($query)){	
			$i=0;
			$total_cost = 0;
			$result = mysql_query($query, $connection);
			$rows = mysql_num_rows($result);
			$i = 0;
			while($sold = mysql_fetch_array($result)){  
                $sale_date = strtotime($sold['datetime']);
				$sale_date = date("j-M-Y h:i:s A", $sale_date);
				$cost = $sold['unit_cost']*$sold['quantity'];
				echo "
                <tr>
					<td ".even_strip($i)."><a href='reciept.php?transaction_no=".$sold['transaction_no']."'>".$sold['transaction_no']."</a></td>
                    <td ".even_strip($i).">".$sold['service']."</td>
                    <td ".even_strip($i).">".$sold['unit_cost']."</td>
                    <td ".even_strip($i).">".$sold['quantity']."</td>
					<td ".even_strip($i).">".$cost."</td>
					<td ".even_strip($i).">".$sale_date."</td>
                    <td ".even_strip($i).">".$sold['client_name']."</td>
					<td ".even_strip($i).">".$sold['staff_name']."</td>";
					$total_cost = $total_cost + $cost;
					$i++;
            }
        
		 $range = $result_list_offset." to ".($result_list_offset+$rows-1);
		}
        ?>
		<tr>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'><?php echo $total_cost;?></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='report_service_from_to_sales.php?increment=-1&result_list_offset=$result_list_offset&fromdate=".$fromdate."&todate=".$todate."&order_by=".$order_by."'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='report_service_from_to_sales.php?increment=1&result_list_offset=$result_list_offset&fromdate=".$fromdate."&todate=".$todate."&order_by=".$order_by."'>>></a>" : "")." ";?></p>
        <p><a href="report_service_from_to_sales.php?generate_xls=true&fromdate=<?php echo $fromdate;?>&todate=<?php echo $todate;?>&order_by="<?php echo $order_by;?>"">Export to Excel</a></p>
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