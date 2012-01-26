<?php
#--------read---------
	$id = $_GET['id'];
	$query = "SELECT * FROM jobs WHERE id='$id'";
	$results = mysql_query($query);
	$row = mysql_fetch_object($results);
		$project = $row->project;
		$id = $row->id;
		$scene = $row->scene;
		$shot = $row->shot;
		$start = $row->start;
		$end = $row->end;
		$total = $end-$start;
       		$filename = basename($shot);
	#-------------------
	print "<h2>preview animation $id : $scene/<b>$shot</b> </h2>";	
		#print " <p class=\"$status\">";
	?>

	<script type="text/javascript" src="js/stopmotion-lite.js"></script>
	<script type="text/javascript">
		speed=50;
                function test2(){
			test("response ");
			speed = 40;
		}
                $(function(){
                        stopmotion('#slideImages',speed, '1');
                });
        </script>

	<div class="table-controls">
		<a class="btn" href="index.php?view=view_job&id=<?php echo $id ?>">back to job</a>
	</div>

	<div id='slides'>
                <div id="loading">
                        <img src="images/ajax-loader.gif" />
                </div>
		
		<div id="slideImages">

			<?php #-------------------------------all images ------------------------------
			$a = $start;
			while ($a++ < ($total+$start)){
	        		$image_name = $filename.str_pad($a,4,0,STR_PAD_LEFT).".png";
    	   		 	$image = "/thumbnails/$project/$scene/$shot/$image_name";
				#print "<img src=\"$image\"><br/>";
				print "<img id=\"photo\" src=\"$image\" />";
			}
			?>
			</a>
		</div>
	</div>
