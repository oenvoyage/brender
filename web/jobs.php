<?php
$msg="";
$queryqq="";
if (isset($_GET['order_by'])) {
	if ($_SESSION[orderby_jobs]==$_GET[order_by]) {
		$_SESSION[orderby_jobs]=$_GET['order_by']." desc";
	}
	else {
		$_SESSION[orderby_jobs]=$_GET['order_by'];
	}
}

#----------------------------
if (isset($_GET['restart_all_paused'])) {
	$queryqq="UPDATE jobs SET current=start,status='waiting' WHERE (project IN (SELECT name FROM projects WHERE status='active') AND status='pause');";
	output("ALL PAUSED JOBS RESTARTED");
	mysql_query($queryqq);
}
if (isset($_GET['restart_all'])) {
	$queryqq="UPDATE jobs SET current=start,status='waiting' WHERE (project IN (SELECT name FROM projects WHERE status='active'));";
	output("ALL JOBS RESTARTED");
	mysql_query($queryqq);
}
if (isset($_POST['updateid'])) {
	$jobid=$_POST['updateid'];
	if ($_POST['copy']=="copy job") {
		#----update COPY so we create a new job-------
		$query="INSERT INTO jobs VALUES('','$_POST[scene]','$_POST[shot]','$_POST[start]','$_POST[end]','$_POST[project]','$_POST[start]','$_POST[chunks]','$_POST[filetype]','$_POST[rem]','$_POST[config]','active','$_POST[progress_status]','$_POST[progress_remark]','$_POST[priority]',now(),'$_SESSION[user]')";
                mysql_query($query);
		print "COPYPROCESS = $_POST[copy] and query = $query";
	}
	else {
		#----update UPDATE so we just update the job-------
		$queryqq="UPDATE jobs SET start='$_POST[start]',end='$_POST[end]',filetype='$_POST[filetype]',config='$_POST[config]',chunks='$_POST[chunks]',priority='$_POST[priority]',progress_status='$_POST[progress_status]',progress_remark='$_POST[progress_remark]', lastseen=NOW(),last_edited_by='$_SESSION[user]'  where id=$jobid;";
		if (isset($_POST['directstart'])){
			print "direct start...";
			$queryqq="UPDATE jobs SET start='$_POST[start]',current='$_POST[start]', end='$_POST[end]',filetype='$_POST[filetype]',config='$_POST[config]',chunks='$_POST[chunks]',priority='$_POST[priority]',status='waiting', lastseen=NOW() where id=$jobid;";
		}	
		mysql_query($queryqq);
		#$msg= "job $jobid updated $queryqq<br/>";
	}
	
}
if (isset($_GET['pause'])) {
	$queryqq="update jobs set status='pause' where id=$_GET[pause];";
	mysql_query($queryqq);
}
if (isset($_GET['finish'])) {
	$queryqq="update jobs set status='finished +' where id=$_GET[finish]";
	mysql_query($queryqq);
}
if (isset($_GET['reset'])) {
	$queryqq="update jobs set current=start where id=$_GET[reset];";
	mysql_query($queryqq);
}
if (isset($_GET['start'])) {
	$queryqq="update jobs set status='waiting' where id=$_GET[start];";
	mysql_query($queryqq);
}
if (isset($_GET['del'])) {
	$queryqq="delete from jobs where id=$_GET[del];";
	output("DELETED job $_GET[del]");
	mysql_query($queryqq);
	# sleep(1);
}

