	<script>
	$(function() {       
        $("button.switchbg").click(function() {
			$(".over").toggleClass("brender-overlay", 100);
			return false;
		});
		
		$(".prev_10").button({
            icons: {
                primary: "ui-icon-seek-start"
            },
            text: false
        });
        
        $(".prev").button({
            icons: {
                primary: "ui-icon-seek-prev"
            },
            text: false
        });
        
        $(".next").button({
            icons: {
                primary: "ui-icon-seek-next"
            },
            text: false
        });			
		
		$(".next_10").button({
            icons: {
                primary: "ui-icon-seek-end"
            },
            text: false
        });
        
        $(".view_image").button({
            icons: {
                primary: "ui-icon-newwin"
            }
        });
	});
	
	</script>
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
	<h2>// <strong>rendered by</strong> <?php print "<a href=\"index.php?view=view_client&client=$rendered_by\">$rendered_by</a> @ $finished_time "?></h2>
	<a href="index.php?view=view_job&id=<?php echo $job_id?>"><img src="<?php print $thumbnail_location ?>" class="image switchbg"></a><br/>
<div class="table-controls">
	<a class="btn" href="index.php?view=view_job&id=<?php echo $job_id ?>">return to job <?php echo $job_id ?></a>
	<a class="prev_10 btn" href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame-10?>">previous 10</a>
	<a class="prev btn" href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame-1?>">previous</a>
	<span class="current_frame"><?php print "frame <b>$frame</b>" ?></span>
	<a class="next btn" href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame+1?>">next</a>
	<a class="next_10 btn" href="index.php?view=view_image&job_id=<?php echo $job_id ?>&frame=<?php echo $frame+10?>">next 10</a>
	<button class="switchbg btn">dark background</button>
	<a class="view_image btn" href="<?php echo $thumbnail_location ?>" target="blank"><?php echo $file_name ?></a>
	
</div>
<div class="over"></div>
	
