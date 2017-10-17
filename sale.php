<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/config.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['customer_val'])){
	$customer_id = trim($_POST['customer_val']);
}
if(isset($_GET['customer_val'])){
	$customer_id = trim($_GET['customer_val']);
}
if(isset($_POST['customer_id'])){
	$customer_id = trim($_POST['customer_id']);
}
if(isset($_POST['new_balance'])){

}
if(isset($_POST['cash_given'])){
	$cash_given = $_POST['cash_given'];
}else{
	$cash_given = 0;	
}

if (isset($_POST['add'])){
	if(isset($_POST['customer_val'])){
		$customer_id = $_POST['customer_val'];
	}
	//echo $customer_id;
	//exit;
	if($_POST['first_name'] != "" && $_POST['last_name'] != ""){
	$query_add = "INSERT INTO customer(first_name, last_name, phone, address) 
	VALUES('".$_POST['first_name']."', '".$_POST['last_name']."', '".$_POST['phone']."', '".$_POST['address']."')";
	$result_add = mysql_query($query_add, $connection);
	$customer_id = mysql_insert_id();
	}
	
	$today = date("Y-m-d");
	if(isset($_POST['all_medicine_val'])){
		$medicine_id = $_POST['all_medicine_val'];
		$query = "SELECT s.id stock_id, m.id medicine_id, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.unit unit_id, u.value unit, m.category category_id, c.name category, s.stock_amount stock_amount, s.current_amount current_amount, s. percentage_balance percentage_balance, s.expiry_date expiry_date, s.buying buying, s.selling selling, s.stock_date stock_date, s.staff staff  FROM medicine m JOIN category c JOIN units u JOIN stock s WHERE m.id = s.medicine AND m.category = c.id
AND m.unit = u.id AND current_amount > 0 AND expiry_date > '".$today."' AND s.medicine = ".$medicine_id." ORDER BY expiry_date ASC LIMIT 1";
		//echo "<p>".$query."</p>";
		$result = mysql_query($query, $connection);
        if(isset($result)){	
		    $medicine = mysql_fetch_assoc($result);
			$stock_id = $medicine['stock_id'];
			$medicine_id = $medicine['medicine_id'];
			$generic_name = $medicine['generic_name'];
			$trade_name = $medicine['trade_name'];
			$weight = $medicine['weight'];
			$unit = $medicine['unit'];
			$medicine_name = $generic_name."/".$trade_name." ".$weight."".$unit;
			$category = $medicine['category'];
			$stock_amount = $medicine['stock_amount'];
			$current_amount = $medicine['current_amount'];
			$percentage_balance = $medicine['percentage_balance'];
			$expiry_date = $medicine['expiry_date'];
			$formated_expiry_date = strtotime($expiry_date);
			$formated_expiry_date = date("d-M-Y", $formated_expiry_date);
			$sql_freq = "SELECT * FROM frequency WHERE id = ".$_POST['frequency'];
			$result_freq = mysql_query($sql_freq, $connection);
			$freq = mysql_fetch_assoc($result_freq);
			$_POST['dispensed'] = $freq['times']*$_POST['duration'];
			$query = "INSERT INTO temp_med_sales(stock_id, medicine_id, medicine_name, frequency, frequency_name, duration, dispensed, buying, selling, client)
			VALUES (".$stock_id.", ".$medicine_id.", '".$medicine_name."', '".$freq['times']."', '".$freq['frequency']."', ".$_POST['duration'].", ".$_POST['dispensed'].", ".$medicine['buying'].", ".$medicine['selling'].", ".$customer_id.")";
			//echo $query;
			
			$result = mysql_query($query, $connection);
			if($_POST['dispensed']>$current_amount){
				$_POST['dispensed'] = $current_amount;
				$notice = "Note! only ".$_POST['dispensed']."".$unit."s were dispensed";	
			}
			//$percentage_balance = $current_amount/$stock_amount*100;
			$query = "UPDATE stock SET current_amount = (current_amount-".$_POST['dispensed'].") WHERE id = ".$stock_id;
			$result = mysql_query($query, $connection);
			$query = "UPDATE stock SET percentage_balance=(current_amount/stock_amount*100) WHERE id = ".$stock_id;
			$result = mysql_query($query, $connection);
		}
	}
} elseif(isset($_GET['delete_id'])){
	$query_del = "SELECT * FROM temp_med_sales WHERE id = ".$_GET['delete_id'];
	$result_del = mysql_query($query_del, $connection);
	if(mysql_num_rows($result_del) == 1){
		$temp = mysql_fetch_assoc($result_del);
		$query = "UPDATE stock SET current_amount = (current_amount+".$temp['dispensed'].") WHERE id = ".$temp['stock_id'];
		$result_update = mysql_query($query, $connection);
		$query = "UPDATE stock SET percentage_balance=(current_amount/stock_amount*100) WHERE id = ".$temp['stock_id'];
		$result_update = mysql_query($query, $connection);
		$result_del = mysql_query("DELETE FROM temp_med_sales WHERE id=".$_GET['delete_id'], $connection);
	}
}elseif(isset($_POST['new_balance'])){
		
}

