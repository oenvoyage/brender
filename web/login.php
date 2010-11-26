<div id="dialog-modal" title="// please login">
<h2>// <b>please</b> login</h2>
<?php	
	if ($_POST['do_login']) {
		if (check_login($_POST['user'],$_POST['password'])) {
			if (init_user($_POST['user'])) {
				#header( 'Location: index.php' );
				check_server_status();
				print "click here to continue to <a href=\"index.php\">brender</a>";
			}
			else {
				print "something went wrong with initializing user... try again";
			}
		}
		else {
			print "<font color=red>error with login... please retry</font>";
			show_login_form();
		}
		
	}
	else {
		show_login_form();
	}
?>
</div>
