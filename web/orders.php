<script>
	$(function() {
		$( "button, input:submit, a.btn").button();
	});
</script>

<?php	

if (isset($_GET['delete_all'])) {
	$qquery="delete from orders";
	mysql_unbuffered_query($qquery);
	print "$qquery";
}
if (isset($_GET['delete_old'])) {
	$qquery="delete from orders where time_format(TIMEDIFF(NOW(),created),'%k') >2";
	mysql_unbuffered_query($qquery);
	print "$qquery";
}
if (isset($_GET['del'])) {
	$qquery="delete from orders where id='$_GET[del]'";
	mysql_unbuffered_query($qquery);
	print "$qquery";
}
?>
	
<h2>// <b>orders</b> <?php output_refresh_button(); ?></h2>
<table>
	<tr class="header_row">
		<td width=120><b> &nbsp; id &nbsp; </b></td>
		<td width=120><b>client name</b></td>
		<td width=120><b>orders</b></td>
		<td width=500 align=center><b> &nbsp; rem &nbsp; </b></td>
		<td width=120 align=center><b> &nbsp; priority &nbsp; </td>
		<td width=120><b>created</b></td>
		<td width=10></td>
	</tr>
	<?php
	$query="select *,time_format(TIMEDIFF(NOW(),created),'%k') as hours_idle from orders";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$priority=$row->priority;
		$id=$row->id;
		$created=$row->created;
		$rem=$row->rem;
		$orders=$row->orders;
		$hours_idle=$row->hours_idle;
		$bgcolor="#cccccc";
		$created_class="";
		if ($hours_idle>2) {   
			# the orders that are older than 2 hours will be displayed in red
			$created_class="error";
		}
		print "<tr>
			<td bgcolor=$bgcolor>$id</td>
			<td class=neutral><a href=\"index.php?view=view_client&client=$client\">$client</a></td> 
			<td class=neutral>$orders</td> 
			<td bgcolor=$bgcolor>$rem</td>
			<td bgcolor=$bgcolor>$priority</td>
			<td class=$created_class>$created</td> 
			<td bgcolor=$bgcolor><a href=\"index.php?view=orders&del=$id\">x</a></td>
		</tr>";
	}
	?>
</table>
<div class="table-controls">
	<a class="btn" href="index.php?view=orders&delete_all=1">delete_all</a>
	<a class="btn" href="index.php?view=orders&delete_old=1">delete_older than 2 hours</a>
</div>