if(isset($_GET['new_sale'])){
	$query = "SELECT * FROM temp_med_sales";
	$result = mysql_query($query, $connection);
	if(mysql_num_rows($result) >= 1){
		while($temp = mysql_fetch_assoc($result)){
			$query = "UPDATE stock SET current_amount = (current_amount+".$temp['dispensed'].") WHERE id = ".$temp['stock_id'];
			$result_update = mysql_query($query, $connection);
			$result_del = mysql_query("DELETE FROM temp_med_sales WHERE id=".$temp['id'], $connection);
		}
	}
	$result_del = mysql_query("DELETE FROM temp_service_sales", $connection);
	$result_del = mysql_query("DELETE FROM temp_test_sales", $connection);
}
$query = "SELECT * FROM temp_med_sales";
$result = mysql_query($query, $connection);
if(mysql_num_rows($result) >= 1){
	$sale_button = "Sale";
}else{
	$sale_button = "";
}

$query = "UPDATE stock SET percentage_balance=(current_amount/stock_amount*100)";
$result_update = mysql_query($query, $connection);
if(isset($customer_id)){
		$query = "SELECT * FROM customer WHERE id = ".$customer_id;
		$result = mysql_query($query, $connection);
		$customer =  mysql_fetch_assoc($result);
		$first_name = $customer['first_name'];
		$last_name = $customer['last_name'];
		$phone = $customer['phone'];
		$address = $customer['address'];
		$customer_name = $customer['first_name']." ".$customer['last_name'];
	}
