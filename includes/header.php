<!DOCTYPE html>
<html>
  <head>
    <!--<link rel="index"    href="./index.html" title="Site map" />-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
<!--<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/jquery-ui.min.js"></script>-->
<script type="text/javascript" src="js/jquery.js"></script>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<script language="javascript" type="text/javascript" src="datetimepicker.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />
<script type="text/javascript">

$().ready(function() {
	$("#medicine").autocomplete("get_medicine_list.php", {
		width: 219,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	$("#medicine").result(function(event, data, formatted) {
		$("#medicine_val").val(data[1]);
	});
});
</script>
<script type="text/javascript">
$().ready(function() {
	$("#customer").autocomplete("get_customer_list.php", {
		width: 400,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	$("#customer").result(function(event, data, formatted) {
		$("#customer_val").val(data[1]);
	});
});
</script>
<script type="text/javascript">

$().ready(function() {
	$("#medicine").autocomplete("get_medicine_list.php", {
		width:400,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	$("#medicine").result(function(event, data, formatted) {
		$("#medicine_val").val(data[1]);
	});
});
</script>
<script type="text/javascript">
$().ready(function() {
	$("#all_medicine").autocomplete("get_all_medicine_list.php", {
		width: 400,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	$("#all_medicine").result(function(event, data, formatted) {
		$("#all_medicine_val").val(data[1]);
	});
});
</script>
<script type="text/javascript">
$().ready(function() {
	$("#all_service").autocomplete("get_all_service_list.php", {
		width: 400,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	$("#all_service").result(function(event, data, formatted) {
		$("#all_service_val").val(data[1]);
	});
});
</script>
<script type="text/javascript">
$().ready(function() {
	$("#all_test").autocomplete("get_all_test_list.php", {
		width: 400,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	$("#all_test").result(function(event, data, formatted) {
		$("#all_test_val").val(data[1]);
	});
});
</script>
<style type="text/css">

</style>

<script language="javascript" type="text/javascript">

function customerDetails(){
	//document.getElementById("customer_fname").innerHTML = "Mike";
	//alert(document.getElementById("customer_fname").innerHTML);
	//document.getElementById("customer_details").style.display = "none";
	//alert(document.getElementById("customer_val").value);
	if(document.getElementById("customer_fname").innerHTML.length >1){
		//alert(document.getElementById("customer_fname").innerHTML.length);
		document.getElementById("customer_details").style.display = "block";
		document.getElementById("display_new_customer").style.display = "none";
		document.getElementById("customer_stuff").style.display = "none";	
		//alert(document.getElementById("customer_fname").innerHTML);
	}else{
		document.getElementById("customer_details").style.display = "none";
		document.getElementById("display_new_customer").style.display = "block";
		//document.getElementById("customer_stuff").style.display = "block";
		//alert(document.getElementById("customer_fname").innerHTML);		
	}
	
};
	
function newCustomer(){
if(document.getElementById("new_customer").clicked = true){
	document.getElementById("new_customer").style.display = "block";
}
//document.getElementById("new_customer").style.display = "none";
//if(document.getElementById("display_new_customer").clicked == true){
//	document.getElementById("new_customer").style.display = "block";	
//}else{
//	document.getElementById("new_customer").style.display = "none";	
//}
};
window.onload = function(){
	customerDetails();
	document.getElementById("new_customer").style.display = "none";
};
</script>
<script language="javascript" type="text/javascript">
function toggleMenu(currMenu)
	{
		if(document.getElementById)
			{
				thisMenu = document.getElementById(currMenu).style;
				if(thisMenu.display == "none")
				{
					thisMenu.display = "block";
				}
				else
				{
				 	thisMenu.display = "none";
				}
				return false;
			}
		else
			{
				return true;
			}
		}
</script>

<title>Pharmacy Inventory System</title>	
</head>
<body onLoad="RotateBanner()">
    <div id="header">
      <div class="superHeader"> 
       
      </div>

      <div class="midHeader">
        <h1 class="headerTitle">PHARMACY MANAGEMENT</h1>
      </div>

      <div class="subHeader">
       <?php show_logout();?>
      </div>
    </div>

    <!-- ##### Side Bar ##### -->

    <div id="side-bar">
        <?php
        if($_SESSION['level'] == "admin"){
        echo admin_nav();
        }
        else if($_SESSION['level'] == "staff"){
        echo clerk_nav();
        }
        ?>
    </div>