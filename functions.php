<?php
/**
* Copyright (C) 2007-2011 Olivier Amrein
* Author Olivier Amrein <olivier@brender-farm.org> 2007-2011
*
* ***** BEGIN GPL LICENSE BLOCK *****
*
* This file is part of Brender.
*
* Brender is free software: you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation, either version 2 * of the License, or any later version.
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

date_default_timezone_set('Europe/Zurich'); # ----needed by php
function play_sound($sound) {
	exec("afplay sounds/$sound");
}
function test() {
	global $qwer;
	print "qwer=$qwer\n";
}
function output($msg,$type="info") {
	# function used in the command line scripts (brender_server.php and brender_client.php) for outputing and logging things
	# output can be customized in future for different types 
	# currently used type = info, warning, error
	if ($type <> "nolog") {
		brender_log($msg);
	}
	$when=date('Y/d/m H:i:s');
	$msg= "$when : $type : $msg";
	print "$msg\n";
}
function debug($msg) {
	if (isset($_SESSION['debug'])) {
		if ($_SESSION['debug']==1) { 
			# only display debug message on web interface when _SESSION debug is enabled
			print "****  DEBUG ***** :: $msg<br/>";
		}
	}
	if (isset($GLOBALS['debug_mode'])) { 
		# --- for command_line we display debug messages if debug_mode global is on
		print "**** DEBUG ***** :: $msg\n";
	}
}
function check_job_exists($job_id) {
	$query="SELECT COUNT(scene) FROM jobs WHERE id='$job_id'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function check_client_exists($client) {
	$query="SELECT COUNT(client) FROM clients WHERE client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function get_client_status($client) {
	$query="SELECT status FROM clients WHERE client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function get_client_os($client) {
	$query="SELECT machine_os FROM clients WHERE client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function get_priority_color($priority) {
	if (preg_match("/^(\d+)$/",$priority)) {
		if ($priority<10) {
			return "#ff1111";
		}
		else if ($priority<20) {
			return "#ffaaaa";
		}
		else if ($priority<60) {
			return "#ddddaa";
		}
		else if ($priority<95) {
			return "#ddcccc";
		}
		else {
			return "#ffffff";
		}
		/*  experimental code to color HEX according to priority value
		$hex=dechex($priority*2.5);
		$color="ff".$hex.$hex;
		#print("number $color <br/>");
		return $color;
		*/
	}
	else {
		print "error : not a number";
	}
}
function get_css_class($status) {
	# we do some regex preg_match to get the status class 
	if (preg_match("/rendering/",$status)) {
		return "rendering";
	}
	else if (preg_match("/idle/",$status)) {
		return "idle";
	}
	else if (preg_match("/disabled/",$status)) {
		return "disabled";
	}
	else if (preg_match("/enabled/",$status)) {
		return "enabled";
	}
	else if (preg_match("/not running/",$status)) {
		return "not_running";
	}
	else if (preg_match("/running/",$status)) {
		# for server status
		return "idle";
	}
	else if (preg_match("/died/",$status)) {
		# for server status
		return "not_running";
	}
	elsE if (preg_match("/pause/",$status)) {
		return "pause";
	}
	else if (preg_match("/finished/",$status)) {
		#print "GLOAB : ".$GLOBALs[last_finished];
		if (isset($GLOBALS['last_finished'])) {
			$GLOBALS['last_finished']=0;
			return "finished";
		}
		else {
			$GLOBALS['last_finished']=1;
			return "finished2";
		}
	}
	else if (preg_match("/waiting/",$status)) {
		return "waiting";
	}
	else if (preg_match("/inactive/",$status)) {
		return "inactive";
	}
	else if (preg_match("/active/",$status)) {
		return "active";
	}
	else {
		# in case we can not find a status type, return finished
		return "finished";
	}
	
}
function check_if_client_should_work($client_name="check all") {
	# function used for checking if a client is during his "working hours", these are set in the database for each client.
	if ($client_name <> "check all") {
		$query="SELECT (DATE_FORMAT( NOW( ) , '%T' ) BETWEEN  working_hour_start AND working_hour_end) as should_work FROM clients SELECT client='$client_name'";
		$results=mysql_query($query);
		$qq=mysql_result($results,0);
		return($qq);
		
	}
	#checking_alive_clients();
	$query="SELECT DATE_FORMAT( NOW( ) , '%T' ) AS now,working_hour_start AS start,working_hour_end AS end,client,status,machine_type,(DATE_FORMAT( NOW( ) , '%T' ) BETWEEN  working_hour_start AND working_hour_end) AS should_work FROM clients WHERE machine_type='workstation' AND status<>'not running'";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)) {
		$client=$row->client;
		$status=$row->status;
		$start=$row->start;
		$now=$row->now;
		$end=$row->end;
		$is_during_office_hours=$row->should_work;
		print "$client is $status :: <br/>\n";
		print "$now :: $start / $end ::::---- is during work hours? = $is_during_office_hours<br/>\n";
		if (!$is_during_office_hours && $status=='disabled') {
			print "OHH $client should work, lets enable him<br/>\n";
			send_order($client,"enable","","5");

		}
		if ($is_during_office_hours && $status=='idle') {
			print "OHH $client should not work, lets disable him<br/>\n";
			send_order($client,"disable","artist@work","5");
			set_info($client,"artist@work");
		}
	}
	
}
function check_project_is_active($project_id) {
	$query="SELECT COUNT(name) FROM projects WHERE id='$project_id' AND status='active'";
        $results=mysql_query($query);
        $check_result=mysql_result($results,0);
        return $check_result;
}
function check_project_exists($project) {
	$query="SELECT count(name) FROM projects WHERE name='$project'";
        $results=mysql_query($query);
        $check_result=mysql_result($results,0);
        return $check_result;
}
function get_path($project,$what,$os="NONE") {
	# get the output/blend path for a specific project and specific OS
	#$path=$what."_".$GLOBALS['os'];
	if (!check_project_exists($project)) {
		debug("project $project not found");
		return 0;
	}
	if ($os=="NONE") {$os=$GLOBALS['os'];};
	$path=$what."_".$os;
	$query="SELECT $path FROM projects WHERE name='$project'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	debug (" ++++ + + + + + + + + + +GETTING PATH $query path = $path");
	return $qq;
}
function windowsify_paths($path) {
	#print("dir sep = " +DIRECTORY_SEPARATOR);
	#if (DIRECTORY_SEPARATOR == "\/") {
	#	$new_path = preg_replace('/', DIRECTORY_SEPARATOR, $path);
	#}
	$new_path=preg_replace("/\//","\\",$path); #change slash to backslash
	$new_path=preg_replace("/\'/i","",$path);  # needed because windows
	debug("\n ***** $path ****** \n ----becomes ----- \n ******* $new_path ****** ------\n");
	return $new_path;
}
function get_blender_path() {
	$query="SELECT blender_local_path FROM clients WHERE client='$GLOBALS[computer_name]'";
	$results=mysql_query($query);
	$local_path=mysql_result($results,0);
	debug("****************** query  = $query *****************\n");
	debug("****************** local path  = $local_path *****************\n");
	if ($local_path) {
		if (file_exists($local_path)) {
			# --- there is a local_path set in the client table, so we return it
			output("blender local path found :: $local_path .... niiiice!");
			return $local_path;
		}
		else {
			output("blender local path not found :: $local_path .... switching to server one","warning");
		}
	}
	# ---there is no local_path, so we take the blender form the brender_root
	switch($GLOBALS['os']) {
		case "mac":
			$path="blender_bin/mac/blender.app/Contents/MacOS/blender";
			break;
		case "linux":
			$path="blender_bin/linux/blender";
			break;
		case "windows":
			$path="blender_bin\windows\blender.exe";
			break;

	}
	return $path;
}
function change_order_owner($id,$client) {
	#function allowing a client to self-assign himself to an order
	# used in case an order's client value is set to ANY, so this value becomes CLIENT and no other client will execute the order
	$query="UPDATE orders set client=$client WHERE id='$id'";
	# mysql_unbuffered_query($query);
	print "become order query $query\n";
	# print "### $client deleted order $id\n";
}
function delete_node($client) {
	$query="DELETE FROM clients WHERE client='$client'";
	debug("delete query $query");
	output("NODE DELETED :: $client");
	mysql_query($query);
}
function remove_order($id) {
	$query="DELETE FROM orders WHERE id='$id'";
	mysql_unbuffered_query($query);
	#$os=$GLOBALS['os'];
	#$client=$GLOBALS['computer_name'];
	 #print "### $client of $os deleted order $id\n";
}
function server_stop($pid){
	$query="UPDATE server_settings set pid='$pid',status='stopped',started=now()";
	# print "\n query = $query ----\n";
	mysql_unbuffered_query($query);
	print "STOPPED SERVER \n";
	stop();
}
function server_start($pid){
	$query="UPDATE server_settings set pid='$pid',status='running',started=now()";
	# 	print "\n query = $query ----\n";
	mysql_query($query);
	print "STARTED SERVER\n";
}
function check_if_client_has_order_waiting($client) {
        $query="SELECT count(orders) FROM orders WHERE client='$client'";
        $results=mysql_query($query);
        $check_result=mysql_result($results,0);
	return $check_result;
}
function check_client_is_running($client) {
	#---to check if client is running, we send him a ping order. If he is alive, it will remove the order. If not the order will still be there after 3 sconds, meaning the server is ko
	$client_status=get_client_status($client);
	if ($client_status=="not running") {
		return 0;
	}
	else if (preg_match("/rendering/",$client_status)) {
		return 1;
	}
	send_order("$client","ping","","1");
	sleep(3);
        $check_query="SELECT COUNT(orders) FROM orders WHERE orders='ping' AND client='$client'";
        $results=mysql_query($check_query);
        $ping_result=mysql_result($results,0);
	#print "ping res = $ping_result\n";
	if ($ping_result==0) {
		# the ping order is still there so it sems the client is dead
		return 1;
	}
}
function check_server_is_dead() {
	#---to check if server is running, we send him a ping order. If he is alive, it will remove the order. If not the order will still be there after 3 sconds, meaning the server is ko
	send_order("server","ping","","1");
	sleep(3);
        $check_query="SELECT COUNT(orders) FROM orders WHERE orders='ping' AND client='server'";
        $results=mysql_query($check_query);
        $ping_result=mysql_result($results,0);
	return $ping_result;
}
function check_server_status(){
	# print "<br/>get server status<br/>";
	# command to see if the server is running or dead
	#brender_log("I CHECK SERVER STATUS");
        if (check_server_is_dead()){
		#$GLOBALS['computer_name']="web_interface";
		set_server_settings("status","died");
		set_server_settings("pid","0");
		set_server_settings("started","now()");
		brender_log("server not responding (PING)");
		brender_log("SERVER DIED");
		//$color="red";
		$status="SERVER DIED !!!!!!!!";
       	}
	else {
		set_server_settings("status","running");
		//$color="green";
		$status="server is running";
	}
	return $status;
}
function get_server_settings($setting){
	$query="SELECT $setting FROM server_settings;";
	$results=mysql_query($query);
	$status=mysql_result($results,0);
	# debug("QUERY SERVER SETTINGS = $query");
	return $status;
}
function set_server_settings($key,$value){
	$query="UPDATE server_settings set $key='$value'";
	mysql_unbuffered_query($query);
	#	print "### $client status : $status $rem\n";
}
function set_info($client,$info){
	#$rem=str_replace("'","\'",$rem);
	$query="UPDATE clients set info='$info' WHERE client='$client'";
	mysql_unbuffered_query($query);
	print "### INFO $client status : $info\n";
}
function get_info($client){
	$query="SELECT info FROM clients WHERE client='$client';";
	#print "*************** infoquery $query\n";
	$results=mysql_query($query);
	$info=mysql_result($results,0);
	#print "*************** info $info\n";
	return $info;
}
function set_status($client,$status,$rem=""){
	$rem=str_replace("'","\'",$rem);
	$query="UPDATE clients set status='$status',rem='$rem' WHERE client='$client'";
	mysql_unbuffered_query($query);
	#print "### $client status : $status $rem\n$query\n";
}
function get_status($client) {
	$query="SELECT status FROM clients WHERE client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function send_order($client,$orders,$rem,$priority){
	#print "------send_order var = $client, $orders, $rem, $priority----\n";
	$query="insert into orders values('','$client',NOW(),'$orders','$priority','$rem')";
	 #print "order query = $query\n";
	mysql_unbuffered_query($query);
}
function brender_log($log){
	$computer_name=$GLOBALS['computer_name'];
	$log=preg_replace("/\n$/","",$log);  # we erase the trailing carriage return to avoid empty lines in the log file
	$prefix=""; // initialize prefix variable
	$slash="/"; //Normally use forward slash
	if ($computer_name=="web_interface") {
		$prefix="../";
	}
	elseif ($computer_name=="ajax") { //adding else to ensure that backslashes are only used when not ajax and not web interface
		$prefix="../../";
	}
	elseif ($GLOBALS['os']=="windows") { //Only if type is client and os is windows right?
		$slash="\\";
	}
	$heure=date('Y/d/m H:i:s');
	$log_koi = "$heure $computer_name: $log\n";
	#print "\n---------------------- I AM LOGGING THIS ::: $log_koi-----end ----\n";
	# --- we log 2 times, first time for the computer itself, and ....
	$foo=fopen($prefix."logs".$slash.$computer_name.".log","a");
            fwrite($foo,"$log_koi");
        fclose($foo);
	# .... second time for the brender.log that includes all logs
	$foo=fopen($prefix."logs".$slash."brender.log","a");
            fwrite($foo,"$log_koi");
        fclose($foo);
	#print "$prefix/logs/brender.log";
}
function output_progress_status_SELECT($default="NONE") {
	$list= array("blocked","layout","model","animation","lighting","compositing","finished","approved","");
	foreach ($list as $item) {
		#print("check default=$default and item=$item");
		if ($default==$item|| $default=="new") {
			print " <option value=\"$item\" SELECTed>$item </option>";
		}	
		else {
			print " <option value=\"$item\">$item</option>";
		}
	}
}
function scene_shot_cascading_dropdown_menus() {
	# ----- WORK IN PROGRESS -----------XXX------
	# to have this working, the server needs to have an server_os set, and have path access to the blend files of the project for autodiscovery of .blend files

	$projects_list=get_projects_list_array("active");
	$projects_options = $shot_options = $scene_options = "";  #initalize the options

	foreach ($projects_list as $project) {
		$scene_list=get_scene_list_array($project);
		$projects_options.="<option class=\"project\" value=\"$project\">$project</option>";
		foreach ($scene_list as $scene) {
			#print "*** $scene <br/>";;
			$scene_options.="<option class=\"$project\" value=\"$scene\">$scene</option>";
			$shot_list=get_shot_list_array($project,$scene);
			#$shot_options="";
			foreach ($shot_list as $shot) {
				#$shot_options.="<option class=\"$scene\" value=\"$shot\">$project /$scene /$shot</option>";
				$shot_options.="<option class=\"$scene\" value=\"$shot\">$shot</option>";
				#print " --- $project -- $scene -- $shot<br/>";
			}
		}
	}
	?>
	<select name="project" id="project">
		<?php echo $projects_options ?>
	</select><br/>

	<select name="scene" id="scene">
		<?php echo $scene_options ?>
	</select><br/>
	
	<select name="shot" id="shot">
		<?php echo $shot_options ?>
		<option class=\"warning_message\" value=\"warning_message\">please choose a scene first</option>
	</select><br/>
	<!--  the javascript needs to be called after <select><option> construction. No idea why. This has to be fixed  -->
	<script type="text/javascript" src="js/brender-0.5.dev.js"></script> 

	<?php

}
function get_projects_list_array($type="DEFAULT") {
	$projects_list=array();   // initalize projects_list

	switch($type) {
		case "active":
			$query="SELECT * FROM projects WHERE status='active' ORDER BY def DESC ";
			break;
		default:
			$query="SELECT * FROM projects ORDER BY def DESC ";
	}
        $results=mysql_query($query);

	while ($row=mysql_fetch_object($results)) {
		$project_name=$row->name;
		$projects_list[]=$project_name;
	}
	#print_r($projects_list);
	
	if (count($projects_list)==0){
		$projects_list[]=" ! no ACTIVE project, please add one !";
	}
	#print "dfdffdfdfddf --- $projects_list ---<br/>";
	return $projects_list;
}
function get_scene_list_array($project) {
	# ----- WORK IN PROGRESS -----------XXX------
	# to have this working, the server needs to have an server_os set, and have path access to the blend files of the project
	$server_os=get_server_settings("server_os");
	$blend_path=get_path($project,"blend",$server_os);
	debug("Debug path server_os = $server_os and path = $blend_path");

	$scene_list=array();
	$list= `ls $blend_path`;
	$list=preg_split("/\n/",$list);
	#$list=scandir($blend_path);

	foreach ($list as $item) {
		#print("<br/>check item = $item<br/>");
		if (is_dir("$blend_path/$item")) {
			if (!$item) {
				$item="/";
			}
			if($item !="." && $item!="..") {
				# we do not want to add the . and .. directories
				$scene_list[]=$item;
			}
		}
	}
	if (count($scene_list)==0){
		# we try with always showing the / folder
		$scene_list[]="/";
	}
	return $scene_list;
}
function get_shot_list_array($project,$selected_scene="/") {
	# ----- WORK IN PROGRESS -----------XXX------
	# to have this working, the server needs to have a server_os set, and have path access to the blend files of the project
	$server_os=get_server_settings("server_os");
	$scenes_path=get_path($project,"blend",$server_os);
	debug("Debug path server_os = $server_os and path = $scenes_path");

	$shot_list=array();
	$list= `ls $scenes_path/$selected_scene`;
	$list=preg_split("/\n/",$list);
	#$list=scandir("$scenes_path/$selected_scene");

	foreach ($list as $item) {
		#print("check item=$item<br/>");
		if (preg_match("/(.*)\.blend$/",$item,$res)) {
			$filename=$res[1]; # we extract only filename without .blend from regex
			$shot_list[]=$filename;
		}
	}
	if (count($shot_list)==0){
		#print "*** error empty for $project and $selected_scene ****";
		$shot_list[]="";
	}
	return $shot_list;
}
function output_scene_selector($project) {
	# ----- WORK IN PROGRESS -----------XXX------
	#  NOT USED for the moment XXX might become useful if ajaxify the cascading
	# to have this working, the server needs to have an server_os set, and have path access to the blend files of the project
	$server_os=get_server_settings("server_os");
	$blend_path=get_path($project,"blend",$server_os);
	debug("Debug path server_os = $server_os and path = $blend_path");

	$list= `ls $blend_path`;
	$list=preg_split("/\n/",$list);

	print "<select name=\"scene\">";
		print " <option value=\"\">---choose a SCENE---</option>";
		foreach ($list as $item) {
			print("check item=$item<br/>");
			if (is_dir("$blend_path/$item")) {
				print " <option value=\"$item\">$item </option>";
			}
		}
	print "</select><br/>";
}
function output_shot_selector($project,$selected_scene="") {
	# ----- WORK IN PROGRESS -----------XXX------
	#  NOT USED for the moment XXX might become useful if ajaxify the cascading
	# to have this working, the server needs to have a server_os set, and have path access to the blend files of the project
	$server_os=get_server_settings("server_os");
	$scenes_path=get_path($project,"blend",$server_os);
	debug("Debug path server_os = $server_os and path = $scenes_path");

	$list= `ls $scenes_path/$selected_scene`;
	$list=preg_split("/\n/",$list);

	print "<select name=\"shot\">";
		print " <option value=\"\">---choose a shot---</option>";
		foreach ($list as $item) {
			print("check item=$item<br/>");
			if (preg_match("/(\w*)\.blend$/",$item,$res)) {
				$filename=$res[1]; # we extract only filename without .blend from regex
				print " <option value=\"$filename\">$item</option>";
			}
		}
	print "</select>";
}
function output_config_select($default="NONE") {
	if ($default=="NONE") {$default=$_SESSION['last_used_config'];};
	#$list= `ls ../conf/`;
	#$list=preg_split("/\n/",$list);
	$list=scandir("../conf/");	
	foreach ($list as $item) {
		# we do not want the "." and ".." folders, and we only want .py python files
		if($item !="." && $item!=".." && preg_match("/^[^.](\w*)\.py/",$item)) {
			$item=preg_replace("/\.py/","",$item);
			#print("check default=$default and item=$item");
			if ($default==$item) {
				print " <option value=\"$item\" selected>$item</option>";
			}	
			else if ($item<>""){
				print " <option value=\"$item\">$item</option>";
			}
		}
	}
}
function checking_alive_clients() {
	# print "i am checking alive clients ";
	# to check alive clients we first looks who is supposed to be active, and send them a png order ....
	$query="select * FROM clients WHERE status='idle' or status='disabled'";
        $results=mysql_query($query);
        while ($row=mysql_fetch_object($results)){
                $id=$row->id;
                $client=$row->client;
                $status=$row->status;
                send_order($client,"ping","","15");
                print "pinging $client...\n";
        }
	#... then we sleep 2 second, time to let a client get the order and delete it....
        sleep(2);
        $query="select * FROM orders WHERE orders='ping'";
        $results=mysql_query($query);
	#..... then we check which client did not reply to ping order, so we know its dead
        while ($row=mysql_fetch_object($results)){
                $id=$row->id;
                $client=$row->client;
                print "$id = $client is dead\n";
                set_status("$client","not running",'client not responding (PING)');
		brender_log("$client not responding (PING)");
                remove_order($id);
        }
}
function seconds_to_hms($time_in_secs) {
	# function to display time from seconds to hours:minutes:seconds
   $secs = $time_in_secs % 60;
   $time_in_secs -= $secs;
   $time_in_secs /= 60;

   $mins = $time_in_secs % 60;
   $time_in_secs -= $mins;
   $time_in_secs /= 60;

   $hours = $time_in_secs % 24;
   $time_in_secs -= $hours;
   $time_in_secs /= 24;

   return str_pad($hours,2,'0',STR_PAD_LEFT) . ":" . str_pad($mins,2,'0',STR_PAD_LEFT) . ":" . str_pad($secs,2,'0',STR_PAD_LEFT);

   #$days= $time_in_secs;
	/*if (!$days) {
	}
	else {
		return ($days." days ".str_pad($hours,2,'0',STR_PAD_LEFT) . ":" . str_pad($mins,2,'0',STR_PAD_LEFT) . ":" . str_pad($secs,2,'0',STR_PAD_LEFT));
	}
	*/
}
function clean_name($name) {
	# cleaning name : spaces tranformed to _ and only lowercase
	#print " I WILL CLEAN $name<br/>";
	$name=strtolower($name);
	$name=str_replace(" ","_",$name);
	#print " I CLEANED $name<br/>";
	return $name;
}
function job_get($what,$id) {
	if (!check_job_exists($id)) {
		# job doesnt exist or was deleted, we just return o
		return 0;
	}
	$query="select $what FROM jobs WHERE id='$id'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	#debug ("******************************************$query*****************************************");
	return $qq;
}
function check_create_path($path) {
	if (is_dir($path)) {
		output("$path already exists");
	}
	else {
		if (!mkdir($path,0777,true)) {
			output("$path creation error", "ERROR");
		}
		else {
			debug("dir $path created successfully");
		}
	}
	# - function to check if a path exists, if not then create it";
	/*
	debug("Check & Create Path  --- $path "); 
	#print "DEBUG --- $path chmod\n"; 
	$paths_array = explode("/",$path);
	$current_path = $path_to_check = "";
	#print_r($paths_array);
	foreach ($paths_array as $item) {
		debug("\n");
		debug("\n");
		debug("--- i am trying  <b>$item</b>\n");
		$path_to_check = $path_to_check."/$item";
		print "-------- path to check = $path_to_check\n<br/>";
		if (!is_dir("$path_to_check")) {
			print "$path_to_check <b>not exist</b>, lets create\n";
			mkdir($path_to_check);
		}
		else {
			print "$path_to_check exists....\n";
		}
		debug("$path_to_check exists, i will now do a chmod");
		chmod($path_to_check,0777);
	}
	*/
}
function shortify_string($string,$max_length=50) {
	#$tmp = preg_replace("/(.4)(.*)/","xx $1 xx",$string);  // TODO replace with REGEX... 
	
	debug("----------ORIGINAL -----<b>$string</b>--------- <br/>");
	if (strlen($string)>$max_length) {
		debug("--- TOO LONG----<br/>");
		$divider = $max_length/2;
		$start = substr($string,0,$divider);
		$end = substr($string,-$divider);
		$tmp ="$start......$end";
		#print "----------start $start--------- <br/>";
		#print "----------end  $end--------- <br/>";
		#print "----------TMP $tmp--------- <br/>";
		#print "<br/>";
		return $tmp;
	}
	else {
		return $string;
	}
}
function filetype_to_ext($filetype) {
	# transform filetype of the format that blender understands PNG TGA JPEG EXR to a simple extension
	switch ($filetype) {
		case "PNG":
			return "png";
		case "TGA":
			return "tga";
		case "JPEG":
			return "jpg";
		case "EXR":
			return "exr";
		case "MULTILAYER":
			return "exr";
	}
	
}
function add_rendered_frames($job_id,$start,$end){
	#will add a rendered frame to the table, usually it is a client that invoke this function just after finishing a render job
	$client=$GLOBALS['computer_name'];
	for ($i=$start;$i<$end+1;$i++) {
		$query="insert into rendered_frames values('','$job_id','$i','$client',now(),'0')";
		debug("ADD RENDER FRAME QUERY = $query");
		mysql_query($query);
	}
}
function parse_render_command($render_command) {
	#print "parsing $render_command<br/>";
	$parsed=array();
        #preg_match("/(.*)\-s (.\d) \-e (.\d)\ -a \-JOB (\d*)/",$render_command,$preg_matches);
        preg_match("/(.*)\-s (\d*) -e (\d*) -a -JOB (\d*)/",$render_command,$preg_matches);
        $parsed["start"]=$preg_matches[2];
        $parsed["end"]=$preg_matches[3];
        $parsed["job_id"]=$preg_matches[4];
        #$job_id=$preg_matches[2];
        debug("-----------------PARSING :: $render_command--------------------------");
        debug("-----------------JOB ID = ".$parsed['job_id']." start=".$parsed["start"]." end=".$parsed['end']." --------------------------");
	return $parsed;
}
function get_thumbnail_image($job_id,$image_number,$class="") {
	# function will output the <img src> of the thumbnail of a specific job_id and frame
	debug("i try to get the frame $image_number from job_id= $job_id and output the image");
	$thumbnail_path="thumbnails/";
	$scene=job_get("scene",$job_id);
	$shot=job_get("shot",$job_id);
	$filename=basename($shot);
	$filetype=filetype_to_ext(job_get("filetype",$job_id));
	$project=job_get("project",$job_id);
	$filetype="png"; // temporary test for fixing job thumbnail viewing when filetype is JPG OPENEXR or TGA
	$thumbnail_location="thumbnails/$project/$scene/$shot/small_$filename".str_pad($image_number,4,0,STR_PAD_LEFT).".$filetype";
	#print "**** $thumbnail_location****";
	if (file_exists("../$thumbnail_location")) {
		return "<img src=\"/$thumbnail_location\" class=\"$class\">";
	}
	else {
		return false;
	}
}
function create_thumbnail($job_id,$image_number) {
	if ($GLOBALS['computer_name']=="web_interface") {
		#print "WEBBBBBB<br/>";
		$thumbnail_path="../thumbnails";
		#$input_prefix="../";
	}
	else {
		$thumbnail_path="thumbnails";
	}
	
	debug("----------------------------------");
	debug ("creating a  thumbnail : for image number $image_number of job with id = $job_id");
	$scene=job_get("scene",$job_id);
	$shot=job_get("shot",$job_id);
	$filename=basename($shot);
	$filetype=filetype_to_ext(job_get("filetype",$job_id));
	$project=job_get("project",$job_id);
	$image_name=$filename.str_pad($image_number,4,0,STR_PAD_LEFT).".$filetype";
	$thumbnail_name=$filename.str_pad($image_number,4,0,STR_PAD_LEFT).".png";  // XXX SPECIAL FIX for trying to resolve non-png jobs thumbnails

	$server_os=get_server_settings("server_os");
	$input_path=get_path($project,"output",$server_os);
	$input_image = "$input_path/$scene/$shot/$image_name";
	#print "<br/>----- input = $input_image ---<br/>";

	if (!file_exists($input_image)) {
		# if input file doesnt exists/rendered we just close the function, dont need to make thumbnail
		debug("file $input_image doesnt exists, we close function <br/>");
		return 0;
	}
	# create and check that all path are existing
	#check_create_path("$thumbnail_path/$project");
	#check_create_path("$thumbnail_path/$project/$scene");
	check_create_path("$thumbnail_path/$project/$scene/$shot");
	$output_image="$thumbnail_path/$project/$scene/$shot/$thumbnail_name";
	$output_image_small="$thumbnail_path/$project/$scene/$shot/small_$thumbnail_name";

	debug("----- output = $output_image ---<br/>");
	#print "<b>creating thumbnail</b> $image_number jobid = $job_id<br/";
	#$image_magick_home="/Users/o/Documents/ImageMagick-6.6.5/bin/";
       	$commande=$GLOBALS['imagemagick_root']."convert -resize 1024 '$input_image' '$output_image'";
	exec($commande);
       	$commande=$GLOBALS['imagemagick_root']."convert -resize 200 '$input_image' '$output_image_small'";
	exec($commande);
	debug("#########   CONVERT COMMAND   ####### $commande");
}

