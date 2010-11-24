#!/usr/bin/php -q
<?php 
require "functions.php";
require_once "connect.php";
print "---- brender client 0.2 ----\n";
if (!$argv[1]) {
	die("ERROR :: no computer_name, please use \n ./brender_client.php node <COMPUTER_NAME>\n");
}
else {
	if (check_client_exists($argv[1])) {
		$computer_name=$argv[1];
	}
	else {
		die("ERROR :: computer not found in client list, please check name again or add it to the list\n");
	}
}

set_status("$computer_name","idle",'');
$os=get_client_os($computer_name);
output("computer name=$computer_name os=$os");
output("process id=".getmypid()."\n");
brender_log("START $computer_name");

while ($q=1) {
	#-----------------main loop---------
	$query="select * from orders order by id desc";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$client=$row->client;
		$orders=$row->orders; # please notice i am using orderS instead of order as to not interfere with ORDER syntax from mysql
		$rem=$row->rem;
		#print "----------- rem Â£=$rem \n";
		# --- we are checking if there is an order for this client name, or for any client.
		if ($client==$computer_name || $client=="any") {
			debug("wow its me...time to work! $orders ---");
			if ($orders=="render") {
				# ---- RENDER ---
				debug("RENDER TIME");
				change_order_owner($id,$computer_name);
				$blender_path=get_blender_path();
				$render_query="$blender_path $rem";
				output("RENDER $rem");
				set_status("$computer_name","rendering","$rem");
				output("render_query=$render_query\n");
				#--- we are now rendering the scene/chunk ...
				system($render_query);
				#--- once rendering is finished we erase the order
				remove_order($id);
				# and finally set client to idle, ready to get some new work
				set_status("$computer_name","idle","");
			}
			else if ($orders=="benchmark") { 
				debug("BENCHMARK RENDER TIME");
				change_order_owner($id,$computer_name);
				$blender_path=get_blender_path();
				$start_time= microtime(true); 
				$render_query="$blender_path -b 'blend/benchmark.blend' -o 'render/benchmark/$computer_name' -F PNG  -f 1";
				debug("BENCHMARK START $start_time");
				set_status("$computer_name","rendering benchmark","$rem");
				#--- we are now rendering the scene benchmark ...
				print "------------------- benchmark renderquery = $render_query\n";
				system($render_query);
				$end_time=microtime(true);
				$benchmark_time= $end_time-$start_time; 
				# ---enabling the computer----
				set_status("$computer_name","idle","BENCHMARK RESULT TIME= $benchmark_time");
				output("BENCHMARK result $end_time");
				remove_order($id);
			}
			else if ($orders=="enable") { 
				# ---enabling the computer----
				set_status("$computer_name","idle","$rem");
				output("ENABLE");
				remove_order($id);
			}
			else if ($orders=="disable") { 
				# ---disabling the computer----
				#print "enabling debug rem $rem\n";
				set_status("$computer_name","disabled","$rem");
				output("DISABLE");
				sleep(1);
				remove_order($id);
			}
			else if ($orders=="declare_finished") { 
				#--- a job is nearly finished (means the last chunk has been allocated) , so we declare it finished, to avoid other clients to render it
				output("DECLARE FINISHED job $rem");
				$heure=date('Y/d/m H:i:s');
				$query="update jobs set status='finished at $heure' where id='$rem'";
				mysql_unbuffered_query($query);
				remove_order($id);
			}
			else if ($orders=="ping") { 
				# brender_log("PING $rem");
				remove_order($id);
			}
			else if ($orders=="stop") { 
				# --- stop and client exits
				set_status("$computer_name","not running","");
				output("STOP");
				remove_order($id);
				die("\n stop $id\n");
			}
		}
	}
	#  --- sleep(1) can be enabled to have the mysql not running too many queries per minute
	# i do not know how to make it sleep less than one second...
	sleep(1);

	if($a++==10000){
		#check_if_i_can_work();
		$a=0;
		print ".";
	}

#---------------- end of main loop------------------
}


?>
