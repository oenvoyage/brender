<?php 
$my_server="localhost";
#$my_server="192.168.30.31";
$my_user="root";
$my_password="video34";
print "connect.php info :: ---- $my_server,$my_user,$my_password ----";
$link=mysql_connect($my_server,$my_user,$my_password) or die("unable to connect to mysql server");
@mysql_select_db("brender");
print "connected to server $my_server : $my_user\n";
?>
