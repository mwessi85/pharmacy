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
if(isset($_GET['customer_id'])){
	$customer_id = trim($_POST['customer_id']);
}
if(isset($_POST['new_balance'])){

}
if(isset($_POST['cash_given'])){
	$cash_given = $_POST['cash_given'];
}else{
	$cash_given = 0;	
}
if(isset($_GET['transaction_no'])){
	$transaction_no = $_GET['transaction_no'];	
}elseif(isset($_POST['transaction_no'])){
	$transaction_no = $_POST['transaction_no'];
}else{
	$transaction_no = "";	
}

if (isset($_POST['add'])){
	if(isset($_POST['customer_val'])){
		$customer_id = $_POST['customer_val'];
	}
	//echo $_POST['customer_val'];
	//echo "Mike";
	//exit;
	if($_POST['first_name'] != "" && $_POST['last_name'] != ""){
	$query_add = "INSERT INTO customer(first_name, last_name, phone, address) 
	VALUES('".$_POST['first_name']."', '".$_POST['last_name']."', '".$_POST['phone']."', '".$_POST['address']."')";
	$result_add = mysql_query($query_add, $connection);
	$customer_id = mysql_insert_id();
	}
	$today = date("Y-m-d");
	if(isset($_POST['all_test_val'])){
		$test_id = $_POST['all_test_val'];
		$query = "SELECT * FROM tests WHERE id = ".$test_id;
		//echo "<p>".$query."</p>";
		$result = mysql_query($query, $connection);
        if(isset($result)){	
		    $tests = mysql_fetch_assoc($result);
			$test_id = $tests['test'];
			$test = $tests['test'];
			$unit_price = $tests['unit_cost'];
			$query = "INSERT INTO temp_test_sales(test_name, unit_cost, quantity)
			VALUES ('".$test."', ".$unit_price.", ".$_POST['quantity'].")";
			//echo $query;
			$result = mysql_query($query, $connection);
		}
	}
}
if(isset($_GET['delete_id'])){
	$query_del = "DELETE FROM temp_test_sales WHERE id = ".$_GET['delete_id'];
	$result_del = mysql_query($query_del, $connection);
}
$query = "SELECT * FROM temp_test_sales";
$result = mysql_query($query, $connection);
if(mysql_num_rows($result) >= 1){
	$sale_button = "Sale";
}else{
	$sale_button = "Print Receipt";	
}
if(isset($customer_id) && $customer_id>=1){
	$query = "SELECT * FROM customer WHERE id = ".$customer_id;
	//echo $query;
	$result = mysql_query($query, $connection);
	$customer =  mysql_fetch_assoc($result);
	$first_name = $customer['first_name'];
	$last_name = $customer['last_name'];
	$phone = $customer['phone'];
	$address = $customer['address'];
	$customer_name = $customer['first_name']." ".$customer['last_name'];
}
if(isset($_POST['sale'])){
	if(!empty($transaction_no)){
		if($_POST['sale'] == "Print Receipt"){
			header("location: reciep_tests.php?transaction_no=".$transaction_no);	
		}
	}
	$query="SELECT * FROM temp_test_sales";
	$result_sale = mysql_query($query, $connection);
	if(mysql_num_rows($result_sale)>=1){
		//$datetime = date("Y-m-d h:i:s A");
		$staff_name = get_users_name($_SESSION['user_id']);
		if(empty($transaction_no)){
			$transaction_no = "T-".time();
		}
		while($sale = mysql_fetch_assoc($result_sale)){
			//$total_cost = $sale['dispensed']*$sale['selling'];
			$query_insert = "INSERT INTO test_sales(transaction_no, test, unit_cost, quantity, datetime, client_id, client_name, staff_id, staff_name)
			VALUES('".$transaction_no."', '".$sale['test_name']."', '".$sale['unit_cost']."', '".$sale['quantity']."', NOW(), '".$customer_id."', '".$customer_name."', '".$_SESSION['user_id']."', '".$staff_name."')";
			echo $query_insert;
			//exit;
			$result_sales = mysql_query($query_insert, $connection);
			$result_del = mysql_query("DELETE FROM temp_test_sales", $connection);
		}
		if(!empty($_POST['transaction_no'])){
		$query = "UPDATE credit SET transaction_no, amount_paid = (amount_paid + ".$_POST['cash_given']."), balance = (balance + ".$_POST['balance'].") WHERE transaction_no = '".$_POST['transaction_no']."'";
		$result = mysql_query($query, $connection);
		header("location: reciept.php?transaction_no=".$transaction_no);
		}else{
		$query = "INSERT INTO credit(transaction_no, amount_paid, balance, customer_id, customer, staff)
				  VALUES('".$transaction_no."', ".$_POST['cash_given'].", ".$_POST['balance'].", '".$customer_id."', '".$customer_name."', '".$staff_name."')";
		$result = mysql_query($query, $connection);
		header("location: reciept.php?transaction_no=".$transaction_no);
		}
		//header("location: after_sale.php?page_reciept=reciept_services&next=sale_tests&button=Tests&customer_val=".$customer_id."&transaction_no=".$transaction_no);
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy"> 
        <?php if(!empty($_GET['success_message'])){echo "<p class='notice'>".$_GET['success_message']."</p>";}?>
        <?php if(!empty($_GET['message'])){echo "<p class=\"notice\">".$_GET['message']."</p>";}?>
        <h1>Test Sale</h1>
        <form autocomplete="off" enctype="multipart/form-data" action="sale_tests.php?transaction_no=<?php echo $transaction_no;?>" method="post">
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
                <input type="hidden" name="customer_val" id="customer_val" value="<?php if(!empty($customer_id)){ echo $customer_id;} else {echo "1";}?>" required/>
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
            <td>Test:<input type="search" name="all_test" id="all_test" value = "" autofocus placeholder="Enter test name" required style="width:400px;"/>
            <input type="hidden" name="all_test_val" id="all_test_val" value = ""/></td>
            <td></td>
            <td>Quantity: <input type="number" name="quantity" id="quantity" maxlength="30" value="" min="1" max="100" placeholder="Quantify the test" required/></td>
            <td style="vertical-align:bottom; text-align:left;"><input type="submit" name="add" id="add" value="Add"/></td>
            </tr>
            </table>
        </td>
        </tr>
        <tr  style=" border-top-color: #666; border-top-style:solid;">
        <td class='column_head'>Test</td>
        <td class='column_head'>Unit Cost</td>
        <td class='column_head'>Quuantity</td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        <td class='column_head'>Total Cost</td>
        <td class='column_head'></td>
        </tr>
        <?php 
		$query ="SELECT * FROM temp_test_sales";
		$result_disp = mysql_query($query, $connection);
		$i = 0;
		$sum = 0;
		while($temp_sale = mysql_fetch_assoc($result_disp)){
			$total_cost = $temp_sale['unit_cost']*$temp_sale['quantity'];
			echo "
		<tr>
		<td ".even_strip($i).">".$temp_sale['test_name']."</td>
        <td ".even_strip($i).">".$temp_sale['unit_cost']."</td>
        <td ".even_strip($i).">".$temp_sale['quantity']."</td>
        <td ".even_strip($i)."></td>
        <td ".even_strip($i)."></td>
        <td ".even_strip($i).">".$total_cost." Ush</td>";
		if(isset($temp_sale['id'])){
        	echo "<td ".even_strip($i)."><a href='sale_tests.php?delete_id=".$temp_sale['id']."&customer_val=".trim($customer_id)."' onClick='return areYouSure(\"Sure you want to delete this Entry?\")'>Delete</a></td>";
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
        <form autocomplete="off" enctype="multipart/form-data" id="sale" action="sale_tests.php" method="post">
        <?php 
		if($sum != 0){
			if($cash_given < 1){
				$cash_given = 0;
			}
			echo "
			<tr>
			<td class='column_foot'></td>
			<td class='column_foot'></td>
			<td class='column_foot'><input type='hidden' name='transaction_no' id='transaction_no'
			value='";
			if(isset($transaction_no)) {echo $transaction_no;
			}
			echo "'/></td>
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
			//if($balance>=0){
			echo "<input type='submit' name='sale' id='sale' value='".$sale_button."' />";
			//}
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