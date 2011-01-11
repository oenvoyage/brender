<?php
include_once("../tpl/connect.php");
include_once("../../functions.php");

$session_user = $_SESSION['user'];

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
		if ($_POST['directstart'] == "true"){
			$status="waiting";
			$msg = "New job direct started.".$_POST['directstart']; # TODO
		}
		else {
			$status="pause";
			$msg = "New job submitted and waiting to be started.".$_POST['directstart'];
			
		}

			$jobid = $_POST['updateid'];
			/*if ($_POST['copy'] == "copy job") {
				#----update COPY so we create a new job-------
				$query="INSERT INTO jobs VALUES('','$_POST[scene]','$_POST[shot]','$_POST[start]','$_POST[end]','$_POST[project]','$_POST[start]','$_POST[chunks]','$_POST[filetype]','$_POST[rem]','$_POST[config]','active','$_POST[progress_status]','$_POST[progress_remark]','$_POST[priority]',now(),'$_SESSION[user]')";
	            mysql_query($query);
				print "COPYPROCESS = $_POST[copy] and query = $query";
			} else {*/
				#----update UPDATE so we just update the job-------
				$queryqq="UPDATE jobs SET start='$start', end='$end', filetype='$filetype', config='$config', chunks='$chunks', priority='$priority', progress_status='$prog_status', progress_remark='$rem', lastseen=NOW(), last_edited_by='$_SESSION[user]' where id=$jobid;";
				if (($_POST['directstart']) == "true"){
					//print "direct start...";
					$queryqq="UPDATE jobs SET start='$start', current='$start', end='$end', filetype='$filetype', config='$config', chunks='$chunks', priority='$priority', status='waiting', last_edited_by='$session_user', lastseen=NOW() where id=$jobid;";
				}	
			mysql_query($queryqq);
			/*}*/
		
		echo "{\"status\":true, \"msg\":\"$msg\", \"query\":\"$dberror\"}";
		
	}
	else {
		//$error="please enter new job infos<br/>";
		echo "{\"status\":false, \"msg\":\"Epic Fail: please enter scene and shot name.\"}";
	}
?>
