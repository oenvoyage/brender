<?php
?>
<head>
<title>set job priority</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="brender.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="document.prio.priority.focus()">
<?php if ($_POST[action]=="update"){
	include "connect.php";
	$client=$_POST[client];
	if (isset($_POST[priority])) {
		$priority=$_POST[priority];
	}
	else {
		$priority=$_POST[prioritylist];
	}
	print "client $client update priority to $priority<br/>";
	print "<br/>";
	$x=rand(1,1000);
	print "<form name=prio>";
	# <input name=priority type=submit onClick=\"javascript:self.close()\" value=\"close\"></form>";
	print "-------------------------------- <a href=\"clients.php?$x\" name=\"priority\" target=\"main\" onClick=\"javascript:self.close()\">close</a> --";
	$query="update clients set client_priority='$priority' where client='$client'";
	mysql_query($query);

}
else {
?>
<form name="prio" id="prio" method="post" action="clients_priority_popup.php">
  enter priority for <b>client <?php echo $_GET[client]?></b>:<br/><br/> 
  <select name="prioritylist" id="prioritylist" onChange="javascript:document.prio.priority.value=document.prio.prioritylist.value">
    <option value="0">all 0</option>
    <option value="50">normal 50</option>
    <option value="80">reserve 90</option>
    <option value="<?php echo $_GET[client_priority]?>" selected>manual--&gt;</option>
  </select>
  <input name="priority" type="text" id="priority" size="3" maxlength="2" value="<?php echo $_GET[client_priority]?>">
  <input type="hidden" name="client" value="<?php echo $_GET[client]?>" />
  <input type="hidden" name="action" value="update"/>
  <input type="submit" name="Submit" value="ok"/>
  <input name="set_priority" type="hidden" id="set_priority" value="yes" />
</form>
<?php } ?>
</body>
</html>

