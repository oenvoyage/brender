<?php
session_start();

include_once("../tpl/connect.php");
include_once("../../functions.php");

if ($_POST['scene'] && $_POST['shot'] && $_POST['updateid']) {
		$progress_status = $_POST['progress_status'];
		$progress_remark = $_POST['progress_remark'];
		$start = $_POST['start'];
		$end = $_POST['end'];
		$shot = $_POST['shot'];
		$project = $_POST['project'];
		$scene = $_POST['scene'];
		$filetype = $_POST['filetype'];
		$rem = $_POST['rem'];
		$config = $_POST['config'];
		$post_render_action = $_POST['post_render_action'];
		$chunks = $_POST['chunks'];
		$priority = $_POST['priority'];
		$directstart = $_POST['directstart'];
		
		$job_id = $_POST['updateid'];
		$session_user = $_SESSION['user'];
		$scene = $_POST['scene'];
		$shot = $_POST['shot'];
		$dberror = "";
		
		#print "DIRRR ---$start----";
		#  do we still need this msg dialog?
		if ($directstart == "true"){
			#$status="waiting";
			$msg = "Successfully edited job $job_id + RESTART"; # TODO
		}
		else {
			#$status="pause";
			$msg = "Successfully edited job $job_id. ";
			
		}


		if ($_POST['action'] == "duplicate") {
			#----update COPY so we create a new job-------
			$query="INSERT INTO jobs (
					scene,shot,start,end,project,
					current,chunks,filetype,rem,config,
					post_render_action,status,progress_status,progress_remark,priority,
					lastseen,created_by,last_edited_by
				) VALUES (
					'$scene','$shot','$start','$end','$project',
					'$start','$chunks','$filetype','$rem','$config',
					'$post_render_action','active','$progress_status','$progress_remark','$priority',
					now(),'$session_user','$session_user'
				)";
            		mysql_query($query);
			$msg = "Job $job_id duplicated successfully and waiting to be started";
		} else {
			#----update UPDATE so we just update the job-------
			$queryqq="UPDATE jobs SET start='$start', end='$end', filetype='$filetype', rem='$rem', config='$config', post_render_action='$post_render_action', chunks='$chunks', priority='$priority', progress_status='$progress_status', progress_remark='$progress_remark', lastseen=NOW(), last_edited_by='$_SESSION[user]' WHERE id=$job_id;";
			mysql_query($queryqq);
			if ($directstart == "true"){
				# for directstart we set the current frame position to start and set to waiting
				$querystart = "UPDATE jobs SET current='$start', status='waiting' WHERE id='$job_id';";
				mysql_query($querystart);
			}	
		}
		
		echo "{\"status\":true, \"msg\":\"$msg\", \"query\":\"$dberror\"}";
		
	}
	else {
		echo "{\"status\":false, \"msg\":\"Epic Fail: error processing POST data.\"}";
	}
?>
