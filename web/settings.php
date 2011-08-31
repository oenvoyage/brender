<script>
	$(function() {
		$( "#render_configs" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible.");
				}
			},
			selected: 0
		});
		
		
		$( " a.check_server_status")
			.button()
			.click(function() {
				$.get("ajax/settings.php", {check_server_status: "1"}, function(data) {
					var obj = jQuery.parseJSON(data);				
					if(obj.status == true) {
						//$("#dialog-form").dialog("close" );
						alert(obj.msg);
					} else {
						alert(obj.msg);
					}
				}, "Json");				
    			return false;});

				
		$("#theme_selector").change(function() {
			var theme_name = $("#theme_selector option:selected").val();
			window.location = 'index.php?view=settings&theme=' + theme_name;
		});
		
		//$(".header_row td:first").addClass("thead_l");
		//$(".header_row td:last").addClass("thead_r"); 
		
		var project_name = $('input#project_name'),
                                rem = $('input#rem'),
                                blend_mac = $('input#blend_mac'),
                                blend_linux = $('input#blend_linux'),
                                blend_win = $('input#blend_win'),
                                output_mac = $('input#output_mac'),
                                output_linux = $('input#output_linux'),
                                output_win = $('input#output_win');
                
                        
	    $("#new_project").dialog({
	            autoOpen: false,
				resizable: false,
				position: 'top',
	            height: 300,
	            width: 450,
	            modal: true,
	            buttons: {
	                    Cancel: function() {
	                            $(this).dialog("close");
	                    },
	                    "Create project": function() {                                                       
	                                    
	                                    $.post("ajax/new_project.php", {
	                                            project_name: project_name.val(), 
	                                            rem: rem.val(), 
	                                            blend_mac: blend_mac.val(), 
	                                            blend_linux: blend_linux.val(), 
	                                            blend_win: blend_win.val() ,
	                                            output_mac: output_mac.val(), 
	                                            output_linux: output_linux.val(), 
	                                            output_win: output_win.val() 
	                                    }, function(data) {
	                                            var obj = jQuery.parseJSON(data);
	                                            //alert(data);
	                                            if(obj.status == true) {
	                                                    $("#dialog-form").dialog("close" );
	                                                    //alert(obj.query);
	                                                    window.location= 'index.php?view=settings';
	                                            } else {
	                                                    alert(obj.msg);
	                                            }
	                                    }, "Json");                             
	                            return false;                                   
	                    }
	            },
	            close: function() {
	                    //allFields.val( "" ).removeClass( "ui-state-error" );
	            }
	    });
	    
	    $("#new_project_button")
	    .click(function() {
	            $( "#new_project" ).dialog( "open" );
	    });

	});
</script>

<h2>// <strong>server</strong> settings</h2>
<?php 
print $rrr+$rr+2;
if (isset($_GET['do_the_test'])) {
	print "<b>doing a test</b><br/>";
	print "go to <a href=\"index.php?view=test\">test page</a><br/><br/>";
	#output_shot_selector("gphg","03_animal_ballon");
	#$qq=get_scene_list_array("gphg");
	$qq=get_shot_list_array("gphg","02_katana");
	#print_r($qq);
	#javascript_selector();
	#print output_scene_list("gphg");
	print "<br/>-----<br/>";
	#print "<br/>";
	#print output_shot_list("gphg","01_bulles_champagne");
}

if (isset($_GET['debug'])) {
	if ($_SESSION['debug']) {
		print "INFO : debug mode OFF";
		$_SESSION['debug']=0;
	}
	else {
		print "INFO : debug mode ON";
		$_SESSION['debug']=1;
	}
}

?>

<p></p>
<div class="settings_container">
	<h3>Server</h3>
	<div class="item">	
		<a class="btn" href="index.php?view=settings&debug=1">switch debug <?php print $_SESSION['debug']?></a>
		<p>By switching to debug mode, it will be possible do view more details about brender queries and eventual errors.</p>
	</div>
	<div class="item">
		<a class="btn" href="index.php?view=settings&do_the_test=1">do a test</a> 
		<p>Run distribute rendering of a default file inside of the blend folder.</p>
	</div>
	<div class="item">
		<a class="check_server_status" href="">check server status</a> 
		<p>Will display an alert informing about the current server status.</p>
	</div>
	<div class="clear"></div>
	
	<h3>Interface</h3>
	<div class="item">	
		<select id="theme_selector">
			<option value="">select theme (<?php print $_SESSION['theme'] ?>)</option>
			<option value="brender">brender</option>
			<option value="brender_dark">brender dark</option>
			<option value="brender_mobile">brender mobile</option>
		</select>
	</div>
	<div class="clear"></div>
