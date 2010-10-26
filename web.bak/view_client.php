<?php
require "../connect.php";	
require "../functions.php";	

#--------read---------
	$query="select * from clients";
	$results=mysql_query($query);
	print "<br>// <b>brender clients</b> <br/>";
	print "<table>";
	print "<tr>
		<td bgcolor=cccccc width=120 align=center>client name</td>
		<td bgcolor=cccccc width=120 align=center> &nbsp; status &nbsp; </td>
		<td bgcolor=cccccc width=500 align=center> &nbsp; rem &nbsp; </td>
		<td bgcolor=cccccc width=120 align=center> &nbsp; &nbsp; </td>
		<td bgcolor=cccccc align=center></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$status=$row->status;
		$rem=$row->rem;
		if ($status<>"disabled") {
			$dis="<a href=\"clients.php?disable=$client\">disable</a>";
			$bgcolor="#bcffa6";
		}
		if ($status=="disabled") {
			$dis="<a href=\"clients.php?enable=$client\">enable</a>";
			$bgcolor="#ffcc99";
		}
		if ($status=="rendering") {
			$bgcolor="#99ccff";
		}
		if ($status=="not running") {
			$dis="";
			$bgcolor="#ffcc99";
		}
		print "<tr>
			<td bgcolor=ddddcc align=center>$client</td> 
			<td bgcolor=$bgcolor align=center>$status</td>
			<td bgcolor=$bgcolor align=center>$rem</td>
			<td bgcolor=$bgcolor align=center>$dis</td>
			<td bgcolor=$bgcolor align=center><a href=\"clients.php?stop=$client\">x</a></td>
		</tr>";
	}
	print "</table>";
print "<p><hr><p>";
?>
