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
$results_per_page = 10;
if(isset($_GET['increment'])){
	$result_list_offset += $_GET['increment']*$results_per_page;
}
if($result_list_offset<1){
		$result_list_offset = 1;
}
?>
<?php
if(isset($_GET['id'])){
	$medicine_id = $_GET['id'];
	if(isset($_GET['generate_xls'])){
		export_medicine($medicine_id);
	}
	if(isset($_GET['activate'])){
		$query = "UPDATE medicine SET status='active' WHERE id = ".$medicine_id;
		$result = mysql_query($query, $connection);
	}
	if(isset($_GET['deactivate'])){
		$query = "UPDATE medicine SET status='inactive' WHERE id = ".$medicine_id;
		$result = mysql_query($query, $connection);
	}
}

$query = "SELECT * FROM medicine m JOIN category c JOIN units u WHERE m.id=".$_GET['id']." AND m.category = c.id AND m.unit=u.id";
$result = mysql_query($query, $connection);
$medicine = mysql_fetch_assoc($result);
$form_result= mysql_query("SELECT * FROM form WHERE id = ".$medicine['form']);
$select_form = mysql_fetch_assoc($form_result);
$form = ucfirst($select_form["form"]);
$sales_unit = ucfirst($select_form["sales_unit"]);
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        
        <h1>Medicine: <?php echo $medicine['generic_name']."/".$medicine['trade_name']." ".$medicine['weight']."".$medicine['value'];?></h1>
        <table class='results'>
        <tr>
        <td width="13%" height="23" class='even'>Generic Name:</td><td width="87%" class='even'><?php echo $medicine['generic_name'];?></td>
        </tr>
        <tr>
        <td class='cell_value'>Trade Name:</td><td class='cell_value'><?php echo $medicine['trade_name'];?></td>
        </tr>
        <tr>
        <td height="23" class='even'>Sales Unit:</td><td class='even'><?php echo $form;?></td>
        </tr>
        <tr>
        <td class='cell_value'>Weight:</td><td class='cell_value'><?php echo $medicine['weight']."".$medicine['value'];?></td>
        </tr>
        <tr>
        <td class='even'>Comment:</td><td class='even'><?php echo ucfirst($medicine['status'])?></td>
        </tr>  
        <tr>
        <td class='cell_value'>Category:</td><td class='cell_value'><?php echo ucfirst(strtolower($medicine['name']));?></td>
        </tr>
         <tr>
        <td style="border-bottom:#000; border-bottom-style:solid;"></td><td style="border-bottom:#000; border-bottom-style:solid;"></td>
        </tr>
        
        <table class='results'>
        <tr>
        <td class='column_head'>ID</td>
        <td class='column_head'>Stock(<?php echo $sales_unit."s";?>)</td>
        <td class='column_head'>Balance(<?php echo $sales_unit."s";?>)</td>
        <td class='column_head'>Balance(%)</td>  
        <td class='column_head'>Expiry Date</td>
        <td class='column_head'>Buying(Ush)</td>
        <td class='column_head'>Selling(Ush)</td>
        <td class='column_head'>Stock Date</td>
        <td class='column_head'>Staff</td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        <?php 
        $query = "SELECT id, stock_amount, current_amount, percentage_balance, expiry_date, buying, selling, stock_date, staff staff  FROM stock WHERE medicine=".$medicine_id." ORDER BY expiry_date LIMIT ".($result_list_offset-1).", ".$results_per_page;
        $result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        if(isset($result)){	
            $i = 0;
			while($medicine = mysql_fetch_array($result)){
                $stock_id = $medicine['id'];
				$stock_amount = $medicine['stock_amount'];
				$current_amount = $medicine['current_amount'];
				$percentage_balance = $medicine['percentage_balance'];
				
				
				$expiry_date = $medicine['expiry_date'];
				$expiry_date_str = strtotime($expiry_date);
				$formated_expiry_date = date("j-M-Y", $expiry_date_str);
				
				//$formated_expiry_date = date("j-M-Y", $formated_expiry_date);
				$today = date("Y-m-d");
				$today_str = strtotime($today);
				$today_ = date("j-M-Y", $today_str);
				$almost_expiryDate_str = $expiry_date_str-(60*60*24*30);
				$formated_almost_expiryDate = date("j-M-Y", $almost_expiryDate_str);
				
				
				$buying = $medicine['buying'];
				$selling = $medicine['selling'];
				$stock_date = $medicine['stock_date'];
				$formated_stock_date = strtotime($stock_date);
				$formated_stock_date = date("j-M-Y h:i:s A", $formated_stock_date);
				$staff = $medicine['staff'];
				$staff_name = get_users_name($staff);
                echo "
                <tr ";
				if($current_amount < 1){
					echo "style='color:#F00;'";
				}
				echo ">
                    <td ".even_strip($i)."><a href=\"medicine.php?id=".$medicine_id."\">".$stock_id."</a></td>
					<td ".even_strip($i).">".$stock_amount."</td>
                    <td ".even_strip($i).">".$current_amount."</td>
                    <td ".even_strip($i).">".$percentage_balance."</td>";
					if($today_str>=$expiry_date_str){
                    	echo "<td ".even_strip($i)."><span style='color:#F00; font-weight:bold'>".$formated_expiry_date."</span></td>";
					}elseif($today_str>=$almost_expiryDate_str){
                    	echo "<td ".even_strip($i)."><span style='color:#F60; font-weight:bold'>".$formated_expiry_date."</span></td>";
					}else{
						echo "<td ".even_strip($i).">".$formated_expiry_date."</td>";	
					};
                   echo "
                    <td ".even_strip($i).">".$buying."</td>
                    <td ".even_strip($i).">".$selling."</td>
                    <td ".even_strip($i).">".$formated_stock_date."</td>
                    <td ".even_strip($i).">".$staff_name."</td>
                    <td ".even_strip($i)."><a href='edit_stock.php?id=".$stock_id."'>";
					if($current_amount < 1){
						echo "Restock";
					}else{
						echo "Edit";	
					}
					echo 
					"</a></td>
                    <td ".even_strip($i)."><a href='stock_levels.php?del_id=".$stock_id."'>Delete</a></td>	
                    </tr>";
					$i++;
					if($current_amount == 0){
						echo "</span>";
					}
				}
			$range = $result_list_offset." to ".($result_list_offset+$rows-1);
			}
        ?>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='medicine.php?id=".$medicine_id."increment=-1&result_list_offset=$result_list_offset'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='medicine.php?id=".$medicine_id."increment=1&result_list_offset=$result_list_offset'>>></a>" : "")." ";?></p>
        
        </table>
        
        
        <?php
        if(isset($medicine)){
        echo "
        <tr>
        <td class='cell_value'><a href='edit_medicine.php?id=".$medicine_id."'>Edit</a></td>
        </tr>";
        }
        ?>
        
        <p><a href="add_medicine.php">Add Medicine</a> | <a href="medicines.php">Show Medicines</a> | <a href="medicine.php?generate_xls=true&id=<?php echo $medicine_id;?>">Export to Excel</a></p>
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