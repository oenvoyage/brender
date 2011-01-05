<?php

include_once("../tpl/connect.php");
include_once("../../functions.php");

if(isset($_POST['action'])) {
		$action = $_POST['action'];
		$name = $_POST['name'];
		$machine_os = $_POST['machine_os'];
		$blender_local_path = $_POST['blender_local_path'];
		$machine_type = $_POST['machine_type'];
		$speed = $_POST['speed'];
		$working_hour_start = $_POST['working_hour_start'];
		$working_hour_end = $_POST['working_hour_end'];
		$client_priority = $_POST['client_priority'];
		
		}

if ($action == "add_client") {
		//$new_client_name=clean_name($_POST['name']);
		if (check_client_exists($name)) {
			$status="false";
			$msg="error client already exists";
		}
		else if ($name == "" ) {
			$status="false";
			$msg="error, please enter a client name";
		}
		else {
			$add_query="insert into clients values('','$name','$speed','$machine_type','$machine_os','$blender_local_path','$client_priority','$working_hour_start','$working_hour_end','not running','','')";
			mysql_query($add_query);
			//$msg="created new client $_POST[client] $add_query";
			$msg = "creation of client : $name with success";
			$status="true";
		}
		echo "{\"status\":$status, \"msg\":\"$msg\", \"query\":\"$add_query\"}";
} else {
	//$error="please enter new job infos<br/>";
	echo "{\"status\":false, \"msg\":\"Epic Fail: please enter a client name.\"}";
	}
?>
