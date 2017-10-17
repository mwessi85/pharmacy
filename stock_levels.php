<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
// Prev... Next...
if(isset($_GET['user_list_offset'])){
	$result_list_offset = $_GET['user_list_offset'];
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
?>
<?php
if(isset($_GET['generate_xls'])){
	export_stock();
}
if(isset($_GET['del_id'])){
	$query = "DELETE FROM stock WHERE id = ".$_GET['del_id'];
	$del_result = mysql_query($query, $connection);
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Stock</h1>
        <?php echo "<p class='notice'>".success_message()."</p>"; ?>
        <div>
        <form autocomplete="off" action="stock_levels.php" method="post">
        <label>Name:</label><input type="text" name="med_search" id="med_search" maxlength="30" value=""/>
        <input type="submit" name="search" id="search" value="Search" />
        </form>
        </div>
        <table class='results'>
        <tr>
        <td class='column_head'>ID</td>
        <td class='column_head'><?php echo "<a href='stock_levels.php?order_by=generic_name ASC'>Medicine</a>";?></td>
        <td class='column_head'><?php echo "<a href='stock_levels.php?order_by=form ASC'>Sales Unit</a>";?></td>
        <td class='column_head'>Stock</td>
        <td class='column_head'>Balance</td>
        <td class='column_head'>Balance(%)</td>  
        <td class='column_head'><?php echo "<a href='stock_levels.php?order_by=expiry_date DESC'>Expiry Date</a>";?></td>
        <td class='column_head'>Buying</td>
        <td class='column_head'>Selling</td>
        <td class='column_head'><?php echo "<a href='stock_levels.php?order_by=stock_date DESC'>Stock Date</a>";?></td>
        <td class='column_head'><?php echo "<a href='stock_levels.php?order_by=staff ASC'>Staff</a>";?></td>
         <td class='column_head'></td>
        </tr>
        <?php 
        if(isset($_GET['order_by'])){
			$order_by = $_GET['order_by']; 
		}
		else{
			$order_by = "s.stock_date DESC";	
		}
	   if($order_by){
			if(isset($_POST['search'])){
				$query = "SELECT m.id medicine_id, m.status medicine_status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.form form, m.unit unit_id, u.value unit, m.category category_id, c.name category,  m.form form_id, f.form medicine_form, f.sales_unit sales_unit, s.id stock_id, s.stock_amount stock_amount, SUM(s.stock_amount) total_stocked, s.current_amount current_amount, SUM(s.current_amount) amount_in_stock ,s. percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff FROM medicine m JOIN category c JOIN units u JOIN form f JOIN stock s WHERE m.id = s.medicine AND m.category = c.id AND m.unit = u.id AND m.form = f.id AND s.id IS NOT NULL AND (m.generic_name LIKE '%".$_POST['med_search']."%' OR m.trade_name LIKE '%".$_POST['med_search']."%') ORDER BY ".$order_by." LIMIT ".($result_list_offset-1).", ".$results_per_page;	
			}else{
			$query = "SELECT m.id medicine_id, m.status medicine_status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.form form, m.unit unit_id, u.value unit, m.category category_id, c.name category,  m.form form_id, f.form medicine_form, f.sales_unit sales_unit, s.id stock_id, s.stock_amount stock_amount, SUM(s.stock_amount) total_stocked, s.current_amount current_amount, SUM(s.current_amount) amount_in_stock ,s. percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff FROM medicine m JOIN category c JOIN units u JOIN form f JOIN stock s WHERE m.id = s.medicine AND m.category = c.id AND m.unit = u.id  AND m.form = f.id GROUP BY m.id ORDER BY ".$order_by." LIMIT ".($result_list_offset-1).", ".$results_per_page;
		   }
	   }
	   //echo $query;
        $result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        if(isset($result)){	
		//if($rows > 0){	
            //echo "<pre>".$rows."</pre>";
			$i = 0;
			//$medicine = mysql_fetch_array($result);
			//echo "<pre>".print_r($medicine, true)."</pre>";
			while($medicine = mysql_fetch_array($result)){
                if(!empty($medicine['stock_id'])){
				$stock_id = $medicine['stock_id'];
				$medicine_id = $medicine['medicine_id'];
				$medicine_status = $medicine['medicine_status'];
				$generic_name = $medicine['generic_name'];
				$trade_name = $medicine['trade_name'];
				$weight = $medicine['weight'];
				$unit_of_sale =  ucfirst($medicine['sales_unit']);
				$unit = $medicine['unit'];
				$category = $medicine['category'];
				$stock_amount = $medicine['stock_amount'];
				$total_stocked = $medicine['total_stocked'];
				$current_amount = $medicine['current_amount'];
				$amount_in_stock = $medicine['amount_in_stock'];
				$balance = $amount_in_stock/$total_stocked*100;
				$balance = round($balance, 2);
				$percentage_balance = $medicine['percentage_balance'];
				$expiry_date = $medicine['expiry_date'];
				$formated_expiry_date = strtotime($expiry_date);
				$formated_expiry_date = date("j-M-Y", $formated_expiry_date);
				$buying = $medicine['buying'];
				$selling = $medicine['selling'];
				$stock_date = $medicine['stock_date'];
				$formated_stock_date = strtotime($stock_date);
				$formated_stock_date = date("j-M-Y h:i:s A", $formated_stock_date);
				$staff = $medicine['staff'];
				$staff_name = get_users_name($staff);

				if(isset($_GET['excel_export'])){
					$filename = date("Y-m-d_H-i",time());
					header("Content-type: application/vnd.ms-excel");
					header('Content-Disposition: attachment; filename='.$filename); 
					header("Pragma: no-cache"); 
				}
                if($medicine_status == "active"){
					echo "
                <tr>
                    <td ".even_strip($i)."><a href=\"medicine.php?id=".$medicine_id."\">".$stock_id."</a></td>
					<td ".even_strip($i)."><a href=\"medicine.php?id=".$medicine_id."\">".$generic_name."/".$trade_name." ".$weight."".$unit."</a></td>";	
				}else{
				echo "
                <tr>
                    <td ".even_strip($i).">".$stock_id."</td>
					<td ".even_strip($i).">".$generic_name."/".$trade_name." ".$weight."".$unit."</td>";
				}
					echo "<td ".even_strip($i).">".$unit_of_sale."s</td>
					<td ".even_strip($i).">".$total_stocked."</td>
                    <td ".even_strip($i).">".$amount_in_stock."</td>
                    <td ".even_strip($i).">".$balance."</td>
                    <td ".even_strip($i).">".$formated_expiry_date."</td>
                    <td ".even_strip($i).">".$buying."</td>
                    <td ".even_strip($i).">".$selling."</td>
                    <td ".even_strip($i).">".$formated_stock_date."</td>
                    <td ".even_strip($i).">".$staff_name."</td>
                    <td ".even_strip($i)."><a href='medicine.php?id=".$medicine_id."'>Edit</a></td>	
                    </tr>";
					$i++;	
				}
            }
		$range = $result_list_offset." to ".($result_list_offset+$rows-1);
        }
        ?>
        
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='stock_levels.php?increment=-1&result_list_offset=$result_list_offset&order_by=".$order_by."'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='stock_levels.php?increment=1&result_list_offset=$result_list_offset&order_by=".$order_by."'>>></a>" : "")." ";?></p>
        <p><a href="add_stock.php">Add Stock</a> | <a href="stock_levels.php?generate_xls=true">Export to Excel</a> | <a href="stock_pending_expiry.php">Stock Pending Expiry</a> | <a href="stock_expired.php">Expired Stock</a></p>
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