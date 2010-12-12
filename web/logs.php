<script>
	$(function() {
		$( "#tabs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible.");
				}
			},
			selected: 1
		});
	});
</script>



<h2>// <b>logs</b></h2>

<div id="tabs">
	<ul>
		<li><a href="ajax/logs.php?log=server">server</a></li>
		<li><a href="ajax/logs.php?log=brender">brender</a></li>
		<?php 
			$query = "select * from clients order by status";
			$results = mysql_query($query);
			while ($row = mysql_fetch_object($results)){
				$client = $row->client;
				$status = $row->status;
				if ($status <> "disabled") {
		                        $bgcolor = "idle";
		                }
		                if ($status == "disabled") {
		                        $bgcolor = "disabled";
		                }
		                if ($status == "rendering") {
		                        $bgcolor = "rendering";
		                }
		                if ($status == "not running") {
		                        $dis = "";
		                        $bgcolor = "not_running";
		                }
				
				print "<li><a class=\"$bgcolor\" href=\"ajax/logs.php?log=$client\">$client</a></li>";
			}
		?>
	</ul>
</div>
