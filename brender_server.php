#!/usr/bin/php -q
<?php 
print "\n---- brender server 0.1 ----\n";
    require "conf/server.conf";
    # $computer_name="server_".$server_name;
    $computer_name="server";
    $pid=getmypid();
require "functions.php";
require "connect.php";
print "process id=$pid\n";
brender_log("SERVER STARTS $pid");
brender_sound("alarm");
server_start($pid);
checking_alive_clients();
while ($q=1) {
#-----------------main loop---------------------------
$query="select * from clients";
$results=mysql_query($query);
while ($row=mysql_fetch_object($results)){
	$id=$row->id;
	$client=$row->client;
	$speed=$row->speed;
	$client_priority=$row->client_priority;
	$status=$row->status;
	$rem=$row->rem;
	if ($status=="idle") {
		# print "$client is idle .... checking for a job\n";
		$query="select * from jobs where status='waiting' or status='rendering' order by priority limit 1;";
		$results_job=mysql_query($query);
		$row_job=mysql_fetch_object($results_job);
			$id=$row_job->id;
			$project=$row_job->project;
			$job=$row_job->name;
			$jobtype=$row_job->jobtype;
			$job_priority=$row_job->priority;
			$file=$row_job->file;
			$start=$row_job->start;
			$output=$row_job->output;
			$filetype=$row_job->filetype;
			if ($filetype=="jpg") {
				$filetype="JPEG";
			}
			elseif ($filetype=="png"){
				$filetype="PNG";
			}
			else {
				$filetype="tga";
			}
			$end=$row_job->end;
			$current=$row_job->current;
			$status=$row_job->status;
			$config="conf/".$row_job->config.".py";
			$chunks=$row_job->chunks;
		if ($job && $client_priority<$job_priority) {
			print "CLIENT $client=$client_priority   ..... JOB $job_poriority \n";
			if ($client_priority<$job_priority) {
				print "CLIENT OK\n";
			}
			else {
				print "CLIENT NOT OK\n";
			}
			print "...found job for $client ::  $name file $file start $start end $end current $current chunks $chunks config=$config\n";	
			brender_sound("sonar");
			$do_start=$current;
			$number_of_chunks=$chunks*$speed;
			$do_end=$current+$number_of_chunks-1;
			if ($do_end>$end) {   # on depasse ce qu'il faut rendre...coupons
				$do_end=$end;
			}
			$new_start=$current+$number_of_chunks; 
			print "$client speed $speed : render $number_of_chunks chunks = ($do_start - $do_end)\n";
			if ($current<$end) {
				if (($do_start+$number_of_chunks)>$end) {
					# ---------render order -----------
					$render_order="-b \'$project/$file\' -o \'$project/$job/$output\' -P $config -F $filetype -s $do_start -e $end -a";
					# ---------------------------------
					print "job_render for $client :\n $render_order\n-----------\n";
					print "===last chunk=== job $name $file finished soon====\n";
					send_order($client,"render",$render_order,"20");
					send_order($client,"declare_finished","$id","30");
					$query="update jobs set current='$new_start',status='rendering' where id='$id'";
				}
				else {
					$render_order="-b \'$project/$file\' -o \'$project/$job/$output\' -P $config -F $filetype -s $do_start -e $do_end -a";
					print "job_render for $client = $render_order\n";
					send_order($client,"render",$render_order,"20");
					$query="update jobs set current='$new_start',status='rendering' where id='$id'";
				}
			}
			else {
				$query="update jobs set status='finished at $heure' where id='$id'";
			}
			# print "--> query= $query\n\n";
			mysql_unbuffered_query($query);
		}
	}
}
$qq=chr(rand(48,122));
 print "$qq";
sleep($server_speed);
if($a++==100){
	$a=0;
	print "... checking alive clients :\n";
	checking_alive_clients();
}
$query="select * from orders where client='server'";
$results=mysql_query($query);
while ($row=mysql_fetch_object($results)){
        $id=$row->id;
        $orders=$row->orders;
	if ($orders=='ping'){
		print "...ping reply $id...";
		remove_order($id);
	}
	elseif ($orders=='stop'){
		print "i shutdown server //\n ";
		remove_order($id);
		server_stop();
	}
	
}

#----------------------------------end main loop -----------------
}


?>
