#!/usr/bin/php -q
<?php 
require "functions.php";
require_once "connect.php";
output("---- brender client 0.1 ----");
if (!$argv[1]) {
	die("ERROR: no .conf file\n");
}
if ($argv[1]=="node") {
	require "conf/node.conf";
	#$r=rand(1,100000);
	$r=$argv[2];
	#$r=md5($r);
	$computer_name="node_$r";
	debug("debug test node computer name=$computer_name");
	require_once "connect.php";
	new_node($computer_name);
}
else {
	require "conf/$argv[1].conf";
}

if (!$argv[1]) {
	die("no computer_name, please use \n ./brender_client.php node <COMPUTER_NAME>\n");
}
global $computer_name;
set_status("$computer_name","idle",'');
output("computer name=$computer_name");
output("blenderpath=$blender_path");
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
		# --- we are checking if there is an order for this client name, or for any client.
		if ($client==$computer_name || $client=="any") {
			debug("wow its me...time to work!");
			if ($orders=="render") {
				# ---- RENDER ---
				change_order_owner($id,$computer_name);
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
			else if ($orders=="enable") { 
				# ---enabling the computer----
				set_status("$computer_name","idle","");
				output("ENABLE");
				remove_order($id);
			}
			else if ($orders=="disable") { 
				# ---disabling the computer----
				set_status("$computer_name","disabled","");
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
		$a=0;
		print ".";
	}

#---------------- end of main loop------------------
}


?>
