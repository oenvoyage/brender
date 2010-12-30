<?php

include_once("../connect.php");
include_once("../../functions.php");

if ($_POST['scene'] && $_POST['shot']) {	
		$start = $_POST[start];
		$end = $_POST[end];
		$shot = $_POST[shot];
		$project = $_POST[project];
		$scene = $_POST[scene];
		$fileformat = $_POST[fileformat];
		$rem = $_POST[rem];
		$config = $_POST[config];
		$chunks = $_POST[chunks];
		$priority = $_POST[priority];
		if ($_POST[directstart] == true){
			$status="waiting";
			$msg = "New job direct started."; # TODO
		}
		else {
			$status="pause";
			$msg = "New job submitted and waiting to be started.";
			
		}
		
		$query="insert into jobs values  ('','$scene','$shot','$start','$end','$project','$start','$chunks','$fileformat','$rem','$config','$status','new','rem','$priority',now(),'$_SESSION[user]')";
				
		mysql_query($query) or die ($dberror = mysql_error());
		//session_destroy();
		//$_SESSION['last_used_config']=$config;
		//print "<a href=\"index.php?view=jobs\">view jobs</a><br/>";
		//print "<a href=\"index.php?view=upload\">send another job</a>";
		echo "{\"status\":true, \"msg\":\"$msg\", \"query\":\"$dberror\"}";
		
	}
	else {
		//$error="please enter new job infos<br/>";
		echo "{\"status\":false, \"msg\":\"Epic Fail: please enter scene and shot name.\"}";
	}
?>
