<script>
		$(function() {
			var project = $('select#project'),
				scene = $('input#scene'),
				shot = $('input#shot'),
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
			
			$("#new_job")
			.click(function() {
				$( "#upload" ).dialog( "open" );
			});

	
		});
</script>
<div id="upload" title="// start new job">

		<p><?php echo $error?></p>
		<table summary="" >
			<tbody>
				<tr>
					<td>project</td>
					<td><select id="project" name="project">
						<?php 
							$query="select * from projects order by def DESC ";
							$results=mysql_query($query);
							while ($row=mysql_fetch_object($results)){
								$id=$row->id;
								$nom=$row->name;
								print "<option>$nom</option>";
								if (!$project) {
									$project=$nom;
								}
							}
						?>
						</select>
					</td>			
				</tr>
					<td>scene</td>
					<td><input id="scene" type="text" name="scene" size="30"></td>
				</tr>
				</tr>
					<td>shot</td>
					<td><input id="shot" type="text" name="shot" size="30"></td>
				</tr>
				</tr>
					<td>file format</td>
					<td><select id="fileformat" name="filetype">
							<option>PNG</option>
							<option>JPEG</option>
							<option>TGA</option>
							<option>OPEN_EXR</option>
						</select>
					</td>
				</tr>
				</tr>
					<td>config</td>
					<td><select id="config" name="config">
						<?php output_config_select() ?>
						</select>
					</td>
				</tr>
				</tr>
					<td>start</td>
					<td><input id="start" type="text" name="start" size="3" value="1"></td>
				</tr>
				</tr>
					<td>end</td>
					<td><input id="end" type="text" name="end" size="3" value="100"></td>
				</tr>
				</tr>
					<td>chunks</td>
					<td><input id="chunks" type="text" name="chunks" size="3" value="3"></td>
				</tr>
				</tr>
					<td>priority</td>
					<td><input id="priority" type="text" name="priority" size="3" value="50"></td>
				</tr>
				</tr>
					<td>remarks</td>
					<td><input id="rem" type="text" name="rem" size="30"></td>
				</tr>
				</tr>
					<td>directstart</td>
					<td><input id="directstart" type="checkbox" name="directstart" value="true" /></td>
				</tr>       
			</tbody>
		</table>
		<div class="clear"></div>
</div>
