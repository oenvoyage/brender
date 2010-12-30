<script>
		$(function() {
			var project = $('select#project'),
				scene = $('select#scene'),
				shot = $('select#shot'),
				fileformat = $('select#fileformat'),
				config = $('select#config'),
				start = $('input#start'),
				end = $('input#end'),
				chunks = $('input#chunks'),
				priority = $('input#priority'),
				rem = $('input#rem'),
				directstart = $('#directstart input[type="checkbox"]');
		
			
			$("#upload").dialog({
				autoOpen: false,
				height: 400,
				width: 450,
				modal: true,
				buttons: {
					Cancel: function() {
						$(this).dialog("close");
					},
					"Start job": function() { 							
							
							$.post("ajax/upload.php", {
								project: project.val(), 
								scene: scene.val(), shot: shot.val(), 
								fileformat: fileformat.val(), 
								config: config.val(), 
								start: start.val(), 
								end: end.val(), 
								chunks: chunks.val(), 
								priority: priority.val(), 
								rem: rem.val(), 
								directstart: directstart.val() 
							}, function(data) {
								var obj = jQuery.parseJSON(data);
								//alert(data);
								if(obj.status == true) {
									$("#dialog-form").dialog("close" );
									//alert(obj.query);
									window.location= 'index.php';
								} else {
									alert(obj.msg);
								}
							}, "Json");				
			    			return false;					
					}
				},
				close: function() {
					//allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});
			
			$("#new_job, #new_job2")
			.click(function() {
				$( "#upload" ).dialog( "open" );
			});

	
		});
</script>
<div id="upload" title="// start new job">
<p><?php echo $error?></p>
	<div class="col_1">
		<label for="project">project</label>
		<label for="scene">scene</label>
		<label for="shot">shot</label>
		<label for="file_format">file format</label>
		<label for="config">config</label>
		<label for="start">start</label>
		<label for="end">end</label>
		<label for="chunks">chunks</label>
		<label for="priority">priority</label>
		<label for="remarks">remarks</label>
		<label for="directstart">directstart</label>				
	</div>
	<div class="col_2">
		<?php scene_shot_cascading_dropdown_menus() ?>
		<select id="file_format" name="file_format">
					<option>PNG</option>
					<option>JPEG</option>
					<option>TGA</option>
					<option>OPEN_EXR</option>
		</select>
		<select id="config" name="config">
				<?php output_config_select() ?>
		</select>
		<input id="start" type="text" name="start" size="3" value="1" />
		<input id="end" type="text" name="end" size="3" value="100" />
		<input id="chunks" type="text" name="chunks" size="3" value="3" />
		<input id="priority" type="text" name="priority" size="3" value="50" />
		<input id="rem" type="text" name="rem" size="30" value="" />
		<input id="directstart" type="checkbox" name="directstart" value="true" />				
	</div>
	<div class="clear"></div>
</div>