function output_progress_bar($start,$end,$current,$style="progress_bar") {
	# --- little display of progress_bars
	$total=$end-$start;
	if ($current==$start) {
		$percent=0;
	}
	else if ($current>=$end) {
		$percent=100;
	}
	else {
		$percent=round(($current-$start)/$total*100);
	}
	$done=$percent/2;
	$remaining=(100-$percent)/2;
	$output= "<img src=\"images/cube_light_green.png\" style=\"width:".$done."px;\" class=\"$style\" alt=\"$percent% done\"/>";
	#$output.= "<br/>$done / $remaining";
	return $output;
}
function show_last_rendered_frame($mode="simple") {
	$query="SELECT * FROM rendered_frames WHERE is_thumbnailed='1' ORDER BY finished_time DESC LIMIT 1";
        $results=mysql_query($query);
        $row=mysql_fetch_object($results);
	if (mysql_num_rows($results)==0) {
		print "no last rendered frame found";
	}
	else {
        	$job_id=$row->job_id;
        	$rendered_by=$row->rendered_by;
        	$frame=$row->frame;
        	$finished_time=$row->finished_time;
		$thumbnail_image=get_thumbnail_image($job_id,$frame);
		if ($thumbnail_image) {
			if ($mode=="full") {
        			print "<a href=\"index.php?view=view_image&job_id=$job_id&frame=$frame\">$thumbnail_image</a><br/>";
				print "$frame by <a href=\"index.php?view=view_client&client=$rendered_by\">$rendered_by</a> @ $finished_time<br/>";
			}
			else {
       			  	print $thumbnail_image;
			}
		}
		else {
			print "no last rendered frame found";
		}
	}
}
function count_rendered_frames($job_id) {
		# counting the total number of rendered frames for a job.
		# If a job is rerendered, the total might already be 100%

		$query="SELECT scene,shot,project,start,end,filetype FROM jobs WHERE id='$job_id'";
		$results=mysql_query($query);
		$row=mysql_fetch_object($results);
		#print "query === $query <br/>";

		$scene=$row->scene;
		$shot=$row->shot;
		$filetype=filetype_to_ext($row->filetype);
		$project=$row->project;
		$server_os=get_server_settings("server_os");
		$path=get_path($project,"output",$server_os);
		$end=$row->end;
		$a=$row->start-1;
		$total=0;

                # print " i check $a to $end $output###.$filetype<br/>";
                while ($a<$end){
                        $a++;
                        $filecheck="$path/$scene/$shot/$shot".str_pad($a,4,0,STR_PAD_LEFT).".".$filetype;
                        if (file_exists($filecheck)) {
                                $ok="ok";
                                $total++;
                        }
                        else {
                                $ok="";
                        }
			debug("count_rendered_frames filecheck = $filecheck  == $ok");
               }
		# print "total $total<br/>";
		return $total;

}

?>
