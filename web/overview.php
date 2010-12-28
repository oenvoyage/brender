<h2>//<b>overview</b> <?php output_refresh_button(); ?> </h2>
<?php
if (isset($_GET['orderby_job'])) {
	if ($_SESSION[orderby_jobs]==$_GET[orderby_job]) {
		$_SESSION[orderby_jobs]=$_GET['orderby_job']." desc";
	}
	else {
		$_SESSION[orderby_jobs]=$_GET['orderby_job'];
	}
}
if (isset($_GET['orderby_client'])) {
	if ($_SESSION[orderby_client]==$_GET[orderby_client]) {
		$_SESSION[orderby_client]=$_GET['orderby_client']." desc";
	}
	else {
		$_SESSION[orderby_client]=$_GET['orderby_client'];
	}
}

#check_if_client_should_work();
?>
<table>
	<tr><td>
	<tr class=header_row><td>// clients </td><td>// last rendered frame</td></tr>
	<tr><td>
		<?php show_client_list();?>
	</td><td>
		 <?php show_last_rendered_frame("full");?>
	</td></tr>
	<tr class="spacer_row"></tr>
	<tr class=header_row><td colspan=2>
		//<b>current jobs</b>
	</td></tr>
	<tr><td colspan=2>
		<?php show_job_list(); ?>
	</td></tr>
	<tr class=header_row><td colspan=2>
		//<b>last logs</b>
	</td></tr>
	<tr><td colspan=2>
		 <?php show_last_log();?>
	</td></tr>
</table>

<?php
function show_last_log() {
	#print "<h2>// last logs</h2>";
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
		<td class="header_td" width=200> <a href="index.php?orderby_client=client">client name</a> </td>
 		<td><a href="index.php?orderby_client=status">status</a></td>
		<td> &nbsp; <a href="index.php?orderby_client=info">info</a></td>
		<td width=120><b> &nbsp; &nbsp; </td>
	</tr>
	<?php
	 if (mysql_num_rows($results)==0) {
		echo '"<tr><td class="header_row error" colspan=8> NO clients running (<a href="index.php?view=clients">click here to add/manage</a>)</td></tr>';
        } 
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$status=$row->status;
		$info=$row->info;
		$speed=$row->speed;
		$machinetype=$row->machinetype;
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
			<td class=neutral><a href=\"index.php?view=view_client&client=$client\"><font size=3>$client</font></a> <font size=1>($machinetype)</font></td> 
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
		$job_query="select * from jobs where (project in (select name from projects where status='active')) and status NOT like '%finish%' order by $_SESSION[orderby_jobs]";
		$results=mysql_query($job_query);
		if (isset($msg)) {
			print "// $msg";
		}
		debug("$job_query<br/>");
		print "<table>\n";
		print "<tr class=header_row>
			<td width=12></td>
			<td width=120><a href=\"index.php?orderby_job=shot\">shot name</a></td>
			<td width=120> &nbsp; <a href=\"index.php?orderby_job=progress_status\">progress status</a></td>
			<td width=120> &nbsp; <a href=\"index.php?orderby_job=config\">output</a></td>
			<td width=10> &nbsp; <a href=\"index.php?orderby_job=start\">start</a>-<a href=\"index.php?orderby_job=end\">end</a> &nbsp; </td>
			<td width=10> &nbsp; <a href=\"index.php?orderby_job=chunks\">chunks</a> &nbsp; </td>
			<td width=50> &nbsp; <a href=\"index.php?orderby_job=current\">current</a> </td>
			<td width=60> &nbsp; rendered</td>
			<td width=12> &nbsp; <a href=\"index.php?orderby_job=status\">status</a> &nbsp; </td>
			<td width=60> &nbsp; </td>
			<td width=10> &nbsp; <a href=\"index.php?orderby_job=priority\">priority</a></td>
		</tr>";
		if (mysql_num_rows($results)==0) {
                	echo '"<tr><td class="header_row warning" colspan=11> no jobs running</td></tr>';
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
			$icon="play";
			$play_pause_button="";
			$status_class=get_css_class($status);
			$priority_color=get_priority_color($priority);
			$thumbnail_img=get_thumbnail_image($id,$start,"small");
			$thumbnail="<a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\">$thumbnail_img</a>";

			if (preg_match("/(rendering|waiting)/",$status)) {
				$play_pause_button="<a href=\"index.php?pause=$id\"><img src=\"images/icons/pause.png\" /></a>";
			}
			else {
				$play_pause_button="<a href=\"index.php?start=$id\"><img src=\"images/icons/play.png\" /></a>";
			}
	
			print "<tr class=$status_class>
				<td class=neutral><a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\">$thumbnail</a></td> 
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
				</td>
				<td bgcolor=$priority_color>$priority</td>
			</tr>";
		}?>
		</table>
		<div class="table-controls">
			<a id="new_job2" href="#"><b class="ordre">new job</a></b> -
			<a href="index.php?view=jobs&restart_all_paused=1"><b class="ordre">restart all paused jobs</b></a> -
			<a href="index.php?view=jobs&x=$random_x"><b class="ordre">reload</a></b> -
			<a href="index.php?view=jobs&restart_all=1"><b class="ordre">restart all</b></a>
		</div>
		<?php
	}
?>
