<div id="nav">

	<ul>	
		<li><a class="button grey" href="index.php">overview</a></li>
		<li><a class="button grey" href="?view=clients">clients</a></li>
		<li><a class="button grey" href="?view=jobs">jobs</a></li>
		<li><a class="button grey" href="?view=orders">orders</a></li>
		<li><a class="button grey" href="?view=upload">new job</a></li>
	</ul>
	<ul class="right"> 
		<li>
		<?php 
			if ($_SESSION[debug]) {
				print "debug";
			}
			display_dead_server_warning() 
		?>
		<a class="button black" href="?view=settings">settings</a></li>
		<li><a class="button black" href="?view=status">status</a></li>
		<li><a class="button black" href="?view=logs">logs</a></li>
	</ul>
</div>
<div class="clear"></div>
</div>
