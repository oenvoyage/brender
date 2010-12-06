<script>
	$(function() {
		$( "#tabs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. " +
						"If this wouldn't be a demo." );
				}
			},
			selected: 1
		});
	});
</script>



<h2>// <b>logs</b></h2>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Preloaded</a></li>
		<li><a href="ajax/logs.php?log=server">server</a></li>
		<li><a href="ajax/logs.php?log=brender">brender</a></li>
	</ul>
	<div id="tabs-1">
		<p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
	</div>
</div>


<?php

#--------read---------
	$query="select * from clients order by status";
	$results=mysql_query($query);
	print "<table border=0>";
	print "<tr>";
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
/*
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
*/
?>
