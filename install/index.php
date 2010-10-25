<html>
	<head>
	<style type="text/css" media="all">

		body { 
			margin:0; 
			padding:0; 
			font-size:15px; 
			font-family:Helvetica, Arial, sans-serif;
		}
		p { 
			margin:0; 
			padding:0;
		}
		h1 { 
			color:white; 
			font-size:24pt; 
			margin:60px 0; 
		}
		h2 { 
			color:#606060; 
			font-size:12pt; 
			margin:0;
		}
		hr {
			border:0; 
			height:1px; 
			margin:20px 20px; 
			background:#CCC;
		}
		a {
			color:#555; 
			font-weight:bold; 
			text-decoration:none;
		}
		a:hover {
			text-decoration: underline;
		}
		#container { 
			width: 450px; 
			position: relative; 
			margin: 0 auto;
			text-align: justify;
			background: url("../web/images/inst-body-grad.png") repeat-x scroll left top white;
		}

		#header { 
			height:121px; 
			margin-bottom:20px;
		}
		.blurb { 
			color:#606060; 
			margin-top:5px; 
			font-size:11pt; 
			line-height:16pt;
			padding: 10px;
		}
		#body { 
			margin-top:15px; 
			width:100%; 
			padding-top:12px; 
			padding-bottom: 1px;
		}
		#body ul { 
			margin:0 0 0 10px; 
			padding:10px; 
		}
		#body li { 
			padding:3px 0; 
			font-size:14pt; 
			color:#707b65; 
		}
		.left {float:left; width:320px; padding:0 5px 5px; margin-left:10px; }
		.right { float:right; width:254px; }
		#footer { float:left; width:100%; margin-top:100px; text-align:center;}
		#footer p {color:#bbb; }

		td {font-size:9pt;  color:#333;}
		td.note {
			color:#999;
			padding-bottom:15px;
			padding-left:15px;
		}
		td h2 {
			
			margin-top:20px;
		}
		.label {
			text-align:right;
			vertical-align:top;
			padding-top:3px;
			 
		}
		
		input {
			font-family:helvetica, arial, sans-serif;
			font-size:10pt;
			padding:3px;
			background:white;
			margin:0 10px 5px;
			border-left:1px solid #83a5c7; 
			border-top:1px solid #83a5c7; 
			border-bottom:1px solid #d3e1ee;  
			border-right:1px solid #d3e1ee; 
		}
		
		.radio{
			margin:4px 4px 0 0px;
			padding:2px;
		}
		.warning {
			border:1px solid red;
			background: #fff6f6;
			padding:10px;
			margin:0 0 15px 0;
		}
		#installform { 
			margin:0 auto;
			width:450px;
			 }
		#installform td img {margin-bottom:5px; }
		.submit {
			border:auto;
			float: left;
			margin: 0 0 0 20px;
		}
	</style>
	
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
        
    $database = mysql_connect($_POST['host'],$_POST['brenderUser'],$_POST['brenderPassword']) || die("Bad database information, press back and try again");
    echo "Connecting to database: ";
    /*
    echo "Deleting relay database...<br/>";
    mysql_query("drop database relay");
    
    echo "Creating database relay...";
    
    mysql_query("create database relay");
    */
    
    mysql_select_db($_POST['database'])||die("could not connect to the database $_POST[database]"); echo "OK<br/>";
    
    echo "Dropping tables if exist: ";
    
    mysql_query("DROP TABLE `$_POST[pre]clients`, `$_POST[pre]jobs`, `$_POST[pre]orders`, `$_POST[pre]projects`, `$_POST[pre]scenes`, `$_POST[pre]status`");
    
    echo "OK<br />";   
    
    echo "Creating tables<br/><ul><li>$_POST[pre]clients</li>";
    
    mysql_query("
	    CREATE TABLE IF NOT EXISTS `$_POST[pre]clients` 
		(
		  `id` int(11) NOT NULL auto_increment,
		  `client` varchar(32) NOT NULL,
		  `speed` tinyint(4) NOT NULL,
		  `machinetype` varchar(24) NOT NULL default 'node',
		  `client_priority` tinyint(4) NOT NULL,
		  `status` varchar(128) NOT NULL default 'not running',
		  `rem` varchar(255) NOT NULL,
		  PRIMARY KEY  (`id`)
		)
		ENGINE=MyISAM COMMENT='les clients' AUTO_INCREMENT=11 ;
	") || die(mysql_error() . " could not create the table filesystem");
	
    
    echo "<li>$_POST[pre]jobs</li>";
    
	mysql_query("
		CREATE TABLE `$_POST[pre]jobs` 
		(
		  	`id` int(11) NOT NULL auto_increment,
		  	`name` varchar(32) NOT NULL,
		  	`jobtype` varchar(32) NOT NULL,
		  	`file` varchar(64) NOT NULL,
		 	`start` int(11) NOT NULL default '1',
		  	`end` int(11) NOT NULL default '100',
		  	`project` varchar(32) NOT NULL,
		  	`output` varchar(64) NOT NULL,
		  	`current` int(11) NOT NULL default '0',
		  	`chunks` tinyint(4) NOT NULL default '0',
		  	`rem` varchar(255) NOT NULL,
		  	`filetype` varchar(12) NOT NULL,
		  	`config` varchar(64) NOT NULL,
		  	`status` varchar(65) NOT NULL,
		  	`priority` smallint(6) NOT NULL default '0',
		  	`lastseen` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
		  	PRIMARY KEY  (`id`)
		)
		ENGINE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=98;	
	")||die(mysql_error() . " could not create the table jobs");

	echo "<li>$_POST[pre]orders</li>";
	
	mysql_query("
		CREATE TABLE `$_POST[pre]orders` 
		(
		  	`id` int(11) NOT NULL auto_increment,
		  	`client` varchar(32) NOT NULL,
		  	`orders` varchar(64) NOT NULL,
		  	`priority` smallint(6) NOT NULL,
		  	`rem` varchar(255) NOT NULL,
		  	PRIMARY KEY  (`id`)
		) 
		ENGINE=MyISAM AUTO_INCREMENT=31156 ;
	")||die(mysql_error() . " could not create the table orders");

   echo "<li>$_POST[pre]projects</li>";
   
   mysql_query("  
		CREATE TABLE `$_POST[pre]projects` 
		(
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(64) NOT NULL,
			`mac_path` varchar(128) NOT NULL,
			`win_path` varchar(128) NOT NULL,
			`rem` varchar(255) NOT NULL,
			`status` varchar(24) NOT NULL default 'active',
			`def` smallint(6) NOT NULL,
			PRIMARY KEY  (`id`)
		) 
		ENGINE=MyISAM AUTO_INCREMENT=19 ;
	")||die(mysql_error() . " could not create the table projects");
    
    echo "<li>$_POST[pre]scenes</li>";
    
	mysql_query("		
		CREATE TABLE `$_POST[pre]scenes` 
		(
			`id` int(11) NOT NULL auto_increment,
		  	`project` varchar(24) default NULL,
		  	`scene` varchar(24) default NULL,
		  	PRIMARY KEY  (`id`)
		) 
		ENGINE=MyISAM AUTO_INCREMENT=1 ;
	")||die(mysql_error() . " could not create the table scenes");
	
	echo "<li>$_POST[pre]status</li></ul>";
			
	mysql_query("		
		CREATE TABLE `$_POST[pre]status` 
		(
			`server` varchar(32) NOT NULL,
			`status` varchar(32) NOT NULL,
			`pid` int(11) NOT NULL,
			`started` timestamp NULL default CURRENT_TIMESTAMP,
			`sound` varchar(12) NOT NULL,
			`last_rendered` varchar(128) NOT NULL,
			`rem` varchar(255) NOT NULL
		) 
		ENGINE=MyISAM;
	")||die(mysql_error() . " could not create the table status");
	    
    
    echo "Done creating tables<br/>";
    
    echo "Filling up tables: ";	
    
	mysql_query("
		INSERT INTO `$_POST[pre]clients`
		VALUES (1, 'macbook', 1, 'node', 0, 'not running', 'client not responding (PING)');
	") || die(mysql_error());


    echo "OK<br/>";
    

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

<p class="blurb">This script will create <strong>conf.inc.php</strong> which is brender's config file. It will also configure your database, creating the tables required by the application. Be sure to provide an existing database name.</p>

	<div id="body">
	<form action='index.php' method='post'>
	
	<table id="installform" cellspacing="0" cellpadding="0" border="0">	
		<tr>
			<td class="label" width="200">Database Host:</td>
			<td><input type='text' name='host' value='localhost' /></td>
		</tr>
		<tr>
			<td class="label">Database Name:</td> 
			<td><input type='text' name='database' value='brender' /></td>
		</tr>
		<tr>
			<td class="label">Table Prefix:</td> 
			<td><input type='text' name='pre' value='' /></td>
		</tr>
		<tr>
			<td class="label">Database Username:</td> 
			<td><input type='text' name='brenderUser' value='root' /></td>
		</tr>
		<tr>
			<td class="label">Database Password:</td> 
			<td><input type='password' name='brenderPassword' /></td>
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
				<input type='hidden' name='stage2' value='true'>
				<input class="submit" type='submit' />
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