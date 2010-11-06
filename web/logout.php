<?php
session_start();
session_destroy();
//you can change index.php with any url
header( 'Location: index.php' );
?>