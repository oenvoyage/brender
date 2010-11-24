<?php	
	if (isset($_GET['orderby'])) {
		$_SESSION[orderby_client]=$_GET['orderby'];
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

#--------read---------
	$query="select * from clients order by $_SESSION[orderby_client]";
	$results=mysql_query($query);
	print "<h2>// <b>clients</b></h2>";
	print "$query<br/>";
	print "<table border=0>";
	print "<tr>
		<td bgcolor=cccccc width=120 height=30 align=center><b><a href=\"index.php?view=clients&orderby=client\">client name</a></b></td>
		<td bgcolor=cccccc width=12 height=30 align=center><b><a href=\"index.php?view=clients&orderby=client_priority\">rp</a></b></td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=status\">status</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=500 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=rem\">rem</a> &nbsp; </b></td>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; &nbsp; </td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=working_hour_start\">workhour start</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=working_hour_end\">workhour end</a> &nbsp; </b></td>
		<td bgcolor=cccccc align=center></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$status=$row->status;
		$rem=$row->rem;
		$speed=$row->speed;
		$machinetype=$row->machinetype;
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
		print "<tr>
			<td bgcolor=ddddcc align=center><a href=\"index.php?view=view_client&client=$client\"><font size=3>$client</font></a> <font size=1>($machinetype)</font></td> 
			<td bgcolor=$bgcolor align=center><font size=1>$speed /</font> <a href=\"#\" onclick=\"javascript:window.open('clients_priority_popup.php?client=$client&client_priority=$client_priority','winam','width=200,height=25')\"><font size=1>$client_priority</font></a></td>
			<td bgcolor=$bgcolor align=center>$status</td>
			<td bgcolor=$bgcolor align=center>$rem</td>
			<td bgcolor=$bgcolor align=center>$dis</td>
			<td bgcolor=$bgcolor align=center>$working_hour_start</td>
			<td bgcolor=$bgcolor align=center>$working_hour_end</td>
			<td bgcolor=$bgcolor align=center><a href=\"index.php?view=clients&stop=$client\">x</a></td>
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

<h2>// <b>add new client</b></h2>
<?php show_new_client_form(); ?>

<?php function show_new_client_form() { ?>
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
		speed (number of processors) <input type="text" name="speed" size="2" value="2"><br>
		<h3>working hours / priority</h3>
		working hours are hours during which the workstation will be disabled<br/>
		 Start: <input type="text" name="working_hour_start" size="10" value="07:00:00"><br/>
		 End: <input type="text" name="working_hour_end" size="10" value="19:00:00"><br>
		 client priority (1-100) <input type="text" name="client_priority" size="3" value="1"><br>

		<input type="submit" name="action" value="add client"><br/>
	</form>
<?php } ?>


