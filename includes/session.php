<?php require_once("includes/functions.php");?>
<?php 
session_start();
$td = date("Y-m-d");
$str = strtotime($td);
function confirm_logged_in(){
	if(!$_SESSION['user_id']){
		check_sense();
		redirect_to("index.php");		
	}
}
function check_position(){
	$level = ($_SESSION['level']);	
	return $level;
}
function show_logout(){
	check_sense();
	if(isset($_SESSION['user_id'])){
		global $connection;
		$user_id = $_SESSION['user_id'];
		$result = select_user($user_id);
		if(mysql_num_rows($result) == 1){
			$logged_user = mysql_fetch_array($result);
		echo "<p>".ucfirst(strtolower($logged_user['level']))." | <a href=\"user.php?id=".$user_id."\">".$logged_user['first_name']." ".$logged_user['last_name']."</a> | <a href=\"logout.php\">
		Logout</a></p>";
		}
	}
}
?>
