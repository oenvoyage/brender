<?php 

	#-------brender alpha 0.01
	if ($_POST[scene] && $_POST['shot']) {	
		$start=$_POST[start];
		$end=$_POST[end];
		$shot=$_POST[shot];
		$project=$_POST[project];
		$scene=$_POST[scene];
		$filetype=$_POST[filetype];
		$rem=$_POST[rem];
		$config=$_POST[config];
		$chunks=$_POST[chunks];
		$priority=$_POST[priority];
		if ($_POST[directstart]=="yes"){
			print "<h2>// <strong>new job</strong> started</h2>";
			print "direct start<br/>";
			$status="waiting";
		}
		else {
			print "<h2>// <strong>new job</strong> submitted (not started)</h2>";
			$status="pause";
		}

		$query="insert into jobs values('','$scene','$shot','$start','$end','$project','$start','$chunks','$filetype','$rem','$config','$status','new','','$priority',now(),'$_SESSION[user]')";
		
		mysql_query($query);
		$_SESSION['last_used_config']=$config;
		print "$query<br/>";
		print "<a href=\"index.php?view=jobs\">view jobs</a><br/>";
		print "<a href=\"index.php?view=upload\">send another job</a>";
		
	}
	else {
		$error="please enter new job infos<br/>";
	}
?>

<h2>// <strong>start</strong> new job</h2>



<form action="index.php?view=upload" method="post">
	<p><?php echo $error?></p>
	<table summary="" >
		<tbody>
			<tr>
				<td>project</td>
				<td><select name="project">
					<?php 
						$query="select * from projects order by def DESC ";
						$results=mysql_query($query);
						while ($row=mysql_fetch_object($results)){
							$id=$row->id;
							$nom=$row->name;
							print "<option>$nom</option>";
						}
					?>
					</select>
				</td>			
			</tr>
				<td>scene</td>
				<td><input type="text" name="scene" size="30"></td>
			</tr>
			</tr>
				<td>shot</td>
				<td><input type="text" name="shot" size="30"></td>
			</tr>
			</tr>
				<td>file format</td>
				<td><select name="filetype">
						<option>PNG</option>
						<option>JPEG</option>
						<option>TGA</option>
					</select>
				</td>
			</tr>
			</tr>
				<td>config</td>
				<td><select name="config">
					<?php output_config_select() ?>
					</select>
				</td>
			</tr>
			</tr>
				<td>start</td>
				<td><input type="text" name="start" size="3" value="1"></td>
			</tr>
			</tr>
				<td>end</td>
				<td><input type="text" name="end" size="3" value="100"></td>
			</tr>
			</tr>
				<td>chunks</td>
				<td><input type="text" name="chunks" size="3" value="3"></td>
			</tr>
			</tr>
				<td>priority</td>
				<td><input type="text" name="priority" size="3" value="50"></td>
			</tr>
			</tr>
				<td>remarks</td>
				<td><input type="text" name="rem" size="30"></td>
			</tr>
			</tr>
				<td>directstart</td>
				<td><input type="checkbox" name="directstart" value="yes"></td>
			</tr>       
		</tbody>
	</table>
	<div class="clear"></div>
	<input class="submit" type="submit" value="send job"><br/>
</form>
