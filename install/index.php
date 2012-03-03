<?php
/**
* Copyright (C) 2007-2011 Olivier Amrein
* Author Olivier Amrein <olivier@brender-farm.org> 2007-2011
*
* ***** BEGIN GPL LICENSE BLOCK *****
* This file is part of Brender.
** Brender is free software: you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation, either version 2 * of the License, or any later version.
* 
* Brender is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License 
* along with brender.  If not, see <http://www.gnu.org/licenses/>.
*
* ***** BEGIN GPL LICENSE BLOCK *****
*/
?>
<html>
	<head>
	
	<link rel="stylesheet" href="install.css">	
	</head>
	<body>
	<div id="header"></div>
<div id="container">
<img alt="brender" src="../web/images/brender_install_logo.png" />
<?php

 	
 	// Obtain installer's path
 	   
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
	
	
	if($rootpath == ''){ ?>
		<p class="warning"><strong>Warning</strong><br />
	    Your webservers rootpath could not be determined.</p>
	    <pre><?php print_r($_SERVER); ?></pre>
		<?php
		exit;
	}
    
    /*if(file_exists("$rootpath/connect.php")){?>
		<p class="warning"><strong>Warning</strong><br />
    	An already existing configuration file has been detected. Delete <i>connect.php</i> in brender's root directory before running this script again.</p>
		<?php
		exit;
	}*/

    if(isset($_POST['stage2'])){
    
    echo "<div class=\"blurb\">";
        
    
	// Name of the file
	$filename = 'brender.sql';
	// MySQL host (needs to be localhost - later to be changed according to MAMP)
	$mysql_host = $_POST['host'].':'.$_POST['port'];
	// MySQL username
	$mysql_username = $_POST['brenderUser'];
	// MySQL password
	$mysql_password = $_POST['brenderPassword'];
	// Database name
	$mysql_database = 'brender';
	// Host OS
	$mysql_host_os = $_POST['host_os'];
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	// Connect to MySQL server
	mysql_connect($mysql_host, $mysql_username, $mysql_password) or display_error_and_die('Error connecting to database: ' . mysql_error());
	// Select database
	#mysql_select_db($mysql_database) or display_error_and_die('Error selecting MySQL database: ' . mysql_error());
	
	
	// Optionally drop prvious tables
	if (isset($_POST['database_overwrite'])) {
		$query="DROP TABLE `clients`, `jobs`, `orders`, `projects`, `rendered_frames`, `server_settings`";
		mysql_query($query) or print(mysql_error());
		echo("Dropping previous tables called `clients`, `jobs`, `orders`, `projects`, `rendered_frames`, `server_settings`<br/>");
	}
	
	
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
	
	// Add a server_settings line with host operating system (to generate proper paths when creating projects)
	mysql_query("DELETE FROM server_settings") or display_error_and_die("Error performing server_settings delete query : " . mysql_error());

	$set_server_os="INSERT INTO `server_settings` (server,status,pid,started,sound,server_os) VALUES('server', 'not started ', 0, '1972-01-07 22:39:49', 'no', '$mysql_host_os')";
	mysql_query($set_server_os) or display_error_and_die("Error performing query : " . mysql_error());
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	
	
    echo "OK<br/>";
    

	if(is_file($_POST['con'])){
	    $imageTypes .= "|image\/x-photoshop|image\/";
	    $imageProcessors .= "    \$convertpath = \"$_POST[con]\"; // imageMagick convert path";
	} else {
	    echo "ImageMagik Convert NOT enabled<br/>";
	}


	$conffile = "<?php
#this is the configuration file for server and database connection generated by the installer
\$my_server=\"$_POST[host]:$_POST[port]\";
\$my_user=\"$_POST[brenderUser]\";
\$my_password=\"$_POST[brenderPassword]\";
#print \"connect.php info :: ---- \$my_server,\$my_user,\$my_password ----\";
\$link=mysql_connect(\$my_server,\$my_user,\$my_password) or die(\"Fatal error: unable to connect to mysql server\");
@mysql_select_db(\"brender\");
#print \"connected to server \$my_server : \$my_user\";

// paths
\$rootPath= \"$rootpath\";
?>";
    
    if(!function_exists("file_put_contents")){
	
		function file_put_contents($file,$data){
		    $f = fopen($file,"w+") or die("$file can not open");
		    fwrite($f,$data);
		    fclose($f);
		}
    }
    
    
    file_put_contents("$rootpath/connect.php",$conffile);
    file_put_contents("$rootpath/web/tpl/connect.php",$conffile);
    
    if (file_exists("$rootpath/connect.php")) {
	$log_message=date('Y/d/m H:i:s')." brender_installer : installation successful";
	file_put_contents("$rootpath/logs/brender.log",$log_message);
    	echo "<br />Config file creation: OK<br /><hr>";
    	echo "Setup completed successfully, you can now go to <a href=\"../web/\">brender</a>!";
    }
    else {
	echo "<p class=\"warning\"><strong>Warning</strong><br />";
    	echo "<br />Config file creation: seems to have FAILED somewhere <br/>";
    	echo "Generating config file: if creation fails make sure the webserver has permission to write to $rootpath<br/><br/>";
    	echo "Setup unsuccessful, please go back to <a href=\"javascript:history.go(-1)\">brender install</a>";
	echo "</p>";
	
    }
    
    
    
    if(isset($_SERVER['PATH_INFO'])){$uploadPath = $_SERVER['PATH_INFO'];}
    if(isset($_SERVER['REQUEST_URI'])){$uploadPath = $_SERVER['REQUEST_URI'];}
    $uploadPath = str_replace("install/index.php","upload.pl?test",$uploadPath);
	$uploadPath = "http://$_SERVER[SERVER_NAME]$uploadPath";
    // echo $uploadPath . " ...";
    
    
    echo "</div>";
    
    } else {
?>

<?php
error_reporting(0);
?>

<p class="blurb">Welcome to the b<strong>render</strong> 0.5 installer. This script will create <strong>connect.php</strong> which is brender's config file. It will also configure your database, creating the tables required by the application. Be sure to provide an existing database name.</p>

	<div id="body">
	<form action='index.php' method='post'>
	
	<table id="installform" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="label" width="250">Host OS:</td>
			<td>
				<select name="host_os">
					<option value="mac">Mac OS</option>
					<option value="windows">Windows</option>
					<option value="linux">Linux</option>
				</select>
			</td>
		</tr>		
		<tr>
			<td class="label" width="200">Database Host <br />(write the IP):</td>
			<td><input type="text" name="host" value="localhost" /></td>
			<td class="label" width="100">Port</td>
			<td><input type="text" name="port" value="3306" size="6"/></td>
		</tr>
		<tr>
			<td class="label">Overwrite existing brender DB:</td> 
			<td><input type="checkbox" name="database_overwrite" value="true" /></td>
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
				<input class="submit" type="submit" value="Install brender!" />
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
# //  function to display error message when setup failed
function display_error_and_die($error_msg) {
	echo "<p class=\"warning\"><strong>Warning</strong><br />";
	echo "<br/>$error_msg<br/>";
	echo "Setup unsuccessful, please go back to <a href=\"javascript:history.go(-1)\">brender install</a>";
	echo "</p>";
	die();
}
?>
