<?php
session_start();
require "header.php";

$msg="";
$queryqq="";
if (isset($_GET['order_by'])) {
	$order_by=$_GET['order_by'];
}
else {
	$order_by="lastseen desc, name, id desc";
}

if (isset($_GET['all_projects'])) {
	$job_query="select * from jobs order by jobtype desc ,$order_by";
}
else {
	$job_query="select * from jobs where (project in (select name from projects where status='active')) order by status, $order_by";
}
#----------------------------
if (isset($_GET['restart_all'])) {
	$queryqq="update jobs set current=start,status='waiting' where (project in (select name from projects where status='active'));";
	mysql_query($queryqq);
}
if (isset($_POST['updateid'])) {
	$jobid=$_POST['updateid'];
	if ($_POST['copy']=="copy job") {
		#----update COPY so we create a new job-------
		$query="insert into jobs values('','$_POST[nom]','$_POST[jobtype]','$_POST[file]','$_POST[start]','$_POST[end]','$_POST[project]','$_POST[output]','$_POST[start]','$_POST[chunks]','','$_POST[filetype]','$_POST[config]','active','$_POST[priority]',now())";
                mysql_query($query);
		print "COPYPROCESS = $_POST[copy] and quesry = $query";
	}
	else {
		#----update UPDATE so we jsut update the job-------
		$queryqq="update jobs set start='$_POST[start]',end='$_POST[end]',filetype='$_POST[filetype]',config='$_POST[config]',chunks='$_POST[chunks]',priority='$_POST[priority]', lastseen=NOW()  where id=$jobid;";
		if (isset($_POST['directstart'])){
			print "direct start...";
			$queryqq="update jobs set start='$_POST[start]',current='$_POST[start]', end='$_POST[end]',filetype='$_POST[filetype]',config='$_POST[config]',chunks='$_POST[chunks]',priority='$_POST[priority]',status='waiting', lastseen=NOW() where id=$jobid;";
		}	
		mysql_query($queryqq);
		# $msg= "job $jobid updated<br/>";
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
	mysql_query($queryqq);
	# sleep(1);
}

#--------read---------
	$results=mysql_query($job_query);
	print "<br/>// <b>jobs</b> $msg <br/> $queryqq\n";
	print "<table>\n";
	print "<tr>
		<td bgcolor=cccccc width=10 align=center><a href=\"jobs.php?order_by=jobtype\">type</a></td>
		<td bgcolor=cccccc width=12 align=center></td>
		<td bgcolor=cccccc width=12 align=center></td>
		<td bgcolor=cccccc width=120 align=center><a href=\"jobs.php?order_by=name\">job name</a></td>
		<td bgcolor=cccccc width=120 align=center> &nbsp; <a href=\"jobs.php?order_by=file\">file</a></td>
		<td bgcolor=cccccc width=120 align=center> &nbsp; <a href=\"jobs.php?order_by=output\">output</a></td>
		<td bgcolor=cccccc width=12 align=center> &nbsp; <a href=\"jobs.php?order_by=config\">config</a></td>
		<td bgcolor=cccccc width=10 align=center> &nbsp; <a href=\"jobs.php?order_by=start\">start</a>-<a href=\"jobs.php?order_by=end\">end</a> &nbsp; </td>
		<td bgcolor=cccccc width=6 align=center> &nbsp; <a href=\"jobs.php?order_by=chunks\">chunks</a> &nbsp; </td>
		<td bgcolor=cccccc width=50 align=center> &nbsp; <a href=\"jobs.php?order_by=current\">current</a> </td>
		<td bgcolor=cccccc width=60 align=center> &nbsp; rendered</td>
		<td bgcolor=cccccc width=12 align=center> &nbsp; <a href=\"jobs.php?order_by=status\">status</a> &nbsp; </td>
		<td bgcolor=cccccc width=10 align=center> &nbsp; </td>
		<td bgcolor=cccccc width=60 align=center> &nbsp; </td>
		<td bgcolor=cccccc width=10 align=center> &nbsp; <a href=\"jobs.php?order_by=priority\">priority</a></td>
		<td bgcolor=cccccc width=10 align=center> &nbsp; </td>
		<td bgcolor=cccccc width=10 align=center> lastseen </td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$padded_id=str_pad((int) $id,3,"0",STR_PAD_LEFT);
		$name=$row->name;
		$jobtype=$row->jobtype;
		$project=$row->project;
		$file=$row->file;
		$start=$row->start;
		$start_padded=str_pad((int) $start,4,"0",STR_PAD_LEFT);
		$end=$row->end;
		$output=$row->output;
		$current=$row->current;
		$chunks=$row->chunks;
		$rem=$row->rem;
		$filetype=$row->filetype;
		$config=$row->config;
		$status=$row->status;
		$priority=$row->priority;
		$lastseen=$row->lastseen;
		$total_frames=$end-$start+1;
		$total_rendered=get_rendered_frames($id);
		$bgcolor="#bcffa6";
		if ($priority == 99 ) {
			$bgcolor="#ffffff";
		}
		else if (preg_match("/^finished/",$status)) {
			$bgcolor="#ddeedd";
		}
		if ($status=="rendering") {
			$bgcolor="#99ccff";
		}
		if ($status=="pause") {
			$bgcolor="#ffff99";
		}
		if ($priority<10) {
			$bgcolorpriority="#ff1111";
		}
		else if ($priority<20) {
			$bgcolorpriority="#ffaaaa";
		}
		else if ($priority<60) {
			$bgcolorpriority="#ddddaa";
		}
		else {
			$bgcolorpriority="#ddddcc";
		}

		if ($_GET[no_visual]) {
			$thumbnail="";
		}
		else {
			$thumbnail="<a href=\"view_job.php?id=$id&x=$x&visual=1\"><img src=\"/Production/renders/".$output."$start_padded.$filetype\" width=\"50\"></a>";
		}

		print "<tr>
			<td bgcolor=$bgcolor align=center><font size=1>$jobtype</font></td>
			<td bgcolor=ddddcc align=center>$padded_id</td> 
			<td bgcolor=ddddcc align=center><a href=\"view_job.php?id=$id&x=$x&visual=1\">$thumbnail</a></td> 
			<td bgcolor=ddddcc align=center><a href=\"view_job.php?id=$id&x=$x\"><b>$name</b> <font size=1>($project)</font></a></td> 
			<td bgcolor=$bgcolor align=center>$file</td>
			<td bgcolor=$bgcolor align=center>$output.$filetype</td>
			<td bgcolor=$bgcolor align=center>$config</td>
			<td bgcolor=$bgcolor align=center>$start - $end</td>
			<td bgcolor=$bgcolor align=center>$chunks</td>
			<td bgcolor=$bgcolor align=center>$current</td>
			<td bgcolor=$bgcolor align=center>$total_rendered / $total_frames</td>
			<td bgcolor=$bgcolor align=center>$status</td>
			<td bgcolor=$bgcolor align=center><a href=\"overview.php?reset=$id\">reset</a><br/>
			<a href=\"overview.php?reset=$id&start=$id\">restart</a></td>
			<td bgcolor=$bgcolor align=center><a href=\"overview.php?pause=$id\">pause</a><br/>
			<a href=\"overview.php?start=$id\">start</a><br/><a href=\"overview.php?finish=$id\">finish</a></td>
			<td bgcolor=$bgcolorpriority align=center><a href=\"#\" onclick=\"javascript:window.open('jobs_priority_popup.php?id=$id&priority=$priority','winame','width=200,height=25')\">$priority</a></td>
			<td bgcolor=$bgcolor align=center><a href=\"jobs.php?del=$id\">x</a></td>
			<td bgcolor=$bgcolor align=center>$lastseen</a><br/>
		</tr>";
	}
	print "\n</table>\n";
	print "<a href=\"upload.php\"><b class=\"ordre\">new job</a></b> - ";
	print "<a href=\"jobs.php?x=$random_x\"><b class=\"ordre\">reload</a></b> - ";
	print "<a href=\"projects.php\"><b class=\"ordre\">projects</a></b> - ";
	print "<a href=\"jobs.php?restart_all=1\"><b class=\"ordre\">restart all</b></a>";
	print "<p><hr><p>";
	print "<p><p>";

?>
