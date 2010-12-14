<?php	

if (isset($_GET['delete_all'])) {
	$qquery="delete from orders";
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
		<td width=10></td>
	</tr>
	<?php
	$query="select * from orders";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$priority=$row->priority;
		$id=$row->id;
		$rem=$row->rem;
		$orders=$row->orders;
		$bgcolor="#cccccc";
		print "<tr>
			<td bgcolor=$bgcolor align=center>$id</td>
			<td class=neutral align=center><a href=\"index.php?view=view_client&client=$client\">$client</a></td> 
			<td class=neutral align=center>$orders</td> 
			<td bgcolor=$bgcolor align=center>$rem</td>
			<td bgcolor=$bgcolor align=center>$priority</td>
			<td bgcolor=$bgcolor align=center><a href=\"index.php?view=orders&del=$id\">x</a></td>
		</tr>";
	}
	?>
</table>
<div class="table-controls">
	<a href="index.php?view=orders&delete_all=1"><div class="ordre">delete_all</div></a>
</div>

