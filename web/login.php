<?php
	session_start();
	if ($_GET[login]) {
		$_SESSION[user]="o";
	}
?>
click here to login as <a href="login.php?login=o">o</a><br/>
click here to login as <a href="login.php?login=francesco">francesco</a><br/>
