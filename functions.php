<?php
date_default_timezone_set('Europe/Zurich'); # ----needed by php
function test() {
	global $qwer;
	print "qwer=$qwer\n";
}
function output($msg,$type="info") {
	# function used in the command line scripts (brender_server.php and brender_client.php) for outputing and logging things
	# output can be customized in future for different types 
	# currently used type = info, warning, error
	brender_log($msg);
	$when=date('Y/d/m H:i:s');
	$msg= "$when : $type : $msg";
	print "$msg\n";
}
function debug($msg) {
	if ($GLOBALS[computer_name]=="web_interface") {
		if ($_SESSION[debug]) { 
		# only display debug message on web interface when _SESSION debug is enabled
			print "## DEBUG :: $msg<br/>";
		}
	}
	else {
		# --- for command_line we always display debug messages
		print "## DEBUG :: $msg\n";
	}
}
function check_client_exists($client) {
	$query="select count(client) from clients where client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function get_client_os($client) {
	$query="select machine_os from clients where client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function get_css_class($status) {
	if (preg_match("/rendering/",$status)) {
		return "color_rendering";
	}
	else if (preg_match("/idle/",$status)) {
		return "color_idle";
	}
	else if (preg_match("/disabled/",$status)) {
		return "color_disabled";
	}
	else if (preg_match("/enabled/",$status)) {
		return "color_enabled";
	}
	else if (preg_match("/not running/",$status)) {
		return "color_not_running";
	}
	else if (preg_match("/pause/",$status)) {
		return "color_pause";
	}
	else if (preg_match("/finished/",$status)) {
		return "color_finished";
	}
	else if (preg_match("/waiting/",$status)) {
		return "color_waiting";
	}
	
}
function check_if_client_should_work($client_name="check all") {
	# function used for checking if a client is during his "working hours", these are set in the database for each client.
	if ($client_name <> "check all") {
		$query="SELECT (DATE_FORMAT( NOW( ) , '%T' ) BETWEEN  working_hour_start AND working_hour_end) as should_work FROM clients WHERE client='$client_name'";
		$results=mysql_query($query);
		$qq=mysql_result($results,0);
		return($qq);
		
	}
	#checking_alive_clients();
	$query="SELECT DATE_FORMAT( NOW( ) , '%T' ) as now,working_hour_start as start,working_hour_end as end,client,status,machinetype,(DATE_FORMAT( NOW( ) , '%T' ) BETWEEN  working_hour_start AND working_hour_end) as should_work FROM clients WHERE machinetype='workstation' and status<>'not running'";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)) {
		$client=$row->client;
		$status=$row->status;
		$start=$row->start;
		$now=$row->now;
		$end=$row->end;
		$is_during_office_hours=$row->should_work;
		print "$client is $status :: <br/>";
		print "$now :: $start / $end ::::---- is during work hours? = $is_during_office_hours<br/>";
		if (!$is_during_office_hours && $status=='disabled') {
			print "OHH $client should work, lets enable him<br/>";
			send_order($client,"enable","","5");

		}
		if ($is_during_office_hours && $status=='idle') {
			print "OHH $client should not work, lets disable him<br/>";
			send_order($client,"disable","artist@work","5");
		}
	}
	
}
function get_path($project,$what,$os="NONE") {
	# get the output/blend path for a specific project and specific OS
	#$path=$what."_".$GLOBALS['os'];
	if ($os=="NONE") {$os=$GLOBALS['os'];};
	$path=$what."_".$os;
	$query="select $path from projects where name='$project'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	#print "GETTING PATH $query \n path = $path\n";
	return $qq;
}
function get_blender_path() {
	switch($GLOBALS['os']) {
		case "mac":
			$path="blender/mac/blender.app/Contents/MacOS/blender";
			break;
		case "linux":
			$path="blender/linux/blender";
			break;
		case "windows":
			$path="blender/windows/blender.exe";
			break;

	}
	return $path;
}
function change_order_owner($id,$client) {
	#function allowing a client to self-assign himself to an order
	# used in case an order's client value is set to ANY, so this value becomes CLIENT and no other client will execute the order
	$query="update orders set client=$client where id='$id'";
	# mysql_unbuffered_query($query);
	print "become order query $query\n";
	# print "### $client deleted order $id\n";
}
function delete_node($node) {
	$query="delete from clients where name='$node'";
	mysql_query($query);
}
function remove_order($id) {
	$query="delete from orders where id='$id'";
	mysql_unbuffered_query($query);
	#$os=$GLOBALS['os'];
	#$client=$GLOBALS['computer_name'];
	 #print "### $client of $os deleted order $id\n";
}
function server_stop($pid){
	$query="update status set pid='$pid',status='stopped',started=now()";
	# print "\n query = $query ----\n";
	mysql_unbuffered_query($query);
	print "STOPPED SERVER \n";
	stop();
}
function server_start($pid){
	$query="update status set pid='$pid',status='running',started=now()";
	# 	print "\n query = $query ----\n";
	mysql_query($query);
	print "STARTED SERVER $status $rem\n";
}
function check_server_is_dead() {
	#---to check if server is running, we send him a ping order. If he is alive, it will remove the order. If not the order will still be there after 3 sconds, meaning the server is ko
	send_order("server","ping","","1");
	sleep(3);
        $query="select count(orders) from orders where orders='ping' and client='server'";
        $results=mysql_query($query);
        $ping_result=mysql_result($results,0);
	return $ping_result;
}
function check_server_status(){
	# print "<br/>get server status<br/>";
	# command to see if the server is running or dead
        if (check_server_is_dead()){
		$GLOBALS['computer_name']="web_interface";
		set_server_status("status","died");
		set_server_status("pid","0");
		set_server_status("started","now()");
		brender_log("server not responding (PING)");
		brender_log("SERVER DIED");
		$color="red";
		$status="SERVER DIED !!!!!!!!<br/>";
       	}
	else {
		set_server_status("status","running");
		$color="green";
		$status="server is running";
	}
	print "<font color=$color>$status $pid</font>\n";
}
function get_server_status(){
	$query="select status from status;";
	$results=mysql_query($query);
	$status=mysql_result($results,0);
	return $status;
}
function set_server_status($key,$value){
	$query="update status set $key='$value'";
	mysql_unbuffered_query($query);
	#	print "### $client status : $status $rem\n";
}
function set_info($client,$info){
	#$rem=str_replace("'","\'",$rem);
	$query="update clients set info='$info' where client='$client'";
	mysql_unbuffered_query($query);
	print "### INFO $client status : $info\n";
}
function get_info($client){
	$query="select info from clients where client='$client';";
	#print "*************** infoquery $query\n";
	$results=mysql_query($query);
	$info=mysql_result($results,0);
	#print "*************** info $info\n";
	return $info;
}
function set_status($client,$status,$rem){
	$rem=str_replace("'","\'",$rem);
	$query="update clients set status='$status',rem='$rem' where client='$client'";
	mysql_unbuffered_query($query);
	#print "### $client status : $status $rem\n$query\n";
}
function get_status($client) {
	$query="select status from clients where client='$client'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	return $qq;
}
function send_order($client,$orders,$rem,$priority){
	#print "------send_order var = $client, $orders, $rem, $priority----\n";
	$query="insert into orders values('','$client','$orders','$priority','$rem')";
	 #print "order query = $query\n";
	mysql_unbuffered_query($query);
}
function brender_log($log){
	$computer_name=$GLOBALS['computer_name'];
	if ($computer_name=="web_interface") {
		$prefix="../";
	}
	$heure=date('Y/d/m H:i:s');
	$log_koi = "$heure $computer_name: $log\n";
	#print "\n---------------------- I AM LOGGING THIS ::: $log_koi-----end ----\n";
	# --- we log 2 times, first time for the computer itself, and ....
	$foo=fopen($prefix."logs/$computer_name.log",a);
            fwrite($foo,"$log_koi");
        fclose($foo);
	# .... second time for the brender.log that includes all logs
	$foo=fopen($prefix."logs/brender.log",a);
            fwrite($foo,"$log_koi");
        fclose($foo);
	#print "$prefix/logs/brender.log";
}
function output_progress_status_select($default="NONE") {
	$list= array("blocked","layout","model","animation","lighting","compositing","finished","approved","");
	foreach ($list as $item) {
		print("check default=$default and item=$item");
		if ($default==$item) {
			print " <option value=\"$item\" selected>$item </option>";
		}	
		else {
			print " <option value=\"$item\">$item </option>";
		}
	}
}
function output_config_select($default="NONE") {
	if ($default=="NONE") {$default=$_SESSION['last_used_config'];};
	$list= `ls ../conf/`;
	$list=preg_split("/\n/",$list);
	foreach ($list as $item) {
		$item=preg_replace("/\.py/","",$item);
		print("check default=$default and item=$item");
		if ($default==$item) {
			print " <option value=\"$item\" selected>$item </option>";
		}	
		else if ($item<>""){
			print " <option value=\"$item\">$item </option>";
		}
	}
}
function checking_alive_clients() {
	# print "i am checking alive clients ";
	$query="select * from clients where status='idle' or status='disabled'";
        $results=mysql_query($query);
        while ($row=mysql_fetch_object($results)){
                $id=$row->id;
                $client=$row->client;
                $status=$row->status;
                send_order($client,"ping","","15");
                print "pinging $client...\n";
        }
        sleep(2);
        $query="select * from orders where orders='ping'";
        $results=mysql_query($query);
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
	$query="select $what from jobs where id='$id'";
	$results=mysql_query($query);
	$qq=mysql_result($results,0);
	#debug ("******************************************$query*****************************************");
	return $qq;
}
function check_create_path($path) {
	# - function to check if a path exists, if not then create it";
	#print "<br>DEBUG --- $path chmod<br/>"; 
	if (!is_dir($path)) {
		mkdir($path);
	}
	chmod($path,0777);
}
function filetype_to_ext($filetype) {
	# transform filetype to the ofrmat that blender understands
	switch ($filetype) {
		case "PNG":
			return "png";
		case "TGA":
			return "tga";
		case "JPEG":
			return "jpg";
	}
	
}
function parse_render_command($render_command) {
	print "parsing $render_command<br/>";
	$parsed=array();
        preg_match("/(.*)\-s (.\d) \-e (.\d)\ -a \-JOB (\d*)/",$render_command,$preg_matches);
        $parsed["start"]=$preg_matches[2];
        $parsed["end"]=$preg_matches[3];
        $parsed["job_id"]=$preg_matches[4];
        #$job_id=$preg_matches[2];
        #print "JOB ID = $job_id start=".$parsed["start"]." end=$end<br/>";
	return $parsed;
}
function create_thumbnail_sequence($job_id,$start,$end) {
	for ($i=$start;$i<$end+1;$i++) {
		debug("\n THUMBNAIL SEQUENCE generating thumbnail $i for job $job_id\n");
		create_thumbnail($job_id,$i);
	}
}
function create_thumbnail($job_id,$image_number) {
	
	debug("----------------------------------");
	debug ("creating a cool thumbnail : for image number $image_number of job with id = $job_id");
	if (preg_match("/brender_server/",$_SERVER[PHP_SELF])) {
		$thumbnail_path="thumbnails/";
	}
	else {
		$thumbnail_path="../thumbnails/";
	}
	$scene=job_get("scene",$job_id);
	$shot=job_get("shot",$job_id);
	$filetype=filetype_to_ext(job_get("filetype",$job_id));
	$project=job_get("project",$job_id);
	$image_name=$shot.str_pad($image_number,4,0,STR_PAD_LEFT).".$filetype";
	$input_path=get_path($project,"output","linux");

	$input_image = "$input_path/$scene/$shot/$image_name";
	#print "<br/>----- input = $input_image ---<br/>";

	if (!file_exists($input_image)) {
		# if input file doesnt exists/rendered we just close the function, dont need to make thumbnail
		debug("file $input_image doesnt exists, we close function <br/>");
		return 0;
	}
	# create and check that all path are existing
	check_create_path("$thumbnail_path/$project");
	check_create_path("$thumbnail_path/$project/$scene");
	check_create_path("$thumbnail_path/$project/$scene/$shot");
	$output_image="$thumbnail_path/$project/$scene/$shot/$image_name";
	$output_image_small="$thumbnail_path/$project/$scene/$shot/small_$image_name";

	debug("----- output = $output_image ---<br/>");
	#print "<b>creating thumbnail</b> $image_number jobid = $job_id<br/";
	#$image_magick_home="/Users/o/Documents/ImageMagick-6.6.5/bin/";
       	$commande=$image_magick_home."convert -resize 1024 $input_image $output_image";
	exec($commande);
       	$commande=$image_magick_home."convert -resize 200 $input_image $output_image_small";
	exec($commande);
	debug("################ $commande");
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
	$output= "<img src=\"images/cube_light_green.png\" style=\"width:".$done."px;\" class=\"$style\"/>";
	#$output.="<img src=\"images/cube_red.png\" style=\"width:".$remaining."px;\" class=\"$style\"/>";
	#$output.= "<br/>$done / $remaining";
	return $output;
}
function get_rendered_frames($job_id) {

		$query="select scene,shot,project,start,end,filetype from jobs where id='$job_id'";
		#print "query === $query <br/>";
		$results=mysql_query($query);
		$row=mysql_fetch_object($results);
		$scene=$row->scene;
		$shot=$row->shot;
		$filetype=$row->filetype;
		$project=$row->project;
		$path=get_path($project,"blend","linux");
		$end=$row->end;
		$name=$row->name;
		$a=$row->start-1;
                # print " i check $a to $end $output###.$filetype<br/>";
                while ($a<$end){
                        $a++;
                        $filecheck="./$path/$scene/$shot/$shot".str_pad($a,4,0,STR_PAD_LEFT).".".$filetype;
			#print "bla filecheck = $filecheck<br/>";
                        if (file_exists($filecheck)) {
                                $ok="ok";
                                $total++;
                        }
                        else {
                                $ok="";
                        }
               }
		# print "total $total<br/>";
              return $total;

}

?>
