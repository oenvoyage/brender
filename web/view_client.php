<?php	
	if (!isset($_GET['client']) && !isset($_POST['client'] )) {
		print "error :: please select a client<br/>";
		print "<a href=\"index.php?view=clients\">back to clients list</a><br/>";
		die();
	}
	else {
		if (isset($_GET['client'])) {
			$client=$_GET['client'];
		}
		else {
			
			$client=$_POST['client'];
		}
		if (!check_client_exists($client)) {
			print "error :: client <b>$client</b> not found<br/>";
			print "<a href=\"index.php?view=clients\">back to clients list</a><br/>";
			die();
		}
	}

	if (isset($_GET['delete'])) {
		$dquery="delete from clients where client='$client'";
		$msg="client $client deleted :: ok $dquery";
	}
	if ($_POST['action']=="update") {
		$uquery="update clients set speed='$_POST[speed]',machinetype='$_POST[machinetype]',client_priority='$_POST[client_priority]',working_hour_start='$_POST[working_hour_start]',working_hour_end='$_POST[working_hour_end]' where client='$client'";
		mysql_query($uquery);
		$msg="$client updated :: ok <br/>";
		$msg.="<a href=\"index.php?view=clients\">back to clients list</a>";
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
	if (isset($_GET['stop'])) {
		$stop=$_GET['stop'];
		$msg= "stopped $stop <a href=\"clients.php\">reload clients list</a><br/>";
		send_order($stop,"stop","","1");
		sleep(2);
		$refresh="0;URL=index.php?view=clients&msg=stopped $stop";
		}

#--------read---------
	$query="select * from clients where client='$client'";
	$results=mysql_query($query);
	if (isset($msg)) {
		print "$msg<br/>";
	}
	print "<h2>//view client <b>$client</b></h2>";
	#print "$query<br/>";
		$row=mysql_fetch_object($results);
		$client=$row->client;
		$status=$row->status;
		$rem=$row->rem;
		$speed=$row->speed;
		$machinetype=$row->machinetype;
		$machine_os=$row->machine_os;
		$client_priority=$row->client_priority;
		$working_hour_start=$row->working_hour_start;
		$working_hour_end=$row->working_hour_end;
		$speed=$row->speed;
		if ($status<>"disabled") {
			$dis="<a href=\"index.php?view=clients&disable=$client\">disable</a>";
			$bgcolor="#bcffa6";
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
		if ($machinetype=='rendernode') {
			$rendernode_selected="selected";
		}
		?>
	<form action="index.php" method="post">
		<?php print $dis?><br/>
		<input type="hidden" name="view" value="view_client">
		<input type="hidden" name="client" value="<?php print $client?>">
		<input type="hidden" name="action" value="update">

		<h3>machine description</h3>
		operating system (<?php echo $machine_os ?>)<br/>
		machine type <select name="machinetype">
			<option>workstation</option>
			<option <?print $rendernode_selected?>>rendernode</option>
		</select><br/>
		speed (number of processors) <input type="text" name="speed" size="2" value="<?php print $speed?>"><br>
		<h3>working hours / priority</h3>
		 Start: <input type="text" name="working_hour_start" size="10" value="<?php print $working_hour_start?>"><br/>
		 End: <input type="text" name="working_hour_end" size="10" value="<?php print $working_hour_end?>"><br>
		 priority (1-100) <input type="text" name="client_priority" size="3" value="<?php print $client_priority?>"><br>

		<input type="submit" value="update <?php print $client?>"><br/>&nbsp;<br/>
	</form>
	<a href="index.php?view=clients&delete=<?php print $client?>">delete <?php print $client ?></a>
