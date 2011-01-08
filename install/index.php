<html>
	<head>
	
	<link rel="stylesheet" href="install.css">	
	</head>
	<body>
	<div id="header"></div>
<div id="container">

<?php

    
	if(isset($_SERVER['ORIG_PATH_TRANSLATED'])){
	    $rootpath =$_SERVER['ORIG_PATH_TRANSLATED'];
	}else if(isset($_SERVER['PATH_TRANSLATED'])){
	    $rootpath = $_SERVER['PATH_TRANSLATED'];
	}else{
	    $rootpath = $_SERVER['SCRIPT_FILENAME'];
	}
	
	if(substr($rootpath,0,1) == '/'){
		$path = explode("/","$rootpath"); // "/"
	}else if(substr($rootpath,2,1) == "\\"){
		$path = explode("\\","$rootpath"); // "\"
	}else if(substr($rootpath,2,2) == "\\\\"){
		$path = explode("\\\\","$rootpath"); // "\\"
	}else{
		$path = explode("/","$rootpath");
	}	
	
	$rootpath = '';
	for($i=0;$i<count($path)-2;$i++){
	    $rootpath .= "$path[$i]/";
	}
	
	if(substr($rootpath,-1,1) == "/"){
	    $rootpath = substr($rootpath,0,strlen($rootpath)-1);
	}
	
	//print($rootpath);
	
	if($rootpath == ''){ ?>
		<p class='warning'><strong>Warning</strong><br />
	    Your webservers rootpath could not be determined.</p>
	    <pre><?php print_r($_SERVER); ?></pre>
		<?php
		exit;
	}
    
    if(file_exists("$rootpath/conf.inc.php")){?>
		<p class='warning'><strong>Warning</strong><br />
    	Configuration file already detected. Delete conf.inc.php in brender's root directory before running this script again.</p>
		<?php
		exit;
	}

    if(isset($_POST['stage2'])){
    
    echo "<div class=\"blurb\">";
        
    
	// Name of the file
	$filename = 'brender.sql';
	// MySQL host (needs to be localhost - later to be changed according to MAMP)
	$mysql_host = $_POST[host].':8889';
	// MySQL username
	$mysql_username = $_POST[brenderUser];
	// MySQL password
	$mysql_password = $_POST[brenderPassword];
	// Database name
	$mysql_database = 'installer';
	// Host OS
	$mysql_host_os = $_POST[host_os];
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	// Connect to MySQL server
	mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());
	// Select database
	mysql_select_db($mysql_database) or die('Error selecting MySQL database: ' . mysql_error());
	
	// Temporary variable, used to store current query
	$templine = '';
	// Read in entire file
	$lines = file($filename);
	// Loop through each line
	foreach ($lines as $line_num => $line) {
		// Only continue if it's not a comment
		if (substr($line, 0, 2) != '--' && $line != '') {
			// Add this line to the current segment
			$templine .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';') {
				// Perform the query
				mysql_query($templine) or print('Error performing query \'<b>' . $templine . '</b>\': ' . mysql_error() . '<br /><br />');
				// Reset temp variable to empty
				$templine = '';
			}
		}
	}
	
	
	// Connect to MySQL server
	//mysql_connect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());
	// Select database
	//mysql_select_db($mysql_database) or die('Error selecting MySQL database: ' . mysql_error());
	$set_server_os = '"INSERT INTO server_settings (server_os) VALUES (\''.$mysql_host_os.'\')"';
	//$set_server_os = '"INSERT INTO server_settings VALUES (\'server\', \'not started \', 0, \'1972-01-07 22:39:49\', \'no\', \'test\', \'\')"';
	echo($set_server_os);
	//INSERT INTO `server_settings` VALUES('server', 'not started ', 0, '1972-01-07 22:39:49', 'no', 'linux', '');
	mysql_query($set_server_os) or print('Error performing query') . mysql_error();
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	
    echo "<br/>OK<br/>";
    

	if(is_file($_POST['con'])){
	    $imageTypes .= "|image\/x-photoshop|image\/";
	    $imageProcessors .= "    \$convertpath = \"$_POST[con]\"; // imageMagick convert path";
	} else {
	    echo "ImageMagik Convert NOT enabled<br/>";
	}


	$conffile = "<?
	    \$database = mysql_connect('$_POST[host]','$_POST[brenderUser]','$_POST[brenderPassword]') or die(\"Database error check conf.inc.php\");
	    mysql_select_db('$_POST[database]', \$database);
	    \$GLOBALS['tablePrefix'] = \"$_POST[pre]\";  
	
	    // paths
	    \$rootPath    = \"$rootpath\";
	
	?>
	";
    
    if(!function_exists("file_put_contents")){
	
		function file_put_contents($file,$data){
		    $f = fopen($file,"w+") or die("$file can not open");
		    fwrite($f,$data);
		    fclose($f);
		}
    }
    
    echo "Generating config file: if creation fails make sure the webserver has permission to write to $rootpath";
    
    file_put_contents("$rootpath/conf.inc.php",$conffile);
    
    echo "<br />Config file creation: OK<br /><hr>";
    
    echo "Setup completed successfully, you can now go to <a href=\"../web/\">brender</a>!";
    
    /*
    if(isset($_SERVER['PATH_INFO'])){$uploadPath = $_SERVER['PATH_INFO'];}
    if(isset($_SERVER['REQUEST_URI'])){$uploadPath = $_SERVER['REQUEST_URI'];}
    $uploadPath = str_replace("install/index.php","upload.pl?test",$uploadPath);
	$uploadPath = "http://$_SERVER[SERVER_NAME]$uploadPath";
    echo $uploadPath . " ...";
    */
    
    echo "</div>";
    
    } else {
?>

<?php
error_reporting(0);
?>
<img alt="brender" src="../web/images/brender_logo.png" />
<p class="blurb">Welcome to the b<strong>render</strong> 0.5 installer. This script will create <strong>conf.inc.php</strong> which is brender's config file. It will also configure your database, creating the tables required by the application. Be sure to provide an existing database name.</p>

	<div id="body">
	<form action='index.php' method='post'>
	
	<table id="installform" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="label" width="200">Host OS:</td>
			<td>
				<select name="host_os">
					<option value="mac">mac</option>
					<option value="linux">win</option>
					<option value="windows">lin</option>
				</select>
			</td>
		</tr>		
		<tr>
			<td class="label" width="200">Database Host:</td>
			<td><input type="text" name="host" value="localhost" /></td>
		</tr>
		<tr>
			<td class="label">Database Name:</td> 
			<td><input type="text" name="database" value="installer" /></td>
		</tr>
		<tr>
			<td class="label">Database Username:</td> 
			<td><input type="text" name="brenderUser" value="root" /></td>
		</tr>
		<tr>
			<td class="label">Database Password:</td> 
			<td><input type="password" name="brenderPassword" value="" /></td>
		</tr>
		<!-- <tr><td></td><td class="note">* password not masked</td></tr> -->
		<tr>
			<td class="label"></td>
		</tr>
		<tr>
			<td colspan="2"><hr /></td>
		</tr>
		<tr>
			<td style="padding-top:20px; text-align:center;">
				<input type="hidden" name="stage2" value="true">
				<input class="submit" type="submit" />
			</td>
		</tr>
	</table>
	
	
	</form>
	</div>

</div>


</body>
</html>

<?php
    }
?>