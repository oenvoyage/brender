<?php	
	if (!isset($_GET['project']) && !isset($_POST['project'] )) {
		print "error :: please select a project<br/>";
		print "<a href=\"index.php?view=settings\">back to settings</a><br/>";
		die();
	}
	else {
		if (isset($_GET['project'])) {
			$project=$_GET['project'];
		}
		else {
			
			$project=$_POST['project'];
		}
		if (!check_project_exists($project)) {
			print "error :: project <b>$project</b> not found<br/>";
			print "<a href=\"index.php?view=settings\">back to projects list</a><br/>";
			die();
		}
	}

	if (isset($_POST['action'])) {
	   	if ($_POST['action']=="update") {
			$uquery="UPDATE projects SET 
				blend_mac='$_POST[blend_mac]',
				blend_linux='$_POST[blend_linux]',
				blend_win='$_POST[blend_win]',
				output_mac='$_POST[output_mac]',
				output_linux='$_POST[output_linux]',
				output_win='$_POST[output_win]',
				rem='$_POST[rem]' where name='$_POST[project]';
				";
			mysql_query($uquery);
			$msg="project <b>$project</b> updated :: ok <br/>";
			$msg.="<a href=\"index.php?view=settings\">back to projects list</a>";
		}
	}

#--------read---------
	$query="select * from projects where name='$project'";
	$results=mysql_query($query);
	if (isset($msg)) {
		print "$msg<br/>";
	}
	print "<h2>// view project <b>$project</b></h2>";
	#print "$query<br/>";
		$row=mysql_fetch_object($results);

		$blend_mac=$row->blend_mac;
		$blend_win=$row->blend_win;
		$blend_linux=$row->blend_linux;

		$output_mac=$row->output_mac;
		$output_win=$row->output_win;
		$output_linux=$row->output_linux;

		$rem=$row->rem;
		$status=$row->status;
		?>
	<form action="index.php" method="post">
		<input type="hidden" name="view" value="view_project">
		<input type="hidden" name="project" value="<?php print $project?>">
		<input type="hidden" name="action" value="update">

		<h3>project description</h3>
		remark : <input type="text" name="rem" size="60" value="<?php echo $rem?>"><br/>
		<h3>project paths</h3>
		.blend files paths<br/>
		blend_mac : <input type="text" name="blend_mac" size="60" value="<?php echo $blend_mac?>"><br/>
		blend_linux : <input type="text" name="blend_linux" size="60" value="<?php echo $blend_linux?>"><br/>
		blend_win : <input type="text" name="blend_win" size="60" value="<?php echo $blend_win?>"><br/>
		render outputs paths<br/>
		output_mac : <input type="text" name="output_mac" size="60" value="<?php echo $output_mac?>"><br/>
		output_linux : <input type="text" name="output_linux" size="60" value="<?php echo $output_linux?>"><br/>
		output_win : <input type="text" name="output_win" size="60" value="<?php echo $output_win?>"><br/>

		<input type="submit" value="update <?php print $project?>"><br/>&nbsp;<br/>
	</form><br/>
	<a href="index.php?view=settings">back to settings</a>

<?php
#------------------------------ functions -----------------