if(isset($_POST['sale'])){
	$query="SELECT t.id temp_id, t.medicine_id, t.medicine_name medicine_name, t.frequency, t.frequency_name, t.duration duration, t.dispensed dispensed, t.buying buying, t.selling selling, f.id, f.frequency frequency FROM temp_med_sales t JOIN frequency f WHERE t.frequency = f.id";
		
		$result_sale = mysql_query($query, $connection);
		if(mysql_num_rows($result_sale)>=1){
			//$datetime = date("Y-m-d h:i:s A");		
			$staff_name = get_users_name($_SESSION['user_id']);
			$transaction_no = "T-".time();
			while($sale = mysql_fetch_assoc($result_sale)){
				$total_cost = $sale['dispensed']*$sale['selling'];
				$query_insert = "INSERT INTO sales(transaction_no, medicine_id, medicine_name, frequency, frequency_name,  duration, dispensed, buying, selling, datetime, client_id, client_name, staff_id, staff_name)
				VALUES('".$transaction_no."', '".$sale['medicine_id']."', '".$sale['medicine_name']."', '".$sale['frequency']."', '".$sale['frequency_name']."', '".$sale['duration']."', '".$sale['dispensed']."', '".$sale['buying']."', '".$sale['selling']."', NOW(), '".$customer_id."', '".$customer_name."', '".$_SESSION['user_id']."', '".$staff_name."')";
			
			$result_sales = mysql_query($query_insert, $connection);
			$sales_id = mysql_insert_id();
			$result_del = mysql_query("DELETE FROM temp_med_sales", $connection);
			}
			$query = "INSERT INTO credit(transaction_no, amount_paid, balance, customer_id, customer, staff)
					  VALUES('".$transaction_no."', ".$_POST['cash_given'].", ".$_POST['balance'].", '".$customer_id."', '".$customer_name."', '".$staff_name."')";
			$result = mysql_query($query, $connection);
			header("location: after_sale.php?page_reciept=reciept&next=sale_services&button=Services&customer_val=".$customer_id."&transaction_no=".$transaction_no);
		}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy"> 
        <?php if(!empty($_GET['success_message'])){echo "<p class='notice'>".$_GET['success_message']."</p>";}?>
        <?php if(!empty($_GET['message'])){echo "<p class=\"notice\">".$_GET['message']."</p>";}?>
        <h1>Medicine Sale</h1>
        <form autocomplete="off" enctype="multipart/form-data" action="sale.php" method="post">
<table class='results'>
    <tr>
        <td colspan="8" style="background-color:#CCC">
            <div id="customer_details">
            <strong>Customer Name: </strong><span id="customer_fname"><?php if(isset($customer['first_name'])){ echo $customer['first_name'];}?> <?php if(isset($customer['last_name'])){ echo $customer['last_name'];}?></span>
                <strong>Phone: </strong><span><?php if(isset($customer['phone'])){ echo $customer['phone'];}?></span>
                <strong>Address: </strong><span><?php if(isset($customer['address'])){ echo $customer['address'];}?></span>
            </div>
            <div id="customer_search">
            <table width="1027" class="results">
            <tr id="customer_stuff">
                <td style="background-color:#CCC"><span style="font-weight: bold; color:#666;">Customer&nbsp;Search:</span>
                <input type="search" name="customer" id="customer" placeholder="Search customer" onFocus="preparePage()" style="width:400px"/>
                <input type="hidden" name="customer_val" id="customer_val" value="<?php if(isset($customer_id)){ echo $customer_id;} else {echo "1";}?>" required/>
                <td width="717"  style="background-color:#CCC">
               <input type="button" name="display_new_customer" id="display_new_customer" onClick="newCustomer()" value="New Customer"/>
                </td>
            </tr>
            </table>
            <div id="new_customer">
            <table width="1028" class='results'>   
            <tr>
            <td colspan="4"><h3>New Customer</h3></td>
            </tr>
            <tr>
            <td width="250"><span style="font-weight: bold; color:#666;">First&nbsp;Name:</span> 
            <input type="text" name="first_name" id="first_name" placeholder="Michael" value="<?php if(isset($first_name_old)) echo $first_name_old;?>" style="width:200px;"/>
            </td>
            <td width="250"><span style="font-weight: bold; color:#666;">Surname:</span>  
            <input type="text" name="last_name" id="last_name" placeholder="Mutebi" value="<?php if(isset($last_name_old)) echo $last_name_old;?>"  style="width:200px;"/>
            </td>
            <td width="250"><span style="font-weight: bold; color:#666;">Address:</span>  
            <input type="text" name="address" id="address" placeholder="Nkozi" value="<?php if(isset($address_old)) echo $address_old;?>"  style="width:200px;"/>
            </td>
            <td width="246"><span style="font-weight: bold; color:#666;">Phone:</span>  
            <input type="text" name="phone" id="phone" placeholder="0782056946" value="<?php if(isset($phone_old)) echo $phone_old;?>"  style="width:200px;"/>
            </td>
            </tr>
            </table>
            </div>
            </div>
            <table class='results' style=" border-top-color: #39C ; border-top-style: inset;"> 
            <tr>
            <td>Medicine: <input type="search" name="all_medicine" id="all_medicine" value = "" autofocus placeholder="Enter Medicine name" required style="width:400px;"/>
            <input type="hidden" name="all_medicine_val" id="all_medicine_val" value = ""/></td>
            <td>Frequency
            <select name="frequency" id="frequency">
            <option selected disabled>Choose Frequency</option>
            <?php $sql = "SELECT * FROM frequency ORDER by frequency";
            $result_frq = mysql_query($sql,$connection);
            while($select_frq = mysql_fetch_assoc($result_frq)){
            echo "<option value = '".$select_frq["id"]."'>";
            	echo $select_frq["frequency"]." - ".$select_frq['details']." </option>";
            } 
            ?>
            </select></td>
            <td>Duration: <input type="number" name="duration" id="duration" maxlength="30" value="" min="1" max="120" placeholder="Duration of dose" required/></td>
            <td style="vertical-align:bottom; text-align:left;"><input type="submit" name="add" id="add" value="Add"/></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr  style=" border-top-color: #666; border-top-style:solid;">
        <td class='column_head'>Medicine</td>
        <td class='column_head'>Frequency</td>
        <td class='column_head'>Duration(days)</td>
        <td class='column_head'>Dispensed</td>
        <td class='column_head'>Unit Cost</td>
        <td class='column_head'>Total Cost</td>
        <td class='column_head'></td>
        </tr>
        <?php 
		$query ="SELECT t.id temp_id, t.medicine_id, t.medicine_name medicine_name, t.frequency, t.frequency_name frequency_name, t.duration duration, t.dispensed dispensed, t.selling selling, f.id, f.frequency frequency FROM temp_med_sales t JOIN frequency f WHERE t.frequency = f.id";
		$result_disp = mysql_query($query, $connection);
		$i = 0;
		$sum = 0;
		while($temp_sale = mysql_fetch_assoc($result_disp)){
			$total_cost = $temp_sale['dispensed']*$temp_sale['selling'];
			echo "
		<tr>
		<td ".even_strip($i).">".$temp_sale['medicine_name']."</td>
        <td ".even_strip($i).">".$temp_sale['frequency_name']."</td>
        <td ".even_strip($i).">".$temp_sale['duration']."</td>
        <td ".even_strip($i).">".$temp_sale['dispensed']."</td>
        <td ".even_strip($i).">".$temp_sale['selling']."</td>
        <td ".even_strip($i).">".$total_cost." Ush</td>";
		if(isset($temp_sale['id'])){
        	echo "<td ".even_strip($i)."><a href='sale.php?delete_id=".$temp_sale['temp_id']."&customer_val=".trim($customer_id)."' onClick='return areYouSure(\"Sure you want to delete this Entry?\")'>Delete</a></td>";
		}
        echo "</tr>";
		$sum = $sum + $total_cost;
		$i++;
		}
		
		?>
       
        <tr>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"></td>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"></td>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"></td>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"></td>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"></td>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"><?php if($sum != 0) echo $sum." USh";?></td>
        <td style="font-size:1.2em; background-color:#333; color:#FFF;"></td>
    	</tr>
        </form>
        <form autocomplete="off" enctype="multipart/form-data" id="sale" action="sale.php" method="post">
        <?php 
		if($sum != 0){
			if($cash_given < 1){
				$cash_given = 0;
			}
			echo "
			<tr>
			<td class='column_foot'></td>
			<td class='column_foot'></td>
			<td class='column_foot'></td>
			<td class='column_foot'><input type='hidden' name='customer_id' id='customer_id'
			value='".$customer_id."'/></td>
			<td class='column_foot'>Cash rendered</td>
			<td class='column_foot'>
			<input type='number' name='cash_given' id='cash_given'
			value='".$cash_given."' max='10000000' min='0' placeholder='Cash rendered' required/>USh
			</td>
			<td class='column_foot'><input type='submit' name='new_balance' id='new_balance' value='Balance' /></td>
			</tr>
			";
				
		}
		?>
        <?php 
		$balance = $cash_given-$sum;
		if(isset($balance)){
			echo "
			<tr>
			<td class='column_foot'></td>
			<td class='column_foot'></td>
			<td class='column_foot'></td>
			<td class='column_foot'></td>
			<td class='column_foot'>Balance</td>
			<td class='column_foot'><input type='number' name='balance' id='balance'
			value='".$balance."', ' required />Ush</td>
			<td class='column_foot'>";
			if($sale_button=="Sale"){
				echo "<input type='submit' name='sale' id='sale' value='".$sale_button."' />";
			}
			echo "
			</td>
			</tr>
			";
				
		}
		?>
        </form>
</table>

        
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