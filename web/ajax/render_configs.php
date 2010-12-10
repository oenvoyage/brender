<?php
if ($_GET['edit']) {
	show_edit_form($_GET['edit']);
}

function show_edit_form($conf) {
	$lok = file("../../conf/$conf.py");
	#$lok=array_reverse($lok);
	foreach ($lok as $line){
		print "$line<br/>";
	}
}

?>




