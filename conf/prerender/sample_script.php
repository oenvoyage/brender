#!/usr/bin/php5
<?php 
	output("this is a sample PHP prerender script that will launch a custom command to be  executed on all clients");
	include "connect.php";
	include "functions.php";
	$query = "SELECT * FROM clients WHERE status='idle' OR status='disabled' OR status='rendering'";
	$results = mysql_query($query);
	while ($row = mysql_fetch_object($results)){
		$client = $row->client;
		print "client $client \n<br/>";
		send_order("$client","execute_command","blender_up","99");
	}
?>
