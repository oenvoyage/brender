<?php
	session_start();
	if(isset($_GET['view'])) {
		$view = $_GET['view'];
	}
	require_once ('tpl/header.php');

	include_once("tpl/menu.php");
	
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
			print("<h2>// <strong>not available</strong></h2>");
		}

	}
?>

</div>

<?php
	include "tpl/footer.php"
?>