</div>

<?php
#print "sid = $sid <br/>";

if (isset($_GET['enable_sound'])) {
	$query="update server_settings set sound='yes'";
	mysql_unbuffered_query($query);
	print "sound enabled<br/>";
}
if (isset($_GET['disable_sound'])) {
	$query="update server_settings set sound='no'";
	mysql_unbuffered_query($query);
	print "sound disabled<br/>";
}
if (isset($_GET['order_by'])) {
        if ($_SESSION['orderby_projects']==$_GET['order_by']) {
                $_SESSION['orderby_projects']=$_GET['order_by']." desc";
        }   
        else {
                $_SESSION['orderby_projects']=$_GET['order_by'];
        }   
}

//system_status();


if (isset($_GET['debug'])) {
	if ($_SESSION['debug']) {
		print "<h2>// <strong>session</strong> settings</h2>";
		print_r($_SESSION);
	}
}

#------------------ system status -----------------
/*
function system_status() {
	$query="select server,status,pid,started,timediff(now(),started) as uptime,server_os,sound from server_settings;";
	$results=mysql_query($query);
	print "<table width=600>";
	print "<tr class=\"header_row\">
		<td><b> &nbsp; server &nbsp; </b></td>
		<td><b>status</b></td>
		<td><b>pid</b></td>
		<td><b>uptime</b></td>
		<td><b>machine os</b></td>
		<td><b>started</b></td>
		<td><b>sound</b></td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$server=$row->server;
		$status=$row->status;
		$started=$row->started;
		$uptime=$row->uptime;
		$sound=$row->sound;
		$server_os=$row->server_os;
		$pid=$row->pid;
		$bgcolor="#cccccc";
		$status_class=get_css_class($status);
		print "<tr>
			<td bgcolor=$bgcolor align=center>$server</td>
			<td class=$status_class >$status</td> 
			<td>$pid</td> 
			<td>$uptime</td> 
			<td>$server_os</td> 
			<td>$started</td> 
			<td>
				$sound<br/> 
				<a href=\"index.php?view=settings&enable_sound=1\">yes</a>
				<a href=\"index.php?view=settings&disable_sound=1\">no</a>
			</td>
		</tr>";
	}
	print "</table>";
}
*/

#   ----------------------------------------
#   ------------ PROJECTS ------------------
#   ----------------------------------------
print "<h2>// <b>projects</b></h2>";

