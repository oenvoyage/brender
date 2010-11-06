<?php
	session_start();
	if(isset($_GET['view'])) {
		$view = $_GET['view'];
	}
	require_once ('tpl/header.php');

	require_once('tpl/menu.php');
	
?>
	<div id="section">
	<?php
	
		if (!$_SESSION['user']) {
				print('<div class="dialog">');
				include "login.php";
				print('</div>');					
		} else {
	 
			if($view == "") { 
				include "clients.php";
				include "jobs.php";
				include "orders.php";
			}
			
			if($view == "login") { 
				include "login.php";
			}
			
			if($view == "clients") { 
				include "clients.php";
			}
			
			if($view == "jobs") { 
				include "jobs.php";
			}
			
			if($view == "orders") { 
				include "orders.php";
			}
			
			if($view == "upload") { 
				print('<div class="dialog">');
				include "upload.php";
				print('</div>');
			}
			
			if($view == "settings") { 
				include "settings.php";
			}
			
			if($view == "status") { 
				include "status.php";
			}
	
			if($view == "logs") { 
				include "logs.php";
			}
			
			if($view == "projects") {
				include "projects.php";
			}
			
			if($view == "view_job") {
				include "view_job.php";
			}			
	
		}
	?>	
	</div>
</div>
<?php
	include "tpl/footer.php"
?>
