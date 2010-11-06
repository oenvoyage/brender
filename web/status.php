<?php
$ticker=$_GET['ticker']+1;
#--------------------------------------------------
#-------- THIS PAGE NEED REWRITE-------------------
#--------------------------------------------------

 # print "ticker= $ticker";
if ($ticker>4) {
	$ticker=0;
}
$qwe="/home/o/blender/install/blender_trunk/blender -a /brender/render/test/test/cathe_test00*";
$qwe="/home/o/blender/install/blender_trunk/blender &";
# system($qwe);

print "<meta http-equiv=\"Refresh\" content=\"5;URL=index.php?view=status&ticker=$ticker\" />\n";
#print "<link href=\"css/status.css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "</head><body>";
print "<a href=\"index.php?view=status&server_stop=1\">stop</a>";
$qq=exec('ps');
# print "qq= $qq";

print "<span class=\"clock\">";
	include "tpl/clock.php";
	$qw=0;
	while ($qw++<$ticker){
		print ".";
	}
print "</span><br/>";

order_status();
client_status();
system_status();
pourcent_total();
print "<br/>";
pourcents();
logs();

#------------------ server log-----------------
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
	$query="select name,status,(end-current)/(end-start+1)*100 as pourcent from jobs where status='rendering' or status='pause' or status='waiting';";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)) {
		$pourcent=$row->pourcent;
		$name=$row->name;
		$status=$row->status;
		# print "$name = $pourcent<br/>";
		$longueur_rouge=$pourcent*5;
		$longueur_vert=(100-$pourcent)*5;
		if ($pourcent>0){
			$bgcolor="#bcffa6";
			if ($status=="waiting") {
			          $bgcolor="#777700";
			}
			if ($status=="rendering") {
			        $bgcolor="#002244";
			}
			if ($status=="pause") {
			          $bgcolor="#ffff99";
			}
			print "<table border=0><tr>";
				print "<td width=\"100\" align=\"right\" class=\"pourcent\">$name</td><td width=\"$longueur_vert\" bgcolor=\"#006600\" height=\"10\"></td><td width=\"$longueur_rouge\" bgcolor=\"#660000\"></td>";
			print "</tr></table>";
		}
			
	}

}
function pourcent_total() {
	#  $query="select sum(end-start+1) as total,sum(end-current) as reste, sum(end-current)/sum(end-start+1)*100 as pourcent from jobs where status='rendering' or status='pause' or status='waiting';";
	$query="select sum(end-current)/sum(end-start+1)*100 as pourcent from jobs where status='rendering' or status='pause' or status='waiting';";
	$results=mysql_query($query);
	$pourcent=mysql_result($results,0);
		$longueur_rouge=$pourcent*5;
		$longueur_vert=(100-$pourcent)*5;
		if ($pourcent>0){
			print "<table border=0><tr>";
				print "<td width=\"100\" align=\"right\" class=\"pourcent\">$pourcent</td><td width=\"$longueur_vert\" bgcolor=\"#006600\" height=\"30\"></td><td width=\"$longueur_rouge\" bgcolor=\"#660000\"></td>";
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
		$status=$row->status;
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
	$query="select server,status,pid,started,timediff(now(),started) as uptime from status;";
	$results=mysql_query($query);
	print "<table width=600>";
	print "<tr>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; server &nbsp; </b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>status</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>pid</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>uptime</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>started</b></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$server=$row->server;
		$status=$row->status;
		$started=$row->started;
		$uptime=$row->uptime;
		$pid=$row->pid;
		$bgcolor="#cccccc";
		print "<tr>
			<td bgcolor=$bgcolor align=center>$server</td>
			<td bgcolor=ddddcc align=center>$status</td> 
			<td bgcolor=ddddcc align=center>$pid</td> 
			<td bgcolor=ddddcc align=center>$uptime</td> 
			<td bgcolor=ddddcc align=center>$started</td> 
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
			print "<td class=\"tdclient\"><font color=$bgcolor>$client</font></td>";
		}
		print "</tr>";
		print "</table>";
}
?>
