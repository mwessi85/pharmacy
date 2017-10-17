<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in();?>
<?php include("includes/header.php");?>
<div id="main-copy">
        <h1>Welcome</h1>
		<?php echo "<p class='notice'>".success_message()."</p>"; ?>
        <div>
		<?php
        if(isset($_GET['page_reciept']) && isset($_GET['transaction_no'])){
			//echo $_GET['transaction_no'];
        ?>
        <?php if(!empty($_GET['message'])){echo "<p class=\"notice\">".$_GET['message']."</p>";}?>
        <?php echo "
		<form name='1' autocomplete='off' action='".$_GET['next'].".php?customer_val=".$_GET['customer_val']."' method='post'>
            <input type='hidden' name='transaction_no' id='transaction_no' value = '".$_GET['transaction_no']."'/>
            <input type='submit' value='".$_GET['button']."' />
        </form>
		<p>&nbsp;</p>
        <form name='1' autocomplete='off' action='".$_GET['page_reciept'].".php' method='post'>
            <input type='hidden' name='transaction_no' id='transaction_no' value = '".$_GET['transaction_no']."'/>
            <input type='submit' value='Print Reciept' />
        </form>";
		}
        ?>
        </div>
        </div>
        <div id="footer">
         <?php 
		if(isset($connection)){
			check_sense();
			mysql_close($connection);
		}
		?>
        <div style="text-align:right"><!--Developed by Mutebi Michael--></div>
        </div>
    </body>
</html>