<?php
	header("Content-Type: text/html; charset=utf-8");
	$sid=session_id();
	global $random_x;
	$random_x=rand(0,9999);
	include_once("connect.php");
	include_once("../functions.php");
	include_once("functions.php");
	
	if (isset($_GET['theme'])) {
	$_SESSION['theme']=$_GET['theme'];
}
?>
<!doctype html>
<html>
	<head>
		<title>brender 0.5</title>
		
		<link rel="stylesheet" href="css/jquery-ui-1.8.6.custom.css">
		<script src="js/jquery-1.4.3.min.js_off"></script>
		<script src="js/jquery-ui-1.8.6.custom.min.js_off"></script>	
		
		<link href="css/<?php if (!$_SESSION[user]) { 
			print "brender";
			} else { print($_SESSION[theme]);
			} ?>.css" rel="stylesheet" type="text/css">
<?php if(!$view == "upload") { ?> <meta http-equiv="Refresh" content="60;URL=index.php" /> <?php } ?>

		<script>
		$(function() {
			$( "#dialog-modal" ).dialog({
				height: 250,
				resizable: false,
				draggable: false,
				modal: true,
				hide: "explode",
				closeOnEscape: false,
			});

		});
		$(function() {
			$( "button, input:submit, a", "#dialog-modal" ).button();
			$( "a", ".demo" ).click(function() { return false; });
		});

		
		</script>		
	</head>
	<body>
		<div id="wrap">
			<div id="header">
			<a href="index.php"><img src="images/<?php if (!$_SESSION[user]) { 
			print "brender";
			} else { print($_SESSION[theme]);
			} ?>_logo.png" class="logo" /></a>
			<div class="metadata">
				<p><?php print "connected to server <a href=\"/phpmyadmin/index.php?db=brender\" target=\"_blank\"> $my_server </a> as: $my_user<br/>";?></p>
				<p class="clock"><?php include "clock.php"?></p>
				<p><?php print "logged in as: $_SESSION[user]";?> <a href="logout.php">[logout]</a></p>
			</div>


