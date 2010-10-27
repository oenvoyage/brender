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
	<form action='login.php' method='post'>
    
        <table id="loginform" cellspacing="0" cellpadding="0" border="0">    
                <tr>
                        <td class="label">Username:</td> 
                        <td><input type='text' name='user' value='root' /></td>
                </tr>
                <tr>
                        <td class="label">Password:</td> 
                        <td><input type='password' name='password' /></td>
                </tr>
                <!-- <tr><td></td><td class="note">* password not masked</td></tr> -->
                <tr>
                        <td colspan="2"><hr /></td>
                </tr>
                <tr>
                        <td style="padding-top:10px; text-align:center;">
                                <input type='hidden' name='do_login' value='true'>
                                <input class="submit" type='submit' value='login'/>
                        </td>
                </tr>
        </table>
    
    
        </form>
<?php
}
?>
