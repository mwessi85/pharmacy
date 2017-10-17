<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php 
if(isset($_GET['transaction_no'])){
	$transaction_no = $_GET['transaction_no'];	
}
$query = "SELECT * FROM test_sales WHERE transaction_no = '".$transaction_no."'";
$test_result = mysql_query($query, $connection);
$sql = "SELECT * FROM credit WHERE transaction_no = '".$transaction_no."'";
$result_credit = mysql_query($sql, $connection);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pharmacy Management System | Client Reciept</title>
<script language="javascript" type="text/javascript" >
        // Disen
        document.onselectstart=new Function('return false');
        function dMDown(e) {return false;}
        function dOClick() {return true;}
        document.onmousedown=dMDown;
        document.onclick=dOClick
        
        -->
    </script>
<style>
body, html
{
	margin:0;
	padding:0;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size:11px;
	background-color:#fff;
}
#outerwrapper
{
	width:800px;
	height:auto;
	margin:auto;

}

#slip
{
	width:747px;
	height:auto;
	margin:auto;
	font-size: x-small;
}

#slip2
{
	width:418px;
	height:auto;
	margin-left:150px;
	margin-top:40px;
	text-align: right;
}

.text
{
	font-size:14px;
	font-family:Tahoma, Geneva, sans-serif;
	font-weight:bold;
}
</style>
</head>

<body>
<div id="outerwrapper">
<div id="slip">
<table width="747" border="1">
  <tr>
    <td colspan="5" align="center"><span class="text">Pharmacy Management System</span><br />
      <br />
      Date: <?php echo date("jS-M-Y h:i:s A");?><br />
      <br />
      <table width="200" border="0">
        <tr>
          <td align="center">Reciept no: <?php echo $transaction_no;?></td>
        </tr>
      </table></td>
    </tr>
   <tr>
    <td><strong>Test</strong></td>
    <td><strong></strong></td>
    <td><strong></strong></td>
    <td><strong>Unit cost</strong></td>
    <td><strong>Total Cost</strong></td>
  </tr>
   <?php 
   $sum = 0;
   while($tests = mysql_fetch_array($test_result)){
	$transaction_no = $sale['transaction_no'];
	$test = $tests['test'];
	$unit_cost = $tests['unit_cost'];
	$quantity = $tests['quantity'];
	$datetime = $tests['datetime'];
	$client_id = $tests['client_id'];
	$client_name = $tests['client_name'];
	$staff_id = $tests['staff_id'];
	$staff_name = $tests['staff_name'];
	$total = $unit_cost*$quantity;
   echo "
   <tr>
    <td>".$test."</td>
    <td></td>
    <td></td>
     <td>".$unit_cost."</td>
    <td>".$total."</td>
  </tr>";
  $sum = $sum+$total;
   }
   $grand_sum = $grand_sum+$sum;
  ?>
  <tr>
    <td><strong></strong></td>
    <td><strong></strong></td>
    <td><strong></strong></td>
    <td><strong></strong></td>
    <td><strong><?php echo $sum;?></strong></td>
  </tr>
  <?php 
  $credit = mysql_fetch_array($result_credit)
  ?>
   <tr>
    <td colspan="4"><strong>Grand Sum</strong></td>
    <td><strong><?php echo $grand_sum;?></strong></td>
  </tr>
   <tr>
    <td colspan="4"><strong>Amount paid</strong></td>
    <td><strong><?php echo $credit['amount_paid'];?></strong></td>
  </tr>
   <tr>
    <td colspan="4"><strong>Balance due</strong></td>
    <td><strong><?php echo $credit['balance'];?></strong></td>
  </tr>
</table>
<table width="747" border="1" align="center">
  <tr>
    <td align="center"><br />
      ............................................................<br />
      Recived By: <?php echo $client_name;?></td>
    <td align="center"><br />
      ............................................................<br />
      Approved By: <?php echo $staff_name;?></td>
  </tr>
  </table>

Click <a href="sale.php?new_sale=true">here</a> to go back<br />
<a href="javascript:self.print()">Print This Page</a> <br />
</div>
</div>
</body>
</html>