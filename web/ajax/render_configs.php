<?php
if ($_GET['edit']) {
	show_edit_form($_GET['edit']);
}

#--------read---------
function show_edit_form($conf) {
	//print "<h2>Viewing $conf (TODO : editing of files)</h2>";

	$lok = file("../../conf/$conf.py");
        #$lok=array_reverse($lok);
        foreach ($lok as $line){
                #if ($a++>$_max ) {
                #        break;
                #}
                print "$line<br/>";
        }
}

?>




