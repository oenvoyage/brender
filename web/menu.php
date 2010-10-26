<div id=menu>
<a href="overview.php?overview=1" target="main"><img src="logo_petit.gif" border=0></a>
<p>
<span class="clock"><?php include "clock.php"?></span>
<a class=menu href="overview.php" target="main">overview</a></td></tr>
<a class=menu href="clients.php" target="main">clients</a></td></tr>
<a class=menu href="jobs.php" target="main">jobs</a> <a href="jobs.php?no_visual=1" target="main">*</a><a href="jobs.php?all_projects=1" target="main">+</a></td></tr>
<a class=menu href="orders.php" target="main">orders</a></td></tr>
-----------------
<a class=menu href="upload.php" target="main">new job</a> 
-----------------
<a class=menu href="settings.php" target="main">settings</a></td></tr>
<a class=menu href="status.php" target="main">status</a></td></tr>
<a class=menu href="logs.php" target="main">logs</a></td></tr>
<br/>
<?php print "logged in as : $_SESSION[user]";?>
<hr>
</div>
