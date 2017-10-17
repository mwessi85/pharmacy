<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?> 

<?php include("includes/header.php");?>
<div id="main-copy"><h1>Units</h1>
<?php echo "<p class='notice'>".success_message()."</p>"; ?>
<table class='results'>
<tr>
<td class='column_head'>Unit</td>
<td class='column_head'>Unit Value</td>
<td class='column_head'></td>
</tr>
<?php 
$query = "SELECT * FROM units ORDER BY value ASC ";
$result = mysql_query($query, $connection);
$i = 0;
if(isset($result)){	
	while($category = mysql_fetch_array($result)){
		$unit_id = $category['id'];
		$value = $category['value'];
		echo "
		<tr>
		<td ".even_strip($i).">".$unit_id."</td>
		<td ".even_strip($i).">".$value."</td>
		<td ".even_strip($i)."><a href='edit_unit.php?id=".$unit_id."'>Edit</a></td>
		</tr>";
		$i++;
	}
	unset($result);
}
?>
</table>
<p><a href="add_unit.php">Add Unit</a>
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
