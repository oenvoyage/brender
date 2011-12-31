<?php
function check_login($user,$pass) {
	if ($user==$pass) {
		return 1;
	} else {
		return 0;
	}
}

function init_user($user) {
	$_SESSION['user']=$user;
	$_SESSION['theme']="brender";
	$_SESSION['orderby_client']="client";
	$_SESSION['orderby_jobs']="shot";
	$_SESSION['orderby_projects']="id";
	$_SESSION['last_used_config']="";
	$_SESSION['debug']= false;
	return 1;
}

function output_refresh_button() {
	print "<a href=\"index.php?view=".$GLOBALS['view']."\"><img src=\"images/icons/reload.png\"></a>";
}
function display_dead_server_warning() {
	if(get_server_settings("status")<>"running") {
		print "<span class=\"alert\"> that currently looks dead</span> ";
	}
}

function show_login_form() {
?>
		<form action='index.php?view=login' method='post'> 
			<fieldset>
				<div class="line">
					<label for="user">Usernamee</label>
		            <input type='text' name='user' value='username' />
				</div>
				<div class="line">
					<label for="password">Password</label>
					<input type='password' name='password' value="username" />
				</div>
				<div class="line">
					<input type='hidden' name='do_login' value='true' />
					<input class="submit" type='submit' value='login' />
				</div>
			</fieldset>
		</form>
<?php
}
?>
