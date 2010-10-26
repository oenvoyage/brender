<?php
session_start();
require "connect.php";	
require "../functions.php";	
print "<link href=\"$_SESSION[theme].css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "<body bgcolor=\"#eeeeee\">";
print "<br>// <b>logs</b> <br/><br/>";

#--------read---------
	$query="select * from clients order by status";
	$results=mysql_query($query);
	print "<table border=0>";
	print"<tr>";
	print "<td bgcolor=dddddd align=center width=80><a href=\"logs.php?log=brender\">brender</a></td>";
	print "<td width=80 height=25 align=center bgcolor=dddddd><a href=\"logs.php?log=server\">$qq<b>server</b></a> </td>";
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
		print "<td width=10 height=25 bgcolor=$bgcolor>&nbsp;</td><td width=80 height=25 align=left bgcolor=$bgcolor_case><a href=\"logs.php?log=$client\">$qq<b>$client</b></a> </td>";
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
	print "<a href=\"logs.php?log=$log&max=400\">400 lines</a><br/>";
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
