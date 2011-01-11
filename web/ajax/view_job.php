<?php
include_once("../tpl/connect.php");
include_once("../../functions.php");

if ($_POST['scene'] && $_POST['shot'] && $_POST['updateid']) {
		$prog_status = $_POST['progress_status'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$shot = $_POST['shot'];
		$project = $_POST['project'];
		$scene = $_POST['scene'];
		$filetype = $_POST['filetype'];
		$rem = $_POST['rem'];
		$config = $_POST['config'];
		$chunks = $_POST['chunks'];
		$priority = $_POST['priority'];
		
		$jobid = $_POST['updateid'];
		$session_user = $_SESSION['user'];
		$scene = $_POST['scene'];
		$shot = $_POST['shot'];
		
		if ($_POST['directstart'] == "true"){
			$status="waiting";
			$msg = "Edited job direct started.".$_POST['directstart']; # TODO
		}
		else {
			$status="pause";
			$msg = "Edited job submitted and waiting to be started. Autostart: ".$_POST['directstart'];
			
		}


		if ($_POST['action'] == "duplicate") {
			#----update COPY so we create a new job-------
			$query="INSERT INTO jobs VALUES('','$scene','$shot','$start','$end','$project','$start','$chunks','$filetype','$rem','$config','active','$progress_status','$rem','$priority',now(),'$user')";
            mysql_query($query);
		} else {
			#----update UPDATE so we just update the job-------
			$queryqq="UPDATE jobs SET start='$start', end='$end', filetype='$filetype', config='$config', chunks='$chunks', priority='$priority', progress_status='$prog_status', progress_remark='$rem', lastseen=NOW(), last_edited_by='$_SESSION[user]' where id=$jobid;";
			if (($_POST['directstart']) == "true"){
				$queryqq="UPDATE jobs SET start='$start', current='$start', end='$end', filetype='$filetype', config='$config', chunks='$chunks', priority='$priority', status='waiting', last_edited_by='$_SESSION[user]', lastseen=NOW() where id=$jobid;";
			}	
			mysql_query($queryqq);
		}
		
		echo "{\"status\":true, \"msg\":\"$msg\", \"query\":\"$dberror\"}";
		
	}
	else {
		echo "{\"status\":false, \"msg\":\"Epic Fail: error processing POST data.\"}";
	}
?>
