<?php
#--------read---------
	$id=$_GET[id];
	$query="select * from jobs where id='$id'";
	$results=mysql_query($query);
	$row=mysql_fetch_object($results);
		$project=$row->project;
		$id=$row->id;
		$scene=$row->scene;
		$shot=$row->shot;
		$jobtype=$row->jobtype;
		$config=$row->config;
		$output=$row->output;
		$start=$row->start;
		$end=$row->end;
		$current=$row->current;
		$chunks=$row->chunks;
		$rem=$row->rem;
		$filetype=$row->filetype;
		$progress_status=$row->progress_status;
		$progress_remark=$row->progress_remark;
		$last_edited_by=$row->last_edited_by;
		$lastseen=$row->lastseen;
		$status=$row->status;
		$priority=$row->priority;
		$total=$end-$start;
	#-------------------
	print "<h2>// job $id : $scene/<b>$shot</b> </h2>";
	?>
	<script>
	$(function() {
		$("button.switchbg").button({
            icons: {
                primary: "ui-icon-gear"
            }
        });
        
        $("button.switchbg").click(function() {
			$(".over").toggleClass("brender-overlay", 100);
			return false;
		});
	});
	
	</script>
		

	<?php
	print "<table border=0>";
	print "<tr>";
	print "<td bgcolor=\"#bbbbbb\" colspan=2>";
	
	print "</td></tr>";
	print "<tr><td width=200>";
		#print "<a class=\"button grey\" href=\"index.php\">back to overview</a><br/>";
		print "&nbsp;<br/>";
		print "<a class=\"button grey\" href=\"index.php?view=jobs\">back to jobs</a><br/>";
		print "<a href=\"index.php?view=view_job&id=$id&bgcolor=$option_couleur\">$option_couleur</a><br/>";
		print "project : $project<br/>";
		print "$total frames ($start-$end by $chunks)<br/>";
		$total_rendered=count_rendered_frames($id);
		print "$total_rendered rendered frames<br/>";
		print "last changes made by  :: $last_edited_by<br/> $lastseen <br/>";
		print "&nbsp;<br/>";
	print "</td>";
	print "<td>";
	#------------------------------ option update job -----------------------
		if ($filetype=="TGA"){
			$select_tga="selected";
		}
		else if ($filetype=="OPEN_EXR"){
			$select_exr="selected";
		}
		else if ($filetype=="PNG"){
			$select_png="selected";
		}
		print "<form action=\"index.php\" method=\"post\">";
                	print "type <select name=\"filetype\">
                       		 	<option value=\"JPEG\">JPEG</option>
                       		 	<option value=\"PNG\" $select_png>PNG</option>
					<option value=\"TGA\" $select_tga>TGA</option>
					<option value=\"OPEN_EXR\" $select_exr>OPEN_EXR</option>
                		</select>
				config
        			<select name=\"config\"> ";
				output_config_select($config);
			#print "DDDD";
				print " </select><br/>";	

        			print "<br/>progress status<br/> <select name=\"progress_status\"> ";
				output_progress_status_select($progress_status);
				print " </select> rem <input name=\"progress_remark\" type=\"text\" value=\"$progress_remark\"><br/><br/>";	

        		print "start:<input type=text name=start size=4 value=$start>";
        		print "end:<input type=text name=end size=4 value=$end>";
        		print "chunks:<input type=text name=chunks size=3 value=$chunks>";
	       		print "priority (1-99):<input type=text name=priority size=3 value=$priority><br/><br/>";
				print "directstart:<input type=checkbox name=directstart value=yes><br/>";
        		print "<input type=hidden name=updateid value=$id>";
        		print "<input type=hidden name=scene value=$scene>";
        		print "<input type=hidden name=shot value=$shot>";
        		print "<input type=hidden name=view value=jobs>";
        		print "<input type=hidden name=jobtype value=$jobtype>";
        		print "<input type=hidden name=project value=$project>";
        		print "<input type=submit name=copy value=\"update job\"> or ";
        		print "<input type=submit name=copy value=\"copy job\"><br/>";
		print "</form>";
	print "</td>";
	print "</table>";
	print "<table border=0 class=\"thumbnails_table\">";
	print "<tr>";
	#-------------------------------les images ------------------------------
	$a=$start;
	$first_image=get_thumbnail_image($id,$start);

	$img_chunks=round(($total)/20);
	# print "a= $a --- start $start -- end $end -- totalframes $total img_chunks =$img_chunks </br>";
	print "<td><a href=\"index.php?view=view_image&job_id=$id&frame=$a\">$first_image</a><br/>$a<br/></td>";
	$rows=1;
	while ($a++<($total+$start)){
		$b++;
		# print " a= $a ---- b=$b/$img_chunks <br/>";
		if ($b==$img_chunks) {
			#  print "je met image $a <br/>";
			/*if ($_GET[renderpreview]) {
					$render_order="-b \'/brender/blend/$file\' -o \'/brender/render/$project/$name/$output\' -P conf/$config.py -F JPEG -f $a";
                                        # ---------------------------------
                                        print "job_render for $client :\n $render_order\n-----------\n";
                                        #send_order("any","render",$render_order,"20");
			}
			*/
                        #$thumbnail_image="../thumbnails/$project/$scene/$shot/small_$shot".str_pad($a,4,0,STR_PAD_LEFT).".$ext";
			$thumbnail_image=get_thumbnail_image($id,$a);

			print "<td bgcolor=\"$tdcolor\">";
				print "<a href=\"index.php?view=view_image&job_id=$id&frame=$a\">$thumbnail_image</a><p>$a</p>";
			print "</td>";
			$b=0;
			#  print "row = $rows";
			if ($rows++>3) {
				$rows=0;
				print "</tr><tr>";
			}
		}
	}
	print "</tr></table>";
	#print "<a href=\"index.php?view=view_job&id=$id&bgcolor=$bgcolor&visual=1&renderpreview=1\">render preview</>";
#--------read---------
?>
<div class="table-controls">
	<a class="btn" href="index.php?view=jobs">back to job list</a>
	<button class="switchbg btn">dark background</button>
	<a class="btn" href="#">duplicate job</a>
</div>
<div class="over"></div>
