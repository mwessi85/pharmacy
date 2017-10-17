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
	export_tests();
}

if(isset($_GET['id'])){
	$test_id = $_GET['id'];
	if(isset($_GET['generate_xls'])){
		export_test();
	}
	if(isset($_GET['activate'])){
		$query = "UPDATE tests SET status='active' WHERE id = ".$test_id;
		$result = mysql_query($query, $connection);
	}
	if(isset($_GET['deactivate'])){
		$query = "UPDATE tests SET status='inactive' WHERE id = ".$test_id;
		$result = mysql_query($query, $connection);
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Tests</h1>
        <?php echo "<p class='notice'>".success_message()."</p>";?>
        <div>
        <form autocomplete="off" action="tests.php" method="post">
        <label>Name:</label><input type="text" name="test_search" id="test_search" maxlength="30" value=""/>
        <input type="submit" name="search" id="search" value="Search" />
        </form>
        </div>
        <table class='results'>
        <tr>
        <td class='column_head'>Test</td>
        <td class='column_head'>Status</td>
        <td class='column_head'>Unit Cost</td>
        <td class='column_head'>Description</td>
        <td class='column_head'></td>
        <td class='column_head'></td>
        </tr>
        <?php 
        if(isset($_POST['search'])){
			$query = "SELECT * FROM tests WHERE test LIKE '%".$_POST['test_search']."%' OR description LIKE '%".$_POST['test_search']."%' ORDER BY test, description LIMIT ".($result_list_offset-1).", ".$results_per_page;
			//echo $query;	
		}else{
			$query = "SELECT * FROM tests LIMIT ".($result_list_offset-1).", ".$results_per_page;
		}
		//echo $query;
		$result = mysql_query($query, $connection);
		$rows = mysql_num_rows($result);
        if(isset($result)){	
            $i = 0;
			while($tests = mysql_fetch_array($result)){
                $test_id = $tests['id'];
                $status = $tests['status'];
				$test = $tests['test'];
                $unit_cost = $tests['unit_cost'];
                $description = $tests['description'];
                
				echo "
                <tr>
                    <td ".even_strip($i).">".$test."</td>
					<td ".even_strip($i).">".ucfirst($status)."</td>
                    <td ".even_strip($i).">".$unit_cost."</td>
                    <td ".even_strip($i).">".$description."</td>
                    <td ".even_strip($i)."><a href='edit_test.php?id=".$test_id."'>Edit</a></td>";
					if($status == "inactive"){
						echo "<td ".even_strip($i)."><a href='tests.php?activate=true&id=".$test_id."'>Activate</a></td>";
					}else{
						echo "<td ".even_strip($i)."><a href='tests.php?deactivate=true&id=".$test_id."'>Deactivate</a></td>";	
					}
                echo "</tr>";
				$i++;
            }
		$range = $result_list_offset." to ".($result_list_offset+$rows-1);
        }
        ?>
        </table>
        <p align="center"><?php echo " ".($result_list_offset > 1 ? "<a href='tests.php?increment=-1&result_list_offset=$result_list_offset'><<</a>" : "")." <b>".$range.
        "</b>".($rows == $results_per_page ? "<a href='tests.php?increment=1&result_list_offset=$result_list_offset'>>></a>" : "")." ";?></p>
        <p><a href="add_test.php">Add Test</a> | <a href="tests.php?generate_xls=true">Export to Excel</a></p>
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