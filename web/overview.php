// THIS IS OVERVIEW PAGE

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
		<td bgcolor=cccccc width=120 height=30 align=center><b><a href=\"index.php?view=clients&orderby=client\">client name</a></b></td>
		<td bgcolor=ccccce width=120 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=status\">status</a> &nbsp; </b></td>
		<td bgcolor=ccccce width=500 align=center><b> &nbsp; <a href=\"index.php?view=clients&orderby=info\">info</a> &nbsp; </b></td>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; &nbsp; </td>

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
			<td bgcolor=$bgcolor align=center>$status</td>
			<td bgcolor=$bgcolor align=center>$info</td>
			<td bgcolor=$bgcolor align=center>$dis</td>

		</tr>";
	}
	print "</table>";

	
	
}
function show_job_list() {
	#print "<h2>// jobs</h2>";
	include "jobs.php";
}
?>
