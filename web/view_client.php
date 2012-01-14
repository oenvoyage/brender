<?php	
	if (!isset($_GET['client']) && !isset($_POST['client'] )) {
		print "<span class=\"error\">error :: please select a client</span><br/>";
		print "<a href=\"index.php?view=clients\">back to clients list</a><br/>";
		die();
	}
	else {
		if (isset($_GET['client'])) {
			$client = $_GET['client'];
		}
		else {
			
			$client = $_POST['client'];
		}
		if (!check_client_exists($client)) {
			print "<span class=\"error\">error :: client <b>$client</b> not found</span><br/>";
			print "<a href=\"index.php?view=clients\">back to clients list</a><br/>";
			die();
		}
	}

	
	if (isset($_POST['execute_command'])) {
		if ($_POST['command']) {
			$cmd = $_POST['command'];
			output("executing command $cmd on client $client");
			send_order("$client","execute_command","$cmd","99");
		}
		else {
			print "<span class=\"error\">please enter a <b>command</b> to execute</span>";
		}
	}
	if (isset($_GET['reset'])) {
		$query = "UPDATE clients set status='not running' WHERE client='$client'";
		mysql_query($query);
		$msg = "client $client reset: $query";
	}
	if (isset($_POST['action'])) {
	   	if ($_POST['action'] == "update") {
			$uquery = "UPDATE clients SET speed='$_POST[speed]',machine_os='$_POST[machine_os]',machine_type='$_POST[machine_type]',blender_local_path='$_POST[blender_local_path]',client_priority='$_POST[client_priority]',working_hour_start='$_POST[working_hour_start]',working_hour_end='$_POST[working_hour_end]' where client='$client'";
			mysql_query($uquery);
			$msg = "$client updated :: ok <br/>";
			$msg.= "<a href=\"index.php?view=clients\">back to clients list</a>";
		}
	}
	if (isset($_GET['stop'])) {
		$stop = $_GET['stop'];
		$msg = "stopped $stop <a href=\"clients.php\">reload clients list</a><br/>";
		send_order($stop,"stop","","1");
		sleep(2);
		$refresh = "0;URL=index.php?view=clients&msg=stopped $stop";
		}

