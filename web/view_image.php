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

	<a href="index.php?view=view_job&id=<?php echo $job_id?>"><img src="<?php $thumbnail_location ?>" class="image big"></a><br/>
	<a href="<?php echo $thumbnail_location ?>"><?php echo $file_name ?></a><br/>
	rendered by <?php print "$rendered_by @ $finished_time "?><br/>

	<a href="index.php?view=view_job&id=<?php echo $job_id ?>\">return to job <?php echo $job_id ?><br/>

