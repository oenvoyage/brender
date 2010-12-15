<?php
#--------read---------
	$job_id=$_GET['job_id'];
	$frame=$_GET['frame'];

	$thumbnail_path="thumbnails/";
        $scene=job_get("scene",$job_id);
        $shot=job_get("shot",$job_id);
        $filetype=filetype_to_ext(job_get("filetype",$job_id));
        $project=job_get("project",$job_id);
	$file_name=$shot.str_pad($frame,4,0,STR_PAD_LEFT).".$filetype";
        $thumbnail_location="/thumbnails/$project/$scene/$shot/$file_name";

	$query="select rendered_by,finished_time from rendered_frames where job_id='$job_id' and frame='$frame'";
	$results=mysql_query($query);
	$row=mysql_fetch_object($results);
	debug(" temp query = $query");
	$rendered_by=$row->rendered_by;
	$finished_time=$row->finished_time;
?>

	<a href="index.php?view=view_job&id=<?php echo $job_id?>"><img src="<?php print $thumbnail_location ?>" class="image"></a><br/>
	<a href="<?php echo $thumbnail_location ?>"><?php echo $file_name ?></a>
	<br/>
	<a href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame-10?>"><img src="images/icons/go-previous10.png"></a>
	<a href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame-1?>"><img src="images/icons/go-previous.png"></a>
	<?php print "<b> $frame</b>" ?>
	<a href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame+1?>"><img src="images/icons/go-next.png"></a>
	<a href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame+10?>"><img src="images/icons/go-next10.png"></a>
	<br/>
	rendered by <?php print "<a href=\"index.php?view=view_client&client=$rendered_by\">$rendered_by</a> @ $finished_time "?><br/>

	<a href="index.php?view=view_job&id=<?php echo $job_id ?>">return to job <?php echo $job_id ?></a><br/>


