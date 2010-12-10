<div id="nav">

	<ul>	
		<li><a class="button grey <?php if($view == ""){print("active");}?>" href="index.php">overview</a></li>
		<li><a class="button grey <?php if($view == "clients"){print("active");}?>" href="?view=clients">clients</a></li>
		<li><a class="button grey <?php if($view == "jobs"){print("active");}?>" href="?view=jobs">jobs</a></li>
		<li><a class="button grey <?php if($view == "orders"){print("active");}?>" href="?view=orders">orders</a></li>
		<li><a id="new_job" class="button grey" href="#">new job</a></li>
	</ul>
	<ul class="right">
		<li><a class="button black <?php if($view == "settings"){print("active");}?>" href="?view=settings">settings</a></li>
		<li><a class="button black <?php if($view == "status"){print("active");}?>" href="?view=status">status</a></li>
		<li><a class="button black <?php if($view == "logs"){print("active");}?>" href="?view=logs">logs</a></li>
	</ul>
</div>
<div class="clear"></div>
</div>
