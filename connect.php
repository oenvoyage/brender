<?php 
#$my_server="localhost";
$my_server="192.168.30.38";
$my_user="brender";
$my_password="brender";
print "connect.php info :: ---- $my_server,$my_user,$my_password ----";
$link=mysql_connect($my_server,$my_user,$my_password) or die("unable to connect to mysql server");
mysql_select_db('brender',$link) or die("blabla error database");
print "connected to server $my_server : $my_user\n";
?>
