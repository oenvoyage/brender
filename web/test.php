<h2>// <strong>TEST </strong> page</h2>
<?php 
include "../functions.php";
if (isset($_GET['test1'])) {
	print "doing TEST 1<br/>-----<br/>";
	$client="macbook";
	send_order("$client","execute_command","blender_up","99");
}
if (isset($_GET['path'])) {
	$path = $_GET['path'];
	print "original path : <b>$path</b><br/>";
	$win_path=windowsify_paths($path);
	print "windowsified path : <b>$win_path</b><br/>";
}
?>

<hr>
<b>doing a test</b><br/>
<form>
path : <input name="path" value="<?php echo $path?>">
	<select onChange='test("ff");'>
		<option>1</option>
		<option>2</option>
		<option>3</option>
	</select><br/>
<input type="submit" onClick='test();'>
</form>
<?php
#$path="thumbnails/test/brender/clalvlavl";
#check_create_path($path);
	#output_shot_selector("gphg","03_animal_ballon");
	#$qq=get_scene_list_array("gphg");
	#print_r($qq);
	#scene_shot_cascading_dropdown_menus();
	#brender_log("just a test");
	#print check_server_status();
	
?>
<a href="index.php?view=test&test1=1">do TEST 1</a>
