<h2>// <strong>TEST </strong> page</h2>

<b>doing a test</b><br/>
<form>
<input >
	<select onChange='test("ff");'>
		<option>1</option>
		<option>2</option>
		<option>3</option>
	</select><br/>
<?php 
	#output_shot_selector("gphg","03_animal_ballon");
	#$qq=get_scene_list_array("gphg");
	#print_r($qq);
	scene_shot_cascading_dropdown_menus();
	#brender_log("just a test");
	#print check_server_status();
	
?>
<input type="submit" onClick='test();'>
</form>
