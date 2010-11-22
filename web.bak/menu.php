<?php
session_start();
include "../functions.php";
include "connect.php";
if (!$_SESSION[theme]){
	$_SESSION[theme]="brender";
}
global $computer_name;
$computer_name="web";
print "<html><head>";
print "<link href=\"$_SESSION[theme].css\" rel=\"stylesheet\" type=\"text/css\">\n";
print "<meta http-equiv=\"Refresh\" content=\"60;URL=menu.php\" />";
p
?>
</head>
&nbsp;
<body>
<br/>
<a href="overview.php?overview=1" target="main"><img src="logo_petit.gif" border=0></a>
<p>
<span class="clock"><?php include "clock.php"?></span>
<table>
<tr><td><a class=menu href="overview.php" target="main">overview</a></td></tr>
<tr><td><a class=menu href="clients.php" target="main">clients</a></td></tr>
<tr><td><a class=menu href="jobs.php" target="main">jobs</a> <a href="jobs.php?no_visual=1" target="main">*</a><a href="jobs.php?all_projects=1" target="main">+</a></td></tr>
<tr><td><a class=menu href="orders.php" target="main">orders</a></td></tr>
</table>
<hr>
<table>
<tr><td><a class=menu href="upload.php" target="main">new job</a> 
</table>
<hr>
<table>
<tr><td><a class=menu href="settings.php" target="main">settings</a></td></tr>
<tr><td><a class=menu href="status.php" target="main">status</a></td></tr>
<tr><td><a class=menu href="logs.php" target="main">logs</a></td></tr>
</table>
<hr>
<?php
	server_status();
?>
</html>
