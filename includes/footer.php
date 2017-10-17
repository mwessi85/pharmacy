        <div id="alert">
            <?php //check_stock();?>
        </div>
    </article>
    <aside>
    <nav  id="archives">
		<?php
        if($_SESSION['level'] == "admin"){
        echo admin_nav();
        }
        else if($_SESSION['level'] == "staff"){
        echo clerk_nav();
        }
        ?>
    </nav>
    </aside>
    </section>
    <footer id="pageFooter">
    <?php 
		if(isset($connection)){
			mysql_close($connection);
		}
	?>
    </footer>
</body>
</html>