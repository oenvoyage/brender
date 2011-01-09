<script>
                $(function() {
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
                                height: 400,
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
                                                                        window.location= 'index.php?view=projects';
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
<?php
if ($projectid=$_GET[del]) {
	$queryqq="delete from projects where id=$projectid;";
	mysql_query($queryqq);
	# sleep(1);
}
if ($deactivate=$_GET[deactivate]) {
	$queryqq="update projects set status='inactive' where id=$deactivate;";
	mysql_query($queryqq);
}
if ($activate=$_GET[activate]) {
	$queryqq="update projects set status='active' where id=$activate;";
	mysql_query($queryqq);
}
if ($def=$_GET[def]) {
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
if (isset($_GET[new_project])) {
	$new_project=clean_name($_GET[new_project]);
	if (check_project_exists($new_project)) {
		print "<span class=error>project $project already exists, please choose other name</span>";
	}
	else {
		print "new project $new_project created<br/>";
		$queryqq="insert into projects values ('','$new_project','$_GET[blend_mac]','$_GET[blend_linux]','$_GET[blend_win]','$_GET[output_mac]','$_GET[output_win]','$_GET[output_linux]','$_GET[rem]','active','');";
		mysql_query($queryqq);
	}
}
if (!$order_by=$_GET[order_by]) {
        $order_by="id";
}



#--------read---------
	$query="SELECT * FROM projects ORDER BY $order_by";
	$results=mysql_query($query);
	print "<h2>// <b>projects</b></h2>\n";
	print "<table>\n";
	print "<tbody>\n";
	print "<tr class=\"header_row\">
		<td></td>
		<td> default </td>
		<td> <a href=\"index.php?view=projects&order_by=name\">project</a></td>
		<td> .blend path</td>
		<td> output path</td>
		<td> <a href=\"index.php?view=projects&order_by=rem\">rem</a></td>
		<td> </td>
		<td>&nbsp;</td>
	</tr>";
	while ($row=mysql_fetch_object($results)){
		$id=$row->id;
		$name=$row->name;
		$rem=$row->rem;
		$blend_mac=$row->blend_mac;
		$blend_win=$row->blend_win;
		$blend_linux=$row->blend_linux;
		$output_mac=$row->output_mac;
		$output_win=$row->output_win;
		$output_linux=$row->output_linux;
		$status=get_css_class($row->status);
		if ($status=="active") {
			$status_link='<a href="index.php?view=projects&deactivate=' . $id.'">active</a>';
		}
		else {
			$status_link='<a href="index.php?view=projects&activate=' . $id.'">inactive</a>';
		}
		$def=$row->def;
		$bgcolor="ddddcc";
		if ($def==1) {
			$is_default="<img src=\"images/icons/close.png\">";
		}
		else {
			$is_default="";
		}
		print "<tr class=\"$status\">
			<td>$id</td> 
			<td>$is_default</td> 
			<td><a href=\"index.php?view=projects&def=$id\">$name</a></td> 
			<td>mac: $blend_mac <br/>win: $blend_win<br/>linux: $blend_linux</td> 
			<td>mac: $output_mac <br/>win: $output_win <br/>linux: $output_linux</td> 
			<td>$rem</td> 
			<td>$status_link</td> 
			<td>&nbsp;<a href=\"index.php?view=projects&del=$id\"><img src=\"images/icons/close.png\"></a></td>
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
		<input type="text" id="project_name" value="my_project_name">
		<input type="text" id="rem" value="">
		<input type="text" id="blend_mac" value="blend/">
		<input type="text" id="blend_linux" value="blend/">
		<input type="text" id="blend_win" value="\\blend">	
		<input type="text" id="output_mac" value="render">
		<input type="text" id="output_linux" value="render">
		<input type="text" id="output_win" value="\\render">
	</div>
	<div class="clear"></div>
</div>
