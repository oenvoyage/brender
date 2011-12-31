#!/usr/bin/php -q
<?php 
/**
* Copyright (C) 2007-2011 Olivier Amrein
* Author Olivier Amrein <olivier@brender-farm.org> 2007-2011
* Author Laurent Clouet <laclouet@gmail.com> 2011
* 
* ***** BEGIN GPL LICENSE BLOCK *****
*
* This file is part of Brender.
*
* Brender is free software: you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation, either version 2 
* of the License, or any later version.
* 
* Brender is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License 
* along with brender.  If not, see <http://www.gnu.org/licenses/>.
*
* ***** BEGIN GPL LICENSE BLOCK *****
*
*/
//Highly Experimental!
$ftpenabled = 0;
#Windows FTP
$winscp = "winscp.com"; #Set to path to winscp.com, use "winscp.com" when it is in the same folder or in PATH


require "functions.php";
require_once "connect.php";
print "---- brender client 0.2 ----\n";
if (isset($argv[1]) == false) {
	die("ERROR :: no computer_name, please use \n ./brender_client.php node <COMPUTER_NAME>\n");
}
else {
	if (check_client_exists($argv[1])) {
		$computer_name = $argv[1];
		#$GLOBALS['computer_name'] = $computer_name;
		if (isset($argv[2])) {
			if ($argv[2] == "force") {
				debug("force start");
			}
			else if (check_client_is_running($computer_name)) {
				output("tried to start brender client with client name : $computer_name.... but a client with that name seems to be already running\n");
		    	     die("could not start client");
			}
			if ($argv[2] == "debug") {
				# -- we enable debug mode ------
				$GLOBALS['debug_mode']=1;
				debug(" STARTED IN DEBUG MODE ");
			}
		}
	}
	else {
		die("ERROR :: computer not found in client list, please check name again or add it to the list\n");
	}
}

set_status("$computer_name","idle",'');
set_info($computer_name,'');
$os = get_client_os($computer_name);
$GLOBALS['os'] = $os;
output("computer name = $computer_name os = $os");
output("process id=".getmypid());
brender_log("START $computer_name");
check_if_client_should_work();
$a = 0;

