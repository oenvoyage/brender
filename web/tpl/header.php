<?php
	header("Content-Type: text/html; charset=utf-8");
	$sid = session_id();
	global $random_x;
	$random_x = rand(0,9999);
	include_once("connect.php");
	include_once("../functions.php");
	include_once("functions.php");
	global $computer_name;
	$computer_name = "web_interface";
	
	if (isset($_GET['theme'])) {
		$_SESSION['theme'] = $_GET['theme'];
	}
	if (isset($_GET['autorefresh'])) {
		$_SESSION['autorefresh'] = $_GET['autorefresh'];
	}
?>
<!doctype html>
<html>
	<head>
		<title>brender 0.5</title>
		
		<link rel="stylesheet" href="css/jquery-ui-1.8.17.custom.css">
		<link href="css/<?php if (!isset($_SESSION['user'])) { 
			print "brender";
			} else { print($_SESSION['theme']);
			} ?>.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
		<script type="text/javascript" src="js/brender-0.5.dev.js"></script>	
		<meta name="viewport" content="width=device-width, user-scalable=no" /> 
		<meta http-equiv="REFRESH" content="<?php echo $_SESSION['autorefresh'] ?>">
		
	</head>
	<body>
		<div id="wrap">
			<div id="header">
			<a href="index.php"><img src="images/<?php if (!isset($_SESSION['user'])) { 
			print "brender";
			} else { print($_SESSION['theme']);
			} ?>_logo.png" class="logo" /></a><img id="loadingSpinner" src="images/ajax-loader.gif" alt="ajax-spinner" />
			<div class="metadata">
				<p>
					<?php print "connected to server <a href=\"/phpmyadmin/index.php?db=brender\" target=\"_blank\">$my_server</a>";
						display_dead_server_warning(); 
						print " as: $my_user<br/>";
						debug("enabled debug");
					?>
				</p>
				<p class="clock"><?php include "clock.php"?></p>
				<p><?php 
					if (isset($_SESSION['user'])) {
						print "logged in as: $_SESSION[user]";
						print "<a href=\"logout.php\">[logout]</a></p>";
				   } ?>
			</div>
