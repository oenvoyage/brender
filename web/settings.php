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
	});
</script>

<h2>// <strong>server</strong> settings</h2>

<p>Brief server status, theme selector, debug mode, check status and debug mode will be place here. Content will be placed in columns with a description of what each option does.</p>

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
	print "doing a test<br/>";

	$log="2010/05/11 19:36:49 macbook: blenderpath=/Applications/blender/2_48/Blender.app/Contents/MacOS/blender";
	$line =preg_replace('/(\d*)/i','<small>$1</small>',$log);
	print "logline == $line<br/>";
	
	/*
	add_rendered_frame(145,2);
	show_last_rendered_frame();
	create_thumbnail(139,3);
	$rem="blender/mac/blender.app/Contents/MacOS/blender -b '/Volumes/rgb_noel/01_3D/SCENES/99_tests/brender_test.blend' -o '/Volumes/rgb_noel/01_3D/RENDER/99_tests/brender_test/brender_test' -P conf/pal_widescreen.py -F PNG  -s 25 -e 26 -a JOB 143";
	$rem="-b '/Volumes/rgb_noel/01_3D/SCENES/99_tests/rainbow.blend' -o '/Volumes/rgb_noel/01_3D/RENDER/99_tests/rainbow/rainbow' -P conf/pal_widescreen.py -F PNG -s 9 -e 10 -a -JOB 152";
	$parsed=parse_render_command($rem);
	print "job_id = ".$parsed["job_id"];
	$parsed_rem=array();

	preg_match("/(.*)\-s (.\d) \-e (.\d)\ -a JOB (\d*)/",$rem,$preg_matches);
	$start=$preg_matches[3];
	$end=$preg_matches[2];
	$job_id=$preg_matches[4];
	$parsed_rem["start"]=$preg_matches[3];
	$parsed_rem["job_id"]=$preg_matches[4];
	#$job_id=$preg_matches[2];
	print "JOB ID = $job_id start=".$parsed_rem["start"]." end=$end<br/>";
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
	$query="update status set sound='yes'";
	mysql_unbuffered_query($query);
	print "sound enabled<br/>";
}
if (isset($_GET['disable_sound'])) {
	$query="update status set sound='no'";
	mysql_unbuffered_query($query);
	print "sound disabled<br/>";
}
if (isset($_GET['test'])) {
}

system_status();
theme_chooser();
print "<br/>";
print "<a class=\"grey\" href=\"index.php?view=settings&debug=1\">switch debug</a> <br/>";
print "<a class=\"grey\" href=\"index.php?view=settings&do_the_test=1\">do a test</a> <br/>";
print "<a class=\"button grey\" href=\"index.php?view=projects\">manage projects</a> ";
print "<br/>";
print "<br/>";
print "<a class=\"button grey\" href=\"index.php?view=settings&check_server_status=1\">check server status</a> ";
print "<h2>// <strong>session</strong> settings</h2>";
print_r($_SESSION);

#------------------ system status -----------------
function system_status() {
	$query="select server,status,pid,started,timediff(now(),started) as uptime,sound from status;";
	$results=mysql_query($query);
	print "<table width=600>";
	print "<tr>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; server &nbsp; </b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>status</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>pid</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>uptime</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>started</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>sound</b></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$server=$row->server;
		$status=$row->status;
		$started=$row->started;
		$uptime=$row->uptime;
		$sound=$row->sound;
		$pid=$row->pid;
		$bgcolor="#cccccc";
		print "<tr>
			<td bgcolor=$bgcolor align=center>$server</td>
			<td bgcolor=ddddcc align=center>$status</td> 
			<td bgcolor=ddddcc align=center>$pid</td> 
			<td bgcolor=ddddcc align=center>$uptime</td> 
			<td bgcolor=ddddcc align=center>$started</td> 
			<td bgcolor=ddddcc align=center>
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
		</tr>
	</table>
	";
}
?>
