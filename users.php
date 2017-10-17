<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Users</h1>
        <?php echo "<p class='notice'>".success_message()."</p>"; ?>
        <table class='results'>
        <tr>
        <td class='column_head'>Name</td>
        <td class='column_head'>Username</td>
        <td class='column_head'>Level</td>
        <td class='column_head'>Status</td>
        <td class='column_head'>Comment</td>
        <td class='column_head'></td>
        </tr>
        <?php 
        $result = select_users();
        if(isset($result)){	
			$i = 0;
			while($user = mysql_fetch_array($result)){
				$user_id = $user['id'];
				$first_name = $user['first_name'];
				$last_name = $user['last_name'];
				$username = $user['username'];
				$name = $first_name." ".$last_name;
				$level = $user['level'];
				$level = level($level);
				$status = $user['status'];
				$status = status($status);
				$comment = $user['comment'];
				echo "
				<tr>
				<td ".even_strip($i)."><a href=\"user.php?id=".$user_id."\">".$name."</a></td>
				<td ".even_strip($i).">".$username."</td>
				<td ".even_strip($i).">".$level."</td>
				<td ".even_strip($i).">".$status."</td>
				<td ".even_strip($i).">".$comment."</td>
				<td ".even_strip($i)."><a href='edit_user.php?id=".$user_id."'>Edit</a></td>
				</tr>";
				$i++;
			}
			unset($result);
        }
        ?>
        </table>
        <p><a href="add_user.php">Add System Users</a></p>
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