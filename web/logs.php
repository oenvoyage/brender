<?php
print "<h2>// <b>logs</b></h2>";

#--------read---------
	$query="select * from clients order by status";
	$results=mysql_query($query);
	print "<table border=0>";
	print"<tr>";
	print "<td bgcolor=dddddd align=center width=80><a href=\"index.php?view=logs&log=brender\">brender</a></td>";
	print "<td width=80 height=25 align=center bgcolor=dddddd><a href=\"index.php?view=logs&log=server\">$qq<b>server</b></a> </td>";
	while ($row=mysql_fetch_object($results)){
		if ($aa++>10) {
			print "</tr><tr>";
			$aa=0;
		}
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
                        $dis="";
                        $bgcolor="#ffcc99";
                }
		
		if ($client==$_GET[log]) {
			$bgcolor_case="ffffff";
		}
		else {
			$bgcolor_case="dddddd";
		}
		print "<td width=10 height=25 bgcolor=$bgcolor>&nbsp;</td><td width=80 height=25 align=left bgcolor=$bgcolor_case><a href=\"index.php?view=logs&log=$client\">$qq<b>$client</b></a> </td>";
	}
	print "</tr></table>";
print "<p><hr><p>";
if ($log=$_GET[log]){ 
	if ($_GET[max]) {
		$_max=$_GET[max];
	}
	else {
		$_max=100;
	}
	print "<b>$log log</b><br/>";
	#check_if_client_should_work($log);
	print "<a href=\"index.php?view=logs&log=$log&max=400\">400 lines</a><br/>";
	$lok = file("../logs/$log.log");
	$lok=array_reverse($lok);
	foreach ($lok as $line){
		if ($a++>$_max ) {
			break;
		}
		print "$line<br/>";
	}
	
}
print "<p><p>";
?>
