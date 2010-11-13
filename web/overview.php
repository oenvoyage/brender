// THIS IS OVERVIEW PAGE

<?php
check_if_client_should_work();
show_last_log();
show_client_list();
show_job_list();

function show_last_log() {
	print "<h2>// last logs</h2>";
	$lok = file("../logs/brender.log");
        $lok=array_reverse($lok);
        foreach ($lok as $line){
                if ($a++>5 ) {
                        break;
                }
                print "$line<br/>";
        }

}
function show_client_list() {
	print "<h2>// clients</h2>";
	include "clients.php";
}
function show_job_list() {
	print "<h2>// jobs</h2>";
	include "jobs.php";
}
?>
