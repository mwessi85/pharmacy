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
	export_medicines();
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Medicines</h1>
        <?php echo "<p class='notice'>".success_message()."</p>";?>
        <div>
        <form autocomplete="off" action="medicines.php" method="post">
        <label>Name:</label><input type="text" name="med_search" id="med_search" maxlength="30" value=""/>
        <input type="submit" name="search" id="search" value="Search" />
        </form>
        </div>
        <table class='results'>
        <tr>
        <td class='column_head'>Generic Name</td>
        <td class='column_head'>Trade Name</td>
        <td class='column_head'>Sales Unit</td>
        <td class='column_head'>Weight</td>
        <td class='column_head'>Units</td>
        <td class='column_head'>Category</td>
        <td class='column_head'>Status</td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        <?php 
        if(isset($_POST['search'])){
			$query = "SELECT DISTINCT m.id id, m.status status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.form form, u.value units, c.name category 
		FROM medicine m JOIN category c JOIN units u WHERE m.category = c.id AND m.unit = u.id AND (m.generic_name LIKE '%".$_POST['med_search']."%' OR m.trade_name LIKE '%".$_POST['med_search']."%') ORDER BY generic_name, trade_name, weight LIMIT ".($result_list_offset-1).", ".$results_per_page;	
		}else{
			$query = "SELECT m.id id, m.status status, m.generic_name generic_name, m.trade_name trade_name, m.weight weight, m.form form, u.value units, c.name category 
			FROM medicine m JOIN category c JOIN units u WHERE m.category = c.id AND m.unit = u.id
			ORDER BY generic_name, trade_name, weight LIMIT ".($result_list_offset-1).", ".$results_per_page;
		}
		//echo $query;
		$result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        if(isset($result)){	
            $i = 0;
			while($medicine = mysql_fetch_array($result)){
                $medicine_id = $medicine['id'];
                $status = $medicine['status'];
				$generic_name = $medicine['generic_name'];
                $trade_name = $medicine['trade_name'];
                $category = $medicine['category'];
                $weight = $medicine['weight'];
                $units = $medicine['units'];
				$form = $medicine['form'];
				$form_result= mysql_query("SELECT * FROM form WHERE id = ".$form);
				$select_form = mysql_fetch_assoc($form_result);
				$form = ucfirst($select_form["form"]);
               /* if(isset($_GET['excel_export'])){
					$filename = date("Y-m-d_H-i",time());
					header("Content-type: application/vnd.ms-excel");
					header('Content-Disposition: attachment; filename='.$filename); 
					header("Pragma: no-cache"); 
				}*/
				echo "
                <tr>
                    <td ".even_strip($i)."><a href=\"medicine.php?id=".$medicine_id."\">".$generic_name."</a></td>
					<td ".even_strip($i)."><a href=\"medicine.php?id=".$medicine_id."\">".$trade_name."</a></td>
                    <td ".even_strip($i).">".$form."</td>
					<td ".even_strip($i).">".$weight."</td>
                    <td ".even_strip($i).">".$units."</td>
                    <td ".even_strip($i).">".$category."</td>
					<td ".even_strip($i).">".ucfirst($status)."</td>
                    <td ".even_strip($i)."><a href='edit_medicine.php?id=".$medicine_id."'>Edit</a></td>";
					if($status == "inactive"){
						echo "<td ".even_strip($i)."><a href='medicine.php?activate=true&id=".$medicine_id."'>Activate</a></td>";
					}else{
						echo "<td ".even_strip($i)."><a href='medicine.php?deactivate=true&id=".$medicine_id."'>Deactivate</a></td>";	
					}
                echo "</tr>";
				$i++;
            }
		$range = $result_list_offset." to ".($result_list_offset+$rows-1);
        }
        ?>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='medicines.php?increment=-1&result_list_offset=$result_list_offset'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='medicines.php?increment=1&result_list_offset=$result_list_offset'>>></a>" : "")." ";?></p>
        <p><a href="add_medicine.php">Add Medicine</a> | <a href="medicines.php?generate_xls=true">Export to Excel</a></p>
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