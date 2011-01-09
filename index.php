<?php 
	if (file_exists("connect.php")) {
		echo '<meta http-equiv="Refresh" content="0;URL=web/">';
	}
	else {
		echo '<meta http-equiv="Refresh" content="0;URL=install/">';
	}
?>

