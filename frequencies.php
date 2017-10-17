<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?> 

<?php include("includes/header.php");?>
<div id="main-copy"><h1>Frequencies</h1>
<?php echo "<p class='notice'>".success_message()."</p>"; ?>
<table class='results'>
<tr>
<td class='column_head'>ID</td>
<td class='column_head'>Frequency</td>
<td class='column_head'>Times a day</td>
<td class='column_head'>details</td>
<td class='column_head'></td>
</tr>
<?php 
$query = "SELECT * FROM frequency ORDER BY id ASC ";
$result = mysql_query($query, $connection);
if(isset($result)){	
	$i = 0;
	while($frq = mysql_fetch_array($result)){
		echo "
		<tr>
		<td ".even_strip($i).">".$frq['id']."</td>
		<td ".even_strip($i).">".$frq['frequency']."</td>
		<td ".even_strip($i).">".$frq['times']."</td>
		<td ".even_strip($i).">".$frq['details']."</td>
		<td ".even_strip($i)."><a href='edit_frequency.php?id=".$frq['id']."'>Edit</a></td>
		</tr>";
		$i++;
	}
}
?>
</table>
<p><a href="add_frequency.php">Add Frequency</a>
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
