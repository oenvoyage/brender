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
if (isset($_POST['query'])) {
	# print "execute query ($_POST[query])<br/>";
	$query=str_replace("\\","",$_POST[query]);   # remplace / par rien...
	mysql_query($query);
	print "execute query ($query)<br/>";
}

#--------read---------
	$query="select * from orders";
	$results=mysql_query($query);
	print "<h2>// <b>orders</b></h2>";
	print "<table>";
	print "<tr>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; id &nbsp; </b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>client name</b></td>
		<td bgcolor=cccccc width=120 height=30 align=center><b>orders</b></td>
		<td bgcolor=cccccc width=500 align=center><b> &nbsp; rem &nbsp; </b></td>
		<td bgcolor=cccccc width=120 align=center><b> &nbsp; priority &nbsp; </td>
		<td bgcolor=cccccc align=center></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$client=$row->client;
		$priority=$row->priority;
		$id=$row->id;
		$rem=$row->rem;
		$orders=$row->orders;
		$bgcolor="#cccccc";
		print "<tr>
			<td bgcolor=$bgcolor align=center>$id</td>
			<td bgcolor=ddddcc align=center>$client</td> 
			<td bgcolor=ddddcc align=center>$orders</td> 
			<td bgcolor=$bgcolor align=center>$rem</td>
			<td bgcolor=$bgcolor align=center>$priority</td>
			<td bgcolor=$bgcolor align=center><a href=\"index.php?view=orders&del=$id\">x</a></td>
		</tr>";
	}
	print "</table>";
	print "<a href=\"index.php?view=orders&delete_all=1\"><div class=\"ordre\">delete_all</div></a>";
?>

