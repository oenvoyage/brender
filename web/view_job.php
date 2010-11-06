<?php
if ($_GET[bgcolor]=="black") {
	$option_couleur= "white";
	$bgcolor= "black";
}
else {
	$option_couleur= "black";
	$bgcolor= "white";
	
}
if ($_GET[visual]=="1") {
	$visual=1;
}
#--------read---------
	$id=$_GET[id];
	$query="select * from jobs where id='$id'";
	$results=mysql_query($query);
	$row=mysql_fetch_object($results);
		$project=$row->project;
		$id=$row->id;
		$scene=$row->scene;
		$jobtype=$row->jobtype;
		$file=$row->file;
		$config=$row->config;
		$output=$row->output;
		$start=$row->start;
		$end=$row->end;
		$current=$row->current;
		$chunks=$row->chunks;
		$rem=$row->rem;
		$filetype=$row->filetype;
		$status=$row->status;
		$priority=$row->priority;
		$total=$end-$start;
	#-------------------
	print "<h2>// job $id : <b>$scene</b></h2> // $rem (project <b>$project</b>)<br/>";
	print "<table border=0>";
	print "<tr>";
	print "<td bgcolor=\"#bbbbbb\" colspan=2>";
	
	print "</td></tr>";
	print "<tr><td width=200>";
		print "<a href=\"index.php\">back to overview</a><br/>";
		print "<a href=\"index.php?view=jobs\">jobs</a><br/>";
		print "<a href=\"view_job.php?id=$id&bgcolor=$option_couleur&visual=$visual\">$option_couleur</a><br/>";
		print "$total frames ($start-$end by $chunks)<br/>";
		$total_rendered=get_rendered_frames($id);
		print "$total_rendered rendered frames<br/>";
		print "file $file <br/>";
	print "</td>";
	print "<td>";
	#------------------------------ option update job -----------------------
		if ($filetype=="tga"){
			$select_tga="selected";
		}
		else if ($filetype=="png"){
			$select_png="selected";
		}
		if ($config=="preview"){
			$select_preview="selected";
		}
		else if ($config=="1k"){
			$select_1k="selected";
		}
		else if ($config=="2k"){
			$select_2k="selected";
		}
		print "<form action=\"overview.php\" method=\"post\">";
                	print "type <select name=\"filetype\">
                       		 	<option value=\"jpg\">jpg</option>
                       		 	<option value=\"png\" $select_png>png</option>
					<option value=\"tga\" $select_tga>tga</option>
                		</select>
				config
        			<select name=\"config\">
                        		<option $select_preview value=\"preview\">preview</option>
                        		<option $select_1k value=\"1k\">1k</option>
                        		<option $select_1k value=\"2k\">2k</option>
                		</select>";	
        		print "start:<input type=text name=start size=4 value=$start>";
        		print "end:<input type=text name=end size=4 value=$end>";
        		print "chunks:<input type=text name=chunks size=3 value=$chunks>";
	       		print "priority (1-99):<input type=text name=priority size=3 value=$priority>";
			print "directstart:<input type=checkbox name=directstart value=yes>";
        		print "<input type=hidden name=updateid value=$id>";
        		print "<input type=hidden name=nom value=$name>";
        		print "<input type=hidden name=jobtype value=$jobtype>";
        		print "<input type=hidden name=file value=$file>";
        		print "<input type=hidden name=output value=$output>";
        		print "<input type=hidden name=project value=$project>";
        		print "<input type=submit name=copy value=\"send job\"><br/>";
        		print "<input type=submit name=copy value=\"copy job\"><br/>";
		print "</form>";
	print "</td>";
	print "<table border=0>";
	print "<tr>";
	#-------------------------------les images ------------------------------
	$first_image=$output.str_pad($start,4,0,STR_PAD_LEFT).".".$filetype;
	$a=$start;

	if ($visual) {
		$img_chunks=round(($total)/20);
		# print "a= $a --- start $start -- end $end -- totalframes $total img_chunks =$img_chunks </br>";
		print "<td><a href=\"view_image.php?id=$id&name=$name&image=$first_image&bgcolor=$bgcolor&project=$project\"><img src=\"/Production/renders/$first_image\" width=\"200\"></a><br/>$first_image<br/></td>";
		$rows=1;
	while ($a++<($total+$start)){
		$b++;
		# print " a= $a ---- b=$b/$img_chunks <br/>";
		if ($b==$img_chunks) {
			#  print "je met image $a <br/>";
			if ($_GET[renderpreview]) {
					$render_order="-b \'/brender/blend/$file\' -o \'/brender/render/$project/$name/$output\' -P conf/$config.py -F JPEG -f $a";
                                        # ---------------------------------
                                        print "job_render for $client :\n $render_order\n-----------\n";
                                        send_order("any","render",$render_order,"20");
			}

			$image=$output.str_pad($a,4,0,STR_PAD_LEFT).".".$filetype;
			print "<td bgcolor=\"$tdcolor\">";
				print "<a href=\"view_image.php?id=$id&name=$name&image=$image&bgcolor=$bgcolor&project=$project\"><img src=\"/Production/renders/$image\" border=0 width=\"200\"></a><br/>$image<br/>";
			print "</td>";
			$b=0;
			#  print "row = $rows";
			if ($rows++>3) {
				$rows=0;
				print "</tr><tr>";
			}
		}
	}
	print "</tr></table><br>";
	print "<a href=\"view_job.php?id=$id&bgcolor=$bgcolor&visual=1&renderpreview=1\">render preview</>";
	print "<hr>";
	}
	#-------------------------------------------------------------------
	if (!$visual) {
		print "<a href=\"view_job.php?id=$id&visual=1&bgcolor=$bgcolor\"><img src=\"/Production/renders/$first_image\" width=\"200\" border=1></a><br/><hr>";
	}
#--------read---------
	print "<a href=\"jobs.php\">back to job list</a>";
?>
