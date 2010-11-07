<?php
function check_login($user,$pass) {
         if ($user==$pass) {
                 return 1;
         }
         else {
                 return 0;
         }
}
function init_user($user) {
	$_SESSION['user']=$user;
	$_SESSION['theme']="brender";
	return 1;
}
function show_login_form() {
		
?>
		<form action='index.php?view=login' method='post'> 
			<fieldset>
				<div class="line">
					<label for="user">Username</label>
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
