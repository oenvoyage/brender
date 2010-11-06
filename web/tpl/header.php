<?php
	header("Content-Type: text/html; charset=utf-8");
	$sid=session_id();
	global $random_x;
	$random_x=rand(0,9999);
	include_once("connect.php");
	include_once("functions.php");
	include_once("../functions.php");
?>
<!doctype html>
<html>
	<head>
		<title>brender 0.5</title>
		<link href="css/<?php if (!$_SESSION[user]) { 
			print "brender";
			} else { print($_SESSION[theme]);
			} ?>.css" rel="stylesheet" type="text/css">
<?php if(!$view == "upload") { ?> <meta http-equiv="Refresh" content="60;URL=index.php" /> <?php } ?>
	</head>
	<body>
		<div id="wrap">
			<div id="header">
			<a href="overview.php?overview=1"><img src="images/logo.png" class="logo" /></a>
			<div class="metadata">
				<p><?php print "connected to server <a href=\"/phpmyadmin/index.php?db=brender\" target=\"_blank\"> $my_server </a> as: $my_user<br/>";?></p>
				<p class="clock"><?php include "clock.php"?></p>
				<p><?php print "logged in as: $_SESSION[user]";?> <a href="logout.php">[logout]</a></p>
			</div>


