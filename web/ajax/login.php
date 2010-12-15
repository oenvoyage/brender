<?php 
	include_once("../..//functions.php");
		
	if(isset($_POST['do_login'])) {
		$log = $_POST['do_login'];
	}
	
	if(isset($_POST['username'])) {
		$user = $_POST['username'];
	}
	
	if(isset($_POST['password'])) {
		$pwd = $_POST['password'];
	}
	

	if ($log == true) {
		if ($user == $pwd) {
			#session_destroy();
			session_start();
			if (init_user($user)) {
				echo "{\"status\":true, \"user\":\"$user\"}"; 
			} else {
				echo "{\"status\":false, \"msg\":\"Epic failed initializing user.\"}";
			}
		} else {
			echo "{\"status\":false, \"msg\":\"User and password must match.\"}";
		}
		
	} else {
		echo "{\"status\":false, \"msg\":\"Epic failed initializing session.\"}";
		}

?>
