<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php

if(isset($_POST['add_medicine'])){
	$errors = array();
	$generic_name = ucfirst(strtolower(trim(mysql_prep($_POST['generic_name']))));
	$trade_name = ucfirst(strtolower(trim(mysql_prep($_POST['trade_name']))));
	$weight = trim(mysql_prep($_POST['weight']));
	$unit = $_POST['unit'];
	$category = $_POST['category'];
	$form = $_POST['form'];
	$query = "SELECT * FROM medicine 
	WHERE generic_name='".$generic_name."' AND trade_name='".$trade_name."' AND weight=".$weight." AND unit=".$unit;
	$result = mysql_query($query, $connection);
	if(mysql_num_rows($result) == 1){
		$errors['The drug '.$generic_name.'/'.$trade_name.''.$weight] = " already exists";
	}
	if(empty($errors)){
		$query = "INSERT INTO medicine(generic_name, trade_name, category, weight, form, unit) 
		VALUES('".$generic_name."', '".$trade_name."', ".$category.", ".$weight.", ".$form.", ".$unit.")";
		$result = mysql_query($query, $connection);
		if(isset($result)){
			$message = "The medince was sucessfully added";
			redirect_success("medicines.php", $message);
		}
	}
}
?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Add new Medicine</h1>
        <?php show_errors();?>
        <?php if(!empty($message)){echo "<p class=\"notice\">".$message."</p>";}?>
        <form autocomplete="off" action="add_medicine.php" method="post">
            <label>Generic Name</label><input type="text" name="generic_name" id="generic_name" maxlength="25" value="" required autofocus placeholder="Enter medicine e.g Paracetamol"/>
            <label>Trade Name</label><input type="text" name="trade_name" id="trade_name" maxlength="25" value="" required placeholder="Enter Trade name e.g Panadol"/>
            <label>Category</label>
            <select name="category" id="category">
                <option disabled selected>Choose medicine category</option>
                <?php $result= mysql_query("SELECT * FROM category ORDER BY name;");
                    while($category = mysql_fetch_assoc($result)){
                    echo "<option value = '".$category["id"]."'>".ucfirst(strtolower($category["name"]))."</option>";
                } 
                ?>
            </select>
            <label>Weight</label><input type="text" name="weight" id="weight" value="" required placeholder=" Enter drug weight"/>
            <label>Sales Unit</label>
            <select name="form" id="form">
                <option disabled selected>Choose a sales unit</option>
                <?php $result= mysql_query("SELECT * FROM form ORDER BY form;");
                    while($form = mysql_fetch_assoc($result)){
                    echo "<option value = '".$form["id"]."'>".ucfirst(strtolower($form["form"]))."</option>";
                } 
                ?>
            </select>
            <label>Unit</label>
            <select name="unit" id="unit">
                <option selected disabled>Choose metric unit</option>
                <?php 	$result = select_units();
						$result = select_units();
                while($select_unit = mysql_fetch_assoc($result)){
                    echo "<option value = '".$select_unit["id"]."'>";
                    echo $select_unit["value"]." </option>";
                } 
                ?>
            </select>
            <input type="submit" name="add_medicine" id="add_medicine" value="Submit" />
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

