<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Medicine Categories</h1> 
		<?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <table class='results'>
        <tr>
        <td class='column_head'>Category</td>
        <td class='column_head'>Comment</td>
        <td class='column_head'></td>
        </tr>
        <?php 
        $query = "SELECT * FROM category ORDER BY name, comment ASC ";
        $result = mysql_query($query, $connection);
        $i = 0;
		if(isset($result)){	
            while($category = mysql_fetch_array($result)){
                $category_id = $category['id'];
                $category_name = $category['name'];
                $comment= $category['comment'];
                
                echo "
                <tr>
                    <td ".even_strip($i).">".ucfirst(strtolower($category_name))."</td>
                    <td ".even_strip($i).">".$comment."</td>
                    <td ".even_strip($i)."><a href='edit_category.php?id=".$category_id."'>Edit</a></td>
                </tr>";
				$i++;
            }
			unset($result);
        }
        ?>
        </table>
        <p><a href="add_category.php">Add Categories</a></p>
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