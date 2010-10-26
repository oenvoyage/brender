<?php
require "connect.php";
require "../functions.php";
if ($_GET[bgcolor]=="black") {
        print "<link href=\"brender_black.css\" rel=\"stylesheet\" type=\"text/css\">\n";
        $option_couleur= "white";
	$bgcolor="black";
}
else {
        print "<link href=\"brender.css\" rel=\"stylesheet\" type=\"text/css\">\n";
        $option_couleur= "black";
	$bgcolor="white";

}
#--------read---------
	$image=$_GET[image];
	$project=$_GET[project];
	$name=$_GET[name];
	$id=$_GET[id];
	print "<a href=\"view_job.php?id=$id&bgcolor=$bgcolor&visual=1\"><img src=\"/Production/renders/$image\" border=0></a><br/>";
	print "<a href=\"/Production/renders/$image\">$image</a><br/>";
	print "<a href=\"view_image.php?name=$name&image=$image&id=$id&project=$project&bgcolor=$option_couleur&visual=1\">$option_couleur</a><br/>";
	print "<a href=\"view_job.php?id=$id&bgcolor=$bgcolor&visual=1\">return to job</a>";
?>
