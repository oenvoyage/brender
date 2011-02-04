<?php
#--------------------------------------------------
#-------- THIS PAGE NEED REWRITE-------------------
#--------------------------------------------------

 # print "ticker= $ticker";

print "<meta http-equiv=\"Refresh\" content=\"5;URL=index.php?view=status\" />\n";
#print "<a href=\"index.php?view=status&server_stop=1\">stop</a>";
$qq=exec('ps');
# print "qq= $qq";

print "<span class=\"clock\">";
	include "tpl/clock.php";
print "</span><br/>";

#decimal_time();

order_status();
client_status();
system_status();
pourcent_total();
print "<br/>";
#pourcents();
logs();

#------------------ server log-----------------
function decimal_time() {
	$full=date('Y-m-d H:i:s');
	$hour=date('H');
	$min=date('i');
	$hour_dec=round(10/(24/$hour),0,PHP_ROUND_HALF_DOWN);
	$min_dec=round(100/(60/$min),0,PHP_ROUND_HALF_DOWN);
	print "full time : $full<br/>";
	print "decimal time : $hour_dec h $min_dec min<br/>";
}
function logs() {
       $lok = file("../logs/brender.log");
        $lok=array_reverse($lok);
	$a=0;
       foreach ($lok as $line){
                if ($a++>10 ) {
                        break;
                }
	       print "<span class=\"log\">$line</span><br/>";
       }
} 
#--------------barre pourcents ---------
function pourcents() {
	#$query="select shot,status,(end-current)/(end-start+1)*100 as pourcent from jobs where status='rendering' or status='pause' or status='waiting';";
	$query="select shot,status,start,end,current from jobs where status='rendering' or status='pause' or status='waiting';";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)) {
		$name=$row->shot;
		$status=$row->status;
		$start=$row->start;
		$end=$row->end;
		$current=$row->current;
		#print "$name = $start $end $current<br/>";
		print "<span class=\"progress-bar large\"> $name :: ".output_progress_bar($start,$end,$current)."</span>";
			
	}

}
function pourcent_total() {
	#  $query="select sum(end-start+1) as total,sum(end-current) as reste, sum(end-current)/sum(end-start+1)*100 as pourcent from jobs where status='rendering' or status='pause' or status='waiting';";
	$query="select sum(end-current)/sum(end-start+1)*100 as percent from jobs where status='rendering' or status='pause' or status='waiting';";
	$results=mysql_query($query);
	$percent=mysql_result($results,0);
	$percent_done=100-$percent;
		$length_red=$percent*5;
		$length_green=(100-$percent)*5;
		if ($percent>0){
			print "<table border=0><tr>";
				#print "<td width=\"100\" align=\"right\" class=\"pourcent\">$pourcent</td><td width=\"$longueur_vert\" bgcolor=\"#006600\" height=\"30\"></td><td width=\"$longueur_rouge\" bgcolor=\"#660000\"></td>";
				print "<img class=\"progress_bar big\"  style=\"width:".$length_green."px;\" src=\"images/cube_green.png\">";
				print "<img class=\"progress_bar big\"  style=\"width:".$length_red."px;\" src=\"images/cube_red.png\">";
				print ":  $percent_done%";
			print "</tr></table>";
			# print "pourcent = $pourcent";
		}
		else {
			print "<table border=0><tr>";
				print "<td width=\"700\" bgcolor=\"#9999aa\" height=\"20\"><font color=\"#00000\" size=\"16px\">WAITING...</font></td>";
			print "</tr></table>";
		}
			

	$query="select name,(end-current)/(end-start+1)*100 as pourcent from jobs where status='rendering' or status='pause' or status='waiting';";
}

#--------------order status ---------
function order_status() {
	$query="select * from orders order by orders";
	$results=mysql_query($query);
	print "<table border=0><tr>";
	while ($row=mysql_fetch_object($results)){
                $client=$row->client;
                $priority=$row->priority;
                $id=$row->id;
                $rem=$row->rem;
	        $orders=$row->orders;
		$tdstyle="none";
		$text="";
		if ($orders=="render") {
		   	$tdstyle="render";
		}
		else if($orders=="disable" or $orders=="enable") {
		 	$text="($client $orders)";
		    	$tdstyle="disable";
		} 
		else if($orders=="ping") {
			$text=$client;
		    	$tdstyle="ping";
		} 
		print "<td class=\"$tdstyle\" width=\"50\">$text</td>";
	}
	print "</tr></table>";
}

#---------------system status ---------
function system_status() {
	$query="select server,status,pid,started,server_os,timediff(now(),started) as uptime from server_settings;";
	$results=mysql_query($query);
	print "<table width=600>";
	print "<tr>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; server &nbsp; </b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>status</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>pid</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>uptime</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>started</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>server os</b></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$server=$row->server;
		$status=$row->status;
		$started=$row->started;
		$server_os=$row->server_os;
		$uptime=$row->uptime;
		$pid=$row->pid;
		$bgcolor="#cccccc";
		print "<tr>
			<td bgcolor=$bgcolor align=center>$server</td>
			<td bgcolor=ddddcc align=center>$status</td> 
			<td bgcolor=ddddcc align=center>$pid</td> 
			<td bgcolor=ddddcc align=center>$uptime</td> 
			<td bgcolor=ddddcc align=center>$started</td> 
			<td bgcolor=ddddcc align=center>$server_os</td> 
		</tr>";
	}
	print "</table>";
}
#------------------- client status----------
function client_status() {
	$query="select * from clients order by status";
	        $results=mysql_query($query);
		print "<table border=0>";
		print"<tr>";
		while ($row=mysql_fetch_object($results)){
			$client=$row->client;
			$status=$row->status;
			if ($status<>"disabled") {
			   $bgcolor="#bcffa6";
			}
			if ($status=="disabled") {
			    $bgcolor="#ffaa99";
			}	
			if ($status=="rendering") {
			    $bgcolor="#99ccff";
			}
			if ($status=="not running") {
			      $bgcolor="#ffcc99";
			}
			#print "<td class=\"client $status\"><font color=$bgcolor>$client</font></td>";
			print "<td class=\"client $status\">$client</td>";
		}
		print "</tr>";
		print "</table>";
}
?>
