<script>
	$(function() {
		$("button.switchbg_btn").button({
	        icons: {
	            primary: "ui-icon-gear"
	        }
	    });

	    $("button.switchbg_btn, button.switchbg_btn2").click(function() {
			$(".over").toggleClass("brender-overlay", 100);
			return false;
		});
		
		// EDIT JOB dialog START
			//$('input#edit_directstart').attr('checked', true);
		var updateid = $('input#updateid'),
			project = $('input#edit_project'),
			scene = $('input#edit_scene'),
			shot = $('input#edit_shot'),
			filetype = $('select#edit_filetype'),
			config = $('select#edit_config'),
			progress_status = $('select#progress_status'),
			progress_remark = $('input#edit_progress_remark'),
			start = $('input#edit_start'),
			end = $('input#edit_end'),
			chunks = $('input#edit_chunks'),
			priority = $('input#edit_priority'),
			rem = $('input#edit_rem')
			
		$("#edit_job").dialog({
			autoOpen: false,
			height: 400,
			width: 450,
			modal: true,
			resizable: false,
			buttons: {
				Cancel: function() {
					$(this).dialog("close");
				},
				"Duplicate job": function() { 							
						
						$.post("ajax/view_job.php", {
							updateid: updateid.val(),
							action: 'duplicate',
							project: project.val(), 
							scene: scene.val(), 
							shot: shot.val(), 
							filetype: filetype.val(), 
							progress_status: progress_status.val(), 
							progress_remark: progress_remark.val(), 
							config: config.val(), 
							start: start.val(), 
							end: end.val(), 
							chunks: chunks.val(), 
							priority: priority.val(), 
							rem: rem.val(), 
							directstart: $('#edit_directstart').is(':checked') 
						}, function(data) {
							//var obj = jQuery.parseJSON(data);
							//alert(data);
							if(data.status == true) {
								$("#edit_job").dialog("close" );
								//alert(obj.query);
								window.location= 'index.php?view=jobs';
							} else {
								alert(data.msg);
							}
						}, "Json");				
						return false;					
				},
				"Update job": function() { 												
						$.post("ajax/view_job.php", {
							updateid: updateid.val(),
							action: 'update',
							project: project.val(), 
							scene: scene.val(), 
							shot: shot.val(), 
							filetype: filetype.val(), 
							progress_status: progress_status.val(), 
							progress_remark: progress_remark.val(), 
							config: config.val(), 
							start: start.val(), 
							end: end.val(), 
							chunks: chunks.val(), 
							priority: priority.val(), 
							rem: rem.val(), 
							directstart: $('#edit_directstart').is(':checked') 
						}, function(data) {
							//var obj = jQuery.parseJSON(data);
							//alert(data);
							if(data.status == true) {
								$("#edit_job").dialog("close" );
								alert(data.msg);
								window.location= 'index.php?view=jobs';
							} else {
								alert(data.msg);
							}
						}, "Json");				
						return false;					
				}
			},
			close: function() {
				//allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
		$("#edit_job_button, #edit_job_button2").click(function() {
			$("#edit_job").dialog("open");
		});
		// EDIT JOB dialog END
		
		// BACKGROUND SWITCH START
		
		//Get focus of an input	
		var focused = false;
		$('input').focus(function() {
			focused = true;
		});
		$('input').focusout(function() {
			focused = false;
		}); 
		
		$(document).keyup(function(e) {
			if (e.keyCode == 27 && !focused) {			// esc (only if overlay is on)
				if ($('div').hasClass('brender-overlay')) {
					$(".over").toggleClass("brender-overlay", 100);
				} 
				else {				// else we close the job_view and go to jobs.php
					window.location.href = 'index.php?view=jobs';
				}
			}
		
			if (e.keyCode == 66 && !focused) {			// b
				$(".over").toggleClass("brender-overlay", 100);
			}    
		});
		// BACKGROUND SWITCH END
	});
	
	
</script>

<?php
#--------read---------
	$id = $_GET['id'];
	$query = "SELECT * FROM jobs WHERE id='$id'";
	$results = mysql_query($query);
	$row = mysql_fetch_object($results);
		$project = $row->project;
		$id = $row->id;
		$scene = $row->scene;
		$shot = $row->shot;
		#$jobtype = $row->jobtype;
		$config = $row->config;
		#$output = $row->output;
		$start = $row->start;
		$end = $row->end;
		$current = $row->current;
		$chunks = $row->chunks;
		$rem = $row->rem;
		$filetype = $row->filetype;
		$progress_status = $row->progress_status;
		$progress_remark = $row->progress_remark;
		$last_edited_by = $row->last_edited_by;
		$lastseen = $row->lastseen;
		$status = $row->status;
		$priority = $row->priority;
		$total = $end-$start;
	#-------------------
	print "<h2>job $id : $scene/<b>$shot</b> </h2>";	
		if ($rem ) {
			print "remark :: $rem<br/>";
		}	
		#print " <p class=\"$status\">";
		print "project: <a href=\"index.php?view=view_project&project=$project\" class=\"tooltip\">$project<span>go to project page</span></a> $total frames ($start-$end by $chunks) ";
		$total_rendered = count_rendered_frames($id);
		print "$total_rendered rendered frames last changes made by  :: $last_edited_by $lastseen "; ?>

	<div class="table-controls">
		<a class="btn" href="index.php?view=jobs">back to job list</a>
		<button class="switchbg_btn btn">dark background</button>
		<a class="btn" id="edit_job_button" href="#">edit or duplicate job</a>
		<a class="btn" href="index.php?view=preview_anim&id=<?php echo $id?>">preview anim</a>
		<a class="btn" href="index.php?reset=<?php echo $id?>&start=<?php echo $id?>"><span class="<?php echo $status?>">&nbsp</span>&nbsp;<img src="images/icons/reload.png" /></a>
	</div>
		
	<table border=0 class="thumbnails_table">
	<tr>
	<?php #-------------------------------les images ------------------------------
	$a = $start;
	$first_image = get_thumbnail_image($id,$start);

	$img_chunks = round(($total)/20);
	if ($img_chunks == 0) {
		$img_chunks = 1;
	}
	# print "a= $a --- start $start -- end $end -- totalframes $total img_chunks =$img_chunks </br>";
	print "<td><a href=\"index.php?view=view_image&job_id=$id&frame=$a\">$first_image<br/>$a<br/></a></td>";
	$rows = 1;
	$b = 0;
	while ($a++ < ($total+$start)){
		$b++;
		# print " a= $a ---- b=$b/$img_chunks <br/>";
		if ($b == $img_chunks) {
			/*if ($_GET[renderpreview]) {
					$render_order = "-b \'/brender/blend/$file\' -o \'/brender/render/$project/$name/$output\' -P conf/$config.py -F JPEG -f $a";
                                        # ---------------------------------
                                        print "job_render for $client :\n $render_order\n-----------\n";
                                        #send_order("any","render",$render_order,"20");
			}
			*/
            $thumbnail_image=get_thumbnail_image($id,$a);

			print "<td>";
				print "<a href=\"index.php?view=view_image&job_id=$id&frame=$a\">$thumbnail_image<p>$a</p></a>";
			print "</td>";
			$b = 0;
			#  print "row = $rows";
			if ($rows++ > 3) {
				$rows = 0;
				print "</tr><tr>";
			}
		}
	}
	print "</tr></table>";

// Update job form
		$select_multilayer = $select_exr = $select_tga = $select_png = "";

		if ($filetype == "TGA"){
			$select_tga = "selected";
		}
		else if ($filetype == "EXR"){
			$select_exr = "selected";
		}
		else if ($filetype == "MULTILAYER"){
			$select_multilayer = "selected";
		}
		else if ($filetype == "PNG"){
			$select_png = "selected";
		}
		?>
		
		<div id="edit_job" title="// edit or duplicate job">
			<div class="col_1">
				<label for="filetype">type</label>
				<label for="config">config</label>
				<label for="progress_status">progress status</label>
				<label for="progress_remark">progress remark</label>
				<label for="start">start</label>
				<label for="end">end</label>
				<label for="chunks">chunks</label>
				<label for="priority">priority</label>
				<label for="rem">remarks</label>
				<label for="directstart">directstart</label>
			</div>
			
			<div class="col_2">
				<select id="edit_filetype" name="filetype">
	                 	<option value="JPEG">JPEG</option>
	                    <option value="PNG" <?php print($select_png); ?>>PNG</option>
						<option value="TGA" <?php print($select_tga); ?>>TGA</option>
						<option value="EXR" <?php print($select_exr); ?>>OPEN_EXR</option>
						<option value="MULTILAYER" <?php print($select_multilayer); ?>>MULTILAYER</option>
                </select>
                <select id="edit_config" name="config">
						<?php output_config_select($config); ?>
				</select>
				<select id="progress_status" name="progress_status"> 
					<?php output_progress_status_select($progress_status); ?>
				</select>
				<input id="edit_progress_remark" name="progress_remark" type="text" value="<?php print($progress_remark); ?>">
				<input id="edit_start" type=text name=start size="4" value="<?php print($start); ?>">
				<input id="edit_end" type="text" name="end" size="4" value="<?php print($end); ?>" />
				<input id="edit_chunks" type="text" name="chunks" size="3" value="<?php print($chunks); ?>" />
				<input id="edit_priority" type="text" name="priority" size="3" value="<?php print($priority); ?>" />
				<input id="edit_rem" name="rem" type="text" value="<?php print($rem); ?>">
				<input id="edit_directstart" type="checkbox" name="directstart" value="yes" />
			</div>
	
    		<input id="updateid" type="hidden" name="updateid" value="<?php print($id); ?>" />
    		<input id="edit_scene" type="hidden" name="scene" value="<?php print($scene); ?>" />
    		<input id="edit_shot" type="hidden" name="shot" value="<?php print($shot); ?>" />
    		<input id="edit_project" type="hidden" name="project" value="<?php print($project); ?>" />
   		</div>




<div class="table-controls">
	<a class="btn" href="index.php?view=jobs">back to job list</a>
	<button class="switchbg_btn btn">dark background</button>
	<a class="btn" id="edit_job_button2" href="#">edit or duplicate job</a>
</div>
<div class="over"></div>
