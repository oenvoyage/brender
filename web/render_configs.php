<?php

print "<h2>render configs</h2>";
list_configs();
if ($_GET['edit']) {
	show_edit_form($_GET['edit']);
}

#--------read---------
function show_edit_form($conf) {
	print "<h2>Viewing $conf (TODO : editing of files)</h2>";

	$lok = file("../conf/$conf.py");
        #$lok=array_reverse($lok);
        foreach ($lok as $line){
                #if ($a++>$_max ) {
                #        break;
                #}
                print "$line<br/>";
        }
}
function list_configs() {
	$list= `ls ../conf/`;
        $list=preg_split("/\n/",$list);
        foreach ($list as $item) {
                $item=preg_replace("/\.py/","",$item);
		if ($item == $_GET['edit']) {
			print "$item ";
		}
		else if ($item<>"") {
			print "<a class=\"button grey\" href=\"index.php?view=render_configs&edit=$item\">$item</a> ";
		}
       }
}
?>

<a href="index.php">back</a>


