#!/usr/bin/php -q
<?php 
require "functions.php";
require "connect.php";

output("---- brender server 0.5 ----");
#-----------------------------------------------------
#-----some server settings------
#-----------------------------------------------------
    $server_speed=2; # server speed is the number of second that tha main loop will sleep(), check at the end of brender_server.php file
    $computer_name="server";
    $GLOBALS['os']="mac";
    $pid=getmypid();
    $imagemagick_root=""; # keep empty if $IMAGEMAGICK_HOME is set 
#-----------------------------------------------------
if ($argv[1] =="debug") {
                        # -- we enable debug mode ------
       $GLOBALS[debug_mode]=1;
       debug(" STARTED IN DEBUG MODE ");
}


output("process id=$pid");
brender_log("SERVER STARTS $pid");
server_start($pid);
# ---things to do and check when starting server
checking_alive_clients();
check_and_delete_old_orders();

#-----------------main loop---------------------------
#--- the main loop acts like this : it checks through all clients if there is an idle one, and trys to find some job for it
#----this part might need some cleaning
#-----------------------------------------------------
while ($q=1) {
	$query="select * from clients";
	$results=mysql_query($query) or die(mysql_error());
	while ($row=mysql_fetch_object($results)){
		check_and_execute_server_orders();
		$id=$row->id;
		$client=$row->client;
		$speed=$row->speed;
		$client_priority=$row->client_priority;
		$client_os=$row->machine_os;
		$status=$row->status;
		$rem=$row->rem;
		if ($status=="idle") {
			if (check_if_client_has_order_waiting($client)) {
				# ... the client seems to be already do something... abort;
				debug("error --- client $client has already an order waiting");
				break;
			}
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
					# -----------------------------------------
					# --------- MAIN RENDER ORDERS  -----------
					# -----------------------------------------

					$render_order="-b \'$blend_path/$scene/$shot.blend\' -o \'$output_path/$scene/$shot/$shot\' -P $config -F $filetype ";
					$info_string="job $id <b>$scene/$shot</b>";

					if (($where_to_start+$number_of_chunks)>$end) {
						#---last chunk of job, its the end, we only need to render frames from CURRENT to END---
						$render_order.=" -s $where_to_start -e $end -a -JOB $id"; 
 						$info_string.=" $where_to_start-$end (last chunk)";
						output("===last chunk=== job $name $file finished soon====");
						send_order($client,"declare_finished","$id","30");
					}
					else {
						#---normal job...we render frames from CURRENT to DO_END
						$render_order.=" -s $where_to_start -e $where_to_end -a -JOB $id"; 
						$info_string.=" $where_to_start-$where_to_end";
					}
					output("job_render for $client :::: $render_order-----------");
					# sending the render order to the client. the render_order contains everything used after the commandline blender -b
					set_info($client,$info_string);
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

	#----every 3600 cycle (about every hour we delete old orders)
	if($cycles_b++==3600){
		check_and_delete_old_orders();
		$cycles_b=0; #reset the cycle_counter;
	}
	#----every 1200 cycle (about every 2 minutes we check if clients are still alive)
	if($num_cycles++==120){
		print ("... checking alive clients :");
		checking_alive_clients();
		check_if_client_should_work();
		$num_cycles=0; #reset the cycle counter
	}
	check_and_execute_server_orders();
	check_and_create_thumbnails();
#----------------------------------end main loop -----------------
}


function check_and_create_thumbnails() {
	# we check if there are some recently rendered frames that have not been thumbnailed. If found some ,then do the thumbnails
	$query="select * from rendered_frames where is_thumbnailed=0";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$job_id=$row->job_id;
		$frame=$row->frame;
		create_thumbnail($job_id,$frame);
		$query="update rendered_frames set is_thumbnailed=1 where id='$id'";
		mysql_query($query);
	}
}
function check_and_delete_old_orders() {
		$max_hours=24;#  number of hours after which the orders get deleted automatically;
		$query="delete from orders WHERE time_format(TIMEDIFF(NOW(),created),'%k') >$max_hours";
		mysql_query($query);
		$affected_rows=mysql_affected_rows();
		print ("... deleting old orders : more than $max_hours hour old (found $affected_rows)...\n");
}
function check_and_execute_server_orders() {
	#------we get and check if there are orders for the server------
	$query="select * from orders where client='server'";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$orders=$row->orders;
		$rem=$row->rem;
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

}

?>
