<?php
	session_start();
	include_once "connect.php";
	include_once "functions.php";
	print "<html><head>";
	        print "<link href=\"css/brender.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	        print "<meta http-equiv=\"Refresh\" content=\"60;URL=index.php\" />";
        print "</head>";
        print "<body>";
	if ($_POST['do_login']) {
		if (check_login($_POST['user'],$_POST['password'])) {
			$_SESSION['user']=$_POST['user'];
			print "click here to continue to <a href=\"index.php\">brender</a>";
		}
		else {
			print "<br/><font color=red>error with login... please retry</font><br/>";
			show_login_form();
		}
		
	}
	else {
		print " click here to login as <a href=\"login.php?login=o\">o</a><br/>";
		print " click here to login as <a href=\"login.php?login=francesco\">francesco</a><br/>";
		show_login_form();
	}
?>
