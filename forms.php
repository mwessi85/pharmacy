<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?> 

<?php include("includes/header.php");?>
<div id="main-copy"><h1>Forms</h1>
<?php echo "<p class='notice'>".success_message()."</p>"; ?>
<table class='results'>
<tr>
<td class='column_head'>Form ID</td>
<td class='column_head'>Medicine Form</td>
<td class='column_head'>Unit of sale</td>
<td class='column_head'></td>
</tr>
<?php 
$query = "SELECT * FROM form ORDER BY form ASC ";
$result = mysql_query($query, $connection);
if(isset($result)){	
	$i = 0;
	while($form = mysql_fetch_array($result)){
		$form_id = $form['id'];
		$form_name = $form['form'];
		$sales_unit = $form['sales_unit'];
		
		echo "
		<tr>
		<td ".even_strip($i).">".$form_id."</td>
		<td ".even_strip($i).">".$form_name."</td>
		<td ".even_strip($i).">".$sales_unit."</td>
		<td ".even_strip($i)."><a href='edit_form.php?id=".$form_id."'>Edit</a></td>
		</tr>";
		$i++;
	}
	unset($result);
}
?>
</table>
<p><a href="add_form.php">Add Form</a>
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
