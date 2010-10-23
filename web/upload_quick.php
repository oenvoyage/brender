<body bgcolor="#eeeeee">
<?php 
require "connect.php";
print "<link href=\"brender.css\" rel=\"stylesheet\" type=\"text/css\">";
print "<br/>";
print "<b>upload new QUICK job</b> <p>\n";
	#-------brender alpha 0.01
	if ($_FILES['userfile']) {
		$file=$_FILES['userfile']['name'];	
		$start=$_POST[start];
		$end=$_POST[end];
		$nom="quickrender";
		$project="quick_render";
		$filetype=$_POST[filetype];
		$output=$_POST[output];
		$rem="quickrender";
		$config=$_POST[config];
		$chunks=$_POST[chunks];
		$priority="1";
		$status="waiting";
		if ($nom) {
			move_uploaded_file($_FILES['userfile']['tmp_name'], "/brender/blend/$file");
			chmod("/brender/blend/$file",0755);
			$query="delete from jobs where name='quickrender'";
			 mysql_query($query);
			$query="insert into jobs values('','$nom','$file','$start','$end','$project','$output','$start','$chunks','$rem','$filetype','$config','$status','$priority',now())";
			 mysql_query($query);
			print "$query<br/>";
			print "<a href=\"jobs.php\">view jobs</a>";
		}
		else {
			$error="no name<br/>";
		}
		
	}
	else {
		$error=" ";
	}
?>

<?php if ($error) { ?>
<font color=red><?php echo $error?></font>
<hr>
<form enctype="multipart/form-data" action="upload_quick.php" method="post">
        blend file: <input name="userfile" type="file"><br/>
        output: <input name="output" type="text" size="12" value="frame">####.
		<select name="filetype">
			<option>jpg</option>
			<option>tga</option>
		</select>
	<br/>
	config : <select name="config">
			<option value="pal">pal</option>
			<option value="preview">preview</option>
			<option value="hd720">hd720</option>
			<option value="hd1080">hd1080</option>
		</select>
	<br/>
	start:<input type="text" name="start" size="3" value="1">
	end:<input type="text" name="end" size="3" value="100"> chunks:<input type="text" name="chunks" size="3" value="5"><br/>
        <input type="submit" value="send job"><br/>
</form>
<?php }?>
