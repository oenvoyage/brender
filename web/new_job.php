<div id="new_job" title="// start new job">
<p><?php 
	if (isset($error)) {
		echo $error;
	} ?></p>
	<div class="col_1">
		<label for="project">project</label>
		<label for="scene">scene</label>
		<label for="shot">shot</label>
		<label for="fileformat">file format</label>
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
		<select id="fileformat" name="fileformat">
					<option>PNG</option>
					<option>JPEG</option>
					<option>TGA</option>
					<option>OPEN_EXR</option>
					<option>MULTILAYER</option>
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