#--------read---------
	$query = "SELECT * FROM clients WHERE client='$client'";
	$results = mysql_query($query);
	if (isset($msg)) {
		print "$msg<br/>";
	}
	print "<h2>// view client <b>$client</b></h2>";
	#print "$query<br/>";
		$row = mysql_fetch_object($results);
		$client = $row->client;
		$status = $row->status;
		$rem = $row->rem;
		$speed = $row->speed;
		$machine_type = $row->machine_type;
		$machine_os = $row->machine_os;
		$blender_local_path = $row->blender_local_path;
		$client_priority = $row->client_priority;
		$working_hour_start = $row->working_hour_start;
		$working_hour_end = $row->working_hour_end;
		$speed = $row->speed;
		if ($status <> "disabled") {
			$disable_enable_button = "<a class=\"grey\" href=\"index.php?view=clients&disable=$client\">disable</a>";
			$bgcolor = "#bcffa6";
		}
		if ($status == "disabled") {
			$disable_enable_button = "<a class=\"grey\" href=\"index.php?view=clients&enable=$client\">enable</a>";
			$bgcolor = "#ffaa99";
		}
		if ($status == "rendering") {
			$bgcolor = "#99ccff";
		}
		if ($status == "not running") {
			$disable_enable_button = "";
			$benchmark_button = "";
			$reset_button = "";
			$bgcolor = "#ffcc99";
		}
		else {
			$reset_button = " <a class=\"grey\" href=\"index.php?view=view_client&reset=1&client=$client\">x</a>";
			$benchmark_button = " &nbsp;<a class=\"grey\" href=\"index.php?view=clients&benchmark=$client\">benchmark </a>";
		}
		if ($machine_type == 'rendernode') {
			$rendernode_selected = "selected";
		}
		else {
			$rendernode_selected = "";
		}

		$linux_selected = $windows_selected = "";
		if ($machine_os == 'linux') {
			$linux_selected = "selected";
		}
		else if ($machine_os == 'windows') {
			$windows_selected = "selected";
		}
		?>
	<!--  ******** form ********  -->
		<?php 
			print "<p>$disable_enable_button $reset_button $benchmark_button</p>";
		?><br/>
	<form action="index.php" method="post" id="client_form">
		<input type="hidden" name="view" value="view_client">
		<input type="hidden" name="client" value="<?php print $client?>">
		<input type="hidden" name="action" value="update">

		<h3>machine description</h3>
			<p>operating system <select name="machine_os">
				<option>mac</option>
				<option <?php print $linux_selected?>>linux</option>
				<option <?php print $windows_selected?>>windows</option>
			</select></p>
			<p>blender local path including blender executable <br/>(leave empty to use the /blender folder in brender_root : <br/>
				<input type="text" name="blender_local_path" size="80" value="<?php print $blender_local_path?>"></p>
			<p>speed (number of processors = number of chunks multiplier) <input type="text" name="speed" size="2" value="<?php print $speed?>"></p>
			<p>machine type <select name="machine_type" onchange="switch_working_hours(this.value)">
				<option>workstation</option>
				<option <?php print $rendernode_selected?>>rendernode</option>
			</select></p>
		<div id="working_hours" class="<?php ($machine_type=="workstation") ? print "visibleDiv": print "hiddenDiv"?>">
				<h3>working hours / priority</h3>
				 <p>Start: <input type="text" name="working_hour_start" size="10" value="<?php print $working_hour_start?>"><br/>
				 End: <input type="text" name="working_hour_end" size="10" value="<?php print $working_hour_end?>"></p>
			</div>
			<p>priority (1-100) (will only render jobs with priority higher than this value)<input type="text" name="client_priority" size="3" value="<?php print $client_priority?>"></p>

		<input type="submit" value="update <?php print $client?>"><br/>&nbsp;<br/>
	</form><br/>

	<!--   ******** execute command form ********  -->
	<?php if ($status <> "not running" ) { ?>
	<form action="index.php" method="post">
		<input type="hidden" name="view" value="view_client">
		<input type="hidden" name="client" value="<?php print $client?>">
		<h3>custom command</h3>
		<p>enter a custom command to execute on this client : <input type="ext" name="command">
		<input type="submit" name="execute_command" value="execute command"></p>
	</form><br/>
	<?php } ?>

	<!--   ******** delete client ********  -->
	<a href="index.php?view=clients&delete=<?php print $client?>"><img src="images/icons/close.png"> delete client <?php print $client ?></a>

	<!--   ******** 5 last rendered frames ********  -->
	<h2>// 5 last rendered frames </h2>
	<?php show_last_rendered_frame_by_client($client); ?>
	

<?php
#------------------------------ functions -----------------
function show_last_rendered_frame_by_client($client) {
	print "<table><tr>";
        $query = "SELECT * FROM rendered_frames WHERE is_thumbnailed='1' AND rendered_by='$client'  ORDER BY finished_time DESC LIMIT 5";
	debug("RENDER FRAME LAST BY CLIENT $query");
        $results = mysql_query($query);
        while ($row = mysql_fetch_object($results)) {
        	$job_id = $row->job_id;
        	$rendered_by= $row->rendered_by;
		$frame = $row->frame;
        	$finished_time = $row->finished_time;
		print "<td>";
		print "<a href=\"index.php?view=view_image&job_id=$job_id&frame=$frame\">".get_thumbnail_image($job_id,$frame)."</a><br/>";
                print "frame <b>$frame</b> <a href=\"index.php?view=view_job&id=$job_id\">job $job_id</a><br/>";
                print "finished @ $finished_time<br/>";
		print "</td>";
	}
	print "</tr></table>";
}
