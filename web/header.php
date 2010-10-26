<?php
        header("Content-Type: text/html; charset=utf-8");
        $sid=session_id();
        global $random_x;
        $random_x=rand(0,9999);
        include_once("connect.php");
	include_once("functions.php");
	print "<html><head>";
  	print "<link href=\"css/$_SESSION[theme].css\" rel=\"stylesheet\" type=\"text/css\">\n";
  	print "<meta http-equiv=\"Refresh\" content=\"60;URL=index.php\" />";
	print "</head>";
	print "<body>";
	if (!$_SESSION[user]) {
		print "No login";
		include_once("login.php");
		die();
	}
	include_once("menu.php");
?>