if (isset($_GET['del'])) {
	$queryqq="delete from projects where id=$_GET[del];";
	mysql_query($queryqq);
	# sleep(1);
}
if (isset($_GET['deactivate'])) {
	$queryqq="update projects set status='inactive' where id=$_GET[deactivate];";
	mysql_query($queryqq);
}
if (isset($_GET['activate'])) {
	$queryqq="update projects set status='active' where id=$_GET[activate];";
	mysql_query($queryqq);
}
if (isset($_GET['def'])) {
	$def=$_GET['def'];
	if (check_project_is_active($def)) {
		$queryqq="update projects set def=0";
		mysql_query($queryqq);
		$queryqq="update projects set def=1 where id=$def;";
		mysql_query($queryqq);
		print "default project = $def";
		# sleep(1);
	}
	else {
		print "<span class=\"error\">to set a project as default, it must be active</span>";
	}
}
if (isset($_GET['new_project'])) {
	$new_project=clean_name($_GET['new_project']);
	if (check_project_exists($new_project)) {
		print "<span class=error>project $project already exists, please choose other name</span>";
	}
	else {
		print "new project $new_project created<br/>";
		$queryqq="insert into projects values ('','$new_project','$_GET[blend_mac]','$_GET[blend_linux]','$_GET[blend_win]','$_GET[output_mac]','$_GET[output_win]','$_GET[output_linux]','$_GET[rem]','active','');";
		mysql_query($queryqq);
	}
}
#if (!$orderby_projects=$_GET[orderby_projects]) {
#        $orderby_projects="id";
#}
  ?>

	<table>
	<tbody>
	<tr class="header_row">
		<td></td>
		<td> default </td>
		<td> <a href="index.php?view=settings&order_by=name">project</a></td>
		<td> .blend path</td>
		<td> output path</td>
		<td> server test</td>
		<td> <a href="index.php?view=settings&order_by=rem">rem</a></td>
		<td> <a href="index.php?view=settings&order_by=status">status</a></td>
		<td>&nbsp;</td>
	</tr>
	<?php 

	$server_os=get_server_settings("server_os");
	$query="select * from projects order by $_SESSION[orderby_projects]";
	$results=mysql_query($query);
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$name=$row->name;
		$rem=$row->rem;
		$blend_mac=shortify_string($row->blend_mac);
		$blend_win=shortify_string($row->blend_win);
		$blend_linux=shortify_string($row->blend_linux);
		$output_mac=shortify_string($row->output_mac);
		$output_win=shortify_string($row->output_win);
		$output_linux=shortify_string($row->output_linux);
		$status=get_css_class($row->status);
		if ($status=="active") {
			$status_link='<a href="index.php?view=settings&deactivate=' . $id.'">active</a>';
		}
		else {
			$status_link='<a href="index.php?view=settings&activate=' . $id.'">inactive</a>';
		}
		$def=$row->def;
		$bgcolor="ddddcc";
		if ($def==1) {
			#$is_default="<img src=\"images/icons/close.png\">";
			$default_button="yes";
		}
		else {
			$default_button="<a href=\"index.php?view=settings&def=$id\"><img src=\"images/icons/close.png\"></a>";
		}
		$test_path=get_path($name,"output",$server_os);
		$test_result= file_exists($test_path);
		print "<tr class=\"$status\">
			<td>$id</td> 
			<td>$default_button</td> 
			<td> <a href=\"index.php?view=view_project&project=$name\">$name</a></td>
			<td>mac: $blend_mac <br/>win: $blend_win<br/>linux: $blend_linux</td> 
			<td>mac: $output_mac <br/>win: $output_win <br/>linux: $output_linux</td> 
			<td>$test_result</td> 
			<td>$rem</td> 
			<td>$status_link</td> 
			<td>&nbsp;<a href=\"index.php?view=settings&del=$id\"><img src=\"images/icons/close.png\"></a></td>
		</tr>";
	}

?>

	</tbody>
</table>
<div class="table-controls">
	<a class="btn" id="new_project_button" class="button grey" href="#">new project</a>
</div>

<div id="new_project" title="// create new project">
	<div class="col_1">
 		<label for="project_name">project name</label>
 		<label for="rem">remarks</label>
 		<label for="blend_mac">blend files path on mac</label>
 		<label for="blend_linux">blend files path on linux</label>
 		<label for="blend_win">blend files path on windows</label>
 		<label for="output_mac">output path on mac</label>
 		<label for="output_linux">output path on linux</label>
 		<label for="output_win">output path on win</label>
 	</div>
	<div class="col_2">
		<input type="text" id="project_name" value="">
		<input type="text" id="rem" value="">
		<input type="text" id="blend_mac" value="../blend/">
		<input type="text" id="blend_linux" value="../blend/">
		<input type="text" id="blend_win" value="../blend">	
		<input type="text" id="output_mac" value="../render">
		<input type="text" id="output_linux" value="../render">
		<input type="text" id="output_win" value="../render">
	</div>
	<div class="clear"></div>
	<small>Please notice paths are relative to brender_root/web folder.<br/>You should use Absolute paths if possible</small>
</div>

<h2>// <strong>render</strong> configurations</h2>

<div id="render_configs">
        <ul>
                <?php    
                $list= `ls ../conf/`;
                $list=preg_split("/\n/",$list);
                foreach ($list as $item) {
                        $item=preg_replace("/\.py/","",$item);
                        if ($item <> "") {
                                print "<li><a href=\"ajax/render_configs.php?edit=$item\">$item</a></li> ";
                        }   
                }    
                ?>  
        </ul>
</div>
