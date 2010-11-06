<?php 
print "<h2>// <strong>start</strong> new job</h2>";
	#-------brender alpha 0.01
	if ($_POST[nom]) {
		$file=$_FILES['userfile']['name'];	
		$jobtype="blend";
		$start=$_POST[start];
		$end=$_POST[end];
		$nom=$_POST[nom];
		$project=$_POST[project];
		$blendfile=$_POST[blendfile];
		$filetype=$_POST[filetype];
		$output=$_POST[output];
		$rem=$_POST[rem];
		$config=$_POST[config];
		$chunks=$_POST[chunks];
		$priority=$_POST[priority];
		if ($_POST[directstart]=="yes"){
			print "direct start<br/>";
			$status="waiting";
		}
		else {
			print "no start<br/>";
			$status="pause";
		}
		if ($blendfile) {
			$file="$blendfile.blend";
		}

		$query="insert into jobs values('','$nom','$jobtype','$file','$start','$end','$project','$output','$start','$chunks','$rem','$filetype','$config','$status','$priority',now())";
		 mysql_query($query);
		print "$query<br/>";
		print "<a href=\"jobs.php\">view jobs</a>";
		
	}
	else {
		$error="no name<br/>";
	}
?>

<?php if ($error) { ?>
<font color=red><?php echo $error?></font>
<hr>
<form action="index.php?view=upload" method="post">
	project : <select name="project">
		<?php 
			$query="select * from projects order by def DESC ";
			$results=mysql_query($query);
			while ($row=mysql_fetch_object($results)){
				$id=$row->id;
				$nom=$row->name;
				print "<option>$nom</option>";
			}
		?>
		</select><br/>
	job/scene name:<input type="text" name="nom" size="30"><br/>
	blendfile name<input type="text" name="blendfile" size="16"><br/>
        output: /project/scene/<input name="output" type="text" size="12">####.
		<select name="filetype">
			<option>tga</option>
			<option>jpg</option>
			<option>png</option>
		</select>
	<br/>
	config : <select name="config">
			<option value="preview">preview</option>
			<option value="1k">1k</option>
			<option value="2k">2k</option>
		</select>
	<br/>
	start:<input type="text" name="start" size="3" value="1">
	end:<input type="text" name="end" size="3" value="100"><br/>
	chunks:<input type="text" name="chunks" size="3" value="3"><br/>
	priority (1-99):<input type="text" name="priority" size="3" value="50"><br/>
	rem:<input type="text" name="rem" size="30"><br/>
	directstart:<input type="checkbox" name="directstart" value="yes"><br/>
        <input type="submit" value="send job"><br/>
</form>
<?php }?>
