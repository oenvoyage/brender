<?php
#--------read---------
	$image=$_GET[image];
	$project=$_GET[project];
	$name=$_GET[name];
	$id=$_GET[id];
	$image_big=preg_replace("/small_/","",$image);;
	print "<a href=\"index.php?view=view_job&id=$id&bgcolor=$bgcolor&visual=1\"><img src=\"$image_big\" border=0></a><br/>";
	print "<a href=\"$image_big\">$image_big</a><br/>";
	#print "<a href=\"view_image.php?name=$name&image=$image&id=$id&project=$project&bgcolor=$option_couleur&visual=1\">$option_couleur</a><br/>";
	print "<a href=\"view_job.php?id=$id&bgcolor=$bgcolor&visual=1\">return to job</a>";
?>
