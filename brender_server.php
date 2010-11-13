#!/usr/bin/php -q
<?php 
require "functions.php";
require "connect.php";

output("---- brender server 0.2 ----");
	#-----some server settings------
    $server_speed=2; # server speed is the number of second that tha main loop will sleep(), check at the end of brender_server.php file
    $computer_name="server";
    $pid=getmypid();

output("process id=$pid");
brender_log("SERVER STARTS $pid");
server_start($pid);
checking_alive_clients();


#-----------------main loop---------------------------
#--- the main loop acts like this : it checks through all clients if there is an idle one, and trys to find some job for it
#----this part might need some cleaning
#-----------------------------------------------------
while ($q=1) {
	$query="select * from clients";
	$results=mysql_query($query) or die(mysql_error());
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$client=$row->client;
		$speed=$row->speed;
		$client_priority=$row->client_priority;
		$client_os=$row->machine_os;
		$status=$row->status;
		$rem=$row->rem;
		if ($status=="idle") {
			# print "$client is idle .... checking for a job\n";
			$query="select * from jobs where status='waiting' or status='rendering' order by priority limit 1;";
			$results_job=mysql_query($query);
			$row_job=mysql_fetch_object($results_job);
				$id=$row_job->id;
				$project=$row_job->project;
				$scene=$row_job->scene;
				$shot=$row_job->shot;
				$job_priority=$row_job->priority;
				$file=$row_job->file;
				$start=$row_job->start;
				$filetype=$row_job->filetype;
				/* if ($filetype=="jpg") {
					$filetype="JPEG";
				}
				elseif ($filetype=="png"){
					$filetype="PNG";
				}
				else {
					$filetype="TGA";
				} */
				#debug("-------------------- $id proj=$project scene=$scene shot=$shot----------");
				$end=$row_job->end;
				$current=$row_job->current;
				$status=$row_job->status;
				$config="conf/".$row_job->config.".py";
				$chunks=$row_job->chunks;

				#output("SCENE = $scene CLIENT priority $client=$client_priority   ..... JOB priority=$job_priority ");
			if ($scene && $client_priority<$job_priority) {
				output("...found job for $client ::  $name file $file start $start end $end current $current chunks $chunks config=$config");	
				$number_of_chunks=$chunks*$speed;
				$where_to_start=$current;
				$where_to_end=$current+$number_of_chunks-1;
				$blend_path=get_path($project,"blend",$client_os);
				$output_path=get_path($project,"output",$client_os);
				if ($where_to_end>$end) {   # we render more than needed, lets cut the end
					$where_to_end=$end;
				}
				$new_start=$current+$number_of_chunks; 
				output("$client speed $speed : render $number_of_chunks chunks = ($do_start - $do_end)");
				if ($current<$end) {
					# --------- MAIN RENDER ORDERS  -----------
					$render_order="-b \'$blend_path/$scene/$shot.blend\' -o \'$output_path/$scene/$shot/$shot\' -P $config -F $filetype ";
					
					if (($where_to_start+$number_of_chunks)>$end) {
						#---last chunk of job, its the end, we only need to render frames from CURRENT to END---
						$render_order.=" -s $where_to_start -e $end -a"; 
						output("===last chunk=== job $name $file finished soon====");
						send_order($client,"declare_finished","$id","30");
					}
					else {
						#---normal job...we render frames from CURRENT to DO_END
						$render_order.=" -s $where_to_start -e $where_to_end -a"; 
					}
					output("job_render for $client :::: $render_order-----------");
					#output(" HEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE!");
					send_order($client,"render","$render_order","20");
					$query="update jobs set current='$new_start',status='rendering' where id='$id'";
				}
				else {
					$query="update jobs set status='finished at $heure' where id='$id'";
				}
				# print "--> query= $query\n\n";
				mysql_unbuffered_query($query);
			}
		}
	}

	#---matrix style useless stuff
	$qq=chr(rand(48,122));
	 print "$qq";

	#----we are sleeping 1 or 2 seconds beetween each cycle
	sleep($server_speed);

	#----every 120 cycle (about every 2 minutes we check if clients are still alive
	if($a++==120){
		$a=0;
		print ("... checking alive clients :");
		checking_alive_clients();
	}

	#------we get and check if there are orders for the server------
	$query="select * from orders where client='server'";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$orders=$row->orders;
		if ($orders=='ping'){
			output("...ping reply from $id...");
			remove_order($id);
		}
		elseif ($orders=='stop'){
			output("i shutdown server","warning");
			remove_order($id);
			server_stop();
		}
		
	}

#----------------------------------end main loop -----------------
}


?>
