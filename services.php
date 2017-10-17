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
	export_services();
}

if(isset($_GET['id'])){
	$service_id = $_GET['id'];
	if(isset($_GET['generate_xls'])){
		export_service();
	}
	if(isset($_GET['activate'])){
		$query = "UPDATE services SET status='active' WHERE id = ".$service_id;
		$result = mysql_query($query, $connection);
	}
	if(isset($_GET['deactivate'])){
		$query = "UPDATE services SET status='inactive' WHERE id = ".$service_id;
		$result = mysql_query($query, $connection);
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Services</h1>
        <?php echo "<p class='notice'>".success_message()."</p>";?>
        <div>
        <form autocomplete="off" action="services.php" method="post">
        <label>Name:</label><input type="text" name="serv_search" id="serv_search" maxlength="30" value=""/>
        <input type="submit" name="search" id="search" value="Search" />
        </form>
        </div>
        <table class='results'>
        <tr>
        <td class='column_head'>Service</td>
        <td class='column_head'>Status</td>
        <td class='column_head'>Unit Cost</td>
        <td class='column_head'>Description</td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        <?php 
        if(isset($_POST['search'])){
			$query = "SELECT * FROM services WHERE service LIKE '%".$_POST['serv_search']."%' OR description LIKE '%".$_POST['serv_search']."%' ORDER BY service, description LIMIT ".($result_list_offset-1).", ".$results_per_page;
			//echo $query;	
		}else{
			$query = "SELECT * FROM services LIMIT ".($result_list_offset-1).", ".$results_per_page;
		}
		//echo $query;
		$result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        if(isset($result)){	
            $i = 0;
			while($services = mysql_fetch_array($result)){
                $service_id = $services['id'];
                $status = $services['status'];
				$service = $services['service'];
                $unit_cost = $services['unit_cost'];
                $description = $services['description'];
                
				echo "
                <tr>
                    <td ".even_strip($i).">".$service."</td>
					<td ".even_strip($i).">".ucfirst($status)."</td>
                    <td ".even_strip($i).">".$unit_cost."</td>
                    <td ".even_strip($i).">".$description."</td>
                    <td ".even_strip($i)."><a href='edit_service.php?id=".$service_id."'>Edit</a></td>";
					if($status == "inactive"){
						echo "<td ".even_strip($i)."><a href='services.php?activate=true&id=".$service_id."'>Activate</a></td>";
					}else{
						echo "<td ".even_strip($i)."><a href='services.php?deactivate=true&id=".$service_id."'>Deactivate</a></td>";	
					}
                echo "</tr>";
				$i++;
            }
		$range = $result_list_offset." to ".($result_list_offset+$rows-1);
        }
        ?>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='services.php?increment=-1&result_list_offset=$result_list_offset'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='services.php?increment=1&result_list_offset=$result_list_offset'>>></a>" : "")." ";?></p>
        <p><a href="add_service.php">Add Service</a> | <a href="services.php?generate_xls=true">Export to Excel</a></p>
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