<?php
if(isset($_GET['log'])) {
		$log = $_GET['log'];
	}
	
if(isset($_GET['max'])) {
		$_max = $_GET['max'];
	}
?>

<script>
		$('.more').click(function(){
			$.get('ajax/logs.php?log=<?php echo $log; ?>&max=400', function(data) {
			  $('.result').html(data);
			  //alert('Load was performed.');
			  $('.more').hide();
			});
		});
		
		$('.less').click(function(){
			$.get('ajax/logs.php?log=<?php echo $log; ?>&max=100', function(data) {
			  $('.result').html(data);
			  //alert('Load was performed.');
			  $('.less').hide();		  
			});
		});
		
</script>

<?php
if ($log=$_GET[log]){ 
	if ($_GET[max]) {
		$_max=$_GET[max];
		$text_note = "<p class=\"less\">show less content...</p><br/>";	
	}
	else {
		$_max=100;	
		$text_note = "<p class=\"more\">more...</p><br/>";
	}
	?> <div class="result"><?php
	//print "<b>$log log</b><br/>";
	//print "<a href=\"index.php?view=logs&log=$log&max=400\">400 lines</a><br/>";	
	$lok = file("../../logs/$log.log");
	$lok = array_reverse($lok);
	foreach ($lok as $line){
		if ($a++>$_max ) {
			break;
		}
		print "$line<br/>";
	}
	print $text_note;
	?> </div><?php	
}
?>
