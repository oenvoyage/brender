<?php	
	if (isset($_GET['orderby'])) {
		if ($_SESSION[orderby_client]==$_GET[orderby]) {
			$_SESSION[orderby_client]=$_GET['orderby']." desc";
		}
		else {
			$_SESSION[orderby_client]=$_GET['orderby'];
		}
	}
	if (isset($_GET['benchmark'])) {
		$benchmark=$_GET['benchmark'];
		if ($benchmark=="all") {
			print "benchmark ALL idle<br/>";
			$query="select * from clients where status='idle'";
			$results=mysql_query($query);
			while ($row=mysql_fetch_object($results)){
				$client=$row->client;
				send_order("$client","benchmark","","99");
				print "benchmark $client<br/>";
			}
		}
		else {
			print "benchmark $benchmark<br/>";
			send_order($benchmark,"benchmark","","99");
		}
		sleep(1); #...we sleep 1 sec for letting time to client to start benchmarking
	}
	if (isset($_GET['disable'])) {
		$disable=$_GET['disable'];
		if ($disable=="all") {
                        print "disable ALL<br/>";
                        $query="select * from clients where status='idle' or status='rendering'";
                        $results=mysql_query($query);
                        while ($row=mysql_fetch_object($results)){
                                $client=$row->client;
                                send_order("$client","disable","","5");
                                $msg.= "disabled $client<br/>";
                        }
        	}
        	else {
			send_order($disable,"disable","","5");
        		print "disable client : $disable<br/>";
		}
		$msg= "disabled $disable <a href=\"index.php?view=clients\">reload</a><br/>";
		sleep(1);
	}
	if (isset($_GET['enable'])) {
		$enable=$_GET['enable'];
		if ($enable=="all") {
			print "enable ALL<br/>";
			$query="select * from clients where status='disabled'";
        		$results=mysql_query($query);
			while ($row=mysql_fetch_object($results)){
				$client=$row->client;
				send_order($client,"enable","","5");
				$msg.= "enabled $client<br/>";
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
		$msg= "enabled $enable <a href=\"index.php?view=clients\">reload</a><br/>";
	}
	if (isset($_GET['refresh'])) {	
		checking_alive_clients();
		check_if_client_should_work();	
	}
	if (isset($_GET['delete'])) {
		$client=$_GET['delete'];
		if (!check_client_exists($client)) {
			$msg="error : client $client not found";
		}
		else {
			delete_node($client);
                	$msg="client $client deleted :: ok ";
			# print "query =$dquery";
		}
        }
	if (isset($_GET['stop'])) {
		$stop=$_GET['stop'];
		$msg= "stopped $stop <a href=\"index.php?view=clients\">reload</a><br/>";
		send_order($stop,"stop","","1");
		sleep(2);
	}
	if ($_POST['action'] == "add client") {
		$new_client_name=clean_name($_POST[new_client_name]);
		if (check_client_exists($new_client_name)) {
			$msg="<span class=\"error\">error client already exists</span>";
		}
		else if ($new_client_name == "" ) {
			$msg="<span class=\"error\">error, please enter a client name</span>";
		}
		else {
			$add_query="insert into clients values('','$new_client_name','$_POST[speed]','$_POST[machinetype]','$_POST[machine_os]','$_POST[client_priority]','$_POST[working_hour_start]','$_POST[working_hour_end]','not running','','')";
			mysql_query($add_query);
			$msg="created new client $_POST[client] $add_query";
		}
	}

if (isset($msg)) {
	print "$msg<br/> <a href=\"index.php?view=clients\">reload</a><br/>";
}

#--------read---------
	$query="select * from clients order by $_SESSION[orderby_client]";
	$results=mysql_query($query);
	print "<h2>// <b>clients</b></h2>";
	debug("$query<br/>");
	print "<table border=0>";
	print "<tr>
		<td bgcolor=cccccc width=120 height=30 align=center><b><a href=\"index.php?view=clients&orderby=client\">client name</a></b></td>
		<td bgcolor=cccccc width=32 height=30 align=center><b><a href=\"index.php?view=clients&orderby=client_priority\">stats</a></b></td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=status\">status</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=500 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=rem\">rem</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=200 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=info\">info</a> &nbsp; </b></td>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; cmd &nbsp; </td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=working_hour_start\">workhour start</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=working_hour_end\">workhour end</a> &nbsp; </b></td>
		<td bgcolor=cccccc align=center></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$status=$row->status;
		$rem=$row->rem;
		$info=$row->info;
		$speed=$row->speed;
		$machinetype=$row->machinetype;
		$machine_os=$row->machine_os;
		$client_priority=$row->client_priority;
		$working_hour_start=$row->working_hour_start;
		$working_hour_end=$row->working_hour_end;
		$speed=$row->speed;
		$status_class=get_css_class($status);
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
			$shutdown_button="";
		}
		else {
			$shutdown_button="<a href=\"index.php?view=clients&stop=$client\">x</a>";
		}
		print "<tr>
			<td bgcolor=ddddcc align=center><a href=\"index.php?view=view_client&client=$client\"><font size=3>$client</font></a> <font size=1>($machinetype)</font></td> 
			<td bgcolor=$bgcolor align=center>$machine_os<br/><font size=1>$speed / $client_priority</font></a></td>
			<td bgcolor=$bgcolor align=center>$status</td>
			<td bgcolor=$bgcolor align=center>$rem</td>
			<td bgcolor=$bgcolor align=center>$info</td>
			<td bgcolor=$bgcolor align=center>$dis</td>
			<td bgcolor=$bgcolor align=center>$working_hour_start</td>
			<td bgcolor=$bgcolor align=center>$working_hour_end</td>
			<td bgcolor=$bgcolor align=center>$shutdown_button</td>
		</tr>";
	}
	print "</table>";
?>
<a href="index.php?view=clients&benchmark=all"><b class="ordre">benchmark ALL</b></a> - 
<a href="index.php?view=clients&enable=all"><b class="ordre">enable ALL</b></a> - 
<a href="index.php?view=clients&disable=all"><b class="ordre">disable ALL</b></a> - 
<a href="index.php?view=clients&refresh=1"><b class="ordre">refresh</b></a> - 
<a href="index.php?view=clients&enable=force_all"><b class="ordre">force_all_enable</b></a>
<p><hr><p>
<p><p>


<?php show_new_client_form(); ?>

<?php function show_new_client_form() { ?>
	<div class="dialog">
		<h2>// <b>add new client</b></h2>
		<form action="index.php" method="post">
			<input type="hidden" name="view" value="clients">
			name <input type="text" name="new_client_name" size="20"> (must be unique)<br>
			<h3>machine description</h3>
			operating system <select name="machine_os">
				<option>linux</option>
				<option>mac</option>
				<option>windows</option>
			</select><br/>
			machine type <select name="machinetype">
				<option>rendernode</option>
				<option>workstation</option>
			</select><br/>
			speed (number of processors (multiplicator for number of chunks)) <input type="text" name="speed" size="2" value="2"><br>
			<h3>working hours / priority</h3>
			working hours are hours during which the workstation will be disabled<br/>
			 Start: <input type="text" name="working_hour_start" size="10" value="07:00:00"><br/>
			 End: <input type="text" name="working_hour_end" size="10" value="19:00:00"><br>
			 client priority (1-100) (will only render jobs with priority higher than this value)<input type="text" name="client_priority" size="3" value="1"><br>
	
			<input type="submit" name="action" value="add client"><br/>
		</form>
	</div>
<?php } ?>


