<div id=menu>
<a href="overview.php?overview=1" target="main"><img src="images/logo_petit.gif" border=0></a>
<p>
<span class="clock"><?php include "clock.php"?></span>
<?php print "logged in as : $_SESSION[user]";?>
<ul>
<li> <a class=menu href="index.php" >overview</a></li>
<li><a class=menu href="clients.php" >clients</a></li>
<li><a class=menu href="jobs.php" >jobs</a> <a href="jobs.php?no_visual=1" target="main">*</a><a href="jobs.php?all_projects=1" target="main">+</a></li>
<li><a class=menu href="orders.php" >orders</a></li>
<li>-----------------</li>
<li><a class=menu href="upload.php" >new job</a></li> 
<li>-----------------</li>
<li><a class=menu href="settings.php" >settings</a></li>
<li><a class=menu href="status.php" >status</a></li>
<li><a class=menu href="logs.php" >logs</a></li>
</ul>
<hr>
</div>
