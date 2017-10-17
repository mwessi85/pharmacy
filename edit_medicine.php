<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php
if(isset($_GET['id'])){
	//$sql = "SELECT m.generic_name, m.trade_name, m.category, m.weight, m.unit, ";
	$sql = "SELECT * FROM medicine m JOIN category c JOIN units u WHERE m.id=".$_GET['id']." AND m.category = c.id AND m.unit=u.id";

	$prepop_result= mysql_query($sql, $connection);
    $prepop = mysql_fetch_assoc($prepop_result);
}
if(isset($_POST['edit_medicine'])){
	
	$errors = array();
	$generic_name = ucfirst(strtolower(trim(mysql_prep($_POST['generic_name']))));
	$trade_name = ucfirst(strtolower(trim(mysql_prep($_POST['trade_name']))));
	$weight = trim(mysql_prep($_POST['weight']));
	$unit = $_POST['unit'];
	$category = $_POST['category'];
	if(empty($errors)){
		
		$query = "UPDATE medicine SET 
		generic_name = '".$generic_name."', trade_name='".$trade_name."', category=".$category.", weight=".$weight.", unit=".$unit." WHERE id=".$_GET['id'];
		

		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The medicince was sucessfully uupdated";
			redirect_success("medicines.php", $message);
		}
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Edit Medicine</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="edit_medicine.php?id=<?php echo $_GET['id'];?>" method="post">
            <label>Generic Name</label>
            <input type="text" name="generic_name" id="generic_name" maxlength="25" 
            value="<?php echo $prepop['generic_name']?>" required autofocus placeholder="Enter medicine e.g Paracetamol"/>
            <label>Trade Name</label>
            <input type="text" name="trade_name" id="trade_name" maxlength="25" 
            value="<?php echo $prepop['trade_name'];?>" required placeholder="Enter Trade name e.g Panadol"/>
            <label>Category</label>
            <select name="category" id="category">
                <option disabled selected>Choose medicine category</option>
                <?php $name_result= mysql_query("SELECT * FROM category ORDER BY name;");
					while($select_category = mysql_fetch_assoc($name_result)){
						echo "<option value = '".$select_category["id"]."' ";
						if($select_category["id"] == $prepop["category"]){
							echo " selected=\"selected\"";	
						}
						echo ">".$select_category["name"]." </option>";
					} 
                      
     ?>
            </select>
            <label>Weight</label>
            <input type="text" name="weight" id="weight" 
            value="<?php echo $prepop['weight']?>" placeholder=" Enter drug weight"/>
            <label>Sales Unit</label>
            <select name="form" id="form">
                <option disabled selected>Choose a sales unit</option>
                <?php $form_result= mysql_query("SELECT * FROM form ORDER BY form;");
					while($select_form = mysql_fetch_assoc($form_result)){
						echo "<option value = '".$select_form["id"]."' ";
						if($select_form["id"] == $prepop["form"]){
							echo " selected=\"selected\"";	
						}
						echo ">".ucfirst($select_form["form"])." </option>";
					} 
                      
     ?>
            </select>
            <label>Unit</label>
            <select name="unit" id="unit">
                <option selected disabled>Choose metric unit</option>
                <?php $result = mysql_query("SELECT * FROM units", $connection);
                while($select_unit = mysql_fetch_assoc($result)){
					echo "<option value = '".$select_unit["id"]."' ";
					if($select_unit["id"] == $prepop["unit"]){
						echo " selected=\"selected\"";	
					}
					echo ">".$select_unit["value"]." </option>";
				} 
                ?>
            </select>
            <input type="submit" name="edit_medicine" id="edit_medicine" value="Submit" />
        </form>
        <p><a href="medicines.php">Show Medicines</a> | <a href="units.php">Show Units</a> | <a href="categories.php">Show Categories</a></p>
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

