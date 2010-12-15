<script>
	$(function() {
		$( "#render_configs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible.");
				}
			},
			selected: 0
		});
		$( "button, input:submit, a.btn").button();
		$( "a", ".btn" ).click(function() { return false; });

	});
</script>

<h2>// <strong>server</strong> settings</h2>

<p>Brief server status, theme selector, debug mode, check status and debug mode will be place here. Content will be placed in columns with a description of what each option does.</p>
<div class="settings_container">
	<h3>Server</h3>
	<div class="item">	
		<a class="btn" href="index.php?view=settings&debug=1">switch to debug</a>
		<p>Brief server status, theme selector, debug mode, check status and debug mode will be place here. Content will be placed in columns with a description of what each option does.</p>
	</div>
	<div class="item">
		<a class="btn" href="index.php?view=settings&do_the_test=1">do a test</a> 
		<p>Brief server status, theme selector, debug mode, check status and debug mode will be place here. Content will be placed in columns with a description of what each option does.</p>
	</div>
	<div class="item">
		<a class="btn" href="index.php?view=projects">manage projects</a>
	</div>
	<div class="item">
		<a class="btn" href="index.php?view=settings&check_server_status=1">check server status</a> 
	</div>
	<div class="clear"></div>
	
	<h3>Interface</h3>
	<div class="item">	
		<select>
			<option>brender</option>
			<option>brender dark</option>
			<option>brender mobile</option>
		</select>
	</div>
	<div class="clear"></div>
</div>

<h2>// <strong>render</strong> configurations</h2>

<div id="render_configs">
	<ul>
		<?php		
		$list= `ls ../conf/`;
		$list=preg_split("/\n/",$list);
		foreach ($list as $item) {
			$item=preg_replace("/\.py/","",$item);
			if ($item <> "") {
				print "<li><a href=\"ajax/render_configs.php?edit=$item\">$item</a><li> ";
			}
		}		
		?>
	</ul>
</div>

<?php
#print "sid = $sid <br/>";

if (isset($_GET['do_the_test'])) {
	print "<form>";
	print "<b>doing a test</b><br/>";
	output_scene_selector("gphg");
	#output_shot_selector("gphg","03_animal_ballon");
	print "</form>";
	#output_shot_selector("gphg","03_animal_ballon");
	/*
	$log="2010/05/11 19:36:49 macbook: blenderpath=/Applications/blender/2_48/Blender.app/Contents/MacOS/blender";
	$line =preg_replace('/(\d*)/i','<small>$1</small>',$log);
	print "logline == $line<br/>";
	*/
	
}
if (isset($_GET[debug])) {
	$_SESSION[debug]=!$_SESSION[debug];
}
if (isset($_GET['check_server_status'])) {
	print "checking server status<br/>";
	check_server_status();
}
if (isset($_GET['enable_sound'])) {
	$query="update server_settings set sound='yes'";
	mysql_unbuffered_query($query);
	print "sound enabled<br/>";
}
if (isset($_GET['disable_sound'])) {
	$query="update server_settings set sound='no'";
	mysql_unbuffered_query($query);
	print "sound disabled<br/>";
}
if (isset($_GET['test'])) {
}

system_status();
theme_chooser();

print "<h2>// <strong>session</strong> settings</h2>";
print_r($_SESSION);

#------------------ system status -----------------
function system_status() {
	$query="select server,status,pid,started,timediff(now(),started) as uptime,server_os,sound from server_settings;";
	$results=mysql_query($query);
	print "<table width=600>";
	print "<tr class=\"header_row\">
		<td><b> &nbsp; server &nbsp; </b></td>
		<td><b>status</b></td>
		<td><b>pid</b></td>
		<td><b>uptime</b></td>
		<td><b>machine os</b></td>
		<td><b>started</b></td>
		<td><b>sound</b></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$server=$row->server;
		$status=$row->status;
		$started=$row->started;
		$uptime=$row->uptime;
		$sound=$row->sound;
		$server_os=$row->server_os;
		$pid=$row->pid;
		$bgcolor="#cccccc";
		$status_class=get_css_class($status);
		print "<tr>
			<td bgcolor=$bgcolor align=center>$server</td>
			<td class=$status_class >$status</td> 
			<td>$pid</td> 
			<td>$uptime</td> 
			<td>$server_os</td> 
			<td>$started</td> 
			<td>
				$sound<br/> 
				<a href=\"index.php?view=settings&enable_sound=1\">yes</a>
				<a href=\"index.php?view=settings&disable_sound=1\">no</a>
			</td>
		</tr>";
	}
	print "</table>";
}
# ------------------------- theme chooser --------------------
function theme_chooser() {
	print "<table width=600>";
	print " <tr>
			<td bgcolor=cccccc width=120 align=center colspan=4 height=25><b> &nbsp; theme ($_SESSION[theme]) &nbsp; </b></td>
		</tr>
		<tr>
			<td bgcolor=ddddcc align=center><b> &nbsp; <a href=\"index.php?view=settings&theme=brender\">brender</a> &nbsp; </b></td>
			<td bgcolor=ddddcc align=center><b> &nbsp; <a href=\"index.php?view=settings&theme=brender_dark\">brender_dark</a> &nbsp; </b></td>
			<td bgcolor=ddddcc align=center><b> &nbsp; <a href=\"index.php?view=settings&theme=brender_mobile\">brender_mobile</a> &nbsp; </b></td>
		</tr>
	</table>
	";
}

#--------read---------
if (!$order_by=$_GET[order_by]) {
        $order_by="id";
}

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

