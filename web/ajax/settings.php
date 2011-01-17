<?php

include_once("../tpl/connect.php");
include_once("../../functions.php");
$GLOBALS['computer_name']="ajax";


if(isset($_GET['check_server_status'])) {
	$server = $_GET['check_server_status'];
}

if ($server) {
	//print "checking server status<br/>";
	$server_status = check_server_status();
	//echo "{\"status\":\"$server_status\"}";
	$status = true;
	$msg = $server_status;
} else {
	//echo "{\"status\":\"fail\"}";
	$status = false;
}

echo  "{\"status\":\"$status\", \"msg\":\"$msg\" }";

?>