while ($q = 1) {
	#-----------------main loop---------
	$query = "SELECT * FROM orders ORDER BY id DESC";
	$results = mysql_query($query);
	while ($row = mysql_fetch_object($results)){
		$id = $row->id;
		$client = $row->client;
		$orders = $row->orders; # please notice i am using orderS instead of order as to not interfere with ORDER syntax from mysql
		$rem = $row->rem;
		#print "----------- rem Â£=$rem \n";
		# --- we are checking if there is an order for this client name, or for any client.
		if ($client == $computer_name || $client == "any") {
			debug("wow its me...time to work! $orders ---");
			if ($orders == "render") {
				
				#Download/synch blend folder
				############################################# Highly Experimental
				if ($ftpenabled) {
					switch ($GLOBALS['os']) {
						case "windows":
							//SYNCH HERE!
							$ftpcommand= $winscp . " /script=winscpsynch.txt";
							system($ftpcommand);
							break;
					}
				}
				############################################# Highly Experimental
				
				# ---- RENDER ---
				debug("RENDER TIME");
				change_order_owner($id,$computer_name);
				$blender_path = get_blender_path();
				$parsed = parse_render_command($rem); # parsing  the rendering command to get an array used later for generating thumbnails
				$rendering_command = preg_replace("/(.*) -JOB (\d*)/","$1",$rem);  #parsing $rem to get the rendering command for blender (without the - JOB xx)
				debug("RENDER command order $rem");

				#--- we are now rendering the scene/chunk ...
				set_status("$computer_name","rendering","$rendering_command");
				$render_query = "$blender_path $rendering_command";
				if ($os == "windows") {
					$render_query = windowsify_paths($render_query);
				}
				output("-  I am rendering using this command = $render_query");
				system($render_query);
				
				# Upload completed frames to remote server
				############################################# Highly Experimental
				if ($ftpenabled) {
					switch ($GLOBALS['os']) {
						case "windows":
							$ftpcommand= $winscp . " /script=winscp.txt";
							system($ftpcommand);
							rrmdir("render/");
							break;
					}
				}
				############################################# Highly Experimental
				
				# --- now we send an order to server to generate the thumbnails
				add_rendered_frames($parsed['job_id'],$parsed['start'],$parsed['end']);
				$thumbnail_creation_order = "JOB=$parsed[job_id] START=$parsed[start] END=$parsed[end]";
				debug(" HHHHHHHHHHHHHHHHHHHHHH- $thumbnail_creation_order");
				// send_order("server","create_thumbnails",$thumbnail_creation_order,"20");
				
				# and finally set client to idle, ready to get some new work
				set_status("$computer_name","idle");
				set_info($computer_name,'');

				#--- once rendering is finished we erase the order
				remove_order($id);
			}
			else if ($orders == "benchmark") { 
				debug("BENCHMARK RENDER TIME");
				change_order_owner($id,$computer_name);
				#---- we get the actualy status for restoring after the end of benchmark
				$old_status=get_status($computer_name);
				set_info($computer_name,"waiting benchmark results");
				set_status("$computer_name","rendering benchmark","$rem");

				$blender_path = get_blender_path();
				$render_query = "$blender_path -b 'blend/benchmark.blend' -o 'render/benchmark/$computer_name' -F PNG  -f 110";
				print "------------------- benchmark renderquery = $render_query\n";
				#--- we are now rendering the scene benchmark ...
				$start_time = microtime(true); 
				debug("BENCHMARK START $start_time");
				if ($os == "windows") {
					$render_query=windowsify_paths($render_query);
				}
				system($render_query);
				$end_time = microtime(true);
				$benchmark_time = round($end_time-$start_time,2) ; 
				$benchmark_result = seconds_to_hms($benchmark_time);
				# ---enabling the computer or putting back to old status----
				set_status("$computer_name","$old_status","BENCHMARK RESULT TIME= $benchmark_result");
				set_info($computer_name,"BENCHMARK RESULT $benchmark_result");
				output("BENCHMARK result $benchmark_result");
				remove_order($id);
			}
			else if ($orders == "enable") { 
				# ---enabling the computer----
				set_status("$computer_name","idle","$rem");
				set_info($computer_name,'');
				output("ENABLE");
				remove_order($id);
			}
			else if ($orders == "disable") { 
				# ---disabling the computer----
				#print "enabling debug rem $rem\n";
				set_status("$computer_name","disabled","$rem");
				#set_info($computer_name,'');
				output("DISABLE");
				sleep(1);
				remove_order($id);
			}
			else if ($orders == "declare_finished") { 
				#--- a job is nearly finished (means the last chunk has been allocated) , so we declare it finished, to avoid other clients to render it
				output("DECLARE FINISHED job $rem");
				$heure = date('Y/d/m H:i:s');
				$query = "UPDATE jobs SET status='finished at $heure' WHERE id='$rem'";
				mysql_unbuffered_query($query);
				remove_order($id);
			}
			else if ($orders == "ping") { 
				# brender_log("PING $rem");
				remove_order($id);
			}
			else if ($orders == "stop") { 
				# --- stop and client exits
				set_status("$computer_name","not running","$rem");
				output("STOP");
				remove_order($id);
				die("\n stop $id\n");
			}
			else if ($orders == "execute_command") { 
				# --- we must execute a command-- 
				$cmd = $rem;
				output("executing command line $cmd");
				set_status("$computer_name","executing command","$rem");

				// executing the command
				$cmd_output = system($cmd);

				output("command line output =$cmd_output");
				set_status("$computer_name","idle","cmd output = $cmd_output");
				remove_order($id);
			}
			else {
				# --- no corresponding order found --- 
				output("order unknown : $orders" , "ERROR");
				remove_order($id);
			}
		}
	}
	#  --- sleep(1) can be enabled to have the mysql not running too many queries per minute
	# i do not know how to make it sleep less than one second...
	sleep(1);

	if($a++ == 10000){
		#check_if_i_can_work();
		$a = 0;
		print ".";
	}

#---------------- end of main loop------------------
}


?>
