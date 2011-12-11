<?php
if (isset($_GET['orderby_job'])) {
	if ($_SESSION['orderby_jobs']==$_GET['orderby_job']) {
		$_SESSION['orderby_jobs']=$_GET['orderby_job']." desc";
	}
	else {
		$_SESSION['orderby_jobs']=$_GET['orderby_job'];
	}
}
if (isset($_GET['orderby_client'])) {
	if ($_SESSION['orderby_client']==$_GET['orderby_client']) {
		$_SESSION['orderby_client']=$_GET['orderby_client']." desc";
	}
	else {
		$_SESSION['orderby_client']=$_GET['orderby_client'];
	}
}

#check_if_client_should_work();
?>
<script>
$(document).ready(function() { 
        $("#current_jobs").tablesorter(); 
}); 
</script>

<table class="overview_grid">
	<tr><td>
	<tr class="header_row overview"><td>// <strong>clients</strong> <?php output_refresh_button(); ?></td><td>// <strong>last rendered frame</strong></td></tr>
	<tr><td>
		<?php show_client_list();?>
	</td><td>
		 <?php show_last_rendered_frame("full");?>
	</td></tr>
	<tr class="spacer_row"></tr>
	<tr class="header_row overview"><td colspan=2>
		// <b>current jobs</b>
	</td></tr>
</table>

<?php show_job_list(); ?>

<table class="overview_grid">
	<tr class="header_row overview"><td colspan=2>
		// <strong>last logs</strong>
	</td></tr>
	<tr><td colspan=2 class="log">
		 <?php show_last_log();?>
	</td></tr>
</table>

<?php
function show_last_log() {
	#print "<h2>// last logs</h2>";
	$a=0;
	$lok = file("../logs/brender.log");
        $lok=array_reverse($lok);
        foreach ($lok as $line){
                if ($a++>5 ) {
                        break;
                }
                print "$line<br/>";
        }

}
function show_client_list() {
#---------------------------------------
#------------ CLIENTS LIST -------------
#---------------------------------------
	$query="select * from clients where status<>'not running' order by $_SESSION[orderby_client]";
	$results=mysql_query($query);
		?>
	<table border=0>
	<tr class="header_row">
		<td class="header_td" width="200"> <a href="index.php?orderby_client=client">client name</a> </td>
 		<td><a href="index.php?orderby_client=status">status</a></td>
		<td> &nbsp; <a href="index.php?orderby_client=info">info</a></td>
		<td width=120><b> &nbsp; &nbsp; </td>
	</tr>
	<?php
	 if (mysql_num_rows($results)==0) {
		echo '<tr><td class="header_row error" colspan=8> NO clients running (<a href="index.php?view=clients">click here to add/manage</a>)</td></tr>';
        } 
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$status=$row->status;
		$info=$row->info;
		$speed=$row->speed;
		$machine_type=$row->machine_type;
		$speed=$row->speed;
		$status_class=get_css_class($status);
		if ($status<>"disabled") {
			$dis="<a href=\"index.php?view=clients&disable=$client\">disable</a>";
		}
		if ($status=="disabled") {
			$dis="<a href=\"index.php?view=clients&enable=$client\">enable</a>";
		}
		else if ($status=="notrunning") {
			$dis="";
		}
		print "<tr class=$status_class>
			<td class=neutral><a href=\"index.php?view=view_client&client=$client\"><font size=3>$client</font></a> <font size=1>($machine_type)</font></td> 
			<td>$status</td>
			<td>$info</td>
			<td>$dis</td>

		</tr>";
	}
	print "</table>";

}
function show_job_list() {
	#----------------------------
	#-------JOB LIST-------------
	#----------------------------
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
	
	# ---------------------------------------------
	#-------- Display Table with all jobs ---------
	# ---------------------------------------------
		$job_query="select *,end-start as total from jobs where (project in (select name from projects where status='active')) and status NOT like '%finish%' order by $_SESSION[orderby_jobs]";
		$results=mysql_query($job_query);
		if (isset($msg)) {
			print "// $msg";
		}
		debug("$job_query<br/>");?>
		<table id="current_jobs" class="tablesorter">
			<thead> 
				<tr class=header_row>
					<th width=12></th>
					<th width=120>shot name</th>
					<th width=120>progress status</th>
					<th width=120>output</th>
					<th width=10>startend</th>
					<th width=10>chunks</th>
					<th width=50>current</th>
					<th width=60>rendered</th>
					<th width=12>status</th>
					<th width=60> &nbsp; </th>
					<th width=10>priority</th>
				</tr>
			</thead>
			<tbody>
		<?php
		if (mysql_num_rows($results)==0) {
                	echo '<tr><td class="header_row warning" colspan=11> no jobs running</td></tr>';
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
			#$total_rendered=count_rendered_frames($id);  // diasable temporarly as it is sometimes slow (especially on a network server)
			$total_rendered="";
			$icon="play";
			$play_pause_button="";
			$status_class=get_css_class($status);
			$priority_color=get_priority_color($priority);
			$thumbnail_img=get_thumbnail_image($id,$start,"small");
			$x=$GLOBALS['random_x'];
			$thumbnail="<a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\">$thumbnail_img</a>";

			if (preg_match("/(rendering|waiting)/",$status)) {
				$play_pause_button="<a href=\"index.php?pause=$id\"><img src=\"images/icons/pause.png\" /></a>";
			}
			else {
				$play_pause_button="<a href=\"index.php?start=$id\"><img src=\"images/icons/play.png\" /></a>";
			}
	
			print "<tr class=$status_class>
				<td class=plain><a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\">$thumbnail</a></td> 
				<td class=neutral><a href=\"index.php?view=view_job&id=$id&x=$x\"><b>$shot</b> <br /><font size=1>($project)</font></a></td> 
				<td>
					<span class=\"progress-bar\">".output_progress_bar($start,$end,$current)."</span><br/>
					$progress_status <small>$progress_remark</small>
				</td>
				<td>$config $filetype</td>
				<td>$start - $end</td>
				<td>$chunks</td>
				<td><b>$current</b></td>
				<td>$total_rendered/$total_frames<br></td>
				<td>$status</td>
				<td>
					<a href=\"index.php?reset=$id&pause=$id\"><img src=\"images/icons/restart.png\" /></a>
					$play_pause_button
					<a href=\"index.php?finish=$id\"><img src=\"images/icons/stop.png\" /></a>
					<a href=\"index.php?reset=$id&start=$id\"><img src=\"images/icons/reload.png\" /></a>
				</td>
				<td bgcolor=$priority_color>$priority</td>
			</tr>";
		}?>
		</tbody>
		</table>
		<div class="table-controls">
			<a class="btn" id="new_job_button2" href="#">new job</a>
			<a class="btn" href="index.php?view=jobs&x=<?php print $GLOBALS['random_x'] ?>">reload</a>
			<a class="btn" href="index.php?view=jobs&restart_all_paused=1">restart all paused jobs</a>
			<a class="btn" href="index.php?view=jobs&restart_all=1">restart all</a>
		</div>
		<?php
	}
?>
