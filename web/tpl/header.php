<?php
	header("Content-Type: text/html; charset=utf-8");
	$sid=session_id();
	global $random_x;
	$random_x=rand(0,9999);
	include_once("connect.php");
	include_once("../functions.php");
	include_once("functions.php");
	$computer_name="web_interface";
	
	if (isset($_GET['theme'])) {
	$_SESSION['theme']=$_GET['theme'];
}
?>
<!doctype html>
<html>
	<head>
		<title>brender 0.5</title>
		
		<link rel="stylesheet" href="css/jquery-ui-1.8.6.custom.css">
		<script src="js/jquery-1.4.3.min.js"></script>
		<script type='text/javascript' src='js/js_brender.js'></script>
		<script src="js/jquery-ui-1.8.6.custom.min.js"></script>	
		<meta name="viewport" content="width=device-width, user-scalable=no" /> 
		
		<link href="css/<?php if (!$_SESSION[user]) { 
			print "brender";
			} else { print($_SESSION[theme]);
			} ?>.css" rel="stylesheet" type="text/css">
<?php if(!$view == "upload") { ?> <meta http-equiv="Refresh" content="60;URL=index.php" /> <?php } ?>	
		<script>
		$(function() {
			$('#loadingSpinner')
			    .hide()  // hide it initially
			    .ajaxStart(function() {
			        $(this).show();
			    })
			    .ajaxStop(function() {
			        $(this).hide();
			    });
		});

		
		</script>		
	</head>
	<body>
		<div id="wrap">
			<div id="header">
			<a href="index.php"><img src="images/<?php if (!$_SESSION[user]) { 
			print "brender";
			} else { print($_SESSION[theme]);
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
				<p><?php print "logged in as: $_SESSION[user]";?> <a href="logout.php">[logout]</a></p>
			</div>
