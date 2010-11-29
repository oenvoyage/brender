<?php
#check_if_client_should_work();
show_last_log();
show_client_list();
show_job_list();

function show_last_log() {
	#print "<h2>// last logs</h2>";
	print "<br/>";
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
	#print "<h2>// clients</h2>";
	//include "clients.php";
	
	if (isset($_GET['orderby'])) {
		if ($_SESSION[orderby_client]==$_GET[orderby]) {
			$_SESSION[orderby_client]=$_GET['orderby']." desc";
		}
		else {
			$_SESSION[orderby_client]=$_GET['orderby'];
		}
	}
	if (isset($_GET['benchmark'])) {
        	print "benchmark ALL idle";
                $query="select * from clients where status='idle'";
                $results=mysql_query($query);
                while ($row=mysql_fetch_object($results)){
                        $client=$row->client;
                	send_order("$client","benchmark","","75");
                        print "benchmark $client<br/>";
                }
	}
	if (isset($_GET['disable'])) {
		$disable=$_GET['disable'];
		if ($disable=="all") {
                        print "disable ALL";
                        $query="select * from clients where status='idle' or status='rendering'";
                        $results=mysql_query($query);
                        while ($row=mysql_fetch_object($results)){
                                $client=$row->client;
                                send_order("$client","disable","","5");
                                print "disable $client<br/>";
                        }
        }
        else {
			send_order($disable,"disable","","5");
            print "disable client : $disable";
		}
		$msg= "disabled $disable <a href=\"clients.php\">reload</a><br/>";
		sleep(1);
		$refresh="0;URL=index.php?view=clients&msg=disabled $disable";
	}
	if (isset($_GET['enable'])) {
		$enable=$_GET['enable'];
		if ($enable=="all") {
			print "enable ALL";
			$query="select * from clients where status='disabled'";
        		$results=mysql_query($query);
			while ($row=mysql_fetch_object($results)){
				$client=$row->client;
				send_order($client,"enable","","5");
				$msg= "enable $client<br/>";
			}
		}
		else if ($enable=="force_all"){
			print "force enable ALL<br/>";
			$query="select * from clients";
        		$results=mysql_query($query);
			while ($row=mysql_fetch_object($results)){
				$client=$row->client;
				send_order($client,"enable","","5");
				$msg.= "enabled $client<br/>";
			}
		}
		else {
			send_order($enable,"enable","","5");
			#header( 'Location: index.php' );
		}
		sleep(2);
		$refresh="0;URL=index.php?view=clients&msg=enabled $enable";
		$msg= "enabled $enable <a href=\"clients.php\">reload</a><br/>";
	}
	if (isset($_GET['refresh'])) {	
		checking_alive_clients();
	}
	if (isset($_GET['delete'])) {
		$client=$_GET['delete'];
		if (!check_client_exists($client)) {
			$msg="error : client $client not found";
		}
		else {
                	$dquery="delete from clients where client='$client'";
			mysql_query($dquery);
                	$msg="client $client deleted :: ok ";
			# print "query =$dquery";
		}
        }
	if (isset($_GET['stop'])) {
		$stop=$_GET['stop'];
		$msg= "stopped $stop <a href=\"clients.php\">reload</a><br/>";
		send_order($stop,"stop","","1");
		sleep(2);
		$refresh="0;URL=index.php?view=clients&msg=stopped $stop";
	}
	if ($_POST['action'] == "add client" || isset($_POST[new_client_name])) {
		if (check_client_exists($_POST[client])) {
			$msg="error client already exists";
		}
		else {
			$add_query="insert into clients values('','$_POST[new_client_name]','$_POST[speed]','$_POST[machinetype]','$_POST[machine_os]','$_POST[client_priority]','$_POST[working_hour_start]','$_POST[working_hour_end]','not running','')";
			mysql_query($add_query);
			$msg="created new client $_POST[client] $add_query";
		}
	}

if (isset($msg)) {
	print "$msg<br/> <a href=\"index.php?view=clients\">reload</a><br/>";
}

#-----------------read------------------
#------------ CLIENTS LIST -------------
#---------------------------------------
	$query="select * from clients where status<>'not running' order by $_SESSION[orderby_client]";
	$results=mysql_query($query);
	print "<h2>// <b>clients</b></h2>";
	print "<table border=0>";
	print "<tr>
		<td width=120 height=30><b><a href=\"index.php?view=clients&orderby=client\">client name</a></b></td>
		<td bgcolor=ccccce width=120><b> &nbsp; <a href=\"index.php?view=clients&orderby=status\">status</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=500><b> &nbsp; <a href=\"index.php?view=clients&orderby=info\">info</a> &nbsp; </b></td>
		<td width=120><b> &nbsp; &nbsp; </td>

	</tr>";
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$status=$row->status;
		$info=$row->info;
		$speed=$row->speed;
		$machinetype=$row->machinetype;
		$client_priority=$row->client_priority;
		$working_hour_start=$row->working_hour_start;
		$working_hour_end=$row->working_hour_end;
		$speed=$row->speed;
		if ($status<>"disabled") {
			$dis="<a href=\"index.php?view=clients&disable=$client\">disable</a>";
			$bgcolor="#bcffa6";
			$icon="play";
		}
		if ($status=="disabled") {
			$dis="<a href=\"index.php?view=clients&enable=$client\">enable</a>";
			$bgcolor="#ffaa99";
		}
		if ($status=="rendering") {
			$bgcolor="#99ccff";
		}
		if ($status=="not running") {
			$dis="";
			$bgcolor="#ffcc99";
		}
		print "<tr>
			<td bgcolor=ddddcc><a href=\"index.php?view=view_client&client=$client\"><font size=3>$client</font></a> <font size=1>($machinetype)</font></td> 
			<td bgcolor=$bgcolor>$status</td>
			<td bgcolor=$bgcolor>$info</td>
			<td bgcolor=$bgcolor>$dis</td>

		</tr>";
	}
	print "</table>";

	
	
}
function show_job_list() {
	#print "<h2>// jobs</h2>";
	#include "jobs.php";
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
		$queryqq="update jobs set current=start,status='waiting' where (project in (select name from projects where status='active') and status='pause');";
		mysql_query($queryqq);
	}
	if (isset($_GET['restart_all'])) {
		$queryqq="update jobs set current=start,status='waiting' where (project in (select name from projects where status='active'));";
		mysql_query($queryqq);
	}
	if (isset($_POST['updateid'])) {
		$jobid=$_POST['updateid'];
		if ($_POST['copy']=="copy job") {
			#----update COPY so we create a new job-------
			$query="insert into jobs values('','$_POST[scene]','$_POST[shot]','$_POST[start]','$_POST[end]','$_POST[project]','$_POST[start]','$_POST[chunks]','$_POST[filetype]','$_POST[rem]','$_POST[config]','active','$_POST[progress_status]','$_POST[progress_remark]','$_POST[priority]',now(),'$_SESSION[user]')";
	                mysql_query($query);
			print "COPYPROCESS = $_POST[copy] and query = $query";
		}
		else {
			#----update UPDATE so we just update the job-------
			$queryqq="update jobs set start='$_POST[start]',end='$_POST[end]',filetype='$_POST[filetype]',config='$_POST[config]',chunks='$_POST[chunks]',priority='$_POST[priority]',progress_status='$_POST[progress_status]',progress_remark='$_POST[progress_remark]', lastseen=NOW(),last_edited_by='$_SESSION[user]'  where id=$jobid;";
			if (isset($_POST['directstart'])){
				print "direct start...";
				$queryqq="update jobs set start='$_POST[start]',current='$_POST[start]', end='$_POST[end]',filetype='$_POST[filetype]',config='$_POST[config]',chunks='$_POST[chunks]',priority='$_POST[priority]',status='waiting', lastseen=NOW() where id=$jobid;";
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
		mysql_query($queryqq);
		# sleep(1);
	}
	
	# ---------------------------------------------
	#-------- Display Table with all jobs ---------
	# ---------------------------------------------
		$job_query="select * from jobs where (project in (select name from projects where status='active')) order by $_SESSION[orderby_jobs]";
		$results=mysql_query($job_query);
		print "<h2>// <b>jobs</b> $msg</h2>";
		debug("$job_query<br/>");
		print "<table>\n";
		print "<tr>
			<td width=12></td>
			<td width=120><a href=\"index.php?view=jobs&order_by=scene\">scene name</a></td>
			<td width=120> &nbsp; <a href=\"index.php?view=jobs&order_by=progress_status\">progress status</a></td>
			<td width=120> &nbsp; <a href=\"index.php?view=jobs&order_by=config\">output</a></td>
			<td width=10> &nbsp; <a href=\"index.php?view=jobs&order_by=start\">start</a>-<a href=\"index.php?view=jobs&order_by=end\">end</a> &nbsp; </td>
			<td width=10> &nbsp; <a href=\"index.php?view=jobs&order_by=chunks\">chunks</a> &nbsp; </td>
			<td width=50> &nbsp; <a href=\"index.php?view=jobs&order_by=current\">current</a> </td>
			<td width=60> &nbsp; rendered</td>
			<td width=12> &nbsp; <a href=\"index.php?view=jobs&order_by=status\">status</a> &nbsp; </td>
			<td width=60> &nbsp; </td>
			<td width=10> &nbsp; <a href=\"index.php?view=jobs&order_by=priority\">priority</a></td>
			<td width=10> &nbsp; </td>
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
			if ($priority == 99 ) {
				$bgcolor="#ffffff";
			}
			else if (preg_match("/^finished/",$status)) {
				$icon="reload";
				$a+=1;
				if ($a==2) {
					$bgcolor="#ddeedd";
					$a=0;
				}
				else {
					$bgcolor="#dffddd";
				}
			}
			if ($status=="rendering") {
				$bgcolor="#99ccff";
				$icon="pause";
			}
			if ($status=="pause") {
				$bgcolor="#ffff99";
				$icon="play";
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
				$bgcolorpriority="#ddcccc";
			}
	
			if ($_GET[no_visual]) {
				$thumbnail="";
			}
			else {
				$ext=filetype_to_ext($filetype);
				$thumbnail_image="../thumbnails/$scene/$shot/$shot$start_padded.$ext";
				if (!file_exists($thumbnail_image)) {
					#print "FILE DOESNT EXIST $thumbnail_image<br/>";
					create_thumbnail($id,$start);
				}
				$thumbnail="<a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\"><img src=\"$thumbnail_image\" width=\"50\"></a>";
			}
	
			print "<tr>
				<td bgcolor=ddddcc><a href=\"index.php?view=view_job&id=$id&x=$x&visual=1\">$thumbnail</a></td> 
				<td bgcolor=ddddcc><a href=\"index.php?view=view_job&id=$id&x=$x\"><b>$scene</b> <br /><font size=1>($project)</font></a></td> 
				<td bgcolor=$bgcolor>
					<span class=\"progress-bar\">".output_progress_bar($start,$end,$current)."</span><br/>
					$progress_status <small>$progress_remark</small>
				</td>
				<td bgcolor=$bgcolor>$config $filetype</td>
				<td bgcolor=$bgcolor>$start - $end</td>
				<td bgcolor=$bgcolor>$chunks</td>
				<td bgcolor=$bgcolor><b>$current</b></td>
				<td bgcolor=$bgcolor>$total_rendered/$total_frames<br></td>
				<td bgcolor=$bgcolor>$status</td>
				<td bgcolor=$bgcolor>
					<a href=\"index.php?view=jobs&reset=$id&start=$id\"><img src=\"images/icons/restart.png\" /></a>
					<a href=\"index.php?view=jobs&pause=$id\"><img src=\"images/icons/$icon.png\" /></a>
					<a href=\"index.php?view=jobs&finish=$id\"><img src=\"images/icons/stop.png\" /></a>
				</td>
				<td bgcolor=$bgcolorpriority><a href=\"#\" onclick=\"javascript:window.open('jobs_priority_popup.php?id=$id&priority=$priority','winame','width=200,height=25')\">$priority</a></td>
				<td bgcolor=$bgcolor>
					<a href=\"index.php?view=jobs&del=$id\"><img src=\"images/icons/close.png\" /></a>
					</td>
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
