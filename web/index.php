<!--  /**
*
* ***** BEGIN GPL LICENSE BLOCK *****
* 
* This file is part of Brender.
* Brender is free software: you can redistribute it and/or 
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
*
* --!>
<?php

	session_start();
	if(isset($_GET['view'])) {
		$view = $_GET['view'];
	}
	if(isset($_POST['view'])) {
		$view = $_POST['view'];
	}
	
	if(isset($_SESSION['user'])) {
		$session_user = $_SESSION['user'];
	}
	
	require_once('tpl/header.php');

	require_once('tpl/menu.php');
	
	
?>
	<div id="section">
	<?php
		if (!$_SESSION['user']) {
				//print('<div id="dialog">');
				include "login.php";
				//print('</div>');					
		} else {
	 
			if($view == "") { 
				include "overview.php";
			}
			
			if($view == "login") { 
				include "login.php";
			}
			
			if($view == "clients") { 
				include "clients.php";
			}
			
			if($view == "jobs") { 
				include "jobs.php";
			}
			
			if($view == "orders") { 
				include "orders.php";
			}
			
			
			if($view == "settings") { 
				include "settings.php";
			}

			if($view == "test") { 
				include "test.php";
			}
			
			if($view == "status") { 
				include "status.php";
			}
	
			if($view == "logs") { 
				include "logs.php";
			}
			
			if($view == "projects") {
				include "projects.php";
			}

			if($view == "render_configs") {
				include "render_configs.php";
			}
			
			
			if($view == "view_job") {
				include "view_job.php";
			}			

			if($view == "view_client") {
				include "view_client.php";
			}			
	
			if($view == "view_image") {
				include "view_image.php";
			}			
	
		}
	?>
	
	<?php include "new_job.php"; ?>	
	</div>
</div>
<?php
	include "tpl/footer.php"
?>
