<?php
require "connect.php";
print "<link href=\"brender.css\" rel=\"stylesheet\" type=\"text/css\">\n";
if ($projectid=$_GET[del]) {
	$queryqq="delete from projects where id=$projectid;";
	mysql_query($queryqq);
	# sleep(1);
}
if ($deactivate=$_GET[deactivate]) {
	$queryqq="update projects set status='finished' where id=$deactivate;";
	mysql_query($queryqq);
}
if ($activate=$_GET[activate]) {
	$queryqq="update projects set status='active' where id=$activate;";
	mysql_query($queryqq);
}
if ($def=$_GET[def]) {
	$queryqq="update projects set def=0";
	mysql_query($queryqq);
	$queryqq="update projects set def=1 where id=$def;";
	mysql_query($queryqq);
	print "default project = $def";
	# sleep(1);
}
if ($new_project=$_GET[new_project]) {
	print "new project $new_project created<br/>";
	$queryqq="insert into projects values ('','$new_project','$_GET[mac_path]','$_GET[win_path]','$_GET[rem]','active','');";
	mysql_query($queryqq);
}
if (!$order_by=$_GET[order_by]) {
        $order_by="id";
}



#--------read---------
	$query="select * from projects order by $order_by";
	$results=mysql_query($query);
	print "<br>// <b>projects</b> <br/>\n";
	print "<table>\n";
	print "<tr>
		<td bgcolor=cccccc width=12 align=center></td>
		<td bgcolor=cccccc width=2 align=center> &nbsp; </td>
		<td bgcolor=cccccc width=10 align=center> &nbsp; <a href=\"projects.php?order_by=name\">project</a></td>
		<td bgcolor=cccccc width=32 align=center> &nbsp; mac path</td>
		<td bgcolor=cccccc width=32 align=center> &nbsp; windows path</td>
		<td bgcolor=cccccc width=32 align=center> &nbsp; <a href=\"projects.php?order_by=rem\">rem</a></td>
		<td bgcolor=cccccc width=10 align=center> status </td>
		<td bgcolor=cccccc width=10 align=center> &nbsp; </td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$name=$row->name;
		$rem=$row->rem;
		$mac_path=$row->mac_path;
		$win_path=$row->win_path;
		$status=$row->status;
		if ($status=="active") {
			$status_link='<a href="projects.php?deactivate=' . $id.'">active</a>';
		}
		else {
			$status_link='<a href="projects.php?activate=' . $id.'">finished</a>';
		}
		$def=$row->def;
		$bgcolor="ddddcc";
		if ($def==1) {
			$bgcolor="ccdddd";
		}
		print "<tr>
			<td bgcolor=$bgcolor align=center>$id</td> 
			<td bgcolor=$bgcolor align=center>$def</td> 
			<td bgcolor=$bgcolor align=center><a href=\"projects.php?def=$id\">$name</a></td> 
			<td bgcolor=$bgcolor align=center>$mac_path</td> 
			<td bgcolor=$bgcolor align=center>$win_path</td> 
			<td bgcolor=$bgcolor align=center>$rem</td> 
			<td bgcolor=$bgcolor align=center>$status_link</td> 
			<td bgcolor=dddddd align=center><a href=\"projects.php?del=$id\">x</a></td>
		</tr>";
	}
	print "\n</table>\n";
	print "<hr>";
	print "<form action=\"projects.php\" method=\"get\">";
		print "new project : ";
		print "<input type=\"text\" name=\"new_project\" size=8>";
		print " rem : ";
		print "<input type=\"text\" name=\"rem\" size=18><br/>";
		print "<input type=\"text\" name=\"mac_path\" size=128>";
		print "<input type=\"text\" name=\"win_path\" size=128>";
		print "<input type=\"submit\" value=\"create\">";
	print "</form>";
	print "<hr>";
	print "\n<a href=\"overview.php?overview=1\">back</a>\n";
?>
