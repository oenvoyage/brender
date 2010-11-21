<?php 
session_start();
if (!$_SESSION[theme]){
        $_SESSION[theme]="brender";
}

$x=rand(1,1000);
print "<meta http-equiv=\"Refresh\" content=\"15;URL=overview.php?overview=21\" />";
print "<body>";
print "<link href=\"$_SESSION[theme].css\" rel=\"stylesheet\" type=\"text/css\">";
print "<a href=\"overview.php?x=$x\"><img src=\"logo.jpg\" border=0></a>";
include "clients.php";
include "jobs.php";
include "orders.php";
print "</body>";
?>
