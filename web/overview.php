<h2>//<b>overview</b> </h2>
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
	<tr class=header_row><td>// clients </td><td>// last log</td></tr>
	<tr><td>
		<?php show_client_list();?>
	</td><td>
		 <?php show_last_log();?>
	</td></tr>
	<tr class=header_row><td colspan=2>
		//<b>current jobs</b>
	</td></tr>
	<tr><td colspan=2>
		<?php show_job_list(); ?>
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
		$job_query="select * from jobs where (project in (select name from projects where status='active')) order by $_SESSION[orderby_jobs]";
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
			$total_rendered=get_rendered_frames($id);
			$bgcolor="#bcffa6";
			$icon="play";
			$status_class=get_css_class($status);
			$priority_color=get_priority_color($priority);

			if ($status=="rendering") {
				$icon="pause";
			}
			if ($status=="pause") {
				$icon="play";
			}

			$ext=filetype_to_ext($filetype);
			$thumbnail_image="../thumbnails/$project/$scene/$shot/$shot$start_padded.$ext";
			if (!file_exists($thumbnail_image)) {
				#print "FILE DOESNT EXIST $thumbnail_image<br/>";
				create_thumbnail($id,$start);
				}
			$thumbnail="<a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\"><img src=\"$thumbnail_image\" width=\"50\"></a>";
	
			print "<tr class=$status_class>
				<td class=neutral><a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\">$thumbnail</a></td> 
				<td class=neutral<a href=\"index.php?view=view_job&id=$id&x=$x\"><b>$shot</b> <br /><font size=1>($project)</font></a></td> 
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
					<a href=\"index.php?view=jobs&reset=$id&start=$id\"><img src=\"images/icons/restart.png\" /></a>
					<a href=\"index.php?view=jobs&pause=$id\"><img src=\"images/icons/$icon.png\" /></a>
					<a href=\"index.php?view=jobs&finish=$id\"><img src=\"images/icons/stop.png\" /></a>
				</td>
				<td bgcolor=$priority_color>$priority</td>
			</tr>";
		}?>
		</table>
		<div class="table-controls">
			<a href="index.php?view=upload"><b class="ordre">new job</a></b> -
			<a href="index.php?view=jobs&restart_all_paused=1"><b class="ordre">restart all paused jobs</b></a> -
			<a href="index.php?view=jobs&x=$random_x"><b class="ordre">reload</a></b> -
			<a href="index.php?view=jobs&restart_all=1"><b class="ordre">restart all</b></a>
		</div>
		<?php
	}
?>
