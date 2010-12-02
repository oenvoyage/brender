<?php
	//$username = (!empty($_POST['username']))?trim($_POST['username']):false;
	//$password = (!empty($_POST['password']))?trim($_POST['password']):false;
	//$do_login = (!empty($_POST['do_login']))?trim($_POST['do_login']):false;	
	if ($_POST['do_login']) {
		if (check_login($_POST['user'],$_POST['password'])) {
			if (init_user($_POST['user'])) {
				#header( 'Location: index.php' );
				check_server_status();
				print "click here to continue to <a href=\"index.php\">brender</a>";
			}
			else {
				print "something went wrong with initializing user... try again";
			}
		}
		else {
			print "<font color=red>error with login... please retry</font>";
			show_login_form();
		}
		
	}
	else {
	
		?>
		
		<script>
		$(function() {
			var username = $('#username').val(),
				password = $( "#password" ).val(),
				do_login = $( "#do_login" ).val();		
			
			$( "#dialog-form" ).dialog({
				autoOpen: true,
				height: 300,
				width: 350,
				modal: true,
				buttons: {
					"Login": function() { 
							//$.post('index.php?view=login', { username: username, password: password, do_login: do_login });
							//var debug = username.password; 
							alert (username);
							//$( this ).dialog( "close" );
							$.post("index.php?view=login", { username: "username", password: "username", do_login: "true" } );
							
							/*var dataString = 'username='+ username + '&do_login=' + do_login + '&password=' + password;  
							  $.ajax({
							    type: "POST",
							    url: "index.php?view=login",
							    data: dataString,
							    success: function() {
							      $('#contact_form').html("<div id='message'></div>");
							      $('#message').html("<h2>Contact Form Submitted!</h2>")
							      .append("<p>We will be in touch soon.</p>")
							      .hide()
							      .fadeIn(1500, function() {
							        $('#message').append("<img id='checkmark' src='images/check.png' />");
							      });
							    }
							  });
							  return false;*/
						
					}
				},
				close: function() {
					//allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});
	
		});
		</script>

		<div id="dialog-form" title="// please login">	

		<form action='index.php?view=login' method='post'> 
			<fieldset>
				<div class="line">
					<label for="user">Username</label>
		            <input id="username" type='text' name="username" value="username" />
				</div>
				<div class="line">
					<label for="password">Password</label>
					<input id="password" type="password" name="password" value="username" />
				</div>
				<div class="line">
					<input id="do_login" type="hidden" name="do_login" value="true" />
					<input class="submit" type="submit" value="login" />
				</div>
			</fieldset>
		</form>
		
		<?php
	
		//show_login_form();
	}

?>
</div>
