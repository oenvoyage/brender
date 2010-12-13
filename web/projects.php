<?php
if ($projectid=$_GET[del]) {
	$queryqq="delete from projects where id=$projectid;";
	mysql_query($queryqq);
	# sleep(1);
}
if ($deactivate=$_GET[deactivate]) {
	$queryqq="update projects set status='inactive' where id=$deactivate;";
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
if (isset($_GET[new_project])) {
	$new_project=clean_name($_GET[new_project]);
	if (check_project_exists($new_project)) {
		print "<span class=error>project $project already exists, please choose other name</span>";
	}
	else {
		print "new project $new_project created<br/>";
		$queryqq="insert into projects values ('','$new_project','$_GET[blend_mac]','$_GET[blend_linux]','$_GET[blend_win]','$_GET[output_mac]','$_GET[output_win]','$_GET[output_linux]','$_GET[rem]','active','');";
		mysql_query($queryqq);
	}
}
if (!$order_by=$_GET[order_by]) {
        $order_by="id";
}



#--------read---------
	$query="select * from projects order by $order_by";
	$results=mysql_query($query);
	print "<h2>// <b>projects</b></h2>\n";
	print "<table>\n";
	print "<tbody>\n";
	print "<tr class=\"header_row\">
		<td></td>
		<td> default </td>
		<td> <a href=\"index.php?view=projects&order_by=name\">project</a></td>
		<td> .blend path</td>
		<td> output path</td>
		<td> <a href=\"index.php?view=projects&order_by=rem\">rem</a></td>
		<td> </td>
		<td>&nbsp;</td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$name=$row->name;
		$rem=$row->rem;
		$blend_mac=$row->blend_mac;
		$blend_win=$row->blend_win;
		$blend_linux=$row->blend_linux;
		$output_mac=$row->output_mac;
		$output_win=$row->output_win;
		$output_linux=$row->output_linux;
		$status=get_css_class($row->status);
		if ($status=="active") {
			$status_link='<a href="index.php?view=projects&deactivate=' . $id.'">active</a>';
		}
		else {
			$status_link='<a href="index.php?view=projects&activate=' . $id.'">inactive</a>';
		}
		$def=$row->def;
		$bgcolor="ddddcc";
		if ($def==1) {
			$is_default="<img src=\"images/icons/close.png\">";
		}
		else {
			$is_default="";
		}
		print "<tr class=\"$status\">
			<td>$id</td> 
			<td>$is_default</td> 
			<td><a href=\"index.php?view=projects&def=$id\">$name</a></td> 
			<td>mac: $blend_mac <br/>win: $blend_win<br/>linux: $blend_linux</td> 
			<td>mac: $output_mac <br/>win: $output_win <br/>linux: $output_linux</td> 
			<td>$rem</td> 
			<td>$status_link</td> 
			<td>&nbsp;<a href=\"index.php?view=projects&del=$id\"><img src=\"images/icons/close.png\"></a></td>
		</tr>";
	}

?>

	</tbody>
</table>

<h2>// <strong>create new</strong> project</h2>

<form action="index.php" method="get">
<table>
	<tbody>
		<tr>
			<td>project title</td>
			<td><input type="text" name="new_project" value="new project" size=18></td>
		</tr>
		<tr>
			<td>remarks</td>
			<td><input type="text" name="rem" size=38></td>
		</tr>
		<tr>
			<td colspan=2><b>.blend files path</b></td>
		</tr>
		<tr>
			<td>blend_mac</td>
			<td><input type="text" name="blend_mac" value="blend/" size=15></td>
		</tr>
		<tr>
			<td>blend_linux</td>
			<td><input type="text" name="blend_linux" value="blend/" size=15></td>
		</tr>
		<tr>
			<td>blend_windows</td>		
			<td><input type="text" name="blend_win" value="\\blend" size=15></td>	
		</tr>
		<tr>
			<td colspan=2><b>output path</b></td>
		</tr>
		<tr>
			<td>output_mac</td>
			<td><input type="text" name="output_mac" value="render" size=15></td>
		</tr>
		<tr>
			<td>output_linux</td>
			<td><input type="text" name="output_linux" value="render" size=15></td>
		</tr>
		<tr>
			<td>output_windows</td>		
			<td><input type="text" name="output_win" value="\\render" size=15></td>	
		</tr>
		<tr>
			<td><input type="hidden" value="projects" name="view"></td>
			<td><input type="submit" value="create"></td>
		</tr>

	</tbody>
</table>
</form>
<a href="index.php">back</a>


