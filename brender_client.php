#!/usr/bin/php -q
<?php 
print "---- brender client 0.1 ----\n";
require "functions.php";
if (!$argv[1]) {
	die("ERROR: no .conf file\n");
}
if ($argv[1]=="node") {
	require "conf/node.conf";
	#$r=rand(1,100000);
	$r=$argv[2];
	#$r=md5($r);
	$computer_name="node_$r";
	print "debug test node computer name=$computer_name\n";
	require_once "connect.php";
	#new_node($computer_name);
}
else {
	require "conf/$argv[1].conf";
}
require_once "connect.php";

if (!$argv[1]) {
	die("no computer_name, please use \n ./brender_client.php <COMPUTER_NAME>\n");
}
global $computer_name;
set_status("$computer_name","idle",'');
print "computer name=$computer_name\n";
print "blenderpath=$blender_path\n";
print "process id=".getmypid()."\n\n";
brender_log("START $computer_name");

while ($q=1) {
#-----------------boucle principale---------
$query="select * from orders order by id desc";
$results=mysql_query($query);
while ($row=mysql_fetch_object($results)){
	$id=$row->id;
	$client=$row->client;
	$orders=$row->orders;
	$rem=$row->rem;
	if ($client==$computer_name || $client=="any") {
		print "wow its me...time to work!\n ";
		if ($orders=="render") {
			# ---- RENDER ---
			change_order_owner($id,$computer_name);
        		$rquery="$blender_path $rem";
			brender_log("RENDER $rem");
			set_status("$computer_name","rendering","$rem");
			print "rquery=$rquery\n";
        		system($rquery);
			remove_order($id);
			set_status("$computer_name","idle","");
		}
		else if ($orders=="enable") { 
			remove_order($id);
			set_status("$computer_name","idle","");
			brender_log("ENABLE");
		}
		else if ($orders=="sound") { 
			remove_order($id);
			brender_playsound($rem);
		}
		else if ($orders=="disable") { 
			remove_order($id);
			set_status("$computer_name","disabled","");
			brender_log("DISABLE");
			sleep(1);
		}
		else if ($orders=="declare_finished") { 
			brender_log("DECLARE FINISHED job $rem");
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
			remove_order($id);
			set_status("$computer_name","not running","");
			brender_log("STOP");
			die("\n stop $id\n");
		}
	}
}
#sleep(1);
if($a++==10000){
	$a=0;
	print ".";
        $foo=fopen("logs/nothing.log",a);
        fwrite($foo,".");
        fclose($foo);
}
#----------------------------------
}


?>