# ---------------------------------------------
#-------- Display Table with all jobs ---------
# ---------------------------------------------
	// select and display all jobs from active projects
	$job_query="select * from jobs where (project in (select name from projects where status='active')) order by $_SESSION[orderby_jobs]";
	#print "<h2>job query $job_query</h2>";
	debug("$job_query<br/>");
	$results=mysql_query($job_query);
	?>

	<h2>// <b>jobs</b> <?php output_refresh_button() ?><br/></h2>
	<table>
	<tr class=header_row>
		<td></td>
		<td></td>
		<td> &nbsp; <a href="index.php?view=jobs&order_by=shot">shot</a></td>
		<td> &nbsp; <a href="index.php?view=jobs&order_by=progress_status">progress status</a></td>
		<td><a href="index.php?view=jobs&order_by=scene">scene name</a></td>
		<td><a href="index.php?view=jobs&order_by=config">output</a></td>
		<td><a href="index.php?view=jobs&order_by=start">start</a>-<a href="index.php?view=jobs&order_by=end">end</a></td>
		<td><a href="index.php?view=jobs&order_by=chunks">chunks</a></td>
		<td><a href="index.php?view=jobs&order_by=current">current</a> </td>
		<td>rendered</td>
		<td width=10><a href="index.php?view=jobs&order_by=status">status</a> &nbsp; </td>
		<td width=70></td>
		<td width=80><a href="index.php?view=jobs&order_by=lastseen">lastseen</a></td>
		<td width=80><a href="index.php?view=jobs&order_by=last_edited_by">last edited by</a></td>
		<td> &nbsp; <a href="index.php?view=jobs&order_by=priority">priority</a></td>
		<td> &nbsp; </td>
	</tr>
	<?php 
	if (mysql_num_rows($results)==0) {
		// there is no jobs to display.... so display a little warning instead :)
       		echo '"<tr><td class="header_row warning" colspan=17> no jobs found (<a id="new_job_button3" href="#">click here to add</a>) or check <a href="index.php?view=projects">active projects</a></td></tr>';
        }

	while ($row=mysql_fetch_object($results)){

		$id=$row->id;
		$padded_id=str_pad((int) $id,3,"0",STR_PAD_LEFT);
		$scene=$row->scene;
		$project=$row->project;
		$scene=$row->scene;
		$shot=$row->shot;

		$start=$row->start;
		$start_padded=str_pad((int) $start,4,"0",STR_PAD_LEFT);
		$end=$row->end;
		$current=$row->current;
		$chunks=$row->chunks;

		$rem=$row->rem;
		$filetype=$row->filetype;
		$config=$row->config;
		$status=$row->status;
		$priority=$row->priority;
		$lastseen=$row->lastseen;
		$last_edited_by=$row->last_edited_by;
		$progress_status=$row->progress_status;
		$progress_remark=$row->progress_remark;

		$total_frames=$end-$start+1;
		$total_rendered=count_rendered_frames($id);
		$status_class=get_css_class($status);
		$priority_color=get_priority_color($priority);
		$thumbnail_image=get_thumbnail_image($id,$start,"thumbnail small");;
		
		if (preg_match("/(rendering|waiting)/",$status)) {
                	$play_pause_button="<a href=\"index.php?view=jobs&pause=$id\"><img src=\"images/icons/pause.png\" /></a>";
		}
		else {
	                $play_pause_button="<a href=\"index.php?view=jobs&start=$id\"><img src=\"images/icons/play.png\" /></a>";
		}

		print "<tr class=$status_class>
			<td>$padded_id</td> 
			<td class=neutral><a href=\"index.php?view=view_job&id=$id&x=$random_x\">$thumbnail_image</a></td> 
			<td class=neutral><a href=\"index.php?view=view_job&id=$id&x=$random_x\"><b>$shot <font size=1>($project)</b></a></td>
			<td>
				<span class=\"progress-bar\">".output_progress_bar($start,$end,$current)."</span><br/>
				$progress_status <small>$progress_remark</small>
			</td>
			<td><b>$scene</b></td> 
			<td>$config $filetype</td>
			<td>$start - $end</td>
			<td>$chunks</td>
			<td><b>$current</b></td>
			<td>$total_rendered / $total_frames<br></td>
			<td>$status</td>
			<td>
				 <a href=\"index.php?view=jobs&reset=$id&pause=$id\"><img src=\"images/icons/restart.png\" /></a>
				 $play_pause_button
				 <a href=\"index.php?view=jobs&finish=$id\"><img src=\"images/icons/stop.png\" /></a>
			</td>
			<td>$lastseen</a><br/>
			<td>$last_edited_by</a><br/>
			<td bgcolor=$priority_color>$priority</a></td>
			<td><a href=\"index.php?view=jobs&del=$id\"><img src=\"images/icons/close.png\"></a></td>
		</tr>";
	} ?>
	</table>
	<div class="table-controls">
		<a id="new_job_button2" href="#"><b class="ordre">new job</a></b> - 
		<a href="index.php?view=jobs&restart_all_paused=1"><b class="ordre">restart all paused jobs</b></a> - 
		<a href="index.php?view=jobs&x=<?php print $random_x ?>"><b class="ordre">reload</a></b> - 
		<a href="index.php?view=jobs&restart_all=1"><b class="ordre">restart all</b></a>
	</div>
